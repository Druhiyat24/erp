<?php 
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode=$_GET['mode'];
$dateinput=date("Y-m-d H:i:s");
$cek = nb($_POST['txtsjno']);
$cek_arr=explode("|",$cek);
$bpbno=$cek_arr[0];
$id_item=$cek_arr[1];
$id_jo=$cek_arr[2];
$qty_bpb=$_POST['txtqtybpb'];
$qty_bpb_act=$_POST['txtqtybpbact'];
$unit_det=$_POST['txtunitdet'];
$cek=flookup("bpbno","bpb_roll_h","bpbno='$bpbno' and id_item='$id_item' and id_jo='$id_jo'");
if (!isset($_POST['jml_roll']))
{	$_SESSION['msg'] = "XTidak Ada Data";
	echo "<script>window.location.href='index.php?mod=$mod';</script>"; 
}
else if ($cek!="")
{	$_SESSION['msg'] = "XData Sudah Ada";
	echo "<script>window.location.href='index.php?mod=$mod';</script>"; 
}
else
{	$sql = "update bpb set qty='$qty_bpb_act' where bpbno='$bpbno' and id_jo='$id_jo' and 
		id_item='$id_item'";
	insert_log($sql,$user);
	$sql = "insert into bpb_roll_h (bpbno,id_jo,id_item,username,dateinput)
		values ('$bpbno','$id_jo','$id_item','$user','$dateinput')";
	insert_log($sql,$user);
	$cekid=flookup("id","bpb_roll_h","bpbno='$bpbno' and id_item='$id_item' and id_jo='$id_jo'");
				
	$JmlArray = $_POST['jml_roll'];
	$JmlActArray=$_POST['jml_rollact'];
	$FOCArray = $_POST['jmlf_roll'];
	$NoArray = $_POST['no_roll'];
	$LotArray = $_POST['lot'];
	$BarArray = $_POST['bar_rollk'];
	$RakArray = $_POST['rak_rollk'];
	#VALIDASI JUMLAH TOTAL ROLL
	$tot_roll=0;
	foreach ($JmlArray as $key => $value) 
	{	if (is_numeric($value))
		{	$txtno_roll = $NoArray[$key];
	    $txtjml_roll = $JmlArray[$key];
	    $txtjml_roll_act = $JmlActArray[$key];
	    $txtjml_foc = $FOCArray[$key];
	    $txtlot = $LotArray[$key];
	    $txtbar = $BarArray[$key];
	    $txtrak = $RakArray[$key];
	    $sql = "insert into bpb_roll (id_h,roll_no,lot_no,roll_qty,roll_foc,unit,barcode,id_rak,roll_qty_act,roll_qty_used)
				values ('$cekid','$txtno_roll','$txtlot','$txtjml_roll','$txtjml_foc',
				'$unit_det','$txtbar','$txtrak','$txtjml_roll_act','0')";
			insert_log($sql,$user);
	  }
	}	
	$_SESSION['msg'] = "Data Berhasil Disimpan";
	echo "<script>window.location.href='index.php?mod=$mod&mode=$mode';</script>";
}
?>