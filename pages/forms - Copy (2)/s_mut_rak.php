<?php 
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode="";
$tgl_input=date('Y-m-d');

$crinya = $_POST['cborak'];
$raknya = $_POST['cbomutrak'];
$ItemArray = $_POST['itemchk'];
$QtyArr = $_POST['itemqty'];
foreach ($ItemArray as $key => $value)
{
$qty=$QtyArr[$key];	
	$sql="insert into bpb_roll (id_h,roll_no,lot_no,roll_qty,roll_foc,unit,roll_qty_used,id_rak,
		barcode,roll_qty_foc_used,id_rak_loc, roll_qty_act) 
		select id_h,roll_no,lot_no,'$qty',roll_foc,unit,'0',id_rak,
		barcode,roll_qty_foc_used,'$raknya',roll_qty_act from bpb_roll 
		where id='$key'";
	#echo "<br>".$sql;
	insert_log($sql,$user);

	$sql_1="insert into bpb_roll_log (id_h,id_bpb_roll,roll_no,lot_no,roll_qty,roll_foc,unit,roll_qty_used,id_rak,
		barcode,roll_qty_foc_used,roll_qty_act,id_rak_loc, id_rak_old,user,tgl_input) 
		select id_h,id,roll_no,lot_no,'$qty',roll_foc,unit,roll_qty_used,id_rak,
		barcode,roll_qty_foc_used,roll_qty_act,'$raknya','$crinya','$user','$tgl_input' from bpb_roll 
		where id='$key'";
	#echo "<br>".$sql;
	insert_log($sql_1,$user);	

	$sql="update bpb_roll set roll_qty= roll_qty - $qty where id='$key'";
	#echo "<br>".$sql;
	insert_log($sql,$user);
}
$_SESSION['msg'] = 'Data Berhasil Disimpan';
echo "<script>window.location.href='../forms/?mod=mr';</script>";
?>