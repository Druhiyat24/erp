<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

$id_sub_group=$_POST['cboid'];
$type=nb($_POST['txttype']);
$type_desc=nb($_POST['txttype_desc']);
$cek=flookup("kode_length","masterlength","id_width='$id_sub_group' and kode_length='$type'");
if ($cek!="")
{ $_SESSION['msg'] = "XData Sudah Ada";
  echo "<script>window.location.href='index.php?mod=$mod';</script>";
}
else if ($id_item!="")
{ $cek=flookup("id_length","masterweight","id_length='$id_item'");
  if($cek=="")
  { $sql="update masterlength set id_width='$id_sub_group',
    kode_length='$type',nama_length='$type_desc' 
    where id='$id_item'";
    insert_log($sql,$user);
    $_SESSION['msg'] = "Data Berhasil Dirubah";
  }
  else
  { $_SESSION['msg'] = "XData Tidak Bisa Dirubah"; }
  echo "<script>window.location.href='index.php?mod=$mod';</script>";
}
else
{ $sql="insert into masterlength (id_width,kode_length,nama_length) 
    values ('$id_sub_group','$type','$type_desc')";
  insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Disimpan";
  echo "<script>window.location.href='index.php?mod=$mod';</script>";
}
?>