<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
$usernya=$_GET['id'];
$kode_mkt=nb($_POST['txtkode_mkt']);
if(isset($_POST['txtnik'])) {$nik=nb($_POST['txtnik']);} else {$nik="";}
if (isset($_POST['txtGroupp'])) {$txtGroupp=nb($_POST['txtGroupp']);} else {$txtGroupp="";}

$sql="update userpassword set kode_mkt='$kode_mkt',
	groupp='$txtGroupp',nik='$nik' where username='$usernya'";
insert_log($sql,$user);

$chkh_arr = $_POST['chkhide'];
if (isset($_POST['itemchk'])) {$chk_arr = $_POST['itemchk'];} else {$chk_arr = "";}
foreach ($chkh_arr as $key => $value) 
{	$nm_fld=$key;
	if (isset($chk_arr[$key]))
	{	$sql="update userpassword set ".$nm_fld."='1' where 
			username='$usernya'";
		insert_log($sql,$user);
	}
	else
	{	$sql="update userpassword set ".$nm_fld."='0' where 
			username='$usernya'";
		insert_log($sql,$user);
	}
}
$_SESSION['msg'] = "Data Berhasil Dirubah";
echo "<script>window.location.href='../setting/?mod=1';</script>";
?>