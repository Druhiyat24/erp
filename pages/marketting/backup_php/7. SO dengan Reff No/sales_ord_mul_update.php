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
	$unit = "";
	$curr = "";
	$fob = "";
	$tax = "";
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
	$unit = $data['unit'];
	$curr = $data['curr'];
	$fob = $data['fob'];
	$tax = $data['tax'];
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
  { var actcostid = document.form.txtid_cost.value;
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
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
</script>
<!--COPAS ADD-->
<?php if ($mod=="7") { ?>
<?php } ?>
<!--END COPAS ADD-->
<?php if ($id_so!="") { ?>
<form method='post' action='upd_so_mul.php?mod=7&id=<?php echo $id_so;?>'>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Sales Order Detail</h3>
  	<button type='submit' name='submit' class='btn btn-primary btn-s'>
			<i class='fa fa-pencil'></i>
			Update Data
		</button>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>
	    	<th>No</th>
	    	<th>Deliv. Date</th>
        <th>Destination</th>
				<th>Color</th>
				<th>Size</th>
				<th>Ref / Style No</th>
				<th>Qty</th>
				<th>Qty Add</th>
				<th>Unit</th>
				<th>Price</th>
				<th>SKU</th>
				<th>Barcode</th>
				<th>Notes</th>
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
			  { $idcri=$data['id'];
			  	echo "
			  	<tr>
			  		<td>$no</td>
				    <td>
				    	<input type='text' class='form-control deldatecl' size='5' name='deldatear[$idcri]' 
				    		value='$data[deldate_det]'>
				    </td>
				    <td>
				    	<input type='text' class='form-control destcl' size='5' name ='destar[$idcri]' 
				    		value='$data[dest]'>
				    </td>
				    <td>
				    	<input type='text' class='form-control colorcl' size='5' name ='colorar[$idcri]' 
				    		value='$data[color]'>
				    </td>
				    <td>
				    	<input type='text' class='form-control sizecl' size='4' name ='sizear[$idcri]' 
				    		value='$data[size]'>
				    </td>
				    <td>
				    	<input type='text' class='form-control sizecl' size='4' name ='reff_no[$idcri]' 
				    		value='$data[reff_no]'>
				    </td>					
				    <td>
				    	<input type='text' class='form-control qtycl' size='4' name='qtyar[$idcri]' 
				    		value='$data[qty]'>
				    </td>
				    <td>
				    	<input type='text' class='form-control qtyaddcl' size='4' name='qtyaddar[$idcri]' 
				    		value='$data[qty_add]'>
				    </td>
				    <td>
				    	<input type='text' class='form-control unitcl' size='4' name='unitar[$idcri]' 
				    		value='$data[unit]'>
				    </td>
				    <td>
				    	<input type='text' class='form-control pricecl' size='4' name='pricear[$idcri]' 
				    		value='$data[px_det]'>
				    </td>
				    <td>
				    	<input type='text' class='form-control skucl' size='4' name='skuar[$idcri]' 
				    		value='$data[sku]'>
				    </td>
				    <td>
				    	<input type='text' class='form-control barcodecl' size='4' name='barcodear[$idcri]' 
				    		value='$data[barcode]'>
				    </td>
				    <td>
				    	<input type='text' class='form-control notescl' size='4' name='notesar[$idcri]' 
				    		value='$data[notes]'>
				    </td>
				  </tr>";
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
  <div class="box-body">
    <table id="examplefix3" class="display responsive" style="width:100%;font-size:10px;">
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
        <th>Delv Date</th>
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
        $query = mysql_query("select a.id_cost,cancel_h,a.username,a.id,so_no,buyerno,kpno,cost_no,
          supplier,product_group,product_item,styleno,a.qty,a.unit,
          deldate,fullname,a.so_date from so a inner join act_costing s on 
          a.id_cost=s.id inner join mastersupplier g on 
          s.id_buyer=g.id_supplier inner join masterproduct h 
          on s.id_product=h.id left join jo_det j on a.id=j.id_so and 'N'=j.cancel 
          inner join userpassword up on a.username=up.username 
          where so_type='B' order by a.so_date desc"); 
        if (!$query) {die(mysql_error());}
        $no = 1; 
        while($data = mysql_fetch_array($query))
        { $id=$data['id'];
          echo "<tr>";
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
            echo "<td>".fd_view($data['deldate'])."</td>";
            echo "<td>$data[fullname]</td>";
            echo "<td>".fd_view($data['so_date'])."</td>";
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
            echo "<td>";
           		echo "<a href='?mod=7&id=$data[id]&pro=Copy'
              	data-toggle='tooltip' title='Copy SO'><i class='fa fa-copy'></i></a>";
            echo "</td>";
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