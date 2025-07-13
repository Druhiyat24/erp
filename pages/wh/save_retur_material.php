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
	$txtno_retur	= nb($_POST['txtno_retur']);
	$cboout	 		= nb($_POST['cboout']);
	$txt_tglretur	= fd($_POST['txt_tglretur']);
	$txt_ket		= nb($_POST['txt_ket']);
	$txtnoreq		= nb($_POST['txtnoreq']);
	$dateinput		= date('Y-m-d H:i:s');


	$JmlArray 		= $_POST['qtyretur'];
	$IdBarcodeArray = $_POST['id_barcode'];
	$JoArray 		= $_POST['job_order'];
	$KodeBrgArray 	= $_POST['kode_barang'];
	$NmBrgArray 	= $_POST['nama_barang'];
	$UnitArray 		= $_POST['unit'];
	$RakArray 		= $_POST['kode_rak'];
	$Roll_noArray 	= $_POST['roll_no'];
	$LotArray 		= $_POST['lot_no'];
	$Id_in_materialArray 		= $_POST['id_in_material'];

	foreach ($JmlArray as $key => $value) {
		if ($value != "") {

			$txtjml     	= $JmlArray[$key];
			$txtid_barcode	= $IdBarcodeArray[$key];
			$txtjo			= $JoArray[$key];
			$txtkd_brg		= $KodeBrgArray[$key];
			$txtnm_brg	 	= $NmBrgArray[$key];
			$txtunit	 	= $UnitArray[$key];
			$txtrak		 	= $RakArray[$key];
			$txtroll		= $Roll_noArray[$key];
			$txtlot			= $LotArray[$key];
			$txtid_in_material			= $Id_in_materialArray[$key];

			$sql = "insert into retur_material 
			(no_retur,tgl_retur,no_out,no_req,keterangan,id_barcode,kode_barang,nama_barang,job_order,qty,unit,dibuat,tgl_input,cancel)
			values ('$txtno_retur','$txt_tglretur','$cboout','$txtnoreq','$txt_ket','$txtid_barcode','$txtkd_brg','$txtnm_brg','$txtjo','$txtjml','$txtunit','$user','$dateinput','N')";
			insert_log($sql, $user);

			$querybarcode = mysql_query("SELECT max(cast(substr(id_barcode,5) as int)) id_b FROM `in_material_det`");
			$databarcode  = mysql_fetch_array($querybarcode);
			$id_b		  = $databarcode['id_b'];
			$urutan = $id_b + 1;
			$id_barcode = $urutan;

			$sql_det = "insert into in_material_det (id_barcode,id_in_material,roll_no,roll_qty,lot_no,kode_rak,user,date_input,cancel,retur,no_retur,ex_id_barcode)
			values ('FAB-$id_barcode','$txtid_in_material','$txtroll','$txtjml','$txtlot','$txtrak','$user','$dateinput','N','Y','$txtno_retur','$txtid_barcode')";
			insert_log($sql_det, $user);


			$_SESSION['msg'] = "Nomor Retur Berhasil Disimpan, Nomor Dok : " . $txtno_retur;
			echo "<script>window.location.href='../wh/?mod=retur_material';</script>";
		}
	}
}
