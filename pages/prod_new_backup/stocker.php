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

$rscomp = mysql_fetch_array(mysql_query("select * from mastercompany"));
$st_company = $rscomp["status_company"];
$logo_company = $rscomp["logo_company"];

?>
<script type="text/javascript">


</script>
<?php if ($mod == "stocker_h") {

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
  window.open ('index.php?mod=master_plan_exc&from=$from&to=$to&dest=xls', '_blank');
    </script>";
  }

?>
  <div class="box">
    <div class="box-header">
      <a href='../prod_new/?mod=stocker_new' class='btn btn-primary btn-s'>
        <i class='fa fa-plus'></i> New
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
            <th>No</th>
            <th>No Cutting</th>
            <th>Tgl Cutting</th>
            <th>WS</th>
            <th>Color</th>
            <th>Size Ratio</th>
            <th>Part</th>
            <th>Created By</th>
            <th>Preview Bundle</th>
          </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE
          $query = mysql_query("
          select no_cut, tgl_cut, kpno, color, group_concat(concat(size, ' (',qty_cut,') ' )) size_ratio, part, user, tgl_input 
          from stocker
          where tgl_cut >= '$tglf' and tgl_cut <= '$tglt'
          group by no_cut");
          $no = 1;
          while ($data = mysql_fetch_array($query)) {

            $createby = $data['user'] . " (" . fd_view_dt($data['tgl_input']) . ")";

            echo "<tr>";
            echo "
            <td>$no</td>
            <td>$data[no_cut]</td>";
            echo "
            <td>" . fd_view($data[tgl_cut]) . "</td>";
            echo "
            <td>$data[kpno]</td>
            <td>$data[color]</td>
            <td>$data[size_ratio]</td>
            <td>$data[part]</td>
            <td>$createby</td>
            <td align = 'center'>
            <a href='../prod_new/?mod=stocker_preview&no_cut=$data[no_cut]'
            data-toggle='tooltip' ><i class='fa fa-eye'></i>
            </a>            
            </td>
            
            ";
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

<?php if ($mod == "stocker_new") {
  $txttglcut = date("d M Y");
?>

  <script type='text/javascript'>
    function getcolor() {
      var cbows = document.form.cbows.value;
      var html = $.ajax({
        type: "POST",
        url: 'ajax_stocker.php?modeajax=view_list_color',
        data: {
          cbows: cbows
        },
        async: false
      }).responseText;
      if (html) {
        $("#cbocolor").html(html);
      }
      jQuery.ajax({

        url: 'ajax_stocker.php?modeajax=cari_data_ws',
        method: 'POST',
        data: {
          cri_item: cbows
        },
        dataType: 'json',
        success: function(response) {
          $('#txtkpno').val(response[0]);
          $('#txtbuyer').val(response[1]);
          $('#txtstyle').val(response[2]);
        },
        error: function(request, status, error) {
          alert(request.responseText);
        },
      });

    };

    function getsize() {
      var cbows = document.form.cbows.value;
      var cbocolor = document.form.cbocolor.value;
      var html = $.ajax({
        type: "POST",
        url: 'ajax_stocker.php?modeajax=view_list_size',
        data: {
          cbows: cbows,
          cbocolor: cbocolor
        },
        async: false
      }).responseText;
      if (html) {
        $("#cbosize").html(html);
      }
    };

    function getdata() {
      var cbows = document.form.cbows.value;
      var cbocolor = document.form.cbocolor.value;
      var cbosize = $('#cbosize').val();
      var html = $.ajax({
        type: "POST",
        url: 'ajax_stocker.php?modeajax=view_list_data',
        data: {
          cbows: cbows,
          cbocolor: cbocolor,
          cbosize: cbosize
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
          ordering: false,
          fixedColumns: {
            leftColumns: 1,
            rightColumns: 1
          }
        });
      });

    };

    function sum() {
      var txtqtyply = document.getElementById('txtqtyply').value;
      var table = document.getElementById("examplefix2");
      for (var i = 1; i < (table.rows.length); i++) {
        var txtratio = document.getElementById("examplefix2").rows[i].cells[3].children[0].value;
        var txtrange_awal = document.getElementById("examplefix2").rows[i].cells[5].children[0].value;
        var result = parseFloat(txtqtyply) * parseFloat(txtratio);
        var result_fix = Math.round(result)
        if (!isNaN(result_fix)) {
          document.getElementById("examplefix2").rows[i].cells[4].children[0].value = result_fix;
        }
        var result_akhir = parseFloat(txtrange_awal) + result_fix - 1;
        if (!isNaN(result_akhir)) {
          document.getElementById("examplefix2").rows[i].cells[6].children[0].value = result_akhir;
        }
      }
    }
  </script>

  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <form method='post' name='form' action='save_stocker.php?mod=simpan'>
          <div class='col-md-4'>
            <div class='form-group'>
              <label>No WS #</label>
              <select class='form-control select2' style='width: 100%;' name='cbows' id='cbows' onchange='getcolor();'>
                <?php
                $sql = "select ac.id isi, ac.kpno tampil from jo_det jd
                inner join so on jd.id_so = so.id
                inner join act_costing ac on so.id_cost = ac.id
                where jd.cancel = 'N' and ac.status != 'CANCEL' and ac.cost_date >= '2023-01-01'
                order by ac.kpno asc";
                IsiCombo($sql, '', 'Pilih WS #');
                ?>
              </select>
            </div>
            <div class='form-group'>
              <label>Color *</label>
              <select class='form-control select2' style='width: 100%;' name='cbocolor' id='cbocolor' onchange='getsize();' required>
              </select>
            </div>
            <div class='row'>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Cutting Number *</label>
                  <input type='number' class='form-control' name='txtcutnumb' id='txtcutnumb' min='1' value='1'>
                </div>
              </div>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Qty Ply</label>
                  <input type='number' class='form-control' onkeyup='sum()' name='txtqtyply' id='txtqtyply'>
                </div>
              </div>
            </div>
          </div>
          <div class='col-md-4'>
            <div class='form-group'>
              <label>Buyer</label>
              <input type='text' class='form-control' readonly name='txtbuyer' id='txtbuyer'>
              <input type='hidden' class='form-control' readonly name='txtkpno' id='txtkpno'>
            </div>
            <div class='form-group'>
              <label>Size *</label>
              <select class='form-control select2' multiple='multiple' style='width: 100%;' name='cbosize' id='cbosize' onchange='getdata();' required>
              </select>
            </div>
            <div class='form-group'>
              <label>Tgl Cutting *</label>
              <input type='text' class='form-control' id='datepicker1' name='txttglcut' placeholder='Masukkan Tgl Cutting' value='<?php echo $txttglcut; ?>'>
            </div>
          </div>
          <div class='col-md-4'>
            <div class='form-group'>
              <label>Style</label>
              <input type='text' class='form-control' readonly name='txtstyle' id='txtstyle'>
            </div>
            <div class='row'>
              <div class='col-md-8'>
                <div class='form-group'>
                  <label>Part *</label>
                  <select class='form-control select2' style='width: 100%;' name='cbopart' id='cbopart' required>
                    <?php
                    $sql = "select nama_part isi , nama_part tampil from master_part where cancel = 'N'";
                    IsiCombo($sql, '', 'Pilih Part #');
                    ?>
                  </select>
                </div>
              </div>
              <div class='col-md-4'>
                <div class='form-group'>
                  <label>Shade</label>
                  <input type='text' class='form-control' name='txtshade' id='txtshade'>
                </div>
              </div>
            </div>
            <div class='form-group'>
              <label>Note *</label>
              <textarea row='5' class='form-control' name='txtremark' id='txtremark' placeholder='Masukkan Notes'></textarea>
            </div>
          </div>
          <div class='box-body'>
            <div id='detail_item'></div>
          </div>
          <div class='form-group' style='padding: 22px'>
            <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div><?php }
        ?>

<?php if ($mod == "stocker_preview") {
  $no_cut = $_GET['no_cut'];

  $querystocker   = mysql_query("
  SELECT ac.id id_ac, s.kpno, mb.supplier buyer, ac.styleno, s.color, s.notes, s.part,group_concat(size) size, 
  concat(cut_number) cut_number, concat(qty_ply) qty_ply, concat(tgl_cut) tgl_cut, part, shade
  from stocker s
  inner join act_costing ac on s.kpno = ac.kpno
  inner join mastersupplier mb on ac.id_buyer = mb.id_supplier
  where no_cut = '$no_cut' and cancel = 'N'
  ");
  $datastocker    = mysql_fetch_array($querystocker);
  $kpno       = $datastocker['kpno'];
  $buyer      = $datastocker['buyer'];
  $style      = $datastocker['styleno'];
  $color      = $datastocker['color'];
  $notes      = $datastocker['notes'];
  $part       = $datastocker['part'];
  $size       = $datastocker['size'];
  $cut_number = $datastocker['cut_number'];
  $qty_ply    = $datastocker['qty_ply'];
  $tgl_cut    = fd_view($datastocker['tgl_cut']);
  $id_ac      = $datastocker['id_ac'];
  $part       = $datastocker['part'];
  $shade      = $datastocker['shade'];

?>
  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <form method='post' name='form' action='save_stocker.php?mod=simpan'>
          <div class='col-md-4'>
            <div class='form-group'>
              <label>No WS #</label>
              <input type='text' class='form-control' readonly name='cbows' id='cbows' value='<?php echo $kpno; ?>'>
            </div>
            <div class='form-group'>
              <label>Color *</label>
              <input type='text' class='form-control' readonly name='cbocolor' id='cbocolor' value='<?php echo $color; ?>'>
              </select>
            </div>
            <div class='row'>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Cutting Number *</label>
                  <input type='number' class='form-control' name='txtcutnumb' id='txtcutnumb' readonly value='<?php echo $cut_number; ?>'>
                </div>
              </div>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Qty Ply</label>
                  <input type='number' class='form-control' name='txtqtyply' id='txtqtyply' readonly value='<?php echo $qty_ply; ?>'>
                </div>
              </div>
            </div>
          </div>
          <div class='col-md-4'>
            <div class='form-group'>
              <label>Buyer</label>
              <input type='text' class='form-control' readonly name='txtbuyer' id='txtbuyer' value='<?php echo $buyer; ?>'>
            </div>
            <div class='form-group'>
              <label>Size *</label>
              <input type='text' class='form-control' name='cbosize' id='cbosize' value='<?php echo $size; ?>' readonly>
            </div>
            <div class='form-group'>
              <label>Tgl Cutting *</label>
              <input type='text' class='form-control' id='datepicker1' name='txttglcut' placeholder='Masukkan Tgl Cutting' disabled value='<?php echo $tgl_cut; ?>'>
            </div>
          </div>
          <div class='col-md-4'>
            <div class='form-group'>
              <label>Style</label>
              <input type='text' class='form-control' readonly name='txtstyle' id='txtstyle' value='<?php echo $style; ?>'>
            </div>
            <div class='row'>
              <div class='col-md-8'>
                <div class='form-group'>
                  <label>Part *</label>
                  <select class='form-control select2' style='width: 100%;' name='cbopart' id='cbopart' required>
                    <?php
                    $sql = "select nama_part isi , nama_part tampil from master_part where cancel = 'N'";
                    IsiCombo($sql, $part, 'Pilih Part #');
                    ?>
                  </select>
                </div>
              </div>
              <div class='col-md-4'>
                <div class='form-group'>
                  <label>Shade</label>
                  <input type='text' class='form-control' name='txtshade' id='txtshade' value='<?php echo $shade; ?>'>
                </div>
              </div>
            </div>
            <div class='form-group'>
              <label>Note *</label>
              <textarea row='5' class='form-control' name='txtremark' id='txtremark'><?php echo $notes; ?></textarea>
            </div>
          </div>
          <div class='box-body'>
            <div id='detail_item'>
              <table id='example1' style='width: 100%;' class='table table-striped table-bordered'>
                <thead>
                  <tr>
                    <th style='width:auto;'>WS</th>
                    <th style='width:auto;'>Color</th>
                    <th style='width:auto;'>Size</th>
                    <th style='width:70px;'>Ratio</th>
                    <th style='width:70px;'>Qty Cut</th>
                    <th style='width:70px;'>Range Awal</th>
                    <th style='width:70px;'>Range Akhir</th>
                    <th style='width:70px;'>Print Stocker</th>
                    <th style='width:70px;'>Print Numbering</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  # QUERY TABLE
                  $query = mysql_query(
                    "select * from stocker  where no_cut = '$no_cut'"
                  );
                  $no = 1;
                  while ($data = mysql_fetch_array($query)) {
                    $id_so_det = $data['id_so_det'];
                    echo "<tr>";
                    echo "
            <td>$data[kpno]</td>
            <td>$data[color]</td>
            <td>$data[size]</td>
            <td>$data[ratio]</td>
            <td>$data[qty_cut]</td>
            <td>$data[range_awal]</td>
            <td>$data[range_akhir]</td>
            <td>
          <a 
            href='cetak_stocker.php?id_stocker=$data[id]';
            data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i>
          </a>
          </td>
          <td>
          <a 
            href='cetak_qr_print_numbering.php?id_so_det=$id_so_det&txtcutnumber=$cut_number&txtdari=$data[range_awal]&txtsampai=$data[range_akhir]';
            data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i>
          </a>
          </td>          
            </td>";
                    echo "</tr>";
                    $no++; // menambah nilai nomor urut
                  }
                  ?>
                </tbody>
            </div>
          </div>
          <div class='form-group' style='padding: 22px'>
            <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div><?php }
        ?>