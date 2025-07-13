<?php
include '../../conn/conn.php';
ini_set('date.timezone', 'Asia/Jakarta');

$no_inv = $_POST['no_inv'];
$cancel_date = date("Y-m-d H:i:s");
$cancel_user = $_POST['cancel_user'];


$sql = "update sb_book_invoice SET status = 'CANCEL' WHERE no_invoice = '$no_inv'";
$execute = mysqli_query($conn2,$sql);


if(!$execute) {
	die('Error: ' . mysqli_error());	
}else{
	
}


// echo $no_kbon;
// echo $amount;
// echo $balance;
// echo $update_balance;

mysqli_close($conn2);

?>