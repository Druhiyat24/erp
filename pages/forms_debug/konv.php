<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$query = mysql_query("SELECT * FROM mastercompany limit 1");
$data = mysql_fetch_array($query);
$nm_company = $data['company'];
$st_company = $data['status_company'];

if (isset($_GET['id'])) {$id_item=$_GET['id'];} else {$id_item="";}
$rono="";
$rodate=date('d M Y');

# COPAS EDIT
if ($id_item=="")
{ $txtqtyawal=0;
  $txtqtykonv=0;
  $satuan_konv="";
  $satuan_awal="";
  $id_bb="";
}
else
{ $rs=mysql_fetch_array(mysql_query("select * from konversi_satuan
    where id='$id_item'"));
  $txtqtyawal=$rs['qty1'];
  $txtqtykonv=$rs['qty2'];;
  $satuan_awal=$rs['unit1'];
  $satuan_konv=$rs['unit2'];
  $id_bb=$rs['id_item'];
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>";
  echo "function validasi()
    { var item = document.form.txtitem.value;
      var qtyawal = document.form.txtqtyawal.value;
      var satawal = document.form.txtsatuan_awal.value;
      var qtykonv = document.form.txtqtykonv.value;
      var satkonv = document.form.txtsatuan_konv.value;
      if (item == '') 
      { valid = false; 
        swal({ title: 'Item Tidak Boleh Kosong', $img_alert });
      }
      else if (qtyawal == '') 
      { valid = false; 
        swal({ title: 'Qty Awal Tidak Boleh Kosong', $img_alert });
      }
      else if (satawal == '') 
      { valid = false; 
        swal({ title: 'Satuan Awal Tidak Boleh Kosong', $img_alert });
      }
      else if (qtykonv == '') 
      { valid = false; 
        swal({ title: 'Qty Konversi Tidak Boleh Kosong', $img_alert });
      }
      else if (satkonv == '') 
      { valid = false; 
        swal({ title: 'Satuan Konversi Tidak Boleh Kosong', $img_alert });
      }
      else valid = true;
      return valid;
      exit;
    }";
echo "</script>";
# END COPAS VALIDASI
# COPAS ADD
if ($mod=="22") {
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='save_data_konv.php?mod=$mod&mode=$mode&id=$id_item' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Bahan Baku *</label>
            <select class='form-control select2' style='width: 100%;' name='txtitem'>";
            $sql="select a.id_item isi,concat(a.goods_code,'|',a.itemdesc) 
              tampil from masteritem a where mattype not in ('S','M','L','C')";
            IsiCombo($sql,$id_bb,'Pilih Bahan Baku');
            echo "
            </select>
          </div>
          <div class='col-md-6'>
            <div class='form-group'>
              <label>Qty Awal *</label>
              <input type='text' class='form-control' name='txtqtyawal' value='$txtqtyawal' placeholder='$cmas Qty Awal'>
            </div>
          </div>
          <div class='col-md-6'>
            <div class='form-group'>
              <label>Satuan Awal *</label>
              <select class='form-control select2' style='width: 100%;' name='txtsatuan_awal'>";
              $sql = "select nama_pilihan isi,nama_pilihan tampil 
                from masterpilihan where kode_pilihan='Satuan' order by nama_pilihan";
              IsiCombo($sql,$satuan_awal,'Pilih Satuan');
              echo "
              </select>
            </div>
          </div>
          <div class='col-md-6'>
            <div class='form-group'>
              <label>Qty Konversi *</label>
              <input type='text' class='form-control' name='txtqtykonv' value='$txtqtykonv' placeholder='$cmas Qty Konversi'>
            </div>
          </div>
          <div class='col-md-6'>
            <div class='form-group'>
              <label>Satuan Konversi *</label>
              <select class='form-control select2' style='width: 100%;' name='txtsatuan_konv'>";
              $sql = "select nama_pilihan isi,nama_pilihan tampil 
                from masterpilihan where kode_pilihan='Satuan' order by nama_pilihan";
              IsiCombo($sql,$satuan_konv,'Pilih Satuan');
              echo "
              </select>
            </div>
          </div>
        </div>";
        echo "<div class='col-md-3'>";
          echo "
          ";
          echo "
          ";    
        echo "</div>";
        echo "<div class='box-body'>";
         echo "<div id='detail_item'></div>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>";
        echo "</div>";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
} else {
# END COPAS ADD
?>
<div class="box">
  <div class="box-header">
      <h3 class="box-title">Konversi Satuan</h3>
      <a href='../forms/?mod=22' class='btn btn-primary btn-s'>
        <i class='fa fa-plus'></i> New
      </a>
  </div>
  <div class="box-body">
    <table id="examplefix3" class="display responsive" style="width:100%">
      <thead>
        <tr>
          <th>Kode Bahan Baku</th>
          <th>Nama Bahan Baku</th>
          <th>Qty 1</th>
          <th>Unit 1</th>
          <th>Qty 2</th>
          <th>Unit 2</th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $query = mysql_query("SELECT a.id,s.goods_code,s.itemdesc,a.qty1,a.unit1
          ,a.qty2,a.unit2 FROM konversi_satuan a inner join 
          masteritem s on a.id_item=s.id_item ORDER BY a.id_item ASC");
        while($data = mysql_fetch_array($query))
        { echo "
          <tr>
            <td>$data[goods_code]</td>
            <td>$data[itemdesc]</td>
            <td>$data[qty1]</td>
            <td>$data[unit1]</td>
            <td>$data[qty2]</td>
            <td>$data[unit2]</td>
            <td><a $cl_ubah href='../forms/?mod=22&mode=$mode&id=$data[id]' $tt_ubah</a></td>
            <td><a $cl_hapus href='del_data.php?mod=$mod&mode=$mode&id=$data[id]' $tt_hapus";?> 
              onclick="return confirm('Apakah anda yakin akan menghapus ?')"><?php echo $tt_hapus2."</a>
            </td>
          </tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<?php } ?>