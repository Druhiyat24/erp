<?php

include_once '../../include/conn.php';

include_once '../forms/fungsi.php';

$dateskrg=date('Ymd_His');

$tblbomjoit="bom_jo_item_".$dateskrg;

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

    public function get_report($jo_id,$tblbomjoit)

    {



        // TODO: get header information

        $sql_h = "

            select g.supplier buyer,d.buyerno po_no,'' agent,jo_date date_of_issue, a.username, a.app_by, a.app_date,

            group_concat(distinct concat(' ',kpno)) ws_no,h.product_item item,styleno style_no,sum(i.qty) qty,

            max(f.deldate) delivery_date 

            from jo a inner join jo_det s on a.id=s.id_jo inner join so d on s.id_so=d.id 

            inner join act_costing f on d.id_cost=f.id inner join mastersupplier g on f.id_buyer=g.Id_Supplier

            inner join masterproduct h on f.id_product=h.id

            inner join so_det i on s.id_so=i.id_so

            where a.id='$jo_id' and i.cancel='N'

            group by a.id";



        $header = $this

            ->query($sql_h)

            ->row();



        if(!count((array) $header)){

            return array(null, null);

        }

        $fld1="if(d.nama_type!='-',concat(d.nama_type,' '),'')";

        $fld2="if(e.nama_contents!='-',concat(e.nama_contents,' '),'')";

        $fld3="if(f.nama_width!='-',concat(f.nama_width,' '),'')";

        $fld4="if(g.nama_length!='-',concat(g.nama_length,' '),'')";

        $fld5="if(h.nama_weight!='-',concat(h.nama_weight,' '),'')";

        $fld6="if(i.nama_color!='-',concat(i.nama_color,' '),'')";

        $fld7="''";

        $fld8="if(j.nama_desc!='-',j.nama_desc,'')";

        $fld8a="if(j.nama_desc!='-',concat(j.nama_desc,' '),'')";

        $fld9="if(j.add_info!='-' or j.add_info!='',j.add_info,'')";

        $fld_item="if(nama_sub_group regexp 'BARCODE' or nama_sub_group regexp 'STICKER',

          concat($fld1,$fld2,$fld3,$fld4,$fld5,$fld6,$fld7,$fld8a,$fld9,l.sku,' ',l.barcode),

          concat($fld1,$fld2,$fld3,$fld4,$fld5,$fld6,$fld7,$fld8,$fld9))";

        $sql = "

            SELECT k.id,k.id_item,a.nama_group, s.nama_sub_group, k.color,k.size,

                $fld_item item,k.qty qty_gmt,k.cons,round(k.qty*k.cons,2) qty_bom,

                k.unit,msup.supplier,s.id idsubgroup,k.notes

            From $tblbomjoit k INNER JOIN so_det l on k.id_so_det=l.id 

            INNER JOIN masterdesc j on k.id_item=j.id

            INNER JOIN mastercolor i on i.id=j.id_color

            INNER JOIN masterweight h on h.id=i.id_weight

            INNER JOIN masterlength g on g.id=h.id_length

            INNER JOIN masterwidth f on f.id=g.id_width

            INNER JOIN mastercontents e on e.id=f.id_contents

            INNER JOIN mastertype2 d on d.id=e.id_type

            INNER JOIN mastersubgroup s on s.id=d.id_sub_group

            INNER JOIN mastergroup a on a.id=s.id_group

            left join mastersupplier msup on k.id_supplier=msup.id_supplier

            WHERE k.id_jo= $jo_id and k.status='M' and l.cancel='N' 

            union all 

            SELECT k.id,k.id_item,j.matclass nama_group,concat(j.matclass,' ',j.goods_code) nama_sub_group,

                k.color,k.size,

                j.itemdesc item,k.qty qty_gmt,k.cons,round(k.qty*k.cons,2) qty_bom,

                k.unit,msup.supplier,j.id_item idsubgroup,k.notes  

            From $tblbomjoit k INNER JOIN masteritem j on k.id_item=j.id_item

            left join mastersupplier msup on k.id_supplier=msup.id_supplier

            WHERE k.id_jo= $jo_id and k.status='P'

            ORDER BY nama_group,color,size

        ";

        #echo $sql;

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

                    'supplier' => $r->supplier,

                    'qty_org' => $r->qty_gmt,

                    'cons' => $r->cons,

                    'idsubgroup' => $r->idsubgroup,

                    'qty_req' => $r->qty_bom,

                    'unit' => $r->unit,

                    'loss' => Config::$loss,

                    'loss_value' => $r->qty_bom * Config::$loss,

                    'tot_qty_req' => $r->qty_bom + ($r->qty_bom * Config::$loss),

                    'notesnya' => $r->notes,

                    'username' => $r->username,

                );



                if(!isset($items[$r->nama_group])){

                    if(!isset($items[$r->nama_group][$r->nama_sub_group])){

                        $items[$r->nama_group][$r->nama_sub_group] = array(

                                'list' => array(),

                                'tot_qty_req' => 0,

                                'loss_value' => 0

                        );

                    }

                }

                $items[$r->nama_group][$r->nama_sub_group]['list'][] = $item;

                @$items[$r->nama_group][$r->nama_sub_group]['tot_qty_req'] += $item['tot_qty_req'];

                @$items[$r->nama_group][$r->nama_sub_group]['loss_value'] += $item['loss_value'];

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

