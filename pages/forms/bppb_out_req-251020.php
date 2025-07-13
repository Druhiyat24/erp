<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }



$mode = $_GET['mode'];

if (isset($_GET['mod'])) { $mod = $_GET['mod']; } else { $mod = ""; } 

$img_err = "'../../images/error.jpg'";

# START CEK HAK AKSES KEMBALI

$akses = flookup("bppb_req","userpassword","username='$user'");

$akses_date = flookup("original_date","userpassword","username='$user'");

if ($akses=="0") 

  { echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }

# END CEK HAK AKSES KEMBALI



$mod = $_GET['mod'];

$nm_tbl="bppb";

$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));

$nm_company = $rscomp["company"];

$st_company = $rscomp["status_company"];

$logo_company = $rscomp["logo_company"];

$jenis_company = $rscomp["jenis_company"];

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

$jono = "";

$bcno = "";

$bcdate = date('d M Y');

$bcaju = "";

$tglaju = date('d M Y');

$bppbdate = date('d M Y');

$status_kb = "";

$txttujuan = "";

$txtsubtujuan = "";

$noreq = "";

$id_jo = "";

$kkbc = "";

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

$id_supplier = flookup("supplier","mastersupplier","id_supplier='$data[id_supplier]'");

$invno = $data['invno'];

$jono = flookup("jo_no","jo","id='$data[id_jo]'");

$bcno = $data['bcno'];

$bcdate = date('d M Y',strtotime($data['bcdate']));

$bcaju = $data['nomor_aju'];

$tglaju = date('d M Y',strtotime($data['tanggal_aju']));

$bppbno = $data['bppbno'];

$bppbdate = date('d M Y',strtotime($data['bppbdate']));

$status_kb = $data['jenis_dok'];

$txttujuan = $data['tujuan'];

$txtsubtujuan = $data['subtujuan'];

$noreq = $data['bppbno_req'];

$id_jo = $data['id_jo'];

$kkbc = $data['nomor_kk_bc'];

}

# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama

echo "<script type='text/javascript'>";

echo "function validasi()";

echo "{";

echo "

var reqno = document.form.txtreqno.value;

var id_supplier = document.form.txtid_supplier.value;

var invno = document.form.txtinvno.value;

var bcno = document.form.txtbcno.value;

var bcdate = document.form.txtbcdate.value;

var status_kb = document.form.txtstatus_kb.value;

var bppbdate = document.form.txtbppbdate.value;

var qtykos = 0;

var qtyover = 0;

var qtyrollover = 0;

var qtyoverall = 0;

var qtys = document.form.getElementsByClassName('qtyoutclass');

var qtybals = document.form.getElementsByClassName('qtysisaclass');

var qtybalsall = document.form.getElementsByClassName('qtysisaallclass');";

if($logo_company=="S")

  { echo "

var qtyrollkos = 0;

var qtyroll = document.getElementsByClassName('totqtyrollclass');

var ketover = 0;

var ketover2 = 0;"; 

}

else

  { echo "var qtyrollkos = 1;"; }

echo "

for (var i = 0; i < qtys.length; i++) 

{ if (qtys[i].value !== '')

{ qtykos = qtykos + 1; }";

if($logo_company=="S")

  { echo "

if (qtys[i].value > 0 && qtyroll[i].value !== '')

{ qtyrollkos = qtyrollkos + 1; }

if (qtys[i].value !== '' && Number(parseFloat(qtys[i].value).toFixed(2)) != Number(parseFloat(qtyroll[i].value).toFixed(2)))

{ qtyrollover = qtyrollover + 1; 

  var ketover = Number(parseFloat(qtys[i].value).toFixed(2));

  var ketover2 = Number(parseFloat(qtyroll[i].value).toFixed(2));

}";

}  

echo "

if (qtys[i].value !== '' && Number(qtys[i].value) > Number(qtybals[i].value))

{ qtyover = qtyover + 1; }

if (qtys[i].value !== '' && Number(qtys[i].value) > Number(qtybalsall[i].value))

{ qtyoverall = qtyoverall + 1; }

}

