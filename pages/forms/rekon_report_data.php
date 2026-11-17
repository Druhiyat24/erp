<?php
// Data rekonsiliasi report aksesoris (dipanggil dari ?mod=lap_data, balasan JSON, khusus user indro).
// Angka tiap laporan diambil dari query asli di file laporannya, jadi selalu mengikuti laporan itu sendiri.
// Hanya membaca: query laporan lewat rekon_query() yang menolak penulisan ke tabel biasa, dan tabel vmut_*
// Mutasi Global dibuat sebagai tabel sementara sesi ini sehingga tabel vmut_* milik laporan tidak tersentuh.
ob_start();
error_reporting(E_ERROR);
include '../../include/conn.php';
include 'fungsi.php';
@session_start();

$GLOBALS['rekon_selesai'] = false;
$GLOBALS['rekon_temp'] = array();
$GLOBALS['rekon_cap'] = array();
$GLOBALS['rekon_baris'] = 0;
$GLOBALS['rekon_mulai'] = microtime(true);

function rekon_balas($data)
{
	$GLOBALS['rekon_selesai'] = true;
	while (ob_get_level()) { ob_end_clean(); }
	header_remove();
	header('Content-Type: application/json');
	echo json_encode($data);
	exit;
}

function rekon_gagal($msg)
{
	rekon_balas(array('ok' => false, 'msg' => $msg));
}

function rekon_berhasil($tipe, $rows, $tambahan = array())
{
	rekon_balas(array_merge(array(
		'ok' => true,
		'tipe' => $tipe,
		'baris' => $GLOBALS['rekon_baris'],
		'detik' => round(microtime(true) - $GLOBALS['rekon_mulai'], 1),
		'rows' => $rows,
	), $tambahan));
}

function rekon_saat_berhenti()
{
	if (!$GLOBALS['rekon_selesai']) {
		$err = error_get_last();
		rekon_gagal('Laporan berhenti sebelum selesai'.($err ? ': '.$err['message'] : ''));
	}
}
register_shutdown_function('rekon_saat_berhenti');

// Satuan disamakan seperti di Mutasi Global, huruf besar kecil tidak dibedakan (sama dengan group by MySQL)
function rekon_unit($unit)
{
	$unit = strtoupper(trim($unit));
	$sama = array('PIECES' => 'PCS', 'KILOGRAM' => 'KGM', 'CONES' => 'CNS');
	return isset($sama[$unit]) ? $sama[$unit] : $unit;
}

function rekon_query($sql)
{
	$s = ltrim($sql);
	if (preg_match('/^drop\s+table\s+if\s+exists\s+(\S+)/i', $s, $m)) {
		return mysql_query('drop temporary table if exists '.$m[1]);
	}
	if (preg_match('/^create\s+table\s+(\S+)/i', $s, $m)) {
		$hasil = mysql_query(preg_replace('/^create\s+table/i', 'create temporary table', $s, 1));
		if (!$hasil) { rekon_gagal('Tabel sementara gagal dibuat: '.mysql_error()); }
		$GLOBALS['rekon_temp'][strtolower($m[1])] = 1;
		return $hasil;
	}
	if (preg_match('/^(alter\s+table|delete\s+from|update|insert\s+into)\s+(\S+)/i', $s, $m)) {
		if (!isset($GLOBALS['rekon_temp'][strtolower($m[2])])) { rekon_gagal('Laporan mencoba menulis ke tabel '.$m[2].', proses dihentikan.'); }
		return mysql_query($s);
	}
	if (preg_match('/^\(*\s*(select|with|show)\b/i', $s)) {
		return mysql_query($s);
	}
	rekon_gagal('Perintah laporan tidak dikenal: '.substr($s, 0, 60));
}

// Pengganti insert_log(): log aktivitas (awalan X) tidak ditulis, query tulis lain ditolak
function rekon_log($sql, $user)
{
	if (substr($sql, 0, 1) != 'X') { rekon_gagal('Laporan mencoba menulis data, proses dihentikan.'); }
}

function rekon_abaikan()
{
}

// Pengganti tampil_data() di Detail In / Detail Out: baris dijumlah per dokumen, item, dan satuan
function rekon_tampil($qry, $jml_kolom)
{
	if ($qry == '') { return; }
	$kol = $GLOBALS['rekon_kolom'];
	if ($jml_kolom != $kol['jumlah']) { rekon_gagal('Struktur kolom laporan berubah, rekonsiliasi perlu disesuaikan.'); }
	$hasil = mysql_query($qry);
	if (!$hasil) { rekon_gagal('Query laporan gagal: '.mysql_error()); }
	while ($d = mysql_fetch_array($hasil)) {
		rekon_tambah_dok($d[0], $d[$kol['item']], $d[$kol['unit']], $d[$kol['qty']]);
	}
}

