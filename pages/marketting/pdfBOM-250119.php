<?php
// Load db connection
include_once '../../include/conn.php';
// Assets class (image, css, etc..)
class Config{
    // Loss percentage in fraction
    public static $loss = 0; //3%
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
            #show_error($err);
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



    /**
     * Get report data and transform into report friendly format
     * @param $jo_id Job Order ID
     * @return array of Job Order and Sales Order
     */
    public function get_report($jo_id)
    {

        // TODO: get header information
        $sql_h = "
            select g.supplier buyer,d.buyerno po_no,'' agent,jo_date date_of_issue,
            kpno ws_no,h.product_item item,styleno style_no,sum(i.qty) qty,
            max(f.deldate) delivery_date 
            from jo a inner join jo_det s on a.id=s.id_jo inner join so d on s.id_so=d.id 
            inner join act_costing f on d.id_cost=f.id inner join mastersupplier g on f.id_buyer=g.Id_Supplier
            inner join masterproduct h on f.id_product=h.id
            inner join so_det i on s.id_so=i.id_so
            where a.id='$jo_id'";

        $header = $this
            ->query($sql_h)
            ->row();

        if(!count((array) $header)){
            return array(null, null);
        }

        $sql = "
            SELECT k.id,a.nama_group, s.nama_sub_group, l.color,l.size,concat(
                   d.nama_type,' ',e.nama_contents,' ',f.nama_width,' ',
                   g.nama_length,' ',h.nama_weight,' ',l.color,' ',l.size,' ',j.nama_desc) item,l.qty qty_gmt,k.cons,round(l.qty*k.cons,2) qty_bom,k.unit 
            From bom_jo_item k INNER JOIN so_det l on k.id_so_det=l.id 
            INNER JOIN masterdesc j on k.id_item=j.id
            INNER JOIN mastercolor i on i.id=j.id_color
            INNER JOIN masterweight h on h.id=i.id_weight
            INNER JOIN masterlength g on g.id=h.id_length
            INNER JOIN masterwidth f on f.id=g.id_width
            INNER JOIN mastercontents e on e.id=f.id_contents
            INNER JOIN mastertype2 d on d.id=e.id_type
            INNER JOIN mastersubgroup s on s.id=d.id_sub_group
            INNER JOIN mastergroup a on a.id=s.id_group
            WHERE k.id_jo= $jo_id and k.status='M'
            ORDER BY l.id_so
                ,a.nama_group
                ,l.color
                ,l.size
        ";

        $raw_result = $this
            ->query($sql)
            ->result();

        $items = array();

        foreach($raw_result as $r){
                $item = array(
                    'nama_group' => $r->nama_group,
                    'nama_sub_group' => $r->nama_sub_group,
                    'color' => $r->color,
                    'size' => $r->size,
                    'item' => $r->item,
                    'qty_org' => $r->qty_gmt,
                    'cons' => $r->cons,
                    'qty_req' => $r->qty_bom,
                    'unit' => $r->unit,
                    'loss' => Config::$loss,
                    'loss_value' => $r->qty_bom * Config::$loss,
                    'tot_qty_req' => $r->qty_bom + ($r->qty_bom * Config::$loss),
                );

                if(!isset($items[$r->nama_group])){
                    if(!isset($items[$r->nama_group][$r->nama_sub_group])){
                        $items[$r->nama_group][$r->nama_sub_group] = array(
                                'list' => array(),
                                'tot_qty_req' => 0
                        );
                    }
                }
                $items[$r->nama_group][$r->nama_sub_group]['list'][] = $item;
                $items[$r->nama_group][$r->nama_sub_group]['tot_qty_req'] += $item['tot_qty_req'];
                $items[$r->nama_group][$r->nama_sub_group]['tot_qty_req_unit'] = $r->unit;
        }


        return(array(
            json_decode(json_encode($header)),
            json_decode(json_encode($items))
        ));
    }
}

// Retrieve parameters
if(!isset($_GET['id'])){
    exit('Missing Job Order Id parameter');
}
$id = $_GET['id'];

// Instantiate model object
$m = new Model($con_new);

// Get report data, assign into job order (header) and sales order(detail) vars
list($h, $d) = $m->get_report($id);

//echo '<pre>';print_r(array($h, $d));exit();

