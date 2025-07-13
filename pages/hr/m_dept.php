<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("hr_lokasi","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_dept = $_GET['id']; } else {$id_dept = "";}
$titlenya="Master Department";
$mode="";
$mod=$_GET['mod'];

# COPAS EDIT
if ($id_dept=="")
{ $dept = "";
  $dept_desc = "";
}
else
{ $query = mysql_query("SELECT * FROM masterpilihan 
    where kode_pilihan='Dept' and nama_pilihan='$id_dept'");
  $data = mysql_fetch_array($query);
  $dept = $data['nama_pilihan'];
  $dept_desc = $data['nama_pilihan'];
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { var dept = document.form.txtdept.value;
      var dept_desc = document.form.txtdept_desc.value;
      if (dept == '') { alert('Dept Tidak Boleh Kosong'); document.form.txtdept.focus();valid = false;}
      else if (dept_desc == '') { alert('Deskripsi Tidak Boleh Kosong'); document.form.txtdept_desc.focus();valid = false;}
      else valid = true;
      return valid;
      exit;
    }
  </script>";
# END COPAS VALIDASI

# COPAS ADD
if ($mod=="16") {
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='s_dept.php?mod=$mod&id=$id_dept' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Dept *</label>
            <input type='text' class='form-control' name='txtdept' 
            placeholder='Masukkan Dept' value='$dept'>
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
      <a href='../hr/?mod=16' class='btn btn-primary btn-s'>
        <i class='fa fa-plus'></i> New
      </a>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>
		      <th>No</th>
          <th>Dept</th>
          <th>Aksi</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		    $query = mysql_query("SELECT * FROM masterpilihan where kode_pilihan='Dept' ");
			  $no = 1; 
				while($data = mysql_fetch_array($query))
			  { echo "<tr>";
				  echo "<td>$no</td>"; 
          echo "<td>$data[nama_pilihan]</td>";
          echo "
          <td>
            <a $cl_ubah href='../hr/?mod=16&id=$data[nama_pilihan]' 
              class='btn btn-info btn-s' $tt_ubah
            </a>
            <a $cl_hapus href='del_data.php?mod=$mod&id=$data[nama_pilihan]&pro=MDep' $tt_hapus";?> 
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