<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

$dept=nb($_POST['txtdept']);
$cek=flookup("nama_pilihan","masterpilihan","kode_pilihan='Dept'
	and nama_pilihan='$dept'");
if ($cek!="")
{ $_SESSION['msg'] = "XData Sudah Ada";
  echo "<script>window.location.href='../hr/?mod=16L';</script>";
}
else if ($id_item!="")
{ $sql="update masterpilihan set nama_pilihan='$dept'
    where nama_pilihan='$id_item' and kode_pilihan='Dept' ";
	insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Dirubah";
  echo "<script>window.location.href='../hr/?mod=16L';</script>";
}
else
{ $sql="insert into masterpilihan (kode_pilihan,nama_pilihan) 
    values ('Dept','$dept')";
  insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Disimpan";
  echo "<script>window.location.href='../hr/?mod=16L';</script>";
}
?>