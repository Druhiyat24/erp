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

	$txt_nmpart		= nb($_POST['txt_nmpart']);
	$dateinput		= date('Y-m-d H:i:s');

	$sql_cari  = mysql_query("select max(kode_part) urut from master_part");
	$row_cari = mysql_fetch_array($sql_cari);
	$kodepay = $row_cari['urut'];
	$urutan = (int) substr($kodepay, 3, 5);
	if ($urutan == "") {
		$urutan = "00001";
	} else {
		$urutan = $urutan;
	}
	$urutan++;
	$kode_part = "MP" . sprintf("%05s", $urutan);


	$sql = "insert into master_part (kode_part, nama_part, tgl_input, user_input, cancel) 
			values ('$kode_part','$txt_nmpart','$dateinput','$user','N')";
	insert_log($sql, $user); {
		$_SESSION['msg'] = "Data Berhasil Disimpan, Nama Part : " . $txt_nmpart;
	}
	echo "<script>window.location.href='../prod_new/?mod=master_part';</script>";
}

if ($mod == 'update_status') {

	$id 		= $_GET['id'];
	$sql_cari  	= mysql_query("select * from master_part where id = '$id'");
	$row_cari 	= mysql_fetch_array($sql_cari);
	$txt_nmpart = $row_cari['nama_part'];

	$dateinput		= date('Y-m-d H:i:s');


	$sql = "update master_part set cancel = case when cancel = 'Y' then'N' else 'Y' end
	where id = '$id'";
	insert_log($sql, $user); {
		$_SESSION['msg'] = "Data Berhasil Di rubah, Nama Part : " . $txt_nmpart;
	}
	echo "<script>window.location.href='../prod_new/?mod=master_part';</script>";
}
