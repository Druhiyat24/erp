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

<?php if ($mod == "in_material_new") {
  $txttgl_dok = date("d M Y");
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

    function addNewRow() {
      var table = document.getElementById("employee-table");
      var rowCount = table.rows.length;
      var cellCount = table.rows[0].cells.length;
      var row = table.insertRow(rowCount);

      for (var i = 0; i < cellCount; i++) {
        var cell = row.insertCell(i);
        // if (i < cellCount - 1) {
        //   cell.innerHTML = '<input type="text" class="form-control" />';
        // } else {
        //   cell.innerHTML = '<input class="btn btn-danger" ' +
        //     ' type="button" value="Hapus" onclick="deleteRow(this)" />';
        // }
        var cell0 = row.insertCell(0);
        var cell1 = row.insertCell(1);
        var cell2 = row.insertCell(2);
        var cell3 = row.insertCell(3);
        var cell4 = row.insertCell(4);
        var cell5 = row.insertCell(5);
        // var deleteButton = '<input name="chk_a[]" type="checkbox" class="checkall_a" value=""/>';
        cell0.innerHTML = '<input type="text" class="form-control" >';
        cell1.innerHTML = '<input type="text" class="form-control" >';
        cell2.innerHTML = '<input type="text" class="form-control" >';
        cell3.innerHTML = '<input type="text" class="form-control" >';
        cell4.innerHTML = '<input type="text" class="form-control" >';
        // cell5.innerHTML = '<input name="chk_a[]" type="checkbox" class="checkall_a" value=""/>';
        cell5.append(deleteButton);
      }
    }
    /* This method will delete a row */
    function deleteRow(ele) {
      var table = document.getElementById('employee-table');
      var rowCount = table.rows.length;
      if (rowCount <= 1) {
        alert("There is no row available to delete!");
        return;
      }
      if (ele) {
        //delete specific row
        ele.parentNode.parentNode.remove();
      } else {
        //delete last row
        table.deleteRow(rowCount - 1);
      }
    }


    function deleteRow() {
      try {
        var table = document.getElementById("employee-table");
        var rowCount = table.rows.length;
        for (var i = 0; i < rowCount; i++) {
          var row = table.rows[i];
          var chkbox = row.cells[5].childNodes[0];
          if (null != chkbox && true == chkbox.checked) {
            if (rowCount <= 1) {
              alert("Tidak dapat menghapus semua baris.");
              break;
            }
            table.deleteRow(i);
            rowCount--;
            i--;
          }
        }
      } catch (e) {
        alert(e);
      }
    }
  </script>


  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <form id="form-simpan" method="post">

          <div class='col-md-3'>
            <div class='form-group'>
              <label>No Dokumen #</label>
              <input type='text' class='form-control' name='txtno_dok' id="txtno_dok" value='<?php echo $txtno_dok; ?>'>
            </div>
            <div class='form-group'>
              <label>Tgl Dok *</label>
              <input type='text' class='form-control' id='datepicker1' name='txttgldok' value='<?php echo $txttgl_dok; ?>'>
            </div>
            <div class='form-group'>
              <label>Supplier #</label>
              <input type='text' class='form-control' id='txtsupp' name='txtsupp' value='<?php echo $txtsupp; ?>'>
            </div>
            <div class='form-group'>
              <label>No PO #</label>
              <input type='text' class='form-control' id='txtpo' name='txtpo' value='<?php echo $txtpo; ?>'>
              </select>
            </div>
          </div>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Tipe Pembelian *</label>
              <select class='form-control select2' style='width: 100%;' name='txtjns_in' id='txtjns_in'>
                <?php
                $sqljns_out = "select nama_trans isi,nama_trans tampil from mastertransaksi where 
                            jenis_trans='IN' and jns_gudang = 'FABRIC' order by id";
                IsiCombo($sqljns_out, '', 'Pilih Jenis Pemasukan');
                ?>
              </select>
            </div>
            <div class='form-group'>
              <label>Nomor Invoice / SJ *</label>
              <input type='text' class='form-control' name='txtinvno' id='txtinvno' placeholder='Masukkan Nomor Invoice / SJ' value='<?php echo $txtinvno; ?>'>
            </div>
            <div class='form-group'>
              <label>Keterangan</label>
              <textarea row='5' class='form-control' name='txtremark' id='txtremark' placeholder='Masukkan Notes'><?php echo $notes; ?></textarea>
            </div>
            <div class='form-group'>
              <button type='submit' name='simpan' id='simpan' class='btn btn-primary'>Simpan</button>
            </div>
          </div>

          <div class='row'>
            <div class='col-md-6'>
              <div class='form-group'>

              </div>
            </div>
          </div>

        </form>

        <div class='row'>
          <div class='col-md-12'>
            <div class='form-group' style="padding-left: 18px;">
              <button onclick="addNewRow()" class="btn btn-primary">Tambah Data
              </button>

              <button type="button" class="btn btn-danger" onclick="hapusbaris()">Hapus Data</button>
            </div>
          </div>
        </div>

        <div class='box-body'>
          <table id="employee-table" class="table table-bordered table-striped">
            <tr>
              <th>Kode Barang</th>
              <th>Nama Barang</th>
              <th>Job Order</th>
              <th>Qty</th>
              <th>Unit</th>
              <th>Act</th>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
<?php }
# END COPAS ADD
#if ($id_req=="") {
if ($mod == "in_material") {

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
  <div class=" box">
    <div class="box-header">
      <a href='../wh/?mod=in_material_new' class='btn btn-primary btn-s'>
        <i class='fa fa-plus'></i> New
      </a>
    </div>

    <div class='row'>
      <form action="">

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
              <button type='submit' name='submit_excel' class='btn btn-success'>Export Excel</button>
            </div>
          </div>

        </div>
    </div>


    <div class="box-body">
      <table id="examplefix3" class="display responsive" style="width: 100%;font-size:13px;">
        <thead>
          <tr>
            <th>Nomor Dok</th>
            <th>Tgl Dok</th>
            <th>No PO#</th>
            <th>Supplier</th>
            <th>No SJ</th>
            <th>Tipe Pembelian</th>
            <th>Dibuat</th>
            <th>Status Lokasi</th>
            <th>Status</th>
            <th>Act</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE
          $query = mysql_query("select * from in_material a where tgl_dok >= '$tglf' and tgl_dok <= '$tglt'");
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
            <td>$data[no_dok]</td>";
            echo "
            <td>" . fd_view($data[tgl_dok]) . "</td>";
            echo "
            <td>$data[no_po]</td>
            <td>$data[supplier]</td>
            <td>$data[no_sj]</td>
            <td>$data[tipe_pembelian]</td>
            <td>$data[dibuat]</td>
            <td>$data[status_lok]</td>
            <td>$data[status_lok]</td>";
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
          }
          ?>
        </tbody>
      </table>
      </form>
    </div>
  </div>
<?php }
?>