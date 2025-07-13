<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

if (isset($_GET['mode'])) { $mode = $_GET['mode']; } else { $mode = ""; }
$rpt = $_GET['mod'];
$modv = $rpt."v";
// $from = date('d M Y');
// $to = date('d M Y');

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
    	echo "<form method='post' name='form' action='?mod=$modv' onsubmit='return validasi()'>";
      echo "<div class='col-md-3'>";
      	echo "<div class='form-group'>";
      		echo "<label>Dari Deliv. Date *</label>";
      		echo "<input type='text' class='form-control' id='datepicker1' name='txtfrom' placeholder='Masukkan Dari Tanggal' value='$from'>";
      	echo "</div>";
        echo "<div class='form-group'>";
      		echo "<label>Sampai Deliv. Date *</label>";
      		echo "<input type='text' class='form-control' id='datepicker2' name='txtto' placeholder='Masukkan Sampai Tanggal' value='$to'>";
      	echo "</div>";?>
        <div class='form-group'>
          <label>Buyer Name</label>
          <select class='form-control select2' style='width: 100%;' 
            name='txtid_buyer'>
           <?php 
            $sql = "select id_supplier isi,supplier tampil from mastersupplier where tipe_sup='C' order by supplier";
            IsiCombo($sql,$buyer,'Pilih Buyer Name');
           ?>
          </select>
        </div>
        <?php
        echo "<button type='submit' name='submit' class='btn btn-primary'>Tampilkan</button>";
      echo "</div>";
      echo "<div class='col-md-3'>";
        echo "<div class='form-group'>";
          echo "<label>Group Marketing</label>";
          echo "<input type='text' class='form-control' name='txtkode_mkt' placeholder='Masukkan Group Marketing'>";
        echo "</div>";?>
        <div class='form-group'>
          <label>Status</label>
          <select class='form-control select2' style='width: 100%;' 
            name='txtstatus'>
            <?php 
              if($mod=="18") { $sql_fil = " and nama_pilihan in ('CANCEL','CONFIRM') "; } else { $sql_fil = ""; }
              $sql = "select nama_pilihan isi,nama_pilihan tampil from 
                masterpilihan where kode_pilihan='ST_CST' $sql_fil ";
              IsiCombo($sql,$status,'Pilih Status');
            ?>
          </select>
        </div>
        <?php
      echo "</div>";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
?>