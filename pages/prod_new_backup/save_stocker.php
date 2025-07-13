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

	$txttglcut = fd($_POST['txttglcut']);
	$dateinput		= date('Y-m-d H:i:s');

	$sql_cari  = mysql_query("select max(no_cut) urut from stocker where YEAR(tgl_cut) = YEAR(CURRENT_DATE()) and month(tgl_cut) =  MONTH(CURRENT_DATE())");
	$row_cari = mysql_fetch_array($sql_cari);
	$kodepay = $row_cari['urut'];
	$urutan = (int) substr($kodepay, 10, 5);
	if ($urutan == "") {
		$urutan = "00000";
	} else {
		$urutan = $urutan;
	}
	$urutan++;
	$tahun   = substr(date('Y', strtotime($txttglcut)), 2, 2);
	$bulan   = substr(date('m', strtotime($txttglcut)), 0, 2);
	$nm_memo = "CUT/$tahun$bulan/";
	$no_cut = $nm_memo . sprintf("%05s", $urutan);

	$id_so_detArray = $_POST['id_so_det'];
	$colorArray = $_POST['color'];
	$sizeArray = $_POST['size'];
	$txtratioArray = $_POST['txtratio'];
	$txtqtycutArray = $_POST['txtqtycut'];
	$txtrange_awalArray = $_POST['txtrange_awal'];
	$txtrange_akhirArray = $_POST['txtrange_akhir'];

	$txtcutnumb = nb($_POST['txtcutnumb']);
	$txtqtyply = nb($_POST['txtqtyply']);
	$txtremark = nb($_POST['txtremark']);
	$txtkpno = nb($_POST['txtkpno']);
	$cbopart = nb($_POST['cbopart']);
	$txtshade = nb($_POST['txtshade']);


	foreach ($id_so_detArray as $key_1 => $value_1) {
		$id_so_det = $id_so_detArray[$key_1];
		$color = $colorArray[$key_1];
		$size = $sizeArray[$key_1];
		$txtratio = $txtratioArray[$key_1];
		$txtqtycut = $txtqtycutArray[$key_1];
		$txtrange_awal = $txtrange_awalArray[$key_1];
		$txtrange_akhir = $txtrange_akhirArray[$key_1];

		$sql = "insert into stocker (no_cut,tgl_cut,kpno,id_so_det,color,size,part,cut_number,qty_ply,ratio,qty_cut,range_awal,range_akhir,notes,user,cancel,tgl_input, shade) 
		values ('$no_cut','$txttglcut','$txtkpno','$id_so_det','$color','$size','$cbopart','$txtcutnumb','$txtqtyply','$txtratio','$txtqtycut','$txtrange_awal','$txtrange_akhir','$txtremark','$user','N','$dateinput','$txtshade')";
		insert_log($sql, $user); {
			$_SESSION['msg'] = "Data Berhasil Disimpan, No Cut : " . $no_cut;
		}
		echo "<script>window.location.href='../prod_new/?mod=stocker_h';</script>";
	}
}
