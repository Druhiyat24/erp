<?php
include "../../include/conn.php";
include "fungsi.php";
ini_set('date.timezone', 'Asia/Jakarta');
session_start();

$no_maintain = $_REQUEST['no_maintain'];
$user = $_SESSION['username'];
$app_date = date("Y-m-d H:i:s");

$query = "UPDATE maintain_bpb_h 
          SET status = 'CANCEL', cancel_by = '$user', cancel_date = '$app_date' 
          WHERE no_maintain = '$no_maintain'";
$execute = mysqli_query($conn_li, $query);

$query2 = "UPDATE bpb a 
           INNER JOIN maintain_bpb_det b ON b.no_bpb = a.bpbno_int 
           SET a.status_maintain = NULL 
           WHERE b.no_maintain = '$no_maintain'";
$execute2 = mysqli_query($conn_li, $query2);

$query3 = "UPDATE maintain_bpb_det 
           SET status = 'N' 
           WHERE no_maintain = '$no_maintain'";
$execute3 = mysqli_query($conn_li, $query3);

// Error handling
if (!$execute || !$execute2 || !$execute3) {
    die('Error: ' . mysqli_error($conn_li));
}

mysqli_close($conn_li);
?>
