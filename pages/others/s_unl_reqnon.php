<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode="";

$cri=split(':', $_POST['txtid_req']);
$id_req=$cri[0];
$tx_req=$cri[1];
$txtreason=nb($_POST['txtreason']);
$date=date('Y-m-d');
$date2=date('Y-m-d H:m:s');

insert_log("XUnlock Request # : ".$tx_req." Dengan Alasan : ".$txtreason,$user);
$sql="update reqnon_header set app='W',app_date=null,app2='W',app_date2=null where id='$id_req'";
insert_log($sql,$user);
$sql="insert into unapprove_gen_req (id_reqno,reqno,unlock_date,reason,username,dateinput) 
	values ('$id_req','$tx_req','$date','$txtreason','$user','$date2')";
insert_log($sql,$user);
$_SESSION['msg'] = "Data Berhasil Diunapprove";
echo "<script>window.location.href='../others/?mod=$mod';</script>";
?>