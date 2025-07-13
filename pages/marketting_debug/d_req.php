<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
$id_so=$_GET['id'];
$mode="";

$sql = "update request_sample_dev set cancel='Y' where id='$id_so' 
		";
	insert_log($sql,$user);
	$_SESSION['msg'] = 'Data Berhasil Dicancel';
echo "<script>
	 window.location.href='../marketting/?mod=$mod';
</script>";
?>