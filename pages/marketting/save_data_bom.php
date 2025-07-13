<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode=$_GET['mode'];

$cbocust = nb($_POST['cbocust']);
$cboseason = nb($_POST['cboseason']);
$txtstyle = nb($_POST['txtstyle']);
$txtstyledesc = nb($_POST['txtstyledesc']);
$txtjcolor = nb($_POST['txtjcolor']);
$txtjsize = nb($_POST['txtjsize']);

$cek=flookup("styleno","bom_h","id_cust='$cbocust' and 
	id_season='$cboseason' and styleno='$txtstyle'");
if (!isset($_POST['jml_col']))
{	$_SESSION['msg'] = "XTidak Ada Data";
	echo "<script>window.location.href='index.php?mod=$mod';</script>"; 
}
else if ($cek!="")
{	$_SESSION['msg'] = "XData Sudah Ada";
	echo "<script>window.location.href='index.php?mod=$mod';</script>"; 
}
else
{	$sql="insert into bom_h (id_cust,id_season,styleno,itemname,
		jml_color,jml_size) values ('$cbocust','$cboseason',
		'$txtstyle','$txtstyledesc','$txtjcolor','$txtjsize')";
	insert_log($sql,$user);
	$id_h=flookup("id","bom_h","id_cust='$cbocust' and 
		id_season='$cboseason' and styleno='$txtstyle'");
	$Col_Arr = $_POST['jml_col'];
	$Sz_Arr = $_POST['jml_sz'];
	foreach ($Col_Arr as $key => $value) 
	{	if ($value!="")
		{	$color = nb($Col_Arr[$key]);
	    foreach ($Sz_Arr as $keysz => $valuesz)
	    {	if ($valuesz!="")
	  		{	$sz = nb($Sz_Arr[$keysz]);
	  			$sql="insert into bom_d (id_h,color_no,color_name,
	  				size_no,size_name) values ('$id_h','$key','$color',
	  				'$keysz','$sz')";
	  			insert_log($sql,$user);	    	
	    	}
	  	}
	  }
	}
	$_SESSION['msg'] = "Data Berhasil Disimpan";
	echo "<script>window.location.href='index.php?mod=$mod&mode=$mode';</script>";
}
?>