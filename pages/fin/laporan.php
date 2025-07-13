<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
if (isset($_SESSION['username'])) { $user=$_SESSION['username']; } else { header("location:../../index.php"); }

$mode = $_GET['mode'];
$mod = $_GET['mod'];
if($mode=="PO")
{ $capfr="PO Date From"; 
  $capto="PO Date To"; 
}
else if($mode=="PL")
{ $capfr="Delivery Date From"; 
  $capto="Delivery Date To";
}
else
{ $capfr=$c55; 
  $capto=$c56;
}
if (isset($_GET['from'])) {$from = date('d M Y',strtotime($_GET['from'])); } else {$from = date('d M Y');}
if (isset($_GET['to'])) {$to = date('d M Y',strtotime($_GET['to'])); } else {$to = date('d M Y');;}

$filephp="index.php";

# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>";
	echo "function validasi()";
	echo "{";

echo "var tipe = document.form.txttipe.value;";
echo "var from = document.form.txtfrom.value;";
echo "var to = document.form.txtto.value;";

echo "if (tipe == '') { alert('Tipe tidak boleh kosong'); document.form.txttipe.focus();valid = false;}";
echo "else if (from == '') { alert('Dari Tanggal tidak boleh kosong'); document.form.txtfrom.style.backgroundColor='yellow'; document.form.txtfrom.focus();valid = false;}";
echo "else if (to == '') { alert('Sampai Tanggal tidak boleh kosong'); document.form.txtto.style.backgroundColor='yellow'; document.form.txtto.focus();valid = false;}";
echo "else valid = true;";
echo "return valid;";
echo "exit;";
	echo "}";
echo "</script>";
# END COPAS VALIDASI
# COPAS ADD
if ($mode!="Out_Prob")
{ echo "<div class='box'>";
    echo "<div class='box-body'>";
      echo "<div class='row'>";
      if ($mode=="PO")
      { echo "<form method='post' name='form' action='?mod=7&mode=PO' onsubmit='return validasi()'>"; }
      else if ($mode=="PL")
      { echo "<form method='post' name='form' action='?mod=6&mode=PL' onsubmit='return validasi()'>"; }
      else if ($mode=="GL")
      { echo "<form method='post' name='form' action='?mod=6&mode=GL' onsubmit='return validasi()'>"; }
      else if ($mode=="PC")
      { echo "<form method='post' name='form' action='?mod=6&mode=PC' onsubmit='return validasi()'>"; }
      echo "<div class='col-md-3'>";
        echo "<div class='form-group'>";
          echo "<label>$capfr *</label>";
          echo "<input type='text' class='form-control' id='datepicker1' 
            name='txtfrom' value='$from'>";
        echo "</div>";
        echo "<div class='form-group'>";
          echo "<label>$capto *</label>";
          echo "<input type='text' class='form-control' id='datepicker2' name='txtto' placeholder='Masukkan Sampai Tanggal' value='$to'>";
        echo "</div>";
        echo "<button type='submit' name='submit' class='btn btn-primary'>$ctam</button>"; 
      echo "</div>";
      if ($mode=="LapAbs")
      { echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Bagian *</label>";
            $sql = "select nama_pilihan isi, nama_pilihan tampil from 
              masterpilihan where kode_pilihan='Bagian'";
            echo "<select class='form-control select2' style='width: 100%;' 
              name='txtbagian'>";
            IsiCombo($sql,$bagian,'Pilih Bagian');
            echo "</select>
          </div>
        </div>";
      }
      echo "</form>";
    echo "</div>";
    echo "</div>";
  echo "</div>";
  # END COPAS ADD
}
?>