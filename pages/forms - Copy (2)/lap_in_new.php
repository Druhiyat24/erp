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
   
    	if ($rpt=='bahanbaku' or $rpt=='bahanbaku2' OR $rpt=='barangjadi' OR $rpt=='barangsisa' 
        OR $rpt=='mesin' OR $rpt=='itemgen' OR $rpt=='gb_bahanbaku' OR $rpt=='mwipdet' OR $rpt=='mwiptot'
        OR $rpt=='hasil' OR $rpt=='hasilfg' OR $rpt=='hasilsl' OR $rpt=='hasilwip' OR $rpt=='hasilmes') 
      { 
        echo "<form method='post' name='form' action='?mod=view_mut_new&rptid=$rpt' onsubmit='return validasi()' >";
         
      }
      else if ($rpt=='wip') 
      { echo "<form method='post' name='form' action='tampil_wip.php?rptid=$rpt' onsubmit='return validasi()'>"; }
      else if ($rpt=='out_dev' or $rpt=='in_dev') 
      { echo "<form method='post' name='form' action='r_devisa.php?rptid=$rpt' onsubmit='return validasi()'>"; }
      else if ($rpt=='listhist') 
      { echo "<form method='post' name='form' action='?mod=19&mode=Hist' onsubmit='return validasi()'>"; }
      else if ($rpt=='reqmat') 
      { echo "<form method='post' name='form' action='?mod=rptreqmat' onsubmit='return validasi()'>"; }
      else if ($rpt=='rkonf') 
      { echo "<form method='post' name='form' action='?mod=rptrkonf' onsubmit='return validasi()'>"; }
      else
      { echo "<form method='post' name='form' action='?mod=view_in&rptid=$rpt' onsubmit='return validasi()'>"; }
      echo "<div class='col-md-3'>";
      	if ($rpt=="bahanbaku" or $rpt=="bahanbaku2")
        { 
          echo "<div class='form-group'>";
            echo "<label>$c14 $rpt</label>"; 
            echo "<select class='form-control select2' style='width: 100%;' name='txtparitem'>";
            $sql = "select matclass isi, CONCAT(if (matclass = '-','ACCESORIES PACKING DAN SEWING',matclass), IF( mattype in ('F'),' (BAHAN BAKU)',' (BAHAN PENOLONG)')) tampil from masteritem 
              where mattype in ('A','F') and matclass not in ('barang jadi','panel') 
              group by matclass order by matclass "; 
            IsiCombo($sql,'',$cpil.' '.$c14.' '.$rpt);
            echo "</select>";
          echo "</div>";
        }
        else if ($rpt=="rkonf")
        { 
          echo "<div class='form-group'>";
            echo "<label>Transaksi</label>"; 
            echo "<select class='form-control select2' style='width: 100%;' name='txtjenis'>";
            $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan  
              where kode_pilihan='J_Tr'"; 
            IsiCombo($sql,'',$cpil.' Transaksi');
            echo "</select>";
          echo "</div>";
        }
        //==adyz========================================================================================
        elseif ($rpt=="itemgen")
        { 
          echo "<div class='form-group'>";
            echo "<label>$c14 $rpt</label>"; 
            echo "<select class='form-control select2' style='width: 100%;' name='txtparitem'>";
            $sql = "select description isi, DESCRIPTION as tampil from mapping_category "; 
            IsiCombo($sql,'',$cpil.' '.$c14.' '.$rpt);
            echo "</select>";
          echo "</div>";
        }

        //==adyz========================================================================================
        
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