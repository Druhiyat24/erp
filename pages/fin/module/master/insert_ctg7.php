<?php
include '../../conn/conn.php';
ini_set('date.timezone', 'Asia/Jakarta');

$txt_idctg6 = trim($_POST['txt_idctg6']);
$txt_indname = $_POST['txt_indname'];
$txt_engname = $_POST['txt_engname'];
$exp_ctg = $_POST['exp_ctg'];
$nama_sub = $_POST['nama_sub'];
$status = "Active";
$create_date = date("Y-m-d H:i:s");
$create_user = $_POST['create_user'];





// echo $txt_group;
// echo "< -- >";
// echo $txt_indname;
// echo "< -- >";
// echo $txt_engname;
// echo "< -- >";
// echo $status;

if ($txt_idctg6 == '') {
	$id_ctg6 = "123";
	echo $id_ctg6;
}
else{
	
	$sqlx = mysqli_query($conn2,"select id_ctg7 FROM sb_master_coa_ctg7 where id_ctg7 = '$txt_idctg6' ");
	$rowx = mysqli_fetch_array($sqlx);
	$id_ctg6 = isset($rowx['id_ctg7']) ? $rowx['id_ctg7'] : NULL;
	echo $id_ctg6;
}

if ($id_ctg6 != null) {
	$query = 'select max(id) from sb_master_coa_ctg7';
	$execute = mysqli_query($conn2,$query);
	// echo "COA Already Exist";
}else{
	$query = "INSERT INTO sb_master_coa_ctg7 (id_ctg7,ind_name,eng_name) 
	VALUES 
	('$txt_idctg6', '$txt_indname', '$txt_engname')";

	$execute = mysqli_query($conn2,$query);

	if ($exp_ctg != '') {
		$sqlz = mysqli_query($conn2,"select DISTINCT kategori_show from sb_kategori_laporan where kategori = '$exp_ctg' ");
		$rowz = mysqli_fetch_array($sqlz);
		$kategori_show = isset($rowz['kategori_show']) ? $rowz['kategori_show'] : NULL;

		$query = "INSERT INTO sb_kategori_laporan (kategori,sub_kategori,keterangan,status,kategori_show) 
		VALUES 
		('$exp_ctg', '$nama_sub', 'EXPLANATION', 'Y', '$kategori_show')";

		$execute = mysqli_query($conn2,$query); 
	}

}


if(!$execute){	
	die('Error: ' . mysqli_error());	
}else{
	
}

mysqli_close($conn2);
?>