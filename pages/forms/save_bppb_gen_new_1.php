<?php 
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
$mod=$_GET['mod'];

if ($mod == 'simpan')
{	
	$txtbppbdate 	=fd($_POST['txtbppbdate']);
	$txtid_supplier	=nb($_POST['cbosupp']);
	$txtbcdate 		=fd($_POST['txtbcdate']);
	$txtnomor_rak	=nb($_POST['txtnomor_rak']);
	$txtinvno		=nb($_POST['txtinvno']);
	$cbokb			=nb($_POST['cbokb']);
	$txtremark		=nb($_POST['txtremark']);
	$dateinput		=date('Y-m-d H:i:s');

	$cbomat			= 'N';
	$txtbppbno = urutkan('Add_BPPB',$cbomat);
	$cbomat2=flookup("nama_pilihan","masterpilihan","kode_pilihan='MAP_MAT_".$cbomat."'");
				$date=fd($txtbppbdate);
				$cri2=$cbomat2."/OUT/".date('my',strtotime($date));
				$txtbppbno2=urutkan_inq($cbomat2."-OUT-".date('Y',strtotime($date)),$cri2);
	
		$JmlArray 		= $_POST['qty_out'];
		$UnitArray 		= $_POST['itemunit'];
		$Id_itemArray 	= $_POST['id_item'];

		foreach ($JmlArray as $key => $value) 
		{	if ($value!="")
			{
		    $txtid_item = $Id_itemArray[$key];
			$txtqty	 	= $JmlArray[$key];
			$txtunit	= $UnitArray[$key];

		    {	$sql = "insert into bppb (bppbno,bppbno_int, bppbdate, id_item, qty, price, remark, username, unit, bcdate, invno, id_supplier, print, bulat, tanggal_aju, status_retur, jenis_dok, confirm, nomor_rak, dateinput, cancel)
				values ('$txtbppbno','$txtbppbno2','$txtbppbdate','$txtid_item','$txtqty','0','$txtremark','$user','$txtunit','$txtbppbdate','$txtinvno',
				'$txtid_supplier','N','1','$txtbppbdate','N','$cbokb','N','$txtnomor_rak','$dateinput','N')";
			insert_log($sql,$user);
			{$_SESSION['msg']="Data Berhasil Disimpan, Nomor BPPB : ".$txtbppbno2;}
			echo "<script>window.location.href='../forms/?mod=new_bppb_gen';</script>";
		  	}
		  }
		}	
}

// if ($mod == 'update')
// {
// 	$id_req 		=$_GET['id_req'];
// 	$txtreqdate 	=fd($_POST['txtreqdate']);
// 	$cbosupp		=nb($_POST['cbosupp']);
// 	$txtremark		=nb($_POST['txtremark']);

// 		    $sql = "update bppb_req set bppbdate = '$txtreqdate', id_supplier = '$cbosupp', remark = '$txtremark' where bppbno  = '$id_req'";
// 			insert_log($sql,$user);

// 	$JmlArray 		= $_POST['qtyreq'];
// 	$Id_itemArray 	= $_POST['id_cek'];
// 	foreach ($JmlArray as $key => $value)
// 	{ 	
// 			$txtid_item = $Id_itemArray[$key];
// 			$txtqty	 	= $JmlArray[$key];

// 		    $sql_det = "update bppb_req set qty = '$txtqty' where bppbno  = '$id_req' and id_item = '$txtid_item'";
// 			insert_log($sql_det,$user);

// 			{$_SESSION['msg']="Data Berhasil Disimpan, Nomor Req : ".$id_req;}
// 			echo "<script>window.location.href='../forms/?mod=det_bppb_req&mode=Bahan_Baku&id=$id_req';</script>";
		
// 		}	  	
			
// }




?>