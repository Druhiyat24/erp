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

$txtberat_bersih=0;
$txtberat_kotor=0;
$txtprice=0;
// $txtremark="";
if (isset($_POST['txtremark'])) { $txtremark=nb($_POST['txtremark']); } else { $txtremark=""; }
if (isset($_GET['id'])) {$reqno = $_GET['id']; } else {$reqno = "";}
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}
if (isset($_POST['txttujuan'])) { $txttujuan=nb($_POST['txttujuan']); } else { $txttujuan = ""; }
if (isset($_POST['txtcurr'])) { $txtcurr=nb($_POST['txtcurr']); } else { $txtcurr = ""; }
if (isset($_POST['txtparitem']) and $mod=="51a") { $txtid_item_fg = $_POST['txtparitem']; } else { $txtid_item_fg = ""; }
if (isset($_POST['txtkpno'])) { $txtkpno=nb($_POST['txtkpno']); } else { $txtkpno=""; }
if (isset($_POST['txtidws_act'])) { $txtidws_act=nb($_POST['txtidws_act']); } else { $txtidws_act=""; }
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
if(isset($_POST['txtJOItem'])) {$id_jo=$_POST['txtJOItem'];} else {$id_jo="";}
if ($mod=="51r") {$retur="Y";} else {$retur="N";}
if ($mod=="61re")
{	$JmlArray = $_POST['txtqtyed'];
	foreach ($JmlArray as $key => $value) 
	{	if (is_numeric($value))
		{	$txtqty = $JmlArray[$key];
	    $txtid_item=$key;
	  	if ($txtqty>0)
			{	$sql="update bppb_req set id_supplier='$txtid_supplier',qty='$txtqty' where bppbno='$reqno' and id_item='$txtid_item'";
				insert_log($sql,$user);
			}
		}
	}
	$_SESSION['msg']="Data Berhasil Dirubah";
}
else
{	if (($reqno=="" AND $id_item=="") OR ($reqno<>"" AND $id_item==""))
	{	$cek="0";
		if ($cek=="0")
		{	$JmlArray = $_POST['qtybpb'];
			$UnitArr = $_POST['unitsisa'];
			$JOArr = $_POST['jo'];
			foreach ($JmlArray as $key => $value) 
			{	if (is_numeric($value))
				{	$txtqty = $JmlArray[$key];
			    $cri=split(":",$key);
			    $txtid_item=$cri[0];
			  	$line_item="";
			  	$txtunit=$UnitArr[$key];
			  	$cbomat = flookup("mattype","masteritem","id_item='$txtid_item'");
					$id_jo = $JOArr[$key];
			    if ($txtqty>0)
					{	if ($txtreqno=="") { $txtreqno = urutkan("Add_BPPBReq",$cbomat); }
						$cek = flookup("count(*)","bppb_req","id_item='$txtid_item' 
						 	and bppbno='$txtreqno' and id_jo='$id_jo'");
						if ($cek=="0")
						{	$sql = "insert into bppb_req (id_item,id_item_fg,qty,qty_out,unit,curr,price,remark,berat_bersih,berat_kotor,nomor_mobil,id_supplier,
								invno,bcno,bcdate,bppbno,bppbdate,jenis_dok,username,use_kite,nomor_aju,tanggal_aju,
								kpno,id_jo,idws_act)
								values ('$txtid_item','$txtid_item_fg','$txtqty','0','$txtunit','$txtcurr','$txtprice','$txtremark','$txtberat_bersih',
								'$txtberat_kotor','','$txtid_supplier','','$txtbcno','$txtbcdate','$txtreqno',
								'$txtreqdate','$status_kb','$user','1','$txtbcaju','$txttglaju','$txtkpno',
								'$id_jo','$txtidws_act')";
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
}
echo "<script>window.location.href='../forms/?mod=61rv';</script>";
?>