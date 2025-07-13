<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
$usernya=$_GET['id'];

if(isset($_GET['id'])) {$txtid=$_GET['id'];} else {$txtid="";}
$sql = "update list_in_out set dihide='Y' where id='$txtid'";
insert_log($sql,$user);
$_SESSION['msg'] = 'Data Berhasil Dihide';
echo "<script>window.location.href='../sec/?mod=1v';</script>";
?>