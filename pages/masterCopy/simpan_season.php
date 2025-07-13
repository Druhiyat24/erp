<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

$season=nb($_POST['txtseason']);
$season_desc=nb($_POST['txtseason_desc']);
$cek=flookup("season","masterseason","season='$season'");
if ($cek!="")
{ $_SESSION['msg'] = "XData Sudah Ada";
  echo "<script>window.location.href='index.php?mod=$mod';</script>";
}
else
{ if ($id_item=="")
	{	$sql="insert into masterseason (season,season_desc) 
	    values ('$season','$season_desc')";
	  insert_log($sql,$user);
	  $_SESSION['msg'] = "Data Berhasil Disimpan";
  }
  else
  {	$sql="update masterseason set season='$season',
			season_desc='$season_desc' where id_season='$id_item'";
	  insert_log($sql,$user);
	  $_SESSION['msg'] = "Data Berhasil Dirubah";
  }
	echo "<script>window.location.href='index.php?mod=$mod';</script>";
}
?>