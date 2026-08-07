<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user = $_SESSION['username'];
$id = isset($_GET['id']) ? $_GET['id'] : '';

if ($id == '') {
    $_SESSION['msg'] = 'ID Item Tidak Ditemukan';
    echo "<script>window.location.href='../others/?mod=2L';</script>";
    exit;
}

// ===== FUNGSI UPLOAD FILE =====
if (isset($_FILES['txtfile']) && $_FILES['txtfile']['name'] != '') {
    $nama_file = $_FILES['txtfile']['name'];
    $tmp_file  = $_FILES['txtfile']['tmp_name'];
    $path      = "upload_files/".$nama_file;
    if (move_uploaded_file($tmp_file, $path)) {
        // ===== QUERY UPDATE HANYA file_gambar =====
        $nama_file = nb($nama_file);
        $sql = "update masteritem set file_gambar='$nama_file' where id_item='$id'";
        insert_log($sql, $user);
        $_SESSION['msg'] = 'File Gambar Berhasil Diupload';
    } else {
        $_SESSION['msg'] = 'Gagal Upload File';
    }
} else {
    $_SESSION['msg'] = 'Tidak Ada File Yang Dipilih';
}

echo "<script>window.location.href='../others/?mod=2L';</script>";
?>
