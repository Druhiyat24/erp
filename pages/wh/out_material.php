<?php
if (empty($_SESSION['username'])) {
  header("location:../../index.php");
}

# START CEK HAK AKSES KEMBALI
# END CEK HAK AKSES KEMBALI

$rscomp = mysql_fetch_array(mysql_query("select * from mastercompany"));
$st_company = $rscomp["status_company"];
$harus_bpb = $rscomp["req_harus_bpb"];
$logo_company = $rscomp["logo_company"];

?>

<?php if ($mod == "new_out_material") {
  $tgl_out = date("d M Y");
  $txttgl_now = date("Y-m-d");
?>

  <script type='text/javascript'>
    function getbarcode() {
      $('#detail_item tbody tr').remove();
      var cboreq = $('#cboreq').val();
      var html = $.ajax({
        type: "POST",
        url: 'ajax_out_material.php?modeajax=get_list_barcode',
        data: {
          cboreq: cboreq
        },
        async: false
      }).responseText;
      if (html) {
        $("#cbobarcode").html(html);
      }

      jQuery.ajax({

        url: 'ajax_out_material.php?modeajax=cari_data_req',
        method: 'POST',
        data: {
          cboreq: cboreq
        },
        dataType: 'json',
        success: function(response) {
          $('#txttujuan').val(response[0]);
          $('#txt_jo').val(response[1]);
        },
        error: function(request, status, error) {
          alert(request.responseText);
        },
      });
    }

    function getdatabarcode() {
      var id_barcode = $('#cbobarcode').val();
      var html = $.ajax({
        type: "POST",
        url: 'ajax_out_material.php?modeajax=view_list_barcode',
        data: {
          id_barcode: id_barcode
        },
        async: false
      }).responseText;
      if (html) {
        $("#detail_item").html(html);
      }
      $(document).ready(function() {
        var table = $('#examplefix2').DataTable({
          scrollCollapse: true,
          paging: false,
          searching: false,
        });
      });
    };

    function getdatareq() {
      var cboreq = $('#cboreq').val();
      var id_barcode = $('#cbobarcode').val();
      var html = $.ajax({
        type: "POST",
        url: 'ajax_out_material.php?modeajax=view_list_req',
        data: {
          cboreq: cboreq,
          id_barcode: id_barcode
        },
        async: false
      }).responseText;
      if (html) {
        $("#detail_header").html(html);
      }
      $(document).ready(function() {
        var table = $('#examplefix1').DataTable({
          scrollCollapse: true,
          paging: false,
          searching: false,
          info: false,
        });
      });
    }
  </script>


  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <form method='post' name='form' action='save_out_material.php?mod=simpan'>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>No. Dok Out</label>
              <input type='text' class='form-control' name='txtno_out' value='<?php
                                                                              $sql = mysqli_query($conn_li, "select max(no_out) from out_material where MONTH(tgl_out) = MONTH('$txttgl_now') and YEAR(tgl_out) = YEAR('$txttgl_now')");
                                                                              $row = mysqli_fetch_array($sql);
                                                                              $kodepay = $row['max(no_out)'];
                                                                              $urutan = (int) substr($kodepay, 12, 4);
                                                                              $urutan++;
                                                                              $bln =  date("m", strtotime($txttgl_now));
                                                                              $thn =  date("y", strtotime($txttgl_now));
                                                                              $huruf = "GK/OUT/$bln$thn/";
                                                                              $kodepay = $huruf . sprintf("%04s", $urutan);
                                                                              $huruf = substr($kodepay, 0, 11);
                                                                              $angka = substr($kodepay, 13, 5) || 0;
                                                                              $angka2 = $angka + 12;
                                                                              $angka3 = sprintf("%05s", $angka2);

                                                                              echo $kodepay; ?>' readonly>
            </div>
            <div class='form-group'>
              <label>No Req #</label>
              <select class='form-control select2' style='width: 100%;' name='cboreq' id='cboreq' onchange='getbarcode();getdatareq();'>
                <?php
                $sql = "select no_req isi, no_req tampil from (
                  select a.*, coalesce(b.qty_out,0)qty_out, a.qty_req - coalesce(b.qty_out,0) sisa_req from
                  (select no_req, kode_barang, nama_barang, job_order, unit, sum(qty) qty_req from req_material
                  group by kode_barang, nama_barang, job_order,unit,no_req) a
                  left join(
                  select kode_barang,nama_barang,job_order,unit,no_req,sum(qty) qty_out from out_material b
                  group by kode_barang,nama_barang,job_order, unit, no_req) b 
                  on a.no_req = b.no_req and a.kode_barang = b.kode_barang and a.nama_barang = b.nama_barang and a.unit = b.unit ) data_req
                  where sisa_req > 0";
                IsiCombo($sql, '', 'Pilih Request #');
                ?>
              </select>
            </div>
            <div class='form-group'>
              <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
            </div>
          </div>
          <div class='col-md-4'>
            <div class='form-group'>
              <label>Tgl BPPB *</label>
              <input type='text' class='form-control' id='datepicker1' name='txt_tglout' value='<?php echo $tgl_out; ?>'>
            </div>
            <div class='row'>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Job Order</label>
                  <input type='text' class='form-control' id='txt_jo' name='txt_jo' readonly>
                </div>
              </div>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Tujuan</label>
                  <input type='text' class='form-control' id='txttujuan' name='txttujuan' readonly>
                </div>
              </div>
            </div>
          </div>
          <div class='col-md-5'>
            <div class='form-group'>
              <label>Barcode # *</label>
              <select class='form-control select2' multiple='multiple' style='width: 100%;' name='cbobarcode' id='cbobarcode' onchange='getdatabarcode();getdatareq();'>
              </select>
            </div>
            <div class='form-group'>
              <label>Keterangan</label>
              <textarea row='5' class='form-control' name='txt_ket' id='txt_ket' placeholder='Masukkan Keterangan'><?php echo $notes; ?></textarea>
            </div>
          </div>
          <div class='box-body'>
            <div id='detail_header'></div>
          </div>
          <div class='box-body'>
            <div id='detail_item'></div>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php }  ?>