function rekon_tambah_dok($dok, $item, $unit, $qty)
{
	$kunci = $dok."\t".$item."\t".rekon_unit($unit);
	if (!isset($GLOBALS['rekon_cap'][$kunci])) { $GLOBALS['rekon_cap'][$kunci] = 0; }
	$GLOBALS['rekon_cap'][$kunci] += (float) $qty;
	$GLOBALS['rekon_baris']++;
}

function rekon_baris_dok()
{
	$rows = array();
	foreach ($GLOBALS['rekon_cap'] as $kunci => $qty) {
		$k = explode("\t", $kunci);
		$rows[] = array($k[0], $k[1], $k[2], round($qty, 4));
	}
	return $rows;
}

// Pengganti query akhir Mutasi Global: hasilnya dijumlah per item dan satuan, lalu langsung dibalas
function rekon_global($sqlk)
{
	$hasil = mysql_query($sqlk);
	if (!$hasil) { rekon_gagal('Query Mutasi Global gagal: '.mysql_error()); }
	$cap = array();
	while ($d = mysql_fetch_assoc($hasil)) {
		$GLOBALS['rekon_baris']++;
		rekon_tambah_mutasi($cap, $d['id_item'], $d['unit'], $d['qtyrcv'], $d['qtyout'], $d['saldo_awal'], round($d['saldo_awal'], 2) + round($d['qtyrcv'], 2) - round($d['qtyout'], 2));
	}
	rekon_berhasil('mutasi', array_values($cap));
}

function rekon_tambah_mutasi(&$cap, $item, $unit, $in, $out, $awal, $akhir)
{
	$unit = rekon_unit($unit);
	$kunci = $item."\t".$unit;
	if (!isset($cap[$kunci])) { $cap[$kunci] = array($item, $unit, 0, 0, 0, 0); }
	$cap[$kunci][2] += (float) $in;
	$cap[$kunci][3] += (float) $out;
	$cap[$kunci][4] += (float) $awal;
	$cap[$kunci][5] += (float) $akhir;
}

// Ambil query dari lap_data_exc.php pada cabang tipe datanya
function rekon_sql_lap_data($tipe, $dari, $sampai)
{
	$src = file_get_contents('lap_data_exc.php');
	$pos = strpos($src, "\$tipe_data == '".$tipe."') { ?>");
	if ($pos === false) { rekon_gagal('Cabang '.$tipe.' tidak ketemu di lap_data_exc.php.'); }
	if (!preg_match('/^[ \t]*\$sql\s*=\s*"/m', $src, $m, PREG_OFFSET_CAPTURE, $pos)) { rekon_gagal('Query '.$tipe.' tidak ketemu.'); }
	$awal = $m[0][1] + strlen($m[0][0]);
	if (!preg_match('/";[ \t]*\r?$/m', $src, $m, PREG_OFFSET_CAPTURE, $awal)) { rekon_gagal('Akhir query '.$tipe.' tidak ketemu.'); }
	$sql = str_replace(array('$fromcri', '$tocri'), array($dari, $sampai), substr($src, $awal, $m[0][1] - $awal));
	if (strpos($sql, '$') !== false || !preg_match('/^\s*\(?\s*select\b/i', $sql)) { rekon_gagal('Query '.$tipe.' berubah, rekonsiliasi perlu disesuaikan.'); }
	return $sql;
}

// Jalankan file laporan apa adanya, dengan query lewat penjaga dan keluaran layar dibuang
function rekon_siapkan_kode($file)
{
	$kode = file_get_contents($file);
	$kode = preg_replace('/\bmysql_query\s*\(/', 'rekon_query(', $kode);
	$kode = preg_replace('/\binsert_log\s*\(/', 'rekon_log(', $kode);
	return preg_replace('/\bheader\s*\(/', 'rekon_abaikan(', $kode);
}

if (empty($_SESSION['username']) || strtolower($_SESSION['username']) !== 'indro') { rekon_gagal('Akses tidak diizinkan.'); }
session_write_close();
set_time_limit(0);
ini_set('memory_limit', '1024M');

$rekon_report = isset($_POST['report']) ? $_POST['report'] : '';

if ($rekon_report == 'item_info') {
	$ids = array();
	foreach ((array) (isset($_POST['ids']) ? $_POST['ids'] : array()) as $id) { $ids[] = (int) $id; }
	$rows = array();
	if ($ids) {
		$hasil = mysql_query("select id_item, goods_code, itemdesc from masteritem where id_item in (".implode(',', $ids).")");
		while ($d = mysql_fetch_assoc($hasil)) { $rows[$d['id_item']] = array($d['goods_code'], $d['itemdesc']); }
	}
	rekon_balas(array('ok' => true, 'rows' => $rows));
}

$rekon_dari = isset($_POST['from']) ? strtotime($_POST['from']) : false;
$rekon_sampai = isset($_POST['to']) ? strtotime($_POST['to']) : false;
if (!$rekon_dari || !$rekon_sampai || $rekon_dari > $rekon_sampai) { rekon_gagal('Periode tidak valid.'); }
$rekon_dari = date('Y-m-d', $rekon_dari);
$rekon_sampai = date('Y-m-d', $rekon_sampai);

