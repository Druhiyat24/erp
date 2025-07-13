<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode="";

$crinya = explode("|",$_POST['cbopo']);
$ponya = $crinya[0];
$BPBArr = $_POST['idbpb'];
$IdPOArr = $_POST['id_jo'];
$QtyOvArr = $_POST['qtyover'];
$QtyArr = $_POST['qtyfoc'];
$QtyRetArr = $_POST['qtyret'];
$QtyAddArr = $_POST['qtyadd'];
foreach ($QtyArr as $key => $value)
{	$crikey=explode(":",$key);
	$bpbno = $BPBArr[$key];
	$id_po_item = $IdPOArr[$key];
	$id_item = flookup("id_item","bpb","bpbno='$bpbno' and id_po_item='$id_po_item'");
	$qty_over = $QtyOvArr[$key];
	if($QtyArr[$key]>0)
	{
		$qty_foc = $QtyArr[$key];	
	}
	else
	{
		$qty_foc = 0;	
	}
	if($QtyRetArr[$key]>0)
	{
		$qty_ret = $QtyRetArr[$key];	
	}
	else
	{
		$qty_ret = 0;	
	}
	if($QtyAddArr[$key]>0)
	{
		$qty_add = $QtyAddArr[$key];	
	}
	else
	{
		$qty_add = 0;	
	}
	$sql="insert into bpb_over (bpbno,id_po_item,qty_foc,qty_ret,qty_add) 
		values ('$bpbno','$id_po_item','$qty_foc','$qty_ret','$qty_add')";
	insert_log($sql,$user);
	$sql="update bpb set qty_over=0 where bpbno='$bpbno' and qty_over>0 and id_item='$id_item'";
	insert_log($sql,$user);
	$sql="update bpb set qty_over='$qty_over' where bpbno='$bpbno' and id_po_item='$id_po_item'";
	insert_log($sql,$user);
	if($qty_ret>0)
	{
		$sql="update bpb set qty=qty+$qty_ret where bpbno='$bpbno' and id_po_item='$id_po_item'";
		insert_log($sql,$user);
	}
	if($qty_foc>0)
	{
		$sql="update bpb set qty=qty+$qty_foc where bpbno='$bpbno' and id_po_item='$id_po_item'";
		insert_log($sql,$user);
	}
}
$sql="update po_header set po_over='W' where pono='$ponya'";
insert_log($sql,$user);
$_SESSION['msg'] = 'Data Berhasil Disimpan';
echo "<script>window.location.href='../pur/?mod=15L';</script>";
?>