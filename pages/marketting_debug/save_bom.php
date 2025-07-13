<?php 
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode=$_GET['mode'];

$cek = nb($_POST['txtsjno']);
$cek_arr=explode("|",$cek);
$bpbno=$cek_arr[0];
$id_item=$cek_arr[1];
$qty_bpb=$_POST['txtqtybpb'];
$cek=flookup("bpbno","bpb_roll","bpbno='$bpbno' and id_item='$id_item'");
if (!isset($_POST['jml_roll']))
{	$_SESSION['msg'] = "XTidak Ada Data";
	echo "<script>window.location.href='index.php?mod=$mod';</script>"; 
}
else if ($cek!="")
{	$_SESSION['msg'] = "XData Sudah Ada";
	echo "<script>window.location.href='index.php?mod=$mod';</script>"; 
}
else
{	$JmlArray = $_POST['jml_roll'];
	$NoArray = $_POST['no_roll'];
	#VALIDASI JUMLAH TOTAL ROLL
	$tot_roll=0;
	foreach ($JmlArray as $key => $value) 
	{	if (is_numeric($value))
		{	$txtjml_roll = $JmlArray[$key];
	    $tot_roll=(int)$tot_roll + (int)$txtjml_roll;
	  }
	}
	if ((int)$qty_bpb<>(int)$tot_roll)
	{	$_SESSION['msg'] = "XJumalah Detail Roll Tidak Sesuai";
		echo "<script>window.location.href='index.php?mod=$mod';</script>";
		exit;
	}
	foreach ($JmlArray as $key => $value) 
	{	if (is_numeric($value))
		{	$txtno_roll = $NoArray[$key];
	    $txtjml_roll = $JmlArray[$key];
	    $cek = flookup("count(*)","bpb_roll","id_item='$txtjml_roll' and 
				bpbno='$bpbno'");
			if ($cek=="0")
			{	$sql = "insert into bpb_roll (bpbno,id_item,roll_no,roll_qty)
					values ('$bpbno','$id_item','$txtno_roll','$txtjml_roll')";
				insert_log($sql,$user);
			}
	  }
	}	
	$_SESSION['msg'] = "Data Berhasil Disimpan";
	echo "<script>window.location.href='index.php?mod=$mod&mode=$mode';</script>";
}
?>