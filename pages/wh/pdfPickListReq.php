<?php
// Load db connection
include_once '../../include/conn.php';
// Assets class (image, css, etc..)
class Config{

}
class Assets{
    // Logo path
    public static $logo = '../../include/img-01.png';
}
class Model{
    private $conn;
    private $result;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Query helper
     * @param $sql
     * @return $this
     */
    public function query($sql)
    {
        $this->result = mysqli_query($this->conn, $sql);
        if(!$this->result){
            $err = "
            <h3>Database Query Error:</h3>
            <p>".mysqli_error($this->conn)."</p>
            <p>$sql</p>
            ";
            die($err);
        }
        return $this;
    }

    /**
     * Fetch multiple rows helper
     * @return array
     */
    public function result()
    {
        $rows = array();
        if(!$this->result){
            return $rows;
        }
        while($row = mysqli_fetch_object($this->result)){
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * Fetch single row helper
     * @return null|object
     */
    public function row()
    {
        return mysqli_fetch_object($this->result);
    }

    public function get_master_company()
    {
        return $this->query("
            SELECT * FROM mastercompany;
        ")->row();
    }

    public function get_total_req($id)
    {
        return $this->query("
            SELECT sum(qty) total_req FROM bppb_req where bppbno = '$id';
        ")->row();
    }



    /**
     * Get report data and transform into report friendly format
     * @param $bppbno int
     * @return array
     */
    public function get_report($bppbno)
    {

        // TODO: assign real column name
        $sql = "
            SELECT 
        b.username
                ,b.bppbno
                ,b.bppbdate
                ,ac.kpno
                ,ac.styleno
                ,b.tanggal_aju
                ,supplier tujuan
                ,so.mindeldate as del_date
                ,concat(mi.goods_code,' ',mi.itemdesc) itemdesc
                ,mi.color
                ,no_rak as location
                ,qtyloc as loc_qty
                ,unitloc as loc_unit
                ,b.qty as qty_request
                ,qtysdhout as out_qty
                ,unitsdhout as out_unit
                ,'' as check_picker
                ,'' as check_loader
                ,'' as check_penerima
                ,b.remark
                ,b.idws_act
                
            FROM 
                bppb_req b
                INNER JOIN masteritem mi on b.id_item = mi.id_item 
                INNER JOIN mastersupplier msup on b.id_supplier=msup.id_supplier 
                INNER JOIN (select id_so,id_jo from jo_det group by id_jo)  jod on b.id_jo=jod.id_jo 
                inner join (select so.id,id_cost,min(sod.deldate_det) mindeldate from so inner join so_det sod on so.id=sod.id_so group by so.id) so on jod.id_so=so.id 
                inner join act_costing ac on so.id_cost=ac.id 
                left join 
                (select id_item,id_jo,group_concat(kode_rak,' ',qtyloc,' ',unitloc SEPARATOR ', ') no_rak,0 qtyloc,'' unitloc  from 
          (select a.id_item,a.id_jo,d.kode_rak,round(sum(roll_qty),2) qtyloc, round(sum(roll_qty_used),2) qtyused,round(sum(roll_qty),2) - round(sum(roll_qty_used),2) qtysisa,unit unitloc 
                  from bpb_roll_h a inner join bpb_roll s on a.id=s.id_h 
                  inner join master_rak d on s.id_rak_loc=d.id
                  group by id_item,id_jo,d.kode_rak
                                    having round(sum(roll_qty),2) - round(sum(roll_qty_used),2) != '0') tmplok group by id_item,id_jo) tbllok
                on b.id_item=tbllok.id_item and b.id_jo=tbllok.id_jo 
                left join 
                (select bppbno_req,id_item,id_jo,sum(qty) qtysdhout,unit unitsdhout from bppb 
                  where bppbno_req='$bppbno' group by id_item,id_jo) tblsdhout
                on b.bppbno=tblsdhout.bppbno_req and b.id_item=tblsdhout.id_item 
            WHERE 1=1
                AND bppbno = '$bppbno'
        ";

        $raw_result = $this
            ->query($sql)
            ->result();

        $items = array();
        $header = array();
        foreach($raw_result as $r){
            if(!count($header)){
                $header = array(
                    'bppbno' => $r->bppbno,
                    'kpno' => $r->kpno,
                    'styleno' => $r->styleno,
                    'req_date' => $r->bppbdate,
                    'tujuan' => $r->tujuan,
                    'del_date' => $r->del_date,
                    'username' => $r->username,
                    'idws_act' => $r->idws_act,
                );
            }
            $item = array(
                'itemdesc' => $r->itemdesc,
                'color' => $r->color,
                'location' => $r->location,
                'loc_qty' => $r->loc_qty,
                'loc_unit' => $r->loc_unit,
                'qty_request' => $r->qty_request,
                'out_qty' => $r->out_qty,
                'out_unit' => $r->out_unit,
                'check_picker' => $r->check_picker,
                'check_loader' => $r->check_loader,
                'check_penerima' => $r->check_penerima,
                'remark' => $r->remark,
            );
            $items[] = $item;
        }
        return(array(
            json_decode(json_encode($header)),
            json_decode(json_encode($items))
        ));
    }


}

// Retrieve parameters
if(!isset($_GET['id'])){
    exit('Missing Bppno parameter');
}
$id = $_GET['id'];

// Instantiate model object
$m = new Model($con_new);

// Get report data, assign into header and detail vars
list($h, $d) = $m->get_report($id);

//echo '<pre>';print_r(array($h, $d));exit();

// Get master company
$company = $m->get_master_company();


// Get Total Req
$total_req = $m->get_total_req($id);

// If job order is null, then it is non existent
if(is_null($h)){
    exit('Bppb Number Not Found');
}

// Close db connection
$con_new->close();

// Begin Capturing Output
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        html {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        html, body{
            font-family: sans-serif;
            width: 100%;
            height: 100%;
            margin:0;
            padding:0;
        }
        h3 {
            font-weight: normal;
        }
        table {
            border-spacing: 0;
            border-collapse: collapse;
            margin:0;
            padding:0;
        }
        td,
        th {
            padding: 2px;
            margin:0;
        }
        .table {
            border-collapse: collapse !important;
            width: 100%;
            max-width: 100%;
            font-size: 10px;
        }
        .table td{
            background-color: #fff;
        }
        .table th {
            background-color: #fff;
        }
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #ddd !important;
        }
        .text-right {
            text-align: right !important;
        }
        .text-center {
            text-align: center !important;
        }
        table.repHeader th {
            text-align: left;
        }
    }
    </style>
    <title>Request</title>
</head>
<body>
<table class="table" style="border-bottom: 2px solid #000000; margin-bottom:5px;">
    <tr>
        <td><img src="<?=Assets::$logo?>" width="100px" height="70px"> </td>
        <td style="text-align: right;vertical-align: bottom;font-size: 16px;"><?=strtoupper($company->company)?></td>
    </tr>
</table>
<h3 class="text-center">MATERIAL REQUEST</h3>
<h3 class="text-center"><?=$h->bppbno?></h3>
<table class="table repHeader" style="width: 100%">
    <tr>
        <th width="10%">WS #</th>
        <td><?=$h->kpno?></td>
        <th width="10%">Del. Date</th>
        <td><?=$h->del_date?></td>
    </tr>
    <tr>
        <th>Style #</th>
        <td><?=$h->styleno?></td>
        <th>To</th>
        <td><?=$h->tujuan?></td>
    </tr>
    <tr>
        <th>Req Date</th>
        <td ><?=$h->req_date?></td>
        <th>Request By</th> 
        <td><?=$h->username?></td>    
    </tr>
    <tr>     
        <th>WS ACT #</th><td><?=$h->idws_act?></td> 
        <th></th><td ></td>
    </tr>    

</table>
<br/>
<?php if(count((array) $d)):?>
    <table class="table table-bordered table-condensed">
        <thead>
        <tr>
            <th rowspan="2">NO</th>
            <th rowspan="2">ITEM NAME</th>
            <th rowspan="2">COLOR</th>
            <th rowspan="2">RAK</th>
            <th rowspan="2">QTY REQUEST</th>
            <th colspan="2">QTY KELUAR</th>
            <th colspan="3">CHECKLIST</th>
            <th rowspan="2">REMARK</th>
        </tr>
        <tr>
            <th>QTY</th>
            <th>UNIT</th>
            <th>PICKER</th>
            <th>LOADER</th>
            <th>PENERIMA</th>
        </tr> 
        </thead>
        <tbody>
        <?php $no = 1;?>
        <?php foreach($d as $_d):?>
            <tr>
                <td><?=$no++;?></td>
                <td><?=$_d->itemdesc?></td>
                <td><?=$_d->color?></td>
                <td><?=$_d->location?></td>
                <td class="text-right"><?=$_d->qty_request?></td>
                <td class="text-right"><?=$_d->out_qty?></td>
                <td><?=$_d->out_unit?></td>
                <td><?=$_d->check_picker?></td>
                <td><?=$_d->check_loader?></td>
                <td><?=$_d->check_penerima?></td>
                <td><?=$_d->remark?></td>
            </tr>  
        <?php endforeach;?>
        </tbody>
        <tr>
        <td colspan ="4" align ="center">TOTAL</td>
        <td align ="right"><?=$total_req->total_req?></td>
        <td colspan ="6"></td>
        </tr>      
    </table>
    <br>
    <table class="table">
        <tr>
            <td class="text-center">PIMPINAN</td>
            <td class="text-center">K. GUDANG</td>
            <td class="text-center">PENERIMA</td>
        </tr>
    </table>
<?php endif;?>
</body>
</html>
<?php
// Store output into vars
$html = ob_get_clean();
//exit($html);
// Convert output into pdf
include("../../mpdf57/mpdf.php");
$mpdf=new mPDF();
$mpdf->WriteHTML($html);
$mpdf->Output();
?>