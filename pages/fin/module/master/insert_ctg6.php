<?php
include '../../conn/conn.php';
ini_set('date.timezone', 'Asia/Jakarta');

$txt_idctg6 = trim($_POST['txt_idctg6']);
$txt_indname = $_POST['txt_indname'];
$txt_engname = $_POST['txt_engname'];
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
	
$sqlx = mysqli_query($conn2,"select id_ctg6 FROM sb_master_coa_ctg6 where id_ctg6 = '$txt_idctg6' ");
$rowx = mysqli_fetch_array($sqlx);
$id_ctg6 = isset($rowx['id_ctg6']) ? $rowx['id_ctg6'] : NULL;
echo $id_ctg6;
}

if ($id_ctg6 != null) {
	$query = 'select max(id) from sb_master_coa_ctg6';
	$execute = mysqli_query($conn2,$query);
	// echo "COA Already Exist";
}else{
$query = "INSERT INTO sb_master_coa_ctg6 (id_ctg6,ind_name,eng_name) 
VALUES 
	('$txt_idctg6', '$txt_indname', '$txt_engname')";

$execute = mysqli_query($conn2,$query);
}


if(!$execute){	
   die('Error: ' . mysqli_error());	
}else{
	
}

mysqli_close($conn2);
?>