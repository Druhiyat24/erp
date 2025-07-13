<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$mode = $_GET['mode'];
if (isset($_GET['mod'])) { $mod = $_GET['mod']; } else { $mod = ""; } 
$img_err = "'../../images/error.jpg'";
# START CEK HAK AKSES KEMBALI
if ($mode=="FG")
{ $akses = flookup("mnuBPPBFG","userpassword","username='$user'");  }
else if ($mode=="Scrap")
{ $akses = flookup("mnuBPPBScrap","userpassword","username='$user'");  }
else if ($mode=="General")
{ $akses = flookup("bppb_gen","userpassword","username='$user'");  }
else
{ $akses = flookup("bpb_consol","userpassword","username='$user'");  }
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI

$mod = $_GET['mod'];
$nm_tbl="bppb";
$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
  $nm_company = $rscomp["company"];
  $st_company = $rscomp["status_company"];
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
  $bcno = "";
  $bcdate = date('d M Y');
  $bcaju = "";
  $tglaju = date('d M Y');
  $bppbdate = date('d M Y');
  $status_kb = "";
  $txttujuan = "";
  $kkbc = "";
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
  $kkbc = $data['nomor_kk_bc'];
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
    var invno = document.form.txtinvno.value;
    var bcno = document.form.txtbcno.value;
    var bcdate = document.form.txtbcdate.value;
    var status_kb = document.form.txtstatus_kb.value;
    var bppbdate = document.form.txtbpbdate.value;
    var itemno = document.form.txtitem.value;
    var qtynya = document.form.txtqty.value;
    var unitnya = document.form.txtunit.value;
    ";
    
    $img_alert = "imageUrl: '../../images/error.jpg'";    
    echo "if (reqno == '') { swal({ title: 'JO # Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";
    echo "else if (itemno == '') { swal({ title: 'Item # Tidak Boleh Kosong', $img_alert }); valid = false; }";
    echo "else if (qtynya == '' || Number(qtynya) == '0') { swal({ title: 'Jumlah Tidak Boleh Kosong', $img_alert }); valid = false; }";
    echo "else if (unitnya == '') { swal({ title: 'Satuan Tidak Boleh Kosong', $img_alert }); valid = false; }";
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
  function Calc_Qty()
  { var qtys = document.form.getElementsByClassName('qtysc');
    var totqty = 0;
    for (var i = 0; i < qtys.length; i++) 
    { if (qtys[i].value !== '')
      { totqty = Number(totqty) + Number(qtys[i].value); }
    }
    $('#txttotal').val(totqty);
  }
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
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_bpb_jo.php?modeajax=view_list_bom',
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
<?php
# COPAS ADD
if ($mod=="40")
{
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='s_bpb_consol.php?mod=$mod&mode=$mode&noid=$bppbno&id=$id_line' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>JO # *</label>";
            if ($jenis_company=="VENDOR LG") { $add_kpno="mb.supplier"; } else { $add_kpno="ac.kpno"; }
            $sql="select a.id isi,concat(a.jo_no,'|',ac.styleno,'|',$add_kpno
              ,'|',ac.status_order,'|',mp.product_group) tampil 
              from jo a inner join jo_det s on a.id=s.id_jo 
              inner join  so on s.id_so=so.id inner join act_costing ac on so.id_cost=ac.id 
              inner join masterproduct mp on ac.id_product=mp.id 
              inner join mastersupplier mb on ac.id_buyer=mb.id_supplier 
              where ac.status='CONSOLIDATE'    
              group by a.id";
            #echo $sql;
            #onchange='getJO()'
            echo "
            <select class='form-control select2' style='width: 100%;' 
              name='txtreqno' id='cboReq'>";
            IsiCombo($sql,'','Pilih JO #');
            echo "
            </select>
          </div>
          <div class='form-group'>
            <label>$c35</label>
            <input type='text' class='form-control' name='txtremark' placeholder='$cmas $c35' 
              value='$remark'>
          </div>";
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
          <div class='form-group'>
            <div class='col-md-4'>
              <input type='text' size='5' class='form-control' name='txttotal' id='txttotal' readonly>
            </div>
          </div>
          <button type='submit' name='submit' class='btn btn-primary'>$csim</button>
        </div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>$c41 *</label>";
            echo "<input type='text' class='form-control' name='txtinvno' $st_txt_h placeholder='$cmas $c41' value='$invno'>";
          echo "</div>";
          echo "<div class='row'>";
            echo "<div class='col-md-6'>";
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
                echo "<select class='form-control select2' style='width: 100%;' $callajax name='txtstatus_kb'>";
                IsiCombo($sql,$status_kb,$cpil.' '.$c46);
                echo "</select>";
              echo "</div>";
            echo "</div>";
            echo "<div class='col-md-6'>";
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
            echo "</div>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Item #</label>";
            echo "<input type='hidden' class='form-control' name='txtkkbc' 
              placeholder='$cmas KK' value='$kkbc'>";
            echo "<select class='form-control select2' style='width: 100%;' name='txtitem'>";
            $sql="select id_item isi,concat(goods_code,' ',itemdesc,' ',color,' ',size) tampil from masteritem 
              where mattype in ('A','F') and id_gen!='0' ";
            IsiCombo($sql,'',$cpil.' '.'Item #');
            echo "</select>";
          echo "</div>";
          echo "<div class='row'>";
            echo "<div class='col-md-6'>";
              echo "<div class='form-group'>";
                echo "<label>Jumlah #</label>";
                echo "<input type='text' class='form-control' name='txtqty'>";
              echo "</div>";
            echo "</div>";
            echo "<div class='col-md-6'>";
              echo "<div class='form-group'>";
                echo "<label>Satuan #</label>";
                echo "<select class='form-control select2' style='width: 100%;' name='txtunit'>";
                $sql="select nama_pilihan isi,nama_pilihan tampil from masterpilihan  
                  where kode_pilihan='Satuan'";
                IsiCombo($sql,'',$cpil.' '.'Satuan');
                echo "</select>";
              echo "</div>";
            echo "</div>";
          echo "</div>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<div class='col-md-6'>";
            echo "<div class='form-group'>";
              if ($mod=="61r")
              { echo "<label>Nomor Request *</label>"; }
              else
              { echo "<label>$caption[2] *</label>"; }
              echo "<input type='text' class='form-control' name='txtbpbno' readonly placeholder='$cmas $caption[2]' value='$bppbno'>";
            echo "</div>";
          echo "</div>";
          echo "
          <div class='col-md-6'>
            <div class='form-group'>
              <label>$caption[3] *</label>
              <input type='text' class='form-control' name='txtbpbdate' $st_txt_tgl_h 
                placeholder='$cmas $caption[3]' value='$bppbdate'>
            </div>
          </div>";
          echo "
          <div class='col-md-6'>
            <div class='form-group'>
              <label>Nomor Aju</label>
              <input type='text' class='form-control' name='txtbcaju' 
                placeholder='Masukan Nomor Aju' value='$bcaju'>
            </div>
          </div>
          <div class='col-md-6'>
            <div class='form-group'>
              <label>Tanggal Aju</label>
              <input type='text' class='form-control' name='txttglaju' id='datepicker3' readonly  
                placeholder='Masukkan Tgl. Aju' value='$tglaju'>
            </div>
          </div>";
          echo "
          <div class='col-md-6'>
            <div class='form-group'>
              <label>$c42 *</label>
              <input type='text' class='form-control' name='txtbcno' $st_txt_h placeholder='$cmas $c42' 
                value='$bcno'>
            </div>
          </div>
          <div class='col-md-6'>
            <div class='form-group'>
              <label>$c43 *</label>
              <input type='text' class='form-control' name='txtbcdate' $st_txt_tgl_h1 
                placeholder='Masukkan Tgl. Daftar' value='$bcdate'>
            </div>
          </div>";
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
if ($mod=="40v")
{
?>
<div class="box">
  <?php 
  $fldnyacri=" left(bpbno,1) in ('A','F','B') "; $mod2=40;
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
          <th>Dari</th>
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
        $sql="SELECT a.*,s.goods_code,$fld_desc itemdesc,supplier 
          FROM bpb a inner join $tbl_mst s on a.id_item=s.id_item
          inner join mastersupplier ms on a.id_supplier=ms.id_supplier 
          inner join jo_det jod on a.id_jo=jod.id_jo 
          inner join so on jod.id_so=so.id 
          inner join act_costing ac on so.id_cost=ac.id 
          where $fldnyacri and (a.id_po_item='' or a.id_po_item is null) 
          and a.id_jo!='' and ifnull(a.id_book,'0')='0' and status='CONSOLIDATE'  
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
            <td>$data[bpbdate]</td>
            <td>$data[supplier]</td>
            <td>$data[invno]</td>
            <td>$data[bcno]</td>
            <td>$data[jenis_dok]</td>
            <td>$data[username]</td>";
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
                <a href='?mod=33e&mode=$mode&noid=$data[bpbno]'
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
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<?php } else { ?>

<?php } ?>