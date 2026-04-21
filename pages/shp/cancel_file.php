<?php
include "../../include/conn.php";

session_start();
$user = isset($_SESSION['username']) ? $_SESSION['username'] : 'SYSTEM';
$now  = date("Y-m-d H:i:s");

$id = $_POST['id'];

$query = "UPDATE memo_file SET status = 'CANCEL', cancel_by = '$user', cancel_date = '$now' WHERE id = '$id'";
$result = mysql_query($query);

if($result){
    echo "OK";
} else {
    echo "ERROR";
}
?>
