<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("m_product","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");
if ($jenis_company=="VENDOR LG") 
{ $cprodgr="Part #"; 
  $cprodit="Part Name";
  $cinput="text";
} 
else 
{
 
 
}
if (isset($_GET['id'])) {$id = $_GET['id']; } else {$id = "";}
$mode="";
$mod=$_GET['mod'];

# COPAS EDIT
if ($id=="")
{ 
  $code_panel = "";
  $nama_panel = "";
  $description="";
}
else
{ $query = mysql_query("SELECT * FROM masterpanel where id='$id'");
  $data = mysql_fetch_array($query);

  $code_panel = $data['v_codepanel'];
  $nama_panel = $data['nama_panel'];
  $description= $data['description'];
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { var product_group = document.form.txtprod_group.value;
      var product_type = document.form.txtprod_item.value;";
      if($jenis_company=="VENDOR LG")
      { echo "
        var model = document.form.txtmodel.value;
        var berat = document.form.txtberat.value;
        var beratk = document.form.txtberat_kotor.value;";
      }
      echo "
      if (product_group == '') { alert('$cprodgr Tidak Boleh Kosong'); document.form.txtprod_group.focus();valid = false;}
      else if (product_type == '') { alert('$cprodit Tidak Boleh Kosong'); document.form.txtprod_item.focus();valid = false;}";
      if($jenis_company=="VENDOR LG")
      { echo "
        else if (model == '') { alert('Model Tidak Boleh Kosong'); document.form.txtmodel.focus();valid = false;}
        else if (berat == '') { alert('Berat Tidak Boleh Kosong'); document.form.txtberat.focus();valid = false;}
        else if (beratk == '') { alert('Berat Kotor Tidak Boleh Kosong'); document.form.txtberat_kotor.focus();valid = false;}
        else if (isNaN(berat)) { alert('Berat Harus Angka'); document.form.txtberat.focus();valid = false;}";
      }
      echo "
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
      echo "<form method='post' name='form' action='s_panel.php?mod=$mod&id=$id' >";
        echo "
        <div class='col-md-3'>
			<div class='form-group'>
            <label>Code Panel *</label>
            <input type='text' class='form-control' name='code_panel' 
            placeholder='Masukkan Code Panel' value='$code_panel'>
          </div>
          <div class='form-group'>
            <label>Nama Panel *</label>
            <input type='text' class='form-control' name='nama_panel' 
            placeholder='Masukkan Nama Panel' value='$nama_panel'>
          </div>
          <div class='form-group'>
            <label>Description</label>
            <textarea type='text' class='form-control' name='description' 
            placeholder='Masukkan Description' value='$description'>$description	</textarea>
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
      <h3 class="box-title">Data Master Panel</h3>
  </div>
  <div class="box-body">
    <table id="masterpanel" class="display responsive" style="width:100%">
      <thead>
      <tr>
				<!-- <th>No</th> -->
				<th># ID</th>
				<th>Nama Panel</th>
				<th>Description</th>
				<th>Aksi</th>
      </tr>
      </thead>
      <tbody>
        
      </tbody>
    </table>
  </div>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> 
<script src="js/Masterpanel.js"></script>