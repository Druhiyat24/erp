<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../"); }

$user=$_SESSION['username'];
$bpbno=$_GET['bpbno'];

$bpbno_int=isset($_POST['bpbno_int']) ? $_POST['bpbno_int'] : '';
$bpbdate=isset($_POST['bpbdate']) ? fd($_POST['bpbdate']) : '';
$id_supplier=isset($_POST['id_supplier']) ? $_POST['id_supplier'] : '';
$pono=isset($_POST['pono']) ? $_POST['pono'] : '';
$jenis_dok=isset($_POST['jenis_dok']) ? $_POST['jenis_dok'] : '';
$jenis_trans=isset($_POST['jenis_trans']) ? $_POST['jenis_trans'] : '';
$invno=isset($_POST['invno']) ? $_POST['invno'] : '';
$remark=isset($_POST['remark']) ? $_POST['remark'] : '';
$profit_center=isset($_POST['profit_center']) ? $_POST['profit_center'] : '';

if ($bpbno=="" or $bpbdate=="" or $id_supplier=="" or $jenis_dok=="" or $jenis_trans=="" or $invno=="" or $remark=="")
{
	$_SESSION['msg'] = "XData Tidak Boleh Kosong, Update Dibatalkan";
	echo "<script>window.location.href='../forms/?mod=edit_bpb&bpbno=$bpbno';</script>";
}
else
{
	$sql="update bpb set bpbdate='$bpbdate', id_supplier='$id_supplier', jenis_dok='$jenis_dok'
		, jenis_trans='$jenis_trans', invno='$invno', remark='$remark', profit_center='$profit_center' where bpbno = '$bpbno'";
	insert_log($sql,$user);

	$_SESSION['msg'] = "Data telah diperbarui";
	echo "<script>window.location.href='../forms/?mod=edit_bpb&bpbno=$bpbno';</script>";
}
?>