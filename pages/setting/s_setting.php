<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];

$txtusername = $_POST['txtusername'];
$txtFullName = nb($_POST['txtFullName']);
$txtPassword = $_POST['txtPassword'];
$txtnik = nb($_POST['txtnik']);
$txtGroupp = nb($_POST['txtGroupp']);
$txtkode_mkt = nb($_POST['txtkode_mkt']);
$cek = flookup("count(*)","userpassword","username='$txtusername'");
if ($cek=="0")
{	$sql = "insert into userpassword (username,FullName,Password,nik,Groupp,kode_mkt)
		values ('$txtusername','$txtFullName','$txtPassword','$txtnik','$txtGroupp','$txtkode_mkt')";
	insert_log($sql,$user);
	$_SESSION['msg'] = 'Data Berhasil Disimpan';
	echo "<script>
		 window.location.href='../setting/?mod=$mod';
	</script>";
}
else
{	$_SESSION['msg'] = 'XData Sudah Ada';
	echo "<script>
		 window.location.href='../setting/?mod=$mod';
	</script>";
}
?>