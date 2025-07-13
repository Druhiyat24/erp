<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$akses = flookup("update_dok_pab","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI

$st_company = flookup("status_company","mastercompany","company!=''");

# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>";
	echo "function validasi()";
	echo "{";
    echo "
    var jenis_trans = document.form.txtjte.value;
    var tgl_trans = document.form.txtdate.value;";

    echo "if (jenis_trans == '') { alert('Jenis Transaksi Kosong'); valid = false;}";
    echo "else if (tgl_trans == '') { alert('Jenis Transaksi Kosong'); valid = false;}";
    echo "else valid = true;";
    echo "return valid;";
    echo "exit;";
	echo "}";
echo "</script>";
# END COPAS VALIDASI

# COPAS ADD
?>
<div class='box'>
  <div class='box-body'>
    <div class='row'>
      <form method='post' name='form' action='../shp/?mod=2L' onsubmit='return validasi()'>
        <div class='col-md-3'>                
          <div class='form-group'>
            <label>Jenis Transaksi *</label>
            <select class='form-control select2' style='width: 100%;' name='txtjte'>
              <?php 
                $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan where kode_pilihan='J_Tr'";
                IsiCombo($sql,$jte,'Pilih Jenis Transaksi');
              ?>
            </select>
          </div>        
          <div class='form-group'>
            <label>Tanggal From*</label>
            <input type='text' class='form-control' name='txtdate' placeholder='Masukkan Tanggal' id='datepicker1' autocomplete="off">
          </div>
          <div class='form-group'>
            <label>Tanggal To*</label>
            <input type='text' class='form-control' name='txtdate2' placeholder='Masukkan Tanggal' id='datepickerto' autocomplete="off">
          </div>
          <button type='submit' name='submit' class='btn btn-primary'>Tampilkan</button>
        </div>
      </form>
    </div>
  </div>
</div><?php 
# END COPAS ADD
?>