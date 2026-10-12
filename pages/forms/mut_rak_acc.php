<?php
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
// $mod=$_GET['mod'];
// $akses = flookup("mut_rak","userpassword","username='$user'");
// if ($akses=="0")
// { echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
?>
<style>
/* ===== Mutasi Rak Accessories - soft card theme (senada header hijau #00a65a) ===== */
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

/* Dropdown select2 (Rak #, Item #, Rak Tujuan) - panelnya nempel ke <body>,
   jadi style ini sengaja tidak di-scope ke .mutacc-wrap */
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
</style>
<script type='text/javascript'>
  function validasi()
  {
    var dipilih = 0;
    var rak_kosong = 0;
    $('.chkclass:checked').each(function() {
      dipilih = dipilih + 1;
      var selRak = $(this).closest('tr').find('select[name^="namarak"]');
      if (selRak.length == 0 || selRak.val() == '' || selRak.val() == null) {
        rak_kosong = rak_kosong + 1;
      }
    });
    if (Number(dipilih) == 0) { swal({ title: 'Tidak Ada Data Dipilih', <?php echo $img_alert; ?> }); return false; }
    if (Number(rak_kosong) > 0) { swal({ title: 'Rak Tujuan Belum Dipilih', text: 'Pastikan setiap item yang dicentang sudah memilih Rak Tujuan.', <?php echo $img_alert; ?> }); return false; }
    // cegah klik Save dua kali: request kedua akan ditolak server (stok sudah termutasi) dan pesan errornya menimpa pesan sukses
    if (sedangSimpan) { return false; }
    sedangSimpan = true;
    $('.mutacc-btn-save').prop('disabled', true).html("<i class='fa fa-spinner fa-spin'></i> Saving...");
    return true;
  };

  var sedangSimpan = false;
  var xhrItem = null;
  var xhrDetail = null;

  function getDetail()
  {
    var id_item = $('#id_item').val();
    var id_rak_loc = $('#nama_rak').val();
    if (xhrDetail) { xhrDetail.abort(); }
    if (!id_rak_loc || !id_item) {
      $("#detail_item").html("<div class='mutacc-empty'>Pilih Rak dan Item terlebih dahulu untuk menampilkan data.</div>");
      return;
    }
    $("#detail_item").html("<div class='mutacc-empty'><i class='fa fa-spinner fa-spin'></i> Memuat data...</div>");
    xhrDetail = $.ajax({
      type: "POST",
      url: 'ajax_mut_acc.php?modeajax=viewdetail',
      data: { id_item: id_item, id_rak_loc: id_rak_loc }
    }).done(function(html) {
      $("#detail_item").html(html);
      $("#detail_item .select2").select2();
      $('#data_mut').dataTable();
    }).fail(function(xhr, status) {
      if (status !== 'abort') {
        $("#detail_item").html("<div class='mutacc-empty'>Gagal memuat data, silakan pilih ulang Rak / Item.</div>");
      }
    });
  };
</script>
 <script type="text/javascript">
  function checkAll(ele) {
       var checkboxes = document.getElementsByTagName('input');
       if (ele.checked) {
           for (var i = 0; i < checkboxes.length; i++) {
               if (checkboxes[i].type == 'checkbox' ) {
                   checkboxes[i].checked = true;
               }
           }
       } else {
           for (var i = 0; i < checkboxes.length; i++) {
               if (checkboxes[i].type == 'checkbox') {
                   checkboxes[i].checked = false;
               }
           }
       }
   }
 </script>
<script type='text/javascript'>
    function getitem() {
      var id_rak_loc = $('#nama_rak').val();
      if (xhrItem) { xhrItem.abort(); }
      // .select2() ulang (bukan trigger change) supaya tampilan ter-refresh tanpa memicu onchange getDetail()
      $("#id_item").html("<option value='' disabled selected>Memuat item...</option>").select2();
      xhrItem = $.ajax({
        type: "POST",
        url: 'ajax_mut_acc.php?modeajax=view_item',
        data: { id_rak_loc: id_rak_loc }
      }).done(function(html) {
        $("#id_item").html(html).select2();
      });
    };
  </script>

<?php
# END COPAS VALIDASI
# COPAS ADD
if($mod=="mrL")
{
?>
  <!-- Jika nantinya ada list -->
<?php } else { ?>
  <div class="mutacc-wrap">

    <div class="mutacc-pagehead">
      <div class="mutacc-pageicon"><i class="fa fa-exchange"></i></div>
      <div>
        <h2>Mutasi Rak Accessories</h2>
        <p>Pindahkan stok antar rak gudang accessories</p>
      </div>
    </div>

    <form method='post' name='form' enctype='multipart/form-data'
      action='save_rak_acc.php?mod=<?=$mod?>' onsubmit='return validasi();'>

      <div class="mutacc-card">
        <div class="mutacc-card-head">
          <span class="mutacc-bar"></span>
          <span class="mutacc-icon"><i class="fa fa-map-marker"></i></span>
          <span class="mutacc-title">Pilih Lokasi &amp; Item</span>
        </div>
        <div class='row'>
          <div class='col-md-4'>
            <div class='form-group'>
              <label>Rak #</label>
              <select class="form-control select2" name="nama_rak" id="nama_rak" data-dropup-auto="false" data-live-search="true" onchange="getitem();getDetail()">
                <option value="" disabled selected="true">Select Rak</option>
                <?php
                $nama_buyer ='';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $nama_buyer = isset($_POST['nama_buyer']) ? $_POST['nama_buyer']: null;
                }
                $sql = mysql_query("select id, CONCAT(kode_rak,' ',nama_rak) nama_rak from master_rak where nama_rak like '%ACCESSORIES%' and aktif = 'Y'");
                while ($row = mysql_fetch_array($sql)) {
                    $data = $row['nama_rak'];
                     $data2 = $row['id'];
                    if($row['nama_rak'] == $_POST['nama_rak']){
                        $isSelected = ' selected="selected"';
                    }else{
                        $isSelected = '';

                    }
                    echo '<option value="'.$data2.'"'.$isSelected.'">'. $data .'</option>';
                }?>
                </select>
            </div>
          </div>

          <div class='col-md-4'>
            <div class='form-group'>
              <label>Item #</label>
              <select class="form-control select2" name="id_item" id="id_item" data-dropup-auto="false" data-live-search="true" onchange="getDetail()">
                <option value="" disabled selected="true">Select Item</option>
                </select>
            </div>
          </div>
        </div>
      </div>

      <div class="mutacc-card">
        <div class="mutacc-card-head">
          <span class="mutacc-bar"></span>
          <span class="mutacc-icon"><i class="fa fa-list-alt"></i></span>
          <span class="mutacc-title">Detail Stok &amp; Mutasi</span>
        </div>
        <div id='detail_item'>
          <div class="mutacc-empty">Pilih Rak dan Item terlebih dahulu untuk menampilkan data.</div>
        </div>
        <div class="mutacc-footer">
          <a href="?mod=mr_acc_hist" class="mutacc-btn-back"><i class="fa fa-arrow-left"></i> Back</a>
          <button type='submit' name='submit' class='mutacc-btn-save'><i class='fa fa-save'></i> Save</button>
        </div>
      </div>

    </form>
  </div>
<?php
}
# END COPAS ADD
?>