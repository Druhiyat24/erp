<?php 
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode=$_GET['mode'];

$RakArray = $_POST['rak_rollk'];
#VALIDASI JUMLAH TOTAL ROLL
foreach ($RakArray as $key => $value) 
{	if ($value != '')
	{	$cri=explode("|",$key);
		$id_det=$cri[0];
		$id_hea=$cri[1];
		$txtrak = $RakArray[$key];
    $sql="update bpb_roll set id_rak_loc='$txtrak' where id='$id_det'";
    insert_log($sql,$user);
    $sql="update bpb_roll_h set location='Ok' where id='$id_hea'";
    insert_log($sql,$user);
  }
}	
$_SESSION['msg'] = "Data Berhasil Disimpan";
echo "<script>window.location.href='../forms/?mod=$mod';</script>";
?>