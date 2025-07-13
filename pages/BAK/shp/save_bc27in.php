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

if ($mod == 'simpan_header') {
	$tipe_bc	= nb($_POST['tipe_bc']);
	$id_supp			= nb($_POST['id_supp']);
	$alamat_supp 		= nb($_POST['alamat_supp']);
	$no_daftar		= nb($_POST['no_daftar']);
	$tgl_daftar	= fd($_POST['tgl_daftar']);
	$no_aju       = nb($_POST['no_aju']);
	$tgl_aju     = fd($_POST['tgl_aju']);
	$curr     		= nb($_POST['curr']);
	$txtnotes     	= nb($_POST['txtnotes']);
	$dateinput		= date('Y-m-d H:i:s');
	// $inv_buyer 		= nb($_POST['inv_buyer']);

	$sql = mysql_query("select max(SUBSTR(no_dok,15)) no_trans from exim_ceisa_manual");
	$row = mysql_fetch_array($sql);
	$kodepay = $row['no_trans'];
	$urutan = (int) substr($kodepay, 0, 5);
	$urutan++;
	$bln = date("m");
	$thn = date("y");
	$huruf = "INS/EXIM/$bln$thn/";
	$kodepay = $huruf . sprintf("%05s", $urutan);


	// $cekheader = flookup("id", "exim_ceisa_manual", "no_dok='$kodepay'");
	// if ($cekheader != "") {

		$sql_insert_det = "insert into exim_ceisa_manual (select '', '".$kodepay."' no_dok, '".$tipe_bc."' jenis_dok, '".$no_daftar."' nomor_daftar, '".$tgl_daftar."' tanggal_daftar, '".$no_aju."' nomor_aju, '".$tgl_aju."' tanggal_aju, '".$id_supp."' id_supplier, '".$alamat_supp."' alamat_supplier, nama_item, satuan, qty, '".$curr."' curr, price, 'POST' status, '".$txtnotes."' keterangan, '".$user."' created_by, '".$dateinput."' created_date from exim_ceisa_temp where user = '$user')";
		insert_log($sql_insert_det, $user); {
			$_SESSION['msg'] = "Data Berhasil Disimpan, Nomor Dokumen : " . $kodepay;
		}
		echo "<script>window.location.href='../shp/?mod=upload_exim';</script>";
	// }
}


if ($mod == 'cancel_item') {
	$id_h = $_GET['id_h'];
	$id	  = $_GET['idd'];


	$sql = "update memo_det set cancel = case when cancel = 'Y' then'N' else 'Y' end where id_h = '$id_h' and id = '$id'";
	insert_log($sql, $user); {
		$_SESSION['msg'] = "Data Berhasil Dicancel";
	}
	echo "<script>window.location.href='../shp/?mod=memo_edit&id_h=$id_h';</script>";
}

if ($mod == 'update_biaya') {
	$id_h = $_GET['id_h'];

	$ItemArray = $_POST['id_cek'];
	$BiayaArray = $_POST['edit_biaya'];
	$CancelArray = $_POST['cancel'];
	foreach ($CancelArray as $key => $value) {
		if ($value == "N") {
			$id = $ItemArray[$key];
			$biaya = $BiayaArray[$key];
			$cancel = $CancelArray[$key];

			$sql = "update memo_det set biaya = '$biaya' where id_h = '$id_h' and id = '$id'";
			insert_log($sql, $user); {
				$_SESSION['msg'] = "Data Berhasil DiRubah";
			}
			echo "<script>window.location.href='../shp/?mod=memo_edit&id_h=$id_h';</script>";
		}
	}



	$sql = "update memo_det set biaya = '$biaya' where id_h = '$id_h' and id = '$id' 1";
	insert_log($sql, $user); {
		$_SESSION['msg'] = "Data Biaya Dirubah";
	}
	echo "<script>window.location.href='../shp/?mod=memo_edit&id_h=$id_h';</script>";
}

