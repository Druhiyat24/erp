<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

$jenis=$_POST['cbojenis'];
$id_sub_group=$_POST['cboid'];
if (isset($_POST['txtid_product'])) {$id_product=$_POST['txtid_product'];} else {$id_product="0";}
$type=nb($_POST['txttype']);
$type_desc=nb($_POST['txttype_desc']);
$kode_posisi=nb($_POST['txtkode_posisi']);
$nama_posisi=nb($_POST['txtnama_posisi']);
$remark=nb($_POST['txtremark']);
$type_risk=nb($_POST['cborisk']);
$cek=flookup("kode_defect","master_defect","mattype='$id_sub_group' and kode_defect='$type'");
if ($cek!="" and $id_item=="")
{ $_SESSION['msg'] = "XData Sudah Ada";
  echo "<script>window.location.href='index.php?mod=$mod';</script>";
}
else if ($id_item!="")
{ $sql="update master_defect set mattype='$id_sub_group',
		kode_defect='$type',nama_defect='$type_desc',kode_posisi='$kode_posisi',nama_posisi='$nama_posisi'
    where id_defect='$id_item'";
  insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Dirubah";
  echo "<script>window.location.href='index.php?mod=$mod';</script>";
}
else
{ $sql="insert into master_defect (jenis_defect,mattype,kode_defect,nama_defect,type_risk,
    kode_posisi,nama_posisi,remark,id_product) 
    values ('$jenis','$id_sub_group','$type','$type_desc','$type_risk',
    '$kode_posisi','$nama_posisi','$remark','$id_product')";
  insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Disimpan";
  echo "<script>window.location.href='index.php?mod=$mod';</script>";
}
?>