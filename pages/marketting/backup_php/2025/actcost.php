<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("act_costing","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");
$kd_mkt = flookup("kode_mkt","userpassword","username='$user'");
$groupp = flookup("groupp","userpassword","username='$user'");
$all_buyer = flookup("all_buyer","userpassword","username='$user'");
if (isset($_GET['id'])) {$id_cs = $_GET['id']; } else {$id_cs = "";}
// if ($id_cs!="")
// {	
// 	$akses = flookup("cost_no","act_costing","username='$user' and id='$id_cs'");
// 	if ($akses=="") 
// 	{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>"; }
// }
if (isset($_GET['idcd'])) {$id_cd = $_GET['idcd']; } else {$id_cd = "";}
if (isset($_GET['idmf'])) {$id_mf = $_GET['idmf']; } else {$id_mf = "";}
if (isset($_GET['idot'])) {$id_ot = $_GET['idot']; } else {$id_ot = "";}
if (isset($_GET['pro'])) {$pro = $_GET['pro']; } else {$pro = "";}
$titlenya="Act-Costing";
$mode="";
$mod=$_GET['mod'];
$id_item=$id_cs;
$st_num="style='text-align: right;'";
if ($pro=="")
{	$sql="delete from upload_tpb where username='$user' and sesi='$sesi'";
	insert_log($sql,$user);
}
# COPAS EDIT
if ($id_item=="")
{	$buyer="";
	$prod_gr="";
	$prod_it="";
	$styleno="";
	$qty="";
	$unit="";
	$cost_no = "";
	$cost_date = date('d M Y');
	$kpno = "";
	$curr = "";
	$id_smode = "";
	$smv_min = "";
	$smv_sec = "";
	$book_min = "";
	$book_sec = "";
	$notes = "";
	$vat = "";
	$cfm = "";
	$comm = "";
	$deal = "";
	$ga = "";
	$deldate = "";
	$attach_file = "";
	# COSTING DETAIL
	$id_item_cd="";
	$price_cd=0;
	$price_idr_cd=0;
	$cons_cd=0;
	$unit_cd="";
	$allow_cd=0;
	$mat_sour_cd="";
	# MFG DETAIL
	$id_item_mf="";
	$price_mf=0;
	$price_idr_mf=0;
	$cons_mf=0;
	$unit_mf="";
	$allow_mf=0;
	$mat_sour_mf="";
	# OTH DETAIL
	$id_item_ot="";
	$price_ot=0;
	$price_idr_ot=0;
		

}
else
{	$query = mysql_query("SELECT * FROM act_costing where id='$id_item'");
//echo "QUERY: SELECT * FROM act_costing where id='$id_item'";
	$data = mysql_fetch_array($query);
	$buyer=$data['id_buyer'];
	$prod_gr=flookup("product_group","masterproduct","id=$data[id_product]");
	$prod_it=$data['id_product'];
	$styleno=$data['styleno'];
	$status=$data['status'];
	$qty=$data['qty'];
	$unit=$data['unit'];
	$tipe_ws=$data['type_ws'];
	$txtbrand=$data['brand'];
	$txtmaindest=$data['main_dest'];
	
	if ($pro=="")
	{	$cost_no = $data['cost_no'];
		$cost_date = fd_view($data['cost_date']);
		$kpno = $data['kpno'];
	}
	else
	{	$cost_no = "";
		$cost_date = date('d M Y');;
		$kpno = "";
	}
	$curr = $data['curr'];
	$id_smode = $data['id_smode'];
	$smv_min = $data['smv_min'];
	$smv_sec = $data['smv_sec'];
	$book_min = $data['book_min'];
	$book_sec = $data['book_sec'];
	$notes = $data['notes'];
	$vat = $data['vat'];
	$cfm = $data['cfm_price'];
	$comm = $data['comm_cost'];
	$deal = $data['deal_allow'];
	$ga = $data['ga_cost'];
	$deldate = fd_view($data['deldate']);
	$attach_file = $data['attach_file'];
	$rs=mysql_fetch_array(mysql_query("select * from masterrate  WHERE v_codecurr IN('COSTING3','COSTING6','COSTING8','COSTING12') AND curr='USD' and tanggal='".fd($deldate)."'"));
		$rate_jual=$rs['rate_jual'];
		$rate_beli=$rs['rate_beli'];
	$tot_cd = flookup("sum((if(jenis_rate='B',price/rate_beli,price)*cons)+((if(jenis_rate='B',price/rate_beli,price)*cons)*allowance/100))",
		"act_costing_mat a inner join act_costing s on a.id_act_cost=s.id
		inner join masterrate d on 'USD'=d.curr and s.deldate=d.tanggal","id_act_cost='$id_item'  AND d.v_codecurr IN('COSTING3','COSTING6','COSTING8','COSTING12')");
	$tot_cd_idr = flookup("sum((if(jenis_rate='J',price*rate_jual,price)*cons)+((if(jenis_rate='J',price*rate_jual,price)*cons)*allowance/100))",
		"act_costing_mat a inner join act_costing s on a.id_act_cost=s.id
		inner join masterrate d on 'USD'=d.curr and s.deldate=d.tanggal","id_act_cost='$id_item' AND  d.v_codecurr IN('COSTING3','COSTING6','COSTING8','COSTING12')");
	$tot_mf = flookup("sum((if(jenis_rate='B',price/rate_beli,price)*cons)+((if(jenis_rate='B',price/rate_beli,price)*cons)*allowance/100))",
		"act_costing_mfg a inner join act_costing s on a.id_act_cost=s.id
		inner join masterrate d on 'USD'=d.curr and s.deldate=d.tanggal","id_act_cost='$id_item'  AND d.v_codecurr IN('COSTING3','COSTING6','COSTING8','COSTING12')");
	$tot_mf_idr = flookup("sum((if(jenis_rate='J',price*rate_jual,price)*cons)+((if(jenis_rate='J',price*rate_jual,price)*cons)*allowance/100))",
		"act_costing_mfg a inner join act_costing s on a.id_act_cost=s.id
		inner join masterrate d on 'USD'=d.curr and s.deldate=d.tanggal","id_act_cost='$id_item'  AND d.v_codecurr IN('COSTING3','COSTING6','COSTING8','COSTING12')");
	$tot_ot = flookup("sum(if(jenis_rate='B',price/rate_beli,price))",
		"act_costing_oth a inner join act_costing s on a.id_act_cost=s.id
		inner join masterrate d on 'USD'=d.curr and s.deldate=d.tanggal","id_act_cost='$id_item' AND   d.v_codecurr IN('COSTING3','COSTING6','COSTING8','COSTING12')");
	$tot_ot_idr = flookup("sum(if(jenis_rate='J',price*rate_jual,price))",
		"act_costing_oth a inner join act_costing s on a.id_act_cost=s.id
		inner join masterrate d on 'USD'=d.curr and s.deldate=d.tanggal","id_act_cost='$id_item' AND d.v_codecurr IN('COSTING3','COSTING6','COSTING8','COSTING12')");
	$total_ga_cost = ($tot_cd + $tot_mf + $tot_ot) * $ga/100;
		
	$total_ga_cost_idr=($tot_cd_idr + $tot_mf_idr + $tot_ot_idr) * $ga/100;
	$total_cost = $tot_cd + $tot_mf + $tot_ot;
	$total_cost_IIDR = $tot_cd_idr + $tot_mf_idr + $tot_ot_idr + $total_ga_cost_idr;
	
	$total_cost_idr=$tot_cd_idr + $tot_mf_idr + $tot_ot_idr + $total_ga_cost_idr;
	$total_vat = (($total_cost+$total_ga_cost)*$vat/100);
	//echo "TOTAl VAT: $total_vat";
	$total_vat_idr = (($total_cost_idr+$total_ga_cost_idr)*$vat/100);
	$total_deal = (($total_cost+$total_vat+$total_ga_cost)*$deal/100);

	$total_deal_idr = (($total_cost_idr+$total_vat_idr+$total_ga_cost_idr)*$deal/100);
	//$total_cost_plus = $total_cost + $total_vat + $total_deal + $total_ga_cost;
	//$total_cost_plus_idr = $total_cost_idr + $total_vat_idr + $total_deal_idr + $g;
	# COSTING DETAIL
	$id_item_cd="";
	$price_cd=0;
	$price_idr_cd=0;
	$cons_cd=0;
	$unit_cd="";
	$allow_cd=0;
	$mat_sour_cd="";
	# MFG DETAIL
	$id_item_mf="";
	$price_mf=0;
	$price_idr_mf=0;
	$cons_mf=0;
	$unit_mf="";
	$allow_mf=0;
	$mat_sour_mf="";
	# OTH DETAIL
	$id_item_ot="";
	$price_ot=0;
	$price_idr_ot=0;
}

$tglcostingawal = date('Y-m-d');
$tglcostingakhir = date('Y-m-d');

if (isset($_POST['submitfilter']))
{
  $tglcostingawal = $_POST['tglcostingawal'];
  $tglcostingakhir = $_POST['tglcostingakhir'];
}



# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
if ($mod=="5")
{	echo "
	<script type='text/javascript'>

	
	

	
		function validasi()
		{	var id_buyer = document.form.txtid_buyer.value;
			var id_prod_it = document.form.txtid_product.value;
			var styleno = document.form.txtstyle.value;
			var id_smode = document.form.txtid_smode.value;
			var qty = document.form.txtqty.value;
			var smv_min = document.form.txtsmv_min.value;
			var smv_sec = document.form.txtsmv_sec.value;
			var book_min = document.form.txtbook_min.value;
			var book_sec = document.form.txtbook_sec.value;
			var deldate = document.form.txtdeldate.value;
			if (id_buyer == '') { swal({ title: 'Nama Buyer Tidak Boleh Kosong', $img_alert }); valid = false;}
			else if (styleno == '') { swal({ title: 'Style # Tidak Boleh Kosong', $img_alert }); valid = false;}
			else if (id_smode == '') { swal({ title: 'Ship Mode Tidak Boleh Kosong', $img_alert }); valid = false;}
			else if (qty == '') { swal({ title: 'Qty Tidak Boleh Kosong', $img_alert }); valid = false;}
			//else if (book_min == '') { document.form.txtbook_min.focus(); swal({ title: 'Book (Min) Tidak Boleh Kosong', $img_alert }); valid = false;}
			//else if (book_sec == '') { document.form.txtbook_sec.focus(); swal({ title: 'Book (Sec) Tidak Boleh Kosong', $img_alert }); valid = false;}
			else if (deldate == '') { document.form.txtdeldate.focus(); swal({ title: 'Delivery Date Tidak Boleh Kosong', $img_alert }); valid = false;}
			else valid = true;
			return valid;
			exit;
		}
	</script>";
}
# END COPAS VALIDASI
# COPAS ADD
?>
<script src="js/jquery.min.js" type="text/javascript"></script>
<script src="js/MasterSummary.Controller.js"></script>
<script type="text/javascript">
  function getProdItem(cri_item)
  { 

  var html = $.ajax
    ({  type: "POST",
        url: "ajax_pre_cost.php?mdajax=get_prod_item",
        data: "cri_item=" +cri_item,
        async: false
    }).responseText;
    if(html)
    { $("#cbopr_it").html(html); }
  };
  function CalcSMVMin()
  { var smvnya = document.form.txtsmv_min.value;
  	var qtynya = document.form.txtqty.value;
  	jQuery.ajax
    ({  
      url: "ajax_act_cost.php?mdajax=calc_smv_min",
      method: 'POST',
      data: {smvnya: smvnya,qtynya: qtynya},
      dataType: 'json',
      success: function(response)
      {	$('#txtsmv_sec').val(response[0]); 
      	$('#txtbook_sec').val(response[1]);
      	$('#txtbook_min').val(response[2]);
      	$('#txtsmv_min').val(response[3]);
    	},
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
/*  function USD_IDR(Data)
  { 
  
	if(Data == 'add'){
		  var pxnya = document.getElementById('txtprice_cd').value;
		  
	}
	if(Data == 'edit'){
		  var pxnya = document.getElementById('txtprice_cd2').value;
	}	
  	var tglnya = document.form.txtdeldate.value;
  	jQuery.ajax
    ({  
      url: "ajax_act_cost.php?mdajax=calc_usd_idr",
      method: 'POST',
      data: {pxnya: pxnya,tglnya: tglnya},
      dataType: 'json',
      success: function(response)
      {	$('#txtprice_idr_cd').val(response[0]);
      	$('#txtjrate_cd').val(response[1]); 
		$('#txtprice_idr_cd2').val(response[0]);
      	$('#txtjrate_cd2').val(response[1]); 		
		
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
  function USD_IDR_MF(data)
  { 
	console.log(data);
	if(data == 'add'){
		var pxnya = document.getElementById('txtprice_mf').value;
		console.log(pxnya);
	}
	if(data == 'edit'){
		 var pxnya = document.getElementById('txtprice_mf2').value;
		console.log(pxnya);
	}
  

  	var tglnya = document.form.txtdeldate.value;
	tanggalGlobal = document.form.txtdeldate.value;
  	jQuery.ajax
    ({  
      url: "ajax_act_cost.php?mdajax=calc_usd_idr",
      method: 'POST',
      data: {pxnya: pxnya,tglnya: tglnya},
      dataType: 'json',
      success: function(response)
      {	$('#txtprice_idr_mf').val(response[0]);
      	$('#txtjrate_mf').val(response[1]); 
		$('#txtprice_idr_mf2').val(response[0]);
      	$('#txtjrate_mf2').val(response[1]); 
console.log($('#txtprice_idr_mf2').val());		
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
  function USD_IDR_OT(Data)
  { 
  
  if(Data == 'add'){
	 var pxnya = document.getElementById('txtprice_ot').value; 
	  
  }
  if(Data == 'edit'){
	 var pxnya = document.getElementById('txtprice_ot2').value; 
	  
  } 
  	var tglnya = document.form.txtdeldate.value;
  	jQuery.ajax
    ({  
      url: "ajax_act_cost.php?mdajax=calc_usd_idr",
      method: 'POST',
      data: {pxnya: pxnya,tglnya: tglnya},
      dataType: 'json',
      success: function(response)
      {	$('#txtprice_idr_ot').val(response[0]);
      	$('#txtjrate_ot').val(response[1]); 
		$('#txtprice_idr_ot2').val(response[0]);
      	$('#txtjrate_ot2').val(response[1]);		
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
  function IDR_USD(Data)
  { 
	if(Data == 'add'){
		var pxnya = document.getElementById('txtprice_idr_cd').value;
	}
  	if(Data == 'edit'){
		var pxnya = document.getElementById('txtprice_idr_cd2').value;
	}
  
  var pxnya = document.getElementById('txtprice_idr_cd').value;
  	var tglnya = document.form.txtdeldate.value;
  	jQuery.ajax
    ({  
      url: "ajax_act_cost.php?mdajax=calc_idr_usd",
      method: 'POST',
      data: {pxnya: pxnya,tglnya: tglnya},
      dataType: 'json',
      success: function(response)
      {	$('#txtprice_cd').val(response[0]);
      	$('#txtjrate_cd').val(response[1]); 
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
  function IDR_USD_MF(Data)
  { 
	 
		  if(Data == "add"){
			var pxnya = document.getElementById('txtprice_idr_mf').value;
	  }
	  if(Data == "edit"){
		  
		  var pxnya = document.getElementById('txtprice_idr_mf2').value;
	  }

  	var tglnya = document.form.txtdeldate.value;
  	jQuery.ajax
    ({  
      url: "ajax_act_cost.php?mdajax=calc_idr_usd",
      method: 'POST',
      data: {pxnya: pxnya,tglnya: tglnya},
      dataType: 'json',
      success: function(response)
      {	$('#txtprice_mf').val(response[0]);
      	$('#txtjrate_mf').val(response[1]); 
		$('#txtprice_mf2').val(response[0]);
      	$('#txtjrate_mf2').val(response[1]); 		
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
  function IDR_USD_OT()
  { var pxnya = document.getElementById('txtprice_idr_ot').value;
  
  	var tglnya = document.form.txtdeldate.value;
  	jQuery.ajax
    ({  
      url: "ajax_act_cost.php?mdajax=calc_idr_usd",
      method: 'POST',
      data: {pxnya: pxnya,tglnya: tglnya},
      dataType: 'json',
      success: function(response)
      {	$('#txtprice_ot').val(response[0]);
      	$('#txtjrate_ot').val(response[1]); 
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
 */

 function CalcSMVSec()
  { var smvnya = document.form.txtsmv_sec.value;
  	var qtynya = document.form.txtqty.value;
  	jQuery.ajax
    ({  
      url: "ajax_act_cost.php?mdajax=calc_smv_sec",
      method: 'POST',
      data: {smvnya: smvnya,qtynya: qtynya},
      dataType: 'json',
      success: function(response)
      {	$('#txtsmv_min').val(response[0]); 
      	$('#txtbook_min').val(response[1]);
      	$('#txtbook_sec').val(response[2]);
      	$('#txtsmv_sec').val(response[3]);
  		},
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
  function Ribuan(angkanya)
  { jQuery.ajax
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
</script>
<?php if ($mod=="5") { ?>
<style>
.dataTables_wrapper .dataTables_filter {
    float: right;
    text-align: right;
    position: relative;
    margin-right: 80px;
}



.select2-results__options{
	width:500px;
	background-color:#ffffff;
	border:1px solid #0000FF
	
	
}
</style>


<div class='box'>
	<div class='box-body'>
		<div class='row'>
			<form method='post' name='form' enctype='multipart/form-data' action="s_actcost.php?mod=<?php echo $mod; ?>&id=<?php echo $id_cs; ?>&pro=<?php echo $pro; ?>" onsubmit='return validasi()'>
				<!-- <div class='col-md-3'>								
					<?php if ($id_cs=="") { ?>
					<img id="output" width="100%" height="auto">	
					<?php } else { $nm_filex="upload_files/costing/".$attach_file; ?>
					<img src="<?php echo $nm_filex; ?>" id="output" width="100%" height="auto">	
					<?php } ?>
					<script>
					  var loadFile = function(event) 
					  {	var output = document.getElementById('output');
					    output.src = URL.createObjectURL(event.target.files[0]);
					  };
					</script>
				</div> -->
				<div class='col-md-3'>				
					<div class='form-group'>
						<label>Buyer Name</label>
						<select class='form-control select2' style='width: 100%;' 
							name='txtid_buyer'>
			       <?php 
			        $sql = "select id_supplier isi,supplier tampil from mastersupplier where tipe_sup='C' order by supplier";
			        IsiCombo($sql,$buyer,'Pilih Buyer Name');
			       ?>
			      </select>
					</div>				
					<div class='form-group'>
						<label>Product Group</label>
						<select class='form-control select2' 
							style='width: 100%;' name='txtprod_group' onchange='getProdItem(this.value)'>
              <?php 
                $sql = "select product_group isi,product_group tampil from 
                  masterproduct group by product_group";
                IsiCombo($sql,$prod_gr,'Pilih Product Group');
              ?>
            </select>
					</div>				
					<div class='form-group'>
						<label>Product Item *</label>
            <?php if ($id_cs=="") { ?>
            <select class='form-control select2' style='width: 100%;' 
            	id='cbopr_it' name='txtid_product'>
            </select>
            <?php } else { ?>
            <select class='form-control select2' style='width: 100%;' 
            	id='cbopr_it' name='txtid_product'>
            	<?php
            	$sql = "select id isi,product_item tampil 
								from masterproduct where product_group='$prod_gr'";
							IsiCombo($sql,$prod_it,'Pilih Product Item');
							?>
            </select>
            <?php } ?>
					</div>				
					<div class='form-group'>
						<label>Style #</label>
						<input type='text' class='form-control' 
							name='txtstyle' value='<?php echo $styleno; ?>' placeholder='Masukkan Style #' >
					</div>
					<div class='form-group'>
						<label>Brand #</label>
						<input type='text' class='form-control' 
							name='txtbrand' required value='<?php echo $txtbrand; ?>' placeholder='Masukkan Brand #' >
					</div>					
					<div class='form-group'>
						<label>Costing #</label>
						<input type='text' class='form-control' readonly name='txtcost_no' placeholder='Masukkan Costing #' value='<?php echo $cost_no;?>' >
					</div>				
					<div class='form-group'>
						<label>Costing Date</label>
						<input type='text' class='form-control' readonly name='txtcost_date' placeholder='Masukkan Costing Date' value='<?php echo $cost_date;?>' >
					</div>
					<div class='form-group'>
						<label>Tipe WS</label>
					<select class='form-control select2' id='tipe_ws'  name='tipe_ws' value='<?php echo $tipe_ws;?> '>
						<option value="STD" <?php if($tipe_ws=="STANDARD"){echo "selected";} ?>>STANDARD</option>
						<option value="GLOBAL" <?php if($tipe_ws=="GLOBAL"){echo "selected";} ?>>GLOBAL</option>
					</select>  	
					</div>						
					<div class='form-group'>
						<label>WS #</label>
						<input type='text' class='form-control' readonly name='txtkpno' placeholder='Masukkan WS #' value='<?php echo $kpno;?>' >
					</div>										
				</div>
				<div class='col-md-3'>
					<div class='form-group'>
						<label>Curr *</label>
						<select class='form-control select2' style='width: 100%;' 
							name='txtcurr'  id="textCurr" onchange="handleCurrNew(this)" >
							<?php 
								$sql = "select nama_pilihan isi,nama_pilihan tampil from 
									masterpilihan where kode_pilihan='Curr'";
								IsiCombo($sql,$curr,'Pilih Currency');
							?>
						</select>
					</div>
					<div class='form-group'>
						<label>Confirm Price</label>
						<input type='text' class='form-control' name='txtcfm' onkeyup="handleCh(this)" id="confirmPrice" placeholder='Masukkan Confirm Price' value='<?php echo $cfm;?>' >
					</div>
					<div class='form-group'>
						<label>Ship Mode *</label>
						<select class='form-control select2' style='width: 100%;' name='txtid_smode'>
							<?php 
								$sql = "select id isi,shipmode tampil from mastershipmode";
								IsiCombo($sql,$id_smode,'Pilih Ship Mode');
							?>
						</select>
					</div>
					<div class='form-group'>
						<label>Qty</label>
						<input type='text' class='form-control' name='txtqty' id='txtqty' 
							value='<?php echo $qty; ?>' placeholder='Masukkan Qty' onchange='Ribuan(this.value)'>
					</div>
					<div class='form-group'>
						<label>Unit</label>
						<select class='form-control select2' style='width: 100%;' name='txtunit'>
							<?php 
								$sql = "select nama_pilihan isi,nama_pilihan tampil from 
									masterpilihan where kode_pilihan='Satuan'";
								IsiCombo($sql,$unit,'Pilih Unit');
							?>
						</select>
					</div>				
					<div class='form-group'>
						<label>SMV (Min) *</label>
						<input type='text' class='form-control' name='txtsmv_min' readonly id='txtsmv_min' placeholder='Masukkan SMV (Min)' onchange='CalcSMVMin()' value='<?php echo $smv_min;?>' >
					</div>				
					<div class='form-group'>
						<label>SMV (Sec) *</label>
						<input type='text' class='form-control' name='txtsmv_sec' readonly id='txtsmv_sec' placeholder='Masukkan SMV (Sec)' onchange='CalcSMVSec()' value='<?php echo $smv_sec;?>' >
					</div>				
					<div class='form-group'>
						<label>Book (Min) *</label>
						<input type='text' class='form-control' readonly name='txtbook_min' id='txtbook_min' placeholder='Masukkan Book (Min)' value='<?php echo $book_min;?>' >
					</div>				
					<div class='form-group'>
						<label>Book (Sec) *</label>
						<input type='text' class='form-control' readonly name='txtbook_sec' id='txtbook_sec' placeholder='Masukkan Book (Sec)' value='<?php echo $book_sec;?>' >
					</div>
				</div>
				<div class='col-md-3'>				
					<div class='form-group'>
						<label>Notes</label>
						<input type='text' class='form-control' name='txtnotes' placeholder='Masukkan Notes' value='<?php echo $notes;?>' >
					</div>				
					<div class='form-group'>
						<label>Delivery Date *</label>
						<input type='text' class='form-control' id='datepicker1' name='txtdeldate' placeholder='Masukkan Delivery Date' value='<?php echo $deldate;?>' >
					</div>				
					<div class='form-group'>
						<label>Status *</label>
						<select class='form-control select2' style='width: 100%;' 
							name='txtstatus'>
							<?php 
								$sql = "select nama_pilihan isi,nama_pilihan tampil from 
									masterpilihan where kode_pilihan='ST_CST'";
								IsiCombo($sql,$status,'Pilih Status');
							?>
						</select> 
					</div>
					<!-- <div class='form-group'>
						<label>Attach File *</label>
      			<input type="file" name='txtattach_file' accept="image/*" onchange="loadFile(event)">
					</div> -->
					<div class='form-group'>
						<label>Main Destination #</label>
						<input type='text' class='form-control' 
							name='txtmaindest' required value='<?php echo $txtmaindest; ?>' placeholder='Masukkan Main Destination #' >
					</div>	

					<div class='form-group'>
						<label>VAT (%)</label>
						<input type='text' onkeyup="handleCh(this)" id="myFat" class='form-control' name='txtvat' placeholder='Masukkan VAT' value='<?php echo $vat;?>' >
					</div>
					<div class='form-group'>
						<label>Deal Allowance (%)</label>
						<input type='text' class='form-control' id="myDeal" name='txtdeal'  placeholder='Masukkan Deal Allowance' value='<?php echo $deal;?>' readonly>

					</div>
					<div class='form-group'>
						<label>GA Cost (%)</label>
						<input type='text' class='form-control' name='txtga' placeholder='Masukkan GA Cost' id="gaCost" onkeyup="handleCh(this)" value='<?php echo $ga;?>' >
					</div>
					<div class='form-group'>
						<label>Commission Fee (%)</label>
						<input type='text' class='form-control' name='txtcomm' onkeyup="handleCh(this)" id="comFee" placeholder='Masukkan Commission Fee' value='<?php echo $comm;?>' >
					</div>
					<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
				</div>
			</form>
		</div>
	</div>
</div><?php }  
# END COPAS ADD REMOVE </DIV> TERAKHIR
?>
<?php if ($id_cs!="") { ?>







<!-- Modal Manufacturing - Complexity -->
<div class="modal fade" id="ComplexityModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<!-- 2019 05 07 -->
		
						<input type='hidden' class='form-control' 
					id='val_c' placeholder='val_c' 
					value='0' >
			<div class="modalCD">
			<div class='col-md-3'>								
			<div class='form-group'>
				<label>Complexity *</label>
							<select class='form-control select2' style='width: 100%;'  
					id='txtid_item_cd2'>
					<?php 
						$sql = "select e.id isi,concat(nama_group,' ',nama_sub_group,' ',nama_type,' ',nama_contents) 
              tampil from mastergroup a inner join mastersubgroup s on 
              a.id=s.id_group 
              inner join mastertype2 d on s.id=d.id_sub_group
              inner join mastercontents e on d.id=e.id_type
              where e.aktif = 'Y'";
						IsiCombo($sql,$id_item_cd,'Pilih Item Contents');
					?>
				</select>
			</div>				
		</div>
		<div class='col-md-3'>
			<!-- <div class='form-group'>
				<label>Price (USD) *</label>
				<input type='text' class='form-control' 
					id='txtprice_cd2' placeholder='Masukkan Price' 
					value='<?php echo $price_cd;?>' onchange='USD_IDR("edit")'>
			</div> -->
			<div class='form-group'>
				<label>Price (USD) *</label>
				<div style="display:flex;">
					<div style="width:75%;">
						<input type='text' class='form-control' 
						id='txtprice_cd2' placeholder='Masukkan Price' 
						value='<?php echo $price_cd;?>'>
					</div>
					<div>
						<button class="btn btn-sm btn-info" style="height: 100%;" href="#" onclick="USD_IDR('edit');">Calc</button>
					</div>
				</div>
			</div>
			<!-- <div class='form-group'>
				<label>Price (IDR)</label>
				<input type='text' class='form-control' 
					id='txtprice_idr_cd2' placeholder='Masukkan Price' 
					value='<?php echo $price_idr_cd;?>' onchange='IDR_USD("edit")'>
			</div> -->
			<div class='form-group'>
				<label>Price (IDR)</label>
				<div style="display:flex;">
					<div style="width:75%;">
						<input type='text' class='form-control' 
						id='txtprice_idr_cd2' placeholder='Masukkan Price' 
						value='<?php echo $price_idr_cd;?>'>
					</div>
					<div>
						<button class="btn btn-sm btn-info" style="height: 100%;" href="#" onclick="IDR_USD('edit')">Calc</button>
					</div>
				</div>
			</div>
		</div>
		<div class='col-md-3'>				
			<div class='form-group'>
				<label>Cons *</label>
				<input type='hidden' id='txthide_cd2'>
				<input type='hidden' id='txtjrate_cd2'>
				<input type='text' class='form-control' 
					id='txtcons_cd2' placeholder='Masukkan Cons' 
					value='<?php echo $cons_ot;?>' >
			</div>					
			<div class='form-group'>
				<label>Unit *</label>
				<select class='form-control select2' style='width: 100%;' 
					id='txtunit_cd2'>
					<?php 
						$sql = "select nama_pilihan isi,nama_pilihan tampil from 
							masterpilihan where kode_pilihan='Satuan'";
						IsiCombo($sql,$unit_cd,'Pilih Unit');
					?>
				</select>	
			</div>
		</div>
		<div class='col-md-3'>				
			<div class='form-group'>
				<label>Allowance (%) *</label>
				<input type='text' class='form-control' 
					id='txtallowance_cd2' placeholder='Masukkan Allowance' 
					value='<?php echo $allow_mf;?>' >
			</div>					
			<div class='form-group'>
				<label>Material Source *</label>
				<select class='form-control select2' style='width: 100%;' 
					id='txtmaterial_source_cd2'>
					<?php 
						$sql = "select nama_pilihan isi,nama_pilihan tampil from 
							masterpilihan where kode_pilihan='Mat_Sour'";
						IsiCombo($sql,$mat_sour_cd,'Pilih Material Source');
					?>
				</select>	
			</div>
		</div>
		</div>	
		
			<div class="modalMF">
			<div class='col-md-3'>								
			<div class='form-group'>
				<label>Complexity *</label>
				<select class='form-control select2' style='width: 100%;' 
					id='txtid_item_mf2'>
					<?php 
						$sql = "SELECT id isi,
              concat(cfcode,' ',cfdesc) tampil
              FROM mastercf 
              ORDER BY id DESC";
						IsiCombo($sql,$id_item_mf,'Pilih Complexity');
					?>
				</select>
			</div>				

		</div>
		<div class='col-md-3'>
			<div class='form-group'>
				<label>Price (USD) *</label>
				<input type='text' class='form-control' 
					id='txtprice_mf2' placeholder='Masukkan Price' 
					value='<?php echo $price_mf;?>' onchange='USD_IDR_MF("edit")'>
			</div>
			<div class='form-group'>
				<label>Price (IDR)</label>
				<input type='text' class='form-control' 
					id='txtprice_idr_mf2' placeholder='Masukkan Price' 
					value='<?php echo $price_idr_mf;?>' onchange='IDR_USD_MF("edit")'>
			</div>
		</div>
		<div class='col-md-3'>				
			<div class='form-group'>
				<label>Cons *</label>
				<input type='hidden' id='txthide_mf2'>
				<input type='hidden' id='txtjrate_mf2'>
				<input type='text' class='form-control' 
					id='txtcons_mf2' placeholder='Masukkan Cons' 
					value='<?php echo $cons_mf;?>' >
			</div>					
			<div class='form-group'>
				<label>Unit *</label>
				<select class='form-control select2' style='width: 100%;' 
					id='txtunit_mf2'>
					<?php 
						$sql = "select nama_pilihan isi,nama_pilihan tampil from 
							masterpilihan where kode_pilihan='Satuan'";
						IsiCombo($sql,$unit_mf,'Pilih Unit');
					?>
				</select>
			</div>
		</div>
		<div class='col-md-3'>				
			<div class='form-group'>
				<label>Allowance (%) *</label>
				<input type='text' class='form-control' 
					id='txtallowance_mf2' placeholder='Masukkan Allowance' 
					value='<?php echo $allow_mf;?>' >
			</div>					
			<div class='form-group'>
				<label>Material Source *</label>
				<select class='form-control select2' style='width: 100%;' 
					id='txtmaterial_source_mf2'>
					<?php 
						$sql = "select nama_pilihan isi,nama_pilihan tampil from 
							masterpilihan where kode_pilihan='Mat_Sour'";
						IsiCombo($sql,$mat_sour_mf,'Pilih Material Source');
					?>
				</select>
			</div>
		</div>
		</div>		

			<div class="modalOT">
			<div class='col-md-3'>								
			<div class='form-group'>
				<label>Complexity *</label>
				<select class='form-control select2' style='width: 100%;' 
					id='txtid_item_ot2'>
					<?php 
						$sql = "SELECT id isi,
              concat(otherscode,' ',othersdesc) tampil
              FROM masterothers 
              ORDER BY id DESC";
						IsiCombo($sql,$id_item_ot,'Pilih Others Cost');
					?>
				</select>
			</div>				

		</div>
		<div class='col-md-3'>
			<div class='form-group'>
				<label>Price (USD) *</label>
				<input type='text' class='form-control' 
					id='txtprice_ot2' placeholder='Masukkan Price' 
					value='<?php echo $price_ot;?>' onchange='USD_IDR_OT("edit")'>
									<input type='hidden' id='txthide_ot2'>
				<input type='hidden' id='txtjrate_ot2'>
			</div>
			<div class='form-group'>
				<label>Price (IDR)</label>
				<input type='text' class='form-control' 
					id='txtprice_idr_ot2' placeholder='Masukkan Price' 
					value='<?php echo $price_idr_ot;?>' onchange='IDR_USD_OT("edit")'>
			</div>
		</div>
		

		</div>		
		
	<!-- 2019 05 07 -->	
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="RoutesEdit('edit')">Save changes</button>
      </div>
    </div>
  </div>
</div>

<div class="box" >
  <div class="box-header">
  
  	<!--
  	<a href='#' class='add-mfg btn btn-info btn-s' 
  		data-id=<?php echo $id_cs; ?>&<?php echo $mod; ?>&<?php echo ""; ?>>
  		<i class='fa fa-plus'></i> Add Complexity
  	</a>-->
  </div>
  <div class="box-body"  style="overflow:scroll;height:500px">
  <div style="">
  
  </div>
	<div class="container">
	<!--<div style="position:absolute;bottom:0;height:3.2vh;width:auto;right:0;left:0;background-color:white">
	
	
	</div>
		<div style="position:absolute;height:5vh;width:3.5vh;right:0;top:0;min-height:100%;background-color:white">
	
	
	</div>
	-->
	
	<label> Pilih Category :</label>
<select name ="categorys"  id="categoryFilter"  onchange="filterCategory(this)" class="form-control" style="width:auto">
	<option value="-200" disabled>-- Choose Kategory --</option>
	<option value="-1">All</option>
	<option value="Costing Detail">Costing Detail</option>
	<option value="Manufacturing - Complexity">Manufacturing - Complexity</option>
	<option value="Other Cost">Other Cost</option>
</select>
		
	
	<table id="example1" class="display responsive" style="auto;font-size:13px;">
      <thead>
      <tr>
	    	    <th style="display:none">Category</th>
				<th style="display:none" >Item Code</th>
				<th style="display:none" >id</th>
				<th>Kode</th>
				<th style="width:50px !important;">Description</th>
				<th>Price (USD)</th>
				<th>Price (IDR)</th>
				<th>Cons</th>
				<th>UOM</th>
				<th>Allow (%)</th>
				<th>Value (USD)</th>
				<th>Value (IDR)</th>
				<th>Percent</th>
				<th>Material Source</th>
				<th width='10%'>Action</th>
			</tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		$q = "SELECT * FROM (

SELECT 	h.id as kode,d.nama_group cod,
		concat(nama_group,' ',nama_sub_group,' ',nama_type,' ',nama_contents)  it,
		 price price,
		 cons cons,
		 s.unit unit,
		 allowance allowance,
		 material_source material_source,
		 s.id id,
		 s.jenis_rate jenis_rate,
		 'Costing Detail' categorydescription,
		 a.id idCostings,
		 '1' category
		    	from act_costing a 
				inner join act_costing_mat s on 
		    	a.id=s.id_act_cost inner join mastergroup d inner join mastersubgroup f on 
          d.id=f.id_group 
          inner join mastertype2 g on f.id=g.id_sub_group
          inner join mastercontents h on g.id=h.id_type and s.id_item=h.id
		      where a.id='$id_cs' 
			  
	UNION
SELECT d.id as kode,d.cfcode cod,
		d.cfdesc it,
		price price,
		cons cons,
		s.unit unit,
		allowance allowance,
		material_source material_source,
		s.id id,
		s.jenis_rate jenis_rate,
		'Manufacturing-Complexity' categorydescription,
		a.id idCostings,
		'2' category
		    	from act_costing a inner join act_costing_mfg s on 
		    	a.id=s.id_act_cost inner join mastercf d on s.id_item=d.id
		    	where a.id='$id_cs'	

	UNION
SELECT d.id as kode,d.otherscode cod,
		d.othersdesc it,
		price,
		cons,
		s.unit,
		allowance,
		material_source,
		s.id,
		s.jenis_rate,
		'Other Costing' categorydescription,
		a.id idCostings,
		'3' category 
		    	from act_costing a inner join act_costing_oth s on 
		    	a.id=s.id_act_cost inner join masterothers d on s.id_item=d.id
		    	where a.id='$id_cs'	) X ORDER BY  X.idCostings ASC";
				
				#echo "$q";
		    $query = mysql_query($q); 

        $no = 1; 
		$sumusd = 0;
		$sumidr = 0;
		$sumCostingDetailIdr = 0;
		$sumCostingDetailUsd = 0;
		$sumManufacturingComplexityIdr = 0;
		$sumManufacturingComplexityUsd = 0;
		$sumOtherCostIdr = 0;
		$sumOtherCostUsd = 0;
				while($data = mysql_fetch_array($query))
			  { 
		  
		  									$urls = '';
						if($data['category']  == '1'){
							$urls = 'd_add_item_cs.php';
						}
						if($data['category'] == '2' ) {
							$urls = 'd_add_mfg_cs.php';
						}
						if($data['category'] == '3') {
							$urls = 'd_add_oth_cs.php';
						}	
		  
		  echo "<tr>";
				    echo "<td style='display:none'>$data[categorydescription]</td>"; 
            echo "<td style='display:none'>$data[cod]</td>";
			echo "<td style='display:none'>$data[id] </td>";
			echo "<td>$data[kode]</td>";
						echo "<td>$data[it]</td>";
						if ($data['jenis_rate']=="J")
						{	$px_idr=$data['price'] * $rate_jual; 
							$px_usd=$data['price'];
						}
						else
						{	
							$px_idr=$data['price']; 
							$px_usd=$data['price'] / $rate_beli;
						}
						echo "<td>".fn($px_usd,4)."</td>";
						echo "<td>".fn($px_idr,4)."</td>";
						if($data['category'] == 3 ){
							$cons = ' ';
						}else{
							$cons = fn($data['cons'],4);
							
						}
						echo "<td>".$cons."</td>";
						echo "<td>$data[unit]</td>";
						if($data['category'] == 3 ){
							$allow = ' ';
						}else{
							$allow = fn($data['allowance'],4);
							
						}						
						
						echo "<td>".$allow."</td>";
						$allowcs = ($px_usd*$data['cons']) * ($data['allowance']/100);
						$allowcs_idr = ($px_idr*$data['cons']) * ($data['allowance']/100);
						$valcs = ($px_usd * $data['cons']) + $allowcs;
						$valcs_idr = ($px_idr * $data['cons']) + $allowcs_idr;
						
						if($data['category'] == 3 ){
							$valcs = $px_usd;
							$valcs_idr = $px_idr;
						}
						
						echo "<td>".fn($valcs,4)."</td>";
						echo "<td>".fn($valcs_idr,4)."</td>";
						$persens = ($valcs_idr  / $total_cost_IIDR ) * 100;	
						echo "<td>".fn($persens,2)."</td>";
						echo "<td>$data[material_source]</td>";
						echo "
						<td>
							<a href='#' onclick='EditRoute($data[id],$data[category])' > <i class='fa fa-pencil' style='color:red;background-color:white' data-toggle='modal' data-target='#ComplexityModal' ></i> </a> &nbsp; | &nbsp;
					  	<a style='color:green' href='$urls?mod=$mod&mode=$mode&
					  		id=$id_cs&idd=$data[id]&mod=$mod'
		            $tt_hapus";?> 
		            onclick="return confirm('Apakah anda yakin akan menghapus ?')">
		            <?PHP echo $tt_hapus2."</a>
				  	</td>";
					echo "</tr>";
						//SUM COSTING DETAIL
						if($data['category'] == '1' ){
							$sumCostingDetailUsd = $sumCostingDetailUsd + $valcs_idr;
							$sumCostingDetailIdr = $sumCostingDetailIdr + $valcs;
						}
							if($data['category'] == '2' ){
							$sumManufacturingComplexityUsd = $sumManufacturingComplexityUsd + $valcs_idr;
							$sumManufacturingComplexityIdr = $sumManufacturingComplexityIdr + $valcs;
						}		
							if($data['category'] == '3' ){
							$sumOtherCostUsd = $sumOtherCostUsd + $px_usd;
							$sumOtherCostIdr = $sumOtherCostIdr + $px_idr;
							//echo "CATEGORY:$sumOtherCostUsd | $valcs_idr";
						}					
					$sumusd = $sumusd + $valcs;
					$sumidr = $sumidr + $valcs_idr; 
					
				  $no++; // menambah nilai nomor urut
				}	
					//Grand Total Yang bener yg ini 
					$total_cost_plus = $sumusd  + $total_deal + $total_ga_cost;
					$total_cost_plus_idr = $sumidr  + $total_deal_idr + $total_ga_cost;
					//Grand Total Yang bener yg ini 
					$valVat = $vat/100;
					$valDeal= $deal/100;
					$vatusd = $sumusd * ($valVat);
					$vatidr = $sumidr * ($valVat);
					$dealusd= $vatusd * ($valDeal);
					$dealidr= $vatidr * ($valDeal);


			  ?> 
      </tbody>
       <tfoot>
		  <tr>
				<td></td>

			  <td style="padding:0" > <select name ="category"  id="category"onchange="changecategory(this)" class="form-control" style="width:200px;display:none" >
						<option value="1">-- Choose Kategory --</option>

					</select>
						
				<div class="selectCD" style="display:none">
							<select class='form-control select2' style='width: 120px;padding:0;position:absolute' 
					id='txtid_item_cd'>
					<?php 
						$sql = "select e.id isi,concat(e.id,' ',nama_group,' ',nama_sub_group,' ',nama_type,' ',nama_contents) 
              tampil from mastergroup a inner join mastersubgroup s on 
              a.id=s.id_group 
              inner join mastertype2 d on s.id=d.id_sub_group
              inner join mastercontents e on d.id=e.id_type";
						IsiCombo($sql,$id_item_cd,'Pilih Item Contents');
					?>
				</select>	
				</div>			
			
			<div class="selectMC" style="display:none">
			<select class='form-control select2' style='width: 100%;' 
					id='txtid_item_mf'>
					<?php 
						$sql = "SELECT id isi,
              concat(cfcode,' ',cfdesc) tampil
              FROM mastercf 
              ORDER BY id DESC";
						IsiCombo($sql,$id_item_mf,'Pilih Complexity');
					?>
				</select> 
				
				</div>


			<div class ="selectOT" style="display:none">
				<select class='form-control select2' style='width: 100%;' 
					id='txtid_item_ot'>
					<?php 
						$sql = "SELECT id isi,
              concat(otherscode,' ',othersdesc) tampil
              FROM masterothers 
              ORDER BY id DESC";
						IsiCombo($sql,$id_item_ot,'Pilih Others Cost');
					?>
				</select>				
			</div>						
			  </td>
			<td style="padding:0;">	
						<div class="selectCD" style="display:none">
				<input type='text' class='form-control' 
						id='txtprice_cd' placeholder='Masukkan Price' 
						value='<?php echo $price_cd;?>' onchange='USD_IDR("add")'>			
			</div>
			
			<div class="selectMC" style="display:none">
				<input type='text' class='form-control' 
					id='txtprice_mf' placeholder='Masukkan Price' 
					value='<?php echo $price_mf;?>' onchange='USD_IDR_MF("add")'>			
			</div>			
			<div class="selectOT" style="display:none">
				<input type='hidden' id='txthide_ot'>
				<input type='hidden' id='txtjrate_ot'>			
				<input type='text' class='form-control' 
					id='txtprice_ot' placeholder='Masukkan Price' 
					value='<?php echo $price_ot;?>' onchange='USD_IDR_OT("add")'>			
			</div>			
			

				</td>
			<td style="padding:0;width:100px"> 
			
							<div class="selectCD" style="display:none">
				<input type='text' class='form-control' 
					id='txtprice_idr_cd' placeholder='Masukkan Price' 
					value='<?php echo $price_idr_cd;?>' onchange='IDR_USD("add")'>				
				</div>
				<div class="selectMC" style="display:none">
				<input type='text' class='form-control' 
					id='txtprice_idr_mf' placeholder='Masukkan Price' 
					value='<?php echo $price_idr_mf;?>' onchange='IDR_USD_MF("add")'>				
				
				</div>
				<div class="selectOT" style="display:none">
					<input type='text' class='form-control' 
					id='txtprice_idr_ot' placeholder='Masukkan Price' 
					value='<?php echo $price_idr_ot;?>' onchange='IDR_USD_OT("add")'>			
				</div>	
		
			</td>
			<td style="padding:0"> 				

				<div class="selectCD" style="display:none">
				<input type='hidden' id='txthide_cd'>
				<input type='hidden' id='txtjrate_cd'>
				<input type='text' class='form-control' 
					id='txtcons_cd' placeholder='Masukkan Cons' 
					value='<?php echo $cons_cd;?>' >
				</div>
				<div class="selectMC" style="display:none">
				<input type='hidden' id='txthide_mf'>
				<input type='hidden' id='txtjrate_mf'>
				<input type='text' class='form-control' 
					id='txtcons_mf' placeholder='Masukkan Cons' 
					value='<?php echo $cons_mf;?>' >
				</div>		
			</td>
			
	
			<td style="padding:0"> 
				<div class="selectCD" style="display:none">
				<select class='form-control select2' style='width: 100%;' 
					id='txtunit_cd'>
					<?php 
						$sql = "select nama_pilihan isi,nama_pilihan tampil from 
							masterpilihan where kode_pilihan='Satuan'";
						IsiCombo($sql,$unit_cd,'Pilih Unit');
					?>
				</select>				
				</div>
				<div class="selectMC" style="display:none">
				<select class='form-control select2' style='width: 100%;' 
					id='txtunit_mf'>
					<?php 
						$sql = "select nama_pilihan isi,nama_pilihan tampil from 
							masterpilihan where kode_pilihan='Satuan'";
						IsiCombo($sql,$unit_mf,'Pilih Unit');
					?>
				</select>				
				</div>		
			</td>			
			<td style="padding:0">	
					 <div class="selectCD" style="display:none">
				<input type='text' class='form-control' 
					id='txtallowance_cd' placeholder='Masukkan Allowance' 
					value='<?php echo $allow_cd;?>' >				 
			  </div>
			 <div class="selectMC" style="display:none">
				<input type='text' class='form-control' 
					id='txtallowance_mf' placeholder='Masukkan Allowance' 
					value='<?php echo $allow_mf;?>' >				 
			  </div>

					
			</td>
			  <td > 
	  
			  
			  
			  
			  </td>
			  <td style="padding:0">
			 

		  
			  </td>
			  <td>&nbsp;</td>
			  <td>				 <div class="selectCD" style="display:none">
				<select class='form-control select2' style='width: 100%;' 
					id='txtmaterial_source_cd'>
					<?php 
						$sql = "select nama_pilihan isi,nama_pilihan tampil from 
							masterpilihan where kode_pilihan='Mat_Sour'";
						IsiCombo($sql,$mat_sour_cd,'Pilih Material Source');
					?>
				</select>				 
				 </div>			  
				 <div class="selectMC" style="display:none">
			  <select class='form-control select2' style='width: 70%;' 
					id='txtmaterial_source_mf'>
					<?php 
						$sql = "select nama_pilihan isi,nama_pilihan tampil from 
							masterpilihan where kode_pilihan='Mat_Sour'";
						IsiCombo($sql,$mat_sour_mf,'Pilih Material Source');
					?>
				</select>				 
				 </div></td>
			  <td>				<a href="#" onclick='Routes("add")' />Add Item </a>  </td>
			  <td>	

</td>

			  </tr>
			
        </tfoot>  
    </table>
	</div>
	
	<div class="col-md-6" style="padding=0;border-bottom:1px solid #000000; border-top:1px solid #000000;font-size:13px">
		<div class="col-md-4" style="padding=0">
			Category :
		</div>
		<div class="col-md-4" style="padding=0">
			(USD)
		</div>
		<div class="col-md-4" style="padding=0">
			(IDR)
		</div>		
	</div><div class="row">
	
	</div>
	<div class="col-md-6" style="padding=0;border-bottom:1px solid #000000;font-size:13px">
		<div class="col-md-4" style="padding=0">
			Costing Detail 
		</div>
		<div class="col-md-4" style="padding=0">
			<?php echo fn($sumCostingDetailIdr,4) ?>
		</div>
		<div class="col-md-4" style="padding=0">
			<?php echo fn($sumCostingDetailUsd,4) ?>
		</div>		
	</div>	
	<div class="row">
	
	</div>
	<div class="col-md-6" style="padding=0;border-bottom:1px solid #000000;font-size:13px" >
		<div class="col-md-4" style="padding=0">
			Manufacturing-Complexity 
		</div>
		<div class="col-md-4" style="padding=0">
			<?php echo fn($sumManufacturingComplexityIdr,4) ?>
		</div>
		<div class="col-md-4" style="padding=0">
			<?php echo fn($sumManufacturingComplexityUsd,4) ?>
		</div>		
	</div>	
<div class="row">
	
	</div>	
	<div class="col-md-6" style="padding=0;border-bottom:1px solid #000000;font-size:13px">
		<div class="col-md-4" style="padding=0">
			Other Cost 
		</div>
		<div class="col-md-4" style="padding=0">
			<?php echo fn($sumOtherCostUsd,4) ?>
		</div>
		<div class="col-md-4" style="padding=0" id="subTotalidr">
			
			<?php echo fn($sumOtherCostIdr,4) ?>
		</div>		
	</div>		
		<div class="row">
	
	</div>
	<div class="col-md-6" style="padding=0;border-bottom:1px solid #000000;font-size:13px">
		<div class="col-md-4" style="padding=0">
			SubTotal
		</div>
		<div class="col-md-4" style="padding=0" id="subTotalusd">
			<?php echo fn($sumusd,4) ; // echo fn($tot_mf,4); 
	
			
			
			?>
		</div>
		<div class="col-md-4" style="padding=0">
			<?php echo fn($sumidr,4); ?>
		</div>		
	</div>		
	<div class="row">
	
	</div>

		
  </div>
</div>
<div class="box">


  	<table width="70%" style="font-weight:bold;">
	  		<tr>
  			<td>Confirm Price(USD)- </td>
  			<td>&nbsp:&nbsp</td>
  			<td id="footerConfirmPriceUsd"><?php echo '0'; ?></td>
  			<td>&nbsp &nbsp </td>
  			<td>Confirm Price(IDR) </td>
  			<td>&nbsp:&nbsp</td>
  			<td id="footerConfirmPriceIdr"><?php echo "0" ?></td>
			</tr>
		  		<tr>
	
  			<td>&nbsp;&nbsp </td>
  			<td>&nbsp;&nbsp </td>
  			<td >&nbsp;</td>
			</tr>			
			
	  		<tr>
  			<td>G&A(%)- </td>
  			<td>&nbsp:&nbsp</td>
  			<td id="footergapercent"><?php echo '0'; ?></td>
			</tr>
	  		<tr>
  			<td>Commission Fee(%)</td>
  			<td>&nbsp:&nbsp</td>
  			<td id="footercommissionfee"><?php echo '0'; ?></td>
			</tr>	
	  		<tr>
  			<td>Vat(%)</td>
  			<td>&nbsp:&nbsp</td>
  			<td id="footervatpercent"><?php echo '0'; ?></td>
			</tr>
	
	  		<tr>
  			<td>&nbsp;&nbsp</td>
  			<td>&nbsp;&nbsp</td>
  			<td >&nbsp;</td>
			</tr>				
			
			
  		<tr style="display:none">
  			<td>SubTotal (USD)</td>
  			<td>&nbsp:&nbsp</td>
  			<td id="footerSubtotal"><?php echo fn($sumusd,4); ?></td>
  			<td>&nbsp &nbsp </td>
  			<td>SubTotal (IDR)</td>
  			<td>&nbsp:&nbsp</td>
  			<td id="footerSubtotalIdr"><?php echo fn($sumidr,4); ?></td>
  		</tr>	

  		<tr>
	
  			<td>Costing Detail (USD)</td>
  			<td>&nbsp:&nbsp</td>
  			<!--<td ><?php echo fn($sumCostingDetailUsd,4); ?></td>-->
			<td ><?php echo fn($sumCostingDetailIdr,4); ?></td>
  			<td>&nbsp &nbsp</td>
  			<td>Costing Detail (IDR)</td>
  			<td>&nbsp:&nbsp</td>
  			<!--<td ><?php echo fn($sumCostingDetailIdr,4); ?></td>-->
			<td ><?php echo fn($sumCostingDetailUsd,4); ?></td>
  		</tr>				
  		<tr>
	
  			<td>Manufacturing-Complexity (USD)</td>
  			<td>&nbsp:&nbsp</td>
  			<!--<td ><?php echo fn($sumManufacturingComplexityUsd,4); ?></td>-->
			<td ><?php echo fn($sumManufacturingComplexityIdr,4); ?></td>
  			<td>&nbsp &nbsp </td>
  			<td>Manufacturing-Complexity (IDR)</td>
  			<td>&nbsp:&nbsp</td>
  			<!--<td ><?php echo fn($sumManufacturingComplexityIdr,4); ?></td>-->
			<td ><?php echo fn($sumManufacturingComplexityUsd,4); ?></td>
  		</tr>			

  		<tr>
	
  			<td>Other Cost (USD)</td>
  			<td>&nbsp:&nbsp</td>
  			<td ><?php echo  fn($sumOtherCostUsd,4); ?></td>
  			<td>&nbsp &nbsp</td>
  			<td>Other Cost (IDR)</td>
  			<td>&nbsp:&nbsp</td>
  			<td ><?php echo fn($sumOtherCostIdr,4); ?></td>
  		</tr>	
		  		<tr>
	
  			<td>&nbsp;&nbsp </td>
  			<td>&nbsp;&nbsp </td>
  			<td >&nbsp;</td>
			</tr>			
			
  		<tr>
	
  			<td>G & A (USD)</td>
  			<td>&nbsp:&nbsp</td>
  			<td id="footergacost"><?php echo "0"; ?></td>
  			<td>&nbsp &nbsp</td>
  			<td>G & A (IDR)</td>
  			<td>&nbsp:&nbsp</td>
  			<td id="footergacostidr"><?php echo "0"; ?></td>
  		</tr>				
			
			
			
	  		<tr>
	
  			<td>Commission Fee(USD) </td>
  			<td>&nbsp:&nbsp</td>
  			<td id="footerCommissionFee"><?php echo '0'; ?></td>
  			<td>&nbsp &nbsp </td>
  			<td>Commission Fee(IDR)</td>
  			<td>&nbsp;:&nbsp </td>
  			<td id="footerCommissionFeeidr"><?php echo "0" ?></td>
			</tr>			
			
		  		<tr>
  			<td>Total Costing (USD)</td>
  			<td>&nbsp:&nbsp</td>
  			<td id="footerTotalCosting"><?php echo "0"; ?></td>
  			<td>&nbsp &nbsp</td>
  			<td>Total Costing (IDR)</td>
  			<td>&nbsp:&nbsp</td>
  			<td id="footerTotalCostingIdr"><?php echo "0"; ?></td>
  		</tr>	
	

  		<tr>
  			<td>VAT (USD)</td>
  			<td>&nbsp:&nbsp</td>
  			<td id="footerVatUsd"><?php echo  "0" //fn($vatusd,4); ?></td>
  			<td>&nbsp &nbsp</td>
  			<td>VAT (IDR)</td>
  			<td >&nbsp:&nbsp</td>
  			<td id="footerVatIdr"><?php echo "0" //fn($vatidr,4); ?></td>
  		</tr>
  		<tr>
  			<td>Grand Total After Vat(USD)</td>
  			<td>&nbsp:&nbsp</td>
  			<td id="footerGrandTotal"><?php echo "0"; ?></td>
  			<td>&nbsp &nbsp</td>
  			<td>Grand Total After Vat(IDR)</td>
  			<td>&nbsp:&nbsp</td>
  			<td id="footerGrandTotalIdr"><?php echo "0"; ?></td>
  		</tr>		
		
  		<tr>
  			<td>Deal Allowance (USD)</td>
  			<td>&nbsp:&nbsp</td>
  			<td id="footerAllowanceValUsd"><?php echo "0"; ?></td>
  			<td>&nbsp &nbsp</td>
  			<td >Deal Allowance (IDR)</td>
  			<td>&nbsp:&nbsp</td>
  			<td  id="footerAllowanceValIdr"><?php echo "0"; ?></td>
  		</tr>		
		
  		<tr>
  			<td>Deal Allowance (%)</td>
  			<td>&nbsp:&nbsp</td>
  			<td id="footerAllowancepercent"><?php echo "0"; ?></td>
  			<td>&nbsp &nbsp </td>
  			<td>Deal Allowance (%)</td>
  			<td>&nbsp:&nbsp</td>
  			<td  id="footerAllowancepercentIdr"><?php echo "0"; ?></td>
  		</tr>




  	</table>
	

  </div>
</div>
<?php } else if ($mod=="5L") { ?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">List Costing</h3>
    <a href='../marketting/?mod=5' class='btn btn-primary btn-s'>
  		<i class='fa fa-plus'></i> New
  	</a>
  </div>

<div class='row'>
    <form action="" method="post">

    <div class="box-header">
      <div class='col-md-2'>                            
        <label>Tgl Costing (Awal) : </label>
        <input type='date' class='form-control' id='tglcostingawal' name='tglcostingawal' placeholder='Masukan Tgl Costing' 
        value='<?php echo $tglcostingakhir;?>'>    
      </div>
      <div class='col-md-2'>                            
        <label>Tgl Costing (Akhir) : </label>
        <input type='date' class='form-control' id='tglcostingakhir' name='tglcostingakhir' placeholder='Masukkan Tgl Costing' 
        value='<?php echo $tglcostingakhir;?>'>    
      </div>      
      <div class='col-md-3'>
          <div>
          <br>
              <button type='submit' name='submitfilter' class='btn btn-primary'>Tampilkan</button>              
          </div>         
      </div>

   </div>
    </form>
  </div>


  <div class="box-body">
  	<table id="examplefix4New" class="display responsive" style="width:100%;font-size:13px;">
      <thead>
      <tr>
	    	<th>Costing #</th>
				<th>Costing Date</th>
				<th>Buyer</th>
				<th>Brand</th>
				<th>Style #</th>
				<th>WS #</th>
				<th>Product Group</th>
				<th>Product Item</th>
				<th>Qty</th>
				<th>Confirm Price</th>
				<th>Delv. Date</th>
				<th>Status</th>
				<th>Created By</th>
				<th width='14%'>Action</th>
			</tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
//if ($groupp=="DIREKTUR" or $all_buyer=="1") { $sql_where=""; } else { $sql_where="where d.kode_mkt='$kd_mkt'"; }
	/*	    $sql="select a.kpno,a.brand,a.app1,a.id,cost_no,cost_date,supplier,styleno,
		    	qty,deldate,fullname,status,mp.product_item,mp.product_group,
		    	a.username,a.dateinput from act_costing a inner join mastersupplier s 
		    	on a.id_buyer=s.id_supplier inner join userpassword d 
		    	on a.username=d.username inner join masterproduct mp 
		    	on a.id_product=mp.id
		    	$sql_where order by cost_date desc";
			*/	#echo $sql;

// RMN
// if ($groupp=="DIREKTUR" or $all_buyer=="1") { $sql_where=""; } else { $sql_where="where d.kode_mkt='$kd_mkt' and cost_date >= '$tglcosting'"; }	


	/*	    $sql="select a.id,a.brand,cost_no,cost_date,supplier,styleno,
		    	qty,deldate,fullname,status,mp.product_item,mp.product_group,
		    	a.username from act_costing a inner join mastersupplier s 
		    	on a.id_buyer=s.id_supplier inner join userpassword d 
		    	on a.username=d.username inner join masterproduct mp 
		    	on a.id_product=mp.id
		    	where d.kode_mkt='$kd_mkt' ORDER BY cost_date DESC"; 
				*/
$sql="select a.id,cost_no,a.brand,cost_date,supplier,styleno,
		    	qty,deldate,fullname,status,mp.product_item,mp.product_group, a.cfm_price,
		    	a.username,kpno from act_costing a inner join mastersupplier s 
		    	on a.id_buyer=s.id_supplier inner join userpassword d 
		    	on a.username=d.username inner join masterproduct mp 
		    	on a.id_product=mp.id
		    	where cost_date >= '$tglcostingawal' and cost_date <= '$tglcostingakhir' ORDER BY id DESC";				
#echo $sql;				
				$result=mysql_query($sql);
				while($rs = mysql_fetch_array($result))
			  {	echo "
					<tr>
						<td>$rs[cost_no]</td>
						<td>".fd_view($rs['cost_date'])."</td>
						<td>$rs[supplier]</td>
						<td>$rs[brand]</td>
						<td>$rs[styleno]</td>
						<td>$rs[kpno]</td>
						<td>$rs[product_group]</td>
						<td>$rs[product_item]</td>
						<td>".fn($rs['qty'],0)."</td>
						<td>$rs[cfm_price]</td>
						<td>".fd_view($rs['deldate'])."</td>
						<td>$rs[status]</td>
						<td>$rs[fullname]</td>
						<td>
							<a class='btn-s' href='pdfCost.php?id=$rs[id]'
							data-toggle='tooltip' title='Save PDF' target='_blank'><i class='fa fa-file-pdf-o'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	            if ($rs['username']==$user)
	            {	echo " <a class='btn-s' href='?mod=5&id=$rs[id]'
	              data-toggle='tooltip' title='View'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;"; 
	            }
	            echo "
	            <a class=' btn-s' href='?mod=5&id=$rs[id]&pro=Copy'
					      data-toggle='tooltip' title='Copy'><i class='fa fa-copy'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					    <a class=' btn-s' href='xlsCost.php?id=$rs[id]'
					      data-toggle='tooltip' title='Save XLS' target='_blank'><i class='fa fa-file-excel-o'></i></a>
					  </td>
					</tr>";
				}
			  ?>
      </tbody>
    </table>
  </div>
</div>


<?php } ?>
<script>

   $(document).ready(function() {
    var table = $('#examplefix4New').DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,
        paging: true,
		//order: [1, "desc" ],
		ordering: false,
		columnDefs : [{"targets":1, "type":"date","targets":8, "type":"date"}],
        pageLength: 20,
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        }
    });
  }); 

function handleCurr(){
	setDealAllowance();
}

function formatDollar(value, separator) {
    if (typeof value == "undefined") return "0";
    if (typeof separator == "undefined" || !separator) separator = ",";
 
    return value.toString()
                .replace(/[^\d]+/g, "")
                .replace(/\B(?=(?:\d{3})+(?!\d))/g, separator);
}



/*
setTimeout(function(){
	setDealAllowance(); 
},5000)
*/	


	function edit_cd(id_cd)
  {		
		var id_cs = <?php echo "'".$id_cs."'"; ?>;
  	jQuery.ajax
    ({  
      url: "ajax_act_cost.php?mdajax=edit_cost_detail",
      method: 'POST',
      data: {id_cs: id_cs,id_cd: id_cd},
      dataType: 'json',
      success: function(response)
      {	$('#txtid_item_cd').select2('val',[response[0]]); 
      	$('#txtprice_cd').val(response[1]);
      	$('#txtcons_cd').val(response[2]);
      	$('#txtunit_cd').select2('val',[response[3]]);
      	$('#txtmaterial_source_cd').select2('val',[response[4]]); 
      	$('#txtallowance_cd').val(response[5]);
      	$('#txthide_cd').val(id_cd);
      	$('#txtprice_idr_cd').val(response[6]);
      	$('#txtjrate_cd').val(response[7]);
		
		$('#txtid_item_cd2').select2('val',[response[0]]); 
      	$('#txtprice_cd2').val(response[1]);
      	$('#txtcons_cd2').val(response[2]);
      	$('#txtunit_cd2').select2('val',[response[3]]);
      	$('#txtmaterial_source_cd2').select2('val',[response[4]]); 
      	$('#txtallowance_cd2').val(response[5]);
      	$('#txthide_cd2').val(id_cd);
      	$('#txtprice_idr_cd2').val(response[6]);
      	$('#txtjrate_cd2').val(response[7]);		
		
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
  function edit_mf(id_cd)
  {	event.preventDefault();
//ComplexityModal.showModal();  
 // var id_cs = <?php echo $id_cs; ?>;
  var id_cs = <?php echo "'".$id_cs."'"; ?>;
  
  	jQuery.ajax
    ({  
      url: "ajax_act_cost.php?mdajax=edit_cost_mf",
      method: 'POST',
      data: {id_cs: id_cs,id_cd: id_cd},
      dataType: 'json',
      success: function(response)
      {	$('#txtid_item_mf').select2('val',[response[0]]); 
      	$('#txtprice_mf').val(response[1]);
      	$('#txtcons_mf').val(response[2]);
      	$('#txtunit_mf').select2('val',[response[3]]);
      	$('#txtmaterial_source_mf').select2('val',[response[4]]); 
      	$('#txtallowance_mf').val(response[5]);
      	$('#txthide_mf').val(id_cd);
      	$('#txtprice_idr_mf').val(response[6]);
      	$('#txtjrate_mf').val(response[7]);
		
		$('#txtid_item_mf2').select2('val',[response[0]]); 
      	$('#txtprice_mf2').val(response[1]);
      	$('#txtcons_mf2').val(response[2]);
      	$('#txtunit_mf2').select2('val',[response[3]]);
      	$('#txtmaterial_source_mf2').select2('val',[response[4]]); 
      	$('#txtallowance_mf2').val(response[5]);
      	$('#txthide_mf2').val(id_cd);
      	$('#txtprice_idr_mf2').val(response[6]);
      	$('#txtjrate_mf2').val(response[7]);		
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
  function edit_ot(id_cd)
  {	
  
 // var id_cs = <?php echo $id_cs; ?>;
 var id_cs = <?php echo "'".$id_cs."'"; ?>;
  	jQuery.ajax
    ({  
      url: "ajax_act_cost.php?mdajax=edit_cost_ot",
      method: 'POST',
      data: {id_cs: id_cs,id_cd: id_cd},
      dataType: 'json',
      success: function(response)
      {	$('#txtid_item_ot').select2('val',[response[0]]); 
      	$('#txtprice_ot').val(response[1]);
      	$('#txthide_ot').val(id_cd);
      	$('#txtprice_idr_ot').val(response[6]);
      	$('#txtjrate_ot').val(response[7]);
		
		$('#txtid_item_ot2').select2('val',[response[0]]); 
      	$('#txtprice_ot2').val(response[1]);
      	$('#txthide_ot2').val(id_cd);
      	$('#txtprice_idr_ot2').val(response[6]);
      	$('#txtjrate_ot2').val(response[7]);		
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
	function add_cd(Data)
   {	
	//var id_cs = <?php echo $id_cs; ?>;
	var id_cs = <?php echo "'".$id_cs."'"; ?>;
	if(Data == 'add'){
  	var id_cd = document.getElementById('txthide_cd').value;
  	var j_rate_cd = document.getElementById('txtjrate_cd').value;
  	var id_item_cd = document.getElementById('txtid_item_cd').value;
  	var price_cd = document.getElementById('txtprice_cd').value;
  	var price_idr_cd = document.getElementById('txtprice_idr_cd').value;
  	var cons_cd = document.getElementById('txtcons_cd').value;
  	var unit_cd = document.getElementById('txtunit_cd').value;
  	var allow_cd = document.getElementById('txtallowance_cd').value;
  	var mat_sour_cd = document.getElementById('txtmaterial_source_cd').value;
  }
   if(Data == 'edit'){
  	var id_cd = document.getElementById('txthide_cd2').value;
  	var j_rate_cd = document.getElementById('txtjrate_cd2').value;
  	var id_item_cd = document.getElementById('txtid_item_cd2').value;
  	var price_cd = document.getElementById('txtprice_cd2').value;
  	var price_idr_cd = document.getElementById('txtprice_idr_cd2').value;
  	var cons_cd = document.getElementById('txtcons_cd2').value;
  	var unit_cd = document.getElementById('txtunit_cd2').value;
  	var allow_cd = document.getElementById('txtallowance_cd2').value;
  	var mat_sour_cd = document.getElementById('txtmaterial_source_cd2').value;
  } 
  	var cek_so = "<?php echo flookup("so_no","so","id_cost='$id_cs'"); ?>";
  	var cek_unlock = "<?php $dateskrg=date('Y-m-d'); echo flookup("cost_no","unlock_cost","id_cost='$id_cs' and DATE_ADD(unlock_date, INTERVAL 2 DAY)>'$dateskrg'"); ?>";
  	jQuery.ajax
    ({url: "ajax_cek_cd.php",
    	method: 'POST', data: {id_cs: id_cs,id_item_cd: id_item_cd},
      dataType: 'json', async: false,
      success: function(response)
      {	cekexist = response[0];
		console.log(response);

	  },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  	if (cek_so != "" && cek_unlock == "")
  	{swal({ title: 'Costing Tidak Bisa Dirubah Karena Sudah Dibuat SO', <?php echo $img_alert; ?> }); 
			exit;
		}
		
		else if (id_item_cd == '')
  	{	swal({ title: 'Item Code Tidak Boleh Kosong', <?php echo $img_alert; ?> }); 
			exit;
		}
		//else if (Number(cekexist) > 0)
  	//{	swal({ title: 'Data Sudah Ada', <?php echo $img_alert; ?> }); 
		//	exit;
		//}
		// else if (price_cd == '' || price_cd == 0)
  	// {	swal({ title: 'Price Tidak Boleh Kosong', <?php echo $img_alert; ?> }); 
		// 	exit;
		// }
  	else if (cons_cd == '' || cons_cd == 0)
  	{	swal({ title: 'Cons Tidak Boleh Kosong', <?php echo $img_alert; ?> }); 
			exit;
		}
		else if (unit_cd == '')
  	{	swal({ title: 'Unit Tidak Boleh Kosong', <?php echo $img_alert; ?> }); 
			exit;
		}
		else if (mat_sour_cd == '')
  	{	swal({ title: 'Mat Source Tidak Boleh Kosong', <?php echo $img_alert; ?> }); 
			exit;
		}
		else
  	{	jQuery.ajax
	    ({url: "ajax_cd.php",
	    	method: 'POST',
	      data: {id_cd: id_cd,id_cs: id_cs,id_item_cd: id_item_cd,
	      	price_cd: price_cd,cons_cd: cons_cd,unit_cd: unit_cd,
	      	allow_cd: allow_cd,mat_sour_cd: mat_sour_cd,
	      	j_rate_cd: j_rate_cd,price_idr_cd: price_idr_cd},
	      success: function(response)
	      {	//alert("Data Berhasil Disimpan");
	      	window.location.reload();
				},
	      error: function (request, status, error) 
	      { alert(request.responseText); },
	    });
		}	
  };
  function add_mf(Data)
  {	
  
	event.preventDefault();
	//alert("TEST");
 // var id_cs = <?php echo $id_cs; ?>;
 var id_cs = <?php echo "'".$id_cs."'"; ?>;
  if(Data == 'add'){
  	var id_mf = document.getElementById('txthide_mf').value;
  	var j_rate_mf = document.getElementById('txtjrate_mf').value;
  	var id_item_mf = document.getElementById('txtid_item_mf').value;
  	var price_mf = document.getElementById('txtprice_mf').value;
  	var price_idr_mf = document.getElementById('txtprice_idr_mf').value;
  	var cons_mf = document.getElementById('txtcons_mf').value;
  	var unit_mf = document.getElementById('txtunit_mf').value;
  	var allow_mf = document.getElementById('txtallowance_mf').value;
  	var mat_sour_mf = document.getElementById('txtmaterial_source_mf').value;	  
	  
  }
  if(Data == 'edit'){
  	var id_mf = document.getElementById('txthide_mf2').value;
  	var j_rate_mf = document.getElementById('txtjrate_mf2').value;
  	var id_item_mf = document.getElementById('txtid_item_mf2').value;
  	var price_mf = document.getElementById('txtprice_mf2').value;
  	var price_idr_mf = document.getElementById('txtprice_idr_mf2').value;
  	var cons_mf = document.getElementById('txtcons_mf2').value;
  	var unit_mf = document.getElementById('txtunit_mf2').value;
  	var allow_mf = document.getElementById('txtallowance_mf2').value;
  	var mat_sour_mf = document.getElementById('txtmaterial_source_mf2').value;
	  
	  
	  
  }
	var cek_so = "<?php echo flookup("so_no","so","id_cost='$id_cs'"); ?>";
  	var cek_unlock = "<?php $dateskrg=date('Y-m-d'); echo flookup("cost_no","unlock_cost","id_cost='$id_cs' and DATE_ADD(unlock_date, INTERVAL 2 DAY)>'$dateskrg'"); ?>";
		//alert(cek_unlock);
		//alert(cek_so);
		if (cek_so != "" && cek_unlock == "")
  	{	swal({ title: 'Costing Tidak Bisa Dirubah Karena Sudah Dibuat SO', <?php echo $img_alert; ?> }); 
			exit;
		}
		else if (id_item_mf == '')
  	{	swal({ title: 'Complexity Tidak Boleh Kosong', <?php echo $img_alert; ?> }); 
			exit;
		}
		else if (price_mf == '' || price_mf == 0)
  	{	swal({ title: 'Price Tidak Boleh Kosong', <?php echo $img_alert; ?> }); 
			exit;
		}
  	else if (cons_mf == '' || cons_mf == 0)
  	{	swal({ title: 'Cons Tidak Boleh Kosong', <?php echo $img_alert; ?> }); 
			exit;
		}
		else if (unit_mf == '')
  	{	swal({ title: 'Unit Tidak Boleh Kosong', <?php echo $img_alert; ?> }); 
			exit;
		}
		else if (mat_sour_mf == '')
  	{	swal({ title: 'Mat Source Tidak Boleh Kosong', <?php echo $img_alert; ?> }); 
			exit;
		}
		else
  	{	jQuery.ajax
	    ({url: "ajax_mf.php",
	      method: 'POST',
	      data: {id_cs: id_cs,id_mf: id_mf,id_item_mf: id_item_mf,
	      	price_mf: price_mf,cons_mf: cons_mf,unit_mf: unit_mf,
	      	allow_mf: allow_mf,mat_sour_mf: mat_sour_mf,
	      	j_rate_mf: j_rate_mf,price_idr_mf: price_idr_mf},
	      success: function(response)
	      {	//alert("Data Berhasil Disimpan");
	      	window.location.reload();
				},
	      error: function (request, status, error) 
	      { alert(request.responseText); },
	    });
		}	
  };
  function add_ot(Data)
  {	
   //var id_cs = <?php echo $id_cs; ?>;
	var id_cs = <?php echo "'".$id_cs."'"; ?>;
	if(Data == 'add'){

  	var id_ot = document.getElementById('txthide_ot').value;
  	var j_rate_ot = document.getElementById('txtjrate_ot').value;
  	var id_item_ot = document.getElementById('txtid_item_ot').value;
  	var price_ot = document.getElementById('txtprice_ot').value;
  	var price_idr_ot = document.getElementById('txtprice_idr_ot').value;		

	}
		if(Data == 'edit'){

  	var id_ot = document.getElementById('txthide_ot2').value;
  	var j_rate_ot = document.getElementById('txtjrate_ot2').value;
  	var id_item_ot = document.getElementById('txtid_item_ot2').value;
  	var price_ot = document.getElementById('txtprice_ot2').value;
  	var price_idr_ot = document.getElementById('txtprice_idr_ot2').value;		

	}
  
  	var cek_so = "<?php echo flookup("so_no","so","id_cost='$id_cs'"); ?>";
  	var cek_unlock = "<?php $dateskrg=date('Y-m-d'); echo flookup("cost_no","unlock_cost","id_cost='$id_cs' and DATE_ADD(unlock_date, INTERVAL 2 DAY)>'$dateskrg'"); ?>";
  	//var cek_so = "<?php echo flookup("so_no","so","id_cost='$id_cs'"); ?>";
	//  	if (cek_so != "")
  //	{	swal({ title: 'Costing Tidak Bisa Dirubah Karena Sudah Dibuat SO', <?php echo $img_alert; ?> }); 
//			exit;
//		}
  	if (cek_so != "" && cek_unlock == "")
  	{swal({ title: 'Costing Tidak Bisa Dirubah Karena Sudah Dibuat SO', <?php echo $img_alert; ?> }); 
			exit;
		}
		else if (id_item_ot == '')
  	{	swal({ title: 'Others Cost Tidak Boleh Kosong', <?php echo $img_alert; ?> }); 
			exit;
		}
		else if (price_ot == '' || price_ot == 0)
  	{	swal({ title: 'Price Tidak Boleh Kosong', <?php echo $img_alert; ?> }); 
			exit;
		}
  	else
  	{	jQuery.ajax
	    ({url: "ajax_ot.php",
	      method: 'POST',
	      data: {id_cs: id_cs,id_ot: id_ot,id_item_ot: id_item_ot,
	      	price_ot: price_ot,j_rate_ot: j_rate_ot,price_idr_ot: price_idr_ot},
	      success: function(response)
	      {	//alert("Data Berhasil Disimpan");
	      	window.location.reload();
				},
	      error: function (request, status, error) 
	      { alert(request.responseText); },
	    });
		}	
  };


function handleAllo(thiss){

/*
if(thiss.value == ''){
$('#'+thiss.id).val(0);	
return false;
	
}

		var NAN =thiss.value * 1;
			console.log(thiss.id);
if(Number.isNaN(NAN)){
	alert('Data Harus Berupa Nilai');
	$('#'+thiss.id).val(0);
return false;
}
  

	if(thiss.id == "comFee"){
console.log('1');
		mySumDeal.comFee =	parseFloat(thiss.value) /100;
console.log(mySumDeal.comFee);
		mySumDeal.myDeal = parseFloat(mySumDeal.confirmPrice) -  parseFloat(mySumDeal.gaCost) - parseFloat(mySumDeal.comFee);

}
	if(thiss.id == 'gaCost'){
console.log('2');
console.log(mySumDeal.gaCost);
		mySumDeal.gaCost =	parseFloat(thiss.value) /100;
		mySumDeal.myDeal = parseFloat(mySumDeal.confirmPrice) -  parseFloat(mySumDeal.gaCost) - parseFloat(mySumDeal.comFee);		
}
	if(thiss.id == 'confirmPrice'){
console.log('3');
		mySumDeal.confirmPrice =	parseFloat(thiss.value) /100;
console.log(mySumDeal.confirmPrice);
		mySumDeal.myDeal = parseFloat(mySumDeal.confirmPrice) -  parseFloat(mySumDeal.gaCost) - parseFloat(mySumDeal.comFee);
}
$('#myDeal').val(mySumDeal.myDeal);
*/
}







</script>