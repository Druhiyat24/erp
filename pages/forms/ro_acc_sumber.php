<?php
/* Sumber stok untuk Retur Accessories (RO) dari satu dokumen BPB.
   User selalu memilih BPB IN (nomor yang mereka kenal), padahal stoknya bisa sudah pindah:
   - mutasi rak  -> baris baru dengan bpbno/bpbno_int yang sama (no_mut terisi)
   - keluar (OUT) lalu kembali (RI) -> baris baru di dokumen GACC/RI
   Fungsi ini mengumpulkan semua baris bpb_det turunan dokumen tsb lewat rantai
   bpb_det -> bppb_det (OUT) -> bpb.bppbno_ri (RI) -> bpb_det RI, lalu menghitung sisa per baris.
   Dipakai oleh tampilan (ajax_ro_acc.php) dan proses simpan (save_data_ro_acc.php). */

function ro_acc_in_str($con, $arr)
{
  $out = array();
  foreach ($arr as $v) { $out[] = "'".mysqli_real_escape_string($con, $v)."'"; }
  return implode(",", $out);
}

function ro_acc_sumber($con, $bpbno_int)
{
  $doc = mysqli_real_escape_string($con, $bpbno_int);

  // baris milik dokumen yang dipilih (termasuk hasil mutasi rak, karena menyalin bpbno_int)
  $asal = array();
  $q = mysqli_query($con, "select id from bpb_det where bpbno_int = '$doc' and coalesce(cancel,'N') != 'Y'");
  while ($r = mysqli_fetch_assoc($q)) { $asal[$r['id']] = 'DOC'; }

  // telusuri RI turunan, bisa berlapis (RI yang keluar lagi lalu di-RI lagi)
  $frontier = array_keys($asal);
  for ($hop = 0; $hop < 5 && count($frontier) > 0; $hop++)
  {
    // id_bpb_det bertipe varchar: dibandingkan sebagai string supaya index-nya terpakai
    $out_pair = array();
    $q = mysqli_query($con, "select distinct bppbno, id_item from bppb_det
      where id_bpb_det in (".ro_acc_in_str($con, $frontier).") and cancel = 'N'");
    while ($r = mysqli_fetch_assoc($q)) { $out_pair[$r['bppbno'].'|'.$r['id_item']] = $r['bppbno']; }
    if (count($out_pair) == 0) { break; }

    $q = mysqli_query($con, "select bd.id, b.bppbno_ri, bd.id_item
      from bpb b inner join bpb_det bd on bd.bpbno = b.bpbno and bd.id_item = b.id_item
      where b.bppbno_ri in (".ro_acc_in_str($con, array_unique(array_values($out_pair))).")
      and b.bpbno_int like 'GACC/RI%' and coalesce(bd.cancel,'N') != 'Y'");
    $frontier = array();
    while ($r = mysqli_fetch_assoc($q))
    {
      if (!isset($out_pair[$r['bppbno_ri'].'|'.$r['id_item']])) { continue; }
      if (isset($asal[$r['id']])) { continue; }
      $asal[$r['id']] = 'RI';
      $frontier[] = $r['id'];
    }
  }

  if (count($asal) == 0) { return array(); }

  $ids = array_keys($asal);
  $out = array();
  $q = mysqli_query($con, "select id_bpb_det, sum(roll_qty) qty from bppb_det
    where id_bpb_det in (".ro_acc_in_str($con, $ids).") and cancel = 'N' group by id_bpb_det");
  while ($r = mysqli_fetch_assoc($q)) { $out[$r['id_bpb_det']] = (float) $r['qty']; }

  $ws = array();
  $rows = array();
  $q = mysqli_query($con, "select bd.id, bd.bpbno, bd.bpbno_int, bd.no_mut, bd.id_item, bd.id_jo, bd.roll_qty,
      bd.qty_mutasi, bd.unit, bd.id_rak_loc, concat(mr.kode_rak,' ',mr.nama_rak) rak, mi.goods_code, mi.itemdesc
    from bpb_det bd
    left join master_rak mr on mr.id = bd.id_rak_loc
    left join masteritem mi on mi.id_item = bd.id_item
    where bd.id in (".implode(",", array_map('intval', $ids)).")
    order by bd.id_item, bd.id");
  while ($r = mysqli_fetch_assoc($q))
  {
    $qty_out = isset($out[$r['id']]) ? $out[$r['id']] : 0;
    $r['qty_out'] = $qty_out;
    $r['sisa'] = round((float) $r['roll_qty'] - $qty_out - (float) $r['qty_mutasi'], 4);
    if ($asal[$r['id']] == 'RI') { $r['asal'] = 'RI'; }
    else if ($r['no_mut'] != '') { $r['asal'] = 'MUTASI'; }
    else { $r['asal'] = 'BPB'; }
    $ws[$r['id_jo']] = '';
    $rows[$r['id']] = $r;
  }

  if (count($ws) > 0)
  {
    $q = mysqli_query($con, "select jd.id_jo, ac.kpno from jo_det jd
      inner join so on jd.id_so = so.id inner join act_costing ac on so.id_cost = ac.id
      where jd.id_jo in (".ro_acc_in_str($con, array_keys($ws)).") and jd.cancel = 'N' group by jd.id_jo");
    while ($r = mysqli_fetch_assoc($q)) { $ws[$r['id_jo']] = $r['kpno']; }
    foreach ($rows as $id => $r) { $rows[$id]['kpno'] = $ws[$r['id_jo']]; }
  }

  return $rows;
}
