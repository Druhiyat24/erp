<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$mode="";
$mod=$_GET['mod'];
$titlenya="Data Keluarga Karyawan";
if (isset($_GET['id'])) { $nik=$_GET['id']; } else { $nik=""; }

# COPAS EDIT
if ($nik=="")
{ $nik = "";
  $nama_pasangan = "";
  $nik_pasangan = "";
  $no_bpjs_pasangan = "";
  $tempat_lahir_pasangan = "";
  $tgl_lahir_pasangan = "";
  $nama = "";
  $nama_anak1 = "";
  $nik_anak1 = "";
  $no_bpjs_anak1 = "";
  $tempat_lahir_anak1 = "";
  $tgl_lahir_anak1 = "";
  $nama_anak2 = "";
  $nik_anak2 = "";
  $no_bpjs_anak2 = "";
  $tempat_lahir_anak2 = "";
  $tgl_lahir_anak2 = "";
  $nama_anak3 = "";
  $nik_anak3 = "";
  $no_bpjs_anak3 = "";
  $tempat_lahir_anak3 = "";
  $tgl_lahir_anak3 = "";
}
else
{ $query = mysql_query("SELECT a.*,s.nama FROM hr_employeefamily a 
    inner join hr_masteremployee s on a.nik=s.nik where a.nik='$nik'");
  $data = mysql_fetch_array($query);
  $nik = $data['nik'];
  $nama_pasangan = $data['nama_pasangan'];
  $nik_pasangan = $data['nik_pasangan'];
  $no_bpjs_pasangan = $data['no_bpjs_pasangan'];
  $tempat_lahir_pasangan = $data['tempat_lahir_pasangan'];
  $tgl_lahir_pasangan = $data['tgl_lahir_pasangan'];
  $nama = $data['nama'];
  $nama_anak1 = $data['nama_anak1'];
  $nik_anak1 = $data['nik_anak1'];
  $no_bpjs_anak1 = $data['no_bpjs_anak1'];
  $tempat_lahir_anak1 = $data['tempat_lahir_anak1'];
  $tgl_lahir_anak1 = $data['tgl_lahir_anak1'];
  $nama_anak2 = $data['nama_anak2'];
  $nik_anak2 = $data['nik_anak2'];
  $no_bpjs_anak2 = $data['no_bpjs_anak2'];
  $tempat_lahir_anak2 = $data['tempat_lahir_anak2'];
  $tgl_lahir_anak2 = $data['tgl_lahir_anak2'];
  $nama_anak3 = $data['nama_anak3'];
  $nik_anak3 = $data['nik_anak3'];
  $no_bpjs_anak3 = $data['no_bpjs_anak3'];
  $tempat_lahir_anak3 = $data['tempat_lahir_anak3'];
  $tgl_lahir_anak3 = $data['tgl_lahir_anak3'];
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>";
  echo "function validasi()";
  echo "{";
    echo "var nik = document.form.txtnik.value;";
    echo "if (nik == '') { alert('NIK tidak boleh kosong'); document.form.txtnik.focus();valid = false;}";
    echo "else valid = true;";
    echo "return valid;";
    echo "exit;";
  echo "}";
echo "</script>";
# END COPAS VALIDASI
?>
  <div class="box">
  <?PHP
  # COPAS ADD
if ($mod=="5") {
echo "<div class='box-body'>";
  echo "<div class='row'>";
  echo "<form method='post' name='form' action='save_fam.php?mod=$mod&mode=$mode' onsubmit='return validasi()'>";
  echo "<div class='col-md-3'>";
  echo "<div class='form-group'>";
    echo "<label>NIK *</label>";
    $sql = "select nik isi,concat(nik,'|',nama) tampil from hr_masteremployee order by nik desc";
    echo "<select class='form-control select2' style='width: 100%;' name='txtnik'>";
    IsiCombo($sql,$nik,'Pilih NIK');
    echo "</select>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Nama Pasangan</label>";
    echo "<input type='text' class='form-control' name='txtnama_pasangan' placeholder='Masukkan Nama Pasangan' value='$nama_pasangan'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>NIK Pasangan</label>";
    echo "<input type='text' class='form-control' name='txtnik_pasangan' placeholder='Masukkan NIK Pasangan' value='$nik_pasangan'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>No. BPJS Pasangan</label>";
    echo "<input type='text' class='form-control' name='txtno_bpjs_pasangan' placeholder='Masukkan No. BPJS Pasangan' value='$no_bpjs_pasangan'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Tempat Lahir Pasangan</label>";
    echo "<input type='text' class='form-control' name='txttempat_lahir_pasangan' placeholder='Masukkan Tempat Lahir Pasangan' value='$tempat_lahir_pasangan'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Tgl. Lahir Pasangan</label>";
    echo "<input type='text' class='form-control' id='datepicker1' name='txttgl_lahir_pasangan' placeholder='Masukkan Tgl. Lahir Pasangan' value='$tgl_lahir_pasangan'>";
  echo "</div>";
  echo "</div>";
  echo "<div class='col-md-3'>";
  echo "<div class='form-group'>";
    echo "<label>Nama</label>";
    echo "<input type='text' class='form-control' name='txtnama' placeholder='Masukkan Nama' readonly value='$nama'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Nama Anak 1</label>";
    echo "<input type='text' class='form-control' name='txtnama_anak1' placeholder='Masukkan Nama Anak 1' value='$nama_anak1'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>NIK Anak 1</label>";
    echo "<input type='text' class='form-control' name='txtnik_anak1' placeholder='Masukkan NIK Anak 1' value='$nik_anak1'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>No. BPJS Anak 1</label>";
    echo "<input type='text' class='form-control' name='txtno_bpjs_anak1' placeholder='Masukkan No. BPJS Anak 1' value='$no_bpjs_anak1'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Tempat Lahir Anak 1</label>";
    echo "<input type='text' class='form-control' name='txttempat_lahir_anak1' placeholder='Masukkan Tempat Lahir Anak 1' value='$tempat_lahir_anak1'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Tgl. Lahir Anak 1</label>";
    echo "<input type='text' class='form-control' id='datepicker2' name='txttgl_lahir_anak1' placeholder='Masukkan Tgl. Lahir Anak 1' value='$tgl_lahir_anak1'>";
  echo "</div>";
  echo "</div>";
  echo "<div class='col-md-3'>";
  echo "<div class='form-group'>";
    echo "<label>Nama Anak 2</label>";
    echo "<input type='text' class='form-control' name='txtnama_anak2' placeholder='Masukkan Nama Anak 2' value='$nama_anak2'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>NIK Anak 2</label>";
    echo "<input type='text' class='form-control' name='txtnik_anak2' placeholder='Masukkan NIK Anak 2' value='$nik_anak2'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>No. BPJS Anak 2</label>";
    echo "<input type='text' class='form-control' name='txtno_bpjs_anak2' placeholder='Masukkan No. BPJS Anak 2' value='$no_bpjs_anak2'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Tempat Lahir Anak 2</label>";
    echo "<input type='text' class='form-control' name='txttempat_lahir_anak2' placeholder='Masukkan Tempat Lahir Anak 2' value='$tempat_lahir_anak2'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Tgl. Lahir Anak 2</label>";
    echo "<input type='text' class='form-control' id='datepicker3' name='txttgl_lahir_anak2' placeholder='Masukkan Tgl. Lahir Anak 2' value='$tgl_lahir_anak2'>";
  echo "</div>";
  echo "</div>";
  echo "<div class='col-md-3'>";
  echo "<div class='form-group'>";
    echo "<label>Nama Anak 3</label>";
    echo "<input type='text' class='form-control' name='txtnama_anak3' placeholder='Masukkan Nama Anak 3' value='$nama_anak3'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>NIK Anak 3</label>";
    echo "<input type='text' class='form-control' name='txtnik_anak3' placeholder='Masukkan NIK Anak 3' value='$nik_anak3'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>No. BPJS Anak 3</label>";
    echo "<input type='text' class='form-control' name='txtno_bpjs_anak3' placeholder='Masukkan No. BPJS Anak 3' value='$no_bpjs_anak3'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Tempat Lahir Anak 3</label>";
    echo "<input type='text' class='form-control' name='txttempat_lahir_anak3' placeholder='Masukkan Tempat Lahir Anak 3' value='$tempat_lahir_anak3'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Tgl. Lahir Anak 3</label>";
    echo "<input type='text' class='form-control' id='datepicker4' name='txttgl_lahir_anak3' placeholder='Masukkan Tgl. Lahir Anak 3' value='$tgl_lahir_anak3'>";
  echo "</div>";
  echo "<div class='box-footer'>";
    echo "<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>";
  echo "</div>";
  echo "</form>";
  echo "</div>";
  echo "</div>";
  echo "</div>";
  echo "</div>";
  # END COPAS ADD
  } else { ?>
  <div class="box">
  <div class="box-header">
    <h3 class="box-title">List <?PHP echo $titlenya; ?></h3>
    <a href='../hr/?mod=5' class='btn btn-primary btn-s'>
      <i class='fa fa-plus'></i> New
    </a>
  </div>
  <div class="box-body">
      <table id="example1" class="table table-bordered table-striped">
          <thead>
          <tr>
				      <th>No</th>
              <th>Nik</th>
              <th>Nama</th>
              <th>Nama Pasangan</th>
              <th>Nama Anak 1</th>
              <th>Nama Anak 2</th>
              <th>Nama Anak 3</th>
              <th>Ubah</th>
              <th>Hapus</th>
          </tr>
          </thead>
          <tbody>
            <?php
            # QUERY TABLE
            $query = mysql_query("SELECT a.*,s.nama 
              FROM hr_employeefamily a inner join hr_masteremployee s 
              on a.nik=s.nik");
  				  $no = 1; 
    				while($data = mysql_fetch_array($query))
    			  { echo "<tr>";
      				  echo "<td>$no</td>"; 
      				  echo "<td>$data[nik]</td>"; 
      				  echo "<td>$data[nama]</td>"; 
      				  echo "<td>$data[nama_pasangan]</td>";
                echo "<td>$data[nama_anak1]</td>"; 
      				  echo "<td>$data[nama_anak2]</td>"; 
                echo "<td>$data[nama_anak3]</td>";
                echo "<td><a href='../hr/?mod=5&id=$data[nik]'>Ubah</a></td>";
      				  echo "<td><a href='del_data.php?id=$data[nik]&pro=EmpFam'";?> 
                onclick="return confirm('Apakah anda yakin akan menghapus ?')"><?PHP echo "Hapus</a></td>";
    				  echo "</tr>";
    				  $no++; // menambah nilai nomor urut
    				}
  				  ?>
          </tbody>
      </table>
      </div>
      </div>
      </div>
      </div>
      </div>
  </section>
  </div> </div>
  <?php } ?>
