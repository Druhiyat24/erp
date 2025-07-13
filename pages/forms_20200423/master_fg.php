<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("mnuMStyle","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}
$titlenya="Barang Jadi";
$mode="FG";

# COPAS EDIT
if ($id_item=="")
{ $Styleno = "";
  $Buyerno = "";
  $goods_code = "";
  $itemname = "";
  $Color = "";
  $Size = "";
  $barcode = "";
  $noorder = "";
  $deldate = date('d M Y');
  $dest = "";
  $whs_code = "";
  $qty = "";
  $satuan = "";
}
else
{ $query = mysql_query("SELECT * FROM masterstyle where id_item='$id_item' ORDER BY id_item ASC");
  $data = mysql_fetch_array($query);
  $Styleno = $data['Styleno'];
  $Buyerno = $data['Buyerno'];
  $goods_code = $data['goods_code'];
  $itemname = $data['itemname'];
  $Color = $data['Color'];
  $Size = $data['Size'];
  $barcode = $data['barcode'];
  $noorder = $data['KPNo'];
  $deldate = date('d M Y',strtotime($data['DelDate']));
  $dest = $data['country'];
  $whs_code = $data['whs_code'];
  $qty = $data['Qty'];
  $satuan = $data['unit'];
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>";
  echo "function validasi()";
  echo "{";
      echo "var Styleno = document.form.txtStyleno.value;";
      echo "var goods_code = document.form.txtgoods_code.value;";
      echo "var itemname = document.form.txtitemname.value;";
      echo "var Color = document.form.txtColor.value;";
      echo "var Size = document.form.txtSize.value;";

      echo "if (Styleno == '') { alert('Style / Model tidak boleh kosong'); document.form.txtStyleno.focus();valid = false;}";
      echo "else if (goods_code == '') { alert('Kode Barang tidak boleh kosong'); document.form.txtgoods_code.focus();valid = false;}";
      echo "else if (itemname == '') { alert('Deskripsi tidak boleh kosong'); document.form.txtitemname.focus();valid = false;}";
      echo "else if (Color == '') { alert('Warna tidak boleh kosong'); document.form.txtColor.focus();valid = false;}";
      echo "else if (Size == '') { alert('Ukuran tidak boleh kosong'); document.form.txtSize.focus();valid = false;}";
      echo "else valid = true;";
      echo "return valid;";
      echo "exit;";
  echo "}";
echo "</script>";
# END COPAS VALIDASI
if ($mod=="3") {
# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='save_data.php?mode=$mode&id=$id_item' onsubmit='return validasi()'>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Style / Model *</label>";
            echo "<input type='text' class='form-control' name='txtStyleno' placeholder='Masukkan Style / Model' value='$Styleno'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>PO Buyer</label>";
            echo "<input type='text' class='form-control' name='txtBuyerno' placeholder='Masukkan PO Buyer' value='$Buyerno'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>$c15fg *</label>";
            echo "<input type='text' class='form-control' name='txtgoods_code' placeholder='$cmas $c15fg' value='$goods_code'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>$c16fg *</label>";
            echo "<input type='text' class='form-control' name='txtitemname' placeholder='$cmas $c16fg' value='$itemname'>";
          echo "</div>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Warna *</label>";
            echo "<input type='text' class='form-control' name='txtColor' placeholder='Masukkan Warna' value='$Color'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Ukuran *</label>";
            echo "<input type='text' class='form-control' name='txtSize' placeholder='Masukkan Ukuran' value='$Size'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Barcode</label>";
            echo "<input type='text' class='form-control' name='txtbarcode' placeholder='Masukkan Barcode' value='$barcode'>";
          echo "</div>";
          echo "<div class='form-group'>";
            if ($nm_company=="PT. Sandrafine Garment")
            { $capt_pk="PK #"; }
            else
            { $capt_pk="No. Order"; }
            echo "<label>$capt_pk</label>";
            echo "<input type='text' class='form-control' name='txtkpno' placeholder='Masukkan $capt_pk' value='$noorder'>";
          echo "</div>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Plan Ship Date</label>";
            echo "<input type='text' id='datepicker1' class='form-control' name='txtdeldate' placeholder='Masukkan Plan Ship Date' value='$deldate'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Destination</label>";
            echo "<input type='text' class='form-control' name='txtdest' placeholder='Masukkan Destination' value='$dest'>";
          echo "</div>";
          if($nm_company=="PT. Sandrafine Garment")
          { echo "<div class='form-group'>";
              echo "<label>Whs Code</label>";
              echo "<input type='text' class='form-control' name='txtwhs_code' placeholder='Masukkan Whs Code' value='$whs_code'>";
            echo "</div>";
          }
          echo "<div class='form-group'>";
            echo "<label>Qty Order</label>";
            echo "<input type='text' class='form-control' name='txtqty' placeholder='Masukkan Qty Order' value='$qty'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Satuan</label>";
            echo "<select id='cbosat' class='form-control select2' style='width: 100%;' name='txtsatuan'>";
            $sql = "select nama_pilihan isi,nama_pilihan tampil 
                from masterpilihan where kode_pilihan='Satuan' order by nama_pilihan";
            IsiCombo($sql,$satuan,'Pilih Satuan');
            echo "</select>";
          echo "</div>";
          echo "<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>";
        echo "</div>";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
} else {
?>
<div class="box">
  <div class="box-header">
      <h3 class="box-title">Data <?PHP echo $titlenya; ?></h3>
      <a href='../forms/?mod=3&mode=<?php echo $mode; ?>' class='btn btn-primary btn-s'>
        <i class='fa fa-plus'></i> New
      </a>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%;font-size:10px;">
      <thead>
      <tr>
		      <th width="5%">No</th>
          <?php if($nm_company=="PT. Sandrafine Garment") {?>
            <th width="5%">No. PK</th>
          <?php } else { ?>
            <th width="5%">No. Order</th>
          <?php } ?>
          <th width="5%">Model</th>
          <th width="5%">PO #</th>
          <th width="5%">Kode <?PHP echo $titlenya;?></th>
          <th width="5%">Deskripsi</th>
          <th width="5%">Warna</th>
          <th width="5%">Ukuran</th>
          <th width="5%">Qty</th>
          <th width="5%">Dest</th>
          <?php if($nm_company=="PT. Sandrafine Garment") { ?>
            <th width="5%">Whs Code</th>
          <?php } ?>
          <th>Kode Lama</th>
          <th>Desc Lama</th>
          <th width="8%">Aksi</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		    $query = mysql_query("SELECT a.*,s.goods_code kode_lama,s.itemdesc desc_lama 
          FROM masterstyle a left join masteritem_odo s on a.id_item_odo=s.id_item_odo ORDER BY a.id_item DESC");
			  $no = 1; 
				while($data = mysql_fetch_array($query))
			  { echo "<tr>";
				  echo "<td>$no</td>"; 
          echo "<td>$data[KPNo]</td>";
          echo "<td>$data[Styleno]</td>";
          echo "<td>$data[Buyerno]</td>";
				  echo "<td>$data[goods_code]</td>"; 
				  echo "<td>$data[itemname]</td>"; 
				  echo "<td>$data[Color]</td>";
          echo "<td>$data[Size]</td>";
          echo "<td>$data[Qty]</td>";
          echo "<td>$data[country]</td>"; 
				  if($nm_company=="PT. Sandrafine Garment") 
          { echo "<td>$data[whs_code]</td>"; } 
          echo "<td>$data[kode_lama]</td>";
          echo "<td>$data[desc_lama]</td>";
          echo "<td><a $cl_ubah href='index.php?mod=3&mode=$mode&id=$data[id_item]' $tt_ubah </a>";
				  echo " <a $cl_hapus href='del_data.php?mod=3&mode=$mode&id=$data[id_item]' $tt_hapus";?> 
            onclick="return confirm('Apakah anda yakin akan menghapus ?')"><?PHP echo $tt_hapus2."</a>";
          echo " <a $cl_hist href='index.php?mod=14&mode=$mode&id=$data[id_item]' $tt_hist</a></td>";
          echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
			  ?>
      </tbody>
      
    </table>
  </div>
</div>
<?php } ?>