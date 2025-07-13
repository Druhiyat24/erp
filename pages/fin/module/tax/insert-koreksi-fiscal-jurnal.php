<?php
include '../../conn/conn.php';
ini_set('date.timezone', 'Asia/Jakarta');

$debit = $_POST['debit'];
$credit = $_POST['credit'];
$koreksi = $_POST['koreksi'];
$id_jurnal = $_POST['id_jurnal'];
$status = "Post";
$create_user = $_POST['create_user'];
$create_date = date("Y-m-d H:i:s");

$debitnya = $debit + $koreksi;
$creditnya = $credit + $koreksi;

$sqlrate = mysqli_query($conn1,"select rate from sb_list_journal where id = '$id_jurnal'");
$rowrate = mysqli_fetch_array($sqlrate);
$rate = $rowrate['rate'];

if ($rate < 1) {
	$ratenya = 1;
}else{
	$ratenya = $rate;
}

$debit_idr = $debitnya * $ratenya;
$credit_idr = $creditnya * $ratenya;

if ($debit < 1) {
	$query = "update sb_list_journal set credit = '$creditnya',credit_idr = '$credit_idr' where id = '$id_jurnal'";
}else{
	$query = "update sb_list_journal set debit = '$debitnya',debit_idr = '$debit_idr' where id = '$id_jurnal'";
}

$execute = mysqli_query($conn2,$query);


// $sqlcoa = mysqli_query($conn1,"select nama_coa from mastercoa_v2 where no_coa = '$no_coa'");
// $rowcoa = mysqli_fetch_array($sqlcoa);
// $nama_coa = $rowcoa['nama_coa'];

// if ($nama_type == 'Positif') {
// 	$query = "INSERT INTO sb_journal_fiscal (no_dok,tgl_dok,no_coa,nama_coa,type_value,val_plus,val_min,deskripsi,status,created_by,created_date) 
// 					VALUES 
// 	('$no_doc', '$tgl_doc', '$no_coa', '$nama_coa', '$nama_type', '$nominal_h', '0', '$pesan', '$status', '$create_user', '$create_date')";

// 	$execute = mysqli_query($conn2,$query);
// }else{
// 	$query = "INSERT INTO sb_journal_fiscal (no_dok,tgl_dok,no_coa,nama_coa,type_value,val_plus,val_min,deskripsi,status,created_by,created_date) 
// 					VALUES 
// 	('$no_doc', '$tgl_doc', '$no_coa', '$nama_coa', '$nama_type', '0', '$nominal_h', '$pesan', '$status', '$create_user', '$create_date')";

// 	$execute = mysqli_query($conn2,$query);
// }


if(!$execute){	
   die('Error: ' . mysqli_error());	
}else{
	// echo 'Data Saved Successfully With No '; echo $no_mj;
}

mysqli_close($conn2);
?>