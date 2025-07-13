<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$akses = flookup("qc_output","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
if (isset($_GET['id'])) {$id_po=$_GET['id'];} else {$id_po="";}
$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
  $st_company = $rscomp["status_company"];
  $jenis_company = $rscomp["jenis_company"];
$id_item="";
# COPAS EDIT
$dateout=date('d M Y');
$notes="";
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
?>
<script type='text/javascript'>
  function validasi()
  { var dateout = document.form.txtdateout.value;
    var id_cost = document.form.txtJOItem.value;
    var id_line = document.form.txtLine.value;
    var qtyo = document.form.getElementsByClassName('qtyclass');
    var rpro = document.form.getElementsByClassName('rprclass');
    var defo = document.form.getElementsByClassName('defectclass');
    var balo = document.form.getElementsByClassName('sisaclass');
    var nodata = 0;
    var dataover = 0;
    var defectkos = 0;
    for (var i = 0; i < qtyo.length; i++) 
    { if (Number(qtyo[i].value) > 0)
      { nodata = nodata + 1; break; }
    }
    for (var i = 0; i < qtyo.length; i++) 
    { if (Number(qtyo[i].value) > 0 && Number(qtyo[i].value) > Number(balo[i].value))
      { dataover = dataover + 1; break; }
    }
    for (var i = 0; i < rpro.length; i++) 
    { if (Number(rpro[i].value) > 0 && defo[i].value === '')
      { defectkos = defectkos + 1; break; }
    }
    if (dateout == '') { document.form.txtdateout.focus(); swal({ title: 'Tgl Output Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (id_line == '') { swal({ title: 'Line Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (id_cost == '') { swal({ title: 'WS # Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (nodata == 0) { swal({ title: 'Tidak Ada Data', <?php echo $img_alert; ?> }); valid = false;}
    else if (Number(dataover) > 0) { swal({ title: 'Qty Sudah Tidak Mencukupi', <?php echo $img_alert; ?> }); valid = false;}
    else if (Number(defectkos) > 0) { swal({ title: 'Defect Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else valid = true;
    return valid;
    exit;
  }
</script>
<?php
# END COPAS VALIDASI
# COPAS ADD
?>
<script type="text/javascript">
  function getJO()
  { var id_jo = $('#cboJO').val();
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_qc.php?modeajax=view_list_jo',
        data: {id_jo: id_jo},
        async: false
    }).responseText;
    if(html)
    {  
        $("#detail_item").html(html);
    }
  };
</script>
<?php if ($mod=="5") { ?>
<div class='box'>
  <div class='box-body'>
    <div class='row'>
      <form method='post' name='form' action='s_qc.php?mod=<?php echo $mod; ?>' onsubmit='return validasi()'>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Tanggal Output *</label>
            <input type='text' class='form-control' id='datepicker4' 
              name='txtdateout' placeholder='Masukkan Tanggal Output' value='<?php echo $dateout;?>' >
          </div>
          <div class="bootstrap-timepicker">
            <div class='form-group'>
              <label>Jam Output *</label>
              <input type='text' class='form-control timepicker' name='txtjam' value='<?php echo date('H:m'); ?>' >
            </div>
          </div>
          <div class='form-group'>
            <label>Notes</label>
            <input type='text' class='form-control' name='txtnotes' 
              placeholder='Masukkan Notes' value='<?php echo $notes;?>' >
          </div>
        </div>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Line *</label>
            <select class='form-control select2' style='width: 100%;' 
              name='txtLine'>
              <?php 
              $sql = "select id_supplier isi,supplier tampil from 
                mastersupplier where area in ('LINE','F')";
              IsiCombo($sql,'','Pilih Line');
              ?>
            </select>
          </div>
          <div class='form-group'>
            <?php if($jenis_company=="VENDOR LG") { ?>
              <label>JO # *</label>
            <?php } else { ?>
              <label>WS # *</label>
            <?php } ?>
            <select class='form-control select2' multiple="multiple" style='width: 100%;' 
              name='txtJOItem' id='cboJO' onchange='getJO()'>
              <?php
              if($jenis_company=="VENDOR LG")
              { $tampil="concat(jo.jo_no,'|',s.product_group)"; }
              else
              { $tampil="concat(a.kpno,'|',a.styleno,'|',s.product_group,'|',s.product_item)"; } 
              $sql = "select a.id isi,$tampil tampil 
                from act_costing a 
				LEFT join masterproduct s on a.id_product=s.id 
                LEFT join so on a.id=so.id_cost 
                LEFT join jo_det jod on jod.id_so=so.id 
                LEFT join jo on jod.id_jo=jo.id 
                LEFT join 
                (select id_jo from bppb group by id_jo) tmpbppb on tmpbppb.id_jo=jo.id    
                order by a.kpno";
              IsiCombo($sql,'','');
              ?>
            </select>
          </div>
        </div>
        <div class='box-body'>
          <div id='detail_item'></div>
        </div>
        <div class='col-md-3'>
          <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div><?php } 
# END COPAS ADD
if ($mod=="5L") {
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">List <?php echo $caption[7];?> Output</h3>
    <a href='../prod/?mod=5' class='btn btn-primary btn-s'>
      <i class='fa fa-plus'></i> New
    </a>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>
        <th>No</th>
        <?php if($jenis_company=="VENDOR LG") { ?>
          <th>JO #</th>
        <?php } else { ?>
          <th>WS #</th>
        <?php } ?>
        <th>SO #</th>
        <th>Buyer PO</th>
        <?php if($jenis_company!="VENDOR LG") { ?>
          <th>Dest</th>
          <th>Color</th>
          <th>Size</th>
        <?php } ?>
        <th>Tgl Entri</th>
        <th>Tgl Output</th>
        <th>Jam Output</th>
        <th><?php if($jenis_company=="VENDOR LG") { echo "Qty OK"; } else { echo "Qty RFT"; }?></th>
        <th><?php if($jenis_company=="VENDOR LG") { echo "Qty NG"; } else { echo "Qty RPR"; }?></th>
        <th>Defect</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $query = mysql_query("select jo.jo_no,ac.kpno,co.dateinput,co.jam,co.dateoutput,co.qty qtyout,co.rpr rprout,md.nama_defect,sod.id,so.so_no,so.buyerno,sod.dest,sod.color,sod.size,sod.qty,so.unit
          from so inner join so_det sod on so.id=sod.id_so
          inner join qc_out co on sod.id=co.id_so_det
          inner join act_costing ac on so.id_cost=ac.id 
          inner join jo_det jod on jod.id_so=so.id 
          inner join jo on jod.id_jo=jo.id 
          left join master_defect md on co.id_defect=md.id_defect order by co.dateoutput desc "); 
        $no = 1; 
        while($data = mysql_fetch_array($query))
        { echo "<tr>";
            $id=$data['id'];
            echo "<td>$no</td>";
            if($jenis_company=="VENDOR LG")
            { echo "<td>$data[jo_no]</td>"; }
            else
            { echo "<td>$data[kpno]</td>"; }
            echo "
            <td>$data[so_no]</td>
            <td>$data[buyerno]</td>";
            if($jenis_company!="VENDOR LG") 
            { echo "
              <td>$data[dest]</td>
              <td>$data[color]</td>
              <td>$data[size]</td>";
            }
            echo "
            <td>$data[dateinput]</td>
            <td>".fd_view($data['dateoutput'])."</td>
            <td>$data[jam]</td>";
            echo "
              <td>$data[qtyout]</td>
              <td>$data[rprout]</td>
              <td>$data[nama_defect]</td>";
          echo "</tr>";
          $no++; 

        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<?php } ?>