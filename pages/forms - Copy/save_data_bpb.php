<?php 
include '../../include/conn.php';
include 'fungsi.php';
include '../forms/func_gen_kartu_stock.php';
session_start();
if (isset($_SESSION['username'])) {} else { header("location:../../index.php"); }
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
$mode=$_GET['mode'];
$mod=$_GET['mod'];
$gen_nomor_int=flookup("gen_nomor_int","mastercompany","company!=''");
if (isset($_GET['noid'])) {$bpbno = $_GET['noid']; } else {$bpbno = "";}
if (isset($_GET['id'])) {$line_id = $_GET['id']; } else {$line_id = "";}

if($bpbno!="")
{	$cekap=flookup("bpbno","acc_pay","bpbno='$bpbno'");
	if($cekap!="")
	{	$_SESSION['msg']="XData Tidak Bisa Dirubah Karena Sudah Dibuat AP";
		echo "<script>window.location.href='../forms/?mod=$mod&mode=$mode&noid=$bpbno';</script>";
		break;
	}
}
if(isset($_POST['txtid_sec'])) {$txtid_sec=$_POST['txtid_sec'];} else {$txtid_sec="";}
$txtid_item=nb($_POST['txtid_item']);
if ($mode=="FG")
{ $cbomat = "FG"; }
else
{ $cbomat = flookup("mattype","masteritem","id_item='$txtid_item'"); }
$txtqty=unfn($_POST['txtqty']);
$txtunit=nb($_POST['txtunit']);
$txtno_fp=nb($_POST['txtno_fp']);
$txttglfp=fd($_POST['txttglfp']);
if (isset($_POST['txttujuan'])) { $txttujuan=nb($_POST['txttujuan']); } else { $txttujuan = ""; }
if (isset($_POST['txtsubtujuan'])) { $txtsubtujuan=nb($_POST['txtsubtujuan']); } else { $txtsubtujuan = ""; }
if (isset($_POST['txtcurr'])) { $txtcurr=nb($_POST['txtcurr']); } else { $txtcurr = ""; }
if (isset($_POST['txtparitem']) and ($mod=="51a" or $mod=="51")) { $txtid_item_fg = $_POST['txtparitem']; } else { $txtid_item_fg = ""; }
$txtprice=unfn($_POST['txtprice']);
$txtremark=nb($_POST['txtremark']);
$txtjam_masuk=nb($_POST['txtjam_masuk']);
$txtberat_bersih=nb($_POST['txtberat_bersih']);
$txtberat_kotor=nb($_POST['txtberat_kotor']);
$txtnomor_mobil=nb($_POST['txtnomor_mobil']);
$txtpono=nb($_POST['txtpono']);
if(isset($_POST['txttotal'])) {$txttotal=unfn($_POST['txttotal']);} else {$txttotal="0";}
if (isset($_POST['txtkpno'])) { $txtkpno=nb($_POST['txtkpno']); } else { $txtkpno=""; }
if ($txtid_item_fg=="") 
{ $txtid_item_fg = $txtkpno; 
	$txtkpno = flookup("kpno","masterstyle","id_item='$txtid_item_fg'");
}
else
{ $txtkpno = flookup("kpno","masterstyle","id_item='$txtid_item_fg'"); }
$txtid_supplier=nb($_POST['txtid_supplier']);
if (isset($_POST['txtid_gudang'])) { $txtid_gudang = $_POST['txtid_gudang']; } else { $txtid_gudang=0; }
$txtinvno=nb($_POST['txtinvno']);
if (isset($_POST['txtbcno'])) { $txtbcno=nb($_POST['txtbcno']); } else { $txtbcno=""; }
if (isset($_POST['txtkkbc'])) { $txtkkbc=nb($_POST['txtkkbc']); } else { $txtkkbc=""; }
if (isset($_POST['txtbcdate'])) { $txtbcdate = fd($_POST['txtbcdate']); } else { $txtbcdate=""; } 
if (isset($_POST['txtbcaju'])) { $txtbcaju=nb($_POST['txtbcaju']); } else { $txtbcaju=""; }
if (isset($_POST['txttglaju'])) { $txttglaju = fd($_POST['txttglaju']); } else { $txttglaju = ""; } 
$txtbpbno=nb($_POST['txtbpbno']);
if (isset($_POST['txtnomor_rak'])) {$txtnomor_rak=nb($_POST['txtnomor_rak']);} else {$txtnomor_rak="";}
if (isset($_POST['txtstatus_kb'])) { $status_kb=nb($_POST['txtstatus_kb']); } else { $status_kb = ""; }
$txtbpbdate = fd($_POST['txtbpbdate']);
if ($mod=="51r") {$retur="Y";} else {$retur="N";}
if (($bpbno=="" AND $line_id=="") OR ($bpbno<>"" AND $line_id==""))
{	# COPAS SAVE ADD
	if ($mod=="51r")
	{ if ($bpbno=="") { $txtbpbno = urutkan("Add_RI",$cbomat); } }
	else
	{ if ($bpbno=="") 
		{ $txtbpbno = urutkan($mode,$cbomat); 
			if($gen_nomor_int=="Y")
			{	$cbomat2=flookup("nama_pilihan","masterpilihan","kode_pilihan='MAP_MAT_".$cbomat."'");
				$date=fd($txtbpbdate);
				$cri2=$cbomat2."/".date('my',strtotime($date));
				$txtbpbno2=urutkan_inq($cbomat2."-".date('Y',strtotime($date)),$cri2);
			}
		} 
	}
	#and pono='$txtpono' and id_supplier='$txtid_supplier' 
	#and invno='$txtinvno' and bcno='$txtbcno' and bcdate='$txtbcdate' 
	$cek = flookup("count(*)","bpb","id='$line_id' and bpbno='$txtbpbno' ");
	if ($cek=="0")
	{	$sql = "insert into bpb (id_item,id_item_bb,id_item_fg,qty,unit,curr,price,remark,jam_masuk,berat_bersih,berat_kotor,nomor_mobil,pono,id_supplier,
			invno,nomor_kk_bc,bcno,bcdate,bpbno,bpbno_int,bpbdate,jenis_dok,username,use_kite,nomor_aju,tanggal_aju,
			kpno,id_gudang,nomor_rak,status_retur,
			no_fp,tgl_fp,tujuan,subtujuan,total_nilai,id_sec)
			values ('$txtid_item','$txtid_item','$txtid_item_fg','$txtqty','$txtunit','$txtcurr','$txtprice',
			'$txtremark','$txtjam_masuk','$txtberat_bersih',
			'$txtberat_kotor','$txtnomor_mobil','$txtpono','$txtid_supplier','$txtinvno','$txtkkbc',
			'$txtbcno','$txtbcdate',
			'$txtbpbno','$txtbpbno2',
			'$txtbpbdate','$status_kb','$user','1','$txtbcaju','$txttglaju','$txtkpno',
			'$txtid_gudang','$txtnomor_rak','$retur',
			'$txtno_fp','$txttglfp','$txttujuan','$txtsubtujuan','$txttotal','$txtid_sec')";
		insert_log($sql,$user);
		calc_stock($cbomat,$txtid_item);
		if($gen_nomor_int=="Y")
		{$_SESSION['msg']="Data Berhasil Disimpan, Nomor BPB : ".$txtbpbno." (".$txtbpbno2.")";}
		else
		{$_SESSION['msg']="Data Berhasil Disimpan, Nomor BPB : ".$txtbpbno;}
		echo "<script>window.location.href='../forms/?mod=$mod&mode=$mode&noid=$txtbpbno';</script>";
	}
	else
	{	$_SESSION['msg'] = "3";
		echo "<script>window.location.href='index.php?mod=$mod&mode=$mode&noid=$txtbpbno';</script>";
	}
	# END COPAS SAVE ADD
}
else if ($bpbno<>"" AND $line_id<>"")
{	# COPAS SAVE EDIT
	# UPDATE DETAIL
	$cri="bpbno='$txtbpbno' and id='$line_id'";
	$qty_old=flookup("qty","bpb",$cri);
	$bpbdate_old=fd(flookup("bpbdate","bpb",$cri));
	$sql = "update bpb set id_item='$txtid_item',
		qty='$txtqty',unit='$txtunit',
		curr='$txtcurr',price='$txtprice',
		remark='$txtremark',jam_masuk='$txtjam_masuk',
		berat_bersih='$txtberat_bersih',berat_kotor='$txtberat_kotor',
		nomor_rak='$txtnomor_rak',kpno='$txtkpno',status_retur='$retur'
		where $cri";
	insert_log($sql,$user);
	calc_stock($cbomat,$txtid_item);
	# UPDATE HEADER
	$sql = "update bpb set no_fp='$txtno_fp',tgl_fp='$txttglfp',jenis_dok='$status_kb',nomor_mobil='$txtnomor_mobil',
		pono='$txtpono',id_supplier='$txtid_supplier',invno='$txtinvno',bcno='$txtbcno',
		bcdate='$txtbcdate',bpbdate='$txtbpbdate',tujuan='$txttujuan',subtujuan='$txtsubtujuan',username='$user',
		nomor_aju='$txtbcaju',tanggal_aju='$txttglaju' 
		where bpbno='$txtbpbno'";
	insert_log($sql,$user);
	if ($cbomat=="FG")
	{ gen_kartu_stock($user,$sesi,$txtid_item,"bpbno like 'FG%'","bppbno like 'SJ-FG%'"); }
	else
	{ gen_kartu_stock($user,$sesi,$txtid_item,"bpbno not like 'FG%'","bppbno not like 'SJ-FG%'"); }
	$cek=cek_minus_by_date($user,$sesi);
	$cekarr=explode(":",$cek);
	$cek=$cekarr[0];
	$cekdt=$cekarr[1];
	if ($cek<0)
	{	$sql="update bpb set bpbdate='$bpbdate_old',qty='$qty_old' where $cri";
		insert_log($sql,$user);
		$_SESSION['msg']="XStock Tidak Mencukupi. Cek Id Item : ".$txtid_item." Sisa Per Tgl ".$cekdt." : ".$cek;
		calc_stock($cbomat,$txtid_item);
		echo "<script>window.location.href='index.php?mod=$mod&mode=$mode&noid=$bpbno';</script>";
		exit;
	}
	$_SESSION['msg']="2";
	echo "<script>window.location.href='../forms/?mod=$mod&mode=$mode&noid=$bpbno';</script>";
	# END COPAS SAVE EDIT
}
?>