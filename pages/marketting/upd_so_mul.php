<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
$id_so=$_GET['id'];
$mode="";
$dateskrg=date('d M Y');

$deldate_ar=$_POST['deldatear'];
$dest_ar=$_POST['destar'];
$color_ar=$_POST['colorar'];
$size_ar=$_POST['sizear'];
$qty_ar=$_POST['qtyar'];
$qtyadd_ar=$_POST['qtyaddar'];
$unit_ar=$_POST['unitar'];
$price_ar=$_POST['pricear'];
$sku_ar=$_POST['skuar'];
$barcode_ar=$_POST['barcodear'];
$notes_ar=$_POST['notesar'];
$reff_no_ar=$_POST['reff_no'];
$styleno_prod_ar=$_POST['styleno_prod'];
foreach ($deldate_ar as $key => $value) 
{	$id_so_det = $key;
	$cek=flookup("count(*)","jo_det","id_so='$id_so'");
	$cekJO=flookup("jo_no","jo_det a inner join jo s on a.id_jo=s.id","id_so='$id_so' and a.cancel='N' ");
	$cek2=flookup("so_no","unlock_so","id_so='$id_so' and DATE_ADD(unlock_date, INTERVAL 2 DAY)>'$dateskrg'");
	if ($cek!="0" and $cek2=="")
	{	$_SESSION['msg'] = 'XData Tidak Bisa Diubah Karena Sudah Dibuat Worksheet : '.$cekJO;	
		echo "<script>window.location.href='../marketting/?mod=$mod&id=$id_so';</script>";
		exit;
	}
	else
	{	$sql = "update so_det set deldate_det='$deldate_ar[$key]',dest='$dest_ar[$key]',color='$color_ar[$key]'
			,size='$size_ar[$key]',qty='$qty_ar[$key]',qty_add='$qtyadd_ar[$key]',unit='$unit_ar[$key]'
			,price='$price_ar[$key]',sku='$sku_ar[$key]',barcode='$barcode_ar[$key]'
			,notes='$notes_ar[$key]',reff_no='$reff_no_ar[$key]',styleno_prod='$styleno_prod_ar[$key]' where id_so='$id_so' 
			and id='$id_so_det'";
		insert_log($sql,$user);
		$_SESSION['msg'] = 'Data Berhasil Diubah';
	}
}
echo "<script>window.location.href='../marketting/?mod=$mod&id=$id_so';</script>";
?>