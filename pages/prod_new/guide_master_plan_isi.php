<?php
/* Isi panduan menu Master Plan. Dipakai bersama oleh halaman web (guide_master_plan.php)
   dan export PDF (guide_master_plan_pdf.php) supaya isinya selalu sama.
   $img  : prefix lokasi gambar (URL untuk web, path file untuk PDF)
   $pdf  : true untuk versi PDF (menyisipkan pemisah halaman) */
function guide_master_plan_isi($img, $pdf = false)
{
  $pb = $pdf ? '<pagebreak />' : '';
  // $lebar_pdf: gambar yang tinggi dikecilkan di PDF supaya tidak menyisakan halaman kosong
  $gambar = function ($file, $caption, $lebar_pdf = '100%') use ($img, $pdf) {
    $style = $pdf ? ' style="width:' . $lebar_pdf . '"' : '';
    return '<div class="figure"><img src="' . $img . $file . '"' . $style . ' alt="' . htmlspecialchars($caption) . '"><div class="caption">' . $caption . '</div></div>';
  };
  $n = function ($x) { return '<span class="num">' . $x . '</span>'; };

  ob_start();
?>
<div class="toc">
  <div class="toc-title">Daftar Isi</div>
  <ol>
    <li><a href="#tentang">Tentang Master Plan</a></li>
    <li><a href="#alur">Alur Kerja Singkat</a></li>
    <li><a href="#daftar">Halaman Daftar Master Plan</a></li>
    <li><a href="#buat">Membuat Master Plan Baru</a></li>
    <li><a href="#edit">Mengubah Master Plan (Edit)</a></li>
    <li><a href="#batal">Membatalkan &amp; Mengaktifkan Kembali</a></li>
    <li><a href="#excel">Export Excel</a></li>
    <li><a href="#faq">Tips &amp; Masalah yang Sering Terjadi</a></li>
  </ol>
</div>

<h2 id="tentang">1. Tentang Master Plan</h2>
<p><b>Master Plan</b> adalah rencana produksi sewing <b>per hari, per line</b>, untuk satu <b>WS</b> dan satu <b>Color</b>.
Di dalamnya tercatat SMV, jumlah operator (man power), jam kerja, target efisiensi, dan target output.</p>
<p>Output yang diinput line (RFT, defect, reject) di sewing, finishing, dan packing <b>dicatat terhadap Master Plan</b>.
Karena itu Master Plan harus sudah dibuat sebelum line mulai bekerja pada hari tersebut.</p>
<div class="note"><b>Cara membuka menu:</b> Modul Produksi &rarr; menu <b>Master</b> &rarr; <b>Master Plan</b>.</div>

<h2 id="alur">2. Alur Kerja Singkat</h2>
<table class="tbl steps">
  <tr><th style="width:60px">Langkah</th><th>Yang dilakukan</th></tr>
  <tr><td class="c">1</td><td>Buka menu <b>Master Plan</b>, lalu klik tombol <b>New</b>.</td></tr>
  <tr><td class="c">2</td><td>Pilih <b>Tgl Plan</b>, <b>No WS</b>, <b>Color</b>, dan <b>Line</b> (boleh lebih dari satu).</td></tr>
  <tr><td class="c">3</td><td>Isi <b>SMV</b>, <b>Man Pwr</b>, <b>Jam Kerja</b>, <b>Target Effy</b>, dan <b>Set Target</b>. Plan Day Target terhitung otomatis.</td></tr>
  <tr><td class="c">4</td><td>Klik <b>Simpan</b>, lalu baca pesan yang muncul.</td></tr>
  <tr><td class="c">5</td><td>Cek hasilnya di halaman daftar. Kalau ada angka yang salah, perbaiki lewat <b>Edit</b>.</td></tr>
  <tr><td class="c">6</td><td>Kalau WS, color, line, atau tanggalnya yang salah: <b>batalkan</b> plan tersebut (selama belum ada output), lalu buat ulang.</td></tr>
</table>

<?php echo $pb; ?>
<h2 id="daftar">3. Halaman Daftar Master Plan</h2>
<p>Halaman pertama yang terbuka adalah daftar Master Plan. Secara bawaan, daftar menampilkan plan untuk <b>tanggal hari ini</b>.</p>
<?php echo $gambar('master_plan_list.png', 'Gambar 1. Halaman daftar Master Plan'); ?>
<table class="tbl">
  <tr><th style="width:40px">No</th><th style="width:150px">Tombol / Kolom</th><th>Fungsi</th></tr>
  <tr><td class="c"><?php echo $n(1); ?></td><td>New</td><td>Membuka form untuk membuat Master Plan baru.</td></tr>
  <tr><td class="c"><?php echo $n(2); ?></td><td>Guide</td><td>Membuka panduan ini.</td></tr>
  <tr><td class="c"><?php echo $n(3); ?></td><td>From Date</td><td>Tanggal awal plan yang ingin ditampilkan.</td></tr>
  <tr><td class="c"><?php echo $n(4); ?></td><td>To Date</td><td>Tanggal akhir plan yang ingin ditampilkan.</td></tr>
  <tr><td class="c"><?php echo $n(5); ?></td><td>Tampilkan</td><td>Menampilkan daftar sesuai rentang tanggal di atas.</td></tr>
  <tr><td class="c"><?php echo $n(6); ?></td><td>Export Excel</td><td>Mengunduh daftar sesuai rentang tanggal ke file Excel. Lihat <a href="#excel">bagian 7</a>.</td></tr>
  <tr><td class="c"><?php echo $n(7); ?></td><td>Search</td><td>Mencari di semua kolom, misalnya ketik nomor WS, nama line (<i>line_24</i>), atau color.</td></tr>
  <tr><td class="c"><?php echo $n(8); ?></td><td>Act (ikon pensil)</td><td>Membuka form edit plan tersebut. Lihat <a href="#edit">bagian 5</a>.</td></tr>
  <tr><td class="c"><?php echo $n(9); ?></td><td>Kolom terakhir</td><td>Tempat ikon <b>batal</b> / <b>aktifkan kembali</b>. Ikon hanya muncul untuk plan yang belum punya output. Lihat <a href="#batal">bagian 6</a>.</td></tr>
</table>

<h3>Arti kolom di tabel</h3>
<table class="tbl">
  <tr><th style="width:150px">Kolom</th><th>Keterangan</th></tr>
  <tr><td>Tgl Plan</td><td>Tanggal produksi yang direncanakan.</td></tr>
  <tr><td>Line</td><td>Line sewing yang mengerjakan.</td></tr>
  <tr><td>WS / Buyer / Style / Color</td><td>Order yang dikerjakan.</td></tr>
  <tr><td>Jam Pengerjaan</td><td>Jam mulai kerja (Jam Kerja Awal).</td></tr>
  <tr><td>Qty Order</td><td>Total qty order WS tersebut untuk color itu (semua size).</td></tr>
  <tr><td>SMV</td><td>Standard Minute Value: waktu standar menjahit 1 pcs, dalam menit.</td></tr>
  <tr><td>Man Power</td><td>Jumlah operator di line.</td></tr>
  <tr><td>Jam Kerja</td><td>Lama jam kerja untuk plan ini.</td></tr>
  <tr><td>Plan Day</td><td>Target output harian hasil perhitungan sistem (Plan Day Target).</td></tr>
  <tr><td>Target Effy</td><td>Target efisiensi, dalam persen.</td></tr>
  <tr><td>Set Target</td><td>Target output yang ditetapkan untuk line.</td></tr>
  <tr><td>Created By</td><td>User pembuat dan waktu input. Kalau plan dibatalkan, kolom ini berisi <span class="red">CANCEL</span> dan barisnya berwarna merah.</td></tr>
</table>

<h2 id="buat">4. Membuat Master Plan Baru</h2>
<h3>Langkah 1 &ndash; Buka form</h3>
<p>Di halaman daftar, klik tombol <b>New</b>. Form kosong akan terbuka.</p>
<?php echo $gambar('master_plan_create_kosong.png', 'Gambar 2. Form Master Plan baru (masih kosong)'); ?>

<h3>Langkah 2 &ndash; Isi form</h3>
<p>Mulailah dengan memilih <b>No WS</b>. Setelah WS dipilih, Buyer dan Style terisi otomatis, daftar Color muncul, dan tabel detail order tampil di bagian bawah.</p>
<?php echo $gambar('master_plan_create_isi.png', 'Gambar 3. Contoh form yang sudah diisi', '68%'); ?>
<table class="tbl">
  <tr><th style="width:40px">No</th><th style="width:130px">Field</th><th style="width:60px">Wajib</th><th>Cara mengisi</th></tr>
  <tr><td class="c"><?php echo $n(1); ?></td><td>Nomor Plan</td><td class="c">Otomatis</td><td>Terisi sendiri dengan tanggal hari ini (format TahunBulanTanggal). Tidak perlu diisi.</td></tr>
  <tr><td class="c"><?php echo $n(2); ?></td><td>Tgl Plan</td><td class="c">Ya</td><td>Tanggal produksi. Bawaannya hari ini; klik untuk memilih tanggal lain.</td></tr>
  <tr><td class="c"><?php echo $n(3); ?></td><td>No WS #</td><td class="c">Ya</td><td>Pilih WS. Ketik sebagian nomor WS untuk mencari.</td></tr>
  <tr><td class="c"><?php echo $n(4); ?></td><td>SMV</td><td class="c">Ya</td><td>Waktu standar per pcs (menit). Pakai <b>titik</b> untuk desimal, contoh <b>7.4</b>.</td></tr>
  <tr><td class="c"><?php echo $n(5); ?></td><td>Man Pwr</td><td class="c">Ya</td><td>Jumlah operator di line.</td></tr>
  <tr><td class="c"><?php echo $n(6); ?></td><td>Jam Kerja</td><td class="c">Ya</td><td>Lama jam kerja untuk plan ini. Boleh desimal, contoh <b>0.5</b> untuk setengah jam.</td></tr>
  <tr><td class="c"><?php echo $n(7); ?></td><td>Upload Image</td><td class="c">Tidak</td><td>Gambar style (jpg/png). Pratinjau tampil di atas tombol.</td></tr>
  <tr><td class="c"><?php echo $n(8); ?></td><td>Buyer</td><td class="c">Otomatis</td><td>Terisi dari WS.</td></tr>
  <tr><td class="c"><?php echo $n(9); ?></td><td>Color</td><td class="c">Ya</td><td>Pilih warna dari order WS. Tabel detail order langsung tersaring untuk color ini.</td></tr>
  <tr><td class="c"><?php echo $n(10); ?></td><td>Plan Day Target</td><td class="c">Otomatis</td><td>Dihitung sistem dari SMV, Man Pwr, dan Jam Kerja (lihat rumus di bawah).</td></tr>
  <tr><td class="c"><?php echo $n(11); ?></td><td>Target Effy</td><td class="c">Ya</td><td>Target efisiensi dalam persen. Isi angkanya saja, contoh <b>80</b>.</td></tr>
  <tr><td class="c"><?php echo $n(12); ?></td><td>Set Target</td><td class="c">Ya</td><td>Target output yang ditetapkan untuk line. Diisi manual, tidak dihitung otomatis.</td></tr>
  <tr><td class="c"><?php echo $n(13); ?></td><td>Style</td><td class="c">Otomatis</td><td>Terisi dari WS.</td></tr>
  <tr><td class="c"><?php echo $n(14); ?></td><td>Line</td><td class="c">Ya</td><td>Pilih satu atau beberapa line sewing. <b>Setiap line yang dipilih dibuatkan Master Plan sendiri</b> dengan isian yang sama.</td></tr>
  <tr><td class="c"><?php echo $n(15); ?></td><td>Jam Kerja Awal</td><td class="c">Ya</td><td>Jam mulai kerja. Bawaannya 07:00.</td></tr>
  <tr><td class="c"><?php echo $n(16); ?></td><td>Simpan</td><td class="c">&ndash;</td><td>Menyimpan Master Plan.</td></tr>
  <tr><td class="c"><?php echo $n(17); ?></td><td>Detail Order</td><td class="c">Info</td><td>Rincian qty order per size untuk WS dan color terpilih, dengan total di baris paling bawah. Hanya informasi.</td></tr>
</table>

<div class="formula">
  <div class="formula-title">Rumus Plan Day Target</div>
  <div class="formula-main">Plan Day Target = Man Power &times; Jam Kerja &times; 60 &divide; SMV</div>
  <div>Contoh (Gambar 3): 39 &times; 8 &times; 60 &divide; 7,4 = 2.529,7 &rarr; dibulatkan menjadi <b>2.530</b>.</div>
  <div>Set Target pada contoh diisi <b>2.024</b> (sekitar 80% dari 2.530, sesuai Target Effy). Set Target tidak ikut berubah otomatis; isi sesuai keputusan target untuk line.</div>
</div>

<h3>Langkah 3 &ndash; Simpan dan baca pesannya</h3>
<p>Klik <b>Simpan</b>. Sistem memeriksa setiap line yang dipilih: kalau sudah ada plan dengan <b>tanggal, WS, color, dan line yang sama</b>, line tersebut tidak dibuat ulang. Setelah itu form kembali kosong, siap untuk input berikutnya.</p>
<?php echo $gambar('master_plan_simpan_ok.png', 'Gambar 4. Semua line berhasil disimpan', '72%'); ?>
<?php echo $gambar('master_plan_simpan_sebagian.png', 'Gambar 5. Sebagian line sudah ada', '72%'); ?>
<div class="warn"><b>Perhatikan isi pesannya, bukan hanya judulnya.</b> Judul <b>&ldquo;Gagal&rdquo;</b> muncul kalau <i>minimal satu</i> line sudah punya plan yang sama.
Line dengan keterangan <b>&ldquo;Berhasil Disimpan&rdquo;</b> tetap tersimpan. Line dengan keterangan <b>&ldquo;sudah ada&rdquo;</b> tidak dibuat ulang; kalau angkanya perlu diubah, gunakan <b>Edit</b> pada plan yang sudah ada.</div>

<h2 id="edit">5. Mengubah Master Plan (Edit)</h2>
<p>Di halaman daftar, klik ikon <b>pensil</b> di kolom Act. Field berwarna abu-abu (Nomor Plan, No WS, Buyer, Color, Style, Line) <b>tidak bisa diubah</b>.</p>
<?php echo $gambar('master_plan_edit.png', 'Gambar 6. Form edit Master Plan'); ?>
<table class="tbl">
  <tr><th style="width:40px">No</th><th style="width:150px">Field yang bisa diubah</th><th>Keterangan</th></tr>
  <tr><td class="c"><?php echo $n(1); ?></td><td>SMV</td><td rowspan="3">Plan Day Target dihitung ulang otomatis saat angka diketik.</td></tr>
  <tr><td class="c"><?php echo $n(2); ?></td><td>Man Pwr</td></tr>
  <tr><td class="c"><?php echo $n(3); ?></td><td>Jam Kerja</td></tr>
  <tr><td class="c"><?php echo $n(4); ?></td><td>Upload Image</td><td>Pilih file baru hanya kalau gambar style ingin diganti. Kalau dikosongkan, gambar lama tetap dipakai.</td></tr>
  <tr><td class="c"><?php echo $n(5); ?></td><td>Target Effy</td><td>Target efisiensi (%).</td></tr>
  <tr><td class="c"><?php echo $n(6); ?></td><td>Set Target</td><td>Target output line.</td></tr>
  <tr><td class="c"><?php echo $n(7); ?></td><td>Jam Kerja Awal</td><td>Jam mulai kerja.</td></tr>
  <tr><td class="c"><?php echo $n(8); ?></td><td>Simpan</td><td>Menyimpan perubahan. Halaman tetap di form edit dengan pesan &ldquo;Data Berhasil Di rubah&rdquo;.</td></tr>
</table>
<div class="note">Kalau yang salah adalah <b>tanggal, WS, color, atau line</b>, plan tidak bisa diedit. Batalkan plan tersebut (lihat bagian 6), lalu buat Master Plan baru.</div>

<?php echo $pb; ?>
<h2 id="batal">6. Membatalkan &amp; Mengaktifkan Kembali</h2>
<p>Pembatalan dan pengaktifan kembali dilakukan dari kolom terakhir di halaman daftar.</p>
<?php echo $gambar('master_plan_cancel.png', 'Gambar 7. Tiga keadaan plan di halaman daftar'); ?>
<table class="tbl">
  <tr><th style="width:40px">No</th><th>Arti</th></tr>
  <tr><td class="c"><?php echo $n(1); ?></td><td>Plan yang sudah <b>dibatalkan</b>: baris berwarna merah dan kolom Created By berisi <span class="red">CANCEL</span>.</td></tr>
  <tr><td class="c"><?php echo $n(2); ?></td><td>Ikon <b>&#10004; biru</b>: <b>aktifkan kembali</b> plan yang dibatalkan.</td></tr>
  <tr><td class="c"><?php echo $n(3); ?></td><td>Ikon <b>&#8856; merah</b>: <b>batalkan</b> plan.</td></tr>
  <tr><td class="c"><?php echo $n(4); ?></td><td><b>Tanpa ikon</b>: plan sudah punya output, jadi tidak bisa dibatalkan.</td></tr>
</table>
<div class="warn"><b>Klik ikon langsung diproses tanpa konfirmasi.</b> Pastikan baris yang Anda klik sudah benar (cek line, WS, dan color).</div>
<p>Aturan yang diperiksa sistem:</p>
<ul>
  <li>Plan hanya bisa dibatalkan kalau <b>belum ada output sama sekali</b> di sewing, finishing, maupun packing. Kalau sudah ada, muncul pesan <b>&ldquo;Master Plan Sudah Memiliki Output.&rdquo;</b></li>
  <li>Plan yang dibatalkan tidak bisa diaktifkan kembali kalau sudah ada plan aktif yang sama (tanggal, WS, color, dan line sama). Pesannya: <b>&ldquo;Master Plan Sudah Ada.&rdquo;</b></li>
</ul>
<?php echo $gambar('master_plan_cancel_gagal.png', 'Gambar 8. Pesan saat plan yang sudah punya output akan dibatalkan', '65%'); ?>

<h2 id="excel">7. Export Excel</h2>
<ol>
  <li>Isi <b>From Date</b> dan <b>To Date</b> di halaman daftar.</li>
  <li>Klik <b>Export Excel</b>. File akan terunduh lewat tab baru.</li>
</ol>
<p>Kolom di file Excel: No, Tgl Plan, Line, WS, Buyer, Style, Color, Qty Order, SMV, Man Power, Jam Kerja, Plan Day, Target Effy, dan Created By.</p>

<h2 id="faq">8. Tips &amp; Masalah yang Sering Terjadi</h2>
<table class="tbl">
  <tr><th style="width:230px">Masalah</th><th>Penyebab &amp; solusi</th></tr>
  <tr><td>WS tidak muncul di pilihan No WS</td><td>Yang muncul hanya WS aktif (tidak cancel) dari costing sejak 2023 dan sudah punya JO. Pastikan WS sudah dibuatkan JO dan tidak dibatalkan.</td></tr>
  <tr><td>Pilihan Color kosong</td><td>Pilih <b>No WS</b> terlebih dulu. Kalau tetap kosong, detail order (SO) WS tersebut belum memiliki color.</td></tr>
  <tr><td>Plan Day Target tidak sesuai atau kosong</td><td>Pastikan SMV, Man Pwr, dan Jam Kerja berisi angka dan memakai <b>titik</b> sebagai desimal (<b>7.4</b>, bukan 7,4). Kalau memakai koma, angka di belakang koma diabaikan.</td></tr>
  <tr><td>Muncul pesan &ldquo;Master Plan line_xx sudah ada&rdquo;</td><td>Plan untuk tanggal, WS, color, dan line itu sudah ada. Cari di halaman daftar dan gunakan <b>Edit</b> kalau angkanya perlu diubah.</td></tr>
  <tr><td>Ikon batal tidak muncul</td><td>Plan sudah punya output, jadi tidak bisa dibatalkan.</td></tr>
  <tr><td>Ingin mengganti WS, color, line, atau tanggal</td><td>Tidak bisa lewat Edit. Batalkan plan lama (selama belum ada output), lalu buat plan baru.</td></tr>
  <tr><td>Line yang dicari tidak ada di pilihan</td><td>Pilihan Line hanya berisi user dengan grup <b>SEWING</b>. Hubungi admin sistem untuk menambahkan line.</td></tr>
  <tr><td>Gambar style berubah sendiri</td><td>Gambar disimpan sesuai nama file. File dengan nama yang sama akan menimpa gambar sebelumnya, jadi beri nama file yang unik (misalnya sertakan nomor style).</td></tr>
</table>
<?php
  return ob_get_clean();
}
