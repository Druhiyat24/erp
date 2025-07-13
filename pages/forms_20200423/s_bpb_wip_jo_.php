<?php 
include '../../include/conn.php';
include 'fungsi.php';

function generate_bpb_inhouse_in($bpb_date){
	//CHECK 
	$date_arr 	= explode("-",$bpb_date);
	$month		= INTVAL($date_arr[1]);
	$years		= INTVAL($date_arr[0]);
	$quenya = "SELECT type_flag,category,code,month,years,sequence FROM bpb_inhouse_flag WHERE 1=1 AND category='IN' AND month='{$month}' AND years='{$years}' ";
/* 	echo $quenya;
	die(); */
		$strsql = mysql_query($quenya);
		if (!$strsql) { die($quenya. mysql_error()); }
	if( mysql_num_rows($strsql) > 0){
		$rs = mysql_fetch_array($strsql);
		$sequence_val = INTVAL($rs['sequence']) + 1;
		$sequence = sprintf("%'.05d\n", $sequence_val);
		$code = $rs['code'];
		$category = $rs['category'];
		$sql="UPDATE bpb_inhouse_flag SET sequence = '{$sequence_val}' WHERE month='{$month}' AND years='{$years}'";
				insert_log($sql,'');		
	}else{
		$sequence_val = 1;
		$sequence = sprintf("%'.05d\n", $sequence_val);
		$code = "WIPIH";
		$category = "IN";
		$sql="INSERT INTO bpb_inhouse_flag (type_flag,category,code,month,years,sequence) VALUES ('WIP_INHOUSE','{$category}','{$code}','{$month}',
			'{$years}','{$sequence_val}'
		
		)";
				insert_log($sql,'');		
	}	
		
	$bpb_no_int = $code."/".$category."/".sprintf("%'.02d\n", $month).SUBSTR($years,2,2)."/".$sequence;
	return $bpb_no_int;
}

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
	$auto_jurnal=$rscomp["auto_jurnal"];
$txtberat_bersih=0;
$txtberat_kotor=0;
$txtremark="";
if (isset($_GET['noid'])) {$bpbno = $_GET['noid']; } else {$bpbno = "";}
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}
if (isset($_POST['txttujuan'])) { $txttujuan=nb($_POST['txttujuan']); } else { $txttujuan = ""; }
if (isset($_POST['txtparitem']) and $mod=="51a") { $txtid_item_fg = $_POST['txtparitem']; } else { $txtid_item_fg = ""; }
if (isset($_POST['txtkpno'])) { $txtkpno=nb($_POST['txtkpno']); } else { $txtkpno=""; }
if ($txtid_item_fg=="") 
{ $txtid_item_fg = $txtkpno; 
	$txtkpno = flookup("kpno","masterstyle","id_item='$txtid_item_fg'");
}
else
{ $txtkpno = flookup("kpno","masterstyle","id_item='$txtid_item_fg'"); }
$txtid_supplier=nb($_POST['txtid_supplier']);
$txtinvno=nb($_POST['txtinvno']);
if (isset($_POST['txtid_gudang'])) { $txtid_gudang = $_POST['txtid_gudang']; } else { $txtid_gudang=0; }
if (isset($_POST['txtbcno'])) { $txtbcno=nb($_POST['txtbcno']); } else { $txtbcno=""; }
if (isset($_POST['txtkkbc'])) { $txtkkbc=nb($_POST['txtkkbc']); } else { $txtkkbc=""; }
if (isset($_POST['txtbcdate'])) { $txtbcdate = fd($_POST['txtbcdate']); } else { $txtbcdate=""; } 
if (isset($_POST['txtbcaju'])) { $txtbcaju=nb($_POST['txtbcaju']); } else { $txtbcaju=""; }
if (isset($_POST['txttglaju'])) { $txttglaju = fd($_POST['txttglaju']); } else { $txttglaju = ""; } 
$txtreqno="";
if (isset($_POST['txtnomor_rak'])) {$txtnomor_rak=nb($_POST['txtnomor_rak']);} else {$txtnomor_rak="";}
if (isset($_POST['txtstatus_kb'])) { $status_kb=nb($_POST['txtstatus_kb']); } else { $status_kb = ""; }
$txtreqdate = fd($_POST['txtbpbdate']);
if ($bpbno=="")
{	
	$cek="0";
	if ($cek=="0")
	{	
		$JmlArray = $_POST['qtysc'];
		$JmlArrayReject = $_POST['qtyreject'];
		$UnitArr = $_POST['unitsisa'];
		if(isset($_POST['currsc'])) {$CurrArr = $_POST['currsc'];} else {$CurrArr = "";}
		$PxArr = $_POST['pxsc'];
		foreach ($JmlArray as $key => $value) 
		{	if (is_numeric($value))
			{	$txtqty = $JmlArray[$key];
				$txtqtyr = $JmlArrayReject[$key];
		    $cri=split(":",$key);
		    $txtid_item=$cri[0];
		  	$id_jo=$cri[1];
		  	$line_item="";
		  	$txtunit=$UnitArr[$key];
		  	if($CurrArr=="") {$txtcurr="";} else {$txtcurr=$CurrArr[$key];}
		  	$txtprice=$PxArr[$key];
		  	$txtqtyreject=$JmlArrayReject[$key];
		  	$cbomat = flookup("mattype","masteritem","id_item='$txtid_item'");
				if ($txtqty>0)
				{	if ($bpbno=="") 
					{ $txtreqno = urutkan("Add_BPB",$cbomat); 
						$bpbno=$txtreqno;
						if($gen_nomor_int=="Y")
						{	$cbomat2=flookup("nama_pilihan","masterpilihan","kode_pilihan='MAP_MAT_".$cbomat."'");
							$date=fd($txtreqdate);
							$cri2=$cbomat2."/IN/".date('my',strtotime($date));
							//$txtbpbno2=urutkan_inq($cbomat2."-IN-".date('Y',strtotime($date)),$cri2);
							$txtbpbno2 =generate_bpb_inhouse_in($date);
						}
						else
						{ $txtbpbno2=""; }
					}
					$cek = 0;
					if ($cek=="0")
					{	
						$sql = "insert into bpb (id_item,id_item_fg,qty,unit,curr,price,remark,berat_bersih,berat_kotor,nomor_mobil,
							id_supplier,invno,nomor_kk_bc,bcno,bcdate,bpbno,bpbno_int,bpbdate,jenis_dok,username,use_kite,nomor_aju,tanggal_aju,
							kpno,id_jo,qty_reject)
							values ('$txtid_item','$txtid_item_fg','$txtqty','$txtunit','$txtcurr','$txtprice','$txtremark','$txtberat_bersih',
							'$txtberat_kotor','','$txtid_supplier','$txtinvno','$txtkkbc','$txtbcno','$txtbcdate',
							'$txtreqno','$txtbpbno2',
							'$txtreqdate','$status_kb','$user','1','$txtbcaju','$txttglaju','$txtkpno',
							'$id_jo','$txtqtyreject')";
						insert_log($sql,$user);
						calc_stock($cbomat,$txtid_item);	
					}
				}
			}
		}
	}
	# END COPAS SAVE ADD
}
$_SESSION['msg']="Data Berhasil Disimpan, Nomor BPB : ".$txtreqno;
echo "<script>window.location.href='../forms/?mod=38v&mode=WIP';</script>";
?>