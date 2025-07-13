<?php
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$mode="";
$mod=$_GET['mode'];

$txtptkpcode = strtoupper($_POST['txtptkpcode']);
$txtptkpdesc = strtoupper($_POST['txtptkpdesc']);
$txtptkp = strtoupper($_POST['txtptkp']);
$cek = flookup("count(*)","hr_masterptkp","ptkpcode='$txtptkpcode'");
if ($cek=="0")
{	$sql = "insert into hr_masterptkp (ptkpcode,ptkpdesc,ptkp)
		values ('$txtptkpcode','$txtptkpdesc','$txtptkp')";
	insert_log($sql,$user);
	echo "<script>
		 alert('Data berhasil disimpan');
		 window.location.href='../hr/?mod=6';
	</script>";
}
else
{	$sql = "update hr_masterptkp set ptkpdesc='$txtptkpdesc',
		ptkp='$txtptkp' where ptkpcode='$txtptkpcode'"; 
	insert_log($sql,$user);
	echo "<script>
		 alert('Data berhasil dirubah');
		 window.location.href='../hr/?mod=6';
	</script>";
}
?>