<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("master_size","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_pterms = $_GET['id']; } else {$id_pterms = "";}
$titlenya="Master Pterms";
$mode="";
$mod=$_GET['mod'];

# COPAS EDIT
if ($id_pterms=="")
{ $pterms = "";
  $pterms_desc = "";
}
else
{ $query = mysql_query("SELECT * FROM mastersize where size='$id_pterms'");
  $data = mysql_fetch_array($query);
  $pterms = $data['urut'];
  $pterms_desc = $data['size'];
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { var pterms = document.form.txtpterms.value;
      var pterms_desc = document.form.txtpterms_desc.value;
      if (pterms == '') { alert('Pterms Tidak Boleh Kosong'); document.form.txtpterms.focus();valid = false;}
      else if (pterms_desc == '') { alert('Deskripsi Tidak Boleh Kosong'); document.form.txtpterms_desc.focus();valid = false;}
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
      echo "<form method='post' name='form' action='s_size.php?mod=$mod&id=$id_pterms' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Urutan Size *</label>
            <input type='text' class='form-control' name='txtpterms' 
            placeholder='Masukkan Urutan Size' value='$pterms'>
          </div>
          <div class='form-group'>
            <label>Size</label>
            <input type='text' class='form-control' name='txtpterms_desc' 
            placeholder='Masukkan Size' value='$pterms_desc'>
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
      <h3 class="box-title">Data <?php echo "Urutan Size"; ?></h3>
  </div>
  <div class="box-body">
    <table id="mastersize" class="display responsive" style="width:100%">
      <thead>
      <tr>
		      <!-- <th>No</th> -->
          <th>Urutan Size</th>
          <th>Size</th>
          <th>Aksi</th>
      </tr>
      </thead>
      <tbody>
       
      </tbody>
    </table>
  </div>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> 
<script src="js/Mastersize.js"></script>