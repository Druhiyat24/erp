<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$akses = flookup("unlock_so","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI

$st_company = flookup("status_company","mastercompany","company!=''");

# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
?>
<script type='text/javascript'>
  function validasi()
  { var jonya = document.form.txtid_cost.value;
    var tglnya = document.form.txttgl.value;
    var reanya = document.form.txtreason.value;
    if (jonya == '') { alert('JO # Tidak Boleh Kosong');valid = false;}
    else if (tglnya == '') { alert('Tanggal Output Tidak Boleh Kosong');valid = false;}
    else if (reanya == '') { alert('Reason Tidak Boleh Kosong');valid = false;}
    else valid = true;
    return valid;
    exit;
  }
</script>
<?php
# END COPAS VALIDASI

# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' enctype='multipart/form-data' action='s_unl_prd.php?mod=$mod' onsubmit='return validasi()'>";
        echo "<div class='col-md-3'>";
          ?>
          <div class='form-group'>
            <label>JO # *</label>
            <select class='form-control select2' style='width: 100%;' name='txtid_cost'>
              <?php
                $sql = "select concat(a.id,':',a.jo_no) isi,concat(a.jo_no,' ',ac.kpno,' ',
                  ac.styleno,' ',ms.supplier) tampil from 
                  jo a inner join jo_det jod on a.id=jod.id_jo 
                  inner join so on jod.id_so=so.id 
                  inner join act_costing ac on so.id_cost=ac.id 
                  inner join mastersupplier ms on ac.id_buyer=ms.id_supplier 
                  group by a.id";
                IsiCombo($sql,'','Pilih JO #');
              ?>
            </select>
          </div>
          <div class='form-group'>
            <label>Tanggal Output *</label>
            <input type='text' class='form-control' id='datepicker1' name='txttgl' placeholder='Masukkan Tanggal Output'>
          </div>
          <div class='form-group'>
            <label>Reason *</label>
            <textarea row="4" class='form-control' name='txtreason' placeholder="Masukan Reason"></textarea>
          </div>
          <?php
          echo "<button type='submit' name='submit' class='btn btn-primary'>Unlock</button>";
        echo "</div>";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
?>