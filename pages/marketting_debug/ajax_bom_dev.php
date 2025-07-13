<?php 
include "../../include/conn.php";
include "../forms/fungsi.php";
$modajax=$_GET['mdajax'];
if ($modajax=="get_so")
{	$joid = $_REQUEST['joid'];
	$rs = mysql_fetch_array(mysql_query("select a.id,so_no,buyerno,kpno,cost_no,
	    supplier,product_group,product_item,styleno,a.qty,a.unit,
	    deldate from so_dev a inner join act_development s on 
	    a.id_cost=s.id inner join mastersupplier g on 
	    s.id_buyer=g.id_supplier inner join masterproduct h 
	    on s.id_product=h.id left join jo_det_dev j on a.id=j.id_so 
	    where j.id_jo='$joid'")); 
    echo json_encode(array($rs['product_group'],$rs['product_item']
    	,$rs['supplier'],$rs['styleno']));
	exit;
}
?>