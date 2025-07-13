<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mod2="2L";
$trx=$_GET['trx'];
$dtrx=$_GET['dtrx'];

$trx_no=nb($_POST['txttrx_no']);
$txtjenis_dok=$_POST['txtjenis_dok'];
$txtnomor_aju=$_POST['txtnomor_aju'];
$txttanggal_aju=fd($_POST['txttanggal_aju']);
$txtnomor_daftar=$_POST['txtnomor_daftar'];
$txttanggal_daftar=fd($_POST['txttanggal_daftar']);
$txtnomor_fp=$_POST['txtnomor_fp'];
$txttanggal_fp=fd($_POST['txttanggal_fp']);
$txtinvno=$_POST['txtinvno'];
if(isset($_POST['txtbm'])) { $txtbm=$_POST['txtbm']; } else { $txtbm=0; }
if(isset($_POST['txtppn'])) { $txtppn=$_POST['txtppn']; } else { $txtppn=0; }
if(isset($_POST['txtpph'])) { $txtpph=$_POST['txtpph']; } else { $txtpph=0; }
if(isset($_POST['txtbmtp'])) { $txtbmtp=$_POST['txtbmtp']; } else { $txtbmtp=0; }
if(substr($trx_no, 0,2)=="SJ") 
{ $tbl="bppb"; $fld="bppbno"; $add_fld=""; } 
else 
{ $tbl="bpb"; $fld="bpbno"; $add_fld=",update_dok_pab='Y'"; }
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
	,no_fp='$txtnomor_fp',tgl_fp='$txttanggal_fp',invno='$txtinvno' $add_fld where $fld='$trx_no'";
insert_log($sql,$user);
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
echo "<script>window.location.href='../shp/?mod=$mod2&trx=$trx&dtrx=$dtrx';</script>";
?>