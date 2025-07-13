<?php
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (isset($_SESSION['username'])) {} else { header("location:../../index.php"); }
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user = $_SESSION['username'];
$txtkpno = $_POST['txtkpno'];
$txtbpbno = $_POST['txtbpbno'];
$pecahkp = explode("~",$txtkpno);
$pecahbpb = explode("~",$txtbpbno);
$sql="update bpb set id_item_fg='$pecahkp[0]',id_item_bb='$pecahkp[1]'
	where bpbno='$pecahbpb[0]' and id='$pecahbpb[1]'";
insert_log($sql,$user);
$_SESSION['msg']="2";
echo "<script>window.location.href='index.php?mod=28';</script>";
?>