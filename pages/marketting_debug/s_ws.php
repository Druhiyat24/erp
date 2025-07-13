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
{	$cri2="JO/".date('my',strtotime($jo_date));
	$jo_no=urutkan_inq("JO-".date('Y',strtotime($jo_date)),$cri2); 
	$sql = "insert into jo (jo_no,jo_date,username) 
		values ('$jo_no','".fd($jo_date)."','$user')";
	insert_log($sql,$user);
	$id_jo=flookup("id","jo","jo_no='$jo_no'");
	$ItemArray = $_POST['itemchk'];
	$PackArray = $_POST['list_pack'];
	$RatArray = $_POST['list_rat'];
	foreach ($ItemArray as $key => $value) 
	{	$chk=$value;
		$id_item=$key;
		if ($chk=="on")
		{	$id_so=$key;
			$sql = "insert into jo_det (id_jo,id_so) 
				values ('$id_jo','$id_so')";
			insert_log($sql,$user);
			$cripack = explode("X",$PackArray[$key]);
			foreach($cripack as $packpil)
			{	$cripack2 = explode("|",$packpil);
				$id_pack = $cripack2[0];
				$sql="insert into so_pack (id_so,id_pack) values ('$id_so','$id_pack')";
				insert_log($sql,$user);
			}
			$crirat = explode("|",$RatArray[$key]);
			foreach($crirat as $ratpil)
			{	$crirat2 = explode("=",$ratpil);
				$size = $crirat2[1];
				$rat = $crirat2[0];
				$sql="insert into so_ratio (id_so,size,ratio) values ('$id_so','$size','$rat')";
				insert_log($sql,$user);
			}

		}
	}	
	$_SESSION['msg'] = "Data Berhasil Disimpan. Nomor JO : ".$jo_no;
	echo "<script>window.location.href='../marketting/?mod=12L';</script>";
}
?>