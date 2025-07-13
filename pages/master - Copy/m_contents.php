<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("generate_kode","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_type = $_GET['id']; } else {$id_type = "";}
$titlenya="Master Type";
$mode="";
$mod=$_GET['mod'];

# COPAS EDIT
if ($id_type=="")
{ $type = "";
  $type_desc = "";
  $id_prev = "";
}
else
{ $query = mysql_query("SELECT * FROM mastercontents where id='$id_type'");
  $data = mysql_fetch_array($query);
  $type = $data['kode_contents'];
  $type_desc = $data['nama_contents'];
  $id_prev = $data['id_type'];
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { var id_group = document.form.cboid.value;
      var type = document.form.txttype.value;
      var type_desc = document.form.txttype_desc.value;
      if (id_group == '') { alert('ID Type Tidak Boleh Kosong'); document.form.txttype.focus();valid = false;}
      else if (type == '') { alert('Kode contEnts Tidak Boleh Kosong'); document.form.txttype.focus();valid = false;}
      else if (type_desc == '') { alert('Deskripsi Tidak Boleh Kosong'); document.form.txttype_desc.focus();valid = false;}
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
      echo "<form method='post' name='form' action='s_contents.php?mod=$mod&id=$id_type' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Id Type *</label>
            <select class='form-control select2' style='width: 100%;' id='cboid' name='cboid'>";
            IsiCombo("select d.id isi,concat(nama_group,' ',nama_sub_group,' ',nama_type) 
              tampil from mastergroup a inner join mastersubgroup s on 
              a.id=s.id_group 
              inner join mastertype2 d on s.id=d.id_sub_group"
              ,$id_prev,'Pilih Id Type');
            echo "
            </select>
          </div>
          <div class='form-group'>
            <label>Kode Contents *</label>
            <input type='text' class='form-control' name='txttype' 
            placeholder='Masukkan Kode Contents' value='$type'>
          </div>
          <div class='form-group'>
            <label>Deskripsi</label>
            <input type='text' class='form-control' name='txttype_desc' 
            placeholder='Masukkan Deskripsi' value='$type_desc'>
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
    <table id="mcontent" class="display responsive" style="width:100%">
      <thead>
      <tr>
		      <!-- <th>No</th> -->
          <th>Nama Sub Group</th>
          <th>Kode Contents</th>
          <th>Deskripsi Type</th>
          <th>Aksi</th>
      </tr>
      </thead>
      <tbody>
       
      </tbody>
    </table>
  </div>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> 
<script src="js/Mastercontent.js"></script>