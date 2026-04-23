<?php
include "../../include/conn.php";
include "fungsi.php";
ini_set('date.timezone', 'Asia/Jakarta');
session_start();

header('Content-Type: application/json');

$id = isset($_GET['id']) ? $_GET['id'] : '';

if ($id == '') {
    echo json_encode([
        "header" => null,
        "detail" => []
    ]);
    exit;
}

// ================= HEADER =================
$sql_header = "
    SELECT *
    FROM transfer_memo_exim_h
    WHERE id = '$id'
";

$q_header = mysqli_query($conn_li, $sql_header);
$header = mysqli_fetch_assoc($q_header);

// ================= DETAIL =================
$sql_detail = "
    SELECT 
        c.id_h,
        b.nm_memo,
        c.tgl_memo,
        ms.supplier,
        c.jns_trans,
        c.jns_pengiriman,
        mb.supplier AS buyer,
        UPPER(IFNULL(b.keterangan, a.keterangan)) AS keterangan

    FROM transfer_memo_exim_h a
    INNER JOIN transfer_memo_exim_det b 
        ON b.no_trans = a.no_trans
    INNER JOIN memo_h c 
        ON c.nm_memo = b.nm_memo
    INNER JOIN mastersupplier ms 
        ON ms.id_supplier = c.id_supplier
    INNER JOIN mastersupplier mb 
        ON mb.id_supplier = c.id_buyer

    WHERE a.id = '$id'
    GROUP BY b.nm_memo
";

$q_detail = mysqli_query($conn_li, $sql_detail);

$detail = [];
while ($row = mysqli_fetch_assoc($q_detail)) {

    // OPTIONAL: format tanggal kalau mau (biar sama kayak Laravel)
    $row['tgl_memo'] = $row['tgl_memo'] ? date("Y-m-d", strtotime($row['tgl_memo'])) : '';

    $detail[] = $row;
}

// ================= OUTPUT =================
echo json_encode([
    "header" => $header,
    "detail" => $detail
]);

?>
