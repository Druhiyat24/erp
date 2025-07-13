<?php
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (empty($_SESSION['username'])) {
	header("location:../../index.php");
}

$user = $_SESSION['username'];
$sesi = $_SESSION['sesi'];
$mod = $_GET['mod'];

if ($mod == 'simpan') {
	$cbows				= nb($_POST['cbows']);
	$txtsmv				= nb($_POST['txtsmv']);
	$txtmpwr			= nb($_POST['txtmpwr']);
	$txtjamkerja		= nb($_POST['txtjamkerja']);
	$txtbuyer     		= nb($_POST['txtbuyer']);
	$cbocolor			= nb($_POST['cbocolor']);
	$cboline			= nb($_POST['cboline']);
	$txt_plan_target	= nb($_POST['txt_plan_target']);
	$txt_target_eff		= nb($_POST['txt_target_eff']);
	$dateinput			= date('Y-m-d H:i:s');
	$tanggal			= date('Y-m-d');
	$id_plan			= date('Ymd');

	$sql = "insert into master_plan (id_plan,id,tgl_plan,sewing_line,id_ws,color,create_by,
				smv,jam_kerja,man_power,plan_target,target_effy,tgl_input,cancel)
				values ('$id_plan','','$tanggal','$cboline','$cbows','$cbocolor',
				'$user','$txtsmv','$txtjamkerja','$txtmpwr','$txt_plan_target','$txt_target_eff',
				'$dateinput','N')";
	insert_log($sql, $user); {
		$_SESSION['msg'] = "Data Berhasil Disimpan";
	}
	echo "<script>window.location.href='../prod_new/?mod=master_plan_new';</script>";
}
