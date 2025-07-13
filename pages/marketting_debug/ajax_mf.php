<?php
include "../../include/conn.php";
include "../forms/fungsi.php";
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];

$jenis_company = flookup("jenis_company","mastercompany","company!=''");
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
{	$sql = "insert into act_costing_mfg (id_act_cost,id_item,price,cons,unit,allowance,material_source,jenis_rate)
		values ('$id_cs','$id_item_mf','$price_mf','$cons_mf',
		'$unit_mf','$allow_mf','$mat_sour_mf','$j_rate_mf')";
	insert_log($sql,$user);
	if ($jenis_company=="VENDOR LG")
	{	
		$sql = "insert into bom_jo_item (id_jo,id_so_det,status,id_item,cons,unit,username) 
			select jod.id_jo,sod.id_so,'P',acm.id_item,acm.cons,acm.unit,'$user' 
			from act_costing_mfg acm inner join so on acm.id_act_cost=so.id_cost 
			inner join so_det sod on so.id=sod.id_so  
			inner join jo_det jod on so.id=jod.id_so 
			left join bom_jo_item bom on jod.id_jo=bom.id_jo and acm.id_item=bom.id_item 
			where acm.id_act_cost='$id_cs' and bom.id_item is null and acm.id_item='$id_item_mf'";
		#echo $sql;
		insert_log($sql,$user);
	}	
}
else
{	$sql = "update act_costing_mfg set jenis_rate='$j_rate_mf',price='$price_mf',cons='$cons_mf',unit='$unit_mf',allowance='$allow_mf',
		material_source='$mat_sour_mf' where id_act_cost='$id_cs' and id='$id_mf'";
	insert_log($sql,$user);
	if ($jenis_company=="VENDOR LG")
	{	
		$sql = "update act_costing_mfg acm inner join so on acm.id_act_cost=so.id_cost 
			inner join jo_det jod on so.id=jod.id_so 
			inner join bom_jo_item bom on jod.id_jo=bom.id_jo and acm.id_item=bom.id_item 
			set bom.cons=acm.cons 
			where acm.id_act_cost='$id_cs' and acm.id_item='$id_item_mf' and bom.status='P' ";
		insert_log($sql,$user);
	}
}
?>