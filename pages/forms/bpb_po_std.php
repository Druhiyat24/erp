<?php
if (empty($_SESSION['username'])) {
  header("location:../../index.php");
}

# START CEK HAK AKSES KEMBALI
$akses = flookup("mnuBPB", "userpassword", "username='$user'");
if ($akses == "0") {
  echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>";
}
# END CEK HAK AKSES KEMBALI
if (isset($_GET['id'])) {
  $id_req = $_GET['id'];
} else {
  $id_req = "";
}

$rscomp = mysql_fetch_array(mysql_query("select * from mastercompany"));
$st_company = $rscomp["status_company"];
$harus_bpb = $rscomp["req_harus_bpb"];
$logo_company = $rscomp["logo_company"];

?>
<script type="text/javascript">


</script>
<?php if ($mod == "bpb_po_v") {
  $reqdate = date("d M Y");
  $txtbcdate = date("d M Y");
?>

  <script type='text/javascript'>
    function getsupp() {
      var id_tipe = document.form.cbotipe.value;
      var html = $.ajax({
        type: "POST",
        url: 'ajax_bpb_po_std.php?modeajax=view_list_supp',
        data: {
          id_tipe: id_tipe
        },
        async: false
      }).responseText;
      if (html) {
        $("#cbosupp").html(html);
      }
    };

    function getpo() {
      var id_tipe = document.form.cbotipe.value;
      var id_supp = $('#cbosupp').val();
      var html = $.ajax({
        type: "POST",
        url: 'ajax_bpb_po_std.php?modeajax=view_list_po',
        data: {
          id_supp: id_supp,
          id_tipe: id_tipe
        },
        async: false
      }).responseText;
      if (html) {
        $("#cbopo").html(html);
      }
    };


    function getdata() {
      var id_po = $('#cbopo').val();
      var id_tipe = document.form.cbotipe.value;
      var id_supp = $('#cbosupp').val();
      var html = $.ajax({
        type: "POST",
        url: 'ajax_bpb_po_std.php?modeajax=view_list_data_po',
        data: {
          id_po: id_po,
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
        <form method='post' name='form' action='save_bpb_po_std.php?mod=simpan'>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Tipe Material #</label>
              <select class='form-control select2' style='width: 100%;' name='cbotipe' id='cbotipe' onchange='getsupp()'>
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
              <label>Supplier #</label>
              <select class='form-control select2' style='width: 100%;' name='cbosupp' id='cbosupp' onchange='getpo()'>
              </select>
            </div>
            <div class='form-group'>
              <label>No PO #</label>
              <select class='form-control select2' required style='width: 100%;' name='cbopo' id='cbopo' onchange='getdata()'>
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
      if ($mod == "bpb_po") {

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
        } else if (isset($_POST['submit_excel'])) {
          $from = date('Y-m-d', strtotime($_POST['frdate']));
          $to = date('Y-m-d', strtotime($_POST['kedate']));
          $tglf = fd($_POST['frdate']);
          $perf = date('d M Y', strtotime($tglf));
          $tglt = fd($_POST['kedate']);
          $pert = date('d M Y', strtotime($tglt));
          echo "<script>
  window.open ('index.php?mod=bpb_po_std_exc&from=$from&to=$to&mode=exc&dest=xls', '_blank');
    </script>";
        }

        ?>
  <div class="box">
    <div class="box-header">
      <?php if ($mod == "bpb_po") { ?>
        <a href='../forms/?mod=bpb_po_v' class='btn btn-primary btn-s'>
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
          <div class='col-md-3'>
            <div style="padding:4px">
              <br>
              <button type='submit' name='submit_filter' class='btn btn-primary'>Tampilkan</button>
              <button type='submit' name='submit_excel' class='btn btn-success'>Export Excel</button>
            </div>
          </div>

        </div>
    </div>


    <div class="box-body">
      <table id="examplefix3" class="display responsive" style="width: 100%;font-size:12px;">
        <thead>
          <tr>
            <th>No</th>
            <th>Nomor BPB</th>
            <th>Tgl BPB</th>
            <th>Supplier</th>
            <th>No PO #</th>
            <th>No SJ</th>
            <th>Ket</th>
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
          $query = mysql_query("select bpb.bpbno,bpb.bpbno_int, bpb.bpbdate, supplier, ac.kpno, bpb.remark, bpb.invno,bpb.pono,
        jenis_dok, bcno, bcdate, jenis_trans, bpb.username, bpb.dateinput, 
        if (sum(bpb.qty) = sr.roll_qty, 'Done', 'Need') stat_lokasi, confirm, confirm_by, confirm_date, bpb.cancel
        from bpb
        inner join mastersupplier ms on bpb.id_supplier = ms.id_supplier
        inner join jo_det jd on bpb.id_jo = jd.id_jo
        inner join so on jd.id_so = so.id
        inner join act_costing ac on so.id_cost = ac.id
        left join (select bpbno,id_item,id_jo,sum(roll_qty) - sum(coalesce(qty_mutasi,0)) roll_qty from bpb_det group by bpbno) sr on bpb.bpbno = sr.bpbno
        where bpb.bpbdate >= '$tglf' and bpb.bpbdate <= '$tglt' and bpb.bpbno like 'A%' and bpb.bpbno like 'A%'
        group by bpbno
        order by bpbdate desc");
          #and substring(bpb.bpbno,-2) != '-R'
          #and pono is not null and id_po_item is not null
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
            <td>$data[pono]</td>
            <td>$data[invno]</td>
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
            <a href='../forms/?mod=bpb_po_item&mode=Bahan_Baku&bpbno=$data[bpbno]'
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
          <a href='../forms/?mod=bpb_po_item&mode=Bahan_Baku&bpbno=$data[bpbno]'
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

<?php if ($mod == "bpb_po_item") {
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
  $bpbdate    = fd_view($databpb['bpbdate']);
  $jns_trans  = $databpb['jenis_trans'];
  $jns_dok    = $databpb['jenis_dok'];
  $bcno       = $databpb['bcno'];
  $bcdate     = fd_view($databpb['bcdate']);
  $invno      = $databpb['invno'];
  $remark      = $databpb['remark'];

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
              <label>Tgl BPB *</label>
              <input type='text' class='form-control' id='datepicker1' name='txtbpbdate' value='<?php echo $bpbdate; ?>'>
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
              <input type='text' class='form-control' id='datepicker2' name='txtbcdate' readonly value='<?php echo $bcdate; ?>' placeholder='Masukkan Tgl. Daftar'>
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
                $query = mysql_query(
                  "select bpb.id_jo,ac.kpno, mp.product_group, mi.id_item, mi.goods_code, mi.itemdesc, bpb.qty qty_bpb, bpb.unit,round(coalesce(sr.roll_qty,0),2) qty_lok,round(coalesce(bpb.qty,0),2) - round(coalesce(sr.roll_qty,0),2) sisa from bpb 
        inner join masteritem mi on bpb.id_item = mi.id_item
        inner join mastersupplier ms on bpb.id_supplier = ms.id_supplier
        inner join jo_det jd on bpb.id_jo = jd.id_jo
        inner join so on jd.id_so = so.id
        inner join act_costing ac on so.id_cost = ac.id
        inner join masterproduct mp on ac.id_product = mp.id
        left join (select bpbno,id_item,id_jo,sum(roll_qty) - sum(coalesce(qty_mutasi,0)) roll_qty from bpb_det group by bpbno, id_item, id_jo) sr on bpb.bpbno = sr.bpbno and bpb.id_item = sr.id_item and sr.id_jo = bpb.id_jo
        where bpb.bpbno = '$bpbno'		
        order by ac.kpno asc, bpb.id_item asc		
        "
                );
                $no = 1;
                while ($data = mysql_fetch_array($query)) {
                  $id_item = $data['id_item'];
                  $id_jo = $data['id_jo'];
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
            href='../forms/?mod=bpb_po_item_lokasi&bpbno=$bpbno&id_item=$id_item&id_jo=$id_jo';
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

        <div class='box-body'>
          <table id="example_bpb_global_lokasi" class="display responsive" style="width:100%">
            <thead>
              <tr>
                <th colspan='3'>Item Per Lokasi</th>
                <th colspan='8'></th>
              </tr>
              <tr>
                <th>No #</th>
                <th>WS #</th>
                <th>ID Item</th>
                <th>Kode Barang</th>
                <th>Nama Bahan Baku</th>
                <th>Nomor Det</th>
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
                "select mi.id_item, ac.kpno, mi.goods_code, mi.itemdesc,bd.no_pack,bd.roll_qty - coalesce(bd.qty_mutasi,0) roll_qty,bd.unit,kode_rak, nama_rak  from bpb_det bd
        inner join masteritem mi on bd.id_item = mi.id_item
        inner join jo_det jd on bd.id_jo = jd.id_jo
        inner join so on jd.id_so = so.id
        inner join act_costing ac on so.id_cost = ac.id
        inner join master_rak mr on bd.id_rak_loc = mr.id
        where bd.bpbno = '$bpbno'
        order by ac.kpno asc, goods_code asc
        "
              );
              $no = 1;
              while ($data = mysql_fetch_array($query)) {
                echo "<tr>";
                echo "
            <td>$no</td>
            <td>$data[kpno]</td>
            <td>$data[id_item]</td>
            <td>$data[goods_code]</td>
            <td>$data[itemdesc]</td>
            <td>$data[no_pack]</td>
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
                <th colspan="6" style="text-align:right">Total:</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div><?php }
        ?>

<?php if ($mod == "bpb_po_item_lokasi") {

  $bpbno = $_GET['bpbno'];
  $id_item = $_GET['id_item'];
  $id_jo = $_GET['id_jo'];

  $querybpb = mysql_query("SELECT bpb.*, mi.mattype, ac.kpno, mi.goods_code, mi.itemdesc, 
  round(coalesce(roll_qty,0),2) roll_qty_lokasi, round(coalesce(bpb.qty,0) - coalesce (roll_qty,0),2) sisa_qty 
  FROM bpb 
  inner join masteritem mi on bpb.id_item = mi.id_item
  inner join jo_det jd on bpb.id_jo = jd.id_jo
  inner join so on jd.id_so = so.id
  inner join act_costing ac on so.id_cost = ac.id
  left join (select bpbno,id_item,id_jo,sum(roll_qty) - coalesce(sum(qty_mutasi),0) roll_qty from bpb_det group by bpbno, id_item, id_jo) sr on bpb.bpbno = sr.bpbno and bpb.id_item = sr.id_item and sr.id_jo = bpb.id_jo
  where bpb.bpbno='$bpbno' and bpb.id_item = '$id_item' and bpb.id_jo = '$id_jo'");
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
  $unit     = $databpb['unit'];

?>
  <script type='text/javascript'>
    function validasi() {
      var total = document.getElementById('total').value;
      var sisa_qty_a = document.form.txtsisa_qty.value;
      if (total > sisa_qty_a) {
        swal({
          title: 'Qty tidak Boleh Melebihi Sisa',
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
      }
      document.getElementById('total').value = tot;
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
        url: 'ajax_bpb_po_std.php?modeajax=view_list_roll',
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
        <form method='post' name='form' action='save_bpb_po_std.php?mod=simpan_lokasi' onsubmit='return validasi()'>
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
                IsiCombo($sql, $unit, "Pilih Unit")
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
                  <input type='hidden' size='5' class='form-control' name='txtsisa_qty' id='txtsisa_qty' value='<?php echo $sisa_qty ?>'>
                  <input type='text' size='5' class='form-control' readonly value='<?php echo $sisa_qty ?>'>
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