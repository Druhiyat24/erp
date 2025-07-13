<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../"); }
if ($user=="") { header("location:../../"); }
$user=$_SESSION['username'];

$id_po=$_GET['id_po'];
$curr = $_POST['txtcurr'];
$txtid_terms = $_POST['txtid_terms'];
$txtdays = $_POST['txtdays'];
$txtid_dayterms = $_POST['txtid_dayterms'];
$n_kurs = $_POST['n_kurs'];

$podate = $_POST['txtpodate'];
$podate_1 = strtotime($podate);
$podate_fix = date('Y-m-d',$podate_1);

$txtetddate = $_POST['txtetddate'];
$txtetddate_1 = strtotime($txtetddate);
$txtetddate_fix = date('Y-m-d',$txtetddate_1);

$txtetadate = $_POST['txtetadate'];
$txtetadate_1 = strtotime($txtetadate);
$txtetadate_fix = date('Y-m-d',$txtetadate_1);

$txtexpdate = $_POST['txtexpdate'];
$txtexpdate_1 = strtotime($txtexpdate);
$txtexpdate_fix = date('Y-m-d',$txtexpdate_1);

$txtdisc = $_POST['txtdisc'];
$txtppn = $_POST['txtppn'];
$txtnotes = $_POST['txtnotes'];
$tipe_com = $_POST['tipe_com'];



$ItemArray = $_POST['id_cek'];
$QtyArr = $_POST['qty_po'];
$PriceArr = $_POST['price_po'];
$cancelArr = $_POST['id_cancel'];
foreach ($ItemArray as $key => $value)
{
	$qty=$QtyArr[$key];
	$id=$ItemArray[$key];
	$cancel= $cancelArr[$key];
	$price= $PriceArr[$key];

	if ($cancel == "")
	{
		$cancelcek = "N";
	}
	else
	{	
		$cancelcek = "Y";
	}	
		$sql_header="update po_header set podate = '$podate_fix', id_terms = '$txtid_terms', jml_pterms = '$txtdays', id_dayterms = '$txtid_dayterms', etd = '$txtetddate_fix', eta = '$txtetadate_fix', expected_date = '$txtexpdate_fix', discount = '$txtdisc', ppn = '$txtppn', tax = '$txtppn', notes = '$txtnotes', n_kurs = '$n_kurs' 
			where id = '$id_po'";
		insert_log($sql_header,$user);

		$sql_tipe_com="update po_header_draft set tipe_com = '$tipe_com' where id = (select id_draft from po_header where id = '$id_po')";
		insert_log($sql_tipe_com,$user);

		
		$sql="update po_item set qty = '$qty', price = '$price', curr = '$curr', cancel = '$cancelcek' where id_po = '$id_po' and id = '$id'";
		insert_log($sql,$user);
		$_SESSION['msg'] = "Data Berhasil Disimpan";

	echo "<script>window.location.href='../pur/?mod=33x&id=$id_po';</script>";	
}
?>