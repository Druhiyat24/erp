<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id = $_GET['id']; } else {$id = "";}

$nama_panel=nb($_POST['nama_panel']);
$description=nb($_POST['description']);
$code_panel=nb($_POST['code_panel']);


if ($id!="")
{ $sql="UPDATE masterpanel SET nama_panel = '$nama_panel', description = '$description', v_codepanel='$code_panel' WHERE id = '$id'";
	insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Dirubah";
  echo "<script>window.location.href='../master/?mod=$mod';</script>";
}
else
{ $sql="insert into masterpanel (nama_panel,description,v_codepanel) 
    values ('$nama_panel','$description','$code_panel')";
  insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Disimpan";
  echo "<script>window.location.href='../master/?mod=$mod';</script>";
}
?>