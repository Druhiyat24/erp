<!DOCTYPE html>
<?php
include_once '../../include/conn.php';
include_once '../forms/fungsi.php';

$images = '../../include/img-01.png';
// $SONO = "SO/1118/00002";

$rsso=mysql_fetch_array(mysql_query("select * from so where id='$_GET[id]'"));
    $SONO = $rsso["so_no"];
    $SONORev = $SONO." Rev ".$rsso["revise"];
$sqlcompany = "select company, alamat1, kec, kota from mastercompany";
$sqlSo = "select buyerno, so_date, qty, unit, fob from so where so_no ='".$SONO."'";

$sqlFinalSO =  "SELECT supplier,act_costing.username,so.buyerno, so.so_date, so.fob, 
so_det.dest, so_det.color, so_det.size, so_det.qty, so_det.unit, 
act_costing.deldate, act_costing.curr, masterproduct.product_item, masterproduct.product_group, 
so_det.price price, act_costing.styleno ,mastersupplier.Phone, mastersupplier.Attn, mastersupplier.alamat, mastersupplier.zip_code,
so_det.price * so_det.qty AS hasilkali,so.tax 
from 
so_det INNER JOIN so ON so_det.id_so = so.id
INNER JOIN act_costing ON so.id_cost = act_costing.id
INNER JOIN masterproduct ON act_costing.id_product = masterproduct.id
INNER JOIN mastersupplier on act_costing.id_buyer = mastersupplier.Id_Supplier
WHERE so_det.cancel='N' and so.so_no = '".$SONO."'";

$shipper = $con_new->query($sqlcompany);
$hasil2 = $con_new->query($sqlFinalSO);

$alamat ='';
$company='';
$kec='';
$kota='';

while($row = $shipper->fetch_assoc()) {
    $company = $row["company"];
    $alamat= $row["alamat1"];
    $kec = $row["kec"];
    $kota = $row["kota"];
};

$tqty = 0;
$totaled = 0;
$taxnya = 0;
$typeAddr = '';
$addrShipp = '';
$zip_code = '';
$destination = '';
$telephone = '';
$buyerno='';
$tanggalmasuk ='';
$tanggalkeluar ='';
$Snama ='';
$curr = '';
$fob = '';
$styleno = '';

$isitable = '';

if (mysqli_num_rows($hasil2)) {
    while($row = $hasil2->fetch_assoc()){
    $taxnya = $row["tax"];
    $isitable .='<tr>
        <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:25%"><p style="text-align:left;">'.$row["product_item"].' '.$row["styleno"].' ('.$row["color"].','.$row["size"].')</p></td>
        <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:25%"><p style="text-align:left;">'.$row["product_item"].' '.$row["styleno"].' ('.$row["color"].','.$row["size"].')</p></td>
        <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:10%"><p style="text-align:right;">'.$taxnya.' %</p></td>
        <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:15%"><p style="text-align:left; padding-left:15px;">'.number_format($row["qty"], 2).' '.$row["unit"].'</p></td>
        <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:10%"><p style="text-align:right;">'.number_format($row["price"],2).'</p></td>
        <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:15% "><p style="text-align:right;">'.$row["curr"].' '.number_format($row["hasilkali"],2).'</p></td>
    </tr>';
    $typeAddr = $row["supplier"];
    $addrShipp = $row["alamat"];
    $zip_code = $row["zip_code"];
    $destination = $row["dest"];
    $telephone = $row["Phone"];
    $styleno=$row["styleno"];
    $buyerno = $row["buyerno"];
    $tanggalmasuk = $row["so_date"];
    $tanggalkeluar = $row["deldate"];
    #$Snama = $row["Attn"];
    $Snama = $row["username"];
    $curr = $row["curr"];
    $fob = $row["fob"];
    $tqty += $row["qty"];
    $totaled += $row["hasilkali"];
}
}else{
    echo "results 0";
}

$totaled_tax = ($totaled * $taxnya/100);
$tanggalecho1 = date_create($tanggalmasuk);
$tanggalecho2 = date_create($tanggalkeluar);
$tanggalin = date_format($tanggalecho1,"Y/m/d");
$tanggalin2 =date_format($tanggalecho2,"Y/m/d");
$con_new->close();



