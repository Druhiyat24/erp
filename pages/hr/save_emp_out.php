<?php
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$mode="";

$txtnik = nb($_POST['txtnik']);
$txttgl = fd($_POST['txtemptermdate']);

$sql = "update hr_masteremployee set selesai_kerja='$txttgl'
	where nik='$txtnik'";
insert_log($sql,$user);
echo "<script>
	alert('Data berhasil dirubah');
	window.location.href='../hr/?mod=14';
	</script>";
?>