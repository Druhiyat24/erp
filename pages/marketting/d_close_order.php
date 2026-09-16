<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();

if (empty($_SESSION['username'])) { 
    header("location:../../index.php"); 
    exit();
}

$user       = $_SESSION['username'];
$mod        = $_GET['mod'];
$id_costing = $_GET['id'];
$action     = 'close'; 

$sql_update = "UPDATE act_costing SET close_order='Y', close_order_by='$user', close_order_at=NOW() WHERE id='$id_costing'";
insert_log($sql_update, $user);

$sql_history = "INSERT INTO close_order_history (id_act_cost, ws, username, action) 
                SELECT id, kpno, '$user', '$action' 
                FROM act_costing 
                WHERE id = '$id_costing'";
insert_log($sql_history, $user);

$_SESSION['msg'] = 'Data Berhasil Di Close Order';

echo "<script>window.location.href='../marketting/?mod=$mod';</script>";
?>