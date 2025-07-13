<?php
// Load db connection
include_once '../../include/conn.php';
@include_once '../forms/fungsi.php';
$id_item=$_GET['id'];
$rscs=mysqli_fetch_array(mysqli_query($con_new, "select * from act_costing where id='$id_item'"));
//print_r($rscs);
$cost_date=$rscs['cost_date'];
$cfm_price=$rscs['cfm_price'];
$cfm_price_curr=$rscs['curr'];
$ga=$rscs['ga_cost'];
$vat=$rscs['vat'];
//$deal=$rscs['deal_allow'];
$wsno=$rscs['kpno'];
$rs=mysqli_fetch_array(mysqli_query($con_new, "select * from masterrate where curr='USD' and tanggal='".fd($cost_date)."'"));
$rate_jual=$rs['rate_jual'];
$rate_beli=$rs['rate_beli'];
$gtaccs_idr1=0;
$gtaccs_usd1=0;
if($cfm_price_curr=="IDR")
{ $cfm_price_idr=$cfm_price;
//  $cfm_price_usd=$cfm_price/$rate_jual;
$cfm_price_usd=$cfm_price/$rate_beli;
}
else
{ $cfm_price_idr=$cfm_price*$rate_jual;
  $cfm_price_usd=$cfm_price;
}

/* 
Deal Allowance : 
JS SCRIPT : 

	data.valuedealallowanceidr = data.confirmpriceidr - data.totalcostingidr;
	data.valuedealallowanceusd = data.confirmpriceusd - data.totalcostingusd;
*/

