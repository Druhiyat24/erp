<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

$jabatan=nb($_POST['txtjabatan']);
$cek=flookup("nama_pilihan","masterpilihan","kode_pilihan='Jabatan'
	and nama_pilihan='$jabatan'");
if ($cek!="")
{ $_SESSION['msg'] = "XData Sudah Ada";
  echo "<script>window.location.href='index.php?mod=$mod';</script>";
}
else if ($id_item!="")
{ $sql="update masterpilihan set nama_pilihan='$jabatan'
    where nama_pilihan='$id_item' and kode_pilihan='Jabatan'";
	insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Dirubah";
  echo "<script>window.location.href='index.php?mod=$mod';</script>";
}
else
{ $sql="insert into masterpilihan (kode_pilihan,nama_pilihan) 
    values ('Jabatan','$jabatan')";
  insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Disimpan";
  echo "<script>window.location.href='index.php?mod=$mod';</script>";
}
?>