<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$akses = flookup("purch_ord_add","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='../pur/?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
if (isset($_GET['id'])) {$id_po=$_GET['id'];} else {$id_po="";}
$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
  $st_company = $rscomp["status_company"];
  $nm_company = $rscomp["company"];
  $nm_file_pdf_po = $rscomp["file_po"];
  $jenis_company = $rscomp["jenis_company"];
  $def_pterms = $rscomp["def_pterms"];
$id_item="";
# COPAS EDIT
if ($id_po=="")
{ $pono = "";
  $podate = date('d M Y');
  $etddate = date('d M Y');
  $disc=0;
  $etadate = date('d M Y');
  $expdate = date('d M Y');
  $notes = "";
  $tax="";
  $id_supplier = "";
  if($def_pterms!='')
  {	$id_terms = $def_pterms; }
	else
	{	$id_terms = ""; }
  $jml_pterms = "";
  $curr = "";
}
else
{ $query = mysql_query("SELECT a.*,s.curr FROM po_header a inner join po_item s on a.id=s.id_po 
    where a.id='$id_po' limit 1");
  $data = mysql_fetch_array($query);
  $pono = $data['pono'];
  $jenis_item = $data['jenis'];
  $podate = fd_view($data['podate']);
  $etddate = fd_view($data['etd']);
  $disc = $data['discount'];
  $etadate = fd_view($data['eta']);
  $expdate = fd_view($data['expected_date']);
  $notes = $data['notes'];
  $tax = $data['tax'];
  $curr = $data['curr'];
  $id_supplier = $data['id_supplier'];
  $id_terms = $data['id_terms'];
  $jml_pterms = $data['jml_pterms'];
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
if($mod=="poaddOT")
{
  $readtxt = " readonly ";
  $eventit = " onchange='get_qty_over()' ";
  // SEMENTARA
  // $readtxt = "";
  // $eventit = "";
}
else
{
  $readtxt = "";
  $eventit = "";
}
?>
<script type='text/javascript'>
  function get_list_item()
  {
    var id_jo = document.form.txtJOItem.value;
    var modenya = '<?=$mod?>';
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_get_item_po_add.php',
        data: {id_jo: id_jo,modenya: modenya},
        async: false
    }).responseText;
    if(html)
    {  
        $("#txtItem").html(html);
    }
  };
  function get_qty_over()
  {
    var id_jo = document.form.txtJOItem.value;
    var id_item = document.form.txtItem.value;
    jQuery.ajax
    ({  url: 'ajax_get_qty_po_add.php',
        method: 'POST',
        data: {id_jo: id_jo, id_item: id_item}, 
        success: function(response){
          jQuery('#txtqty').val(response);  
        },
        error: function (request, status, error) 
        { alert(request.responseText); },
    });
  };
  function go_frm()
  {
    var pronya = document.form.txtJItem.value;
    if (pronya == '')
    {
      alert('Silahkan Pilih Proses');
    }
    else if (pronya == 'ADDITIONAL PO')
    {
      window.location.href='../pur/?mod=poadd';
    }
    else if (pronya == 'OVER TOLLERANCE')
    {
      window.location.href='../pur/?mod=poaddOT';
    }
  };
  function validasi()
  { 
    var podate = document.form.txtpodate.value;
    var id_supplier = document.form.txtid_supplier.value;
    var curr = document.form.txtcurr.value;
    var price = document.form.txtprice.value;
    var id_terms = document.form.txtid_terms.value;
    var id_jo = document.form.txtJOItem.value;
    var id_item = document.form.txtItem.value;
    var qty = document.form.txtqty.value;
    var unit = document.form.txtunit.value;
    
    if (podate == '') { document.form.txtpodate.focus(); swal({ title: 'PO Date Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (new Date(podate) > new Date()) { valid = false;swal({ title: 'Tgl. Transaksi Tidak Boleh Melebihi Hari Ini', <?=$img_alert?> }); }
    else if (id_supplier == '') { swal({ title: 'Supplier Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (curr == '') { swal({ title: 'Currency Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (Number(price) == '0' || price == '') { swal({ title: 'Price Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (id_terms == '') { swal({ title: 'Payment Terms Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (id_item == '') { swal({ title: 'Item Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (Number(qty) == '0' || qty == '') { swal({ title: 'Qty Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (unit == '') { swal({ title: 'Satuan Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    <?php if ($mod=="poadd" or $mod=="poaddOT") { ?>
    else if (id_jo == '') { swal({ title: 'JO # Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    <?php } ?>
    else valid = true;
    return valid;
    exit;
  };
</script>
<!-- # END COPAS VALIDASI
# COPAS ADD -->
<?php if ($mod=="poadd" or $mod=="poaddC" or $mod=="poaddOT" or $mod=="poadde") { ?>
<div class='box'>
  <div class='box-body'>
    <div class='row'>
      <form method='post' name='form' action='s_po_add.php?mod=<?php echo $mod; ?>' onsubmit='return validasi()'>
        <div class='col-md-3'>
          <?php if($mod=="poaddC") { ?>              
          <div class='form-group'>
            <label>Proses *</label>
            <select class='form-control select2' style='width: 100%;' 
              name='txtJItem'>
              <!-- onchange='getListSupp()' -->
              <?php
              $sql = "SELECT nama_pilihan isi,nama_pilihan tampil
                from masterpilihan where kode_pilihan='PO_ADD_PRO'";
              IsiCombo($sql,'','Pilih Proses');
              ?>
            </select>
          </div>
          <a href='#' onclick='go_frm()' class='btn btn-primary btn-s'>
            <i class='fa fa-plus'></i> New
          </a>
          <?php } else { ?>
          <div class='form-group'>
            <label>PO #</label>
            <input type='text' readonly class='form-control' name='txtpono' placeholder='Masukkan PO #' value='<?php echo $pono;?>' >
            <input type='hidden' readonly class='form-control' name='txtconv' id='txtconv'>
          </div>        
          <div class='form-group'>
            <label>PO Date *</label>
            <input type='text' class='form-control' id='datepicker1' name='txtpodate' placeholder='Masukkan PO Date' value='<?php echo $podate;?>' >
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
            else
            { $sql="select id_supplier isi,supplier tampil from mastersupplier where tipe_sup='S'";
              IsiCombo($sql,$id_supplier,'Pilih Supplier');
            }
            ?>
            </select>
          </div>
          <div class='row'>
            <div class='col-md-6'>
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
            </div>
            <div class='col-md-6'>
              <div class='form-group'>
                <label>Price</label>
                <input type='text' class='form-control' name='txtprice'>
              </div>
            </div>
          </div>
        </div>
        <div class='col-md-3'>          
          <div class='row'>
            <div class='col-md-6'>
              <div class='form-group'>
                <label>Payment Terms *</label>
                <select class='form-control select2' style='width: 100%;' name='txtid_terms'>
                  <?php 
                    $sql = "select id isi,concat(kode_pterms,'-',nama_pterms) tampil
                      from masterpterms where aktif='Y' ";
                    IsiCombo($sql,$id_terms,'Pilih Payment Terms');
                  ?>
                </select>
              </div>
            </div>
            <div class='col-md-6'>
              <div class='form-group'>
                <label>Days</label>
                <input type='text' class='form-control' name='txtdays' value='<?php echo $jml_pterms; ?>' >
              </div>
            </div>    
          </div>
          <?php if ($mod=="poadd" or $mod=="poaddOT") { ?>
          <div class='form-group'>
            <label>Job Order # *</label>
            <select class='form-control select2' style='width: 100%;' 
              name='txtJOItem' id='cboJO' onchange='get_list_item()'>
              <?php 
              if($mod=="poaddOT")
              {
                $sql="select jod.id_jo isi,concat(jo.jo_no,' - ',ms.supplier,' - ',ac.kpno,' - ',ac.styleno) tampil 
                  from jo inner join jo_det jod 
                  on jo.id=jod.id_jo inner join so on jod.id_so=so.id 
                  inner join act_costing ac on so.id_cost=ac.id 
                  inner join mastersupplier ms on ac.id_buyer=ms.id_supplier 
                  inner join po_item poi on jod.id_jo=poi.id_jo 
                  inner join bpb_over bov on poi.id=bov.id_po_item 
                  inner join po_header poh on poi.id_po=poh.id 
                  where poh.po_over='W' and bov.qty_add>0";
              }
              else
              {
                $sql="select id_jo isi,concat(jo_no,' - ',ms.supplier,' - ',ac.kpno,' - ',ac.styleno) tampil 
                  from jo inner join jo_det jod 
                  on jo.id=jod.id_jo inner join so on jod.id_so=so.id 
                  inner join act_costing ac on so.id_cost=ac.id 
                  inner join act_costing_mat acm on ac.id=acm.id_act_cost  
                  inner join mastersupplier ms on ac.id_buyer=ms.id_supplier 
                  group by id_jo";
              }
              IsiCombo($sql,"","Pilih JO #")
              ?>
            </select>
          </div>
          <?php } ?>
          <div class='form-group'>
            <label>Item *</label>
            <select class='form-control select2' style='width: 100%;' 
              name='txtItem' id='txtItem' <?=$eventit?>>
            </select>
          </div>
          <div class='row'>
            <div class='col-md-6'>
              <div class='form-group'>
                <label>Qty PO</label>
                <input type='text' class='form-control' name='txtqty' id='txtqty' <?=$readtxt?>>
              </div>  
            </div>
            <div class='col-md-6'>
              <div class='form-group'>
                <label>Satuan *</label>
                <select class='form-control select2' style='width: 100%;' name='txtunit'>
                  <?php
                    if($mod<>"XpoaddOT")
                    {
                      $sql = "select nama_pilihan isi,nama_pilihan tampil
                        from masterpilihan where kode_pilihan='Satuan' ";
                      IsiCombo($sql,"",'Pilih Satuan');
                    } 
                  ?>
                </select>
              </div>
            </div>    
          </div>
        </div>
        <div class='col-md-3'>
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
          <div class='row'>
            <div class='col-md-6'>
              <div class='form-group'>
                <label>Discount</label>
                <input type='text' class='form-control' name='txtdisc' placeholder='Masukkan Discount' value='<?php echo $disc;?>' >
              </div>
              <div class='form-group'>
                <label>Notes</label>
                <textarea row='5' class='form-control' name='txtnotes' placeholder='Masukkan Notes'><?php echo $notes;?></textarea>
              </div>
            </div>
            <div class='col-md-6'>
              <div class='form-group'>
                <label>PPN</label>
                <input type='text' class='form-control' name='txtppn' 
                  placeholder='Masukkan PPN' value='<?php echo $tax;?>' > 
              </div>
            </div>
          </div>
        </div>
        <div class='box-body'>
          <div id='detail_item'></div>
        </div>
        <div class='col-md-6'>
          <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
        </div>
      </form>
      <?php } ?>
    </div>
  </div>
</div><?php } 
# END COPAS ADD
#if ($id_po=="") {
if ($mod=="poaddL") {
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">List PO</h3>
    <a href='../pur/?mod=poaddC' class='btn btn-primary btn-s'>
      <i class='fa fa-plus'></i> New
    </a>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>
        <th>No</th>
        <th>PO #</th>
        <th>Type</th>
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
        <th>Status PO</th>
        <th></th>
        <th></th>
        <th></th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $sql="select if(t_it_po_cx=t_it_po,'Cancelled','') status_po,a.app,a.id,pono,podate,supplier,
          nama_pterms,tmppoit.buyer,a.notes,a.revise,tmppoit.kpno,tmppoit.jo_no,tmppoit.styleno,
          tmppoit.tqtypo,tmppoit.tqtybpb,tmppoit.tqtyover,a.po_close,a.type_ot    
          from po_header a inner join 
          mastersupplier s on a.id_supplier=s.id_supplier inner join 
          masterpterms d on a.id_terms=d.id
          inner join 
          (select group_concat(distinct jo_no) jo_no,ac.kpno,ac.styleno,poit.id_jo,poit.id_po,ms.supplier buyer,count(*) t_it_po,
            sum(if(poit.cancel='Y',1,0)) t_it_po_cx,sum(poit.qty) tqtypo,
            tmpbpb.tqtybpb,tmpbpb.tqtyover from po_item poit 
            inner join jo_det jod on jod.id_jo=poit.id_jo 
            inner join jo on jo.id=jod.id_jo  
            inner join so on jod.id_so=so.id 
            inner join act_costing ac on so.id_cost=ac.id 
            inner join mastersupplier ms on ac.id_buyer=ms.id_supplier 
            left join 
            (select s.id_po,a.id_po_item,sum(a.qty) tqtybpb,sum(qty_over) tqtyover from bpb a inner join po_item s on a.id_po_item=s.id group by s.id_po)
            tmpbpb on poit.id_po=tmpbpb.id_po 
            where poit.cancel='N'   
            group by poit.id_po
          ) tmppoit on tmppoit.id_po=a.id where a.jenis!='N' and pono regexp '/ADD' order by podate desc";
        $query = mysql_query($sql); 
        #echo $sql;
        $no = 1; 
        while($data = mysql_fetch_array($query))
        { if ($data['tqtybpb']>=$data['tqtypo']) 
          {$bgcol=" style='background-color: green; color:yellow;'";} 
          else if ($data['tqtybpb']<$data['tqtypo'] and $data['tqtybpb']>0) 
          {$bgcol=" style='background-color: yellow; font-color:green;'";} 
          else 
          {$bgcol="";}
          if($data['type_ot']=="Y")
          {
            $typepo = "Over Tollerance";
          }
          else
          {
            $typepo = "PO Addistional";
          }
          echo "
            <tr $bgcol>
              <td>$no</td>
              <td>$data[pono]</td>
              <td>$typepo</td>
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
              <td>$data[status_po]</td>";
              if($data['po_close']=="Y")
              { echo "<td>Close</td>"; }
              else
              { echo "<td>Open</td>"; }
              echo "
              <td>";
              if($data['app']=="A") 
              { if($nm_file_pdf_po=="")
                { $nm_file_pdf_po = "pdfPO.php"; }
                echo "<a href='$nm_file_pdf_po?id=$data[id]'
                data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i></a>";
              }
              else
              { echo "<a href='?mod=3e&id=$data[id]'
                data-toggle='tooltip' title='View'><i class='fa fa-eye'></i></a>"; 
              }
            echo "</td>";
            $cek=nb(flookup("username","userpassword","username='$user' and update_notes_po='1'"));
            if($cek=="")
            { echo "<td></td>"; }
            else
            { echo "<td><a href='?mod=3en&id=$data[id]'
              data-toggle='tooltip' title='Update Notes'><i class='fa fa-sticky-note-o'></i></a>
              </td>"; 
            }
          echo "
            <td>
              <a href='?mod=3C&id=$data[id]'
                data-toggle='tooltip' title='Close PO'";?>
                onclick="return confirm('Apakah Anda Yakin Akan Close PO ?')">
                <?php echo "<i class='fa fa-flag-checkered'></i></a>
            </td>
          </tr>";
          $no++; // menambah nilai nomor urut
        }
        ?>
      </tbody>
    </table>
    <i style='background-color:green;color:yellow;'>Qty BPB >= Qty PO</i>
    <i style='background-color:yellow;color:green;'>Qty BPB < Qty PO</i>
  </div>
</div>
<?php } else if (($mod=="poadd" or $mod=="poadde") and $id_po!="") { ?>
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
            l.qty,l.unit,l.curr,l.price,l.cancel,l.id_jo,l.id_gen id_mat_cs  
            from po_item l inner join jo m on l.id_jo=m.id 
            inner join masteritem a on a.id_item=l.id_gen 
            where l.id_po='$id_po'");
        }
        else
        { if($jenis_company=="VENDOR LG")
          { $query = mysql_query("select l.id,jo_no,concat(a.goods_code,' ',a.itemdesc,' ',
              a.color,' ',a.size) item,
              l.qty,l.unit,l.curr,l.price,l.cancel,l.id_jo,l.id_gen id_mat_cs  
              from po_item l inner join jo m on l.id_jo=m.id 
              inner join masteritem a on a.id_item=l.id_gen where l.id_po='$id_po'");
          }
          else
          { $query = mysql_query("select l.id,jo_no,concat(a.nama_group,' ',s.nama_sub_group,' ',
              d.nama_type,' ',e.nama_contents,' ',f.nama_width,' ',
              g.nama_length,' ',h.nama_weight,' ',i.nama_color,' ',j.nama_desc,' ',ifnull(j.add_info,'')) item,
              l.qty,l.unit,l.curr,l.price,l.cancel,l.id_jo,e.id id_mat_cs 
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
          }
        }
        $no = 1; 
        while($data = mysql_fetch_array($query))
        { if($jenis_item=="P")
          { $cek=flookup("acm.price","jo_det jod inner join so on jod.id_so=so.id 
              inner join act_costing_oth acm on so.id_cost=acm.id_act_cost ",
              "jod.id_jo='$data[id_jo]' and acm.id_item='$data[id_mat_cs]'");
          }
          else
          { $sql="select if(jenis_rate='B',price/rate_beli,price) cst_usd, 
      				if(jenis_rate='J',price*rate_jual,price) cst_idr 
      				from jo_det jod inner join so on jod.id_so=so.id 
              inner join act_costing act on act.id=so.id_cost inner join act_costing_mat acm on so.id_cost=acm.id_act_cost 
              inner join masterrate d on 'USD'=d.curr and act.cost_date=d.tanggal 
              where jod.id_jo='$data[id_jo]' and acm.id_item='$data[id_mat_cs]'";
          	$rscst=mysql_fetch_array(mysql_query($sql));
          	if ($data['curr']=="IDR")
          	{	$cek = round($rscst['cst_idr'],2);	}
          	else
          	{	$cek = round($rscst['cst_usd'],2);	}
          }
          if ($data['price']>$cek) {$bgcol=" style='background-color: blue;color:yellow;'";} else {$bgcol="";}
          echo "
          <tr $bgcol>
            <td>$no</td>
            <td>$data[jo_no]</td>
            <td>$data[item]</td>
            <td>".fn($data['qty'],2)."</td>
            <td>$data[unit]</td>
            <td>$data[curr]</td>
            <td>".fn($data['price'],2)."</td>";
            if($data['cancel']=="Y")
            { echo "<td>Canceled</td>"; }
            else
            { echo "<td></td>"; }
            if($data['cancel']=="N")
            { echo "
              <td>
                <a href='?mod=3ei&id=$data[id]'
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
    <i style='background-color:blue;color:yellow;'>Price PO > Price Costing</i>
  </div>
</div>
<?php } ?>