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


if ($mod == 'simpan_lokasi') {
	$id_in_material	 	= nb($_POST['id_in_material']);
	$dateinput			= date('Y-m-d H:i:s');
	$no_dok 			= nb($_POST['no_dok']);

	$JmlArray 		= $_POST['jml_roll'];
	$LotArray 		= $_POST['lot'];
	$NoArray 		= $_POST['no_roll'];
	$Rak_rollArray 	= $_POST['kode_rak'];

	foreach ($JmlArray as $key => $value) {
		if ($value != "") {

			$querybarcode = mysql_query("SELECT max(cast(substr(id_barcode,5) as int)) id_b FROM `in_material_det`");
			$databarcode  = mysql_fetch_array($querybarcode);
			$id_b		  = $databarcode['id_b'];
			$urutan = $id_b + 1;
			$id_barcode = $urutan;

			$txtno_roll = $NoArray[$key];
			$txtlot		= $LotArray[$key];
			$txtjml_roll = $JmlArray[$key];
			$txtrak_roll = $Rak_rollArray[$key];

			$sql = "insert into in_material_det (id_barcode,id_in_material,roll_no,roll_qty,lot_no,kode_rak,user,date_input,cancel,retur,no_retur,ex_id_barcode)
				values ('FAB-$id_barcode','$id_in_material','$txtno_roll','$txtjml_roll','$txtlot','$txtrak_roll','$user','$dateinput','N','','','')";
			insert_log($sql, $user);
			$_SESSION['msg'] = "Lokasi Berhasil Disimpan, Nomor Dok : " . $no_dok;
			echo "<script>window.location.href='../wh/?mod=in_material_item&id_in_material=$id_in_material';</script>";
		}
	}
}
