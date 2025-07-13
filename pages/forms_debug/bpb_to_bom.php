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
    echo "if (kpno == '') { alert('Nomor Order tidak boleh kosong');valid = false;}";
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
          url: 'ajax2.php?modeajax=cari_list_kpno2',
          data: "cri_item=" +cri_item,
          async: false
      }).responseText;
      if(html)
      {	 
          $("#cbokpno").html(html);
      }
  }
  function getListBPBNo(cri_item)
  {   var html = $.ajax
      ({  type: "POST",
          url: 'ajax2.php?modeajax=cari_list_bpbno2',
          data: "cri_item=" +cri_item,
          async: false
      }).responseText;
      if(html)
      {  
          $("#cbobpbno").html(html);
      }
  }
</script>
<?PHP
# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='s_bpb_to_bom.php' onsubmit='return validasi()'>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Filter Tgl Pemasukan *</label>";
            echo "<input type='text' class='form-control' name='txttglcutbpb' id='datepicker2' 
            placeholder='Masukkan Filter Tgl Pemasukan' onchange='getListBPBNo(this.value)'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Nomor Pemasukan *</label>";
            echo "<select class='form-control select2' style='width: 100%;' name='txtbpbno' id='cbobpbno'>";
            echo "</select>";
          echo "</div>";
          echo "<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Filter Delivery Date *</label>";
            echo "<input type='text' class='form-control' name='txttglcut' id='datepicker1' 
            placeholder='Masukkan Filter Delivery Date' onchange='getListKPNo(this.value)'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Nomor Order *</label>";
            echo "<select class='form-control select2' style='width: 100%;' name='txtkpno' id='cbokpno'>";
            echo "</select>";
          echo "</div>";
        echo "</div>";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
?>