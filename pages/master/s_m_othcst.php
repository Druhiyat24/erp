<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

$others_code=nb($_POST['txtotherscode']);
$others_desc=nb($_POST['txtothersdesc']);
$cek=flookup("otherscode","masterothers","otherscode='$others_code'");
if ($cek!="")
{ $_SESSION['msg'] = "XData Sudah Ada";
  echo "<script>window.location.href='../master/?mod=$mod';</script>";
}
else if ($id_item!="")
{ $sql="update masterothers set otherscode='$others_code',
		othersdesc='$others_desc' where id='$id_item'";
	insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Dirubah";
  echo "<script>window.location.href='../master/?mod=$mod';</script>";
}
else
{ $sql="insert into masterothers (otherscode,othersdesc) 
    values ('$others_code','$others_desc')";
  insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Disimpan";
  echo "<script>window.location.href='../master/?mod=$mod';</script>";
}
?>