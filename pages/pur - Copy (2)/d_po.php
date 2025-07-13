<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mod2=$mod;
if (isset($_GET['id'])) {$id_rate = $_GET['id']; } else {$id_rate = "";}
if (isset($_GET['idd'])) {$id_po = $_GET['idd']; } else {$id_po = "";}
if($id_rate=="")
{
	$cek=flookup("bpbno","bpb a inner join po_item s on a.id_po_item=s.id","s.id_po='$id_po'");
}
else
{
	$id_po=flookup("id_po","po_item","id='$id_rate'");
	$cek=flookup("bpbno","bpb a inner join po_item s on a.id_po_item=s.id","id_po_item='$id_rate' or s.id_po='$id_po'");
}
if($cek=="" and $id_rate!="")
{	$sql="update po_item set cancel='Y' where id='$id_rate'";
	insert_log($sql,$user);
	$sql="update po_header set revise=revise+1 where id='$id_po'";
	insert_log($sql,$user);
	$_SESSION['msg'] = "Data Berhasil Dicancel";
	echo "<script>window.location.href='../pur/?mod=$mod2&id=$id_po';</script>";
}
else if($cek=="" and $id_rate=="")
{	$sql="update po_item set cancel='Y' where id_po='$id_po'";
	insert_log($sql,$user);
	$sql="update po_header set revise=revise+1 where id='$id_po'";
	insert_log($sql,$user);
	$_SESSION['msg'] = "Data Berhasil Dicancel";
	echo "<script>window.location.href='../pur/?mod=$mod2';</script>";
}
else
{	$_SESSION['msg'] = "XData Tidak Bisa Dihapus Karena Sudah Ada BPB";
	echo "<script>window.location.href='../pur/?mod=$mod2&id=$id_po';</script>";
}
?>