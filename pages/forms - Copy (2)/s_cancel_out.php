<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode="";

$cri=$_POST['txtid_cost'];
$txtreason=nb($_POST['txtreason']);
$date=date('Y-m-d');
$date2=date('Y-m-d H:m:s');

insert_log("XCancel BPB # : ".$cri." Dengan Alasan : ".$txtreason,$user);
$sql="insert into cancel_trans (jenis_trans,trans_no,cancel_date,reason,username,dateinput) 
	values ('OUT','$cri','$date','$txtreason','$user','$date2')";
insert_log($sql,$user);
$sql="update bppb set qty_old=qty where bppbno='$cri'";
insert_log($sql,$user);
$sql="update bppb set qty=0,cancel='Y',cancel_by='$user',cancel_date='$date2',confirm_by='',confirm_date=null,confirm='N'  where bppbno='$cri'";
insert_log($sql,$user);

$bppbreq = flookup("bppbno_req","bppb","bppbno='$cri'");
$sql="update bppb_req set qty_old=qty where bppbno='$bppbreq'";
insert_log($sql,$user);
$sql="update bppb_req set qty=0,cancel='Y',cancel_by='$user',cancel_date='$date2' where bppbno='$bppbreq'";
insert_log($sql,$user);
$_SESSION['msg'] = "Data Berhasil Dicancel";
echo "<script>window.location.href='../forms/?mod=cao';</script>";
?>