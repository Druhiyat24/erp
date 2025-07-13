<?php
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
# START CEK HAK AKSES KEMBALI
$akses = 1;
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
  $tax="";
  $id_supplier = "";
  $id_terms = "";
  $curr = "";
}
else
{ $query = mysql_query("SELECT a.*,s.curr FROM po_header a inner join po_item s on a.id=s.id_po
    where a.id='$id_po' limit 1");
  $data     = mysql_fetch_array($query);
  $pono     = $data['pono'];
  $podate   = fd_view($data['podate']);
  $etddate  = fd_view($data['etd']);
  $etadate  = fd_view($data['eta']);
  $expdate  = fd_view($data['expected_date']);
  $notes    = $data['notes'];
  $tax      = $data['tax'];
  $curr     = $data['curr'];
  $id_supplier = $data['id_supplier'];
  $id_terms    = $data['id_terms'];
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
        if (qtys[i].value > qtybtss[i].value)
        { qtyover = qtyover + 1; }
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
    else if (qtyover > 0) { alert('Qty Melebihi Kebutuhan'); valid = true; }
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
  function getListSupp()
  { var jenis_item = document.form.txtJItem.value;
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_po.php?modeajax=get_list_supp',
        data: "jenis_item=" +jenis_item,
        async: false
    }).responseText;
    if(html)
    { $("#cbosupp").html(html); }
  };
  function getJOList()
  { var id_supp = document.form.txtid_supplier.value;
    var jenis_item = document.form.txtJItem.value;
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_po.php?modeajax=get_list_jo',
        data: {id_supp: id_supp,jenis_item: jenis_item},
        async: false
    }).responseText;
    if(html)
    { $("#cboJO").html(html); }
  };
  function getJO()
  { var id_jo = $('#cboJO').val();
    var id_supp = document.form.txtid_supplier.value;
    var jenis_item = document.form.txtJItem.value;
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_po.php?modeajax=view_list_jo',
        data: {id_jo: id_jo,id_supp: id_supp,jenis_item: jenis_item},
        async: false
    }).responseText;
    if(html)
    {
        $("#detail_item").html(html);
    }
  };
