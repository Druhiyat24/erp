<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("pay_terms","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_pterms = $_GET['id']; } else {$id_pterms = "";}
$titlenya="Master Pterms";
$mode="";
$mod=$_GET['mod'];

# COPAS EDIT
if ($id_pterms=="")
{ $pterms = "";
  $pterms_desc = "";
  $days_pterms="";
  $par_pterms="";
  $cri_pterms="";
}
else
{ $query = mysql_query("SELECT * FROM masterpterms where id='$id_pterms'");
  $data = mysql_fetch_array($query);
  $pterms = $data['kode_pterms'];
  $pterms_desc = $data['nama_pterms'];
  $days_pterms=$data['days_pterms'];
  $par_pterms=$data['par_pterms'];
  $cri_pterms=$data['cri_pterms'];
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { var pterms = document.form.txtpterms.value;
      var pterms_desc = document.form.txtpterms_desc.value;
      if (pterms == '') { alert('Pterms Tidak Boleh Kosong'); document.form.txtpterms.focus();valid = false;}
      else if (pterms_desc == '') { alert('Deskripsi Tidak Boleh Kosong'); document.form.txtpterms_desc.focus();valid = false;}
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
      echo "<form method='post' name='form' action='s_pterms.php?mod=$mod&id=$id_pterms' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Kode Payment Terms *</label>
            <input type='text' class='form-control' name='txtpterms' 
            placeholder='Masukkan Kode Payment Terms' value='$pterms'>
          </div>
          <div class='form-group'>
            <label>Deskripsi Payment Terms</label>
            <input type='text' class='form-control' name='txtpterms_desc' 
            placeholder='Masukkan Deskripsi Payment Terms' value='$pterms_desc'>
          </div>
          <button type='submit' name='submit' class='btn btn-primary'>
          Simpan</button>
        </div>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Days Terms *</label>
            <input type='number' class='form-control' name='txtdays' 
            placeholder='Masukkan Jumlah Hari' value='$days_pterms'>
          </div>
          <div class='col-md-6'>
	          <div class='form-group'>
	            <label>Parameter</label>
	            <select class='form-control select2' style='width: 100%;' id='cbopar' name='cbopar'>";
	            $sql="select nama_pilihan isi,nama_pilihan tampil from masterpilihan where 
	            	kode_pilihan='PAR_TERMS'";
	            IsiCombo($sql,$par_pterms,'Pilih Parameter');
	            echo "
	            </select>
	          </div>
	        </div>
	        <div class='col-md-6'>
	          <div class='form-group'>
	            <label>Kriteria</label>
	            <select class='form-control select2' style='width: 100%;' id='cbocri' name='cbocri'>";
	            $sql="select nama_pilihan isi,nama_pilihan tampil from masterpilihan where 
	            	kode_pilihan='CRI_TERMS'";
	            IsiCombo($sql,$cri_pterms,'Pilih Kriteria');
	            echo "
	            </select>
	          </div>
	        </div>
	      </div>";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
?>
<div class="box">
  <div class="box-header">
      <h3 class="box-title">Data <?php echo "Payment Terms"; ?></h3>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>
		      <th>No</th>
          <th>Kode Payment Terms</th>
          <th>Deskripsi Payment Terms</th>
          <th>Jumlah Hari</th>
          <th>Parameter</th>
          <th>Kriteria</th>
          <th>Aksi</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		    $query = mysql_query("SELECT * FROM masterpterms ORDER BY id DESC limit 500");
			  $no = 1; 
				while($data = mysql_fetch_array($query))
			  { echo "
					<tr>
						<td>$no</td>
						<td>$data[kode_pterms]</td>
						<td>$data[nama_pterms]</td>
						<td>$data[days_pterms]</td>
						<td>$data[par_pterms]</td>
						<td>$data[cri_pterms]</td>";
          echo "<td><a $cl_ubah href='index.php?mod=$mod&mode=$mode&id=$data[id]' $tt_ubah </a>";
				  echo " <a $cl_hapus href='d_terms.php?mod=$mod&mode=$mode&id=$data[id]' $tt_hapus";?> 
            onclick="return confirm('Apakah anda yakin akan menghapus ?')"><?PHP echo $tt_hapus2."</a>";
          echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
			  ?>
      </tbody>
    </table>
  </div>
</div>