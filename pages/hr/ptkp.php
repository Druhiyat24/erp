<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$titlenya="Master PTKP";
if (isset($_GET['id'])) { $id_item=$_GET['id']; } else { $id_item=""; }
$mod=$_GET['mod'];
# COPAS EDIT
if ($id_item=="")
{ $ptkpcode = "";
  $ptkpdesc = "";
  $ptkp = "";
}
else
{ $query = mysql_query("SELECT * FROM hr_masterptkp where 
    ptkpcode='$id_item'");
  $data = mysql_fetch_array($query);
  $ptkpcode = $data['ptkpcode'];
  $ptkpdesc = $data['ptkpdesc'];
  $ptkp = $data['ptkp'];
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>";
  echo "function validasi()";
  echo "{";
    echo "var ptkpcode = document.form.txtptkpcode.value;";
    echo "var ptkpdesc = document.form.txtptkpdesc.value;";
    echo "var ptkp = document.form.txtptkp.value;";

    echo "if (ptkpcode == '') { alert('Kode PTKP tidak boleh kosong'); document.form.txtptkpcode.focus();valid = false;}";
    echo "else if (ptkpdesc == '') { alert('Deskripsi tidak boleh kosong'); document.form.txtptkpdesc.focus();valid = false;}";
    echo "else if (ptkp == '') { alert('Nilai PTKP tidak boleh kosong'); document.form.txtptkp.focus();valid = false;}";
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
  echo "<form method='post' name='form' action='save_ptkp.php?mod=$mod&mode=$mode&id=$id_item' onsubmit='return validasi()'>";
  echo "<div class='col-md-3'>";
  echo "<div class='form-group'>";
    echo "<label>Kode PTKP *</label>";
    echo "<input type='text' class='form-control' name='txtptkpcode' placeholder='Masukkan Kode PTKP' value='$ptkpcode'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Deskripsi *</label>";
    echo "<input type='text' class='form-control' name='txtptkpdesc' placeholder='Masukkan Deskripsi' value='$ptkpdesc'>";
  echo "</div>";
  echo "<div class='form-group'>";
    echo "<label>Nilai PTKP *</label>";
    echo "<input type='text' class='form-control' name='txtptkp' placeholder='Masukkan Nilai PTKP' value='$ptkp'>";
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
              <th>Kode PTKP</th>
              <th>Deskripsi</th>
              <th>Nilai PTKP</th>
              <th>Ubah</th>
              <th>Hapus</th>
          </tr>
          </thead>
          <tbody>
            <?php
            # QUERY TABLE
            $query = mysql_query("SELECT * FROM hr_masterptkp");
  				  $no = 1; 
    				while($data = mysql_fetch_array($query))
    			  { echo "<tr>";
      				  echo "<td>$no</td>"; 
      				  echo "<td>$data[ptkpcode]</td>"; 
      				  echo "<td>$data[ptkpdesc]</td>"; 
      				  echo "<td>".fn($data['ptkp'],2)."</td>";
                echo "<td><a href='?mod=6&id=$data[ptkpcode]'>Ubah</a></td>";
      				  echo "<td><a href='del_data.php?id=$data[ptkpcode]&pro=MPTKP'";?> 
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
  