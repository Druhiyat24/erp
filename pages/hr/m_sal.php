<?php 
include '../../include/conn.php';
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$mode="";
$titlenya="Master Salary";
if (isset($_GET['id'])) { $nik=$_GET['id']; } else { $nik=""; }

# COPAS EDIT
if ($nik=="")
{ $nik = "";
  $nama_bank = "";
  $no_rekening = "";
  $ptkpcode = "";
  $gaji_pokok = "";
  $t_jabatan = "";
  $t_luarkota = "";
  $t_pulsa = "";
  $t_lain = "";
}
else
{ $query = mysql_query("SELECT * FROM hr_mastersalary where nik='$nik' ");
  $data = mysql_fetch_array($query);
  $nik = $data['nik'];
  $nama_bank = $data['nama_bank'];
  $no_rekening = $data['no_rekening'];
  $ptkpcode = $data['ptkpcode'];
  $gaji_pokok = $data['gaji_pokok'];
  $t_jabatan = $data['t_jabatan'];
  $t_luarkota = $data['t_luarkota'];
  $t_pulsa = $data['t_pulsa'];
  $t_lain = $data['t_lain'];
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>";
  echo "function validasi()";
  echo "{";
    echo "var nik = document.form.txtnik.value;";
    echo "var nama_bank = document.form.txtnama_bank.value;";
    echo "var no_rekening = document.form.txtno_rekening.value;";
    echo "var ptkpcode = document.form.txtptkpcode.value;";
    echo "var gaji_pokok = document.form.txtgaji_pokok.value;";

    echo "if (nik == '') { alert('NIK tidak boleh kosong'); document.form.txtnik.focus();valid = false;}";
    echo "else if (nama_bank == '') { alert('Nama Bank tidak boleh kosong'); document.form.txtnama_bank.focus();valid = false;}";
    echo "else if (no_rekening == '') { alert('No. Rekening tidak boleh kosong'); document.form.txtno_rekening.focus();valid = false;}";
    echo "else if (ptkpcode == '') { alert('PTKP tidak boleh kosong'); document.form.txtptkpcode.focus();valid = false;}";
    echo "else if (gaji_pokok == '') { alert('Gaji Pokok tidak boleh kosong'); document.form.txtgaji_pokok.focus();valid = false;}";
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
  echo "<div class='box-body'>";
  echo "<div class='row'>";
  echo "<form method='post' name='form' action='save_sal.php?mode=$mode&id=$nik' onsubmit='return validasi()'>";
  echo "<div class='col-md-3'>";
  echo "<div class='form-group'>";
    echo "<label>NIK *</label>";
    $sql = "select nik isi,concat(nik,'|',nama) tampil from hr_masteremployee order by nik";
    echo "<select class='form-control select2' style='width: 100%;' name='txtnik'>";
    IsiCombo($sql,$nik,'Pilih NIK');
    echo "</select>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Nama Bank *</label>";
    $sql = "select id isi,concat(kode_bank,'|',nama_bank) tampil 
      from masterbank ";
    echo "<select class='form-control select2' style='width: 100%;' name='txtnama_bank'>";
    IsiCombo($sql,$nama_bank,'Pilih Nama Bank');
    echo "</select>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>No. Rekening *</label>";
    echo "<input type='text' class='form-control' name='txtno_rekening' placeholder='Masukkan No. Rekening' value='$no_rekening'>";
  echo "</div>";
  echo "</div>";
  echo "<div class='col-md-3'>";
  echo "<div class='form-group'>";
    echo "<label>PTKP *</label>";
    $sql = "select ucase(ptkpcode) isi,ucase(ptkpcode) tampil from hr_masterptkp";
    echo "<select class='form-control select2' style='width: 100%;' name='txtptkpcode'>";
    IsiCombo($sql,$ptkpcode,'Pilih PTKP');
    echo "</select>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Gaji Pokok *</label>";
    echo "<input type='text' class='form-control' name='txtgaji_pokok' placeholder='Masukkan Gaji Pokok' value='$gaji_pokok'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Tunj. Jabatan</label>";
    echo "<input type='text' class='form-control' name='txtt_jabatan' placeholder='Masukkan Tunj. Jabatan' value='$t_jabatan'>";
  echo "</div>";
  echo "</div>";
  echo "<div class='col-md-3'>";
  echo "<div class='form-group'>";
    echo "<label>Tunj. Luar Kota</label>";
    echo "<input type='text' class='form-control' name='txtt_luarkota' placeholder='Masukkan Tunj. Luar Kota' value='$t_luarkota'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Tunj. Pulsa</label>";
    echo "<input type='text' class='form-control' name='txtt_pulsa' placeholder='Masukkan Tunj. Pulsa' value='$t_pulsa'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Tunj. Lain-Lain</label>";
    echo "<input type='text' class='form-control' name='txtt_lain' placeholder='Masukkan Tunj. Lain-Lain' value='$t_lain'>";
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
  ?>
  <div class="box">
  <div class="box-header">
      <h3 class="box-title">Detil <?PHP echo $titlenya; ?></h3>
  </div>
  <div class="box-body">
      <table id="example1" class="table table-bordered table-striped">
          <thead>
          <tr>
				      <th>No</th>
              <th>Nik</th>
              <th>Nama</th>
              <th>PTKP</th>
              <th>Gaji Pokok</th>
              <th>Tunj. Jabatan</th>
              <th>Nama Bank</th>
              <th>Ubah</th>
          </tr>
          </thead>
          <tbody>
            <?php
            # QUERY TABLE
            $query = mysql_query("SELECT a.*,s.nama,d.kode_bank 
              FROM hr_mastersalary a inner join hr_masteremployee s on a.nik=s.nik
              left join masterbank d on a.nama_bank=d.id ");
  				  $no = 1; 
    				while($data = mysql_fetch_array($query))
    			  { echo "<tr>";
      				  echo "<td>$no</td>"; 
      				  echo "<td>$data[nik]</td>"; 
      				  echo "<td>$data[nama]</td>"; 
      				  echo "<td>$data[ptkpcode]</td>";
                echo "<td>".fn($data['gaji_pokok'],2)."</td>"; 
      				  echo "<td>".fn($data['t_jabatan'],2)."</td>"; 
                echo "<td>$data[kode_bank]</td>";
                echo "<td><a href='?mod=12&id=$data[nik]'>Ubah</a></td>";
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