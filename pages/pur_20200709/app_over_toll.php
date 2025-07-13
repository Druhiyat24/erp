<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];

$mod=$_GET['mod'];
$mode="";
$id_po_item=$_GET['id'];
$bpbno=$_GET['idd'];
$qty_foc=flookup("qty_foc","bpb_over","bpbno='$bpbno' and id_po_item='$id_po_item'");

$sql="update po_header a inner join po_item s on a.id=s.id_po set po_over='C' where s.id='$id_po_item'";
insert_log($sql,$user);
$sql="update bpb set qty_foc='$qty_foc' where bpbno='$bpbno' and id_po_item='$id_po_item'";
insert_log($sql,$user);

$_SESSION['msg'] = "Data Berhasil Disimpan";
echo "<script>window.location.href='../pur/?mod=$mod';</script>";
?>