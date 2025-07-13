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
$lead_time=$_POST['txtlead_time'];
$cek=flookup("kode_route","masterroute","kode_route='$group'");
if ($cek!="" and $id_item=="")
{ $_SESSION['msg'] = "XData Sudah Ada";
  echo "<script>window.location.href='../shp/?mod=$mod';</script>";
}
else if ($id_item!="")
{ $sql="update masterroute set kode_route='$group',
		nama_route='$group_desc',lead_time='$lead_time' where id='$id_item'";
	insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Dirubah";
  echo "<script>window.location.href='../shp/?mod=$mod';</script>";
}
else
{ $sql="insert into masterroute (kode_route,nama_route,lead_time) 
    values ('$group','$group_desc','$lead_time')";
  insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Disimpan";
  echo "<script>window.location.href='../shp/?mod=$mod';</script>";
}
?>