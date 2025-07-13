<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$mode = $_GET['mode'];

# START CEK HAK AKSES KEMBALI
if ($mode=="In")
{ $akses = flookup("import_tpb_in","userpassword","username='$user'"); }
else
{ $akses = flookup("import_tpb_out","userpassword","username='$user'"); }
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI

$st_company = flookup("status_company","mastercompany","company!=''");

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

# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' enctype='multipart/form-data' action='import_proses.php?mode=$mode' onsubmit='return validasi()'>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
          	echo "<label for='exampleInputFile'>Pilih File</label>";
            echo "<input type='file' name='txtfile' accept='.txt'>";
          echo "</div>";
          echo "<button type='submit' name='submit' class='btn btn-primary'>Upload</button>";
        echo "</div>";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
?>