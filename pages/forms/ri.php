<?php 

if (empty($_SESSION['username'])) { header("location:../../index.php"); }


$akses_date = flookup("original_date","userpassword","username='$user'");

$query = mysql_query("SELECT * FROM mastercompany limit 1");

$data = mysql_fetch_array($query);

$nm_company = $data['company'];

$st_company = $data['status_company'];

$logo_company = $data['logo_company'];

$id_item="";

$rino="";

$ridate=date('d M Y');

$mod=$_GET['mod'];

$mode=$_GET['mode'];

if($mode=="FG")
  { $titlenya="Barang Jadi"; }
else if($mode=="General")
  { $titlenya="Item General"; }
else
  { $titlenya="Bahan Baku"; }

# COPAS EDIT

$frdate=date("d M Y");
$kedate=date("d M Y");

$tglf=date("d M Y");
$tglt=date("d M Y");

$dtf=date("d M Y");
$dtt=date("d M Y");

$perf=date("d M Y");
$pert=date("d M Y");

if (isset($_POST['submit']))
{
  $excel="N";
  $tglf = fd($_POST['frdate']);
  $perf = date('d M Y', strtotime($tglf));
  $tglt = fd($_POST['kedate']);
  $pert = date('d M Y', strtotime($tglt));


}


# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama

echo "

<script type='text/javascript'>

function validasi()

{

  var sjno = document.form.txtsjno.value;

  var sjno2 = document.form.txtsjno2.value;

  var jendok = document.form.txtstatus_kb.value;

  var qtyo = document.form.getElementsByClassName('itemclass');

  var qtybts = document.form.getElementsByClassName('qtysjclass');

  var nodata = 0;

  var dataover = 0;

  var bcno = document.form.txtbcno.value;

  var bcdate = document.form.txtbcdate.value;

  for (var i = 0; i < qtyo.length; i++) 

  { if (Number(qtyo[i].value) > 0)

    { nodata = nodata + 1; break; }

  }

  for (var i = 0; i < qtyo.length; i++) 

  { if (Number(qtyo[i].value) > 0 && Number(qtyo[i].value) > Number(qtybts[i].value) )

    { dataover = dataover + 1; break; }

  }

  if (sjno == '') 

  { valid = false; 

    swal({ title: 'Nomor SJ Asal Tidak Boleh Kosong', $img_alert });

  }

  else if (sjno2 == '') 

  { valid = false; 

    swal({ title: 'Inv # / SJ # Tidak Boleh Kosong', $img_alert });

  }

  else if (nodata == '0') 

  { valid = false; 

    swal({ title: 'Tidak Ada Data', $img_alert });

  }

  else if (dataover > 0) 

  { valid = false; 

    swal({ title: 'Qty RI Melebihi Qty SJ', $img_alert });

  }

  else if (jendok == '') 

  { valid = false; 

    swal({ title: 'Jenis Dokumen Tidak Boleh Kosong', $img_alert });

  }";

   //echo "else if (jendok !== 'INHOUSE' && bcno == '') { document.form.txtbcno.focus();swal({ title: 'Nomor Daftar Tidak Boleh Kosong', $img_alert });valid = false;}";

  echo "else if (jendok !== 'INHOUSE' && bcdate == '') { document.form.txtbcdate.focus();swal({ title: 'Tgl. Daftar Tidak Boleh Kosong', $img_alert });valid = false;}";

  echo "

  else valid = true;

  return valid;

  exit;

}

</script>";

# END COPAS VALIDASI

?>

<script type="text/javascript">

  function getListKPNo(cri_item)

  {   var html = $.ajax

    ({  type: "POST",
      <?php if($mode=="FG") { ?>
        url: 'ajax2_ri.php?modeajax=cari_list_sj_ri_fg',
      <?php } else if($mode=="General") { ?>
        url: 'ajax2_ri.php?modeajax=cari_list_sj_ri_gen',
      <?php } else { ?>
        url: 'ajax2_ri.php?modeajax=cari_list_sj_ri',
      <?php } ?>
      data: "cri_item=" +cri_item,

      async: false

    }).responseText;

    if(html)

    {  

      $("#cbosj").html(html);

    }

  };

  function getTujuan(cri_item)

  {   var html = $.ajax

    ({  type: "POST",

      url: 'ajax.php?modeajax=cari_tujuan',

      data: "cri_item=" +cri_item,

      async: false

    }).responseText;

    if(html)

      { $("#cbotujuan").html(html); }

  };

  function getListData(cri_item)

  {   var html = $.ajax

    ({  type: "POST",

      <?php if($mode=="FG") { ?>

        url: 'ajax2_ri.php?modeajax=view_list_sj_ri_fg',

      <?php } else { ?>

        url: 'ajax2_ri.php?modeajax=view_list_sj_ri',

      <?php } ?>

      data: "cri_item=" +cri_item,

      async: false

    }).responseText;

    if(html)

    {  

      $("#detail_item").html(html);

    }

    $(document).ready(function() {

      var table = $('#examplefix2').DataTable

      ({  scrollCollapse: true,

        paging: false,

        fixedColumns:   

        { leftColumns: 1,

          rightColumns: 1

        }

      });

    });

  };

