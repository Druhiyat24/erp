<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("master_unit","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_Unit = $_GET['id']; } else {$id_Unit = "";}
$titlenya="Master Unit";
$mode="";
$mod=$_GET['mod'];

# COPAS EDIT
if ($id_Unit=="")
{ $Unit = "";
  $Unit_desc = "";
}
else
{ $query = mysql_query("SELECT * FROM masterpilihan where kode_pilihan='Satuan' and nama_pilihan='$id_Unit'");
  $data = mysql_fetch_array($query);
  $Unit = $data['nama_pilihan'];
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { var unit = document.form.txtUnit.value;
      if (unit == '') { alert('Unit Tidak Boleh Kosong'); document.form.txtUnit.focus();valid = false;}
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
      echo "<form method='post' name='form' action='simpan_unit.php?mod=$mod&id=$id_Unit' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Unit *</label>
            <input type='text' class='form-control' name='txtUnit' 
            placeholder='Masukkan Unit' value='$Unit'>
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
          <th>Unit</th>
          <th></th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		    $query = mysql_query("SELECT * FROM masterpilihan where kode_pilihan='Satuan' ORDER BY nama_pilihan limit 500");
			  $no = 1; 
				while($data = mysql_fetch_array($query))
			  { echo "
          <tr>
            <td>$no</td>
            <td>$data[nama_pilihan]</td>
            <td>
              <a $cl_ubah href='../master/?mod=26&mode=$mode&id=$data[nama_pilihan]' $tt_ubah </a>";
				  echo " <a $cl_hapus href='d_unit.php?mod=26&mode=$mode&id=$data[nama_pilihan]' $tt_hapus";?> 
            onclick="return confirm('Apakah anda yakin akan menghapus ?')"><?PHP echo $tt_hapus2."</a>";
          echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
			  ?>
      </tbody>
    </table>
  </div>
</div>