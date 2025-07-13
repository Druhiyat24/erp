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
	$txt_baris		= nb($_POST['txt_baris']);
	$txt_kolom		= nb($_POST['txt_kolom']);
	$txt_no			= nb($_POST['txt_no']);
	$txt_kap		= nb($_POST['txt_kap']);
	$txt_desk		= nb($_POST['txt_desk']);

	$kode_rak 		= $txt_kd . "." . $txt_baris . "." . $txt_kolom . "." . $txt_no;
	$dateinput		= date('Y-m-d H:i:s');

	$sql = "insert into m_rak (kd, baris, kolom, no, kode_rak, nama_rak, kapasitas, unit, tgl_input, user_input, cancel ) 
			values ('$txt_kd','$txt_baris','$txt_kolom','$txt_no','$kode_rak','$txt_desk', '$txt_kap', 'ROLL','$dateinput','$user','N')";
	insert_log($sql, $user); {
		$_SESSION['msg'] = "Data Berhasil Disimpan, Kode Rak : " . $kode_rak . " Nama Rak: " . $txt_desk;
	}
	echo "<script>window.location.href='../wh/?mod=m_rak';</script>";
}

if ($mod == 'update_status') {

	$id 		= $_GET['id'];
	$sql_cari  	= mysql_query("select * from m_rak where id = '$id'");
	$row_cari 	= mysql_fetch_array($sql_cari);
	$kode_rak	= $row_cari['kode_rak'];
	$nama_rak	= $row_cari['nama_rak'];


	$sql = "update m_rak set cancel = case when cancel = 'Y' then'N' else 'Y' end
	where id = '$id'";
	insert_log($sql, $user); {
		$_SESSION['msg'] = "XData Berhasil Di rubah, Kode Rak : " . $kode_rak . " Nama Rak : " . $nama_rak;
	}
	echo "<script>window.location.href='../wh/?mod=m_rak';</script>";
}

if ($mod == 'update') {

	$id 		= $_GET['id'];
	$txt_kd			= nb($_POST['txt_kd']);
	$txt_baris		= nb($_POST['txt_baris']);
	$txt_kolom		= nb($_POST['txt_kolom']);
	$txt_no			= nb($_POST['txt_no']);
	$txt_kap		= nb($_POST['txt_kap']);
	$txt_desk		= nb($_POST['txt_desk']);

	$kode_rak 		= $txt_kd . "." . $txt_baris . "." . $txt_kolom . "." . $txt_no;

	$sql = "update m_rak set kd = '$txt_kd', baris = '$txt_baris', kolom = '$txt_kolom' , 
	no = '$txt_no', kapasitas = '$txt_kap', nama_rak = '$txt_desk', kode_rak = '$kode_rak'
	where id = '$id'";
	insert_log($sql, $user); {
		$_SESSION['msg'] = "XData Berhasil Di rubah, Kode Rak : " . $kode_rak . " Nama Rak : " . $nama_rak;
	}
	echo "<script>window.location.href='../wh/?mod=m_rak';</script>";
}
