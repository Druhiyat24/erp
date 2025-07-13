<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$akses = flookup("cancel_all_out","userpassword","username='$user'");
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
  { var reason = document.form.txtreason.value;
    var sono = document.form.txtid_cost.value;
    if (sono == '') { swal({ title: 'Transaksi # Tidak Boleh Kosong', type: 'warning' }); valid = false;}
    else if (reason == '') { document.form.txtreason.focus(); swal({ title: 'Reason Tidak Boleh Kosong', type: 'warning' }); valid = false;}
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
      echo "<form method='post' name='form' enctype='multipart/form-data' action='s_cancel_out.php?mod=$mod' onsubmit='return validasi()'>";
        echo "<div class='col-md-3'>";
          ?>
          <div class='form-group'>
            <label>SJ # *</label>
            <select class='form-control select2' style='width: 100%;' name='txtid_cost'>
              <?php
                $sql = "select bppbno isi,if(bppbno_int!='',bppbno_int,bppbno) tampil from 
                  bppb where ifnull(confirm_by,'')='' group by bppbno";
                IsiCombo($sql,$id_cost,'Pilih SJ #');
              ?>
            </select>
          </div>
          <div class='form-group'>
            <label>Reason *</label>
            <textarea row="4" class='form-control' name='txtreason' placeholder="Masukan Reason"></textarea>
          </div>
          <?php
          echo "<button type='submit' name='submit' class='btn btn-warning'>Cancel</button>";
        echo "</div>";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
?>