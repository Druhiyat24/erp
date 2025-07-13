<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

$bank=nb($_POST['txtbank']);
$bank_desc=nb($_POST['txtbank_desc']);
$curr=nb($_POST['txtcurr']);
$norek=nb($_POST['txtnorek']);
$namarek=nb($_POST['txtnamarek']);
if(isset($_POST['txtcoa'])) {$txtcoa=nb($_POST['txtcoa']);} else {$txtcoa="";}
$cek=flookup("kode_bank","masterbank","kode_bank='$bank'");
if ($cek!="" and $id_item=="")
{ $_SESSION['msg'] = "XData Sudah Ada";
  echo "<script>window.location.href='../master/?mod=$mod';</script>";
}
else if ($id_item!="")
{ $sql="update masterbank set id_coa='$txtcoa',kode_bank='$bank',
		nama_bank='$bank_desc',curr='$curr',no_rek='$norek',
		nama_rek='$namarek' where id='$id_item'";
	insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Dirubah";
  echo "<script>window.location.href='../master/?mod=$mod';</script>";
}
else
{ $sql="insert into masterbank (id_coa,kode_bank,nama_bank,curr,no_rek,nama_rek) 
    values ('$txtcoa','$bank','$bank_desc','$curr','$norek','$namarek')";
  insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Disimpan";
  echo "<script>window.location.href='../master/?mod=$mod';</script>";
}
?>