<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

$ship_mode=nb($_POST['txtshipmode']);
$ship_desc=nb($_POST['txtshipdesc']);
$cek=flookup("shipmode","mastershipmode","shipmode='$ship_mode'");
if ($cek!="")
{ $_SESSION['msg'] = "XData Sudah Ada";
  echo "<script>window.location.href='../master/?mod=$mod';</script>";
}
else if ($id_item!="")
{ $sql="update mastershipmode set shipmode='$ship_mode',
		shipdesc='$ship_desc' where id='$id_item'";
	insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Dirubah";
  echo "<script>window.location.href='../master/?mod=$mod';</script>";
}
else
{ $sql="insert into mastershipmode (shipmode,shipdesc) 
    values ('$ship_mode','$ship_desc')";
  insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Disimpan";
  echo "<script>window.location.href='../master/?mod=$mod';</script>";
}
?>