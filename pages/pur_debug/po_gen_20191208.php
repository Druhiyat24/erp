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
  $pph = "0";
  $notes = "";
  $tax="";
  $id_supplier = "";
  $kode_dept = "";
  $id_terms = "";
  $curr = "";
}
else
{ $query = mysql_query("SELECT a.*,s.curr,f.kode_mkt FROM po_header a inner join po_item s 
    on a.id=s.id_po inner join reqnon_header d on s.id_jo=d.id 
    inner join userpassword f on d.username=f.username 
    where a.id='$id_po' limit 1");
  $data = mysql_fetch_array($query);
  $pono = $data['pono'];
  $podate = fd_view($data['podate']);
  $etddate = fd_view($data['etd']);
  $etadate = fd_view($data['eta']);
  $expdate = fd_view($data['expected_date']);
  $pph = $data['pph'];
  $notes = $data['notes'];
  $tax = $data['tax'];
  $curr = $data['curr'];
  $id_supplier = $data['id_supplier'];
  $kode_dept = $data['kode_mkt'];
  $id_terms = $data['id_terms'];
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
?>
<script type='text/javascript'>
  function select_all()
  { var chks = document.form.getElementsByClassName('chkclass');
    for (var i = 0; i < chks.length; i++) 
    { chks[i].checked = true; }
  };
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
        if (qtys[i].value == '' || qtys[i].value <= '0')
        { qtykos = qtykos + 1; }
        if (qtys[i].value > qtybtss[i].value)
        { qtyover = qtyover + 1; }
      }
    }
    if (podate == '') { document.form.txtpodate.focus(); swal({ title: 'PO Date Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (id_supplier == '') { swal({ title: 'Supplier Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (curr == '') { swal({ title: 'Currency Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (id_terms == '') { swal({ title: 'Payment Terms Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    <?php if ($mod=="9") { ?>
    else if (id_jo == '') { swal({ title: 'JO # Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (pilih == 0) { swal({ title: 'Tidak Ada Data Yang Dipilih', <?php echo $img_alert; ?>}); valid = false; }
    else if (unitkos > 0) { swal({ title: 'Currency Tidak Boleh Kosong', <?php echo $img_alert; ?>}); valid = false; }
    else if (qtykos > 0) { swal({ title: 'Qty Tidak Boleh Kosong', <?php echo $img_alert; ?>}); valid = false; }
    else if (qtyover > 0) { alert('Qty Melebihi Kebutuhan'); valid = true; }
    <?php } ?>
    else valid = true;
    return valid;
    exit;
  };

</script>
<?php
# END COPAS VALIDASI

# COPAS ADD
?>
<script type="text/javascript">
  function getReqList()
  { var kode_dept = $('#cboDept').val();
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_po.php?modeajax=get_list_req',
        data: {kode_dept: kode_dept},
        async: false
    }).responseText;
    if(html)
    { $("#cboJO").html(html); }
  };
  function getReq()
  { var id_jo = $('#cboJO').val();
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_po.php?modeajax=view_list_req',
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
    $(".select2").select2();
  };
</script>
<?php if ($mod=="9" or $mod=="9e") { ?>
<div class='box'>
  <div class='box-body'>
    <div class='row'>
      <form method='post' name='form' action='s_po.php?mod=<?php echo $mod; ?>' onsubmit='return validasi()'>
        <div class='col-md-3'>              
          <div class='form-group'>
            <label>PO #</label>
            <input type='text' readonly class='form-control' name='txtpono' placeholder='Masukkan PO #' value='<?php echo $pono;?>' >
          </div>        
          <div class='form-group'>
            <label>PO Date *</label>
            <input type='text' class='form-control' id='datepicker1' name='txtpodate' placeholder='Masukkan PO Date' value='<?php echo $podate;?>' >
          </div>
          <div class='form-group'>
            <label>Kode Dept *</label>
            <select class='form-control select2' multiple='multiple' style='width: 100%;' 
              name='txtkode_dept' id='cboDept' onchange='getReqList()'>
              <?php
              $sql="select kode_mkt isi,kode_mkt tampil from userpassword
                where kode_mkt!='' group by kode_mkt order by kode_mkt";
              IsiCombo($sql,'','')
              ?>
            </select>
            <input type="hidden" name='txtJItem' value='N'>
          </div>
          <div class='form-group'>
            <label>Supplier *</label>
            <select class='form-control select2' style='width: 100%;' 
              name='txtid_supplier'>
              <?php
              $sql="select id_supplier isi,supplier tampil from mastersupplier
                where tipe_sup='S' and supplier!='' order by supplier";
              IsiCombo($sql,$id_supplier,'Pilih Supplier')
              ?>
            </select>
          </div>
        </div>
        <div class='col-md-3'>          
          <div class='form-group'>
            <label>Currency *</label>
            <select class='form-control select2' style='width: 100%;' name='txtcurr'>
              <?php 
                $sql = "select nama_pilihan isi,nama_pilihan tampil
                  from masterpilihan where kode_pilihan='Curr' order by nama_pilihan";
                IsiCombo($sql,$curr,'Pilih Currency');
              ?>
            </select>
          </div>
          <div class='form-group'>
            <label>Payment Terms *</label>
            <select class='form-control select2' style='width: 100%;' name='txtid_terms'>
              <?php 
                $sql = "select id isi,concat(kode_pterms,' ',nama_pterms) tampil
                  from masterpterms where aktif='Y' ";
                IsiCombo($sql,$id_terms,'Pilih Payment Terms');
              ?>
            </select>
          </div>
          <div class='row'>
            <div class='col-md-6'>
              <div class='form-group'>
                <label>ETD Date *</label>
                <input type='text' class='form-control' id='datepicker2' 
                  name='txtetddate' placeholder='Masukkan ETD Date' value='<?php echo $etddate;?>' >
              </div>
            </div>
            <div class='col-md-6'>
              <div class='form-group'>
                <label>ETA Date *</label>
                <input type='text' class='form-control' id='datepicker3' 
                  name='txtetadate' placeholder='Masukkan ETA Date' value='<?php echo $etadate;?>' >
              </div>
            </div>
          </div>
          <div class='form-group'>
            <label>Expected Date *</label>
            <input type='text' class='form-control' id='datepicker4' 
              name='txtexpdate' placeholder='Masukkan Expected Date' value='<?php echo $expdate;?>' >
          </div>
        </div>
        <div class='col-md-3'>
          <div class='row'>
            <div class='col-md-6'>
              <div class='form-group'>
                <label>PPh</label>
                <input type='number' class='form-control' name='txtpph' placeholder='Masukkan PPh' value='<?php echo $pph;?>' >
              </div>
            </div>
            <div class='col-md-6'>
              <div class='form-group'>
                <label>PPN</label>
                <input type='number' class='form-control' name='txtppn' 
                  placeholder='Masukkan PPN' value='<?php echo $tax;?>' > 
              </div>
            </div>
          </div>
          <div class='form-group'>
            <label>Notes</label>
            <textarea row='5' class='form-control' name='txtnotes' id='txtnotes' placeholder='Masukkan Notes'><?php echo $notes;?></textarea>
          </div>
          <?php if ($mod=="9") { ?>
          <div class='form-group'>
            <label>Request # *</label>
            <select class='form-control select2' multiple='multiple' style='width: 100%;' 
              name='txtJOItem' id='cboJO' onchange='getReq()'>
            </select>
          </div>
          <?php } ?>
        </div>
        <div class='box-body'>
          <div id='detail_item'></div>
        </div>
        <div class='col-md-3'>
          <button type='submit' name='submit' class='btn btn-primary'>Simpan</button> 
          <button type='button' class='btn btn-primary' onclick='select_all()'>Select All</button>
        </div>
      </form>
    </div>
  </div>
</div><?php } 
# END COPAS ADD
#if ($id_po=="") {
if ($mod=="9L") {
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">List PO General</h3>
    <a href='../pur/?mod=9' class='btn btn-primary btn-s'>
      <i class='fa fa-plus'></i> New
    </a>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>
        <th>No</th>
        <th>PO #</th>
        <th>PO Date</th>
        <th>Supplier</th>
        <th>P.Terms</th>
        <th>Request #</th>
        <th>Status</th>
        <th></th>
        <th></th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $sql = "select a.app,a.id,pono,podate,supplier,
          nama_pterms,tmppoit.buyer,tmppoit.t_row,tmppoit.t_rows_cancel 
          from po_header a inner join 
          mastersupplier s on a.id_supplier=s.id_supplier inner join 
          masterpterms d on a.id_terms=d.id
          inner join 
          (select poit.id_jo,poit.id_po,group_concat(distinct reqno) buyer,
            count(*) t_row,sum(if(cancel='Y',1,0)) t_rows_cancel from po_item poit 
            inner join reqnon_header rnh on poit.id_jo=rnh.id  group by poit.id_po) 
          tmppoit on tmppoit.id_po=a.id 
          where a.jenis='N' order by podate desc";
        $query = mysql_query($sql); 
        #echo $sql;
        $no = 1; 
        while($data = mysql_fetch_array($query))
        { if($data['t_row']>0 and $data['t_row']==$data['t_rows_cancel'])
          { $bgcol=" style='color: red; font-weight:bold;'"; }
          else
          { $bgcol=" "; }
          echo "<tr $bgcol>";
            echo "
            <td>$no</td>
            <td>$data[pono]</td>
            <td>".fd_view($data['podate'])."</td>
            <td>$data[supplier]</td>
            <td>$data[nama_pterms]</td>
            <td>$data[buyer]</td>
            <td>$data[app]</td>";
            if($data['t_row']>0 and $data['t_row']==$data['t_rows_cancel'])
            { echo "<td>Cancelled</td>"; }
            else
            { if($data['app']=="A")
              { echo "<td></td>"; }
              else
              { echo "
                <td>
                  <a href='?mod=9e&id=$data[id]'
                    data-toggle='tooltip' title='View'><i class='fa fa-eye'></i>
                  </a>
                </td>";
              }
            }
            if($data['app']=="A") 
            { echo "
              <td>
                <a href='pdfPOG.php?id=$data[id]'
                  data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i>
                </a>
              </td>";
            }
            else
            { echo "<td></td>"; }
          echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<?php } else if (($mod=="9" or $mod=="9e") and $id_po!="") { ?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">PO Detail</h3>
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
        <th></th>
        <th></th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $query = mysql_query("select l.cancel,l.id,reqno,itemdesc item,l.qty,l.unit,l.curr,l.price 
        from po_item l inner join reqnon_header m on l.id_jo=m.id 
        inner join masteritem j on l.id_gen=j.id_item 
        where l.id_po='$id_po' "); 
        $no = 1; 
        while($data = mysql_fetch_array($query))
        { echo "<tr>";
            echo "
            <td>$no</td>
            <td>$data[reqno]</td>
            <td>$data[item]</td>
            <td>".fn($data['qty'],2)."</td>
            <td>$data[unit]</td>
            <td>$data[curr]</td>
            <td>".fn($data['price'],2)."</td>";
            if($data['cancel']=='Y')
            { echo "
              <td>Canceled</td>
              <td></td>";
            }
            else
            { echo "
              <td>
                <a href='?mod=9ei&id=$data[id]'
                  $tt_ubah</a>
              </td>
              <td>
                <a href='d_po.php?mod=$mod&id=$data[id]&idd=$id_po'
                  $tt_hapus";?> 
                  onclick="return confirm('Are You Sure Want To Cancel ?')">
                  <?php echo $tt_hapus2."</a>
              </td>";
            }
          echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<?php } ?>