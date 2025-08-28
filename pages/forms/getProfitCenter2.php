<?php
include "../../include/conn.php";
include "fungsi.php";
ini_set('date.timezone', 'Asia/Jakarta');
session_start();
// getDetailsPengajuan.php
if (isset($_GET['bppbno'])) {
    $bppbno = $_GET['bppbno'];

    // Query untuk mengambil detail pengajuan
    $sql = "SELECT profit_center FROM bppb WHERE bppbno = '$bppbno' limit 1";
    $result = mysqli_query($conn_li, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode($row); // Mengembalikan data dalam format JSON
    }
}
?>
