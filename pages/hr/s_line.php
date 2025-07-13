<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

$line=nb($_POST['txtline']);
$cek=flookup("nama_pilihan","masterpilihan","kode_pilihan='Line'
	and nama_pilihan='$line'");
if ($cek!="")
{ $_SESSION['msg'] = "XData Sudah Ada";
  echo "<script>window.location.href='../hr/?mod=17L';</script>";
}
else if ($id_item!="")
{ $sql="update masterpilihan set nama_pilihan='$line'
    where nama_pilihan='$id_item' and kode_pilihan='Line'";
	insert_log($sql,$user);
  $sql="update mastersupplier set supplier='$line'
    where supplier='$id_item' and area='LINE'";
  insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Dirubah";
  echo "<script>window.location.href='../hr/?mod=17L';</script>";
}
else
{ $sql="insert into masterpilihan (kode_pilihan,nama_pilihan) 
    values ('Line','$line')";
  insert_log($sql,$user);
  $sql="insert into mastersupplier (area,supplier,tipe_sup) 
    values ('LINE','$line','L')";
  insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Disimpan";
  echo "<script>window.location.href='../hr/?mod=17L';</script>";
}
?>