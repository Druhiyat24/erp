<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$akses = flookup("purch_ord","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='../pur/?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
if (isset($_GET['id'])) {$id_po=$_GET['id'];} else {$id_po="";}
$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
  $st_company = $rscomp["status_company"];
  $nm_company = $rscomp["company"];
  $nm_file_pdf_po = $rscomp["file_po"];
  $jenis_company = $rscomp["jenis_company"];
  $pr_need_app = $rscomp["pr_need_app"];
$id_item="";
# COPAS EDIT
if ($id_po=="")
{ $pono = "";
  $podate = date('d M Y');
  $etddate = date('d M Y');
  $etadate = date('d M Y');
  $expdate = date('d M Y');
  $notes = "";
  $tax="";
  $id_supplier = "";
  $id_terms = "";
  $curr = "";
}
else
{ $query = mysql_query("SELECT a.*,s.curr FROM po_header_dev a inner join po_item_dev s on a.id=s.id_po 
    where a.id='$id_po' limit 1");
  $data = mysql_fetch_array($query);
  $pono = $data['pono'];
  $jenis_item = $data['jenis'];
  $podate = fd_view($data['podate']);
  $etddate = fd_view($data['etd']);
  $etadate = fd_view($data['eta']);
  $expdate = fd_view($data['expected_date']);
  $notes = $data['notes'];
  $tax = $data['tax'];
  $curr = $data['curr'];
  $id_supplier = $data['id_supplier'];
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
  function add_item()
  { window.location.href='../marketting/?mod=x3ea';
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
    var qtymin = 0;
    var chks = document.form.getElementsByClassName('chkclass');
    var units = document.form.getElementsByClassName('unitclass');
    var qtys = document.form.getElementsByClassName('qtyclass');
    var qtybtss = document.form.getElementsByClassName('qtybtsclass');
    var jenis_item = document.form.txtJItem.value;
    for (var i = 0; i < chks.length; i++) 
    { if (chks[i].checked) 
      { pilih = pilih + 1;
        if (units[i].value == '')
        { unitkos = unitkos + 1; }
        if (qtys[i].value == '' || qtys[i].value <= '0')
        { qtykos = qtykos + 1; }
        if (qtys[i].value > qtybtss[i].value + 1)
        { qtyover = qtyover + 1; }
        if (qtys[i].value < 0)
        { qtymin = qtymin + 1; }
      }
    }
    if (podate == '') { document.form.txtpodate.focus(); swal({ title: 'PO Date Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (jenis_item == '') { swal({ title: 'Jenis Item Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (id_supplier == '') { swal({ title: 'Supplier Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (curr == '') { swal({ title: 'Currency Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (id_terms == '') { swal({ title: 'Payment Terms Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    <?php if ($mod=="3") { ?>
    else if (id_jo == '') { swal({ title: 'JO # Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (pilih == 0) { swal({ title: 'Tidak Ada Data Yang Dipilih', <?php echo $img_alert; ?>}); valid = false; }
    else if (unitkos > 0) { swal({ title: 'Currency Tidak Boleh Kosong', <?php echo $img_alert; ?>}); valid = false; }
    else if (qtykos > 0) { swal({ title: 'Qty Tidak Boleh Kosong', <?php echo $img_alert; ?>}); valid = false; }
    else if (qtymin > 0) { swal({ title: 'Qty Tidak Boleh Lebih Kecil Nol', <?php echo $img_alert; ?>}); valid = false; }
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
  function getListSupp()
  { var jenis_item = document.form.txtJItem.value;
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_po_dev.php?modeajax=get_list_supp',
        data: "jenis_item=" +jenis_item,
        async: false
    }).responseText;
    if(html)
    { $("#cbosupp").html(html); }
  };
  function getJO()
  { var id_jo = $('#cboJO').val();
    var id_supp = document.form.txtid_supplier.value;
    var jenis_item = document.form.txtJItem.value;
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_po_dev.php?modeajax=view_list_jo',
        data: {id_jo: id_jo,id_supp: id_supp,jenis_item: jenis_item},
        async: false
    }).responseText;
    if(html)
    {  
        $("#detail_item").html(html);
    }
  };

</script>
<?php if ($mod=="x3ea") { ?>
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
            <label>Jenis Item *</label>
            <select class='form-control select2' style='width: 100%;' 
              name='txtJItem' onchange='getListSupp()'>
              <?php
              if($jenis_item!="") 
              { if($jenis_item=="M")
                { $jenis_item2="Material"; }
                else
                { $jenis_item2="Manufacturing"; }
                $sql_fil=" and nama_pilihan='$jenis_item2'"; 
              } 
              else 
              { $sql_fil=""; }
              $sql = "SELECT if(nama_pilihan='Material','M','P') isi,if(nama_pilihan='Others','Manufacturing',nama_pilihan) tampil
                from masterpilihan where kode_pilihan='J_BOM_Det' $sql_fil ";
              IsiCombo($sql,$jenis_item,'Pilih Jenis Item');
              ?>
            </select>
          </div>
          <div class='form-group'>
            <label>Supplier *</label>
            <select class='form-control select2' style='width: 100%;' 
              name='txtid_supplier' id='cbosupp'>
            <?php 
            if($id_supplier!="")
            { $sql="select id_supplier isi,supplier tampil from mastersupplier where id_supplier='$id_supplier'";
              IsiCombo($sql,$id_supplier,'Pilih Supplier');
            }
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
                $sql = "select id isi,concat(kode_pterms,'-',nama_pterms) tampil
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
            <input type='text' class='form-control' id='datepicker4k' 
              name='txtexpdate' placeholder='Masukkan Expected Date' value='<?php echo $expdate;?>' >
          </div>
          <div class='form-group'>
            <label>PPN</label>
            <input type='text' class='form-control' name='txtppn' 
              placeholder='Masukkan PPN' value='<?php echo $tax;?>' > 
          </div>
          <div class='form-group'>
            <label>Notes</label>
            <textarea row='5' class='form-control' name='txtnotes' placeholder='Masukkan Notes'><?php echo $notes;?></textarea>
          </div>
          <?php if ($mod=="x3ea") { ?>
          <div class='form-group'>
            <label>Job Order # *</label> 
            <select class='form-control select2' multiple='multiple' style='width: 100%;' 
              name='txtJOItem[]' id='cboJO' onchange='getJO()'>
            <?php 
            $id_supp = $id_supplier;
            $jenis_item = $jenis_item;
            if($pr_need_app=="Y") { $sql_app=" and a.app='A' "; } else { $sql_app=""; }
            $sql = "select tmppr.id isi,tmppr.vtampil tampil from  
              (select a.id,concat(jo_no,' - ',ms.supplier,' - ',ac.styleno) vtampil,
              sum(sod.qty*s.cons) qtybom,tmppo.qty_po from 
              jo_dev a inner join bom_dev_jo_item s on a.id=s.id_jo
              inner join jo_det_dev jod on a.id=jod.id_jo
              inner join so_dev so on jod.id_so=so.id
              inner join act_development ac on so.id_cost=ac.id 
              inner join mastersupplier ms on ac.id_buyer=ms.id_supplier
              inner join so_det_dev sod on s.id_so_det=sod.id  
              left join (select id_jo,id_gen,sum(qty) qty_po from po_item_dev where cancel='N' group by id_jo,id_gen) tmppo 
              on tmppo.id_jo=s.id_jo and tmppo.id_gen=s.id_item
              where (s.id_supplier='$id_supp' or s.id_supplier2='$id_supp') 
              and s.status='$jenis_item' and s.cancel='N' $sql_app group by jo_no) tmppr 
              where qtybom>qty_po or qty_po is null ";
            //echo "<script>alert('".$id_supp."');</script>";
            IsiCombo($sql,'-','');
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
          <?php if($mod=="x3ea") { ?>
            <button type='button' class='btn btn-primary' onclick='select_all()'>Select All</button>
          <?php } else if($mod=="x3e") { ?>
            <button type='button' class='btn btn-primary' onclick='add_item()'>Add Item</button>
          <?php } ?>
        </div>
      </form>
    </div>
  </div>
</div><?php } 
# END COPAS ADD
#if ($id_po=="") {
if ($mod=="x3L") {
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">List PO Development</h3>
    <a href='../marketting/?mod=x3' class='btn btn-primary btn-s'>
      <i class='fa fa-plus'></i> New
    </a>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>
        <th>No</th>
        <th>PO #</th>
        <th>Rev</th>
        <th>PO Date</th>
        <th>Supplier</th>
        <th>P.Terms</th>
        <th>Buyer</th>
        <?php if($jenis_company=="VENDOR LG") { ?>
          <th>JO #</th>
        <?php } else { ?>
          <th>WS #</th>
        <?php } ?>
        <th>Style #</th>
        <th>Notes</th>
        <th>Status</th>
        <th></th>
        <th></th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $sql="select if(t_it_po_cx=t_it_po,'Cancelled','') status_po,a.app,a.id,pono,podate,supplier,
          nama_pterms,tmppoit.buyer,a.notes,a.revise,tmppoit.kpno,tmppoit.jo_no,tmppoit.styleno from po_header_dev a inner join 
          mastersupplier s on a.id_supplier=s.id_supplier inner join 
          masterpterms d on a.id_terms=d.id
          inner join 
          (select group_concat(distinct jo_no) jo_no,ac.kpno,ac.styleno,poit.id_jo,poit.id_po,ms.supplier buyer,count(*) t_it_po,
            sum(if(poit.cancel='Y',1,0)) t_it_po_cx from po_item_dev poit 
            inner join jo_det_dev jod on jod.id_jo=poit.id_jo 
            inner join jo_dev jo on jo.id=jod.id_jo  
            inner join so_dev so on jod.id_so=so.id 
            inner join act_development ac on so.id_cost=ac.id 
            inner join mastersupplier ms on ac.id_buyer=ms.id_supplier 
            group by poit.id_po
          ) tmppoit on tmppoit.id_po=a.id where a.jenis!='N' order by podate desc";
        $query = mysql_query($sql); 
        #echo $sql;
        $no = 1; 
        while($data = mysql_fetch_array($query))
        { echo "
            <tr>
              <td>$no</td>
              <td>$data[pono]</td>
              <td>$data[revise]</td>
              <td>".fd_view($data['podate'])."</td>
              <td>$data[supplier]</td>
              <td>$data[nama_pterms]</td>
              <td>$data[buyer]</td>";
              if($jenis_company=="VENDOR LG") 
              { echo "<td>$data[jo_no]</td>"; }
              else
              { echo "<td>$data[kpno]</td>"; }
              echo "
              <td>$data[styleno]</td>
              <td>$data[notes]</td>
              <td>$data[status_po]</td>
              <td>";
              if($data['app']=="A") 
              { if($nm_file_pdf_po=="")
                { $nm_file_pdf_po = "pdfPO_dev.php"; }
                echo "<a href='$nm_file_pdf_po?id=$data[id]'
                data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i></a>";
              }
              else
              { echo "<a href='?mod=x3e&id=$data[id]'
                data-toggle='tooltip' title='View'><i class='fa fa-eye'></i></a>"; 
              }
            echo "</td>";
            $cek=nb(flookup("username","userpassword","username='$user' and update_notes_po='1'"));
            if($cek=="")
            { echo "<td></td>"; }
            else
            { echo "<td><a href='?mod=x3en&id=$data[id]'
              data-toggle='tooltip' title='Update Notes'><i class='fa fa-sticky-note-o'></i></a>
              </td>"; 
            }
          echo "
          </tr>";
          $no++; // menambah nilai nomor urut
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<?php } else if (($mod=="x3" or $mod=="x3e") and $id_po!="") { ?>
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
        <th>Status</th>
        <th></th>
        <th></th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        if($jenis_item=="P")
        { $query = mysql_query("select l.id,jo_no,concat(a.goods_code,' ',a.itemdesc,' ',
            a.color,' ',a.size) item,
            l.qty,l.unit,l.curr,l.price,l.cancel 
            from po_item_dev l inner join jo_dev jo m on l.id_jo=m.id 
            inner join masteritem a on a.id_item=l.id_gen 
            where l.id_po='$id_po'");
        }
        else
        { if($jenis_company=="VENDOR LG")
          { $query = mysql_query("select l.id,jo_no,concat(a.goods_code,' ',a.itemdesc,' ',
              a.color,' ',a.size) item,
              l.qty,l.unit,l.curr,l.price,l.cancel 
              from po_item_dev l inner join jo_dev m on l.id_jo=m.id 
              inner join masteritem a on a.id_item=l.id_gen where l.id_po='$id_po'");
          }
          else
          { $query = mysql_query("select l.id,jo_no,concat(a.nama_group,' ',s.nama_sub_group,' ',
              d.nama_type,' ',e.nama_contents,' ',f.nama_width,' ',
              g.nama_length,' ',h.nama_weight,' ',i.nama_color,' ',j.nama_desc) item,
              l.qty,l.unit,l.curr,l.price,l.cancel 
              from po_item_dev l inner join jo_dev jo m on l.id_jo=m.id 
              inner join mastergroup a inner join mastersubgroup s on a.id=s.id_group
              inner join mastertype2 d on s.id=d.id_sub_group
              inner join mastercontents e on d.id=e.id_type
              inner join masterwidth f on e.id=f.id_contents 
              inner join masterlength g on f.id=g.id_width
              inner join masterweight h on g.id=h.id_length
              inner join mastercolor i on h.id=i.id_weight
              inner join masterdesc j on i.id=j.id_color 
              and l.id_gen=j.id where l.id_po='$id_po'");
          }
        }
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
            if($data['cancel']=="Y")
            { echo "<td>Canceled</td>"; }
            else
            { echo "<td></td>"; }
            if($data['cancel']=="N")
            { echo "
              <td>
                <a href='?mod=x3ei&id=$data[id]'
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