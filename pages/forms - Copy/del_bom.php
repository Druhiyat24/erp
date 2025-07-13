<?php 
session_start();
include "../../include/conn.php";
include "fungsi.php";

$user=$_SESSION['username'];

$id_item_bb=$_GET['idbb'];
$id_item_fg=$_GET['idfg'];
$sql="delete from bom where id_item_bb='$id_item_bb' and id_item_fg='$id_item_fg'";
insert_log($sql,$user);
$_SESSION['msg']="Data Berhasil Dihapus";
echo "<script>window.location.href='index.php?mod=16';</script>";
?>