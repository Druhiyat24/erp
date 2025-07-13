<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];

$jo_date = date('Y-m-d');
if (!isset($_POST['itemchk']))
{	$_SESSION['msg'] = "XTidak Ada Data Yang Akan Disimpan";
	echo "<script>window.location.href='../marketting/?mod=12L';</script>"; 
}
else
{	$cri2="WSDEV/".date('my',strtotime($jo_date));
	$jo_no=urutkan_inq("WSDEV-".date('Y',strtotime($jo_date)),$cri2); 
	$sql = "insert into jo_dev (jo_no,jo_date,username) 
		values ('$jo_no','".fd($jo_date)."','$user')";
	insert_log($sql,$user);
	$id_jo=flookup("id","jo_dev","jo_no='$jo_no'");
	$ItemArray = $_POST['itemchk'];
	foreach ($ItemArray as $key => $value) 
	{	$chk=$value;
		$id_item=$key;
		if ($chk=="on")
		{	$id_so=$key;
			$sql = "insert into jo_det_dev (id_jo,id_so) 
				values ('$id_jo','$id_so')";
			insert_log($sql,$user);
		}
	}	
	$_SESSION['msg'] = "Data Berhasil Disimpan. Nomor JO : ".$jo_no;
	echo "<script>window.location.href='../marketting/?mod=23L';</script>";
}
?>