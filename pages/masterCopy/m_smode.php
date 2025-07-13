<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("m_shipmode","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_shipmode = $_GET['id']; } else {$id_shipmode = "";}
$titlenya="Master Ship Mode";
$mode="";
$mod=$_GET['mod'];

# COPAS EDIT
if ($id_shipmode=="")
{ $shipmode = "";
  $shipdesc = "";
}
else
{ $query = mysql_query("SELECT * FROM mastershipmode where id='$id_shipmode'");
  $data = mysql_fetch_array($query);
  $shipmode = $data['shipmode'];
  $shipdesc = $data['shipdesc'];
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { var shipmode = document.form.txtshipmode.value;
      var shipdesc = document.form.txtshipdesc.value;
      if (shipmode == '') { alert('Ship Mode Tidak Boleh Kosong'); document.form.txtshipmode.focus();valid = false;}
      else if (shipdesc == '') { alert('Ship Desc Tidak Boleh Kosong'); document.form.txtshipdesc.focus();valid = false;}
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
      echo "<form method='post' name='form' action='s_smode.php?mod=$mod&id=$id_shipmode' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Ship Mode *</label>
            <input type='text' class='form-control' name='txtshipmode' 
            placeholder='Masukkan Ship Mode' value='$shipmode'>
          </div>
          <div class='form-group'>
            <label>Ship Desc</label>
            <input type='text' class='form-control' name='txtshipdesc' 
            placeholder='Masukkan Ship Desc' value='$shipdesc'>
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
          <th>Ship modE</th>
          <th>Ship desC</th>
          <th>Aksi</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		    $query = mysql_query("SELECT * FROM mastershipmode ORDER BY id DESC limit 500");
			  $no = 1; 
				while($data = mysql_fetch_array($query))
			  { echo "<tr>";
				  echo "<td>$no</td>"; 
          echo "<td>$data[shipmode]</td>";
          echo "<td>$data[shipdesc]</td>";
          echo "<td><a $cl_ubah href='index.php?mod=$mod&mode=$mode&id=$data[id]' $tt_ubah </a>";
				  echo " <a $cl_hapus href='d_smode.php?mod=$mod&mode=$mode&id=$data[id]' $tt_hapus";?> 
            onclick="return confirm('Apakah anda yakin akan menghapus ?')"><?PHP echo $tt_hapus2."</a>";
          echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
			  ?>
      </tbody>
    </table>
  </div>
</div>