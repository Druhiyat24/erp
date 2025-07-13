<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
$id_cs=$_GET['id'];
$mode="";
if (isset($_GET['idd'])) {$id_cs_mfg=$_GET['idd'];} else {$id_cs_mfg="";}

$sql = "delete from act_development_mfg where id_act_cost='$id_cs' 
	and id='$id_cs_mfg'";
insert_log($sql,$user);
$_SESSION['msg'] = 'Data Berhasil Dihapus';
echo "<script>
	 window.location.href='../marketting/?mod=$mod&id=$id_cs';
</script>";
?>