<?php 
include '../../include/conn.php';
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$mode="";
$titlenya="Karyawan Keluar";
if (isset($_GET['id'])) { $id_item=$_GET['id']; } else { $id_item=""; }

# COPAS EDIT
if ($id_item=="")
{ $nik = "";
  $nama = "";
  $emptermdate = "";
  $keterangan = "";
}
else
{ $query = mysql_query("SELECT * FROM hr_masteremployee_out where id_item='$id_item' ORDER BY id_item ASC");
  $data = mysql_fetch_array($query);
  $nik = $data['nik'];
  $nama = $data['nama'];
  $emptermdate = $data['emptermdate'];
  $keterangan = $data['keterangan'];
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>";
  echo "function validasi()";
  echo "{";
    echo "var nik = document.form.txtnik.value;";
    echo "var nama = document.form.txtnama.value;";
    echo "var emptermdate = document.form.txtemptermdate.value;";
    echo "var keterangan = document.form.txtketerangan.value;";

    echo "if (nik == '') { alert('NIK tidak boleh kosong'); document.form.txtnik.focus();valid = false;}";
    echo "else if (nama == '') { alert('Tanggal tidak boleh kosong'); document.form.txtnama.focus();valid = false;}";
    echo "else if (emptermdate == '') { alert('Jam Masuk tidak boleh kosong'); document.form.txtemptermdate.focus();valid = false;}";
    echo "else if (keterangan == '') { alert('Jam Pulang tidak boleh kosong'); document.form.txtketerangan.focus();valid = false;}";
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
  echo "<form method='post' name='form' action='save_emp_out.php?mode=$mode&id=$id_item' onsubmit='return validasi()'>";
  echo "<div class='col-md-3'>";
  echo "<div class='form-group'>";
    echo "<label>NIK *</label>";
    $sql = "select nik isi,concat(nik,'|',nama) tampil from hr_masteremployee 
      where selesai_kerja is null or selesai_kerja='0000-00-00'";
    echo "<select class='form-control select2' style='width: 100%;' name='txtnik'>";
    IsiCombo($sql,$nik,'Pilih NIK');
    echo "</select>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Tanggal *</label>";
    echo "<input type='text' class='form-control' id='datepicker1' name='txtemptermdate' placeholder='Masukkan Tanggal' value='$emptermdate'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Keterangan *</label>";
    echo "<input type='text' class='form-control' name='txtketerangan' placeholder='Masukkan Keterangan' value='$keterangan'>";
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
              <th>Jenis Kelamin</th>
              <th>Tgl. Lahir</th>
              <th>Based</th>
              <th>Lokasi Kerja</th>
              <th>Tgl. Masuk</th>
              <th>Action</th>
          </tr>
          </thead>
          <tbody>
            <?php
            # QUERY TABLE
            $query = mysql_query("SELECT * FROM hr_masteremployee where selesai_kerja!='0000-00-00'");
            $no = 1; 
            while($data = mysql_fetch_array($query))
            { echo "<tr>";
                echo "<td>$no</td>"; 
                echo "<td>$data[nik]</td>"; 
                echo "<td>$data[nama]</td>"; 
                echo "<td>$data[jenis_kelamin]</td>";
                echo "<td>$data[tgl_lahir]</td>"; 
                echo "<td>$data[divisi_kerja]</td>"; 
                echo "<td>$data[serikat]</td>";
                echo "<td>$data[mulai_kerja]</td>"; 
                echo "<td><a class='btn btn-warning btn-s' href='?mod=2&id=$data[nik]&pro=Copy'
                      data-toggle='tooltip' title='Renew'><i class='fa fa-copy'></i></a></td>";
              echo "</tr>";
              $no++; // menambah nilai nomor urut
            }
            ?>
          </tbody>
          <tfoot>
          <tr>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
          </tr>
          </tfoot>
      </table>
      </div>
      </div>
      </div>
      </div>
      </div>
  </section>
  </div> </div>