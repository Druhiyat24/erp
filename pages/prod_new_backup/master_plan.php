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
<script type="text/javascript">


</script>
<?php if ($mod == "master_plan_h") {

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
      <a href='../prod_new/?mod=master_plan_new' class='btn btn-primary btn-s'>
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
      <table id="examplefix3" class="display responsive" style="width: 100%;font-size:12px;">
        <thead>
          <tr>
            <th>No</th>
            <th>Tgl Plan</th>
            <th>Line</th>
            <th>WS</th>
            <th>Buyer</th>
            <th>Style</th>
            <th>Color</th>
            <th>Qty Order</th>
            <th>SMV</th>
            <th>Man Power</th>
            <th>Jam Kerja</th>
            <th>Plan Day</th>
            <th>Target Effy</th>
            <th>Created By</th>
          </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE
          $query = mysql_query("select a.*, ac.kpno,ac.styleno, supplier buyer,sd.qty_order from master_plan a
          inner join act_costing ac on a.id_ws = ac.id
          inner join mastersupplier mb on ac.id_buyer = mb.id_supplier
          inner join so on ac.id = so.id_cost
          inner join (select id_so, color,sum(qty) qty_order from so_det sd where sd.cancel = 'N' group by id_so,color) sd 
          on so.id = sd.id_so and sd.color = a.color
          where a.tgl_plan >= '$tglf' and a.tgl_plan <= '$tglt'");
          $no = 1;
          while ($data = mysql_fetch_array($query)) {

            $createby = $data['create_by'] . " (" . fd_view_dt($data['tgl_input']) . ")";

            echo "<tr>";
            echo "
            <td>$no</td>";
            echo "
            <td>" . fd_view($data[tgl_plan]) . "</td>";
            echo "
            <td>$data[sewing_line]</td>
            <td>$data[kpno]</td>
            <td>$data[buyer]</td>
            <td>$data[styleno]</td>
            <td>$data[color]</td>
            <td>$data[qty_order]</td>
            <td>$data[smv]</td>
            <td>$data[man_power]</td>
            <td>$data[jam_kerja]</td>
            <td>$data[plan_target]</td>
            <td>$data[target_effy]</td>
            <td>$createby</td>";
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

<?php if ($mod == "master_plan_new") {
  $id_plan      = date('Ymd');
?>

  <script type='text/javascript'>
    function getdata() {
      var cbows = document.form.cbows.value;
      var html = $.ajax({
        type: "POST",
        url: 'ajax_master_plan.php?modeajax=view_list_data',
        data: {
          cbows: cbows
        },
        async: false
      }).responseText;
      if (html) {
        $("#detail_item").html(html);
      }
      $(document).ready(function() {
        var table = $('#examplefix1').DataTable({
          info: false,
          paging: false,
          footerCallback: function(row, data, start, end, display) {
            var api = this.api();

            // Remove the formatting to get integer data for summation
            var intVal = function(i) {
              return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            };

            // Total over all pages
            total = api
              .column(8)
              .data()
              .reduce(function(a, b) {
                let result = intVal(a) + intVal(b);
                return result;
              }, 0);

            // Total over this page
            pageTotal = api
              .column(7, {
                page: 'current'
              })
              .data()
              .reduce(function(a, b) {
                let result = intVal(a) + intVal(b);
                return result;
              }, 0);

            // Update footer
            $(api.column(7).footer()).html(pageTotal);
          },
        });
      });


      jQuery.ajax({

        url: 'ajax_master_plan.php?modeajax=cari_data_ws',
        method: 'POST',
        data: {
          cri_item: cbows
        },
        dataType: 'json',
        success: function(response) {
          $('#txtbuyer').val(response[1]);
          $('#txtstyle').val(response[2]);
        },
        error: function(request, status, error) {
          alert(request.responseText);
        },
      });

    };


    function getdata_with_color() {
      var cbows = document.form.cbows.value;
      var cbocolor = document.form.cbocolor.value;
      var html = $.ajax({
        type: "POST",
        url: 'ajax_master_plan.php?modeajax=view_list_data_with_color',
        data: {
          cbows: cbows,
          cbocolor: cbocolor
        },
        async: false
      }).responseText;
      if (html) {
        $("#detail_item").html(html);
      }
      $(document).ready(function() {
        var table = $('#examplefix1').DataTable({
          info: false,
          paging: false,
          footerCallback: function(row, data, start, end, display) {
            var api = this.api();

            // Remove the formatting to get integer data for summation
            var intVal = function(i) {
              return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            };

            // Total over all pages
            total = api
              .column(8)
              .data()
              .reduce(function(a, b) {
                let result = intVal(a) + intVal(b);
                return result;
              }, 0);

            // Total over this page
            pageTotal = api
              .column(7, {
                page: 'current'
              })
              .data()
              .reduce(function(a, b) {
                let result = intVal(a) + intVal(b);
                return result;
              }, 0);

            // Update footer
            $(api.column(7).footer()).html(pageTotal);
          },
        });
      });
    };


    function getcolor() {
      var cbows = document.form.cbows.value;
      var html = $.ajax({
        type: "POST",
        url: 'ajax_master_plan.php?modeajax=view_list_color',
        data: {
          cbows: cbows
        },
        async: false
      }).responseText;
      if (html) {
        $("#cbocolor").html(html);
      }
    };



    function sum() {
      var txt_man_power_value = document.getElementById('txtmpwr').value;
      var txt_jam_kerja_value = document.getElementById('txtjamkerja').value;
      var txt_smv_value = document.getElementById('txtsmv').value;
      var result = parseFloat(txt_man_power_value) * parseFloat(txt_jam_kerja_value) * 60 / parseFloat(txt_smv_value);
      var result_fix = Math.round(result)
      if (!isNaN(result_fix)) {
        document.getElementById('txt_plan_target').value = result_fix;
      }
    }
  </script>




  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <form method='post' name='form' action='save_master_plan.php?mod=simpan'>
          <div class='col-md-4'>
            <div class='form-group'>
              <label>Nomor Plan</label>
              <input type='text' class='form-control' readonly value=<?php echo $id_plan; ?>>
            </div>
            <div class='form-group'>
              <label>No WS #</label>
              <select class='form-control select2' style='width: 100%;' name='cbows' id='cbows' onchange='getdata(); getcolor();'>
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
            <div class='row'>
              <div class='col-md-4'>
                <div class='form-group'>
                  <label>SMV *</label>
                  <input type='text' class='form-control' name='txtsmv' id='txtsmv' onkeyup='sum()' required autocomplete='off'>
                </div>
              </div>
              <div class='col-md-4'>
                <div class='form-group'>
                  <label>Man Pwr *</label>
                  <input type='text' class='form-control' name='txtmpwr' id='txtmpwr' onkeyup='sum()' required autocomplete='off'>
                </div>
              </div>
              <div class='col-md-4'>
                <div class='form-group'>
                  <label>Jam Kerja *</label>
                  <input type='text' class='form-control' name='txtjamkerja' id='txtjamkerja' onkeyup='sum()' required autocomplete='off'>
                </div>
              </div>
            </div>
          </div>
          <div class='col-md-4'>
            <div class='form-group'>
              <label>Buyer</label>
              <input type='text' class='form-control' readonly name='txtbuyer' id='txtbuyer'>
            </div>
            <div class='form-group'>
              <label>Color *</label>
              <select class='form-control select2' style='width: 100%;' name='cbocolor' id='cbocolor' onchange='getdata_with_color()' required>
              </select>
            </div>
            <div class='row'>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Plan Day Target</label>
                  <input type='text' class='form-control' name='txt_plan_target' id='txt_plan_target' readonly autocomplete='off'>
                </div>
              </div>
              <div class='col-md-4'>
                <div class='form-group'>
                  <label>Target Effy *</label>
                  <div class='row'>
                    <div class='col-md-6'>
                      <input type='text' style='width:60px;' class='form-control' name='txt_target_eff' id='txt_target_eff' required autocomplete='off'>
                    </div>
                    <div class='col-md-6'>
                      <label style="padding:7px">%</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class='col-md-4'>
            <div class='form-group'>
              <label>Style</label>
              <input type='text' class='form-control' readonly name='txtstyle' id='txtstyle'>
            </div>
            <div class='form-group'>
              <label>Line *</label>
              <select class='form-control select2' style='width: 100%;' name='cboline' id='cboline' required>
                <?php
                $sql = "select username isi, fullname tampil from userpassword where groupp = 'SEWING'";
                IsiCombo($sql, '', 'Pilih Line #');
                ?>
              </select>
            </div>
            <div class='form-group' style='padding: 22px'>
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
        ?>