$cekerr=flookup("posno","(select posno,count(distinct cons) jcons from bom_jo_item where 

    id_jo='$id' and rule_bom='ALL COLOR ALL SIZE' and cancel='N' 

    group by posno) tmpjo","jcons>1");

if($cekerr!=''){

    exit('Ada Kesalahan Di PNo '.$cekerr);

}

$sql="drop table if exists $tblbomjoit";

insert_log($sql,'TempTable');

$sql="create table $tblbomjoit 

  select a.*,l.color,l.size,l.qty,l.sku,l.barcode,l.id_so 

  from bom_jo_item a INNER JOIN so_det l on a.id_so_det=l.id 

  where a.id_jo='$id' and a.cancel='N' and l.cancel='N'";

insert_log($sql,'TempTable');

insert_log("delete from $tblbomjoit",'TempTable');

$sql="select posno,rule_bom,id_item,nama_type 

  from bom_jo_item a inner join masterdesc md on a.id_item=md.id 

  inner join mastercolor mc on md.id_color=mc.id

  inner join masterweight mw on mc.id_weight=mw.id 

  inner join masterlength ml on mw.id_length=ml.id 

  inner join masterwidth mwi on ml.id_width=mwi.id 

  inner join mastercontents mco on mwi.id_contents=mco.id 

  inner join mastertype2 mt on mco.id_type=mt.id 

  where id_jo='$id' and a.cancel='N' and a.status='M'

  group by posno,rule_bom,id_item";

$rs1=mysql_query($sql);

while($row1 = mysql_fetch_array($rs1))

{ if ($row1['rule_bom']=="ALL COLOR ALL SIZE")

  { $fldcol="'All Color'";

    $fldsiz="'All Size'";

    if($row1['nama_type']=="SKU")

    { $fldgrp=" group by l.sku,a.status,a.id_item "; }

    else

    { $fldgrp=" group by a.status,a.id_item "; }

  }

  else if ($row1['rule_bom']=="ALL COLOR RANGE SIZE")

  { $fldcol="concat('All Color - ',l.size)";

    $fldsiz="l.size";

    $fldgrp=" group by a.status,a.id_item,l.size";

  }

  else if ($row1['rule_bom']=="PER COLOR ALL SIZE")

  { $fldcol="l.color";

    $fldsiz="'All Size'";

    $fldgrp=" group by a.status,a.id_item,l.color";

  }

  else 

  { #PER COLOR RANGE SIZE

    $fldcol="l.color";

    $fldsiz="l.size";

    $fldgrp=" group by a.status,a.id_item,l.color,l.size";

  }

  if ($row1['posno']==null) {$posno=" (posno is null or posno='')";} else {$posno=" posno='$row1[posno]'";}

  $sql="insert into $tblbomjoit 

    select a.*,$fldcol,$fldsiz,sum(l.qty),l.sku,l.barcode,l.id_so 

    from bom_jo_item a INNER JOIN so_det l on a.id_so_det=l.id 

    where a.id_jo='$id' and a.cancel='N' and l.cancel='N' and a.status='M' and a.id_item='$row1[id_item]' and $posno $fldgrp ";

  insert_log($sql,'TempTable');

  #echo "<br><br>".$sql."<br><br>";

}

$sql_pro="select posno,if(rule_bom='','ALL COLOR ALL SIZE',rule_bom) rule_bom,md.id_item,md.matclass nama_type  

  from bom_jo_item a inner join masteritem md on a.id_item=md.id_item  

  where id_jo='$id' and a.cancel='N' and a.status='P'

  group by posno,rule_bom,id_item";

$rs1_pro=mysql_query($sql_pro);

while($row1_pro = mysql_fetch_array($rs1_pro))

{ if ($row1_pro['rule_bom']=="ALL COLOR ALL SIZE")

  { $fldcol="'All Color'";

    $fldsiz="'All Size'";

    if($row1_pro['nama_type']=="SKU")

    { $fldgrp=" group by l.sku,a.status,a.id_item "; }

    else

    { $fldgrp=" group by a.status,a.id_item "; }

  }

  else if ($row1_pro['rule_bom']=="ALL COLOR RANGE SIZE")

  { $fldcol="'All Color'";

    $fldsiz="l.size";

    $fldgrp=" group by a.status,a.id_item,l.size";

  }

  else if ($row1_pro['rule_bom']=="PER COLOR ALL SIZE")

  { $fldcol="l.color";

    $fldsiz="'All Size'";

    $fldgrp=" group by a.status,a.id_item,l.color";

  }

  else 

  { #PER COLOR RANGE SIZE

    $fldcol="l.color";

    $fldsiz="l.size";

    $fldgrp=" group by a.status,a.id_item,l.color,l.size";

  }

  if ($row1_pro['posno']==null) {$posno=" (posno is null or posno='')";} else {$posno=" posno='$row1_pro[posno]'";}

  $sql_pro="insert into $tblbomjoit 

    select a.*,$fldcol,$fldsiz,sum(l.qty),l.sku,l.barcode,l.id_so 

    from bom_jo_item a INNER JOIN so_det l on a.id_so_det=l.id 

    where a.id_jo='$id' and a.cancel='N' and l.cancel='N' and a.id_item='$row1_pro[id_item]' 

    and a.status='P' and $posno $fldgrp ";

  insert_log($sql_pro,'TempTable');

  #echo "<br><br>".$sql_pro."<br><br>";

}

