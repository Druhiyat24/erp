<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$akses = flookup("purch_ord","userpassword","username='$user'");
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
{ $query = mysql_query("SELECT a.*,s.curr FROM po_header a inner join po_item s on a.id=s.id_po 
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
  { var podate = document.form.txtpodate.value;
    var id_supplier = document.form.txtid_supplier.value;
    var curr = document.form.txtcurr.value;
    var id_terms = document.form.txtid_terms.value;
    var id_jo = document.form.txtJOItem.value;
    var pilih = 0;
    var unitkos = 0;
    var qtykos = 0;
    var qtyover = 0;
    var chks = document.form.getElementsByClassName('chkclass');
    var units = document.form.getElementsByClassName('unitclass');
    var qtys = document.form.getElementsByClassName('qtyclass');
    var qtybtss = document.form.getElementsByClassName('qtybtsclass');
    for (var i = 0; i < chks.length; i++) 
    { if (chks[i].checked) 
      { pilih = pilih + 1;
        if (units[i].value == '')
        { unitkos = unitkos + 1; }
        if (qtys[i].value == '')
        { qtykos = qtykos + 1; }
        <?php 
        #NEXTNYA REMOVE DARI TAG PHP YA !!
        #if (qtys[i].value > qtybtss[i].value)
        #{ qtyover = qtyover + 1; }
        ?>
      }
    }
    if (podate == '') { document.form.txtpodate.focus(); swal({ title: 'PO Date Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (id_supplier == '') { swal({ title: 'Supplier Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (curr == '') { swal({ title: 'Currency Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (id_terms == '') { swal({ title: 'Payment Terms Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    <?php if ($mod=="3") { ?>
    else if (id_jo == '') { swal({ title: 'JO # Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (pilih == 0) { swal({ title: 'Tidak Ada Data Yang Dipilih', <?php echo $img_alert; ?>}); valid = false; }
    else if (unitkos > 0) { swal({ title: 'Currency Tidak Boleh Kosong', <?php echo $img_alert; ?>}); valid = false; }
    else if (qtykos > 0) { swal({ title: 'Qty Tidak Boleh Kosong', <?php echo $img_alert; ?>}); valid = false; }
    else if (qtyover > 0) { swal({ title: 'Qty Melebihi Kebutuhan', <?php echo $img_alert; ?>}); valid = false; }
    <?php } ?>
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
        url: 'ajax3.php?modeajax=view_list_stock',
        data: {id_jo: id_jo},
        async: false
    }).responseText;
    if(html)
    {  
        $("#detail_item").html(html);
    }
  };
</script>
<?php if ($mod=="61r") { ?>
<div class='box'>
  <div class='box-body'>
    <div class='row'>
      <form method='post' name='form' action='s_bppb_req.php?mod=<?php echo $mod; ?>' onsubmit='return validasi()'>
        <div class='col-md-3'>              
          <div class='form-group'>
            <label>Request #</label>
            <input type='text' readonly class='form-control' name='txtreqno' placeholder='Masukkan Request #' value='<?php echo $pono;?>' >
          </div>        
          <div class='form-group'>
            <label>Request Date *</label>
            <input type='text' class='form-control' id='datepicker1' name='txtreqdate' placeholder='Masukkan Request Date' value='<?php echo $podate;?>' >
          </div>
          <div class='form-group'>
            <label>Dikirim Ke *</label>
            <select class='form-control select2' style='width: 100%;' 
              name='txtid_supplier'>
              <?php 
                $sql = "select id_supplier isi,supplier tampil from mastersupplier";
                // $sql = "select d.id_supplier isi,supplier tampil from 
                //   jo a inner join bom_jo_item s on a.id=s.id_jo
                //   inner join mastersupplier d on s.id_supplier=d.id_supplier 
                //   where tipe_sup='S' group by supplier";
                IsiCombo($sql,$id_supplier,'Pilih Dikirim Ke');
              ?>
            </select>
          </div>
        </div>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Notes</label>
            <textarea row='5' class='form-control' name='txtnotes' placeholder='Masukkan Notes'><?php echo $notes;?></textarea>
          </div>
          <?php if ($mod=="61r") { ?>
          <div class='form-group'>
            <label>Job Order # *</label>
            <select class='form-control select2' style='width: 100%;' 
              name='txtJOItem' id='cboJO' onchange='getJO()'>
            <?php 
            $sql="select id isi,jo_no tampil from jo";
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
if ($mod=="61rv") {
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">List Request</h3>
    <a href='../forms/?mod=61r' class='btn btn-primary btn-s'>
      <i class='fa fa-plus'></i> New
    </a>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>
        <th>No</th>
        <th>Request #</th>
        <th>Request Date</th>
        <th>Sent To</th>
        <th>Action</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $query = mysql_query("select bppbno,bppbdate,supplier 
          from bppb_req a inner join mastersupplier s on a.id_supplier=s.id_supplier 
          group by bppbno order by bppbdate desc "); 
        $no = 1; 
        while($data = mysql_fetch_array($query))
        { echo "<tr>";
            echo "<td>$no</td>"; 
            echo "<td>$data[bppbno]</td>";
            echo "<td>$data[bppbdate]</td>";
            echo "<td>$data[supplier]</td>";
            echo "
            <td>
              <a $cl_ubah href='?mod=61re&id=$data[bppbno]'
                data-toggle='tooltip' title='View'><i class='fa fa-eye'></i></a>
              <a class='btn btn-info btn-s' href='pdfPickList.php?id=$data[bppbno]'
                data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i></a>
            </td>";
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
    <h3 class="box-title">Request Detail</h3>
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