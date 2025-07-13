<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode="";

$id_jo=$_GET['id'];
$cek=flookup("count(*)","jo","id='$id_jo' and app='A'");
if ($cek!="0")
{	$_SESSION['msg'] = 'XData Tidak Bisa Diubah Karena Sudah Diapprove';	
	echo "<script>window.location.href='../marketting/?mod=14&id=$id_jo';</script>";
	exit;
}
$id_item=$_GET['idd'];
$cons = nb($_POST['txtcons']);
$unit = nb($_POST['txtunit']);
$id_gen = flookup("id_item","bom_jo_item","id_jo='$id_jo' and id='$id_item'");
$id_item_bb=flookup("id","po_item","id_gen='$id_gen' and id_jo='$id_jo'");
if ($id_item_bb!="")
{	$_SESSION['msg'] = "XData Sudah Dibuat PO"; }
else
{	$rscek=mysql_fetch_array(mysql_query("select * from bom_jo_item where id_jo='$id_jo' 
		and id='$id_item' "));
	$cekrule=$rscek["rule_bom"];
	$cekpno=$rscek["posno"];
	if($cekrule=="ALL COLOR ALL SIZE")
	{	$sql="update bom_jo_item set cons='$cons',unit='$unit' where id_jo='$id_jo' and posno='$cekpno'
			and rule_bom='$cekrule'";
		insert_log($sql,$user);
	}
	else
	{	$sql="update bom_jo_item set cons='$cons',unit='$unit' where id_jo='$id_jo' and id='$id_item'";
		insert_log($sql,$user);
	}
	$_SESSION['msg'] = "Data Berhasil Dirubah";
}
echo "<script>window.location.href='../marketting/?mod=14&id=$id_jo';</script>";
?>