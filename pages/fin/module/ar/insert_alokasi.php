<?php
include '../../conn/conn.php';
ini_set('date.timezone', 'Asia/Jakarta');

$no_alk = $_POST['no_alk'];
$tgl_alk = date("Y-m-d",strtotime($_POST['tgl_alk']));
$customer = $_POST['customer'];
$buyer = $_POST['buyer'];
$doc_number = $_POST['doc_number'];
$bank = $_POST['bank'];
$account = $_POST['account'];
$curr = $_POST['curr'];
$rate = $_POST['rate'];
$amount = $_POST['amount'];
$eqp_idr = $_POST['eqp_idr'];
$sisa = $_POST['sisa'];
$create_user = $_POST['create_user'];
$create_date = date("Y-m-d H:i:s");
$kata1 = "PELUNASAN PIUTANG USAHA DARI";
$keterangan = $kata1 +' '+ $buyer;

// if ($curr == 'IDR') {
// 	$rates = 1;
// 	$total_idr = $rate * $rates;
// }else{
// 	$rates = $rate;
// 	$total_idr = $rate * $rate;
// }

$query = "INSERT INTO sb_alokasi (no_alk, tgl_alk, customer, doc_number, bank, account, curr, rate, amount, eqp_idr, sisa, status) 
VALUES 
	('$no_alk', '$tgl_alk', '$customer', '$doc_number', '$bank', '$account', '$curr', '$rate', '$amount', '$eqp_idr', '$sisa', 'POST')";

$execute = mysqli_query($conn2,$query);

$query2 = "INSERT INTO sb_log (nama,activity, tanggal_input, doc_number, tanggal_doc, keterangan) 
VALUES 
	('$create_user','Create Alokasi', '$create_date', '$no_alk', '$tgl_alk', 'POST')";

$execute2 = mysqli_query($conn2,$query2);

$query3 = "INSERT INTO sb_list_journal (no_journal, tgl_journal, type_journal, no_coa, nama_coa, no_costcenter, nama_costcenter, reff_doc, reff_date, buyer, no_ws, curr, rate, debit, credit, debit_idr, credit_idr,status, keterangan, create_by, create_date, approve_by, approve_date, cancel_by, cancel_date)
VALUES 
	('$no_alk', '$tgl_alk', 'Alokasi', '1.90.02', 'POS SILANG PIUTANG USAHA', '-', '-', '-', '', '$buyer', '', '$curr', '$rate', '$amount', '0', '$eqp_idr', '0','POST', '$keterangan', '$create_user', '$create_date', '', '', '', '')";

$execute3 = mysqli_query($conn2,$query3);


if(!$execute){	
   die('Error: ' . mysqli_error());	
}else{
	
}

mysqli_close($conn2);
?>