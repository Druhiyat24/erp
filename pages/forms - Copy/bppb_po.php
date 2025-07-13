<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$mode = $_GET['mode'];
if (isset($_GET['mod'])) { $mod = $_GET['mod']; } else { $mod = ""; } 
$img_err = "'../../images/error.jpg'";
# START CEK HAK AKSES KEMBALI
if($mode=="WIP")
{
  $akses = flookup("mnuBPPBWIP_JO","userpassword","username='$user'");
}
else
{
  $akses = flookup("mnuBPPBScrap_JO","userpassword","username='$user'");
}

$akses_date = flookup("original_date","userpassword","username='$user'");

if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI

$mod = $_GET['mod'];
$nm_tbl="bppb";
$rscomp=mysqli_fetch_array(mysqli_query($con_new,"select * from mastercompany"));
  $nm_company = $rscomp["company"];
  $st_company = $rscomp["status_company"];
  $jenis_company = $rscomp["jenis_company"];
  $logo_company = $rscomp["logo_company"];
$c_nom_order=$capt_no_ord;
if ($nm_company=="PT. Gaya Indah Kharisma")
{ $sql="update masteritem set matclass=mattype where matclass='-'"; 
  insert_log($sql,$user);
}
if (isset($_GET['noid'])) {$bppbno = $_GET['noid']; } else {$bppbno = "";}
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}
if (isset($_GET['id'])) {$id_line = $_GET['id']; } else {$id_line = "";}
if (isset($_GET['kp'])) {$kpno = $_GET['kp']; } else {$kpno = "";}