<?php
# END COPAS ADD
#if ($id_req=="") {
if ($mod == "out_material") {

  $frdate = date("d M Y");
  $kedate = date("d M Y");

  $tglf = fd(date("d M Y"));
  $tglt = fd(date("d M Y"));

  $dtf = date("d M Y");
  $dtt = date("d M Y");

  $perf = date("d M Y");
  $pert = date("d M Y");

  if (isset($_POST['submit_filter'])) {
    $tglf = fd($_POST['frdate']);
    $perf = date('d M Y', strtotime($tglf));
    $tglt = fd($_POST['kedate']);
    $pert = date('d M Y', strtotime($tglt));
  } else if (isset($_POST['submit_excel_inmaterial'])) {
    $from = date('Y-m-d', strtotime($_POST['frdate']));
    $to = date('Y-m-d', strtotime($_POST['kedate']));
    $tglf = fd($_POST['frdate']);
    $perf = date('d M Y', strtotime($tglf));
    $tglt = fd($_POST['kedate']);
    $pert = date('d M Y', strtotime($tglt));
    echo "<script>
  window.open ('?mod=inmaterial_excel&from=$from&to=$to&dest=xls', '_blank');
    </script>";
  }

?>
  <div class=" box">
    <div class="box-header">
      <a href='../wh/?mod=new_out_material' class='btn btn-primary btn-s'>
        <i class='fa fa-plus'></i> New
      </a>
    </div>

    <div class='row'>
      <form action="" method="post">

        <div class="box-header">
          <div class='col-md-2'>
            <label>Tgl Awal : </label>
            <input type='text' class='form-control' id='datepicker1' name='frdate' placeholder='Masukkan From Date' value='<?php echo $perf; ?>'>

          </div>
          <div class='col-md-2'>
            <label>Tgl Akhir : </label>
            <input type='text' class='form-control' id='datepicker2' name='kedate' placeholder='Masukkan To Date' value='<?php echo $pert; ?>'>
          </div>
          <div class='col-md-3'>
            <div style="padding:4px">
              <br>
              <button type='submit' name='submit_filter' class='btn btn-primary'>Tampilkan</button>
              <button type='submit' name='submit_excel_inmaterial' class='btn btn-success'>Export Excel</button>
            </div>
          </div>

        </div>
    </div>


    <div class="box-body">
      <table id="examplefix3" class="display responsive" style="width: 100%;font-size:13px;">
        <thead>
          <tr>
            <th>Nomor Dok Out</th>
            <th>Tgl Dok</th>
            <th>No Req</th>
            <th>Job Order</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Qty</th>
            <th>Unit</th>
            <th>Tipe Req</th>
            <th>Tujuan</th>
            <th>Keterangan</th>
            <th></th>
          </tr>
        </thead>
        <tbody>

          <?php
          # QUERY TABLE
          $query = mysql_query("select td.*,b.jml_no_out from
          (select a.*, sum(a.qty) qty_out , b.supplier, b.tipe_pengeluaran
                    from out_material a 
                    inner join (select * from req_material  where cancel = 'N' group by no_req) b on a.no_req = b.no_req
                    where a.cancel = 'N' and a.tgl_out >= '$tglf' and a.tgl_out <= '$tglt'
                    group by a.no_out, a.kode_barang, job_order, unit order by no_out asc
                    ) td
                    inner join
                     (
                     select count(no_out) jml_no_out,no_out, kode_barang, nama_barang, job_order, unit from
          (
          select * from out_material where cancel = 'N' and  tgl_out >= '$tglf' and tgl_out <= '$tglt' group by kode_barang, nama_barang, job_order, unit,no_out) a
          group by no_out
                     ) b on td.no_out = b.no_out and td.kode_barang = b.kode_barang and td.nama_barang = b.nama_barang and td.unit = b.unit
                    ");
          $no = 1;
          $supplier = null;
          $sizeStartRow = 0;
          $counter = 0;
          while ($data = mysql_fetch_array($query)) {
            $nodok = $data[no_out];
            if ($data['percent'] == "0") {
              $col = "brown'";
              $width = "20";
            } else if ($data['percent'] > "0" && $data['percent'] <= "50") {
              $col = "CornflowerBlue";
              $width = "50";
            } else if ($data['percent'] > "50" && $data['percent'] <= "99,99") {
              $col = "goldenrod'";
              $width = "75";
            } else if ($data['percent'] > "100") {
              $col = "mediumseagreen'";
              $width = "100";
            }

            echo "<tr>";
            echo "
            <td>$data[no_out]</td>";
            echo "
            <td>" . fd_view($data[tgl_out]) . "</td>";
            echo "
            <td>$data[no_req]</td>
            <td>$data[job_order]</td>
            <td>$data[kode_barang]</td>
            <td>$data[nama_barang]</td>
            <td>$data[qty_out]</td>
            <td>$data[unit]</td>
            <td>$data[tipe_pengeluaran]</td>
            <td>$data[supplier]</td>
            <td>$data[keterangan]</td>
          ";
            if ($supplier != $data[no_out]) {
              $sizeStartRow = $counter;
              $sizeCol = '<td rowspan = "' . $data[jml_no_out] . '" style="float: center;vertical-align: middle;"><a href="cetaksj_out.php?no_dok=' . $nodok . '" 
        data-toggle="tooltip" target="_blank" ><i class="fa fa-print"></i>
        </a> </td>';
            } else {
              //change the rowspan value at the start position, as we know it's increased
              $sizeCol = "<td hidden></td>";
            }
            echo "
      $sizeCol";

            echo "</tr>";
            $supplier = $data[no_out]; //update the previous size value
            $counter++;
          }
          ?>
        </tbody>
      </table>
      </form>
    </div>
  </div>
<?php }
?>
<?php if ($mod == "in_material_item") {

  $id = $_GET['id_in_material'];

  $querybpb = mysql_query("select a.*,round(coalesce(a.qty,0),2) qtybpb ,round(coalesce(sum(b.roll_qty),0),2) qty_lok, 
  round(coalesce(a.qty,0),2) - round(coalesce(sum(b.roll_qty),0),2) sisa_qty 
 from in_material a
 left join in_material_det b on a.id = b.id_in_material
 where a.id = '$id'");
  $databpb      = mysql_fetch_array($querybpb);
  $no_dok       = $databpb['no_dok'];
  $kode_barang  = $databpb['kode_barang'];
  $nama_barang  = $databpb['nama_barang'];
  $job_order    = $databpb['job_order'];
  $supplier     = $databpb['supplier'];
  $no_po        = $databpb['no_po'];
  $qtybpb       = $databpb['qtybpb'];
  $qty_lok      = $databpb['qty_lok'];
  $sisa_qty     = $databpb['sisa_qty'];
  $unit         = $databpb['unit'];

?>
  <script type='text/javascript'>
    function getjuml_roll() {
      var cborak = document.form.cborak.value;
      var html = $.ajax({
        type: "POST",
        url: 'ajax_in_material.php?modeajax=view_list_juml_roll',
        data: {
          cborak: cborak
        },
        async: false
      }).responseText;
      if (html) {
        $("#txtroll").html(html);
      }

      jQuery.ajax({

        url: 'ajax_in_material.php?modeajax=cari_data_rak',
        method: 'POST',
        data: {
          cborak: cborak
        },
        dataType: 'json',
        success: function(response) {
          $('#txtsisa_rak').val(response[0]);
        },
        error: function(request, status, error) {
          alert(request.responseText);
        },
      });

    };



    function validasi() {
      var total = document.getElementById('total').value;
      var sisa_qty_a = document.form.txtsisa_qty.value;
      var total_fix = parseFloat(total);
      var sisa_qty_a_fix = parseFloat(sisa_qty_a);
      if (total_fix > sisa_qty_a_fix) {
        swal({
          title: 'Qty tidak Boleh Melebihi Sisa',
          <?php echo $img_alert; ?>
        });
        valid = false;
      } else valid = true;
      return valid;
      exit;
      // alert(sisa_qty_a_fix);
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
      var sat_nya = document.form.txtunit.value;
      var txtrak = document.form.cborak.value;
      var html = $.ajax({
        type: "POST",
        url: 'ajax_in_material.php?modeajax=view_list_roll',
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
        <form method='post' name='form' action='save_in_material.php?mod=simpan_lokasi' onsubmit='return validasi()'>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Nomor Dok</label>
              <input type='text' class='form-control' disabled value='<?php echo $no_dok ?>'>
              <input type='hidden' class='form-control' name='id_in_material' name='id_in_material' value='<?php echo $id ?>'>
              <input type='hidden' class='form-control' name='no_dok' name='no_dok' value='<?php echo $no_dok ?>'>
            </div>
            <div class='form-group'>
              <label>Supplier #</label>
              <input type='text' class='form-control' disabled value='<?php echo $supplier ?>'>
            </div>
            <div class='form-group'>
              <label>No PO #</label>
              <input type='text' class='form-control' disabled value='<?php echo $no_po ?>'>
            </div>
            <div class='form-group'>
              <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
            </div>
          </div>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Kode Barang #</label>
              <input type='text' class='form-control' disabled value='<?php echo $kode_barang ?>'>
            </div>

            <div class='form-group'>
              <label>Job Order #</label>
              <input type='text' class='form-control' disabled value='<?php echo $job_order ?>'>
            </div>

            <div class='row'>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Lokasi Rak *</label>
                  <select class='form-control select2' style='width: 100%;' name='cborak' onchange='getjuml_roll()'>
                    <?php
                    $sql = "select kode_rak isi,kode_rak tampil from m_rak 
                order by kode_rak asc";
                    IsiCombo($sql, "", "Pilih Rak")
                    ?>
                  </select>
                </div>
              </div>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Sisa Rak</label>
                  <input type='text' class='form-control' readonly name='txtsisa_rak' id='txtsisa_rak'>
                </div>
              </div>
            </div>


          </div>
          <div class='col-md-6'>
            <div class='form-group'>
              <label>Nama Barang #</label>
              <input type='text' class='form-control' disabled value='<?php echo $nama_barang ?>'>
            </div>
            <div class='row'>
              <div class='col-md-3'>
                <div class='form-group'>
                  <label>Qty BPB #</label>
                  <input type='text' size='5' class='form-control' readonly value='<?php echo $qtybpb ?>'>
                </div>
              </div>
              <div class='col-md-3'>
                <div class='form-group'>
                  <label>Qty Lokasi #</label>
                  <input type='text' size='5' class='form-control' readonly value='<?php echo $qty_lok ?>'>
                </div>
              </div>
              <div class='col-md-3'>
                <div class='form-group'>
                  <label>Sisa Qty #</label>
                  <input type='hidden' size='5' class='form-control' name='txtsisa_qty' id='txtsisa_qty' value='<?php echo $sisa_qty ?>'>
                  <input type='text' size='5' class='form-control' readonly value='<?php echo $sisa_qty ?>'>
                </div>
              </div>
              <div class='col-md-3'>
                <div class='form-group'>
                  <label>Unit</label>
                  <input type='hidden' class='form-control' name='txtunit' id='txtunit' value='<?php echo $unit ?>'>
                  <input type='text' class='form-control' readonly value='<?php echo $unit ?>'>
                </div>
              </div>
            </div>
            <div class='form-group'>
              <label>Jml Roll *</label>
              <select class='form-control select2' style='width: 100%;' name='txtroll' id='txtroll' onchange='getListData()'>
              </select>
            </div>

          </div>
          <div class='box-body'>
            <div id='detail_item_roll'></div>
          </div>

          <div class='box-body'>
            <div class='box-body'>
              <table id="example_in_material_lokasi" class="display responsive" style="width:100%">
                <thead>
                  <tr>
                    <th colspan='3'>Item Per Lokasi</th>
                    <th colspan='6'></th>
                  </tr>
                  <tr>
                    <th>No #</th>
                    <th>No Roll</th>
                    <th>No Lot</th>
                    <th>Barcode</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Kode Rak</th>
                    <th>Tgl Input</th>
                    <th>User</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  # QUERY TABLE
                  $query = mysql_query(
                    "select a.*, b.unit from in_material_det a 
                    inner join in_material b on a.id_in_material = b.id
                    where id_in_material = '$id'"
                  );
                  $no = 1;
                  while ($data = mysql_fetch_array($query)) {
                    $tgl_input = fd_view($data['date_input']);
                    echo "<tr>";
                    echo "
            <td>$no</td>
            <td>$data[roll_no]</td>
            <td>$data[lot_no]</td>
            <td>$data[id_barcode]</td>
            <td>$data[roll_qty]</td>
            <td>$data[unit]</td>
            <td>$data[kode_rak]</td>
            <td>$tgl_input</td>
            <td>$data[user]</td>
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
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div><?php }
        ?>