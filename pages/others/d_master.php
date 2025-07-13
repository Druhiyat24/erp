<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user = $_SESSION['username'];
$sesi = $_SESSION['sesi'];
if (isset($_GET['mode'])) {$mode=$_GET['mode'];} else {$mode="";}
if (isset($_GET['mod'])) {$mod=$_GET['mod'];} else {$mod="";}

$id_item=$_GET['id'];
if (isset($_GET['noid'])) {$transno=$_GET['noid']; } else {$transno="";}
if (isset($_GET['pro'])) {$pronya=$_GET['pro']; } else {$pronya="";}

$cek = flookup("count(*)","reqnon_item","id_item='$id_item' ");
if ($cek==0)
{	$sql = "delete from masteritem where id_item='$id_item'";
	insert_log($sql,$user);
	echo "<script>
		alert('Data berhasil dihapus');
		window.location.href='../others/?mod=2L';
	</script>";
}
else
{	echo "<script>
		alert('Data tidak bisa dihapus karena sudah ada transaksi');
		window.location.href='../others/?mod=2L';
	</script>";
}
?>