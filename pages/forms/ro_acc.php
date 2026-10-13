<?php
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$mode_ro = isset($_GET['mode']) ? preg_replace('/[^A-Za-z_]/', '', $_GET['mode']) : '';
if ($mode_ro == '') { $mode_ro = 'Bahan_Baku'; }
$rodate = date('d M Y');
$status_kb_cri = ($st_company=="KITE") ? "Status KITE In" : "Status KB Out";
?>
<style>
/* ===== Retur Accessories - tema sama dengan Mutasi Rak Accessories (mut_rak_acc.php) ===== */
.mutacc-wrap{ width:100%; }

.mutacc-pagehead{
  display:flex; align-items:center; gap:14px;
  margin:4px 0 20px;
}
.mutacc-pagehead .mutacc-pageicon{
  width:46px; height:46px; border-radius:12px;
  background:#00a65a; color:#fff;
  display:flex; align-items:center; justify-content:center;
  font-size:19px; flex:0 0 auto;
  box-shadow:0 4px 10px rgba(0,166,90,.25);
}
.mutacc-pagehead h2{
  margin:0; font-size:19px; font-weight:700; color:#1e293b; line-height:1.25;
}
.mutacc-pagehead p{
  margin:1px 0 0; font-size:12.5px; color:#8a97a6;
}

.mutacc-card{
  background:#fff; border:1px solid #eef1f4; border-radius:12px;
  box-shadow:0 1px 2px rgba(15,23,42,.04), 0 1px 10px rgba(15,23,42,.03);
  padding:18px 20px 20px; margin-bottom:20px;
}
.mutacc-card-head{
  display:flex; align-items:center; gap:10px;
  padding-bottom:12px; margin-bottom:16px;
  border-bottom:1px solid #f1f5f9;
}
.mutacc-card-head .mutacc-bar{
  width:3px; height:16px; border-radius:2px; background:#00a65a; flex:0 0 auto;
}
.mutacc-card-head .mutacc-icon{
  width:26px; height:26px; border-radius:7px;
  background:#e5f7ee; color:#00a65a;
  display:flex; align-items:center; justify-content:center;
  font-size:12.5px; flex:0 0 auto;
}
.mutacc-card-head span.mutacc-title{
  font-size:14px; font-weight:700; color:#1e293b;
}
.mutacc-card-head small{
  margin-left:auto; font-size:11.5px; color:#9aa5b1;
}

