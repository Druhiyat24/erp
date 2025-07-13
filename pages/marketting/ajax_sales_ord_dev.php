<?php 
include "../../include/conn.php";
include "../forms/fungsi.php";
$modajax=$_GET['mdajax'];
if ($modajax=="get_act_cost")
{	$crinya=$_REQUEST['actcostid'];
	$sql="select d.supplier,f.product_group,f.product_item,styleno
		,g.kpno,g.deldate,g.cfm_price,g.curr 
		from act_development g inner join mastersupplier d on g.id_buyer=d.id_supplier 
		inner join masterproduct f on g.id_product=f.id 
		where g.id='$crinya'";
	$rs=mysql_fetch_array(mysql_query($sql));
	echo json_encode(array($rs['product_group'],$rs['product_item']
		,$rs['styleno'],$rs['kpno'],$rs['supplier']
		,fd_view($rs['deldate'])
		,$rs['cfm_price']
		,$rs['curr']));
	exit;
}
?>