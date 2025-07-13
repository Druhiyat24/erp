<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$akses = flookup("transfer_post","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
if (isset($_GET['id'])) {$id_po=$_GET['id'];} else {$id_po="";}
$st_company = flookup("status_company","mastercompany","company!=''");
$id_item="";
# COPAS EDIT
if ($id_po=="")
{ $pono = "";
  $podate = date('d M Y');
  $etddate = date('d M Y');
  $etadate = date('d M Y');
  $expdate = date('d M Y');
  $notes = "";
  $id_supplier = "";
  $id_terms = "";
  $curr = "";
}
else
{ $query = mysql_query("SELECT a.*,s.curr FROM po_header_dev a inner join po_item_dev s on a.id=s.id_po 
    where a.id='$id_po' limit 1");
  $data = mysql_fetch_array($query);
  $pono = $data['pono'];
  $podate = fd_view($data['podate']);
  $etddate = fd_view($data['etd']);
  $etadate = fd_view($data['eta']);
  $expdate = fd_view($data['expected_date']);
  $notes = $data['notes'];
  $curr = $data['curr'];
  $id_supplier = $data['id_supplier'];
  $id_terms = $data['id_terms'];
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
?>
<script type='text/javascript'>
  function validasi()
  { var reqdate = document.form.txtreqdate.value;
    var id_jo2 = document.form.txtToJOItem.value;
    var qtykos = 0;
    var qtys = document.form.getElementsByClassName('qtybpbclass');
    var qtyover = 0;
    //var units = document.form.getElementsByClassName('unitclass');
    var qtybtss = document.form.getElementsByClassName('qtysisaclass');
    for (var i = 0; i < qtys.length; i++) 
    { if (qtys[i].value > 0)
      { qtykos = qtykos + 1; }
      if (qtys[i].value > qtybtss[i].value)
      { qtyover = qtyover + 1; }
    }
    if (reqdate == '') { document.form.txtreqdate.focus(); swal({ title: 'Booking Date Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (id_jo2 == '') { swal({ title: 'To JO # Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (qtykos == 0) { swal({ title: 'Tidak Ada Data', <?php echo $img_alert; ?>}); valid = false; }
    else if (qtyover > 0) { swal({ title: 'Qty Booking Melebihi Stock', <?php echo $img_alert; ?>}); valid = false; }
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
  { var id_jo = $('#cboToJO').val();
    var html = $.ajax
    ({  type: "POST",
        url: '../forms/ajax3_dev.php?modeajax=view_list_stock2',
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
      <form method='post' name='form' action='s_trans_post_dev.php?mod=<?php echo $mod; ?>' onsubmit='return validasi()'>
        <div class='col-md-3'>              
          <div class='form-group'>
            <label>Booking #</label>
            <input type='text' readonly class='form-control' name='txtreqno' placeholder='Masukkan Booking #' value='<?php echo $pono;?>' >
          </div>        
          <div class='form-group'>
            <label>Booking Date *</label>
            <input type='text' class='form-control' id='datepicker1' name='txtreqdate' placeholder='Masukkan Booking Date' value='<?php echo $podate;?>' >
          </div>
        </div>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Notes</label>
            <textarea row='5' class='form-control' name='txtnotes' placeholder='Masukkan Notes'><?php echo $notes;?></textarea>
          </div>
        </div>
        <div class='col-md-3'>
          <?php if ($mod=="5") { ?>
          <!-- <div class='form-group'>
            <label>From Job Order # *</label>
            <select class='form-control select2' style='width: 100%;' 
              name='txtJOItem' id='cboJO' onchange='getJO()'>
            <?php 
            $sql="select jo.id isi,concat(jo.jo_no,' | ',ac.styleno,' | ',ac.kpno) tampil 
              from jo_dev jo inner join jo_det_dev jod on jo.id=jod.id_jo
              inner join so_dev so on jod.id_so=so.id
              inner join act_development ac on so.id_cost=ac.id";
            IsiCombo($sql,'','Pilih JO');
            ?>
            </select>
          </div> -->
          <div class='form-group'>
            <label>To Job Order # *</label>
            <select class='form-control select2' style='width: 100%;' 
              name='txtToJOItem' id='cboToJO' onchange='getJO()'>
            <?php 
            $sql="select jo.id isi,concat(jo.jo_no,' | ',ac.styleno,' | ',ac.kpno) tampil 
              from jo_dev jo inner join jo_det_dev jod on jo.id=jod.id_jo
              inner join so_dev so on jod.id_so=so.id
              inner join act_development ac on so.id_cost=ac.id";
            IsiCombo($sql,'','Pilih JO');
            ?>
            </select>
          </div>
          <?php } ?>
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
#if ($id_po=="") {
if ($mod=="5L") {
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">List Booking Stock</h3>
    <a href='../pur/?mod=5' class='btn btn-primary btn-s'>
      <i class='fa fa-plus'></i> New
    </a>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>
        <th>No</th>
        <th>Booking #</th>
        <th>Booking Date</th>
        <th>Kode Barang</th>
        <th>Nama Barang</th>
        <th>From JO</th>
        <th>To JO</th>
        <th>Qty</th>
        <th>Unit</th>
        <th>Notes</th>
        <th></th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $query = mysql_query("select a.*,jo.jo_no jo_from,jo2.jo_no jo_to,s.goods_code,s.itemdesc 
          from transfer_post a inner join masteritem s on a.id_item=s.id_item
          inner join jo on jo.id=a.id_jo_from inner join jo jo2 on jo2.id=a.id_jo_to"); 
        $no = 1; 
        while($data = mysql_fetch_array($query))
        { echo "<tr>";
            echo "<td>$no</td>"; 
            echo "<td>$data[bookno]</td>";
            echo "<td>".fd_view($data['bookdate'])."</td>";
            echo "<td>$data[goods_code]</td>";
            echo "<td>$data[itemdesc]</td>";
            echo "<td>$data[jo_from]</td>";
            echo "<td>$data[jo_to]</td>";
            echo "<td>$data[qty]</td>";
            echo "<td>$data[unit]</td>";
            echo "<td>$data[notes]</td>";
            if($data['cancel']=="N")
            { echo "
              <td><a href='d_tp.php?mod=$mod&id=$data[id]&idd=$data[id_item]'
                $tt_cancel";?> 
                onclick="return confirm('Apakah anda yakin akan dicancel ?')">
                <?php echo $tt_hapus2."</a>
              </td>";
            }
            else
            { echo "<td></td>";  }
          echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<?php } else if (($mod=="3" or $mod=="3e") and $id_po!="") { ?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Transfer Posting Detail</h3>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>
        <th>No</th>
        <th>JO #</th>
        <th>Item</th>
        <th>Qty</th>
        <th>Unit</th>
        <th>Curr</th>
        <th>Price</th>
        <th>Action</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $query = mysql_query("select l.id,jo_no,concat(a.nama_group,' ',s.nama_sub_group,' ',
        d.nama_type,' ',e.nama_contents,' ',f.nama_width,' ',
        g.nama_length,' ',h.nama_weight,' ',i.nama_color,' ',j.nama_desc) item,l.qty,l.unit,l.curr,l.price 
        from po_item l inner join jo m on l.id_jo=m.id 
        inner join mastergroup a inner join mastersubgroup s on a.id=s.id_group
        inner join mastertype2 d on s.id=d.id_sub_group
        inner join mastercontents e on d.id=e.id_type
        inner join masterwidth f on e.id=f.id_contents 
        inner join masterlength g on f.id=g.id_width
        inner join masterweight h on g.id=h.id_length
        inner join mastercolor i on h.id=i.id_weight
        inner join masterdesc j on i.id=j.id_color 
        and l.id_gen=j.id where l.id_po='$id_po'"); 
        $no = 1; 
        while($data = mysql_fetch_array($query))
        { echo "<tr>";
            echo "<td>$no</td>"; 
            echo "<td>$data[jo_no]</td>";
            echo "<td>$data[item]</td>";
            echo "<td>".fn($data['qty'],2)."</td>";
            echo "<td>$data[unit]</td>";
            echo "<td>$data[curr]</td>";
            echo "<td>".fn($data['price'],2)."</td>";
            echo "
            <td>
              <a $cl_ubah href='?mod=3ei&id=$data[id]'
                $tt_ubah</a>
            </td>";
          echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<?php } ?>