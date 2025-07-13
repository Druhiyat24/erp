<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

$umktr=nb($_POST['txtumktransisi']);
$umk=nb($_POST['txtumk']);
$sql="update hr_parameter_payroll set umk='$umk',transisi_umk='$umktr'";
insert_log($sql,$user);
$_SESSION['msg'] = "Data Berhasil Dirubah";
echo "<script>window.location.href='../hr/?mod=$mod';</script>";
?>