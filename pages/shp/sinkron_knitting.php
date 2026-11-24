<?php
// Sinkron dari update dokumen pabean ke DB program knitting (PostgreSQL): nomor/tgl aju, nomor/tgl daftar, dan tanggal dokumen
// kalau tanggal transaksinya disesuaikan ke tgl daftar. Dipanggil dari s_upd_dok_pab.php setelah data di signalbit tersimpan.
// Dibuat supaya tidak mengganggu simpan:
//  - koneksi hanya dibuka untuk dokumen knitting (dilihat dari awalan nomor internal), dokumen lain tidak menyentuh knitting
//  - batas waktu koneksi / query pendek, dan kegagalan apa pun hanya dikembalikan sebagai peringatan (simpan di signalbit tetap jalan)
//  - semua perubahan di knitting dalam 1 transaksi (berhasil semua atau batal semua)
//  - jenis dokumen tidak disamakan (isi jenis dokumen di knitting beda arti, mis. official_out_h berisi FOB)
//  - baris yang datanya sudah sama tidak diupdate, dan pabean + tanggal diupdate dalam 1 perintah
//    (supaya trigger di knitting, mis. update_qty_out di gp_out_material_h, tidak jalan percuma / berulang)

// tabel knitting per awalan nomor internal:
// array(tabel signalbit, pola nomor internal, tabel knitting, kolom nomor, kolom aju, tgl aju, daftar, tgl daftar, tgl dokumen,
//       detail yang menyimpan salinan tgl dokumen: array(tabel, kolom id header, kolom tgl))
function peta_pabean_knitting($tbl, $no_int)
{	$peta = array(
		array('bpb',  '#^GB/(IN|RI)/#', 'bpb',                    'no_bpb',   'nomor_aju', 'tanggal_aju', 'bcno',         'bcdate',         'tanggal_bpb',     array()),
		array('bpb',  '#^GM/IN/#',      'bpb_celup',              'no_bpb',   'nomor_aju', 'tanggal_aju', 'nomor_daftar', 'tanggal_daftar', 'tanggal_bpb',     array()),
		array('bpb',  '#^S?GG/IN/#',    'gp_penerimaan_greige_h', 'no_bpb',   'nomor_aju', 'tanggal_aju', 'nomor_daftar', 'tanggal_daftar', 'tgl_bpb',         array()),
		// scrap knitting bernomor SCR + angka (SCR2604001); SCR/IN/... bukan dari knitting
		array('bpb',  '#^SCR[0-9]#',    'scraps',                 'name',     'nomor_aju', 'tanggal_aju', 'nomor_daftar', 'tanggal_daftar', 'date',            array()),
		array('bppb', '#^OFC/OUT/#',    'official_out_h',         'kode_out', 'nomor_aju', 'tanggal_aju', 'nomor_daftar', 'tanggal_daftar', 'tgl_pengeluaran', array()),
		array('bppb', '#^GG/OUT/#',     'gp_out_greige_h',        'no_bppb',  'no_aju',    'tgl_aju',     'no_bc',        'tgl_bc',         'tgl_bppb',        array()),
		array('bppb', '#^GB/OUT/#',     'gp_out_material_h',      'no_bppb',  'no_aju',    'tgl_aju',     'no_daftar',    'tgl_daftar',     'tgl_bppb',
			array(array('gp_out_material_det', 'out_material_id', 'tgl_bppb'), array('gp_out_material_det_lokasi', 'out_material_id', 'tgl_bppb'))),
	);
	foreach ($peta as $p)
	{	if ($p[0]==$tbl && preg_match($p[1], $no_int)) { return array_slice($p, 2); }
	}
	return null;
}

// tanggal Y-m-d yang valid, selain itu (kosong / 0000-00-00) jadi NULL
function tgl_knitting($tgl)
{	return (preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $tgl) && $tgl!="0000-00-00") ? $tgl : null;
}

