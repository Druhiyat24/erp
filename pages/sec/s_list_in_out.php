<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
$usernya=$_GET['id'];

if(isset($_GET['id'])) {$txtid=$_GET['id'];} else {$txtid="";}
$txtjenis_kendaraan = nb($_POST['txtjenis_kendaraan']);
$txtnama_supir = nb($_POST['txtnama_supir']);
$txtnomor_polisi = nb($_POST['txtnomor_polisi']);
$txtnomor_sj = nb($_POST['txtnomor_sj']);
$txtid_supplier = nb($_POST['txtid_supplier']);
$txtpono = nb($_POST['txtpono']);
$txtjenis_barang = nb($_POST['txtjenis_barang']);
$txtjumlah = unfn($_POST['txtjumlah']);
if(isset($_POST['txtsatuan'])) {$txtsatuan = nb($_POST['txtsatuan']);} else {$txtsatuan = "";}
$dateinput = date("Y-m-d H:i:s");
if($txtid=="") { $sql_id=""; } else { $sql_id=" and id!='$txtid'"; }
$cek = flookup("count(*)","list_in_out","jenis_kendaraan='$txtjenis_kendaraan' and nama_supir='$txtnama_supir' 
	and nomor_polisi='$txtnomor_polisi' and nomor_sj='$txtnomor_sj' and id_supplier='$txtid_supplier' and 
	jenis_barang='$txtjenis_barang' $sql_id ");
if ($cek=="0" and $txtid=="")
{	$sql = "insert into list_in_out (jenis_kendaraan,nama_supir,nomor_polisi,nomor_sj,id_supplier,jenis_barang,
		username,dateinput,pono,qty,unit) values ('$txtjenis_kendaraan','$txtnama_supir','$txtnomor_polisi','$txtnomor_sj',
		'$txtid_supplier','$txtjenis_barang','$user','$dateinput','$txtpono','$txtjumlah','$txtsatuan')";
	insert_log($sql,$user);
	$_SESSION['msg'] = 'Data Berhasil Disimpan';
}
else if ($cek=="0" and $txtid!="")
{	$sql = "update list_in_out set jenis_kendaraan='$txtjenis_kendaraan',nama_supir='$txtnama_supir',
		nomor_polisi='$txtnomor_polisi',nomor_sj='$txtnomor_sj',id_supplier='$txtid_supplier',
		jenis_barang='$txtjenis_barang',pono='$txtpono',qty='$txtjumlah',
		unit='$txtsatuan',username='$user' where id='$txtid'";
	insert_log($sql,$user);
	$_SESSION['msg'] = 'Data Berhasil Disimpan';
}
else
{	$_SESSION['msg'] = 'XData Sudah Ada'; }
echo "<script>window.location.href='../sec/?mod=1L';</script>";
?>