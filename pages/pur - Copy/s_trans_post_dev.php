<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
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
$id_jo2=$_POST['txtToJOItem'];
$txtnotes=nb($_POST['txtnotes']);
if ($mod=="51r") {$retur="Y";} else {$retur="N";}
if (($bpbno=="" AND $id_item=="") OR ($bpbno<>"" AND $id_item==""))
{	# COPAS SAVE ADD
	#and pono='$txtpono' and id_supplier='$txtid_supplier' 
	#and invno='$txtinvno' and bcno='$txtbcno' and bcdate='$txtbcdate' 
	$cek="0";
	if ($cek=="0")
	{	$JmlArray = $_POST['qtybpb'];
		$JOArr = $_POST['jo'];
		$UnitArr = $_POST['unitsisa'];
		foreach ($JmlArray as $key => $value) 
		{	if (is_numeric($value))
			{	$txtqty = $JmlArray[$key];
		    $cri=split(":",$key);
		    $txtid_item=$cri[0];
		  	$line_item="";
		  	$txtunit=$UnitArr[$key];
		  	$id_jo=$JOArr[$key];
		  	$cbomat = flookup("mattype","masteritem","id_item='$txtid_item'");
				if ($txtqty>0)
				{	if ($txtreqno=="") 
					{ $date=fd($txtreqdate);
						$cri2="BOOK/".date('my',strtotime($date));
						$txtreqno=urutkan_inq("BOOK-".date('Y',strtotime($date)),$cri2); 
	 				}
					$cek="0";
					if ($cek=="0")
					{	$sql = "insert into transfer_post_dev (bookno,bookdate,id_jo_from,id_jo_to,id_item,qty,unit,username,notes) 
							values ('$txtreqno','$txtreqdate','$id_jo','$id_jo2','$txtid_item','$txtqty','$txtunit','$user','$txtnotes') ";
						insert_log($sql,$user);
					}
				}
			}
		}
	}
	# END COPAS SAVE ADD
}
$_SESSION['msg']="Data Berhasil Disimpan, Nomor Booking : ".$txtreqno;
echo "<script>window.location.href='../pur/?mod=gendev5L';</script>";
?>