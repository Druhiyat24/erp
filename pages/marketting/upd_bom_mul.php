<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
$id_jo=$_GET['id'];
$mode="";
$dateskrg=date('d M Y');

$cons_ar=$_POST['consar'];
$unit_ar=$_POST['unitar'];
foreach ($cons_ar as $key => $value) 
{	$id_jo_det = $key;
	$cek=flookup("count(*)","jo","id='$id_jo' and app='A'");
	if ($cek!="0")
	{	$_SESSION['msg'] = 'XData Tidak Bisa Diubah Karena Sudah Diapprove';	
		echo "<script>window.location.href='../marketting/?mod=$mod&id=$id_jo';</script>";
		exit;
	}
	else
	{	$sql = "update bom_jo_item set cons='$cons_ar[$key]',unit='$unit_ar[$key]' 
			where id_jo='$id_jo' 
			and id='$id_jo_det'";
		insert_log($sql,$user);
		$_SESSION['msg'] = 'Data Berhasil Diubah';
	}
}
echo "<script>window.location.href='../marketting/?mod=$mod&id=$id_jo';</script>";
?>