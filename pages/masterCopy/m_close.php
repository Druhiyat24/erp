<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("generate_kode","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_closing = $_GET['id']; } else {$id_closing = "";}
$titlenya="Closing Periode";
$mode="";
$mod=$_GET['mod'];

# COPAS EDIT
if ($id_closing=="")
{ $closing = "";
  $closing_desc = "";
}
else
{ $query = mysql_query("SELECT * FROM tbl_closing where id='$id_closing'");
  $data = mysql_fetch_array($query);
  $closing = $data['kode_closing'];
  $closing_desc = $data['nama_closing'];
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { var closing = document.form.txtclosing.value;
      var closing_desc = document.form.txtclosing_desc.value;
      if (closing == '') { alert('Closing Tidak Boleh Kosong'); document.form.txtclosing.focus();valid = false;}
      else if (closing_desc == '') { alert('Deskripsi Tidak Boleh Kosong'); document.form.txtclosing_desc.focus();valid = false;}
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
      echo "<form method='post' name='form' action='s_close.php?mod=$mod&id=$id_closing' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Closing *</label>
            <input type='text' class='form-control' id='monthpicker' name='txtclosing' 
            placeholder='Masukkan Closing' value='$closing'>
          </div>
          <div class='form-group'>
            <label>Keterangan Closing</label>
            <input type='text' class='form-control' name='txtclosing_desc' 
            placeholder='Masukkan Keterangan Closing' value='$closing_desc'>
          </div>
          <button type='submit' name='submit' class='btn btn-primary'>
          Closing</button>
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
          <th>Closing Periode</th>
          <th>Closing Ket</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		    $query = mysql_query("SELECT * FROM tbl_closing ORDER BY id DESC");
			  $no = 1; 
				while($data = mysql_fetch_array($query))
			  { echo "<tr>";
				  echo "<td>$no</td>"; 
          echo "<td>$data[closing_periode]</td>";
          echo "<td>$data[closing_ket]</td>";
          echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
			  ?>
      </tbody>
    </table>
  </div>
</div>