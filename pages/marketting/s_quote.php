<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

$txtquote_no = nb($_POST['txtquote_no']);
$txtquote_date = fd($_POST['txtquote_date']);
$txtid_buyer = nb($_POST['txtid_buyer']);
$txtid_bisnis = nb($_POST['txtid_bisnis']);
$txtid_payment = nb($_POST['txtid_payment']);
$txtseason = nb($_POST['txtseason']);
$txtstyleno = nb($_POST['txtstyleno']);
$txtstyledesc = nb($_POST['txtstyledesc']);
$txtqty = unfn($_POST['txtqty']);
$txtunit = nb($_POST['txtunit']);
$txtcurr = nb($_POST['txtcurr']);
$txtprice = nb($_POST['txtprice']);
$txtstatus = nb($_POST['txtstatus']);
$txtvalid_day = nb($_POST['txtvalid_day']);
if (isset($_FILES['txtattach_file']))
{	$nama_file = $_FILES['txtattach_file']['name'];
	$tmp_file = $_FILES['txtattach_file']['tmp_name'];
	$path = "upload_files/quote_inq/".$nama_file;
	move_uploaded_file($tmp_file, $path);
}
else
{ $nama_file=""; }
$txtattach_file = $nama_file;
if ($txtquote_no=="")
{	$kd_mkt=flookup("kode_mkt","userpassword","username='$user'");
	$cri2=$kd_mkt."/".date('my',strtotime($txtquote_date));
	$txtquote_no=urutkan_inq("INQ-2018",$cri2); 
}
$cek = flookup("count(*)","quote_inq","quote_no='$txtquote_no'");
if ($cek=="0")
{	$sql = "insert into quote_inq (quote_no,quote_date,id_buyer,id_bisnis,id_payment,season,styleno,styledesc,qty,unit,curr,price,status,valid_day,attach_file)
		values ('$txtquote_no','$txtquote_date','$txtid_buyer','$txtid_bisnis','$txtid_payment','$txtseason','$txtstyleno','$txtstyledesc','$txtqty','$txtunit','$txtcurr','$txtprice','$txtstatus','$txtvalid_day','$txtattach_file')";
	insert_log($sql,$user);
	$_SESSION['msg'] = 'Data Berhasil Disimpan';
	echo "<script>
		 window.location.href='../marketting/?mod=3';
	</script>";
}
else
{	$sql = "update quote_inq set status='$txtstatus',season='$txtseason',styleno='$txtstyleno',
		id_bisnis='$txtid_bisnis',id_payment='$txtid_payment',styledesc='$txtstyledesc' where quote_no='$txtquote_no'";
	insert_log($sql,$user);
	$_SESSION['msg'] = 'Data Berhasil Dirubah';
	echo "<script>
		 window.location.href='../marketting/?mod=3';
	</script>";
}
?>