// $tgl_dok: tanggal transaksi signalbit yang sudah disesuaikan ke tgl daftar (kosong = tanggal dokumen knitting tidak disentuh).
//           Tanggal dokumen knitting hanya dimajukan, tidak pernah dimundurkan.
// Hasil: array('status' => 'lewat' (bukan dokumen knitting) / 'ok' / 'gagal', 'cocok' => jml baris knitting bernomor itu,
//              'ubah' => jml baris header yang diupdate, 'ubah_tgl' => jml baris header yang tanggalnya dimajukan,
//              'pesan' => alasan kalau gagal, 'log' => keterangan untuk act_hist)
// $cfg hanya diisi untuk pengujian; kosong = ambil dari include/conn_knitting.php
function sinkron_pabean_knitting($tbl, $no_int, $jenis_dok, $nomor_aju, $tgl_aju, $nomor_daftar, $tgl_daftar, $tgl_dok="", $cfg=null)
{	$hasil = array('status'=>'lewat', 'cocok'=>0, 'ubah'=>0, 'ubah_tgl'=>0, 'pesan'=>'', 'log'=>'');
	$p = peta_pabean_knitting($tbl, $no_int);
	if ($p===null) { return $hasil; }
	list($t, $f_no, $f_aju, $f_taju, $f_df, $f_tdf, $f_tgl, $detail) = $p;
	$hasil['status'] = 'gagal';
	if (!function_exists('pg_connect')) { $hasil['pesan'] = 'ekstensi pgsql PHP belum aktif'; return $hasil; }
	if ($cfg===null)
	{	$file = dirname(__FILE__).'/../../include/conn_knitting.php';
		if (!file_exists($file)) { $hasil['pesan'] = 'konfigurasi koneksi knitting belum ada'; return $hasil; }
		$cfg = include $file;
	}
	if (!is_array($cfg)) { $hasil['pesan'] = 'konfigurasi koneksi knitting tidak valid'; return $hasil; }
	$cs = "";
	foreach (array('host','port','dbname','user','password','options') as $k)
	{	if (isset($cfg[$k]) && $cfg[$k]!=="") { $cs .= $k."='".addcslashes($cfg[$k], "'\\")."' "; }
	}
	$cs .= "connect_timeout=5 application_name='signalbit_update_dok_pabean'";
	$db = @pg_connect($cs, PGSQL_CONNECT_FORCE_NEW);
	if (!$db) { $hasil['pesan'] = 'koneksi ke DB knitting gagal'; return $hasil; }
	// query jangan sampai menggantung kalau tabel knitting sedang terkunci
	@pg_query($db, "set statement_timeout = 10000");
	@pg_query($db, "set lock_timeout = 5000");
	// INHOUSE tidak punya nomor aju / nomor daftar (sama dengan sinkron ke tabel gudang); isian kosong disimpan NULL
	$inh = ($jenis_dok=="INHOUSE");
	$tgl_dok = tgl_knitting($tgl_dok);
	$v = array(
		($inh || trim($nomor_aju)=="") ? null : trim($nomor_aju),
		tgl_knitting($tgl_aju),
		($inh || trim($nomor_daftar)=="") ? null : trim($nomor_daftar),
		tgl_knitting($tgl_daftar),
		$no_int,
		$tgl_dok);
	$r = @pg_query_params($db, "select count(*), min($f_tgl)::text, sum(case when $f_tgl < $2::date then 1 else 0 end)
		from $t where $f_no = $1", array($no_int, $tgl_dok));
	if (!$r) { $hasil['pesan'] = substr('cek dokumen di knitting gagal: '.pg_last_error($db), 0, 150); @pg_close($db); return $hasil; }
	$hasil['cocok'] = (int) pg_fetch_result($r, 0, 0);
	$tgl_lama = pg_fetch_result($r, 0, 1);
	$perlu_tgl = (int) pg_fetch_result($r, 0, 2);
	$log_tgl = "";
	if ($hasil['cocok']>0)
	{	$gagal = "";
		@pg_query($db, "begin");
		// pabean + tanggal dokumen dalam 1 perintah, hanya baris yang berbeda; tanggal hanya dimajukan (greatest),
		// kalau $6 kosong greatest() mengabaikannya dan tanggal tetap
		$sql = "update $t set $f_aju = $1::varchar, $f_taju = $2::date, $f_df = $3::varchar, $f_tdf = $4::date,
				$f_tgl = greatest($f_tgl, $6::date)
			where $f_no = $5 and (($f_aju, $f_taju, $f_df, $f_tdf) is distinct from ($1::varchar, $2::date, $3::varchar, $4::date)
				or $f_tgl < $6::date)";
		$r = @pg_query_params($db, $sql, $v);
		if (!$r) { $gagal = 'update knitting gagal: '.pg_last_error($db); }
		else { $hasil['ubah'] = pg_affected_rows($r); }
		// detail yang menyimpan salinan tanggal dokumen ikut dimajukan
		if ($gagal=="" && $tgl_dok!==null)
		{	foreach ($detail as $d)
			{	$r = @pg_query_params($db, "update $d[0] set $d[2] = $1::date
					where $d[1] in (select id from $t where $f_no = $2) and $d[2] < $1::date", array($tgl_dok, $no_int));
				if (!$r) { $gagal = 'update detail knitting gagal: '.pg_last_error($db); break; }
				$log_tgl .= ", $d[0].$d[2] ".pg_affected_rows($r)." baris";
			}
		}
		if ($gagal!="")
		{	@pg_query($db, "rollback"); @pg_close($db);
			$hasil['ubah'] = 0; $hasil['pesan'] = substr($gagal, 0, 150); return $hasil;
		}
		if (!@pg_query($db, "commit")) { $hasil['ubah'] = 0; $hasil['pesan'] = 'commit knitting gagal'; @pg_close($db); return $hasil; }
		$hasil['ubah_tgl'] = $perlu_tgl;
	}
	@pg_close($db);
	$hasil['status'] = 'ok';
	$hasil['log'] = "[knitting] update $t set $f_aju=".var_export($v[0],true).", $f_taju=".var_export($v[1],true)
		.", $f_df=".var_export($v[2],true).", $f_tdf=".var_export($v[3],true)
		.($tgl_dok!==null ? ", $f_tgl=greatest($f_tgl, '$tgl_dok') (sebelumnya $tgl_lama)$log_tgl" : "")
		." where $f_no=$no_int ($hasil[cocok] baris cocok, $hasil[ubah] diubah)";
	return $hasil;
}
?>
