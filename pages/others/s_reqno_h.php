<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id=$_GET['id'];} else {$id="";}

$txtreqno = nb($_POST['txtreqno']);
$txtreqdate = fd($_POST['txtreqdate']);
$txtjenis = $_POST['txtjenis'];
$txtnotes = nb($_POST['txtnotes']);
$txtapp_by = $_POST['txtapp_by'];
if(isset($_POST['txtapp_by2'])) {$txtapp_by2 = $_POST['txtapp_by2'];} else {$txtapp_by2 = "";}
$dateinput = date("Y-m-d H:i:s");
if ($txtreqno=="")
{	
	$kode_dept=flookup("kode_mkt","userpassword","username='$user'");
	$date=fd($txtreqdate);
	$cri2="GEN/".$kode_dept."/".date('my',strtotime($date));
	$txtreqno=urutkan_inq("GEN/".$kode_dept.date('Y',strtotime($date)),$cri2);
}
$cek = flookup("count(*)","reqnon_header","reqno='$txtreqno'");
if ($cek=="0")
{	
	$sql = "insert into reqnon_header (jenis_po,reqno,reqdate,notes,username,dateinput,app_by,app_by2)
		values ('$txtjenis','$txtreqno','$txtreqdate','$txtnotes','$user','$dateinput','$txtapp_by','$txtapp_by2')";
	insert_log($sql,$user);
	$id = flookup("id","reqnon_header","reqno='$txtreqno'");
	$_SESSION['msg'] = 'Data Berhasil Disimpan';
	echo "<script>window.location.href='../others/?mod=1&id=$id';</script>";
}
else
{	
	$sql="update reqnon_header set notes='$txtnotes',jenis_po='$txtjenis',app_by='$txtapp_by',app_by2='$txtapp_by2' where reqno='$txtreqno'";
	insert_log($sql,$user);
	$_SESSION['msg'] = 'Data Berhasil Dirubah';
	echo "<script>window.location.href='../others/?mod=1L';</script>";
}
?>