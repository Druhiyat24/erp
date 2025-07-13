<?php 
include __DIR__ .'/../../../include/conn.php';
include __DIR__ .'/../../forms/fungsi.php';
if($_POST['code'] == '1' ){

$txtid_cost =$_POST['id_cost'];
$id_buyer = $_POST['id_cst'];//id_cst
$pages = $_POST['pages'];
if($pages == "MKT"){
$i_buyer=flookup("id_buyer","act_costing","id='{$txtid_cost}'");
$block=flookup("b_mkt","tbl_block_cust","id_cust='{$i_buyer}'");
}
if($pages == "INV"){
	$block=flookup("b_inv","tbl_block_cust","id_cust='{$id_buyer}'");
}
else if($pages == "SHP"){
	$block=flookup("b_shp","tbl_block_cust","id_cust='{$id_buyer}'");
}
else {
	$block=flookup("b_mkt","tbl_block_cust","id_cust='{$id_buyer}'");
}
}else{
	$block = 0;
}
$result = '{ "status":"ok", "message":"1", "records":"'.$block.'"}';

print_r($result);
?>