<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$mode="";
$titlenya="Master Hari Libur";
if (isset($_GET['id'])) { $id_item=$_GET['id']; } else { $id_item=""; }

# COPAS EDIT
if ($id_item=="")
{ $holiday_date = "";
  $holiday_desc = "";
  $pot_cuti = "";
}
else
{ $query = mysql_query("SELECT * FROM hr_masterholiday where 
    holiday_date='$id_item'");
  $data = mysql_fetch_array($query);
  $holiday_date = $data['holiday_date'];
  $holiday_desc = $data['holiday_desc'];
  $pot_cuti = $data['pot_cuti'];
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>";
  echo "function validasi()";
  echo "{";
    echo "var holiday_date = document.form.txtholiday_date.value;";
    echo "var holiday_desc = document.form.txtholiday_desc.value;";
    echo "var pot_cuti = document.form.txtpot_cuti.value;";

    echo "if (holiday_date == '') { alert('Tgl. Hari Libur tidak boleh kosong'); document.form.txtholiday_date.focus();valid = false;}";
    echo "else if (holiday_desc == '') { alert('Deskripsi tidak boleh kosong'); document.form.txtholiday_desc.focus();valid = false;}";
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
  echo "<form method='post' name='form' action='save_hol.php?mode=$mode&id=$id_item' onsubmit='return validasi()'>";
  echo "<div class='col-md-3'>";
  echo "<div class='form-group'>";
    echo "<label>Tgl. Hari Libur *</label>";
    echo "<input type='text' class='form-control' id='datepicker1' name='txtholiday_date' placeholder='Masukkan Tgl. Hari Libur' value='$holiday_date'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Deskripsi *</label>";
    echo "<input type='text' class='form-control' name='txtholiday_desc' placeholder='Masukkan Deskripsi' value='$holiday_desc'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Pot. Cuti *</label>";
    $sql = "select if(nama_pilihan='PEREMPUAN','Y','N') isi,
      if(nama_pilihan='PEREMPUAN','Y','N') tampil from 
      masterpilihan where kode_pilihan='Sex'";
    echo "<select class='form-control select2' style='width: 100%;' name='txtpot_cuti'>";
    IsiCombo($sql,$pot_cuti,'Pilih Pot. Cuti');
    echo "</select>";
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
              <th>Tgl. Hari Libur</th>
              <th>Deskripsi</th>
              <th>Pot. Cuti</th>
              <th>Ubah</th>
              <th>Hapus</th>
          </tr>
          </thead>
          <tbody>
            <?php
            # QUERY TABLE
            $query = mysql_query("SELECT * from hr_masterholiday");
  				  $no = 1; 
    				while($data = mysql_fetch_array($query))
    			  { echo "<tr>";
      				  echo "<td>$no</td>"; 
      				  echo "<td>$data[holiday_date]</td>"; 
      				  echo "<td>$data[holiday_desc]</td>"; 
      				  echo "<td>$data[pot_cuti]</td>";
                echo "<td><a href='?mod=11&id=$data[holiday_date]'>Ubah</a></td>";
      				  echo "<td><a href='del_data.php?id=$data[holiday_date]&pro=MHol'";?> 
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