if (($bppbno!="" AND $id_item=="") or $bppbno=="")
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
  $last_date_bppb = date('d M Y');
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
  $query = mysqli_query($con_new,"SELECT * FROM $nm_tbl where bppbno='$bppbno' ORDER BY id_item ASC");
  $data = mysqli_fetch_array($query);
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
  $last_date_bppb = date('d M Y',strtotime($data['last_date_bppb']));
}
else
{ $query = mysqli_query($con_new,"SELECT * FROM $nm_tbl where bppbno='$bppbno' 
    and id='$id_item' ORDER BY id_item ASC");
  $data = mysqli_fetch_array($query);
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
  $last_date_bppb = date('d M Y',strtotime($data['last_date_bppb']));
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>";
  echo "function validasi()";
  echo "{";
    echo "

    var id_supplier = document.form.txtid_supplier.value;
    var invno = document.form.txtinvno.value;
    var bcno = document.form.txtbcno.value;
    var bcdate = document.form.txtbcdate.value;
    var status_kb = document.form.txtstatus_kb.value;
    var bppbdate = document.form.txtbppbdate.value;
    var qtykos = 0;
    var qtyover = 0;
    var qtys = document.form.getElementsByClassName('qtyoutclass');
    var qtybts = document.form.getElementsByClassName('qtysisaclass');

    for (var i = 0; i < qtys.length; i++) 
    { if (qtys[i].value !== '')
      { qtykos = qtykos + 1; }
    }
    for (var i = 0; i < qtys.length; i++) 
    { if (qtys[i].value != '')
      {
        if (Number(qtys[i].value) > Number(qtybts[i].value))
        { qtyover = Number(qtyover) + 1; }
      }
    }";
    
    $img_alert = "imageUrl: '../../images/error.jpg'";    
    echo "if (reqno == '') { swal({ title: 'JO # Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";
    echo "else if (qtykos == 0) { swal({ title: 'Tidak Ada Data', $img_alert }); valid = false; }";
    echo "else if (qtyover > 0) { swal({ title: 'Stock Tidak Cukup', $img_alert }); valid = false; }";
    echo "else if (id_supplier == '') { swal({ title: 'Dikirim Ke Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";
    echo "else if (invno == '') { document.form.txtinvno.focus();swal({ title: 'Nomor Inv/SJ Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";
    echo "else if (status_kb == '') { swal({ title: 'Jenis Dokumen Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";
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
  <?php if ($nm_company!="PT. Sinar Gaya Busana") { ?>
    function getTujuan(cri_item)
    {   var html = $.ajax
        ({  type: "POST",
            url: 'ajax.php?modeajax=cari_tujuan',
            data: "cri_item=" +cri_item,
            async: false
        }).responseText;
        if(html)
          { $("#cbotujuan").html(html); }
    }
  <?php } ?>
  function getJO()
  { var id_jo = $('#cboReq').val();
    <?php
    if($mode=="WIP")
    { echo "var jenismat = 'WIP';"; }
    else
    { echo "var jenismat = 'Scrap';"; }
    ?>
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax5.php?modeajax=view_list_stock_sc_jo',
        data: {id_jo: id_jo, jenismat: jenismat},
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
        success: function(response){
            jQuery('#cbosupp').val(response);  
        },
        error: function (request, status, error) {
            alert(request.responseText);
        },
    });
  };
  
 
  function getListKPNo(cri_item)
  { 
  
/*   		alert("123");
		return false; */
  var nama_sup = document.form.txtid_supplier.value;

    var html = $.ajax

    ({  type: "POST",

        <?php 

        if($mode=="General")

        { echo "url: 'ajax2.php?modeajax=cari_list_po_gen',"; }

        else if($mode=="WIP")

        { echo "url: 'ajax2.php?modeajax=cari_list_po_wip',"; }

        else

        { echo "url: 'ajax2.php?modeajax=cari_list_po',"; }

        ?>

        data: {nama_sup: nama_sup},

        async: false

    }).responseText;

    if(html)

    { $("#cbopo").html(html); }

  }; 
  
  
  function getListData(cri_item)

  { jQuery.ajax

/*     ({  url: 'ajax3.php?modeajax=copy_po',

        method: 'POST',

        data: {cri_item: cri_item}, 

        success: function(response){

          jQuery('#txtpono').val(response);  

        },

        error: function (request, status, error) 

        { alert(request.responseText); },

    }); */

/*     var html = $.ajax

    ({  type: "POST",

        url: 'ajax3.php?modeajax=cari_sup',

        data: "cri_item=" +cri_item,

        async: false

    }).responseText;

    if(html)

    { $("#cbosup").html(html); } */

    var html = $.ajax

    ({  type: "POST",

        url: 'ajax3_bppb_po.php?modeajax=view_list_po',

        data: "cri_item=" +cri_item,

        async: false

    }).responseText;

    if(html)

    { $("#detail_item").html(html); }

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
<?PHP
# COPAS ADD
if ($mod=="37_bppb_po")
{
	echo "<br/><br/><br/>";
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='save_sc_po.php?mod=$mod&mode=$mode&noid=$bppbno&id=$id_line' onsubmit='return validasi()'>";
        echo "<div class='col-md-3'>";
		
          echo "<div class='form-group'>";
            echo "<label>$c51 *</label>";
            $sql = "select id_supplier isi,supplier tampil from mastersupplier order by supplier";
            echo "<select class='form-control select2' style='width: 100%;' name='txtid_supplier' onchange='getListKPNo(this.value)'>";
            IsiCombo($sql,'',$cpil.' Dikirim Ke');
            echo "</select>";
          echo "</div>";		
		
		echo "
		                  <div class='form-group'>

                    <label>Nomor PO *</label>

                    <select class='form-control select2' style='width: 100%;' name='cbopo' id='cbopo' 

                      onchange='getListData(this.value)'>

                    </select>

                  </div>
		";
		
/*           echo "<div class='form-group'>";
            echo "<label>JO # *</label>";
            echo "<select class='form-control select2' multiple='multiple' style='width: 100%;' 
              name='txtreqno' id='cboReq' onchange='getJO()'>"; 
            if($mode=="Scrap")
            { $crimat="left(bpbno,1) in ('S','L')"; }
            else
            { $crimat="left(bpbno,1) in ('C')"; }
            $sql="select s.id isi,concat(s.jo_no,'|',ac.styleno,'|',ac.kpno) tampil 
              from bpb a inner join jo s on a.id_jo=s.id inner join jo_det jod on s.id=jod.id_jo 
              inner join  so on jod.id_so=so.id inner join act_costing ac on so.id_cost=ac.id 
              where $crimat group by s.id";
            #echo $sql;
            IsiCombo($sql,'','');
            echo "</select>";
          echo "</div>"; */
          echo "<div class='form-group'>";
            echo "<label>$c35</label>";
            echo "<input type='text' class='form-control' name='txtremark' placeholder='$cmas $c35' value='$remark'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>$c50</label>";
            echo "<input type='text' class='form-control' name='txtnomor_mobil' placeholder='$cmas $c50' value='$nomor_mobil'>";
          echo "</div>";
          echo "
            <button type='submit' name='submit' class='btn btn-primary'>$csim</button>
            <a href='?mod=$mod&mode=$mode'>Baru</a>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>$c41 *</label>";
            echo "<input type='text' class='form-control' name='txtinvno' placeholder='$cmas $c41' value='$invno'>";
          echo "</div>";
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
            if ($nm_company=="PT. Sinar Gaya Busana") { $callajax=""; } else { $callajax="onchange='getTujuan(this.value)'"; }
            echo "<select class='form-control select2' style='width: 100%;' $callajax name='txtstatus_kb'>";
            IsiCombo($sql,$status_kb,$cpil.' '.$c46);
            echo "</select>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Tujuan Pengeluaran</label>";
            echo "<select class='form-control select2' style='width: 100%;' id='cbotujuan' $st_txt_h name='txttujuan' disabled>";
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
                <label>Nomor Aju</label>
                <input type='text' class='form-control' name='txtbcaju' $st_txt_h placeholder='Masukan Nomor Aju' value='$bcaju'>
              </div>
            </div>
            <div class='col-md-6'>
              <div class='form-group'>
                <label>Tanggal Aju</label>
                <input type='text' class='form-control' name='txttglaju' $st_txt_tgl_h placeholder='Masukkan Tgl. Aju' 
                  value='$tglaju'>
              </div>
            </div>
          </div>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            if ($mod=="61r")
            { echo "<label>Nomor Request *</label>"; }
            else
            { echo "<label>Nomor BPPB *</label>"; }
            echo "<input type='text' class='form-control' name='txtbppbno' readonly placeholder='$cmas Nomor BPPB' value='$bppbno'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Tanggal BPPB *</label>";
            echo "<input type='text' class='form-control' name='txtbppbdate' onchange='getSat(this.value)' placeholder='$cmas Tanggal BPPB' value='$bppbdate'>";
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
if ($mod=="37v_bppb_po")
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
  { $fldnyacri=" mid(bppbno,4,1) in ('S','L') "; $mod2=37; }
  else if ($mode=="WIP")
  { $fldnyacri=" mid(bppbno,4,1)='C' "; $mod2=37; }
  else 
  { $fldnyacri=" mid(bppbno,4,1) in ('A','F','B') and mid(bppbno,4,2)!='FG' "; $mod2=37; }
  ?>
  <div class="box-header">
    <h3 class="box-title">List Pengeluaran <?PHP echo $titlenya; ?></h3>
    <a href='../forms/?mod=37_bppb_po&mode=<?php echo $mode; ?>' class='btn btn-primary btn-s'>
      <i class='fa fa-plus'></i> New
    </a>
  </div>
  <div class="box-body">
    <table id="examplefix3" class="display responsive" style="width:100%;font-size:10px;">
      <thead>
        <tr>
          <th>Nomor BPPB</th>
          <th>Tanggal BPPB</th>          
          <!-- <th>Nomor Req</th> -->
          <?php if($akses_date=="1") { ?>
          <th>Original BPPB Date</th>
          <?php } ?>  
          <th>Buyer</th>
          <th>Style #</th>
          <?php if($jenis_company=="VENDOR LG") { ?>
          <th>JO #</th>
          <?php } else { ?>
          <th>PO #</th>
          <?php } ?>

          <th>Penerima</th>
          <th>No. Invoice</th>
          <th>No. Dokumen</th>
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
        $sql="SELECT POH.pono,a.*,s.goods_code,s.itemdesc itemdesc,ms.supplier,mb.supplier buyer, a.last_date_bppb,
          ac.styleno,ac.kpno,a.username,group_concat(distinct concat(' ',jo.jo_no)) jo_nya 
          FROM bppb a inner join masteritem s on a.id_item=s.id_item
          inner join mastersupplier ms on a.id_supplier=ms.id_supplier 
          inner join jo_det jod on a.id_jo=jod.id_jo 
          inner join jo on jod.id_jo=jo.id  
          inner join so on jod.id_so=so.id 
          inner join act_costing ac on so.id_cost=ac.id 
          inner join mastersupplier mb on ac.id_buyer=mb.id_supplier  
          LEFT JOIN (SELECT * FROM po_item WHERE 1=1 GROUP BY id_po,id_jo,id_gen)POI ON POI.id_jo = a.id_jo AND POI.id_gen = a.id_item
          LEFT JOIN po_header POH ON POH.id = POI.id_po
          where mid(bppbno,4,1)='C' and a.id_jo!='' 
          GROUP BY a.bppbno ASC order by bppbdate desc";
        $query = mysqli_query($con_new,$sql);
        while($data = mysqli_fetch_array($query))
        { 
          if($logo_company=="Z")
          {
            $createby=$data['username'];
          }
          else
          {
            $createby=$data['username']." ".fd_view_dt($data['dateinput']);
          }
          echo "<tr>";
            if($data['bppbno_int']!="")
            { echo "<td>$data[bppbno_int]</td>"; }
            else
            { echo "<td>$data[bppbno]</td>"; }
            echo "<td>".fd_view($data['bppbdate'])."</td>";
            #echo "
            #<td>$data[bppbno_req]</td>";
            if($akses_date=="1") {
            echo "<td>".fd_view($data['last_date_bppb'])."</td>";
            } 
            echo "<td>$data[buyer]</td>
            <td>$data[styleno]</td>";
            if($jenis_company=="VENDOR LG")
            { echo "<td>$data[pono]</td>"; }
            else
            { echo "<td>$data[pono]</td>"; }
            echo "

            <td>$data[supplier]</td>
            <td>$data[invno]</td>
            <td>$data[bcno]</td>
            <td>$data[jenis_dok]</td>
            <td>$createby</td>";
            if($data['confirm']=='Y')
            { if($logo_company=="S") { $captses="Confirmed By"; } else { $captses="Sesuai"; }
              echo "
              <td>$captses ".$data['confirm_by']." (".fd_view_dt($data['confirm_date']).")</td>
              <td></td>"; 
            }
            else
            { echo "
              <td></td>
              <td>
                <a href='?mod=37e&mode=$mode&noid=$data[bppbno]'
                  data-toggle='tooltip' title='$cub'><i class='fa fa-pencil'></i>
                </a>
              </td>"; 
            } 
            if ($print_sj=="1")
            { echo "
              <td>
                <a href='cetaksj.php?mode=Out&noid=$data[bppbno]' 
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