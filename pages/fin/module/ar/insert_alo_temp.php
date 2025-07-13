<?php
include '../../conn/conn.php';
ini_set('date.timezone', 'Asia/Jakarta');

$ref_number = $_POST['ref_number'];
$ref_date =  date("Y-m-d H:i:s",strtotime($_POST['ref_date']));
$due_date =  date("Y-m-d H:i:s",strtotime($_POST['due_date']));
$curr = $_POST['curr'];
$total = $_POST['total'];
$eqp_idr = $_POST['eqp_idr'];
$amount = $_POST['amount'];
$rate = $_POST['rate'];
$create_user = $_POST['create_user'];
$status = "DRAFT";
$create_date = date("Y-m-d H:i:s");

$query = "INSERT INTO sb_alokasi_temp (ref_number, ref_date, due_date, curr, total, eqp_idr, amount, rate) 
VALUES 
	('$ref_number', '$ref_date', '$due_date', '$curr', '$total', '$eqp_idr', '$amount', '$rate')";

$execute = mysqli_query($conn2,$query);


if(!$execute){	
   die('Error: ' . mysqli_error());	
}else{
	
}

mysqli_close($conn2);
?>