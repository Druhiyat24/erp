<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

$id_group=$_POST['cboid'];
$group=nb($_POST['txtsub_group']);
$group_desc=nb($_POST['txtsub_group_desc']);
$txtcoad=nb($_POST['txtcoad']);
$txtcoak=nb($_POST['txtcoak']);
$cek=flookup("kode_sub_group","mastersubgroup","id_group='$id_group' and kode_sub_group='$group'");
if ($cek!="" and $id_item=="")
{ $_SESSION['msg'] = "XData Sudah Ada";
  echo "<script>window.location.href='index.php?mod=$mod';</script>";
}
else if ($id_item!="")
{ $cek=flookup("id_sub_group","mastertype2","id_sub_group='$id_item'");
  if($cek=="")
  { $sql="update mastersubgroup set id_group='$id_group',
      kode_sub_group='$group',nama_sub_group='$group_desc',id_coa_d='$txtcoad',id_coa_k='$txtcoak' 
      where id='$id_item'";
    insert_log($sql,$user);
    $_SESSION['msg'] = "Data Berhasil Dirubah";
  }
  else
  { $_SESSION['msg'] = "XData Tidak Bisa Dirubah"; }
  echo "<script>window.location.href='index.php?mod=$mod';</script>";
}
else
{ $sql="insert into mastersubgroup (id_group,kode_sub_group,nama_sub_group,id_coa_d,id_coa_k) 
    values ('$id_group','$group','$group_desc','$txtcoad','$txtcoak')";
  insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Disimpan";
  echo "<script>window.location.href='index.php?mod=$mod';</script>";
}
?>