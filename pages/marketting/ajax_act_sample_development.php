<?php 
include "../../include/conn.php";
include "../forms/fungsi.php";
$modajax=$_GET['mdajax']; 

if ($modajax=="get_nama_file")
{	$id_item=$_REQUEST['id_itemnya'];
	if($id_item!="")
	{	$nm_file=flookup("file_gambar","masteritem","id_item='$id_item'");
		echo json_encode(array($nm_file));	
	}
}

if ($modajax=="edit_cost_detail" or $modajax=="edit_cost_mf" or $modajax=="edit_cost_ot")
{	$id_cs=$_REQUEST['id_cs'];
	$id_cd=$_REQUEST['id_cd'];
	if ($modajax=="edit_cost_mf")
	{	$nm_tbl="act_development_mfg"; }
	else if ($modajax=="edit_cost_ot")
	{	$nm_tbl="act_development_oth"; }
	else
	{	$nm_tbl="act_development_mat"; }
	$sql="select a.*,
		if(jenis_rate='B',price/rate_beli,price) px_usd,
		if(jenis_rate='J',price*rate_jual,price) px_idr 
		from $nm_tbl a inner join act_development s on a.id_act_cost=s.id
		left join masterrate d on 'USD'=d.curr and s.cost_date=d.tanggal
		where a.id_act_cost='$id_cs' and a.id='$id_cd'";
	#echo "<script>alert($sql)</script>";
	$rs=mysql_fetch_array(mysql_query($sql));
	echo json_encode(array($rs['id_item'],fn($rs['px_usd'],3),$rs['cons'],$rs['unit'],
		$rs['material_source'],$rs['allowance'],fn($rs['px_idr'],2),$rs['jenis_rate']));
}
if ($modajax=="get_pre_cost")
{	$crinya=$_REQUEST['precostid'];
	$sql="select a.qty,d.supplier,f.product_group,f.product_item,s.styleno 
		from pre_costing a inner join quote_inq s on a.id_inq=s.id 
		inner join mastersupplier d on s.id_buyer=d.id_supplier 
		inner join masterproduct f on a.id_product=f.id 
		where a.id='$crinya'";
	$rs=mysql_fetch_array(mysql_query($sql));
	echo json_encode(array($rs['supplier'],$rs['product_group'],
			$rs['product_item'],$rs['styleno'],$rs['qty']));
	exit;
}
if ($modajax=="calc_smv_min")
{	$cri=$_REQUEST['smvnya'];
	$qty=unfn($_REQUEST['qtynya']);
	$hsl=$cri * 60;
	$hsl2=$qty * $hsl;
	$hsl3=$hsl2 / 60;
	echo json_encode(array(fn($hsl,3),fn($hsl2,3),fn($hsl3,3),fn($cri,3)));
}
if ($modajax=="get_kurs_rate")
{	$curr=$_REQUEST['currnya'];
	$tgl=fd($_REQUEST['tglnya']);
	$rate=flookup("count(*)","masterrate","curr='$curr' and tanggal='$tgl'
		and rate<>0 and rate_jual<>0 and rate_beli<>0");
	echo json_encode(array($rate));
}
if ($modajax=="calc_usd_idr")
{	$px=$_REQUEST['pxnya'];
	$tgl=$_REQUEST['tglnya'];
	$rate=flookup("rate_jual","masterrate","curr='USD' 
		and tanggal='".fd($tgl)."'");
	$hsl=$px * $rate;
	echo json_encode(array($hsl,'J'));
}
if ($modajax=="calc_idr_usd")
{	$px=$_REQUEST['pxnya'];
	$tgl=$_REQUEST['tglnya'];
	$rate=flookup("rate_beli","masterrate","curr='USD' 
		and tanggal='".fd($tgl)."'");
	$hsl=round($px/$rate,3);
	echo json_encode(array($hsl,'B'));
}
if ($modajax=="calc_smv_sec")
{	$cri=unfn($_REQUEST['smvnya']);
	$qty=unfn($_REQUEST['qtynya']);
	$hsl=$cri / 60;
	$hsl2=$qty * $hsl;
	$hsl3=$hsl2 * 60;
	echo json_encode(array(fn($hsl,3),fn($hsl2,3),fn($hsl3,3),fn($cri,3)));
}
?>