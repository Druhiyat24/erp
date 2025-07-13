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
{	$sql="update mastercontents set aktif='N' where id='$id_rate'";
	insert_log($sql,$user);
	$_SESSION['msg'] = "Data Berhasil Dinonaktifkan";
	echo "<script>window.location.href='../master/?mod=$mod';</script>";
}
?>