<?php 
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode="";

$crinya = $_POST['cborak'];
$raknya = $_POST['cbomutrak'];
$ItemArray = $_POST['itemchk'];
foreach ($ItemArray as $key => $value)
{	
	$sql="insert into bpb_roll (id_h,roll_no,lot_no,roll_qty,roll_foc,unit,roll_qty_used,id_rak,
		barcode,roll_qty_foc_used,id_rak_loc) 
		select id_h,roll_no,lot_no,roll_qty,roll_foc,unit,roll_qty_used,id_rak,
		barcode,roll_qty_foc_used,'$raknya' from bpb_roll 
		where id='$key'";
	#echo "<br>".$sql;
	insert_log($sql,$user);
	$sql="update bpb_roll set roll_qty_used=roll_qty where id='$key'";
	#echo "<br>".$sql;
	insert_log($sql,$user);
}
$_SESSION['msg'] = 'Data Berhasil Disimpan';
echo "<script>window.location.href='../forms/?mod=mr';</script>";
?>