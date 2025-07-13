<?php 
	include '../../include/conn.php';
  include '../forms/fungsi.php';
  $cub2="View";
  $cl_ubah=" ";
  $tt_ubah="data-toggle='tooltip' title='$cub2'><i class='fa fa-eye'></i>";
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
			<table width="100%">
				<tr>
					<thead>
						<th>Costing #</th>
						<th>Costing Date</th>
						<th>Buyer</th>
						<th>Style #</th>
						<th>Delv. Date</th>
						<th>Action</th>
					</thead>
				</tr>
			<tbody>
				<?php 
				$sql="select a.id,cost_no,cost_date,supplier,styleno,deldate from act_costing a inner join pre_costing s on a.id_pre_cost=s.id inner join quote_inq d on s.id_inq=d.id 
					inner join mastersupplier f on d.id_buyer=f.id_supplier";
				$result=mysql_query($sql);
				while($rs = mysql_fetch_array($result))
			  {	echo "
					<tr>
						<td>$rs[cost_no]</td>
						<td>".fd_view($rs['cost_date'])."</td>
						<td>$rs[supplier]</td>
						<td>$rs[styleno]</td>
						<td>".fd_view($rs['deldate'])."</td>
						<td><a $cl_ubah href='?mod=5&id=$rs[id]'
                $tt_ubah</a></td>
					</tr>";

			  }
				?>
			</tbody>
			</table>
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