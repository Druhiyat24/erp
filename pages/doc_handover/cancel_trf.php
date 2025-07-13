<?php
include "../../include/conn.php";
include "fungsi.php";
ini_set('date.timezone', 'Asia/Jakarta');
session_start();

$no_transfer = $_REQUEST['no_transfer'];
$user = $_SESSION['username'];
$app_date = date("Y-m-d H:i:s");


$query = "update ir_trans_bpb SET status = 'Cancel', cancel_by = '$user', cancel_date = '$app_date' where no_transfer = '$no_transfer'";

$execute = mysqli_query($conn_li,$query);

$query2 = "update bpb a INNER JOIN ir_trans_bpb b ON b.no_bpb = a.bpbno_int SET a.stat_trf = null where b.no_transfer = '$no_transfer'";

$execute2 = mysqli_query($conn_li,$query2);


if(!$execute){	
	die('Error: ' . mysqli_error());	
}else{
	
}

mysqli_close($conn_li);

?>
