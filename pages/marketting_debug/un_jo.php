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
  { var reason = document.form.txtreason.value;
    var sono = document.form.txtid_cost.value;
    if (sono == '') { swal({ title: 'JO # Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (reason == '') { document.form.txtreason.focus(); swal({ title: 'Reason Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
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
      echo "<form method='post' name='form' enctype='multipart/form-data' action='s_unl_jo.php?mod=$mod' onsubmit='return validasi()'>";
        echo "<div class='col-md-3'>";
          ?>
          <div class='form-group'>
            <label>JO # *</label>
            <select class='form-control select2' style='width: 100%;' name='txtid_cost'>
              <?php
                $sql = "select concat(jo.id,':',jo.jo_no) isi,concat(jo.jo_no,' - ',s.styleno) tampil from 
                  so a inner join act_costing s on a.id_cost=s.id 
                  inner join jo_det d on a.id=d.id_so 
                  inner join jo on jo.id=d.id_jo group by jo.jo_no";
                IsiCombo($sql,$id_cost,'Pilih JO #');
              ?>
            </select>
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