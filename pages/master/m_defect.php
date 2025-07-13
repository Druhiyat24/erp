<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("m_defect","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_type = $_GET['id']; } else {$id_type = "";}
$titlenya="Master Defect";
$mode="";
$mod=$_GET['mod'];

# COPAS EDIT
if ($id_type=="")
{ $type = "";
  $type_desc = "";
  $id_prev = "";
  $id_type2="";
  $id_risk="";
  $id_product="";
  $kode_posisi="";
  $nama_posisi="";
}
else
{ $query = mysql_query("SELECT * FROM master_defect where id_defect='$id_type'");
  $data = mysql_fetch_array($query);
  $type = $data['kode_defect'];
  $type_desc = $data['nama_defect'];
  $id_prev = $data['jenis_defect'];
  $id_type2 = $data['mattype'];
  $id_risk = $data['type_risk'];
  $id_product = $data['id_product'];
  $kode_posisi = $data['kode_posisi'];
  $nama_posisi = $data['nama_posisi'];
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { var id_group = document.form.cboid.value;
      var type = document.form.txttype.value;
      var type_desc = document.form.txttype_desc.value;
      var type_risk = document.form.cborisk.value;
      if (id_group == '') { alert('Jenis Tidak Boleh Kosong'); document.form.txttype.focus();valid = false;}
      else if (type == '') { alert('Kode Defect Tidak Boleh Kosong'); document.form.txttype.focus();valid = false;}
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
      echo "<form method='post' name='form' action='s_defect.php?mod=$mod&id=$id_type' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Jenis *</label>
            <select class='form-control select2' style='width: 100%;' name='cbojenis'>";
            IsiCombo("select nama_pilihan isi,nama_pilihan tampil from masterpilihan where 
              kode_pilihan='Jenis_Def'"
              ,$id_prev,'Pilih Jenis');
            echo "
            </select>
          </div>
          <div class='form-group'>
            <label>Type *</label>
            <select class='form-control select2' style='width: 100%;' id='cboid' name='cboid'>";
            IsiCombo("select nama_pilihan isi,nama_pilihan tampil from masterpilihan where kode_pilihan='Jenis_Defect'"
              ,$id_type2,'Pilih Type');
            echo "
            </select>
          </div>
          <div class='form-group'>
            <label>Type Risk *</label>
            <select class='form-control select2' style='width: 100%;' name='cborisk'>";
            IsiCombo("select nama_pilihan isi,nama_pilihan tampil from masterpilihan 
              where kode_pilihan='Jenis_Defect2'"
              ,$id_risk,'Pilih Type');
            echo "
            </select>
          </div>
        </div>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Product Group</label>
            <select class='form-control select2' style='width: 100%;' name='txtid_product'>";
            IsiCombo("select id isi,product_group tampil from 
            	masterproduct group by product_group"
              ,$id_product,'Pilih Product Group');
            echo "
            </select>
          </div>
          <div class='form-group'>
            <label>Kode Defect *</label>
            <input type='text' class='form-control' name='txttype' 
            placeholder='Masukkan Kode ContEnts' value='$type'>
          </div>
          <div class='form-group'>
            <label>Deskripsi</label>
            <input type='text' class='form-control' name='txttype_desc' 
            placeholder='Masukkan Deskripsi' value='$type_desc'>
          </div>
        </div>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Kode Posisi</label>
            <input type='text' class='form-control' name='txtkode_posisi' 
            placeholder='Masukkan Kode Posisi' value='$kode_posisi'>
          </div>
          <div class='form-group'>
            <label>Nama Posisi</label>
            <input type='text' class='form-control' name='txtnama_posisi' 
            placeholder='Masukkan Nama Posisi' value='$nama_posisi'>
          </div>
          <div class='form-group'>
            <label>Remark</label>
            <input type='text' class='form-control' name='txtremark' 
            placeholder='Masukkan Remark' value='$remark'>
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
    <table id="mdefect" class="display responsive" style="width:100%">
      <thead>
      <tr>
		      <!-- <th>No</th> -->
          <th>Jenis</th>
          <th>Type</th>
          <th>Kode Defect</th>
          <th>Deskripsi</th>
          <th>Kode Posisi</th>
          <th>Posisi</th>
          <th>Remark</th>
          <th>Aksi</th>
      </tr>
      </thead>
      <tbody>
        
      </tbody>
    </table>
  </div>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> 
<script src="js/Masterdefect.js"></script>