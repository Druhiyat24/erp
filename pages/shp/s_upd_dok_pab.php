<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mod2="2L";
$trx = $_GET['trx'];
$trx = str_replace(' ', '', $trx);
$dtrx=$_GET['dtrx'];
$dtrx2=$_GET['dtrx2'];

$trx_no=nb($_POST['txttrx_no']);
$txtjenis_dok=$_POST['txtjenis_dok'];
$txtnomor_aju=$_POST['txtnomor_aju'];
$txttanggal_aju=fd($_POST['txttanggal_aju']);
$txtnomor_daftar=$_POST['txtnomor_daftar'];
$txttanggal_daftar=fd($_POST['txttanggal_daftar']);
$txtnomor_fp=$_POST['txtnomor_fp'];
$txttanggal_fp=fd($_POST['txttanggal_fp']);
$txtinvno=$_POST['txtinvno'];
$ratebc_h = isset($_POST['rate_bc_h']) ? $_POST['rate_bc_h'] : '';

if ($ratebc_h === '' || floatval($ratebc_h) < 1) {
    $rate_bc_h = null;
} else {
    $rate_bc_h = floatval($ratebc_h);
}

$rate_value = is_null($rate_bc_h) ? "NULL" : "'" . $rate_bc_h . "'";
if(isset($_POST['txtbm'])) { $txtbm=$_POST['txtbm']; } else { $txtbm=0; }
if(isset($_POST['txtppn'])) { $txtppn=$_POST['txtppn']; } else { $txtppn=0; }
if(isset($_POST['txtpph'])) { $txtpph=$_POST['txtpph']; } else { $txtpph=0; }
if(isset($_POST['txtbmtp'])) { $txtbmtp=$_POST['txtbmtp']; } else { $txtbmtp=0; }
if(substr($trx_no, 0,2)=="SJ") 
{ $tbl="bppb"; $fld="bppbno"; $add_fld=""; } 
else 
{ $tbl="bpb"; $fld="bpbno"; $add_fld=",update_dok_pab='Y'"; }
// Tgl. transaksi (BPB / BPPB) lebih dulu dari Tgl. Daftar (INHOUSE tidak punya Tgl. Daftar): boleh disimpan kalau exim sudah
// konfirmasi di form (konfirm_ubah_tgl=Y), tgl transaksi nanti diubah ke Tgl. Daftar. Dicek sebelum ada data yang diubah.
$tgl_trx = flookup("min(".$tbl."date)",$tbl,"$fld='$trx_no'");
$ubah_tgl = ($txtjenis_dok!="INHOUSE" && $txttanggal_daftar!="" && $tgl_trx!="" && $tgl_trx < $txttanggal_daftar);
if ($ubah_tgl && (!isset($_POST['konfirm_ubah_tgl']) || $_POST['konfirm_ubah_tgl']!="Y"))
{	$_SESSION['msg'] = "XTanggal Transaksi ".strtoupper($tbl)." ".fd_view($tgl_trx)." lebih awal dari Tanggal Daftar ".fd_view($txttanggal_daftar).", data tidak disimpan";
	echo "<script>window.location.href='../shp/?mod=$mod&trx=$trx&dtrx=$dtrx&dtrx2=$dtrx2&noid=$trx_no';</script>";
	exit;
}
if(isset($_POST['totnil'])) { $totnil = $_POST['totnil']; } else { $totnil=""; }
if($totnil!="")
{	foreach ($totnil as $key => $value)
	{	
		if (is_numeric($value))
		{
			$txttotnil = $totnil[$key];
			$line_id = $key;
			$sql="update $tbl set price=$txttotnil/qty where id='$line_id'";
			insert_log($sql,$user);
		}
	}
}
$sql="update $tbl set jenis_dok='$txtjenis_dok',nomor_aju='$txtnomor_aju',tanggal_aju='$txttanggal_aju' 
	,bcno='$txtnomor_daftar',bcdate='$txttanggal_daftar'
	,no_fp='$txtnomor_fp',tgl_fp='$txttanggal_fp',invno='$txtinvno',rate_bc=$rate_value $add_fld where $fld='$trx_no'";
