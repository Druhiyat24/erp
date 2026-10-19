<?php
// Cancel satu baris mutasi rak accessories (dipanggil dari ?mod=mr_acc_hist, balasan JSON).
// Baris tujuan dinolkan (qty asli disimpan di qty_old, cancel='Y') dan qty_mutasi baris asal dikembalikan,
// sama dengan cara cancel manual sebelumnya. Tidak bisa dicancel kalau stok hasil mutasi sudah dimutasi lagi
// atau sudah dikeluarkan (OUT/retur), karena baris tujuan bisa jadi minus.
include '../../include/conn.php';
include 'fungsi.php';
session_start();
header('Content-Type: application/json');

function balas($ok, $msg)
{
	echo json_encode(array('ok' => $ok, 'msg' => $msg));
	exit;
}

if (empty($_SESSION['username'])) { balas(false, 'Sesi habis, silakan login ulang.'); }
$user = $_SESSION['username'];
$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
if ($id <= 0) { balas(false, 'Data mutasi tidak valid.'); }

$mut = mysql_fetch_array(mysql_query("select d.id, d.no_mut, d.id_item, d.roll_qty, d.cancel, d.tgl_mut, d.id_bpbdet,
	coalesce(d.qty_mutasi,0) qty_mutasi,
	(select count(*) from bppb_det o where o.id_bpb_det = cast(d.id as char) and o.cancel = 'N') jml_out,
	s.id id_asal, coalesce(s.qty_mutasi,0) qty_mutasi_asal
	from bpb_det d left join bpb_det s on s.id = d.id_bpbdet
	where d.id = '$id' and d.no_mut like 'MUT/GACC/%'"));

if (!$mut) { balas(false, 'Data mutasi tidak ditemukan.'); }
if ($mut['cancel'] == 'Y') { balas(false, "Mutasi $mut[no_mut] sudah dibatalkan."); }
if ($mut['tgl_mut'] == '' || $mut['tgl_mut'] == '0000-00-00') { balas(false, 'Mutasi alur lama tidak bisa dibatalkan dari menu ini.'); }
if ($mut['qty_mutasi'] > 0) { balas(false, "Mutasi $mut[no_mut] tidak bisa dibatalkan: stok hasil mutasi ini sudah dimutasi lagi ke rak lain."); }
if ($mut['jml_out'] > 0) { balas(false, "Mutasi $mut[no_mut] tidak bisa dibatalkan: stok hasil mutasi ini sudah dikeluarkan (OUT/retur)."); }
if (!$mut['id_asal'] || $mut['qty_mutasi_asal'] < $mut['roll_qty']) { balas(false, "Mutasi $mut[no_mut] tidak bisa dibatalkan: data rak asal tidak sesuai, hubungi IT."); }

$qty = $mut['roll_qty'];
$id_asal = (int) $mut['id_asal'];

mysql_query("START TRANSACTION");
// 1) baris tujuan dinolkan dulu (syarat diulang sebagai guard atomik) supaya stoknya tidak bisa terpakai lagi
$sql1 = "update bpb_det set qty_old = roll_qty, roll_qty = 0, cancel = 'Y'
	where id = '$id' and cancel = 'N' and roll_qty = $qty and coalesce(qty_mutasi,0) = 0
	and not exists (select 1 from bppb_det o where o.id_bpb_det = '$id' and o.cancel = 'N')";
mysql_query($sql1);
$ok1 = mysql_affected_rows();
// 2) stok rak asal dikembalikan
$sql2 = "update bpb_det set qty_mutasi_old = qty_mutasi, qty_mutasi = qty_mutasi - $qty
	where id = '$id_asal' and qty_mutasi >= $qty";
mysql_query($sql2);
$ok2 = mysql_affected_rows();

if ($ok1 == 1 && $ok2 == 1)
{
	mysql_query("COMMIT");
	insert_log("X".$sql1, $user); // "X" supaya insert_log cuma mencatat log, tidak menjalankan ulang query-nya
	insert_log("X".$sql2, $user);
	balas(true, "Mutasi $mut[no_mut] berhasil dibatalkan, stok kembali ke rak asal.");
}
mysql_query("ROLLBACK");
balas(false, "Mutasi $mut[no_mut] gagal dibatalkan: data berubah saat diproses, silakan muat ulang halaman.");
