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

<?php if ($mod == "retur_material_new") {
  $tgl_retur = date("d M Y");
  $txttgl_now = date("Y-m-d");
?>

  <script type='text/javascript'>
    function getbarcode() {
      $('#detail_item tbody tr').remove();
      var cboout = $('#cboout').val();
      var html = $.ajax({
        type: "POST",
        url: 'ajax_retur_material.php?modeajax=get_list_barcode',
        data: {
          cboout: cboout
        },
        async: false
      }).responseText;
      if (html) {
        $("#cbobarcode").html(html);
      }

      jQuery.ajax({

        url: 'ajax_retur_material.php?modeajax=cari_data_req',
        method: 'POST',
        data: {
          cboout: cboout
        },
        dataType: 'json',
        success: function(response) {
          $('#txtnoreq').val(response[0]);
          $('#txt_jo').val(response[1]);
        },
        error: function(request, status, error) {
          alert(request.responseText);
        },
      });


    }

    function getdatabarcode() {
      var id_barcode = $('#cbobarcode').val();
      var cboout = $('#cboout').val();
      var html = $.ajax({
        type: "POST",
        url: 'ajax_retur_material.php?modeajax=view_list_barcode',
        data: {
          id_barcode: id_barcode,
          cboout: cboout
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
  </script>


  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <form method='post' name='form' action='save_retur_material.php?mod=simpan'>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>No. Dok Retur</label>
              <input type='text' class='form-control' name='txtno_retur' value='<?php
                                                                                $sql = mysqli_query($conn_li, "select max(no_retur) from retur_material where MONTH(tgl_retur) = MONTH('$txttgl_now') and YEAR(tgl_retur) = YEAR('$txttgl_now')");
                                                                                $row = mysqli_fetch_array($sql);
                                                                                $kodepay = $row['max(no_retur)'];
                                                                                $urutan = (int) substr($kodepay, 12, 4);
                                                                                $urutan++;
                                                                                $bln =  date("m", strtotime($txttgl_now));
                                                                                $thn =  date("y", strtotime($txttgl_now));
                                                                                $huruf = "GK/RI/$bln$thn/";
                                                                                $kodepay = $huruf . sprintf("%04s", $urutan);
                                                                                $huruf = substr($kodepay, 0, 11);
                                                                                $angka = substr($kodepay, 12, 4) || 0;
                                                                                $angka2 = $angka + 12;
                                                                                $angka3 = sprintf("%05s", $angka2);

                                                                                echo $kodepay; ?>' readonly>
            </div>
            <div class='form-group'>
              <label>No Dok Out #</label>
              <select class='form-control select2' style='width: 100%;' name='cboout' id='cboout' onchange='getbarcode()'>
                <?php
                $sql = "select no_out isi, no_out tampil from out_material
                group by no_out";
                IsiCombo($sql, '', 'Pilih Nomor Dok Out #');
                ?>
              </select>
            </div>
            <div class='form-group'>
              <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
            </div>
          </div>
          <div class='col-md-4'>
            <div class='form-group'>
              <label>Tgl Retur *</label>
              <input type='text' class='form-control' id='datepicker1' name='txt_tglretur' value='<?php echo $tgl_retur; ?>'>
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
                  <label>No Req</label>
                  <input type='text' class='form-control' id='txtnoreq' name='txtnoreq' readonly>
                </div>
              </div>
            </div>
          </div>
          <div class='col-md-5'>
            <div class='form-group'>
              <label>Barcode # *</label>
              <select class='form-control select2' multiple='multiple' style='width: 100%;' name='cbobarcode' id='cbobarcode' onchange='getdatabarcode()'>
              </select>
            </div>
            <div class='form-group'>
              <label>Keterangan</label>
              <textarea row='5' class='form-control' name='txt_ket' id='txt_ket' placeholder='Masukkan Keterangan'><?php echo $notes; ?></textarea>
            </div>
          </div>
          <div class='box-body'>
            <div id='detail_item'></div>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php }
# END COPAS ADD
#if ($id_req=="") {
if ($mod == "retur_material") {

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
      <a href='../wh/?mod=retur_material_new' class='btn btn-primary btn-s'>
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
            <th>Nomor Retur</th>
            <th>Tgl Retur</th>
            <th>No Out</th>
            <th>No Req</th>
            <th>Job Order</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Qty</th>
            <th>Act</th>
            <th></th>
          </tr>
        </thead>
        <tbody>

          <?php
          # QUERY TABLE
          $query = mysql_query("select td.*,b.jml_no_retur from
          (
          select
                    no_retur,
                    tgl_retur,
                    no_out,
                    no_req,
                    kode_barang,
                    nama_barang,
                    job_order,
                    concat(sum(qty),' ',unit) qty_retur,
                    unit,
                    keterangan,
                    dibuat,
                    tgl_input,
                    cancel
                      from retur_material a
                    where cancel = 'N' and a.tgl_retur >= '$tglf' and a.tgl_retur <= '$tglt'
                    group by no_retur,kode_barang, nama_barang, unit, job_order
                    order by no_retur asc ) td
          inner join 
          (				
          select count(no_retur) jml_no_retur,no_retur, kode_barang, nama_barang, job_order, unit from
                    (
                    select * from retur_material where cancel = 'N' and tgl_retur >= '$tglf' and tgl_retur <= '$tglt' group by kode_barang, nama_barang, job_order, unit,no_retur) a
                    group by no_retur
                               ) b on td.no_retur = b.no_retur and td.kode_barang = b.kode_barang and td.nama_barang = b.nama_barang and td.unit = b.unit	");
          $no = 1;
          $supplier = null;
          $sizeStartRow = 0;
          $counter = 0;
          while ($data = mysql_fetch_array($query)) {
            $nodok = $data[no_retur];
            echo "<tr>";
            echo "
            <td>$data[no_retur]</td>";
            echo "
            <td>" . fd_view($data[tgl_retur]) . "</td>";
            echo "
            <td>$data[no_out]</td>
            <td>$data[no_req]</td>
            <td>$data[job_order]</td>
            <td>$data[kode_barang]</td>
            <td>$data[nama_barang]</td>
            <td>$data[qty_retur]</td>
          <td>         
            <a href='pdfBarcode_retur_material.php?mode=barcode&noretur=$data[no_retur]&kdbrg=$data[kode_barang]&nmbrg=$data[nama_barang]&jo=$data[job_order]&unit=$data[unit]'
             data-toggle='tooltip' title='Preview' target='_blank'><i class='fa fa-barcode'></i></a>
          </td>";

            if ($supplier != $data[jml_no_retur]) {
              $sizeStartRow = $counter;
              $sizeCol = '<td rowspan = "' . $data[jml_no_retur] . '" style="float: center;vertical-align: middle;"><a href="cetaksj_retur.php?no_dok=' . $nodok . '" 
          data-toggle="tooltip" target="_blank" ><i class="fa fa-print"></i>
          </a> </td>';
            } else {
              //change the rowspan value at the start position, as we know it's increased
              $sizeCol = "<td hidden></td>";
            }
            echo "
        $sizeCol";

            echo "</tr>";
            $supplier = $data[no_retur]; //update the previous size value
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

  $querybpb = mysql_query("SELECT * from in_material where '$id'");
  $databpb      = mysql_fetch_array($querybpb);
  $no_dok       = $databpb['no_dok'];
  $kode_barang  = $databpb['kode_barang'];
  $nama_barang  = $databpb['nama_barang'];
  $job_order   = $databpb['job_order'];
  $supplier   = $databpb['supplier'];
  $no_po   = $databpb['no_po'];
  $qty          = $databpb['qty'];
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
              <input type='text' class='form-control' disabled value='<?php echo $no_dok ?>'>
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
              <input type='text' class='form-control' disabled value='<?php echo $nama_barang ?>'>
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