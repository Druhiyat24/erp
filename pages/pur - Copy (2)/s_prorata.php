<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];

$id_po=$_POST['txtpono'];
$id_item_po=$_POST['txtpoitem'];
$qtypo_ori=$_POST['txtqtypo'];
$qtypo_prorata=$_POST['txtqtyprorate'];
$qtybal=($qtypo_prorata-$qtypo_ori)/$qtypo_ori;
$id_item_bpb=flookup("id_item","masteritem","id_gen='$id_item_po'");
$cek=flookup("bpbno","bpb","id_item='$id_item_bpb'");
if ($cek=="")
{	$sql="update po_item set qty=round(qty+(qty*$qtybal),2) where id_po='$id_po' and id_gen='$id_item_po'";
	insert_log($sql,$user);
	#echo $sql;
	$_SESSION['msg'] = "Data Berhasil Dirubah";
	echo "<script>window.location.href='../pur/?mod=$mod';</script>";
}
else
{	$_SESSION['msg'] = "XData Tidak Bisa Diubah Karena Sudah Ada BPB";
	echo "<script>window.location.href='../pur/?mod=$mod';</script>";
}
?>