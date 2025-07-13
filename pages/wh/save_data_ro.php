<?php 
include '../../include/conn.php';
include 'fungsi.php';
include 'journal_interface.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode=$_GET['mode'];
$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
	$gen_nomor_int=$rscomp["gen_nomor_int"];
	$auto_ap_ar=$rscomp["auto_ap_ar"];
if($auto_ap_ar=="Y") { include 'insert_ap_ar.php'; }

$txtbpbno_ro = nb($_POST['txtsjno']);
$tgl_ro = fd($_POST['txtbpbdate']);
$jenis_dok=$_POST['txtstatus_kb'];
$txtbcno=$_POST['txtbcno'];
$txtbcdate=$_POST['txtbcdate'];
$txtbcaju="";
$txttglaju="";
$cbomat=substr($txtbpbno_ro,0,1);
if (!isset($_POST['item']))
{	$_SESSION['msg'] = "XTidak Ada Data Yang Harus Disimpan";
	echo "<script>window.location.href='../forms/?mod=21v';</script>"; 
}
else
{	$QtySJ = $_POST['itemstock'];
	$QtyRI = $_POST['item'];
	if(isset($_POST['qtyroll'])) {$RollArr = $_POST['qtyroll'];} else {$RollArr = "";}
	#VALIDASI QTY RI > QTY SJ
	foreach ($QtyRI as $key => $value) 
	{	if (is_numeric($value))
		{	$txtqtysj = $QtySJ[$key];
	    $txtqtyro = $QtyRI[$key];
			if ((int)$txtqtyro>(int)$txtqtysj)
			{	$_SESSION['msg'] = "XQty RI Tidak Boleh Melebihi Qty SJ";
				echo "<script>window.location.href='../forms/?mod=$mod';</script>";
				exit;
			}    
	  }
	}
	$txtrono=urutkan("Add_RO",$cbomat);
	if($gen_nomor_int=="Y")
	{	$cbomat2=flookup("nama_pilihan","masterpilihan","kode_pilihan='MAP_MAT_".$cbomat."'");
		$date=fd($tgl_ro);
		$cri2=$cbomat2."/RO/".date('my',strtotime($date));
		$txtrono2=urutkan_inq($cbomat2."-OUT-".date('Y',strtotime($date)),$cri2);
	}
	else
	{ $txtrono2=""; }
	foreach ($QtyRI as $key => $value) 
	{	if (is_numeric($value))
		{	$txtqtyro = $QtyRI[$key];
	    $cri=explode(":",$key);
	    $id_item = $cri[0];
	    $id_jo = $cri[1];
	    $sql="select * from bpb where bpbno='$txtbpbno_ro' 
	    	and id_item='$id_item'";
	    $databpb=mysql_fetch_array(mysql_query($sql));
	    $txtid_item_fg=$databpb['id_item_fg'];
	    $txtunit=$databpb['unit'];
	    $txtcurr=$databpb['curr'];
	    $txtprice=$databpb['price'];
	    $txtremark=$_POST['txtdefect'];
	    $txtberat_bersih=0;
	    $txtberat_kotor=0;
	    $txtnomor_mobil="";
	    $txtid_supplier=$databpb['id_supplier'];
	    $txtinvno="";
	    $txtkpno=$databpb['kpno'];
	    $txtnomor_rak="";
	    $retur="Y";
	    $sql = "insert into bppb (id_item,id_item_fg,qty,unit,curr,price,remark,berat_bersih,berat_kotor,nomor_mobil,id_supplier,invno,bcno,bcdate,bppbno,bppbno_int,
				bppbdate,jenis_dok,username,use_kite,nomor_aju,tanggal_aju,kpno,
				nomor_rak,status_retur,bpbno_ro,id_jo) values ('$id_item','$txtid_item_fg',
				'$txtqtyro','$txtunit','$txtcurr','$txtprice','$txtremark','$txtberat_bersih','$txtberat_kotor',
				'$txtnomor_mobil','$txtid_supplier','$txtinvno','$txtbcno','$txtbcdate','$txtrono','$txtrono2',
				'$tgl_ro','$jenis_dok','$user','1','$txtbcaju','$txttglaju',
				'$txtkpno','$txtnomor_rak','$retur','$txtbpbno_ro','$id_jo')";
			insert_log($sql,$user);
			calc_stock($cbomat,$id_item);
	  	if($RollArr!="")
	  	{
	  		$criroll = explode("X",$RollArr[$key]);
				foreach($criroll as $rollrak)
				{	
					$qtyuse = 0; 
					$criroll2 = explode("|",$rollrak);
					$idroll = $criroll2[2];
					$idroll_h = $criroll2[3];
					$qtyuse = $criroll2[4];
					if(!ISSET($qtyuse)){
						$qtyuse = 0;
					}
					if($qtyuse == ''){
						$qtyuse = 0;
					}
					$sql="update bpb_roll set roll_qty_used=(roll_qty_used + $qtyuse) where id='$idroll' 
						and id_h='$idroll_h'";
					insert_log($sql,$user);
				}
	  	}
	  }
	}
	insert_bpb_gr_ret($txtrono);
	if($auto_ap_ar=="Y") { insert_jurnal_retur("AR",$txtrono,$user); }	
	$_SESSION['msg'] = "Data Berhasil Disimpan Dengan Nomor RO : ".$txtrono;
	echo "<script>window.location.href='../wh/?mod=out_material&mode=$mode';</script>";
}
?>