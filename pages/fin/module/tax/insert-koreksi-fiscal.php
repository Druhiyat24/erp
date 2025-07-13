<?php
include '../../conn/conn.php';
ini_set('date.timezone', 'Asia/Jakarta');

$no_doc = $_POST['no_doc'];
$tgl_doc =  date("Y-m-d",strtotime($_POST['tgl_doc']));
$no_coa = $_POST['no_coa'];
$pesan = $_POST['pesan'];
$nama_type = $_POST['nama_type'];
$nominal_h =  $_POST['nominal_h'];
$status = "Post";
$create_user = $_POST['create_user'];
$create_date = date("Y-m-d H:i:s");




$sqlcoa = mysqli_query($conn1,"select nama_coa from mastercoa_v2 where no_coa = '$no_coa'");
$rowcoa = mysqli_fetch_array($sqlcoa);
$nama_coa = $rowcoa['nama_coa'];

if ($nama_type == 'Positif') {
	$query = "INSERT INTO sb_journal_fiscal (no_dok,tgl_dok,no_coa,nama_coa,type_value,val_plus,val_min,deskripsi,status,created_by,created_date) 
					VALUES 
	('$no_doc', '$tgl_doc', '$no_coa', '$nama_coa', '$nama_type', '$nominal_h', '0', '$pesan', '$status', '$create_user', '$create_date')";

	$execute = mysqli_query($conn2,$query);
}else{
	$query = "INSERT INTO sb_journal_fiscal (no_dok,tgl_dok,no_coa,nama_coa,type_value,val_plus,val_min,deskripsi,status,created_by,created_date) 
					VALUES 
	('$no_doc', '$tgl_doc', '$no_coa', '$nama_coa', '$nama_type', '0', '$nominal_h', '$pesan', '$status', '$create_user', '$create_date')";

	$execute = mysqli_query($conn2,$query);
}


if(!$execute){	
   die('Error: ' . mysqli_error());	
}else{
	// echo 'Data Saved Successfully With No '; echo $no_mj;
}

mysqli_close($conn2);
?>