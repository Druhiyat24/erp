<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

$unit=nb($_POST['txtUnit']);
$cek=flookup("kode_pilihan","masterpilihan","kode_pilihan='Satuan' and nama_pilihan='$unit'");
if ($cek!="")
{ $_SESSION['msg'] = "XData Sudah Ada";
  echo "<script>window.location.href='../master/?mod=$mod';</script>";
}
else
{ if ($id_item=="")
	{	$sql="insert into masterpilihan (kode_pilihan,nama_pilihan) 
	    values ('Satuan','$unit')";
	  insert_log($sql,$user);
	  $_SESSION['msg'] = "Data Berhasil Disimpan";
  }
  else
  {	$sql="update masterpilihan set nama_pilihan='$unit' where nama_pilihan='$id_item' and kode_pilihan='Satuan'";
	  insert_log($sql,$user);
	  $_SESSION['msg'] = "Data Berhasil Dirubah";
  }
	echo "<script>window.location.href='../master/?mod=$mod';</script>";
}
?>