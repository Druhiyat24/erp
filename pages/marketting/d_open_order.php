<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$id_costing=$_GET['id'];

$sql = "update act_costing set close_order='N' where id='$id_costing' ";
insert_log($sql,$user);
$_SESSION['msg'] = 'Data Berhasil Di Oper Order';

echo "<script>window.location.href='../marketting/?mod=$mod';</script>";
?>