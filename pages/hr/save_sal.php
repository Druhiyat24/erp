<?php
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$mode="";

$txtnik = strtoupper($_POST['txtnik']);
$txtnama_bank = strtoupper($_POST['txtnama_bank']);
$txtno_rekening = strtoupper($_POST['txtno_rekening']);
$txtptkpcode = strtoupper($_POST['txtptkpcode']);
$txtgaji_pokok = strtoupper($_POST['txtgaji_pokok']);
$txtt_jabatan = strtoupper($_POST['txtt_jabatan']);
$txtt_luarkota = strtoupper($_POST['txtt_luarkota']);
$txtt_pulsa = strtoupper($_POST['txtt_pulsa']);
$txtt_lain = strtoupper($_POST['txtt_lain']);
$cek = flookup("count(*)","hr_mastersalary","nik='$txtnik'");
if ($cek=="0")
{	$sql = "insert into hr_mastersalary (nik,nama_bank,no_rekening,ptkpcode,gaji_pokok,t_jabatan,t_luarkota,t_pulsa,t_lain)
		values ('$txtnik','$txtnama_bank','$txtno_rekening','$txtptkpcode','$txtgaji_pokok','$txtt_jabatan','$txtt_luarkota','$txtt_pulsa','$txtt_lain')";
	insert_log($sql,$user);
	echo "<script>
		 alert('Data berhasil disimpan');
		 window.location.href='../hr/?mod=12';
	</script>";
}
else
{	$sql="update hr_mastersalary set nama_bank='$txtnama_bank',
		no_rekening='$txtno_rekening',ptkpcode='$txtptkpcode',gaji_pokok='$txtgaji_pokok',
		t_jabatan='$txtt_jabatan',t_luarkota='$txtt_luarkota',t_pulsa='$txtt_pulsa',
		t_lain='$txtt_lain' where nik='$txtnik'";
	insert_log($sql,$user);
	$sql="update hr_masteremployee set ptkp='$txtptkpcode' where nik='$txtnik'";
	insert_log($sql,$user);
	echo "<script>
		 alert('Data Berhasil Disimpan');
		 window.location.href='../hr/?mod=12';
	</script>";
}
?>