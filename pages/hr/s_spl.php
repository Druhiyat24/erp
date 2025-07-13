<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod	=$_GET['mod'];
#$usernya=$_GET['id'];
$tanggal = fd($_POST['txttanggal']);
$keterangan = $_POST['txtketerangan'];
$jamspl = $_POST['txtjamspl'];
$mulai = ft($_POST['txtmulai']);
$selesai = ft($_POST['txtselesai']);
$h_ot = $jamspl;
$ceklibur=flookup("holiday_desc","hr_masterholiday","holiday_date='$tanggal'");
if ($ceklibur=="") { $ceklibur=date("D",strtotime($tanggal)); }
if ($ceklibur=="Sun") { $ceklibur="Y"; } else { $ceklibur=""; }
if ($ceklibur!="")
{	$ot1 = 0;
	$pot1 = 0;
	$ot2 = $h_ot;
	$pot2 = $ot2 * 2;
}
else
{	if ($h_ot==0.5)
	{	$ot1 = 0.5;	}
	else
	{	$ot1 = 1;	}
	$pot1 = $ot1 * 1.5;
	$ot2 = $h_ot - $ot1;
	$pot2 = $ot2 * 2;
}
$tpot = $pot1 + $pot2;

foreach ($_POST['txtnik'] as $selectedOption)
{	$key=$selectedOption;
	$cek=flookup("nik","hr_spl","nik='$key' and tanggal='$tanggal'");
	if ($cek=="")
	{	$sql="insert into hr_spl (nik,mulai,selesai,tanggal,keterangan,jamspl,pot) 
			values ('$key','$mulai','$selesai','$tanggal',
			'$keterangan','$jamspl','$tpot')";
		insert_log($sql,$user);
	}
	else
	{	$_SESSION['msg'] = "XData Sudah Ada NIK ".$key." Tgl. ".fd_view($tanggal);
		echo "<script>window.location.href='../hr/?mod=18L';</script>";
		exit;
	}
}
$_SESSION['msg'] = "Data Berhasil Disimpan";
echo "<script>window.location.href='../hr/?mod=18L';</script>";
?>