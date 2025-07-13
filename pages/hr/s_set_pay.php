<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
$nik=$_POST['txtNIK'];

$chkh_arr = $_POST['chkhide'];
if (isset($_POST['itemchk'])) {$chk_arr = $_POST['itemchk'];} else {$chk_arr = "";}
foreach ($chkh_arr as $key => $value) 
{	$nm_fld=$key;
	if (isset($chk_arr[$key]))
	{	$sql="update hr_masteremployee set ".$nm_fld."='1' where 
			nik='$nik'";
		insert_log($sql,$user);
	}
	else
	{	$sql="update hr_masteremployee set ".$nm_fld."='0' where 
			nik='$nik'";
		insert_log($sql,$user);
	}
}
$_SESSION['msg'] = "Data Berhasil Dirubah";
echo "<script>window.location.href='../hr/?mod=$mod';</script>";
?>