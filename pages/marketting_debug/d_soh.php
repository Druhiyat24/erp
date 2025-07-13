<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
$id_so=$_GET['id'];
$mode="";
if (isset($_GET['idd'])) {$id_so_det=$_GET['idd'];} else {$id_so_det="";}

$cek=flookup("count(*)","jo_det","id_so='$id_so' and cancel='N'");
$cekJO=flookup("jo_no","jo_det a inner join jo s on a.id_jo=s.id","id_so='$id_so' and cancel='N'");
$cek2=flookup("so_no","unlock_so","id_so='$id_so' and DATE_ADD(unlock_date, INTERVAL 2 DAY)>'$dateskrg'");
if ($cek!="0" and $cek2=="")
{	$_SESSION['msg'] = 'XData Tidak Bisa Dicancel Karena Sudah Dibuat Worksheet : '.$cekJO;	
	echo "<script>window.location.href='../marketting/?mod=$mod';</script>";
	exit;
}
else
{	$sql = "update so set cancel_h='Y' where id='$id_so' ";
	insert_log($sql,$user);
	$sql = "update so_det set cancel='Y' where id_so='$id_so' ";
	insert_log($sql,$user);
	$_SESSION['msg'] = 'Data Berhasil Dicancel';
}
echo "<script>window.location.href='../marketting/?mod=$mod';</script>";
?>