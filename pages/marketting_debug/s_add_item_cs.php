<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
$id_cs=$_GET['id'];
$mode="";
if (isset($_GET['idd'])) {$id_cs_mat=$_GET['idd'];} else {$id_cs_mat="";}

$txtid_item = nb($_POST['txtid_item']);
$txtprice = nb($_POST['txtprice']);
$txtcons = nb($_POST['txtcons']);
$txtunit = nb($_POST['txtunit']);
$txtallowance = nb($_POST['txtallowance']);
$txtmaterial_source = nb($_POST['txtmaterial_source']);
$cek = "0";
if ($cek=="0" and $id_cs_mat=="")
{	$sql = "insert into act_costing_mat (id_act_cost,id_item,price,cons,unit,allowance,material_source)
		values ('$id_cs','$txtid_item','$txtprice','$txtcons','$txtunit','$txtallowance','$txtmaterial_source')";
	insert_log($sql,$user);
	$_SESSION['msg'] = 'Data Berhasil Disimpan';
	echo "<script>
		 window.location.href='../marketting/?mod=$mod&id=$id_cs';
	</script>";
}
else
{	$sql = "update act_costing_mat set id_item='$txtid_item',
		price='$txtprice',cons='$txtcons',unit='$txtunit',
		allowance='$txtallowance',material_source='$txtmaterial_source'
		where id_act_cost='$id_cs' and id='$id_cs_mat'";
	insert_log($sql,$user);
	$_SESSION['msg'] = 'Data Berhasil Dirubah';
	echo "<script>
		 window.location.href='../marketting/?mod=$mod&id=$id_cs';
	</script>";
}
?>