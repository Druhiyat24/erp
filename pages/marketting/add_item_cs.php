<?php 
	include '../../include/conn.php';
  include '../forms/fungsi.php';
  $id_cs=$_POST['id'];
  if ($id_cs!="")
  {	$arrid_cs=explode("&",$id_cs);
  	$id_cs_mat=$arrid_cs[2];
  	$mod=$arrid_cs[1];
	}
	if ($id_cs_mat=="")
  {	$id_item = "";
		$price = "0";
		$cons = "0";
		$unit = "";
		$allowance = "0";
		$material_source = "";
	}
	else
	{	$rs=mysql_fetch_array(mysql_query("select * from act_costing_mat where
			id_act_cost='$id_cs' and id='$id_cs_mat' "));
		$id_item = $rs['id_item'];
		$price = $rs['price'];
		$cons = $rs['cons'];
		$unit = $rs['unit'];
		$allowance = $rs['allowance'];
		$material_source = $rs['material_source'];
	}
?>
<head>
	<link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../plugins/select2/select2.min.css">
  <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
</head>
<!--COPAS VALIDASI-->
<?php 
echo "<script type='text/javascript'>
	function validasi()
	{	var id_item = document.form.txtid_item.value;
		var price = document.form.txtprice.value;
		var cons = document.form.txtcons.value;
		var unit = document.form.txtunit.value;
		var allowance = document.form.txtallowance.value;
		var material_source = document.form.txtmaterial_source.value;
		if (id_item == '') { document.form.txtid_item.focus(); alert('asas'); valid = false;}
		else if (price == '0') { document.form.txtprice.focus(); alert('asas'); valid = false;}
		else valid = true;
		return valid;
		exit;
	}
</script>";
?>
<!--END COPAS VALIDASI-->
<!--COPAS ADD-->
<div class='box'>
	<div class='box-body'>
		<div class='row'>
			<form method='post' name='form' action='s_add_item_cs.php?mod=<?php echo $mod; ?>&id=<?php echo $id_cs; ?>&idd=<?php echo $id_cs_mat; ?>' onsubmit='return validasi()'>
				<div class='col-md-6'>								
					<div class='form-group'>
						<label>Item Code *</label>
						<select class='form-control select2' style='width: 100%;' name='txtid_item'>
							<?php 
								#$sql = "SELECT j.id isi,
		            #  concat(a.nama_group,' ',s.nama_sub_group,' ',
		            #  d.nama_type,' ',e.nama_contents,' ',f.nama_width,' ',
		            #  g.nama_length,' ',h.nama_weight,' ',i.nama_color,' ',j.nama_desc) tampil
		            #  FROM mastergroup a inner join mastersubgroup s on a.id=s.id_group
		            #  inner join mastertype2 d on s.id=d.id_sub_group
		            #  inner join mastercontents e on d.id=e.id_type
		            #  inner join masterwidth f on e.id=f.id_contents 
		            #  inner join masterlength g on f.id=g.id_width
		            #  inner join masterweight h on g.id=h.id_length
		            #  inner join mastercolor i on h.id=i.id_weight
		            #  inner join masterdesc j on i.id=j.id_color
		            #  ORDER BY j.id DESC";
								$sql = "select e.id isi,concat(nama_group,' ',nama_sub_group,' ',nama_type,' ',nama_contents) 
		              tampil from mastergroup a inner join mastersubgroup s on 
		              a.id=s.id_group 
		              inner join mastertype2 d on s.id=d.id_sub_group
		              inner join mastercontents e on d.id=e.id_type";
								IsiCombo($sql,$id_item,'Pilih Item Contents');
							?>
						</select>
					</div>				
					<div class='form-group'>
						<label>Price *</label>
						<input type='text' class='form-control' name='txtprice' placeholder='Masukkan Price' value='<?php echo $price;?>' >
					</div>				
					<div class='form-group'>
						<label>Cons *</label>
						<input type='text' class='form-control' name='txtcons' placeholder='Masukkan Cons' value='<?php echo $cons;?>' >
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
				</div>
				<div class='col-md-6'>				
					<div class='form-group'>
						<label>Allowance *</label>
						<input type='text' class='form-control' name='txtallowance' placeholder='Masukkan Allowance' value='<?php echo $allowance;?>' >
					</div>					
					<div class='form-group'>
						<label>Material Source *</label>
						<select class='form-control select2' style='width: 100%;' name='txtmaterial_source'>
							<?php 
								$sql = "select nama_pilihan isi,nama_pilihan tampil from 
									masterpilihan where kode_pilihan='Mat_Sour'";
								IsiCombo($sql,$material_source,'Pilih Material Source');
							?>
						</select>
					</div>
					<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!--END COPAS ADD-->
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="../../bootstrap/js/bootstrap.min.js"></script>
<script src="../../plugins/select2/select2.full.min.js"></script>
<script>
  $(function () {
    //Initialize Select2 Elements
    $(".xselect2").select2();
  });
</script>