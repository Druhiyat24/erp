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
  $pterms2 = "";
  $pterms_desc = "";
  $days_pterms="";
  $par_pterms="";
  $cri_pterms="";
}
else
{ $query = mysql_query("SELECT * FROM masterpterms where id='$id_pterms'");
  $data = mysql_fetch_array($query);
  $pterms = $data['kode_pterms'];
  $pterms2 = $data['terms_pterms'];
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
    { var pterms = document.form.cbopt.value;
      var pterms_days = document.form.txtdays.value;
      var pterms_cri = document.form.cbocri.value;
      if (pterms == '') { alert('Payment Terms Tidak Boleh Kosong'); document.form.txtpterms.focus();valid = false;}
      else if (pterms_days == '') { alert('Period Tidak Boleh Kosong'); document.form.txtpterms_desc.focus();valid = false;}
      else if (pterms_cri == '') { alert('Kriteria Tidak Boleh Kosong'); document.form.txtpterms_desc.focus();valid = false;}
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
        <input type='hidden' class='form-control' name='txtpterms'value='$pterms'>
        <input type='hidden' class='form-control' name='txtpterms_desc' value='$pterms_desc'>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Payment Terms</label>
            <select class='form-control select2' style='width: 100%;' id='cbopt' name='cbopt'>";
            $sql="select nama_pilihan isi,ucase(nama_pilihan) tampil from masterpilihan where 
              kode_pilihan='PTERMS'";
            IsiCombo($sql,$pterms2,'Pilih Payment Terms');
            echo "
            </select>
          </div>
          <div class='col-md-6'>
            <div class='form-group'>
              <label>Period</label>
              <input type='number' class='form-control' name='txtdays' value='$days_pterms'>";
              // echo "
              // <select class='form-control select2' style='width: 100%;' id='txtdays' name='txtdays'>";
              // $sql="select nama_pilihan isi,concat(nama_pilihan,' DAYS') tampil from masterpilihan where 
              //   kode_pilihan='DTERMS'";
              // IsiCombo($sql,$days_pterms,'Pilih Period');
              // echo "
              // </select>";
            echo "</div>
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
      <h3 class="box-title">Data <?php echo "Payment Terms"; ?></h3>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>
		      <th>No</th>
          <th>Deskripsi Payment Terms</th>
          <th>Deskripsi Payment Terms</th>
          <th>Period</th>
          <th>Kriteria</th>
          <th></th>
          <th></th>
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
						<td>$data[terms_pterms]</td>
						<td>$data[nama_pterms]</td>
            <td>$data[days_pterms]</td>
						<td>$data[cri_pterms]</td>";
          echo "
          <td>
            <a href='index.php?mod=$mod&mode=$mode&id=$data[id]' $tt_ubah </a>
          </td>";
				  echo "
          <td>
            <a href='d_terms.php?mod=$mod&mode=$mode&id=$data[id]' $tt_hapus";?> 
              onclick="return confirm('Are You Sure ?')"><?php echo $tt_hapus2."</a>
          </tr>";
				  $no++; // menambah nilai nomor urut
				}
			  ?>
      </tbody>
    </table>
  </div>
</div>