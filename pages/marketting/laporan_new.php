<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

if (isset($_GET['mode'])) { $mode = $_GET['mode']; } else { $mode = ""; }
$rpt = $_GET['mod'];
$modv = $rpt."v";
$from = date('d M Y');
 $to = date('d M Y');

# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>";
	echo "function validasi()";
	echo "{";
      echo "var tipe = document.form.txttipe.value;";
      echo "if (tipe == '') { alert('Tipe pencarian tidak boleh kosong'); document.form.txttipe.focus();valid = false;}";
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
    	echo "<form method='post' name='form' onsubmit='return validasi()'>";
      echo "<div class='col-md-3'>";
        echo "<div class='form-group'>";
          echo "<label>Pilih Tipe Pencarian</label>";
          echo "<select class='form-control select2' style='width: 100%;'name='txttipe' id='txttipe'>";
          echo "<option value='' selected disabled hidden>Pilih Tipe</option>";
          echo "<option value='SO'>Per SO</option>";
          echo "<option value='Warna'>Per Warna</option>";
          echo "<option value='Size'>Per Size</option>";
          echo "</select>";
        echo "</div>";      
      	echo "<div class='form-group'>";
      		echo "<label>Tgl SO Awal</label>";
      		echo "<input type='text' class='form-control' id='datepicker1' name='txtfrom' placeholder='Masukkan Dari Tanggal' value='$from'>";
      	echo "</div>";
        echo "<div class='form-group'>";
      		echo "<label>Tgl SO Akhir</label>";
      		echo "<input type='text' class='form-control' id='datepicker2' name='txtto' placeholder='Masukkan Sampai Tanggal' value='$to'>";
      	echo "</div>";?>
        <?php
        echo "<button type='submit' name='submit' class='btn btn-primary'>Export</button>";
      echo "</div>";?>
        <?php
      echo "</div>";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";


if(isset($_POST['submit'])) //KLIK SUBMIT
{ $from=date('Y-m-d',strtotime($_POST['txtfrom']));
  $to=date('Y-m-d',strtotime($_POST['txtto']));
  $tipe=$_POST['txttipe'];
  echo "<script>
  window.open ('?mod=177v&from=$from&to=$to&tipe=$tipe&dest=xls', '_blank');
    </script>";    
}
else
{ 
}

?>