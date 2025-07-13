<?php
include '../../conn/conn.php';
ini_set('date.timezone', 'Asia/Jakarta');

$id_bppb = $_POST['id_bppb'];
$so_number = $_POST['so_number'];
$bppb_number = $_POST['bppb_number'];
$sj_date =  date("Y-m-d H:i:s",strtotime($_POST['sj_date']));
$shipp_number = $_POST['shipp_number'];
$ws = $_POST['ws'];
$styleno = $_POST['styleno'];
$product_group = $_POST['product_group'];
$product_item = $_POST['product_item'];
$color = $_POST['color'];
$size = $_POST['size'];
$curr = $_POST['curr'];
$uom = $_POST['uom'];
$qty = $_POST['qty'];
$unit_price = $_POST['unit_price'];
$disc = $_POST['disc'];
$total_price = $_POST['total_price'];
$status = "DRAFT";
$create_user = $_POST['create_user'];
$create_date = date("Y-m-d H:i:s");

$query = "INSERT INTO sb_invoice_detail_temp (id_bppb, so_number, bppb_number, sj_date, shipp_number, ws, styleno, product_group, product_item, color, size, curr, uom, qty, unit_price, disc, total_price) 
VALUES 
	('$id_bppb', '$so_number', '$bppb_number', '$sj_date', '$shipp_number', '$ws', '$styleno', '$product_group', '$product_item', '$color', '$size', '$curr', '$uom', '$qty', '$unit_price', '$disc', '$total_price')";

$execute = mysqli_query($conn2,$query);


if(!$execute){	
   die('Error: ' . mysqli_error());	
}else{
	
}

mysqli_close($conn2);
?>