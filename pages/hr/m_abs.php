<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$mode="";
$titlenya="Master Kode Absensi";
if (isset($_GET['id'])) { $id_item=$_GET['id']; } else { $id_item=""; }

# COPAS EDIT
if ($id_item=="")
{ $kodeabsen = "";
  $keterangan = "";
  $pot_cuti = "";
  $jml_hari = "";
}
else
{ $query = mysql_query("SELECT * FROM hr_masterabsen where kodeabsen='$id_item'");
  $data = mysql_fetch_array($query);
  $kodeabsen = $data['kodeabsen'];
  $keterangan = $data['keterangan'];
  $pot_cuti = $data['pot_cuti'];
  $jml_hari = $data['jml_hari'];
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>";
  echo "function validasi()";
  echo "{";
    echo "var kodeabsen = document.form.txtkodeabsen.value;";
    echo "var keterangan = document.form.txtketerangan.value;";
    echo "var pot_cuti = document.form.txtpot_cuti.value;";

    echo "if (kodeabsen == '') { alert('Kode Absen tidak boleh kosong'); document.form.txtkodeabsen.focus();valid = false;}";
    echo "else if (keterangan == '') { alert('Deskripsi tidak boleh kosong'); document.form.txtketerangan.focus();valid = false;}";
    echo "else if (pot_cuti == '') { alert('Pot. Cuti tidak boleh kosong'); document.form.txtpot_cuti.focus();valid = false;}";
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
  echo "<form method='post' name='form' action='save_abs.php?mode=$mode&id=$id_item' onsubmit='return validasi()'>";
  echo "<div class='col-md-3'>";
    echo "<div class='form-group'>";
      echo "<label>Kode Absen *</label>";
      echo "<input type='text' class='form-control' name='txtkodeabsen' placeholder='Masukkan Kode Absen' value='$kodeabsen'>";
    echo "</div>";
    echo "<div class='form-group'>";
      echo "<label>Deskripsi *</label>";
      echo "<input type='text' class='form-control' name='txtketerangan' placeholder='Masukkan Deskripsi' value='$keterangan'>";
    echo "</div>";
    echo "<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>";
  echo "</div>";
  echo "<div class='col-md-3'>";
    echo "<div class='form-group'>";
      echo "<label>Pot. Cuti *</label>";
      $sql = "select if(nama_pilihan='PEREMPUAN','Y','N') isi,
        if(nama_pilihan='PEREMPUAN','Y','N') tampil 
        from masterpilihan where kode_pilihan='Sex'";
      echo "<select class='form-control select2' style='width: 100%;' name='txtpot_cuti'>";
      IsiCombo($sql,$pot_cuti,'Pilih Pot. Cuti');
      echo "</select>";
    echo "</div>";
    echo "<div class='form-group'>";
      echo "<label>Jumlah Hari</label>";
      echo "<input type='text' class='form-control' name='txthari' placeholder='Masukkan Jml Hari' value='$jml_hari'>";
    echo "</div>";
  echo "</div>";
  echo "</form>";
  echo "</div>";
  echo "</div>";
  echo "</div>";
  # END COPAS ADD
  ?>
  <div class="box">
  <div class="box-header">
      <h3 class="box-title"><?PHP echo $titlenya; ?></h3>
  </div>
  <div class="box-body">
      <table id="example1" class="table table-bordered table-striped">
          <thead>
          <tr>
				      <th>No</th>
              <th>Kode Absensi</th>
              <th>Deskripsi</th>
              <th>Pot. Cuti</th>
              <th>Jml Hari</th>
              <th>Ubah</th>
              <th>Hapus</th>
          </tr>
          </thead>
          <tbody>
            <?php
            # QUERY TABLE
            $query = mysql_query("SELECT * from hr_masterabsen");
  				  $no = 1; 
    				while($data = mysql_fetch_array($query))
    			  { echo "<tr>";
      				  echo "<td>$no</td>"; 
      				  echo "<td>$data[kodeabsen]</td>"; 
      				  echo "<td>$data[keterangan]</td>"; 
      				  echo "<td>$data[pot_cuti]</td>";
                echo "<td>$data[jml_hari]</td>";
                echo "<td><a href='?mod=10&id=$data[kodeabsen]'>Ubah</a></td>";
      				  echo "<td><a href='del_data.php?id=$data[kodeabsen]&pro=MAbs'";?> 
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
