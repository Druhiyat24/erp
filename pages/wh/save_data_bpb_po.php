<?php 
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (isset($_SESSION['username'])) {} else { header("location:../../index.php"); }
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
$mode=$_GET['mode'];
$mod=$_GET['mod'];
$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
	$gen_nomor_int=$rscomp["gen_nomor_int"];
	$auto_ap_ar=$rscomp["auto_ap_ar"];
	$auto_jurnal=$rscomp["auto_jurnal"];
#if($auto_ap_ar=="Y") { include 'insert_ap_ar.php'; }
if($auto_ap_ar=="Y") { include 'journal_interface.php'; }
$txtprice=0;
$txtremark=nb($_POST['txtremark']);
if (isset($_GET['noid'])) {$bpbno = $_GET['noid']; } else {$bpbno = "";}
if($bpbno!="")
{	$cekap=flookup("bpbno","acc_pay","bpbno='$bpbno'");
	if($cekap!="")
	{	$_SESSION['msg']="XData Tidak Bisa Dirubah Karena Sudah Dibuat AP";
		echo "<script>window.location.href='../forms/?mod=$mod&mode=$mode&noid=$bpbno';</script>";
		break;
	}
}
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
$txtcontractno=nb($_POST['txtcontractno']);
$txtpo_asal=nb($_POST['txtpo_asal']);
$txtjns_in=nb($_POST['txtjns_in']);
if(isset($_POST['txtbcno'])) {$txtbcno=nb($_POST['txtbcno']);} else {$txtbcno="";}
if (isset($_POST['txtbcdate'])) { $txtbcdate = fd($_POST['txtbcdate']); } else { $txtbcdate=""; } 
if (isset($_POST['txtkkbc'])) { $txtkkbc=nb($_POST['txtkkbc']); } else { $txtkkbc=""; }
if (isset($_POST['txtno_fp'])) { $txtno_fp=nb($_POST['txtno_fp']); } else { $txtno_fp=""; }
if (isset($_POST['txttglfp'])) { $txttglfp = fd($_POST['txttglfp']); } else { $txttglfp=""; } 
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
		$BalArray = $_POST['qtybal'];
		$JmlArrayReject = $_POST['qtyreject'];
		$LnArr = $_POST['id_jo'];
		$UnitArr = $_POST['unitpo'];
		$PxArr = $_POST['pricepo'];
		$CxArr = $_POST['currpo'];
		if(isset($_POST['nomrak'])) {$NRakArr = $_POST['nomrak'];} else {$NRakArr = "";}
		$BBerArr = $_POST['beratb'];
		$BKotArr = $_POST['beratk'];
		if(isset($_POST['ket'])) {$KetArr = $_POST['ket'];} else {$KetArr = "";}
		
		foreach ($JmlArray as $key => $value) 
		{	if (is_numeric($value))
			{	$txtqty = $JmlArray[$key];
				$txtqtyr = $JmlArrayReject[$key];
		    $cri=split(":",$key);
		    $txtid_item=$cri[0];
		  	$line_item=$cri[1];
		  	$id_jo=$LnArr[$key];
		  	$txtunit=$UnitArr[$key];
		  	$cbomat = flookup("mattype","masteritem","id_item='$txtid_item'");
				$price=$PxArr[$key];
				//$txtqtyreject=$JmlArrayReject[$key];
				$curr=$CxArr[$key];
				$txtnomor_rak="";
				$txtberat_bersih=$BBerArr[$key];
				$txtberat_kotor=$BKotArr[$key];
				$txtbal=$BalArray[$key];
				if ($txtqty>0)
				{	if ($txtbpbno=="") 
					{ $txtbpbno = urutkan($mode,$cbomat); 
						if($gen_nomor_int=="Y")
						{	$cbomat2=flookup("nama_pilihan","masterpilihan","kode_pilihan='MAP_MAT_".$cbomat."'");
							$date=fd($txtbpbdate);
							$cri2=$cbomat2."/IN/".date('my',strtotime($date));
							$txtbpbno2=urutkan_inq($cbomat2."-IN-".date('Y',strtotime($date)),$cri2);
							//echo $txtbpbno2;
							//die();
						}
						else
						{ $txtbpbno2=""; }	
					}
					if(($txtqty > $txtbal or $txtbal < 0) and $mode=="Bahan_Baku")
					{
						if($txtbal<=0)
						{
							$txtsisa = $txtqty;
							$txtqty = 0;
						}
						else
						{
							$txtsisa = $txtqty - $txtbal;
							$txtqty = $txtbal;
						}
						$sql="update po_header set po_over='Y' where pono='$txtpono'";
						insert_log($sql,$user);
					}
					else
					{
						$txtsisa = 0;
					}
					$cek = flookup("count(*)","bpb","id_item='$txtid_item' 
						and bpbno='$txtbpbno' and kpno='$txtkpno' and id_po_item='$line_item'");
					if ($cek=="0")
					{	
				
				//die();
				$sql = "insert into bpb (id_item,id_item_fg,qty,qty_over,unit,curr,price,remark,jam_masuk,berat_bersih,berat_kotor,nomor_mobil,pono,id_supplier,
							invno,nomor_kk_bc,bcno,bcdate,no_fp,tgl_fp,bpbno,bpbno_int,bpbdate,tujuan,jenis_dok,username,use_kite,nomor_aju,tanggal_aju,
							kpno,id_gudang,nomor_rak,status_retur,id_po_item,id_jo,id_sec,qty_reject,no_contract,jenis_trans,reffno)
							values ('$txtid_item','$txtid_item_fg','$txtqty','$txtsisa','$txtunit','$curr','$price','$txtremark','$txtjam_masuk','$txtberat_bersih',
							'$txtberat_kotor','$txtnomor_mobil','$txtpono','$txtid_supplier','$txtinvno','$txtkkbc','$txtbcno','$txtbcdate',
							'$txtno_fp','$txttglfp','$txtbpbno','$txtbpbno2',
							'$txtbpbdate','$txttujuan','$status_kb','$user','1','$txtbcaju','$txttglaju','$txtkpno',
							'$txtid_gudang','$txtnomor_rak','$retur','$line_item','$id_jo','$txtid_sec','$txtqtyr','$txtcontractno','$txtjns_in','$txtpo_asal')";
						insert_log($sql,$user);
						calc_stock($cbomat,$txtid_item);	
						#echo "<br>".$sql;
					}
				}
			}
		}
	}
	# END COPAS SAVE ADD
}
if($auto_ap_ar=="Y") 
{ if($auto_ap_ar=="Y") {insert_data_ap_ar("AP",$txtbpbno,$user);}
	#insert_jurnal("AP",$txtbpbno,$user);
	if($auto_jurnal=="Y")
	{	$period=date('m/Y',strtotime($txtbpbdate));
		$datenya=date("Y-m-d H:i:s");
		$jh = 
		array
		(	'period' => '$period', // Periode accounting (mm/yyyy)
	    'num_journal' => "200", // Nomor Jurnal (string)
	    'date_journal' => '$txtbpbdate', // Tanggal jurnal (yyyy-mm-dd)
	    'type_journal' => 2, // Tipe jurnal, refer ke $journal_type
	    'reff_doc' => '$txtinvno', // Referensi dokumen
	    'fg_intercompany' => 0, // Flag transaksi intercompany (0/1)
	    'id_intercompany' => "", // Id company
	    'fg_post' => '2', // Posting (2), Parked(0). refer ke $posting_flag
	    'date_post' => '$datenya', // Timestamp ganti status dari parked ke posting. kosongkan jika parked.
	    'user_post' => '$user', // User yang mengubah status ke posting. kosongkan jika parked.
	    'dateadd' => '$datenya', // Timestamp jurnal di buat
	    'useradd' => '$user', // User yang membuat jurnal
		);
		$jd = 
		array
		(	array
			(	'id_coa' => '$id_coa', // id chart of account. referensi dari tabel mastercoa
	      'curr' => '$txtcurr', // kode currency
	      'id_costcenter' => '$id_costcenter', // id cost center, referensi dari tabel mastercostcenter
	      'nm_ws' => 'FKLCTCTN100CTNBLK123',// nama/nomor workstation
	      'debit' => $nil_trx, // Nominal debit
	      'credit' => 0, // Nominal kredit
	      'description' => "FABRIC KNIT LACOSTE COTTON 100% COTTON BLACK 123", // Deskripsi jurnal/transaksi
	      'dateadd' => '$datenya', // Timestamp jurnal di buat
	      'useradd' => '$user', // User yang membuat jurnal
	    ),
	    array
	    (	'id_coa' => '13001',
	      'curr' => 'IDR',
	      'id_costcenter' => '03-90-903',
	      'nm_ws' => 'FKLCTCTN100CTNBLK123',
	      'debit' => 0,
	      'credit' => $nil_trx,
	      'description' => "FABRIC KNIT LACOSTE COTTON 100% COTTON BLACK 123",
	      'dateadd' => '$datenya',
	      'useradd' => '$user',
	    )
		);
		$result=journal_posting($jh, $jd);
	}
}
if($gen_nomor_int=="Y")
{$_SESSION['msg']="Data Berhasil Disimpan, Nomor BPB : ".$txtbpbno." (".$txtbpbno2.")";}
else
{$_SESSION['msg']="Data Berhasil Disimpan, Nomor BPB : ".$txtbpbno;}
echo "<script>window.location.href='../wh/?mod=in_material&mode=$mode&noid=$bpbno';</script>";
?>