<?php 
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];

$id_item_fg = strtoupper($_POST['txtkpno']);
$id_item_bb = strtoupper($_POST['txtitem']);
$cons = strtoupper($_POST['txtcons']);
$satuan = strtoupper($_POST['txtsatuan']);
$allow = strtoupper($_POST['txtallow']);
$allow = $allow / 100;
$cek=flookup("id_item_fg","bom","id_item_fg='$id_item_fg' 
	and id_item_bb='$id_item_bb'");
if ($cek!="")
{	$_SESSION['msg'] = "XData Sudah Ada";
	echo "<script>window.location.href='index.php?mod=$mod';</script>"; 
}
else
{	$sql = "insert into bom (id_item_fg,id_item_bb,cons,allowance,satuan)
		values ('$id_item_fg','$id_item_bb','$cons','$allow','$satuan')";
	insert_log($sql,$user);
	$_SESSION['msg'] = "Data Berhasil Disimpan";
	echo "<script>window.location.href='index.php?mod=$mod&mode=$mode';</script>";
}
?>