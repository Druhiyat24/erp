<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode="";
$id_so=$_GET['id'];
$txtdest = nb($_POST['txtdest']);
$txtbuyerno = nb($_POST['txtbuyerno']);
$txtcolor = nb($_POST['txtcolor']);
$txtsku = nb($_POST['txtsku']);
$txtnotes = nb($_POST['txtnotes']);
$txtunit = nb($_POST['txtunit']);
if (!isset($_POST['jml_roll']))
{	$_SESSION['msg'] = "XTidak Ada Data";
	echo "<script>window.location.href='index.php?mod=$mod';</script>"; 
}
else
{	$JmlArray = $_POST['jml_roll'];
	$NoArray = $_POST['no_roll'];
	$BarArr = $_POST['barcode'];
	foreach ($JmlArray as $key => $value) 
	{	if (is_numeric($value))
		{	$txtsize = nb($NoArray[$key]);
	    $txtqty = $JmlArray[$key];
	    $barnya = $BarArr[$key];
	    $cek = flookup("count(*)","so_det","id_so='$id_so' and 
				buyerno='$txtbuyerno' and dest='$txtdest' and color='$txtcolor' 
				and sku='$txtsku' and size='$txtsize'");
			if ($cek=="0")
			{	$sql = "insert into so_det (id_so,buyerno,dest,color,sku,notes,size,qty,unit,barcode)
					values ('$id_so','$txtbuyerno','$txtdest','$txtcolor','$txtsku','$txtnotes','$txtsize'
					,'$txtqty','$txtunit','$barnya')";
				insert_log($sql,$user);
			}
	  }
	}	
	$_SESSION['msg'] = "Data Berhasil Disimpan";
	echo "<script>window.location.href='../marketting/?mod=7&id=$id_so';</script>";
}
?>