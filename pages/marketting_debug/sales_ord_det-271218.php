<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$query = mysql_query("SELECT * FROM mastercompany limit 1");
$data = mysql_fetch_array($query);
$nm_company = $data['company'];
$st_company = $data['status_company'];

$id_item=$_GET['id'];
$unit=flookup("unit","so","id='$id_item'");
# COPAS EDIT

# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
$cek_jo=flookup("count(*)","jo_det","id_so='$id_item'");
echo "<script type='text/javascript'>";
  echo "function validasi()";
  echo "{";
    echo "var cek_jo = $cek_jo;";
    echo "var buyerno = document.form.txtbuyerno.value;";
    echo "var dest = document.form.txtdest.value;";
    echo "var color = document.form.txtcolor.value;";
    echo "var unit = document.form.txtunit.value;";
    echo "var jmlsize = document.form.txtroll.value;";
    ?>
    <?php
    echo "if (cek_jo != '') { alert('Data Tidak Bisa Dirubah Karena SO Sudah Dibuat Worksheet');valid = false;}";
    echo "else if (buyerno == '') { alert('Buyer PO tidak boleh kosong');valid = false;}";
    echo "else if (dest == '') { alert('Destination tidak boleh kosong');valid = false;}";
    echo "else if (color == '') { alert('Color tidak boleh kosong');valid = false;}";
    echo "else if (unit == '') { alert('Unit tidak boleh kosong');valid = false;}";
    echo "else if (jmlsize == '') { alert('Number Of Size tidak boleh kosong');valid = false;}";
    echo "else {valid = true;}";
    echo "return valid;";
    echo "exit;";
  echo "}";
echo "</script>";
# END COPAS VALIDASI
?>
<script type="text/javascript">
  function getListData(cri_item)
  {   var html = $.ajax
      ({  type: "POST",
          url: '../forms/ajax2.php?modeajax=view_list_size',
          data: "cri_item=" +cri_item,
          async: false
      }).responseText;
      if(html)
      {	 
          $("#detail_item").html(html);
      }
  }
</script>
<?PHP
# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='s_sales_ord_det.php?mod=$mod&id=$id_item' onsubmit='return validasi()'>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Buyer PO *</label>";
            echo "<input type='text' class='form-control' name='txtbuyerno'  
            placeholder='Masukkan Buyer PO'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Destination *</label>";
            echo "<input type='text' class='form-control' name='txtdest'  
            placeholder='Masukkan Destination'>";
          echo "</div>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Color *</label>";
            echo "<input type='text' class='form-control' name='txtcolor'  
            placeholder='Masukkan Color'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>SKU</label>";
            echo "<input type='text' class='form-control' name='txtsku'  
            placeholder='Masukkan SKU'>";
          echo "</div>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Notes</label>";
            echo "<input type='text' class='form-control' name='txtnotes'  
            placeholder='Masukkan Notes'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Unit *</label>";
            echo "
              <input type='hidden' name='txtunit' value='$unit'>
              <select disabled class='form-control select2' style='width: 100%;' 
              name='txtunit2'>";
              $sql = "select nama_pilihan isi,nama_pilihan tampil from 
                masterpilihan where kode_pilihan='Satuan'";
              IsiCombo($sql,$unit,'Pilih Unit');
            echo "</select>";
          echo "</div>";
        echo "</div>";
        echo "<div class='col-md-3'>";  
          echo "<div class='form-group'>";
            echo "<label>Number Of Size *</label>";
            echo "<select class='form-control select2' style='width: 100%;' name='txtroll' onchange='getListData(this.value)'>";
              echo "<option value='' disabled selected>Number Of Size</option>";
              for ($x = 1; $x <= 100; $x++) 
              { echo "<option value='$x'>$x</option>"; }
            echo "</select>";
          echo "</div>";
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
# END COPAS ADD
?>