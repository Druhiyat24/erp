<?php
include '../../include/conn.php';
include '../forms/fungsi.php';
include "f_calc_jk.php";
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$mode="";
$mod=$_GET['mod'];

$txtnik = nb($_POST['txtnik']);
$txttanggal = fd($_POST['txttanggal']);
$txtkode_absen = nb($_POST['txtkode_absen']);
if (isset($_POST['txtalasan'])) {$txtalasan = nb($_POST['txtalasan']);} else {$txtalasan = "";}
$txtketerangan = nb($_POST['txtketerangan']);
$cek = flookup("count(*)","hr_ijinkaryawan","nik='$txtnik' and tanggal='$txttanggal'");
if ($cek=="0")
{	$sql = "insert into hr_ijinkaryawan (nik,tanggal,kode_absen,
		alasan,keterangan) values ('$txtnik','$txttanggal',
		'$txtkode_absen','$txtalasan','$txtketerangan')";
	insert_log($sql,$user);
	$cek = flookup("count(*)","hr_timecard","empno='$txtnik' and workdate='$txttanggal'");
	if ($cek=="0")
	{	$sql = "insert into hr_timecard (empno,workdate,absentcode,
			workhour) values ('$txtnik','$txttanggal',
			'$txtkode_absen','0')";
		insert_log($sql,$user);
	}
	$_SESSION['msg']="Data Berhasil Disimpan";
	echo "<script>
		 window.location.href='../hr/?mod=20L';
	</script>";
}
else
{	$sql="update hr_ijinkaryawan set kode_absen='$txtkode_absen',
		alasan='$txtalasan',keterangan='$txtketerangan' where nik='$txtnik' and tanggal='$txttanggal'";
	insert_log($sql,$user);
	$_SESSION['msg']="Data Berhasil Dirubah";
	echo "<script>
		 window.location.href='../hr/?mod=20L';
	</script>";
}
?>