<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
$id_so=$_GET['id'];
$mode="";
if (isset($_GET['idd'])) {$id_so_det=$_GET['idd'];} else {$id_so_det="";}

$chkh_arr = $_POST['chkhide'];
if (isset($_POST['itemchk'])) {$chk_arr = $_POST['itemchk'];} else {$chk_arr = "";}
foreach ($chkh_arr as $key => $value) 
{	$nm_fld=$key;
	if ($mod=="14")
	{	if (isset($chk_arr[$key]))
		{	$id_jo_det = $key;
			$id_item_jo = flookup("id_item","bom_jo_item","id='$id_jo_det'");
			$cek=flookup("count(*)","po_item","id_jo='$id_so' and id_gen='$id_item_jo' 
				and cancel='N' ");
			$cekPO=flookup("pono","po_item a inner join po_header s on a.id_po=s.id","id_jo='$id_so' 
				and id_gen='$id_item_jo' and cancel='N' ");
			if ($cek!="0")
			{	$_SESSION['msg'] = 'XData Tidak Bisa Dicancel Karena Sudah Dibuat PO : '.$cekPO;	
				echo "<script>window.location.href='../marketting/?mod=$mod&id=$id_so';</script>";
				exit;
			}
			else
			{	$sql = "update bom_jo_item set cancel='Y' where id_jo='$id_so' 
					and id='$id_jo_det'";
				insert_log($sql,$user);
				$_SESSION['msg'] = 'Data Berhasil Dicancel';
			}	
		}
	}
	else
	{	if (isset($chk_arr[$key]))
		{	$id_so_det = $key;
			$cek=flookup("count(*)","jo_det","id_so='$id_so'");
			$cekJO=flookup("jo_no","jo_det a inner join jo s on a.id_jo=s.id","id_so='$id_so' and a.cancel='N' ");
			$cek2=flookup("so_no","unlock_so","id_so='$id_so' and DATE_ADD(unlock_date, INTERVAL 2 DAY)>'$dateskrg'");
			if ($cek!="0" and $cek2=="")
			{	$_SESSION['msg'] = 'XData Tidak Bisa Dicancel Karena Sudah Dibuat Worksheet : '.$cekJO;	
				echo "<script>window.location.href='../marketting/?mod=$mod&id=$id_so';</script>";
				exit;
			}
			else
			{	$sql = "update so_det set cancel='Y' where id_so='$id_so' 
					and id='$id_so_det'";
				insert_log($sql,$user);
				$_SESSION['msg'] = 'Data Berhasil Dicancel';
			}	
		}
	}
}
echo "<script>window.location.href='../marketting/?mod=$mod&id=$id_so';</script>";
?>