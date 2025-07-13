<?php
include '../../include/conn.php';
include 'fungsi.php';

session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$old_pass=$_POST['txtoldpass'];
$new_pass=$_POST['txtnewpass'];

$cek=flookup("username","userpassword","username='$user' and password='$old_pass'");
if ($cek=="")
{	echo "<script>window.location.href='index.php?mod=10&msg=5';</script>";
}
$sql="update userpassword set password='$new_pass' where username='$user' and password='$old_pass'";
insert_log($sql,$user);

echo "<script>window.location.href='index.php?mod=10&msg=6';</script>";
?>