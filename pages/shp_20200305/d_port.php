<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_rate = $_GET['id']; } else {$id_rate = "";}

$cek=flookup("id","invoice_header","port_of_loading='$id_rate' or 
	port_of_discharge='$id_rate' or port_of_entrance='$id_rate'");
if ($cek=="")
{	$sql="delete from masterport where id='$id_rate'";
	insert_log($sql,$user);
	$_SESSION['msg'] = "Data Berhasil Dihapus";
	echo "<script>window.location.href='../shp/?mod=$mod';</script>";
}
else
{	$_SESSION['msg'] = "XData Tidak Bisa Dihapus";
	echo "<script>window.location.href='../shp/?mod=$mod';</script>";
}
?>