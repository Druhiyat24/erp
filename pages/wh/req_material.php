<?php
if (empty($_SESSION['username'])) {
  header("location:../../index.php");
}

# START CEK HAK AKSES KEMBALI

# END CEK HAK AKSES KEMBALI

$rscomp = mysql_fetch_array(mysql_query("select * from mastercompany"));
$st_company = $rscomp["status_company"];
$logo_company = $rscomp["logo_company"];

?>
<?php if ($mod == "req_material") {

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
  window.open ('?mod=reqmaterial_excel&from=$from&to=$to&dest=xls', '_blank');
    </script>";
  }

?>

  <div class="box">
    <div class="box-header">
      <a href='../wh/?mod=new_req_material' class='btn btn-primary btn-s'>
        <i class='fa fa-plus'></i> New
      </a>
    </div>

    <div class='row'>
      <form action="" method="post">

        <div class="box-header">
          <div class='col-md-2'>
            <label>Tgl Awal Req : </label>
            <input type='text' class='form-control' id='datepicker1' name='frdate' placeholder='Masukkan From Date' value='<?php echo $perf; ?>'>

          </div>
          <div class='col-md-2'>
            <label>Tgl Akhir Req : </label>
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
            <th style="width: 2%;">No</th>
            <th style="width: 5%;">Nomor Req</th>
            <th style="width: 8%;">Tgl Req</th>
            <th style="width: 10%;">Job Order</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th style="width: 5%;">Qty</th>
            <th style="width: 5%;">Qty Out</th>
            <th style="width: 5%;">Unit</th>
            <th>Tipe Req</th>
            <th>Tujuan</th>
            <th style="width: 10%;">Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE
          $query = mysql_query("select a.*,b.jml_noreq,round(coalesce(100/(qty / qtytot),0),1) percent,round(coalesce(qtytot,0),2) qtytot from 
          (select * from req_material where tgl_req >= '$tglf' and tgl_req <= '$tglt') a
           inner join (select no_req noreq,COUNT(no_req) jml_noreq from req_material GROUP BY no_req) b on b.noreq = a.no_req
           left join (select no_req,kode_barang,nama_barang,job_order, sum(qty)qtytot, unit from out_material a group by no_req,kode_barang,nama_barang, job_order, unit ) c 
           on a.no_req = c.no_req and a.kode_barang = c.kode_barang and a.nama_barang = c.nama_barang and a.job_order	= c.job_order and a.unit = c.unit
           ");
          $no = 1;
          $supplier = null;
          $sizeStartRow = 0;
          $counter = 0;
          while ($data = mysql_fetch_array($query)) {
            if ($data['percent'] == "0") {
              $col = "brown'";
              $width = "25";
              $stat = "hidden";
            } else if ($data['percent'] > "0" && $data['percent'] <= "50") {
              $col = "goldenrod";
              $width = "50";
              $stat = "true";
            } else if ($data['percent'] > "50" && $data['percent'] <= "99") {
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
            <td>$no</td>
            <td>$data[no_req]</td>";
            echo "
            <td>" . fd_view($data[tgl_req]) . "</td>";
            echo "
            <td>$data[job_order]</td>
            <td>$data[kode_barang]</td>
            <td>$data[nama_barang]</td>
            <td>$data[qty]</td>
            <td>$data[qtytot]</td>
            <td>$data[unit]</td>
            <td>$data[tipe_pengeluaran]</td>
            <td>$data[supplier]</td>
            <td>  
            <div class='progress-bar' role='progressbar'  style='width: $width%;background-color:$col	;color: black;' aria-valuenow='50'
             aria-valuemin='0' aria-valuemax='100'>$data[percent]%</div>
            </td>
            ";
            if ($supplier != $data[no_req]) {
              $sizeStartRow = $counter;
              $sizeCol = '<td rowspan = "' . $data[jml_noreq] . '" style="float: center;vertical-align: middle;"><a href="pdf_reqmaterial.php?no_dok=' . $data[no_req] . '" 
          data-toggle="tooltip" target="_blank" ><i class="fa fa-print"></i>
          </a> </td>';
            } else {
              //change the rowspan value at the start position, as we know it's increased
              $sizeCol = "<td hidden></td>";
            }
            echo "
        $sizeCol";
            echo "</tr>";
            $no++;
            $supplier = $data[no_req]; //update the previous size value
            $counter++; // menambah nilai nomor urut
          }
          ?>
        </tbody>
      </table>
      </form>
    </div>
  </div>

<?php }
?>

<?php if ($mod == "new_req_material") {
  $tgl_req = date("d M Y");
  $txttgl_now = date("Y-m-d");
?>

  <script type='text/javascript'>
    function getdata() {
      var txt_jo = document.form.txt_jo.value;
      var html = $.ajax({
        type: "POST",
        url: 'ajax_req_material.php?modeajax=view_list_data',
        data: {
          txt_jo: txt_jo
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
        <form method='post' name='form' action='save_req_material.php?mod=simpan'>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>No. Req</label>
              <input type='text' class='form-control' name='txtno_req' value='<?php
                                                                              $sql = mysqli_query($conn_li, "select max(no_req) from req_material where MONTH(tgl_req) = MONTH('$txttgl_now') and YEAR(tgl_req) = YEAR('$txttgl_now')");
                                                                              $row = mysqli_fetch_array($sql);
                                                                              $kodepay = $row['max(no_req)'];
                                                                              $urutan = (int) substr($kodepay, 9, 4);
                                                                              $urutan++;
                                                                              $bln =  date("m", strtotime($txttgl_now));
                                                                              $thn =  date("y", strtotime($txttgl_now));
                                                                              $huruf = "RQ/$bln$thn/";
                                                                              $kodepay = $huruf . sprintf("%04s", $urutan);
                                                                              $huruf = substr($kodepay, 0, 8);
                                                                              $angka = substr($kodepay, 9, 4) || 0;
                                                                              $angka2 = $angka + 12;
                                                                              $angka3 = sprintf("%05s", $angka2);
                                                                              echo $kodepay; ?>' readonly>
            </div>
            <div class='form-group'>
              <label>Tujuan #</label>
              <input type='text' class='form-control' name='txt_tujuan' placeholder='Masukkan Nama Tujuan'>
            </div>
            <div class='form-group'>
              <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
            </div>
          </div>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Tgl Req *</label>
              <input type='text' class='form-control' id='datepicker1' name='txt_tglreq' value='<?php echo $tgl_req; ?>'>
            </div>
            <div class='form-group'>
              <label>Nomor Job Order *</label>
              <select class='form-control select2' style='width: 100%;' name='txt_jo' id='txt_jo' onchange='getdata()' required>
                <?php
                $sqljns_out = "SELECT job_order isi, job_order tampil FROM in_material
                group by job_order order by job_order asc";
                IsiCombo($sqljns_out, '', 'Pilih Nomor Job Order');
                ?>
              </select>
            </div>
          </div>
          <div class='col-md-6'>
            <div class='form-group'>
              <label>Jenis Pengeluaran *</label>
              <select class='form-control select2' style='width: 100%;' name='txtjns_out' required>
                <?php
                $sqljns_out = "select nama_trans isi,nama_trans tampil from mastertransaksi where 
                            jenis_trans='OUT' and jns_gudang = 'FABRIC' order by id";
                IsiCombo($sqljns_out, '', 'Pilih Jenis Pengeluaran');
                ?>
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
<?php }  ?>



<?php if ($mod == "master_part_exc") {
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=lap_master_part.xls"); //ganti nama sesuai keperluan 
  header("Pragma: no-cache");
  header("Expires: 0");
?>
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">Master Part</h3>
    </div>
    <div>
      List Master Part
    </div>
    <div class="box-body">
      <table border="1" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>No</th>
            <th>Kode Part</th>
            <th>Nama Part</th>
            <th>Tgl Input</th>
            <th>Dibuat</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE
          $sql = "select * from master_part ";
          #echo $sql;
          $query = mysql_query($sql);
          $no = 1;
          while ($data = mysql_fetch_array($query)) {
            $tgl_input = fd_view_dt($data['tgl_input']);
            if ($data['cancel'] == "Y") {
              $fontcol  = "style='color:red;'";
              $status   = "Cancel";
              $icon     = "fa-check-circle-o fa-lg";
              $col      = "style='color:blue;'";
            } else {
              $fontcol  = "";
              $status   = "Aktif";
              $icon     = "fa-ban fa-lg";
              $col      = "style='color:red;'";
            }
            echo "<tr $fontcol>";
            echo "<td>$no</td>";
            echo "
          <td>$data[kode_part]</td>
          <td>$data[nama_part]</td>
          <td>$tgl_input</td>
          <td>$data[user_input]</td>
          <td>$status</td>";
            echo "</tr>";
            $no++; // menambah nilai nomor urut
          }
          // echo "<td>$data[tgl_input]</td>";
          ?>
        </tbody>
      </table>
    </div>
  </div>
<?php }
?>