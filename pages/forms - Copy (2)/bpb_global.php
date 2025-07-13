<?php
if (empty($_SESSION['username'])) {
  header("location:../../index.php");
}

# START CEK HAK AKSES KEMBALI
$akses = flookup("req_mat", "userpassword", "username='$user'");
if ($akses == "0") {
  echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>";
}
# END CEK HAK AKSES KEMBALI
if (isset($_GET['id'])) {
  $id_req = $_GET['id'];
} else {
  $id_req = "";
}
if (isset($_GET['mode'])) {
  $mode = $_GET['mode'];
} else {
  $mode = "";
}
$rscomp = mysql_fetch_array(mysql_query("select * from mastercompany"));
$st_company = $rscomp["status_company"];
$harus_bpb = $rscomp["req_harus_bpb"];
$logo_company = $rscomp["logo_company"];

?>
<script type="text/javascript">
  function getWS() {
    var id_supp = document.form.cbosupp.value;
    var id_tipe = document.form.cbotipe.value;
    var html = $.ajax({
      type: "POST",
      url: 'ajax_bpb_global.php?modeajax=view_list_ws',
      data: {
        id_supp: id_supp,
        id_tipe: id_tipe
      },
      async: false
    }).responseText;
    if (html) {
      $("#cbows").html(html);
    }
  };

  function getdata() {
    var id_ws = $('#cbows').val();
    var id_tipe = document.form.cbotipe.value;
    var id_supp = document.form.cbosupp.value;
    var html = $.ajax({
      type: "POST",
      url: 'ajax_bpb_global.php?modeajax=view_list_bom',
      data: {
        id_ws: id_ws,
        id_tipe: id_tipe,
        id_supp: id_supp
      },
      async: false
    }).responseText;
    if (html) {
      $("#detail_item").html(html);
    }
    $(document).ready(function() {
      var table = $('#examplefix1').DataTable({
        scrollCollapse: true,
        paging: false,
        fixedColumns: {
          leftColumns: 1,
          rightColumns: 1
        }
      });
    });
  };
