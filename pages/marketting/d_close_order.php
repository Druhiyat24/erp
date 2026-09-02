<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$id_so=$_GET['id'];

$sql = "update so set close_order='Y', close_order_by='$user', close_order_at=NOW() where id='$id_so' ";
insert_log($sql,$user);
$_SESSION['msg'] = 'Data Berhasil Di Close Order';

echo "<script>window.location.href='../marketting/?mod=$mod';</script>";
?>