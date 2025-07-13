<?PHP
include "../../include/conn.php";
include "../forms/fungsi.php";
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];

$id_cs = $_REQUEST['id_cs'];
$id_item_cd = $_REQUEST['id_item_cd'];
$cek=flookup("count(*)","act_development_mat","id_act_cost='$id_cs' and id_item='$id_item_cd'");
echo json_encode(array($cek));
?>