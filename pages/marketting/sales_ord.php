<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("sales_order","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

$cprodgr="Product Group"; 
$cprodit="Product Item";
$cstyle="Style #";

$mod=$_GET['mod'];

if (isset($_GET['id'])) {$id_so = $_GET['id']; } else {$id_so = "";}
if (isset($_GET['pro'])) {$pro = $_GET['pro']; } else {$pro = "";}
$user_so=flookup("username","so","id='$id_so'");
$titlenya="Act-Costing";
$mode="";
$mod=$_GET['mod'];
$id_item=$id_so;
$st_num="style='text-align: right;'";
# COPAS EDIT
if ($id_item=="")
{	$id_cost = "";
	$txtprodgroup="";
	$txtproditem="";
	$txtstyleno="";
	$txtwsno="";
	$txtsupplier="";
	$txtdeldate="";
	$buyerno = "";
	$so_no = "";
	$so_date = date('d M Y');;
	$qty = "";
	$season = "";
	$terms = "";
	$jdays = "";
	$unit = "";
	$curr = "";
	$fob = "";
	$tax = "";
	$jns_so = "";
}
else
{	$query = mysql_query("SELECT * FROM so where id='$id_so' ");
	$data = mysql_fetch_array($query);
	$id_cost = $data['id_cost'];
	$sql="select d.supplier,f.product_group,f.product_item,styleno
		,g.kpno,g.deldate 
		from act_costing g 
		inner join mastersupplier d on g.id_buyer=d.id_supplier 
		inner join masterproduct f on g.id_product=f.id 
		where g.id='$id_cost'";
	$rs=mysql_fetch_array(mysql_query($sql));
	$txtprodgroup=$rs['product_group'];
	$txtproditem=$rs['product_item'];
	$txtstyleno=$rs['styleno'];
	$txtwsno=$rs['kpno'];
	$txtsupplier=$rs['supplier'];
	$txtdeldate=fd_view($rs['deldate']);
	if ($pro=="")
	{	$buyerno = $data['buyerno'];
		$so_no = $data['so_no'];
		$so_date = fd_view($data['so_date']);
		$qty = $data['qty'];
	}
	else
	{	$buyerno = "";
		$so_no = "";
		$so_date = date('d M Y');;
		$qty = "";
	}
	$season = $data['id_season'];
	$terms = $data['id_terms'];
	$jdays = $data['jml_pterms'];
	$unit = $data['unit'];
	$curr = $data['curr'];
	$fob = $data['fob'];
	$tax = $data['tax'];
	$jns_so = $data['jns_so'];

$sql_cost ="SELECT 
		idcostings,
		round(sum(tot_cost) + total_ga_cost,2) cost_act
		FROM
(
		SELECT 
		a.id id_cost,
		nama_group,
		price,
		cons,
		allowance,
		jenis_rate,
		curr,
		sum(round((price * cons) + (price * cons * allowance / 100),2)) tot_cost
		FROM act_costing a 
		INNER JOIN act_costing_mat s ON a.id=s.id_act_cost 
		INNER JOIN mastergroup d INNER JOIN mastersubgroup f ON d.id=f.id_group 
		INNER JOIN mastertype2 g ON f.id=g.id_sub_group
		INNER JOIN mastercontents h ON g.id=h.id_type AND s.id_item=h.id
		INNER JOIN mastersupplier i ON a.id_buyer=i.Id_Supplier
		WHERE a.id = '$id_cost'
		AND nama_group LIKE '%FABRIC%' and price > 0 
UNION
		SELECT 
		a.id id_cost,
		nama_group,
		price,
		cons,
		allowance,
		jenis_rate,
		curr,
		sum(round((price * cons) + (price * cons * allowance / 100),2)) tot_cost
		FROM act_costing a 
		INNER JOIN act_costing_mat s ON a.id=s.id_act_cost 
		INNER JOIN mastergroup d 
		INNER JOIN mastersubgroup f ON d.id=f.id_group 
		INNER JOIN mastertype2 g ON f.id=g.id_sub_group
		INNER JOIN mastercontents h ON g.id=h.id_type AND s.id_item=h.id
		INNER JOIN mastersupplier i ON a.id_buyer=i.Id_Supplier
		WHERE a.id = '$id_cost'
		AND nama_group LIKE '%ACCESORIES%' and price > 0 
UNION	
		SELECT 
		a.id id_cost,
		cfdesc,
		price,
		cons,
		allowance,
		jenis_rate,
		curr,
		sum(round((price * cons) + (price * cons * allowance / 100),2)) tot_cost
		FROM act_costing a 
		INNER JOIN act_costing_mfg s ON a.id=s.id_act_cost 
		INNER JOIN mastercf i ON s.id_item = i.id
		WHERE a.id = '$id_cost'
UNION	
		SELECT
		a.id id_cost,
		othersdesc,
		price,
		cons,
		allowance,
		jenis_rate,
		curr,
		round(sum(price),2) tot_cost
		FROM act_costing a 
		INNER JOIN act_costing_oth s ON a.id=s.id_act_cost 
		INNER JOIN masterothers i ON s.id_item = i.id
		INNER JOIN mastersupplier h ON a.id_buyer=h.Id_Supplier
		WHERE a.id = '$id_cost'		
) a
left join 
(
SELECT
				idCostings,
       round((total_cd + total_mf + total_ot) * ga_cost / 100,2) AS total_ga_cost
FROM (
    SELECT 
				idCostings,
        x.ga_cost,
        SUM(CASE WHEN category = '1' THEN valcs ELSE 0 END) AS total_cd,
        SUM(CASE WHEN category = '1' THEN valcs_idr ELSE 0 END) AS total_cd_idr,
        SUM(CASE WHEN category = '2' THEN valcs ELSE 0 END) AS total_mf,
        SUM(CASE WHEN category = '2' THEN valcs_idr ELSE 0 END) AS total_mf_idr,
        SUM(CASE WHEN category = '3' THEN valcs ELSE 0 END) AS total_ot,
        SUM(CASE WHEN category = '3' THEN valcs_idr ELSE 0 END) AS total_ot_idr
    FROM (
        SELECT 
            a.ga_cost,
            d.nama_group AS cod,
            CONCAT(d.nama_group, ' ', f.nama_sub_group, ' ', g.nama_type, ' ', h.nama_contents) AS it,
            s.price AS price,
            s.cons AS cons,
            s.unit AS unit,
            s.allowance AS allowance,
            s.material_source AS material_source,
            s.id AS id,
            s.jenis_rate AS jenis_rate,
            'Costing Detail' AS categorydescription,
            a.id AS idCostings,
            '1' AS category,
            COALESCE(m.rate_beli, 1) AS rate_beli,
            COALESCE(m.rate_jual, 1) AS rate_jual,
            CASE 
                WHEN s.jenis_rate = 'J' THEN s.price
                ELSE s.price / COALESCE(m.rate_beli, 1)
            END AS px_usd,
            CASE 
                WHEN s.jenis_rate = 'J' THEN s.price * COALESCE(m.rate_jual, 1)
                ELSE s.price
            END AS px_idr,
            (CASE 
                WHEN s.jenis_rate = 'J' THEN s.price
                ELSE s.price / COALESCE(m.rate_beli, 1)
            END * s.cons) + ((CASE 
                WHEN s.jenis_rate = 'J' THEN s.price
                ELSE s.price / COALESCE(m.rate_beli, 1)
            END * s.cons) * (s.allowance / 100)) AS valcs,
            (CASE 
                WHEN s.jenis_rate = 'J' THEN s.price * COALESCE(m.rate_jual, 1)
                ELSE s.price
            END * s.cons) + ((CASE 
                WHEN s.jenis_rate = 'J' THEN s.price * COALESCE(m.rate_jual, 1)
                ELSE s.price
            END * s.cons) * (s.allowance / 100)) AS valcs_idr
        FROM act_costing a
        INNER JOIN act_costing_mat s ON a.id = s.id_act_cost
        INNER JOIN mastergroup d 
        INNER JOIN mastersubgroup f ON d.id = f.id_group
        INNER JOIN mastertype2 g ON f.id = g.id_sub_group
        INNER JOIN mastercontents h ON g.id = h.id_type AND s.id_item = h.id
        LEFT JOIN masterrate m ON m.curr = 'USD' 
                               AND m.v_codecurr IN ('COSTING3', 'COSTING6', 'COSTING8', 'COSTING12') 
                               AND m.tanggal = a.deldate
        WHERE a.id = '$id_cost'
        UNION
        SELECT 
            a.ga_cost,
            d.cfcode AS cod,
            d.cfdesc AS it,
            s.price AS price,
            s.cons AS cons,
            s.unit AS unit,
            s.allowance AS allowance,
            s.material_source AS material_source,
            s.id AS id,
            s.jenis_rate AS jenis_rate,
            'Manufacturing-Complexity' AS categorydescription,
            a.id AS idCostings,
            '2' AS category,
            COALESCE(m.rate_beli, 1) AS rate_beli,
            COALESCE(m.rate_jual, 1) AS rate_jual,
            CASE 
                WHEN s.jenis_rate = 'J' THEN s.price
                ELSE s.price / COALESCE(m.rate_beli, 1)
            END AS px_usd,
            CASE 
                WHEN s.jenis_rate = 'J' THEN s.price * COALESCE(m.rate_jual, 1)
                ELSE s.price
            END AS px_idr,
            (CASE 
                WHEN s.jenis_rate = 'J' THEN s.price
                ELSE s.price / COALESCE(m.rate_beli, 1)
            END * s.cons) + ((CASE 
                WHEN s.jenis_rate = 'J' THEN s.price
                ELSE s.price / COALESCE(m.rate_beli, 1)
            END * s.cons) * (s.allowance / 100)) AS valcs,
            (CASE 
                WHEN s.jenis_rate = 'J' THEN s.price * COALESCE(m.rate_jual, 1)
                ELSE s.price
            END * s.cons) + ((CASE 
                WHEN s.jenis_rate = 'J' THEN s.price * COALESCE(m.rate_jual, 1)
                ELSE s.price
            END * s.cons) * (s.allowance / 100)) AS valcs_idr
        FROM act_costing a
        INNER JOIN act_costing_mfg s ON a.id = s.id_act_cost
        INNER JOIN mastercf d ON s.id_item = d.id
        LEFT JOIN masterrate m ON m.curr = 'USD' 
                               AND m.v_codecurr IN ('COSTING3', 'COSTING6', 'COSTING8', 'COSTING12') 
                               AND m.tanggal = a.deldate
        WHERE a.id = '$id_cost'
        UNION
        SELECT 
            a.ga_cost,
            d.otherscode AS cod,
            d.othersdesc AS it,
            s.price AS price,
            s.cons AS cons,
            s.unit AS unit,
            s.allowance AS allowance,
            s.material_source AS material_source,
            s.id AS id,
            s.jenis_rate AS jenis_rate,
            'Other Costing' AS categorydescription,
            a.id AS idCostings,
            '3' AS category,
            COALESCE(m.rate_beli, 1) AS rate_beli,
            COALESCE(m.rate_jual, 1) AS rate_jual,
            CASE 
                WHEN s.jenis_rate = 'J' THEN s.price
                ELSE s.price / COALESCE(m.rate_beli, 1)
            END AS px_usd,
            CASE 
                WHEN s.jenis_rate = 'J' THEN s.price * COALESCE(m.rate_jual, 1)
                ELSE s.price
            END AS px_idr,
            CASE 
                WHEN s.jenis_rate = 'J' THEN s.price
                ELSE s.price / COALESCE(m.rate_beli, 1)
            END  AS valcs,
            CASE 
                WHEN s.jenis_rate = 'J' THEN s.price * COALESCE(m.rate_jual, 1)
                ELSE s.price
            END AS valcs_idr
        FROM act_costing a
        INNER JOIN act_costing_oth s ON a.id = s.id_act_cost
        INNER JOIN masterothers d ON s.id_item = d.id
        LEFT JOIN masterrate m ON m.curr = 'USD' 
                               AND m.v_codecurr IN ('COSTING3', 'COSTING6', 'COSTING8', 'COSTING12') 
                               AND m.tanggal = a.deldate
        WHERE a.id = '$id_cost'
    ) X
) Y
ORDER BY Y.total_cd DESC
) b on a.id_cost = b.idCostings";

	$rs_cost=mysql_fetch_array(mysql_query($sql_cost));
	$txtcost_act=$rs_cost['cost_act'];

$blc = 	$fob - $txtcost_act;

if ($fob > $txtcost_act){
	$stat_blc = 'readonly';
}
else
{
	$stat_blc = 'required';
}

}


$tglsoawal = date('Y-m-d');
$tglsoakhir = date('Y-m-d');

if (isset($_POST['submitfilter']))
{
  $tglsoawal = $_POST['tglsoawal'];
  $tglsoakhir = $_POST['tglsoakhir'];
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>
	function validasi()
	{	var id_cost = document.form.txtid_cost.value;
		var buyerno = document.form.txtbuyerno.value;
		var so_no = document.form.txtso_no.value;
		var so_date = document.form.txtso_date.value;
		var qty = document.form.txtqty.value;
		var unit = document.form.txtunit.value;
		var fob = document.form.txtfob.value;
 		
 		if (id_cost == '') { document.form.txtid_cost.focus(); swal({ title: 'Costing # Tidak Boleh Kosong', $img_alert }); valid = false;}
		else if (buyerno == '') { document.form.txtbuyerno.focus(); swal({ title: 'PO Buyer # Tidak Boleh Kosong', $img_alert }); valid = false;}
		else if (so_date == '') { document.form.txtso_date.focus(); swal({ title: 'SO Date Tidak Boleh Kosong', $img_alert }); valid = false;}
		else if (qty == '') { document.form.txtqty.focus(); swal({ title: 'Qty Tidak Boleh Kosong', $img_alert }); valid = false;}
		else if (unit == '') { document.form.txtunit.focus(); swal({ title: 'Unit Tidak Boleh Kosong', $img_alert }); valid = false;}
		else if (fob == '') { document.form.txtfob.focus(); swal({ title: 'FOB Tidak Boleh Kosong', $img_alert }); valid = false;}
		else valid = true;
		return valid;
		exit;
	}
</script>";
# END COPAS VALIDASI
# COPAS ADD
?>
<script type="text/javascript">
  function getActCost()
  { 
	
	var actcostid = document.form.txtid_cost.value;
		check_customer(actcostid,"N","MKT").then(function($responnya) {
			console.log($responnya);
			if($responnya == 'PASS'){
				jQuery.ajax
				({  
				url: "ajax_sales_ord.php?mdajax=get_act_cost",
				method: 'POST',
				data: {actcostid: actcostid},
				dataType: 'json',
				success: function(response)
				{
					$('#txtprodgroup').val(response[0]);
					$('#txtproditem').val(response[1]);
					$('#txtstyleno').val(response[2]);
					$('#txtwsno').val(response[3]);
					$('#txtsupplier').val(response[4]);
					$('#txtdeldate').val(response[5]);
					$('#txtfob').val(response[6]);
					var $newOption = $("<option selected='selected'></option>").val(response[7]).text(response[7])
					$("#txtcurr").append($newOption).trigger('change');
				},
				error: function (request, status, error) 
				{ alert(request.responseText); },
				});
			}else{
				{ swal({ title: 'No costing ini tidak dapat diproses karena customer di block', imageUrl: '../../images/error.jpg' });}
					$('#txtprodgroup').val('');
					$('#txtproditem').val('');
					$('#txtstyleno').val('');
					$('#txtwsno').val('');
					$('#txtsupplier').val('');
					$('#txtdeldate').val('');
					$('#txtfob').val('');
					$('#txtid_cost').val('').trigger('change');
				
				
				
			}
		})
  };
</script>
<!--COPAS ADD-->
<?php if ($mod=="7") { ?>
<div class='box'>
	<div class='box-body'>
		<div class='row'>
			<form method='post' name='form' enctype='multipart/form-data' action='s_sales_ord.php?mod=<?php echo $mod; ?>&id=<?php echo $id_so; ?>&pro=<?php echo $pro; ?>' onsubmit='return validasi()'>
				<div class='col-md-3'>								
					<div class='form-group'>
						<label>Costing # *</label>
						<select class='form-control select2' style='width: 100%;' name='txtid_cost' onchange='getActCost(this.value)'>
							<?php
								if ($id_so=="") {$sql_whe="a.status in ('CONFIRM','SAMPLE') and app1='A' and s.id is null ";} else {$sql_whe="a.id='$id_cost'";}
								$sql = "select a.id isi,concat(a.cost_no,'|',ms.supplier,'|',a.styleno) tampil from 
									act_costing a inner join mastersupplier ms on a.id_buyer=ms.id_supplier 
									left join so s on a.id=s.id_cost and 'N'=s.cancel_h 
									where $sql_whe ";
								IsiCombo($sql,$id_cost,'Pilih Costing #');
							?>
						</select>
					</div>				
					<div class='form-group'>
						<label><?php echo $cprodgr; ?></label>
						<input type='text' class='form-control' name='txtprodgroup' id='txtprodgroup' value='<?php echo $txtprodgroup; ?>' placeholder='Masukkan <?php echo $cprodgr; ?>' readonly >
					</div>				
					<div class='form-group'>
						<label><?php echo $cprodit; ?></label>
						<input type='text' class='form-control' name='txtproditem' id='txtproditem' value='<?php echo $txtproditem; ?>' placeholder='Masukkan <?php echo $cprodit; ?>' readonly >
					</div>				
					<div class='row'>
						<div class='col-md-6'>
							<div class='form-group'>
								<label><?php echo $cstyle; ?></label>
								<input type='text' class='form-control' name='txtstyleno' id='txtstyleno' value='<?php echo $txtstyleno; ?>' placeholder='Masukkan <?php echo $cstyle; ?>' readonly >
							</div>
						</div>				
						<div class='col-md-6'>
							<div class='form-group'>
								<label>WS Number</label>
								<input type='text' class='form-control' id='txtwsno' value='<?php echo $txtwsno; ?>' placeholder='Masukkan WS Number' readonly >
							</div>
						</div>
					</div>
				</div>
				<div class='col-md-3'>				
					<div class='form-group'>
						<label>Buyer</label>
						<input type='text' class='form-control' id='txtsupplier' value='<?php echo $txtsupplier; ?>' placeholder='Masukkan Buyer' readonly >
					</div>				
					<div class='form-group'>
						<label>Delivery Date</label>
						<input type='text' class='form-control' id='txtdeldate' value='<?php echo $txtdeldate; ?>' placeholder='Masukkan Delivery Date' readonly >
					</div>				
					<div class='form-group'>
						<label>PO Buyer # *</label>
						<input type='text' class='form-control' name='txtbuyerno' placeholder='Masukkan PO Buyer #' value='<?php echo $buyerno;?>' >
					</div>
					<div class='row'>
						<div class='col-md-6'>				
							<div class='form-group'>
								<label>SO # *</label>
								<input type='text' readonly class='form-control' name='txtso_no' placeholder='Masukkan SO #' value='<?php echo $so_no;?>' >
							</div>
						</div>
						<div class='col-md-6'>				
							<div class='form-group'>
								<label>SO Date *</label>
								<input type='text' readonly class='form-control' name='txtso_date' placeholder='Masukkan SO Date' value='<?php echo $so_date;?>' >
							</div>
						</div>
					</div>
				</div>
				<div class='col-md-3'>				
					<div class='form-group'>
			      <label>Season *</label>
			      <select class='form-control select2' style='width: 100%;' name='txtseason' required>
			       <?php 
			        $sql = "select id_season isi,season tampil from 
			          masterseason";
			        IsiCombo($sql,$season,'Pilih Season');
			       ?>
			      </select>
			    </div>
					<div class='row'>
						<div class='col-md-6'>
							<div class='form-group'>
								<label>Qty *</label>
								<input type='text' class='form-control' name='txtqty' placeholder='Masukkan Qty' value='<?php echo $qty;?>' >
							</div>
						</div>					
						<div class='col-md-6'>
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
						</div>
						<div class='col-md-6'>
							<div class='form-group'>
								<label>Currency</label>
								<select class='form-control select2' style='width: 100%;' name='txtcurr' id='txtcurr'>
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
								<label>FOB *</label>
								<input type='text' class='form-control' name='txtfob' id='txtfob' placeholder='Masukkan FOB' value='<?php echo $fob;?>' readonly>
							</div>
						</div>
						<div class='col-md-6'>
							<div class='form-group'>
					      <label>Payment Terms *</label>
					      <select class='form-control select2' style='width: 100%;' name='txtterms'>
					       <?php 
					        $sql = "select id isi,concat(kode_pterms,'-',nama_pterms) tampil
		              	from masterpterms where aktif='Y' ";
		              IsiCombo($sql,$terms,'Pilih Pay Terms');
					       ?>
					      </select>
					    </div>
						</div>
						<div class='col-md-6'>				
							<div class='form-group'>
								<label>Days</label>
								<input type='text' class='form-control' name='txtdays' value='<?php echo $jdays;?>' >
							</div>
						</div>
					</div>
				</div>

				<div class='col-md-3'>
					<div class='form-group'>
						<label>Tax (%)</label>
						<input type='text' class='form-control' name='txttax' placeholder='Masukkan Tax' value='<?php echo $tax;?>' >
					</div>
					<div class='form-group'>
								<label>Jenis SO *</label>
								<select class='form-control select2' style='width: 100%;' name='txtjns_so' id='txtjns_so' value='<?php echo $jns_so;?>' required>
									<?php 
										$sql = "select nama_pilihan isi,nama_pilihan tampil from 
											masterpilihan where kode_pilihan='Sales_order' order by id asc";
										IsiCombo($sql,$jns_so,'Pilih Jenis');
									?>
								</select>
					</div>

    <div class='form-group row'>
        <div class='col-md-6'>
            <label>Cost Act</label>
            <input type='text' class='form-control' name='txtcost_act' id='txtcost_act' value='<?php echo $txtcost_act;?>' readonly>
        </div>
        <div class='col-md-6'>
            <label>Balance</label>
            <input type='text' class='form-control' name='txtblc' id='txtblc' value='<?php echo $blc;?>' readonly>
        </div>
    </div>
    				<div class='form-group'>
						<label>Keterangan Blc</label>
						<input type='text' class='form-control' name='txtket_blc' id='txtket_blc' <?php echo $stat_blc;?>>
					</div>

					<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
				</div>

			</form>
		</div>
	</div>
</div>
<?php } ?>
<!--END COPAS ADD-->
<?php if ($id_so!="") { ?>
<form method='post' action='d_so_mul.php?mod=7&id=<?php echo $id_so;?>'>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Sales Order Detail</h3>
  	<?php if ($mod=="8d") { ?>
  		<button type='submit' name='submit' class='btn btn-danger btn-s'>
  			<i class='fa fa-trash'></i>
  			Cancel Selected
  		</button>
  	<?php } else if ($user_so==$user) { ?>
	  	<a href='../marketting/?mod=8&id=<?php echo $id_so; ?>' class='btn btn-primary btn-s'>
	  		<i class='fa fa-plus'></i> Add Item
	  	</a> <a href='../marketting/?mod=8d&id=<?php echo $id_so; ?>' class='btn btn-danger btn-s'>
	  		<i class='fa fa-trash'></i> Multi Cancel
	  	</a> <a href='../marketting/?mod=8mu&id=<?php echo $id_so; ?>' class='btn btn-primary btn-s'>
	  		<i class='fa fa-pencil'></i> Multi Update
	  	</a> 
	<?php } ?>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>
	    	<?php if ($mod!="8d") { ?>
	    		<th>No</th>
	    	<?php } else { ?>
	    		<th>..</th>
	    	<?php } ?>
        <th>Deliv. Date</th>
        <th>Destination</th>
				<th>Color</th>
				<th>Size</th>
				<th>Reff No</th>
				<th>Style No Production</th>
				<th>Qty</th>
				<th>Qty Add</th>
				<th>Unit</th>
				<th>Price</th>
				<th>SKU</th>
				<th>Barcode</th>
				<th>Notes</th>
				<th>Status</th>
				<?php if ($mod!="8d") { ?>
					<th></th>
					<th></th>
				<?php } ?>
			</tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		    $query = mysql_query("SELECT sod.*,so.username,sod.price px_det from so_det sod inner join so on sod.id_so=so.id 
		    	where sod.id_so='$id_so'"); 
        $no = 1;
        $tot_qty=0; 
				while($data = mysql_fetch_array($query))
			  { if($data['cancel']=="Y") { $bgcol=" style='color: red; font-weight:bold;'"; } else { $bgcol=""; }
          echo "<tr $bgcol>";
				    if ($mod!="8d") 
				    	{	echo "<td>$no</td>"; } 
				    	else 
				    	{ echo "
				    		<td>
				    			<input type ='hidden' name='chkhide[$data[id]]' value='$data[id]'>
                  <input type ='checkbox' name='itemchk[$data[id]]' class='chkclass'>
               	</td>"; 
              }
				    echo "
				    <td>".fd_view($data['deldate_det'])."</td>
				    <td>$data[dest]</td>
				    <td>$data[color]</td>
				    <td>$data[size]</td>
					<td>$data[reff_no]</td>
					<td>$data[styleno_prod]</td>
				    <td>".fn($data['qty'],0)."</td>
				    <td>".fn($data['qty_add'],0)."</td>
				    <td>$data[unit]</td>
				    <td>$data[px_det]</td>
				    <td>$data[sku]</td>
				    <td>$data[barcode]</td>
				    <td>$data[notes]</td>";
						if($data['cancel']=="Y")
						{echo "<td>Cancelled</td>";}
						else
						{echo "<td></td>";}
						if ($mod!="8d")
						{	if ($data['username']==$user and $data['cancel']=="N")
							{	echo "
								<td>
									<a href='?mod=8&id=$id_so&idd=$data[id]'
		              	data-toggle='tooltip' title='Ubah'><i class='fa fa-pencil'></i></a>
	              </td>";
								echo "
								<td>
									<a href='d_so.php?mod=$mod&id=$id_so&idd=$data[id]&mod=$mod'
			            	$tt_cancel";?> 
			            	onclick="return confirm('Apakah anda yakin akan dicancel ?')">
			            	<?php echo $tt_hapus2."</a>
			          </td>";
			       	}
			       	else
			       	{ echo "<td></td>"; 
			       		echo "<td></td>";
			     		}

						}
					echo "</tr>";
				  $tot_qty = $tot_qty + $data['qty'];
				  $no++; // menambah nilai nomor urut
				}
			  ?>
      </tbody>
    </table>
    Total : <?php echo fn($tot_qty,0); ?> 
  </div>
</div>
</form>
<?php } else if ($mod=="7L") { ?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">List Sales Order</h3>
  	<a href='../marketting/?mod=7' class='btn btn-primary btn-s'>
  		<i class='fa fa-plus'></i> New
  	</a>
  </div>

<div class='row'>
    <form action="" method="post">

    <div class="box-header">
      <div class='col-md-2'>                            
        <label>Tgl So (Awal) : </label>
        <input type='date' class='form-control' id='tglsoawal' name='tglsoawal' placeholder='Masukkan Tgl SO' value='<?php echo $tglsoawal;?>' >             
      </div>
      <div class='col-md-2'>                            
        <label>Tgl So (Akhir) : </label>
        <input type='date' class='form-control' id='tglsoakhir' name='tglsoakhir' placeholder='Masukkan Tgl SO' value='<?php echo $tglsoakhir;?>' >             
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
    <table id="examplefix3" class="display responsive" style="width:100%;font-size:12px;">
      <thead>
      <tr>
        <?php if ($mod=="12v") { ?>
          <th>..</th>
        <?php } ?>
        <th>SO #</th>
        <th>Buyer PO</th>
        <th>WS #</th>
        <th>Cost #</th>
        <th>Buyer</th>
        <th>Product</th>
        <th>Item Name</th>
        <th><?php echo $cstyle; ?></th>
        <th>Qty</th>
        <th>Price FOB</th>
        <th>Delv Date</th>
        <th>P.Terms</th>
        <th>Created By</th>
        <th>Created Date</th>
        <th>Jenis SO</th>
        <th>Season</th>
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
        $query = mysql_query("select a.id_cost,cancel_h,a.username,a.id,so_no,buyerno,kpno,cost_no,
          supplier,product_group,product_item,styleno,a.qty,a.unit,
          deldate,fullname,a.so_date, a.jns_so, round(a.fob,2) fob, ms.season, concat(nama_pterms, ' (' ,a.jml_pterms, ' days - After',' ', mp.kode_pterms,')') ket_terms
          from so a inner join act_costing s on 
          a.id_cost=s.id inner join mastersupplier g on 
          s.id_buyer=g.id_supplier inner join masterproduct h 
          on s.id_product=h.id left join jo_det j on a.id=j.id_so and 'N'=j.cancel 
          inner join userpassword up on a.username=up.username
          left join masterseason ms on a.id_season = ms.id_season 
          left join masterpterms mp on a.id_terms = mp.id
          where so_type='B' and a.so_date >= '$tglsoawal' and a.so_date <= '$tglsoakhir' order by a.so_date desc"); 
        if (!$query) {die(mysql_error());}
        $no = 1; 
        while($data = mysql_fetch_array($query))
        { $id=$data['id'];
    			$cekjo = flookup("id_jo","jo_det","id_so='$id'");
          if($data['cancel_h']=="Y") { $bgcol=" style='color: red; font-weight:bold;'"; } else { $bgcol=""; }
          echo "<tr $bgcol>";
            if ($mod=="12v")
            {echo "<td><input type ='checkbox' name ='itemchk[$id]' id='chkajax$id' class='chkclass' ></td>";} 
            echo "<td>$data[so_no]</td>";
            echo "<td>$data[buyerno]</td>";
            echo "<td>$data[kpno]</td>";
            echo "<td>$data[cost_no]</td>";
            echo "<td>$data[supplier]</td>";
            echo "<td>$data[product_group]</td>";
            echo "<td>$data[product_item]</td>";
            echo "<td>$data[styleno]</td>";
            echo "<td>".fn($data['qty'],0)." $data[unit]</td>";
            echo "<td>$data[fob]</td>";
            echo "<td>".fd_view($data['deldate'])."</td>";
            echo "<td>$data[ket_terms]</td>";
            echo "<td>$data[fullname]</td>";
            echo "<td>".fd_view($data['so_date'])."</td>";
            echo "<td>$data[jns_so]</td>";
            echo "<td>$data[season]</td>";
            if($data['cancel_h']=="Y")
            {echo "<td>Cancelled</td>";}
          	else
          	{echo "<td></td>";}
          	echo "<td>";
            	if ($data['username']==$user)
            	{	echo "<a href='?mod=7&id=$data[id]'
              	$tt_ubah</a> "; 
              }
            echo "</td>";
            echo "<td>"; 
            	echo "<a href='pdfSO.php?id=$data[id]'
              	data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i></a>";
            echo "</td>";
            echo "
            <td>
            	<a href='pdfCost.php?id=$data[id_cost]'
      					data-toggle='tooltip' title='Preview Costing' target='_blank'><i class='fa fa-calculator'></i>
    					</a>
  					</td>";
            if($cekjo=="")
            {
            	echo "<td>";
	           		echo "<a href='?mod=7&id=$data[id]&pro=Copy'
	              	data-toggle='tooltip' title='Copy SO'><i class='fa fa-copy'></i></a>";
	            echo "</td>";
            }
            else
            {
            	echo "<td>";
	           	echo "</td>";
            }
            echo "<td>";
            	if ($data['username']==$user)
            	{	echo "
	              <a href='d_soh.php?mod=$mod&id=$data[id]'
	                $tt_hapus";?> 
	                onclick="return confirm('Are You Sure Want To Cancel ?')">
	                <?php echo $tt_hapus2."</a>";
              }
            echo "</td>";
          echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<?php } ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="js/SalesOrder.js"></script>