</script>
<?php if ($mod == "bpb_global_v") {
  $reqdate = date("d M Y");
  $txtbcdate = date("d M Y");
?>

  <script type='text/javascript'>
    function startCalcBpb() {
      intervalBpb = setInterval('findTotalBpb()', 1);
    }

    function findTotalBpb() {
      var arr = document.getElementsByClassName('form-control qtybpbclass');
      var tot = 0;
      for (var i = 0; i < arr.length; i++) {
        if (parseFloat(arr[i].value))
          tot += parseFloat(arr[i].value);
      }
      document.getElementById('total_qty_chk').value = tot;
    }

    function stopCalcBpb() {
      clearInterval(intervalBpb);
    }
  </script>


  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <form method='post' name='form' action='save_bpb_global.php?mod=simpan'>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Supplier #</label>
              <select class='form-control select2' style='width: 100%;' name='cbosupp' id='cbosupp' onchange='getWS()'>
                <?php
                $sql = "select a.id_supplier isi, supplier tampil from mastersupplier a where tipe_sup = 'S' order by supplier asc";
                IsiCombo($sql, '', 'Pilih Supplier #');
                ?>
              </select>
            </div>
            <div class='form-group'>
              <label>Tipe Material #</label>
              <select class='form-control select2' style='width: 100%;' name='cbotipe' id='cbotipe' onchange='getWS()'>
                <?php
                $sql = "select distinct(mattype) isi, case mattype when 'F' then 'FABRIC'
              when 'A' then 'ACCESSORIES'
              end tampil
              from masteritem where mattype in ('F','A')
              order by 
              case mattype when 'F' then '1'
              else '2'
              end ";
                IsiCombo($sql, '', 'Pilih Tipe #');
                ?>
              </select>
            </div>
            <div class='form-group'>
              <label>No Ws Global #</label>
              <select class='form-control select2' required style='width: 100%;' name='cbows' id='cbows' onchange='getdata()'>
              </select>
            </div>
            <div class='form-group'>
              <label>Tgl BPB *</label>
              <input type='text' class='form-control' id='datepicker1' name='txtbpbdate' placeholder='Masukkan Request Date' value='<?php echo $reqdate; ?>'>
            </div>
          </div>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Jenis Pemasukan *</label>
              <select class='form-control select2' style='width: 100%;' name='txtjns_in' required>
                <?php
                $sqljns_out = "select nama_trans isi,nama_trans tampil from mastertransaksi where 
                            jenis_trans='IN' and jns_gudang = 'FACC' order by id";
                IsiCombo($sqljns_out, '', 'Pilih Jenis Pemasukan');
                ?>
              </select>
            </div>

            <div class='form-group'>
              <label>Jenis Dokumen *</label>
              <select class='form-control select2' style='width: 100%;' name='txtstatus_kb' required>
                <?php
                $sqljns_kb = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan where 
                    kode_pilihan='Status KB IN' order by nama_pilihan";
                IsiCombo($sqljns_kb, '', 'Pilih Jenis Dokumen');
                ?>
              </select>
            </div>
            <div class='form-group'>
              <label>Nomor Daftar *</label>
              <input type='text' maxlength='6' class='form-control' name='txtbcno' id='txtbcno' placeholder='Masukan No Daftar'>
            </div>
            <div class='form-group'>
              <label>Tgl Daftar *</label>
              <input type='text' class='form-control' id='datepicker2' name='txtbcdate' value='<?php echo $txtbcdate; ?>' placeholder='Masukkan Tgl. Daftar'>
            </div>
          </div>
          <div class='col-md-6'>
            <div class='form-group'>
              <label>Nomor Invoice / SJ *</label>
              <input type='text' class='form-control' name='txtinvno' placeholder='Masukkan Nomor Invoice / SJ' value='<?php echo $txtinvno; ?>'>

            </div>

            <div class='form-group'>
              <label>Keterangan</label>
              <textarea row='5' class='form-control' name='txtremark' id='txtremark' placeholder='Masukkan Notes'><?php echo $notes; ?></textarea>
            </div>
            <div class='form-group'>
              <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
            </div>
          </div>
          <div class='box-body'>
            <div id='detail_item'></div>
          </div>
        </form>
      </div>
    </div>
  </div><?php }
      # END COPAS ADD
      #if ($id_req=="") {
      if ($mod == "bpb_global") {

        $frdate = date("d M Y");
        $kedate = date("d M Y");

        $tglf = date("d M Y");
        $tglt = date("d M Y");

        $dtf = date("d M Y");
        $dtt = date("d M Y");

        $perf = date("d M Y");
        $pert = date("d M Y");

        if (isset($_POST['submit_filter'])) {
          $tglf = fd($_POST['frdate']);
          $perf = date('d M Y', strtotime($tglf));
          $tglt = fd($_POST['kedate']);
          $pert = date('d M Y', strtotime($tglt));
          $cbotipe = $_POST['cbotipe'];
        }

        ?>
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">WS Global</h3>
      <?php if ($mod == "bpb_global") { ?>
        <a href='../forms/?mod=bpb_global_v&mode=<?php echo $mode; ?>' class='btn btn-primary btn-s'>
          <i class='fa fa-plus'></i> New
        </a>
      <?php } ?>
    </div>

    <div class='row'>
      <form action="" method="post">

        <div class="box-header">
          <div class='col-md-2'>
            <label>From Date (BPB) : </label>
            <input type='text' class='form-control' id='datepicker1' name='frdate' placeholder='Masukkan From Date' value='<?php echo $perf; ?>'>
          </div>
          <div class='col-md-2'>
            <label>To Date (BPB) : </label>
            <input type='text' class='form-control' id='datepicker2' name='kedate' placeholder='Masukkan To Date' value='<?php echo $pert; ?>'>
          </div>
          <div class='col-md-2'>
            <label>Jenis Material : </label>
            <select class='form-control select2' style='width: 100%;' name='cbotipe' id='cbotipe' required>
              <?php
              $sql = "select distinct(mattype) isi, case mattype when 'F' then 'FABRIC'
              when 'A' then 'ACCESSORIES'
              end tampil
              from masteritem where mattype in ('F','A')
              order by 
              case mattype when 'F' then '1'
              else '2'
              end ";
              IsiCombo($sql, $cbotipe, 'Pilih Tipe #');
              ?>
            </select>
          </div>
          <div class='col-md-3'>
            <div>
              <br>
              <button type='submit' name='submit_filter' class='btn btn-primary'>Tampilkan</button>
            </div>
          </div>

        </div>
    </div>


    <div class="box-body">
      <table id="examplefix3" class="display responsive" style="width:100%">
        <thead>
          <tr>
            <th>No</th>
            <th>Nomor BPB</th>
            <th>Tgl BPB</th>
            <th>Supplier</th>
            <th>No WS #</th>
            <th>Keterangan</th>
            <th>Jenis Dok</th>
            <th>No Daftar</th>
            <th>Tgl Daftar</th>
            <th>Jenis Trans</th>
            <th>Dibuat</th>
            <th>Status Lokasi</th>
            <th>Status</th>
            <th></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE

          if ($cbotipe == 'F') {
            $sqljoin = "left join (select bpbno, sum(roll_qty) roll_qty from bpb_roll_h brh
            inner join bpb_roll br on brh.id = br.id_h
            group by bpbno) br on bpb.bpbno = br.bpbno";
            $tipemode = 'fabric';
          } else  if ($cbotipe == 'A') {
            $sqljoin = "left join (select bpbno,sum(roll_qty) roll_qty from bpb_det group by bpbno) br on bpb.bpbno = br.bpbno";
            $tipemode = 'acc';
          }

          $query = mysql_query("select bpb.bpbno,bpb.bpbno_int, bpb.bpbdate, supplier, ac.kpno, bpb.remark, 
          jenis_dok, bcno, bcdate, jenis_trans, bpb.username, bpb.dateinput, round(sum(bpb.qty),2), round(br.roll_qty,2),
          if (round(sum(bpb.qty),2) = round(br.roll_qty,2), 'Done', 'Need') stat_lokasi, confirm, confirm_by, confirm_date, bpb.cancel
        from bpb 
        inner join (select * from bom_jo_global_item group by id_jo) a on a.id_jo = bpb.id_jo
        inner join mastersupplier ms on bpb.id_supplier = ms.id_supplier
        inner join jo_det jd on a.id_jo = jd.id_jo
        inner join so on jd.id_so = so.id
        inner join act_costing ac on so.id_cost = ac.id
        $sqljoin
        where bpb.bpbdate >= '$tglf' and bpb.bpbdate <= '$tglt' and bpb.bpbno like '$cbotipe%' and bpb.pono is null and bpb.id_po_item is null
        group by bpbno
        order by bpbdate desc ");

          $no = 1;
          while ($data = mysql_fetch_array($query)) {
            if ($data['cancel'] == "Y") {
              $fontcol = "style='color:red;'";
            } else {
              $fontcol = "";
            }
            if ($data['stat_lokasi'] == "Done") {
              $icon = "<i class='fa fa-check'></i>";
            } else {
              $icon = "<i class='fa fa-times'></i>";
            }
            echo "<tr $fontcol>";
            echo "
            <td>$no</td>
            <td>$data[bpbno_int]</td>";
            echo "
            <td>" . fd_view($data[bpbdate]) . "</td>";
            echo "
            <td>$data[supplier]</td>
            <td>$data[kpno]</td>
            <td>$data[remark]</td>
            <td>$data[jenis_dok]</td>
            <td>$data[bcno]</td>";
            echo "
            <td>" . fd_view($data[bcdate]) . "</td>";
            echo "
            <td>$data[jenis_trans]</td>
            <td>$data[username] (" . fd_view_dt($data['dateinput']) . ")</td>
            <td><a>$icon</a></td>";
            echo "
            <td>";
            if ($data['confirm'] == 'Y' or $data['cancel'] == 'Y') {
              if ($data['confirm'] == 'Y') {
                $captses = "Confirmed By " . $data['confirm_by'] . " (" . fd_view_dt($data['confirm_date']) . ")";
              } else {
                $reason = flookup("reason", "cancel_trans", "trans_no='$data[bpbno]'");
                $captses = "Cancelled By " . $data['cancel_by'] . " (" . fd_view_dt($data['cancel_date']) . ") Reason " . $reason;
              }
              echo "$captses";
            } else {
              echo "
            <a href='../forms/?mod=bpb_global_item&mode=$tipemode&bpbno=$data[bpbno]'
              data-toggle='tooltip' ><i class='fa fa-pencil'></i>
            </a> 
            </td>";
            }
            echo "<td>";
            if ($data['confirm'] == 'Y') {
              $status_print = "
          <a href='cetaksj.php?mode=In&noid=$data[bpbno]' 
          data-toggle='tooltip' ><i class='fa fa-print'></i>
          </a>
          <a href='../forms/?mod=bpb_global_item&mode=$tipemode&bpbno=$data[bpbno]'
          data-toggle='tooltip' ><i class='fa fa-eye'></i>
        </a>";
            } else {
              $status_print = "";
            }
            echo $status_print;
            echo " 
          </td>";
            echo "<td><a href='#' class='edit-record' data-id=$data[bpbno] 
          data-toggle='tooltip' title='Detail'><i class='fa fa-bars'></i>
          </a> </td>";
            echo "</tr>";
            $no++; // menambah nilai nomor urut
          }
          ?>
        </tbody>
      </table>
      </form>
    </div>
  </div>
<?php }
?>

