<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode="";

$cri=split(':', $_POST['txtid_cost']);
$id_jo=$cri[0];
$jo_no=$cri[1];
$txtreason=nb($_POST['txtreason']);
$txttgl=fd($_POST['txttgl']);
$date=date('Y-m-d');
$date2=date('Y-m-d H:m:s');

insert_log("XUnlock Production # : ".$jo_no." Dengan Alasan : ".$txtreason,$user);
$sql="insert into unlock_prod (id_jo,jo_no,dateoutput,unlock_date,reason,username,dateinput) 
	values ('$id_jo','$jo_no','$txttgl','$date','$txtreason','$user','$date2')";
insert_log($sql,$user);
$_SESSION['msg'] = "Data Berhasil Diunlock";
echo "<script>window.location.href='../prod/?mod=$mod';</script>";
?>