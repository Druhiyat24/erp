<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode="";
if (isset($_GET['id'])) {$id_so = $_GET['id']; } else {$id_so = "";}

$txtid_cost = nb($_POST['txtid_cost']);
$txtso_no = nb($_POST['txtso_no']);
$txtso_date = fd($_POST['txtso_date']);
$txtqty = nb($_POST['txtqty']);
$txtseason = nb($_POST['txtseason']);
$txtunit = nb($_POST['txtunit']);
$txtcurr = nb($_POST['txtcurr']);
$txtfob = nb($_POST['txtfob']);
$txttax = nb($_POST['txttax']);
if (isset($_FILES['txtfile']))
{ $nama_file = $_FILES['txtfile']['name'];
  $tmp_file = $_FILES['txtfile']['tmp_name'];
  $path = "upload_files/so/".$nama_file;
  move_uploaded_file($tmp_file, $path);
}
else
{ $nama_file=""; }
if ($txtso_no=="" and $id_so=="")
{	$date=fd($txtso_date);
	$cri2="SO/".date('my',strtotime($date));
	$txtso_no=urutkan_inq("SO-2018",$cri2); 
}
$cek = flookup("count(*)","so","id_cost='$txtid_cost'");
if ($cek=="0")
{	$sql = "insert into so (id_cost,so_no,so_date,qty,unit,curr,fob,nm_file,username,tax,id_season)
		values ('$txtid_cost','$txtso_no','$txtso_date','$txtqty','$txtunit','$txtcurr','$txtfob',
		'$nama_file','$user','$txttax','$txtseason')";
	insert_log($sql,$user);
	$_SESSION['msg'] = 'Data Berhasil Disimpan';
	$id=flookup("id","so","so_no='$txtso_no'");
	echo "<script>
		 window.location.href='../marketting/?mod=$mod&id=$id';
	</script>";
}
else
{	$cek=flookup("count(*)","jo_det","id_so='$id_so'");
	if ($cek!="0")
	{	$_SESSION['msg'] = 'XData Tidak Bisa Dirubah Karena Sudah Dibuat Worksheet';	}
	else
	{	$sql = "update so set qty='$txtqty',unit='$txtunit',curr='$txtcurr',fob='$txtfob',username='$user',
			tax='$txttax',id_season='$txtseason'
			where id='$id_so'";
		insert_log($sql,$user);
		$_SESSION['msg'] = 'Data Berhasil Dirubah';
	}
	echo "<script>
		 window.location.href='../marketting/?mod=$mod';
	</script>";
}
?>