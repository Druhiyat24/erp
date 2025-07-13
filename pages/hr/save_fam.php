<?php
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$mode="";

$txtnik = strtoupper($_POST['txtnik']);
$txtnama_pasangan = strtoupper($_POST['txtnama_pasangan']);
$txtnik_pasangan = strtoupper($_POST['txtnik_pasangan']);
$txtno_bpjs_pasangan = strtoupper($_POST['txtno_bpjs_pasangan']);
$txttempat_lahir_pasangan = strtoupper($_POST['txttempat_lahir_pasangan']);
$txttgl_lahir_pasangan = strtoupper($_POST['txttgl_lahir_pasangan']);
$txtnama = strtoupper($_POST['txtnama']);
$txtnama_anak1 = strtoupper($_POST['txtnama_anak1']);
$txtnik_anak1 = strtoupper($_POST['txtnik_anak1']);
$txtno_bpjs_anak1 = strtoupper($_POST['txtno_bpjs_anak1']);
$txttempat_lahir_anak1 = strtoupper($_POST['txttempat_lahir_anak1']);
$txttgl_lahir_anak1 = strtoupper($_POST['txttgl_lahir_anak1']);
$txtnama_anak2 = strtoupper($_POST['txtnama_anak2']);
$txtnik_anak2 = strtoupper($_POST['txtnik_anak2']);
$txtno_bpjs_anak2 = strtoupper($_POST['txtno_bpjs_anak2']);
$txttempat_lahir_anak2 = strtoupper($_POST['txttempat_lahir_anak2']);
$txttgl_lahir_anak2 = strtoupper($_POST['txttgl_lahir_anak2']);
$txtnama_anak3 = strtoupper($_POST['txtnama_anak3']);
$txtnik_anak3 = strtoupper($_POST['txtnik_anak3']);
$txtno_bpjs_anak3 = strtoupper($_POST['txtno_bpjs_anak3']);
$txttempat_lahir_anak3 = strtoupper($_POST['txttempat_lahir_anak3']);
$txttgl_lahir_anak3 = strtoupper($_POST['txttgl_lahir_anak3']);
$cek = flookup("count(*)","hr_employeefamily","nik='$txtnik'");
if ($cek=="0")
{	$sql = "insert into hr_employeefamily (nik,nama_pasangan,nik_pasangan,no_bpjs_pasangan,tempat_lahir_pasangan,tgl_lahir_pasangan,nama_anak1,nik_anak1,no_bpjs_anak1,tempat_lahir_anak1,tgl_lahir_anak1,nama_anak2,nik_anak2,no_bpjs_anak2,tempat_lahir_anak2,tgl_lahir_anak2,nama_anak3,nik_anak3,no_bpjs_anak3,tempat_lahir_anak3,tgl_lahir_anak3)
		values ('$txtnik','$txtnama_pasangan','$txtnik_pasangan','$txtno_bpjs_pasangan','$txttempat_lahir_pasangan','$txttgl_lahir_pasangan','$txtnama_anak1','$txtnik_anak1','$txtno_bpjs_anak1','$txttempat_lahir_anak1','$txttgl_lahir_anak1','$txtnama_anak2','$txtnik_anak2','$txtno_bpjs_anak2','$txttempat_lahir_anak2','$txttgl_lahir_anak2','$txtnama_anak3','$txtnik_anak3','$txtno_bpjs_anak3','$txttempat_lahir_anak3','$txttgl_lahir_anak3')";
	insert_log($sql,$user);
	echo "<script>
		 alert('Data berhasil disimpan');
		 window.location.href='../hr/?mod=5L';
	</script>";
}
else
{	$sql="update hr_employeefamily set nama_pasangan='$txtnama_pasangan',
		nik_pasangan='$txtnik_pasangan',no_bpjs_pasangan='$txtno_bpjs_pasangan',
		tempat_lahir_pasangan='$txttempat_lahir_pasangan',tgl_lahir_pasangan='$txttgl_lahir_pasangan',
		nama_anak1='$txtnama_anak1',nik_anak1='$txtnik_anak1',no_bpjs_anak1='$txtno_bpjs_anak1',
		tempat_lahir_anak1='$txttempat_lahir_anak1',tgl_lahir_anak1='$txttgl_lahir_anak1',
		nama_anak2='$txtnama_anak2',nik_anak2='$txtnik_anak2',no_bpjs_anak2='$txtno_bpjs_anak2',
		tempat_lahir_anak2='$txttempat_lahir_anak2',tgl_lahir_anak2='$txttgl_lahir_anak2',
		nama_anak3='$txtnama_anak3',nik_anak3='$txtnik_anak3',no_bpjs_anak3='$txtno_bpjs_anak3',
		tempat_lahir_anak3='$txttempat_lahir_anak3',tgl_lahir_anak3='$txttgl_lahir_anak3' 
		where nik='$txtnik'";
	insert_log($sql,$user);
	echo "<script>
		 alert('Data berhasil dirubah');
		 window.location.href='../hr/?mod=5L';
	</script>";
}
?>