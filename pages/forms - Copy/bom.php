<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$query = mysql_query("SELECT * FROM mastercompany limit 1");
$data = mysql_fetch_array($query);
$nm_company = $data['company'];
$st_company = $data['status_company'];

$id_item="";

# COPAS EDIT

# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>";
  echo "function validasi()";
  echo "{";
    echo "var kpno = document.form.cbokpno.value;";
    echo "var item = document.form.cboitem.value;";
    echo "var cons = document.form.txtcons.value;";
    echo "var allow = document.form.txtallow.value;";
    echo "var satuan = document.form.txtsatuan.value;";
    ?>
    <?php
    echo "if (kpno == '') { alert('Nomor Order tidak boleh kosong');valid = false;}";
    echo "else if (item == '') { alert('Item tidak boleh kosong');valid = false;}";
    echo "else if (cons == '') { alert('Cons tidak boleh kosong');valid = false;}";
    echo "else if (satuan == '') { alert('Satuan tidak boleh kosong');valid = false;}";
    echo "else if (allow == '') { alert('Allow tidak boleh kosong');valid = false;}";
    echo "else {valid = true;}";
    echo "return valid;";
    echo "exit;";
  echo "}";
echo "</script>";
# END COPAS VALIDASI
?>
<script type="text/javascript">
  function getListKPNo(cri_item)
  {   var html = $.ajax
      ({  type: "POST",
          url: 'ajax2.php?modeajax=cari_list_kpno',
          data: "cri_item=" +cri_item,
          async: false
      }).responseText;
      if(html)
      {	 
          $("#cbokpno").html(html);
      }
  }
  function getListData(cri_item)
  {   var html = $.ajax
      ({  type: "POST",
          url: 'ajax2.php?modeajax=view_list_kpno',
          data: "cri_item=" +cri_item,
          async: false
      }).responseText;
      if(html)
      {	 
          $("#detail_item").html(html);
      }
      var html = $.ajax
      ({  type: "POST",
          url: 'ajax2.php?modeajax=cari_list_kpno_item',
          data: "cri_item=" +cri_item,
          async: false
      }).responseText;
      if(html)
      {  
          $("#cboitem").html(html);
      }
  }
</script>
<?PHP
# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='save_add_bom.php?mod=$mod' onsubmit='return validasi()'>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Filter Delivery Date *</label>";
            echo "<input type='text' class='form-control' name='txttglcut' id='datepicker1' 
            placeholder='Masukkan Filter Delivery Date' onchange='getListKPNo(this.value)'>";
          echo "</div>";
          echo "<div class='form-group'>";
          	echo "<label>Nomor Order *</label>";
          	echo "<select class='form-control select2' style='width: 100%;' name='txtkpno' id='cbokpno' onchange='getListData(this.value)'>";
          	echo "</select>";
          echo "</div>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Bahan Baku *</label>";
            echo "<select class='form-control select2' style='width: 100%;' name='txtitem' id='cboitem'>";
            echo "</select>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Cons *</label>";
            echo "<input type='text' class='form-control' name='txtcons'  
            placeholder='Masukkan Cons'>";
          echo "</div>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Satuan *</label>";
            echo "<select class='form-control select2' style='width: 100%;' name='txtsatuan'>";
              $sql = "select nama_pilihan isi,nama_pilihan tampil 
                from masterpilihan where kode_pilihan='Satuan' order by nama_pilihan";
              IsiCombo($sql,'','Pilih Satuan');  
            echo "</select>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Allow *</label>";
            echo "<input type='text' class='form-control' name='txtallow'  
            placeholder='Masukkan Allow'>";
          echo "</div>";
          echo "<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>";
        echo "</div>";
        echo "<div class='box-body'>";
         echo "<div id='detail_item'></div>";
        echo "</div>";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
?>