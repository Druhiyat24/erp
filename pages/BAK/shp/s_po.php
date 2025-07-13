<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode="";

$id_supplier=$_POST['txtid_supplier'];
$id_terms=$_POST['txtid_terms'];
$podate=fd($_POST['txtpodate']);
$etddate=fd($_POST['txtetddate']);
$etadate=fd($_POST['txtetadate']);
$expdate=fd($_POST['txtexpdate']);
$notes=nb($_POST['txtnotes']);
$pono=$_POST['txtpono'];
$id_jo=$_POST['txtJOItem'];
$curr=$_POST['txtcurr'];

$cek="";
$cri2="PO/".date('my',strtotime($podate));
$cri3="PO/".date('Y',strtotime($podate));
if ($pono=="") 
{	$pono=urutkan_inq($cri3,$cri2);
	$sql="insert into po_header (pono,podate,etd,eta,expected_date,id_supplier,id_terms,notes)
		values ('$pono','$podate','$etddate','$etadate','$expdate','$id_supplier','$id_terms','$notes')";
	insert_log($sql,$user);
	$id_po=flookup("id","po_header","pono='$pono'");
} 
$ItemArray = $_POST['itemchk'];
$ItemBBArr = $_POST['itembb'];
$UnitArr = $_POST['itemunit'];
$QtyArr = $_POST['itemqty'];
$PriceArr = $_POST['itemprice'];
foreach ($ItemArray as $key => $value) 
{	if ($value=="on")
	{	$id_gen=$ItemBBArr[$key];
		$qty_po=$QtyArr[$key];
		$curr_po=$curr;
		$unit_po=$UnitArr[$key];
		$price_po=$PriceArr[$key];
		$sql="insert into po_item (id_po,id_jo,id_gen,qty,unit,curr,price)
			values ('$id_po','$id_jo','$id_gen','$qty_po',
			'$unit_po','$curr_po','$price_po')";
		insert_log($sql,$user);
	}
}	
$_SESSION['msg'] = "Data Berhasil Disimpan";
echo "<script>window.location.href='../pur/?mod=$mod&id=$id_po';</script>";
?>