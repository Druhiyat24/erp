<?php
$mod=$_GET['mod'];
$grafik=flookup("grafik","mastercompany","company!='' ");
$user=$_SESSION['username'];
if ($mod==1)
{	include "setting.php"; }
elseif ($mod=="reset")
{	$usernya=$_GET['id'];
	$sql="update userpassword set password='123' where username='$usernya'";
	insert_log($sql,$user);
	$_SESSION['msg'] = "Password Reset To 123";
	echo "<script>window.location.href='../setting/?mod=1';</script>";
}
elseif ($mod=="inactive")
{	$usernya=$_GET['id'];
	$sql="update userpassword set aktif='N' where username='$usernya'";
	insert_log($sql,$user);
	$_SESSION['msg'] = "User Berhasil Di Non Aktifkan";
	echo "<script>window.location.href='../setting/?mod=1';</script>";
}
elseif ($mod=="active")
{	$usernya=$_GET['id'];
	$sql="update userpassword set aktif='Y' where username='$usernya'";
	insert_log($sql,$user);
	$_SESSION['msg'] = "User Berhasil Di Aktifkan";
	echo "<script>window.location.href='../setting/?mod=1';</script>";
}
elseif ($mod==2)
{	include "user_setting.php"; }
?>