<?php if ($mod == "bpb_global_item") {
  $mode = $_GET['mode'];
  $bpbno = $_GET['bpbno'];

  $querybpb = mysql_query("SELECT bpb.*, mi.mattype, ac.kpno FROM bpb 
  inner join masteritem mi on bpb.id_item = mi.id_item
  inner join jo_det jd on bpb.id_jo = jd.id_jo
  inner join so on jd.id_so = so.id
  inner join act_costing ac on so.id_cost = ac.id 
  where bpbno='$bpbno' limit 1");
  $databpb    = mysql_fetch_array($querybpb);
  $bpbno_int  = $databpb['bpbno_int'];
  $id_supplier = $databpb['id_supplier'];
  $tipe_mat   = $databpb['mattype'];
  $no_ws      = $databpb['kpno'];
  $bpbdate    = fd_view($databpb['bpbdate']);
  $jns_trans  = $databpb['jenis_trans'];
  $jns_dok    = $databpb['jenis_dok'];
  $bcno       = $databpb['bcno'];
  $bcdate     = fd_view($databpb['bcdate']);
  $invno      = $databpb['invno'];
  $remark      = $databpb['remark'];
  $confirm    = $databpb['confirm'];

  if ($confirm == 'Y') {
    $stat_read = 'disabled';
  } else {
    $stat_read = '';
  }

?>

  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <form method='post' name='form' action='save_bpb_global.php?mod=update&bpbno=<?php echo $bpbno; ?>'>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Nomor BPB</label>
              <input type='text' class='form-control' disabled value='<?php echo $bpbno_int ?>'>
            </div>
            <div class='form-group'>
              <label>Supplier #</label>
              <select class='form-control select2' style='width: 100%;' disabled name='cbosupp' id='cbosupp'>
                <?php
                $sql = "select a.id_supplier isi, supplier tampil from mastersupplier a where tipe_sup = 'S' order by supplier asc";
                IsiCombo($sql, $id_supplier, 'Pilih Supplier #');
                ?>
              </select>
            </div>
            <div class='form-group'>
              <label>Tipe Material #</label>
              <select class='form-control select2' style='width: 100%;' name='cbotipe' id='cbotipe' disabled>
                <?php
                $sql = "select distinct(mattype) isi, case mattype when 'F' then 'FABRIC'
              when 'A' then 'ACCESSORIES'
              end tampil
              from masteritem where mattype in ('F','A')
              order by 
              case mattype when 'F' then '1'
              else '2'
              end ";
                IsiCombo($sql, $tipe_mat, 'Pilih Tipe #');
                ?>
              </select>
            </div>
            <div class='form-group'>
              <label>No Ws Global #</label>
              <input type='text' class='form-control' disabled value='<?php echo $no_ws ?>'>
            </div>
            <div class='form-group'>
              <label>Tgl BPB *</label>
              <input type='text' class='form-control' id='datepicker1' <?php echo $stat_read ?> name='txtbpbdate' value='<?php echo $bpbdate; ?>'>
            </div>
          </div>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Jenis Pemasukan *</label>
              <select class='form-control select2' style='width: 100%;' name='txtjns_in' required>
                <?php
                $sqljns_out = "select UPPER(nama_trans) isi,nama_trans tampil from mastertransaksi where 
                            jenis_trans='IN' and jns_gudang = 'FACC' order by id";
                IsiCombo($sqljns_out, $jns_trans, 'Pilih Jenis Pemasukan');
                ?>
              </select>
            </div>

            <div class='form-group'>
              <label>Jenis Dokumen *</label>
              <select class='form-control select2' style='width: 100%;' name='txtstatus_kb' required>
                <?php
                $sqljns_kb = "select UPPER(nama_pilihan) isi,nama_pilihan tampil from masterpilihan where 
                    kode_pilihan='Status KB IN' order by nama_pilihan";
                IsiCombo($sqljns_kb, $jns_dok, 'Pilih Jenis Dokumen');
                ?>
              </select>
            </div>
            <div class='form-group'>
              <label>Nomor Daftar *</label>
              <input type='text' maxlength='6' class='form-control' name='txtbcno' readonly id='txtbcno' value='<?php echo $bcno; ?>'>
            </div>
            <div class='form-group'>
              <label>Tgl Daftar *</label>
              <input type='text' class='form-control' id='datepicker2' name='txtbcdate' readonly value='<?php echo $bcdate; ?>' placeholder='Masukkan Tgl. Daftar' <?php echo $stat_read ?>>
            </div>
          </div>
          <div class='col-md-6'>
            <div class='form-group'>
              <label>Nomor Invoice / SJ *</label>
              <input type='text' class='form-control' name='txtinvno' placeholder='Masukkan Nomor Invoice / SJ' value='<?php echo $invno; ?>'>
            </div>
            <div class='form-group'>
              <label>Keterangan</label>
              <textarea row='5' class='form-control' name='txtremark' id='txtremark' placeholder='Masukkan Notes'><?php echo $remark; ?></textarea>
            </div>
            <div class='form-group'>
              <button type='submit' name='submit' class='btn btn-primary'>Update</button>
            </div>
          </div>
          <div class='box-body'>
            <table id="example_bpb_global" class="display responsive" style="width:100%">
              <thead>
                <tr>
                  <th colspan='3'>Item BPB</th>
                  <th colspan='8'></th>
                </tr>
                <tr>
                  <th>No #</th>
                  <th>WS #</th>
                  <th>Product</th>
                  <th>ID Item</th>
                  <th>Kode Barang</th>
                  <th>Kode Bahan Baku</th>
                  <th>Qty BPB</th>
                  <th>Satuan BOM</th>
                  <th>Qty Lokasi</th>
                  <th>Sisa Belum di Lokasi</th>
                  <th>Location</th>
                </tr>
              </thead>
              <tbody>
                <?php
                # QUERY TABLE
                if ($mode == 'fabric') {
                  $sqljoin = "left join bpb_roll_h brh on bpb.id_item = brh.id_item and bpb.id_jo = brh.id_jo and bpb.bpbno = brh.bpbno
                  left join (select id_h,sum(roll_qty) roll_qty from bpb_roll group by id_h) br on brh.id = br.id_h";
                } else if ($mode == 'acc') {
                  $sqljoin = "left join (select bpbno,id_item,id_jo,sum(roll_qty) roll_qty from bpb_det bd group by bpbno,id_item,id_jo ) br on br.bpbno = bpb.bpbno and br.id_item = bpb.id_item and br.id_jo = bpb.id_jo";
                }
                $query = mysql_query(
                  "select bpb.id_jo, ac.kpno, mp.product_group, mi.id_item, mi.goods_code, mi.itemdesc, bpb.qty qty_bpb
        , bpb.unit,  round(coalesce(br.roll_qty,0),2) qty_lok, round(coalesce(bpb.qty,0),2) - round(coalesce(br.roll_qty,0),2) sisa
        from bpb
        inner join masteritem mi on bpb.id_item = mi.id_item
        inner join mastersupplier ms on bpb.id_supplier = ms.id_supplier
        inner join jo_det jd on bpb.id_jo = jd.id_jo
        inner join so on jd.id_so = so.id
        inner join act_costing ac on so.id_cost = ac.id
        inner join masterproduct mp on ac.id_product = mp.id
        $sqljoin
        where bpb.bpbno = '$bpbno'
        group by bpb.id_item
        order by goods_code asc
        "
                );
                $no = 1;
                while ($data = mysql_fetch_array($query)) {
                  $id_item = $data['id_item'];
                  echo "<tr>";
                  echo "
            <td>$no</td>
            <td>$data[kpno]</td>
            <td>$data[product_group]</td>
            <td>$data[id_item]</td>
            <td>$data[goods_code]</td>
            <td>$data[itemdesc]</td>
            <td>$data[qty_bpb]</td>
            <td>$data[unit]</td>
            <td>$data[qty_lok]</td>
            <td>$data[sisa]</td>
            <td>
          <a 
            href='../forms/?mod=bpb_global_item_lokasi&mode=$mode&bpbno=$bpbno&id_item=$id_item';
            data-toggle='tooltip' title='Preview'><i class='fa fa-plus'></i>
          </a>
          </td>
            </td>";
                  echo "</tr>";
                  $no++; // menambah nilai nomor urut
                }
                ?>
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="6" style="text-align:right">Total:</th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                </tr>
              </tfoot>
            </table>
          </div>
        </form>

        <?php if ($mode == 'fabric') { ?>
          <div class='box-body'>
            <table id="example_bpb_global_lokasi" class="display responsive" style="width:100%">
              <thead>
                <tr>
                  <th colspan='3'>Item Per Lokasi</th>
                  <th colspan='8'></th>
                </tr>
                <tr>
                  <th>No #</th>
                  <th>ID Item</th>
                  <th>Kode Barang</th>
                  <th>Nama Bahan Baku</th>
                  <th>No Roll</th>
                  <th>No Lot</th>
                  <th>Qty</th>
                  <th>Unit</th>
                  <th>Kode Rak</th>
                  <th>Nama Rak</th>
                </tr>
              </thead>
              <tbody>
                <?php
                # QUERY TABLE
                $query = mysql_query(
                  "select mi.id_item, mi.goods_code, mi.itemdesc, lot_no, roll_no, roll_qty, br.unit, kode_rak, nama_rak from bpb
        inner join masteritem mi on bpb.id_item = mi.id_item
        inner join mastersupplier ms on bpb.id_supplier = ms.id_supplier
        inner join jo_det jd on bpb.id_jo = jd.id_jo
        inner join so on jd.id_so = so.id
        inner join act_costing ac on so.id_cost = ac.id
        inner join masterproduct mp on ac.id_product = mp.id
        inner join bpb_roll_h brh on bpb.id_item = brh.id_item and bpb.id_jo = brh.id_jo and bpb.bpbno  = brh.bpbno
        inner join bpb_roll br on brh.id = br.id_h
				inner join master_rak mr on br.id_rak_loc = mr.id
        where bpb.bpbno = '$bpbno'
        order by goods_code asc, kode_rak asc, roll_no asc
        "
                );
                $no = 1;
                while ($data = mysql_fetch_array($query)) {
                  echo "<tr>";
                  echo "
            <td>$no</td>
            <td>$data[id_item]</td>
            <td>$data[goods_code]</td>
            <td>$data[itemdesc]</td>
            <td>$data[roll_no]</td>
            <td>$data[lot_no]</td>
            <td>$data[roll_qty]</td>
            <td>$data[unit]</td>
            <td>$data[kode_rak]</td>
            <td>$data[nama_rak]</td>
            </td>";
                  echo "</tr>";
                  $no++; // menambah nilai nomor urut
                }
                ?>
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="4" style="text-align:right">Total:</th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                </tr>
              </tfoot>
            </table>
          </div>
        <?php } ?>


        <?php if ($mode == 'acc') { ?>
          <div class='box-body'>
            <table id="example_bpb_global_lokasi" class="display responsive" style="width:100%">
              <thead>
                <tr>
                  <th colspan='3'>Item Per Lokasi</th>
                  <th colspan='8'></th>
                </tr>
                <tr>
                  <th>No #</th>
                  <th>ID Item</th>
                  <th>Kode Barang</th>
                  <th>Nama Bahan Baku</th>
                  <th>No Roll</th>
                  <th>No Lot</th>
                  <th>Qty</th>
                  <th>Unit</th>
                  <th>Kode Rak</th>
                  <th>Nama Rak</th>
                </tr>
              </thead>
              <tbody>
                <?php
                # QUERY TABLE
                $query = mysql_query(
                  "select mi.id_item, mi.goods_code, mi.itemdesc, roll_qty, br.unit, kode_rak, nama_rak from bpb
        inner join masteritem mi on bpb.id_item = mi.id_item
        inner join mastersupplier ms on bpb.id_supplier = ms.id_supplier
        inner join jo_det jd on bpb.id_jo = jd.id_jo
        inner join so on jd.id_so = so.id
        inner join act_costing ac on so.id_cost = ac.id
        inner join masterproduct mp on ac.id_product = mp.id
				inner join bpb_det br on br.bpbno = bpb.bpbno and br.id_item = bpb.id_item and br.id_jo = bpb.id_jo
				inner join master_rak mr on br.id_rak_loc = mr.id
        where bpb.bpbno = '$bpbno'
        order by goods_code asc, kode_rak asc
        "
                );
                $no = 1;
                while ($data = mysql_fetch_array($query)) {
                  echo "<tr>";
                  echo "
            <td>$no</td>
            <td>$data[id_item]</td>
            <td>$data[goods_code]</td>
            <td>$data[itemdesc]</td>
            <td>-</td>
            <td>-</td>            
            <td>$data[roll_qty]</td>
            <td>$data[unit]</td>
            <td>$data[kode_rak]</td>
            <td>$data[nama_rak]</td>
            </td>";
                  echo "</tr>";
                  $no++; // menambah nilai nomor urut
                }
                ?>
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="4" style="text-align:right">Total:</th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                </tr>
              </tfoot>
            </table>
          </div>
        <?php } ?>




      </div>
    </div>
  </div><?php }
        ?>

<?php if ($mod == "bpb_global_item_lokasi") {

  $mode = $_GET['mode'];
  $bpbno = $_GET['bpbno'];
  $id_item = $_GET['id_item'];

  if ($mode == 'fabric') {
    $sql_join = "	left join bpb_roll_h brh on bpb.id_item = brh.id_item and bpb.id_jo = brh.id_jo and bpb.bpbno  = brh.bpbno
    left join (select id_h,sum(roll_qty) roll_qty from bpb_roll group by id_h) br on brh.id = br.id_h";
  } else {
    $sql_join = "left join (select bpbno,id_item, id_jo,sum(roll_qty) roll_qty from bpb_det group by bpbno, id_item,id_jo) br on bpb.bpbno = br.bpbno and bpb.id_item = br.id_item and bpb.id_jo = br.id_jo";
  }

  $querybpb = mysql_query("SELECT bpb.*, mi.mattype, ac.kpno, mi.goods_code, mi.itemdesc, 
  round(coalesce(roll_qty,0),2) roll_qty_lokasi, round(coalesce(bpb.qty,0) - coalesce (roll_qty,0),2) sisa_qty 
  FROM bpb 
  inner join masteritem mi on bpb.id_item = mi.id_item
  inner join jo_det jd on bpb.id_jo = jd.id_jo
  inner join so on jd.id_so = so.id
  inner join act_costing ac on so.id_cost = ac.id
  $sql_join
  where bpb.bpbno='$bpbno' and bpb.id_item = '$id_item'");
  $databpb      = mysql_fetch_array($querybpb);
  $bpbno_int    = $databpb['bpbno_int'];
  $tipe_mat     = $databpb['mattype'];
  $no_ws        = $databpb['kpno'];
  $goods_code   = $databpb['goods_code'];
  $itemdesc     = $databpb['itemdesc'];
  $qty          = $databpb['qty'];
  $bpbno        = $databpb['bpbno'];
  $id_jo        = $databpb['id_jo'];
  $roll_qty_lokasi = $databpb['roll_qty_lokasi'];
  $sisa_qty     = $databpb['sisa_qty'];

?>
  <script type='text/javascript'>
    function validasi() {
      var total = parseFloat(document.getElementById('total').value, 2) || 0;
      var sisa_qty_a = parseFloat(document.form.txtsisa_qty.value, 2) || 0;

      if (total > sisa_qty_a) {
        swal({
          title: 'Qty tidak Boleh Melebihi Sisa  ',
          <?php echo $img_alert; ?>
        });
        valid = false;
      } else valid = true;
      return valid;
      exit;
    }

    function startCalc() {
      interval = setInterval('findTotal()', 1);
    }

    function findTotal() {
      var arr = document.getElementsByClassName('form-control jmlclass');
      var tot = 0;
      for (var i = 0; i < arr.length; i++) {
        if (parseFloat(arr[i].value))
          tot += parseFloat(arr[i].value);
        tot_fix = tot.toFixed(2);
      }
      document.getElementById('total').value = tot_fix;
    }

    function stopCalc() {
      clearInterval(interval);
    }

    function getListData() {
      var cri_item = document.form.txtroll.value;
      var sat_nya = document.form.txtunitdet.value;
      var txtrak = document.form.txtrak.value;
      var html = $.ajax({
        type: "POST",
        url: 'ajax_bpb_global.php?modeajax=view_list_roll',
        data: {
          cri_item: cri_item,
          sat_nya: sat_nya,
          txtrak: txtrak
        },
        async: false
      }).responseText;
      if (html) {
        $("#detail_item_roll").html(html);
      }
      $(".select2").select2();
    };
  </script>

  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <form method='post' name='form' action='save_bpb_global.php?mod=simpan_lokasi&mode=<?php echo $mode; ?>' onsubmit='return validasi()'>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Nomor BPB</label>
              <input type='text' class='form-control' disabled value='<?php echo $bpbno_int ?>'>
              <input type='hidden' name='bpbnoint_lok' class='form-control' value='<?php echo $bpbno_int ?>'>
              <input type='hidden' name='bpbno_lok' class='form-control' value='<?php echo $bpbno ?>'>
              <input type='hidden' name='id_item_lok' class='form-control' value='<?php echo $id_item ?>'>
              <input type='hidden' name='id_jo_lok' class='form-control' value='<?php echo $id_jo ?>'>
            </div>
            <div class='form-group'>
              <label>Tipe Material #</label>
              <select class='form-control select2' style='width: 100%;' name='cbotipe' id='cbotipe' disabled>
                <?php
                $sql = "select distinct(mattype) isi, case mattype when 'F' then 'FABRIC'
              when 'A' then 'ACCESSORIES'
              end tampil
              from masteritem where mattype in ('F','A')
              order by 
              case mattype when 'F' then '1'
              else '2'
              end ";
                IsiCombo($sql, $tipe_mat, 'Pilih Tipe #');
                ?>
              </select>
            </div>

            <div class='form-group'>
              <label>Jml Detail *</label>
              <select class='form-control select2' style='width: 100%;' name='txtroll'>
                <option value='' disabled selected>Pilih Jml Detail</option>
                <?php
                for ($x = 1; $x <= 100; $x++) {
                  echo "<option value='$x'>$x</option>";
                }
                ?>
              </select>
            </div>
            <div class='form-group'>
              <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
            </div>
          </div>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Kode Barang #</label>
              <input type='text' class='form-control' disabled value='<?php echo $goods_code ?>'>
            </div>

            <div class='form-group'>
              <label>No Ws Global #</label>
              <input type='text' class='form-control' disabled value='<?php echo $no_ws ?>'>
            </div>
            <div class='form-group'>
              <label>Unit Detail *</label>
              <select class='form-control select2' style='width: 100%;' name='txtunitdet'>
                <?php
                $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan where 
                    kode_pilihan='Satuan' order by nama_pilihan";
                IsiCombo($sql, "", "Pilih Unit")
                ?>
              </select>
            </div>
          </div>
          <div class='col-md-6'>
            <div class='form-group'>
              <label>Nama Barang #</label>
              <input type='text' class='form-control' disabled value='<?php echo $itemdesc ?>'>
            </div>
            <div class='row'>
              <div class='col-md-3'>
                <div class='form-group'>
                  <label>Qty BPB #</label>
                  <input type='text' size='5' class='form-control' readonly value='<?php echo $qty ?>'>
                </div>
              </div>
              <div class='col-md-3'>
                <div class='form-group'>
                  <label>Qty Lokasi #</label>
                  <input type='text' size='5' class='form-control' readonly value='<?php echo $roll_qty_lokasi ?>'>
                </div>
              </div>
              <div class='col-md-3'>
                <div class='form-group'>
                  <label>Sisa Qty #</label>
                  <input type='text' class='form-control' name='txtsisa_qty' id='txtsisa_qty' value='<?php echo $sisa_qty ?>'>
                  <input type='text' class='form-control' readonly value='<?php echo $sisa_qty ?>'>
                </div>
              </div>
            </div>
            <div class='form-group'>
              <label>No. Rak *</label>
              <select class='form-control select2' style='width: 100%;' name='txtrak' onchange='getListData()'>
                <?php
                $sql = "select id isi,concat(kode_rak,' ',nama_rak) tampil from master_rak 
                  order by kode_rak";
                IsiCombo($sql, "", "Pilih Rak")
                ?>
              </select>
            </div>
          </div>
          <div class='box-body'>
            <div id='detail_item_roll'></div>
          </div>
        </form>
      </div>
    </div>
  </div><?php }
        ?>