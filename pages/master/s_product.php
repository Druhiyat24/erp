<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

$prod_group=nb($_POST['txtprod_group']);
$prod_item=nb($_POST['txtprod_item']);
$model=nb($_POST['txtmodel']);
$berat=nb($_POST['txtberat']);
$berat_kotor=nb($_POST['txtberat_kotor']);
$cek=flookup("product_group","masterproduct","product_group='$prod_group'
		and product_item='$prod_item'");
if (($cek!="" and $id_item=="") or ($cek!="" and $id_item!=""))
{ 
  $_SESSION['msg'] = "XData Sudah Ada";
}
else if ($cek=="" and $id_item!="")
{ $sql="update masterproduct set model='$model',berat='$berat',berat_kotor='$berat_kotor',product_group='$prod_group',
		product_item='$prod_item' where id='$id_item'";
	insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Dirubah";
}
else
{ $sql="insert into masterproduct (product_group,product_item,model,berat,berat_kotor) 
    values ('$prod_group','$prod_item','$model','$berat','$berat_kotor')";
  insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Disimpan";
}
echo "<script>window.location.href='../master/?mod=$mod';</script>";
?>