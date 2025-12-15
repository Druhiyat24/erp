<?php 

if (empty($_SESSION['username'])) { header("location:../../index.php"); }



$mode = $_GET['mode'];

if (isset($_GET['mod'])) { $mod = $_GET['mod']; } else { $mod = ""; } 

$img_err = "'../../images/error.jpg'";

# START CEK HAK AKSES KEMBALI

$akses = flookup("mnuBPBScrap_SJ","userpassword","username='$user'");

$akses_date = flookup("original_date","userpassword","username='$user'");

if ($akses=="0") 

{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }

# END CEK HAK AKSES KEMBALI



$mod = $_GET['mod'];

$nm_tbl="bppb";

$nm_company = flookup("company","mastercompany","company!=''");

$st_company = flookup("status_company","mastercompany","company!=''");

$c_nom_order=$capt_no_ord;

if ($nm_company=="PT. Gaya Indah Kharisma")

{ $sql="update masteritem set matclass=mattype where matclass='-'"; 

  insert_log($sql,$user);

}

if (isset($_GET['noid'])) {$bppbno = $_GET['noid']; } else {$bppbno = "";}

if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

if (isset($_GET['id'])) {$id_line = $_GET['id']; } else {$id_line = "";}

if (isset($_GET['kp'])) {$kpno = $_GET['kp']; } else {$kpno = "";}



if ($bppbno=="")

{ $st_txt_tgl_h = "id='datepicker2'"; $st_txt_tgl_h1 = "id='datepicker1'"; $st_txt_h = ""; }

else if ($bppbno!="" AND $id_item=="")

{ $st_txt_tgl_h = "readonly"; $st_txt_tgl_h1 = "readonly"; $st_txt_h = "readonly"; }

else

{ $st_txt_tgl_h = "id='datepicker2'"; $st_txt_tgl_h1 = "id='datepicker1'"; $st_txt_h = ""; }

          

if ($mode=="Bahan_Baku") 

{ if ($st_company=="GB" OR $st_company=="MULTI_WHS")

  { $titlenya="Barang"; }

  else

  { $titlenya=$c5; }

  $filternya="a.mattype in ('A','F','B')";

}

else if ($mode=="Scrap") 

{ $titlenya="Scrap"; 

  $filternya="a.mattype in ('S','L')";

}

else if ($mode=="General") 

{ $titlenya="Item General"; 

  $filternya="a.mattype in ('N')";

}

else if ($mode=="Mesin") 

{ $titlenya=$caption[1]; 

  $filternya="a.mattype in ('M')";

}

else if ($mode=="WIP") 

{ if ($nm_company=="PT. Youngil Leather Indonesia") { $titlenya="Chemical"; } else { $titlenya="WIP"; } 

  $filternya="a.mattype in ('C')";

}

else if ($mode=="FG") 

{ $titlenya="Barang Jadi"; 

  $filternya=" ";

}

else

{ echo "<script>

    alert('Terjadi kesalahan');

    window.location.href='index.php';

  </script>";

}

# COPAS EDIT

if ($bppbno=="" AND $id_item=="")

{ $id_item = "";

  $qty = "";

  $unit = "";

  $curr = "";

  $price = "";

  $remark = "";

  $nomor_rak = "";

  $kpno = "";

  $berat_bersih = "";

  $berat_kotor = "";

  $nomor_mobil = "";

  $id_supplier = "";

  $invno = "";

  $bcno = "";

  $bcdate = date('d M Y');

  $bcaju = "";

  $tglaju = date('d M Y');

  $bppbdate = date('d M Y');

  $status_kb = "";

  $txttujuan = "";

  $txtsubtujuan = "";

}

else if ($bppbno<>"" AND $id_item=="")

{ $id_item = "";

  $qty = "";

  $unit = "";

  $curr = "";

  $price = "";

  $remark = "";

  $nomor_rak = "";

  $kpno = "";

  $berat_bersih = "";

  $berat_kotor = "";

  $query = mysql_query("SELECT * FROM $nm_tbl where bppbno='$bppbno' ORDER BY id_item ASC");

  $data = mysql_fetch_array($query);

  $nomor_mobil = $data['nomor_mobil'];

  $id_supplier = $data['id_supplier'];

  $invno = $data['invno'];

  $bcno = $data['bcno'];

  $bcdate = date('d M Y',strtotime($data['bcdate']));

  $bcaju = $data['nomor_aju'];

  $tglaju = date('d M Y',strtotime($data['tanggal_aju']));

  $bppbno = $data['bppbno'];

  $bppbdate = date('d M Y',strtotime($data['bppbdate']));

  $status_kb = $data['jenis_dok'];

  $txttujuan = $data['tujuan'];

  $txtsubtujuan = $data['subtujuan'];

}

