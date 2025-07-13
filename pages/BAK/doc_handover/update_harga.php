<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../"); }

$user=$_SESSION['username'];
$id=$_GET['iddata'];
$editharga=$_POST['editharga'];
$dtrx=$_GET['dtrx'];
$noid=$_GET['noid'];
$jen_trx=$_GET['jen_trx'];
$tbl=$_GET['tbl'];
#echo $dtrx;
$sql="update $tbl set price_bc='$editharga' where id='$id'";
insert_log($sql,$user);
echo "<script>window.location.href='../shp/?mod=2U&trx=$jen_trx&dtrx=$dtrx&noid=$noid';</script>";
?>