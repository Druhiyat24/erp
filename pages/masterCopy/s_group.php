<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

$group=nb($_POST['txtgroup']);
$group_desc=nb($_POST['txtgroup_desc']);
$cek=flookup("kode_group","mastergroup","kode_group='$group'");
if ($cek!="")
{ $_SESSION['msg'] = "XData Sudah Ada";
  echo "<script>window.location.href='index.php?mod=$mod';</script>";
}
else if ($id_item!="")
{ $cek=flookup("id_group","mastersubgroup","id_group='$id_item'");
	if($cek=="")
	{	$sql="update mastergroup set kode_group='$group',
			nama_group='$group_desc' where id='$id_item'";
		insert_log($sql,$user);
	  $_SESSION['msg'] = "Data Berhasil Dirubah";
  }
  else
  { $_SESSION['msg'] = "XData Tidak Bisa Dirubah"; }
	echo "<script>window.location.href='index.php?mod=$mod';</script>";
}
else
{ $sql="insert into mastergroup (kode_group,nama_group) 
    values ('$group','$group_desc')";
  insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Disimpan";
  echo "<script>window.location.href='index.php?mod=$mod';</script>";
}
?>