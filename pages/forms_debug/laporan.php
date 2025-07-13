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
  $invno=$_POST['txtinvno'];
  $tipenya=$_POST['txttipe'];
  echo "<script>
    window.location.href='index.php?mod=$mod&mode=$mode&from=$from&to=$to&id=$id_item&mode2=$tipenya&inv=$invno';
    </script>";
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
        if ($mode=="Detail_In" OR $mode=="Detail_Out")
        { echo "<form method='post' name='form' action='?mod=19&mode=$mode' onsubmit='return validasi()'>"; }
        else
        { echo "<form method='post' name='form' action='index.php?mod=$mod&mode=$mode' onsubmit='return validasi()'>"; }
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
              $sql = "select $fldclass isi,$fldclass tampil from masteritem 
                where mattype in ('A','F') 
                group by $fldclass order by $fldclass "; 
              IsiCombo($sql,'',$cpil.' '.$c14.' '.$rpt);
            ?>
            </select>
          </div>
        </div>
        <div class='col-md-2'>
          <div class='form-group'>
            <label><?php echo $c55; ?> *</label>
            <input type='text' class='form-control' id='datepicker1' name='txtfrom' 
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
            <input type='text' class='form-control' id='datepicker2' name='txtto' 
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
          <button type='submit' name='submit' class='btn btn-primary'><?php echo $ctam; ?></button>
        </div>
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
<div class="box">
  <div class="box-header">
    <h3 class="box-title">List <?php echo $titlenya; ?></h3>
  </div>
  <div class="box-body">
    <table id="tbl_list_in_out" class="table table-bordered table-striped">
      <thead>
        <tr>
            <th>No</th>
            <th>Nomor Trans</th>
            <th>Seq Trans</th>
            <th>Tgl. Trans</th>
            <th>PO #</th>
            <th>Supplier</th>
            <?php 
            if ($mode=="Out_Prob")
            { echo "<th>Nomor Aju</th>"; }
            ?>
            <th>Jenis Dok</th>
            <th>Nomor Dok</th>
            <th>Tgl. Dok</th>
            <?php 
            if ($mode=="Out_Prob")
            { echo "<th>Kode Barang</th>";
              echo "<th>Nama Barang</th>";
              echo "<th>Jumlah</th>";
            }
            else
            { echo "
              <th></th>
              <th></th>";
              $data = mysql_fetch_array(mysql_query("SELECT * FROM mastercompany limit 1"));
                $print_sj = $data['print_sj'];
                $logo_company = $data['logo_company'];
              $data = mysql_fetch_array(mysql_query("SELECT * FROM userpassword where username='$user' limit 1"));
              if ($tipe=="Barang Jadi")
              { $akses_ubah = $data['mnuBPBFG']; }
              else
              { $akses_ubah = $data['mnuBPB']; }
            }
            ?>
        </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $fromcri=date('Y-m-d',strtotime($from));
        $tocri=date('Y-m-d',strtotime($to));
        if ($mode=="In" AND $invnya!="")
        { $sql="SELECT $fldnya,supplier,jenis_dok,bcno,bcdate,a.cancel   
            FROM $tblnya a inner join mastersupplier s on a.id_supplier=s.id_supplier 
            where invno='$invnya' GROUP BY $grpnya ORDER BY $fldnyacri ASC";
        }
        else if ($mode=="Out" AND $invnya!="")
        { $sql="SELECT $fldnya,supplier,jenis_dok,bcno,bcdate,a.cancel   
            FROM $tblnya a inner join mastersupplier s on a.id_supplier=s.id_supplier 
            where bcno='$invnya' GROUP BY $grpnya ORDER BY $fldnyacri ASC";
        }
        else if ($mode=="Out_Prob")
        { $sql="SELECT $fldnya,supplier,right(a.nomor_aju,6) ajuno,concat(a.jenis_dok,' ',a.bcno) bcno,
            a.bcdate,d.goods_code,d.itemdesc,a.qty,a.cancel   
            FROM $tblnya a inner join mastersupplier s on a.id_supplier=s.id_supplier
            inner join masteritem d on a.id_item=d.id_item
            left join bppb f on a.bcno=f.bcno and a.bcdate=f.bcdate and a.qty=f.qty
            where f.bppbno is null
            GROUP BY $grpnya ORDER BY a.bppbdate DESC";
        }
        else if ($from!='' AND $to!='')
        { $sql="SELECT $fldnya,supplier,jenis_dok,bcno,bcdate,a.cancel   
            FROM $tblnya a inner join mastersupplier s on a.id_supplier=s.id_supplier 
            where $fldnyacri between '$fromcri' and '$tocri' GROUP BY $grpnya ORDER BY $fldnyacri ASC";
        }
        else
        { if ($mode=="In") {$fld_order="bpbdate"; $fldwh="bpbno";} else {$fld_order="bppbdate";$fldwh="bppbno";}
          $sql="SELECT $fldnya,supplier,jenis_dok,bcno,bcdate,a.cancel   
            FROM $tblnya a inner join mastersupplier s on a.id_supplier=s.id_supplier 
            where $fldwh not regexp 'FG' GROUP BY $grpnya ORDER BY $fld_order desc limit 100";
        }
        #echo $sql;
        $query = mysql_query($sql);
        $no = 1; 
        while($data = mysql_fetch_array($query))
        { 
          $seq_no = substr($data['trans_no'],-5,5);
          if($data['cancel']=="Y")
          {
            $fcol = "style='color:red;'";
          }
          else
          {
            $fcol = "";
          }
          echo "<tr $fcol>";
        echo "<td>$no</td>"; 
        echo "<td>$data[trans_no]</td>"; 
        echo "<td>$seq_no</td>"; 
        $trans_date=date('d M Y',strtotime($data['trans_date']));
        if ($data['bcdate']=="0000-00-00")
        { $bcdate="-"; }
        else
        { $bcdate=date('d M Y',strtotime($data['bcdate'])); }
        echo "<td>$trans_date</td>"; 
        if($tipe=="Barang Jadi" and $mode=="Out")
        { $ponya=flookup("buyerno","bppb a inner join masterstyle s on a.id_item=s.id_item","bppbno='$data[trans_no]'");
          echo "<td>$ponya</td>";
        }
        else
        { echo "<td>$data[pono]</td>"; }
        echo "<td>$data[supplier]</td>";
        if ($mode=="Out_Prob")
        { echo "<td>$data[ajuno]</td>"; }
        echo "<td>$data[jenis_dok]</td>"; 
        echo "<td>$data[bcno]</td>"; 
        echo "<td>$bcdate</td>"; 
        if ($mode!="Out_Prob")
        { if($logo_company!="S")
          { echo "
            <td>
              <a $cl_ubah href='$filephp?mod=$mod2&mode=$mode2&noid=$data[trans_no]' $tt_ubah
              </a>
            </td>";
          }
          else
          { echo "<td></td>"; }
          echo "
          <td>
            <a href='#' class='edit-record' data-id=$data[trans_no] 
              data-toggle='tooltip' title='Detail'><i class='fa fa-bars'></i>
            </a>
          </td>";
          // if ($print_sj=="1")
          // { if($nm_company=="PT. Sandrafine Garment") {$nm_file="cetaksj_sf.php";} else {$nm_file="cetaksj.php";}
          //   echo "
          //   <td>
          //     <a href='$nm_file?mode=$mode&noid=$data[trans_no]' 
          //       data-toggle='tooltip' title='Cetak'><i class='fa fa-print'></i>
          //     </a>
          //   </td>"; 
          // }
          // else
          // { echo "<td></td>"; }
        }
        else
        { echo "<td>$data[goods_code]</td>";
          echo "<td>$data[itemdesc]</td>";
          echo "<td>$data[qty]</td>";
        }
        echo "</tr>";
        $no++; // menambah nilai nomor urut
        }
        ?>
      </tbody>
    </table>
  </div>
</div>