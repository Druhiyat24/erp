<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("hr_lokasi","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_lokasi = $_GET['id']; } else {$id_lokasi = "";}
$titlenya="Master Lokasi";
$mode="";
$mod=$_GET['mod'];

# COPAS EDIT
if ($id_lokasi=="")
{ $lokasi = "";
  $lokasi_desc = "";
}
else
{ $query = mysql_query("SELECT * FROM masterpilihan where 
    kode_pilihan='Lokasi' and nama_pilihan='$id_lokasi'");
  $data = mysql_fetch_array($query);
  $lokasi = $data['nama_pilihan'];
  $lokasi_desc = $data['nama_pilihan'];
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { var lokasi = document.form.txtlokasi.value;
      var lokasi_desc = document.form.txtlokasi_desc.value;
      if (lokasi == '') { alert('Lokasi Tidak Boleh Kosong'); document.form.txtlokasi.focus();valid = false;}
      else if (lokasi_desc == '') { alert('Deskripsi Tidak Boleh Kosong'); document.form.txtlokasi_desc.focus();valid = false;}
      else valid = true;
      return valid;
      exit;
    }
  </script>";
# END COPAS VALIDASI

# COPAS ADD
if ($mod=="3") {
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='s_lokasi.php?mod=$mod&id=$id_lokasi' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Lokasi *</label>
            <input type='text' class='form-control' name='txtlokasi' 
            placeholder='Masukkan Lokasi' value='$lokasi'>
          </div>
          <button type='submit' name='submit' class='btn btn-primary'>
          Simpan</button>
        </div>";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
} else {
?>
<div class="box">
  <div class="box-header">
      <h3 class="box-title">Data <?PHP echo $titlenya; ?></h3>
      <a href='../hr/?mod=3' class='btn btn-primary btn-s'>
        <i class='fa fa-plus'></i> New
      </a>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>
		      <th>No</th>
          <th>Lokasi</th>
          <th>Action</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		    $query = mysql_query("SELECT * FROM masterpilihan where kode_pilihan='Lokasi' ");
			  $no = 1; 
				while($data = mysql_fetch_array($query))
			  { echo "<tr>";
				  echo "<td>$no</td>"; 
          echo "<td>$data[nama_pilihan]</td>";
          echo "
          <td>
            <a $cl_ubah href='../hr/?mod=3&id=$data[nama_pilihan]' 
              class='btn btn-info btn-s' $tt_ubah
            </a>
            <a $cl_hapus href='del_data.php?mod=$mod&id=$data[nama_pilihan]&pro=MLok' $tt_hapus";?> 
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
<?php } ?>