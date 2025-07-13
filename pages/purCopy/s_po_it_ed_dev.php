<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode="";

$id_po_det=$_GET['id'];
$id_po_h=flookup("id_po","po_item","id='$id_po_det'");
$txtprice=$_POST['txtprice'];
$txtqty=$_POST['txtqty'];
$cek=flookup("bpbno","bpb","id_po_item='$id_po_det'");
if ($cek=="")
{	$sql="update po_item set qty='$txtqty',price='$txtprice' where id='$id_po_det'";
	insert_log($sql,$user);
	$sql="update po_header set revise=revise+1 where id='$id_po_h'";
	insert_log($sql,$user);
	$_SESSION['msg'] = "Data Berhasil Dirubah";
	echo "<script>window.location.href='../pur/?mod=3e&id=$id_po_h';</script>";
}
else
{	$_SESSION['msg'] = "XData Tidak Bisa Diubah Karena Sudah Ada BPB";
	echo "<script>window.location.href='../pur/?mod=3e&id=$id_po_h';</script>";
}
?>