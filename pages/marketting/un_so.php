<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$akses = flookup("unlock_so","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$mod = $_GET['mod'];
# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
?>
<script type='text/javascript'>
  function validasi()
  { var reason = document.form.txtreason.value;
    var sono = document.form.txtid_cost.value;
    if (sono == '') { swal({ title: 'SO # Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
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
      echo "<form method='post' name='form' enctype='multipart/form-data' action='s_unl_so.php?mod=$mod' onsubmit='return validasi()'>";
        echo "<div class='col-md-3'>";
          ?>
          <div class='form-group'>
            <label>SO # *</label>
            <select class='form-control select2' style='width: 100%;' name='txtid_cost'>
              <?php
                if($mod=="21c")
                {
                  $sql = "select concat(a.id,':',a.so_no) isi,concat(a.so_no,' - ',jo.jo_no) tampil from 
                    so a inner join so_det sod on a.id=sod.id_so 
                    inner join jo_det d on a.id=d.id_so 
                    inner join jo on d.id_jo=jo.id  
                    where sod.cancel='Y' group by a.so_no";
                }
                else
                {
                  $sql = "select concat(a.id,':',a.so_no) isi,concat(a.so_no,' - ',s.styleno) tampil from 
                    so a inner join act_costing s on a.id_cost=s.id 
                    inner join jo_det d on a.id=d.id_so group by a.so_no";
                }
                IsiCombo($sql,$id_cost,'Pilih SO #');
              ?>
            </select>
          </div>
          <div class='form-group'>
            <label>Reason *</label>
            <textarea row="4" class='form-control' name='txtreason' placeholder="Masukan Reason"></textarea>
          </div>
          <?php
          if($mod=="21c") { $butcap="Uncancel"; } else { $butcap="Unlock"; }
          echo "<button type='submit' name='submit' class='btn btn-primary'>$butcap</button>";
        echo "</div>";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
?>