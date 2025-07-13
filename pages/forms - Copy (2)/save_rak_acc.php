<?php 
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode="";
$tgl_input=date('Y-m-d');

// $crinya = $_POST['cborak'];
// $raknya = $_POST['cbomutrak'];
$ItemArray = $_POST['itemchk'];
$QtyArr = $_POST['qty_mutasi'];
$rak = $_POST['namarak'];


$sql_cari  = mysql_query("select max(no_mut) urut from bpb_det where YEAR(CURRENT_DATE()) and MONTH(CURRENT_DATE())");
	$row_cari = mysql_fetch_array($sql_cari);
	$kodepay = $row_cari['urut'];
	$urutan = (int) substr($kodepay, 15, 5);
	if ($urutan == "") {
		$urutan = "00000";
	} else {
		$urutan = $urutan;
	}
	$urutan++;
	$tahun   = substr(date('Y', strtotime($tgl_input)), 2, 2);
	$bulan   = substr(date('m', strtotime($tgl_input)), 0, 2);
	$nm_mut = "MUT/GACC/$bulan$tahun/";
	$no_mutasi = $nm_mut . sprintf("%05s", $urutan);

foreach ($ItemArray as $key => $value)
{
$qty=$QtyArr[$key];
$rak_tujuan=$rak[$key];	
echo $key;
echo $qty;
echo $rak_tujuan; 
if ($qty > 0) {
	$sql="update bpb_det set qty_mutasi = (coalesce(qty_mutasi,0) + $qty) where id='$key'";
	insert_log($sql,$user);

	$sql2="insert into bpb_det select '', bpbno,bpbno_int,id_item,id_jo,no_pack, '$qty' roll_qty, unit,'$rak_tujuan' id_rak_loc, '$user' user, '$tgl_input' bpbdate, 'N' cancel,id id_bpbdet, '' qty_mutasi,'$no_mutasi' no_mut,'$tgl_input' tgl_mut  from bpb_det where id = '$key'";
	#echo "<br>".$sql;
	insert_log($sql2,$user);
}

// 	$sql_1="insert into bpb_roll_log (id_h,id_bpb_roll,roll_no,lot_no,roll_qty,roll_foc,unit,roll_qty_used,id_rak,
// 		barcode,roll_qty_foc_used,roll_qty_act,id_rak_loc, id_rak_old,user,tgl_input) 
// 		select id_h,id,roll_no,lot_no,'$qty',roll_foc,unit,roll_qty_used,id_rak,
// 		barcode,roll_qty_foc_used,roll_qty_act,'$raknya','$crinya','$user','$tgl_input' from bpb_roll 
// 		where id='$key'";
// 	#echo "<br>".$sql;
// 	insert_log($sql_1,$user);	

// 	$sql="update bpb_roll set roll_qty= roll_qty - $qty where id='$key'";
// 	#echo "<br>".$sql;
// 	insert_log($sql,$user);
}
$_SESSION['msg'] = 'Data Berhasil Disimpan';
echo "<script>window.location.href='../forms/?mod=mr_acc';</script>";
?>