<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_rate = $_GET['id']; } else {$id_rate = "";}

$cek=flookup("id_item","masteritem","id_gen='$id_rate'");
if ($cek=="")
{	$sql="delete from masterdesc where id='$id_rate'";
	insert_log($sql,$user);
	$_SESSION['msg'] = "Data Berhasil Dihapus";
	echo "<script>window.location.href='../master/?mod=$mod';</script>";
}
else
{	$_SESSION['msg'] = "Data Tidak Bisa Dihapus";
	echo "<script>window.location.href='../master/?mod=$mod';</script>";
}
?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> 
<script src="js/Masterdesc.js"></script>