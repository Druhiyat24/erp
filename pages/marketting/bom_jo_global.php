<?php
if (empty($_SESSION['username'])) {
	header("location:../../index.php");
}

$user = $_SESSION['username'];
$sesi = $_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("bom_jo", "userpassword", "username='$user'");
if ($akses == "0") {
	echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>";
}
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company", "mastercompany", "company!=''");
if ($jenis_company == "VENDOR LG") {
	$cprodgr = "Part #";
	$cprodit = "Part Name";
	$cstyle = "Model";
} else {
	$cprodgr = "Product Group";
	$cprodit = "Product Item";
	$cstyle = "Style #";
}

if (isset($_GET['id'])) {
	$id_jo = $_GET['id'];
} else {
	$id_jo = "";
}
if (isset($_GET['pro'])) {
	$pro = $_GET['pro'];
} else {
	$pro = "";
}
$cekapp = flookup("app", "jo", "id='$id_jo'");

$titlenya = "Act-Costing";
$mode = "";
$mod = $_GET['mod'];
$id_item = $id_jo;
$st_num = "style='text-align: right;'";
# COPAS EDIT
if ($id_item == "") {
	$id_pre_cost = "";
	$txtprodgroup = "";
	$txtproditem = "";
	$txtbuyer = "";
	$txtstyle = "";
} else {
	$query = mysql_query("SELECT * FROM bom_jo_item where id_jo='$id_jo'");
	$data = mysql_fetch_array($query);
	$id_pre_cost = $data['id_jo'];
	$sql = "select a.id,so_no,buyerno,kpno,cost_no,
   	supplier,product_group,product_item,styleno,a.qty,a.unit,
    deldate from so a inner join act_costing s on 
    a.id_cost=s.id inner join mastersupplier g on 
    s.id_buyer=g.id_supplier inner join masterproduct h 
    on s.id_product=h.id left join jo_det j on a.id=j.id_so 
    where j.id_jo='$id_jo'";
	$rs = mysql_fetch_array(mysql_query($sql));
	$txtprodgroup = $rs['product_group'];
	$txtproditem = $rs['product_item'];
	$txtbuyer = $rs['supplier'];
	$txtstyle = $rs['styleno'];
	$id_pre_cost = $data['id_jo'];
}

$tglbomawal = date('Y-m-d');
$tglbomakhir = date('Y-m-d');

if (isset($_POST['submitfilter'])) {
	$tglbomawal = $_POST['tglbomawal'];
	$tglbomakhir = $_POST['tglbomakhir'];
}

# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>
	function validasi()
	{	var id_pre_cost = document.form.txtid_pre_cost.value;
		var cekapp = '" . $cekapp . "';
		if (cekapp == 'A') { swal({ title: 'BOM Tidak Bisa Diubah', $img_alert }); valid = false;}
		else if (id_pre_cost == '') { document.form.txtid_pre_cost.focus(); swal({ title: 'JO # Tidak Boleh Kosong', $img_alert }); valid = false;}
		else valid = true;
		return valid;
		exit;
	}
</script>";
# END COPAS VALIDASI
# COPAS ADD
?>
<script type="text/javascript">

	function getSO() {
		var joid = document.form.txtid_pre_cost.value;
		jQuery.ajax({
			url: "ajax_bom.php?mdajax=get_so",
			method: 'POST',
			data: {
				joid: joid
			},
			dataType: 'json',
			success: function(response) {
				$('#txtprodgroup').val(response[0]);
				$('#txtproditem').val(response[1]);
				$('#txtbuyer').val(response[2]);
				$('#txtstyle').val(response[3]);
			},
			error: function(request, status, error) {
				alert(request.responseText);
			},
		});
	};
