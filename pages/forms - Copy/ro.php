<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$query = mysql_query("SELECT * FROM mastercompany limit 1");
$data = mysql_fetch_array($query);
  $nm_company = $data['company'];
  $st_company = $data['status_company'];
  $jenis_company = $data['jenis_company'];
$titlenya = "Bahan Baku";
$id_item="";
$rono="";
$rodate=date('d M Y');
$bcdate=date('d M Y');

# COPAS EDIT

# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>";
  echo "function validasi()
    { var bppbno = document.form.cbosj.value;
      var jenisdok = document.form.txtstatus_kb.value;
      var jenisdef = document.form.txtdefect.value;
      var qtyo = document.form.getElementsByClassName('itemclass');
    var qtybts = document.form.getElementsByClassName('qtysjclass');
    var qtybtsall = document.form.getElementsByClassName('stallclass');
    var qtybtsjo = document.form.getElementsByClassName('stjoclass');
    var nodata = 0;
    var dataover = 0;
    var dataoverall = 0;
    var dataoverjo = 0;
    for (var i = 0; i < qtyo.length; i++) 
    { if (Number(qtyo[i].value) > 0)
      { nodata = nodata + 1; break; }
    }
    for (var i = 0; i < qtyo.length; i++) 
    { if (Number(qtyo[i].value) > 0 && Number(qtyo[i].value) > Number(qtybtsall[i].value) )
      { dataoverall = dataoverall + 1; break; }
    }
    for (var i = 0; i < qtyo.length; i++) 
    { if (Number(qtyo[i].value) > 0 && Number(qtyo[i].value) > Number(qtybtsjo[i].value) )
      { dataoverjo = dataoverjo + 1; break; }
    }
    for (var i = 0; i < qtyo.length; i++) 
    { if (Number(qtyo[i].value) > 0 && Number(qtyo[i].value) > Number(qtybts[i].value) )
      { dataover = dataover + 1; break; }
    }
    
    if (bppbno == '') 
    { valid = false; 
      swal({ title: 'Nomor BPB Tidak Boleh Kosong', $img_alert });
    }
    else if (nodata == '0') 
    { valid = false; 
      swal({ title: 'Tidak Ada Data', $img_alert });
    }
    else if (dataoverall > 0) 
    { valid = false; 
      swal({ title: 'Qty RO Melebihi Stock', $img_alert });
    }
    else if (dataoverjo > 0) 
    { valid = false; 
      swal({ title: 'Qty RO Melebihi Stock/JO', $img_alert });
    }
    else if (dataover > 0) 
    { valid = false; 
      swal({ title: 'Qty RO Melebihi Qty BPPB', $img_alert });
    }
    else if (jenisdok == '') 
    { valid = false; 
      swal({ title: 'Jenis Dok Tidak Boleh Kosong', $img_alert });
    }
    else if (jenisdef == '') 
    { valid = false; 
      swal({ title: 'Jenis Defect Tidak Boleh Kosong', $img_alert });
    }
    else valid = true;
    return valid;
    exit;
    }";
echo "</script>";
# END COPAS VALIDASI
?>
<script type="text/javascript">
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
  function getListKPNo(cri_item)
  {   var html = $.ajax
      ({  type: "POST",
          url: 'ajax2.php?modeajax=cari_list_sj_ro',
          data: "cri_item=" +cri_item,
          async: false
      }).responseText;
      if(html)
      {  
          $("#cbosj").html(html);
      }
  }
  function getListData(cri_item)
  {   var html = $.ajax
      ({  type: "POST",
          url: 'ajax2_ro.php?modeajax=view_list_sj_ro',
          data: "cri_item=" +cri_item,
          async: false
      }).responseText;
      if(html)
      {  
          $("#detail_item").html(html);
      }
      $(document).ready(function() {
        var table = $('#examplefix').DataTable
        ({  scrollCollapse: true,
            paging: false,
            fixedColumns:   
            { leftColumns: 1,
              rightColumns: 1
            }
        });
      });
      var html = $.ajax
      ({  type: "POST",
          url: 'ajax2.php?modeajax=cari_defect',
          data: "cri_item=" +cri_item,
          async: false
      }).responseText;
      if(html)
      {  
          $("#cbodefect").html(html);
      }
  }
