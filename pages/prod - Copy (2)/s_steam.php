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
$jam="";
$line="";
$dateinput = date("Y-m-d H:i:s");

$QtyArr = $_POST['itemqty'];
foreach ($QtyArr as $key => $value) 
{	if ($value>0)
	{	$id_so_det=$key;
		$qty=$QtyArr[$key];
		$rpr=0;
		$defect=0;
		$sql="insert into steam_out (id_so_det,dateoutput,qty,notes,username,dateinput)
			values ('$id_so_det','$dateoutput','$qty','$notes','$user','$dateinput')";
		insert_log($sql,$user);
	}
}	
$_SESSION['msg'] = "Data Berhasil Disimpan";
echo "<script>window.location.href='../prod/?mod=11L';</script>";
?>