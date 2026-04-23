<?php
include "../../include/conn.php";

session_start();

$user = isset($_SESSION['username']) ? $_SESSION['username'] : 'SYSTEM';
$now  = date("Y-m-d H:i:s");
$status = 'POST';

$id_h = $_POST['id_h'];


foreach($_FILES['file']['name'] as $key => $val){

    $tmp  = $_FILES['file']['tmp_name'][$key];
    $name = time().'_'.$val;

    move_uploaded_file($tmp, "upload/".$name);

    mysqli_query($conn_li,"INSERT INTO memo_file (id_h, file_name, status, created_by, created_date)
                 VALUES ('$id_h','$name','$status','$user','$now')");
}

echo "OK";
?>
