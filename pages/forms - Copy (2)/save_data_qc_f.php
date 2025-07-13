<?php 
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode=$_GET['mode'];

$tgl_cfm = date('Y-m-d H-i-s');

if (!isset($_POST['cbostatus']))
{	$_SESSION['msg'] = "XTidak Ada Data Yang Harus Dikonfirmasi";
	echo "<script>window.location.href='index.php?mod=$mod';</script>"; 
}
else
{	$StatusArr = $_POST['cbostatus'];
	$QtyArr = $_POST['itemqc'];
	$ByArr = $_POST['itemby'];
	$DefArr = $_POST['itemdef'];
	foreach ($StatusArr as $key => $value) 
	{	$status=$value;
		$txtqtycek=$QtyArr[$key];
		if ($status=="Pass") { $chk="Y"; }
		else if ($status=="Reject") { $chk="R"; }
		else if ($status=="Pass With Condition") { $chk="C"; }
		else $chk="";
		$keysplit=explode("|",$key);
		$id_item=$keysplit[0];
		$txtbppbno=$keysplit[1];
		$txtcekby = $ByArr[$key];
		$txtdefect= $DefArr[$key];
		if ($chk!="")
		{	$sql = "update bpb set dicekqc='$chk',dicekqc_date='$tgl_cfm',
				dicekqc_qty='$txtqtycek',dicekqc_by='$txtcekby',id_defect='$txtdefect' 
				where bpbno='$txtbppbno' and id_item='$id_item'";
			insert_log($sql,$user);
		}
	}	
	$_SESSION['msg'] = "Data Berhasil Disimpan";
	echo "<script>window.location.href='../forms/?mod=23z&mode=$mode';</script>";
}
?>