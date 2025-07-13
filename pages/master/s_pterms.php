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
$pterms_days=nb($_POST['txtdays']);
if(isset($_POST['cbopar'])) {$pterms_par=nb($_POST['cbopar']);} else {$pterms_par="";}
$pterms_cri=nb($_POST['cbocri']);
if(isset($_POST['cbopt'])) {$pterms_terms=$_POST['cbopt'];} else {$pterms_terms="";}
if($pterms_terms!="")
{	$pterms=$pterms_terms." ".$pterms_days." ".$pterms_cri; 
	$cek=flookup("terms_pterms","masterpterms","terms_pterms='$pterms_terms' and days_pterms='$pterms_days' 
		and cri_pterms='$pterms_cri'");
}
else
{ $cek=flookup("kode_pterms","masterpterms","kode_pterms='$pterms'");
 }
if ($cek!="" and $id_item=="")
{ $_SESSION['msg'] = "XData Sudah Ada";
  echo "<script>window.location.href='../master/?mod=$mod';</script>";
}
else if ($cek=="" and $id_item!="")
{ $sql="update masterpterms set nama_pterms='$pterms_desc',days_pterms='$pterms_days',
		par_pterms='$pterms_par',cri_pterms='$pterms_cri',terms_pterms='$pterms_terms' 
		where id='$id_item'";
	insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Dirubah";
  echo "<script>window.location.href='../master/?mod=$mod';</script>";
}
else
{ $sql="insert into masterpterms (kode_pterms,nama_pterms,terms_pterms,days_pterms,par_pterms,cri_pterms) 
    values ('$pterms','$pterms_desc','$pterms_terms','$pterms_days','$pterms_par','$pterms_cri')";
  insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Disimpan";
  echo "<script>window.location.href='../master/?mod=$mod';</script>";
}
?>