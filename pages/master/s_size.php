<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

$pterms=nb($_POST['txtpterms']);
$pterms_desc=nb($_POST['txtpterms_desc']);
$cek=flookup("size","mastersize","size='$pterms_desc'");
if ($cek!="" and $id_item=="")
{ $_SESSION['msg'] = "XData Sudah Ada";
}
else if ($cek!="" and $id_item!="")
{ $sql="update mastersize set urut='$pterms' where size='$id_item'";
	insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Dirubah";
}
else if ($cek=="" and $id_item!="")
{ $sql="update mastersize set urut='$pterms',size='$pterms_desc' where size='$id_item'";
	insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Dirubah";
}
else
{ $sql="insert into mastersize (urut,size) 
    values ('$pterms','$pterms_desc')";
  insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Disimpan";
}
echo "<script>window.location.href='../master/?mod=$mod';</script>";
?>