<?PHP
include "../../include/conn.php";
include "../forms/fungsi.php";
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];

$id_cs = $_REQUEST['id_cs'];
$id_mf = $_REQUEST['id_mf'];
$j_rate_mf = $_REQUEST['j_rate_mf'];
$id_item_mf = $_REQUEST['id_item_mf'];
if ($j_rate_mf=="J")
{	$price_mf = unfn($_REQUEST['price_mf']);	}
else
{	$price_mf = unfn($_REQUEST['price_idr_mf']);	}
$cons_mf = $_REQUEST['cons_mf'];
$unit_mf = $_REQUEST['unit_mf'];
$allow_mf = $_REQUEST['allow_mf'];
$mat_sour_mf = $_REQUEST['mat_sour_mf'];
if ($id_mf=="")
{	$sql = "insert into act_development_mfg (id_act_cost,id_item,price,cons,unit,allowance,material_source,jenis_rate)
		values ('$id_cs','$id_item_mf','$price_mf','$cons_mf',
		'$unit_mf','$allow_mf','$mat_sour_mf','$j_rate_mf')";
	insert_log($sql,$user);
}
else
{	$sql = "update act_development_mfg set jenis_rate='$j_rate_mf',price='$price_mf',cons='$cons_mf',unit='$unit_mf',allowance='$allow_mf',
		material_source='$mat_sour_mf' where id_act_cost='$id_cs' and id='$id_mf'";
	insert_log($sql,$user);
}
?>