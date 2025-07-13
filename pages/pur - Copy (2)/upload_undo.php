<!-- import excel ke mysql -->
<!-- www.malasngoding.com -->

<?php
// menghubungkan dengan koneksi
include "../../include/conn.php";
// menghubungkan dengan library excel reader
include "excel_reader.php";
session_start();
$dateinput		= date('Y-m-d H:i:s');
$id = $_GET['id_po'];
$user = $_SESSION['username'];
?>

<?php

mysql_query("DELETE from po_item_draft_upload where id_po_draft = '$id'");

// alihkan halaman ke index.php
$_SESSION['msg'] = "Data Berhasil di Undo";
echo "<script>window.location.href='../pur/?mod=33z&id=$id';</script>";
?>