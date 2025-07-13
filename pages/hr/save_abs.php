<?php
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$mode="";

$txtkodeabsen = strtoupper($_POST['txtkodeabsen']);
$txtketerangan = strtoupper($_POST['txtketerangan']);
$txthari = $_POST['txthari'];
$txtpot_cuti = strtoupper($_POST['txtpot_cuti']);
$cek = flookup("count(*)","hr_masterabsen","kodeabsen='$txtkodeabsen'");
if ($cek=="0")
{	$sql = "insert into hr_masterabsen (kodeabsen,keterangan,pot_cuti,jml_hari)
		values ('$txtkodeabsen','$txtketerangan','$txtpot_cuti','$txthari')";
	insert_log($sql,$user);
	echo "<script>
		 alert('Data berhasil disimpan');
		 window.location.href='../hr/?mod=10';
	</script>";
}
else
{	$sql="update hr_masterabsen set keterangan='$txtketerangan',
		pot_cuti='$txtpot_cuti' where kodeabsen='$txtkodeabsen' ";
	insert_log($sql,$user);
	echo "<script>
		 alert('Data berhasil dirubah');
		 window.location.href='../hr/?mod=10';
	</script>";
}
?>