<?php
$query = mysqli_query($con_new,"SELECT * FROM tbl_caption order by id_caption asc");
while($data = mysqli_fetch_array($query))
{	$caption[$data['id_caption']] = $data['id_text']; }
$nm_company=flookup("company","mastercompany","company!=''");

$c1="Pemasukan Bulan Ini";
$c2="Pengeluaran Bulan Ini";
$c3="Pemasukan";
$c3a="Masuk";
$c4="Pengeluaran";
$c4a="Keluar";
$c5="Bahan Baku";
$c6="Mesin";
$c7="Barang Dalam Proses";
$c8="Barang Jadi";
$c9="Lap. Pemasukan";
$c10="Lap. Pengeluaran";
$c11="Mutasi";
$c12="Akun";
$cpil="Pilih";
$cmas="Masukan";
$c13="Tipe";
$c14="Klasifikasi";
$c15="Kode ";
$c16="Nama ";
$c15fg="Kode Barang Jadi";
$c16fg="Nama Barang Jadi";
$c17="Warna";
$c18="Ukuran";
$c19="Kartu Stock";
$cub="Ubah";
$chap="Hapus";
$cri="Riwayat";
$csim="Simpan";
$ch1="Master Item";
$ch2="Master Mesin";
$ch3="Master Barang Dalam Proses";
$c20="Bahan Baku & Bahan Penolong";
$c21="Barang Sisa & Scrap";
$c22="Mesin & Peralatan Kantor";
$c23="Riwayat Aktivitas";
$c24="Rubah Password";
$c25="Dokumen";
$c26="Nama Kontak";
$c27="Telp.";
$c28="Alamat";
$c29="NPWP";
$c30="Negara";
$c31="Jumlah";
$c32="Satuan";
$c33="Mata Uang";
$c34="Harga Satuan";
$c35="Keterangan";
$c36="Jam Masuk";
$c37="Berat Bersih";
$c38="Berat Kotor";
$c39="Nomor Mobil";
$c40="Nomor PO";
$c41="Nomor Invoice / Nomor SJ";
$c42="Nomor Daftar";
$c43="Tgl. Daftar";
$c46="Jenis Dokumen";
$c47="Tujuan Pemasukan";
$c48="Deskripsi";
$c49="Nilai";
$c50="Nomor Mobil";
$c51="Dikirim Ke";
$c52="Nomor BPPB";
$c53="Tgl. BPPB";
$c54="Tujuan Pengeluaran";
$c55="Dari";
$c56="Sampai";
$ctam="Tampilkan";
$c_bs="Kasbon";
$c_pcash="Petty Cash";
$c_dashboard="Dashboard";
$c_inv="Invoice";
$c_ajt="Akan Jatuh Tempo";
$c_Tljt="Telah Lewat Jatuh Tempo";
$c_detail="Detail";
$c_master="Master";
$c_bank="Bank";
$c_proses="Proses";
$c_ap="Account Payable";
$c_ar="Account Receivable";
$c_lap="Laporan";
$c_mbank="Master Bank";
$c_curr="Mata Uang";
$c_pil="Pilih";
$c_tbk="Tidak Boleh Kosong";
$c_nmbank="Nama Bank";
$c_mskan="Masukan";
$c_norek="No. Rekening";
$c_nmacc="Nama Account Bank";
$c_save="Simpan";
$c_dinv="Tgl. Invoice";
$c_ninv="No. Invoice";
$c_nofak="No. Faktur Pajak";
$c_sup="Supplier";
$c_cus="Customer";
$c_amt="Amount";
$c_dtt="Tgl. Tanda Terima";
$c_djt="Tgl. Jatuh Tempo";
$c_dbyr="Tgl. Bayar";
$c_list="List";
$c_lap="Laporan";
$c_view="Tampilkan";
$c_dti="Dari Tgl. Invoice";
$c_sti="Sampai Tgl. Invoice";
$c_byrke="Pembayaran Ke";
$cm_mkt="Marketing";
$cm_pro="Procurement";
$cm_inv="Inventory";
$cm_prd="Production";
$cm_shp="Shipping";
$cm_mstdt="Master Data";
$cm_mon="Monitoring";
$cm_hr="HR & Payroll";
$cm_fin="Finance & Accounting";
$cm_app="Approval";
$cm_usr="User Setting";
$cm_chp="Rubah Password";
$cm_logout="Log Out";
if($nm_company=="PT. Sandrafine Garment")
{	$capt_no_ord="Nomor PK"; }
else if ($nm_company=="PT. Nirwana Alabare Garment")
{	$capt_no_ord="Nomor WS"; }
else
{	$capt_no_ord="Nomor Order"; }
?>