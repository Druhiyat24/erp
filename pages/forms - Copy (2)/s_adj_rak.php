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
$ItemArray = $_POST['itemchk'];
$QtyArr = $_POST['itemqty'];
foreach ($ItemArray as $key => $value)
{
$qty=$QtyArr[$key];

	$sql_1="insert into adj_rak_log (id_h,id_bpb_roll,roll_no,lot_no,roll_qty_old,roll_qty_used,sisa_old,sisa_new,unit, barcode,id_rak_loc,user,tgl_input) 
select id_h,id,roll_no,lot_no,roll_qty,roll_qty_used,roll_qty - roll_qty_used,'$qty',unit,barcode,'$crinya','$user','$tgl_input' from bpb_roll 
		where id='$key'";
	#echo "<br>".$sql;
	insert_log($sql_1,$user);	


	$sql="update bpb_roll set roll_qty_used = roll_qty - $qty where id='$key'";
	#echo "<br>".$sql;
	insert_log($sql,$user);

}
$_SESSION['msg'] = 'Data Berhasil Disimpan';
echo "<script>window.location.href='../forms/?mod=adj_rak';</script>";
?>