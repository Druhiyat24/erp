<?php
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$jo_no=$_POST['txtjo'];
if (isset($_FILES['txtattach_file']))
{	$nama_file = $_FILES['txtattach_file']['name'];
	$tmp_file = $_FILES['txtattach_file']['tmp_name'];
	$path = "upload_files/ws/".$nama_file;
	move_uploaded_file($tmp_file, $path);
}
else
{ $nama_file=""; }
$txtattach_file = $nama_file;
$sql="update jo set attach_file='$txtattach_file' where jo_no='$jo_no'";
insert_log($sql,$user);
$_SESSION['msg'] = 'Data Berhasil Disimpan';
echo "<script>
	 window.location.href='../marketting/?mod=12L';
</script>";
?>