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
$id_jo = nb($_GET['id']);
if ($txtJItem=="M") {$rulebom = nb($_POST['txtroll']);} else {$rulebom="";}
$posno1=flookup("count(distinct posno,id_item)","bom_dev_jo_item","id_jo='$id_jo'");
$posno=sprintf("%'.03d", $posno1+1);

if (isset($_POST['txtdest'])) {$dest=$_POST['txtdest'];} else {$dest="";}
$cek="";
if (!isset($_POST['jml_roll']) AND $txtJItem=="M")
{	$_SESSION['msg'] = "XTidak Ada Data";
	echo "<script>window.location.href='../marketting/?mod=22DEV';</script>"; 
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
		    if ($dest!="") {$sql_dest=" and s.dest='$dest'";} else {$sql_dest="";}
		    if (nb($rulebom)==nb("Per Color All Size"))
		    {	$arrrule=explode(" | ",$txtcri);
		  		$sql_wh=" and s.color='$arrrule[0]' $sql_dest ";
				}
				else if (nb($rulebom)==nb("All Color Range Size"))
		    {	$arrrule=explode(" | ",$txtcri);
		  		$sql_wh=" and s.size='$arrrule[1]' $sql_dest ";
				}
				else if (nb($rulebom)==nb("Per Color Range Size"))
		    {	$arrrule=explode(" | ",$txtcri);
		  		$sql_wh=" and s.color='$arrrule[0]' and s.size='$arrrule[1]' $sql_dest ";
				}
				else
				{	$sql_wh=" $sql_dest "; }
		    $sql="select s.* 
					from jo_det_dev a inner join so_det_dev s 
					on a.id_so=s.id_so where 
					id_jo='$id_jo' and s.cancel='N' $sql_wh ";
		    $rs=mysql_query($sql);
		    while($data=mysql_fetch_array($rs))
		    {	$sql="insert into bom_dev_jo_item (id_jo,id_so_det,dest,id_item,cons,unit,username,dateinput,status,rule_bom,posno,cancel)
		    		values ('$id_jo','$data[id]','$dest','$txtitem','$cons','$unit','$user','$dateinput','$txtJItem','$rulebom','$posno','N')";
		    	insert_log($sql,$user);
		  	}
		  }
		}	
	}
	else
	{	
	}
	$_SESSION['msg'] = "Data Berhasil Disimpan";
	echo "<script>window.location.href='../marketting/?mod=22DEV&id=$id_jo';</script>";
}
?>