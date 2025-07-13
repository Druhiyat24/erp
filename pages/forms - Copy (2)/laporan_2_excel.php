<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
if (isset($_SESSION['username'])) { $user=$_SESSION['username']; } else { header("location:../../index.php"); }

$mode = $_GET['mode'];
$mod = $_GET['mod'];

if (isset($_GET['noid'])) {$bppbno = $_GET['noid']; } else {$bppbno = "";}
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}
if (isset($_GET['from'])) {$from = date('d M Y',strtotime($_GET['from'])); } else {$from = "";}
if (isset($_GET['to'])) {$to = date('d M Y',strtotime($_GET['to'])); } else {$to = "";}
if (isset($_GET['inv'])) {$invnya = strtoupper($_GET['inv']); } else {$invnya = "";}
if (isset($_GET['mode2'])) {$tipe = $_GET['mode2']; } else {$tipe = "Bahan Baku";}


if ($tipe=="Bahan Baku") 
{ $mode2="Bahan_Baku"; }
else if ($tipe=="Item General") 
{ $mode2="General"; }
else if ($tipe=="Mesin") 
{ $mode2="Mesin"; }
else if ($tipe=="Scrap") 
{ $mode2="Scrap"; }
else if ($tipe=="Barang Dalam Proses") 
{ $mode2="WIP"; }
else if ($tipe=="Barang Jadi") 
{ $mode2="FG"; } 
else if ($tipe=="Barang Dalam Proses") 
{ $mode2="WIP"; } else { $mode2=$tipe; }

$filephp="index.php";
if ($mode=="In" OR $mode=="Detail_In")
{ 
  $titlenya = $c3; 
  $tblnya="bpb";
  $fldnya="if(bpbno_int!='',bpbno_int,bpbno) trans_no,bpbdate trans_date,pono";
  $grpnya="bpbno";
  if ($tipe=="Barang Jadi")
  { $fldnyacri=" left(bpbno,2)='FG' and bpbdate "; $mod2=55; }
  else if ($tipe=="Mesin")
  { $fldnyacri=" left(bpbno,1)='M' and bpbdate "; $mod2=53; }
  else if ($tipe=="Scrap")
  { $fldnyacri=" left(bpbno,1) in ('S','L') and bpbdate "; $mod2=52; }
  else if ($tipe=="Barang Dalam Proses")
  { $fldnyacri=" left(bpbno,1)='C' and bpbdate "; $mod2=54; }
  else if ($tipe=="Item General")
  { $fldnyacri=" left(bpbno,1)='N' and bpbdate "; $mod2=54; }
  else 
  { $fldnyacri=" left(bpbno,1) in ('A','F','B') and left(bpbno,2)!='FG' and bpbdate "; $mod2=51; }
}
else if ($mode=="Out" OR $mode=="Detail_Out")
{ 
  $titlenya = "Pengeluaran"; 
  $tblnya="bppb";
  $fldnya="if(bppbno_int!='',bppbno_int,bppbno) trans_no,bppbdate trans_date,'' pono";
  $grpnya="bppbno";
  if ($tipe=="Barang Jadi")
  { $fldnyacri=" mid(bppbno,4,2)='FG' and bppbdate "; $mod2=65; }
  else if ($tipe=="Mesin")
  { $fldnyacri=" mid(bppbno,4,1)='M' and bppbdate "; $mod2=63; }
  else if ($tipe=="Scrap")
  { $fldnyacri=" mid(bppbno,4,1) in ('S','L') and bppbdate "; $mod2=62; }
  else if ($tipe=="Barang Dalam Proses")
  { $fldnyacri=" mid(bppbno,4,1)='C' and bppbdate "; $mod2=64; }
  else if ($tipe=="Item General")
  { $fldnyacri=" mid(bppbno,4,1)='N' and bppbdate "; $mod2=64; }
  else 
  { $fldnyacri=" mid(bppbno,4,1) in ('A','F','B') and mid(bppbno,4,2)!='FG' and bppbdate "; $mod2=61; }
}
else if ($mode=="Out_Prob")
{ $titlenya = "Pengeluaran Bermasalah"; 
  $tblnya="bppb_prob";
  $fldnya="a.bppbno trans_no,a.bppbdate trans_date";
  $grpnya="a.bppbno,a.id_item";
}

if(isset($_POST['submit'])) //KLIK SUBMIT
{ $from=date('Y-m-d',strtotime($_POST['txtfrom']));
  $to=date('Y-m-d',strtotime($_POST['txtto']));
  $id_item="";
  $tipenya=$_POST['txttipe'];
  $matclass=$_POST['txtparitem'];
  echo "<script>
    window.location.href='?mod=19&mode=$mode&frexc=$from&toexc=$to&tpexc=$tipenya&clexc=$matclass&suppexc=&dest=xls';
    </script>"; 
  // echo "<script>
  //   window.location.href='index.php?mod=711&mode=$mod&frexc=$from&toexc=$to&tpexc=$tipenya&clexc=$matclass&suppexc=&dest=xls';
  //   </script>";    
}
else
{ #$from=date('d M Y');
  #$to=date('d M Y');
}



# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
/*
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
*/
# END COPAS VALIDASI
# COPAS ADD

if ($mode!="Out_Prob")
{ ?>
  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <?php
        if ($mode=="Detail_In" OR $mode=="Detail_Out" )
        { echo "<form method='post' name='form'>"; }
        else
        { echo "<form method='post' name='form'>"; }
        ?>

        <div class='col-md-3'>
          <div class='form-group'>
            <label><?php echo $c13; ?> *</label>
            <?php 
              $sql = "select nama_pilihan isi,if(nama_pilihan='Mesin','".$caption[1]."',nama_pilihan) tampil from masterpilihan 
                where kode_pilihan='Type Mat' order by nama_pilihan";
              echo "<select class='form-control select2' style='width: 100%;' name='txttipe'>";
              IsiCombo($sql,$tipe,$cpil.' '.$c13);
              echo "</select>";
            ?>
          </div>
          <div class='form-group'>
            <label><?php echo $c14.' '.$rpt;?></label>
            <select class='form-control select2' style='width: 100%;' name='txtparitem'>
            <?php 
              if ($nm_company=="PT. Sandrafine Garment") {$fldclass="mattype";} else {$fldclass="matclass";}
              $sql = "select matclass isi,if (matclass = '-','ACCESORIES PACKING DAN SEWING',matclass) tampil from masteritem 
              where mattype in ('A','F') 
              group by matclass order by matclass "; 
              IsiCombo($sql,'',$cpil.' '.$c14.' '.$rpt);
            ?>
            </select>
          </div>
        </div>
        <div class='col-md-2'>
          <div class='form-group'>
            <label><?php echo $c55; ?> *</label>
            <input type='text' class='form-control' autocomplete='off' id='datepicker1' name='txtfrom'  required
              placeholder='Masukkan Dari Tanggal' value='<?php echo $from;?>'>
          </div>
          <?php
          if ($mode=="Detail_In")
          { ?>
          <div class='form-group'>
            <label><?php echo $c_sup;?></label>
            <select class='form-control select2' style='width: 100%;' name='txtid_supplier'>
              <?php 
              $sql = "select id_supplier isi,supplier tampil from mastersupplier 
                where tipe_sup='S' order by supplier"; 
              IsiCombo($sql,'',$cpil.' '.$c_sup);
              ?>
            </select>
          </div>
          <?php } ?>
        </div>
        <div class='col-md-2'>
          <div class='form-group'>
            <label><?php echo $c56; ?> *</label>
            <input type='text' class='form-control' autocomplete='off' id='datepicker2' name='txtto' required
              placeholder='Masukkan Sampai Tanggal' value='<?php echo $to; ?>'>
          </div>
        </div>
        <?php
        if ($mode!="Detail_In" AND $mode!="Detail_Out")
        { ?>
          <div class='col-md-3'>
            <div class='form-group'>
              <?php 
              if ($mode=="Out")
              { echo "<label>Nomor Pendaftaran</label>"; 
                echo "<input type='text' class='form-control' name='txtinvno' placeholder='Masukkan Nomor Pendaftaran'>";
              }
              else
              { echo "<label>$c41</label>";
                echo "<input type='text' class='form-control' name='txtinvno' placeholder='$cmas $c41'>";
              }
              ?>
            </div>
            <button type='submit' name='submit' class='btn btn-primary'><?php echo $ctam; ?></button>
          </div>
        <?php } else { ?>
          </div>
<!--           <button type='submit' name='submit1' class='btn btn-primary' value='submit1' id = 'submit1'>Tampilkan</button>   -->        
          <button type='submit' name='submit' class='btn btn-primary' value='submit'>Export Excel</button>
<!--           <button type='submitexc' name='submitexc' class='btn btn-primary' value='submitexc'>Submit Excel</button>
         <a href='?mod=19&mode=Detail_In&frexc=$from&toexc=$to&tpexc=Bahan Baku&clexc=&suppexc=&dest=xls'>Save To Excel</a> -->
          <!--<br><a href='?mod=19&mode=Detail_In&frexc=$from&toexc=$to&tpexc=$tipenya&clexc=&suppexc=&dest=xls'>Save To Excel</a></br> -->
        </div>
      <?php } ?>
      </form>
    </div>
  </div>
</div>
<?php
  # END COPAS ADD
}
?>
