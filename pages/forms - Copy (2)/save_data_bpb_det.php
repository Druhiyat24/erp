<?php 
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode=$_GET['mode'];

$cek = nb($_POST['txtsjno']);
$cek_arr=explode("|",$cek);
$bpbno=$cek_arr[0];
if($bpbno!="")
{	$cekap=flookup("bpbno","acc_pay","bpbno='$bpbno'");
	if($cekap!="")
	{	$_SESSION['msg']="XData Tidak Bisa Dirubah Karena Sudah Dibuat AP";
		echo "<script>window.location.href='../forms/?mod=$mod&mode=$mode&noid=$bpbno';</script>";
		break;
	}
}
$id_item=$cek_arr[1];
$rs=mysql_fetch_array(mysql_query("select * from bpb 
	where bpbno='$bpbno' and id_item='$id_item'"));
$qty_bpb=$_POST['txtqtybpb'];
$cek="";
if (!isset($_POST['no_roll']))
{	$_SESSION['msg'] = "XTidak Ada Data";
	echo "<script>window.location.href='index.php?mod=$mod';</script>"; 
}
else
{	$JmlArray = $_POST['no_roll'];
	$DetArray = $_POST['item_det'];
	foreach ($JmlArray as $key => $value) 
	{	if (is_numeric($value))
		{	$txtid_item = $DetArray[$key];
	    $txtqty_det = $JmlArray[$key];
    	$sql = "insert into bpb (bpbno,id_item,qty,unit,id_supplier,sudah_detail,
    		jenis_dok,bcno,bcdate,bpbdate,use_kite,tanggal_aju,pono,username)
				values ('$bpbno','$txtid_item','$txtqty_det','$rs[unit]','$rs[id_supplier]','Y',
				'$rs[jenis_dok]','$rs[bcno]','$rs[bcdate]','$rs[bpbdate]',1,'$rs[tanggal_aju]',
				'$rs[pono]','$rs[username]')";
			insert_log($sql,$user);
		}
	}
	$sql="delete from bpb 
		where bpbno='$bpbno' and id_item='$id_item' and sudah_detail='N'";
	insert_log($sql,$user);		
	$_SESSION['msg'] = "Data Berhasil Disimpan";
	echo "<script>window.location.href='index.php?mod=$mod&mode=$mode';</script>";
}
?>