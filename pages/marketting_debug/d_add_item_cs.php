<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
$id_cs=$_GET['id'];
$mode="";
if (isset($_GET['idd'])) {$id_cs_mat=$_GET['idd'];} else {$id_cs_mat="";}
$jenis_company=flookup("jenis_company","mastercompany","company!=''");

/* if($jenis_company=="VENDOR LG")
{	
	$id_item_cd = flookup("id_item","act_costing_mat","id_act_cost='$id_cs' 
	and id='$id_cs_mat'");
	$sql = "update act_costing_mat acm inner join so on acm.id_act_cost=so.id_cost 
		inner join jo_det jod on so.id=jod.id_so 
		inner join bom_jo_item bom on jod.id_jo=bom.id_jo and acm.id_item=bom.id_item 
		set bom.cons=0 
		where acm.id_act_cost='$id_cs' and acm.id_item='$id_item_cd'";
	insert_log($sql,$user);
}
$sql = "update act_costing_mat set cons=0,price=0 where id_act_cost='$id_cs' 
	and id='$id_cs_mat'"; */
$sql = "DELETE FROM act_costing_mat WHERE id_act_cost='$id_cs'
	and id='$id_cs_mat'";	
insert_log($sql,$user);
$_SESSION['msg'] = 'Data Berhasil Dihapus';
echo "<script>window.location.href='../marketting/?mod=$mod&id=$id_cs';</script>";
?>