if ($rekon_report == 'penerimaan' || $rekon_report == 'pengeluaran') {
	$rekon_tipe = $rekon_report == 'penerimaan' ? 'PENERIMAAN DETAIL' : 'PENGELUARAN DETAIL';
	$rekon_kol_dok = $rekon_report == 'penerimaan' ? 'nomor_dok' : 'bppbno_int';
	$hasil = mysql_query(rekon_sql_lap_data($rekon_tipe, $rekon_dari, $rekon_sampai));
	if (!$hasil) { rekon_gagal('Query '.$rekon_tipe.' gagal: '.mysql_error()); }
	while ($d = mysql_fetch_assoc($hasil)) {
		rekon_tambah_dok($d[$rekon_kol_dok], $d['id_item'], $d['unit'], $d['roll_qty']);
	}
	rekon_berhasil('dok', rekon_baris_dok());
}

if ($rekon_report == 'mutasi_detail') {
	$rekon_sql = rekon_sql_lap_data('MUTASI DETAIL', $rekon_dari, $rekon_sampai);
	$hasil = mysql_query($rekon_sql);
	if (!$hasil) { rekon_gagal('Query Mutasi Detail gagal: '.mysql_error()); }
	$cap = array();
	while ($d = mysql_fetch_assoc($hasil)) {
		$GLOBALS['rekon_baris']++;
		rekon_tambah_mutasi($cap, $d['id_item'], $d['unit'], $d['qty_in'], $d['qty_out'], $d['qty_awal'], $d['sisa']);
	}
	// Saldo minus per rak dicari tanpa filter tampilan laporan, karena filter itu ikut menyembunyikan baris minus
	$minus = array();
	$rekon_sql_minus = str_replace('where (qty_awal + qty_in) > 0', 'where sisa < -0.005', $rekon_sql, $rekon_ganti);
	if ($rekon_ganti == 1) {
		$hasil = mysql_query($rekon_sql_minus);
		if (!$hasil) { rekon_gagal('Query saldo minus gagal: '.mysql_error()); }
		while ($d = mysql_fetch_assoc($hasil)) {
			$minus[] = array('lokasi' => $d['lokasi'], 'id_item' => $d['id_item'], 'kode' => $d['goods_code'], 'nama' => $d['itemdesc'], 'ws' => $d['kpno'], 'unit' => $d['unit'], 'awal' => (float) $d['qty_awal'], 'in' => (float) $d['qty_in'], 'out' => (float) $d['qty_out'], 'sisa' => (float) $d['sisa']);
		}
	}
	rekon_berhasil('mutasi', array_values($cap), array('minus' => $rekon_ganti == 1 ? $minus : null));
}

if ($rekon_report == 'detail_in' || $rekon_report == 'detail_out') {
	$rekon_masuk = $rekon_report == 'detail_in';
	$GLOBALS['rekon_kolom'] = $rekon_masuk
		? array('jumlah' => 37, 'item' => 13, 'qty' => 19, 'unit' => 22)
		: array('jumlah' => 33, 'item' => 11, 'qty' => 17, 'unit' => 20);
	$_GET = array('mode' => $rekon_masuk ? 'Detail_In' : 'Detail_Out', 'mod' => $rekon_masuk ? '711' : '722', 'frexc' => $rekon_dari, 'toexc' => $rekon_sampai, 'tpexc' => 'Bahan Baku', 'clexc' => '-', 'suppexc' => '', 'dest' => 'xls');
	$rekon_kode = preg_replace('/\btampil_data\s*\(/', 'rekon_tampil(', rekon_siapkan_kode('detail_trans.php'));
	eval('?>'.$rekon_kode);
	rekon_berhasil('dok', rekon_baris_dok());
}

if ($rekon_report == 'global') {
	$_GET = array('mod' => '79', 'mode' => 'mut', 'rptid' => 'bahanbaku2', 'parfrom' => $rekon_dari, 'parto' => $rekon_sampai, 'parfromv' => $rekon_dari, 'partov' => $rekon_sampai, 'katitembb' => '-', 'dest' => 'xls');
	$rekon_kode = rekon_siapkan_kode('tampil_mutasi_new.php');
	$rekon_kode = preg_replace('/\$sql\s*=\s*rekon_query\s*\(\s*\$sqlk\s*\)/', '$sql = rekon_global($sqlk)', $rekon_kode, -1, $rekon_ganti);
	if ($rekon_ganti < 1) { rekon_gagal('Query akhir Mutasi Global tidak ketemu, rekonsiliasi perlu disesuaikan.'); }
	eval('?>'.$rekon_kode);
	rekon_gagal('Query akhir Mutasi Global tidak terjalankan.');
}

rekon_gagal('Laporan tidak dikenal.');
