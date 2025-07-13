<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("quote_inq","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_pre = $_GET['id']; } else {$id_pre = "";}
$titlenya="Pre-Costing";
$mode="";
$mod=$_GET['mod'];
$id_item="";
$st_num="style='text-align: right;'";
# COPAS EDIT
if ($id_item=="")
{ $curr = "";
  $precost_no = "";
  $qty = "";
  $unit = "";
  $description = "";
  $fabric_cost = "";
  $accs_cost = "";
  $mfg_cost = "";
  $other_cost = "";
  $id_inq = "";
  $prod_group = "";
  $id_product = "";
  $profit_persen = "";
}
else
{ $query = mysql_query("SELECT * FROM pre_costing where id_item='$id_item' ORDER BY id_item ASC");
  $data = mysql_fetch_array($query);
  $curr = $data['curr'];
  $precost_no = $data['precost_no'];
  $qty = $data['qty'];
  $unit = $data['unit'];
  $description = $data['description'];
  $fabric_cost = $data['fabric_cost'];
  $accs_cost = $data['accs_cost'];
  $mfg_cost = $data['mfg_cost'];
  $other_cost = $data['other_cost'];
  $id_inq = $data['id_inq'];
  $prod_group = $data['prod_group'];
  $id_product = $data['id_product'];
  $profit_persen = $data['profit_persen'];
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
<script type='text/javascript'>
  function validasi()
  { var curr = document.form.txtcurr.value;
    var precost_no = document.form.txtprecost_no.value;
    var qty = document.form.txtqty.value;
    var unit = document.form.txtunit.value;
    var description = document.form.txtdescription.value;
    var fabric_cost = document.form.txtfabric_cost.value;
    var accs_cost = document.form.txtaccs_cost.value;
    var mfg_cost = document.form.txtmfg_cost.value;
    var other_cost = document.form.txtother_cost.value;
    var id_inq = document.form.txtid_inq.value;
    var id_product = document.form.txtid_product.value;
    var profit_persen = document.form.txtprofit_persen.value;
    
    if (curr == '') { document.form.txtcurr.focus(); swal({ title: 'Currency Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (qty == '') { document.form.txtqty.focus(); swal({ title: 'Qty Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (unit == '') { document.form.txtunit.focus(); swal({ title: 'Unit Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (description == '') { document.form.txtdescription.focus(); swal({ title: 'Description Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (fabric_cost == '') { document.form.txtfabric_cost.focus(); swal({ title: 'Fabric Cost Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (accs_cost == '') { document.form.txtaccs_cost.focus(); swal({ title: 'Accessories Cost Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (mfg_cost == '') { document.form.txtmfg_cost.focus(); swal({ title: 'Manufacturing Cost Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (other_cost == '') { document.form.txtother_cost.focus(); swal({ title: 'Others Cost Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (id_inq == '') { document.form.txtid_inq.focus(); swal({ title: 'Quotation # Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (id_product == '') { document.form.txtid_product.focus(); swal({ title: 'Product Item Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (profit_persen == '') { document.form.txtprofit_persen.focus(); swal({ title: 'Profit Tidak Boleh Kosong', $img_alert }); valid = false;}
    else valid = true;
    return valid;
    exit;
  }
</script>";
# END COPAS VALIDASI
# COPAS ADD
?>
<script type="text/javascript">
  function getProdItem(cri_item)
  { var html = $.ajax
    ({  type: "POST",
        url: "ajax_pre_cost.php?mdajax=get_prod_item",
        data: "cri_item=" +cri_item,
        async: false
    }).responseText;
    if(html)
    { $("#cbopr_it").html(html); }
  };
  function Ribuan()
  { var angkanya = document.form.txtqty.value;
    jQuery.ajax
    ({  
      url: "ajax_quote_inq.php?mdajax=format_ribuan",
      method: 'POST', data: {angkanya: angkanya},
      dataType: 'json', 
      success: function(response)
      { $('#txtqty').val(response[0]); },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
  function Calc_Total()
  { var costf = document.form.txtfabric_cost.value;
    var costa = document.form.txtaccs_cost.value;
    var costm = document.form.txtmfg_cost.value;
    var costo = document.form.txtother_cost.value;
    var curr = document.form.txtcurr.value;
    var profit = document.form.txtprofit_persen.value;
    jQuery.ajax
    ({
      url: 'ajax_pre_cost.php?mdajax=get_total',
      method: 'POST',
      data: {costf: costf,costa: costa,costm: costm,costo: costo,curr: curr,profit: profit},
      dataType: 'json', 
      success: function(response)
      { $('#txttotal').val(response[0]); 
        $('#txtfabric_cost').val(response[1]);
        $('#txtaccs_cost').val(response[2]);
        $('#txtmfg_cost').val(response[3]);
        $('#txtother_cost').val(response[4]);
        $('#txttotal').val(response[5]);
        $('#txtpro_idr').val(response[6]);
        $('#txtpro_usd').val(response[7]);
        $('#txtsell_idr').val(response[8]);
        $('#txtsell_usd').val(response[9]);
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
  function Calc_Profit()
  { var curr = document.form.txtcurr.value;
    var profit = document.form.txtprofit_persen.value;
    var totalcost = document.getElementById("txttotal").value;
    jQuery.ajax
    ({  
      url: "ajax_pre_cost.php?mdajax=get_profit",
      method: 'POST',
      data: {curr: curr,profit: profit,totalcost: totalcost},
      dataType: 'json',
      success: function(response)
      {
        $('#txtpro_idr').val(response[0]);
        $('#txtpro_usd').val(response[1]);
        $('#txtsell_idr').val(response[2]);
        $('#txtsell_usd').val(response[3]);
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
</script>

<div class='box'>
  <div class='box-body'>
    <div class='row'>
      <form method='post' name='form' action='s_precost.php?mod=<?php echo $mod; ?>&mode=$mode&id=<?php echo $id_pre; ?>' onsubmit='return validasi()'>
        <div class='col-md-3'>                
          <div class='form-group'>
            <label>Currency *</label>
            <select class='form-control select2' style='width: 100%;' name='txtcurr'>
              <?php 
                $sql = "select nama_pilihan isi,nama_pilihan tampil from 
                  masterpilihan where kode_pilihan='Curr'
                  and nama_pilihan in ('IDR','USD')";
                IsiCombo($sql,$curr,'Pilih Currency');
              ?>
            </select>
          </div>        
          <div class='form-group'>
            <label>Pre-Cost # *</label>
            <input type='text' class='form-control' readonly name='txtprecost_no' placeholder='Masukkan Pre-Cost #' value='<?php echo $precost_no;?>' >
          </div>        
          <div class='form-group'>
            <label>Qty *</label>
            <input type='text' class='form-control' name='txtqty' id='txtqty' placeholder='Masukkan Qty' onchange='Ribuan()'> value='<?php echo $qty;?>' >
          </div>          
          <div class='form-group'>
            <label>Unit *</label>
            <select class='form-control select2' style='width: 100%;' name='txtunit'>
              <?php 
                $sql = "select nama_pilihan isi,nama_pilihan tampil from 
                  masterpilihan where kode_pilihan='Satuan'";
                IsiCombo($sql,$unit,'Pilih Unit');
              ?>
            </select>
          </div>        
          <div class='form-group'>
            <label>Description *</label>
            <input type='text' class='form-control' name='txtdescription' placeholder='Masukkan Description' value='<?php echo $description;?>' >
          </div>
        </div>
        <div class='col-md-3'>        
          <div class='form-group'>
            <label>Fabric Cost *</label>
            <input type='text' class='form-control' <?php echo $st_num; ?> name='txtfabric_cost' id='txtfabric_cost' onchange='Calc_Total()' placeholder='Masukkan Fabric Cost' value='<?php echo $fabric_cost;?>' >
          </div>        
          <div class='form-group'>
            <label>Accessories Cost *</label>
            <input type='text' class='form-control' <?php echo $st_num; ?> name='txtaccs_cost' id='txtaccs_cost' onchange='Calc_Total()' placeholder='Masukkan Accessories Cost' value='<?php echo $accs_cost;?>' >
          </div>        
          <div class='form-group'>
            <label>Manufacturing Cost *</label>
            <input type='text' class='form-control' <?php echo $st_num; ?> name='txtmfg_cost' id='txtmfg_cost' onchange='Calc_Total()' placeholder='Masukkan Manufacturing Cost' value='<?php echo $mfg_cost;?>' >
          </div>        
          <div class='form-group'>
            <label>Others Cost *</label>
            <input type='text' class='form-control' <?php echo $st_num; ?> name='txtother_cost' id='txtother_cost' onchange='Calc_Total()' placeholder='Masukkan Others Cost' value='<?php echo $other_cost;?>' >
          </div>
          <div class='form-group'>
            <label>Total Cost *</label>
            <input type='text' class='form-control' <?php echo $st_num; ?> name='txttotal' readonly id='txttotal'>
          </div>
        </div>
        <div class='col-md-3'>          
          <div class='form-group'>
            <label>Quotation # *</label>
            <select class='form-control select2' style='width: 100%;' name='txtid_inq'>
              <?php 
                $sql = "select a.id isi,a.quote_no tampil from 
                  quote_inq a left join pre_costing s on a.id=s.id_inq 
                  where s.precost_no is null and a.status='FORECAST' order by a.id desc";
                IsiCombo($sql,$id_inq,'Pilih Quotation #');
              ?>
            </select>
          </div>          
          <div class='form-group'>
            <label>Product Group</label>
            <select class='form-control select2' style='width: 100%;' onchange='getProdItem(this.value)' name='txtprod_group'>
              <?php 
                $sql = "select product_group isi,product_group tampil from 
                  masterproduct group by product_group";
                IsiCombo($sql,$prod_group,'Pilih Product Group');
              ?>
            </select>
          </div>          
          <div class='form-group'>
            <label>Product Item *</label>
            <select class='form-control select2' style='width: 100%;' id='cbopr_it' name='txtid_product'>
            </select>
          </div>        
          <div class='form-group'>
            <label>Profit *</label>
            <input type='text' class='form-control' onchange='Calc_Profit()' name='txtprofit_persen' placeholder='Masukkan Profit' value='<?php echo $profit_persen;?>' >
          </div>
          <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
        </div>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Profit (IDR) *</label>
            <input type='text' class='form-control' id='txtpro_idr' readonly name='txtpro_idr'>
          </div>
          <div class='form-group'>
            <label>Profit (USD) *</label>
            <input type='text' class='form-control' id='txtpro_usd' readonly name='txtpro_usd'>
          </div>
          <div class='form-group'>
            <label>Selling Price (IDR) *</label>
            <input type='text' class='form-control' id='txtsell_idr' readonly name='txtsell_idr'>
          </div>
          <div class='form-group'>
            <label>Selling Price (USD) *</label>
            <input type='text' class='form-control' id='txtsell_usd' readonly name='txtsell_usd'>
          </div>  
        </div>
      </form>
    </div>
  </div>
</div><?php 
# END COPAS ADD REMOVE </DIV> TERAKHIR
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Data <?PHP echo $titlenya; ?></h3>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>
	      <th>No</th>
        <th>Currency</th>
        <th>Pre-Cost #</th>
        <th>Qty</th>
        <th>Unit</th>
        <th>Description</th>
        <th>Fabric Cost</th>
        <th>Accessories Cost</th>
        <th>Manufacturing Cost</th>
        <th>Others Cost</th>
        <th>Profit</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		    $query = mysql_query("SELECT * 
          FROM pre_costing ORDER BY id DESC limit 500");
			  $no = 1; 
				while($data = mysql_fetch_array($query))
			  { echo "<tr>";
				    echo "<td>$no</td>"; 
            echo "<td>$data[curr]</td>";
            echo "<td>$data[precost_no]</td>";
            echo "<td>$data[qty]</td>";
            echo "<td>$data[unit]</td>";
            echo "<td>$data[description]</td>";
            echo "<td>$data[fabric_cost]</td>";
            echo "<td>$data[accs_cost]</td>";
            echo "<td>$data[mfg_cost]</td>";
            echo "<td>$data[other_cost]</td>";
            echo "<td>$data[profit_persen]</td>";
          echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
			  ?>
      </tbody>
    </table>
  </div>
</div>