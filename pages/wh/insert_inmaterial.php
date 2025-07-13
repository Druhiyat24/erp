<?php
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (empty($_SESSION['username'])) {
	header("location:../../index.php");
}
$user=$_SESSION['username'];
$mod = $_GET['mod'];

$no_dok = $_POST['no_dok'];
$tgl_dok = date("Y-m-d",strtotime($_POST['tgl_dok']));
$supplier = $_POST['supplier'];
$no_po = $_POST['no_po'];
$tipe_pembelian = $_POST['tipe_pembelian'];
$no_sj = $_POST['no_sj'];
$keterangan = $_POST['keterangan'];
$kode_barang = $_POST['kode_barang'];
$nama_barang = $_POST['nama_barang'];
$job_order = $_POST['job_order'];
$qty = $_POST['qty'];
$unit = $_POST['unit'];
$tgl_input = date("Y-m-d H:i:s");
$cancel = 'N';


// if ($mod == 'simpan') {
// 	$no_dok	 		= nb($_POST['txtno_dok']);
// 	$tgl_dok	 	= fd($_POST['tgl_dok']);
// 	$supplier	 	= nb($_POST['supplier']);
// 	$no_po			= nb($_POST['no_po']);
// 	$tipe_pembelian	= nb($_POST['tipe_pembelian']);
// 	$no_sj			= nb($_POST['no_sj']);
// 	$keterangan		= nb($_POST['keterangan']);
// 	$tgl_input 		= date("Y-m-d H:i:s");
// 	$cancel 		= 'N';

// 	$dateinput		= date('Y-m-d H:i:s');


// 	$kode_barang 	= $_POST['kode_barang'];
// 	$nama_barang 	= $_POST['nama_barang'];
// 	$job_order 		= $_POST['job_order'];
// 	$qty 			= $_POST['qty'];
// 	$unit 			= $_POST['pil_unit'];

// 	$sql = "insert into in_material (no_dok,tgl_dok,supplier,no_po,tipe_pembelian,no_sj,keterangan,kode_barang,nama_barang,job_order,qty,unit,dibuat,tgl_input,cancel)
// 			values ('$no_dok','$tgl_dok','$supplier','$no_po','$tipe_pembelian','$no_sj','$keterangan','$kode_barang','$nama_barang','$job_order','$qty','$unit','$user','$tgl_input','$cancel')";
// 	insert_log($sql, $user);

// 	$_SESSION['msg'] = "Penerimaan Berhasil Disimpan, Nomor Penerimaan : " . $no_dok;
// 	echo "<script>window.location.href='../wh/?mod=in_material_new';</script>";

	
// }


// echo $no_dok;
// echo $tglftrdp;
// echo $nama_supp;
// echo $no_pi;
// echo $curr;
// echo $create_date;
// echo $status;
// echo $create_user;
// echo $no_bpb;
// echo $tgl_bpb;
// echo $total;
// echo $dp_code;
// echo $dp_value;
// echo $balance;

if ($kode_barang != '') {
$query = "INSERT INTO in_material (no_dok,tgl_dok,supplier,no_po,tipe_pembelian,no_sj,keterangan,kode_barang,nama_barang,job_order,qty,unit,dibuat,tgl_input,cancel)
VALUES 
	('$no_dok','$tgl_dok','$supplier','$no_po','$tipe_pembelian','$no_sj','$keterangan','$kode_barang','$nama_barang','$job_order','$qty','$unit','$user','$tgl_input','$cancel')";
	
$execute = mysqli_query($conn_li,$query);


}

if ($mod == 'simpan') {
	$no_dok	 	= nb($_POST['txtno_dok']);

            $_SESSION['msg'] = "Penerimaan Berhasil Disimpan, Nomor Penerimaan : " . $no_dok;
			echo "<script>window.location.href='../wh/?mod=in_material';</script>";

}


if(!$execute){	
   die('Error: ' . mysqli_error());	
}

?>