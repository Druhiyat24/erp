<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode="";

$dateinput=date('Y-m-d H:i:s');
$txtJItem = nb($_POST['txtJItem']);
$id_contents = nb($_POST['txtItemCS']);
$cons = nb($_POST['txtcons']);
$unit = nb($_POST['txtunit']);
if (isset($_POST['txtpanel'])) {$id_panel = nb($_POST['txtpanel']);} else {$id_panel = "";}
$id_jo = nb($_GET['id']);
$rulebom = nb($_POST['txtroll']);
$txtnotes = nb($_POST['txtnotes']);
$posno1=flookup("count(distinct posno,id_item)","bom_jo_item","id_jo='$id_jo'");
$posno=sprintf("%'.03d", $posno1+1);
if (isset($_POST['txtdest'])) {$dest=$_POST['txtdest'];} else {$dest="";}
if ($dest!="")
{
	$dest="";
	foreach ($_POST['txtdest'] as $names)
	{
		if($dest=="")
		{
			$dest = "'".$names."'";
		}
		else
		{
			$dest = $dest.",'".$names."'";
		}
	};
}
if (isset($_POST['txtsku'])) {$sku=$_POST['txtsku'];} else {$sku="";}
if ($sku!="")
{
	$sku="";
	foreach ($_POST['txtsku'] as $names)
	{
		if($sku=="")
		{
			$sku = "'".$names."'";
		}
		else
		{
			$sku = $sku.",'".$names."'";
		}
	};
}
$cek="";
if (!isset($_POST['jml_roll']) AND $txtJItem=="M")
{	$_SESSION['msg'] = "XTidak Ada Data";
	echo "<script>window.location.href='../marketting/?mod=14&id=$id_jo';</script>"; 
}
else if ($cek!="")
{	$_SESSION['msg'] = "XData Sudah Ada";
	#echo "<script>window.location.href='index.php?mod=$mod';</script>"; 
}
else
{	if ($txtJItem=="M" or $txtJItem=="P")
	{	$JmlArray = $_POST['jml_roll'];
		$NoArray = $_POST['no_roll'];
		foreach ($JmlArray as $key => $value) 
		{	if ($value!="")
			{	$txtcri = $NoArray[$key];
		    $txtitem = $JmlArray[$key];
		    if ($dest!="") {$sql_dest=" and s.dest in ($dest) ";} else {$sql_dest="";}
		    if ($sku!="") {$sql_sku=" and s.sku in ($sku) ";} else {$sql_sku="";}
		    if (nb($rulebom)==nb("Per Color All Size"))
		    {	$arrrule=explode(" | ",$txtcri);
		  		$sql_wh=" and s.color='$arrrule[0]' $sql_dest $sql_sku ";
				}
				else if (nb($rulebom)==nb("All Color Range Size"))
		    {	$arrrule=explode(" | ",$txtcri);
		  		$sql_wh=" and s.size='$arrrule[1]' $sql_dest $sql_sku ";
				}
				else if (nb($rulebom)==nb("Per Color Range Size"))
		    {	$arrrule=explode(" | ",$txtcri);
		  		$sql_wh=" and s.color='$arrrule[0]' and s.size='$arrrule[1]' $sql_dest $sql_sku ";
				}
				else
				{	$sql_wh=" $sql_dest $sql_sku "; }
		    $sql="select s.* 
					from jo_det a inner join so_det s 
					on a.id_so=s.id_so where 
					id_jo='$id_jo' and s.cancel='N' $sql_wh ";
		    $rs=mysql_query($sql);
		    while($data=mysql_fetch_array($rs))
		    {	$sql="insert into bom_jo_item (id_jo,id_so_det,dest,id_item,cons,unit,id_panel,username,dateinput,status,rule_bom,posno,notes)
		    		values ('$id_jo','$data[id]','".str_replace("'","",$dest)."','$txtitem','$cons','$unit',
		    		'$id_panel','$user','$dateinput','$txtJItem','$rulebom','$posno','$txtnotes')";
		    	insert_log($sql,$user);
		  	}
		  }
		}	
	}
	else
	{	
	}
	$_SESSION['msg'] = "Data Berhasil Disimpan";
	echo "<script>window.location.href='../marketting/?mod=14&id=$id_jo';</script>";
}
?>