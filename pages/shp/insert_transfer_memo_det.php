<?php
include "../../include/conn.php";
include "fungsi.php";
ini_set('date.timezone', 'Asia/Jakarta');
session_start();

$no_memo = $_REQUEST['no_memo'];
$unik_code = $_REQUEST['unik_code'];
$keterangan = $_REQUEST['keterangan'];
$user = $_SESSION['username'];
$app_date = date("Y-m-d H:i:s");



$sql = mysqli_query($conn_li,"select max(no_trans) doc_number from transfer_memo_exim_h where unik_code = '$unik_code'");
$row = mysqli_fetch_array($sql);
$kode = $row['doc_number'];

$query = "INSERT INTO transfer_memo_exim_det (no_trans,nm_memo,keterangan,status,created_by,created_at,updated_at) 
VALUES 
	('$kode', '$no_memo', '$keterangan', 'Y', '$user', '$app_date', '$app_date')";

$execute = mysqli_query($conn_li,$query);


if(!$execute){	
   die('Error: ' . mysqli_error());	
}else{

	if (strpos($kode, 'TETM') !== false) {
    	$sql_upt = "update memo_h set status_transfer = 'TETM' where nm_memo = '$no_memo'";
		$execute_upt = mysqli_query($conn_li,$sql_upt);
	}else{
		$sql_upt = "update memo_h set status_transfer = 'TETF' where nm_memo = '$no_memo'";
		$execute_upt = mysqli_query($conn_li,$sql_upt);
	}

	

}

mysqli_close($conn_li);

?>
