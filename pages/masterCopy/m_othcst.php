<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("m_others","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_otherscode = $_GET['id']; } else {$id_otherscode = "";}
$titlenya="Master Others Cost";
$mode="";
$mod=$_GET['mod'];

# COPAS EDIT
if ($id_otherscode=="")
{ $otherscode = "";
  $othersdesc = "";
}
else
{ $query = mysql_query("SELECT * FROM masterothers where id='$id_otherscode'");
  $data = mysql_fetch_array($query);
  $otherscode = $data['otherscode'];
  $othersdesc = $data['othersdesc'];
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { var otherscode = document.form.txtotherscode.value;
      var othersdesc = document.form.txtothersdesc.value;
      if (otherscode == '') { alert('Others Code Tidak Boleh Kosong'); document.form.txtotherscode.focus();valid = false;}
      else if (othersdesc == '') { alert('Others Desc Tidak Boleh Kosong'); document.form.txtothersdesc.focus();valid = false;}
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
      echo "<form method='post' name='form' action='s_m_othcst.php?mod=$mod&id=$id_otherscode' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Others Code *</label>
            <input type='text' class='form-control' name='txtotherscode' 
            placeholder='Masukkan Others Code' value='$otherscode'>
          </div>
          <div class='form-group'>
            <label>Others Desc</label>
            <input type='text' class='form-control' name='txtothersdesc' 
            placeholder='Masukkan Others Desc' value='$othersdesc'>
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
          <th>Others Code</th>
          <th>Others Desc</th>
          <th>Aksi</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		    $query = mysql_query("SELECT * FROM masterothers ORDER BY id DESC limit 500");
			  $no = 1; 
				while($data = mysql_fetch_array($query))
			  { echo "<tr>";
				  echo "<td>$no</td>"; 
          echo "<td>$data[otherscode]</td>";
          echo "<td>$data[othersdesc]</td>";
          echo "<td><a $cl_ubah href='index.php?mod=$mod&mode=$mode&id=$data[id]' $tt_ubah </a>";
				  echo " <a $cl_hapus href='d_oth.php?mod=$mod&mode=$mode&id=$data[id]' $tt_hapus";?> 
            onclick="return confirm('Apakah anda yakin akan menghapus ?')"><?PHP echo $tt_hapus2."</a>";
          echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
			  ?>
      </tbody>
    </table>
  </div>
</div>