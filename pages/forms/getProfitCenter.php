<?php
include "../../include/conn.php";
include "fungsi.php";
ini_set('date.timezone', 'Asia/Jakarta');
session_start();
// getDetailsPengajuan.php
if (isset($_GET['bpbno'])) {
    $bpbno = $_GET['bpbno'];

    // Query untuk mengambil detail pengajuan
    $sql = "SELECT profit_center FROM bpb WHERE bpbno = '$bpbno' limit 1";
    $result = mysqli_query($conn_li, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode($row); // Mengembalikan data dalam format JSON
    }
}
?>
