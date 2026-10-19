<?php
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

if ($excel=="N")
{ echo "<a href='stock_opname_excel.php?mode=$mode' target='_blank'>Save To Excel</a></br>"; }

$rs=mysql_fetch_array(mysql_query("select * from mastercompany"));
  $nm_company=$rs['company'];
  $isSandrafine = ($nm_company=="PT. Sandrafine Garment");
  $showRiwayat  = ($mode=="FG" || !$isSandrafine);

// jumlah kolom sebelum kolom Stock (untuk colspan footer)
if ($mode=="FG") { $stockIdx = 8; }
else { $stockIdx = 9 + ($isSandrafine ? 1 : 0); }
?>

<div class='box'>
  <div class='box-body'>
    <table id='example' class='display displayresponsive'>
      <thead>
        <tr>
          <th style='width: 5%;'>No</th>
          <?php if ($mode=="FG") { ?>
          <th>Style / Model</th>
          <th>No. WS</th>
          <?php } ?>
          <th>Kategori</th>
          <th>ID Item</th>
          <th style='width: 10%;'>Kode Barang</th>
          <?php if ($mode!="FG") { ?>
          <th>Nama Barang</th>
          <?php } ?>
          <th>Warna</th>
          <th>Ukuran</th>
          <?php if ($mode!="FG" and $isSandrafine) { ?>
          <th>Stock Card</th>
          <?php } ?>
          <?php if ($mode!="FG") { ?>
          <th>Kode Odo</th>
          <th>Item Odo</th>
          <?php } ?>
          <th style='width: 15%;'>Stock</th>
          <th>Riwayat</th>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <th colspan='<?php echo $stockIdx; ?>' style='text-align:right'>Total:</th>
          <th id='opname_total' style='text-align:right'></th>
          <th></th>
        </tr>
      </tfoot>
      <tbody></tbody>
    </table>
  </div>
</div>

<script>
// Tabel diisi dari stock_opname_data.php (server-side).
// Init dijalankan saat window load, setelah jQuery & DataTables dimuat dari index.php.
window.addEventListener('load', function () {
  var STOCK_MODE      = <?php echo json_encode($mode); ?>;
  var IS_FG           = (STOCK_MODE == 'FG');
  var SHOW_STOCK_CARD = <?php echo $isSandrafine ? 'true' : 'false'; ?>;
  var SHOW_RIWAYAT    = <?php echo $showRiwayat ? 'true' : 'false'; ?>;

  var cols = [{ data: 'no', orderable: false }];
  if (IS_FG) {
    cols.push({ data: 'styleno' }, { data: 'kpno' }, { data: null, orderable: false, defaultContent: 'FG' });
  } else {
    cols.push({ data: 'matclass' });
  }
  cols.push({ data: 'id_item' }, { data: 'goods_code' });
  if (!IS_FG) { cols.push({ data: 'itemdesc' }); }
  cols.push({ data: 'color' }, { data: 'size' });
  if (!IS_FG && SHOW_STOCK_CARD) { cols.push({ data: null, orderable: false, defaultContent: '' }); }
  if (!IS_FG) { cols.push({ data: 'kode_odo' }, { data: 'item_odo' }); }
  cols.push({
    data: 'stock',
    createdCell: function (td) { $(td).css('text-align', 'right'); }
  });
  cols.push({
    data: null,
    orderable: false,
    defaultContent: '',
    render: function (data, type, row) {
      if (!SHOW_RIWAYAT) { return ''; }
      return "<a href='?mod=14&mode=" + encodeURIComponent(STOCK_MODE) +
             "&id=" + encodeURIComponent(row.id_item) + "'>Riwayat</a>";
    }
  });

  var $tbl = $('#example');
  // Hapus init lama dari index.php (footerCallback sisi klien tidak dipakai lagi)
  if ($.fn.DataTable.isDataTable($tbl)) { $tbl.DataTable().destroy(); }

  $tbl.DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: 'stock_opname_data.php',
      type: 'POST',
      data: function (d) { d.mode = STOCK_MODE; }
    },
    columns: cols,
    order: []
  }).on('xhr.dt', function (e, settings, json) {
    if (json && json.stock_total !== undefined) {
      $('#opname_total').text(Number(json.stock_total).toFixed(2));
    }
  });
});
</script>
