<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';

$id_item=$_POST['id'];
$nama_file=flookup("file_gambar","masteritem","id_item='$id_item'");
echo $nama_file;
echo "<img src='upload_files/$nama_file' class='img-responsive' alt='-'>";
?>