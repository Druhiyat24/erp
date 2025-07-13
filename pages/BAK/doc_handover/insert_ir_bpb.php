<?php
include "../../include/conn.php";
include "fungsi.php";
ini_set('date.timezone', 'Asia/Jakarta');
session_start();

$no_bpb = $_REQUEST['no_bpb'];
$tgl_bpb = date("Y-m-d",strtotime($_REQUEST['tgl_bpb']));
$pono = $_REQUEST['pono'];
$Supplier = $_REQUEST['Supplier'];
$jml_pterms = $_REQUEST['jml_pterms'];
$kode_pterms = $_REQUEST['kode_pterms'];
$keterangan = $_REQUEST['keterangan'];
$curr = $_REQUEST['curr'];
$total = $_REQUEST['total'];
$confirm_by = $_REQUEST['confirm_by'];
$confirm_date = date("Y-m-d",strtotime($_REQUEST['confirm_date']));
$tgl_transfer = date("Y-m-d",strtotime($_REQUEST['tgl_transfer']));
$unik_code = $_REQUEST['unik_code'];
$user = $_SESSION['username'];
$app_date = date("Y-m-d H:i:s");


$sql = mysqli_query($conn_li,"select max(no_trans) doc_number from ir_log_trans where kode_trans = 'TBPB' and unik_code = '$unik_code'");
$row = mysqli_fetch_array($sql);
$kode = $row['doc_number'];

$query = "INSERT INTO ir_trans_bpb (no_transfer,tgl_transfer,nama_supp,no_bpb,tgl_bpb,no_po,top,p_terms,curr,total,bpb_confirm,bpb_confirm_date,status,keterangan,created_by,created_at) 
VALUES 
	('$kode', '$tgl_transfer', '$Supplier', '$no_bpb', '$tgl_bpb', '$pono', '$jml_pterms', '$kode_pterms', '$curr', '$total', '$confirm_by', '$confirm_date', 'Transfer', '$keterangan', '$user', '$app_date')";

$execute = mysqli_query($conn_li,$query);


if(!$execute){	
   die('Error: ' . mysqli_error());	
}else{
	$sql_upt = "update bpb set stat_trf = 'Transfer' where bpbno_int = '$no_bpb'";
	$execute_upt = mysqli_query($conn_li,$sql_upt);

	$sql_upt2 = "update bppb set stat_trf = 'Transfer' where bpbno_int = '$no_bpb'";
	$execute_upt2 = mysqli_query($conn_li,$sql_upt2);
}

mysqli_close($conn_li);

?>
