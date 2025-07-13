<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode="";

$id_po=$_GET['id'];
$notes=nb($_POST['txtnotes']);

$sql="update po_header_dev set notes='$notes' where id='$id_po'";
insert_log($sql,$user);
$_SESSION['msg'] = "Data Berhasil Disimpan";
echo "<script>window.location.href='../marketting/?mod=x3L';</script>";

?>