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
}
else
{ $query = mysql_query("SELECT * FROM po_header where id='$id_po'");
  $data = mysql_fetch_array($query);
  $pono = $data['pono'];
  $podate = fd_view($data['podate']);
  $etddate = fd_view($data['etd']);
  $etadate = fd_view($data['eta']);
  $expdate = fd_view($data['expected_date']);
  $notes = $data['notes'];
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
    var chks = document.form.getElementsByClassName('chkclass');
    var units = document.form.getElementsByClassName('unitclass');
    var qtys = document.form.getElementsByClassName('qtyclass');
    for (var i = 0; i < chks.length; i++) 
    { if (chks[i].checked) 
      { pilih = pilih + 1;
        if (units[i].value == '')
        { unitkos = unitkos + 1; }
        if (qtys[i].value == '')
        { qtykos = qtykos + 1; }
      }
    }
    if (podate == '') { document.form.txtpodate.focus(); swal({ title: 'PO Date Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (id_supplier == '') { swal({ title: 'Supplier Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (curr == '') { swal({ title: 'Currency Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (id_terms == '') { swal({ title: 'Payment Terms Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (id_jo == '') { swal({ title: 'JO # Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (pilih == 0)
    { swal({ title: 'Tidak Ada Data Yang Dipilih', <?php echo $img_alert; ?>});
      valid = false;
    }
    else if (unitkos > 0)
    { swal({ title: 'Currency Tidak Boleh Kosong', <?php echo $img_alert; ?>});
      valid = false;
    }
    else if (qtykos > 0)
    { swal({ title: 'Qty Tidak Boleh Kosong', <?php echo $img_alert; ?>});
      valid = false;
    }
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
  function getJOList()
  { var id_supp = document.form.txtid_supplier.value;
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_po.php?modeajax=get_list_jo',
        data: "id_supp=" +id_supp,
        async: false
    }).responseText;
    if(html)
    { $("#cboJO").html(html); }
  };
  function getJO()
  { var id_jo = document.form.txtJOItem.value;
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_po.php?modeajax=view_list_jo',
        data: {id_jo: id_jo},
        async: false
    }).responseText;
    if(html)
    {  
        $("#detail_item").html(html);
    }
  };
</script>

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
            <label>Supplier *</label>
            <select class='form-control select2' style='width: 100%;' 
              name='txtid_supplier' onchange='getJOList()'>
              <?php 
                $sql = "select d.id_supplier isi,supplier tampil from 
                  jo a inner join bom_jo_item s on a.id=s.id_jo
                  inner join mastersupplier d on s.id_supplier=d.id_supplier 
                  where tipe_sup='S' group by supplier";
                IsiCombo($sql,$id_supplier,'Pilih Supplier');
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
                $sql = "select id isi,kode_pterms tampil
                  from masterpterms";
                IsiCombo($sql,$id_terms,'Pilih Payment Terms');
              ?>
            </select>
          </div>
          <div class='form-group'>
            <label>ETD Date *</label>
            <input type='text' class='form-control' id='datepicker2' 
              name='txtetddate' placeholder='Masukkan ETD Date' value='<?php echo $etddate;?>' >
          </div>
          <div class='form-group'>
            <label>ETA Date *</label>
            <input type='text' class='form-control' id='datepicker3' 
              name='txtetadate' placeholder='Masukkan ETA Date' value='<?php echo $etadate;?>' >
          </div>
        </div>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Expected Date *</label>
            <input type='text' class='form-control' id='datepicker4' 
              name='txtexpdate' placeholder='Masukkan Expected Date' value='<?php echo $expdate;?>' >
          </div>
          <div class='form-group'>
            <label>Notes</label>
            <input type='text' class='form-control' name='txtnotes' 
              placeholder='Masukkan Notes' value='<?php echo $notes;?>' >
          </div>
          <div class='form-group'>
            <label>Job Order # *</label>
            <select class='form-control select2' style='width: 100%;' 
              name='txtJOItem' id='cboJO' onchange='getJO()'>
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
</div><?php 
# END COPAS ADD
if ($id_po=="") {
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">List PO</h3>
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
        <th>Action</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $query = mysql_query("select a.id,pono,podate,supplier,
          nama_pterms from po_header a inner join 
          mastersupplier s on a.id_supplier=s.id_supplier inner join 
          masterpterms d on a.id_terms=d.id"); 
        $no = 1; 
        while($data = mysql_fetch_array($query))
        { echo "<tr>";
            echo "<td>$no</td>"; 
            echo "<td>$data[pono]</td>";
            echo "<td>$data[podate]</td>";
            echo "<td>$data[supplier]</td>";
            echo "<td>$data[nama_pterms]</td>";
            echo "
            <td>
              <a $cl_ubah href='?mod=$mod&id=$data[id]'
                data-toggle='tooltip' title='View'><i class='fa fa-eye'></i></a>
              <a class='btn btn-info btn-s' href='pdfPO.php?id=$data[id]'
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
<?php } else { ?>
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
              <a $cl_ubah href='?mod=$mod&id=$data[id]'
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