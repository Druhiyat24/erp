<!DOCTYPE html>
<?php
include_once '../../include/conn.php';
include_once '../forms/fungsi.php';

$images = '../../include/img-01.png';

$PONO = flookup("pono","po_header","id='$_GET[id]'");
$sqlcompany = "select company, alamat1, kec, kota from mastercompany";
$sqldatatable = 'select po_header.podate, po_item.price, po_item.qty, 
  po_item.qty * po_item.price as totalin, po_item.curr, po_item.unit,
  masteritem.itemdesc from po_header INNER JOIN po_item 
  on po_header.id=po_item.id_po inner join 
  masteritem ON po_item.id_gen = masteritem.id_gen
  WHERE po_header.pono = "'.$PONO.'"';
$sqldataAlamatSP = 'select mastersupplier.supplier, 
                mastersupplier.alamat, mastersupplier.fax, mastersupplier.phone,
                mastersupplier.product_name, mastersupplier.terms_of_pay
                from po INNER JOIN mastersupplier ON
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
$notes='';
$termofpay='';
$cicil='';

$jumlah = 0;
$quanty = 0;
$currency='';
$tanggal = '';

while($row = $shipper->fetch_assoc()) {
    $company = $row["company"];
    $alamat= $row["alamat1"];
    $kec = $row["kec"];
    $kota = $row["kota"];
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
        <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:35%">'.$row["itemdesc"].'</td>
        <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:15%">Purchase Tax 10.00%</td>
        <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:15%">'.fd_view($row["podate"]).'</td>
        <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:4% ">'.number_format($row["qty"],2).'</td>
        <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:4%">'.$row["unit"].'</td>
        <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:12% "><p style="text-align:right;">'.$row["price"].'</p></td>
        <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:15% "><p style="text-align:right;">'.$row["curr"].' '.number_format($row["totalin"], 2).'</p></td>
    </tr>';
       $jumlah += $row["totalin"];
       $quanty += $row["qty"]; 
       $currency = $row["curr"];
       $tanggal = fd_view($row["podate"]);
}
}else{
    echo "results 0";
}
$con_new->close();

$bunga=($jumlah*0.1);
$nambah = $jumlah + $bunga;
$tanggalecho = $tanggal;

$tanggalin = $tanggalecho;
$hari = date_format($tanggalecho, "D");
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
            <p style="color:black; font-weight:bold; font-size:14px; padding-top:5px;">
            Shipping address:<br/>
                '.$company.'<br/>'
                .$alamat.' '.$kec.'<br/>'
                .$kota.' 40376<br/>
                Indonesia<br/>
                022-85962076, 022-85962081
            </p>
        </td>
        <td style="width:40%; border-collapse: collapse;">
            <p style="color:black; font-weight:bold; font-size:14px; padding-top:5px;">
            '.$supplier.'<br/>'
            .$alamatsp.'<br/>'
            .$fax.'<br/>'
            .$phone.'
        </p>
        </td>
        </tr>
    </table>
    <p style="font-size:20px">Purchase Order Confirmation '.$PONO.'</p>

    <table style="width:70%">
        <tr>
            <td style="width:40%"><p style="font-weight:bold;">Our Order Reference:</p></td>
            <td style="width:35% "><p style="font-weight:bold;">Order Date:</p></td>            
            <td style="width:25% "><p style="font-weight:bold;">Validate By:</p></td>
        </tr>
        <tr>
            <td style=" ">'.$PONO.'</td>
            <td style=" ">'.$tanggalin.'</td>            
            <td style=" ">'.$hari.'</td>
        </tr>
    </table>
    <table style="width:100%; margin-top:25px; border-collapse: collapse;">
          <tr>
              <td style="border-collapse: collapse; border-bottom:solid 2px black; width:35%"><p style="font-weight:bold; ">Description</p></td>
              <td style="border-collapse: collapse; border-bottom:solid 2px black; width:15%"><p style="font-weight:bold; text-align:center;">Taxes</p></td>
              <td style="border-collapse: collapse; border-bottom:solid 2px black; width:15%"><p style="font-weight:bold; text-align:center;">Date Req.</p></td>
              <td style="border-collapse: collapse; border-bottom:solid 2px black; width:8% "colspan="2"><p style="font-weight:bold; text-align:center;">Qty</p></td>
              <td style="border-collapse: collapse; border-bottom:solid 2px black; width:12%"><p style="font-weight:bold; text-align:center;">Unit Price</p></td>
              <td style="border-collapse: collapse; border-bottom:solid 2px black; width:15%"><p style="font-weight:bold; text-align:center;">Net Price</p></td>
          </tr>;
            '.$tableatas.'
            <tr>
                <td style="padding:5px; width:35%"></td>
                <td style="padding:5px; width:15%"></td>
                <td style="padding:5px; width:15%"></td>
                <td style="padding:5px; width:4% "><p style="font-weight:bold">'.number_format($quanty,2).'</p></td>
                <td style="padding:5px; width:4%"></td>
                <td style="padding:5px; width:12% "></p></td>
                <td style="padding:5px; width:15% "></p></td>
            </tr>
    </table>
    <table align="right" style="width:50%; margin-top:15px; border-collapse: collapse;">
            <tr>
                <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; border-top:solid 2px black; padding:5px;"><p style="font-weight:bold">Total Without Taxes</p></td>
                <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; border-top:solid 2px black; padding:5px;"><p style="text-align:right;">'.$currency.' '.number_format($jumlah,2).'</p></td>
            </tr>
            <tr>
                <td style="border-collapse: collapse; border-bottom:solid 2px black; padding:5px;"><p style="font-weight:bold">Taxes</p></td>
                <td style="border-collapse: collapse; border-bottom:solid 2px black; padding:5px;"><p style="text-align:right;">'.$currency.' '.number_format($bunga,2).'</p></td>
            </tr>
            <tr>
                <td style="padding:5px;"><p style="font-weight:bold">Total</p></td>
                <td style="padding:5px;"><p style="text-align:right; font-size:18px; font-weight:bold">'.$currency.' '.number_format($nambah,2).'</p></td>
            </tr>
    </table>
    <div style="width:100%; margin-top:20px;">
    <p>'.$namaproduk.'</p>
    <p>'.$notes.'</p>
    <p>'.$termofpay.'</p>
    </div>'
?>

<?php
include("../../mpdf57/mpdf.php");

$mpdf=new mPDF(); 

$mpdf->WriteHTML($html);
$mpdf->Output();
exit;
?>