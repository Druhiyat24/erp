<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
$id_so=$_GET['id'];
$mode="";
if (isset($_GET['idd'])) {$id_so_det=$_GET['idd'];} else {$id_so_det="";}

$sql = "update request_det_dev set cancel='Y' where id='$id_so_det' 
		";
	insert_log($sql,$user);
	$_SESSION['msg'] = 'Data Berhasil Dicancel';
echo "<script>
	 window.location.href='../marketting/?mod=$mod&id=$id_so';
</script>";
?>