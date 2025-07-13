<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("brosur","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_cs = $_GET['id']; } else {$id_cs = "";}
if (isset($_GET['idcd'])) {$id_cd = $_GET['idcd']; } else {$id_cd = "";}
if (isset($_GET['idmf'])) {$id_mf = $_GET['idmf']; } else {$id_mf = "";}
if (isset($_GET['idot'])) {$id_ot = $_GET['idot']; } else {$id_ot = "";}
if (isset($_GET['pro'])) {$pro = $_GET['pro']; } else {$pro = "";}
$titlenya="Act-Costing";
$mode="";
$mod=$_GET['mod'];
$id_item=$id_cs;
$st_num="style='text-align: right;'";
# COPAS EDIT
if ($id_item=="")
{	$id_season = "";
	$id_product = "";
	$itemname = "";
	$styleno = "";
	$styledesc = "";
	$color = "";
	$colorname = "";
	$nm_file = "";
}
else
{	$query = mysql_query("SELECT * FROM brosur where id='$id_item' ORDER BY id ASC");
	$data = mysql_fetch_array($query);
	$id_season = $data['id_season'];
	$id_product = $data['id_product'];
	$itemname = $data['itemname'];
	$styleno = $data['styleno'];
	$styledesc = $data['styledesc'];
	$color = $data['color'];
	$colorname = $data['color_name'];
	$nm_file = $data['nm_file'];
	$prod_gr=flookup("product_group","masterproduct","id='$id_product'");
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>
	function validasi()
	{	var id_season = document.form.txtid_season.value;
		var id_product = document.form.txtid_product.value;
		var itemname = document.form.txtitemname.value;
		var styleno = document.form.txtstyleno.value;
		var styledesc = document.form.txtstyledesc.value;
		var color = document.form.txtcolor.value;
		if (id_season == '') { document.form.txtid_season.focus(); swal({ title: 'Season Tidak Boleh Kosong', $img_alert }); valid = false;}
		else if (id_product == '') { document.form.txtid_product.focus(); swal({ title: 'Product Item Tidak Boleh Kosong', $img_alert }); valid = false;}
		else if (itemname == '') { document.form.txtitemname.focus(); swal({ title: 'Product Name Tidak Boleh Kosong', $img_alert }); valid = false;}
		else if (styleno == '') { document.form.txtstyleno.focus(); swal({ title: 'Style # Tidak Boleh Kosong', $img_alert }); valid = false;}
		else if (styledesc == '') { document.form.txtstyledesc.focus(); swal({ title: 'Style Description Tidak Boleh Kosong', $img_alert }); valid = false;}
		else if (color == '') { document.form.txtcolor.focus(); swal({ title: 'Color Tidak Boleh Kosong', $img_alert }); valid = false;}
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
</script>
<div class='box'>
	<div class='box-body'>
		<div class='row'>
			<form method='post' name='form' enctype='multipart/form-data' action="s_brosur.php?mod=<?php echo $mod; ?>&id=<?php echo $id_item; ?>" onsubmit='return validasi()'>
				<div class='col-md-3'>								
					<?php if ($id_cs=="") { ?>
					<img id="output" width="270px" height="400pxpx">	
					<?php } else { $nm_filex="upload_files/brosur/".$nm_file; ?>
					<img src="<?php echo $nm_filex; ?>" id="output" width="270px" height="400px">	
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
						<label>Season *</label>
						<select class='form-control select2' style='width: 100%;' name='txtid_season'>
							<?php 
								$sql = "select id_season isi,season tampil from 
			          	masterseason";
								IsiCombo($sql,$id_season,'Pilih Season');
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
            <?php if ($id_item=="") { ?>
            <select class='form-control select2' style='width: 100%;' 
            	id='cbopr_it' name='txtid_product'>
            </select>
            <?php } else { ?>
            <select class='form-control select2' style='width: 100%;' 
            	id='cbopr_it' name='txtid_product'>
            	<?php
            	$sql = "select id isi,product_item tampil 
								from masterproduct where product_group='$prod_gr'";
							IsiCombo($sql,$id_product,'Pilih Product Item');
							?>
            </select>
            <?php } ?>
					</div>				
					<div class='form-group'>
						<label>Product Name *</label>
						<input type='text' class='form-control' name='txtitemname' placeholder='Masukkan Product Name' value='<?php echo $itemname;?>' >
					</div>
				</div>
				<div class='col-md-3'>				
					<div class='form-group'>
						<label>Style # *</label>
						<input type='text' class='form-control' name='txtstyleno' placeholder='Masukkan Style #' value='<?php echo $styleno;?>' >
					</div>				
					<div class='form-group'>
						<label>Style Description *</label>
						<input type='text' class='form-control' name='txtstyledesc' placeholder='Masukkan Style Description' value='<?php echo $styledesc;?>' >
					</div>				
					<div class='form-group'>
						<label>Color *</label>
						<?php if ($color=="") {$bgcol=" placeholder='Pilih Color' ";} else {$bgcol=" style='background-color:$color;' ";} ?>
						<input type='text' <?php echo $bgcol; ?> class='form-control my-colorpicker1' name='txtcolor'>
					</div>
					<div class='form-group'>
						<label>Color Name *</label>
						<input type='text' class='form-control' name='txtcolorname' placeholder='Masukkan Color Name' value='<?php echo $colorname;?>' >
					</div>
				</div>				
				<div class='col-md-3'>
					<div class='form-group'>
						<label>Attach File *</label>
      			<input type="file" name='txtattach_file' accept="image/*" onchange="loadFile(event)">
					</div>
					<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
				</div>
			</form>
		</div>
	</div>
</div><?php 
# END COPAS ADD REMOVE </DIV> TERAKHIR
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">List Brochure</h3>
  </div>
  <div class="box-body">
  	<table id="examplefix4" class="display responsive" style="width:100%;font-size:12px;">
      <thead>
      <tr>
	    	<th>Season</th>
				<th>Product Group</th>
				<th>Product Name</th>
				<th>Style #</th>
				<th>Style Description</th>
				<th>Color</th>
				<th>Action</th>
			</tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		    $sql="select b.id_season,b.id_product,b.id,ms.season,mp.product_group,b.itemname,b.styleno,b.styledesc,
		    	b.color from brosur b inner join masterseason ms on b.id_season=ms.id_season
		    	inner join masterproduct mp on b.id_product=mp.id";
				$result=mysql_query($sql);
				while($rs = mysql_fetch_array($result))
			  {	echo "
					<tr>
						<td>$rs[season]</td>
						<td>$rs[product_group]</td>
						<td>$rs[itemname]</td>
						<td>$rs[styleno]</td>
						<td>$rs[styledesc]</td>
						<td>$rs[color]</td>
						<td>
							<a class='btn btn-info btn-s' href='?mod=$mod&id=$rs[id]'
	              data-toggle='tooltip' title='Edit'><i class='fa fa-pencil'></i></a>
	          	<a class='btn btn-info btn-s' href='pdfBrosur.php?season_id=$rs[id_season]&product_id=$rs[id_product]'
              	data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i></a>
	          </td>
					</tr>";
				}
			  ?>
      </tbody>
    </table>
  </div>
</div>