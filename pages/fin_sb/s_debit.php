<?php
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];


if (isset($_GET['id'])) {$id_debit = $_GET['id']; } else {$id_debit = "";}

  $kd_debit =nb($_POST['txtkddebit']);
  $no_invoice =nb($_POST['txtno_invoice']);
  $attn =nb($_POST['txtattn']);
  $tanggal=nb($_POST['txttanggal']);
  //$tanggal=date('d M Y', strtotime($tanggal));
  $tanggal=date('Y-m-d', strtotime($tanggal));
  $SKU =nb($_POST['txtSKU']);
  $price =nb($_POST['txtprice']);
  $qty =nb($_POST['txtqty']);
  $total_amount =nb($_POST['txttotal_amount']);
  $nopo =nb($_POST['txtnopo']);
  $notes =nb($_POST['txtnotes']);
  $nm_tempat =nb($_POST['txtnm_tempat']);
  $nm_kantor =nb($_POST['txtnm_kantor']);
  $nm_bank =nb($_POST['txtnm_bank']);
  $bank_alamat =nb($_POST['txtbank_alamat']);
  $country =nb($_POST['txtcountry']);
  $city =nb($_POST['txtcity']);
  $act_usd =nb($_POST['txtact_usd']);
  $swift_code =nb($_POST['txtswift_code']);
  $nm_buyer =nb($_POST['txtnm_buyer']);
  


if ($id_debit!="")
{ $sql="update tbl_debit set no_invoice='$no_invoice',attn='$attn',SKU='$SKU',price='$price',qty='$qty',nopo='$nopo',notes='$notes',
	nm_tempat='$nm_tempat',nm_kantor='$nm_kantor',nm_bank='$nm_bank',bank_alamat='$bank_alamat',country='$country',city='$city',act_usd='$act_usd',
	swift_code='$swift_code',nm_buyer='$nm_buyer'
    where kd_debit='$id_debit'";
	insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Dirubah";

  echo "<script>window.location.href='../fin/?mod=debit_note';</script>";
}
else
{ $sql="insert into tbl_debit (kd_debit,no_invoice,attn,tanggal,price,SKU,qty,total_amount,nopo,notes,nm_tempat,nm_kantor,
	nm_bank,bank_alamat,country,city,act_usd,swift_code,nm_buyer)
    values ('$kd_debit','$no_invoice','$attn','$tanggal','$price','$SKU','$qty','$total_amount','$nopo','$notes',
	'$nm_tempat','$nm_kantor','$nm_bank','$bank_alamat','$country','$city','$act_usd','$swift_code','$nm_buyer')";
  insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Disimpan";
  echo "<script>window.location.href='../fin/?mod=debit_note';</script>";

}
?>
