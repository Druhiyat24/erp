<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$akses = flookup("m_general_req","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI

$st_company = flookup("status_company","mastercompany","company!=''");
if (isset($_GET['id'])) {$id_item=$_GET['id'];} else {$id_item="";}
# COPAS EDIT
if ($id_item=="")
{ $mattype = "N";
  $goods_code = "";
  $itemdesc = "";
  $jenis_item="";
  $jenis_mut="";
  $color = "";
  $size = "";
}
else
{ $query = mysql_query("SELECT * FROM masteritem where id_item='$id_item' ");
  $data = mysql_fetch_array($query);
  $mattype = $data['mattype'];
  $goods_code = $data['goods_code'];
  $itemdesc = $data['itemdesc'];
  $jenis_item = $data['tipe_item'];
  $jenis_mut = $data['tipe_mut'];
  $color = $data['color'];
  $size = $data['size'];
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>
  function validasi()
  { var mattype = document.form.txtmattype.value;
    var goods_code = document.form.txtgoods_code.value;
    var itemdesc = document.form.txtitemdesc.value;
    var color = document.form.txtcolor.value;
    var size = document.form.txtsize.value;
    if (mattype == '') { document.form.txtmattype.focus(); swal({ title: 'Mat Type Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (goods_code == '') { document.form.txtgoods_code.focus(); swal({ title: 'Item Code Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (itemdesc == '') { document.form.txtitemdesc.focus(); swal({ title: 'Description Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (color == '') { document.form.txtcolor.focus(); swal({ title: 'Color Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (size == '') { document.form.txtsize.focus(); swal({ title: 'Size Tidak Boleh Kosong', $img_alert }); valid = false;}
    else valid = true;
    return valid;
    exit;
  }
</script>";
# END COPAS VALIDASI
# COPAS ADD
if ($mod=="2") { ?>
<div class='box'>
  <div class='box-body'>
    <div class='row'>
      <form method='post' name='form' enctype='multipart/form-data' action='s_master.php?mod=<?php echo $mod; ?>&id=<?php echo $id_item; ?>' onsubmit='return validasi()'>
        <div class='col-md-3'>              
          <div class='form-group'>
            <input type='hidden' class='form-control' name='txtmattype' placeholder='Masukkan Mat Type' value='<?php echo $mattype;?>' >
            <label>Item Code *</label>
            <input type='text' class='form-control' name='txtgoods_code' placeholder='Masukkan Item Code' value='<?php echo $goods_code;?>' >
          </div>        
          <div class='form-group'>
            <label>Description *</label>
            <input type='text' class='form-control' name='txtitemdesc' placeholder='Masukkan Description' value='<?php echo $itemdesc;?>' >
          </div>
          <div class='form-group'>
            <label>Item Type</label>
            <select class='form-control select2' style='width: 100%;' name='txtjenisitem'>
              <?php 
                $sql = "select nama_pilihan isi,nama_pilihan tampil 
                  from masterpilihan where kode_pilihan='J_Item'";
                IsiCombo($sql,$jenis_item,'Pilih Item Type');
              ?>
            </select>
          </div>
        </div>
        <div class='col-md-3'>
          <div class='row'>
            <div class='col-md-6'>        
              <div class='form-group'>
                <label>Color *</label>
                <input type='text' class='form-control' name='txtcolor' placeholder='Masukkan Color' value='<?php echo $color;?>' >
              </div>
            </div>
            <div class='col-md-6'>        
              <div class='form-group'>
                <label>Size *</label>
                <input type='text' class='form-control' name='txtsize' placeholder='Masukkan Size' value='<?php echo $size;?>' >
              </div>
            </div>
          </div>
          <div class='form-group'>
            <label>Mutation Type</label>
            <select class='form-control select2' style='width: 100%;' name='txtjenismut'>
              <?php 
                $sqlsel=" and nama_pilihan='Mesin'";
                $sql = "select nama_pilihan isi,if(nama_pilihan='Mesin','Barang Modal',nama_pilihan) tampil 
                  from masterpilihan where kode_pilihan='Type Mat' $sqlsel ";
                IsiCombo($sql,$jenis_mut,'Pilih Mutation Type');
              ?>
            </select>
          </div>
          <div class='form-group'>
            <label for='exampleInputFile'>Image File</label>
            <input type='file' name='txtfile' accept='.jpg'>
          </div>
          <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div><?php 
# END COPAS ADD
} else if ($id_item=="") { ?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">List Master Non Production</h3>
    <a href='../others/?mod=2' class='btn btn-primary btn-s'>
      <i class='fa fa-plus'></i> New
    </a>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>
        <th>Item Code</th>
        <th>Description</th>
        <th>Color</th>
        <th>Size</th>
        <th>Item Type</th>
        <th></th>
        <th></th>
        <th></th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $sql="select * from masteritem where mattype='N' order by id_item desc";
        $result=mysql_query($sql);
        while($data = mysql_fetch_array($result))
        { echo "<tr>";
            echo "<td>$data[goods_code]</td>";
            echo "<td>$data[itemdesc]</td>";
            echo "<td>$data[color]</td>";
            echo "<td>$data[size]</td>";
            echo "<td>$data[tipe_item]</td>";
            echo "
            <td><a href='../others/?mod=2&id=$data[id_item]'
              data-toggle='tooltip' title='Update'>
              <i class='fa fa-pencil'></i></a>
            </td>";
            echo "
            <td>
              <a href='d_master.php?mode=$mode&id=$data[id_item]'
                $tt_hapus";?> 
                onclick="return confirm('Apakah anda yakin akan menghapus ?')">
                <?php echo $tt_hapus2."</a>
            </td>";
            echo "
            <td><a href='#' class='img-prev' data-id=$data[id_item]
                data-toggle='tooltip' title='Attachment'><i class='fa fa-paperclip'>
                </i></a>
            </td>";
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>  
  </div>
</div>
<?php } ?>