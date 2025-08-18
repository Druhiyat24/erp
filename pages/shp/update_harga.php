<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();

if (empty($_SESSION['username'])) { 
    header("location:../../"); 
    exit;
}

$user   = $_SESSION['username'];

// ambil parameter GET
$id     = $_GET['iddata'];
$dtrx   = $_GET['dtrx'];
$noid   = $_GET['noid'];
$jen_trx= $_GET['jen_trx'];
$tbl    = $_GET['tbl'];

// ambil parameter POST
$editharga = $_POST['editharga'];
$satuan_bc = $_POST['satuan_bc'];
$curr_bc   = $_POST['curr_bc'];

// jika kosong → NULL, jika ada → cast angka
$qty_bc  = ($_POST['qty_bc']  === "" ? "NULL" : (float)$_POST['qty_bc']);
$rate_bc = ($_POST['rate_bc'] === "" ? "NULL" : (float)$_POST['rate_bc']);

// buat query
$sql = "
    UPDATE $tbl SET 
        price_bc  = '" . mysqli_real_escape_string($conn_li, $editharga) . "',
        satuan_bc = '" . mysqli_real_escape_string($conn_li, $satuan_bc) . "',
        qty_bc    = $qty_bc,
        curr_bc   = '" . mysqli_real_escape_string($conn_li, $curr_bc) . "',
        rate_bc   = $rate_bc
    WHERE id = '" . mysqli_real_escape_string($conn_li, $id) . "'
";

// jalankan query
if (!mysqli_query($conn_li, $sql)) {
    die("Error update: " . mysqli_error($conn));
}

// logging
insert_log($sql, $user);

// redirect
echo "<script>window.location.href='../shp/?mod=2U_new&trx=$jen_trx&dtrx=$dtrx&noid=$noid';</script>";
?>
