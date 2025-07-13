<?php
include '../../include/conn.php';
include 'fungsi.php';

session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$jen_trans=strtoupper($_POST['txttrans']);
$nom_trans=strtoupper($_POST['txtfrom']);
$to=date('Y-m-d',strtotime($_POST['txtdate']));

if ($jen_trans=="PEMASUKAN")
{ $nm_tbl="bpb"; $nm_fld="bpbno"; $nm_fld2="bpbdate"; }
else
{ $nm_tbl="bppb"; $nm_fld="bppbno"; $nm_fld2="bppbdate"; }

$sql="update $nm_tbl set $nm_fld2='$to' where $nm_fld='$nom_trans'";
insert_log($sql,$user);

echo "<script>
	alert('Data berhasil dirubah');
	window.location.href='index.php?mod=111';
	</script>";
?>