// Instantiate model object

$m = new Model($con_new);



// Get report data, assign into job order (header) and sales order(detail) vars

list($h, $d) = $m->get_report($id,$tblbomjoit);



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

    <title>PR</title>

</head>

<body>

<table class="table" style="border-bottom: 2px solid #000000; margin-bottom:5px;">

    <tr>

        <td><img src="<?=Assets::$logo?>" width="100px" height="70px"> </td>

        <td style="text-align: right;vertical-align: bottom;font-size: 16px;"><?=strtoupper($company->company)?></td>

    </tr>

</table>

<h3>Purchase Request</h3>

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

                    <td><?=$h->date_of_issue ? date('d F Y - h:m:s', strtotime($h->date_of_issue)) : '';?></td>

                </tr>

                <tr>

                    <th>CREATED BY</th>

                    <td><?=$h->username;?></td>

                </tr>

            </table>

        </td>

        <td>&nbsp;</td>

        <td style="vertical-align: top">

            <table class="table table-bordered table-condensed">

                <tr>

                    <th>NO WS</th>

                    <td><?=$h->ws_no;?></td>

                </tr>

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

            <th rowspan="2">SUPPLIER</th>

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

                <th colspan="13"><?=$_g?></th>

            </tr>

            <?php $gab_notes=""; ?>

            <?php foreach($_d as $_sg => $__d):?>

                <?php $tot_pr=0; $tot_val=0; foreach($__d->list as $l):?>

                    <tr>

                        <td> </td>

                        <td><?=$_sg?></td>

                        <td><?=$l->item?></td>

                        <td><?=$l->color?></td>

                        <td><?=$l->supplier?></td>

                        <td class="text-right"><?=number_format($l->qty_org)?></td>

                        <td><?=$l->cons?></td>

                        <td><?=$l->unit?></td>

                        <td class="text-right"><?=number_format($l->qty_req)?></td>

                        <?php

                        $qty_bomnya=$l->qty_req; 

                        $allow=flookup("allowance","masterallow","id_sub_group='$l->idsubgroup'

                          and qty1<=$qty_bomnya and qty2>=$qty_bomnya");

                        if ($allow==null) { $allow=0; }

                        $allow_val=$qty_bomnya*$allow/100;

                        $tot_pr=$tot_pr + ($qty_bomnya+$allow_val); 

                        $tot_val=$tot_val + $allow_val; 

                        $notes_ori=$l->notesnya;

                        $gab_notes=$gab_notes." ".$notes_ori;

                        ?>

                        <td><?=$allow?>%</td>

                        <td class="text-right"><?=number_format($allow_val)?></td>

                        <td class="text-right"><?=number_format($l->qty_req+$allow_val)?></td>

                        <td><?=$l->unit?></td>

                    </tr>

                    <tr>

                        <td colspan="13">Notes : <?php echo $notes_ori;?></td>

                    </tr>

                <?php endforeach;?>

                <!-- <tr>

                    <td colspan="13">Notes : <?php echo $gab_notes;?></td>

                </tr> -->

                <tr>

                    <td colspan="9">PO No.</td>

                    <td>TOTAL</td>

                    <td class="text-right"><?=number_format($tot_val);?></td>

                    <td class="text-right"><?=number_format($tot_pr);?></td>

                    <td><?=$__d->tot_qty_req_unit;?></td>

                </tr>

                <tr>

                    <td colspan="12">&nbsp;</td>

                </tr>

            <?php endforeach;?>

        <?php endforeach;?>

        </tbody>

    </table>
<br><br>
    <table class="table" align="center">

        <tr>

            <td style="height: 100px"></td>

            <td>&nbsp;</td>

            <td>&nbsp;</td>

            <td>&nbsp;</td>

        </tr>

        <tr>

            <td class="text-center">Requested By,</td>

            <td class="text-center">Acknowledged By,</td>

            <td class="text-center">Approved By,</td>

            <td class="text-center">Approved At,</td>

        </tr>

         <tr>

            <td class="text-center"><b><?=$h->username;?><b></td>

            <td class="text-center">&nbsp;</td>

            <td class="text-center"><b><?=$h->app_by;?><b></td>

            <td class="text-center"><b><?=$h->app_date;?><b></td>

        </tr>


    </table>

<?php endif;?>

</body>

</html>

<?php

$html = ob_get_clean();

include("../../mpdf57/mpdf.php");

$mpdf=new mPDF();

$mpdf->WriteHTML($html);

$mpdf->Output();

$sql="drop table if exists $tblbomjoit";

insert_log($sql,'TempTable');

?>