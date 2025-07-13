<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$mode = $_GET['mode'];

# CEK HAK AKSES KEMBALI
if ($mode=="Supplier") 
  { $fld="mnuMasterSupplier"; } 
else if ($mode=="Gudang") 
  { $fld="master_whs"; } 
else 
  { $fld="master_customer"; }
$akses = flookup($fld,"userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI

if (isset($_GET['id'])) {$id_supplier = $_GET['id']; } else {$id_supplier = "";}

if ($mode=="Supplier") 
{ $titlenya="Supplier"; 
  $tipe_sup=$titlenya;
  $filternya="tipe_sup='S'";
}
else if ($mode=="Gudang") 
{ $titlenya="Gudang"; 
  $tipe_sup=$titlenya;
  $filternya="tipe_sup='G'";
}
else if ($mode=="Customer") 
{ $titlenya="Customer"; 
  $tipe_sup=$titlenya;
  $filternya="tipe_sup='C'";
}

# COPAS EDIT
if ($id_supplier=="")
{ $Supplier = "";
  $Attn = "";
  $Phone = "";
  $Fax = "";
  $Email = "";
  $area = "";
  $alamat = "";
  $alamat2 = "";
  $npwp = "";
  $status_kb = "";
  $country = "";
}
else
{ $query = mysql_query("SELECT * FROM mastersupplier where id_supplier='$id_supplier' ORDER BY id_supplier ASC");
  $data = mysql_fetch_array($query);
  $Supplier = $data['Supplier'];
  $Attn = $data['Attn'];
  $Phone = $data['Phone'];
  $Fax = $data['Fax'];
  $Email = $data['Email'];
  $area_ori = $data['area'];
  if ($area_ori=="I") 
  { $area="Import/Export"; }
  else if ($area_ori=="L") 
  { $area="Lokal"; }
  else if ($area_ori=="F") 
  { $area="Factory"; }
  $alamat = $data['alamat'];
  $alamat2 = $data['alamat2'];
  $npwp = $data['npwp'];
  $status_kb = $data['status_kb'];
  $country = $data['country'];
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>";
  echo "function validasi()";
  echo "{";
      echo "var Supplier = document.form.txtSupplier.value;";
      echo "var tipe_sup = document.form.txttipe_sup.value;";
      echo "var area = document.form.txtarea.value;";

      echo "if (Supplier == '') { alert('Supplier tidak boleh kosong'); document.form.txtSupplier.focus();valid = false;}";
      echo "else if (tipe_sup == '') { alert('Tipe tidak boleh kosong'); document.form.txttipe_sup.focus();valid = false;}";
      echo "else if (area == '') { alert('Area tidak boleh kosong'); document.form.txtarea.focus();valid = false;}";
      echo "else valid = true;";
      echo "return valid;";
      echo "exit;";
  echo "}";
echo "</script>";
# END COPAS VALIDASI

# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='save_data.php?mode=$mode&id=$id_supplier' onsubmit='return validasi()'>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>$titlenya *</label>";
            echo "<input type='text' class='form-control' name='txtSupplier' placeholder='$cmas $titlenya' value='$Supplier'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>$c26</label>";
            echo "<input type='text' class='form-control' name='txtAttn' placeholder='$cmas $c26' value='$Attn'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>$c27</label>";
            echo "<input type='text' class='form-control' name='txtPhone' placeholder='$cmas $c27' value='$Phone'>";
          echo "</div>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Fax.</label>";
            echo "<input type='text' class='form-control' name='txtFax' placeholder='$cmas Fax.' value='$Fax'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>E-Mail</label>";
            echo "<input type='text' class='form-control' name='txtEmail' placeholder='$cmas E-Mail' value='$Email'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Area *</label>";
            $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan where 
                  kode_pilihan='Area' order by nama_pilihan";
            echo "<select class='form-control select2' style='width: 100%;' name='txtarea'>";
            IsiCombo($sql,$area,$cpil.' Area');
            echo "</select>";
          echo "</div>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>$c28</label>";
            echo "<input type='text' class='form-control' name='txtalamat' placeholder='$cmas $c28' value='$alamat'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>$c28 2</label>";
            echo "<input type='text' class='form-control' name='txtalamat2' placeholder='$cmas $c28 2' value='$alamat2'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>$c29</label>";
            echo "<input type='text' class='form-control' name='txtnpwp' placeholder='$cmas $c29' value='$npwp'>";
          echo "</div>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>$c30</label>";
            echo "<input type='text' class='form-control' name='txtcountry' placeholder='$cmas $c30' value='$country'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Tipe *</label>";
            echo "<input type='text' class='form-control' name='txttipe_sup' placeholder='Masukkan Tipe' value='$tipe_sup' readonly>";
          echo "</div>";
          echo "<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>";
        echo "</div>";
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
    <table id="example1" class="table table-bordered table-striped">
      <thead>
        <tr>
  		      <th>No</th>
            <th><?PHP echo $titlenya;?></th>
            <th><?php echo $c28; ?></th>
            <th>Area</th>
            <th><?php echo $c29; ?></th>
            <th width="108px"><?php echo "Aksi"; ?></th>
        </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		    $query = mysql_query("SELECT *,if(area='I','Import/Export',
          if(area='L','Lokal',if(area='F','Factory',area))) areanya 
          FROM mastersupplier where $filternya ORDER BY id_supplier desc");
			  $no = 1; 
				while($data = mysql_fetch_array($query))
			  { echo "<tr>";
				  echo "<td>$no</td>"; 
				  echo "<td>$data[Supplier]</td>"; 
				  echo "<td>$data[alamat]</td>"; 
				  echo "<td>$data[areanya]</td>";
          echo "<td>$data[npwp]</td>"; 
				  echo "<td><a class='btn btn-success btn-s' href='index.php?mod=4&mode=$mode&id=$data[Id_Supplier]'>$cub</a>";
				  echo " <a class='btn btn-danger btn-s' href='del_data.php?mod=4&mode=$mode&id=$data[Id_Supplier]'";?> 
            onclick="return confirm('Apakah anda yakin akan menghapus ?')"><?PHP echo "$chap</a></td>";
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