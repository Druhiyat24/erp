<?php
include '../../conn/conn.php';
ini_set('date.timezone', 'Asia/Jakarta');

$doc_number = $_POST['doc_number'];

// echo "< -- >";
// echo $no_kbon;

	$query = "delete from sb_supp_doc_temp ";

$execute = mysqli_query($conn2,$query);




if(!$execute){	
   die('Error: ' . mysqli_error());	
}else{
	
}

mysqli_close($conn2);
?>