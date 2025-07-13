<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("master_hs","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_hscode = $_GET['id']; } else {$id_hscode = "";}
$titlenya="Master HS & Tarif";
$mode="";
$mod=$_GET['mod'];

# COPAS EDIT
if ($id_hscode=="")
{ $hscode = "";
  $hsdesc = "";
  $hstarif = "";
}
else
{ $query = mysql_query("SELECT * FROM masterhs where id='$id_hscode'");
  $data = mysql_fetch_array($query);
  $hscode = $data['kode_hs'];
  $hsdesc = $data['nama_hs'];
  $hstarif = $data['tarif_hs'];
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { var hscode = document.form.txthscode.value;
      var hstarif = document.form.txthstarif.value;
      if (hscode == '') { alert('Hs Tidak Boleh Kosong'); document.form.txthscode.focus();valid = false;}
      else if (hstarif == '') { alert('Tarif Tidak Boleh Kosong'); document.form.txthstarif.focus();valid = false;}
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
      echo "<form method='post' name='form' action='s_hs.php?mod=$mod&id=$id_hscode' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>HS Code *</label>
            <input type='text' class='form-control' name='txthscode' 
            placeholder='Masukkan HS Code' value='$hscode'>
          </div>
          <div class='form-group'>
            <label>HS Desc *</label>
            <input type='text' class='form-control' name='txthsdesc' 
            placeholder='Masukkan HS Desc' value='$hsdesc'>
          </div>
          <div class='form-group'>
            <label>Tarif</label>
            <input type='text' class='form-control' name='txthstarif' 
            placeholder='Masukkan Tarif' value='$hstarif'>
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
      <h3 class="box-title">Data <?PHP echo $titlenya; ?></h3>
  </div>
  <div class="box-body">
    <table id="mhstarif" class="display responsive" style="width:100%">
      <thead>
      <tr>
		      <!-- <th>No</th> -->
          <th>HS Code</th>
          <th>HS Desc</th>
          <th>Tarif</th>
          <th>Aksi</th>
      </tr>
      </thead>
      <tbody>
      
      </tbody>
    </table>
  </div>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> 
<script src="js/Masterhstarif.js"></script>