<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("item_odo","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_odo = $_GET['id']; } else {$id_odo = "";}
$titlenya="Master Item ODO";
$mode="";
$mod=$_GET['mod'];

# COPAS EDIT
if ($id_odo=="")
{ $kode = "";
  $item = "";
}
else
{ $query = mysql_query("SELECT * FROM masteritem_odo where id_item_odo='$id_odo'");
  $data = mysql_fetch_array($query);
  $kode = $data['goods_code'];
  $item = $data['itemdesc'];
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { var kode = document.form.txtkode.value;
      var item = document.form.txtitem.value;
      if (kode == '') { alert('kode Tidak Boleh Kosong'); document.form.txtkode.focus();valid = false;}
      else if (item == '') { alert('Deskripsi Tidak Boleh Kosong'); document.form.txtitem.focus();valid = false;}
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
      echo "<form method='post' name='form' action='s_odo.php?mod=$mod&id=$id_odo' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Kode Barang *</label>
            <input type='text' class='form-control' name='txtkode' 
            placeholder='Masukkan Kode Barang' value='$kode'>
          </div>
          <div class='form-group'>
            <label>Deskripsi</label>
            <input type='text' class='form-control' name='txtitem' 
            placeholder='Masukkan Deskripsi' value='$item'>
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
      <h3 class="box-title">Data <?php echo "Item ODO"; ?></h3>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>
		      <th>No</th>
          <th>Kode Barang</th>
          <th>Deskripsi</th>
          <!-- <th>Aksi</th> -->
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		    $query = mysql_query("SELECT * FROM masteritem_odo ORDER BY id_item_odo DESC");
			  $no = 1; 
				while($data = mysql_fetch_array($query))
			  { echo "<tr>";
				  echo "<td>$no</td>"; 
          echo "<td>$data[goods_code]</td>";
          echo "<td>$data[itemdesc]</td>";
          echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
			  ?>
      </tbody>
    </table>
  </div>
</div>