<?php
include '../../conn/conn.php';
ini_set('date.timezone', 'Asia/Jakarta');

$id_inv = $_POST['id_inv'];
$no_inv = $_POST['no_inv'];
$pph = $_POST['pph'];
$id_top = $_POST['id_top'];
$id_bank = $_POST['id_bank'];
$type_so = $_POST['type_so'];
$no_coa = $_POST['no_coa'];
$nama_coa = $_POST['nama_coa'];
$create_user = $_POST['create_user'];
$inv_date = date("Y-m-d");
$create_date = date("Y-m-d H:i:s");

$query = "UPDATE sb_book_invoice SET status  = 'POST', pph = '$pph', tgl_inv = '$inv_date', id_top  = '$id_top', id_bank =  '$id_bank', type_so = '$type_so', no_coa =  '$no_coa', nama_coa = '$nama_coa' WHERE id = '$id_inv'";

$execute = mysqli_query($conn2,$query);

$query2 = "INSERT INTO sb_log (nama,activity, tanggal_input, doc_number, tanggal_doc, keterangan) 
VALUES 
	('$create_user','Create Invoice', '$create_date', '$no_inv', '$inv_date', 'POST')";

$execute2 = mysqli_query($conn2,$query2);

if(!$execute){	
   die('Error: ' . mysqli_error());	
}else{
	
}

mysqli_close($conn2);
?>