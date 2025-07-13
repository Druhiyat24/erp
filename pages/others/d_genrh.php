<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mod2=$mod;
if (isset($_GET['id'])) {$id_gen = $_GET['id']; } else {$id_gen = "";}
$cek=flookup("a.id","po_item a inner join po_header s on a.id_po=s.id","s.jenis='N' and a.id_jo='$id_gen'");
if($cek=="")
{	$sql="update reqnon_item set cancel='Y' where id_reqno='$id_gen'";
	insert_log($sql,$user);
	$sql="update reqnon_header set cancel_h='Y' where id='$id_gen'";
	insert_log($sql,$user);
	$_SESSION['msg'] = "Data Berhasil Dicancel";
}
else
{
	$_SESSION['msg'] = "XData Tidak Bisa Dicancel Karena Sudah Dibuat PO";
}
echo "<script>window.location.href='../others/?mod=$mod2';</script>";
?>