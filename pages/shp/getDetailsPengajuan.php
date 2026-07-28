<?php
include "../../include/conn.php";
include "fungsi.php";
ini_set('date.timezone', 'Asia/Jakarta');
session_start();
// getDetailsPengajuan.php
if (isset($_GET['nomor_aju'])) {
    $nomorAju = $_GET['nomor_aju'];
    $jenisDok = $_GET['jenis_dok'];
    $nomordaftar = $_GET['nomordaftar'];

    // Query untuk mengambil detail pengajuan
    $sql = "select * from (select kode_dokumen, nomor_aju, SUBSTRING(nomor_aju, -6) AS no_aju, DATE_FORMAT(STR_TO_DATE(SUBSTRING(nomor_aju, 13, 8), '%Y%m%d'), '%Y-%m-%d') AS tgl_aju, LPAD(nomor_daftar,6,0) no_daftar, tanggal_daftar tgl_daftar, status_update_sb from exim_header) a where tgl_daftar >= '2026-01-01' and nomor_aju = '$nomorAju' and kode_dokumen = '$jenisDok' GROUP BY no_aju UNION
select REPLACE(REPLACE(REPLACE(jenis_dok, '.', ''), ' ', ''),'BC', '') jenis_dok, nomor_aju, nomor_aju no_aju, tanggal_aju, nomor_daftar, tanggal_daftar, 'Active' status from exim_ceisa_manual where tanggal_aju >= '2026-01-01' and status != 'CANCEL' and REPLACE(REPLACE(REPLACE(jenis_dok, '.', ''), ' ', ''),'BC', '') = '$jenisDok' and nomor_aju = '$nomorAju' and nomor_daftar = '$nomordaftar'";
    $result = mysqli_query($conn_li, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode($row); // Mengembalikan data dalam format JSON
    }
}
?>
