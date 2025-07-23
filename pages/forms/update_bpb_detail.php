<?php
include '../../include/conn.php';
include 'fungsi.php';
session_start();

if (empty($_SESSION['username'])) {
  header("location:../../");
  exit();
}

$user = $_SESSION['username'];
$iddata_arr = $_POST['iddata'];
$totqty_arr = $_POST['totqty'];
$totqty_reject_arr = $_POST['totqty_reject'];
$bpbno = $_POST['bpbno'];

for ($i = 0; $i < count($iddata_arr); $i++) {
  $id = mysql_real_escape_string($iddata_arr[$i]);
  $qty = mysql_real_escape_string($totqty_arr[$i]);
  $qty_reject = mysql_real_escape_string($totqty_reject_arr[$i]);

  $sql = "UPDATE bpb SET qty = '$qty', qty_reject = '$qty_reject' WHERE id = '$id'";
  insert_log($sql, $user);
  mysql_query($sql);
}

$_SESSION['msg'] = "Data telah diperbarui";
echo "<script>window.location.href='../forms/?mod=edit_bpb&bpbno=$bpbno';</script>";
?>
