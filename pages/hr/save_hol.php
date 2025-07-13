<?php
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$mode="";

$txtholiday_date = fd($_POST['txtholiday_date']);
$txtholiday_desc = nb($_POST['txtholiday_desc']);
$txtpot_cuti = strtoupper($_POST['txtpot_cuti']);
$cek = flookup("count(*)","hr_masterholiday","holiday_date='$txtholiday_date'");
if ($cek=="0")
{	$sql = "insert into hr_masterholiday (holiday_date,holiday_desc,pot_cuti)
		values ('$txtholiday_date','$txtholiday_desc','$txtpot_cuti')";
	insert_log($sql,$user);
	echo "<script>
		 alert('Data berhasil disimpan');
		 window.location.href='../hr/?mod=11';
	</script>";
}
else
{	$sql = "update hr_masterholiday set holiday_desc='$txtholiday_desc',
		pot_cuti='$txtpot_cuti' where holiday_date='$txtholiday_date' ";
	insert_log($sql,$user);
	echo "<script>
		 alert('Data berhasil dirubah');
		 window.location.href='../hr/?mod=11';
	</script>";
}
?>