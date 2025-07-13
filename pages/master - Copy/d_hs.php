<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_item_odo = $_GET['id']; } else {$id_item_odo = "";}

$sql="delete from masteritem_odo where id='$id_item_odo'";
insert_log($sql,$user);
$_SESSION['msg'] = "Data Berhasil Dihapus";
echo "<script>window.location.href='../master/?mod=$mod';</script>";
?>