<?php
include "../../include/conn.php";
include "fungsi.php";
ini_set('date.timezone', 'Asia/Jakarta');
session_start();

$doc_num = $_REQUEST['doc_num'];
$tanggal_doc = date("Y-m-d",strtotime($_REQUEST['tanggal_doc']));
$keterangan = $_REQUEST['keterangan'];
$unik_code = $_REQUEST['unik_code'];
$user = $_SESSION['username'];
$app_date = date("Y-m-d H:i:s");

$sql = mysql_query("select CONCAT(kode,'/',bulan,tahun,'/',nomor) kode from (select 'RVS/BPB' kode, DATE_FORMAT(CURRENT_DATE(), '%m') bulan, DATE_FORMAT(CURRENT_DATE(), '%y') tahun,if(MAX(no_maintain) is null,'00001',LPAD(SUBSTR(max(SUBSTR(no_maintain,15)),1,5)+1,5,0)) nomor from maintain_bpb_h) a");
$row = mysql_fetch_array($sql);
$kode = $row['kode'];

$query = "INSERT INTO maintain_bpb_h (no_maintain,tgl_maintain,status,keterangan,created_by,created_date,unik_code) 
VALUES 
('$kode', '$tanggal_doc', 'POST', '$keterangan', '$user', '$app_date', '$unik_code')";

$execute = mysqli_query($conn_li,$query);


if(!$execute){	
	die('Error: ' . mysqli_error());	
}else{
	echo 'Data Saved Successfully With Document Number '; echo $kode;
}

mysqli_close($conn_li);

?>
