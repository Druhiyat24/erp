<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../"); }
if ($user=="") { header("location:../../"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode="";

$jenis=$_POST['txtJItem'];
$id_supplier= $_POST['txtid_supplier'];
$id_terms=$_POST['txtid_terms'];
$podate=fd($_POST['txtpodate']);
$etddate=fd($_POST['txtetddate']);
$etadate=fd($_POST['txtetadate']);
$expdate=fd($_POST['txtexpdate']);
$notes=nb($_POST['txtnotes']);
$pono=$_POST['txtpono'];
$curr=$_POST['txtcurr'];
$tax=$_POST['txtppn'];
if(isset($_POST['txtpph'])) {$pph=$_POST['txtpph'];} else {$pph="0";}

if($jenis!="N" and $pono=="")
{	$joselect="";
	foreach ($_POST['txtJOItem'] as $names)
	{	if($joselect=="")
		{	$joselect="'".$names."'"; }
		else
		{	$joselect=$joselect.",'".$names."'"; } 
	}
	$jmlws=flookup("count(distinct id_so)","jo_det_dev","id_jo in ($joselect)");
}
else
{
	$jmlws=0;
	$joselect="";
}
$cek="";
$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany "));
$nm_company=$rscomp["company"];
$logo_company=$rscomp["logo_company"];
if($nm_company=="PT. Cheong Woon Indonesia")
{	$cri2="CWIDEV".date('dmY',strtotime($podate));
	$cri3="PODEV/".date('Y',strtotime($podate));
}
else if($logo_company=="S" and $jenis!="N" and $pono=="")
{	if($jmlws=="1")
	{	$nows=flookup("kpno","jo_det_dev jod inner join so_dev so on jod.id_so=so.id 
			inner join act_development ac on so.id_cost=ac.id ","id_jo in ($joselect)");
		$cri2=$nows; 
	}
	else
	{	$nmbuyer=flookup("distinct supplier_code","jo_det_dev jod inner join so_dev so on jod.id_so=so.id 
			inner join act_development ac on so.id_cost=ac.id 
			inner join mastersupplier ms on ac.id_buyer=ms.id_supplier","id_jo in ($joselect)");
		$cri2="CDEV/".$nmbuyer; 
	}
	$cri3="PODEV/".date('Y',strtotime($podate));
}
else
{	$cri2="PODEV/".date('my',strtotime($podate));
	if($logo_company=="S")
	{$cri3="NPODEV/".date('Y',strtotime($podate));}
	else
	{$cri3="PODEV/".date('Y',strtotime($podate));}
}
if ($pono=="" or $mod=="x3ea") 
{	if($pono=="") { $pono=urutkan_inq($cri3,$cri2); }
	#$tax=flookup("if(pkp='PKP','10','0')","mastersupplier","id_supplier='$id_supplier'");
	$sql="insert into po_header_dev (username,pono,podate,etd,eta,expected_date,id_supplier,id_terms,notes,tax,pph,jenis)
		values ('$user','$pono','$podate','$etddate','$etadate','$expdate','$id_supplier','$id_terms',
		'$notes','$tax','$pph','$jenis')";
	insert_log($sql,$user);
	$id_po=flookup("id","po_header_dev","pono='$pono'");
	# PO DETAIL
	$ItemArray = $_POST['itemchk'];
	$ItemBBArr = $_POST['itembb'];
	$IdJOArr = $_POST['idjo'];
	$UnitArr = $_POST['itemunit'];
	$QtyArr = $_POST['itemqty'];
	$PriceArr = $_POST['itemprice'];
	foreach ($ItemArray as $key => $value) 
	{	if ($value=="on")
		{	$id_gen = $ItemBBArr[$key];
			$id_jo = $IdJOArr[$key];
			$qty_po=$QtyArr[$key];
			$curr_po=$curr;
			$unit_po=$UnitArr[$key];
			$price_po=$PriceArr[$key];
			$sql="insert into po_item_dev (id_po,id_jo,id_gen,qty,unit,curr,price)
				values ('$id_po','$id_jo','$id_gen','$qty_po',
				'$unit_po','$curr_po','$price_po')";
			insert_log($sql,$user);
		}
	}	
	$_SESSION['msg'] = "Data Berhasil Disimpan";
}
else
{	$cekbpb=flookup("bpbno","bpb","pono='$pono'");
	if($cekbpb=="")
	{	$id_po=flookup("id","po_header_dev","pono='$pono'");
		$sql="update po_header_dev set tax='$tax',pph='$pph',notes='$notes',id_terms='$id_terms',etd='$etddate',eta='$etadate',
			expected_date='$expdate' 
			where id='$id_po'";
		insert_log($sql,$user);
		$sql="update po_item_dev set curr='$curr' where id_po='$id_po'";
		insert_log($sql,$user);
		$_SESSION['msg'] = "Data Berhasil Dirubah";
	}
	else
	{	$_SESSION['msg'] = "XData Tidak Bisa Dirubah, Karena Sudah Ada BPB : ".$cekbpb;	}
}
if ($mod=="x9")
{	
	echo "<script>window.location.href='../pur/?mod=x9L';</script>";	
}
else
{	
	echo "<script>window.location.href='../pur/?mod=x3L';</script>";	
}
?>