$tot_cd = flookup("sum((if(jenis_rate='B',price/rate_beli,price)*cons)+((if(jenis_rate='B',price/rate_beli,price)*cons)*allowance/100))",
    "act_costing_mat a inner join act_costing s on a.id_act_cost=s.id
    inner join masterrate d on 'USD'=d.curr and s.cost_date=d.tanggal","id_act_cost='$id_item'");
$tot_cd_idr = flookup("sum((if(jenis_rate='J',price*rate_jual,price)*cons)+((if(jenis_rate='J',price*rate_jual,price)*cons)*allowance/100))",
    "act_costing_mat a inner join act_costing s on a.id_act_cost=s.id
    inner join masterrate d on 'USD'=d.curr and s.cost_date=d.tanggal","id_act_cost='$id_item'");
$tot_mf = flookup("sum((if(jenis_rate='B',price/rate_beli,price)*cons)+((if(jenis_rate='B',price/rate_beli,price)*cons)*allowance/100))",
    "act_costing_mfg a inner join act_costing s on a.id_act_cost=s.id
    inner join masterrate d on 'USD'=d.curr and s.cost_date=d.tanggal","id_act_cost='$id_item'");
$tot_mf_idr = flookup("sum((if(jenis_rate='J',price*rate_jual,price)*cons)+((if(jenis_rate='J',price*rate_jual,price)*cons)*allowance/100))",
    "act_costing_mfg a inner join act_costing s on a.id_act_cost=s.id
    inner join masterrate d on 'USD'=d.curr and s.cost_date=d.tanggal","id_act_cost='$id_item'");
$tot_ot = flookup("sum(if(jenis_rate='B',price/rate_beli,price))",
    "act_costing_oth a inner join act_costing s on a.id_act_cost=s.id
    inner join masterrate d on 'USD'=d.curr and s.cost_date=d.tanggal","id_act_cost='$id_item'");
$tot_ot_idr = flookup("sum(if(jenis_rate='J',price*rate_jual,price))",
    "act_costing_oth a inner join act_costing s on a.id_act_cost=s.id
    inner join masterrate d on 'USD'=d.curr and s.cost_date=d.tanggal","id_act_cost='$id_item'");
$total_ga_cost = ($tot_cd + $tot_mf + $tot_ot) * $ga/100;
$total_ga_cost_idr=($tot_cd_idr + $tot_mf_idr + $tot_ot_idr) * $ga/100;
$total_cost = $tot_cd + $tot_mf + $tot_ot + $total_ga_cost;
$total_cost_idr=$tot_cd_idr + $tot_mf_idr + $tot_ot_idr + $total_ga_cost_idr;
$total_vat = (($total_cost+$total_ga_cost)*$vat/100);
$total_vat_idr = (($total_cost_idr+$total_ga_cost_idr)*$vat/100);
//$total_deal = (($total_cost+$total_vat+$total_ga_cost)*$deal/100);
//$total_deal_idr = (($total_cost_idr+$total_vat_idr+$total_ga_cost_idr)*$deal/100);
$total_deal = $cfm_price_usd - $total_cost;
$total_deal_idr =  $cfm_price_idr - $total_cost_idr;
$deal = ($total_deal_idr / $cfm_price_idr) * 100;


$total_cost_plus = $total_cost + $total_vat + $total_deal + $total_ga_cost;
$total_cost_plus_idr = $total_cost_idr + $total_vat_idr + $total_deal_idr + $total_ga_cost_idr;

// Assets class (image, css, etc..)
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
     * Fetch multiple rows helper in array format
     * @return array
     */
    public function result_array()
    {
        $rows = array();
        if(!$this->result){
            return $rows;
        }
        while($row = mysqli_fetch_assoc($this->result)){
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

    /**
     * Fetch single row helper as array
     * @return null|object
     */
    public function row_array()
    {
        return mysqli_fetch_assoc($this->result);
    }

    public function get_master_company()
    {
        return $this->query("
            SELECT * FROM mastercompany;
        ")->row();
    }

    /**
     * Get report data and transform into report friendly format
     * @param $act_costing_id Actual Costing ID
     * @return array
     */
    public function get_report_fabric($act_costing_id){
        return $this->query("SELECT *, 
                d.nama_group mattype,
                concat(nama_group,' ',nama_sub_group,' ',nama_type,' ',nama_contents) itemdesc,
                jenis_rate,
                price,
                cons,
                s.unit,
                allowance,
                material_source,
                s.id,
                i.supplier as buyer,
                'JACKET TUTTLE NECK' as item,
                1500 as planned_qty
                FROM act_costing a INNER JOIN act_costing_mat s ON 
                a.id=s.id_act_cost INNER JOIN mastergroup d INNER JOIN mastersubgroup f ON 
          d.id=f.id_group 
          INNER JOIN mastertype2 g ON f.id=g.id_sub_group
          INNER JOIN mastercontents h ON g.id=h.id_type AND s.id_item=h.id
          INNER JOIN mastersupplier i ON a.id_buyer=i.Id_Supplier
          WHERE a.id = $act_costing_id
            AND nama_group LIKE '%FABRIC%'")->result();
    }

    public function get_report_accesories($act_costing_id){
        return $this->query("SELECT *, 
                d.nama_group mattype,
                concat(nama_group,' ',nama_sub_group,' ',nama_type,' ',nama_contents) itemdesc,
                price,
                jenis_rate,
                cons,
                s.unit,
                allowance,
                material_source,
                s.id,
                i.supplier as buyer,
                'JACKET TUTTLE NECK' as item,
                1500 as planned_qty
                FROM act_costing a INNER JOIN act_costing_mat s ON 
                a.id=s.id_act_cost INNER JOIN mastergroup d INNER JOIN mastersubgroup f ON 
          d.id=f.id_group 
          INNER JOIN mastertype2 g ON f.id=g.id_sub_group
          INNER JOIN mastercontents h ON g.id=h.id_type AND s.id_item=h.id
          INNER JOIN mastersupplier i ON a.id_buyer=i.Id_Supplier
          WHERE a.id = $act_costing_id
            AND nama_group LIKE '%ACCESORIES%'")->result();
    }

    public function get_report_manufacturing($act_costing_id){
        return $this->query("SELECT * FROM act_costing a INNER JOIN act_costing_mfg s ON a.id=s.id_act_cost 
                             INNER JOIN mastercf i ON s.id_item = i.id
                             WHERE a.id = $act_costing_id
                             ")->result();
    }

    public function get_report_other($act_costing_id){
        return $this->query("SELECT * FROM act_costing a INNER JOIN act_costing_oth s ON a.id=s.id_act_cost 
                             INNER JOIN masterothers i ON s.id_item = i.id
                             INNER JOIN mastersupplier h ON a.id_buyer=h.Id_Supplier
                             WHERE a.id = $act_costing_id
                             ")->result();
    }

}

// Retrieve parameters
if(!isset($_GET['id'])){
    exit('Missing Costing Id parameter');
}
$id = $_GET['id'];
$sql="SELECT s.supplier,d.product_item,a.qty,up.fullname,up2.fullname app1_by_name,app1_date,
    dateinput,a.attach_file,a.styleno 
    from act_costing a inner join mastersupplier s on a.id_buyer=s.id_supplier 
    inner join masterproduct d on a.id_product=d.id 
    inner join userpassword up on a.username=up.username
    left join userpassword up2 on a.app1_by=up2.username 
    where a.id='$id'";
//$rsh=mysql_fetch_array(mysql_query($sql));

// Instantiate model object
$m = new Model($con_new);

$rsh=$m->query($sql)->row_array();

// Get report data
$costings_fabric = $m->get_report_fabric($id);
$costings_accesories = $m->get_report_accesories($id);
$costing_manufacturing = $m->get_report_manufacturing($id);
$costing_other = $m->get_report_other($id);

// pengulangan 2 array untuk Kode group
$accessories = array();
foreach($costings_accesories as $r){
    $accessories[$r->kode_group][] = $r;
}



// ARRAY KURS
$kurs = array(
 'IDR' => 13200,
 'USD' => 13600,
);

// TOTAL cost 
$totalcost_USD = 0;
$totalcost_IDR = 0;
$t_totalcost_USD = 0;
$t_totalcost_IDR = 0;
$VAT = 1+10/100;

$tkurs = array(
    'T_Value_IDR'=> 0,
    'T_Value_USD'=> 0,
);

$sum = array(
        'Value_IDR'=> 0,
        'Value_USD'=> 0,
        'Value_IDR1'=> 0,
        'Value_USD1'=> 0,
        'Value_IDR_MFG'=> 0,
        'Value_USD_MFG'=> 0,
        'Value_IDR_OTH'=> 0,
        'Value_USD_OTH'=> 0,
    );

foreach($costings_fabric as $tcostings_fabric) {
    $sum['Value_IDR'] += $tcostings_fabric->price * $kurs['IDR'] * $tcostings_fabric->cons * (1 + $tcostings_fabric->allowance);
    $sum['Value_USD'] += $tcostings_fabric->price * $kurs['USD'] * $tcostings_fabric->cons * (1 + $tcostings_fabric->allowance);
}

foreach($accessories as $tacc){
    foreach($tacc as $tcostings_accesories) {
        $sum['Value_IDR1'] += $tcostings_accesories->price * $kurs['IDR'] * $tcostings_accesories->cons * (1 + $tcostings_accesories->allowance);
        $sum['Value_USD1'] += $tcostings_accesories->price * $kurs['USD'] * $tcostings_accesories->cons * (1 + $tcostings_accesories->allowance);
    }
}

foreach($costing_manufacturing as $tcosting_manufacturing){
    $sum['Value_IDR_MFG'] += $tcosting_manufacturing->price * $kurs['IDR'] * $tcosting_manufacturing->cons * (1 + $tcosting_manufacturing->allowance );
    $sum['Value_USD_MFG'] += $tcosting_manufacturing->price * $kurs['USD'] * $tcosting_manufacturing->cons * (1 + $tcosting_manufacturing->allowance );
}

foreach($costing_other as $tcosting_other) {
    $sum['Value_IDR_OTH'] += $tcosting_other->price * $kurs['IDR'] * $tcosting_other->cons * (1 + $tcosting_other->allowance);
    $sum['Value_USD_OTH'] += $tcosting_other->price * $kurs['USD'] * $tcosting_other->cons * (1 + $tcosting_other->allowance);
}

$t_totalcost_IDR += $sum['Value_IDR'];
$t_totalcost_USD += $sum['Value_USD'];
$t_totalcost_IDR += $sum['Value_IDR1'];
$t_totalcost_USD += $sum['Value_USD1'];
$t_totalcost_IDR += $sum['Value_IDR_MFG'];
$t_totalcost_USD += $sum['Value_USD_MFG'];
$t_totalcost_IDR += $sum['Value_IDR_OTH'];
$t_totalcost_USD += $sum['Value_USD_OTH'];


// mengechek data array
/*echo '<pre>';print_r($accessories);
exit();*/

// Get master company
$company = $m->get_master_company();

// If job order is null, then it is non existent
if(is_null($costings_fabric)){
    exit('Actual Costing Not Found');
}
if(is_null($costings_accesories)){
    exit('Actual Costing Not Found');
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
            font-size: 12px;
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
        .collapse{
            font
            font-size: 31px;
            display:block;
        }
        .collapse + input{
            display:none;
        }
        .collapse + input + *{
            display:none;
        }
        .collapse+ input:checked + *{
            display:block;
        }
        .produk
        {
            font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;

            border-collapse:collapse;
            background-color:#dddddd;
        }
        .produk td, .produk th
        {
            font-size:0.9em;
            border:1px solid #000000;
            padding:0px 5px 0px 5px;
        }
        .produk th
        {
            font-size:0.9em;
            text-align:center;
            padding-top:1px;
            padding-bottom:1px;
            background-color:#ffffff;
            color:#000000;
        }
        .produk td.smaller, produk th.smaller{
            font-size:0.8em;
        }
        .produk tr.alt td
        {
            color:#000000;
            background-color:#FFFFFF;
        }
    </style>
    <title>Actual Costing</title>
</head>
<body>
<table class="table" style="border-bottom: 2px solid #000000; margin-bottom:5px;">
    <tr>
        <td><img src="<?=Assets::$logo?>" width="100px" height="70px"> </td>
        <td style="text-align: right;vertical-align: bottom;font-size: 16px;"><?=strtoupper($company->company)?></td>
    </tr>
</table>
<?php $stylehead="class='text-center' style='background-color:#ffffff;border-bottom-color:#005ce6;text-align:right;'"; ?>
<table width="50%">
    <tr>
        <td <?php echo $stylehead; ?>>Buyer / Brand</td>
        <td <?php echo $stylehead; ?>><?php echo $rsh['supplier']; ?></td>
        <td rowspan="7">&nbsp&nbsp&nbsp<img src='upload_files/costing/<?php echo $rsh['attach_file']; ?>' width='150px' height='150px'></td>
    </tr>
    <tr>
        <td <?php echo $stylehead; ?>>Item</td>
        <td <?php echo $stylehead; ?>><?php echo $rsh['product_item']; ?></td>
    </tr>
    <tr>
        <td <?php echo $stylehead; ?>>Style #</td>
        <td <?php echo $stylehead; ?>><?php echo $rsh['styleno']; ?></td>
    </tr>
    <tr>
        <td <?php echo $stylehead; ?>>WS #</td>
        <td <?php echo $stylehead; ?>><?php echo $wsno; ?></td>
    </tr>
    <tr>
        <td <?php echo $stylehead; ?>>Planned Qtty</td>
        <td <?php echo $stylehead; ?>><?php echo fn($rsh['qty'],0); ?></td>
    </tr>
    <tr>
        <td <?php echo $stylehead; ?>>Rate Jual</th>
        <td <?php echo $stylehead; ?>><?php echo fn($rate_jual,0)?></th>
    </tr>
    <tr>
        <td <?php echo $stylehead; ?>>Rate Beli</th>
        <td <?php echo $stylehead; ?>><?php echo fn($rate_beli,0)?></th>
    </tr>
</table>
<table class="table produk">
<!--COSTING TABLE-->
    <tr>
        <td colspan="23" align="left" style="background-color:#292931;color:white;font-size:20px;border:0px solid #98bf21;">COSTING TABLE</td>
    </tr>
    <tr>
        <th class="text-center" style="background-color:#ffffff;" rowspan="">FABRIC</th>
        <th class="text-center" style="background-color:#ffffff;" rowspan="">DESC</th>
        <th class="text-center" style="background-color:#ffffff;">SUPPLIER</th>
        <th class="text-center" style="background-color:#ffffff;">UNIT px, IDR</th>
        <th class="text-center" style="background-color:#ffffff;">Unit Px, USD</th>
        <th class="text-center" style="background-color:#ffffff;">Cons. / PC</th>
        <th class="text-center" style="background-color:#ffffff;">Unit</th>
        <th class="text-center" style="background-color:#ffffff;">Allowance</th>
        <th class="text-center" style="background-color:#ffffff;">Value, IDR</th>
        <th class="text-center" style="background-color:#ffffff;">Value, USD</th>
        <th class="text-center" style="background-color:#ffffff;">%</th>
    </tr>

    <?php
    $sum = array(
        'Value_IDR'=> 0,
        'Value_USD'=> 0,
    );
    $sumgt = array(
        'GT_IDR'=> 0,
        'GT_USD'=> 0,
    );
    ?>
    <?php if(count((array)$costings_fabric)):?>
        <?php foreach($costings_fabric as $costings_fabric):?>

            <tr>
                <td align="center" nowrap style="background-color:;"><?=$costings_fabric->mattype?></td>
                <td align="center" style="background-color:;"><?=$costings_fabric->itemdesc?></td>
                <td align="center" style="background-color:;"></td>
                <?php 
                if ($costings_fabric->jenis_rate=="J")
                { $px_idr=$costings_fabric->price * $rate_jual; 
                  $px_usd=round($costings_fabric->price,4);
                }
                else
                { $px_idr=$costings_fabric->price; 
                  $px_usd=round($costings_fabric->price / $rate_beli,4);
                }
                $allowcs_usd = ($px_usd*$costings_fabric->cons) * ($costings_fabric->allowance/100);
                $allowcs_idr = ($px_idr*$costings_fabric->cons) * ($costings_fabric->allowance/100);
                $valcs_usd = ($px_usd * $costings_fabric->cons) + $allowcs_usd;
                $valcs_idr = ($px_idr * $costings_fabric->cons) + $allowcs_idr;
                if ($total_cost==0)
                {   $persen=0;  }
                else
                {   $persen = ($valcs_usd / $total_cost) * 100; }
                ?>
                <td align="center" style="background-color:;"><?php echo fn($px_idr,2); ?></td>
                <td align="center" style="background-color:;"><?php echo fn($px_usd,4); ?></td>
                <td align="center" style="background-color:;"><?=$costings_fabric->cons?></td>
                <td align="center" style="background-color:;"><?=$costings_fabric->unit?></td>
                <td align="center" style="border-right-color: #ff0000;background-color:;"><?=$costings_fabric->allowance?> %</td>
                <td align="center" style="border-right-color: #ff0000;background-color:;"><?php echo fn($valcs_idr,2); ?></td>
                <td align="center" style="background-color:;"><?php echo fn($valcs_usd,4); ?></td>
                <td align="center" style="background-color:;"><?php echo fn($persen,2)." %"; ?> %</td>
            </tr>
        <?php 
        $sum['Value_IDR'] += round($valcs_idr,2);
        $sum['Value_USD'] += round($valcs_usd,4);
        ?>
        <?php endforeach;?>
        <?php
        $totalcost_IDR += $sum['Value_IDR'];
        $totalcost_USD += $sum['Value_USD'];
        ?>
    <?php endif;?>

    <tr>
    <tr class='alt' >
        <td align="center" nowrap style="background-color:#ffffff;">TOTAL </td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="border-right-color: #ff0000;background-color:#ffffff;"></td>
        <td align="center" style="border-right-color: #ff0000;background-color:#ffffff;"><?php 
        echo fn($sum['Value_IDR'],2)?>
        <td align="center" style="background-color:#ffffff;"><?php 
        echo fn($sum['Value_USD'],4)?>
        </td>
        <td align="center" style="background-color:#ffffff;"></td>
    </tr>
<!-- TABLE ACCESORIES -->
    <tr>
        <td colspan="23" align="left" style="background-color:#292931;color:white;font-size:20px;border:0px solid #98bf21;">ACCESSORIES</td>
    </tr>
        <?php
        $sum = array(
            'Value_IDR1'=> 0,
            'Value_USD1'=> 0,
        );
        ?>
        <?php if(count((array)$accessories)):?>
        <?php foreach($accessories as $acc):?>
            
    <tr>
        <th class="text-center" style="background-color:#ffffff;" ><?=$acc[0]->nama_group?></th>
        <th class="text-center" style="background-color:#ffffff;" >DESC</th>
        <th class="text-center" style="background-color:#ffffff;">SUPPLIER</th>
        <th class="text-center" style="background-color:#ffffff;">UNIT px, IDR</th>
        <th class="text-center" style="background-color:#ffffff;">Unit Px, USD</th>
        <th class="text-center" style="background-color:#ffffff;">Cons. / PC</th>
        <th class="text-center" style="background-color:#ffffff;">Unit</th>
        <th class="text-center" style="background-color:#ffffff;">Allowance</th>
        <th class="text-center" style="background-color:#ffffff;">Value, IDR</th>
        <th class="text-center" style="background-color:#ffffff;">Value, USD</th>
        <th class="text-center" style="background-color:#ffffff;">%</th>
    </tr>
            <?php foreach ($acc as $costings_accesories) {?>

            <tr>
                <td align="center" nowrap style="background-color:;"><?=$costings_accesories->mattype?></td>
                <td align="center" style="background-color:;"><?=$costings_accesories->itemdesc?></td>
                <td align="center" style="background-color:;"></td>
                <?php 
                if ($costings_accesories->jenis_rate=="J")
                { $px_idr=$costings_accesories->price * $rate_jual; 
                  $px_usd=round($costings_accesories->price,4);
                }
                else
                { $px_idr=$costings_accesories->price; 
                  $px_usd=round($costings_accesories->price / $rate_beli,4);
                }
                $allowcs_usd = ($px_usd*$costings_accesories->cons) * ($costings_accesories->allowance/100);
                $allowcs_idr = ($px_idr*$costings_accesories->cons) * ($costings_accesories->allowance/100);
                $valcs_usd = ($px_usd * $costings_accesories->cons) + $allowcs_usd;
                $valcs_idr = ($px_idr * $costings_accesories->cons) + $allowcs_idr;
                if ($total_cost==0)
                {   $persen=0;  }
                else
                {   $persen = ($valcs_usd / $total_cost) * 100; }
                ?>
                <td align="center" style="background-color:;"><?php echo fn($px_idr,2) ?></td>
                <td align="center" style="background-color:;"><?php echo fn($px_usd,4) ?></td>
                <td align="center" style="background-color:;"><?=$costings_accesories->cons?></td>
                <td align="center" style="background-color:;"><?=$costings_accesories->unit?></td>
                <td align="center" style="border-right-color: #ff0000;background-color:;"><?=$costings_accesories->allowance?> %</td>
                <td align="center" style="border-right-color: #ff0000;background-color:;"><?php echo fn($valcs_idr,2); ?></td>
                <td align="center" style="background-color:;"><?php echo fn($valcs_usd,4) ?></td>
                <td align="center" style="background-color:;"><?php echo fn($persen,2)." %" ?> %</td>
            </tr>
        <?php 
        $sum['Value_IDR1'] += round($valcs_idr,2);
        $sum['Value_USD1'] += round($valcs_usd,4);
        $sumgt['GT_USD'] += round($sum['Value_USD1'],4);
        $sumgt['GT_IDR'] += round($sum['Value_IDR1'],2);
        }
        ?>
    <tr>
    <tr class='alt' >
        <td align="center" nowrap style="background-color:#ffffff;">TOTAL </td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="border-right-color: #ff0000;background-color:#ffffff;"></td>
        <td align="center" style="border-right-color: #ff0000;background-color:#ffffff;"><?php 
        echo fn($sum['Value_IDR1'],2)?>
        <td align="center" style="background-color:#ffffff;"><?php 
        echo fn($sum['Value_USD1'],4)?>
        </td>
        <td align="center" style="background-color:#ffffff;"></td>
    </tr>
        <p></p>
    <?php
    $gtaccs_idr1=$gtaccs_idr1+$sum['Value_IDR1'];
    $gtaccs_usd1=$gtaccs_usd1+$sum['Value_USD1'];
    $sum = array(
        'Value_IDR1'=> 0,
        'Value_USD1'=> 0,
    );
    ?>
    <?php endforeach;?>
    <?php
    $totalcost_IDR = $gtaccs_idr1 + $sum['Value_IDR1'];
    $totalcost_USD = $gtaccs_usd1 + $sum['Value_USD1'];
    ?>
    <?php endif;?>
    <tr class='alt' >
        <td align="center" nowrap style="background-color:#ffffff;">TOTAL ACCESSORIES COST</td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center"></td>
        <td align="center"><?php 
        echo fn($totalcost_IDR,2)?>
        <td align="center" style="background-color:#ffffff;"><?php 
        echo fn($totalcost_USD,4)?>
        </td>
        <td align="center" style="background-color:#ffffff;"></td>
    </tr>
<!-- table manufacturing -->
    <tr>
        <td colspan="23" align="left" style="background-color:#292931;color:white;font-size:20px;border:0px solid #98bf21;">MANUFACTURING</td>
    </tr>
    <tr>
        <th class="text-center" style="background-color:#ffffff;" >WORK</th>
        <th class="text-center" style="background-color:#ffffff;" >DESC</th>
        <th class="text-center" style="background-color:#ffffff;">SUPPLIER</th>
        <th class="text-center" style="background-color:#ffffff;">UNIT px, IDR</th>
        <th class="text-center" style="background-color:#ffffff;">Unit Px, USD</th>
        <th class="text-center" style="background-color:#ffffff;">Cons. / PC</th>
        <th class="text-center" style="background-color:#ffffff;">Unit</th>
        <th class="text-center" style="background-color:#ffffff;">Allowance</th>
        <th class="text-center" style="background-color:#ffffff;">Value, IDR</th>
        <th class="text-center" style="background-color:#ffffff;">Value, USD</th>
        <th class="text-center" style="background-color:#ffffff;">%</th>
    </tr>
        <?php
        $sum = array(
            'Value_IDR_MFG'=> 0,
            'Value_USD_MFG'=> 0,
        );
        ?>
        <?php if(count((array)$costing_manufacturing)):?>
        <?php foreach($costing_manufacturing as $costing_manufacturing):?>
            <tr>
                <td align="center" nowrap style="background-color:;"><?=$costing_manufacturing->cfcode?></td>
                <td align="center" style="background-color:;"><?=$costing_manufacturing->cfdesc?></td>
                <td align="center" style="background-color:;"></td>
                <?php 
                if ($costing_manufacturing->jenis_rate=="J")
                { $px_idr=$costing_manufacturing->price * $rate_jual; 
                  $px_usd=round($costing_manufacturing->price,4);
                }
                else
                { $px_idr=$costing_manufacturing->price; 
                  $px_usd=round($costing_manufacturing->price / $rate_beli,4);
                }
                $allowcs_usd = ($px_usd*$costing_manufacturing->cons) * ($costing_manufacturing->allowance/100);
                $allowcs_idr = ($px_idr*$costing_manufacturing->cons) * ($costing_manufacturing->allowance/100);
                $valcs_usd = ($px_usd * $costing_manufacturing->cons) + $allowcs_usd;
                $valcs_idr = ($px_idr * $costing_manufacturing->cons) + $allowcs_idr;
                if ($total_cost==0)
                {   $persen=0;  }
                else
                {   $persen = ($valcs_usd / $total_cost) * 100; }
                ?>
                <td align="center" style="background-color:;"><?php echo fn($px_idr,2); ?></td>
                <td align="center" style="background-color:;"><?php echo fn($px_usd,4); ?></td>
                <td align="center" style="background-color:;"><?=$costing_manufacturing->cons?></td>
                <td align="center" style="background-color:;"><?=$costing_manufacturing->unit?></td>
                <td align="center" style="border-right-color: #ff0000;background-color:;"><?=$costing_manufacturing->allowance?> %</td>
                <td align="center" style="border-right-color: #ff0000;background-color:;"><?php echo fn($valcs_idr,2); ?></td>
                <td align="center" style="background-color:;"><?php echo fn($valcs_usd,4); ?></td>
                <td align="center" style="background-color:;"><?php echo fn($persen,2)." %" ?></td>
            </tr>
            <?php 
        $sum['Value_IDR_MFG'] += round($valcs_idr,2);
        $sum['Value_USD_MFG'] += round($valcs_usd,4);
        ?>
            <?php endforeach;?>
            <?php
                $totalcost_IDR += $sum['Value_IDR_MFG'];
                $totalcost_USD += $sum['Value_USD_MFG'];
            ?>
            <?php endif;?>
    <tr class='alt' >
        <td align="center" nowrap style="background-color:#ffffff;">TOTAL MANUFACTURING COST</td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="border-right-color: #ff0000;background-color:#ffffff;"></td>
        <td align="center" style="border-right-color: #ff0000;background-color:#ffffff;"><?php 
        echo fn($sum['Value_IDR_MFG'],2)?>
        <td align="center" style="background-color:#ffffff;"><?php 
        echo fn($sum['Value_USD_MFG'],4)?>
        </td>
        <td align="center" style="background-color:#ffffff;"></td>
    </tr>
<P></P>
<!-- TABLE OTHER COST -->
    <tr>
        <td colspan="23" align="left" style="background-color:#292931;color:white;font-size:20px;border:0px solid #98bf21;">OTHER COST</td>
    </tr>
    <tr>
        <th class="text-center" style="background-color:#ffffff;" >OTHER COST</th>
        <th class="text-center" style="background-color:#ffffff;" >DESC</th>
        <th class="text-center" style="background-color:#ffffff;">SUPPLIER</th>
        <th class="text-center" style="background-color:#ffffff;">UNIT px, IDR</th>
        <th class="text-center" style="background-color:#ffffff;">Unit Px, USD</th>
        <th class="text-center" style="background-color:#ffffff;">Cons. / PC</th>
        <th class="text-center" style="background-color:#ffffff;">Unit</th>
        <th class="text-center" style="background-color:#ffffff;">Allowance</th>
        <th class="text-center" style="background-color:#ffffff;">Value, IDR</th>
        <th class="text-center" style="background-color:#ffffff;">Value, USD</th>
        <th class="text-center" style="background-color:#ffffff;">%</th>
    </tr>
        <?php
        $sum = array(
            'Value_IDR_OTH'=> 0,
            'Value_USD_OTH'=> 0,
        );
        ?>
        <?php if(count((array)$costing_other)):?>
        <?php foreach($costing_other as $costing_other):?>
            <tr>
                <td align="center" nowrap style="background-color:;"><?=$costing_other->otherscode?></td>
                <td align="center" style="background-color:;"><?=$costing_other->othersdesc?></td>
                <td align="center" style="background-color:;"></td>
                <?php 
                if ($costing_other->jenis_rate=="J")
                { $px_idr=$costing_other->price * $rate_jual; 
                  $px_usd=round($costing_other->price,4);
                }
                else
                { $px_idr=$costing_other->price; 
                  $px_usd=round($costing_other->price / $rate_beli,4);
                }
                $allowcs_usd = ($px_usd*1) * ($costing_other->allowance/100);
                $allowcs_idr = ($px_idr*1) * ($costing_other->allowance/100);
                $valcs_usd = ($px_usd * 1) + $allowcs_usd;
                $valcs_idr = ($px_idr * 1) + $allowcs_idr;
                if ($total_cost==0)
                {   $persen=0;  }
                else
                {   $persen = ($valcs_usd / $total_cost) * 100; }
                ?>
                <td align="center" style="background-color:;"><?php echo fn($px_idr,2); ?></td>
                <td align="center" style="background-color:;"><?php echo fn($px_usd,4); ?></td>
                <td align="center" style="background-color:;"><?=$costing_other->cons?></td>
                <td align="center" style="background-color:;"><?=$costing_other->unit?></td>
                <td align="center" style="border-right-color: #ff0000;background-color:;"><?=$costing_other->allowance?> %</td>
                <td align="center" style="border-right-color: #ff0000;background-color:;"><?php echo fn($valcs_idr,2); ?></td>
                <td align="center" style="background-color:;"><?php echo fn($valcs_usd,4); ?></td>
                <td align="center" style="background-color:;"><?php echo fn($persen,2)." %"; ?> %</td>
            </tr>
            <?php 
        $sum['Value_IDR_OTH'] += round($valcs_idr,2);
        $sum['Value_USD_OTH'] += round($valcs_usd,4);
        ?>
            <?php endforeach;?>
            <?php
                $totalcost_IDR += $sum['Value_IDR_OTH'];
                $totalcost_USD += $sum['Value_USD_OTH'];
            ?>
            <?php endif;?>
    <tr class='alt' >
        <td align="center" nowrap style="background-color:#ffffff;">TOTAL OTHER COST</td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="border-right-color: #ff0000;background-color:#ffffff;"></td>
        <td align="center" style="border-right-color: #ff0000;background-color:#ffffff;"><?php 
        echo fn($sum['Value_IDR_OTH'],2)?>
        <td align="center" style="background-color:#ffffff;"><?php 
        echo fn($sum['Value_USD_OTH'],4)?>
        </td>
        <td align="center" style="background-color:#ffffff;"></td>
    </tr>
    <tr class='alt' >
        <td align="center" nowrap style="background-color:#ffffff;">G & A</td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="background-color:#ffffff;"></td>
        <td align="center" style="border-right-color: #ff0000;background-color:#ffffff;"></td>
        <td align="center" style="border-right-color: #ff0000;background-color:#ffffff;"><?php echo fn($total_ga_cost_idr,2);?></td>
        <td align="center" style="background-color:#ffffff;"><?php echo fn($total_ga_cost,4);?></td>
        <td align="center" style="background-color:#ffffff;"></td>
    </tr>
<!-- TABLE TOTAL COST -->

    <tr>
        <th class="text-center" style="" colspan="7">TOTAL COST</th>
        <th class="text-center" style="" rowspan="">IDR / USD</th>
        <th class="text-center" style="" rowspan=""><?php echo fn($total_cost_idr,2);?></th>
        <th class="text-center" style="" rowspan=""><?php echo fn($total_cost,4);?></th>
        <th class="text-center" style="" rowspan="">&nbsp;</th>
    </tr>
    <tr class='alt' >
        <th class="text-center" style="" colspan="5"></th>
        <th class="text-center" style=" " >VAT</th>
        <th align="center" style=""><?php echo $vat; ?> %</th>
        <th align="center" style="">IDR / USD</th>
        <th align="center" style=""><?php echo fn($total_vat_idr,2);?></th>
        <th align="center" style=""><?php echo fn($total_vat,2);?></th>
        <th class="text-center" style="" rowspan="">&nbsp;</th>
    </tr>
    <tr class='alt' >
        <th align="left" colspan="6">DEAL ALLOWANCE</th>
        <th align="center" style=""><?php echo round($deal,4); ?> %</th>
        <th align="center" style="">IDR / USD</th>
        <th align="center" style=""><?php echo fn($total_deal_idr,2);?></th>
        <th align="center" style=""><?php echo fn($total_deal,2);?></th>
        <th class="text-center" style="" rowspan="">&nbsp;</th>
    </tr>
    <tr class='alt' >
        <th align="left" colspan="6">FOB</th>
        <th align="center" style=""></th>
        <th align="center" style="">IDR / USD</th>
        <!-- <th align="center" style=""><?php echo fn($total_cost_plus_idr,2);?></th>
        <th align="center" style=""><?php echo fn($total_cost_plus,2);?></th> -->
        <th align="center" style=""><?php echo fn($cfm_price_idr,2);?></th>
        <th align="center" style=""><?php echo fn($cfm_price_usd,2);?></th>
        <th class="text-center" style="" rowspan="">&nbsp;</th>
    </tr>
    <tr class='alt' >
        <th align="left" colspan="6">FOB x Planed Qty</th>
        <th align="center" style=""></th>
        <th align="center" style="">IDR / USD</th>
        <!-- <th align="center" style=""><?php echo fn(round($total_cost_plus_idr,2)*$rsh['qty'],2);?></th>
        <th align="center" style=""><?php echo fn(round($total_cost_plus,2)*$rsh['qty'],2);?></th> -->
        <th align="center" style=""><?php echo fn(round($cfm_price_idr,2)*$rsh['qty'],2);?></th>
        <th align="center" style=""><?php echo fn(round($cfm_price_usd,2)*$rsh['qty'],2);?></th>
        <th class="text-center" style="" rowspan="">&nbsp;</th>
    </tr>
</table>
<table>
    <tr>
        <td>Created By</td>
        <td><?php echo $rsh['fullname']." - ".fd_view_dt($rsh['dateinput']); ?></td>
    </tr>
    <tr>
        <td>Approved By</td>
        <td><?php echo $rsh['app1_by_name']." - ".fd_view_dt($rsh['app1_date']); ?></td>
    </tr>
</table>
</body>
</html>
<?php
// Store output into vars
$html = ob_get_clean();

exit($html);

// Convert output into pdf
include("../../mpdf57/mpdf.php");
$mpdf=new mPDF();
$mpdf->WriteHTML($html);
$mpdf->Output();
?>