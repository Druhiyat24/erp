<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

$hs_code=nb($_POST['txthscode']);
$hs_desc=nb($_POST['txthsdesc']);
$hs_tarif=nb($_POST['txthstarif']);
$cek=flookup("kode_hs","masterhs","kode_hs='$hs_code'");
if ($cek!="" and $id_item=="")
{ $_SESSION['msg'] = "XData Sudah Ada";
  echo "<script>window.location.href='../master/?mod=$mod';</script>";
}
else if ($id_item!="")
{ $sql="update masterhs set nama_hs='$hs_desc',kode_hs='$hs_code',
		tarif_hs='$hs_tarif' where id='$id_item'";
	insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Dirubah";
  echo "<script>window.location.href='../master/?mod=$mod';</script>";
}
else
{ $sql="insert into masterhs (kode_hs,nama_hs,tarif_hs) 
    values ('$hs_code','$hs_desc','$hs_tarif')";
  insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Disimpan";
  echo "<script>window.location.href='../master/?mod=$mod';</script>";
}
?>