.mutacc-wrap label{
  font-size:12.5px; font-weight:600; color:#51606f; margin-bottom:5px;
}
.mutacc-wrap .form-control,
.mutacc-wrap .select2-container--default .select2-selection--single{
  border:1px solid #dde3ea !important; border-radius:6px !important;
  box-shadow:none !important; height:30px !important; font-size:12px;
}
.mutacc-wrap .form-control{ padding:5px 10px; }
.mutacc-wrap .form-control[readonly]{ background:#f8fafc; }
.mutacc-wrap .select2-container--default .select2-selection--single .select2-selection__rendered{
  line-height:28px !important; color:#334155; font-size:12px;
}
.mutacc-wrap .select2-container--default .select2-selection--single .select2-selection__arrow{
  height:28px !important;
}
.mutacc-wrap .form-control:focus,
.mutacc-wrap .select2-container--focus .select2-selection--single{
  border-color:#00a65a !important;
  box-shadow:0 0 0 3px rgba(0,166,90,.12) !important;
}

.mutacc-empty{
  text-align:center; color:#9aa5b1; font-size:12.5px; padding:26px 10px;
}

.mutacc-table-wrap table.dataTable{ border-collapse:separate !important; border-spacing:0; }
.mutacc-table-wrap table.dataTable thead th{
  background:#f8fafc; color:#6b7684; font-weight:700;
  font-size:10.5px; text-transform:uppercase; letter-spacing:.4px;
  border-bottom:1px solid #eef1f4 !important; border-top:none !important;
  padding:10px 8px !important; white-space:nowrap;
}
.mutacc-table-wrap table.dataTable tbody td{
  padding:9px 8px !important; font-size:12px; color:#334155;
  border-top:1px solid #f4f6f8 !important; vertical-align:middle;
}
.mutacc-table-wrap table.dataTable tbody tr:hover{ background:#f8fafc; }
.mutacc-table-wrap .form-control{ padding:4px 8px; }

.mutacc-footer{
  display:flex; justify-content:flex-end; align-items:center;
  gap:6px; margin-top:18px; padding-top:16px; border-top:1px solid #f1f5f9;
}
.mutacc-btn-save, .mutacc-btn-back{
  font-weight:600; font-size:12px; line-height:1.5; padding:5px 14px; border-radius:6px;
  display:inline-flex; align-items:center; gap:6px; text-decoration:none;
  transition:background .15s ease;
}
.mutacc-btn-save{
  background:#00a65a; border:1px solid #00a65a; color:#fff;
  box-shadow:0 2px 6px rgba(0,166,90,.25);
}
.mutacc-btn-save:hover, .mutacc-btn-save:focus{
  background:#00934f; border-color:#00934f; color:#fff; text-decoration:none;
}
.mutacc-btn-back{
  background:#eef1f4; border:1px solid #d5dbe3; color:#475569;
}
.mutacc-btn-back:hover, .mutacc-btn-back:focus{
  background:#e2e7ed; border-color:#c7cfd9; color:#334155; text-decoration:none;
}

/* Dropdown select2 - panelnya nempel ke <body>, jadi style ini sengaja tidak di-scope ke .mutacc-wrap */
.select2-dropdown{
  border-color:#dde3ea !important; border-radius:8px; overflow:hidden;
  box-shadow:0 8px 20px rgba(15,23,42,.08);
}
.select2-search--dropdown{ background:#f4fbf7; padding:8px; }
.select2-search--dropdown .select2-search__field{
  border:1px solid #dde3ea !important; border-radius:6px; padding:5px 8px;
}
.select2-search--dropdown .select2-search__field:focus{
  border-color:#00a65a !important; outline:none;
}
.select2-results__options{ background:#f4fbf7; }
.select2-results__option{ padding:6px 10px; font-size:12px; }
.select2-results__option--highlighted[aria-selected]{
  background-color:#00a65a !important; color:#fff !important;
}
.select2-results__option[aria-selected="true"]{
  background-color:#e5f7ee !important; color:#00753f !important; font-weight:600;
}

/* tambahan khusus retur */
.roacc-badge{
  display:inline-block; font-size:10px; font-weight:700; line-height:1.4;
  padding:1px 6px; border-radius:4px; margin-right:4px;
}
.roacc-badge.bpb{ background:#eef1f4; color:#475569; }
.roacc-badge.mut{ background:#e8f0fe; color:#1d4ed8; }
.roacc-badge.ri{ background:#e5f7ee; color:#00753f; }
.mutacc-table-wrap .roacc-num{ text-align:right; font-weight:600; white-space:nowrap; }
.mutacc-table-wrap .roacc-qty{ width:110px; }
.roacc-total{ margin-right:auto; font-size:12px; color:#51606f; }
.roacc-total b{ color:#00753f; font-size:13px; }
</style>
<script type="text/javascript">
  var sedangSimpan = false;
  var xhrBpb = null, xhrDetail = null;

  function validasi()
  {
    var dipilih = 0, lebih = 0;
    $('.roacc-qty').each(function() {
      var v = Number(this.value);
      if (v > 0) {
        dipilih++;
        if (v > Number($(this).data('sisa'))) { lebih++; }
      }
    });
    if (!$('#cbosj').val()) { swal({ title: 'Nomor BPB Tidak Boleh Kosong', <?php echo $img_alert; ?> }); return false; }
    if (dipilih == 0) { swal({ title: 'Qty RO Belum Diisi', <?php echo $img_alert; ?> }); return false; }
    if (lebih > 0) { swal({ title: 'Qty RO Melebihi Sisa di Rak', <?php echo $img_alert; ?> }); return false; }
    if (!$('#cbojenisdok').val()) { swal({ title: 'Jenis Dokumen Tidak Boleh Kosong', <?php echo $img_alert; ?> }); return false; }
    if (!$('#cbodefect').val()) { swal({ title: 'Jenis Defect Tidak Boleh Kosong', <?php echo $img_alert; ?> }); return false; }
    if (!$('#cbojenistrans').val()) { swal({ title: 'Jenis Pengeluaran Tidak Boleh Kosong', <?php echo $img_alert; ?> }); return false; }
    // cegah klik Save dua kali
    if (sedangSimpan) { return false; }
    sedangSimpan = true;
    $('.mutacc-btn-save').prop('disabled', true).html("<i class='fa fa-spinner fa-spin'></i> Saving...");
    return true;
  }

  function hitungTotal()
  {
    var total = 0;
    $('.roacc-qty').each(function() { total += Number(this.value) || 0; });
    $('#roacc_total').text(total.toLocaleString('en-US', { maximumFractionDigits: 4 }));
  }

  function getListBpb(tgl)
  {
    if (xhrBpb) { xhrBpb.abort(); }
    $('#cbosj').html("<option value='' disabled selected>Memuat BPB...</option>").select2();
    resetDetail();
    xhrBpb = $.ajax({
      type: "POST",
      url: 'ajax2.php?modeajax=cari_list_bpb_acc',
      data: { cri_item: tgl, mode: '<?php echo $mode_ro; ?>' }
    }).done(function(html) {
      $('#cbosj').html(html).select2();
    });
  }

  function resetDetail()
  {
    if (xhrDetail) { xhrDetail.abort(); }
    $('#detail_item').html("<div class='mutacc-empty'>Pilih Filter Tgl BPB dan Nomor BPB terlebih dahulu untuk menampilkan data.</div>");
    hitungTotal();
  }

  function getDetail(bpbno)
  {
    if (!bpbno) { resetDetail(); return; }
    if (xhrDetail) { xhrDetail.abort(); }
    $('#detail_item').html("<div class='mutacc-empty'><i class='fa fa-spinner fa-spin'></i> Memuat data...</div>");
    xhrDetail = $.ajax({
      type: "POST",
      url: 'ajax_ro_acc.php?modeajax=view_sumber',
      data: { bpbno: bpbno }
    }).done(function(html) {
      $('#detail_item').html(html);
      // paging dimatikan: input di halaman lain DataTables tidak ikut terkirim saat submit
      $('#tbl_ro_acc').DataTable({
        "paging": false,
        "order": [],
        "language": { "emptyTable": "Tidak ada sisa stok dari BPB ini (semua sudah keluar / diretur)." }
      });
      hitungTotal();
    }).fail(function(xhr, status) {
      if (status !== 'abort') { $('#detail_item').html("<div class='mutacc-empty'>Gagal memuat data, silakan pilih ulang Nomor BPB.</div>"); }
    });

    if (!$('#cbodefect').val()) {
      $.ajax({ type: "POST", url: 'ajax2.php?modeajax=cari_defect2', data: { cri_item: bpbno } })
        .done(function(html) { $('#cbodefect').html(html).select2(); });
    }
  }

  function getTujuan(kode)
  {
    $.ajax({ type: "POST", url: 'ajax.php?modeajax=cari_tujuan', data: { cri_item: kode } })
      .done(function(html) { $('#cbotujuan').html(html).select2(); });
  }

  // jQuery & plugin baru dimuat di bawah index.php (setelah content.php)
  document.addEventListener('DOMContentLoaded', function() {
    $(document).on('input change', '.roacc-qty', hitungTotal);
  });
</script>

<div class="mutacc-wrap">

  <div class="mutacc-pagehead">
    <div class="mutacc-pageicon"><i class="fa fa-undo"></i></div>
    <div>
      <h2>Retur Accessories</h2>
      <p>Retur barang accessories ke supplier dari stok BPB</p>
    </div>
  </div>

  <form method="post" name="form" action="save_data_ro_acc.php?mode=<?php echo $mode_ro; ?>" onsubmit="return validasi();">

    <div class="mutacc-card">
      <div class="mutacc-card-head">
        <span class="mutacc-bar"></span>
        <span class="mutacc-icon"><i class="fa fa-file-text-o"></i></span>
        <span class="mutacc-title">Dokumen Retur</span>
      </div>
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label>Filter Tgl BPB *</label>
            <input type="text" id="datepicker1" class="form-control" autocomplete="off" onkeydown="return false" name="txttglcut"
              placeholder="Pilih tanggal BPB" onchange="getListBpb(this.value)">
          </div>
          <div class="form-group">
            <label>Nomor BPB *</label>
            <select class="form-control select2" style="width:100%;" name="txtsjno" id="cbosj" onchange="getDetail(this.value)">
              <option value="" disabled selected>Pilih Filter Tgl BPB dulu</option>
            </select>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label>Tgl RO *</label>
            <input type="text" id="datepicker2" class="form-control" autocomplete="off" onkeydown="return false" name="txtbpbdate" value="<?php echo $rodate; ?>">
          </div>
          <div class="form-group">
            <label>Jenis Defect *</label>
            <select class="form-control select2" style="width:100%;" name="txtdefect" id="cbodefect">
              <option value="" disabled selected>Pilih Jenis Defect</option>
            </select>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label>Jenis Dokumen *</label>
            <select class="form-control select2" style="width:100%;" name="txtstatus_kb" id="cbojenisdok" onchange="getTujuan(this.value)">
              <?php IsiCombo("select nama_pilihan isi,nama_pilihan tampil from masterpilihan where kode_pilihan='$status_kb_cri' order by nama_pilihan", '', 'Pilih Jenis Dokumen'); ?>
            </select>
          </div>
          <div class="form-group">
            <label>Tujuan Pemasukan</label>
            <select class="form-control select2" style="width:100%;" id="cbotujuan" name="txttujuan"></select>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label>Jenis Pengeluaran *</label>
            <select class="form-control select2" style="width:100%;" name="txtjenis_trans" id="cbojenistrans">
              <?php IsiCombo("select nama_trans isi,nama_trans tampil from mastertransaksi where jenis_trans='OUT' and jns_gudang='FACC' order by id", '', 'Pilih Jenis Pengeluaran'); ?>
            </select>
          </div>
        </div>
      </div>
    </div>

    <div class="mutacc-card">
      <div class="mutacc-card-head">
        <span class="mutacc-bar"></span>
        <span class="mutacc-icon"><i class="fa fa-list-alt"></i></span>
        <span class="mutacc-title">Stok Tersedia untuk Diretur</span>
        <small>Termasuk stok hasil mutasi rak &amp; RI dari BPB ini</small>
      </div>
      <div id="detail_item">
        <div class="mutacc-empty">Pilih Filter Tgl BPB dan Nomor BPB terlebih dahulu untuk menampilkan data.</div>
      </div>
      <div class="mutacc-footer">
        <span class="roacc-total">Total Qty RO: <b id="roacc_total">0</b></span>
        <a href="?mod=21v&mode=<?php echo $mode_ro; ?>" class="mutacc-btn-back"><i class="fa fa-arrow-left"></i> Back</a>
        <button type="submit" name="submit" class="mutacc-btn-save"><i class="fa fa-save"></i> Save</button>
      </div>
    </div>

  </form>
</div>
