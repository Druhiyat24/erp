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
// Tgl. transaksi BPB tidak boleh lebih dulu dari Tgl. Daftar, dicek sebelum ada data yang diubah (INHOUSE tidak punya Tgl. Daftar)
// sementara hanya untuk pemasukan (bpb), pengeluaran (bppb) belum dibatasi
$tgl_trx = flookup("min(".$tbl."date)",$tbl,"$fld='$trx_no'");
if ($tbl=="bpb" && $txtjenis_dok!="INHOUSE" && $txttanggal_daftar!="" && $tgl_trx!="" && $tgl_trx < $txttanggal_daftar)
{	$_SESSION['msg'] = "XTgl. Transaksi ".strtoupper($tbl)." ".fd_view($tgl_trx)." lebih dulu dari Tgl. Daftar ".fd_view($txttanggal_daftar).", data tidak disimpan";
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
// Dokumen gudang kain (nomor GK/...) juga ada di tabel gudang: pemasukan di whs_inmaterial_fabric, pengeluaran di whs_bppb_h.
// Yang disamakan hanya data pabean, no_invoice tetap milik gudang (SJ supplier)
$no_gk = flookup($fld."_int",$tbl,"$fld='$trx_no'");
if (substr($no_gk,0,3)=="GK/")
{	$tgl_aju_gk = ($txttanggal_aju=="") ? "NULL" : "'$txttanggal_aju'";
	$tgl_daftar_gk = ($txttanggal_daftar=="") ? "NULL" : "'$txttanggal_daftar'";
	// INHOUSE tidak punya nomor aju / nomor daftar
	$no_aju_gk = ($txtjenis_dok=="INHOUSE") ? "NULL" : "'$txtnomor_aju'";
	$no_daftar_gk = ($txtjenis_dok=="INHOUSE") ? "NULL" : "'$txtnomor_daftar'";
	if ($tbl=="bpb")
	{	$sql="update whs_inmaterial_fabric set type_bc='$txtjenis_dok',no_aju=$no_aju_gk,tgl_aju=$tgl_aju_gk
			,no_daftar=$no_daftar_gk,tgl_daftar=$tgl_daftar_gk,updated_at=now() where no_dok='$no_gk'";
	}
	else
	{	$sql="update whs_bppb_h set dok_bc='$txtjenis_dok',no_aju=$no_aju_gk,tgl_aju=$tgl_aju_gk
			,no_daftar=$no_daftar_gk,tgl_daftar=$tgl_daftar_gk,updated_at=now() where no_bppb='$no_gk'";
	}
	insert_log($sql,$user);
}
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
$_SESSION['msg'] = "Data Berhasil Dirubah";
echo "<script>window.location.href='../shp/?mod=$mod2&trx=$trx&dtrx=$dtrx&dtrx2=$dtrx2';</script>";
?>