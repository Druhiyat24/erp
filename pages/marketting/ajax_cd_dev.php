<?PHP
include "../../include/conn.php";
include "../forms/fungsi.php";
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];

$id_cs = $_REQUEST['id_cs'];
$id_cd = $_REQUEST['id_cd'];
$j_rate_cd = $_REQUEST['j_rate_cd'];
$id_item_cd = $_REQUEST['id_item_cd'];
if ($j_rate_cd=="J")
{	$price_cd = unfn($_REQUEST['price_cd']);	}
else
{	$price_cd = unfn($_REQUEST['price_idr_cd']);	}
$cons_cd = $_REQUEST['cons_cd'];
$unit_cd = $_REQUEST['unit_cd'];
$allow_cd = $_REQUEST['allow_cd'];
$mat_sour_cd = $_REQUEST['mat_sour_cd'];
if ($id_cd=="")
{	$sql = "insert into act_development_mat (id_act_cost,id_item,price,cons,unit,allowance,material_source,jenis_rate)
		values ('$id_cs','$id_item_cd','$price_cd','$cons_cd',
		'$unit_cd','$allow_cd','$mat_sour_cd','$j_rate_cd')";
	insert_log($sql,$user);
}
else
{	$sql = "update act_development_mat set jenis_rate='$j_rate_cd',price='$price_cd',cons='$cons_cd',unit='$unit_cd',allowance='$allow_cd',
		material_source='$mat_sour_cd' where id_act_cost='$id_cs' and id='$id_cd'";
	insert_log($sql,$user);
}
?>