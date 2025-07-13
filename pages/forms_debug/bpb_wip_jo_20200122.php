<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$mode = $_GET['mode'];
if (isset($_GET['mod'])) { $mod = $_GET['mod']; } else { $mod = ""; } 
$img_err = "'../../images/error.jpg'";
# START CEK HAK AKSES KEMBALI
$akses = flookup("mnubpbwip_jo","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI

$mod = $_GET['mod'];
$nm_tbl="bpb";
$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
  $nm_company = $rscomp["company"];
  $st_company = $rscomp["status_company"];
  $logo_company = $rscomp["logo_company"];
  $jenis_company = $rscomp["jenis_company"];
  $whs_input_bc_dok = $rscomp['whs_input_bc_dok'];
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
          
if ($nm_company=="PT. Youngil Leather Indonesia") { $titlenya="Chemical"; } else { $titlenya="WIP"; } 
$filternya="a.mattype in ('C')";

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
  $kkbc = "";
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
  $kkbc = $data['nomor_kk_bc'];
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
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>";
  echo "function validasi()";
  echo "{";
    echo "
    var reqno = document.form.txtreqno.value;
    var id_supplier = document.form.txtid_supplier.value;
    var invno = document.form.txtinvno.value;";
    if($whs_input_bc_dok=="Y")
    {
      echo "
      var bcno = document.form.txtbcno.value;
      var bcdate = document.form.txtbcdate.value;";
    }
    else 
    {
      echo "
      var bcno = '-';
      var bcdate = '-';";
    }
    echo "
    var status_kb = document.form.txtstatus_kb.value;
    var bppbdate = document.form.txtbpbdate.value;
    var qtykos = 0;
    var uomkos = 0;
    var itmkos = 0;
    var qtys = document.form.getElementsByClassName('qtysc');
    
    for (var i = 0; i < qtys.length; i++) 
    { if (qtys[i].value !== '')
      { qtykos = qtykos + 1; }
    }
    ";
    
    $img_alert = "imageUrl: '../../images/error.jpg'";    
    echo "if (reqno == '') { swal({ title: 'JO # Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";
    echo "else if (qtykos == 0) { swal({ title: 'Tidak Ada Data', $img_alert }); valid = false; }";
    echo "else if (id_supplier == '') { swal({ title: 'Supplier Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";
    echo "else if (invno == '') { document.form.txtinvno.focus();swal({ title: 'Nomor Inv/SJ Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";
    echo "else if (status_kb == '') { swal({ title: 'Jenis Dokumen Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";
    echo "else if (status_kb !== 'INHOUSE' && bcno == '') { document.form.txtbcno.focus();swal({ title: 'Nomor Daftar Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";
    echo "else if (bcdate == '') { document.form.txtbcdate.focus();swal({ title: 'Tgl. Daftar Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";
    echo "else if (bppbdate == '') { document.form.txtbpbdate.focus();swal({ title: 'Tgl. BKB Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";
    echo "else if (new Date(bppbdate) > new Date()) 
      { document.form.txtbpbdate.focus();valid = false;
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
        url: 'ajax_bpb_wip_jo.php?modeajax=view_list_bom',
        data: {id_jo: id_jo},
        async: false
    }).responseText;
    if(html)
    {  
        $("#detail_item").html(html);
    }
    $(".select2").select2();
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
if ($mod=="38")
{
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='s_bpb_wip_jo.php?mod=$mod&mode=$mode&noid=$bppbno&id=$id_line' onsubmit='return validasi()'>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>JO # *</label>";
            echo "<select class='form-control select2' multiple='multiple' style='width: 100%;' 
              name='txtreqno' id='cboReq' onchange='getJO()'>";
            if($logo_company=="Z") { $fldws="mp.product_group"; } else { $fldws="ac.kpno"; }
/*             $sql="select a.id isi,concat(a.jo_no,'|',ac.styleno,'|',$fldws) tampil 
              from jo a inner join jo_det s on a.id=s.id_jo 
              inner join  so on s.id_so=so.id inner join act_costing ac on so.id_cost=ac.id 
              inner join act_costing_mfg acm on ac.id=acm.id_act_cost 
              inner join masterproduct mp on ac.id_product=mp.id 
              group by a.id"; */
$sql = "select a.id isi,concat(a.jo_no,'|',ac.styleno,'|',ac.kpno) tampil  
              from jo a inner join jo_det s on a.id=s.id_jo 
              inner join  so on s.id_so=so.id inner join act_costing ac on so.id_cost=ac.id 
              inner join act_costing_mfg acm on ac.id=acm.id_act_cost 
              inner join masterproduct mp on ac.id_product=mp.id 
	inner join po_item poi ON a.id = poi.id_jo
	inner join po_header poh ON poi.id_po = poh.id

  group by a.id";			  
            IsiCombo($sql,'','');
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
            $sql = "select id_supplier isi,supplier tampil from mastersupplier";
            echo "<select class='form-control select2' style='width: 100%;' name='txtid_supplier'>";
            IsiCombo($sql,$id_supplier,$cpil.' Supplier');
            echo "</select>";
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
          if($whs_input_bc_dok=="Y") 
          {
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
              </div>";
              echo "
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
            </div>";
          }
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Nomor KK</label>";
            echo "<input type='text' class='form-control' name='txtkkbc' placeholder='$cmas KK' value='$kkbc'>";
          echo "</div>";
          echo "<div class='form-group'>";
            if ($mod=="61r")
            { echo "<label>Nomor Request *</label>"; }
            else
            { echo "<label>$caption[2] *</label>"; }
            echo "<input type='text' class='form-control' name='txtbpbno' readonly placeholder='$cmas $caption[2]' value='$bppbno'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>$caption[3] *</label>";
            echo "<input type='text' class='form-control' name='txtbpbdate' $st_txt_tgl_h placeholder='$cmas $caption[3]' value='$bppbdate'>";
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
if ($mod=="38v")
{
?>
<div class="box">
  <?php 
  $fldnyacri=" left(bpbno,1) in ('C') "; $mod2=38;
  ?>
  <div class="box-header">
    <h3 class="box-title">List Pemasukan <?php echo $titlenya; ?></h3>
    <a href='../forms/?mod=<?php echo $mod2; ?>&mode=<?php echo $mode; ?>' class='btn btn-primary btn-s'>
      <i class='fa fa-plus'></i> New
    </a>
  </div>
  <div class="box-body">
    <table id="examplefix3" class="display responsive" style="width:100%">
      <thead>
        <tr>
          <th>Nomor BPB</th>
          <th>Tanggal BPB</th>
          <?php if($logo_company=="S") { ?>
            <th>Style #</th>
            <th>WS #</th>
          <?php } ?>
          <th>Dari</th>
          <th>No. Invoice</th>
          <th>Jenis BC</th>
          <th>No. Daftar</th>
          <th>Tgl. Daftar</th>
          <th>Created By</th>
          <th></th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        if ($mode=="FG") { $tbl_mst="masterstyle"; $fld_desc="s.itemname"; } else { $tbl_mst="masteritem"; $fld_desc="s.itemdesc"; }
        $sql="SELECT ac.styleno stylecs,ac.kpno ws,a.*,s.goods_code,$fld_desc itemdesc,supplier 
          FROM bpb a inner join $tbl_mst s on a.id_item=s.id_item
          inner join mastersupplier ms on a.id_supplier=ms.id_supplier 
          inner join jo_det jod on a.id_jo=jod.id_jo 
          inner join so on jod.id_so=so.id 
          inner join act_costing ac on so.id_cost=ac.id 
          where $fldnyacri and a.id_jo!='' 
          GROUP BY a.bpbno ASC order by bpbdate desc";
        $query = mysql_query($sql);
        #echo $sql;
        while($data = mysql_fetch_array($query))
        { echo "<tr>";
          if($data['bpbno_int']!="")
          { echo "<td>$data[bpbno_int]</td>"; }
          else
          { echo "<td>$data[bpbno]</td>"; }
          $createby=$data['username']." ".fd_view_dt($data['dateinput']);
          echo "
            <td>$data[bpbdate]</td>";
            if($logo_company=="S")
            { echo "
              <td>$data[stylecs]</td>
              <td>$data[ws]</td>";
            }
            echo "
            <td>$data[supplier]</td>
            <td>$data[invno]</td>
            <td>$data[jenis_dok]</td>
            <td>$data[bcno]</td>
            <td>".fd_view($data['bcdate'])."</td>
            <td>$createby</td>";
            if($data['confirm']=='Y')
            { if($logo_company=="S") { $captses="Confirmed By"; } else { $captses="Sesuai"; }
              echo "
              <td>$captses ".$data['confirm_by']." (".fd_view_dt($data['confirm_date']).")</td>"; 
            }
            else
            { echo "
              <td>
                <a href='?mod=38e&mode=$mode&noid=$data[bpbno]'
                  data-toggle='tooltip' title='$cub'><i class='fa fa-pencil'></i>
                </a>
              </td>"; 
            }  
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
            echo "
            <td>
              <a href='../shp/show_pdf.php?trx=Pemasukan&id=$data[bpbno]'
                data-toggle='tooltip' title='Attach File'><i class='fa fa-paperclip'></i>
              </a>
            </td>";
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<?php } else { ?>

<?php } ?>