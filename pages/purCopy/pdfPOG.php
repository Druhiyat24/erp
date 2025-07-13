<!DOCTYPE html>
<?php
include_once '../../include/conn.php';
include_once '../forms/fungsi.php';

$images = '../../include/img-01.png';

//$PONO = flookup("pono","po_header","id='$_GET[id]'");
$rs=mysqli_fetch_assoc(mysqli_query($con_new,"select po.jenis status,po.pono,'' kpno,
  po.notes,count(distinct poit.id_jo) totjo,up.fullname  
  from po_header po inner join po_item poit on po.id=poit.id_po 
  inner join reqnon_header rnh on poit.id_jo=rnh.id 
  left join userpassword up on po.username=up.username  
  where po.id='$_GET[id]' and poit.cancel='N' group by po.pono"));
$PONO = $rs['pono'];

if ($rs['totjo']>1)
{   $wsno = "(Combine)";
    $wsdet = "";
    $sql=mysqli_query($con_new, "select distinct ac.kpno from po_header po inner join po_item poit 
      on po.id=poit.id_po inner join jo_det jod on poit.id_jo=jod.id_jo inner join so on jod.id_so=so.id inner join 
      act_costing ac on ac.id=so.id_cost  
      where po.id='$_GET[id]' and poit.cancel='N' ");
    while($data = mysqli_fetch_assoc($sql))
    { if ($wsdet=="") {$wsdet=$data['kpno'];} else {$wsdet=$wsdet.", ".$data['kpno'];} }
}
else
{ $wsno = $rs['kpno'];  
  $wsdet = $wsno;
}
$notes = $rs['notes'];
$createby = $rs['fullname'];
$status = $rs['status'];
$sqlcompany = "select company, alamat1, kec, kota, kodepos, telp, fax from mastercompany";
$space=" ";
if ($status=="P")
{ $sqldatatable = 'select masteritem.color,masteritem.size,po_header.pph,po_header.app_by,po_header.tax,po_header.podate, po_item.price, 
    sum(po_item.qty) qty, 
  sum(po_item.qty * po_item.price)as totalin, po_item.curr, po_item.unit,
  concat(masteritem.itemdesc,"'.$space.'",masteritem.color,"'.$space.'",masteritem.size) itemdesc,po_header.eta from po_header INNER JOIN po_item 
  on po_header.id=po_item.id_po inner join 
  masteritem ON po_item.id_gen = masteritem.id_item
  WHERE po_header.pono = "'.$PONO.'" and po_item.cancel="N" group by masteritem.id_item';
}
else
{ $sql_join="masteritem.id_item";
  $sqldatatable = 'select masteritem.color,masteritem.size,po_header.pph,po_header.app_by,po_header.tax,po_header.podate, po_item.price, 
    sum(po_item.qty) qty, 
    sum(po_item.qty * po_item.price)as totalin, po_item.curr, po_item.unit,
    concat(masteritem.itemdesc,"'.$space.'",masteritem.color,"'.$space.'",masteritem.size) itemdesc,
    po_header.eta from po_header INNER JOIN po_item 
    on po_header.id=po_item.id_po inner join 
    masteritem ON po_item.id_gen = '.$sql_join.' 
    WHERE po_header.pono = "'.$PONO.'" and po_item.cancel="N" group by '.$sql_join.'';
  #echo $sqldatatable;
}
$sqldataAlamatSP = 'select mastersupplier.supplier, 
                mastersupplier.alamat, mastersupplier.fax, mastersupplier.phone,
                mastersupplier.product_name, mastersupplier.terms_of_pay
                from po_header po INNER JOIN mastersupplier ON
                po.id_supplier = mastersupplier.id_supplier
                WHERE po.pono = "'.$PONO.'"';
$shipper = $con_new->query($sqlcompany);
$result = $con_new->query($sqldatatable);
$supps = $con_new->query($sqldataAlamatSP);

$alamat ='';
$company='';
$kec='';
$kota='';

$alamatsp = '';
$supplier = '';
$fax = '';
$phone = '';
$namaproduk='';
#$notes='';
$termofpay='';
$cicil='';

$jumlah = 0;
$quanty = 0;
$currency='';
$tanggal = '';
$valby = '';
while($row = $shipper->fetch_assoc()) {
    $company = $row["company"];
    $alamat= $row["alamat1"];
    $kec = $row["kec"];
    $kota = $row["kota"];
    $kodepos = $row["kodepos"];
    $telp = $row["telp"];
    $fax = $row["fax"];
}

while($row = $supps->fetch_assoc()) {
    $supplier = $row["supplier"];
    $alamatsp= $row["alamat"];
    $fax = $row["fax"];
    $phone = $row["phone"];
    $namaproduk=$row["product_name"];
    $cicil=$row["terms_of_pay"];
}

$tableatas= '';

if (mysqli_num_rows($result)) {
    while($row = $result->fetch_assoc()){
    $tableatas .='
    <tr>
        <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:34%">'
        .$row["itemdesc"].'</td>
        <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:14%">'.fd_view($row["eta"]).'</td>
        <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:4% ">'.fn($row["qty"],2).' '.$row["unit"].'</td>
        <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:4%">'.$row["curr"].'</td>
        <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:10% "><p style="text-align:right;">'.number_format($row["price"],2).'</p></td>
        <td nowrap style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:20% "><p style="text-align:right;">'.$row["curr"].' '.number_format($row["totalin"], 2).'</p></td>
    </tr>';
    $jumlah += $row["totalin"];
    $taxnya = $row["tax"];
    $pphnya = $row["pph"];
    $quanty += $row["qty"]; 
    $currency = $row["curr"];
    $tanggal = fd_view($row["podate"]);
    if ($row["app_by"]!="")
    { $valby=flookup("fullname","userpassword","username='".$row["app_by"]."'"); }
} 
}else{
    echo "results 0";
}
$con_new->close();

$bunga=($jumlah*$taxnya/100);
$jpph=($jumlah*$pphnya/100);
$nambah = $jumlah + $bunga + $jpph;
$tanggalecho = $tanggal;

$tanggalin = $tanggalecho;
$hari = date_format(new DateTime($tanggalecho), "D");
$html= '
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body{
            font-family:Helvetica;
        }

        .hairline{
        width:100%;
        background-color:#939aa5;
        height: 2px;
    }
    </style>
    <title>Purchase Order</title>
</head>
<body >
    <table style="width:100%;  border-collapse: collapse;" >
        <tr style="">
        <td style="border-bottom:2px solid black; width:50% border-collapse: collapse;"> <img src="'.$images.'" width="100px" height="70px"> </td>
        <td style="border-bottom:2px solid black; width:50%; border-collapse: collapse; vertical-align:bottom;" ><p style="color:black; font-weight:bold; font-size:18px; text-align:right; text-transform:uppercase;">
                 '.$company.'
            </p>
        </td>
        </tr>
        <tr>
        <td style="width:60%; border-collapse: collapse;">
            <p style="color:black; font-weight:bold; font-size:10px; padding-top:5px;">
            Shipping address:<br/>
                '.$company.'<br/>'
                .$alamat.' '.$kec.'<br/>'
                .$kota.' '.$kodepos.'<br/>
                Indonesia<br/>
                '.$telp.', '.$fax.'
            </p>
        </td>
        <td style="width:40%; border-collapse: collapse;">
            <p style="color:black; font-weight:bold; font-size:12px; padding-top:5px;">
            '.$supplier.'<br/>'
            .$alamatsp.'<br/>'
            .$fax.'<br/>'
            .$phone.'
        </p>
        </td>
        </tr>
    </table>
    <p style="font-size:14px">Purchase Order Confirmation '.$PONO.' '.$wsno.'</p>

    <table style="width:70%;font-size:12px;">
        <tr>
            <td style="width:40%"><p style="font-weight:bold;">Our Order Reference:</p></td>
            <td style="width:35% "><p style="font-weight:bold;">Order Date:</p></td>            
            <td style="width:25% "><p style="font-weight:bold;">Validate By:</p></td>
        </tr>
        <tr>
            <td style=" ">'.$PONO.'</td>
            <td style=" ">'.$tanggalin.'</td>            
            <td style=" ">'.$valby.'</td>
        </tr>
    </table>
    <table style="width:100%; margin-top:25px; border-collapse: collapse;font-size:11px;">
          <tr>
              <td style="border-collapse: collapse; border-bottom:solid 2px black; width:35%"><p style="font-weight:bold; ">Description</p></td>
              <td style="border-collapse: collapse; border-bottom:solid 2px black; width:15%"><p style="font-weight:bold; text-align:center;">Date Req.</p></td>
              <td style="border-collapse: collapse; border-bottom:solid 2px black; width:8% "colspan="2"><p style="font-weight:bold; text-align:center;">Qty</p></td>
              <td style="border-collapse: collapse; border-bottom:solid 2px black; width:12%"><p style="font-weight:bold; text-align:center;">Unit Price</p></td>
              <td style="border-collapse: collapse; border-bottom:solid 2px black; width:15%"><p style="font-weight:bold; text-align:center;">Net Price</p></td>
          </tr>
            '.$tableatas.'
            <tr>
                <td style="padding:5px; width:35%"></td>
                <td style="padding:5px; width:15%"></td>
                <td style="padding:5px; width:15%"><p style="font-weight:bold">'.number_format($quanty,2).'</p></td>
                <td style="padding:5px; width:4% "></td>
                <td style="padding:5px; width:4%"></td>
                <td style="padding:5px; width:12% "></p></td>
            </tr>
    </table>
    <table align="right" style="width:50%; margin-top:15px; border-collapse: collapse;font-size:12px;">
            <tr>
                <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; border-top:solid 2px black; padding:5px;">
                  <p style="font-weight:bold">Total Without PPN</p>
                </td>
                <td style="text-align:right;border-collapse: collapse; border-bottom:solid 2px #f5f5f5; border-top:solid 2px black; padding:5px;">
                  <p style="text-align:right;">'.$currency.' '.number_format($jumlah,2).'</p>
                </td>
            </tr>
            <tr>
                <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; border-top:solid 2px black; padding:5px;">
                  <p style="font-weight:bold">PPh '.$pphnya.' %</p>
                </td>
                <td style="text-align:right;border-collapse: collapse; border-bottom:solid 2px #f5f5f5; border-top:solid 2px black; padding:5px;">
                  <p style="text-align:right;">'.$currency.' '.fn($jpph,2).'</p>
                </td>
            </tr>
            <tr>
                <td style="border-collapse: collapse; border-bottom:solid 2px black; padding:5px;"><p style="font-weight:bold">PPN</p></td>
                <td style="text-align:right;border-collapse: collapse; border-bottom:solid 2px black; padding:5px;">
                  <p style="text-align:right;">'.$currency.' '.number_format($bunga,2).'</p>
                </td>
            </tr>
            <tr>
                <td style="padding:5px;"><p style="font-weight:bold">Total</p></td>
                <td style="padding:5px;"><p style="text-align:right; font-size:18px; font-weight:bold">'.$currency.' '.number_format($nambah,2).'</p></td>
            </tr>
    </table>
    <div style="width:100%; margin-top:20px;">
    <p>Notes '.$notes.'</p>
    <p>'.$termofpay.'</p>
    <p>Created By : '.$createby.'</p>
    </div>'
?>
<?php
include("../../mpdf57/mpdf.php");

$mpdf=new mPDF(); 

$mpdf->WriteHTML($html);
$mpdf->Output();
exit;
?>