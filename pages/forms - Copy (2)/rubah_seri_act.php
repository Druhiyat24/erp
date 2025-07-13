<?php
include '../../include/conn.php';
include 'fungsi.php';

session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$criold=strtoupper($_POST['txtfrom']);
$crinew=strtoupper($_POST['txtbaru']);
$criold_sep=explode('|', $criold);
$crinew_sep=explode('|', $crinew);

$sql="update bppb set id_item='$crinew_sep[0]' where bppbno='$criold_sep[1]' and 
	id_item='$criold_sep[0]' and qty='$criold_sep[2]' limit 1";
insert_log($sql,$user);
$sql2="update bpb set qty=0 where id_item='$criold_sep[0]' and qty='$criold_sep[2]' and bcno='-' limit 1";
insert_log($sql2,$user);
calc_stock('A',$crinew_sep[0]);
calc_stock('A',$criold_sep[0]);

echo "<script>
	alert('Data berhasil dirubah');
	window.location.href='rubah_seri.php';
</script>";
?>