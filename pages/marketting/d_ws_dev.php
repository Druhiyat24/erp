<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
$id_jo=$_GET['id'];
$mode="";

$cek=flookup("count(*)","bom_dev_jo_item","id_jo='$id_jo' and cancel='N'");
if ($cek!="0")
{	$_SESSION['msg'] = 'XData Tidak Bisa Dicancel Karena Sudah Dibuat BOM';	}
else
{	$sql = "update jo_det_dev set cancel='Y' where id_jo='$id_jo' ";
	insert_log($sql,$user);
	$_SESSION['msg'] = 'Data Berhasil Dicancel';
}
echo "<script>
	 window.location.href='../marketting/?mod=$mod&id=$id_jo';
</script>";
?>