<?PHP
include "../../include/conn.php";
include "../forms/fungsi.php";
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];

$id_cs = $_REQUEST['id_cs'];
$id_ot = $_REQUEST['id_ot'];
$j_rate_ot = $_REQUEST['j_rate_ot'];
$id_item_ot = $_REQUEST['id_item_ot'];
if ($j_rate_ot=="J")
{	$price_ot = unfn($_REQUEST['price_ot']);	}
else
{	$price_ot = unfn($_REQUEST['price_idr_ot']);	}
if ($id_ot=="")
{	$sql = "insert into act_costing_oth (id_act_cost,id_item,price,jenis_rate)
		values ('$id_cs','$id_item_ot','$price_ot','$j_rate_ot')";
	insert_log($sql,$user);
}
else
{	$sql = "update act_costing_oth set jenis_rate='$j_rate_ot',id_item='$id_item_ot',price='$price_ot'
		where id_act_cost='$id_cs' and id='$id_ot'"; 
	insert_log($sql,$user);
}
?>