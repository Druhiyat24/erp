<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mod2=$_GET['mod']."v";
if (isset($_GET['mode'])) {$mode=$_GET['mode'];} else {$mode="";}
$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
	$gen_nomor_int=$rscomp["gen_nomor_int"];
$txtbpbno = nb($_POST['txtbpbno']);
if ($txtbpbno=="") { $txtbpbno = urutkan("FG","FG"); }
$txtbpbdate = fd($_POST['txtbpbdate']);
if($gen_nomor_int=="Y")
{	$cbomat="FG";
	$cbomat2=flookup("nama_pilihan","masterpilihan","kode_pilihan='MAP_MAT_".$cbomat."'");
	$date=fd($txtbpbdate);
	$cri2=$cbomat2."/IN/".date('my',strtotime($date));
	$txtbpbno2=urutkan_inq($cbomat2."-IN-".date('Y',strtotime($date)),$cri2);
}
else
{ $txtbpbno2=""; }
$txtid_supplier = nb($_POST['txtid_supplier']);
$txtgrade = nb($_POST['txtgrade']);
$txtinvno = nb($_POST['txtinvno']);
$txtreffno = nb($_POST['txtreffno']);
$txtjenis_dok = nb($_POST['txtjenis_dok']);
if(isset($_POST['txttujuan'])) {$txttujuan = nb($_POST['txttujuan']);} else {$txttujuan = "";}
if(isset($_POST['txtsubtujuan'])) {$txtsubtujuan = nb($_POST['txtsubtujuan']);} else {$txtsubtujuan = "";}
$txtnomor_aju = nb($_POST['txtnomor_aju']);
$txttanggal_aju = nb($_POST['txttanggal_aju']);
$txtbcno = nb($_POST['txtbcno']);
$txtbcdate = nb($_POST['txtbcdate']);
$txtno_fp = nb($_POST['txtno_fp']);
$txttgl_fp = nb($_POST['txttgl_fp']);
$txtkkbc = $_POST['txtkkbc'];
$txtid_jo = $_POST['txtid_jo'];
$cek = flookup("count(*)","bpb","bpbno='$txtbpbno'");
if ($cek=="0")
{	$QtyArr = $_POST['itemqty'];
	$UnitArr = $_POST['itemunit'];
	$CurrArr = $_POST['itemcurr'];
	$PxArr = $_POST['itemprice'];
	foreach ($QtyArr as $key => $value) 
	{	if ($value>0)
		{	$id_so_det=$key;
			$qty=$QtyArr[$key];
			$unit=$UnitArr[$key];
			$curr=$CurrArr[$key];
			$price=$PxArr[$key];
			$id_item=cek_masterstyle($id_so_det);
			$sql1 = mysql_query("update bppb set switch_out = 'D' where bppbno_int = '$txtreffno'")or die(mysql_error());					
			$sql = "insert into bpb (bpbno,bpbno_int,bpbdate,id_supplier,grade,invno,jenis_dok,tujuan,
				subtujuan,nomor_aju,tanggal_aju,bcno,bcdate,no_fp,tgl_fp,nomor_kk_bc,id_item,id_so_det,
				qty,unit,curr,price,username,reffno,switch_in)
				values ('$txtbpbno','$txtbpbno2','$txtbpbdate','$txtid_supplier','$txtgrade','$txtinvno','$txtjenis_dok',
				'$txttujuan','$txtsubtujuan','$txtnomor_aju','$txttanggal_aju','$txtbcno',
				'$txtbcdate','$txtno_fp','$txttgl_fp','$txtkkbc','$id_item','$id_so_det','$qty','$unit',
				'$curr','$price','$user','$txtreffno','Y')";		
			insert_log($sql,$user);
		}
	}
	$_SESSION['msg'] = 'Data Berhasil Disimpan';
	echo "<script>
		 window.location.href='../forms/?mod=31z';
	</script>";
}
else
{	$_SESSION['msg'] = 'XData Sudah Ada';
	echo "<script>
		 window.location.href='../forms/?mod=$mod2&mode=$mode';
	</script>";
}
?>