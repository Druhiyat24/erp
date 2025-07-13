<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$titlenya="Barang Jadi";
$mod=$_GET['mod'];
$akses = flookup("invoice","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
# COPAS EDIT
$trx_no="";
if ($trx_no=="")
{ $invno = "";
  $invdate = date("d M Y");
  $id_buyer = "";
  $consignee = "";
  $shipper = "";
  $notify_party = "";
  $wsno = "";
  $country_of_origin = "";
  $manufacture_address = "";
  $vessel_name = "";
  $port_of_loading = "";
  $port_of_discharge = "";
  $port_of_entrance = "";
  $lc_no = "";
  $lc_issue_by = "";
  $hs_code = "";
  $etd = "";
  $eta = "";
  $eta_lax = "";
  $id_pterms = "";
  $shipped_by = "";
  $route = "";
  $ship_to = "";
  $nw = "";
  $gw = "";
  $measurement = "";
  $container_no = "";
  $seal_no = "";
}
else
{ $query = mysql_query("SELECT * FROM invoice_header where id_item='$trx_no' ORDER BY id_item ASC");
  $data = mysql_fetch_array($query);
  $invno = $data['invno'];
  $invdate = fd_view($data['invdate']);
  $id_buyer = $data['id_buyer'];
  $consignee = $data['consignee'];
  $shipper = $data['shipper'];
  $notify_party = $data['notify_party'];
  $wsno = $data['wsno'];
  $country_of_origin = $data['country_of_origin'];
  $manufacture_address = $data['manufacture_address'];
  $vessel_name = $data['vessel_name'];
  $port_of_loading = $data['port_of_loading'];
  $port_of_discharge = $data['port_of_discharge'];
  $port_of_entrance = $data['port_of_entrance'];
  $lc_no = $data['lc_no'];
  $lc_issue_by = $data['lc_issue_by'];
  $hs_code = $data['hs_code'];
  $etd = $data['etd'];
  $eta = $data['eta'];
  $eta_lax = $data['eta_lax'];
  $id_pterms = $data['id_pterms'];
  $shipped_by = $data['shipped_by'];
  $route = $data['route'];
  $ship_to = $data['ship_to'];
  $nw = $data['nw'];
  $gw = $data['gw'];
  $measurement = $data['measurement'];
  $container_no = $data['container_no'];
  $seal_no = $data['seal_no'];
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
?>
<script type='text/javascript'>
  function validasi()
  { var invno = document.form.txtinvno.value;
    var invdate = document.form.txtinvdate.value;
    var id_buyer = document.form.txtid_buyer.value;
    var wsno = document.form.txtwsno.value;
    var port_of_loading = document.form.txtport_of_loading.value;
    var port_of_discharge = document.form.txtport_of_discharge.value;
    var port_of_entrance = document.form.txtport_of_entrance.value;
    var hs_code = document.form.txths_code.value;
    var id_pterms = document.form.txtid_pterms.value;
    var shipped_by = document.form.txtshipped_by.value;
    var route = document.form.txtroute.value;
    var measurement = document.form.txtmeasurement.value;
    if (invdate == '') { document.form.txtinvdate.focus(); swal({ title: 'Invoice Date Tidak Boleh Kosong', <?php echo $img_alert;?> }); valid = false;}
    else if (id_buyer == '') { document.form.txtid_buyer.focus(); swal({ title: 'Customer Tidak Boleh Kosong', <?php echo $img_alert;?> }); valid = false;}
    else if (wsno == '') { document.form.txtwsno.focus(); swal({ title: 'WS # Tidak Boleh Kosong', <?php echo $img_alert;?> }); valid = false;}
    else if (port_of_loading == '') { document.form.txtport_of_loading.focus(); swal({ title: 'Port Of Loading Tidak Boleh Kosong', <?php echo $img_alert;?> }); valid = false;}
    else if (port_of_discharge == '') { document.form.txtport_of_discharge.focus(); swal({ title: 'Port Of Discharge Tidak Boleh Kosong', <?php echo $img_alert;?> }); valid = false;}
    else if (port_of_entrance == '') { document.form.txtport_of_entrance.focus(); swal({ title: 'Port Of Entrance Tidak Boleh Kosong', <?php echo $img_alert;?> }); valid = false;}
    else if (hs_code == '') { document.form.txths_code.focus(); swal({ title: 'HS Code Tidak Boleh Kosong', <?php echo $img_alert;?> }); valid = false;}
    else if (id_pterms == '') { document.form.txtid_pterms.focus(); swal({ title: 'Payment Terms Tidak Boleh Kosong', <?php echo $img_alert;?> }); valid = false;}
    else if (shipped_by == '') { document.form.txtshipped_by.focus(); swal({ title: 'Shipped By Tidak Boleh Kosong', <?php echo $img_alert;?> }); valid = false;}
    else if (route == '') { document.form.txtroute.focus(); swal({ title: 'Route Tidak Boleh Kosong', <?php echo $img_alert;?> }); valid = false;}
    else if (measurement == '') { document.form.txtmeasurement.focus(); swal({ title: 'Measurement Tidak Boleh Kosong', <?php echo $img_alert;?> }); valid = false;}
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
  { var id_jo = document.form.txtwsno.value;
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_inv.php?modeajax=view_list_jo',
        data: {id_jo: id_jo},
        async: false
    }).responseText;
    if(html)
    {  
        $("#detail_item").html(html);
    }
  };
  function getListWS(cri_item)
  { var html = $.ajax
    ({  type: "POST",
        url: 'ajax_inv.php?modeajax=get_list_kp',
        data: "cri_item=" +cri_item,
        async: false
    }).responseText;
    if(html)
    { $("#cbokp").html(html); }
  };
</script>
<?php if($mod=="3") {?>
<form method='post' name='form' action='s_inv.php?mod=<?php echo $mod; ?>' 
  onsubmit='return validasi()'>
  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <div class='col-md-3'>              
          <div class='form-group'>
            <label>Invoice # *</label>
            <input type='text' class='form-control' readonly name='txtinvno' value='<?php echo $invno;?>' >
          </div>        
          <div class='form-group'>
            <label>Invoice Date *</label>
            <input type='text' class='form-control' id='datepicker1' name='txtinvdate' readonly value='<?php echo $invdate;?>' >
          </div>          
          <div class='form-group'>
            <label>Customer *</label>
            <select class='form-control select2' style='width: 100%;' name='txtid_buyer'
              onchange="getListWS(this.value)">
              <?php 
                $sql = "select id_supplier isi,supplier tampil from mastersupplier where tipe_sup='C' order by supplier";
                IsiCombo($sql,$id_buyer,'Pilih Customer');
              ?>
            </select>
          </div>        
          <div class='form-group'>
            <label>Consignee</label>
            <input type='text' class='form-control' name='txtconsignee' placeholder='Masukkan Consignee' value='<?php echo $consignee;?>' >
          </div>        
          <div class='form-group'>
            <label>Shipper</label>
            <input type='text' class='form-control' name='txtshipper' placeholder='Masukkan Shipper' value='<?php echo $shipper;?>' >
          </div>        
          <div class='form-group'>
            <label>Notiry Party</label>
            <input type='text' class='form-control' name='txtnotify_party' placeholder='Masukkan Notiry Party' value='<?php echo $notify_party;?>' >
          </div>          
          <div class='form-group'>
            <label>WS # *</label>
            <select class='form-control select2' style='width: 100%;' name='txtwsno' id='cbokp' onchange="getJO()">
            </select>
          </div>
          <div class='form-group'>
            <label>Faktur Pajak</label>
            <input type='text' class='form-control' name='faktur_pajak' placeholder='Masukkan Faktur Pajak' value='' >
          </div> 		  
        </div>
        <div class='col-md-3'>        
          <div class='form-group'>
            <label>Country Of Origin</label>
            <input type='text' class='form-control' name='txtcountry_of_origin' placeholder='Masukkan Country Of Origin' value='<?php echo $country_of_origin;?>' >
          </div>        
          <div class='form-group'>
            <label>Mfg Adress</label>
            <input type='text' class='form-control' name='txtmanufacture_address' placeholder='Masukkan Mfg Adress' value='<?php echo $manufacture_address;?>' >
          </div>        
          <div class='form-group'>
            <label>Vessel Name</label>
            <input type='text' class='form-control' name='txtvessel_name' placeholder='Masukkan Vessel Name' value='<?php echo $vessel_name;?>' >
          </div>          
          <div class='form-group'>
            <label>Port Of Loading *</label>
            <select class='form-control select2' style='width: 100%;' name='txtport_of_loading'>
              <?php 
                $sql = "select id isi,concat(kode_pel,' ',nama_pel) tampil from masterport";
                IsiCombo($sql,$port_of_loading,'Pilih Port Of Loading');
              ?>
            </select>
          </div>          
          <div class='form-group'>
            <label>Port Of Discharges *</label>
            <select class='form-control select2' style='width: 100%;' name='txtport_of_discharge'>
              <?php 
                $sql = "select id isi,concat(kode_pel,' ',nama_pel) tampil from masterport";
                IsiCombo($sql,$port_of_discharge,'Pilih Port Of Discharges');
              ?>
            </select>
          </div>          
          <div class='form-group'>
            <label>Port Of Entrances *</label>
            <select class='form-control select2' style='width: 100%;' name='txtport_of_entrance'>
              <?php 
                $sql = "select id isi,concat(kode_pel,' ',nama_pel) tampil from masterport";
                IsiCombo($sql,$port_of_entrance,'Pilih Port Of Entrances');
              ?>
            </select>
          </div>
          <div class='form-group'>
            <label>Container #</label>
            <input type='text' class='form-control' name='txtcontainer_no' placeholder='Masukkan Container #' value='<?php echo $container_no;?>' >
          </div>        
        </div>
        <div class='col-md-3'>        
          <div class='form-group'>
            <label>LC #</label>
            <input type='text' class='form-control' name='txtlc_no' placeholder='Masukkan LC #' value='<?php echo $lc_no;?>' >
          </div>        
          <div class='form-group'>
            <label>LC Issue By</label>
            <input type='text' class='form-control' name='txtlc_issue_by' placeholder='Masukkan LC Issue By' value='<?php echo $lc_issue_by;?>' >
          </div>          
          <div class='form-group'>
            <label>HS Code *</label>
            <select class='form-control select2' style='width: 100%;' name='txths_code'>
              <?php 
                $sql = "select id isi,concat(kode_hs,' ',nama_hs) tampil from masterhs";
                IsiCombo($sql,$hs_code,'Pilih HS Code');
              ?>
            </select>
          </div>        
          <div class='form-group'>
            <label>ETD</label>
            <input type='text' id='datepicker2' class='form-control' name='txtetd' placeholder='Masukkan ETD' value='<?php echo $etd;?>' >
          </div>        
          <div class='form-group'>
            <label>ETA</label>
            <input type='text' class='form-control' id='datepicker3' name='txteta' placeholder='Masukkan ETA' value='<?php echo $eta;?>' >
          </div>        
          <div class='form-group'>
            <label>ETA Lax</label>
            <input type='text' class='form-control' id='datepicker4' name='txteta_lax' placeholder='Masukkan ETA Lax' value='<?php echo $eta_lax;?>' >
          </div>
          <div class='form-group'>
            <label>Seal #</label>
            <input type='text' class='form-control' name='txtseal_no' placeholder='Masukkan Seal #' value='<?php echo $seal_no;?>' >
          </div>
        </div>
        <div class='col-md-3'>          
          <div class='form-group'>
            <label>Payment Terms *</label>
            <select class='form-control select2' style='width: 100%;' name='txtid_pterms'>
              <?php 
                $sql = "select id isi,concat(kode_pterms,' ',nama_pterms) tampil from 
                  masterpterms";
                IsiCombo($sql,$id_pterms,'Pilih Payment Terms');
              ?>
            </select>
          </div>          
          <div class='form-group'>
            <label>Shipped By *</label>
            <select class='form-control select2' style='width: 100%;' name='txtshipped_by'>
              <?php 
                $sql = "select id isi,shipdesc tampil from 
                  mastershipmode";
                IsiCombo($sql,$shipped_by,'Pilih Shipped By');
              ?>
            </select>
          </div>          
          <div class='form-group'>
            <label>Route *</label>
            <select class='form-control select2' style='width: 100%;' name='txtroute'>
              <?php 
                $sql = "select id isi,concat(kode_route,' ',nama_route) tampil from masterroute";
                IsiCombo($sql,$route,'Pilih Route');
              ?>
            </select>
          </div>        
          <div class='form-group'>
            <label>Ship To</label>
            <input type='text' class='form-control' name='txtship_to' placeholder='Masukkan Ship To' value='<?php echo $ship_to;?>' >
          </div>        
          <div class='form-group'>
            <label>Net Weight</label>
            <input type='text' class='form-control' name='txtnw' placeholder='Masukkan Net Weight' value='<?php echo $nw;?>' >
          </div>        
          <div class='form-group'>
            <label>Gross Weight</label>
            <input type='text' class='form-control' name='txtgw' placeholder='Masukkan Gross Weight' value='<?php echo $gw;?>' >
          </div>          
          <div class='form-group'>
            <label>Measurement *</label>
            <select class='form-control select2' style='width: 100%;' name='txtmeasurement'>
              <?php 
                $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan where kode_pilihan='Satuan'";
                IsiCombo($sql,$measurement,'Pilih Measurement');
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
      </div>
    </div>
  </div>
</form>
<?php } else if ($mod=="3v") {
# END COPAS ADD
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">List Invoice</h3>
    <a href='../shp/?mod=3' class='btn btn-primary btn-s'>
      <i class='fa fa-plus'></i> New
    </a>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead> 
        <tr>
          <th>Invoice #</th>
          <th>Invouce Date</th>
          <th>Customer</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $query = mysql_query("SELECT a.*,supplier,W.n_id id_ic FROM invoice_header a inner join 
          mastersupplier ms on a.id_buyer=ms.id_supplier
		  LEFT JOIN invoice_commercial W ON W.n_idinvoiceheader = a.id
		  
		  ");
        while($data = mysql_fetch_array($query))
        { echo "<tr>"; 
          echo "
            <td>$data[invno]</td>
            <td>$data[invdate]</td>
            <td>$data[supplier]</td>
            <td><a class='btn btn-success btn-s' href='../shp/?mod=InvoiceForm&noid=$data[id]'
              data-toggle='tooltip' title='$cub'><i class='fa fa-pencil'></i></a>"; 
            echo " <a class='btn btn-warning btn-s' href='PdfInvoiceCommercial.php?id=$data[id_ic]' 
                data-toggle='tooltip' title='Cetak'><i class='fa fa-print'></i></a></td>"; 
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<?php } ?>