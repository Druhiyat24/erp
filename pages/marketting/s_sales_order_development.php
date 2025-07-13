<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode="";
if (isset($_GET['id'])) {$id_so = $_GET['id']; } else {$id_so = "";}
if (isset($_GET['pro'])) {$pro = $_GET['pro']; } else {$pro = "";}

$txtid_cost = nb($_POST['txtid_cost']);
$txtbuyerno = nb($_POST['txtbuyerno']);
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
  $path = "upload_files/costing/".$nama_file;
  move_uploaded_file($tmp_file, $path);
}
else
{ $nama_file=""; }
if (($txtso_no=="" and $id_so=="") or ($txtso_no=="" and $id_so!="" and $pro=="Copy"))
{	$date=fd($txtso_date);
	$cri2="SODEV/".date('my',strtotime($date));
	$txtso_no=urutkan_inq("SODEV-".date('Y',strtotime($date)),$cri2); 
}
#$cek = flookup("count(*)","so","id_cost='$txtid_cost'");
$cek = 0; # 1 Act Costing Bisa Beberapa SO
if (($cek=="0" and $id_so=="") or ($cek=="0" and $id_so!="" and $pro=="Copy"))
{	$sql = "insert into so_dev (id_cost,buyerno,so_no,so_date,qty,unit,curr,fob,nm_file,username,tax,id_season)
		values ('$txtid_cost','$txtbuyerno','$txtso_no','$txtso_date','$txtqty','$txtunit','$txtcurr','$txtfob',
		'$nama_file','$user','$txttax','$txtseason')";
	insert_log($sql,$user);
	if ($id_so!="" and $pro=="Copy")
	{	$id_so_new=flookup("id","so_dev","so_no='$txtso_no'");
		$sql="insert into so_det_dev (id_so,dest,color,size,qty,unit,sku,barcode,notes) 
			select '$id_so_new',dest,color,size,qty,unit,sku,barcode,notes 
			from so_det_dev where id_so='$id_so'";
		insert_log($sql,$user);
	}
	$_SESSION['msg'] = 'Data Berhasil Disimpan';
	$id=flookup("id","so_dev","so_no='$txtso_no'");
	echo "<script>
		 window.location.href='../marketting/?mod=$mod&id=$id';
	</script>";
}
else
{	$cek=flookup("count(*)","jo_det","id_so='$id_so'");
	$dateskrg=date('Y-m-d');
	$cek2=flookup("so_no","unlock_so","id_so='$id_so' and DATE_ADD(unlock_date, INTERVAL 2 DAY)>'$dateskrg'");
	if ($cek!="0" and $cek2=="")
	{	$_SESSION['msg'] = 'XData Tidak Bisa Dirubah Karena Sudah Dibuat Worksheet';	}
	else
	{	$sql = "update so_dev set buyerno='$txtbuyerno',
			qty='$txtqty',unit='$txtunit',curr='$txtcurr',fob='$txtfob',username='$user',
			tax='$txttax',id_season='$txtseason'
			where id='$id_so'";
		insert_log($sql,$user);
		$_SESSION['msg'] = 'Data Berhasil Dirubah';
	}
	echo "<script>
		 window.location.href='../marketting/?mod=12vSO';
	</script>";
}
?>