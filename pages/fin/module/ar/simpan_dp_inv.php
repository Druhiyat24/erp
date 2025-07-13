<?php
include '../../conn/conn.php';
ini_set('date.timezone', 'Asia/Jakarta');

$no_inv = $_POST['no_inv'];
$inv_date =  date("Y-m-d H:i:s",strtotime($_POST['inv_date']));
$no_coa = $_POST['no_coa'];
$nama_coa = $_POST['nama_coa'];
$buyer = $_POST['buyer'];
$inv_curr = $_POST['inv_curr'];
$rate = $_POST['rate'];
$total = $_POST['total'];
$total_idr = $_POST['total_idr'];
$keterangan = $_POST['keterangan'];
$create_user = $_POST['create_user'];
$create_date = date("Y-m-d H:i:s");

$query = "INSERT INTO sb_list_journal (no_journal, tgl_journal, type_journal, no_coa, nama_coa, no_costcenter, nama_costcenter, reff_doc, reff_date, buyer, no_ws, curr, rate, debit, credit, debit_idr, credit_idr,status, keterangan, create_by, create_date, approve_by, approve_date, cancel_by, cancel_date)
VALUES 
	('$no_inv', '$inv_date', 'Invoice', '$no_coa', '$nama_coa', '-', '-', '-', '', '$buyer', '', '$inv_curr', '$rate', '$total', '0', '$total_idr', '0','POST', '$keterangan', '$create_user', '$create_date', '', '', '', '')";

$execute = mysqli_query($conn2,$query);

if(!$execute){	
   die('Error: ' . mysqli_error());	
}else{
	
}

mysqli_close($conn2);
?>