if ($mod == 'update_header') {
	$id_h = $_GET['id_h'];
	$txtmemodate	= fd($_POST['txtmemodate']);
	$cbokpd			= nb($_POST['cbokpd']);
	$cbosupp 		= nb($_POST['cbosupp']);
	$jns_trans		= nb($_POST['jns_trans']);
	$jns_pengiriman	= nb($_POST['jns_pengiriman']);
	$ditagihkan     = nb($_POST['ditagihkan']);
	$curr     		= nb($_POST['curr']);
	$txtjth_tempo	= fd($_POST['txtjth_tempo']);
	$dok_pendukung	= nb($_POST['dok_pendukung']);
	$txtnotes		= nb($_POST['txtnotes']);
	$inv_buyer		= nb($_POST['inv_buyer']);

	$nm_memo = flookup("nm_memo", "memo_h", "id_h='$id_h'");

	$sql = "update memo_h set tgl_memo = '$txtmemodate', kepada = '$cbokpd', id_supplier = '$cbosupp', jns_trans = '$jns_trans', jns_pengiriman = '$jns_pengiriman', ditagihkan = '$ditagihkan', curr = '$curr', jatuh_tempo_new = '$txtjth_tempo', dok_pendukung = '$dok_pendukung', notes = '$txtnotes', inv_buyer = '$inv_buyer' where id_h = '$id_h'";
	insert_log($sql, $user); {
		$_SESSION['msg'] = "Data Berhasil Diupdate, Nomor Memo : " . $nm_memo;
	}
	echo "<script>window.location.href='../shp/?mod=memo_edit&id_h=$id_h';</script>";
}

if ($mod == 'update_header_non_inv') {
	$id_h = $_GET['id_h'];
	$txtmemodate	= fd($_POST['txtmemodate']);
	$cbokpd			= nb($_POST['cbokpd']);
	$cbosupp 		= nb($_POST['cbosupp']);
	$jns_trans		= nb($_POST['jns_trans']);
	$jns_pengiriman	= nb($_POST['jns_pengiriman']);
	$ditagihkan     = nb($_POST['ditagihkan']);
	$curr     		= nb($_POST['curr']);
	$txtjth_tempo	= fd($_POST['txtjth_tempo']);
	$dok_pendukung	= nb($_POST['dok_pendukung']);
	$txtnotes		= nb($_POST['txtnotes']);
	$inv_buyer		= nb($_POST['inv_buyer']);

	$nm_memo = flookup("nm_memo", "memo_h", "id_h='$id_h'");

	$sql = "update memo_h set tgl_memo = '$txtmemodate', kepada = '$cbokpd', id_supplier = '$cbosupp', jns_trans = '$jns_trans', jns_pengiriman = '$jns_pengiriman', ditagihkan = '$ditagihkan', curr = '$curr', jatuh_tempo_new = '$txtjth_tempo', dok_pendukung = '$dok_pendukung', notes = '$txtnotes',inv_buyer = '$inv_buyer' where id_h = '$id_h'";
	insert_log($sql, $user); {
		$_SESSION['msg'] = "Data Berhasil Diupdate, Nomor Memo : " . $nm_memo;
	}
	echo "<script>window.location.href='../shp/?mod=memo_edit_non_inv&id_h=$id_h';</script>";
}

if ($mod == 'tambah_det') {
	$id_h = $_GET['id_h'];
	$id_kat_add = nb($_POST['cbokat']);
	$id_sub_kat_add = nb($_POST['cbosubkat']);
	$biaya_add = nb($_POST['txtbiaya']);
	$inv_vendor_add = nb($_POST['txtinv_vendor']);
	$inv_pajak_add = nb($_POST['txtfak_pajak']);

	$nm_memo = flookup("nm_memo", "memo_h", "id_h='$id_h'");

	$sql = "insert into memo_det (id_h,id_ctg,nm_ctg,id_sub_ctg,nm_sub_ctg,inv_vendor,faktur_pajak,biaya,cancel) 
	select '$id_h',b.id_ctg, b.nm_ctg, c.id_sub_ctg, c.nm_sub_ctg,'$inv_vendor_add', '$inv_pajak_add','$biaya_add', 'N' from
	master_memo_ctg b 
	inner join master_memo_subctg c on b.id_ctg = c.id_ctg
	where b.id_ctg = '$id_kat_add' and c.id_sub_ctg = '$id_sub_kat_add'";
	insert_log($sql, $user); {
		$_SESSION['msg'] = "Data Berhasil Diupdate, Nomor Memo : " . $nm_memo;
	}
	echo "<script>window.location.href='../shp/?mod=memo_edit&id_h=$id_h';</script>";
}
