<?php
if (empty($_SESSION['username'])) {
  header("location:../../index.php");
}
$rscomp = mysql_fetch_array(mysql_query("select * from mastercompany"));
$st_company = $rscomp["status_company"];
$logo_company = $rscomp["logo_company"];
?>
<?php
if ($mod == "upload_exim") {

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
  }

  ?>

  <script type='text/javascript'>
    function cancelDoc(nodok) {
  // alert('test');
  if (!confirm("Yakin ingin menghapus dokumen ini?")) return;

  $.ajax({
    type: "POST",
    url: "ajax_bc27in.php?modeajax=delete_upload",
    data: {
      no_dokumen: nodok
    },
    success: function(response) {
      alert(response);
      location.reload();
    },
    error: function(xhr, status, error) {
      alert("Terjadi kesalahan: " + error);
    }
  });
}

</script>



<div class="box">
  <div class="box-header">
    <a href='../shp/?mod=proses_upload_exim' class='btn btn-primary btn-s'>
      <i class='fa fa-plus'></i> New
    </a>

    <a href='../shp/?mod=bc27_new&id_h=' class='btn btn-primary btn-s'>
      <i class='fa fa-plus'></i> BC 2.7 IN
    </a>

  </div>

  <div class='row'>
    <form action="" method="post">

      <div class="box-header">
        <div class='col-md-2'>
          <label>From Date : </label>
          <input type='text' class='form-control' id='datepicker1' name='frdate' placeholder='Masukkan From Date' value='<?php echo $perf; ?>'>

        </div>
        <div class='col-md-2'>
          <label>To Date : </label>
          <input type='text' class='form-control' id='datepicker2' name='kedate' placeholder='Masukkan To Date' value='<?php echo $pert; ?>'>
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
      <table id="examplefix" class="display responsive" style="width:100%">
        <thead>
          <tr>
            <th>No</th>
            <th>Jenis Dokumen</th>
            <th>No Dokumen Upload</th>
            <th>User Upload</th>
            <th>Uploaded Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE
          $query = mysql_query("(select CONCAT('BC ',kode_dokumen) jenis_dokumen, no_dokumen, created_by, created_date, 'POST' status from exim_header where DATE(created_date) BETWEEN '$tglf' and '$tglt' GROUP BY no_dokumen)
            UNION
            (select jenis_dok, no_dok, created_by, created_date, status from exim_ceisa_manual where DATE(created_date) BETWEEN '$tglf' and '$tglt' GROUP BY no_dok)");
          $no = 1;
          while ($data = mysql_fetch_array($query)) {
            $no_dokumen = htmlspecialchars($data['no_dokumen'], ENT_QUOTES);
            $status = $data['status'];

            echo "<tr>";
            echo "
            <td>$no</td>
            <td>$data[jenis_dokumen]</td>
            <td>$data[no_dokumen]</td>
            <td>$data[created_by]</td>";
            echo "
            <td>$data[created_date]</td>";
            if ($status == 'POST') {
              echo "<td><button type='button' class='btn btn-danger' onclick=\"cancelDoc('$no_dokumen')\">Cancel</button></td>";
            }else{
              echo "<td><b>Cancelled</b></td>";
            }
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
<?php if ($mod == "proses_upload_exim") {


  $reqdate = date("d M Y");

  ?>
  <style type="text/css">
    form {
      max-width: 500px;
      padding: 20px;
    }

    label {
      display: block;
      margin-bottom: 10px;
      font-weight: bold;
      color: #333;
    }

    input[type="file"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
      margin-bottom: 20px;
      background-color: #f7f7f7;
    }

    input[type="submit"] {
      background-color: #4CAF50;
      color: white;
      padding: 7px 17px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    input[type="submit"]:hover {
      background-color: #45a049;
    }

  </style>


  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <form id="uploadForm" action="simpan_upload.php" method="post" enctype="multipart/form-data">
          <label for="file">Upload Excel File:</label>
          <input type="file" name="file" id="file" accept=".xls,.xlsx">
          <input type="submit" value="Upload">
          <button class="btn btn-warning" type="button" id="backButton" onclick="window.location.href='../shp/?mod=upload_exim'">Kembali</button>
        </form>

      </div>
    </div>

  <?php }
  ?>


  <?php if ($mod == "show_detail") {
    $no_dok = base64_decode($_GET['no_dok']);
    $nm_tbl = base64_decode($_GET['nm_tbl']);
    $username =  $_SESSION['username'];

    $sql_cek_data  = mysql_query("select DISTINCT date(created_date) tgl_dok from $nm_tbl where no_dokumen = '$no_dok'");
    $dataupload = mysql_fetch_array($sql_cek_data);
    $tgl_dok = fd_view($dataupload['tgl_dok']);
    ?>


    <div class='box'>
      <div class='box-body'>
        <div class='row'>
          <form method='post' name='form'>
            <div class='col-md-3'>
              <div class='form-group'>
                <label>Nomor Dokumen :</label>
                <input type='text' class='form-control' name='no_dok' readonly value='<?php echo $no_dok; ?>'>
              </div>
            </div>
            <div class='col-md-2'>
              <div class='form-group'>
                <label>Tanggal Upload :</label>
                <input type='text' class='form-control' id='datepicker1_memo' name='tgl_dok' value='<?php echo $tgl_dok; ?>' readonly>
              </div>
            </div>
            <div class='col-md-12'>
              <div class='box-body'>
                <table id="examplememo" class="table table-striped" style="width:100%">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Kode kantor</th>
                      <th>No Daftar</th>
                      <th>Tgl Daftar</th>

                      <?php 
                      if ($nm_tbl == 'exim_bc261' OR $nm_tbl == 'exim_bc262') {
                        echo "<th>Nama Penerima</th>
                        <th>Curr</th>
                        <th>Asal Barang</th>
                        <th>Tujuan Pengirim</th>
                        <th>CIF</th>
                        <th>CIF IDR</th>
                        <th>Netto</th>
                        <th>Seri</th>
                        <th>HS Code</th>
                        <th>Uraian Barang</th>
                        <th>Jml</th>
                        <th>Satuan</th>";
                      }elseif ($nm_tbl == 'exim_bc27in') {
                        echo "<th>Nama Pengirim</th>
                        <th>Curr</th>
                        <th>CIF</th>
                        <th>Harga Serah</th>
                        <th>Netto</th>
                        <th>Tujuan Pengirim</th>
                        <th>Seri</th>
                        <th>HS Code</th>
                        <th>Uraian Barang</th>
                        <th>Jml</th>
                        <th>Satuan</th>";
                      }elseif ($nm_tbl == 'exim_bc27out') {
                        echo "<th>Nama Penerima</th>
                        <th>Curr</th>
                        <th>CIF</th>
                        <th>Harga Serah</th>
                        <th>Netto</th>
                        <th>Tujuan Pengirim</th>
                        <th>Seri</th>
                        <th>HS Code</th>
                        <th>Uraian Barang</th>
                        <th>Jml</th>
                        <th>Satuan</th>";
                      }elseif ($nm_tbl == 'exim_bc40' OR $nm_tbl == 'exim_bc41') {
                        echo "<th>Nama Pengirim</th>
                        <th>Tujuan Pengirim</th>
                        <th>Netto</th>
                        <th>Harga Serah</th>
                        <th>Seri</th>
                        <th>HS Code</th>
                        <th>Uraian Barang</th>
                        <th>Jml</th>
                        <th>Satuan</th>";
                      }elseif ($nm_tbl == 'exim_bc23') {
                        echo "<th>Nama Pemasok</th>
                        <th>Negara Pemasok</th>
                        <th>Kurs</th>
                        <th>CIF</th>
                        <th>Netto</th>
                        <th>Kategori Barang</th>
                        <th>Seri</th>
                        <th>HS Code</th>
                        <th>Uraian Barang</th>
                        <th>Jml</th>
                        <th>Satuan</th>
                        <th>Trf BM</th>
                        <th>Trf PPN</th>
                        <th>Trf PPH</th>
                        <th>BM</th>
                        <th>PPN</th>
                        <th>PPH</th>";
                      }elseif ($nm_tbl == 'exim_bc25') {
                        echo "<th>Nama Pemasok</th>
                        <th>Curr</th>
                        <th>Kurs</th>
                        <th>Harga Serah</th>
                        <th>Netto</th>
                        <th>Kategori Barang</th>
                        <th>Seri</th>
                        <th>HS Code</th>
                        <th>Uraian Barang</th>
                        <th>Jml</th>
                        <th>Satuan</th>";
                      }elseif ($nm_tbl == 'exim_bc30') {
                        echo "<th>Nama Pemasok</th>
                        <th>Negara Pemasok</th>
                        <th>Curr</th>
                        <th>Kurs</th>
                        <th>Devisa</th>
                        <th>Netto</th>
                        <th>Nilai Makloon</th>
                        <th>Jenis Ekspor</th>
                        <th>Cara Serah</th>
                        <th>Seri</th>
                        <th>HS Code</th>
                        <th>Uraian Barang</th>
                        <th>Jml</th>
                        <th>Satuan</th>";
                      }
                      ?>

                    </tr>
                  </thead>
                  <tbody>
                    <?php
                  # QUERY TABLE
                    if ($nm_tbl == 'exim_bc261' OR $nm_tbl == 'exim_bc262') {
                      $query = mysql_query("select kd_kantor, no_daftar, tgl_daftar, nama_penerima, val_curr, asal_barang, tujuan_pengirim, cif, cif_idr, netto, seri, hs_code, uraian_barang, jml, sat from $nm_tbl where no_dokumen = '$no_dok'");
                    }elseif ($nm_tbl == 'exim_bc27in') {
                      $query = mysql_query("select kd_kantor, no_daftar, tgl_daftar, nama_pengirim, val_curr, cif, harga_serah, netto, tujuan_pengiriman, seri, hs_code, uraian_barang, jml, sat from $nm_tbl where no_dokumen = '$no_dok'");
                    }elseif ($nm_tbl == 'exim_bc27out') {
                      $query = mysql_query("select kd_kantor, no_daftar, tgl_daftar, nama_penerima, val_curr, cif, harga_serah, netto, tujuan_pengiriman, seri, hs_code, uraian_barang, jml, sat from $nm_tbl where no_dokumen = '$no_dok'");
                    }elseif ($nm_tbl == 'exim_bc40' OR $nm_tbl == 'exim_bc41') {
                      $query = mysql_query("select kd_kantor, no_daftar, tgl_daftar, nama_pengirim, tujuan_pengiriman, netto, harga_serah, seri, hs_code, uraian_barang, jml, sat from $nm_tbl where no_dokumen = '$no_dok'");
                    }elseif ($nm_tbl == 'exim_bc23') {
                      $query = mysql_query("select kd_kantor, no_daftar, tgl_daftar, nama_pemasok, negara_pemasok, kurs, cif, netto, kode_kategori_barang, seri, hs_code, uraian_barang, jml, sat, trf_bm, trf_ppn, trf_pph, bm, ppn, pph from $nm_tbl where no_dokumen = '$no_dok'");
                    }elseif ($nm_tbl == 'exim_bc25') {
                      $query = mysql_query("select kd_kantor, no_daftar, tgl_daftar, nama_pemasok, val_curr, kurs, harga_serah, netto, kategori_barang, seri, hs_code, uraian_barang, jml, sat from $nm_tbl where no_dokumen = '$no_dok'");
                    }elseif ($nm_tbl == 'exim_bc30') {
                      $query = mysql_query("select kd_kantor, no_daftar, tgl_daftar, nama_penerima, negara_penerima, val_curr, kurs, total_devisa, netto, nilai_makloon, jenis_ekspor, cara_serah, seri, hs_code, uraian_barang, jml, sat from $nm_tbl where no_dokumen = '$no_dok'");
                    }
                    $no = 1;
                    while ($data = mysql_fetch_array($query)) {

                      echo "<tr>";
                      echo "
                      <td>$no</td>
                      <td>$data[kd_kantor]</td>
                      <td>$data[no_daftar]</td>
                      <td>$data[tgl_daftar]</td>";

                      if ($nm_tbl == 'exim_bc261' OR $nm_tbl == 'exim_bc262') {
                        echo "
                        <td>$data[nama_penerima]</td>
                        <td>$data[val_curr]</td>
                        <td>$data[asal_barang]</td>
                        <td>$data[tujuan_pengirim]</td>
                        <td>$data[cif]</td>
                        <td>$data[cif_idr]</td>
                        <td>$data[netto]</td>
                        <td>$data[seri]</td>
                        <td>$data[hs_code]</td>
                        <td>$data[uraian_barang]</td>
                        <td>$data[jml]</td>
                        <td>$data[sat]</td>";
                      }elseif ($nm_tbl == 'exim_bc27in') {
                        echo "
                        <td>$data[nama_pengirim]</td>
                        <td>$data[val_curr]</td>
                        <td>$data[cif]</td>
                        <td>$data[harga_serah]</td>
                        <td>$data[netto]</td>
                        <td>$data[tujuan_pengiriman]</td>
                        <td>$data[seri]</td>
                        <td>$data[hs_code]</td>
                        <td>$data[uraian_barang]</td>
                        <td>$data[jml]</td>
                        <td>$data[sat]</td>";
                      }elseif ($nm_tbl == 'exim_bc27out') {
                        echo "
                        <td>$data[nama_penerima]</td>
                        <td>$data[val_curr]</td>
                        <td>$data[cif]</td>
                        <td>$data[harga_serah]</td>
                        <td>$data[netto]</td>
                        <td>$data[tujuan_pengiriman]</td>
                        <td>$data[seri]</td>
                        <td>$data[hs_code]</td>
                        <td>$data[uraian_barang]</td>
                        <td>$data[jml]</td>
                        <td>$data[sat]</td>";
                      }elseif ($nm_tbl == 'exim_bc40' OR $nm_tbl == 'exim_bc41') {
                        echo "
                        <td>$data[nama_pengirim]</td>
                        <td>$data[tujuan_pengiriman]</td>
                        <td>$data[netto]</td>
                        <td>$data[harga_serah]</td>
                        <td>$data[seri]</td>
                        <td>$data[hs_code]</td>
                        <td>$data[uraian_barang]</td>
                        <td>$data[jml]</td>
                        <td>$data[sat]</td>";
                      }elseif ($nm_tbl == 'exim_bc23') {
                        echo "
                        <td>$data[nama_pemasok]</td>
                        <td>$data[negara_pemasok]</td>
                        <td>$data[kurs]</td>
                        <td>$data[cif]</td>
                        <td>$data[netto]</td>
                        <td>$data[kode_kategori_barang]</td>
                        <td>$data[seri]</td>
                        <td>$data[hs_code]</td>
                        <td>$data[uraian_barang]</td>
                        <td>$data[jml]</td>
                        <td>$data[sat]</td>
                        <td>$data[trf_bm]</td>
                        <td>$data[trf_ppn]</td>
                        <td>$data[trf_pph]</td>
                        <td>$data[bm]</td>
                        <td>$data[ppn]</td>
                        <td>$data[pph]</td>";
                      }elseif ($nm_tbl == 'exim_bc25') {
                        echo "
                        <td>$data[nama_pemasok]</td>
                        <td>$data[val_curr]</td>
                        <td>$data[kurs]</td>
                        <td>$data[harga_serah]</td>
                        <td>$data[netto]</td>
                        <td>$data[kategori_barang]</td>
                        <td>$data[seri]</td>
                        <td>$data[hs_code]</td>
                        <td>$data[uraian_barang]</td>
                        <td>$data[jml]</td>
                        <td>$data[sat]</td>";
                      }elseif ($nm_tbl == 'exim_bc30') {
                        echo "
                        <td>$data[nama_penerima]</td>
                        <td>$data[negara_penerima]</td>
                        <td>$data[val_curr]</td>
                        <td>$data[kurs]</td>
                        <td>$data[total_devisa]</td>
                        <td>$data[netto]</td>
                        <td>$data[nilai_makloon]</td>
                        <td>$data[jenis_ekspor]</td>
                        <td>$data[cara_serah]</td>
                        <td>$data[seri]</td>
                        <td>$data[hs_code]</td>
                        <td>$data[uraian_barang]</td>
                        <td>$data[jml]</td>
                        <td>$data[sat]</td>";
                      }

                      echo "</tr>";
                    $no++; // menambah nilai nomor urut
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class='col-md-6'>
            <div class='form-group'>
              <?php echo "<button class='btn btn-danger' onclick=\"window.location.href='../shp/?mod=show_detail';\" data-toggle='tooltip'>
              <i class='fa fa-paper-plane-o' aria-hidden='true'></i> Back
              </button>"; ?>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php }
?>

<?php if ($mod == "bc27_new") {

  $username =  $_SESSION['username'];
  $sql_clear_tmp  = mysql_query("delete from exim_ceisa_temp where user = '$user'");
  $row_cari = mysql_fetch_array($sql_clear_tmp);

  $reqdate = date("d M Y");
  $jatemdate = date("d M Y");
  $tgldaftar = date("d M Y");
  $tglaju = date("d M Y");

  ?>
  <script type='text/javascript'>
    function getinvoice() {
      var id_buyer = document.form.cbobuyer.value;
      var html = $.ajax({
        type: "POST",
        url: 'ajax_memo.php?modeajax=view_list_invoice',
        data: {
          id_buyer: id_buyer
        },
        async: false
      }).responseText;
      if (html) {
        $("#cboinv").html(html);
      }
    };

    function get_list_invoice() {
      var id_inv = $('#cboinv').val();
      var html = $.ajax({
        type: "POST",
        url: 'ajax_memo.php?modeajax=view_list_invoice_data',
        data: {
          id_inv: id_inv
        },
        async: false
      }).responseText;
      if (html) {
        $("#detail_invoice").html(html);
      }
      $(document).ready(function() {
        var table = $('#examplefix2').DataTable({
          paging: false,
          ordering: true,
          info: false,
          scrollY: "300px",
          scrollX: true,
          scrollCollapse: true,
          paging: false,
          columnDefs: [{
            width: '20%',
            targets: 0
          }],
        });
      });
    };

    function getsubkat() {
      var id_kat = $('#cbokat').val();
      var html = $.ajax({
        type: "POST",
        url: 'ajax_memo.php?modeajax=view_sub_category',
        data: {
          id_kat: id_kat
        },
        async: false
      }).responseText;
      if (html) {
        $("#cbosubkat").html(html);
      }
    };

    function additem() {
      var item = $('#txtitem').val().toUpperCase();
      var satuan = $('#txtsatuan').val();
      var qty = $('#txtqty').val();
      var price = $('#txtprice').val();
      var html = $.ajax({
        type: "POST",
        url: 'ajax_bc27in.php?modeajax=simpan_temp',
        data: {
          item: item,
          satuan: satuan,
          qty: qty,
          price: price
        },
        async: false
      }).responseText;
      if (html) {
        $("#detail_temp").html(html);
      }
      $(document).ready(function() {
        var table = $('#examplefix1').DataTable({
          scrollCollapse: true,
          paging: false,
          searching: false,
          fixedColumns: {
            leftColumns: 1,
            rightColumns: 1
          }
        });
      });
    };

    function del_item(id_temp) {
      var html = $.ajax({
        type: "POST",
        url: 'ajax_bc27in.php?modeajax=delete_temp',
        data: {
          id_temp: id_temp
        },
        async: false
      }).responseText;
      if (html) {
        $("#detail_temp").html(html);
      }
      $(document).ready(function() {
        var table = $('#examplefix1').DataTable({
          scrollCollapse: true,
          paging: false,
          searching: false,
          fixedColumns: {
            leftColumns: 1,
            rightColumns: 1
          }
        });
      });
    };


    function getalamatsupp() {
      var id_supplier = $('#id_supp').val();
      // alert(id_supplier);
      $.post('ajax_bc27in.php?modeajax=get_alamat', 
      { 
        id_supplier: id_supplier 
      }, 
      function(response) {
        if (response) {
          $("#alamat_supp").val(response);
        } else {
          $("#alamat_supp").val('-');
        }
      });
    }

  </script>


  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <form method='post' name='form' action='save_bc27in.php?mod=simpan_header'>
          <div class='col-md-5'>

            <div class='row'>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>No Dokumen</label>
                  <?php
                  $sql = mysql_query("select max(SUBSTR(no_dok,15)) no_trans from exim_ceisa_manual");
                  $row = mysql_fetch_array($sql);
                  $kodepay = $row['no_trans'];
                  $urutan = (int) substr($kodepay, 0, 5);
                  $urutan++;
                  $bln = date("m");
                  $thn = date("y");
                  $huruf = "INS/EXIM/$bln$thn/";
                  $kodepay = $huruf . sprintf("%05s", $urutan);

                  echo'<input type="text" readonly style="font-size: 14px;" class="form-control" id="no_dok" name="no_dok" value="'.$kodepay.'">'
                  ?>
                </div>
              </div>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Jenis Dokumen :</label>
                  <select class='form-control select2' style='width: 100%;' name='tipe_bc' id='tipe_bc' required>
                    <option value="BC 2.7" selected="selected">BC 2.7 IN</option>
                  </select>
                </div>
              </div>
            </div>

            <div class='form-group'>
              <label>Nama Pengirim :</label>
              <select class="form-control select2" name="id_supp" id="id_supp" data-dropup-auto="false" data-live-search="true" onchange="getalamatsupp()">
                <option value="" disabled selected>Pilih Supplier</option>                                                 
                <?php
                $data2 ='';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                  $data2 = isset($_POST['id_supp']) ? $_POST['id_supp']: null;
                }                 
                $sql = mysql_query("select distinct(Supplier),id_supplier from mastersupplier where tipe_sup = 'S' order by Supplier ASC");
                while ($row = mysql_fetch_array($sql)) {
                  $data = $row['Supplier'];
                  $data2 = $row['id_supplier'];
                  if($row['id_supplier'] == $_POST['id_supp']){
                    $isSelected = ' selected="selected"';
                  }else{
                    $isSelected = '';

                  }
                  echo '<option value="'.$data2.'"'.$isSelected.'">'. $data .'</option>';    
                }?>
              </select>
            </div>

            <div class='form-group'>
              <label>Alamat Pengirim :</label>
              <input type='text' class='form-control' name='alamat_supp' id="alamat_supp">
            </div>

            <div class='row'>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>No Daftar :</label>
                  <input type='text' class='form-control' name='no_daftar' id="no_daftar" required placeholder='Masukkan No Daftar'>
                </div>
              </div>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Tgl Daftar :</label>
                  <input type='text' class='form-control' id='datepicker1' name='tgl_daftar' required placeholder='Masukkan Jatuh Tempo' value='<?php echo $tgldaftar; ?>'>
                </div>
              </div>
            </div>

            <div class='row'>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>No Aju :</label>
                  <input type='text' class='form-control' name='no_aju' id="no_aju" required placeholder='Masukkan No Daftar'>
                </div>
              </div>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Tgl Aju :</label>
                  <input type='text' class='form-control' id='datepicker1' name='tgl_aju' required placeholder='Masukkan Jatuh Tempo' value='<?php echo $tglaju; ?>'>
                </div>
              </div>
            </div>
          </div>
          <div class='col-md-7'>
<!--             <div class='form-group'>
              <label>No Invoice * :</label>
              <input type='text' class='form-control' name='inv_buyer' required placeholder='Masukkan No Invoice'>
            </div> -->
            <div class='col-md-5'>
              <div class='form-group'>
                <label>Currency :</label>
                <select class='form-control select2' style='width: 100%;' name='curr' id='curr' required>
                  <?php
                  $sql = "select nama_pilihan isi, nama_pilihan tampil 
                  from masterpilihan where kode_pilihan = 'Curr' and nama_pilihan in ('IDR','USD')";
                  IsiCombo($sql, $curr, 'Pilih Currency #');
                  ?>
                </select>
              </div>
            </div>
            <div class='col-md-10'>
              <div class='form-group'>
                <label>Notes :</label>
                <textarea id='txtnotes' class='form-control' name='txtnotes' rows='3' cols='20'></textarea>
              </div>
            </div>
            <div class='col-md-6'>
              <div class='form-group'>
                <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
                <button type="button" name="kembali" class="btn btn-danger" onclick="window.location.href='../shp/?mod=upload_exim';">Kembali</button>

              </div>
            </div>
          </div>
          
        </div>
      </form>
    </div>

    <div class='box-body'>
      <form method='post' name='form1'>
        <h4> Rincian Item</h4>
        <div class='row'>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Nama Barang :</label>
              <input type="text" class="form-control" name="txtitem" id="txtitem" required placeholder="Masukkan Item" autocomplete="off" style="text-transform: uppercase;">
            </div>
          </div>

          <div class='col-md-2'>
            <div class='form-group'>
              <label>Satuan :</label>
              <select class='form-control select2' style='width: 100%;' name='txtsatuan' id='txtsatuan'>
                <?php
                $sql = "select nama_pilihan isi, nama_pilihan tampil from masterpilihan where kode_pilihan = 'satuan'";
                IsiCombo($sql, '', 'Pilih Satuan #');
                ?>
              </select>
            </div>
          </div>

          <div class='col-md-2'>
            <div class='form-group'>
              <label>Qty :</label>
              <input type='text' class='form-control text-right' name='txtqty' onfocus="this.value=''" id='txtqty' autocomplete="off">
            </div>
          </div>

          <div class='col-md-2'>
            <div class='form-group'>
              <label>Price :</label>
              <input type='text' class='form-control text-right' name='txtprice' onfocus="this.value=''" id='txtprice' autocomplete="off">
            </div>
          </div>

          <div class='col-md-1'>
            <div class='form-group'>
            </div>
            <div class='form-group' style="padding:10px">
              <button type='button' name='submit_temp' class='btn btn-primary' onclick='additem()'>Tambah</button>
            </div>
          </div>

        </div>
        <div id='detail_temp'></div>
      </form>
    </div>
  </div>
<?php }

