<?php
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
$user=$_SESSION['username'];

$kain = $_POST['kain'];
$color = $_POST['color'];
$size = $_POST['no_roll'];
$qty = $_POST['jml_roll'];
$id = $_GET['id'];

$countkain = count($kain);

for($i=0;$i<$countkain;$i++){

$sql = "insert into request_det_dev (id_req,kain,color,size,qty)
		values ('$id','$kain[$i]','$color[$i]','$size[$i]','$qty[$i]')";
insert_log($sql,$user);
$_SESSION['msg'] = 'Data Berhasil Disimpan';
echo "<script>
		 window.location.href='../marketting/?mod=request1&id=$id';
	</script>";		
	
	
}


?>