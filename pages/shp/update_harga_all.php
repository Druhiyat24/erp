<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();

if (empty($_SESSION['username'])) { 
    header("location:../../"); 
    exit;
}

$user    = $_SESSION['username'];
$jen_trx = $_GET['jen_trx'];
$tbl     = $_GET['tbl'];
$dtrx    = $_GET['dtrx'] ?? '';
$noid    = $_GET['noid'] ?? '';

// ambil semua data POST array
$editharga = $_POST['editharga'] ?? [];
$satuan_bc = $_POST['satuan_bc'] ?? [];
$curr_bc   = $_POST['curr_bc'] ?? [];
$qty_bc    = $_POST['qty_bc'] ?? [];
$rate_bc   = $_POST['rate_bc'] ?? [];

// counter untuk mengetahui berapa baris sukses
$updated = 0;

foreach ($editharga as $id => $harga) {
    // konversi angka
    $harga    = str_replace(',', '', trim($harga));
    $sat_bc   = trim($satuan_bc[$id] ?? '');
    $cur_bc   = trim($curr_bc[$id] ?? '');
    $q_bc     = ($qty_bc[$id] === "" ? "NULL" : (float)$qty_bc[$id]);
    $r_bc     = ($rate_bc[$id] === "" ? "NULL" : (float)$rate_bc[$id]);

    // query update
    $sql = "
        UPDATE $tbl SET 
            price_bc  = '" . mysqli_real_escape_string($conn_li, $harga) . "',
            satuan_bc = '" . mysqli_real_escape_string($conn_li, $sat_bc) . "',
            qty_bc    = $q_bc,
            curr_bc   = '" . mysqli_real_escape_string($conn_li, $cur_bc) . "',
            rate_bc   = $r_bc
        WHERE id = '" . mysqli_real_escape_string($conn_li, $id) . "'
    ";

    if (mysqli_query($conn_li, $sql)) {
        $updated++;
        insert_log($sql, $user);
    } else {
        // kalau gagal, bisa catat error-nya
        error_log("Gagal update ID $id: " . mysqli_error($conn_li));
    }
}

// redirect dengan pesan
echo "<script>
alert('Update berhasil untuk $updated baris data!');
window.location.href='../shp/?mod=2U_new&trx=$jen_trx&dtrx=$dtrx&noid=$noid';
</script>";
?>
