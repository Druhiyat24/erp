<?php
if (empty($_SESSION['username'])) {
  header("location:../../index.php");
}

# START CEK HAK AKSES KEMBALI
$akses = flookup("lap_inventory", "userpassword", "username='$user'");
if ($akses == "0") {
  echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>";
}
# END CEK HAK AKSES KEMBALI

$rscomp = mysql_fetch_array(mysql_query("select * from mastercompany"));
$st_company = $rscomp["status_company"];
$logo_company = $rscomp["logo_company"];
?>
<?php if ($mod == "lap_data") {

  if (isset($_POST['submit'])) //KLIK SUBMIT
  {
    $from = date('Y-m-d', strtotime($_POST['txtfrom']));
    $to = date('Y-m-d', strtotime($_POST['txtto']));
    $tipe_data = $_POST['tipe_data'];
    $cbotipe = $_POST['cbotipe'];
    echo "<script>
  window.open ('?mod=lap_data_exc&from=$from&to=$to&tipe_data=$tipe_data&cbotipe=$cbotipe&dest=xls', '_blank');
    </script>";
  } else {
  }



?>
  <script type='text/javascript'>
    function gettipe() {
      var tipe_data = document.form.tipe_data.value;
      var html = $.ajax({
        type: "POST",
        url: 'ajax_lap_data.php?modeajax=view_list_tipe',
        data: {
          tipe_data: tipe_data,
        },
        async: false
      }).responseText;
      if (html) {
        $("#cbotipe").html(html);
      }
    };
  </script>




  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <form method='post' name='form'>

          <div class='col-md-3'>
            <div class='form-group'>
              <label>Tipe Data #</label>
              <select class='form-control select2' id='tipe_data' name='tipe_data' onchange='gettipe()'>
                <option value="" selected> - PILIH DATA - </option>
                <option value="PENERIMAAN DETAIL" <?php if ($tipe_data == "PENERIMAAN DETAIL") {
                                                    echo "selected";
                                                  } ?>>PENERIMAAN DETAIL</option>
                <option value="PENGELUARAN DETAIL" <?php if ($tipe_data == "PENGELUARAN DETAIL") {
                                                      echo "selected";
                                                    } ?>>PENGELUARAN DETAIL</option>
                <option value="MUTASI DETAIL" <?php if ($tipe_data == "MUTASI DETAIL") {
                                                echo "selected";
                                              } ?>>MUTASI DETAIL</option>
              </select>
            </div>
          </div>

          <div class='col-md-3'>
            <div class='form-group'>
              <label>Tipe Item #</label>
              <select class='form-control select2' style='width: 100%;' name='cbotipe' id='cbotipe'>
              </select>
            </div>
          </div>
      </div>
      <div class='row'>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Dari *</label>
            <input type='text' class='form-control' autocomplete='off' id='datepicker1' name='txtfrom' required placeholder='Masukkan Dari Tanggal'>
          </div>
        </div>

        <div class='col-md-3'>
          <div class='form-group'>
            <label>Sampai *</label>
            <input type='text' class='form-control' autocomplete='off' id='datepicker2' name='txtto' required placeholder='Masukkan Dari Tanggal'>
          </div>
        </div>
        <div class='col-md-2'>
          <div class='form-group' style='padding-top:25px'>
            <button type='submit' name='submit' class='btn btn-success'>Export Excel</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  </form>
  </div>
  </div><?php }
