<?php
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode="";
$tgl_input=date('Y-m-d');

// $crinya = $_POST['cborak'];
// $raknya = $_POST['cbomutrak'];
$ItemArray = $_POST['itemchk'];
$QtyArr = $_POST['qty_mutasi'];
$rak = $_POST['namarak'];


$tahun   = substr(date('Y', strtotime($tgl_input)), 2, 2);
$bulan   = substr(date('m', strtotime($tgl_input)), 0, 2);
$nm_mut = "MUT/GACC/$bulan$tahun/";
// cari nomor terakhir khusus bulan ini; max() seluruh tabel selalu ketemu MUT/GACC/1225/... (urutan string) sehingga nomor tidak pernah naik
$sql_cari = mysql_query("select max(no_mut) urut from bpb_det where no_mut like '$nm_mut%'");
$row_cari = mysql_fetch_array($sql_cari);
$urutan = (int) substr($row_cari['urut'], strlen($nm_mut)) + 1;
$no_mutasi = $nm_mut . sprintf("%05d", $urutan);

$gagal = array(); // kumpulan pesan item yang gagal/dilewati, supaya user tahu kenapa

foreach ($ItemArray as $key => $value)
{
	$key = (int) $key; // id bpb_det, pastikan integer murni
	$qty = (float) str_replace(',', '', $QtyArr[$key]);
	$rak_tujuan = nb($rak[$key]);

	if ($qty <= 0) { continue; } // baris tidak dicentang qty-nya / tidak valid, lewati tanpa diproses

	// Ambil kondisi TERKINI baris sumber langsung dari DB (bukan dari input form),
	// pakai rumus yang SAMA PERSIS dengan yang dipakai tampilan (ajax_mut_acc.php):
	// sisa = roll_qty - qty_out (yang sudah keluar lewat bppb_det) - qty_mutasi (yang sudah termutasi sebelumnya)
	$sql_cek = "select bd.id, bd.bpbno, bd.bpbno_int, bd.id_item, bd.id_jo, bd.no_pack, bd.roll_qty,
		bd.unit, bd.bpbdate, bd.qty_mutasi,
		(select COALESCE(SUM(roll_qty),0) from bppb_det where id_bpb_det = bd.id and cancel = 'N') qty_out
		from bpb_det bd where bd.id = '$key'";
	$cek = mysql_fetch_array(mysql_query($sql_cek));

	if (!$cek) { $gagal[] = "ID $key: data tidak ditemukan"; continue; }

	if ($rak_tujuan == "") {
		$gagal[] = "$cek[id_item] ($cek[bpbno_int]): Rak Tujuan belum dipilih";
		continue;
	}

	$sisa = $cek['roll_qty'] - $cek['qty_out'] - (float) $cek['qty_mutasi'];

	if ($qty > $sisa) {
		$gagal[] = "$cek[id_item] ($cek[bpbno_int]): qty mutasi diminta $qty, sisa stok real cuma ".number_format($sisa,2);
		continue;
	}

	// UPDATE baris sumber: roll_qty TIDAK diubah (tetap jadi catatan qty asli diterima,
	// dipakai laporan lain sebagai fakta historis), yang bertambah cuma qty_mutasi.
	// Kondisi di WHERE diulang lagi sebagai guard atomik terhadap race condition /
	// submit ganda - kalau antara pengecekan di atas dan UPDATE ini ada proses lain
	// yang sudah mengurangi stok duluan, baris ini tidak akan ter-update (affected=0).
	$sql = "update bpb_det set
		qty_mutasi_old = COALESCE(qty_mutasi,0),
		qty_mutasi = COALESCE(qty_mutasi,0) + $qty
		where id='$key'
		and (roll_qty - (select COALESCE(SUM(roll_qty),0) from bppb_det where id_bpb_det = bpb_det.id and cancel = 'N') - COALESCE(qty_mutasi,0)) >= $qty";
	mysql_query($sql);
	$affected = mysql_affected_rows();
	insert_log("X".$sql, $user); // "X" supaya insert_log cuma mencatat log, tidak menjalankan ulang query-nya

	if ($affected < 1) {
		$gagal[] = "$cek[id_item] ($cek[bpbno_int]): stok berubah saat disimpan (kemungkinan dimutasi/dipakai bersamaan), silakan cek ulang";
		continue;
	}

	// Baris baru di rak tujuan: tanggal terima (bpbdate) tetap mengikuti baris sumber
	// (bukan tanggal hardcode / tanggal hari ini), tgl_mut diisi tanggal mutasi
	// sebenarnya supaya laporan periode bisa mengklasifikasikan dengan benar.
	$sql2 = "insert into bpb_det
		(bpbno,bpbno_int,id_item,id_jo,no_pack,roll_qty,unit,id_rak_loc,user,bpbdate,cancel,id_bpbdet,qty_mutasi,no_mut,tgl_mut,qty_old,qty_mutasi_old)
		values
		('$cek[bpbno]','$cek[bpbno_int]','$cek[id_item]','$cek[id_jo]','$cek[no_pack]','$qty','$cek[unit]','$rak_tujuan','$user','$cek[bpbdate]','N','$key','0','$no_mutasi','$tgl_input','0','0')";
	insert_log($sql2,$user);
}

if (count($gagal) > 0)
{
	$pesan = implode("\\n", $gagal);
	$_SESSION['msg'] = "XSebagian item tidak tersimpan:\\n".$pesan;
}
else
{
	$_SESSION['msg'] = 'Data Berhasil Disimpan';
}
echo "<script>window.location.href='../forms/?mod=mr_acc';</script>";
?>
