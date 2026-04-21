<?php
include "../../include/conn.php";
include "fungsi.php";
ini_set('date.timezone', 'Asia/Jakarta');
session_start();

$no_transfer = $_REQUEST['no_transfer'];
$user = $_SESSION['username'];
$app_date = date("Y-m-d H:i:s");



$query = "update transfer_memo_exim_h SET status = 'CANCEL', cancel_by = '$user', cancel_date = '$app_date' where no_trans = '$no_transfer'";
$execute = mysqli_query($conn_li,$query);

$query_det = "update transfer_memo_exim_det SET status = 'N', updated_at = '$app_date' where no_trans = '$no_transfer'";
$execute_det = mysqli_query($conn_li,$query_det);

$query2 = "update memo_h a INNER JOIN transfer_memo_exim_det b ON b.nm_memo = a.nm_memo SET a.status_transfer = 'PENDING', a.tetm_by = null, a.tetm_date = null where b.no_trans = '$no_transfer'";
$execute2 = mysqli_query($conn_li,$query2);


if(!$execute){	
	die('Error: ' . mysqli_error());	
}else{
	
}

mysqli_close($conn_li);

?>
