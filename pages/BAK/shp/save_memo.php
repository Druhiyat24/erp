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
	$txtmemodate	= fd($_POST['txtmemodate']);
	$cbokpd			= nb($_POST['cbokpd']);
	$cbosupp 		= nb($_POST['cbosupp']);
	$jns_trans		= nb($_POST['jns_trans']);
	$jns_pengiriman	= nb($_POST['jns_pengiriman']);
	$cbobuyer       = nb($_POST['cbobuyer']);
	$ditagihkan     = nb($_POST['ditagihkan']);
	$curr     		= nb($_POST['curr']);
	$id_item     	= nb($_POST['id_item']);
	$txtjth_tempo	= fd($_POST['txtjth_tempo']);
	$dok_pendukung	= nb($_POST['dok_pendukung']);
	$dateinput		= date('Y-m-d H:i:s');
	$txtnotes 		= nb($_POST['txtnotes']);
	// $inv_buyer 		= nb($_POST['inv_buyer']);

	// $sql_cari  = mysql_query("select max(nm_memo) urut from memo_h where YEAR(CURRENT_DATE()) and MONTH(CURRENT_DATE())");
	// $row_cari = mysql_fetch_array($sql_cari);
	// $kodepay = $row_cari['urut'];
	// $urutan = (int) substr($kodepay, 15, 5);
	// if ($urutan == "") {
	// 	$urutan = "00001";
	// } else {
	// 	$urutan = $urutan;
	// }
	// $urutan++;
	// $tahun   = substr(date('Y', strtotime($txtmemodate)), 2, 2);
	// $bulan   = substr(date('m', strtotime($txtmemodate)), 0, 2);
	// $nm_memo = "MEMO/NAG/$tahun$bulan/";
	// $kodepay_f = $nm_memo . sprintf("%05s", $urutan);

	$sql_cari  = mysql_query("select CONCAT('MEMO/NAG/',DATE_FORMAT('$txtmemodate', '%y%m'),'/',LPAD((COALESCE(max(SUBSTR(nm_memo,15)),0) + 1),5,0)) nomor from memo_h WHERE nm_memo not in ('MEMO/NAG/2501/02992','MEMO/NAG/2501/02993','MEMO/NAG/2501/02994','MEMO/NAG/2501/02995','MEMO/NAG/2501/02996','MEMO/NAG/2501/02997','MEMO/NAG/2501/02998','MEMO/NAG/2501/02999','MEMO/NAG/2501/03000','MEMO/NAG/2501/03001','MEMO/NAG/2501/03007','MEMO/NAG/2501/03002','MEMO/NAG/2501/03003','MEMO/NAG/2501/03004','MEMO/NAG/2501/03005','MEMO/NAG/2501/03006','MEMO/NAG/2501/03008','MEMO/NAG/2501/03009','MEMO/NAG/2501/03010','MEMO/NAG/2501/03011','MEMO/NAG/2501/03012','MEMO/NAG/2501/03013','MEMO/NAG/2501/03014','MEMO/NAG/2501/03015','MEMO/NAG/2501/03016','MEMO/NAG/2501/03017','MEMO/NAG/2501/03018','MEMO/NAG/2501/03019','MEMO/NAG/2501/03020','MEMO/NAG/2501/03021','MEMO/NAG/2501/03022','MEMO/NAG/2501/03023','MEMO/NAG/2501/03024','MEMO/NAG/2501/03025','MEMO/NAG/2501/03026','MEMO/NAG/2501/03027') and YEAR(tgl_memo) = YEAR ('$txtmemodate')");
	$row_cari = mysql_fetch_array($sql_cari);
	$kodepay_f = $row_cari['nomor'];


	$sql = "insert into memo_h (nm_memo, tgl_memo, kepada, id_supplier, jns_trans, jns_pengiriman, id_buyer, 
			ditagihkan, curr, jatuh_tempo_new, dok_pendukung, date_input, status, jns_inv, user,notes, inv_buyer,id_item) 
			values ('$kodepay_f','$txtmemodate','$cbokpd','$cbosupp','$jns_trans','$jns_pengiriman','$cbobuyer',
			'$ditagihkan','$curr','$txtjth_tempo','$dok_pendukung','$dateinput','DRAFT','INVOICE','$user','$txtnotes','$inv_buyer','$id_item')";
	insert_log($sql, $user);

	$cekheader = flookup("id_h", "memo_h", "nm_memo='$kodepay_f'");
	if ($cekheader != "") {
		$ItemArray = $_POST['id_cek'];
		$NoArr	   = $_POST['no_invoice'];
		foreach ($ItemArray as $key => $value) {
			if ($value != "") {
				$id_book = $ItemArray[$key];
				$no_invoice = $NoArr[$key];

				$sql_insert_inv = "insert into memo_inv (id_h,id_book,no_invoice) values
						('$cekheader','$id_book','$no_invoice')";
				insert_log($sql_insert_inv, $user);

				$sql_update_book = "update tbl_book_invoice set stat_memo = 'Y' where id = '$id_book'";
				insert_log($sql_update_book, $user);
			}
		}

		$sql_insert_det = "insert into memo_det (id_h,id_ctg,nm_ctg,id_sub_ctg,nm_sub_ctg,biaya,inv_vendor,faktur_pajak,cancel)
			select $cekheader,a.id_ctg, b.nm_ctg, a.id_sub_ctg, c.nm_sub_ctg, a.biaya, a.inv_vendor,a.faktur_pajak, 'N' from memo_det_tmp a
			inner join master_memo_ctg b on a.id_ctg = b.id_ctg
			inner join master_memo_subctg c on a.id_sub_ctg = c.id_sub_ctg
			where user = '$user'";
		insert_log($sql_insert_det, $user); {
			$_SESSION['msg'] = "Data Berhasil Disimpan, Nomor Memo : " . $kodepay_f;
		}
		echo "<script>window.location.href='../shp/?mod=memo_edit&id_h=$cekheader';</script>";
	}
}


