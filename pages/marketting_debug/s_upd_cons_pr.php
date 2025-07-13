<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode="";
$id_jo=$_GET['id'];

$ConsNya = $_POST['txtconsarr'];
foreach ($ConsNya as $key => $value)
{	$cons=$ConsNya[$key];
	$keyarr=split(":",$key);
	$id_item=$keyarr[0];
	$id_jo=$keyarr[1];
	$sql="update bom_jo_item set cons='$cons' 
		where id_jo='$id_jo' and id_item='$id_item'";
	insert_log($sql,$user);
}
$_SESSION['msg'] = "Data Berhasil Disimpan";
echo "<script>window.location.href='../marketting/?mod=15LC';</script>";
?>