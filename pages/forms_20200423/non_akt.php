<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode=$_GET['mode'];
if($mode=="Supplier")
{	$id_supplier=$_GET['id'];	}
else
{	$id_item=$_GET['id'];	}

if($mode=="Supplier")
{	$sql="update mastersupplier set non_aktif='1' where id_supplier='$id_supplier'";
	insert_log($sql,$user);
}
else
{	$sql="update masteritem set non_aktif='Y' where id_item='$id_item'";
	insert_log($sql,$user);
}

$_SESSION['msg'] = 'Data Berhasil Di Non Aktifkan';
if($mode=="Supplier")
{	echo "<script>window.location.href='../master/?mod=$mod&mode=$mode';</script>";	}
else
{	echo "<script>window.location.href='../forms/?mod=$mod&mode=$mode';</script>";	}
?>