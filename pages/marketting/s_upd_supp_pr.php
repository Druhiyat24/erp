<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode="";
$id_jo=$_GET['id'];

$Supp = $_POST['txtsupplierarr'];
$Supp2 = $_POST['txtsupplier2arr'];
foreach ($Supp as $key => $value)
{	$id_supplier=$Supp[$key];
	$id_supplier2=$Supp2[$key];
	$keyarr=split(":",$key);
	$id_item=$keyarr[0];
	$id_jo=$keyarr[1];
	$sql="update bom_jo_item set id_supplier='$id_supplier',id_supplier2='$id_supplier2' 
		where id_jo='$id_jo' and id_item='$id_item'";
	insert_log($sql,$user);
}
$_SESSION['msg'] = "Data Berhasil Disimpan";
echo "<script>window.location.href='../marketting/?mod=15&id=$id_jo';</script>";
?>