<?php
include '../../conn/conn.php';
ini_set('date.timezone', 'Asia/Jakarta');

$exp_ctg = $_POST['exp_ctg'];
$nama_sub = $_POST['nama_sub'];
$update_user = $_POST['update_user'];
$active_date = date("Y-m-d H:i:s");


$sqlx = mysqli_query($conn2,"select DISTINCT kategori_show from sb_kategori_laporan where kategori_show = '$exp_ctg' ");
$rowx = mysqli_fetch_array($sqlx);
$kategori_show = isset($rowx['kategori_show']) ? $rowx['kategori_show'] : NULL;

$query = "INSERT INTO sb_kategori_laporan (kategori,sub_kategori,keterangan,status,kategori_show) 
VALUES 
    ('$exp_ctg', '$nama_sub', 'EXPLANATION', 'Y', '$kategori_show')";

$execute = mysqli_query($conn2,$query);   

if($execute){

}else{
	die('Error: ' . mysqli_error());		
}

mysqli_close($conn2);

?>