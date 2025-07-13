<?php 
include '../../include/conn.php';
include 'fungsi.php';
include '../forms/func_gen_kartu_stock.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
if (isset($_SESSION['username'])) {} else { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
$mod=$_GET['mod'];
$mod2="35v";
$mode=$_GET['mode'];
if (isset($_GET['noid'])) {$bppbno = $_GET['noid']; } else {$bppbno = "";}

if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}
$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
$gen_nomor_int=$rscomp["gen_nomor_int"];

$txtremark=nb($_POST['txtremark']);
$txtid_supplier=nb($_POST['txtid_supplier']);
$txtjns_out=nb($_POST['txtjns_out']);
if (isset($_POST['txtbcno'])) { $txtbcno=nb($_POST['txtbcno']); } else { $txtbcno = ""; } 
if (isset($_POST['txtbcdate'])) { $txtbcdate = fd($_POST['txtbcdate']); } else { $txtbcdate = ""; } 
if ($txtid_item_fg=="") 
{ $txtid_item_fg = $txtkpno; 
	$txtkpno = flookup("kpno","masterstyle","id_item='$txtid_item_fg'");
}
else
{ $txtkpno = flookup("kpno","masterstyle","id_item='$txtid_item_fg'"); }

$txtreqno=nb($_POST['txtreqno']);
$cbomat=substr($txtreqno,3,1);
$txtbppbno=nb($_POST['txtbppbno']);
$txtbppbdate = fd($_POST['txtbppbdate']);


if (isset($_POST['txtstatus_kb'])) { $status_kb=nb($_POST['txtstatus_kb']); } else { $status_kb = ""; } 
if (isset($_POST['txtnomor_rak'])) {$txtnomor_rak=nb($_POST['txtnomor_rak']);} else {$txtnomor_rak="";}
if ($mod=="61r") { $nm_tbl="bppb_req"; } else { $nm_tbl="bppb"; }
if ($bppbno=="")
{ # COPAS SAVE ADD

	$txtbppbno = urutkan('Add_BPPB',$cbomat); 
	if($gen_nomor_int=="Y")
	{	$cbomat2=flookup("nama_pilihan","masterpilihan","kode_pilihan='MAP_MAT_".$cbomat."'");
		$date=fd($txtbppbdate);
		$cri2=$cbomat2."/OUT/".date('my',strtotime($date));
		$txtbppbno2=urutkan_inq($cbomat2."-OUT-".date('Y',strtotime($date)),$cri2);
	}
	else
		{ $txtbppbno2=""; }

$ItemArray = $_POST['id_cek'];
$QtyArr = $_POST['qtyout_roll'];
$Id_item_barcode_Arr = $_POST['id_item_barcode'];
$Id_jo_barcode_Arr = $_POST['id_jo_barcode'];
foreach ($ItemArray as $key => $value)
{
	$qtyout_roll=$QtyArr[$key];
	$id_roll=$ItemArray[$key];
	$id_item_barcode=$Id_item_barcode_Arr[$key];
	$id_jo_barcode=$Id_jo_barcode_Arr[$key];

$sql_upd_roll = "update bpb_roll set roll_qty_used = roll_qty_used + $qtyout_roll where id = '$id_roll'";
						insert_log($sql_upd_roll,$user);

$sql_insert_det = "insert into bppb_barcode_det (id,bppbno,bppbno_int,bppbno_req,id_roll,qty_roll,id_item,id_jo,user) values
					('','$txtbppbno','$txtbppbno2','$txtreqno','$id_roll','$qtyout_roll','$id_item_barcode','$id_jo_barcode','$user')";
					insert_log($sql_insert_det,$user);											
}

$QtyoutArray = $_POST['qtyout'];
$id_itemArray = $_POST['id_item'];
$unit_itemArray = $_POST['unitsisa'];
$id_suppArray = $_POST['id_supp'];
$JoArray = $_POST['jono'];

foreach ($QtyoutArray as $key_1 => $value_1)
{
	$qtyout_global=$QtyoutArray[$key_1];
	$id_item=$id_itemArray[$key_1];
	$unit=$unit_itemArray[$key_1];
	$id_supp=$id_suppArray[$key_1];
	$id_jo=$JoArray[$key_1];
	
if ($qtyout_global != '0' && $qtyout_global != '' )
	{	
$sql = "insert into bppb (bppbno,bppbno_int,bppbno_req,bppbdate,id_item,qty,price,remark,use_kite,berat_bersih,berat_kotor,username,unit,qty_karton,bcno,bcdate,jenis_dok,id_supplier,id_jo,jenis_trans) 
values ('$txtbppbno','$txtbppbno2','$txtreqno','$txtbppbdate','$id_item','$qtyout_global','0','$txtremark','1','0','0','$user','$unit','0','$txtbcno','$txtbcdate','$status_kb','$id_supp','$id_jo','$txtjns_out')
";
						#echo "<br>".$sql;
						insert_log($sql,$user);

	if ($qtyout_global_fix == '')
		{
			$qtyout_global_fix = '0';
		}
	else
		{
			$qtyout_global_fix = $qtyout_global;	
		}	

$sql_upd_req = "update bppb_req set qty_out = qty_out + $qtyout_global_fix where bppbno = '$txtreqno' and id_item = '$id_item'";
						insert_log($sql_upd_req,$user);						

}
}		
		$_SESSION['msg'] = "Data Berhasil Disimpan";
		echo "<script>window.location.href='../forms/?mod=355v&mode=Bahan_Baku';</script>";						



	# END COPAS SAVE ADD
}

?>