<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$akses = flookup("sewing_output","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
if (isset($_GET['id'])) {$id_po=$_GET['id'];} else {$id_po="";}
$st_company = flookup("status_company","mastercompany","company!=''");
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
    var balo = document.form.getElementsByClassName('sisaclass');
    var nodata = 0;
    var dataover = 0;
    for (var i = 0; i < qtyo.length; i++) 
    { if (Number(qtyo[i].value) > 0)
      { nodata = nodata + 1; break; }
    }
    for (var i = 0; i < qtyo.length; i++) 
    { if (Number(qtyo[i].value) > 0 && Number(qtyo[i].value) > Number(balo[i].value))
      { dataover = dataover + 1; break; }
    }
    if (dateout == '') { document.form.txtdateout.focus(); swal({ title: 'Tgl Output Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (id_line == '') { swal({ title: 'Line Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (id_cost == '') { swal({ title: 'WS # Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (nodata == 0) { swal({ title: 'Tidak Ada Data', <?php echo $img_alert; ?> }); valid = false;}
    else if (Number(dataover) > 0) { swal({ title: 'Qty Sudah Tidak Mencukupi', <?php echo $img_alert; ?> }); valid = false;}
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
  { var id_jo = document.form.txtJOItem.value;
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_dc.php?modeajax=view_list_jo_dc',
        data: {id_jo: id_jo},
        async: false
    }).responseText;
    if(html)
    {  
        $("#detail_item").html(html);
    }
  };
</script>
<?php if ($mod=="10") { ?>
<div class='box'>
  <div class='box-body'>
    <div class='row'>
      <form method='post' name='form' action='s_dc_out.php?mod=<?php echo $mod; ?>' onsubmit='return validasi()'>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Tanggal Output *</label>
            <input type='text' class='form-control' id='datepicker4' 
              name='txtdateout' placeholder='Masukkan Tanggal Output' value='<?php echo $dateout;?>' >
          </div>
          <div class='form-group'>
            <label>Notes</label>
            <input type='text' class='form-control' name='txtnotes' 
              placeholder='Masukkan Notes' value='<?php echo $notes;?>' >
          </div>
        </div>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>WS # *</label>
            <select class='form-control select2' style='width: 100%;' 
              name='txtJOItem' id='cboJO' onchange='getJO()'>
              <?php 
              $sql = "select id isi,concat(kpno,' | ',styleno) tampil 
              from act_costing 
              #INNER JOIN cut_out ON act_costing.id=cut_out.id_act_costing 
              order by kpno";
              IsiCombo($sql,'','Pilih WS #');
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
if ($mod=="10L") {
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">List DC Output</h3>
    <a href='../prod/?mod=10' class='btn btn-primary btn-s'>
      <i class='fa fa-plus'></i> New
    </a>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>
        <th>No</th>
        <th>Buyer</th>
        <th>Style #</th>
        <th>WS #</th>
        <th>SO #</th>
        <th>Buyer PO</th>
        <th>Dest</th>
        <th>Color</th>
        <th>Size</th>
        <th>Tgl Entri</th>
        <th>Tgl Output</th>
        <th>Qty Output</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $query = mysql_query("select ac.styleno,ms.supplier,ac.kpno,co.dateinput,co.dateoutput,co.qty qtyout,sod.id,so.so_no,so.buyerno,sod.dest,sod.color,sod.size,sod.qty,so.unit
          from so inner join so_det sod on so.id=sod.id_so
          inner join dc_out co on sod.id=co.id_so_det
          inner join act_costing ac on so.id_cost=ac.id 
          inner join mastersupplier ms on ac.id_buyer=ms.id_supplier order by co.dateoutput desc"); 
        $no = 1; 
        while($data = mysql_fetch_array($query))
        { echo "<tr>";
            $id=$data['id'];
            echo "
            <td>$no</td>
            <td>$data[supplier]</td>
            <td>$data[styleno]</td>
            <td>$data[kpno]</td>
            <td>$data[so_no]</td>
            <td>$data[buyerno]</td>
            <td>$data[dest]</td>
            <td>$data[color]</td>
            <td>$data[size]</td>
            <td>$data[dateinput]</td>
            <td>".fd_view($data['dateoutput'])."</td>
            <td>$data[qtyout]</td>";
          echo "</tr>";
          $no++; 

        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<?php } ?>