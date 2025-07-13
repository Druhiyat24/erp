<?php
include_once './connect.php';


$PONO = $_GET["pono"];
$sqlcompany = "select company, alamat1, kec, kota from mastercompany";
$sqldatatable = 'select po.podate, po.price, po.qty, po.qty * po.price as totalin, 
                masteritem.itemdesc 
                from po INNER JOIN masteritem ON po.id_item = masteritem.id_item
                WHERE po.pono = "'.$PONO.'"';
$sqldataAlamatSP = 'select mastersupplier.supplier, 
                mastersupplier.alamat, mastersupplier.fax, mastersupplier.phone
                from po INNER JOIN mastersupplier ON
                po.id_supplier = mastersupplier.id_supplier
                WHERE po.pono = "'.$PONO.'"';
$shipper = $conn->query($sqlcompany);
$result = $conn->query($sqldatatable);
$supps = $conn->query($sqldataAlamatSP);

$alamat ='';
$company='';
$kec='';
$kota='';

$alamatsp = '';
$supplier = '';
$fax = '';
$phone = '';

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
}

$jumlah = 0;
$quanty = 0;

?>
<!DOCTYPE html>
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
<body style=" padding-left:5%; padding-right:5%;">
    <table style="width:100%;  border-collapse: collapse;" >
        <tr style="">
        <td style="border-bottom:2px solid black; width:50% border-collapse: collapse;"> <img src="./img.png" width="100px" height="100px"> </td>
        <td style="border-bottom:2px solid black; width:50%; border-collapse: collapse; vertical-align:bottom;" ><p style="color:black; font-weight:bold; font-size:20px; text-align:right; text-transform:uppercase;">
            <?php
                echo $company;
            ?></p>
        </td>
        </tr>
        <tr>
        <td style="width:60%; border-collapse: collapse;">
            <p style="color:black; font-weight:bold; font-size:16px; padding-top:5px;">
            Shipping address:<br/>
            <?php
            echo $company."<br/>"
                .$alamat." ".$kec."<br/>"
                .$kota." 40376<br/>
                Indonesi<br\>
                022-85962076, 022-85962081
                ";
            ?>
            </p>
        </td>
        <td style="width:40%; border-collapse: collapse;">
            <p style="color:black; font-weight:bold; font-size:16px; padding-top:5px;">
            <?
            echo $supplier.'<br/>'
                .$alamatsp.'<br/>'
                .$fax.'<br/>'
                .$phone;
            ?>
        </p>
        </td>
        </tr>
    </table>
    <p style="font-size:30px">Purchase Order Confirmation <? echo $PONO ?></p>

    <table style="width:60%">
        <tr>
            <td style=" "><p style="font-weight:bold;">Our Order Reference:</p></td>
            <td style=" "><p style="font-weight:bold;">Order Date:</p></td>            
            <td style=" "><p style="font-weight:bold;">Validate By:</p></td>
        </tr>
        <tr>
            <td style=" "><? echo $PONO ?></td>
            <td style=" ">06/01/2018 12:25:42</td>            
            <td style=" ">Sun</td>
        </tr>
    </table>
    <table style="width:100%; margin-top:25px; border-collapse: collapse;">
    <?php 
    if (mysqli_num_rows($result)) {
          echo
            '<tr>
                <td style="border-collapse: collapse; border-bottom:solid 2px black; width:35%"><p style="font-weight:bold; ">Description</p></td>
                <td style="border-collapse: collapse; border-bottom:solid 2px black; width:15%"><p style="font-weight:bold; text-align:center;">Taxes</p></td>
                <td style="border-collapse: collapse; border-bottom:solid 2px black; width:15%"><p style="font-weight:bold; text-align:center;">Date Req.</p></td>
                <td style="border-collapse: collapse; border-bottom:solid 2px black; width:8% "colspan="2"><p style="font-weight:bold; text-align:center;">Qty</p></td>
                <td style="border-collapse: collapse; border-bottom:solid 2px black; width:12%"><p style="font-weight:bold; text-align:center;">Unit Price</p></td>
                <td style="border-collapse: collapse; border-bottom:solid 2px black; width:15%"><p style="font-weight:bold; text-align:center;">Net Price</p></td>
            </tr>';
            while($row = $result->fetch_assoc()){
            echo
            '<tr>
                <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:35%">'.$row["itemdesc"].'</td>
                <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:15%">Purchase Tax 10.00%</td>
                <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:15%">'.$row["podate"].'</td>
                <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:4% ">'.$row["qty"].'</td>
                <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:4%">Unit</td>
                <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:12% "><p style="text-align:right;">'.$row["price"].'</p></td>
                <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:15% "><p style="text-align:right;">'.$row["totalin"].'</p></td>
            </tr>';
               $jumlah += $row["totalin"];
               $quanty += $row["qty"]; 
        }
        }else{
            echo 'results 0';
        }
            $conn->close();
            ?>
            <tr>
                <td style="padding:5px; width:35%"></td>
                <td style="padding:5px; width:15%"></td>
                <td style="padding:5px; width:15%"></td>
                <td style="padding:5px; width:4% "><p style="font-weight:bold"><?php echo $quanty;?></p></td>
                <td style="padding:5px; width:4%"></td>
                <td style="padding:5px; width:12% "></p></td>
                <td style="padding:5px; width:15% "></p></td>
            </tr>
    </table>
    <table align="right" style="width:50%; margin-top:15px; border-collapse: collapse;">
            <tr>
                <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; border-top:solid 2px black; padding:5px;"><p style="font-weight:bold">Total Without Taxes</p></td>
                <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; border-top:solid 2px black; padding:5px;"><p style="text-align:right;"><?php echo $jumlah; 
                                    $bunga=($jumlah*0.01);?></p></td>
            </tr>
            <tr>
                <td style="border-collapse: collapse; border-bottom:solid 2px black; padding:5px;"><p style="font-weight:bold">Taxes</p></td>
                <td style="border-collapse: collapse; border-bottom:solid 2px black; padding:5px;"><p style="text-align:right;"><? echo $bunga;
                $nambah = $jumlah + $bunga;
                ?></p></td>
            </tr>
            <tr>
                <td style="padding:5px;"><p style="font-weight:bold">Total</p></td>
                <td style="padding:5px;"><p style="text-align:right; font-size:18px; font-weight:bold"><? echo $nambah;?></p></td>
            </tr>
    </table>
    <div style="width:100%; margin-top:200px;">
    <p>MESIN CUTTING MANUAL</p>
    <p>HRG $920</p>
    <p>PEMBYR 4 KALI</p>
    </div>
</body>
</html>