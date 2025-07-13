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
{ $query = mysql_query("SELECT * FROM mastertype2 where id='$id_type'");
  $data = mysql_fetch_array($query);
  $type = $data['kode_type'];
  $type_desc = $data['nama_type'];
  $id_prev = $data['id_sub_group'];
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { var id_group = document.form.cboid.value;
      var type = document.form.txttype.value;
      var type_desc = document.form.txttype_desc.value;
      if (id_group == '') { alert('ID Sub Group Tidak Boleh Kosong'); document.form.txttype.focus();valid = false;}
      else if (type == '') { alert('Material Type Tidak Boleh Kosong'); document.form.txttype.focus();valid = false;}
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
      echo "<form method='post' name='form' action='s_type.php?mod=$mod&id=$id_type' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Id Sub Group *</label>
            <select class='form-control select2' style='width: 100%;' id='cboid' name='cboid'>";
            IsiCombo("select s.id isi,concat(nama_group,' ',nama_sub_group) 
              tampil from mastergroup a inner join mastersubgroup s on 
              a.id=s.id_group",$id_prev,'Pilih Id Sub Group');
            echo "
            </select>
          </div>
          <div class='form-group'>
            <label>MaterIal Type *</label>
            <input type='text' class='form-control' name='txttype' 
            placeholder='Masukkan MaterIal Type' value='$type'>
          </div>
          <div class='form-group'>
            <label>Deskripsi Type</label>
            <input type='text' class='form-control' name='txttype_desc' 
            placeholder='Masukkan Deskripsi Type' value='$type_desc'>
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
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>
		      <th>No</th>
          <th>Nama Sub Group</th>
          <th>MaterIal Type</th>
          <th>Deskripsi Type</th>
          <th>Aksi</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		    $query = mysql_query("SELECT concat(a.nama_group,' ',s.nama_sub_group) tampil
          ,d.* FROM 
          mastergroup a inner join mastersubgroup s on a.id=s.id_group
          inner join mastertype2 d on s.id=d.id_sub_group 
          ORDER BY d.id DESC");
			  $no = 1; 
				while($data = mysql_fetch_array($query))
			  { echo "<tr>";
				  echo "<td>$no</td>"; 
          echo "<td>$data[tampil]</td>";
          echo "<td>$data[kode_type]</td>";
          echo "<td>$data[nama_type]</td>";
          echo "<td><a $cl_ubah href='../master/?mod=$mod&mode=$mode&id=$data[id]' $tt_ubah </a>";
				  echo " <a $cl_hapus href='d_type.php?mod=$mod&mode=$mode&id=$data[id]' $tt_hapus";?> 
            onclick="return confirm('Apakah anda yakin akan menghapus ?')"><?PHP echo $tt_hapus2."</a>";
          echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
			  ?>
      </tbody>
    </table>
  </div>
</div>