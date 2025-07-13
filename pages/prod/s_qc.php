<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode="";

$id_so_det=$_POST['txtJOItem'];
$dateoutput=fd($_POST['txtdateout']);
$notes=nb($_POST['txtnotes']);
$jam=nb($_POST['txtjam']);
$line=nb($_POST['txtLine']);
$dateinput = date("Y-m-d H:i:s");

$QtyArr = $_POST['itemqty'];
$RPRArr = $_POST['itemrpr'];
$DefArr = $_POST['defect'];
foreach ($QtyArr as $key => $value) 
{	if ($value>0)
	{	$id_so_det=$key;
		$qty=$QtyArr[$key];
		$rpr=$RPRArr[$key];
		if (isset($DefArr[$key])) {$defect=$DefArr[$key];} else {$defect=0;}
		$sql="insert into qc_out (id_so_det,dateoutput,qty,notes,username,dateinput,id_line,jam,rpr,id_defect)
			values ('$id_so_det','$dateoutput','$qty','$notes','$user','$dateinput','$line','$jam','$rpr','$defect')";
		insert_log($sql,$user);
	}
}	
$_SESSION['msg'] = "Data Berhasil Disimpan";
echo "<script>window.location.href='../prod/?mod=5L';</script>";
?>