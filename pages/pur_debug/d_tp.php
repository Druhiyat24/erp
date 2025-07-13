<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
$id=$_GET['id'];
$mode="";

$cek=0;
if ($cek!="0")
{	$_SESSION['msg'] = 'XData Tidak Bisa Dicancel';	}
else
{	$sql = "update transfer_post set cancel='Y' where id='$id' ";
	insert_log($sql,$user);
	$_SESSION['msg'] = 'Data Berhasil Dicancel';
}
echo "<script>
	 window.location.href='../pur/?mod=$mod';
</script>";
?>