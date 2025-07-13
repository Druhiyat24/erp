<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod	=$_GET['mod'];
$tanggal = fd($_POST['txttanggal']);
$keterangan = $_POST['txtketerangan'];
$mulai = ft($_POST['txtmulai']);
$selesai = ft($_POST['txtselesai']);
$nik = $_POST['txtnik'];

$sql="update hr_spl set keterangan='$keterangan',mulai='$mulai',selesai='$selesai'
	where nik='$nik' and tanggal='$tanggal'";
insert_log($sql,$user);
$_SESSION['msg'] = "Data Berhasil Dirubah";
echo "<script>window.location.href='../hr/?mod=18L';</script>";
?>