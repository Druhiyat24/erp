<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../"); }

$user=$_SESSION['username'];
$bpbno=$_GET['bpbno'];

$bpbno_int=$_POST['bpbno_int'];
$bpbdate=fd($_POST['bpbdate']);
$id_supplier=$_POST['id_supplier'];
$pono=$_POST['pono'];
$jenis_dok=$_POST['jenis_dok'];
$jenis_trans=$_POST['jenis_trans'];
$invno=$_POST['invno'];
$remark=$_POST['remark'];

$sql="update bpb set bpbdate='$bpbdate', id_supplier='$id_supplier', jenis_dok='$jenis_dok' 
	, jenis_trans='$jenis_trans', invno='$invno', remark='$remark' where bpbno = '$bpbno'";
insert_log($sql,$user);

$_SESSION['msg'] = "Data telah diperbarui";
echo "<script>window.location.href='../forms/?mod=edit_bpb&bpbno=$bpbno';</script>";
?>