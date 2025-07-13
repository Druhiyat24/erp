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
  $txttgl_now = date("Y-m-d");
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


    function addRow(tableID) {
      var tableID = "tbody2";
      var table = document.getElementById(tableID);
      var rowCount = table.rows.length;
      var row = table.insertRow(rowCount);


      $(document).ready(function() {
        $('.tanggal').datepicker({
          format: "dd-mm-yyyy",
          autoclose: true
        });
      });
      $(function() {
        //Initialize Select2 Elements
        var selectcoba = rowCount;
        $('.rowCount').select2({
          theme: 'bootstrap4'
        })
        //Initialize Select2 Elements
        $('.select2add').select2({
          theme: 'bootstrap4'
        })
      });
      $coa = '';
      $j = 1;
      var element1 = '<tr ><td><input type="text" class="form-control" id= "kode_barang" name="kode_barang" placeholder="" autocomplete="off"></td><td><input type="text" class="form-control" id= "nama_barang" name="nama_barang" placeholder="" autocomplete="off"></td><td><input type="text" class="form-control" id= "job_order" name="job_order" placeholder="" autocomplete="off"></td><td><input type="text" class="form-control" id= "qty" name="qty" placeholder="" autocomplete="off"></td><td><select class="form-control" name="pil_unit" id="pil_unit" data-live-search="true"> <option value="-" > - </option><?php $sql = mysqli_query($conn_li, "select kode_unit,nama_unit from m_unit where cancel = 'N'");
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              foreach ($sql as $coa) : ?> <option value="<?= $coa["kode_unit"]; ?>"><?= $coa["nama_unit"]; ?> </option><?php endforeach; ?></select></td><td><input name="chk_a[]" type="checkbox" class="checkall_a" value=""></td></tr>';
      row.innerHTML = element1;

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
        cell0.innerHTML = '<input id= "kode_barang" name="kode_barang" type="text" class="form-control" >';
        cell1.innerHTML = '<input id= "kode_barang" name="kode_barang" type="text" class="form-control" >';
        cell2.innerHTML = '<input id= "kode_barang" name="kode_barang" type="text" class="form-control" >';
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


    function hapusbaris() {
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
        <form id="form-simpan_newmaterial" method="post" action='insert_inmaterial.php?mod=simpan'>

          <div class='col-md-3'>
            <div class='form-group'>
              <label>No Dokumen #</label>
              <input type='text' class='form-control' name='txtno_dok' id="txtno_dok" value='<?php
                                                                                              $sql = mysqli_query($conn_li, "select max(no_dok) from in_material where MONTH(tgl_dok) = MONTH('$txttgl_now') and YEAR(tgl_dok) = YEAR('$txttgl_now')");
                                                                                              $row = mysqli_fetch_array($sql);
                                                                                              $kodepay = $row['max(no_dok)'];
                                                                                              $urutan = (int) substr($kodepay, 12, 4);
                                                                                              $urutan++;
                                                                                              $bln =  date("m", strtotime($txttgl_now));
                                                                                              $thn =  date("y", strtotime($txttgl_now));
                                                                                              $huruf = "GK/IN/$bln$thn/";
                                                                                              $kodepay = $huruf . sprintf("%04s", $urutan);
                                                                                              $huruf = substr($kodepay, 0, 11);
                                                                                              $angka = substr($kodepay, 12, 5) || 0;
                                                                                              $angka2 = $angka + 12;
                                                                                              $angka3 = sprintf("%05s", $angka2);

                                                                                              echo $kodepay; ?>' readonly>
            </div>
            <div class='form-group'>
              <label>Tgl Dok *</label>
              <input type='text' class='form-control' id='tanggal' name='tanggal' value='<?php echo $txttgl_dok; ?>'>
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
              <!-- <button type='submit' name='simpan' id='simpan' class='btn btn-primary'>Back</button> -->
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
              <!-- <button onclick="addNewRow()" class="btn btn-primary">Tambah Data
              </button>

              <button type="button" class="btn btn-danger" onclick="hapusbaris()">Hapus Data</button> -->
              <button type="button" class="btn btn-primary" onclick="addRow('tbody2')">Tambah Data</button>
              <button type="button" class="btn btn-danger" onclick="hapusbaris()">Hapus Data</button>
            </div>
          </div>
        </div>

        <div class='box-body'>
          <table id="employee-table" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Job Order</th>
                <th>Qty</th>
                <th>Unit</th>
                <th>Act</th>
              </tr>
            </thead>

            <tbody id="tbody2">
            </tbody>
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
      <a href='../wh/?mod=in_material_new' class='btn btn-primary btn-s'>
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
            <th>Nomor Dok</th>
            <th>Tgl Dok</th>
            <th>No PO#</th>
            <th>Supplier</th>
            <th>No SJ</th>
            <th>Job Order</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Qty</th>
            <th>Status Lokasi</th>
            <th>Act</th>
            <th></th>
            <th></th>
          </tr>
        </thead>
        <tbody>

          <?php
          # QUERY TABLE
          $query = mysql_query("select *, round(coalesce(100/(qtybpb / jml_roll),0),1) percent from (select id,no_dok,tgl_dok,supplier,no_po,tipe_pembelian,no_sj,keterangan,kode_barang,nama_barang,job_order,qty qtybpb,CONCAT(qty,' ',unit) qty,unit,CONCAT(dibuat,' (',tgl_input,')') dibuat,tgl_input,cancel from in_material a 
          where tgl_dok >= '$tglf' and tgl_dok <= '$tglt') a inner join (select no_dok nodok,count(no_dok) jml_no_dok from in_material GROUP BY no_dok) b on b.nodok = a.no_dok 
          left join
          (select id_in_material,sum(roll_qty) jml_roll from in_material_det where cancel = 'N' and retur = '' group by id_in_material) c on a.id = c.id_in_material
          order by no_dok");
          $no = 1;
          $supplier = null;
          $sizeStartRow = 0;
          $counter = 0;
          while ($data = mysql_fetch_array($query)) {
            $nodok = $data[no_dok];
            if ($data['percent'] == "0") {
              $col = "brown'";
              $width = "25";
              $stat = "hidden";
            } else if ($data['percent'] > "0" && $data['percent'] <= "50") {
              $col = "goldenrod";
              $width = "50";
              $stat = "true";
            } else if ($data['percent'] > "50" && $data['percent'] <= "99.9") {
              $col = "CornflowerBlue'";
              $width = "75";
              $stat = "true";
            } else if ($data['percent'] >= "100") {
              $col = "mediumseagreen'";
              $width = "100";
              $stat = "true";
            }

            echo "<tr>";
            echo "
            <td>$data[no_dok]</td>";
            echo "
            <td>" . fd_view($data[tgl_dok]) . "</td>";
            echo "
            <td>$data[no_po]</td>
            <td>$data[supplier]</td>
            <td>$data[no_sj]</td>
            <td>$data[job_order]</td>
            <td>$data[kode_barang]</td>
            <td>$data[nama_barang]</td>
            <td>$data[qty]</td>
            <td>  
            <div class='progress-bar' role='progressbar'  style='width: $width%;background-color:$col	;color: black;' aria-valuenow='50'
             aria-valuemin='0' aria-valuemax='100'>$data[percent]%</div>
            </td>
          <td>
          <a href='../wh/?mod=in_material_item&id_in_material=$data[id]'
          data-toggle='tooltip' target='_blank' ><i class='fa fa-pencil'></i>
          </a> 
          </td>
          <td>         
          <a href='pdfBarcode_in_material.php?mode=barcode&id=$data[id]'
           data-toggle='tooltip' title='Preview' target='_blank'><i class='fa fa-barcode' style='visibility:$stat;'></i></a>
          </td>
          ";

            if ($supplier != $data[no_dok]) {
              $sizeStartRow = $counter;
              $sizeCol = '<td rowspan = "' . $data[jml_no_dok] . '" style="float: center;vertical-align: middle;"><a href="cetaksj.php?no_dok=' . $nodok . '" 
          data-toggle="tooltip" target="_blank" ><i class="fa fa-print"></i>
          </a> </td>';
            } else {
              //change the rowspan value at the start position, as we know it's increased
              $sizeCol = "<td hidden></td>";
            }
            echo "
        $sizeCol";

            echo "</tr>";
            $supplier = $data[no_dok]; //update the previous size value
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