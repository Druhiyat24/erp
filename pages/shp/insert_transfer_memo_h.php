<?php
include "../../include/conn.php";
include "fungsi.php";
ini_set('date.timezone', 'Asia/Jakarta');
session_start();

$transfer_to = $_REQUEST['transfer_to'];
$unik_code = $_REQUEST['unik_code'];
$trf_date = date("Y-m-d",strtotime($_REQUEST['trf_date']));
$keterangan = $_REQUEST['keterangan'];
$user = $_SESSION['username'];
$app_date = date("Y-m-d H:i:s");




if ($transfer_to == 'MARKETING') {
	$kode_dok = 'TETM';
}else{
	$kode_dok = 'TETF';
}


$sql = mysql_query("select max(SUBSTR(no_trans,16)) no_trans from transfer_memo_exim_h where no_trans LIKE '%$kode_dok%'");
$row = mysql_fetch_array($sql);
$kodepay = $row['no_trans'];
$urutan = (int) substr($kodepay, 0, 5);
$urutan++;
$bln = date("m");
$thn = date("y");
$huruf = "$kode_dok/NAG/$bln$thn/";
$kode = $huruf . sprintf("%05s", $urutan);

$query = "INSERT INTO transfer_memo_exim_h (no_trans,tgl_trans,keterangan,status,created_by,created_at,updated_at,unik_code) 
VALUES 
( '$kode', '$trf_date', '$keterangan', 'POST', '$user', '$app_date', '$app_date', '$unik_code')";

$execute = mysqli_query($conn_li,$query);


if(!$execute){	
	die('Error: ' . mysqli_error());	
}else{
	echo 'Data Saved Successfully With Document Number '; echo $kode;
}

mysqli_close($conn_li);

?>