insert_log($sql,$user);
// Tgl. transaksi diubah ke Tgl. Daftar. Tgl. asli disimpan di bpbdate_ori / bppbdate_ori hanya kalau masih kosong,
// jadi kalau diubah lagi (mis. asli 1 Nov, diubah ke 2 Nov, lalu ke 3 Nov) tgl. asli tetap 1 Nov
if ($ubah_tgl)
{	$sql="update $tbl set ".$tbl."date_ori=ifnull(".$tbl."date_ori,".$tbl."date),".$tbl."date='$txttanggal_daftar'
		,update_dokumen_by='$user',update_dokumen_date=now() where $fld='$trx_no' and ".$tbl."date<'$txttanggal_daftar'";
	insert_log($sql,$user);
}
// Dokumen gudang kain (nomor GK/...) juga ada di tabel gudang: pemasukan di whs_inmaterial_fabric, pengeluaran di whs_bppb_h.
// Yang disamakan hanya data pabean, no_invoice tetap milik gudang (SJ supplier)
$no_int = flookup($fld."_int",$tbl,"$fld='$trx_no'");
if (substr($no_int,0,3)=="GK/")
{	$tgl_aju_gk = ($txttanggal_aju=="") ? "NULL" : "'$txttanggal_aju'";
	$tgl_daftar_gk = ($txttanggal_daftar=="") ? "NULL" : "'$txttanggal_daftar'";
	// INHOUSE tidak punya nomor aju / nomor daftar
	$no_aju_gk = ($txtjenis_dok=="INHOUSE") ? "NULL" : "'$txtnomor_aju'";
	$no_daftar_gk = ($txtjenis_dok=="INHOUSE") ? "NULL" : "'$txtnomor_daftar'";
	if ($tbl=="bpb")
	{	$sql="update whs_inmaterial_fabric set type_bc='$txtjenis_dok',no_aju=$no_aju_gk,tgl_aju=$tgl_aju_gk
			,no_daftar=$no_daftar_gk,tgl_daftar=$tgl_daftar_gk,updated_at=now() where no_dok='$no_int'";
	}
	else
	{	$sql="update whs_bppb_h set dok_bc='$txtjenis_dok',no_aju=$no_aju_gk,tgl_aju=$tgl_aju_gk
			,no_daftar=$no_daftar_gk,tgl_daftar=$tgl_daftar_gk,updated_at=now() where no_bppb='$no_int'";
	}
	insert_log($sql,$user);
	// tgl transaksi yang disesuaikan ke Tgl. Daftar ikut diubah langsung di tabel gudang
	// (histori tgl asli sudah ada di bpb.bpbdate_ori / bppb.bppbdate_ori)
	if ($ubah_tgl)
	{	if ($tbl=="bpb")
		{	$sql="update whs_inmaterial_fabric set tgl_dok='$txttanggal_daftar',updated_at=now() where no_dok='$no_int' and tgl_dok<'$txttanggal_daftar'";
			insert_log($sql,$user);
			$sql="update whs_inmaterial_fabric_det set tgl_dok='$txttanggal_daftar',updated_at=now() where no_dok='$no_int' and tgl_dok<'$txttanggal_daftar'";
			insert_log($sql,$user);
		}
		else
		{	$sql="update whs_bppb_h set tgl_bppb='$txttanggal_daftar',updated_at=now() where no_bppb='$no_int' and tgl_bppb<'$txttanggal_daftar'";
			insert_log($sql,$user);
		}
	}
}
// Dokumen dari program knitting (GB, GM, GG/SGG, scrap SCR..., OFC): nomor/tgl aju & daftar juga disamakan ke DB knitting
// (PostgreSQL, koneksinya hanya dibuka di sini). Kalau gagal, data di signalbit tetap tersimpan, hanya muncul peringatan.
// Tanggal dokumen di knitting ikut dimajukan kalau tgl transaksi di signalbit pernah disesuaikan ke tgl daftar
// (tgl asli tersimpan di bpbdate_ori / bppbdate_ori), termasuk yang disesuaikan sebelum sinkron tanggal ini ada.
include_once 'sinkron_knitting.php';
$tgl_dok_knit = "";
if (flookup("max(".$tbl."date_ori)",$tbl,"$fld='$trx_no'")!="")
{	$tgl_dok_knit = flookup("min(".$tbl."date)",$tbl,"$fld='$trx_no'");
}
$knit = sinkron_pabean_knitting($tbl, $no_int, $txtjenis_dok, $txtnomor_aju, $txttanggal_aju, $txtnomor_daftar, $txttanggal_daftar, $tgl_dok_knit);
if ($knit['status']=="ok") { insert_log("X".$knit['log'],$user); }
else if ($knit['status']=="gagal") { insert_log("X[knitting] gagal sinkron $no_int: ".$knit['pesan'],$user); }
if($txtjenis_dok=="BC 2.3")
{	$cek=flookup("bcno","detail_bm","jenis_dok='$txtjenis_dok' 
		and bcno='$txtnomor_daftar' and bcdate='".fd($txttanggal_daftar)."'");
	if($cek=="")
	{	$sql="insert into detail_bm (jenis_dok,bcno,bcdate,bm,ppn,pph,bmtp) 
			values ('$txtjenis_dok','$txtnomor_daftar','".fd($txttanggal_daftar)."','$txtbm','$txtppn','$txtpph','$txtbmtp') ";
		insert_log($sql,$user);
	}
	else
	{	$sql="update detail_bm set bm='$txtbm',ppn='$txtppn',pph='$txtpph',bmtp='$txtbmtp' where  
			jenis_dok='$txtjenis_dok' and bcno='$txtnomor_daftar' and bcdate='".fd($txttanggal_daftar)."'";
		insert_log($sql,$user);
	}
}
// tampilkan info berhasil dulu (nomor internal BPB / BPPB), kembali ke daftar setelah OK
$url_kembali = "../shp/?mod=$mod2&trx=$trx&dtrx=$dtrx&dtrx2=$dtrx2";
$judul = "Data Berhasil Diperbarui";
$info = "Dokumen pabean <b>".htmlspecialchars(($no_int!="") ? $no_int : $trx_no, ENT_QUOTES)."</b> berhasil diperbarui.";
if ($ubah_tgl)
{	$info .= "<br>Tanggal ".strtoupper($tbl)." disesuaikan dari <b style='white-space:nowrap'>".fd_view($tgl_trx)."</b>"
		." menjadi <b style='white-space:nowrap'>".fd_view($txttanggal_daftar)."</b>.";
}
if ($knit['status']=="ok" && $knit['ubah']>0)
{	$info .= "<br>Data pabean di program knitting ikut diperbarui"
		.(($knit['ubah_tgl']>0) ? ", tanggal dokumennya menjadi <b style='white-space:nowrap'>".fd_view($tgl_dok_knit)."</b>." : ".");
}
else if ($knit['status']=="gagal")
{	$info .= "<br><span style='color:#c0392b; font-size:15px'>Catatan: data pabean belum tersinkron ke program knitting ("
		.htmlspecialchars($knit['pesan'], ENT_QUOTES)."), silakan hubungi IT.</span>";
}
echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>Update Dokumen Pabean</title>
<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script></head>
<body style='background:#ecf0f5; font-family:sans-serif'>
<script>
var info = ".json_encode($info).", url = ".json_encode($url_kembali).";
if (window.Swal)
{ Swal.fire({ icon: 'success', width: 600, allowOutsideClick: false,
	title: '<span style=\"font-size:26px\">$judul</span>',
	html: '<div style=\"font-size:18px; line-height:1.6\">' + info + '</div>',
	confirmButtonText: '<span style=\"font-size:16px; padding:0 16px\">OK</span>' })
  .then(function() { window.location.href = url; });
}
else { alert('$judul. ' + info.replace(/<br>/g, ' ').replace(/<[^>]*>/g, '')); window.location.href = url; }
</script></body></html>";
?>