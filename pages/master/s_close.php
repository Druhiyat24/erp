<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

$closing=nb($_POST['txtclosing']);
$closing=date('Y-m',strtotime($closing));
$closing_desc=nb($_POST['txtclosing_desc']);
$tgl_down=date('Y-m-d H:m:s');
$cek=flookup("closing_periode","tbl_closing","closing_periode='$closing'");
if ($cek!="")
{ $_SESSION['msg'] = "XData Sudah Ada";
  echo "<script>window.location.href='index.php?mod=$mod';</script>";
}
else if ($id_item!="")
{ $sql="update tbl_closing set closing_periode='$closing',
		closing_ket='$closing_desc' where id='$id_item'";
	insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Dirubah";
  echo "<script>window.location.href='index.php?mod=$mod';</script>";
}
else
{ $sql="insert into tbl_closing (closing_periode,closing_ket,username,closing_date) 
    values ('$closing','$closing_desc','$user','$tgl_down')";
  insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Disimpan";
  echo "<script>window.location.href='index.php?mod=$mod';</script>";
}
?>