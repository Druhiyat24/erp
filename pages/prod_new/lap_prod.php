<?php
if (empty($_SESSION['username'])) {
  header("location:../../index.php");
}

# START CEK HAK AKSES KEMBALI
// $akses = flookup("lap_inventory", "userpassword", "username='$user'");
// if ($akses == "0") {
//   echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>";
// }
# END CEK HAK AKSES KEMBALI

$rscomp = mysql_fetch_array(mysql_query("select * from mastercompany"));
$st_company = $rscomp["status_company"];
$logo_company = $rscomp["logo_company"];
?>
<?php if ($mod == "lap_prod") {

  if (isset($_POST['submit'])) //KLIK SUBMIT
  {
    $from = date('Y-m-d', strtotime($_POST['txtfrom']));
    $to = date('Y-m-d', strtotime($_POST['txtto']));
    $tipe_data = $_POST['tipe_data'];
    echo "<script>
  window.open ('?mod=lap_prod_exc&from=$from&to=$to&tipe_data=$tipe_data&cbotipe=$cbotipe&cbojenis=$cbojenis&dest=xls', '_blank');
    </script>";
  } else {
  }

?>

  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <form method='post' name='form'>

          <div class='col-md-3'>
            <div class='form-group'>
              <label>Tipe Data #</label>
              <select class='form-control select2' id='tipe_data' name='tipe_data'>
                <option value="" selected> - PILIH DATA - </option>
                <option value="CUTTING NUMBERING" <?php if ($tipe_data == "CUTTING NUMBERING") {
                                                    echo "selected";
                                                  } ?>>CUTTING NUMBERING</option>
              </select>
            </div>
          </div>
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
