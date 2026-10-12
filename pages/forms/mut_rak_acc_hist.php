<?php
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$tglinputawal = isset($_POST['tglinputawal']) && $_POST['tglinputawal'] != "" ? $_POST['tglinputawal'] : date('Y-m-d');
$tglinputakhir = isset($_POST['tglinputakhir']) && $_POST['tglinputakhir'] != "" ? $_POST['tglinputakhir'] : date('Y-m-d');
?>
<style>
/* ===== History Mutasi Rak Accessories - senada tema mutacc di mut_rak_acc.php ===== */
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
.mutacc-wrap .form-control{
  border:1px solid #dde3ea !important; border-radius:6px !important;
  box-shadow:none !important; height:30px !important;
  padding:5px 10px; font-size:12px;
}
.mutacc-wrap .form-control:focus{
  border-color:#00a65a !important;
  box-shadow:0 0 0 3px rgba(0,166,90,.12) !important;
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

.mutacc-btn-save{
  background:#00a65a; border:1px solid #00a65a; color:#fff;
  font-weight:600; font-size:12px; line-height:1.5; padding:5px 14px; border-radius:6px;
  box-shadow:0 2px 6px rgba(0,166,90,.25); transition:background .15s ease;
  display:inline-flex; align-items:center; gap:6px; text-decoration:none;
}
.mutacc-btn-save:hover, .mutacc-btn-save:focus{
  background:#00934f; border-color:#00934f; color:#fff; text-decoration:none;
}

.mutacc-btn-outline{
  background:#e5f7ee; border:1px solid #00a65a; color:#00753f;
  font-weight:600; font-size:12px; line-height:1.5; padding:5px 14px; border-radius:6px;
  transition:background .15s ease, color .15s ease;
  display:inline-flex; align-items:center; gap:6px; text-decoration:none;
}
.mutacc-btn-outline:hover, .mutacc-btn-outline:focus{
  background:#d2f0e0; border-color:#00a65a; color:#00753f; text-decoration:none;
}
</style>
<script type="text/javascript">
  // jQuery & DataTables baru dimuat di bawah index.php (setelah content.php), jadi tunggu DOMContentLoaded
  document.addEventListener('DOMContentLoaded', function() {
    $('#tbl_mutacc_hist').DataTable({
      "order": [],
      "pageLength": 10,
      "language": { "emptyTable": "Tidak ada data mutasi untuk filter ini." }
    });
  });
</script>
<div class="mutacc-wrap">

  <div class="mutacc-pagehead">
    <div class="mutacc-pageicon"><i class="fa fa-history"></i></div>
    <div>
      <h2>History Mutasi Rak Accessories</h2>
      <p>Riwayat mutasi stok antar rak gudang accessories</p>
    </div>
  </div>

  <div class="mutacc-card">
    <div class="mutacc-card-head">
      <span class="mutacc-bar"></span>
      <span class="mutacc-icon"><i class="fa fa-filter"></i></span>
      <span class="mutacc-title">Filter Tanggal Mutasi</span>
    </div>
    <form method="post" action="">
      <div class="row">
        <div class="col-md-2">
          <div class="form-group">
            <label>Tgl Mutasi (Awal)</label>
            <input type="date" class="form-control" name="tglinputawal" value="<?php echo htmlspecialchars($tglinputawal); ?>">
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <label>Tgl Mutasi (Akhir)</label>
            <input type="date" class="form-control" name="tglinputakhir" value="<?php echo htmlspecialchars($tglinputakhir); ?>">
          </div>
        </div>
        <div class="col-md-2">
          <label>&nbsp;</label>
          <div style="display:flex; gap:6px; white-space:nowrap;">
            <button type="submit" name="submitfilter" class="mutacc-btn-save"><i class="fa fa-search"></i> Search</button>
            <a href="?mod=mr_acc" class="mutacc-btn-outline"><i class="fa fa-plus"></i> Create</a>
          </div>
        </div>
      </div>
    </form>
  </div>

  <div class="mutacc-card">
    <div class="mutacc-card-head">
      <span class="mutacc-bar"></span>
      <span class="mutacc-icon"><i class="fa fa-list-alt"></i></span>
      <span class="mutacc-title">Daftar Mutasi</span>
    </div>
    <div class="mutacc-table-wrap">
      <table id="tbl_mutacc_hist" class="display responsive" style="width:100%;font-size:11px;">
        <thead>
          <tr>
            <th>Tgl Mutasi</th>
            <th>No Mutasi</th>
            <th>No BPB</th>
            <th>Kode Item</th>
            <th>Nama Item</th>
            <th>Qty</th>
            <th>Unit</th>
            <th>Rak Asal</th>
            <th>Rak Tujuan</th>
            <th>User</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $cri_tgl = "and bd.tgl_mut >= '$tglinputawal' and bd.tgl_mut <= '$tglinputakhir'";
          $sql = "select bd.id, bd.no_mut, bd.tgl_mut, bd.bpbno_int, bd.user,
            bd.id_item, mi.goods_code, mi.itemdesc,
            bd.roll_qty, bd.unit,
            concat(mr_asal.kode_rak,' ',mr_asal.nama_rak) rak_asal,
            concat(mr_tujuan.kode_rak,' ',mr_tujuan.nama_rak) rak_tujuan
            from bpb_det bd
            left join bpb_det src on src.id = bd.id_bpbdet
            left join masteritem mi on mi.id_item = bd.id_item
            left join master_rak mr_asal on mr_asal.id = src.id_rak_loc
            left join master_rak mr_tujuan on mr_tujuan.id = bd.id_rak_loc
            where bd.no_mut like 'MUT/GACC/%' $cri_tgl
            order by bd.id desc
            limit 500";
          $query = mysqli_query($con_new, $sql);
          while ($data = mysqli_fetch_array($query)) {
            $tgl = ($data['tgl_mut'] != "" && $data['tgl_mut'] != "0000-00-00") ? date('d M Y', strtotime($data['tgl_mut'])) : '-';
            echo "
            <tr>
              <td>$tgl</td>
              <td>$data[no_mut]</td>
              <td>$data[bpbno_int]</td>
              <td>$data[goods_code]</td>
              <td>$data[itemdesc]</td>
              <td>".number_format($data['roll_qty'], 2)."</td>
              <td>$data[unit]</td>
              <td>$data[rak_asal]</td>
              <td>$data[rak_tujuan]</td>
              <td>$data[user]</td>
            </tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

</div>
