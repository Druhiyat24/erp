<?php 
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode=$_GET['mode'];
$cbomat="A";
$txtunit="";
$txtcurr="";
$txtprice = 0;
$txtremark = strtoupper($_POST['txtremark']);
$txtberat_bersih = 0;
$txtberat_kotor = 0;
$txtnomor_mobil = strtoupper($_POST['txtnomor_mobil']);
$txtid_supplier = strtoupper($_POST['txtid_supplier']);
$txtinvno = strtoupper($_POST['txtinvno']);
$txtbcno = strtoupper($_POST['txtbcno']);
$txtbcdate = fd($_POST['txtbcdate']);
$txtbppbno = strtoupper($_POST['txtbppbno']);
$txtbppbdate = fd($_POST['txtbppbdate']);
$status_kb = strtoupper($_POST['txtstatus_kb']);
$txtbppbno = urutkan('Add_BPPB',$cbomat);

if (!isset($_POST['item']))
{	echo "<script>window.location.href='index.php?mod=$mod&mode=$mode&msg=7';</script>"; }
else
{	$ItemArray = $_POST['item'];
	$CurrArray = $_POST['curr'];
	$PriceArray = $_POST['price'];
	foreach ($ItemArray as $key => $value) 
	{	if (is_numeric($value))
		{	#echo $value." in ".$key;
	    #echo "<br>";
	    $txtcurr = $CurrArray[$key];
	    $txtprice = $PriceArray[$key];

	    $cek = flookup("count(*)","bppb","id_item='$key' and 
				bppbno='$txtbppbno'");
			if ($cek=="0")
			{	$sql = "insert into bppb (id_item,qty,unit,curr,price,remark,berat_bersih,berat_kotor,nomor_mobil,id_supplier,invno,bcno,bcdate,bppbno,
					bppbdate,jenis_dok,username,use_kite)
					values ('$key','$value','$txtunit','$txtcurr','$txtprice','$txtremark','$txtberat_bersih','$txtberat_kotor',
					'$txtnomor_mobil','$txtid_supplier','$txtinvno','$txtbcno','$txtbcdate','$txtbppbno',
					'$txtbppbdate','$status_kb','$user','1')";
				insert_log($sql,$user);
				calc_stock($cbomat,$key);
			}
	  }
	}	
	$_SESSION['msg'] = "Data Berhasil Disimpan dengan Nomor BKB : $txtbppbno";
	echo "<script>window.location.href='index.php?mod=$mod&mode=$mode';</script>";
}
?>