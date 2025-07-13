<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

$kode=nb($_POST['txtkode']);
$item=nb($_POST['txtitem']);
$cek=flookup("goods_code","masteritem_odo","goods_code='$kode'");
if($cek!="" and $id_item=="")
{ $_SESSION['msg'] = "XData Sudah Ada"; }
else if ($cek=="" and $id_item!="")
{ 
	// $sql="update masteritem_odo set goods_code='$kode',
	// 	itemdesc='$item' where id='$id_item'";
	// insert_log($sql,$user);
 //  $_SESSION['msg'] = "Data Berhasil Dirubah";
}
else if ($cek!="" and $id_item!="")
{ 
	// $sql="update masteritem_odo set itemdesc='$item' where id='$id_item'";
	// insert_log($sql,$user);
 //  $_SESSION['msg'] = "Data Berhasil Dirubah";
}
else
{ 
	$sql="insert into masteritem_odo (goods_code,itemdesc) 
    values ('$kode','$item')";
  insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Disimpan";
}
echo "<script>window.location.href='../master/?mod=$mod';</script>";
?>