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
	$txtid_supplier	=nb($_POST['cbosupp']);
	$cbomat			=nb($_POST['cbotipe']);
	$txtreqdate 	=fd($_POST['txtreqdate']);
	$txtremark		=nb($_POST['txtremark']);
	$txtwsact		=nb($_POST['cbowsact']);
	$dateinput		=date('Y-m-d H:i:s');

	$txtreqno = urutkan("Add_BPPBReq",$cbomat); 
	
		$JmlArray 		= $_POST['qtyreq'];
		$UnitArray 		= $_POST['unit_req'];
		$Id_itemArray 	= $_POST['id_item_req'];
		$Id_joArray 	= $_POST['id_jo_req'];

		foreach ($JmlArray as $key => $value) 
		{	if ($value!="")
			{
		    $txtid_item = $Id_itemArray[$key];
			$txtqty	 	= $JmlArray[$key];
			$txtunit	= $UnitArray[$key];
			$id_jo	 	= $Id_joArray[$key];

		    {	$sql = "insert into bppb_req (bppbno,bppbdate,id_item,qty,remark,username,unit,id_supplier,id_jo,cancel,idws_act,qty_out,use_kite,berat_bersih,berat_kotor)
				values ('$txtreqno','$txtreqdate','$txtid_item','$txtqty','$txtremark','$user','$txtunit',
				'$txtid_supplier','$id_jo','N','$txtwsact','0','1','0','0')";
			insert_log($sql,$user);
			{$_SESSION['msg']="Data Berhasil Disimpan, Nomor Req : ".$txtreqno;}
			echo "<script>window.location.href='../forms/?mod=list_bppb_req';</script>";
		  	}
		  }
		}	
}

if ($mod == 'update')
{
	$id_req 		=$_GET['id_req'];
	$txtreqdate 	=fd($_POST['txtreqdate']);
	$cbosupp		=nb($_POST['cbosupp']);
	$txtremark		=nb($_POST['txtremark']);

		    $sql = "update bppb_req set bppbdate = '$txtreqdate', id_supplier = '$cbosupp', remark = '$txtremark' where bppbno  = '$id_req'";
			insert_log($sql,$user);

	$JmlArray 		= $_POST['qtyreq'];
	$Id_itemArray 	= $_POST['id_cek'];
	foreach ($JmlArray as $key => $value)
	{ 	
			$txtid_item = $Id_itemArray[$key];
			$txtqty	 	= $JmlArray[$key];

		    $sql_det = "update bppb_req set qty = '$txtqty' where bppbno  = '$id_req' and id_item = '$txtid_item'";
			insert_log($sql_det,$user);

			{$_SESSION['msg']="Data Berhasil Disimpan, Nomor Req : ".$id_req;}
			echo "<script>window.location.href='../forms/?mod=det_bppb_req&mode=Bahan_Baku&id=$id_req';</script>";
		
		}	  	
			
}




?>