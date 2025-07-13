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
	$txtno_out	 	= nb($_POST['txtno_out']);
	$cboreq	 		= nb($_POST['cboreq']);
	$txt_tglout	 	= fd($_POST['txt_tglout']);
	$txt_ket		= nb($_POST['txt_ket']);

	$dateinput		= date('Y-m-d H:i:s');


	$JmlArray 		= $_POST['qtyout'];
	$IdBarcodeArray = $_POST['id_barcode'];
	$JoArray 		= $_POST['job_order'];
	$KodeBrgArray 	= $_POST['kode_barang'];
	$NmBrgArray 	= $_POST['nama_barang'];
	$UnitArray 		= $_POST['unit'];


	foreach ($JmlArray as $key => $value) {
		if ($value != "") {

			$txtjml     	= $JmlArray[$key];
			$txtid_barcode	= $IdBarcodeArray[$key];
			$txtjo			= $JoArray[$key];
			$txtkd_brg		= $KodeBrgArray[$key];
			$txtnm_brg	 	= $NmBrgArray[$key];
			$txtunit	 	= $UnitArray[$key];

			$sql = "insert into out_material 
			(no_out,tgl_out,no_req,keterangan,id_barcode,kode_barang,nama_barang,job_order,qty,unit,dibuat,tgl_input,cancel)
			values ('$txtno_out','$txt_tglout','$cboreq','$txt_ket','$txtid_barcode','$txtkd_brg','$txtnm_brg','$txtjo','$txtjml','$txtunit','$user','$dateinput','N')";
			insert_log($sql, $user);
			$_SESSION['msg'] = "Nomor Pengeluaran Berhasil Disimpan, Nomor Dok : " . $txtno_out;
			echo "<script>window.location.href='../wh/?mod=out_material';</script>";
		}
	}
}
