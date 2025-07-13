<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

$lokasi=nb($_POST['txtlokasi']);
$cek=flookup("nama_pilihan","masterpilihan","kode_pilihan='Lokasi'
	and nama_pilihan='$lokasi'");
if ($cek!="")
{ $_SESSION['msg'] = "XData Sudah Ada";
  echo "<script>window.location.href='../hr/?mod=3L';</script>";
}
else if ($id_item!="")
{ $sql="update masterpilihan set nama_pilihan='$lokasi'
		where kode_pilihan='Lokasi' and nama_pilihan='$id_item'";
	insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Dirubah";
  echo "<script>window.location.href='../hr/?mod=3L';</script>";
}
else
{ $sql="insert into masterpilihan (kode_pilihan,nama_pilihan) 
    values ('Lokasi','$lokasi')";
  insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Disimpan";
  echo "<script>window.location.href='../hr/?mod=3L';</script>";
}
?>