<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("master_hs","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_hscode = $_GET['id']; } else {$id_hscode = "";}
$titlenya="Master HS & Tarif";
$mode="";
$mod=$_GET['mod'];

# COPAS EDIT
if ($id_hscode=="")
{ $hscode = "";
  $hsdesc = "";
  $hstarif = "";
}
else
{ $query = mysql_query("SELECT * FROM masterhs where id='$id_hscode'");
  $data = mysql_fetch_array($query);
  $hscode = $data['kode_hs'];
  $hsdesc = $data['nama_hs'];
  $hstarif = $data['tarif_hs'];
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { var hscode = document.form.txthscode.value;
      var hstarif = document.form.txthstarif.value;
      if (hscode == '') { alert('Hs Tidak Boleh Kosong'); document.form.txthscode.focus();valid = false;}
      else if (hstarif == '') { alert('Tarif Tidak Boleh Kosong'); document.form.txthstarif.focus();valid = false;}
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
      echo "<form method='post' name='form' action='s_hs.php?mod=$mod&id=$id_hscode' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>HS Code *</label>
            <input type='text' class='form-control' name='txthscode' 
            placeholder='Masukkan HS Code' value='$hscode'>
          </div>
          <div class='form-group'>
            <label>HS Desc *</label>
            <input type='text' class='form-control' name='txthsdesc' 
            placeholder='Masukkan HS Desc' value='$hsdesc'>
          </div>
          <div class='form-group'>
            <label>Tarif</label>
            <input type='text' class='form-control' name='txthstarif' 
            placeholder='Masukkan Tarif' value='$hstarif'>
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
          <th>HS Code</th>
          <th>HS Desc</th>
          <th>Tarif</th>
          <th>Aksi</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		    $query = mysql_query("SELECT * FROM masterhs ORDER BY id DESC limit 500");
			  $no = 1; 
				while($data = mysql_fetch_array($query))
			  { echo "<tr>";
				  echo "<td>$no</td>"; 
          echo "<td>$data[kode_hs]</td>";
          echo "<td>$data[nama_hs]</td>";
          echo "<td>$data[tarif_hs]</td>";
          echo "<td><a $cl_ubah href='../master/?mod=$mod&mode=$mode&id=$data[id]' $tt_ubah </a>";
				  echo " <a $cl_hapus href='d_hs.php?mod=$mod&mode=$mode&id=$data[id]' $tt_hapus";?> 
            onclick="return confirm('Apakah anda yakin akan menghapus ?')"><?PHP echo $tt_hapus2."</a>";
          echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
			  ?>
      </tbody>
    </table>
  </div>
</div>