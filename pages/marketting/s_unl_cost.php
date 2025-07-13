<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode="";

$cri=split(':', $_POST['txtid_cost']);
$id_cost=$cri[0];
$tx_cost=$cri[1];
$txtreason=nb($_POST['txtreason']);
$date=date('Y-m-d');
$date2=date('Y-m-d H:m:s');

insert_log("XUnlock Costing # : ".$tx_cost." Dengan Alasan : ".$txtreason,$user);
$sql="insert into unlock_cost (id_cost,cost_no,unlock_date,reason,username,dateinput) 
	values ('$id_cost','$tx_cost','$date','$txtreason','$user','$date2')";
insert_log($sql,$user);
$_SESSION['msg'] = "Data Berhasil Diunlock";
echo "<script>window.location.href='../marketting/?mod=$mod';</script>";
?>