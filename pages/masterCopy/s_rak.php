<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

$rak=nb($_POST['txtrak']);
$rak_desc=nb($_POST['txtrak_desc']);
$cek=flookup("kode_rak","master_rak","kode_rak='$rak'");
if ($cek!="" and $id_item=="")
{ $_SESSION['msg'] = "XData Sudah Ada"; }
else if ($cek=="" and $id_item!="")
{ $sql="update master_rak set kode_rak='$rak',
		nama_rak='$rak_desc' where id='$id_item'";
	insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Dirubah";
}
else if ($cek!="" and $id_item!="")
{ $sql="update master_rak set nama_rak='$rak_desc' where id='$id_item'";
	insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Dirubah";
}
else
{ $sql="insert into master_rak (kode_rak,nama_rak) 
    values ('$rak','$rak_desc')";
  insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Disimpan";
}
echo "<script>window.location.href='../master/?mod=$mod';</script>";
?>