<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
if (isset($_SESSION['username'])) { $user=$_SESSION['username']; } else { header("location:../../index.php"); }

$mode = "";
$mod = $_GET['mod'];
if($mode=="PO")
{ $capfr="PO Date From"; 
  $capto="PO Date To"; 
}
else if($mode=="MCC" or $mod=="monorder")
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
	echo "
  function getStyle(cri_item)
  { var html = $.ajax
    ({  type: 'POST',
        url: 'ajax_ws.php?mdajax=cari_style',
        data: 'cri_item=' +cri_item,
        async: false
    }).responseText;
    if(html)
    { $('#txtstyle').html(html); }
  };";
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
      echo "<form method='post' name='form' action='?mod=monorderv' onsubmit='return validasi()'>";
      ?>
      <div class='col-md-3'>
        <div class='form-group'>
          <label><?=$capfr?> *</label>
          <input type='text' class='form-control' id='datepicker1' 
            name='txtfrom' value='<?=$from?>'>
        </div>
        <div class='form-group'>
          <label><?=$capto?> *</label>
          <input type='text' class='form-control' id='datepicker2' name='txtto' 
            placeholder='Masukkan Sampai Tanggal' value='<?=$to?>'>
        </div>
        <button type='submit' name='submit' class='btn btn-primary'><?=$ctam?></button>
      </div>
      <div class='col-md-3'>
        <div class='form-group'>
          <label>Buyer *</label>
            <select class='form-control select2' style='width: 100%;' name='txtid_buyer' 
              onchange='getStyle(this.value)'>
            <?php 
              $sql = "select id_supplier isi,supplier tampil from mastersupplier where tipe_sup='C' order by supplier";
              IsiCombo($sql,'','Pilih Buyer Name');
            ?>
            </select>
        </div>
        <div class='form-group'>
          <label>Style # *</label>
          <select class='form-control select2' style='width: 100%;' name='txtstyle' id='txtstyle'>
          </select>
        </div>
      </div>
      <?php 
      // if ($mode=="LapAbs")
      // { echo "
      //   <div class='col-md-3'>
      //     <div class='form-group'>
      //       <label>Bagian *</label>";
      //       $sql = "select nama_pilihan isi, nama_pilihan tampil from 
      //         masterpilihan where kode_pilihan='Bagian'";
      //       echo "<select class='form-control select2' style='width: 100%;' 
      //         name='txtbagian'>";
      //       IsiCombo($sql,$bagian,'Pilih Bagian');
      //       echo "</select>
      //     </div>
      //   </div>";
      // }
      echo "</form>";
    echo "</div>";
    echo "</div>";
  echo "</div>";
  # END COPAS ADD
}
?>