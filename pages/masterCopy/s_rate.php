<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

$curr=$_POST['cbocurr'];
$tglfr=fd($_POST['txtfrom']);
$tglto=fd($_POST['txtto']);
$rate=nb($_POST['txtrate']);
$ratejual=nb($_POST['txtratejual']);
$ratebeli=nb($_POST['txtratebeli']);

$begin = new DateTime($tglfr);
$end   = new DateTime($tglto);
for($i = $begin; $i <= $end; $i->modify('+1 day'))
{	$tglnya = $i->format("Y-m-d");
	$cek=flookup("curr","masterrate","curr='$curr' and tanggal='$tglnya'");
	if ($cek!="" and $id_item=="")
	{ $_SESSION['msg'] = "XData Sudah Ada";
	  echo "<script>window.location.href='index.php?mod=$mod';</script>";
	}
	else if ($id_item!="")
	{ $sql="update masterrate set rate='$rate',rate_jual='$ratejual',rate_beli='$ratebeli' 
		where curr='$curr' and tanggal='$tglnya'";
	  insert_log($sql,$user);
	  $_SESSION['msg'] = "Data Berhasil Dirubah";
	  echo "<script>window.location.href='index.php?mod=$mod';</script>";
	}
	else
	{ $sql="insert into masterrate (curr,tanggal,rate,rate_jual,rate_beli) 
	    values ('$curr','$tglnya','$rate','$ratejual','$ratebeli')";
	  insert_log($sql,$user);
	}
}
$_SESSION['msg'] = "Data Berhasil Disimpan";
echo "<script>window.location.href='index.php?mod=$mod';</script>";
?>