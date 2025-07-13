<?php
include "../../include/conn.php";
include "fungsi.php";
ini_set('date.timezone', 'Asia/Jakarta');
session_start();
// getNomorPengajuan.php
if (isset($_GET['jenis_dok'])) {
    $jenisDok = $_GET['jenis_dok'];

    // Query untuk mendapatkan nomor pengajuan berdasarkan jenis dokumen
    $sql = "select * from (select kode_dokumen, nomor_aju, SUBSTRING(nomor_aju, -6) AS no_aju, DATE_FORMAT(STR_TO_DATE(SUBSTRING(nomor_aju, 13, 8), '%Y%m%d'), '%Y-%m-%d') AS tgl_aju, LPAD(nomor_daftar,6,0) no_daftar, tanggal_daftar tgl_daftar, status_update_sb from exim_header) a where kode_dokumen = '$jenisDok' GROUP BY no_aju, no_daftar
UNION
select REPLACE(REPLACE(REPLACE(jenis_dok, '.', ''), ' ', ''),'BC', '') jenis_dok, nomor_aju, nomor_aju no_aju, tanggal_aju, nomor_daftar, tanggal_daftar, 'Active' status from exim_ceisa_manual where status != 'CANCEL' and REPLACE(REPLACE(REPLACE(jenis_dok, '.', ''), ' ', ''),'BC', '') = '$jenisDok'";
    $result = mysqli_query($conn_li, $sql);

    echo "<option value='-'>Select Nomor Pengajuan</option>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='{$row['no_daftar']}'>{$row['no_daftar']}</option>";
    }
}
?>
