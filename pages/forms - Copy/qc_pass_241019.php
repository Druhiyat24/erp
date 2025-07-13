<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$query = mysql_query("SELECT * FROM mastercompany limit 1");
$data = mysql_fetch_array($query);
$nm_company = $data['company'];
$st_company = $data['status_company'];

$titlenya="Bahan Baku";
$id_item="";

# COPAS EDIT

# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
?>
<script type='text/javascript'>
  function validasi()
  {
    var bppbno = document.form.cbosj.value;
    var namaqc = document.form.txtnamaqc.value;
    var jdef = document.form.txtdefect.value;
    var statcek = document.getElementsByClassName('staclass');
    var statnodef = "";
    for (var i = 0; i < statcek.length; i++) 
    { 
      if (statcek[i].value != 'Pass' && jdef == '')
      { statnodef='1'; }
    }
    if (bppbno == '') { alert('No BPB tidak boleh kosong'); valid = false;}
    else if (namaqc == '') { alert('Nama QC tidak boleh kosong');valid = false;}
    else if (statnodef == '1') { alert('Jenis Defect tidak boleh kosong');valid = false;}
    else valid = true;
    return valid;
    exit;
  }
</script>
<?php
# END COPAS VALIDASI
?>
<script type="text/javascript">
  function getListKPNo(cri_item)
  {   var html = $.ajax
      ({  type: "POST",
          url: 'ajax2.php?modeajax=cari_list_qc',
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
          url: 'ajax2.php?modeajax=view_list_qc',
          data: "cri_item=" +cri_item,
          async: false
      }).responseText;
      if(html)
      {	 
          $("#detail_item").html(html);
      }
      $(".select2").select2();
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
<?php if($mod=="23") {
# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='save_data_qc.php?mod=$mod&mode=$mode&id=$id_item' onsubmit='return validasi()'>";
        echo "<div class='col-md-3'>";
          echo "
            <div class='form-group'>
              <label>Filter Tgl BPB *</label>
              <input type='text' class='form-control' name='txttglcut' id='datepicker1' 
                placeholder='Masukkan Filter Tgl BPB' onchange='getListKPNo(this.value)'>
            </div>";
          echo "
            <div class='form-group'>
              <label>Nomor BPB *</label>
              <select class='form-control select2' style='width: 100%;' name='txtsjno' id='cbosj' onchange='getListData(this.value)'>
              </select>
            </div>";
        echo "</div>";
        echo "
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Nama QC</label>
              <input type='text' class='form-control' name='txtnamaqc' placeholder='Masukkan Nama QC'>
            </div>
            <div class='form-group'>
              <label>Jenis Defect *</label>
              <select class='form-control select2' style='width: 100%;' name='txtdefect' id='cbodefect'>
              </select>
            </div>
          </div>";
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
    { $fldnyacri=" left(bpbno,2)='FG' "; $mod2=55; }
    else if ($mode=="Mesin")
    { $fldnyacri=" left(bpbno,1)='M' "; $mod2=53; }
    else if ($mode=="Scrap")
    { $fldnyacri=" left(bpbno,1) in ('S','L') "; $mod2=52; }
    else if ($mode=="WIP")
    { $fldnyacri=" left(bpbno,1)='C' "; $mod2=54; }
    else if ($mode=="General")
    { $fldnyacri=" left(bpbno,1)='N' "; $mod2="26e"; }
    else 
    { $fldnyacri=" left(bpbno,1) in ('A','F','B') and left(bpbno,2)!='FG' "; $mod2="26e"; }
    ?>
    <div class="box-header">
      <h3 class="box-title">List QC <?php echo $titlenya; ?></h3>
      <a href='../forms/?mod=<?php echo 23; ?>&mode=<?php echo $mode; ?>' class='btn btn-primary btn pull-right'>
        <i class='fa fa-plus'></i> New
      </a>
    </div>
    <div class="box-body">
      <table id="examplefix3" class="display responsive" style="width:100%">
        <thead>
          <tr>
            <th>Nomor BPB</th>
            <th>Tanggal BPB</th>
            <th>Pemasok</th>
            <th>No. Invoice</th>
            <th>No. Dokumen</th>
            <th>Jenis BC</th>
            <th>Check By</th>
            <th>Check Date</th>
            <th>Check Status</th>
            <th>Defect</th>
            <th></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE
          if ($mode=="FG") { $tbl_mst="masterstyle"; $fld_desc="s.itemname"; } else { $tbl_mst="masteritem"; $fld_desc="s.itemdesc"; }
          $sql="SELECT a.*,s.goods_code,$fld_desc itemdesc,supplier,mdef.nama_defect 
            FROM bpb a inner join $tbl_mst s on a.id_item=s.id_item 
            inner join po_header poh on a.pono=poh.pono 
            inner join mastersupplier ms on a.id_supplier=ms.id_supplier  
            inner join master_defect mdef on a.id_defect=mdef.id_defect 
            where $fldnyacri and a.id_po_item!='' and a.id_jo!='' 
            order by bpbdate desc limit 1000";
          #echo $sql;
          $query = mysql_query($sql);
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
              <td>$data[dicekqc_by]</td>
              <td>$data[dicekqc_date]</td>";
              if($data['dicekqc']=="Y")
              { echo "<td>Pass</td>"; }
              elseif($data['dicekqc']=="R")
              { echo "<td>Reject</td>"; }
              else
              { echo "<td>Not Check</td>"; }
              echo "<td>$data[nama_defect]</td>";
              // <td>
              //   <a href='?mod=$mod2&mode=$mode&noid=$data[bpbno]'
              //     data-toggle='tooltip' title='$cub'><i class='fa fa-pencil'></i>
              //   </a>
              // </td>"; 
              // if ($print_sj=="1")
              // { echo "
              //   <td>
              //     <a href='cetaksj.php?mode=In&noid=$data[bpbno]' 
              //       data-toggle='tooltip' title='Cetak'><i class='fa fa-print'></i>
              //     </a>
              //   </td>"; 
              // }
              // else
              // { echo "<td></td>"; }
            echo "</tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>  
<?php } ?>