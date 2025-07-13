<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';

$id_item=$_POST['id'];
$nama_file=flookup("attach_file","jo","id='$id_item'");
echo $nama_file;
echo "<img src='upload_files/ws/$nama_file' class='img-responsive' alt='-'>";
?>