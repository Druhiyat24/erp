<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['idd'])) {$id=$_GET['idd'];} else {$id="";}
if (isset($_GET['id'])) {$id_reqno=$_GET['id'];} else {$id_reqno="";}
#if ($id_reqno=="") 
#{ echo "<script>alert('Request Tidak Ditemukan'); window.location.href='../others/?mod=1';</script>"; }

$txtid_item = nb($_POST['txtid_item']);
if (isset($_POST['txtid_supplier'])) {$txtid_supplier=$_POST['txtid_supplier'];} else {$txtid_supplier="";}
$txtqty = $_POST['txtqty'];
$txtunit = nb($_POST['txtunit']);
if(isset($_POST['txtcurr'])) {$txtcurr = nb($_POST['txtcurr']);} else {$txtcurr = "";}
if(isset($_POST['txtprice'])) {$txtprice = nb($_POST['txtprice']);} else {$txtprice = "0";}
$cek = flookup("count(*)","reqnon_item","id_reqno='$id_reqno' and id_item='$txtid_item'");
if ($cek=="0" and $id=="")
{	$sql = "insert into reqnon_item (id_reqno,id_item,id_supplier,qty,unit,curr,price)
		values ('$id_reqno','$txtid_item','$txtid_supplier','$txtqty','$txtunit','$txtcurr','$txtprice')";
	insert_log($sql,$user);
	#echo $sql;
	$_SESSION['msg'] = 'Data Berhasil Disimpan';
	echo "<script>window.location.href='../others/?mod=1&id=$id_reqno';</script>";
}
else if ($cek!="0" and $id=="")
{	$_SESSION['msg'] = 'XData Sudah Ada';
	echo "<script>window.location.href='../others/?mod=1&id=$id_reqno';</script>";
}
else if ($cek!="0" and $id!="")
{	$id_req=flookup("id_reqno","reqnon_item","id='$id'");
	$id_gen=flookup("id_item","reqnon_item","id='$id'");
	$cekpo=flookup("a.id","po_item a inner join po_header s on a.id_po=s.id ","a.id_jo='$id_reqno' and a.id_gen='$id_gen' and s.jenis='N'");
	if($cekpo=="")
	{	$sql = "update reqnon_item set id_supplier='$txtid_supplier',
			qty='$txtqty',unit='$txtunit',curr='$txtcurr',price='$txtprice' 
			where id_reqno='$id_reqno' and id='$id'";
		insert_log($sql,$user);
		#echo $sql;
		$_SESSION['msg'] = 'Data Berhasil Dirubah';
	}
	else
	{
		$_SESSION['msg'] = 'XData Tidak Bisa Dirubah Karena Sudah Dibuat PO';
	}
	echo "<script>window.location.href='../others/?mod=1&id=$id_reqno';</script>";
}
?>