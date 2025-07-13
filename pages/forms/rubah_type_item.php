<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];

# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>";
	echo "function validasi()";
	echo "{";

  echo "var tipe = document.form.txttipe.value;";
  echo "var from = document.form.txtfrom.value;";
  echo "var tipe_old = document.form.txttahun.value;";
  
  echo "if (tipe == '') { alert('Tipe Baru tidak boleh kosong'); document.form.txttipe.focus();valid = false;}";
  echo "else if (tipe == tipe_old) { alert('Tipe tidak boleh sama'); document.form.txttipe.focus();valid = false;}";
  echo "else if (from == '') { alert('Nama Barang tidak boleh kosong'); document.form.txtfrom.focus();valid = false;}";
  echo "else valid = true;";
  echo "return valid;";
  echo "exit;";
	echo "}";
echo "</script>";
# END COPAS VALIDASI
?>
<script type="text/javascript">
  function getItem(cri_item)
  {   var html = $.ajax
      ({  type: "POST",
          url: 'ajax.php?modeajax=cari_item',
          data: "cri_item=" +cri_item,
          async: false
      }).responseText;
      if(html)
      {
          $("#cboaju").html(html);
      }
  }
</script>
<?PHP
# COPAS ADD
echo "<div class='box'>";
echo "<div class='box-body'>";
echo "<div class='row'>";
echo "<form method='post' name='form' action='rubah_type_item_act.php' onsubmit='return validasi()'>";
echo "<div class='col-md-3'>";
echo "<div class='form-group'>";
echo "<label>Jenis Barang</label>";
$sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan 
where kode_pilihan='Rubah_Type'";
echo "<select class='form-control select2' style='width: 100%;' name='txttahun' onchange='getItem(this.value)'>";
IsiCombo($sql,'','Pilih Jenis Barang');
echo "</select>";
echo "</div>";
echo "<div class='form-group'>";
echo "<label>Nama Barang *</label>";
echo "<select class='form-control select2' style='width: 100%;' id='cboaju' name='txtfrom'>";
echo "</select>";
echo "</div>";
echo "<div class='form-group'>";
echo "<label>Tipe Baru *</label>";
$tipe="";
$sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan 
where kode_pilihan='Mat Type' or nama_pilihan='Barang Jadi' group by nama_pilihan order by nama_pilihan";
echo "<select class='form-control select2' style='width: 100%;' name='txttipe'>";
IsiCombo($sql,$tipe,'Pilih Tipe');
echo "</select>";
echo "</div>";
echo "<div class='box-footer'>";
echo "<button type='submit' name='submit' class='btn btn-primary'>Rubah</button>";
echo "</div>";
echo "</form>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "</div>";
# END COPAS ADD
?>