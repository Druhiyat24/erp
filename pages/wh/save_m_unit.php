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

if ($mod == 'simpan_data') {

	$txt_kd			= nb($_POST['txt_kd']);
	$txt_nm			= strtoupper($_POST['txt_nm']);
	$dateinput		= date('Y-m-d H:i:s');

	$sql = "insert into m_unit (kode_unit, nama_unit, tgl_input, user_input, cancel ) 
			values ('$txt_kd','$txt_nm','$dateinput','$user','N')";
	insert_log($sql, $user); {
		$_SESSION['msg'] = "Data Berhasil Disimpan, Kode Unit : " . $txt_kd . " Nama Unit: " . $txt_nm;
	}
	echo "<script>window.location.href='../wh/?mod=m_unit';</script>";
}

if ($mod == 'update_status') {

	$id 		= $_GET['id'];
	$sql_cari  	= mysql_query("select * from m_unit where id = '$id'");
	$row_cari 	= mysql_fetch_array($sql_cari);
	$kode_unit	= $row_cari['kode_unit'];
	$nama_unit	= $row_cari['nama_unit'];


	$sql = "update m_unit set cancel = case when cancel = 'Y' then'N' else 'Y' end
	where id = '$id'";
	insert_log($sql, $user); {
		$_SESSION['msg'] = "XData Berhasil Di rubah, Kode Unit : " . $kode_unit . " Nama Unit : " . $nama_unit;
	}
	echo "<script>window.location.href='../wh/?mod=m_unit';</script>";
}

if ($mod == 'update') {

	$id 			= $_GET['id'];
	$txt_kd			= nb($_POST['txt_kd']);
	$txt_nm			= nb($_POST['txt_nm']);

	$sql = "update m_unit set kode_unit = '$txt_kd', nama_unit = '$txt_nm'
	where id = '$id'";
	insert_log($sql, $user); {
		$_SESSION['msg'] = "XData Berhasil Di rubah, Kode Unit : " . $txt_kd . " Nama Unit : " . $txt_nm;
	}
	echo "<script>window.location.href='../wh/?mod=m_unit';</script>";
}
