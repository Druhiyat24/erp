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
{ $cprodgr="Product Group"; 
  $cprodit="Product Item";
  $cinput="hidden";
}
if (isset($_GET['id'])) {$id_prod_gr = $_GET['id']; } else {$id_prod_gr = "";}
$titlenya="Master $cprodgr";
$mode="";
$mod=$_GET['mod'];

# COPAS EDIT
if ($id_prod_gr=="")
{ $product_group = "";
  $product_type = "";
  $model="";
  $berat="";
  $berat_kotor="";
}
else
{ $query = mysql_query("SELECT * FROM masterproduct where id='$id_prod_gr'");
  $data = mysql_fetch_array($query);
  $product_group = $data['product_group'];
  $product_type = $data['product_item'];
  $model = $data['model'];
  $berat = $data['berat'];
  $berat_kotor = $data['berat_kotor'];
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
      echo "<form method='post' name='form' action='s_product.php?mod=$mod&id=$id_prod_gr' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>$cprodgr *</label>
            <input type='text' class='form-control' name='txtprod_group' 
            placeholder='Masukkan $cprodgr' value='$product_group'>
          </div>
          <div class='form-group'>
            <label>$cprodit</label>
            <input type='text' class='form-control' name='txtprod_item' 
            placeholder='Masukkan $cprodit' value='$product_type'>
          </div>
          <button type='submit' name='submit' class='btn btn-primary'>
          Simpan</button>
        </div>";
        if($jenis_company=="VENDOR LG") 
        { echo "
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Model *</label>
              <input type='$cinput' class='form-control' name='txtmodel' 
              placeholder='Masukkan Model' value='$model'>
            </div>
            <div class='col-md-6'>
              <div class='form-group'>
                <label>Berat (KG)</label>
                <input type='$cinput' class='form-control' name='txtberat' 
                placeholder='Masukkan Berat' value='$berat'>
              </div>
            </div>
            <div class='col-md-6'>
              <div class='form-group'>
                <label>Berat Kotor (KG)</label>
                <input type='$cinput' class='form-control' name='txtberat_kotor' 
                placeholder='Masukkan Berat Kotor' value='$berat_kotor'>
              </div>
            </div>
          </div>";
        }
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
    <table id="mproduct" class="display responsive" style="width:100%">
      <thead>
      <tr>
		      <!-- <th>No</th> -->
          <th><?php echo $cprodgr; ?></th>
          <th><?php echo $cprodit; ?></th>
          <?php if ($jenis_company=="VENDOR LG") {?>
            <th>Model</th>
            <th>Berat (KG)</th>
            <th>Berat Kotor (KG)</th>
          <?php } ?>
          <th>Aksi</th>
      </tr>
      </thead>
      <tbody>
       
      </tbody>
    </table>
  </div>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> 
<script src="js/Masterproduct.js"></script>