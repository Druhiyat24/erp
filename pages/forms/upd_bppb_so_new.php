<?php 
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
$mod=$_GET['mod'];

if ($mod == 'update')
{
   $id_bppb = $_GET['id_bppb'];

   $queryheader = mysql_query("SELECT * from bppb where bppbno = '$id_bppb' limit 1");
   $databppb    = mysql_fetch_array($queryheader);
   $bppbno_int  =$databppb['bppbno_int'];

   $txtgrade = nb($_POST['txtgrade']);
   $txtbppbdate = fd($_POST['txtbppbdate']);
   $txtid_supplier = nb($_POST['txtid_supplier']);
   $txtbuyer = nb($_POST['txtbuyer']);
   $txtinvno = nb($_POST['txtinvno']);
   $txtjenis_dok = nb($_POST['txtjenis_dok']);
   $txtjenis_trans = trim(str_replace("'","",$_POST['txtjenis_trans']));

   $idbppbs = $_POST['idbppb'];
   $qtys = $_POST['qty'];

   // Qty/total lama vs baru di-grup per (so_number + product item), bukan digabung jadi
   // 1 baris per SJ - biar tiap kombinasi SO/item punya baris log sendiri. Yang diedit
   // di form ini selalu qty, jadi field_name='qty' - tapi price & total tetap dicatat
   // biar konteksnya lengkap.
   $grup_qty_lama = array();
   $grup_qty_baru = array();
   $grup_total_lama = array();
   $grup_total_baru = array();
   $grup_price = array();

   for ($i = 0; $i < count($idbppbs); $i++) {
     $idbppb = nb($idbppbs[$i]);
     $qty_baru = nb($qtys[$i]);

     // Ambil qty & price lama dulu sebelum ditimpa, sekalian so_number & product item-nya.
     $rowlama = mysql_fetch_array(mysql_query("
       SELECT c.qty, c.price, a.so_no, e.product_item
       FROM bppb c
       INNER JOIN so_det b ON b.id = c.id_so_det
       INNER JOIN so a ON a.id = b.id_so
       INNER JOIN act_costing d ON d.id = a.id_cost
       INNER JOIN masterproduct e ON e.id = d.id_product
       WHERE c.id = '$idbppb'
     "));
     $qty_lama = $rowlama['qty'];
     $price    = $rowlama['price'];
     $so_no    = $rowlama['so_no'];
     $itemdesc = $rowlama['product_item'];
     $grup_key = $so_no.'|'.$itemdesc;

     if (!isset($grup_qty_lama[$grup_key])) {
       $grup_qty_lama[$grup_key] = 0; $grup_qty_baru[$grup_key] = 0;
       $grup_total_lama[$grup_key] = 0; $grup_total_baru[$grup_key] = 0;
     }
     $grup_qty_lama[$grup_key] += $qty_lama;
     $grup_qty_baru[$grup_key] += $qty_baru;
     $grup_total_lama[$grup_key] += $qty_lama * $price;
     $grup_total_baru[$grup_key] += $qty_baru * $price;
     $grup_price[$grup_key] = $price; // price tidak berubah di form ini, ambil yang terakhir

     $sql_det = "UPDATE bppb SET qty = '$qty_baru' WHERE id = '$idbppb'";
     insert_log($sql_det, $user);
  }

  // Log perubahan data (dashboard) - qty (+ price & total sebagai konteks) per
  // (so_number + product item), sebelum vs sesudah edit
  foreach ($grup_qty_lama as $grup_key => $ql) {
    $qb = $grup_qty_baru[$grup_key];
    if ($ql != $qb) {
      $grup_parts = explode('|', $grup_key, 2);
      $so_number_log    = nb($grup_parts[0]);
      $product_item_log = nb($grup_parts[1]);
      $price_log = $grup_price[$grup_key];
      $tl = $grup_total_lama[$grup_key];
      $tb = $grup_total_baru[$grup_key];

      $log_created_at = date('Y-m-d H:i:s');
      $sql_log = "INSERT INTO tbl_data_change_log (doc_number,so_number,product_item,source_table,action,field_name,qty_old,qty_new,price_old,price_new,total_old,total_new,profit_center,created_by,created_at)
        VALUES ('$bppbno_int','$so_number_log','$product_item_log','bppb','Edit SJ FG/OUT','qty','$ql','$qb','$price_log','$price_log','$tl','$tb','NAG','$user','$log_created_at')";
      mysql_query($sql_log);
    }
  }

  $sql	="update bppb set grade = '$txtgrade', bppbdate = '$txtbppbdate', id_buyer = '$txtbuyer',
  id_supplier = '$txtid_supplier', invno = '$txtinvno', jenis_dok = '$txtjenis_dok', jenis_trans = '$txtjenis_trans'
  where bppbno = '$id_bppb'";
  insert_log($sql,$user);
  $_SESSION['msg'] = 'Data Berhasil Disimpan. Nomor BKB : '.$bppbno_int;
  echo "<script>
  window.location.href='../forms/?mod=321ed&mode=FG&noid=$id_bppb';
  </script>";
}




?>