$html = '
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sales Order</title>
    <style>
        body{
            font-family:Helvetica;
        }
    </style>
</head>
<body style=" padding-left:5%; padding-right:5%;">
<table style="width:100%;  border-collapse: collapse;" >
        <tr style="">
        <td style="border-bottom:2px solid black; width:50% border-collapse: collapse;"> <img src="'.$images.'" width="100px" height="100px"> </td>
        <td style="border-bottom:2px solid black; width:50%; border-collapse: collapse; vertical-align:bottom;" ><p style="color:black; font-weight:bold; font-size:18px; text-align:right; text-transform:uppercase;">'.$company.'</p>
        </td>
        </tr>
        <tr>
        <td style="width:60%; border-collapse: collapse;">
            <p style="color:black; font-weight:bold; font-size:16px; padding-top:5px;">
            Invoice and shipping address:<br/>
            '.$typeAddr.'<br/>
            '.$addrShipp.' '.$zip_code.'<br/>
            '.$destination.'<br/>
            
            <p style="margin-top:15px; margin-bottom:15px; color:black; font-weight:bold; font-size:16px; ">'.$telephone.'</p>
            </p>
        </td>
        <td style="width:40%; border-collapse: collapse;">
        </td>
        </tr>
    </table>
    <p style="font-size:30px">Sales Order '.$SONORev.'</p>
    <table style="width:100%; padding-right:10%;">
    <tr>
            <td style=" "><p style="font-weight:bold;">Your Reference:</p></td>
            <td style=" "><p style="font-weight:bold;">Date Orderred:</p></td>            
            <td style=" "><p style="font-weight:bold;">Time of Delivery:</p></td>
            <td style=" "><p style="font-weight:bold;">SalesPerson:</p></td>
        </tr>
        <tr>
             <td style=" font-size:12px">'.$styleno.' '.$buyerno.'</td>
            <td style=" ">'.$tanggalin.'</td>            
            <td style=" ">'.$tanggalin2.'</td>
            <td>'.$Snama.'</td>
        </tr>
    </table>
    <table style="width:100%; margin-top:25px; border-collapse: collapse;">
        <tr>
            <td style="border-collapse: collapse; border-bottom:solid 2px black; width:25%"><p style="font-weight:bold; text-align:left;">Product</p></td>
            <td style="border-collapse: collapse; border-bottom:solid 2px black; width:25%"><p style="font-weight:bold; text-align:left;">Description</p></td>
            <td style="border-collapse: collapse; border-bottom:solid 2px black; width:10%"><p style="font-weight:bold; text-align:right;">Taxes</p></td>
            <td style="border-collapse: collapse; border-bottom:solid 2px black; width:15% "><p style="font-weight:bold; text-align:left; padding-left:15px;">Quantity</p></td>
            <td style="border-collapse: collapse; border-bottom:solid 2px black; width:10%"><p style="font-weight:bold; text-align:right;">Unit Price</p></td>
            <td style="border-collapse: collapse; border-bottom:solid 2px black; width:15%"><p style="font-weight:bold; text-align:right;">Price</p></td>
        </tr>
        '.$isitable.'
        <tr>
            <td style="border-collapse: collapse; padding:5px; width:25%"><p style="text-align:left;"></p></td>
            <td style="border-collapse: collapse; padding:5px; width:25%"><p style="text-align:left;"></p></td>
            <td style="border-collapse: collapse; padding:5px; width:10%"><p style="text-align:right;"></p></td>
            <td style="border-collapse: collapse; padding:5px; width:15%"><p style="font-weight:bold; text-align:left; padding-left:15px;">'.number_format($tqty, 2).'</p></td>
            <td style="border-collapse: collapse; padding:5px; width:10%"><p style="text-align:right;"></p></td>
            <td style="border-collapse: collapse; padding:5px; width:15% "><p style="text-align:right;"></p></td>
        </tr>
        </table>
        <table align="right" style="width:50%; margin-top:15px; border-collapse: collapse;">
            <tr>
                <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; border-top:solid 2px black; padding:5px;"><p style="font-weight:bold">Total Without Taxes</p></td>
                <td style="text-align:right;border-collapse: collapse; border-bottom:solid 2px #f5f5f5; border-top:solid 2px black; padding:5px;"><p style="text-align:right;">'.$curr.' '.fn($totaled,2).'</p></td>
            </tr>
            <tr>
                <td style="border-collapse: collapse; border-bottom:solid 2px black; padding:5px;"><p style="font-weight:bold">Taxes</p></td>
                <td style="text-align:right; border-collapse: collapse; border-bottom:solid 2px black; padding:5px;"><p style="text-align:right;">'.$curr.' '.fn($totaled_tax,2).'</p></td>
            </tr>
            <tr>
                <td style="padding:5px;"><p style="font-weight:bold">Total</p></td>
                <td style="padding:5px;"><p style="text-align:right; font-size:18px; font-weight:bold">'.$curr.' '.fn($totaled + $totaled_tax,2).'</p></td>
            </tr>
        </table>
    <div style="width:100%;">
    <p>'.$styleno.' '.$buyerno.'</p>
    <p>FOB: '.$curr.' '.number_format($fob, 2).'</p>
    </div>
    <table style="width:100%; border-collapse: collapse;">
        <tr>
            <td style="border-collapse: collapse; border:solid 1px black; border-top:solid 2px black; padding:5px; width:30%;" align="center"><p style="text-align:center;">Made By,</p></td>
            <td style="border-collapse: collapse; border:solid 1px black; border-top:solid 2px black; padding:5px; width:40%;" align="center" colspan="2" ><p style="text-align:center;"> Approved By, </p></td>
            <td style="border-collapse: collapse; border:solid 1px black; border-top:solid 2px black; padding:5px; width:30%;" align="center"><p style="text-align:center;"> Ackonwledge By, </p></td>
        </tr>
        <tr>
            <td style="border-collapse: collapse; border:solid 1px black; border-top:solid 2px black; padding:5px; width:30%; height:60px;"><p style="text-align:center;"></p></td>
            <td style="border-collapse: collapse; border:solid 1px black; border-top:solid 2px black; padding:5px; width:20%; height:60px;"><p style="text-align:center;"></p></td>
            <td style="border-collapse: collapse; border:solid 1px black; border-top:solid 2px black; padding:5px; width:20%; height:60px;"><p style="text-align:center;"></p></td>
            <td style="border-collapse: collapse; border:solid 1px black; border-top:solid 2px black; padding:5px; width:30%; height:60px;"><p style="text-align:center;"></p></td>
        </tr>
        <tr>
            <td style="border-collapse: collapse; border:solid 1px black; border-top:solid 2px black; padding:5px; width:30%;" align="center"><p style="text-align:center; font-weight:bold">'.$Snama.'</p></td>
            <td style="border-collapse: collapse; border:solid 1px black; border-top:solid 2px black; padding:5px; width:20%;" align="center"><p style="text-align:center;"></p></td>
            <td style="border-collapse: collapse; border:solid 1px black; border-top:solid 2px black; padding:5px; width:20%;" align="center"><p style="text-align:center;"></p></td>
            <td style="border-collapse: collapse; border:solid 1px black; border-top:solid 2px black; padding:5px; width:30%;" align="center"><p style="text-align:center;"></p></td>
        </tr>
        <tr>
            <td style="border-collapse: collapse; border:solid 1px black; border-top:solid 2px black; padding:5px; width:30%;" align="center"><p style="text-align:center; font-weight:bold">MD</p></td>
            <td style="border-collapse: collapse; border:solid 1px black; border-top:solid 2px black; padding:5px; width:20%;" align="center"><p style="text-align:center; font-weight:bold">GM</p></td>
            <td style="border-collapse: collapse; border:solid 1px black; border-top:solid 2px black; padding:5px; width:20%;" align="center"><p style="text-align:center; font-weight:bold">CMO</p></td>
            <td style="border-collapse: collapse; border:solid 1px black; border-top:solid 2px black; padding:5px; width:30%;" align="center"><p style="text-align:center; font-weight:bold">Accounting</p></td>
        </tr>
    </table>
</body>
</html>'
?>

<?php
include("../../mpdf57/mpdf.php");

$mpdf=new mPDF(); 

$mpdf->WriteHTML($html);
$mpdf->Output();
exit;
?>