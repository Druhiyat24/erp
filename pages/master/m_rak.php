<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("master_rak","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_rak = $_GET['id']; } else {$id_rak = "";}
$titlenya="Master Rak";
$mode="";
$mod=$_GET['mod'];

# COPAS EDIT
if ($id_rak=="")
{ $rak = "";
  $rak_desc = "";
}
else
{ $query = mysql_query("SELECT * FROM master_rak where id='$id_rak'");
  $data = mysql_fetch_array($query);
  $rak = $data['kode_rak'];
  $rak_desc = $data['nama_rak'];
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { var rak = document.form.txtrak.value;
      var rak_desc = document.form.txtrak_desc.value;
      if (rak == '') { alert('Rak Tidak Boleh Kosong'); document.form.txtrak.focus();valid = false;}
      else if (rak_desc == '') { alert('Deskripsi Tidak Boleh Kosong'); document.form.txtrak_desc.focus();valid = false;}
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
      echo "<form method='post' name='form' action='s_rak.php?mod=$mod&id=$id_rak' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Nomor Rak *</label>
            <input type='text' class='form-control' name='txtrak' 
            placeholder='Masukkan Nomor Rak' value='$rak'>
          </div>
          <div class='form-group'>
            <label>Deskripsi Rak</label>
            <input type='text' class='form-control' name='txtrak_desc' 
            placeholder='Masukkan Deskripsi Rak' value='$rak_desc'>
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
<div class="box">
  <div class="box-header">
      <h3 class="box-title">Data <?php echo "Rak"; ?></h3>
  </div>
  <div class="box-body">
    <table id="masterrak" class="display responsive" style="width:100%">
      <thead>
      <tr>
          <!-- <th>No</th> -->
          <th>Nomor Rak</th>
          <th>Deskripsi Rak</th>
          <th>Aksi</th>
      </tr>
      </thead>
      <tbody>
        
      </tbody>
    </table>
  </div>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> 
<script src="js/Masterrak.js"></script>