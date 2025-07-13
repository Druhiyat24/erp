<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode="";

$cri=split(':', $_POST['txtid_cost']);
$id_cost=$cri[0];
$tx_cost=$cri[1];
$txtreason=nb($_POST['txtreason']);
$date=date('Y-m-d');
$date2=date('Y-m-d H:m:s');

if($mod=="21c")
{
	insert_log("XUncancel SO # : ".$tx_cost." Dengan Alasan : ".$txtreason,$user);
	$sql="update so set revise=revise+1,cancel_h='N' where id='$id_cost'";
	insert_log($sql,$user);
	$sql="update so_det set cancel='N' where id_so='$id_cost'";
	insert_log($sql,$user);
	$_SESSION['msg'] = "Data Berhasil Diuncancel";
}
else
{
	$cekbppb=flookup("bppbno","bppb a inner join so_det s on a.id_so_det=s.id","
	s.id_so='$id_cost'");
	if($cekbppb=="")
	{
		insert_log("XUnlock Costing # : ".$tx_cost." Dengan Alasan : ".$txtreason,$user);
		$sql="insert into unlock_so (id_so,so_no,unlock_date,reason,username,dateinput) 
			values ('$id_cost','$tx_cost','$date','$txtreason','$user','$date2')";
		insert_log($sql,$user);
		$sql="update so set revise=revise+1 where id='$id_cost'";
		insert_log($sql,$user);
		$_SESSION['msg'] = "Data Berhasil Diunlock";
	}
	else
	{
		$_SESSION['msg'] = "XData Tidak Bisa Diunlock Karena Sudah Ada Pengeluaran FG";
	}
}
echo "<script>window.location.href='../marketting/?mod=$mod';</script>";
?>