</script>
<?PHP
# COPAS ADD
if($mod=="21") {
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='save_data_ro.php?mod=$mod&mode=$mode&id=$id_item' onsubmit='return validasi()'>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Filter Tgl BPB *</label>";
            echo "<input type='text' id='datepicker1' class='form-control dtpspc' autocomplete='off' onkeydown='return false' name='txttglcut'  
            placeholder='Masukkan Filter Tgl Surat Jalan' onchange='getListKPNo(this.value)'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>$caption[2] *</label>";
            echo "<select class='form-control select2' style='width: 100%;' name='txtsjno' id='cbosj' onchange='getListData(this.value)'>";
            echo "</select>";
          echo "</div>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Nomor RO</label>";
            echo "<input type='text' class='form-control' name='txtrono' placeholder='$cmas Nomor RO' readonly value='$rono'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Tgl RO *</label>";
            echo "<input type='text' id='datepicker2' class='form-control dtpspc' autocomplete='off' onkeydown='return false' name='txtbpbdate' placeholder='$cmas Tgl RO' value='$rodate'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Jenis Defect *</label>";
            echo "<select class='form-control select2' style='width: 100%;' name='txtdefect' id='cbodefect'>";
            echo "</select>";
          echo "</div>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>$c46 *</label>";
            if ($st_company=="KITE") 
            { $status_kb_cri="Status KITE In"; }
            else
            { $status_kb_cri="Status KB Out"; }
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
                <input type='text' class='form-control dtpspc' autocomplete='off' onkeydown='return false' name='txtbcdate' value='$bcdate'   
                  placeholder='Masukkan Tgl. Daftar'>
              </div>
            </div>
          </div>";
          echo "
          <div class='row'>
            <div class='col-md-6'>
              <div class='form-group'>
                <label>Nomor Aju *</label>
                <input type='text' class='form-control' name='txtbcaju' placeholder='$cmas' >
              </div>
            </div>
            <div class='col-md-6'>
              <div class='form-group'>
                <label>Tgl Aju *</label>
                <input type='text' class='form-control dtpspc' autocomplete='off' onkeydown='return false' name='txttglaju' value='$bcdate'   
                  placeholder='Masukkan Tgl. Aju'>
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
# END COPAS ADD
} else { ?>
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
    { $fldnyacri=" mid(bppbno,4,1) in ('A','F','B') and mid(bppbno,4,2)!='FG' "; $mod2="21e"; }
    ?>
    <div class="box-header">
      <h3 class="box-title">List Retur <?php echo $titlenya; ?></h3>
      <a href='../forms/?mod=21&mode=<?php echo $mode; ?>' class='btn btn-primary btn-s'>
        <i class='fa fa-plus'></i> New
      </a>
    </div>
    <div class="box-body">
      <table id="examplefix3" class="display responsive" style="width:100%">
        <thead>
          <tr>
            <th>Nomor SJ</th>
            <th>Tanggal SJ</th>
            <th>Style #</th>
            <?php if($jenis_company=="VENDOR LG") { ?>
            <th>JO #</th>
            <?php } else { ?>
            <th>WS #</th>
            <?php } ?>
            <th>Penerima</th>
            <th>No. Invoice</th>
            <th>Jenis BC</th>
            <th>No. Dokumen</th>
            <th>Tgl. Dokumen</th>
            <th>Created By</th>
            <th></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE
          if ($mode=="FG") { $tbl_mst="masterstyle"; $fld_desc="s.itemname"; } else { $tbl_mst="masteritem"; $fld_desc="s.itemdesc"; }
          $query = mysql_query("SELECT jo_no,ac.kpno wsno,ac.styleno stylenya,a.*,s.goods_code,$fld_desc itemdesc,supplier 
            FROM bppb a inner join $tbl_mst s on a.id_item=s.id_item
            inner join mastersupplier ms on a.id_supplier=ms.id_supplier 
            left join jo_det jod on a.id_jo=jod.id_jo 
            left join jo on jod.id_jo=jo.id  
            left join so on jod.id_so=so.id 
            left join act_costing ac on so.id_cost=ac.id  
            where $fldnyacri and right(bppbno,1)='R'  
            GROUP BY a.bppbno ASC order by bppbdate desc limit 1000");
          while($data = mysql_fetch_array($query))
          { echo "<tr>";
            if($data['bppbno_int']!="")
            { echo "<td>$data[bppbno_int]</td>"; }
            else
            { echo "<td>$data[bppbno]</td>"; }
            echo "
              <td>$data[bppbdate]</td>
              <td>$data[stylenya]</td>";
              if($jenis_company=="VENDOR LG")
              { echo "
                <td>$data[jo_no]</td>";
              }
              else
              { echo "
                <td>$data[wsno]</td>";
              }
              echo "
              <td>$data[supplier]</td>
              <td>$data[invno]</td>
              <td>$data[jenis_dok]</td>
              <td>$data[bcno]</td>
              <td>$data[bcdate]</td>
              <td>$data[username]</td>
              <td>
                <a href='?mod=$mod2&mode=$mode&noid=$data[bppbno]'
                  data-toggle='tooltip' title='$cub'><i class='fa fa-pencil'></i>
                </a>
              </td>"; 
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
<?php } ?>