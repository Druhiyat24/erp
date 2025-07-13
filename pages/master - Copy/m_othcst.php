<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("m_others","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_otherscode = $_GET['id']; } else {$id_otherscode = "";}
$titlenya="Master Others Cost";
$mode="";
$mod=$_GET['mod'];

# COPAS EDIT
if ($id_otherscode=="")
{ $otherscode = "";
  $othersdesc = "";
}
else
{ $query = mysql_query("SELECT * FROM masterothers where id='$id_otherscode'");
  $data = mysql_fetch_array($query);
  $otherscode = $data['otherscode'];
  $othersdesc = $data['othersdesc'];
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { var otherscode = document.form.txtotherscode.value;
      var othersdesc = document.form.txtothersdesc.value;
      if (otherscode == '') { alert('Others Code Tidak Boleh Kosong'); document.form.txtotherscode.focus();valid = false;}
      else if (othersdesc == '') { alert('Others Desc Tidak Boleh Kosong'); document.form.txtothersdesc.focus();valid = false;}
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
      echo "<form method='post' name='form' action='s_m_othcst.php?mod=$mod&id=$id_otherscode' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Others Code *</label>
            <input type='text' class='form-control' name='txtotherscode' 
            placeholder='Masukkan Others Code' value='$otherscode'>
          </div>
          <div class='form-group'>
            <label>Others Desc</label>
            <input type='text' class='form-control' name='txtothersdesc' 
            placeholder='Masukkan Others Desc' value='$othersdesc'>
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
    <table id="mothercost" class="display responsive" style="width:100%">
      <thead>
      <tr>
		      <!-- <th>No</th> -->
          <th>Others Code</th>
          <th>Others Desc</th>
          <th>Aksi</th>
      </tr>
      </thead>
      <tbody>
       
      </tbody>
    </table>
  </div>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> 
<script src="js/Masterothercost.js"></script>