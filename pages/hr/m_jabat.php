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
if ($id_jabatan=="")
{ $jabatan = "";
  $jabatan_desc = "";
}
else
{ $query = mysql_query("SELECT * FROM masterpilihan 
    where nama_pilihan='$id_jabatan' and kode_pilihan='Jabatan'");
  $data = mysql_fetch_array($query);
  $jabatan = $data['nama_pilihan'];
  $jabatan_desc = $data['nama_pilihan'];
}
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
      echo "<form method='post' name='form' action='s_jabat.php?mod=$mod&id=$id_jabatan' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Jabatan *</label>
            <input type='text' class='form-control' name='txtjabatan' 
            placeholder='Masukkan Jabatan' value='$jabatan'>
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
          <th>Jabatan</th>
          <th>Aksi</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		    $query = mysql_query("SELECT * FROM masterpilihan where kode_pilihan='Jabatan' ");
			  $no = 1; 
				while($data = mysql_fetch_array($query))
			  { echo "<tr>";
				  echo "<td>$no</td>"; 
          echo "<td>$data[nama_pilihan]</td>";
          echo "
          <td>
            <a $cl_ubah href='../hr/?mod=$mod&id=$data[nama_pilihan]' 
              class='btn btn-info btn-s' $tt_ubah
            </a>
            <a $cl_hapus href='del_data.php?mod=$mod&id=$data[nama_pilihan]&pro=MJab' $tt_hapus";?> 
              onclick="return confirm('Apakah anda yakin akan menghapus ?')">
              <?PHP echo $tt_hapus2."</a>
          </td>";
          echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
			  ?>
      </tbody>
    </table>
  </div>
</div>