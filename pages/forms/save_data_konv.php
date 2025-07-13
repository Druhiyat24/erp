<?php 
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_item=$_GET['id'];} else {$id_item="";}
$txtitem=nb($_POST['txtitem']);
$txtqtyawal=$_POST['txtqtyawal'];
$txtsatuan_awal=$_POST['txtsatuan_awal'];;
$txtqtykonv=$_POST['txtqtykonv'];
$txtsatuan_konv=$_POST['txtsatuan_konv'];;

$cek=flookup("unit1","konversi_satuan","id_item='$txtitem'");
if ($cek!="" and $id_item=="")
{	$_SESSION['msg'] = "XData Sudah Ada";
	echo "<script>window.location.href='../forms/?mod=22L';</script>"; 
}
else if ($id_item!="")
{	$sql="update konversi_satuan set qty1='$txtqtyawal',unit1='$txtsatuan_awal',
		qty2='$txtqtykonv',unit2='$txtsatuan_konv'
		where id='$id_item' ";
	insert_log($sql,$user);
	$_SESSION['msg'] = "Data Berhasil Dirubah";
	echo "<script>window.location.href='../forms/?mod=22L&mode=$mode';</script>";
}
else
{	$sql="insert into konversi_satuan (id_item,qty1,unit1,qty2,unit2) 
		values ('$txtitem','$txtqtyawal','$txtsatuan_awal',
		'$txtqtykonv','$txtsatuan_konv') ";
	insert_log($sql,$user);
	$_SESSION['msg'] = "Data Berhasil Disimpan";
	echo "<script>window.location.href='../forms/?mod=22L&mode=$mode';</script>";
}
?>