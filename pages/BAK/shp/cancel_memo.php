<?php
include "../../include/conn.php";
include "fungsi.php";
ini_set('date.timezone', 'Asia/Jakarta');
session_start();

$no_memo = $_REQUEST['no_memo'];
$user = $_SESSION['username'];
$app_date = date("Y-m-d H:i:s");
$sql = "update memo_h set status='CANCEL',cancel_by='$user',cancel_date = '$app_date' where nm_memo = '$no_memo'";
$query = mysqli_query($conn_li,$sql);

?>
