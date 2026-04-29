<?php
include "koneksi.php";

$start  = $_POST['start'];
$length = $_POST['length'];

$from = date('Y-m-d', strtotime($_POST['from']));
$to   = date('Y-m-d', strtotime($_POST['to']));
$tipe_inv   = $_POST['tipe_inv'];
$nama_supp  = $_POST['nama_supp'];
$nama_buyer = $_POST['nama_buyer'];

/* =========================
   WHERE DINAMIS (RAPI)
========================= */
$where = " WHERE a.tgl_memo BETWEEN '$from' AND '$to' ";

if($tipe_inv != 'ALL'){
    $where .= " AND a.jns_inv = '$tipe_inv'";
}
if($nama_supp != 'ALL'){
    $where .= " AND a.id_supplier = '$nama_supp'";
}
if($nama_buyer != 'ALL'){
    $where .= " AND a.id_buyer = '$nama_buyer'";
}

/* =========================
   QUERY UTAMA (SINGKATIN DULU)
========================= */
$sql = "select * from ((select * from (select id_det,id_h,id_supplier,id_buyer,nm_memo,tgl_memo,jns_inv,no_invoice, inv_buyer, kepada, jns_trans,jns_pengiriman,ditagihkan,curr,jatuh_tempo,dok_pendukung,supplier,buyer,nm_ctg,nm_sub_ctg,biaya, cancel,notes, status,user, date_input,approved_by,approved_date,nama_pc from (select * from (select mdet.id id_det,a.id_h,a.nm_memo,a.tgl_memo,a.jns_inv,IF(mdet.inv_vendor is null,'-',mdet.inv_vendor) inv_buyer,a.kepada,a.jns_trans,a.jns_pengiriman,IF(a.ditagihkan != 'Y','TIDAK','YA') ditagihkan,a.curr,a.jatuh_tempo, a.dok_pendukung, ms.supplier supplier, mb.supplier buyer,mdet.nm_ctg,mdet.nm_sub_ctg,format(round(sum(mdet.biaya),2),2) biaya,mdet.cancel, IF(a.no_aju is null,'-',a.no_aju) no_aju, IF(a.notes is null,'-',a.notes) notes,a.status,a.user,a.date_input,a.id_supplier,a.id_buyer,a.approved_by,a.approved_date,mp.nama_pc from memo_h a
          inner join mastersupplier ms on a.id_supplier = ms.id_supplier
          inner join mastersupplier mb on a.id_buyer = mb.id_supplier
          inner join memo_det mdet on mdet.id_h = a.id_h
          left join master_pc mp on mp.kode_pc = a.profit_center
          where mdet.cancel != 'Y' GROUP BY mdet.id order by mdet.id_h asc) a left join      
