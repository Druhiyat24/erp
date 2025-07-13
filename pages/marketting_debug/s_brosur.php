<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode="";
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

$txtid_season = nb($_POST['txtid_season']);
$txtid_product = nb($_POST['txtid_product']);
$txtitemname = nb($_POST['txtitemname']);
$txtstyleno = nb($_POST['txtstyleno']);
$txtstyledesc = nb($_POST['txtstyledesc']);
$txtcolor = nb($_POST['txtcolor']);
$txtcolorname = nb($_POST['txtcolorname']);
if (isset($_FILES['txtattach_file']))
{	$txtnm_file = $_FILES['txtattach_file']['name'];
	$tmp_file = $_FILES['txtattach_file']['tmp_name'];
	$path = "upload_files/brosur/".$txtnm_file;
	move_uploaded_file($tmp_file, $path);
}
else
{ $txtnm_file=""; }
$cek = flookup("count(*)","brosur","id_season='$txtid_season' and id_product='$txtid_product' and itemname='$txtitemname' and styleno='$txtstyleno' and color='$txtcolor'");
if ($cek=="0" and $id_item=="")
{	$sql = "insert into brosur (id_season,id_product,itemname,styleno,styledesc,color,color_name,
		nm_file) values ('$txtid_season','$txtid_product','$txtitemname','$txtstyleno',
		'$txtstyledesc','$txtcolor','$txtcolorname','$txtnm_file')";
	insert_log($sql,$user);
	$_SESSION['msg'] = 'Data Berhasil Disimpan';
	echo "<script>
		 window.location.href='../marketting/?mod=$mod';
	</script>";
}
else
{	if ($id_item=="")
	{$_SESSION['msg'] = 'XData Sudah Ada';}
	else
	{	$sql="update brosur set itemname='$txtitemname',styleno='$txtstyleno',styledesc='$txtstyledesc',
			color='$txtcolor',color_name='$txtcolorname' where id='$id_item'";
		insert_log($sql,$user);
		$_SESSION['msg'] = 'Data Berhasil Dirubah';
	}
	echo "<script>
		 window.location.href='../marketting/?mod=$mod';
	</script>";
}
?>