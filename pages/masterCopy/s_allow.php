<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

$id_sub_group=$_POST['cboid'];
$txtqtyfrom=$_POST['txtqtyfrom'];
$txtqtyto=$_POST['txtqtyto'];
$txtallow=$_POST['txtallow'];
$cek=flookup("id_sub_group","masterallow","id_sub_group='$id_sub_group' 
	and ((qty1<=$txtqtyfrom and qty2>=$txtqtyfrom) or 
  (qty1<=$txtqtyto and qty2>=$txtqtyto))");
if ($cek!="")
{ $_SESSION['msg'] = "XData Sudah Ada";
  echo "<script>window.location.href='index.php?mod=$mod';</script>";
}
else if ($id_item!="")
{ $sql="update masterallow set qty1='$txtqtyfrom',
	qty2='$txtqtyto',allowance='$txtallow' where id='$id_item'";
  insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Dirubah";
  echo "<script>window.location.href='index.php?mod=$mod';</script>";
}
else
{ $sql="insert into masterallow (id_sub_group,qty1,qty2,allowance) 
    values ('$id_sub_group','$txtqtyfrom','$txtqtyto','$txtallow')";
  insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Disimpan";
  echo "<script>window.location.href='../master/?mod=$mod';</script>";
}
?>