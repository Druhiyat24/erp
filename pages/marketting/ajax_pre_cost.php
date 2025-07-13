<?php 
include "../../include/conn.php";
include "../forms/fungsi.php";
$modajax=$_GET['mdajax'];
if ($modajax=="get_model_LG")
{	$id_prod = $_REQUEST['cri_item'];
	$cek=flookup("model","masterproduct","id='$id_prod'");
	echo json_encode(array($cek));
}
if ($modajax=="get_part_name")
{	$prod_group = $_REQUEST['cri_item'];
	$rs=mysql_fetch_array(mysql_query("select * from masterproduct where 
		product_group='$prod_group'"));
	echo json_encode(array($rs['id'],$rs['product_item']));
}
if ($modajax=="get_total")
{	$costf = unfn($_REQUEST['costf']);
	if ($costf!="") {$costfr = fn($costf,2);} else {$costfr="";}
	$costa = unfn($_REQUEST['costa']);
	if ($costa!="") {$costar = fn($costa,2);} else {$costar="";}
	$costm = unfn($_REQUEST['costm']);
	if ($costm!="") {$costmr = fn($costm,2);} else {$costmr="";}
	$costo = unfn($_REQUEST['costo']);
	if ($costo!="") {$costor = fn($costo,2);} else {$costor="";}
	$total = $costf + $costa + $costm + $costo;
	if ($total!="") {$totalr = fn($total,2);} else {$totalr="";}
	$curr = $_REQUEST['curr'];
	$profit = $_REQUEST['profit'];
	$totalcost = unfn($total);
	$tgl=date('Y-m-d');
	$rate=flookup("rate","masterrate","curr='USD' and tanggal='$tgl'");
	if ($curr=="IDR")
	{	$pro_idr = $totalcost * $profit/100; 
		$pro_usd = $pro_idr / $rate;
		$sell_idr = $totalcost + $pro_idr;
		$sell_usd = $sell_idr + $rate;
	}
	else
	{	$pro_usd = $totalcost * $profit/100;
		$pro_idr = $pro_usd * $rate; 
		$sell_usd = $totalcost + $pro_usd;
		$sell_idr = $sell_usd * $rate;
	}
	echo json_encode(array($total,$costfr,$costar,$costmr,$costor,$totalr,fn($pro_idr,2),fn($pro_usd,2),fn($sell_idr,2),fn($sell_usd,2)));
	exit;
}
if ($modajax=="get_profit")
{	$curr = $_REQUEST['curr'];
	$profit = $_REQUEST['profit'];
	$totalcost = unfn($_REQUEST['totalcost']);
	$tgl=date('Y-m-d');
	$rate=flookup("rate","masterrate","curr='USD' and tanggal='$tgl'");
	if ($curr=="IDR")
	{	$pro_idr = $totalcost * $profit/100; 
		$pro_usd = $pro_idr / $rate;
		$sell_idr = $totalcost + $pro_idr;
		$sell_usd = $sell_idr + $rate;
	}
	else
	{	$pro_usd = $totalcost * $profit/100;
		$pro_idr = $pro_usd * $rate; 
		$sell_usd = $totalcost + $pro_usd;
		$sell_idr = $sell_usd * $rate;
	}
	echo json_encode(array(fn($pro_idr,2),fn($pro_usd,2),fn($sell_idr,2),fn($sell_usd,2)));
	exit;
}
if ($modajax=="get_prod_item")
{	$crinya = $_REQUEST['cri_item'];
	if ($crinya!="")
	{	$sql = "select id isi,product_item tampil 
			from masterproduct where product_group='$crinya'";
		IsiCombo($sql,'','Pilih Product Item');
		exit;
	}
}
?>