(select a.id_h idh, GROUP_CONCAT(b.no_invoice) no_invoice from memo_h a inner join memo_inv b on b.id_h = a.id_h GROUP BY a.id_h) b on b.idh = a.id_h) a) a inner join 
(select nm_memo nomemo, nm_memo memo1, no_dn,tgl_dn,'' no_alk, '' tgl_alk,nm_memo memo2, no_pv,tgl_pv pv_date, no_bankout, tgl_bankout bankout_date from dd_update_memo) b on b.nomemo = a.nm_memo) 
UNION 
(select * from (select id_det,id_h,id_supplier,id_buyer,nm_memo,tgl_memo,jns_inv,no_invoice, inv_buyer, kepada, jns_trans,jns_pengiriman,ditagihkan,curr,jatuh_tempo,dok_pendukung,supplier,buyer,nm_ctg,nm_sub_ctg,biaya, cancel,notes, status,user, date_input,approved_by,approved_date,nama_pc from (select * from (select mdet.id id_det,a.id_h,a.nm_memo,a.tgl_memo,a.jns_inv,IF(mdet.inv_vendor is null,'-',mdet.inv_vendor) inv_buyer,a.kepada,a.jns_trans,a.jns_pengiriman,IF(a.ditagihkan != 'Y','TIDAK','YA') ditagihkan,a.curr,a.jatuh_tempo, a.dok_pendukung, ms.supplier supplier, mb.supplier buyer,mdet.nm_ctg,mdet.nm_sub_ctg,format(round(sum(mdet.biaya),2),2) biaya,mdet.cancel, IF(a.no_aju is null,'-',a.no_aju) no_aju, IF(a.notes is null,'-',a.notes) notes,a.status,a.user,a.date_input,a.id_supplier,a.id_buyer,a.approved_by,a.approved_date,mp.nama_pc from memo_h a
          inner join mastersupplier ms on a.id_supplier = ms.id_supplier
          inner join mastersupplier mb on a.id_buyer = mb.id_supplier
          inner join memo_det mdet on mdet.id_h = a.id_h
          left join master_pc mp on mp.kode_pc = a.profit_center
          where mdet.cancel != 'Y' GROUP BY mdet.id order by mdet.id_h asc) a left join      
(select a.id_h idh, GROUP_CONCAT(b.no_invoice) no_invoice from memo_h a inner join memo_inv b on b.id_h = a.id_h GROUP BY a.id_h) b on b.idh = a.id_h) a) a left join 
(select * from (select nm_memo nomemo from memo_h) nm left join (select nm_memo memo1, no_dn,tgl_dn,no_alk,tgl_alk from (select * from (select b.nm_memo, a.no_dn,a.tgl_dn from tbl_debitnote_h a INNER JOIN tbl_debitnote_det b on b.no_dn = a.no_dn where b.nm_memo like '%MEMO%' and a.status != 'CANCEL' GROUP BY b.nm_memo) a left JOIN
(select b.no_ref,a.no_alk,a.tgl_alk from tbl_alokasi a INNER JOIN tbl_alokasi_detail b on b.no_alk = a.no_alk where b.no_ref like '%DN%' and a.status != 'CANCEL' GROUP BY b.no_ref) b on b.no_ref = a.no_dn) a) a on a.memo1 = nm.nomemo LEFT JOIN
(select reff_doc memo2, no_pv,pv_date,no_bankout,bankout_date from (select * from (select b.reff_doc,a.no_pv,a.pv_date from tbl_pv_h a INNER JOIN tbl_pv b on b.no_pv =  a.no_pv where b.reff_doc like '%MEMO/%' and a.status != 'CANCEL' GROUP BY b.reff_doc) a LEFT JOIN
(select a.no_bankout,a.bankout_date,b.no_reff from b_bankout_h a INNER JOIN b_bankout_det b on b.no_bankout = a.no_bankout where b.no_reff like '%PV/%' and a.status != 'CANCEL') b on b.no_reff = a.no_pv) a) b on b.memo2 = nm.nomemo) b on b.nomemo = a.nm_memo where a.nm_memo >= 'MEMO/NAG/2310/01039')) a
$where
LIMIT $start, $length
";

$query = mysql_query($sql);

/* =========================
   FORMAT DATA
========================= */
$data = array();
$no = $start + 1;

while($row = mysql_fetch_assoc($query)){

    $data[] = array(
        $no++,
        $row['nm_memo'],
        date('d M Y', strtotime($row['tgl_memo'])),
        $row['nama_pc'],
        $row['jns_inv'],
        $row['no_invoice'],
        $row['inv_buyer'],
        $row['kepada'],
        $row['jns_trans'],
        $row['jns_pengiriman'],
        $row['dok_pendukung'],
        $row['supplier'],
        $row['buyer'],
        $row['ditagihkan'],
        $row['jatuh_tempo'],
        $row['nm_ctg'],
        $row['nm_sub_ctg'],
        $row['curr'],
        $row['biaya'],
        $row['no_aju'],
        $row['notes'],
        $row['status'],
        $row['user'],
        date('d M Y', strtotime($row['date_input']))
    );
}

/* =========================
   TOTAL DATA
========================= */
$total = mysql_fetch_array(mysql_query("SELECT COUNT(*) as total FROM view_memo a $where"));

echo json_encode(array(
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $total['total'],
    "recordsFiltered" => $total['total'],
    "data" => $data
));
