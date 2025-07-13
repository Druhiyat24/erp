<?php
include '../../conn/conn.php';
ini_set('date.timezone', 'Asia/Jakarta');

$no_fiskal = $_POST['no_fiskal'];
$cancel_date = date("Y-m-d H:i:s");
$cancel_user = $_POST['cancel_user'];

if(isset($no_fiskal)){
$sql = "update sb_journal_fiscal set cancel_date = '$cancel_date', cancel_by = '$cancel_user', status = 'Cancel' where id = '$no_fiskal'";
$execute = mysqli_query($conn2,$sql);


// $sql2 = "insert into sb_list_journal_cancel (select * from sb_list_journal where no_journal='$no_mj')";
// $query2 = mysqli_query($conn2,$sql2);

// header('Refresh:0; url=memorial-journal.php');

if(!$query2) {
	die('Error: ' . mysqli_error());	
}else{
	
}

}else{
	die('Error: ' . mysqli_error());		
}

// echo $no_kbon;
// echo $amount;
// echo $balance;
// echo $update_balance;

mysqli_close($conn2);

?>