// Get master company
$company = $m->get_master_company();

// If job order is null, then it is non existent
if(is_null($h)){
    exit('Job Order Not Found');
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
            background-color: #eee;
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
    }
    </style>
    <title>BOM</title>
</head>
<body>
<table class="table" style="border-bottom: 2px solid #000000; margin-bottom:5px;">
    <tr>
        <td><img src="<?=Assets::$logo?>" width="100px" height="70px"> </td>
        <td style="text-align: right;vertical-align: bottom;font-size: 16px;"><?=strtoupper($company->company)?></td>
    </tr>
</table>
<h3>BILL OF MATERIAL</h3>
<table class="table">
    <tr>
        <td>
            <table class="table table-bordered table-condensed">
                <tr>
                    <th>BUYER</th>
                    <td><?=$h->buyer;?></td>
                </tr>
                <tr>
                    <th>PO. NUMBER</th>
                    <td><?=$h->po_no;?></td>
                </tr>
                <tr>
                    <th>AGENT</th>
                    <td><?=$h->agent;?></td>
                </tr>
                <tr>
                    <th>DATE OF ISSUE</th>
                    <td><?=$h->date_of_issue ? date('d-F-Y', strtotime($h->date_of_issue)) : '';?></td>
                </tr>
                <tr>
                    <th>NO WS</th>
                    <td><?=$h->ws_no;?></td>
                </tr>
            </table>
        </td>
        <td>&nbsp;</td>
        <td style="vertical-align: top">
            <table class="table table-bordered table-condensed">
                <tr>
                    <th>ITEM</th>
                    <td><?=$h->item;?></td>
                </tr>
                <tr>
                    <th>STYLE NO.</th>
                    <td><?=$h->style_no;?></td>
                </tr>
                <tr>
                    <th>QUANTITY</th>
                    <td><?=$h->qty;?></td>
                </tr>
                <tr>
                    <th>DELIVERY DATE</th>
                    <td><?=$h->delivery_date ? date('d-F-Y', strtotime($h->delivery_date)) : '';?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<?php if(count((array) $d)):?>
    <table class="table table-bordered table-condensed">
        <thead>
        <tr>
            <th rowspan="2">NO</th>
            <th rowspan="2">ITEM</th>
            <th rowspan="2">DESCRIPTION</th>
            <th rowspan="2">COLOR</th>
            <th rowspan="2">ORIG QTY</th>
            <th colspan="2">CONS</th>
            <th rowspan="2">QTY REQ'D</th>
            <th colspan="2">LOSS</th>
            <th colspan="2">TOT QTY REQ'D</th>
        </tr>
        <tr>
            <th>/PC</th>
            <th>UNIT</th>
            <th>%</th>
            <th>VALUE</th>
            <th>QTY</th>
            <th>UNIT</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($d as $_g => $_d):?>
            <tr>
                <th colspan="12"><?=$_g?></th>
            </tr>
            <?php foreach($_d as $_sg => $__d):?>
                <?php foreach($__d->list as $l):?>
                    <tr>
                        <td> </td>
                        <td><?=$_sg?></td>
                        <td><?=$l->item?></td>
                        <td><?=$l->color?></td>
                        <td class="text-right"><?=number_format($l->qty_org)?></td>
                        <td><?=$l->cons?></td>
                        <td><?=$l->unit?></td>
                        <td class="text-right"><?=number_format($l->qty_req)?></td>
                        <td><?=$l->loss*100?>%</td>
                        <td class="text-right"><?=number_format($l->loss_value)?></td>
                        <td class="text-right"><?=number_format($l->tot_qty_req)?></td>
                        <td><?=$l->unit?></td>
                    </tr>
                <?php endforeach;?>
                <tr>
                    <td colspan="9">PO No.</td>
                    <td>TOTAL</td>
                    <td class="text-right"><?=number_format($__d->tot_qty_req);?></td>
                    <td><?=$__d->tot_qty_req_unit;?></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="15">&nbsp;</td>
                </tr>
            <?php endforeach;?>
        <?php endforeach;?>
        </tbody>
    </table>
    <table class="table">
        <tr>
            <td style="height: 100px"></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td class="text-center">Requester</td>
            <td class="text-center">Acknowledged</td>
            <td class="text-center">Approved</td>
            <td class="text-center">Approved</td>
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