if ($mod == 'simpan_header_non_inv') {
	$txtmemodate	= fd($_POST['txtmemodate']);
	$cbokpd			= nb($_POST['cbokpd']);
	$cbosupp 		= nb($_POST['cbosupp']);
	$jns_trans		= nb($_POST['jns_trans']);
	$jns_pengiriman	= nb($_POST['jns_pengiriman']);
	$cbobuyer       = nb($_POST['cbobuyer']);
	$ditagihkan     = nb($_POST['ditagihkan']);
	$curr     		= nb($_POST['curr']);
	$txtjth_tempo	= fd($_POST['txtjth_tempo']);
	$dok_pendukung	= nb($_POST['dok_pendukung']);
	$txtno_aju		= nb($_POST['txtno_aju']);
	$txtnotes 		= nb($_POST['txtnotes']);
	$dateinput		= date('Y-m-d H:i:s');
	$id_item     	= nb($_POST['id_item']);
	// $inv_buyer 		= nb($_POST['inv_buyer']);

	// $sql_cari  = mysql_query("select max(nm_memo) urut from memo_h where YEAR(CURRENT_DATE()) and MONTH(CURRENT_DATE())");
	// $row_cari = mysql_fetch_array($sql_cari);
	// $kodepay = $row_cari['urut'];
	// $urutan = (int) substr($kodepay, 15, 5);
	// if ($urutan == "") {
	// 	$urutan = "00001";
	// } else {
	// 	$urutan = $urutan;
	// }
	// $urutan++;
	// $tahun   = substr(date('Y', strtotime($txtmemodate)), 2, 2);
	// $bulan   = substr(date('m', strtotime($txtmemodate)), 0, 2);
	// $nm_memo = "MEMO/NAG/$tahun$bulan/";
	// $kodepay_f = $nm_memo . sprintf("%05s", $urutan);

	$sql_cari  = mysql_query("select CONCAT('MEMO/NAG/',DATE_FORMAT('$txtmemodate', '%y%m'),'/',LPAD((COALESCE(max(SUBSTR(nm_memo,15)),0) + 1),5,0)) nomor from memo_h WHERE nm_memo not in ('MEMO/NAG/2501/02992','MEMO/NAG/2501/02993','MEMO/NAG/2501/02994','MEMO/NAG/2501/02995','MEMO/NAG/2501/02996','MEMO/NAG/2501/02997','MEMO/NAG/2501/02998','MEMO/NAG/2501/02999','MEMO/NAG/2501/03000','MEMO/NAG/2501/03001','MEMO/NAG/2501/03007','MEMO/NAG/2501/03002','MEMO/NAG/2501/03003','MEMO/NAG/2501/03004','MEMO/NAG/2501/03005','MEMO/NAG/2501/03006','MEMO/NAG/2501/03008','MEMO/NAG/2501/03009','MEMO/NAG/2501/03010','MEMO/NAG/2501/03011','MEMO/NAG/2501/03012','MEMO/NAG/2501/03013','MEMO/NAG/2501/03014','MEMO/NAG/2501/03015','MEMO/NAG/2501/03016','MEMO/NAG/2501/03017','MEMO/NAG/2501/03018','MEMO/NAG/2501/03019','MEMO/NAG/2501/03020','MEMO/NAG/2501/03021','MEMO/NAG/2501/03022','MEMO/NAG/2501/03023','MEMO/NAG/2501/03024','MEMO/NAG/2501/03025','MEMO/NAG/2501/03026','MEMO/NAG/2501/03027') and YEAR(tgl_memo) = YEAR ('$txtmemodate')");
	$row_cari = mysql_fetch_array($sql_cari);
	$kodepay_f = $row_cari['nomor'];


	$sql = "insert into memo_h (nm_memo, tgl_memo, kepada, id_supplier, jns_trans, jns_pengiriman, id_buyer, 
			ditagihkan, curr, jatuh_tempo_new, dok_pendukung, date_input, status, jns_inv, user,no_aju, notes, inv_buyer,id_item) 
			values ('$kodepay_f','$txtmemodate','$cbokpd','$cbosupp','$jns_trans','$jns_pengiriman','$cbobuyer',
			'$ditagihkan','$curr','$txtjth_tempo','$dok_pendukung','$dateinput','DRAFT','NON INVOICE','$user','$txtno_aju','$txtnotes','$inv_buyer','$id_item')";
	insert_log($sql, $user);

	$cekheader = flookup("id_h", "memo_h", "nm_memo='$kodepay_f'");
	if ($cekheader != "") {


		$sql_insert_det = "insert into memo_det (id_h,id_ctg,nm_ctg,id_sub_ctg,nm_sub_ctg,biaya,inv_vendor,faktur_pajak,cancel)
			select $cekheader,a.id_ctg, b.nm_ctg, a.id_sub_ctg, c.nm_sub_ctg, a.biaya, a.inv_vendor,a.faktur_pajak, 'N' from memo_det_tmp a
			inner join master_memo_ctg b on a.id_ctg = b.id_ctg
			inner join master_memo_subctg c on a.id_sub_ctg = c.id_sub_ctg
			where user = '$user'";
		insert_log($sql_insert_det, $user); {
			$_SESSION['msg'] = "Data Berhasil Disimpan, Nomor Memo : " . $kodepay_f;
		}
		echo "<script>window.location.href='../shp/?mod=memo_edit&id_h=$cekheader';</script>";
	}
}

// if ($mod == 'simpan_temp') {
// 	$cbokat			= nb($_POST['cbokat']);
// 	$cbosubkat 		= nb($_POST['cbosubkat']);
// 	$txtbiaya		= nb($_POST['txtbiaya']);


// 	$sql = "insert into memo_det_tmp (id_ctg, id_sub_ctg, biaya, user) 
// 			values ('$cbokat','$cbosubkat','$txtbiaya','$user')";
// 	insert_log($sql, $user); {
// 		$_SESSION['msg'] = "Data Berhasil Disimpan";
// 	}
// 	echo "<script>window.location.href='../forms/?mod=bpb_global_item&mode=Bahan_Baku&bpbno=$txtbpbno';</script>";
// }

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
