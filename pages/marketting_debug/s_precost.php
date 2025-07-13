<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode=$_GET['mode'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

$txtcurr = nb($_POST['txtcurr']);
$txtprecost_no = nb($_POST['txtprecost_no']);
$txtqty = unfn($_POST['txtqty']);
$txtunit = nb($_POST['txtunit']);
$txtdescription = nb($_POST['txtdescription']);
$txtfabric_cost = unfn($_POST['txtfabric_cost']);
$txtaccs_cost = unfn($_POST['txtaccs_cost']);
$txtmfg_cost = unfn($_POST['txtmfg_cost']);
$txtother_cost = unfn($_POST['txtother_cost']);
$txtid_inq = nb($_POST['txtid_inq']);
$txtprod_group = nb($_POST['txtprod_group']);
$txtid_product = nb($_POST['txtid_product']);
$txtprofit_persen = nb($_POST['txtprofit_persen']);
if ($txtprecost_no=="")
{	$date=date('Y-m-d');
	$cri2="PRECST/".date('my',strtotime($date));
	$txtprecost_no=urutkan_inq("PRE-2018",$cri2); 
}
$cek = flookup("count(*)","pre_costing","precost_no='$txtprecost_no'");
if ($cek=="0")
{	$sql = "insert into pre_costing (curr,precost_no,precost_date,qty,unit,description,fabric_cost,accs_cost,mfg_cost,other_cost,id_inq,id_product,profit_persen)
		values ('$txtcurr','$txtprecost_no','$date','$txtqty','$txtunit','$txtdescription','$txtfabric_cost','$txtaccs_cost','$txtmfg_cost','$txtother_cost','$txtid_inq','$txtid_product','$txtprofit_persen')";
	insert_log($sql,$user);
	$_SESSION['msg'] = 'Data Berhasil Disimpan';
	echo "<script>
		 window.location.href='../marketting/?mod=4';
	</script>";
}
else
{	$_SESSION['msg'] = 'XData Sudah Ada';
	echo "<script>
		 window.location.href='../marketting/?mod=4';
	</script>";
}
?>