<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

if (isset($_GET['mode'])) { $mode = $_GET['mode']; } else { $mode = ""; }
$rpt = $_GET['rptid'];
$from = date('d M Y');
$to = date('d M Y');

# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>";
	echo "function validasi()";
	echo "{";
      echo "var from = document.form.txtfrom.value;";
      echo "var to = document.form.txtto.value;";
      echo "if (from == '') { alert('Dari Tanggal tidak boleh kosong'); document.form.txtfrom.focus();valid = false;}";
      echo "else if (to == '') { alert('Sampai Tanggal tidak boleh kosong'); document.form.txtto.focus();valid = false;}";
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
    
   
    	if ($rpt=='wip_cut') 
      { 
        echo "<form method='post' name='form' action='?mod=view_mut_wip_cut&rptid=wip_cut' onsubmit='return validasi()' >";
         
      }
      echo "<div class='col-md-3'>";
      echo "Laporan Mutasi WIP Cutting";
      echo "<br>";
        echo "<div class='form-group'>";
      		echo "<label>Dari Tanggal *</label>";
      		echo "<input type='text' class='form-control' id='datepicker1' name='txtfrom' placeholder='Masukkan Dari Tanggal' value='$from' autocomplete='off'>";
      	echo "</div>";
      	echo "<div class='form-group'>";
      		echo "<label>Sampai Tanggal *</label>";
      		echo "<input type='text' class='form-control' id='datepicker2' name='txtto' placeholder='Masukkan Sampai Tanggal' value='$to' autocomplete='off'>";
      	echo "</div>";
        echo "<button type='submit' name='submit' class='btn btn-primary'>Tampilkan</button>";
      echo "</div>";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
?>