</script>
<?php if ($mod == "144") { ?>
	<div class='box'>
		<div class='box-body'>
			<div class='row'>
				<form method='post' name='form' enctype='multipart/form-data' action="../marketting/?mod=144d&id=<?php echo $id_jo; ?>" onsubmit='return validasi()'>
					<div class='col-md-3'>
						<div class='form-group'>
							<label>Job Order # *</label>
							<select class='form-control select2' style='width: 100%;' name='txtid_pre_cost' id='txtid_pre_cost' onchange='getSO()'>
								<?php
								if ($id_jo == "") {
									$sql_wh = "where bji.id_jo is null and ac.type_ws = 'global'";
								} else {
									$sql_wh = " where jo.id='$id_jo' and ac.type_ws = 'global'";
								}
								$sql = "select jo.id isi,concat(jo.jo_no,' - ',supplier,' - ',ac.styleno,' - ',ac.kpno) tampil from 
									jo inner join jo_det jod on jo.id=jod.id_jo
									inner join so on jod.id_so=so.id
									inner join act_costing ac on so.id_cost=ac.id 
									inner join mastersupplier ms on ac.id_buyer=ms.id_supplier 
									left join (select id_jo from bom_jo_global_item group by id_jo) bji on jo.id=bji.id_jo 
									$sql_wh 
									group by jo.id ";
								IsiCombo($sql, $id_jo, 'Pilih Job Order #');
								?>
							</select>
						</div>
						<div class='form-group'>
							<label><?php echo $cprodgr; ?></label>
							<input type='text' class='form-control' readonly id='txtprodgroup' value='<?php echo $txtprodgroup; ?>'>
						</div>
						<div class='form-group'>
							<label><?php echo $cprodit; ?></label>
							<input type='text' class='form-control' readonly id='txtproditem' value='<?php echo $txtproditem; ?>'>
						</div>
					</div>
					<div class='col-md-3'>
						<div class='form-group'>
							<label>Buyer</label>
							<input type='text' class='form-control' readonly id='txtbuyer' value='<?php echo $txtbuyer; ?>'>
						</div>
						<div class='form-group'>
							<label><?php echo $cstyle; ?></label>
							<input type='text' class='form-control' readonly id='txtstyle' value='<?php echo $txtstyle; ?>'>
						</div>
						<button type='submit' name='submit' class='btn btn-primary'>Add Item</button>
						<a href='../marketting/?mod=144add_c&id=<?php echo $id_jo; ?>' class='btn btn-info btn-s'>
					Add Ws Child </a>
					</div>
				</form>
			</div>
		</div>
	</div><?php }
		# END COPAS ADD REMOVE </DIV> TERAKHIR
		if ($mod == "144L") {
			?>
	<div class="box">
		<div class="box-header">
			<h3 class="box-title">BOM Global Summary</h3>
			<a href='../marketting/?mod=144' class='btn btn-primary btn-s'>
				<i class='fa fa-plus'></i> New
			</a>
		</div>

		<div class='row'>
			<form action="" method="post">

				<div class="box-header">
					<div class='col-md-2'>
						<label>Tgl BOM (Awal) : </label>
						<input type='date' class='form-control' id='tglbomawal' name='tglbomawal' placeholder='Masukkan Tgl JO /  Worksheet' value='<?php echo $tglbomawal; ?>'>
					</div>
					<div class='col-md-2'>
						<label>Tgl BOM (Akhir) : </label>
						<input type='date' class='form-control' id='tglbomakhir' name='tglbomakhir' placeholder='Masukkan Tgl JO /  Worksheet' value='<?php echo $tglbomakhir; ?>'>
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
			<table id="examplefix" class="display responsive" style="width:100%;font-size:12px;">
				<thead>
					<tr>
						<th>No</th>
						<th>Buyer</th>
						<th>WS #</th>
						<th>Job Order #</th>
						<th>Job Order Date</th>
						<th>Style #</th>
						<th>Created By</th>
						<th></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php
					# QUERY TABLE
					$query = mysql_query("select a.id_jo,ms.supplier, ac.kpno,jo.jo_no, jo.jo_date,ac.styleno, jo.username, jo.d_insert  from bom_jo_global_item a
		inner join jo on a.id_jo = jo.id
		inner join jo_det jd on a.id_jo = jd.id_jo
		inner join so on jd.id_so = so.id
		inner join act_costing ac on so.id_cost = ac.id
		inner join mastersupplier ms on ac.id_buyer = ms.id_supplier
		where jo.jo_date >= '$tglbomawal' and jo.jo_date <= '$tglbomakhir'
		group by a.id_jo");
					$no = 1;
					while ($data = mysql_fetch_array($query)) {
						$createby = $data['username'] . " " . fd_view_dt($data['d_insert']);
						echo "<tr>";
						echo "
				    <td>$no</td>
				    <td>$data[supplier]</td>
				    <td>$data[kpno]</td>
				    <td>$data[jo_no]</td>
				    <td>" . fd_view($data['jo_date']) . "</td>
				    <td>$data[styleno]</td>
				    <td>$createby</td>";
						echo "
            	<td>
            	<a href='pdfBOM.php?id=$data[id]'
	              data-toggle='tooltip' title='Save PDF' target='_blank'><i class='fa fa-file-pdf-o'></i>
	            </a>
	          </td>
	          <td>
	            <a href='?mod=144&id=$data[id_jo]'
              	data-toggle='tooltip' title='View'><i class='fa fa-eye'></i>
              </a>
            </td>";
						echo "</tr>";
						$no++; // menambah nilai nomor urut
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
<?php } else if ($mod == "144" && $id_jo != "") { ?>
	<form name='form2' method='post' action='d_so_mul.php?mod=14&id=<?php echo $id_jo; ?>'>
		<div class="box">
			<div class="box-header">
				<h3 class="box-title">BOM Global Detail Summary</h3>
			</div>

			<div class='col-md-2'>
				<a href='../marketting/?mod=144t&id=<?php echo $id_jo; ?>' class='btn btn-warning btn-s'>
					Tracking </a>
			</div>

			<div class="box-body">
				<table id="example_bom_global_sum" class="display responsive" style="width:100%;font-size:12px;">
					<thead>
						<tr>
							<th>No</th>
							<th>Jenis Item</th>
							<th>ID Contents</th>
							<th>Nama Contents</th>
							<th>ID Item</th>
							<th>Nama Item</th>
							<th>Qty</th>
							<th>Qty Terima</th>
							<th>Unit</th>
							<th>Supplier</th>
							<th>Notes</th>
						</tr>
					</thead>
					<tbody>
						<?php
						# QUERY TABLE
						$sql = "select a.id, jo.jo_no,ac.kpno,c.matclass,mp.product_group,mp.product_item, a.id_contents, concat(nama_group,' ',nama_sub_group,' ',nama_type,' ',nama_contents) nama_contents,
						a.id_item,c.itemdesc, sum(a.qty) qty, round(coalesce(sum(bpb.qty),0),2) qty_in, a.unit, a.rule_bom, ms.id_supplier,ms.supplier, a.notes, a.username, a.dateinput, a.cancel , a.notes
						from bom_jo_global_item a
						inner join mastercontents b on a.id_contents = b.id
						inner join mastertype2 y on b.id_type = y.id
						inner join mastersubgroup x on y.id_sub_group = x.id
						inner join mastergroup w on x.id_group = w.id
						inner join masteritem c on a.id_item = c.id_item
						inner join mastersupplier ms on a.id_supplier = ms.id_supplier
						inner join jo on a.id_jo = jo.id
						inner join jo_det jd on a.id_jo = jd.id_jo
						inner join so on jd.id_so = so.id
						inner join act_costing ac on so.id_cost = ac.id
						inner join masterproduct mp on ac.id_product = mp.id
						left join (select sum(qty) qty,id_jo,id_supplier,id_item from bpb where id_jo = '$id_jo' group by id_item, id_jo, id_supplier) bpb on a.id_jo = bpb.id_jo and a.id_item = bpb.id_item and a.id_supplier = bpb.id_supplier
						where a.id_jo = '$id_jo'
								group by a.id_item,a.id_jo,a.id_supplier
						order by 
								case 
								when c.matclass = 'Fabric' then '1'
								when c.matclass = 'Accesories Packing' then '2'
								when c.matclass = 'Accesories Sewing' then '3'
								else '4'
								end asc,a.dateinput asc";
						$query = mysql_query($sql);
						#echo $sql;
						$no = 1;
						while ($data = mysql_fetch_array($query)) {
							if ($data['cancel'] == "Y") {
								$bgcol = "style='color:red;'";
								$status = 'Cancel';
							} else {
								$bgcol = "";
								$status = 'None';
							}
							echo "
          <tr $bgcol>
		  <td>$no</td>
		  <td>$data[matclass]</td>
		  <td>$data[id_contents]</td>
		  <td>$data[nama_contents]</td>
		  <td>$data[id_item]</td>
		  <td>$data[itemdesc]</td>
		  <td>$data[qty]</td>
		  <td>$data[qty_in]</td>
		  <td>$data[unit]</td>
		  <td>$data[supplier]</td>
		  <td>$data[notes]</td>";
		  echo "</tr>";
		  $no++; // menambah nilai nomor urut
						}
						?>
					</tbody>
        <tfoot>
            <tr>
                <th colspan="6" style="text-align:right">Total:</th>
                <th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
            </tr>
        </tfoot>		
				</table>
			</div>
		</div>
	</form>
<?php }
?>
<?php if ($mod == "144t") { ?>


<form name='form2' method='post' action='d_so_mul.php?mod=14&id=<?php echo $id_jo; ?>'>
		<div class="box">
			<div class="box-header">
				<h3 class="box-title">BOM Global Detail Summary</h3>
			</div>

			<div class='col-md-2'>
				<a href='../marketting/?mod=144&id=<?php echo $id_jo; ?>' class='btn btn-danger btn-s'>
					Back </a>
			</div>

			<div class="box-body">
				<table id="example_bom_global_sum" class="display responsive" style="width:100%;font-size:11px;">
					<thead>
						<tr>
							<th>No</th>
							<th>No BPB</th>
							<th>Tgl BPB</th>
							<th>Supplier</th>
							<th>ID Item</th>
							<th>Nama Item</th>
							<th>Qty Terima</th>
							<th>Unit</th>
							<th>Tgl Input</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						<?php
						# QUERY TABLE
						$sql = "
select a.bpbno_int, a.bpbdate, ms.supplier,a.id_item,mi.itemdesc,a.qty, a.unit, a.cancel, a.confirm, confirm_by, confirm_date, a.dateinput 
from bpb a
inner join bom_jo_global_item b on a.id_jo = b.id_jo and a.id_item = b.id_item
inner join mastersupplier ms on a.id_supplier = ms.id_supplier
inner join masteritem mi on a.id_item = mi.id_item
where a.id_jo = '$id_jo'
order by bpbdate desc";
						$query = mysql_query($sql);
						#echo $sql;
						$no = 1;	
						while ($data = mysql_fetch_array($query)) {
						$confirmfix = $data['confirm_by'] . " " . fd_view_dt($data['confirm_date']);
						if ($data['cancel'] == "Y") {
								$bgcol = "style='color:red;'";
								$status = 'Cancel';
							} 
						else if ($data['confirm'] == "Y") {
								$bgcol = "";
								$status = "$confirmfix";
							}							
							echo "
          <tr $bgcol>
		  <td>$no</td>
		  <td>$data[bpbno_int]</td>
		  <td>" . fd_view($data['bpbdate']) . "</td>
		  <td>$data[supplier]</td>
		  <td>$data[id_item]</td>
		  <td>$data[itemdesc]</td>
		  <td>$data[qty]</td>
		  <td>$data[unit]</td>
		  <td>" . fd_view_dt($data['dateinput']) . "</td>
		  <td>$status</td>";
		  echo "</tr>";
		  $no++; // menambah nilai nomor urut
						}
						?>
					</tbody>
        <tfoot>
            <tr>
                <th colspan=6 style="text-align:right">Total:</th>
                <th></th>
				<th></th>
				<th></th>
				<th></th>
            </tr>
        </tfoot>		
				</table>
			</div>
		</div>
	</form>
	<?php } ?>


<?php if ($mod == "144add_c") { ?>


<form name='form2' method='post' action='s_bom_jo_global.php?mod=simpan_ws_child&id=<?php echo $id_jo; ?>'>

		<div class="box">
			<div class="box-header">
				<h3 class="box-title">BOM Global Detail Summary</h3>
								<a href='../marketting/?mod=144&id=<?php echo $id_jo; ?>' class='btn btn-danger btn-s'>
					Back </a>
			</div>

			<div class='col-md-6'>
						<div class='form-group'>
							<label>Job Order # *</label>
							<select class='form-control select2' style='width: 100%;' name='txtid_pre_cost' id='txtid_pre_cost'>
								<?php
								$sql = "select jo.id isi,concat(jo.jo_no,' - ',supplier,' - ',ac.styleno,' - ',ac.kpno) tampil from 
									jo inner join jo_det jod on jo.id=jod.id_jo
									inner join so on jod.id_so=so.id
									inner join act_costing ac on so.id_cost=ac.id 
									inner join mastersupplier ms on ac.id_buyer=ms.id_supplier 
									left join (select id_jo from bom_jo_global_item group by id_jo) bji on jo.id=bji.id_jo 
									where jo.id = '$id_jo' 
									group by jo.id ";
								IsiCombo($sql, $id_jo, 'Pilih Job Order #');
								?>
							</select>
						</div>
			</div>
			<div class='col-md-7'>
						<div class='form-group'>
						</div>
			</div>

              <div class='col-md-6'>
                <div class='form-group'>
                  <label>WS Child :</label>
                  <select class='form-control select2' style='width: 100%;' name='cbo_jo_child' id='cbo_jo_child' required>
                    <?php
								$sql = "select jo.id isi,concat(jo.jo_no,' - ',supplier,' - ',ac.styleno,' - ',ac.kpno) tampil from 
									jo inner join jo_det jod on jo.id=jod.id_jo
									inner join so on jod.id_so=so.id
									inner join act_costing ac on so.id_cost=ac.id 
									inner join mastersupplier ms on ac.id_buyer=ms.id_supplier 
									left join (select id_jo from bom_jo_global_item group by id_jo) bji on jo.id=bji.id_jo  
									group by jo.id ";
								IsiCombo($sql, '', 'Pilih Job Order #');
                    ?>
                  </select>
                </div>
              </div>

        <div class='col-md-3'>
        <div class='form-group' style="padding:24px">
        <button type='submit' name='submit' class='btn btn-primary'>Tambah</button>
        </div>
        </div> 

			<div class="box-body">
				<table id="example_bom_global_sum" class="display responsive" style="width:100%;font-size:13px;">
					<thead>
						<tr>
							<th>No</th>
							<th>WS Global</th>
							<th>WS Anak</th>
							<th>Tgl Input</th>
							<th>Status</th>
							<th>Act</th>
						</tr>
					</thead>
					<tbody>
						<?php
						# QUERY TABLE
						$sql = "
select a.id, a.id_jo_global,ac.kpno ws_global, ac_ch.kpno ws_anak, a.tgl_input, a.cancel from bom_jo_global_child a  
inner join jo_det jd on a.id_jo_global = jd.id_jo
inner join so on jd.id_so = so.id
inner join act_costing ac on so.id_cost = ac.id
inner join jo_det jd_ch on a.id_jo_child = jd_ch.id_jo
inner join so so_ch on jd_ch.id_so = so_ch.id
inner join act_costing ac_ch on so_ch.id_cost = ac_ch.id
where id_jo_global = '$id_jo'";
						$query = mysql_query($sql);
						#echo $sql;
						$no = 1;	
						while ($data = mysql_fetch_array($query)) {
						if ($data['cancel'] == "Y") {
								$bgcol = "style='color:red;'";
								$status = 'Cancel';
							} 
						else  {
								$bgcol = "";
								$status = "";
							}							
							echo "
          <tr $bgcol>
		  <td>$no</td>
		  <td>$data[ws_global]</td>
		  <td>$data[ws_anak]</td>
		  <td>" . fd_view($data['tgl_input']) . "</td>
		  <td>$status</td>
          <td>
            <a href='s_bom_jo_global.php?mod=cancel_item&id=$data[id]&id_jo=$data[id_jo_global]'<i class='fa fa-trash'></a>          
          </td>		  ";
		  echo "</tr>";
		  $no++; // menambah nilai nomor urut
						}
						?>
					</tbody>		
				</table>
			</div>
		</div>
	</form>
	<?php } ?>