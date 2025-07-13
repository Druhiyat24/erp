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
	$txtno_req	 	= nb($_POST['txtno_req']);
	$txt_tujuan	 	= nb($_POST['txt_tujuan']);
	$txt_tglreq	 	= fd($_POST['txt_tglreq']);
	$txtjns_out		= nb($_POST['txtjns_out']);
	$txt_ket		= nb($_POST['txt_ket']);
	$txtjns_out		= nb($_POST['txtjns_out']);

	$dateinput		= date('Y-m-d H:i:s');


	$JmlArray 		= $_POST['qtyreq'];
	$JoArray 		= $_POST['job_order'];
	$KodeBrgArray 	= $_POST['kode_barang'];
	$NmBrgArray 	= $_POST['nama_barang'];
	$UnitArray 		= $_POST['unit'];

	foreach ($JmlArray as $key => $value) {
		if ($value != "") {

			$txtjml     	= $JmlArray[$key];
			$txtjo			= $JoArray[$key];
			$txtkd_brg		= $KodeBrgArray[$key];
			$txtnm_brg	 	= $NmBrgArray[$key];
			$txtunit	 	= $UnitArray[$key];

			$sql = "insert into req_material 
			(no_req,tgl_req,supplier,tipe_pengeluaran,keterangan,kode_barang,nama_barang,job_order,qty,unit,dibuat,tgl_input,cancel)
			values ('$txtno_req','$txt_tglreq','$txt_tujuan','$txtjns_out','$txt_ket','$txtkd_brg','$txtnm_brg','$txtjo','$txtjml','$txtunit','$user','$dateinput','N')";
			insert_log($sql, $user);
			$_SESSION['msg'] = "Nomor Req Berhasil Disimpan, Nomor Req : " . $txtno_req;
			echo "<script>window.location.href='../wh/?mod=req_material';</script>";
		}
	}
}
