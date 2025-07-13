<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user  =$_SESSION['username'];
$mod   =$_GET['mod'];
$aktif =$_GET['is_active'];
$part  =$_GET['part'];
$table = "master".$part;


if (isset($_GET['id'])) {$id = $_GET['id']; } else {$id = "";}

$cek="";
if ($cek=="")
{	
	$sql="update {$table} set aktif='{$aktif}' where id='$id'";
	insert_log($sql,$user);
/*  	echo $sql;
	die();  */
	$_SESSION['msg'] = "Data Berhasil Dinonaktifkan";
	echo "<script>window.location.href='../master/?mod=$mod';</script>";
}
?>