<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
if (isset($_SESSION['username'])) { $user=$_SESSION['username']; } else { header("location:../../index.php"); }

$mode = $_GET['mode'];
$mod = $_GET['mod'];

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
      if ($mode=="LapAbs")
      { echo "<form method='post' name='form' action='?mod=9&mode=$mode' onsubmit='return validasi()'>"; }
      else if ($mode=="LapRek")
      { echo "<form method='post' name='form' action='?mod=21' onsubmit='return validasi()'>"; }
      else if ($mode=="LapRekS")
      { echo "<form method='post' name='form' action='lap_slip2.php' onsubmit='return validasi()'>"; }
      else if ($mode=="LapOT" or $mode=="LapOTD")
      { echo "<form method='post' name='form' action='?mod=22&mode=$mode' onsubmit='return validasi()'>"; }
      else if ($mode=="LapRAbs")
      { echo "<form method='post' name='form' action='?mod=25' onsubmit='return validasi()'>"; }
      else if ($mode=="LapSPL")
      { echo "<form method='post' name='form' action='?mod=30' onsubmit='return validasi()'>"; }
      echo "<div class='col-md-3'>";
        echo "<div class='form-group'>";
          if ($mode=="LapRek" or $mode=="LapRekS")
          { echo "<label>Pay Date *</label>"; }
          else
          { echo "<label>$c55 *</label>"; }
          echo "<input type='text' class='form-control' id='datepicker1' name='txtfrom' value='$from'>";
        echo "</div>";
        if ($mode=="LapRek" or $mode=="LapRekS")
        { echo "<div class='form-group'>";
            echo "<label>Department *</label>";
            $sql = "select nama_pilihan isi, nama_pilihan tampil from 
              masterpilihan where kode_pilihan='Dept'";
            echo "<select class='form-control select2' style='width: 100%;' 
              name='txtdept'>";
            IsiCombo($sql,'','Pilih Dept');
            echo "</select>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Line *</label>";
            $sql = "select line isi, line tampil from 
              hr_masteremployee group by line";
            echo "<select class='form-control select2' style='width: 100%;' 
              name='txtline'>";
            IsiCombo($sql,'','Pilih Line');
            echo "</select>";
          echo "</div>";
        }
        else
        { echo "<div class='form-group'>";
            echo "<label>$c56 *</label>";
            echo "<input type='text' class='form-control' id='datepicker2' name='txtto' placeholder='Masukkan Sampai Tanggal' value='$to'>";
          echo "</div>";
        }
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