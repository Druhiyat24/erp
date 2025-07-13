<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
include '../forms/func_gen_kartu_stock.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mod2=$_GET['mod']."v";
if(isset($_GET['mode'])) {$mode=$_GET['mode'];} else {$mode="";}
$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
	$gen_nomor_int=$rscomp["gen_nomor_int"];
	$auto_ap_ar=$rscomp["auto_ap_ar"];
if($auto_ap_ar=="Y") { include 'insert_ap_ar.php'; }
$txtbppbno = nb($_POST['txtbppbno']);
$txtbppbdate = fd($_POST['txtbppbdate']);
if ($txtbppbno=="") 
{ $txtbppbno = urutkan("Add_BPPB","FG"); 
	if($gen_nomor_int=="Y")
	{	$cbomat="FG";
		$cbomat2=flookup("nama_pilihan","masterpilihan","kode_pilihan='MAP_MAT_".$cbomat."'");
		$date=fd($txtbppbdate);
		$cri2=$cbomat2."/OUT/".date('my',strtotime($date));
		$txtbppbno2=urutkan_inq($cbomat2."-OUT-".date('Y',strtotime($date)),$cri2);
	}
	else
	{ $txtbppbno2=""; }
}
$txtid_supplier = nb($_POST['txtid_supplier']);
$txtinvno = nb($_POST['txtinvno']);
$txtjenis_dok = nb($_POST['txtjenis_dok']);
if(isset($_POST['txttujuan'])) {$txttujuan = nb($_POST['txttujuan']);} else {$txttujuan = "";}
if(isset($_POST['txtsubtujuan'])) {$txtsubtujuan = nb($_POST['txtsubtujuan']);} else {$txtsubtujuan = "";}
$txtnomor_aju = nb($_POST['txtnomor_aju']);
$txttanggal_aju = fd($_POST['txttanggal_aju']);
$txtbcno = nb($_POST['txtbcno']);
$txtbcdate = fd($_POST['txtbcdate']);
$txtno_fp = nb($_POST['txtno_fp']);
$txttgl_fp = nb($_POST['txttgl_fp']);
$txtkkbc = nb($_POST['txtkkbc']);
$cek = flookup("count(*)","bppb","bppbno='$txtbppbno'");
if ($cek=="0")
{	$QtyArr = $_POST['itemqty'];
	$UnitArr = $_POST['itemunit'];
	$CurrArr = $_POST['itemcurr'];
	$PriceArr = $_POST['itemprice'];
	foreach ($QtyArr as $key => $value) 
	{	if ($value>0)
		{	$id_so_det=$key;
			$qty=$QtyArr[$key];
			$unit=$UnitArr[$key];
			$id_item=cek_masterstyle($id_so_det);
			$curr=$CurrArr[$key];
			$price=$PriceArr[$key];
			$cbomat = "FG";
			$txtid_item = $id_item;
			$sql = "insert into bppb (username,bppbno,bppbno_int,bppbdate,id_supplier,invno,jenis_dok,tujuan,
				subtujuan,nomor_aju,tanggal_aju,bcno,bcdate,no_fp,tgl_fp,nomor_kk_bc,
				id_item,id_so_det,qty,unit,curr,price)
				values ('$user','$txtbppbno','$txtbppbno2','$txtbppbdate','$txtid_supplier','$txtinvno','$txtjenis_dok',
				'$txttujuan','$txtsubtujuan','$txtnomor_aju','$txttanggal_aju','$txtbcno',
				'$txtbcdate','$txtno_fp','$txttgl_fp','$txtkkbc','$id_item','$id_so_det',
				'$qty','$unit','$curr','$price')";
			insert_log($sql,$user);
			calc_stock($cbomat,$txtid_item);
			if ($cbomat=="FG")
			{ gen_kartu_stock($user,$sesi,$txtid_item,"bpbno like 'FG%'","bppbno like 'SJ-FG%'"); }
			else
			{ gen_kartu_stock($user,$sesi,$txtid_item,"bpbno not like 'FG%'","bppbno not like 'SJ-FG%'"); }
			// $cek=cek_minus_by_date($user,$sesi);
			// if($cek!="")
			// {	$cekarr=explode(":",$cek);
			// 	$cek=$cekarr[0];
			// 	$cekdt=$cekarr[1];
			// 	if ($cek<0)
			// 	{	$sql="delete from bppb where id_item='$txtid_item' and 
			// 			bppbno='$txtbppbno' ";
			// 		insert_log($sql,$user);
			// 		calc_stock($cbomat,$txtid_item);
			// 		$_SESSION['msg']="XStock Tidak Mencukupi. Cek Id Item : ".$txtid_item." Sisa Per Tgl ".$cekdt." : ".$cek;
			// 		echo "<script>window.location.href='../forms/?mod=$mod2&mode=$mode';</script>";
			// 		exit;
			// 	}
			// }
		}
	}
	if($auto_ap_ar=="Y") 
	{ insert_data_ap_ar("AR",$txtbppbno,$user); 
		insert_jurnal("AR",$txtbppbno,$user);
	}
	$_SESSION['msg'] = 'Data Berhasil Disimpan. Nomor BKB : '.$txtbppbno;
}
else
{	$_SESSION['msg'] = 'XData Sudah Ada'; }
echo "<script>
	 window.location.href='../forms/?mod=32z&mode=FG';
</script>";
?>