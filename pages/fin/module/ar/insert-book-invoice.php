<?php
include '../../conn/conn.php';
ini_set('date.timezone', 'Asia/Jakarta');

$no_inv = $_POST['no_inv'];
$tgl_inv =  date("Y-m-d H:i:s",strtotime($_POST['tgl_inv']));
$customer = $_POST['customer'];
$shipp = $_POST['shipp'];
$dok_type = $_POST['dok_type'];
$dok_number = $_POST['dok_number'];
$jml_value = $_POST['jml_value'];
$txt_type = $_POST['txt_type'];
$status = "DRAFT";
$create_user = $_POST['create_user'];
$create_date = date("Y-m-d H:i:s");

if ($shipp == 'L') {
	$shipp = 'Local';
}else{
	$shipp = 'Export';
}


$query = "INSERT INTO sb_book_invoice (no_invoice, id_customer, shipp, id_type, tgl_book_inv, status, value, doc_type, doc_number) 
VALUES 
	('$no_inv', '$customer', '$shipp', '$txt_type', '$tgl_inv', '$status', '$jml_value', '$dok_type', '$dok_number')";

$execute = mysqli_query($conn2,$query);


if(!$execute){	
   die('Error: ' . mysqli_error());	
}else{
	
}

mysqli_close($conn2);
?>