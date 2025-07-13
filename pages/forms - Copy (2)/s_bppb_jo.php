<?php 
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (isset($_SESSION['username'])) {} else { header("location:../../index.php"); }
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
if (isset($_GET['mode'])) {$mode=$_GET['mode'];} else {$mode="";}
$mod=$_GET['mod'];
$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
	$gen_nomor_int=$rscomp["gen_nomor_int"];
	$auto_ap_ar=$rscomp["auto_ap_ar"];
$txtberat_bersih=0;
$txtberat_kotor=0;
$txtprice=0;
$txtremark="";
if (isset($_GET['noid'])) {$bpbno = $_GET['noid']; } else {$bpbno = "";}
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}
if (isset($_POST['txttujuan'])) { $txttujuan=nb($_POST['txttujuan']); } else { $txttujuan = ""; }
if (isset($_POST['txtcurr'])) { $txtcurr=nb($_POST['txtcurr']); } else { $txtcurr = ""; }
if (isset($_POST['txtparitem']) and $mod=="51a") { $txtid_item_fg = $_POST['txtparitem']; } else { $txtid_item_fg = ""; }
if (isset($_POST['txtkpno'])) { $txtkpno=nb($_POST['txtkpno']); } else { $txtkpno=""; }
if ($txtid_item_fg=="") 
{ $txtid_item_fg = $txtkpno; 
	$txtkpno = flookup("kpno","masterstyle","id_item='$txtid_item_fg'");
}
else
{ $txtkpno = flookup("kpno","masterstyle","id_item='$txtid_item_fg'"); }
$txtid_supplier=nb($_POST['txtid_supplier']);
if (isset($_POST['txtid_gudang'])) { $txtid_gudang = $_POST['txtid_gudang']; } else { $txtid_gudang=0; }
if (isset($_POST['txtbcno'])) { $txtbcno=nb($_POST['txtbcno']); } else { $txtbcno=""; }
if (isset($_POST['txtbcdate'])) { $txtbcdate = fd($_POST['txtbcdate']); } else { $txtbcdate=""; } 
if (isset($_POST['txtbcaju'])) { $txtbcaju=nb($_POST['txtbcaju']); } else { $txtbcaju=""; }
if (isset($_POST['txttglaju'])) { $txttglaju = fd($_POST['txttglaju']); } else { $txttglaju = ""; } 
$txtreqno=nb($_POST['txtreqno']);
if (isset($_POST['txtnomor_rak'])) {$txtnomor_rak=nb($_POST['txtnomor_rak']);} else {$txtnomor_rak="";}
if (isset($_POST['txtstatus_kb'])) { $status_kb=nb($_POST['txtstatus_kb']); } else { $status_kb = ""; }
$txtreqdate = fd($_POST['txtreqdate']);
$id_jo=$_POST['txtJOItem'];
if ($mod=="51r") {$retur="Y";} else {$retur="N";}
if (($bpbno=="" AND $id_item=="") OR ($bpbno<>"" AND $id_item==""))
{	# COPAS SAVE ADD
	#and pono='$txtpono' and id_supplier='$txtid_supplier' 
	#and invno='$txtinvno' and bcno='$txtbcno' and bcdate='$txtbcdate' 
	$cek="0";
	if ($cek=="0")
	{	$JmlArray = $_POST['qtysisa'];
		$UnitArr = $_POST['unitsisa'];
		foreach ($JmlArray as $key => $value) 
		{	if (is_numeric($value))
			{	$txtqty = $JmlArray[$key];
		    $cri=split(":",$key);
		    $txtid_item=$cri[0];
		  	$line_item="";
		  	$txtunit=$UnitArr[$key];
		  	$cbomat = flookup("mattype","masteritem","id_item='$txtid_item'");
				if ($txtqty>0)
				{	if ($txtreqno=="") 
					{ $txtreqno = urutkan($mode,$cbomat); }
					$cek = flookup("count(*)","bpb","id_item='$txtid_item' 
						and bpbno='$txtreqno' and kpno='$txtkpno' and id_po_item='$line_item'");
					if ($cek=="0")
					{	$sql = "insert into bppb_req (id_item,id_item_fg,qty,unit,curr,price,remark,berat_bersih,berat_kotor,nomor_mobil,id_supplier,
							invno,bcno,bcdate,bppbno,bppbdate,jenis_dok,username,use_kite,nomor_aju,tanggal_aju,
							kpno,id_jo)
							values ('$txtid_item','$txtid_item_fg','$txtqty','$txtunit','$txtcurr','$txtprice','$txtremark','$txtberat_bersih',
							'$txtberat_kotor','','$txtid_supplier','','$txtbcno','$txtbcdate',
							'$txtreqno',
							'$txtreqdate','$status_kb','$user','1','$txtbcaju','$txttglaju','$txtkpno',
							'$id_jo')";
						insert_log($sql,$user);
						calc_stock($cbomat,$txtid_item);	
					}
				}
			}
		}
	}
	# END COPAS SAVE ADD
}
$_SESSION['msg']="Data Berhasil Disimpan, Nomor Req : ".$txtreqno;
echo "<script>window.location.href='../forms/?mod=$mod';</script>";
?>