<?php 
include '../../include/conn.php';
include 'fungsi.php';
include 'journal_interface.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];

$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
	$gen_nomor_int=$rscomp["gen_nomor_int"];
	$auto_jurnal=$rscomp["auto_jurnal"];

$cridt = $_POST['txttglcut'];

$arrnya = explode(":",$_POST['txtsjno']);
$txtbppbno = $arrnya[1];
$nm_tbl = $arrnya[0];
if ($nm_tbl=="bpb") {$nm_fld="bpbno";} else {$nm_fld="bppbno";}
$tgl_cfm = date('Y-m-d H-i-s');


// {	
// 	{	
// 		{	$sql = "update $nm_tbl set confirm_by='$user',confirm='Y',confirm_date='$tgl_cfm' 
// 				where $nm_fld='$txtbppbno'";
// 			insert_log($sql,$user);
// 		}
// 	}
// 	if($nm_tbl=="bpb" and $auto_jurnal=="Y")
// 	{	$bpbno_int=flookup("bpbno_int","bpb","bpbno='$txtbppbno'");	
// 		insert_bpb_gr($bpbno_int);
// 	}
// 	$_SESSION['msg'] = "Data Berhasil Dikonfirmasi";
// 	echo "<script>window.location.href='index.php?mod=$mod&mode=$mode';</script>";
// }




if (!isset($_POST['itemchk']))
{	$_SESSION['msg'] = "XTidak Ada Data Yang Harus Dikonfirmasi";
	echo "<script>window.location.href='index.php?mod=$mod';</script>"; 
}
else
{	$ItemArray = $_POST['itemchk'];
	foreach ($ItemArray as $key => $value) 
	{	$chk=$value;
		$id_item=$key;
		if ($chk=="on")
		{	$sql = "update $nm_tbl set confirm_by='$user',confirm='Y',confirm_date='$tgl_cfm' 
				where $nm_fld='$txtbppbno' and id_item='$id_item'";
			insert_log($sql,$user);
		}
	}
	if($nm_tbl=="bpb" and $auto_jurnal=="Y")
	{	$bpbno_int=flookup("bpbno_int","bpb","bpbno='$txtbppbno'");	
		insert_bpb_gr($bpbno_int);
	}
	$_SESSION['msg'] = "Data Berhasil Dikonfirmasi";
	echo "<script>window.location.href='index.php?mod=$mod&mode=$mode&tgldt=$cridt';</script>";
}
?>