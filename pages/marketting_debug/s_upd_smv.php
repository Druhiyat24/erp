<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod="updsmvl";
$id_cost=$_POST['txtid_cost'];
$txtsmv_sec=unfn($_POST['txtsmv_sec']);
$txtsmv_min=unfn($_POST['txtsmv_min']);

$sql="update act_costing set smv_sec='$txtsmv_sec',smv_min='$txtsmv_min' 
  where id='$id_cost'";
insert_log($sql,$user);
$_SESSION['msg'] = "Data Berhasil Disimpan";
echo "<script>window.location.href='../marketting/?mod=".$mod."';</script>";
?>