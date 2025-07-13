<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$mode="";
$titlenya="Upload Data Absensi";
if (isset($_GET['id'])) { $id_item=$_GET['id']; } else { $id_item=""; }

# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>";
  echo "function validasi()";
  echo "{";
    echo "var filenya = document.form.txtfile.value;";

    echo "if (filenya == '') { alert('Nama file belum dipilih'); document.form.txtfile.focus();valid = false;}";
    echo "else valid = true;";
    echo "return valid;";
    echo "exit;";
  echo "}";
echo "</script>";
# END COPAS VALIDASI
?>
<div class="box">
  <?PHP
  # COPAS ADD
  echo "<div class='box-body'>";
  echo "<div class='row'>";
  echo "<form method='post' name='form' enctype='multipart/form-data' action='pro_abs.php?mode=$mode' onsubmit='return validasi()'>";
  echo "<div class='col-md-3'>";
  echo "<div class='form-group'>";
    echo "<label for='exampleInputFile'>Pilih File</label>";
    echo "<input type='file' name='txtfile' accept='.txt'>";
  echo "</div>";
  echo "<div class='box-footer'>";
    echo "<button type='submit' name='submit' class='btn btn-primary'>Upload</button>";
  echo "</div>";
  echo "</form>";
  echo "</div>";
  echo "</div>";
  echo "</div>";
  echo "</div>";
  # END COPAS ADD
  ?>
</div>