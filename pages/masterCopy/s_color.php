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
$pantone=nb($_POST['txtpantone']);
if (isset($_FILES['txtfile']))
{ $nama_file = $_FILES['txtfile']['name'];
  $tmp_file = $_FILES['txtfile']['tmp_name'];
  $path = "upload_files/color/".$nama_file;
  move_uploaded_file($tmp_file, $path);
}
else
{ $nama_file=""; }
$cek=flookup("kode_color","mastercolor","id_weight='$id_sub_group' and 
  kode_color='$type'");
if ($cek!="")
{ $_SESSION['msg'] = "XData Sudah Ada";
  echo "<script>window.location.href='index.php?mod=$mod';</script>";
}
else if ($id_item!="")
{ $cek=flookup("id_color","masterdesc","id_color='$id_item'");
  if($cek=="")
  { $sql="update mastercolor set id_weight='$id_sub_group',
    kode_color='$type',nama_color='$type_desc',phantom='$pantone'
      where id='$id_item'";
    insert_log($sql,$user);
    $_SESSION['msg'] = "Data Berhasil Dirubah";
  }
  else
  { $_SESSION['msg'] = "XData Tidak Bisa Dirubah"; }
  echo "<script>window.location.href='index.php?mod=$mod';</script>";
}
else
{ $sql="insert into mastercolor (id_weight,kode_color,nama_color,phantom,nm_file) 
    values ('$id_sub_group','$type','$type_desc','$pantone','$nama_file')";
  insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Disimpan";
  echo "<script>window.location.href='index.php?mod=$mod';</script>";
}
?>