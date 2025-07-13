<?php
include '../../conn/conn.php';
ini_set('date.timezone', 'Asia/Jakarta');

$nama_ctg = $_POST['nama_ctg'];
$nama_sub = $_POST['nama_sub'];
$update_user = $_POST['update_user'];
$active_date = date("Y-m-d H:i:s");


$sql = "delete from sb_kategori_laporan where sub_kategori = '$nama_sub' and kategori_show = '$nama_ctg' and keterangan = 'EXPLANATION'";
$execute = mysqli_query($conn2,$sql);

if($execute){

}else{
	die('Error: ' . mysqli_error());		
}

mysqli_close($conn2);

?>