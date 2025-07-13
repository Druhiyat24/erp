<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("m_complex","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_cfcode = $_GET['id']; } else {$id_cfcode = "";}
$titlenya="Master ".$caption[6];
$mode="";
$mod=$_GET['mod'];

# COPAS EDIT
if ($id_cfcode=="")
{ $cfcode = "";
  $cfdesc = "";
}
else
{ $query = mysql_query("SELECT * FROM mastercf where id='$id_cfcode'");
  $data = mysql_fetch_array($query);
  $cfcode = $data['cfcode'];
  $cfdesc = $data['cfdesc'];
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { var cfcode = document.form.txtcfcode.value;
      var cfdesc = document.form.txtcfdesc.value;
      if (cfcode == '') { alert('Complexity Tidak Boleh Kosong'); document.form.txtcfcode.focus();valid = false;}
      else if (cfdesc == '') { alert('Complexity Desc Tidak Boleh Kosong'); document.form.txtcfdesc.focus();valid = false;}
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
      echo "<form method='post' name='form' action='s_cf.php?mod=$mod&id=$id_cfcode' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>$caption[6] Code *</label>
            <input type='text' class='form-control' name='txtcfcode' 
            placeholder='Masukkan $caption[6] Code' value='$cfcode'>
          </div>
          <div class='form-group'>
            <label>$caption[6] Desc</label>
            <input type='text' class='form-control' name='txtcfdesc' 
            placeholder='Masukkan $caption[6] Desc' value='$cfdesc'>
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
          <th><?php echo $caption[6];?> Code</th>
          <th><?php echo $caption[6];?> Desc</th>
          <th>Aksi</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		    $query = mysql_query("SELECT * FROM mastercf ORDER BY id DESC limit 500");
			  $no = 1; 
				while($data = mysql_fetch_array($query))
			  { echo "<tr>";
				  echo "<td>$no</td>"; 
          echo "<td>$data[cfcode]</td>";
          echo "<td>$data[cfdesc]</td>";
          echo "<td><a $cl_ubah href='../master/?mod=$mod&mode=$mode&id=$data[id]' $tt_ubah </a>";
				  echo " <a $cl_hapus href='d_cf.php?mod=$mod&mode=$mode&id=$data[id]' $tt_hapus";?> 
            onclick="return confirm('Apakah anda yakin akan menghapus ?')"><?PHP echo $tt_hapus2."</a>";
          echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
			  ?>
      </tbody>
    </table>
  </div>
</div>