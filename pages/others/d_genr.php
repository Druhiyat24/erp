<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mod2=$mod;
if (isset($_GET['idd'])) {$id_line = $_GET['idd']; } else {$id_line = "";}
if (isset($_GET['id'])) {$id_gen = $_GET['id']; } else {$id_gen = "";}
$id_req=flookup("id_reqno","reqnon_item","id='$id_line'");
$id_gen=flookup("id_item","reqnon_item","id='$id_line'");
$cek=flookup("a.id","po_item a inner join po_header s on a.id_po=s.id","a.id_jo='$id_req' and a.id_gen='$id_gen' and s.jenis='N'");
#echo "idgen=".$id_gen."cek=".$cek."idline=".$id_line;
if ($cek=="")
{	$sql="update reqnon_item set cancel='Y' where id='$id_line'";
	insert_log($sql,$user);
	$_SESSION['msg'] = "Data Berhasil Dicancel";
	echo "<script>window.location.href='../others/?mod=$mod2&id=$id_req';</script>";
}
else
{	$_SESSION['msg'] = "XData Tidak Bisa Dihapus Karena Sudah Dibuat PO";
	echo "<script>window.location.href='../others/?mod=$mod2&id=$id_req';</script>";
}
?>