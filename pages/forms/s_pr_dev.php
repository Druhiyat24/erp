<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode="";
$id_jo=$_GET['id'];
$id_itjo=$_GET['idd'];
#if (isset($_GET['status'])) {$status=$_GET['status'];} else {$status="";}
$status=$_GET['status'];

$id_supplier = nb($_POST['txtid_supplier']);
if(isset($_POST['txtid_supplier2'])) {$id_supplier2 = nb($_POST['txtid_supplier2']);} else {$id_supplier2 = "";}
$txtnotes=nb($_POST['txtnotes']);
$sql="update bom_jo_item set id_supplier='$id_supplier',id_supplier2='$id_supplier2',notes='$txtnotes' 
	where id_jo='$id_jo' and id_item='$id_itjo' and status='$status'";
insert_log($sql,$user);
$_SESSION['msg'] = "Data Berhasil Disimpan";
echo "<script>window.location.href='../marketting/?mod=$mod&id=$id_jo';</script>";
?>