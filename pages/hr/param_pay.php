<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("hr_jabatan","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_jabatan = $_GET['id']; } else {$id_jabatan = "";}
$titlenya="Master Jabatan";
$mode="";
$mod=$_GET['mod'];
# COPAS EDIT
$rs=mysql_fetch_array(mysql_query("select * from hr_parameter_payroll"));
$umktrans=$rs['transisi_umk'];
$umk=$rs['umk'];
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { var jabatan = document.form.txtjabatan.value;
      var jabatan_desc = document.form.txtjabatan_desc.value;
      if (jabatan == '') { alert('Jabatan Tidak Boleh Kosong'); document.form.txtjabatan.focus();valid = false;}
      else if (jabatan_desc == '') { alert('Deskripsi Tidak Boleh Kosong'); document.form.txtjabatan_desc.focus();valid = false;}
      else valid = true;
      return valid;
      exit;
    }
  </script>";
# END COPAS VALIDASI

# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='s_param_pay.php?mod=$mod&id=$id_jabatan' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>UMK Transisi *</label>
            <input type='text' class='form-control' name='txtumktransisi' 
            placeholder='Masukkan UMK Transisi' value='$umktrans'>
          </div>
          <div class='form-group'>
            <label>UMK *</label>
            <input type='text' class='form-control' name='txtumk' 
            placeholder='Masukkan UMK' value='$umk'>
          </div>
          <button type='submit' name='submit' class='btn btn-primary'>
          Simpan</button>
        </div>";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
?>