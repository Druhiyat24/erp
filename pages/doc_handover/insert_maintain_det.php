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
$dateinput = date("Y-m-d",strtotime($_REQUEST['dateinput']));
$qty = $_REQUEST['qty'];
$user = $_SESSION['username'];
$app_date = date("Y-m-d H:i:s");


$sql = mysqli_query($conn_li,"select max(no_maintain) no_maintain from maintain_bpb_h where unik_code = '$unik_code'");
$row = mysqli_fetch_array($sql);
$kode = $row['no_maintain'];


$query = "INSERT INTO maintain_bpb_det (no_maintain,nama_supp,no_bpb,tgl_bpb,no_po,top,p_terms,curr,qty,total,bpb_input_date,bpb_confirm,bpb_confirm_date,status,keterangan,created_by,created_date) 
VALUES 
	('$kode', '$Supplier', '$no_bpb', '$tgl_bpb', '$pono', '$jml_pterms', '$kode_pterms', '$curr', '$qty', '$total', '$dateinput', '$confirm_by', '$confirm_date', 'Y', '$keterangan', '$user', '$app_date')";

$execute = mysqli_query($conn_li,$query);


if(!$execute){	
   die('Error: ' . mysqli_error());	
}else{
	$sql_upt = "update bpb set status_maintain = 'Maintain' where bpbno_int = '$no_bpb'";
	$execute_upt = mysqli_query($conn_li,$sql_upt);

	$sql_upt2 = "update bppb set status_maintain = 'Maintain' where bppbno_int = '$no_bpb'";
	$execute_upt2 = mysqli_query($conn_li,$sql_upt2);
}

mysqli_close($conn_li);

?>
