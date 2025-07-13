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

$cek=flookup("bpbno","bpb a inner join po_item s on a.id_po_item=s.id","s.id_po='$id_cost' and a.cancel='N' ");
if($cek=="")
{
	insert_log("XUnapprove PO # : ".$tx_cost." Dengan Alasan : ".$txtreason,$user);
	$sql="insert into unapp_po (id_po,pono,unlock_date,reason,username,dateinput) 
		values ('$id_cost','$tx_cost','$date','$txtreason','$user','$date2')";
	insert_log($sql,$user);
	$sql="update po_header set app='W' where id='$id_cost'";
	insert_log($sql,$user);
	$_SESSION['msg'] = "Data Berhasil Diunapprove";
}
else
{
	$_SESSION['msg'] = "XPO Tidak Bisa Diunapprove Karena Sudah Ada BPB";
}
echo "<script>window.location.href='../appr/?mod=$mod';</script>";
?>