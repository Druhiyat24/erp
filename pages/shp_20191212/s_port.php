<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

$group=nb($_POST['txtgroup']);
$group_desc=nb($_POST['txtgroup_desc']);
$cek=flookup("kode_pel","masterport","kode_pel='$group'");
if ($cek!="" and $id_item=="")
{ $_SESSION['msg'] = "XData Sudah Ada";
  echo "<script>window.location.href='../shp/?mod=$mod';</script>";
}
else if ($id_item!="")
{ $sql="update masterport set kode_pel='$group',
		nama_pel='$group_desc' where id='$id_item'";
	insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Dirubah";
  echo "<script>window.location.href='../shp/?mod=$mod';</script>";
}
else
{ $sql="insert into masterport (kode_pel,nama_pel) 
    values ('$group','$group_desc')";
  insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Disimpan";
  echo "<script>window.location.href='../shp/?mod=$mod';</script>";
}
?>