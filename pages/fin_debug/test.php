<?php

include_once '../forms/journal_interface.php';

/*
$journal_type = array(
    '1' => 'Jurnal Penjualan',
    '2' => 'Jurnal Pembelian',
    '3' => 'Jurnal Kas &amp; Bank',
    '4' => 'Jurnal Depresiasi',
    '5' => 'Jurnal Auto-reverse',
    '6' => 'Jurnal Umum',
    '7' => 'Jurnal Closing',
);
$posting_flag = array(
    '0' => 'Parked',
    '2' => 'Posted',
);
*/

/*$jh = array(
    'period' => '07/2019', // Periode accounting (mm/yyyy)
//    'num_journal' => "200", // Nomor Jurnal (string)
    'date_journal' => '2019-07-24', // Tanggal jurnal (yyyy-mm-dd)
    'type_journal' => 2, // Tipe jurnal, refer ke $journal_type
    'reff_doc' => 'DOC/SAMPLE', // Referensi dokumen
    'fg_intercompany' => 0, // Flag transaksi intercompany (0/1)
    'id_intercompany' => "", // Id company
    'fg_post' => '0', // Posting (2), Parked(0). refer ke $posting_flag
    'date_post' => '2019-07-24 04:34:15', // Timestamp ganti status dari parked ke posting. kosongkan jika parked.
    'user_post' => 'indro', // User yang mengubah status ke posting. kosongkan jika parked.
    'dateadd' => '2019-07-24 04:34:15', // Timestamp jurnal di buat
    'useradd' => 'indro', // User yang membuat jurnal
);

$jd = array(
    array(
        'id_coa' => '13611', // id chart of account. referensi dari tabel mastercoa
        'curr' => 'IDR', // kode currency
        'id_costcenter' => '03-90-903', // id cost center, referensi dari tabel mastercostcenter
        'nm_ws' => 'FKLCTCTN100CTNBLK123',// nama/nomor workstation
        'debit' => 123456, // Nominal debit
        'credit' => 0, // Nominal kredit
        'description' => "FABRIC KNIT LACOSTE COTTON 100% COTTON BLACK 123", // Deskripsi jurnal/transaksi
        'dateadd' => '2019-07-24 04:34:15', // Timestamp jurnal di buat
        'useradd' => 'indro', // User yang membuat jurnal
    ),
    array(
        'id_coa' => '13001',
        'curr' => 'IDR',
        'id_costcenter' => '03-90-903',
        'nm_ws' => 'FKLCTCTN100CTNBLK123',
        'debit' => 0,
        'credit' => 123456,
        'description' => "FABRIC KNIT LACOSTE COTTON 100% COTTON BLACK 123",
        'dateadd' => '2019-07-24 04:34:15',
        'useradd' => 'indro',
    )
);*/

//var_dump(journal_posting($jh, $jd));
//var_dump(insert_inv_sales('EXP/EXIM-NAG/2019/00002'));
var_dump(insert_bpb_gr('GACC/IN/0619/00004'));