</script>

<?php

# COPAS ADD

if($mod=="20")

  { echo "<div class='box'>";

echo "<div class='box-body'>";

echo "<div class='row'>";

echo "<form method='post' name='form' action='save_data_ri.php?mod=$mod&mode=$mode&id=$id_item' onsubmit='return validasi()'>";

echo "

<div class='col-md-3'>

<div class='form-group'>

<label>Filter Tgl Surat Jalan *</label>

<input type='text' class='form-control' name='txttglcut' id='datepicker1' 

placeholder='Masukkan Filter Tgl Surat Jalan' onchange='getListKPNo(this.value)'>

</div>

<div class='form-group'>

<label>Surat Jalan Asal*</label>

<select class='form-control select2' style='width: 100%;' name='txtsjno' id='cbosj' 

onchange='getListData(this.value)'>

</select>

</div>

<div class='form-group'>

<label>Inv # / SJ # *</label>

<input type='text' class='form-control' name='txtsjno2'>

</div>

</div>";

echo "<div class='col-md-3'>";

echo "<div class='form-group'>";

echo "<label>$caption[4]</label>";

echo "<input type='text' class='form-control' name='txtrino' placeholder='$cmas $caption[4]' readonly value='$rino'>";

echo "</div>";

echo "<div class='form-group'>";

echo "<label>$caption[5] *</label>";

echo "<input type='text' class='form-control' name='txtbpbdate' id='datepicker2' placeholder='$cmas $caption[5]' value='$ridate'>";

echo "</div>";

echo "</div>";

echo "<div class='col-md-3'>";

echo "<div class='form-group'>";

echo "<label>$c46 *</label>";

if ($st_company=="KITE") 

  { $status_kb_cri="Status KITE In"; }

else

  { $status_kb_cri="Status KB In"; }

$sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan where 

kode_pilihan='$status_kb_cri' order by nama_pilihan";

echo "<select class='form-control select2' style='width: 100%;' name='txtstatus_kb' onchange='getTujuan(this.value)'>";

IsiCombo($sql,$status_kb,$cpil.' '.$c46);

echo "</select>";

echo "</div>";

echo "<div class='form-group'>";

echo "<label>$c47</label>";

echo "<select class='form-control select2' style='width: 100%;' id='cbotujuan' name='txttujuan'>";

if ($bpbno!="")

  { $sql = "select nama_pilihan isi,nama_pilihan tampil 

from masterpilihan where kode_pilihan='$status_kb'";

IsiCombo($sql,trim($txttujuan),$cpil.' '.$c47);

}

echo "</select>";

echo "</div>";

echo "

<div class='row'>

<div class='col-md-6'>

<div class='form-group'>

<label>$c42 *</label>

<input type='text' class='form-control' name='txtbcno' placeholder='$cmas $c42' >

</div>

</div>

<div class='col-md-6'>

<div class='form-group'>

<label>$c43 *</label>

<input type='text' class='form-control' name='txtbcdate' id='datepicker3'  

placeholder='Masukkan Tgl. Daftar'>

</div>

</div>

</div>";

echo "</div>";

echo "<div class='box-body'>";

echo "<div id='detail_item'></div>";

echo "</div>";

echo "<div class='col-md-3'>";

echo "<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>";

echo "</div>";

echo "</form>";

echo "</div>";

echo "</div>";

echo "</div>";

}

