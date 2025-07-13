<?php
include '../../conn/conn.php';
ini_set('date.timezone', 'Asia/Jakarta');

$coa = $_POST['coa'];
$no_alk = $_POST['no_alk'];
$tgl_alk = $_POST['tgl_alk'];
$buyer = $_POST['buyer'];
$curr = $_POST['curr'];
$rate = $_POST['rate'];
$cost_center = $_POST['cost_center'];
$no_ref = $_POST['no_ref'];
$ref_date = date("Y-m-d",strtotime($_POST['ref_date']));
$due_date = date("Y-m-d",strtotime($_POST['due_date']));
$total = $_POST['total'];
$eqp_idr = $_POST['eqp_idr'];
$amount = $_POST['amount'];
$keterangan = $_POST['keterangan'];
$create_user = $_POST['create_user'];
$create_date = date("Y-m-d H:i:s");
$kata1 = "PELUNASAN PIUTANG USAHA DARI";
$keterangan = $kata1 +' '+ $buyer;

echo $coa;

if ($amount > 0) {
	$amount2 = $amount;
}else{
	$amount2 = $amount * -1;
}

if ($eqp_idr > 0) {
	$eqp_idr2 = $eqp_idr;
}else{
	$eqp_idr2 = $eqp_idr * -1;
}


$sqlcoa = mysqli_query($conn1,"select nama_coa from mastercoa_v2 where no_coa = '$coa'");
$rowcoa = mysqli_fetch_array($sqlcoa);
$nama_coa = $rowcoa['nama_coa'];

$sqlcc = mysqli_query($conn1,"select cc_name from b_master_cc where no_cc = '$cost_center'");
$rowcc = mysqli_fetch_array($sqlcc);
$nama_cc = $rowcc['cc_name'];

$query = "INSERT INTO sb_alokasi_detail (no_alk, coa, cost_center, no_ref, ref_date, due_date, total, eqp_idr, amount, keterangan, status) 
VALUES 
	('$no_alk', '$coa', '$cost_center', '$no_ref', '$ref_date', '$due_date', '$total', '$eqp_idr', '$amount', '$keterangan', 'POST')";

$execute = mysqli_query($conn2,$query);

if ($amount > 0) {
	$query2 = "INSERT INTO sb_list_journal (no_journal, tgl_journal, type_journal, no_coa, nama_coa, no_costcenter, nama_costcenter, reff_doc, reff_date, buyer, no_ws, curr, rate, debit, credit, debit_idr, credit_idr,status, keterangan, create_by, create_date, approve_by, approve_date, cancel_by, cancel_date)
VALUES 
	('$no_alk', '$tgl_alk', 'Alokasi', '$coa', '$nama_coa', '$cost_center', '$nama_cc', '$no_ref', '$ref_date', '$buyer', '', '$curr', '$rate', '0', '$amount2', '0', '$eqp_idr2','POST', '$keterangan', '$create_user', '$create_date', '', '', '', '')";
}else{
	$query2 = "INSERT INTO sb_list_journal (no_journal, tgl_journal, type_journal, no_coa, nama_coa, no_costcenter, nama_costcenter, reff_doc, reff_date, buyer, no_ws, curr, rate, debit, credit, debit_idr, credit_idr,status, keterangan, create_by, create_date, approve_by, approve_date, cancel_by, cancel_date)
VALUES 
	('$no_alk', '$tgl_alk', 'Alokasi', '$coa', '$nama_coa', '$cost_center', '$nama_cc', '$no_ref', '$ref_date', '$buyer', '', '$curr', '$rate', '$amount2', '0', '$eqp_idr2', '0','POST', '$keterangan', '$create_user', '$create_date', '', '', '', '')";
}

$execute2 = mysqli_query($conn2,$query2);



if(!$execute){	
   die('Error: ' . mysqli_error());	
}else{
	
}

mysqli_close($conn2);
?>