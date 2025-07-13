<?php 
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (isset($_SESSION['username'])) {} else { header("location:../../index.php"); }
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
$mode=$_GET['mode'];
if($_GET['mod']=="553e")
	{$mod="53v";}
else
	{$mod=str_replace("e","v",$_GET['mod']);}
$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
	$gen_nomor_int=$rscomp["gen_nomor_int"];
	$auto_ap_ar=$rscomp["auto_ap_ar"];
if($auto_ap_ar=="Y") { include 'insert_ap_ar.php'; }
$txtberat_bersih=0;
$txtberat_kotor=0;
$txtprice=0;
$txtremark="";
if (isset($_GET['noid'])) {$bpbno = $_GET['noid']; } else {$bpbno = "";}
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}
if (isset($_POST['txttujuan'])) { $txttujuan=nb($_POST['txttujuan']); } else { $txttujuan = ""; }
if (isset($_POST['txtcurr'])) { $txtcurr=nb($_POST['txtcurr']); } else { $txtcurr = ""; }
if (isset($_POST['txtparitem']) and $mod=="51a") { $txtid_item_fg = $_POST['txtparitem']; } else { $txtid_item_fg = ""; }
if (isset($_POST['txtid_sec'])) { $txtid_sec=$_POST['txtid_sec']; } else { $txtid_sec=""; }
$txtjam_masuk=nb($_POST['txtjam_masuk']);
$txtnomor_mobil=nb($_POST['txtnomor_mobil']);
$txtpono=nb($_POST['txtpono']);
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
$txtkkbc=nb($_POST['txtkkbc']);
if (isset($_POST['txtbcno'])) { $txtbcno=nb($_POST['txtbcno']); } else { $txtbcno=""; }
if (isset($_POST['txtbcdate'])) { $txtbcdate = fd($_POST['txtbcdate']); } else { $txtbcdate=""; } 
if (isset($_POST['txtbcaju'])) { $txtbcaju=nb($_POST['txtbcaju']); } else { $txtbcaju=""; }
if (isset($_POST['txttglaju'])) { $txttglaju = fd($_POST['txttglaju']); } else { $txttglaju = ""; } 
$txtbpbno=nb($_POST['txtbpbno']);
if (isset($_POST['txtnomor_rak'])) {$txtnomor_rak=nb($_POST['txtnomor_rak']);} else {$txtnomor_rak="";}
if (isset($_POST['txtstatus_kb'])) { $status_kb=nb($_POST['txtstatus_kb']); } else { $status_kb = ""; }
$txtbpbdate = fd($_POST['txtbpbdate']);
if ($mod=="51r") {$retur="Y";} else {$retur="N";}
if (($bpbno=="" AND $id_item=="") OR ($bpbno<>"" AND $id_item==""))
{	# COPAS SAVE ADD
	#and pono='$txtpono' and id_supplier='$txtid_supplier' 
	#and invno='$txtinvno' and bcno='$txtbcno' and bcdate='$txtbcdate' 
	$cek="0";
	if ($cek=="0")
	{	$JmlArray = $_POST['qtybpb'];
		$LnArr = $_POST['id_jo'];
		$qtyreject = $_POST['qtyreject'];
		$UnitArr = $_POST['unitpo'];
		$PxArr = $_POST['pricepo'];
		$CxArr = $_POST['currpo'];
		$NRakArr = $_POST['nomrak'];
		$BBerArr = $_POST['beratb'];
		$BKotArr = $_POST['beratk'];
		$KetArr = $_POST['ket'];
		$bpbno = (SUBSTR($txtbpbno,-1) == 'R' ? $txtbpbno:flookup("bpbno","bpb","bpbno_int='$txtbpbno'")  );
		foreach ($JmlArray as $key => $value) 
		{	if (is_numeric($value))
			{	$txtqty = $JmlArray[$key];
		    $cri=split(":",$key);
		    $txtid_item=$cri[0];
		  	$line_item=$cri[1];
		  	$id_jo=$LnArr[$key];
			$qty_reject=$qtyreject[$key];
		  	$txtunit=$UnitArr[$key];
		  	$cbomat = flookup("mattype","masteritem","id_item='$txtid_item'");
				$price=$PxArr[$key];
				$curr=$CxArr[$key];
				$txtnomor_rak=$NRakArr[$key];
				$txtberat_bersih=$BBerArr[$key];
				$txtberat_kotor=$BKotArr[$key];
				$txtremark=$KetArr[$key];
				if ($txtqty>0)
				{	$sql = "update bpb set qty_reject='$qty_reject', qty='$txtqty',remark='$txtremark',jam_masuk='$txtjam_masuk',
						berat_bersih='$txtberat_bersih',berat_kotor='$txtberat_kotor',nomor_mobil='$txtnomor_mobil',
						invno='$txtinvno',nomor_kk_bc='$txtkkbc',nomor_rak='$txtnomor_rak' where bpbno='$bpbno' and id='$line_item'";
/* 		print_r($sql);
		die();	 */ 				
					insert_log($sql,$user);
					calc_stock($cbomat,$txtid_item);
				}
			}
		}
		$sql = "update bpb set bcno='$txtbcno',bcdate='$txtbcdate',nomor_aju='$txtbcaju',
			tanggal_aju='$txttglaju' where bpbno='$bpbno' ";
		insert_log($sql,$user);
	}
	# END COPAS SAVE ADD
}
if($auto_ap_ar=="Y") 
{ insert_data_ap_ar("AP",$bpbno,$user); 
	insert_jurnal("AP",$bpbno,$user);
}
if($gen_nomor_int=="Y")
{$_SESSION['msg']="Data Berhasil Disimpan, Nomor BPB : ".$txtbpbno." (".$txtbpbno2.")";}
else
{$_SESSION['msg']="Data Berhasil Disimpan, Nomor BPB : ".$txtbpbno;}
echo "<script>window.location.href='../forms/?mod=$mod&mode=$mode&noid=$bpbno';</script>";
?>