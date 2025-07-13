<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_rate = $_GET['id']; } else {$id_rate = "";}

$cek="";
if ($cek=="")
{	$sql="delete from master_defect where id_defect='$id_rate'";
	insert_log($sql,$user);
	$_SESSION['msg'] = "Data Berhasil Dihapus";
	echo "<script>window.location.href='../master/?mod=$mod';</script>";
}
else
{	$_SESSION['msg'] = "XData Tidak Bisa Dihapus";
	echo "<script>window.location.href='../master/?mod=$mod';</script>";
}
?>