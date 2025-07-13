<?php
include '../../conn/conn.php';
ini_set('date.timezone', 'Asia/Jakarta');

$id_book_invoice = $_POST['id_book_invoice'];
$total = $_POST['total'];
$discount = $_POST['discount'];
$dp = $_POST['dp'];
$retur = $_POST['retur'];
$twot = $_POST['twot'];
$vat = $_POST['vat'];
$grand_total = $_POST['grand_total'];
$create_date = date("Y-m-d H:i:s");

$query = "INSERT INTO sb_invoice_pot (id_book_invoice,total, discount, dp, retur, twot, vat, grand_total) 
VALUES 
	('$id_book_invoice','$total', '$discount', '$dp', '$retur', '$twot', '$vat', '$grand_total')";

$execute = mysqli_query($conn2,$query);


if(!$execute){	
   die('Error: ' . mysqli_error());	
}else{
	
}

mysqli_close($conn2);
?>