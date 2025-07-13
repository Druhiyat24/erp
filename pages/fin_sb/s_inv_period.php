<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';

session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
require_once "../log_activity/log.php";
$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_periode = $_GET['id']; } else {$id_periode = "";}

$id_periode        =nb($_POST['txtid_periode']);
$gudang            =nb($_POST['txtgudang']);
$tglawal           =nb($_POST['txttglawal']);
$tglakhir          =nb($_POST['txttglakhir']);

{ $sql="update tptglperiode set tgl1='$tglawal',tgl2='$tglakhir'
		where idx='$id_periode'";
	insert_log($sql,$user);
  echo "<script>window.location.href='index.php?mod=99';</script>";  
}
?>