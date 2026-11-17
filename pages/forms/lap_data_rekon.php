<?php
// Tampilan rekonsiliasi report (pilihan di ?mod=lap_data, khusus user indro), datanya dari rekon_report_data.php
?>
<style>
  .rekon-num { text-align: right; white-space: nowrap; }
  .rekon-pos { color: #b02a37; font-weight: 600; }
  .rekon-neg { color: #0a58ca; font-weight: 600; }
  .rekon-nol { color: #198754; font-weight: 600; }
  #rekon-ringkasan tr.rekon-acuan td { background: #f1f5ff; }
  #rekon-status-line .label { font-size: 12px; margin-right: 6px; display: inline-block; padding: 5px 8px; }
  #rekon-tabs > li > a { padding: 6px 10px; font-size: 12px; }
  .rekon-info { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px 10px; margin-bottom: 10px; font-size: 12px; }
  #rekon-box table, #rekon-hasil table { font-size: 12px; }
</style>

<div class='box' id='rekon-box' style='display:none'>
  <div class='box-header with-border'>
    <h3 class='box-title'><i class='fa fa-balance-scale'></i> Rekonsiliasi Report Aksesoris</h3>
  </div>
  <div class='box-body'>
    <p class='text-muted'>Angka tiap laporan diambil dari query asli laporannya, jadi sama dengan hasil laporan itu sendiri. Isi tanggal lalu klik <b>Proses</b>.</p>
    <div class='table-responsive'>
      <table class='table table-bordered table-condensed' id='rekon-progres'>
        <thead>
          <tr><th>Laporan</th><th style='width:180px'>Status</th><th class='rekon-num' style='width:110px'>Baris</th><th class='rekon-num' style='width:120px'>Waktu hitung</th></tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>

<div id='rekon-hasil' style='display:none'>
  <div class='box'>
    <div class='box-header with-border'>
      <h3 class='box-title'>Ringkasan <small id='rekon-periode'></small></h3>
    </div>
    <div class='box-body'>
      <div id='rekon-status-line' style='margin-bottom:10px'></div>
      <div class='table-responsive'>
        <table class='table table-bordered table-condensed' id='rekon-ringkasan'>
          <thead>
            <tr>
              <th>Laporan</th>
              <th class='rekon-num'>Qty IN</th><th class='rekon-num'>Selisih IN vs Detail In</th>
              <th class='rekon-num'>Qty OUT</th><th class='rekon-num'>Selisih OUT vs Detail Out</th>
              <th class='rekon-num'>Saldo Awal</th><th class='rekon-num'>Saldo Akhir</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <small class='text-muted'>Baris biru = acuan (Detail In dan Detail Out). Selisih = laporan dikurangi acuan.</small>
      <div class='row' style='margin-top:15px'>
        <div class='col-md-6'>
          <label>IN per satuan</label>
          <div class='table-responsive'><table class='table table-bordered table-condensed' id='rekon-unit-in'></table></div>
        </div>
        <div class='col-md-6'>
          <label>OUT per satuan</label>
          <div class='table-responsive'><table class='table table-bordered table-condensed' id='rekon-unit-out'></table></div>
        </div>
      </div>
    </div>
  </div>

  <div class='box'>
    <div class='box-header with-border'>
      <h3 class='box-title'>Penyebab Selisih &amp; Data Bermasalah</h3>
    </div>
    <div class='box-body'>
      <ul class='nav nav-tabs' id='rekon-tabs'></ul>
      <div class='tab-content' id='rekon-tab-isi' style='padding-top:12px'></div>
    </div>
  </div>
</div>

<script type='text/javascript'>
document.addEventListener('DOMContentLoaded', function () {
  var REKON_URL = 'rekon_report_data.php';
  var LAPORAN = [
    { key: 'detail_in', label: 'Detail In (mod 711)', masuk: true, keluar: false, saldo: false },
    { key: 'penerimaan', label: 'Penerimaan Detail', masuk: true, keluar: false, saldo: false },
    { key: 'detail_out', label: 'Detail Out (mod 722)', masuk: false, keluar: true, saldo: false },
    { key: 'pengeluaran', label: 'Pengeluaran Detail', masuk: false, keluar: true, saldo: false },
    { key: 'global', label: 'Mutasi Global', masuk: true, keluar: true, saldo: true },
    { key: 'mutasi_detail', label: 'Mutasi Detail', masuk: true, keluar: true, saldo: true }
  ];
  var PERBANDINGAN = [
    { id: 'in_dok', judul: 'IN: Detail In vs Penerimaan Detail', a: 'detail_in', b: 'penerimaan', jenis: 'in', dok: true, penjelasan: 'Dibandingkan per dokumen, item, dan satuan.' },
    { id: 'in_item', judul: 'IN: Penerimaan Detail vs Mutasi Detail', a: 'penerimaan', b: 'mutasi_detail', jenis: 'in', dok: false, penjelasan: 'Dibandingkan per item dan satuan.' },
    { id: 'in_mutasi', judul: 'IN: Mutasi Detail vs Mutasi Global', a: 'mutasi_detail', b: 'global', jenis: 'in', dok: false, penjelasan: 'Dibandingkan per item dan satuan.' },
    { id: 'out_dok', judul: 'OUT: Detail Out vs Pengeluaran Detail', a: 'detail_out', b: 'pengeluaran', jenis: 'out', dok: true, penjelasan: 'Dibandingkan per dokumen, item, dan satuan.' },
    { id: 'out_item', judul: 'OUT: Pengeluaran Detail vs Mutasi Detail', a: 'pengeluaran', b: 'mutasi_detail', jenis: 'out', dok: false, penjelasan: 'Dibandingkan per item dan satuan.' },
    { id: 'out_mutasi', judul: 'OUT: Mutasi Detail vs Mutasi Global', a: 'mutasi_detail', b: 'global', jenis: 'out', dok: false, penjelasan: 'Dibandingkan per item dan satuan.' }
  ];
  var D = {};
  var nama = {};
  var pengatur = {};
  var jalanKe = 0;
  var $tombol = $("button[name='submit']");

  var fmt = function (v) { return Number(v || 0).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); };
  var esc = function (s) { return $('<div>').text(s === null || s === undefined ? '' : String(s)).html(); };
  var bulat = function (v) { return Math.round((Number(v) || 0) * 100) / 100; };
  var labelDari = function (key) { return $.grep(LAPORAN, function (l) { return l.key === key; })[0].label; };

  function selisihHtml(v) {
    v = bulat(v);
    if (v === 0) { return '<span class="rekon-nol">' + fmt(0) + '</span>'; }
    return '<span class="' + (v > 0 ? 'rekon-pos' : 'rekon-neg') + '">' + (v > 0 ? '+' : '') + fmt(v) + '</span>';
  }

  function barisProgres(key) { return $('#rekon-progres tr[data-laporan="' + key + '"]'); }
  function aturStatus(key, html) { barisProgres(key).find('.rekon-status').html(html); }

  function mulaiPengatur(key) {
    var mulai = Date.now();
    var tick = function () {
      var s = Math.floor((Date.now() - mulai) / 1000);
      aturStatus(key, '<span class="label label-warning"><i class="fa fa-spinner fa-spin"></i> Menghitung ' + Math.floor(s / 60) + ':' + ('0' + (s % 60)).slice(-2) + '</span>');
    };
    tick();
    pengatur[key] = setInterval(tick, 1000);
  }

  function hentikanPengatur(key) {
    if (pengatur[key]) { clearInterval(pengatur[key]); delete pengatur[key]; }
  }

  function siapkanProgres() {
    var html = '';
    $.each(LAPORAN, function (i, l) {
      html += '<tr data-laporan="' + l.key + '"><td>' + esc(l.label) + '</td><td class="rekon-status"><span class="label label-default">Belum diproses</span></td><td class="rekon-num rekon-baris">-</td><td class="rekon-num rekon-detik">-</td></tr>';
    });
    $('#rekon-progres tbody').html(html);
  }

  function hitungLaporan(i, dari, sampai, kode) {
    if (kode !== jalanKe) { return; }
    if (i >= LAPORAN.length) {
      $tombol.prop('disabled', false);
      ambilNama(function () { tampilkanHasil(dari, sampai); });
      return;
    }
    var key = LAPORAN[i].key;
    mulaiPengatur(key);
    $.ajax({ type: 'POST', url: REKON_URL, dataType: 'json', timeout: 0, data: { report: key, from: dari, to: sampai } })
      .done(function (res) {
        hentikanPengatur(key);
        if (!res.ok) { gagal(key, res.msg); return; }
        D[key] = res;
        aturStatus(key, '<span class="label label-success"><i class="fa fa-check"></i> Selesai</span>');
        barisProgres(key).find('.rekon-baris').text(Number(res.baris).toLocaleString('id-ID'));
        barisProgres(key).find('.rekon-detik').text(res.detik + ' dtk');
        hitungLaporan(i + 1, dari, sampai, kode);
      })
      .fail(function (xhr) {
        hentikanPengatur(key);
        gagal(key, xhr.statusText || 'Koneksi terputus');
      });
  }

  function gagal(key, pesan) {
    aturStatus(key, '<span class="label label-danger" title="' + esc(pesan) + '"><i class="fa fa-times"></i> Gagal</span>');
    $tombol.prop('disabled', false);
    swal({ title: 'Laporan gagal dihitung', text: labelDari(key) + ': ' + pesan, type: 'error' });
  }

  function proses() {
    var dari = $('#datepicker1').val(), sampai = $('#datepicker2').val();
    if (!dari || !sampai) { swal({ title: 'Isi tanggal Dari dan Sampai', type: 'warning' }); return; }
    jalanKe++;
    D = {};
    $.each(pengatur, function (key) { hentikanPengatur(key); });
    $('#rekon-hasil').hide();
    siapkanProgres();
    $tombol.prop('disabled', true);
    hitungLaporan(0, dari, sampai, jalanKe);
  }

  // Peta qty per item dan satuan; jenis: in, out, awal, atau akhir
  function petaItem(key, jenis) {
    var hasil = {};
    var kolom = { 'in': 2, 'out': 3, 'awal': 4, 'akhir': 5 };
    $.each(D[key].rows, function (i, r) {
      var k = D[key].tipe === 'dok' ? r[1] + '|' + r[2] : r[0] + '|' + r[1];
      hasil[k] = (hasil[k] || 0) + (D[key].tipe === 'dok' ? r[3] : r[kolom[jenis]]);
    });
    return hasil;
  }

  function petaDok(key) {
    var hasil = {};
    $.each(D[key].rows, function (i, r) { hasil[r[0] + '|' + r[1] + '|' + r[2]] = { dok: r[0], id_item: r[1], unit: r[2], qty: r[3] }; });
    return hasil;
  }

  function total(peta) {
    var t = 0;
    $.each(peta, function (k, v) { t += v; });
    return t;
  }

  function pecahItem(k) {
    var p = k.indexOf('|');
    return { id_item: k.substring(0, p), unit: k.substring(p + 1) };
  }

  function bandingkan(e) {
    var rows = [], labelA = labelDari(e.a), labelB = labelDari(e.b);
    if (e.dok) {
      var a = petaDok(e.a), b = petaDok(e.b), kunci = $.extend({}, a, b);
      $.each(kunci, function (k, v) {
        var qa = a[k] ? a[k].qty : 0, qb = b[k] ? b[k].qty : 0;
        if (Math.abs(bulat(qa - qb)) >= 0.01) {
          rows.push({ dok: v.dok, id_item: v.id_item, unit: v.unit, qty_a: qa, qty_b: qb, selisih: qa - qb, ket: !a[k] ? 'Tidak ada di ' + labelA : (!b[k] ? 'Tidak ada di ' + labelB : '') });
        }
      });
    } else {
      var pa = petaItem(e.a, e.jenis), pb = petaItem(e.b, e.jenis);
      $.each($.extend({}, pa, pb), function (k) {
        var qa = pa[k] || 0, qb = pb[k] || 0;
        if (Math.abs(bulat(qa - qb)) >= 0.01) {
          var it = pecahItem(k);
          rows.push({ id_item: it.id_item, unit: it.unit, qty_a: qa, qty_b: qb, selisih: qa - qb, ket: pa[k] === undefined ? 'Tidak ada di ' + labelA : (pb[k] === undefined ? 'Tidak ada di ' + labelB : '') });
        }
      });
    }
    return rows;
  }

  function bandingkanSaldo() {
    var awalG = petaItem('global', 'awal'), awalD = petaItem('mutasi_detail', 'awal'), akhirG = petaItem('global', 'akhir'), akhirD = petaItem('mutasi_detail', 'akhir');
    var rows = [];
    $.each($.extend({}, awalG, awalD, akhirG, akhirD), function (k) {
      var a1 = awalG[k] || 0, a2 = awalD[k] || 0, b1 = akhirG[k] || 0, b2 = akhirD[k] || 0;
      if (Math.abs(bulat(a1 - a2)) >= 0.01 || Math.abs(bulat(b1 - b2)) >= 0.01) {
        var it = pecahItem(k);
        rows.push({ id_item: it.id_item, unit: it.unit, awal_g: a1, awal_d: a2, sel_awal: a1 - a2, akhir_g: b1, akhir_d: b2, sel_akhir: b1 - b2 });
      }
    });
    return rows;
  }

  // Nama item hanya diambil untuk baris yang berselisih
  function ambilNama(lanjut) {
    var ids = {};
    $.each(PERBANDINGAN, function (i, e) { $.each(bandingkan(e), function (j, r) { ids[r.id_item] = 1; }); });
    $.each(bandingkanSaldo(), function (j, r) { ids[r.id_item] = 1; });
    var daftar = $.map(ids, function (v, k) { return k; });
    nama = {};
    if (!daftar.length) { lanjut(); return; }
    $.ajax({ type: 'POST', url: REKON_URL, dataType: 'json', data: { report: 'item_info', ids: daftar } })
      .done(function (res) { if (res.ok) { nama = res.rows; } lanjut(); })
      .fail(function () { lanjut(); });
  }

  function tampilkanRingkasan() {
    var acuanIn = total(petaItem('detail_in', 'in')), acuanOut = total(petaItem('detail_out', 'out'));
    var nilaiIn = [], nilaiOut = [], nilaiSaldo = [], html = '';
    $.each(LAPORAN, function (i, l) {
      var qin = l.masuk ? total(petaItem(l.key, 'in')) : 0, qout = l.keluar ? total(petaItem(l.key, 'out')) : 0;
      var awal = l.saldo ? total(petaItem(l.key, 'awal')) : 0, akhir = l.saldo ? total(petaItem(l.key, 'akhir')) : 0;
      if (l.masuk) { nilaiIn.push(bulat(qin)); }
      if (l.keluar) { nilaiOut.push(bulat(qout)); }
      if (l.saldo) { nilaiSaldo.push(bulat(awal) + '/' + bulat(akhir)); }
      html += '<tr class="' + (l.key === 'detail_in' || l.key === 'detail_out' ? 'rekon-acuan' : '') + '"><td>' + esc(l.label) + '</td>'
        + '<td class="rekon-num">' + (l.masuk ? fmt(qin) : '-') + '</td>'
        + '<td class="rekon-num">' + (l.masuk && l.key !== 'detail_in' ? selisihHtml(qin - acuanIn) : '-') + '</td>'
        + '<td class="rekon-num">' + (l.keluar ? fmt(qout) : '-') + '</td>'
        + '<td class="rekon-num">' + (l.keluar && l.key !== 'detail_out' ? selisihHtml(qout - acuanOut) : '-') + '</td>'
        + '<td class="rekon-num">' + (l.saldo ? fmt(awal) : '-') + '</td>'
        + '<td class="rekon-num">' + (l.saldo ? fmt(akhir) : '-') + '</td></tr>';
    });
    $('#rekon-ringkasan tbody').html(html);
    var lencana = function (sama, teks) {
      return '<span class="label ' + (sama ? 'label-success' : 'label-danger') + '"><i class="fa ' + (sama ? 'fa-check' : 'fa-times') + '"></i> ' + teks + (sama ? ' sama' : ' tidak sama') + '</span>';
    };
    var semuaSama = function (a) { return $.grep(a, function (v) { return v !== a[0]; }).length === 0; };
    $('#rekon-status-line').html(lencana(semuaSama(nilaiIn), 'IN semua laporan') + lencana(semuaSama(nilaiOut), 'OUT semua laporan') + lencana(semuaSama(nilaiSaldo), 'Saldo Global dan Detail'));
  }

  function tabelSatuan(selektor, jenis, syarat) {
    var laporan = $.grep(LAPORAN, function (l) { return l[syarat]; });
    var peta = {}, unit = {};
    $.each(laporan, function (i, l) {
      peta[l.key] = {};
      $.each(petaItem(l.key, jenis), function (k, q) {
        var u = pecahItem(k).unit;
        peta[l.key][u] = (peta[l.key][u] || 0) + q;
        unit[u] = 1;
      });
    });
    var html = '<thead><tr><th>Satuan</th>';
    $.each(laporan, function (i, l) { html += '<th class="rekon-num">' + esc(l.label) + '</th>'; });
    html += '</tr></thead><tbody>';
    $.each($.map(unit, function (v, u) { return u; }).sort(), function (i, u) {
      var nilai = $.map(laporan, function (l) { return bulat(peta[l.key][u] || 0); });
      if ($.grep(nilai, function (v) { return v !== 0; }).length === 0) { return; }
      var sama = $.grep(nilai, function (v) { return v !== nilai[0]; }).length === 0;
      html += '<tr><td>' + esc(u || '(kosong)') + (sama ? '' : ' <i class="fa fa-exclamation-triangle text-danger" title="Tidak sama"></i>') + '</td>';
      $.each(nilai, function (j, v) { html += '<td class="rekon-num">' + fmt(v) + '</td>'; });
      html += '</tr>';
    });
    $(selektor).html(html + '</tbody>');
  }

  var kolomTeks = function (data, judul) { return { data: data, title: judul, defaultContent: '', render: function (v, t) { return t === 'display' ? esc(v) : v; } }; };
  var kolomAngka = function (data, judul) { return { data: data, title: judul, className: 'rekon-num', render: function (v, t) { return t === 'display' ? fmt(v) : v; } }; };
  var kolomSelisih = function (data, judul) { return { data: data, title: judul, className: 'rekon-num', render: function (v, t) { return t === 'display' ? selisihHtml(v) : v; } }; };
  var kolomItem = {
    data: null, title: 'Item', render: function (r, t) {
      var n = nama[r.id_item] || [r.kode || '', r.nama || ''];
      return t === 'display' ? '<b>' + esc(r.id_item) + '</b> ' + esc(n[0]) + '<br><small class="text-muted">' + esc(n[1]) + '</small>' : r.id_item + ' ' + n[0] + ' ' + n[1];
    }
  };

  function tampilkanTab() {
    if ($.fn.dataTable.tables().length) { $.fn.dataTable.tables({ api: true }).destroy(); }
    var pane = $.map(PERBANDINGAN, function (e) {
      var rows = bandingkan(e), labelA = labelDari(e.a), labelB = labelDari(e.b);
      var ta = total(petaItem(e.a, e.jenis)), tb = total(petaItem(e.b, e.jenis));
      var kolom = (e.dok ? [kolomTeks('dok', 'Dokumen')] : []).concat([kolomItem, kolomTeks('unit', 'Satuan'), kolomAngka('qty_a', labelA), kolomAngka('qty_b', labelB), kolomSelisih('selisih', 'Selisih'), kolomTeks('ket', 'Keterangan')]);
      return { id: e.id, judul: e.judul, rows: rows, kolom: kolom, info: esc(e.penjelasan) + '<br><b>' + esc(labelA) + '</b>: ' + fmt(ta) + ' &nbsp;|&nbsp; <b>' + esc(labelB) + '</b>: ' + fmt(tb) + ' &nbsp;|&nbsp; Selisih: ' + selisihHtml(ta - tb) };
    });
    pane.push({
      id: 'saldo', judul: 'Saldo: Mutasi Global vs Mutasi Detail', rows: bandingkanSaldo(),
      kolom: [kolomItem, kolomTeks('unit', 'Satuan'), kolomAngka('awal_g', 'Saldo Awal Global'), kolomAngka('awal_d', 'Saldo Awal Detail'), kolomSelisih('sel_awal', 'Selisih'), kolomAngka('akhir_g', 'Saldo Akhir Global'), kolomAngka('akhir_d', 'Saldo Akhir Detail'), kolomSelisih('sel_akhir', 'Selisih')],
      info: 'Dibandingkan per item dan satuan. Mutasi Detail tidak menampilkan baris rak yang saldo awal + IN-nya 0 atau minus, jadi baris seperti itu bisa membuat saldonya berbeda dari Mutasi Global.'
    });
    var minus = D.mutasi_detail.minus;
    pane.push({
      id: 'minus', judul: 'Saldo minus per rak', rows: minus || [],
      kolom: [kolomTeks('lokasi', 'Lokasi'), kolomItem, kolomTeks('ws', 'WS'), kolomTeks('unit', 'Satuan'), kolomAngka('awal', 'Saldo Awal'), kolomAngka('in', 'IN'), kolomAngka('out', 'OUT'), kolomSelisih('sisa', 'Saldo Akhir')],
      info: minus === null ? 'Tidak bisa dihitung karena filter di query Mutasi Detail berubah.' : 'Baris rak, item, JO, dan satuan yang saldo akhirnya minus, termasuk yang tidak tampil di laporan Mutasi Detail.'
    });

    var $tabs = $('#rekon-tabs').empty(), $isi = $('#rekon-tab-isi').empty();
    var pertama = $.grep(pane, function (p) { return p.rows.length > 0; })[0] || pane[0];
    $.each(pane, function (i, p) {
      var aktif = p === pertama;
      $tabs.append('<li class="' + (aktif ? 'active' : '') + '"><a href="#rekon-pane-' + p.id + '" data-toggle="tab">' + esc(p.judul) + ' <span class="label ' + (p.rows.length ? 'label-danger' : 'label-success') + '">' + p.rows.length + '</span></a></li>');
      $isi.append('<div class="tab-pane ' + (aktif ? 'active' : '') + '" id="rekon-pane-' + p.id + '"><div class="rekon-info">' + p.info + '</div>'
        + (p.rows.length ? '<table class="table table-bordered table-striped table-condensed" style="width:100%" id="rekon-tbl-' + p.id + '"></table>' : '<div class="rekon-nol"><i class="fa fa-check-circle"></i> Tidak ada selisih.</div>') + '</div>');
      if (p.rows.length) {
        $('#rekon-tbl-' + p.id).DataTable({
          data: p.rows, columns: p.kolom, order: [], pageLength: 10, autoWidth: false,
          language: { search: 'Cari:', lengthMenu: 'Tampil _MENU_ baris', info: '_START_-_END_ dari _TOTAL_', infoEmpty: '0 data', infoFiltered: '', zeroRecords: 'Tidak ada data', paginate: { previous: '&lsaquo;', next: '&rsaquo;' } }
        });
      }
    });
    $('#rekon-tabs a[data-toggle="tab"]').on('shown.bs.tab', function () { $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust(); });
  }

  function tampilkanHasil(dari, sampai) {
    $('#rekon-periode').text('(' + dari + ' s/d ' + sampai + ')');
    tampilkanRingkasan();
    tabelSatuan('#rekon-unit-in', 'in', 'masuk');
    tabelSatuan('#rekon-unit-out', 'out', 'keluar');
    $('#rekon-hasil').show();
    tampilkanTab();
  }

  function aturMode() {
    var rekon = $('#tipe_data').val() === 'REKONSILIASI REPORT';
    $('#rekon-box').toggle(rekon);
    if (!rekon) { $('#rekon-hasil').hide(); }
    $tombol.text(rekon ? 'Proses' : 'Export Excel');
  }

  siapkanProgres();
  $('#tipe_data').on('change', aturMode);
  $(document.form).on('submit', function (e) {
    if ($('#tipe_data').val() === 'REKONSILIASI REPORT') {
      e.preventDefault();
      proses();
    }
  });
});
</script>
