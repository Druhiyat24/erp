<?PHP
include "../../include/conn.php";
include "../forms/fungsi.php";
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];

$jenis_company = flookup("jenis_company","mastercompany","company!=''");
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
{	$sql = "insert into act_costing_mat (id_act_cost,id_item,price,cons,unit,allowance,material_source,jenis_rate)
		values ('$id_cs','$id_item_cd','$price_cd','$cons_cd',
		'$unit_cd','$allow_cd','$mat_sour_cd','$j_rate_cd')";
	insert_log($sql,$user);
	if ($jenis_company=="VENDOR LG")
	{	
		$sql = "insert into bom_jo_item (id_jo,id_so_det,status,id_item,cons,unit,username) 
			select jod.id_jo,sod.id_so,'M',acm.id_item,acm.cons,acm.unit,'$user' 
			from act_costing_mat acm inner join so on acm.id_act_cost=so.id_cost 
			inner join so_det sod on so.id=sod.id_so  
			inner join jo_det jod on so.id=jod.id_so 
			left join bom_jo_item bom on jod.id_jo=bom.id_jo and acm.id_item=bom.id_item 
			where acm.id_act_cost='$id_cs' and bom.id_item is null and acm.id_item='$id_item_cd'";
		#echo $sql;
		insert_log($sql,$user);
	}
}
else
{	$sql = "update act_costing_mat set jenis_rate='$j_rate_cd',price='$price_cd',cons='$cons_cd',unit='$unit_cd',allowance='$allow_cd',
		material_source='$mat_sour_cd' where id_act_cost='$id_cs' and id='$id_cd'";
	insert_log($sql,$user);
	if ($jenis_company=="VENDOR LG")
	{	
		$sql = "update act_costing_mat acm inner join so on acm.id_act_cost=so.id_cost 
			inner join jo_det jod on so.id=jod.id_so 
			inner join bom_jo_item bom on jod.id_jo=bom.id_jo and acm.id_item=bom.id_item 
			set bom.cons=acm.cons 
			where acm.id_act_cost='$id_cs' and acm.id_item='$id_item_cd' and bom.status='M'";
		insert_log($sql,$user);
	}
}
?>