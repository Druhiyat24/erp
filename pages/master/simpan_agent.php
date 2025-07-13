<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
$id=$_GET['id'];

$sqlcekdata=("select * from master");


$id_buyer=nb($_POST['cbobuyer']);

$sqlbuyer=("select supplier from mastersupplier where id_supplier = '$id_buyer'");
$querybuyer = mysql_query($sqlbuyer);
$querybuyer1 = mysql_fetch_array($querybuyer);
$buyer = $querybuyer1['supplier'];

$id_agent=nb($_POST['cboagent']);

$sqlagent=("select supplier from mastersupplier where id_supplier = '$id_agent'");
$queryagent = mysql_query($sqlagent);
$queryagent1 = mysql_fetch_array($queryagent);
$agent = $queryagent1['supplier'];

	if(isset($_POST['aktif'])) { $aktif='Y'; } else { $aktif=""; }


$sqlcek=("select * from master_agent where id_buyer = '$id_buyer' and id_agent = '$id_agent' and aktif = '$aktif'");
$querycek = mysql_query($sqlcek);
$querycekbuyer = $sqlcek['id_buyer'];
$querycekagent = $sqlcek['id_agent'];
$cek = mysql_num_rows($querycek);

if ($cek>="1")
{ $_SESSION['msg'] = "XData Sudah Ada";
  echo "<script>window.location.href='index.php?mod=$mod';</script>";
}	
else 
{
	if ($querycekbuyer=="" && $querycekagent=="" or $id =="")
	{	
		$sql="insert into master_agent (id_buyer,buyer,id_agent,agent,aktif) 
	    values ('$id_buyer','$buyer','$id_agent','$agent','$aktif')";
	  insert_log($sql,$user);
	  $_SESSION['msg'] = "Data Berhasil Disimpan";
	}
	else
	{
		$sql="update master_agent set id_buyer = '$id_buyer', buyer = '$buyer',id_agent = '$id_agent', agent = '$agent',aktif = '$aktif' where id = '$id'";
	  insert_log($sql,$user);
	  $_SESSION['msg'] = "Data Berhasil Di ubah";		
	} 
	} 
	echo "<script>window.location.href='index.php?mod=$mod';</script>";

?>