else if ($mod=="20v")

  { ?>

    <div class="box">

      <?php 

      if ($mode=="FG")

        { $fldnyacri=" left(bpbno,2)='FG' "; $mod2="20e"; }

      else if ($mode=="Mesin")

        { $fldnyacri=" left(bpbno,1)='M' "; $mod2=53; }

      else if ($mode=="Scrap")

        { $fldnyacri=" left(bpbno,1) in ('S','L') "; $mod2=52; }

      else if ($mode=="WIP")

        { $fldnyacri=" left(bpbno,1)='C' "; $mod2=54; }

      else if ($mode=="General")

        { $fldnyacri=" left(bpbno,1)='N' "; $mod2="20e"; }

      else 

        { $fldnyacri=" left(bpbno,1) in ('A','F','B') and left(bpbno,2)!='FG' "; $mod2="20e"; }

      ?>

      <div class="box-header">

        <h3 class="box-title">List Pengembalian <?php echo $titlenya; ?></h3>

        <a href='../forms/?mod=<?php echo "20"; ?>&mode=<?php echo $mode; ?>' class='btn btn-primary btn'>

          <i class='fa fa-plus'></i> New

        </a>

      </div>

      <div class='row'>
        <form action="" method="post">

          <div class="box-header">
            <div class='col-md-2'>                            
              <label>From Date (BPB) : </label>
              <input type='text' class='form-control' id='datepicker1' name='frdate' placeholder='Masukkan From Date' value='<?php echo $perf;?>' >
              
            </div>
            <div class='col-md-2'>
              <label>To Date (BPB) : </label>
              <input type='text' class='form-control' id='datepicker2' name='kedate' placeholder='Masukkan To Date' value='<?php echo $pert;?>' >
            </div> 
            <div class='col-md-3'>
              <div>
                <br>
                <button type='submit' name='submit' class='btn btn-primary'>Tampilkan</button>              
              </div>         
            </div>

          </div>
        </form>
      </div>  


      <div class="box-body">

        <table id="examplefix3" class="display responsive" style="width:100%">

          <thead>

            <tr>

              <th>RI #</th>

              <th>Tgl. RI</th>

              <?php if($akses_date=="1") { ?>
                <th>Original BPB Date</th>
              <?php } ?>  

              <th>SJ #</th>

              <th>Pemasok</th>

              <th>No. Invoice</th>

              <th>Jenis BC</th>

              <th>No. Daftar</th>

              <th>Tgl. Daftar</th>

              <th>Created By</th>

              <th>Status</th>

              <th></th>

              <th></th>

            </tr>

          </thead>

          <tbody>

            <?php

          # QUERY TABLE

            if ($mode=="FG") { $tbl_mst="masterstyle"; $fld_desc="s.itemname"; } else { $tbl_mst="masteritem"; $fld_desc="s.itemdesc"; }

            $query = mysql_query("SELECT a.*,s.goods_code,$fld_desc itemdesc,supplier, a.last_date_bpb

              FROM bpb a inner join $tbl_mst s on a.id_item=s.id_item 

              inner join mastersupplier ms on a.id_supplier=ms.id_supplier  

              where $fldnyacri and bppbno_ri!='' and  a.bpbdate >='$tglf' and a.bpbdate <='$tglt' 

              GROUP BY a.bpbno ASC order by bpbdate desc ");

            while($data = mysql_fetch_array($query))

              { $sjint=flookup("bppbno_int","bppb","bppbno='$data[bppbno]'");

            if($sjint=="") { $sjint=$data['bppbno']; }

            if($logo_company=="Z")

            {

              $createby=$data['username'];

            }

            else

            {

              $createby=$data['username']." ".fd_view_dt($data['dateinput']);

            }
            if($data['cancel']=="Y")
            {
              $fontcol="style='color:red;'";
            }
            else
            {
              $fontcol="";
            }
            echo "<tr $fontcol>";

            if($data['bpbno_int']!="")

              { echo "<td>$data[bpbno_int]</td>"; }

            else

              { echo "<td>$data[bpbno]</td>"; }

            echo "

            <td>".fd_view($data[bpbdate])."</td>";
            
            if($akses_date=="1") {
              
              echo "<td>".fd_view($data[last_date_bpb])."</td>";
              
            }
            echo "

            <td>$sjint</td>

            <td>$data[supplier]</td>

            <td>$data[invno]</td>

            <td>$data[jenis_dok]</td>

            <td>$data[bcno]</td>

            <td>$data[bcdate]</td>

            <td>$createby</td>";

            if($data['confirm']=='Y')
            { 
              if($logo_company=="S") { $captses="Confirmed By"; } else { $captses="Sesuai"; }
              echo "
              <td>$captses ".$data['confirm_by']." (".fd_view_dt($data['confirm_date']).")</td>
              <td></td>"; 
            }
            else if($data['cancel']=='Y')
            { 
              if($logo_company=="S") { $captses="Cancelled By"; } else { $captses="Cancelled"; }
              echo "
              <td>$captses ".$data['cancel_by']." (".fd_view_dt($data['cancel_date']).")</td>
              <td></td>"; 
            }
            else
            {
              echo "
              <td></td>
              <td>
              <a href='?mod=$mod2&mode=$mode&noid=$data[bpbno]'

              data-toggle='tooltip' title='$cub'><i class='fa fa-pencil'></i>

              </a>

              <a href='?mod=edit_bpb&bpbno=$data[bpbno]' target='_blank'
              data-toggle='tooltip' title='Edit New'><i class='fa fa-pencil-square-o text-success' aria-hidden='true'></i>
              </a>

              </td>"; 

            }

            if($data['confirm']=='Y')

              { echo "

            <td>

            <a href='cetaksj_ri.php?mode=In&noid=$data[bpbno]' 

            data-toggle='tooltip' title='Cetak'><i class='fa fa-print'></i>

            </a>

            </td>"; 

          }

          else

            { echo "<td></td>"; }

          echo "</tr>";

        }

        ?>

      </tbody>

    </table>

  </div>

</div>

<?php } 

# END COPAS ADD

?>