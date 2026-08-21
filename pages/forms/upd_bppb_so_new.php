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
   $old_jenis_trans = $databppb['jenis_trans'];

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
   $grup_curr = array();

   // Grup khusus buat log perubahan jenis_trans ("Type SJ") di bawah - cuma baris
   // yang sudah approved (confirm='Y') dan BELUM diinvoice yang boleh ikut kehitung
   // sebagai penambah/pengurang nilai sales, sama seperti aturan logging FG/OUT lain.
   $grup_qty_eligible = array();
   $grup_price_eligible = array();
   $grup_curr_eligible = array();

   for ($i = 0; $i < count($idbppbs); $i++) {
     $idbppb = nb($idbppbs[$i]);
     $qty_baru = nb($qtys[$i]);

     // Ambil qty & price lama dulu sebelum ditimpa, sekalian so_number & product item-nya.
     $rowlama = mysql_fetch_array(mysql_query("
       SELECT c.qty, c.price, c.confirm, c.price_invoice, c.total_invoice, a.so_no, a.curr, e.product_item
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
     $curr     = $rowlama['curr'];
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
     $grup_curr[$grup_key] = $curr;

     if ($rowlama['confirm'] == 'Y' && $rowlama['price_invoice'] === null && $rowlama['total_invoice'] === null) {
       if (!isset($grup_qty_eligible[$grup_key])) { $grup_qty_eligible[$grup_key] = 0; }
       $grup_qty_eligible[$grup_key] += $qty_baru;
       $grup_price_eligible[$grup_key] = $price;
       $grup_curr_eligible[$grup_key] = $curr;
     }

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
      $curr_log  = $grup_curr[$grup_key];
      $tl = $grup_total_lama[$grup_key];
      $tb = $grup_total_baru[$grup_key];

      $log_created_at = date('Y-m-d H:i:s');
      $sql_log = "INSERT INTO tbl_data_change_log (doc_number,so_number,product_item,source_table,action,field_name,qty_old,qty_new,price_old,price_new,total_old,total_new,curr,profit_center,created_by,created_at)
        VALUES ('$bppbno_int','$so_number_log','$product_item_log','bppb','Edit SJ FG/OUT','qty','$ql','$qb','$price_log','$price_log','$tl','$tb','$curr_log','NAG','$user','$log_created_at')";
      mysql_query($sql_log);
    }
  }

  // Log perubahan data (dashboard) kalau klasifikasi Penjualan/Bukan Penjualan
  // (field jenis_trans / "Type SJ") berubah - dampaknya ke nilai sales sama besar
  // seperti perubahan qty/price di atas (dari kehitung jadi tidak kehitung, atau
  // sebaliknya), jadi harus ikut ke-log. "Penjualan" = jenis_trans diawali kata
  // "Penjualan" (Penjualan Ekspor/Penjualan Lokal) - selain itu (Pengiriman
  // Sample, Pengiriman Hasil Perbaikan, Retur ke Produksi, dst) dianggap bukan
  // penjualan.
  $was_sale = (strpos((string) $old_jenis_trans, 'Penjualan') === 0);
  $is_sale  = (strpos((string) $txtjenis_trans, 'Penjualan') === 0);
  if ($was_sale !== $is_sale) {
    foreach ($grup_qty_eligible as $grup_key => $qty_elig) {
      if ($qty_elig == 0) continue;
      $grup_parts = explode('|', $grup_key, 2);
      $so_number_log    = nb($grup_parts[0]);
      $product_item_log = nb($grup_parts[1]);
      $price_log  = $grup_price_eligible[$grup_key];
      $curr_log   = $grup_curr_eligible[$grup_key];
      $total_elig = $qty_elig * $price_log;

      $qty_old_j   = $was_sale ? $qty_elig   : 0;
      $qty_new_j   = $is_sale  ? $qty_elig   : 0;
      $price_old_j = $was_sale ? $price_log  : 0;
      $price_new_j = $is_sale  ? $price_log  : 0;
      $total_old_j = $was_sale ? $total_elig : 0;
      $total_new_j = $is_sale  ? $total_elig : 0;
      $old_value_j = mysql_real_escape_string((string) $old_jenis_trans);
      $new_value_j = mysql_real_escape_string((string) $txtjenis_trans);

      $log_created_at = date('Y-m-d H:i:s');
      $sql_log = "INSERT INTO tbl_data_change_log (doc_number,so_number,product_item,source_table,action,field_name,qty_old,qty_new,price_old,price_new,total_old,total_new,old_value,new_value,curr,profit_center,created_by,created_at)
        VALUES ('$bppbno_int','$so_number_log','$product_item_log','bppb','Edit Type SJ','jenis_trans','$qty_old_j','$qty_new_j','$price_old_j','$price_new_j','$total_old_j','$total_new_j','$old_value_j','$new_value_j','$curr_log','NAG','$user','$log_created_at')";
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