<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$rsUser=mysql_fetch_array(mysql_query("select * from userpassword where username='$user'")); 
$akses = $rsUser["act_costing"];
$kd_mkt = $rsUser["kode_mkt"];
$groupp = $rsUser["Groupp"];
$all_buyer = $rsUser["all_buyer"];
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

$cprodgr="Product Group"; 
$cprodit="Product Item";
$cstyle="Style #";

if (isset($_GET['id'])) {$id_cs = $_GET['id']; } else {$id_cs = "";}
if (isset($_GET['pro'])) {$pro = $_GET['pro']; } else {$pro = "";}
if ($id_cs!="")
{	$akses = flookup("cost_no","act_costing","username='$user' and id='$id_cs'");
	if ($akses=="" and $pro!="Copy") 
	{ echo "<script>alert('Akses tidak dijinkan, User Costing Tidak Sesuai'); window.location.href='?mod=1';</script>"; }
}
if (isset($_GET['idcd'])) {$id_cd = $_GET['idcd']; } else {$id_cd = "";}
if (isset($_GET['idmf'])) {$id_mf = $_GET['idmf']; } else {$id_mf = "";}
if (isset($_GET['idot'])) {$id_ot = $_GET['idot']; } else {$id_ot = "";}
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
	$status="";
	$status_order="";
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
	$data = mysql_fetch_array($query);
	$buyer=$data['id_buyer'];
	$prod_gr=flookup("product_group","masterproduct","id=$data[id_product]");
	$prod_it=$data['id_product'];
	$styleno=$data['styleno'];
	$status=$data['status'];
	$status_order=$data['status_order'];
	$qty=$data['qty'];
	$unit=$data['unit'];
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
	$rs=mysql_fetch_array(mysql_query("select * from masterrate where curr='USD' and tanggal='".fd($cost_date)."'"));
		$rate_jual=$rs['rate_jual'];
		$rate_beli=$rs['rate_beli'];
	$tot_cd = flookup("sum((if(jenis_rate='B',price/rate_beli,price)*cons)+((if(jenis_rate='B',price/rate_beli,price)*cons)*allowance/100))",
		"act_costing_mat a inner join act_costing s on a.id_act_cost=s.id
		inner join masterrate d on 'USD'=d.curr and s.cost_date=d.tanggal","id_act_cost='$id_item'");
	$tot_cd_idr = flookup("sum((if(jenis_rate='J',price*rate_jual,price)*cons)+((if(jenis_rate='J',price*rate_jual,price)*cons)*allowance/100))",
		"act_costing_mat a inner join act_costing s on a.id_act_cost=s.id
		inner join masterrate d on 'USD'=d.curr and s.cost_date=d.tanggal","id_act_cost='$id_item'");
	$tot_mf = flookup("sum((if(jenis_rate='B',price/rate_beli,price)*cons)+((if(jenis_rate='B',price/rate_beli,price)*cons)*allowance/100))",
		"act_costing_mfg a inner join act_costing s on a.id_act_cost=s.id
		inner join masterrate d on 'USD'=d.curr and s.cost_date=d.tanggal","id_act_cost='$id_item'");
	$tot_mf_idr = flookup("sum((if(jenis_rate='J',price*rate_jual,price)*cons)+((if(jenis_rate='J',price*rate_jual,price)*cons)*allowance/100))",
		"act_costing_mfg a inner join act_costing s on a.id_act_cost=s.id
		inner join masterrate d on 'USD'=d.curr and s.cost_date=d.tanggal","id_act_cost='$id_item'");
	$tot_ot = flookup("sum(if(jenis_rate='B',price/rate_beli,price))",
		"act_costing_oth a inner join act_costing s on a.id_act_cost=s.id
		inner join masterrate d on 'USD'=d.curr and s.cost_date=d.tanggal","id_act_cost='$id_item'");
	$tot_ot_idr = flookup("sum(if(jenis_rate='J',price*rate_jual,price))",
		"act_costing_oth a inner join act_costing s on a.id_act_cost=s.id
		inner join masterrate d on 'USD'=d.curr and s.cost_date=d.tanggal","id_act_cost='$id_item'");
	$total_ga_cost = ($tot_cd + $tot_mf + $tot_ot) * $ga/100;
	$total_ga_cost_idr=($tot_cd_idr + $tot_mf_idr + $tot_ot_idr) * $ga/100;
	$total_cost = $tot_cd + $tot_mf + $tot_ot + $total_ga_cost;
	$total_cost_idr=$tot_cd_idr + $tot_mf_idr + $tot_ot_idr + $total_ga_cost_idr;
	$total_vat = (($total_cost+$total_ga_cost)*$vat/100);
	$total_vat_idr = (($total_cost_idr+$total_ga_cost_idr)*$vat/100);
	$total_deal = (($total_cost+$total_vat+$total_ga_cost)*$deal/100);
	$total_deal_idr = (($total_cost_idr+$total_vat_idr+$total_ga_cost_idr)*$deal/100);
	$total_cost_plus = $total_cost + $total_vat + $total_deal + $total_ga_cost;
	$total_cost_plus_idr = $total_cost_idr + $total_vat_idr + $total_deal_idr + $total_ga_cost_idr;
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
			var statusnya = document.form.txtstatus.value;
			var statusordnya = document.form.txtstatus_order.value;
			var currnya = document.form.txtcurr.value;
			var kursnya = document.form.txtKurs.value;
			if (id_buyer == '') { swal({ title: 'Nama Buyer Tidak Boleh Kosong', $img_alert }); valid = false;}
			else if (id_prod_it == '') { swal({ title: '$cprodit Tidak Boleh Kosong', $img_alert }); valid = false;}
			else if (styleno == '') { swal({ title: '$cstyle Tidak Boleh Kosong', $img_alert }); valid = false;}
			else if (id_smode == '') { swal({ title: 'Ship Mode Tidak Boleh Kosong', $img_alert }); valid = false;}
			else if (qty == '') { swal({ title: 'Qty Tidak Boleh Kosong', $img_alert }); valid = false;}
			else if (smv_min == '') { document.form.txtsmv_min.focus(); swal({ title: 'SMV (Min) Tidak Boleh Kosong', $img_alert }); valid = false;}
			else if (smv_sec == '') { document.form.txtsmv_sec.focus(); swal({ title: 'SMV (Sec) Tidak Boleh Kosong', $img_alert }); valid = false;}
			else if (book_min == '') { document.form.txtbook_min.focus(); swal({ title: 'Book (Min) Tidak Boleh Kosong', $img_alert }); valid = false;}
			else if (book_sec == '') { document.form.txtbook_sec.focus(); swal({ title: 'Book (Sec) Tidak Boleh Kosong', $img_alert }); valid = false;}
			else if (deldate == '') { document.form.txtdeldate.focus(); swal({ title: 'Delivery Date Tidak Boleh Kosong', $img_alert }); valid = false;}
			else if (statusnya == '') { swal({ title: 'Status Tidak Boleh Kosong', $img_alert }); valid = false;}
			else if (statusordnya == '') { swal({ title: 'Status Order Tidak Boleh Kosong', $img_alert }); valid = false;}
			else if (currnya == '') { swal({ title: 'Currency Tidak Boleh Kosong', $img_alert }); valid = false;}
			else if (kursnya == '0' && currnya != 'IDR') { swal({ title: 'Rate Tidak Ditemukan', $img_alert }); valid = false;}
			else valid = true;
			return valid;
			exit;
		}
	</script>";
}
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
  function getKurs()
  { var currnya = document.form.txtcurr.value;
  	var tglnya = document.form.txtcost_date.value;
  	jQuery.ajax
    ({  
      url: "ajax_act_cost.php?mdajax=get_kurs_rate",
      method: 'POST',
      data: {currnya: currnya,tglnya: tglnya},
      dataType: 'json',
      success: function(response)
      {	$('#txtKurs').val(response[0]); },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
  function USD_IDR()
  { var pxnya = document.getElementById('txtprice_cd').value;
  	var tglnya = document.form.txtcost_date.value;
  	jQuery.ajax
    ({  
      url: "ajax_act_cost.php?mdajax=calc_usd_idr",
      method: 'POST',
      data: {pxnya: pxnya,tglnya: tglnya},
      dataType: 'json',
      success: function(response)
      {	$('#txtprice_idr_cd').val(response[0]);
      	$('#txtjrate_cd').val(response[1]); 
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
  function USD_IDR_MF()
  { var pxnya = document.getElementById('txtprice_mf').value;
  	var tglnya = document.form.txtcost_date.value;
  	jQuery.ajax
    ({  
      url: "ajax_act_cost.php?mdajax=calc_usd_idr",
      method: 'POST',
      data: {pxnya: pxnya,tglnya: tglnya},
      dataType: 'json',
      success: function(response)
      {	$('#txtprice_idr_mf').val(response[0]);
      	$('#txtjrate_mf').val(response[1]); 
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
  function USD_IDR_OT()
  { var pxnya = document.getElementById('txtprice_ot').value;
  	var tglnya = document.form.txtcost_date.value;
  	jQuery.ajax
    ({  
      url: "ajax_act_cost.php?mdajax=calc_usd_idr",
      method: 'POST',
      data: {pxnya: pxnya,tglnya: tglnya},
      dataType: 'json',
      success: function(response)
      {	$('#txtprice_idr_ot').val(response[0]);
      	$('#txtjrate_ot').val(response[1]); 
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
  function IDR_USD()
  { var pxnya = document.getElementById('txtprice_idr_cd').value;
  	var tglnya = document.form.txtcost_date.value;
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
  function IDR_USD_MF()
  { var pxnya = document.getElementById('txtprice_idr_mf').value;
  	var tglnya = document.form.txtcost_date.value;
  	jQuery.ajax
    ({  
      url: "ajax_act_cost.php?mdajax=calc_idr_usd",
      method: 'POST',
      data: {pxnya: pxnya,tglnya: tglnya},
      dataType: 'json',
      success: function(response)
      {	$('#txtprice_mf').val(response[0]);
      	$('#txtjrate_mf').val(response[1]); 
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
  function IDR_USD_OT()
  { var pxnya = document.getElementById('txtprice_idr_ot').value;
  	var tglnya = document.form.txtcost_date.value;
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
    ({url: "ajax_quote_inq.php?mdajax=format_ribuan",
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
<div class='box'>
	<div class='box-body'>
		<div class='row'>
			<form method='post' name='form' enctype='multipart/form-data' action="s_actcost.php?mod=<?php echo $mod; ?>&id=<?php echo $id_cs; ?>&pro=<?php echo $pro; ?>" onsubmit='return validasi()'>
				<div class='col-md-3'>								
					<?php if ($id_cs=="") { ?>
					<img id="output" width="270px" height="500px">	
					<?php } else { $nm_filex="upload_files/costing/".$attach_file; ?>
					<img src="<?php echo $nm_filex; ?>" id="output" width="270px" height="500px">	
					<?php } ?>
					<script>
					  var loadFile = function(event) 
					  {	var output = document.getElementById('output');
					    output.src = URL.createObjectURL(event.target.files[0]);
					  };
					</script>
				</div>
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
						<label><?php echo $cprodgr; ?></label>
						<select class='form-control select2' 
							style='width: 100%;' name='txtprod_group' onchange='getProdItem(this.value)'>
              <?php 
                $sql = "select product_group isi,product_group tampil from 
                  masterproduct group by product_group";
                IsiCombo($sql,$prod_gr,'Pilih '.$cprodgr);
              ?>
            </select>
					</div>				
					<div class='form-group'>
						<label><?php echo $cprodit; ?> *</label>
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
							IsiCombo($sql,$prod_it,'Pilih '.$cprodit);
							?>
            </select>
            <?php } ?>
					</div>				
					<div class='form-group'>
						<label><?php echo $cstyle; ?></label>
						<input type='text' class='form-control' 
							name='txtstyle' value='<?php echo $styleno; ?>' placeholder='Masukkan <?php echo $cstyle; ?>' >
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Costing #</label>
							<input type='text' class='form-control' readonly name='txtcost_no' placeholder='Masukkan Costing #' value='<?php echo $cost_no;?>' >
						</div>
					</div>
					<div class='col-md-6'>				
						<div class='form-group'>
							<label>Costing Date</label>
							<input type='text' class='form-control' readonly name='txtcost_date' placeholder='Masukkan Costing Date' value='<?php echo $cost_date;?>' >
						</div>
					</div>
					<div class='form-group'>
						<label>WS #</label>
						<input type='text' class='form-control' readonly name='txtkpno' placeholder='Masukkan WS #' value='<?php echo $kpno;?>' >
					</div>
				</div>
				<div class='col-md-3'>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Curr *</label>
							<input type='hidden' name='txtKurs' id='txtKurs'>
							<select class='form-control select2' style='width: 100%;' 
								name='txtcurr' onchange='getKurs()'>
								<?php 
									$sql = "select nama_pilihan isi,nama_pilihan tampil from 
										masterpilihan where kode_pilihan='Curr'";
									IsiCombo($sql,$curr,'Pilih Currency');
								?>
							</select>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Confirm Price</label>
							<input type='text' class='form-control' name='txtcfm' placeholder='Masukkan Confirm Price' value='<?php echo $cfm;?>' >
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Ship Mode *</label>
							<select class='form-control select2' style='width: 100%;' name='txtid_smode'>
								<?php 
									$sql = "select id isi,shipmode tampil from mastershipmode";
									IsiCombo($sql,$id_smode,'Pilih Ship Mode');
								?>
							</select>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Status Order *</label>
							<select class='form-control select2' style='width: 100%;' 
								name='txtstatus_order'>
								<?php 
									$sql = "select nama_pilihan isi,nama_pilihan tampil from 
										masterpilihan where kode_pilihan='BUS_TYPE'";
									IsiCombo($sql,$status_order,'Pilih Status Order');
								?>
							</select>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Qty</label>
							<input type='text' class='form-control' name='txtqty' id='txtqty' 
								value='<?php echo $qty; ?>' placeholder='Masukkan Qty' onchange='Ribuan(this.value)'>
						</div>
					</div>
					<div class='col-md-6'>
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
					</div>
					<div class='col-md-6'>				
						<div class='form-group'>
							<label>SMV (Min) *</label>
							<input type='text' class='form-control' name='txtsmv_min' id='txtsmv_min' placeholder='Masukkan SMV (Min)' onchange='CalcSMVMin()' value='<?php echo $smv_min;?>' >
						</div>
					</div>
					<div class='col-md-6'>				
						<div class='form-group'>
							<label>SMV (Sec) *</label>
							<input type='text' class='form-control' name='txtsmv_sec' id='txtsmv_sec' placeholder='Masukkan SMV (Sec)' onchange='CalcSMVSec()' value='<?php echo $smv_sec;?>' >
						</div>
					</div>
					<div class='col-md-6'>				
						<div class='form-group'>
							<label>Book (Min) *</label>
							<input type='text' class='form-control' readonly name='txtbook_min' id='txtbook_min' placeholder='Masukkan Book (Min)' value='<?php echo $book_min;?>' >
						</div>
					</div>
					<div class='col-md-6'>				
						<div class='form-group'>
							<label>Book (Sec) *</label>
							<input type='text' class='form-control' readonly name='txtbook_sec' id='txtbook_sec' placeholder='Masukkan Book (Sec)' value='<?php echo $book_sec;?>' >
						</div>
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
					<div class='form-group'>
						<label>Attach File *</label>
      			<input type="file" name='txtattach_file' accept="image/*" onchange="loadFile(event)">
					</div>
					<div class='form-group'>
						<label>VAT (%)</label>
						<input type='text' class='form-control' name='txtvat' placeholder='Masukkan VAT' value='<?php echo $vat;?>' >
					</div>
					<div class='form-group'>
						<label>Deal Allowance (%)</label>
						<input type='text' class='form-control' name='txtdeal' placeholder='Masukkan Deal Allowance' value='<?php echo $deal;?>' >
					</div>
					<div class='form-group'>
						<label>GA Cost (%)</label>
						<input type='text' class='form-control' name='txtga' placeholder='Masukkan GA Cost' value='<?php echo $ga;?>' >
					</div>
					<div class='form-group'>
						<label>Commission Fee (%)</label>
						<input type='text' class='form-control' name='txtcomm' placeholder='Masukkan Commission Fee' value='<?php echo $comm;?>' >
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
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Costing Detail</h3>
  	<!--
  	<a href='#' class='add-item btn btn-info btn-s' 
  		data-id=<?php echo $id_cs; ?>&<?php echo $mod; ?>&<?php echo ""; ?>>
  		<i class='fa fa-plus'></i> Add Item
  	</a>-->
  </div>
  <div class="box-body">
  	<div class='col-md-3'>								
			<div class='form-group'>
				<label>Item Code *</label>
				<select class='form-control select2' style='width: 100%;' 
					id='txtid_item_cd'>
					<?php 
						if ($nm_company!="PT. Nirwana Alabare Garment")
						{	$sql = "select e.id isi,concat(nama_group,' ',nama_sub_group,' ',nama_type,' ',nama_contents) 
              tampil from mastergroup a inner join mastersubgroup s on 
              a.id=s.id_group 
              inner join mastertype2 d on s.id=d.id_sub_group
              inner join mastercontents e on d.id=e.id_type";
						}
						else
						{	/*
							$sql = "select s.id isi,concat(nama_group,' ',nama_sub_group) 
              tampil from mastergroup a inner join mastersubgroup s on 
              a.id=s.id_group ";
							*/
							$sql = "select e.id isi,concat(nama_group,' ',nama_sub_group,' ',nama_type,' ',nama_contents) 
              tampil from mastergroup a inner join mastersubgroup s on 
              a.id=s.id_group 
              inner join mastertype2 d on s.id=d.id_sub_group
              inner join mastercontents e on d.id=e.id_type";	
						}
						IsiCombo($sql,$id_item_cd,'Pilih Item Sub Group');
					?>
				</select>
			</div>
			<button class='btn btn-primary' onclick='add_cd()'>Add Item</button>
			<p>
		</div>
		<div class='col-md-3'>
			<div class='col-md-6'>	
				<div class='form-group'>
					<label>Price (USD) *</label>
					<input type='text' class='form-control' 
						id='txtprice_cd' placeholder='Masukkan Price' 
						value='<?php echo $price_cd;?>' onchange='USD_IDR()'>
				</div>
			</div>
			<div class='col-md-6'>
				<div class='form-group'>
					<label>Price (IDR)</label>
					<input type='text' class='form-control' 
						id='txtprice_idr_cd' placeholder='Masukkan Price' 
						value='<?php echo $price_idr_cd;?>' onchange="IDR_USD()">
				</div>
			</div>
		</div>
		<div class='col-md-3'>				
			<div class='col-md-6'>
				<div class='form-group'>
					<label>Cons *</label>
					<input type='hidden' id='txthide_cd'>
					<input type='hidden' id='txtjrate_cd'>
					<input type='text' class='form-control' 
						id='txtcons_cd' placeholder='Masukkan Cons' 
						value='<?php echo $cons_cd;?>' >
				</div>
			</div>
			<div class='col-md-6'>					
				<div class='form-group'>
					<label>Unit *</label>
					<select class='form-control select2' style='width: 100%;' 
						id='txtunit_cd'>
						<?php 
							$sql = "select nama_pilihan isi,nama_pilihan tampil from 
								masterpilihan where kode_pilihan='Satuan'";
							IsiCombo($sql,$unit_cd,'Pilih Unit');
						?>
					</select>
				</div>
			</div>
		</div>
		<div class='col-md-3'>				
			<div class='col-md-6'>
				<div class='form-group'>
					<label>Allowance (%) *</label>
					<input type='text' class='form-control' 
						id='txtallowance_cd' placeholder='Masukkan Allowance' 
						value='<?php echo $allow_cd;?>' >
				</div>
			</div>
			<div class='col-md-6'>					
				<div class='form-group'>
					<label>Material Source *</label>
					<select class='form-control select2' style='width: 100%;' 
						id='txtmaterial_source_cd'>
						<?php 
							$sql = "select nama_pilihan isi,nama_pilihan tampil from 
								masterpilihan where kode_pilihan='Mat_Sour'";
							IsiCombo($sql,$mat_sour_cd,'Pilih Material Source');
						?>
					</select>
				</div>
			</div>
		</div>
    <table id="examplefix" class="display responsive" style="width:100%; font-size:12px;">
      <thead>
      <tr>
	    	<th>No</th>
        <th>Group Name</th>
				<th>Description</th>
				<th>Price (USD)</th>
				<th>Price (IDR)</th>
				<th>Cons</th>
				<th>UOM</th>
				<th>Allow (%)</th>
				<th>Value (USD)</th>
				<th>Value (IDR)</th>
				<th>Percent</th>
				<th>Material Source</th>
				<th></th>
				<th></th>
			</tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $query = mysql_query("SELECT d.nama_group mattype,
		    	concat(nama_group,' ',nama_sub_group,' ',nama_type,' ',nama_contents) itemdesc,
		    	price,cons,s.unit,allowance,material_source,s.id,s.jenis_rate 
		    	from act_costing a inner join act_costing_mat s on 
		    	a.id=s.id_act_cost inner join mastergroup d inner join mastersubgroup f on 
          d.id=f.id_group 
          inner join mastertype2 g on f.id=g.id_sub_group
          inner join mastercontents h on g.id=h.id_type and s.id_item=h.id
		      where a.id='$id_cs' order by s.id"); 
        $no = 1; 
				while($data = mysql_fetch_array($query))
			  { echo "<tr>";
				    echo "<td>$no</td>"; 
            echo "<td>$data[mattype]</td>";
						echo "<td>$data[itemdesc]</td>";
						if ($data['jenis_rate']=="J")
						{	$px_idr=$data['price'] * $rate_jual; 
							$px_usd=round($data['price'],4);
						}
						else
						{	$px_idr=$data['price']; 
							if($rate_beli=="0")
							{	$px_usd=0;	}
							else
							{	$px_usd=round($data['price'] / $rate_beli,4);	}
						}
						echo "<td>".fn($px_usd,4)."</td>";
						echo "<td>".fn($px_idr,2)."</td>";
						echo "<td>".fn($data['cons'],3)."</td>";
						echo "<td>$data[unit]</td>";
						$allowcs = ($px_usd*$data['cons']) * ($data['allowance']/100);
						$allowcs_idr = ($px_idr*$data['cons']) * ($data['allowance']/100);
						echo "<td>".fn($data['allowance'],2)."</td>";
						$valcs = ($px_usd * $data['cons']) + $allowcs;
						$valcs_idr = ($px_idr * $data['cons']) + $allowcs_idr;
						echo "<td>".fn($valcs,4)."</td>";
						echo "<td>".fn($valcs_idr,2)."</td>";
						if ($total_cost==0)
						{	$persen=0;	}
						else
						{	$persen = ($valcs / $total_cost) * 100;	}
						echo "<td>".fn($persen,2)."</td>";
						echo "<td>$data[material_source]</td>";
						echo "
						<td>
							<button onclick='edit_cd($data[id])'>
					  		<i class='fa fa-pencil'></i>
					  	</button>
					  </td>
					  <td>
					  	<a href='d_add_item_cs.php?mod=$mod&mode=$mode&
					  		id=$id_cs&idd=$data[id]&mod=$mod'
		            $tt_hapus";?> 
		            onclick="return confirm('Apakah anda yakin akan menghapus ?')">
		            <?PHP echo $tt_hapus2."</a>
				  	</td>";
					echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
			  ?>
      </tbody>
    </table>
    Sub Total (USD) : <?php echo fn($tot_cd,4); ?>
    <br>
		Sub Total (IDR) : <?php echo fn($tot_cd_idr,2); ?>
  </div>
</div>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Manufacturing - Complexity</h3>
  	<!--
  	<a href='#' class='add-mfg btn btn-info btn-s' 
  		data-id=<?php echo $id_cs; ?>&<?php echo $mod; ?>&<?php echo ""; ?>>
  		<i class='fa fa-plus'></i> Add Complexity
  	</a>-->
  </div>
  <div class="box-body">
  	<div class='col-md-3'>								
			<div class='form-group'>
				<label>Complexity *</label>
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
			<button class='btn btn-primary' onclick='add_mf()'>Add Item</button>
		</div>
		<div class='col-md-3'>
			<div class='form-group'>
				<label>Price (USD) *</label>
				<input type='text' class='form-control' 
					id='txtprice_mf' placeholder='Masukkan Price' 
					value='<?php echo $price_mf;?>' onchange='USD_IDR_MF()'>
			</div>
			<div class='form-group'>
				<label>Price (IDR)</label>
				<input type='text' class='form-control' 
					id='txtprice_idr_mf' placeholder='Masukkan Price' 
					value='<?php echo $price_idr_mf;?>' onchange='IDR_USD_MF()'>
			</div>
		</div>
		<div class='col-md-3'>				
			<div class='form-group'>
				<label>Cons *</label>
				<input type='hidden' id='txthide_mf'>
				<input type='hidden' id='txtjrate_mf'>
				<input type='text' class='form-control' 
					id='txtcons_mf' placeholder='Masukkan Cons' 
					value='<?php echo $cons_mf;?>' >
			</div>					
			<div class='form-group'>
				<label>Unit *</label>
				<select class='form-control select2' style='width: 100%;' 
					id='txtunit_mf'>
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
					id='txtallowance_mf' placeholder='Masukkan Allowance' 
					value='<?php echo $allow_mf;?>' >
			</div>					
			<div class='form-group'>
				<label>Material Source *</label>
				<select class='form-control select2' style='width: 100%;' 
					id='txtmaterial_source_mf'>
					<?php 
						$sql = "select nama_pilihan isi,nama_pilihan tampil from 
							masterpilihan where kode_pilihan='Mat_Sour'";
						IsiCombo($sql,$mat_sour_mf,'Pilih Material Source');
					?>
				</select>
			</div>
		</div>
    <table id="example1" class="display responsive" style="width:100%;font-size:12px;">
      <thead>
      <tr>
	    	<th>No</th>
        <th>Group Name</th>
				<th>Description</th>
				<th>Price (USD)</th>
				<th>Price (IDR)</th>
				<th>Cons</th>
				<th>UOM</th>
				<th>Allow (%)</th>
				<th>Value (USD)</th>
				<th>Value (IDR)</th>
				<th>Percent</th>
				<th>Material Source</th>
				<th></th>
				<th></th>
			</tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		    $query = mysql_query("SELECT d.cfcode,d.cfdesc,price,cons,s.unit,
		    	allowance,material_source,s.id,s.jenis_rate 
		    	from act_costing a inner join act_costing_mfg s on 
		    	a.id=s.id_act_cost inner join mastercf d on s.id_item=d.id
		    	where a.id='$id_cs'"); 
        $no = 1; 
				while($data = mysql_fetch_array($query))
			  { echo "<tr>";
				    echo "<td>$no</td>"; 
            echo "<td>$data[cfcode]</td>";
						echo "<td>$data[cfdesc]</td>";
						if ($data['jenis_rate']=="J")
						{	$px_idr=$data['price'] * $rate_jual; 
							$px_usd=$data['price'];
						}
						else
						{	$px_idr=$data['price']; 
							$px_usd=$data['price'] / $rate_beli;
						}
						echo "<td>".fn($px_usd,4)."</td>";
						echo "<td>".fn($px_idr,2)."</td>";
						echo "<td>".fn($data['cons'],2)."</td>";
						echo "<td>$data[unit]</td>";
						echo "<td>".fn($data['allowance'],2)."</td>";
						$allowcs = ($px_usd*$data['cons']) * ($data['allowance']/100);
						$allowcs_idr = ($px_idr*$data['cons']) * ($data['allowance']/100);
						$valcs = ($px_usd * $data['cons']) + $allowcs;
						$valcs_idr = ($px_idr * $data['cons']) + $allowcs_idr;
						echo "<td>".fn($valcs,4)."</td>";
						echo "<td>".fn($valcs_idr,2)."</td>";
						if ($total_cost==0)
						{	$persen=0;	}
						else
						{	$persen = ($valcs / $total_cost) * 100;	}
						echo "<td>".fn($persen,2)."</td>";
						echo "<td>$data[material_source]</td>";
						echo "
						<td>
							<button onclick='edit_mf($data[id])'>
					  		<i class='fa fa-pencil'></i>
					  	</button>
					  </td>
					  <td>
					  	<a href='d_add_mfg_cs.php?mod=$mod&mode=$mode&
					  		id=$id_cs&idd=$data[id]&mod=$mod'
		            $tt_hapus";?> 
		            onclick="return confirm('Apakah anda yakin akan menghapus ?')">
		            <?php echo $tt_hapus2."</a>
				  	</td>";
					echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
			  ?>
      </tbody>
    </table>
    Sub Total (USD) : <?php echo fn($tot_mf,4); ?>
    <br>
		Sub Total (IDR) : <?php echo fn($tot_mf_idr,2); ?>
  </div>
</div>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Others Cost</h3>
  	<!--
  	<a href='#' class='add-oth btn btn-info btn-s' 
  		data-id=<?php echo $id_cs; ?>&<?php echo $mod; ?>&<?php echo ""; ?>>
  		<i class='fa fa-plus'></i> Add Others Cost
  	</a>-->
  </div>
  <div class="box-body">
  	<div class='col-md-3'>								
			<div class='form-group'>
				<label>Others Cost *</label>
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
			<div class='form-group'>
				<label>Price (USD) *</label>
				<input type='hidden' id='txthide_ot'>
				<input type='hidden' id='txtjrate_ot'>
				<input type='text' class='form-control' 
					id='txtprice_ot' placeholder='Masukkan Price' 
					value='<?php echo $price_ot;?>' onchange="USD_IDR_OT()">
			</div>
			<div class='form-group'>
				<label>Price (IDR)</label>
				<input type='text' class='form-control' 
					id='txtprice_idr_ot' placeholder='Masukkan Price' 
					value='<?php echo $price_idr_ot;?>' onchange="IDR_USD_OT()">
			</div>
			<button class='btn btn-primary' onclick='add_ot()'>Add Item</button>
			<p>
		</div>
		<table id="examplefix2" class="display responsive" style="width:100%;font-size:12px;">
      <thead>
      <tr>
	    	<th>No</th>
        <th>Group Name</th>
				<th>Description</th>
				<th>Price (USD)</th>
				<th>Price (IDR)</th>
				<th>Percent</th>
				<th></th>
				<th></th>
			</tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		    $query = mysql_query("SELECT d.otherscode,d.othersdesc,price,cons,s.unit,
		    	allowance,material_source,s.id,s.jenis_rate 
		    	from act_costing a inner join act_costing_oth s on 
		    	a.id=s.id_act_cost inner join masterothers d on s.id_item=d.id
		    	where a.id='$id_cs'"); 
        $no = 1; 
				while($data = mysql_fetch_array($query))
			  { echo "<tr>";
				    echo "<td>$no</td>"; 
            echo "<td>$data[otherscode]</td>";
						echo "<td>$data[othersdesc]</td>";
						if ($data['jenis_rate']=="J")
						{	$px_idr=$data['price'] * $rate_jual; 
							$px_usd=$data['price'];
						}
						else
						{	$px_idr=$data['price']; 
							$px_usd=$data['price'] / $rate_beli;
						}
						echo "<td>".fn($px_usd,4)."</td>";
						echo "<td>".fn($px_idr,2)."</td>";
						$valcs = $px_usd;
						if ($total_cost==0)
						{	$persen=0;	}
						else
						{	$persen = ($valcs / $total_cost) * 100;	}
						echo "<td>".fn($persen,2)."</td>";
						echo "
						<td>
							<button onclick='edit_ot($data[id])'>
					  		<i class='fa fa-pencil'></i>
					  	</button>
					  </td>
					  <td>
							<a href='d_add_oth_cs.php?mod=$mod&mode=$mode&
					  		id=$id_cs&idd=$data[id]&mod=$mod'
		            $tt_hapus";?> 
		            onclick="return confirm('Apakah anda yakin akan menghapus ?')">
		            <?php echo $tt_hapus2."</a>
				  	</td>";
					echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
			  ?>
      </tbody>
    </table>
    Sub Total (USD) : <?php echo fn($tot_ot,4); ?>
    <br>
		Sub Total (IDR) : <?php echo fn($tot_ot_idr,2); ?>
    <br>
    <br> 
  	<table width="45%" style="font-weight:bold;">
  		<tr>
  			<td>G & A (USD)</td>
  			<td>&nbsp:&nbsp</td>
  			<td><?php echo fn($total_ga_cost,4); ?></td>
  			<td>&nbsp &nbsp</td>
  			<td>G & A (IDR)</td>
  			<td>&nbsp:&nbsp</td>
  			<td><?php echo fn($total_ga_cost_idr,2); ?></td>
  		</tr>
  		<tr>
  			<td>Total Costing (USD)</td>
  			<td>&nbsp:&nbsp</td>
  			<td><?php echo fn($total_cost,4); ?></td>
  			<td>&nbsp &nbsp</td>
  			<td>Total Costing (IDR)</td>
  			<td>&nbsp:&nbsp</td>
  			<td><?php echo fn($total_cost_idr,2); ?></td>
  		</tr>
  		<tr>
  			<td>VAT (USD)</td>
  			<td>&nbsp:&nbsp</td>
  			<td><?php echo fn($total_vat,2); ?></td>
  			<td>&nbsp &nbsp</td>
  			<td>VAT (IDR)</td>
  			<td>&nbsp:&nbsp</td>
  			<td><?php echo fn($total_vat_idr,2); ?></td>
  		</tr>
  		<tr>
  			<td>Deal Allowance (USD)</td>
  			<td>&nbsp:&nbsp</td>
  			<td><?php echo fn($total_deal,2); ?></td>
  			<td>&nbsp &nbsp</td>
  			<td>Deal Allowance (IDR)</td>
  			<td>&nbsp:&nbsp</td>
  			<td><?php echo fn($total_deal_idr,2); ?></td>
  		</tr>
  		<tr>
  			<td>FOB (USD)</td>
  			<td>&nbsp:&nbsp</td>
  			<td><?php echo fn($total_cost_plus,4); ?></td>
  			<td>&nbsp &nbsp</td>
  			<td>FOB (IDR)</td>
  			<td>&nbsp:&nbsp</td>
  			<td><?php echo fn($total_cost_plus_idr,2); ?></td>
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
  <div class="box-body">
  	<table id="examplefix4" class="display responsive" style="width:100%;font-size:12px;">
      <thead>
      <tr>
	    	<th>Costing #</th>
				<th>Costing Date</th>
				<th>Buyer</th>
				<th><?php echo $cstyle; ?></th>
				<th>WS #</th>
				<th><?php echo $cprodgr; ?></th>
				<th><?php echo $cprodit; ?></th>
				<th>Qty</th>
				<th>Delv. Date</th>
				<th>Status</th>
				<th>Created By</th>
				<th>Created Date</th>
				<th>Status</th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
			</tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		    if ($groupp=="DIREKTUR" or $all_buyer=="1") { $sql_where=""; } else { $sql_where="where d.kode_mkt='$kd_mkt'"; }
		    $sql="select a.kpno,a.app1,a.id,cost_no,cost_date,supplier,styleno,
		    	qty,deldate,fullname,status,mp.product_item,mp.product_group,
		    	a.username,a.dateinput from act_costing a inner join mastersupplier s 
		    	on a.id_buyer=s.id_supplier inner join userpassword d 
		    	on a.username=d.username inner join masterproduct mp 
		    	on a.id_product=mp.id
		    	$sql_where order by cost_date desc";
				#echo $sql;
				$result=mysql_query($sql);
				while($rs = mysql_fetch_array($result))
			  {	echo "
					<tr>
						<td>$rs[cost_no]</td>
						<td>".fd_view($rs['cost_date'])."</td>
						<td>$rs[supplier]</td>
						<td>$rs[styleno]</td>
						<td>$rs[kpno]</td>
						<td>$rs[product_group]</td>
						<td>$rs[product_item]</td>
						<td>".fn($rs['qty'],0)."</td>
						<td>".fd_view($rs['deldate'])."</td>
						<td>$rs[status]</td>
						<td>$rs[fullname]</td>
						<td>$rs[dateinput]</td>
						<td>$rs[app1]</td>
						<td>
							<a href='pdfCost.php?id=$rs[id]'
	              data-toggle='tooltip' title='Save PDF' target='_blank'><i class='fa fa-file-pdf-o'></i>
	            </a>
	          </td>";
            if ($rs['username']==$user)
            {	echo "
          		<td>
          			<a href='?mod=5&id=$rs[id]'
              		data-toggle='tooltip' title='View'><i class='fa fa-pencil'></i>
              	</a>
              </td>"; 
            }
            else
            {	echo "<td></td>"; }
	          echo "
	          <td>
	            <a href='?mod=5&id=$rs[id]&pro=Copy'
	              data-toggle='tooltip' title='Copy'><i class='fa fa-copy'></i></a>
	          </td>
	          <td>
	            <a href='xlsCost.php?id=$rs[id]'
	              data-toggle='tooltip' title='Excel'><i class='fa fa-file-excel-o'></i></a>
	          </td>";
	          $cekid_so=flookup("id","so","id_cost='$rs[id]'");
	          if($cekid_so!="")
	          {	echo "
		          <td>
		          	<a href='pdfSO.php?id=$cekid_so'
	              	data-toggle='tooltip' title='Preview SO'><i class='fa fa-line-chart'></i></a>
	            </td>";
          	}
          	else
          	{	echo "
		          <td></td>";
          	}
	        echo "
					</tr>";
				}
			  ?>
      </tbody>
    </table>
  </div>
</div>
<?php } ?>
<script>
	function edit_cd(id_cd)
  {	var id_cs = <?php echo $id_cs; ?>;
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
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
  function edit_mf(id_cd)
  {	var id_cs = <?php echo $id_cs; ?>;
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
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
  function edit_ot(id_cd)
  {	var id_cs = <?php echo $id_cs; ?>;
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
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
	function add_cd()
  {	var id_cs = <?php echo $id_cs; ?>;
  	var id_cd = document.getElementById('txthide_cd').value;
  	var j_rate_cd = document.getElementById('txtjrate_cd').value;
  	var id_item_cd = document.getElementById('txtid_item_cd').value;
  	var price_cd = document.getElementById('txtprice_cd').value;
  	var price_idr_cd = document.getElementById('txtprice_idr_cd').value;
  	var cons_cd = document.getElementById('txtcons_cd').value;
  	var unit_cd = document.getElementById('txtunit_cd').value;
  	var allow_cd = document.getElementById('txtallowance_cd').value;
  	var mat_sour_cd = document.getElementById('txtmaterial_source_cd').value;
  	var cek_so = "<?php echo flookup("so_no","so","id_cost='$id_cs'"); ?>";
  	var cek_unlock = "<?php $dateskrg=date('Y-m-d'); echo flookup("cost_no","unlock_cost","id_cost='$id_cs' and DATE_ADD(unlock_date, INTERVAL 2 DAY)>'$dateskrg'"); ?>";
  	jQuery.ajax
    ({url: "ajax_cek_cd.php",
    	method: 'POST', data: {id_cs: id_cs,id_item_cd: id_item_cd},
      dataType: 'json', async: false,
      success: function(response)
      {	cekexist = response[0]; },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  	if (cek_so != "" && cek_unlock == "")
  	{	swal({ title: 'Costing Tidak Bisa Dirubah Karena Sudah Dibuat SO', <?php echo $img_alert; ?> }); 
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
		else if (price_cd == '' || price_cd == 0)
  	{	swal({ title: 'Price Tidak Boleh Kosong', <?php echo $img_alert; ?> }); 
			exit;
		}
  	else if (isNaN(price_cd))
  	{	swal({ title: 'Price Tidak Valid', <?php echo $img_alert; ?> }); 
			exit;
		}
  	else if (cons_cd == '' || cons_cd == 0)
  	{	swal({ title: 'Cons Tidak Boleh Kosong', <?php echo $img_alert; ?> }); 
			exit;
		}
		else if (isNaN(cons_cd))
  	{	swal({ title: 'Cons Tidak Valid', <?php echo $img_alert; ?> }); 
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
	      {	alert("Data Berhasil Disimpan");
	      	window.location.reload();
				},
	      error: function (request, status, error) 
	      { alert(request.responseText); },
	    });
		}	
  };
  function add_mf()
  {	var id_cs = <?php echo $id_cs; ?>;
  	var id_mf = document.getElementById('txthide_mf').value;
  	var j_rate_mf = document.getElementById('txtjrate_mf').value;
  	var id_item_mf = document.getElementById('txtid_item_mf').value;
  	var price_mf = document.getElementById('txtprice_mf').value;
  	var price_idr_mf = document.getElementById('txtprice_idr_mf').value;
  	var cons_mf = document.getElementById('txtcons_mf').value;
  	var unit_mf = document.getElementById('txtunit_mf').value;
  	var allow_mf = document.getElementById('txtallowance_mf').value;
  	var mat_sour_mf = document.getElementById('txtmaterial_source_mf').value;
  	var cek_so = "<?php echo flookup("so_no","so","id_cost='$id_cs'"); ?>";
  	var cek_unlock = "<?php $dateskrg=date('Y-m-d'); echo flookup("cost_no","unlock_cost","id_cost='$id_cs' and DATE_ADD(unlock_date, INTERVAL 2 DAY)>'$dateskrg'"); ?>";
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
  	else if (isNaN(price_mf))
  	{	swal({ title: 'Price Tidak Valid', <?php echo $img_alert; ?> }); 
			exit;
		}
  	else if (cons_mf == '' || cons_mf == 0)
  	{	swal({ title: 'Cons Tidak Boleh Kosong', <?php echo $img_alert; ?> }); 
			exit;
		}
		else if (isNaN(cons_mf))
  	{	swal({ title: 'Cons Tidak Valid', <?php echo $img_alert; ?> }); 
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
	      {	alert("Data Berhasil Disimpan");
	      	window.location.reload();
				},
	      error: function (request, status, error) 
	      { alert(request.responseText); },
	    });
		}	
  };
  function add_ot()
  {	var id_cs = <?php echo $id_cs; ?>;
  	var id_ot = document.getElementById('txthide_ot').value;
  	var j_rate_ot = document.getElementById('txtjrate_ot').value;
  	var id_item_ot = document.getElementById('txtid_item_ot').value;
  	var price_ot = document.getElementById('txtprice_ot').value;
  	var price_idr_ot = document.getElementById('txtprice_idr_ot').value;
  	var cek_so = "<?php echo flookup("so_no","so","id_cost='$id_cs'"); ?>";
  	var cek_unlock = "<?php $dateskrg=date('Y-m-d'); echo flookup("cost_no","unlock_cost","id_cost='$id_cs' and DATE_ADD(unlock_date, INTERVAL 2 DAY)>'$dateskrg'"); ?>";
		if (cek_so != "" && cek_unlock == "")
  	{	swal({ title: 'Costing Tidak Bisa Dirubah Karena Sudah Dibuat SO', <?php echo $img_alert; ?> }); 
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
		else if (isNaN(price_ot))
  	{	swal({ title: 'Price Tidak Valid', <?php echo $img_alert; ?> }); 
			exit;
		}
		else
  	{	jQuery.ajax
	    ({url: "ajax_ot.php",
	      method: 'POST',
	      data: {id_cs: id_cs,id_ot: id_ot,id_item_ot: id_item_ot,
	      	price_ot: price_ot,j_rate_ot: j_rate_ot,price_idr_ot: price_idr_ot},
	      success: function(response)
	      {	alert("Data Berhasil Disimpan");
	      	window.location.reload();
				},
	      error: function (request, status, error) 
	      { alert(request.responseText); },
	    });
		}	
  };
</script>