<?php
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (empty($_SESSION['username'])) {
    header("location:../../index.php");
}
$user = $_SESSION['username'];
$tgl_dok = date("Y-m-d", strtotime($_POST['tgl_dok']));

$sql = mysqli_query($conn_li, "select max(no_dok) from in_material where MONTH(tgl_dok) = MONTH('$tgl_dok') and YEAR(tgl_dok) = YEAR('$tgl_dok')");
$row = mysqli_fetch_array($sql);
$kodepay = $row['max(no_dok)'];
$urutan = (int) substr($kodepay, 12, 4);
$urutan++;
$bln =  date("m", strtotime($tgl_dok));
$thn =  date("y", strtotime($tgl_dok));
$huruf = "GK/IN/$bln$thn/";
$kodepay = $huruf . sprintf("%04s", $urutan);
$huruf = substr($kodepay, 0, 11);
$angka = substr($kodepay, 12, 5) || 0;
$angka2 = $angka + 12;
$angka3 = sprintf("%05s", $angka2); 
  
    echo $kodepay;      


// echo '<div id="txt_sub" class="modal-body col-6" style="padding: 0.5rem; margin-left: 65%;"><h7>Subtotal: '.number_format($sub,2).'</h7></div>';
// echo '<div id="txt_tax" class="modal-body col-6" style="padding: 0.5rem; margin-left: 65%;"><h7>Tax: '.number_format($tax,2).'</h7></div>';
// echo '<div id="txt_total" class="modal-body col-6" style="padding: 0.5rem; margin-left: 65%;"><h6>Total: '.number_format($total,2).'</h6></div>';
?>