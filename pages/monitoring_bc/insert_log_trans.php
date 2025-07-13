<?php
include "../../include/conn.php";
include "fungsi.php";
ini_set('date.timezone', 'Asia/Jakarta');
session_start();

$doc_num = $_REQUEST['doc_num'];
$unik_code = $_REQUEST['unik_code'];
$user = $_SESSION['username'];
$app_date = date("Y-m-d H:i:s");

$sql = mysql_query("select max(no_trans) from ir_log_trans where kode_trans = 'TBPB'");
$row = mysql_fetch_array($sql);
$kodepay = $row['max(no_trans)'];
$urutan = (int) substr($kodepay, 15, 5);
$urutan++;
$bln = date("m");
$thn = date("y");
$huruf = "TBPB/NAG/$bln$thn/";
$kode = $huruf . sprintf("%05s", $urutan);

$query = "INSERT INTO ir_log_trans (kode_trans,no_trans,status,created_by,created_date,unik_code) 
VALUES 
('TBPB', '$kode', 'Y', '$user', '$app_date', '$unik_code')";

$execute = mysqli_query($conn_li,$query);


if(!$execute){	
	die('Error: ' . mysqli_error());	
}else{
	echo 'Data Saved Successfully With Document Number '; echo $kode;
}

mysqli_close($conn_li);

?>
