<?php 
include '../../include/conn.php';
include 'fungsi.php';
include '../forms/func_gen_kartu_stock.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
if (isset($_SESSION['username'])) {} else { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
$mod=$_GET['mod'];
$mod2="35v";
$mode=$_GET['mode'];
if (isset($_GET['noid'])) {$bppbno = $_GET['noid']; } else {$bppbno = "";}
if($bppbno!="")
{	$cekap=flookup("bppbno","acc_rec","bppbno='$bppbno'");
	if($cekap!="")
	{	$_SESSION['msg']="XData Tidak Bisa Dirubah Karena Sudah Dibuat AR";
		echo "<script>window.location.href='../forms/?mod=$mod&mode=$mode&noid=$bpbno';</script>";
		break;
	}
}
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}
$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
	$gen_nomor_int=$rscomp["gen_nomor_int"];
	$auto_ap_ar=$rscomp["auto_ap_ar"];
if($auto_ap_ar=="Y") { include 'insert_ap_ar.php'; }
if (isset($_POST['txttujuan'])) { $txttujuan=nb($_POST['txttujuan']); } else { $txttujuan = ""; }
if (isset($_POST['txtsubtujuan'])) { $txtsubtujuan=nb($_POST['txtsubtujuan']); } else { $txtsubtujuan = ""; }
if (isset($_POST['txtcurr'])) { $txtcurr=nb($_POST['txtcurr']); } else { $txtcurr = ""; }
if (isset($_POST['txtparitem']) and $mod=="61a") { $txtid_item_fg = $_POST['txtparitem']; } else { $txtid_item_fg = ""; }
$txtremark=nb($_POST['txtremark']);
$txtnomor_mobil=nb($_POST['txtnomor_mobil']);
$txtid_supplier=nb($_POST['txtid_supplier']);
$txtinvno=nb($_POST['txtinvno']);
$txtkkbc=nb($_POST['txtkkbc']);
if (isset($_POST['txtbcno'])) { $txtbcno=nb($_POST['txtbcno']); } else { $txtbcno = ""; } 
if (isset($_POST['txtbcdate'])) { $txtbcdate = fd($_POST['txtbcdate']); } else { $txtbcdate = ""; } 
if (isset($_POST['txtbcaju'])) { $txtbcaju=nb($_POST['txtbcaju']); } else { $txtbcaju = ""; }
if (isset($_POST['txttglaju'])) { $txttglaju = fd($_POST['txttglaju']); } else { $txttglaju = ""; } 
if (isset($_POST['txtkpno'])) { $txtkpno=nb($_POST['txtkpno']); } else { $txtkpno = ""; } 
if ($txtid_item_fg=="") 
{ $txtid_item_fg = $txtkpno; 
	$txtkpno = flookup("kpno","masterstyle","id_item='$txtid_item_fg'");
}
else
{ $txtkpno = flookup("kpno","masterstyle","id_item='$txtid_item_fg'"); }
$txtreqno=nb($_POST['txtreqno']);
$cbomat=substr($txtreqno,3,1);
$txtbppbno=nb($_POST['txtbppbno']);
$txtbppbdate = fd($_POST['txtbppbdate']);
if (isset($_POST['chkret'])) { $retur=$_POST['chkret']; } else { $retur="N"; }
if (isset($_POST['txtstatus_kb'])) { $status_kb=nb($_POST['txtstatus_kb']); } else { $status_kb = ""; } 
if (isset($_POST['txtnomor_rak'])) {$txtnomor_rak=nb($_POST['txtnomor_rak']);} else {$txtnomor_rak="";}
if ($mod=="61r") { $nm_tbl="bppb_req"; } else { $nm_tbl="bppb"; }
if ($bppbno=="")
{ # COPAS SAVE ADD
	if ($bppbno=="") 
	{ if ($mod=="61r")
		{ $txtbppbno = urutkan('Add_Req',$cbomat); }
		else
		{ $txtbppbno = urutkan('Add_BPPB',$cbomat); 
			if($gen_nomor_int=="Y")
			{	$cbomat2=flookup("nama_pilihan","masterpilihan","kode_pilihan='MAP_MAT_".$cbomat."'");
				$date=fd($txtbppbdate);
				$cri2=$cbomat2."/OUT/".date('my',strtotime($date));
				$txtbppbno2=urutkan_inq($cbomat2."-OUT-".date('Y',strtotime($date)),$cri2);
			}
			else
			{ $txtbppbno2=""; }
		}
	}
	// $cek = flookup("count(*)","bppb","id_item='$txtid_item' and 
	// 	bppbno='$txtbppbno' and kpno='$txtkpno' 
	// 	and qty='$txtqty' and price='$txtprice'");
	$cek = "0";
	if ($cek=="0")
	{	$JmlArray = $_POST['qtyout'];
		$UnitArr = $_POST['unitsisa'];
		$RakArr = $_POST['rak'];
		$JoArr = $_POST['jono'];
		$RollArr = $_POST['qtyroll'];
		$SuppArr = $_POST['id_supp'];
		foreach ($JmlArray as $key => $value) 
		{	if (is_numeric($value))
			{	$txtqty = $JmlArray[$key];
		    $cri=split(":",$key);
		    $txtid_item=$cri[0];
		  	$line_item="";
		  	$txtunit=$UnitArr[$key];
		  	$txtid_jo=$JoArr[$key];
		  	$txtid_supplier=$SuppArr[$key];
		  	$cbomat = flookup("mattype","masteritem","id_item='$txtid_item'");
				$txtprice = 0;
				$txtberat_bersih = 0;
				$txtberat_kotor = 0;
				$txtnomor_rak = $RakArr[$key];
				if ($txtqty>0)
				{	
					$cek="0";
					if ($cek=="0")
					{	$sql = "insert into $nm_tbl (id_item,id_item_fg,qty,unit,curr,price,remark,berat_bersih,berat_kotor,nomor_mobil,
							id_supplier,invno,nomor_kk_bc,bcno,bcdate,
							bppbno,bppbno_int,bppbno_req,bppbdate,jenis_dok,username,use_kite,nomor_aju,tanggal_aju,kpno,
							nomor_rak,status_retur,tujuan,subtujuan,id_jo) values ('$txtid_item','$txtid_item_fg',
							'$txtqty','$txtunit','$txtcurr','$txtprice','$txtremark','$txtberat_bersih','$txtberat_kotor',
							'$txtnomor_mobil','$txtid_supplier','$txtinvno','$txtkkbc','$txtbcno','$txtbcdate',
							'$txtbppbno','$txtbppbno2','$txtreqno',
							'$txtbppbdate','$status_kb','$user','1','$txtbcaju','$txttglaju',
							'$txtkpno','$txtnomor_rak','$retur','$txttujuan','$txtsubtujuan','$txtid_jo')";
						#echo "<br>".$sql;
						insert_log($sql,$user);
						calc_stock($cbomat,$txtid_item);
						if ($cbomat=="FG")
						{ gen_kartu_stock($user,$sesi,$txtid_item,"bpbno like 'FG%'","bppbno like 'SJ-FG%'"); }
						else
						{ gen_kartu_stock($user,$sesi,$txtid_item,"bpbno not like 'FG%'","bppbno not like 'SJ-FG%'"); }
						$cek=cek_minus_by_date($user,$sesi);
						if($cek!="")
						{	
							$cekarr=explode(":",$cek);
							$cek=$cekarr[0];
							$cekdt=$cekarr[1];
							if ($cek<0)
							{	$sql="delete from bppb where id_item='$txtid_item' and 
									bppbno='$txtbppbno' and kpno='$txtkpno'";
								insert_log($sql,$user);
								calc_stock($cbomat,$txtid_item);
								$_SESSION['msg']="XStock Tidak Mencukupi. Cek Id Item : ".$txtid_item." Sisa Per Tgl ".$cekdt." : ".$cek;
								echo "<script>window.location.href='../forms/?mod=$mod2&mode=$mode';</script>";
								exit;
							}
						}
						else
						{
							$criroll = explode("X",$RollArr[$key]);
							foreach($criroll as $rollrak)
							{	$criroll2 = explode("|",$rollrak);
								$idroll = $criroll2[2];
								$idroll_h = $criroll2[3];
								$qtyuse = $criroll2[4];
								$sql="update bpb_roll set roll_qty_used=roll_qty_used + $qtyuse where id='$idroll' 
									and id_h='$idroll_h'";
								insert_log($sql,$user);
							}
						}
					}
				}
			}
		}
		$_SESSION['msg'] = "1";
		echo "<script>window.location.href='../forms/?mod=$mod2&mode=$mode';</script>";
	}
	else
	{	$_SESSION['msg'] = "3";
		echo "<script>window.location.href='../forms/?mod=$mod2&mode=$mode';</script>";
	}
	# END COPAS SAVE ADD
}
else if ($bppbno<>"")
{	$JmlArray = $_POST['qtyout'];
	$UnitArr = $_POST['unitsisa'];
	$JoArr = $_POST['jono'];
	$BrsArr = $_POST['line_item'];
	$SuppArr = $_POST['id_supp'];
	foreach ($JmlArray as $key => $value) 
	{	if (is_numeric($value))
		{	$txtqty = $JmlArray[$key];
	    $cri=split(":",$key);
	    $txtid_item=$cri[0];
	  	$line_item=$BrsArr[$key];
	  	$txtunit=$UnitArr[$key];
	  	$txtid_jo=$JoArr[$key];
	  	$txtid_supplier=$SuppArr[$key];
	  	$cbomat = flookup("mattype","masteritem","id_item='$txtid_item'");
			$txtprice = 0;
			$txtberat_bersih = 0;
			$txtberat_kotor = 0;
			if ($txtqty>0)
			{	$cek="0";
				if ($cek=="0")
				{	$sql = "update $nm_tbl set qty='$txtqty' where bppbno='$bppbno' and id='$line_item'";
					insert_log($sql,$user);
					calc_stock($cbomat,$txtid_item);
					if ($cbomat=="FG")
					{ gen_kartu_stock($user,$sesi,$txtid_item,"bpbno like 'FG%'","bppbno like 'SJ-FG%'"); }
					else
					{ gen_kartu_stock($user,$sesi,$txtid_item,"bpbno not like 'FG%'","bppbno not like 'SJ-FG%'"); }
					$cek=cek_minus_by_date($user,$sesi);
					if($cek!="")
					{	$cekarr=explode(":",$cek);
						$cek=$cekarr[0];
						$cekdt=$cekarr[1];
						if ($cek<0)
						{	$sql="delete from bppb where id_item='$txtid_item' and 
								bppbno='$txtbppbno' and kpno='$txtkpno'";
							insert_log($sql,$user);
							calc_stock($cbomat,$txtid_item);
							$_SESSION['msg']="XStock Tidak Mencukupi. Cek Id Item : ".$txtid_item." Sisa Per Tgl ".$cekdt." : ".$cek;
							echo "<script>window.location.href='../forms/?mod=$mod2&mode=$mode';</script>";
							exit;
						}
					}
				}
			}
		}
	}
	$_SESSION['msg'] = "2";
	echo "<script>window.location.href='../forms/?mod=$mod2&mode=$mode';</script>";
}
?>