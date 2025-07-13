<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode=$_GET['mode'];

$_SESSION['msg'] = 'Alert';
echo "<script>window.location.href='../???/?mod=???';</script>";
?>