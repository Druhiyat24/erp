<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

$cf_code=nb($_POST['txtcfcode']);
$cf_desc=nb($_POST['txtcfdesc']);
$cek=flookup("cfcode","mastercf","cfcode='$cf_code'");
if ($cek!="")
{ $_SESSION['msg'] = "XData Sudah Ada";
  echo "<script>window.location.href='../master/?mod=$mod';</script>";
}
else if ($id_item!="")
{ $sql="update mastercf set cfcode='$cf_code',
		cfdesc='$cf_desc' where id='$id_item'";
	insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Dirubah";
  echo "<script>window.location.href='../master/?mod=$mod';</script>";
}
else
{ $sql="insert into mastercf (cfcode,cfdesc) 
    values ('$cf_code','$cf_desc')";
  insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Disimpan";
  echo "<script>window.location.href='../master/?mod=$mod';</script>";
}
?>