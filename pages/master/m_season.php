<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("master_season","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_season = $_GET['id']; } else {$id_season = "";}
$titlenya="Master Season";
$mode="";
$mod=$_GET['mod'];

# COPAS EDIT
if ($id_season=="")
{ $season = "";
  $season_desc = "";
}
else
{ $query = mysql_query("SELECT * FROM masterseason where id_season='$id_season'");
  $data = mysql_fetch_array($query);
  $season = $data['season'];
  $season_desc = $data['season_desc'];
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { var season = document.form.txtseason.value;
      var season_desc = document.form.txtseason_desc.value;
      if (season == '') { alert('Season Tidak Boleh Kosong'); document.form.txtseason.focus();valid = false;}
      else if (season_desc == '') { alert('Deskripsi Tidak Boleh Kosong'); document.form.txtseason_desc.focus();valid = false;}
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
      echo "<form method='post' name='form' action='simpan_season.php?mod=$mod&id=$id_season' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Season *</label>
            <input type='text' class='form-control' name='txtseason' 
            placeholder='Masukkan Season' value='$season'>
          </div>
          <div class='form-group'>
            <label>Deskripsi</label>
            <input type='text' class='form-control' name='txtseason_desc' 
            placeholder='Masukkan Deskripsi' value='$season_desc'>
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
    <table id="mseason" class="display responsive" style="width:100%">
      <thead>
      <tr>
		      <!-- <th>No</th> -->
          <th>Season</th>
          <th>Deskripsi</th>
          <th>Aksi</th>
      </tr>
      </thead>
      <tbody>
       
      </tbody>
    </table>
  </div>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> 
<script src="js/Masterseason.js"></script>