<?php 
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode=$_GET['mode'];

$QtyArr = $_POST['txtqtyact'];
#VALIDASI JUMLAH TOTAL ROLL
foreach ($QtyArr as $key => $value) 
{	if ($value != '')
	{	$cri=explode("|",$key);
		$id_det=$cri[0];
		$id_hea=$cri[1];
		$txtqtyact = $QtyArr[$key];
    $sql="update bpb_roll set roll_qty='$txtqtyact' where id='$id_det'";
    insert_log($sql,$user);
  }
}	
$_SESSION['msg'] = "Data Berhasil Disimpan";
echo "<script>window.location.href='../forms/?mod=18v';</script>";
?>