<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../"); }

$user=$_SESSION['username'];
$id=$_GET['iddata'];
$editharga=$_POST['editharga'];
$satuan_bc=$_POST['satuan_bc'];
$curr_bc=$_POST['curr_bc'];
$qty_bc=$_POST['qty_bc'];
$dtrx=$_GET['dtrx'];
$noid=$_GET['noid'];
$jen_trx=$_GET['jen_trx'];
$tbl=$_GET['tbl'];
#echo $dtrx;
$sql="update $tbl set price_bc='$editharga', satuan_bc='$satuan_bc', qty_bc='$qty_bc', curr_bc='$curr_bc' where id='$id'";
insert_log($sql,$user);
echo "<script>window.location.href='../shp/?mod=2U_new&trx=$jen_trx&dtrx=$dtrx&noid=$noid';</script>";
?>