else

{ $query = mysql_query("SELECT * FROM $nm_tbl where bppbno='$bppbno' 

    and id='$id_item' ORDER BY id_item ASC");

  $data = mysql_fetch_array($query);

  $id_item = $data['id_item'];

  $qty = $data['qty'];

  $unit = $data['unit'];

  $curr = $data['curr'];

  $price = $data['price'];

  $remark = $data['remark'];

  $nomor_rak = $data['nomor_rak'];

  $kpno = $data['kpno'];

  $berat_bersih = $data['berat_bersih'];

  $berat_kotor = $data['berat_kotor'];

  $nomor_mobil = $data['nomor_mobil'];

  $id_supplier = $data['id_supplier'];

  $invno = $data['invno'];

  $bcno = $data['bcno'];

  $bcdate = date('d M Y',strtotime($data['bcdate']));

  $bcaju = $data['nomor_aju'];

  $tglaju = date('d M Y',strtotime($data['tanggal_aju']));

  $bppbno = $data['bppbno'];

  $bppbdate = date('d M Y',strtotime($data['bppbdate']));

  $status_kb = $data['jenis_dok'];

  $txttujuan = $data['tujuan'];

  $txtsubtujuan = $data['subtujuan'];

}

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



$rsTGPER=mysql_fetch_array(mysql_query("select tgl1,tgl2 from tptglperiode where gudang='FABRIC' and stat<>'Z'"));

$dtper1 = date('Y-m-d',strtotime($rsTGPER["tgl1"]));
$dtper2 = date('Y-m-d',strtotime($rsTGPER["tgl2"]));

# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama

echo "<script type='text/javascript'>";

  echo "function validasi()";

  echo "{";

    echo "


    var dtperiode1 = '$dtper1';
    var dtperiode2 = '$dtper2';    

    var reqno = document.form.txtreqno.value;

    var id_supplier = document.form.txtid_supplier.value;

    var invno = document.form.txtinvno.value;

    var bcno = document.form.txtbcno.value;

    var bcdate = document.form.txtbcdate.value;

    var status_kb = document.form.txtstatus_kb.value;

    var bppbdate = document.form.txtbppbdate.value;

    var qtykos = 0;

    var uomkos = 0;

    var itmkos = 0;

    var qtys = document.form.getElementsByClassName('qtysc');

    var uoms = document.form.getElementsByClassName('unitsc');

    var itms = document.form.getElementsByClassName('itemsc');



    for (var i = 0; i < qtys.length; i++) 

    { if (qtys[i].value !== '')

      { qtykos = qtykos + 1; }

      if (qtys[i].value !== '' && uoms[i].value !== '')

      { uomkos = uomkos + 1; }

      if (qtys[i].value !== '' && itms[i].value !== '')

      { itmkos = itmkos + 1; }

    }

    ";

    

    $img_alert = "imageUrl: '../../images/error.jpg'";    

    echo "if (reqno == '') { swal({ title: 'SJ # Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";

    echo "else if (qtykos == 0) { swal({ title: 'Tidak Ada Data', $img_alert }); valid = false; }";

    echo "else if (itmkos == 0) { swal({ title: 'Item Scrap Kosong', $img_alert }); valid = false; }";

    echo "else if (uomkos == 0) { swal({ title: 'Unit Kosong', $img_alert }); valid = false; }";

    echo "else if (invno == '') { document.form.txtinvno.focus();swal({ title: 'Nomor Inv/SJ Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";

    echo "else if (id_supplier == '') { swal({ title: 'Diterima Dari Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";

    echo "else if (status_kb == '') { swal({ title: 'Jenis Dokumen Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";

    echo "else if (status_kb !== 'INHOUSE' && bcno == '') { document.form.txtbcno.focus();swal({ title: 'Nomor Daftar Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";

    echo "else if (bcdate == '') { document.form.txtbcdate.focus();swal({ title: 'Tgl. Daftar Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";

    echo "else if (bppbdate == '') { document.form.txtbppbdate.focus();swal({ title: 'Tgl. BKB Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";

    echo "else if (new Date(bppbdate) > new Date()) 

      { document.form.txtbppbdate.focus();valid = false;

        swal({ title: 'Tgl. Transaksi Tidak Boleh Melebihi Hari Ini', $img_alert });

      }";

    echo "else valid = true;";

    echo "return valid;";

    echo "exit;";

  echo "}";

echo "</script>";

# END COPAS VALIDASI

?>

<script type="text/javascript">

  function getJO()

  { var id_jo = $('#cboReq').val();

    var html = $.ajax

    ({  type: "POST",

        url: 'ajax4.php?modeajax=view_list_sc',

        data: {id_jo: id_jo},

        async: false

    }).responseText;

    if(html)

    {  

        $("#detail_item").html(html);

    }

    jQuery.ajax({

        url: 'ajax.php?modeajax=cari_supp_sc',

        method: 'POST',

        data: {cri_item: id_jo},

        success: function(response){

            jQuery('#cbosupp').val(response);  

        },

        error: function (request, status, error) {

            alert(request.responseText);

        },

    });

  };

</script>

<?PHP

# COPAS ADD

if ($mod=="36")

{

echo "<div class='box'>";

  echo "<div class='box-body'>";

    echo "<div class='row'>";

      echo "<form method='post' name='form' action='save_data_bpb_sc_sj.php?mod=$mod&mode=$mode&noid=$bppbno&id=$id_line' onsubmit='return validasi()'>";

        echo "<div class='col-md-3'>";

          echo "<div class='form-group'>";

            echo "<label>SJ # *</label>";

            echo "<select class='form-control select2' style='width: 100%;' 

              name='txtreqno' id='cboReq' onchange='getJO()'>";

            $sql="select a.bppbno isi,group_concat(distinct concat(a.bppbno_int,' ',a.bppbno,' ',jo_no,' ',ac.styleno,' ',msup.supplier)) tampil from 

              bppb a inner join jo_det jod on a.id_jo=jod.id_jo 

              inner join jo on jod.id_jo=jo.id 

              inner join so on jod.id_so=so.id 

              inner join act_costing ac on ac.id=so.id_cost 

              left join mastersupplier msup on ac.id_buyer=msup.id_supplier where 

              mid(bppbno,4,2)!='FG' and mid(bppbno,4,1) in ('A','F') 

              and right(bppbno,1)!='R' and a.id_jo!='' and bppbdate >= '2021-01-01' group by bppbno";

            IsiCombo($sql,'','Pilih SJ #');

            echo "</select>";

          echo "</div>";

          echo "<div class='form-group'>";

            echo "<label>$c35</label>";

            echo "<input type='text' class='form-control' name='txtremark' placeholder='$cmas $c35' value='$remark'>";

          echo "</div>";

          echo "<div class='form-group'>";

            echo "<label>$c50</label>";

            echo "<input type='text' class='form-control' name='txtnomor_mobil' placeholder='$cmas $c50' value='$nomor_mobil'>";

          echo "</div>";

          echo "<div class='form-group'>";

            echo "<label>Diterima Dari *</label>";

            echo "<input type='text' class='form-control' name='txtid_supplier' id='cbosupp' readonly >";

          echo "</div>";

          echo "

            <button type='submit' name='submit' class='btn btn-primary'>$csim</button>

        </div>";

        echo "<div class='col-md-3'>";

          echo "<div class='form-group'>";

            echo "<label>$c41 *</label>";

            echo "<input type='text' class='form-control' name='txtinvno' $st_txt_h placeholder='$cmas $c41' value='$invno'>";

          echo "</div>";

          echo "<div class='form-group'>";

            echo "<label>$c46 *</label>";

            if ($st_company=="KITE") 

            { $status_kb_cri="Status KITE In"; }

            else if ($st_company=="PLB") 

            { $status_kb_cri="Status PLB In"; }

            else

            { $status_kb_cri="Status KB In"; }

            $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan where 

                  kode_pilihan='$status_kb_cri' order by nama_pilihan";

            if ($nm_company=="PT. Sinar Gaya Busana") { $callajax=""; } else { $callajax="onchange='getTujuan(this.value)'"; }

            echo "<select class='form-control select2' style='width: 100%;' name='txtstatus_kb'>";

            IsiCombo($sql,$status_kb,$cpil.' '.$c46);

            echo "</select>";

          echo "</div>";

          echo "<div class='form-group'>";

            echo "<label>Nomor Aju</label>";

            echo "<input type='text' class='form-control' name='txtbcaju' placeholder='Masukan Nomor Aju' value='$bcaju'>";

          echo "</div>";

          echo "<div class='form-group'>";

            echo "<label>Tanggal Aju</label>";

            echo "<input type='text' class='form-control' name='txttglaju' id='datepicker3' placeholder='Masukkan Tgl. Aju' value='$tglaju'>";

          echo "</div>";

        echo "</div>";

        echo "<div class='col-md-3'>";

          echo "<div class='form-group'>";

            if ($mod=="61r")

            { echo "<label>Nomor Request *</label>"; }

            else

            { echo "<label>$c52 *</label>"; }

            echo "<input type='text' class='form-control' name='txtbppbno' readonly placeholder='$cmas $c52' value='$bppbno'>";

          echo "</div>";

          echo "<div class='form-group'>";

            echo "<label>$c53 *</label>";

            echo "<input type='text' class='form-control' name='txtbppbdate' onchange='getSat(this.value)' $st_txt_tgl_h placeholder='$cmas $c53' value='$bppbdate'>";

          echo "</div>";

          echo "<div class='form-group'>";

            echo "<label>$c42 *</label>";

            echo "<input type='text' class='form-control' name='txtbcno' $st_txt_h placeholder='$cmas $c42' value='$bcno'>";

          echo "</div>";

          echo "<div class='form-group'>";

            echo "<label>$c43 *</label>";

            echo "<input type='text' class='form-control' name='txtbcdate' $st_txt_tgl_h1 placeholder='Masukkan Tgl. Daftar' value='$bcdate'>";

          echo "</div>";          

        echo "</div>";

        echo "<div class='col-md-3'>";

          echo "<div class='form-group'>";

            echo "<label>Jenis Pemasukan *</label>";

  echo "                <select class='form-control select2' style='width: 100%;' name='txtjns_in' required>";


                  if ($mode=="Scrap")
                  {

                    $sqljns_in = "select nama_trans isi,nama_trans tampil from mastertransaksi where 

                          jenis_trans='IN' and jns_gudang = 'SCRAP' order by id";

                    IsiCombo($sqljns_in,'','Pilih Jenis Pemasukan');    

                  } 
                  else
                  {

                    $sqljns_in = "select nama_trans isi,nama_trans tampil from mastertransaksi where 

                          jenis_trans='IN' and jns_gudang = 'FACC' order by id";

                    IsiCombo($sqljns_in,'','Pilih Jenis Pemasukan');    

                  } 

  
  echo  "                </select>"; 

          echo "</div>";          

        echo "</div>";        

        ?>

        <div class='box-body'>

          <?php if($mod=="61re") 

          { 

          } 

          else 

          { ?>

            <div id='detail_item'></div>

          <?php } ?>

          </div>

        <?php

      echo "</form>";

    echo "</div>";

  echo "</div>";

echo "</div>";

}

# END COPAS ADD

if ($mod=="36v")

{

?>

<div class="box">

  <?php 

  if ($mode=="Scrap")

  { $fldnyacri=" left(bpbno,1) in ('S','L') "; $mod2=36; }

  else 

  { $fldnyacri=" mid(bppbno,4,1) in ('A','F','B') and mid(bppbno,4,2)!='FG' "; $mod2=36; }

  ?>

  <div class="box-header">

    <h3 class="box-title">List Pemasukan <?php echo $titlenya; ?></h3>

    <a href='../forms/?mod=<?php echo $mod2; ?>&mode=<?php echo $mode; ?>' class='btn btn-primary btn-s'>

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

          <th>Nomor BPB</th>

          <th>Nomor SJ</th>

          <th>Tanggal BPB</th>

          <?php if($akses_date=="1") { ?>
          <th>Original BPB Date</th>
          <?php } ?>  

          <th>Dari</th>

          <th>No. Invoice</th>

          <th>No. Dokumen</th>

          <th>Jenis BC</th>

          <th>Jenis Trans</th>

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

        $sql="SELECT a.*,s.goods_code,$fld_desc itemdesc,supplier, a.last_date_bpb, a.confirm_by, a.confirm_date 

          FROM bpb a inner join $tbl_mst s on a.id_item=s.id_item

          inner join mastersupplier ms on a.id_supplier=ms.id_supplier 

          where $fldnyacri and a.bpbdate >= '$tglf' and a.bpbdate <= '$tglt'

          GROUP BY a.bpbno ASC order by bpbdate desc limit 1000";

        $query = mysql_query($sql);

        #echo $sql;

        while($data = mysql_fetch_array($query))

        { echo "<tr>";

          if($data['bpbno_int']!="")

          { echo "<td>$data[bpbno_int]</td>"; }

          else

          { echo "<td>$data[bpbno]</td>"; }

          echo "

            <td>$data[bppbno]</td>

            <td>$data[bpbdate]</td>";

            if($akses_date=="1") {
            echo "<td>".fd_view($data['last_date_bpb'])."</td>";
            } 

            echo "<td>$data[supplier]</td>

            <td>$data[invno]</td>

            <td>$data[bcno]</td>

            <td>$data[jenis_dok]</td>

            <td>$data[jenis_trans]</td>

            <td>$data[username] $data[dateinput]</td>
			
            <td>$data[confirm_by] $data[confirm_date]</td>

            <td>

              <a href='?mod=36e&mode=$mode&noid=$data[bpbno]'

                data-toggle='tooltip' title='$cub'><i class='fa fa-pencil'></i>

              </a>

            </td>"; 

            if ($print_sj=="1")

            { echo "

              <td>

                <a href='cetaksj.php?mode=In&noid=$data[bpbno]' 

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

<?php } else { ?>



<?php } ?>