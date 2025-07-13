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
	$txtbpbdate 	=fd($_POST['txtbpbdate']);
	$txtjns_in		=nb($_POST['txtjns_in']);
	$status_kb	    =nb($_POST['txtstatus_kb']);
	$txtbcno        =nb($_POST['txtbcno']);
	$txtbcdate      =fd($_POST['txtbcdate']);
	$txtinvno		=nb($_POST['txtinvno']);
	$txtremark		=nb($_POST['txtremark']);
	$dateinput		=date('Y-m-d H:i:s');

	$txtbpbno = urutkan("Bahan_Baku",$cbomat); 

	$cbomat2=flookup("nama_pilihan","masterpilihan","kode_pilihan='MAP_MAT_".$cbomat."'");
	$date=fd($_POST['txtbpbdate']);
	$cri2=$cbomat2."/IN/".date('my',strtotime($date));
	$txtbpbno2=urutkan_inq($cbomat2."-IN-".date('Y',strtotime($date)),$cri2);
	
		$JmlArray 		= $_POST['qtybpb'];
		$UnitArray 		= $_POST['unitbpb'];
		$Id_itemArray 	= $_POST['id_item'];
		$Id_joArray 	= $_POST['id_jo'];
		$KpnoArray 		= $_POST['kpno'];

		foreach ($JmlArray as $key => $value) 
		{	if ($value!="")
			{
		    $txtid_item = $Id_itemArray[$key];
			$txtqty	 	= $JmlArray[$key];
			$txtunit	= $UnitArray[$key];
			$id_jo	 	= $Id_joArray[$key];
			$txtkpno	= $KpnoArray[$key];


		    {	$sql = "insert into bpb (id_item,qty,unit,curr,price,remark,id_supplier,
				invno,bcno,bcdate,bpbno,bpbno_int,bpbdate,jenis_dok,username,use_kite,
				kpno,status_retur,id_jo,id_sec,jenis_trans)
				values ('$txtid_item','$txtqty','$txtunit','IDR','0','$txtremark',
				'$txtid_supplier','$txtinvno','$txtbcno','$txtbcdate',
				'$txtbpbno','$txtbpbno2',
				'$txtbpbdate','$status_kb','$user','1','$txtkpno',
				'N','$id_jo','0','$txtjns_in')";
			insert_log($sql,$user);
			calc_stock($cbomat,$txtid_item);
			{$_SESSION['msg']="Data Berhasil Disimpan, Nomor BPB : ".$txtbpbno2;}
			echo "<script>window.location.href='../forms/?mod=bpb_global_item&mode=Bahan_Baku&bpbno=$txtbpbno';</script>";
		  	}
		  }
		}	
}

if ($mod == 'simpan_lokasi')
{
	$bpbno_lok	 		=nb($_POST['bpbno_lok']);
	$id_item_lok 		=nb($_POST['id_item_lok']);
	$id_jo_lok  		=nb($_POST['id_jo_lok']);
	$dateinput			=date('Y-m-d H:i:s');
	$unit 				=nb($_POST['txtunitdet']);

	$cekid=flookup("id","bpb_roll_h","bpbno='$bpbno_lok' and id_item='$id_item_lok' and id_jo='$id_jo_lok'");
	if ($cekid == "")
	{
		$sql = "insert into bpb_roll_h (bpbno,id_jo,id_item,username,dateinput)
		values ('$bpbno_lok','$id_jo_lok','$id_item_lok','$user','$dateinput')";
		insert_log($sql,$user);	

		$cek_header=flookup("id","bpb_roll_h","bpbno='$bpbno_lok' and id_item='$id_item_lok' and id_jo='$id_jo_lok'");

		$JmlArray 		= $_POST['jml_roll'];
		$LotArray 		= $_POST['lot'];
		$NoArray 		= $_POST['no_roll'];
		$Rak_rollArray 	= $_POST['rak_rollk'];

		foreach ($JmlArray as $key => $value) 
		{	
			if ($value!="")
			{				
			
			$txtno_roll = $NoArray[$key];	
		    $txtlot		= $LotArray[$key];
			$txtjml_roll= $JmlArray[$key];
			$txtrak_roll= $Rak_rollArray[$key];

		    	$sql = "insert into bpb_roll (id_h,roll_no,lot_no,roll_qty,unit,id_rak_loc,roll_foc,roll_qty_act,roll_qty_used)
				values ('$cek_header','$txtno_roll','$txtlot','$txtjml_roll','$unit','$txtrak_roll','0','0','0')";
			insert_log($sql,$user);
			$_SESSION['msg']="Lokasi Berhasil Disimpan, Nomor BPB : ".$bpbno_lok;
			echo "<script>window.location.href='../forms/?mod=bpb_global_item&mode=Bahan_Baku&bpbno=$bpbno_lok';</script>";
		  	}
		  
		}	

	}
	else if ($cekid != "")
	{
		$cek_header=flookup("id","bpb_roll_h","bpbno='$bpbno_lok' and id_item='$id_item_lok' and id_jo='$id_jo_lok'");

		$JmlArray 		= $_POST['jml_roll'];
		$LotArray 		= $_POST['lot'];
		$NoArray 		= $_POST['no_roll'];
		$Rak_rollArray 	= $_POST['rak_rollk'];

		foreach ($JmlArray as $key => $value) 
		{	
			if ($value!="")
			{				
			
			$txtno_roll = $NoArray[$key];	
		    $txtlot		= $LotArray[$key];
			$txtjml_roll= $JmlArray[$key];
			$txtrak_roll= $Rak_rollArray[$key];

		    	$sql = "insert into bpb_roll (id_h,roll_no,lot_no,roll_qty,unit,id_rak_loc,roll_foc,roll_qty_act,roll_qty_used)
				values ('$cek_header','$txtno_roll','$txtlot','$txtjml_roll','$unit','$txtrak_roll','0','0','0')";
			insert_log($sql,$user);
			$_SESSION['msg']="Lokasi Berhasil Disimpan, Nomor BPB : ".$bpbno_lok;
			echo "<script>window.location.href='../forms/?mod=bpb_global_item&mode=Bahan_Baku&bpbno=$bpbno_lok';</script>";
		  	}
		  
		}			
	}
	

}


if ($mod == 'update')
{
	$bpbno 			=$_GET['bpbno'];
	$txtbpbdate 	=fd($_POST['txtbpbdate']);
	$txtjns_in		=nb($_POST['txtjns_in']);
	$status_kb	    =nb($_POST['txtstatus_kb']);
	$txtinvno		=nb($_POST['txtinvno']);
	$txtremark		=nb($_POST['txtremark']);

	$bpbno_int=flookup("bpbno_int","bpb","bpbno='$bpbno' limit 1");

		    {	$sql = "update bpb set bpbdate = '$txtbpbdate', invno = '$txtinvno', jenis_trans = '$txtjns_in', jenis_dok = '$status_kb', remark = '$txtremark' where bpbno  = '$bpbno'";
			insert_log($sql,$user);
			{$_SESSION['msg']="Data Berhasil Disimpan, Nomor BPB : ".$bpbno_int;}
			echo "<script>window.location.href='../forms/?mod=bpb_global_item&mode=Bahan_Baku&bpbno=$bpbno';</script>";
		  	}
			
}




?>