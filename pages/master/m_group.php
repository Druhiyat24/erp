<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("generate_kode","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_group = $_GET['id']; } else {$id_group = "";}
$titlenya="Master Group";
$mode="";
$mod=$_GET['mod'];

# COPAS EDIT
if ($id_group=="")
{ $group = "";
  $group_desc = "";
}
else
{ $query = mysql_query("SELECT * FROM mastergroup where id='$id_group'");
  $data = mysql_fetch_array($query);
  $group = $data['kode_group'];
  $group_desc = $data['nama_group'];
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { var group = document.form.txtgroup.value;
      var group_desc = document.form.txtgroup_desc.value;
      if (group == '') { alert('Group Tidak Boleh Kosong'); document.form.txtgroup.focus();valid = false;}
      else if (group_desc == '') { alert('Deskripsi Tidak Boleh Kosong'); document.form.txtgroup_desc.focus();valid = false;}
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
      echo "<form method='post' name='form' action='s_group.php?mod=$mod&id=$id_group' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Kode Group *</label>
            <input type='text' class='form-control' name='txtgroup' 
            placeholder='Masukkan Kode Group' value='$group'>
          </div>
          <div class='form-group'>
            <label>Deskripsi Group</label>
            <input type='text' class='form-control' name='txtgroup_desc' 
            placeholder='Masukkan Deskripsi Group' value='$group_desc'>
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
    <table id="mgroup" class="display responsive" style="width:100%">
      <thead>
      <tr>
		      <!-- <th>No</th> -->
          <th>Kode Group</th>
          <th>Deskripsi Group</th>
          <th>Aksi</th>
      </tr>
      </thead>
      <tbody>
      
      </tbody>
    </table>
  </div>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> 
<script src="js/Mastergroup.js"></script>