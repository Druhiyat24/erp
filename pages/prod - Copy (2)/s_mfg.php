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
$dateinput = date("Y-m-d H:i:s");
$cmt=$_POST['txtSubkon'];
if (isset($_POST['txtNmSubkon'])) {$nmcmt=$_POST['txtNmSubkon'];} else {$nmcmt="";}
$id_pro=$_POST['txtPro'];

$QtyArr = $_POST['itemqty'];
foreach ($QtyArr as $key => $value) 
{	if ($value>0)
	{	$id_so_det=$key;
		$qty=$QtyArr[$key];
		$sql="insert into mfg_out (id_so_det,dateoutput,qty,notes,username,dateinput,cmt,id_supplier,id_mfg)
			values ('$id_so_det','$dateoutput','$qty','$notes','$user','$dateinput','$cmt','$nmcmt','$id_pro')";
		insert_log($sql,$user);
	}
}	
$_SESSION['msg'] = "Data Berhasil Disimpan";
echo "<script>window.location.href='../prod/?mod=3L';</script>";
?>