</script>
<?php if ($mod=="3" or $mod=="3e") { ?>
<div class='box'>
  <div class='box-body'>
    <div class='row'>
      <form method='post' name='form' action=''>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Invoice No.</label>
            <!-- <input type='hidden' class='form-control' name='txtid' placeholder='Masukkan InvNo' > -->
            <input type='text' class='form-control' name='txtvono' placeholder='Masukkan InvNo' >
          </div>
          <div class='form-group'>
            <label>Consignee</label>
            <input type='text' class='form-control' name='txtconsi' placeholder='Masukkan Consignee' >
          </div>
          <div class='form-group'>
            <label>Shipper</label>
            <input type='text' class='form-control' name='txtshipp' placeholder='Masukkan Shipper' >
          </div>
					<div class='form-group'>
            <label>Notify Party</label>
						<input type='text' class='form-control' name='txtnotipy' placeholder='Masukkan NoPar' >
            <!-- <select class='form-control select2' style='width: 100%;'
              name='txtid_supplier' id='cbosupp' onchange='getJOList()'>
            </select> -->
          </div>
					<div class='form-group'>
            <label>Country of Origin</label>
            <input type='text' class='form-control' name='txtcoun' placeholder='Masukkan Cor' >
          </div>
    </div>
        <div class='col-md-3'>
					<div class='form-group'>
            <label>InvDate</label>
						<input type='text' class='form-control' id='datepicker2'
              name='txtetddate' placeholder='Masukkan ETD Date' >
          </div>
					<div class='form-group'>
            <label>Vessel Name</label>
            <input type='text' class='form-control' name='txtvesel' placeholder='.....'>
          </div>
					<div class='form-group'>
            <label>Port of Loading</label>
            <input type='text' class='form-control' name='txtpl' placeholder='.....'>
          </div>
					<div class='form-group'>
            <label>Port of Discharge</label>
            <input type='text' class='form-control' name='txtpd' placeholder='.....'>
          </div>
					<div class='form-group'>
            <label>Port of Entrance</label>
            <input type='text' class='form-control' name='txtpe' placeholder='.....'>
          </div>
			</div>
        <div class='col-md-3'>
					<div class='form-group'>
            <label>Lc Issue By</label>
            <input type='text' class='form-control' name='txtiib' placeholder='.....'>
          </div>
					<div class='form-group'>
            <label>Hs Code</label>
            <input type='text' class='form-control' name='txths' placeholder='.....'>
          </div>
          <div class='form-group'>
            <label>ETD</label>
          <input type='date' class='form-control' name='txtetd' placeholder='.....'>
          </div>
					<div class='form-group'>
            <label>ETA</label>
            <input type='date' class='form-control' name='txteta' placeholder='.....'>
          </div>
					<div class='form-group'>
            <label>Eta Lax</label>
            <input type='date' class='form-control' name='txtetl' placeholder='.....'>
          </div>
      </div>
		 			<div class='col-md-3'>
						<div class='form-group'>
	            <label>Route</label>
	            <input type='text' class='form-control' name='txtrut' placeholder='.....'>
	          </div>
						<div class='form-group'>
	            <label>Ship To</label>
	            <input type='text' class='form-control' name='txtshito' placeholder='.....'>
	          </div>
						<div class='form-group'>
	            <label>Nw</label>
	            <input type='text' class='form-control' name='txtnw' placeholder='.....'>
	          </div>
						<div class='form-group'>
	            <label>Gw</label>
	            <input type='text' class='form-control' name='txtgw' placeholder='.....'>
	          </div>
						<div class='form-group'>
	            <label>Measurement</label>
	            <input type='text' class='form-control' name='txtmea' placeholder='.....'>
	          </div>
					</div>
      <div class='col-md-3'>
  						<div class='form-group'>
	            <label>Lc No</label>
	            <input type='text' class='form-control' name='txticno' placeholder='.....'>
	          </div>
						<div class='form-group'>
	            <label>Id Pterms</label>
	            <input type='text' class='form-control' name='txtidp' placeholder='.....'>
	          </div>
        </div>
          <div class='col-md-3'>
						<div class='form-group'>
							<label>Seal No</label>
							<input type='text' class='form-control' name='txtseal' placeholder='.....'>
						</div>
            <div class='form-group'>
							<label>Shipped By</label>
							<input type='text' class='form-control' name='txtsb' placeholder='.....'>
						</div>
          </div>
      <div class='col-md-3'>
            <div class='form-group'>
              <label>No Container </label>
              <input type='text' class='form-control' name='txtcontaino' placeholder='.....'>
            </div>
            <div class='form-group'>
							<label>Manufacture Address</label>
							<textarea name='txtmanuf' cols="38" rows="2"></textarea>
						</div>

				</div>
        <div class='box-body'>
          <div id='detail_item'></div>
        </div>
        <div class='col-md-3'>
          <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
        </div>
      </form>
      <?php

        if (isset($_POST['submit'])) {

          $ivno 	  = $_POST['txtvono'];
          $invd     = $_POST['txtetddate'];
          $consi 	  = $_POST['txtconsi'];
          $shipp 	  = $_POST['txtshipp'];
          $notipy 	= $_POST['txtnotipy'];
          $coun 	  = $_POST['txtcoun'];
          $manuf    = $_POST['txtmanuf'];
          $vesel    = $_POST['txtvesel'];
          $pl       = $_POST['txtpl'];
          $pd       = $_POST['txtpd'];
          $pe       = $_POST['txtpe'];
          $icno     = $_POST['txticno'];
          $iib      = $_POST['txtiib'];
          $hs       = $_POST['txths'];
          $etd      = $_POST['txtetd'];
          $eta      = $_POST['txteta'];
          $etl      = $_POST['txtetl'];
          $idp      = $_POST['txtidp'];
          $shito    = $_POST['txtshito'];
          $rut      = $_POST['txtrut'];
          $sb       = $_POST['txtsb'];
          $nw       = $_POST['txtnw'];
          $gw       = $_POST['txtgw'];
          $mea      = $_POST['txtmea'];
          $containo = $_POST['txtcontaino'];
          $seal     = $_POST['txtseal'];

          $sqlsimpan = "INSERT INTO invoice_header(invno, invdate, consignee, shipper, notify_party, country_of_origin, manufacture_address, vessel_name,
          port_of_loading, port_of_discharge, port_of_entrance, lc_no, lc_issue_by, hs_code, etd, eta, eta_lax, id_pterms, shipped_by, route, ship_to, nw,
          gw, measurement, container_no, seal_no) VALUES('$ivno','$invd','$consi','$shipp','
          $notipy','$coun','$manuf','$vesel','$pl','$pd','$pe','$icno','$iib','$hs','$etd','$eta','
          $etl','$idp','$shito','$rut','$sb','$nw','$gw','$mea','$containo','$seal')";
          insert_log($sqlsimpan,$user);
          $_SESSION['msg']="Data Berhasil Disimpan";
          echo "<script>window.location.href='../shp/?mod=$mod';</script>";
        }
      ?>
    </div>
  </div>
</div>
<?php }
# END COPAS ADD
#if ($id_po=="") {
if ($mod=="3L") {
?>
<!-- <div class="box">
  <div class="box-header">
    <h3 class="box-title">List PO</h3>
    <a href='../pur/?mod=3' class='btn btn-primary btn-s'>
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
        <th>Buyer</th>
        <th>Action</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $query = mysql_query("select a.app,a.id,pono,podate,supplier,
          nama_pterms,tmppoit.buyer from po_header a inner join
          mastersupplier s on a.id_supplier=s.id_supplier inner join
          masterpterms d on a.id_terms=d.id
          inner join
          (select poit.id_jo,poit.id_po,ms.supplier buyer from po_item poit
          inner join jo_det jod on jod.id_jo=poit.id_jo
          inner join so on jod.id_so=so.id
          inner join act_costing ac on so.id_cost=ac.id
          inner join mastersupplier ms on ac.id_buyer=ms.id_supplier
          group by poit.id_po) tmppoit
          on tmppoit.id_po=a.id order by podate desc");
        $no = 1;
        while($data = mysql_fetch_array($query))
        { echo "<tr>";
            echo "<td>$no</td>";
            echo "<td>$data[pono]</td>";
            echo "<td>".fd_view($data['podate'])."</td>";
            echo "<td>$data[supplier]</td>";
            echo "<td>$data[nama_pterms]</td>";
            echo "<td>$data[buyer]</td>";
            echo "
            <td>
              <a $cl_ubah href='?mod=3e&id=$data[id]'
                data-toggle='tooltip' title='View'><i class='fa fa-eye'></i></a>";
              if($data['app']=="A")
              { echo " <a class='btn btn-info btn-s' href='pdfPO.php?id=$data[id]'
                data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i></a>";
              }
            echo "
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