";



$img_alert = "imageUrl: '../../images/error.jpg'";    

echo "

if (reqno == '') { swal({ title: 'Request # Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}

else if (qtykos == 0) { swal({ title: 'Tidak Ada Data', $img_alert }); valid = false; }

else if (qtyrollkos == 0) { swal({ title: 'Rak Detail Tidak Boleh Kosong', $img_alert }); valid = false; }

else if (qtyover > 0) { swal({ title: 'Qty Tidak Mencukupi Stock / JO', $img_alert }); valid = false; }

else if (qtyrollover > 0) { swal({ title: 'Qty Rak Detail ('+ketover2+') Tidak Sesuai ('+ketover+')', $img_alert }); valid = false; }

else if (qtyoverall > 0) { swal({ title: 'Qty Tidak Mencukupi Stock', $img_alert }); valid = false; }";

echo "else if (id_supplier == '') { swal({ title: 'Dikirim Ke Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";

echo "else if (status_kb == '') { swal({ title: 'Jenis Dokumen Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";

echo "else if (status_kb !== 'INHOUSE' && invno == '') { document.form.txtinvno.focus();swal({ title: 'Nomor Inv/SJ Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";

echo "else if (status_kb !== 'INHOUSE' && bcno == '') { document.form.txtbcno.focus();swal({ title: 'Nomor Daftar Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";

echo "else if (status_kb !== 'INHOUSE' && bcdate == '') { document.form.txtbcdate.focus();swal({ title: 'Tgl. Daftar Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";

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

    url: 'ajax3.php?modeajax=view_list_stock_req',

    data: {id_jo: id_jo},

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

  jQuery.ajax({

    url: 'ajax.php?modeajax=cari_supp_req',

    method: 'POST',

    data: {cri_item: id_jo},

    dataType: 'json',

    success: function(response)

    { $('#cbosupp').val(response[0]);

    $('#txtjono').val(response[1]);  

  },

  error: function (request, status, error) {

    alert(request.responseText);

  },

});

};

function choose_rak(id_jo,id_item)

{ var html = $.ajax

  ({  type: "POST",

    url: 'ajax_bpb_jo.php?modeajax=view_list_rak_loc',

    data: {id_jo: id_jo,id_item: id_item},

    async: false

  }).responseText;

  if(html)

  {  

    $("#detail_rak").html(html);

  }

  $(document).ready(function() {

    var table = $('#examplefix').DataTable

    ({  sorting: false,

      searching: false,

      paging: false,

      fixedColumns:   

      { leftColumns: 1,

        rightColumns: 1

      }

    });
    
        $("input:checkbox").on("change", function () {
            var sum = 0;
            $.each($("input[id='chkajax']:checked"), function(){            
                sum += Number($(this).closest('tr').find('td:nth-child(4)').text());
            });
            $('#total_qty_chk').show();
            $('#total_qty_chk').text(sum);
        });


    });


};

function save_rak()

{ var chkroll = document.getElementsByClassName('chkclass');

var chkqty = document.getElementsByClassName('txtbtsclass');

var chkcri = document.getElementsByClassName('txtcriclass');

var qtydet = document.getElementsByClassName('qtyrollclass');

var totqtydet = document.getElementsByClassName('totqtyrollclass');

var qtydetori = document.getElementsByClassName('qtyrolloriclass');

var qtyrollpil = 0;

var crinya = "";

var crirollnya = "";

for (var i = 0; i < chkroll.length; i++) 

  { if (chkroll[i].checked)

    { 

      qtyrollpil = Number(qtyrollpil) + Number(chkqty[i].value);

      crinya = chkcri[i].value;

      if (crirollnya == '')

        { crirollnya = chkcri[i].value; }

      else

        { crirollnya = crirollnya + "X" + chkcri[i].value; }

    }

  }

  var res = crinya.split("|"); 

  var rescri = res[0]+"|"+res[1];

  for (var i = 0; i < qtydet.length; i++) 

    { if (qtydetori[i].value == rescri)

      {

        qtydet[i].value = crirollnya;

        totqtydet[i].value = qtyrollpil;

      }

    }

  };

</script>

<!-- Modal -->

<div class="modal fade" id="myRak"  tabindex="-1" role="dialog">

  <div class="modal-dialog modal-lg" style="overflow-y:auto;" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">Pilih Detail</h4> 


      </div>

      <div class="modal-body" style="overflow-y:auto; height:500px;">

        <div id='detail_rak'></div>    

      </div>

      <div class="modal-footer">

        <button type="button" class="btn btn-primary" onclick="save_rak()" data-dismiss="modal">Simpan</button>

      </div>

    </div>

  </div>

</div>

<?php

# COPAS ADD

if ($mod=="35" or $mod=="35e")

{

  echo "<div class='box'>";

  echo "<div class='box-body'>";

  echo "<div class='row'>";

  echo "<form method='post' name='form' action='save_data_bppb_out_req.php?mod=$mod&mode=$mode&noid=$bppbno&id=$id_line' onsubmit='return validasi()'>";

  echo "

  <div class='col-md-3'>

  <div class='form-group'>

  <label>Request # *</label>

  <select class='form-control select2' style='width: 100%;' 

  name='txtreqno' id='cboReq' onchange='getJO()'>";

  if($noreq!="") {$fldcri=" where bppbno='$noreq'";} else {$fldcri="";}

  $sql="select a.bppbno isi,concat(a.bppbno,'|',ac.kpno,'|',ac.styleno,'|',mb.supplier) tampil from bppb_req a inner join jo_det s on a.id_jo=s.id_jo 

  inner join so on s.id_so=so.id inner join act_costing ac on so.id_cost=ac.id 

  inner join mastersupplier mb on ac.id_buyer=mb.id_supplier  

  $fldcri group by bppbno";

  IsiCombo($sql,$noreq,'Pilih Request #');

  echo "

  </select>

  </div>

  <div class='form-group'>

  <label>$c35</label>

  <input type='text' class='form-control' name='txtremark' 

  placeholder='$cmas $c35' value='$remark'>

  </div>

  <div class='form-group'>

  <label>$c50</label>

  <input type='text' class='form-control' name='txtnomor_mobil' 

  placeholder='$cmas $c50' value='$nomor_mobil'>

  </div>";

  echo "

  <div class='form-group'>

  <label>$c51 *</label>

  <input type='text' class='form-control' name='txtid_supplier' id='cbosupp' 

  value='$id_supplier' readonly >

  </div>";

  echo "

  <button type='submit' name='submit' class='btn btn-primary'>$csim</button>

  <a href='?mod=$mod&mode=$mode'>Baru</a>";

  echo "</div>";

  echo "<div class='col-md-3'>";

  echo "<div class='form-group'>";

  echo "<label>$c46 *</label>";

  if ($st_company=="KITE") 

    { $status_kb_cri="Status KITE Out"; }

  else if ($st_company=="PLB") 

    { $status_kb_cri="Status PLB Out"; }

  else

    { $status_kb_cri="Status KB Out"; }

  $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan where 

  kode_pilihan='$status_kb_cri' order by nama_pilihan";

  if ($nm_company=="PT. Sinar Gaya Busana") { $callajax=""; } else { $callajax="onchange='getTujuan(this.value)'"; }

  echo "<select class='form-control select2' style='width: 100%;' name='txtstatus_kb'>";

  IsiCombo($sql,$status_kb,$cpil.' '.$c46);

  echo "</select>";

  echo "</div>";

  echo "

  <div class='row'>

  <div class='col-md-6'>

  <div class='form-group'>

  <label>Nomor Aju</label>

  <input type='text' maxlength='6' class='form-control' name='txtbcaju' 

  placeholder='Masukan Nomor Aju' value='$bcaju'>

  </div>

  </div>";

  echo "

  <div class='col-md-6'>

  <div class='form-group'>

  <label>Tanggal Aju</label>

  <input type='text' class='form-control' name='txttglaju' id='datepicker3' 

  placeholder='Masukkan Tgl. Aju' value='$tglaju'>

  </div>

  </div>

  </div>";

  echo "

  <div class='row'>

  <div class='col-md-6'>

  <div class='form-group'>

  <label>$c42 *</label>

  <input type='text' maxlength='6' class='form-control' name='txtbcno' $st_txt_h 

  placeholder='$cmas $c42' value='$bcno'>

  </div>

  </div>";

  echo "

  <div class='col-md-6'>

  <div class='form-group'>

  <label>$c43 *</label>

  <input type='text' class='form-control' name='txtbcdate' $st_txt_tgl_h1 

  placeholder='Masukkan Tgl. Daftar' value='$bcdate'>

  </div>

  </div>

  </div>

  <div class='form-group'>

  <label>Nomor KK</label>

  <input type='text' class='form-control' name='txtkkbc' $st_txt_h 

  placeholder='$cmas KK' value='$kkbc'>

  </div>

  </div>";

  echo "<div class='col-md-3'>";

  echo "<div class='form-group'>";

  echo "<label>JO #</label>";

  echo "<input type='text' class='form-control' name='txtjono' id='txtjono' readonly value='$jono'>";

  echo "</div>";

  echo "<div class='form-group'>";

  echo "<label>$c41 *</label>";

  echo "<input type='text' class='form-control' name='txtinvno' $st_txt_h placeholder='$cmas $c41' value='$invno'>";

  echo "</div>";

  echo "

  <div class='row'>

  <div class='col-md-6'>

  <div class='form-group'>";

  if ($mod=="61r")

    { echo "<label>Nomor Request *</label>"; }

  else

    { echo "<label>Nomor BPPB *</label>"; }

  echo "<input type='text' class='form-control' name='txtbppbno' readonly 

  placeholder='$cmas Nomor BPPB' value='$bppbno'>

  </div>

  </div>";

  echo "

  <div class='col-md-6'>

  <div class='form-group'>

  <label>Tgl BPPB *</label>

  <input type='text' class='form-control' name='txtbppbdate' onchange='getSat(this.value)' $st_txt_tgl_h 

  placeholder='$cmas Tanggal BPPB' value='$bppbdate'>

  </div>

  </div>

  </div>";

  echo "</div>";

  ?>

  <div class='box-body'>

    <?php if($mod=="35e") 

    {	echo "<table id='examplefix2' style='width: 100%;'>";

    echo "

    <thead>

    <tr>";

    if($jenis_company=="VENDOR LG")

      { echo "<th>JO #</th>"; }

    else

      { echo "<th>WS #</th>"; }

    echo "

    <th>Style #</th>

    <th>Buyer</th>

    <th>Kode Bahan Baku</th>

    <th>Deskripsi</th>

    <th>Nomor Rak</th>

    <th>Stock</th>

    <th>Unit</th>

    <th>Qty Out</th>

    </tr>

    </thead>

    <tbody>";

    $id_jo = flookup("group_concat(distinct id_jo)","bppb","bppbno='$bppbno'");

    $sql="select breq.nomor_rak,breq.id line_item,breq.id_supplier,breq.qty qtyout,mi.goods_code,mi.itemdesc,tbl_in.id_item,tbl_in.id_jo,tbl_in.qty_in,

    if(tbl_out.qty_out is null,0,tbl_out.qty_out) qty_out,

    tbl_in.unit,

    ac.kpno,ac.styleno,mbuyer.supplier buyer,jo_no

    from 

    bppb breq inner join masteritem mi on mi.id_item=breq.id_item inner join 

    (select id_item,id_jo,sum(qty) qty_in,unit from bpb where id_jo in ($id_jo) 

    group by id_item,id_jo) as tbl_in 

    on mi.id_item=tbl_in.id_item and breq.id_jo=tbl_in.id_jo

    left join 

    (select id_item,id_jo,sum(qty) qty_out from bppb where id_jo in ($id_jo) 

    group by id_item,id_jo) as tbl_out

    on tbl_in.id_item=tbl_out.id_item and tbl_in.id_jo=tbl_out.id_jo

    INNER JOIN 

    (select id_so,id_jo,jo_no from jo_det jod inner join jo on jod.id_jo=jo.id 

    where id_jo in ($id_jo) group by id_jo)  

    jod on breq.id_jo=jod.id_jo 

    inner join (select so.id,id_cost,min(sod.deldate_det) mindeldate from so inner join so_det sod on so.id=sod.id_so group by so.id) so on jod.id_so=so.id 

    inner join act_costing ac on so.id_cost=ac.id

    inner join mastersupplier mbuyer on ac.id_buyer=mbuyer.id_supplier

    where breq.bppbno='$bppbno'";

							#echo $sql;

    $i=1;

    $query=mysql_query($sql);

    while($data=mysql_fetch_array($query))

     {	$id=$data['id_item'];

   $sisa = ($data['qty_in'] - $data['qty_out']) + $data['qtyout'];

   echo "

   <tr>";

   if($jenis_company=="VENDOR LG")

    { echo "<td>$data[jo_no]</td>"; }

  else

    { echo "<td>$data[kpno]</td>"; }

  echo "

  <td>$data[styleno]</td>

  <td>$data[buyer]</td>

  <td>$data[goods_code]</td>

  <td>$data[itemdesc]</td>

  <td>$data[nomor_rak]</td>

  <td><input type ='text' size='4' name ='qtysisa[$id]' value='$sisa' id='qtysisa$i' class='qtysisaclass' readonly></td>

  <td>

  <input type ='text' size='4' name ='unitsisa[$id]' value='$data[unit]' id='unitsisa$i' readonly>

  </td>

  <td>

  <input type ='text' size='4' name ='qtyout[$id]' id='qtyout$i' class='qtyoutclass' value='$data[qtyout]'>

  <input type ='hidden' name ='jono[$id]' id='jono$i' class='jonoclass' value='$id_jo'>

  <input type ='hidden' name ='id_supp[$id]' id='id_supp$i' class='id_suppclass' value='$data[id_supplier]'>

  <input type ='hidden' name ='line_item[$id]' id='line_item$i' class='line_itemclass' value='$data[line_item]'>

  </td>

  </tr>";

  $i++;

};

echo "</tbody></table>"; 

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

if ($mod=="35v")

{

  ?>

  <div class="box">

    <?php 

    if ($mode=="FG")

      { $fldnyacri=" mid(bppbno,4,2)='FG' "; $mod2=65; }

    else if ($mode=="Mesin")

      { $fldnyacri=" mid(bppbno,4,1)='M' "; $mod2=63; }

    else if ($mode=="General")

      { $fldnyacri=" mid(bppbno,4,1)='N' "; $mod2=63; }

    else if ($mode=="Scrap")

      { $fldnyacri=" mid(bppbno,4,1) in ('S','L') "; $mod2=62; }

    else if ($mode=="WIP")

      { $fldnyacri=" mid(bppbno,4,1)='C' "; $mod2=64; }

    else 

      { $fldnyacri=" mid(bppbno,4,1) in ('A','F','B') and mid(bppbno,4,2)!='FG' "; $mod2=35; }

    ?>

    <div class="box-header">

      <h3 class="box-title">List Pengeluaran <?PHP echo $titlenya; ?></h3>

      <a href='../forms/?mod=<?php echo $mod2; ?>&mode=<?php echo $mode; ?>' class='btn btn-primary btn-s'>

        <i class='fa fa-plus'></i> New

      </a>

    </div>

    <div class="box-body">

      <table id="examplefix3" class="display responsive" style="width:100%;font-size:10px;">

        <thead>

          <tr>

            <th>Nomor BPPB</th>

            <th>Tanggal BPPB</th> 

            <?php if($akses_date=="1") { ?>

            <th>Original BPPB Date</th>

            <?php } ?>           

            <th>Nomor Req</th>

            <th>Buyer</th>

            <th>Style #</th>

            <?php if($jenis_company=="VENDOR LG") { ?>

             <th>JO #</th>

           <?php } else { ?>

             <th>WS #</th>

           <?php } ?>



           <th>Penerima</th>

           <th>No. Invoice</th>

           <th>No. Dokumen</th>

           <th>Tgl. Dokumen</th>

           <th>Jenis BC</th>

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

        $sql="SELECT a.bppbno_int,a.bppbno,a.bppbno_req,a.bppbdate, a.dateinput,

        a.invno,a.bcno,a.bcdate,a.jenis_dok,s.goods_code,$fld_desc itemdesc,ms.supplier,mb.supplier buyer,

        ac.styleno,ac.kpno,a.username,group_concat(distinct concat(' ',jo.jo_no)) jo_nya,

        a.confirm_by, a.confirm_date, a.confirm,a.cancel_by, a.cancel_date, a.cancel, a.last_date_bppb

        FROM bppb a inner join $tbl_mst s on a.id_item=s.id_item

        inner join mastersupplier ms on a.id_supplier=ms.id_supplier 

        inner join jo_det jod on a.id_jo=jod.id_jo 

        inner join jo on jod.id_jo=jo.id  

        inner join so on jod.id_so=so.id 

        inner join act_costing ac on so.id_cost=ac.id 

        inner join mastersupplier mb on ac.id_buyer=mb.id_supplier  

        where $fldnyacri and bppbno_req!='' 

        GROUP BY a.bppbno ASC order by bppbdate desc /* limit 1000 */";

        $query = mysql_query($sql);

        #echo $sql;

        while($data = mysql_fetch_array($query))
        { 
          if($data['cancel']=="Y")
          {
            $fontcol="style='color:red;'";
          }
          else
          {
            $fontcol="";
          }
          echo "<tr $fontcol>";

        if($data['bppbno_int']!="")

          { echo "<td>$data[bppbno_int]</td>"; }

        else

          { echo "<td>$data[bppbno]</td>"; }

        echo "

        <td>".fd_view($data[bppbdate])."</td>";
		if($akses_date=="1") {
        echo "<td>".fd_view($data[last_date_bppb])."</td>";
		}
		echo "
        <td>$data[bppbno_req]</td>

        <td>$data[buyer]</td>

        <td>$data[styleno]</td>";

        if($jenis_company=="VENDOR LG")

          {	echo "<td>$data[jo_nya]</td>"; }

        else

         {	echo "<td>$data[kpno]</td>"; }

       echo "

       <td>$data[supplier]</td>

       <td>$data[invno]</td>

       <td>$data[bcno]</td>

       <td>".fd_view($data[bcdate])."</td>

       <td>$data[jenis_dok]</td>

       <td>$data[username] ($data[dateinput])</td>";

       if($data['confirm'] == 'N' and $data['cancel'] == 'N') {  
        echo "<td>&nbsp;</td>
        <td>
          <a href='?mod=35e&mode=$mode&noid=$data[bppbno]'
            data-toggle='tooltip' title='$cub'><i class='fa fa-pencil'></i>
          </a>
        </td>";
      } 
      else if($data['cancel']=="Y") 
      {
        $reason=flookup("reason","cancel_trans","trans_no='$data[bppbno]'");
        $captses="Cancelled By ".$data['cancel_by']." (".fd_view_dt($data['cancel_date']).") Reason ".$reason;
        echo "<td>$captses</td>
        <td></td>";
      }
      else 
      {
        echo "<td>Confirmed by: $data[confirm_by] ($data[confirm_date])</td><td></td>";
      }



      if ($print_sj=="1")

        { if($logo_company=="Z")

      { $filepdf="cetaksj.php"; }

      else

        { $filepdf="pdfDO_.php"; }

      echo "

      <td>

      <a href='$filepdf?mode=Out&noid=$data[bppbno]' 

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