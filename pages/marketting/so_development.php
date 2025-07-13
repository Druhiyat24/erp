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
$user_so=flookup("username","so_dev","id='$id_so'");
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
{	$query = mysql_query("SELECT * FROM so_dev where id='$id_so' ");
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
      url: "ajax_sales_ord_dev.php?mdajax=get_act_cost",
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
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="js/SalesOrderDevelopment.Controller.js?v=<?php date('Ymdhms')?>"></script>
<!--COPAS ADD-->
<?php if ($mod=="12SO") { ?>

<div class='box'>
	<div class='box-body'>
		<div class='row'>
			<form method='post' name='form' enctype='multipart/form-data' action='s_sales_order_development.php?mod=<?php echo $mod; ?>&id=<?php echo $id_so; ?>&pro=<?php echo $pro; ?>' onsubmit='return validasi()'>
				<div class='col-md-3'>								
					<div class='form-group'>
						<label>Sample Development # *</label>
						<select class='form-control select2' style='width: 100%;' name='txtid_cost' onchange='getActCost(this.value)'>
							<?php
								if ($id_so=="") {$sql_whe="a.status!='' and app1='A' and s.id is null ";} else {$sql_whe="a.id='$id_cost'";}
								$sql = "select a.id isi,concat(a.cost_no,'|',ms.supplier,'|',a.styleno) tampil from 
									act_development a inner join mastersupplier ms on a.id_buyer=ms.id_supplier  
									left join so_dev s on a.id=s.id_cost and 'N'=s.cancel_h  where $sql_whe
									";
								IsiCombo($sql,$id_cost,'Pilih Sample #');
							?>
						</select>
						<?php // echo $sql; ?>
					</div>				
					<div class='form-group'>
						<label><?php echo $cprodgr; ?></label>
						<input type='text' class='form-control' name='txtprodgroup' id='txtprodgroup' value='<?php echo $txtprodgroup; ?>' placeholder='Masukkan <?php echo $cprodgr; ?>' readonly >
					</div>				
					<div class='form-group'>
						<label><?php echo $cprodit; ?></label>
						<input type='text' class='form-control' name='txtproditem' id='txtproditem' value='<?php echo $txtproditem; ?>' placeholder='Masukkan <?php echo $cprodit; ?>' readonly >
					</div>				
					<div class='form-group'>
						<label><?php echo $cstyle; ?></label>
						<input type='text' class='form-control' name='txtstyleno' id='txtstyleno' value='<?php echo $txtstyleno; ?>' placeholder='Masukkan <?php echo $cstyle; ?>' readonly >
					</div>				
					<div class='form-group'>
						<label>WS Number</label>
						<input type='text' class='form-control' id='txtwsno' value='<?php echo $txtwsno; ?>' placeholder='Masukkan WS Number' readonly >
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
					<div class='form-group'>
						<label>SO # *</label>
						<input type='text' readonly class='form-control' name='txtso_no' placeholder='Masukkan SO #' value='<?php echo $so_no;?>' >
					</div>				
					<div class='form-group'>
						<label>SO Date *</label>
						<input type='text' readonly class='form-control' name='txtso_date' placeholder='Masukkan SO Date' value='<?php echo $so_date;?>' >
					</div>
				</div>
				<div class='col-md-3'>				
					<div class='form-group'>
			      <label>Season *</label>
			      <select class='form-control select2' style='width: 100%;' name='txtseason'>
			       <?php 
			        $sql = "select id_season isi,season tampil from 
			          masterseason";
			        IsiCombo($sql,$season,'Pilih Season');
			       ?>
			      </select>
			    </div>
					<div class='form-group'>
						<label>Qty *</label>
						<input type='text' class='form-control' name='txtqty' placeholder='Masukkan Qty' value='<?php echo $qty;?>' >
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
						<label>Currency</label>
						<select class='form-control select2' style='width: 100%;' name='txtcurr'>
							<?php 
								$sql = "select nama_pilihan isi,nama_pilihan tampil from 
									masterpilihan where kode_pilihan='Curr'";
								IsiCombo($sql,$curr,'Pilih Currency');
							?>
						</select>
					</div>				
					<div class='form-group'>
						<label>FOB *</label>
						<input type='text' class='form-control' name='txtfob' placeholder='Masukkan FOB' value='<?php echo $fob;?>' >
					</div>
				</div>
				<div class='col-md-3'>
					<div class='form-group'>
						<label>Tax (%)</label>
						<input type='text' class='form-control' name='txttax' placeholder='Masukkan Tax' value='<?php echo $tax;?>' >
					</div>
					<div class='form-group'>
            <label for='exampleInputFile'>Attach File</label>
            <input type='file' name='txtfile'>
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

<form method='post' action='d_so_mul.php?mod=12SO&id=<?php echo $id_so;?>'>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Sales Order Development</h3>
  	<?php if ($mod=="8d") { ?>
  		<button type='submit' name='submit' class='btn btn-danger btn-s'>
  			<i class='fa fa-trash'></i>
  			Cancel Selected
  		</button>
  	<?php } else if ($user_so==$user) { ?>
	
	  &nbsp;&nbsp;	<a href='#' onclick="formss('add')" class='btn btn-primary btn-s'>
	  		<i class='fa fa-plus'></i> Add Item
	  	</a> <a href='../marketting/?mod=8d&id=<?php echo $id_so; ?>' class='btn btn-danger btn-s'>
	  		<i class='fa fa-trash'></i> Multi Cancel
	  	</a>
	<?php } ?>
  </div>
  
  

  <div class="box-body" style="overflow:scroll;height:400px">
    <table id="example1"  style="width:100%">
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
				<th>Qty</th>
				<th>Qty Add</th>
				<th>Unit</th>
				<th>Notes</th>
				<th align="center" colspan="2">Action</th>
				<?php if ($mod!="8d") { ?>
				
					<th></th>
				<?php } ?>
			</tr>
      </thead>
      <tbody style="overflow:none">
        <?php
        # QUERY TABLE
		    $query = mysql_query("SELECT sod.*,so.username,sod.price px_det from so_det_dev sod inner join so_dev so on sod.id_so=so.id 
		    	where sod.id_so='$id_so'"); 
        $no = 1;
        $tot_qty=0; 
				while($data = mysql_fetch_array($query))
			  { echo "<tr>";
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
				    <td>".fn($data['qty'],0)."</td>
				    <td>".fn($data['qty_add'],0)."</td>
				    <td>$data[unit]</td>
				    <td>$data[notes]</td>";
						if($data['cancel']=="Y")
						{echo "<td>Cancelled</td>";}
						else
						{echo "<td></td>";}
						if ($mod!="8d")
						{	if ($data['username']==$user and $data['cancel']=="N")
							{	echo "
								<td>
									
									<a href='#' onclick='formss(\"edit\",\"$id_so\",\"$data[id]\")'
		              	data-toggle='tooltip' title='Ubah'><i class='fa fa-pencil'></i></a>
	              ";
					/*	?>
						<a href="d_so_development.php?mod=$mod&id=$id_so&idd=$data[id]&mod=$mod"
			            	onclick="return confirm('Apakah anda yakin akan dicancel ?')">
			            	&nbsp;&nbsp;<?php echo $tt_hapus2;?></a>
			          </td><?php*/
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
	 </form> 
	  <?php if($mod != '8d'){ ?>
	    <tfoot>
			<tr>
			<th style="padding-left:0"> <div id="form_numberofsize" style="width:100px"></div>  </th>
				<th style="padding-left:0"> <div id="form_deliverydate"></div> </th> 
				<th style="padding-left:0"> <div id="form_destination"></div> </th> 
				<th style="padding-left:0"> <div id="form_color"></div> </th> 
				<th style="padding-left:0"> <div id="form_size"></div> </th> 
				<th style="padding-left:0"> <div id="form_qty"></div></th>
				<th style="padding-left:0"> <!--<div id="form_qtyadd"></div>--> &nbsp;</th>
				<th style="padding-left:0"> <div id="form_unit"></div> </th>
				<th style="padding-left:0"> <div id="form_notes"></div> </th>
				<th style="padding-left:0"> <!--<div id="form_status"> </div>--> &nbsp;</th>
				<th style="padding-left:0"> <div id="form_action"></div>  </th>
				<th style="padding-left:0"> &nbsp;</th>
				
			</tr>
		</tfoot>
		 <tfoot id='render'>
		 </tfoot>
		 
	  <?php } ?>
    </table>
	
  	<table id="addNumberOfSize" style="width:100%">
      <thead>
        <tr>
			<th>No</th>
			<th>Deliv date</th>
            <th>Destination</th>   
            <th>Color</th>
			<th>Size</th>
			<th>Qty</th>
            <th>Add</th>   


		</tr>
      </thead>


    </table>	
	
    Total : <?php echo fn($tot_qty,0); ?> 
  


  
  </div>

</div>

<?php } else if ($mod=="12vSO") { ?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">List Sales Order Development</h3>
  	<a href='../marketting/?mod=12SO' class='btn btn-primary btn-s'>
  		<i class='fa fa-plus'></i> New
  	</a>
  </div>
 
  <div class="box-body">
    <table id="examplefix3" class="display responsive" style="width:100%;font-size:10px;">
      <thead>
      <tr>
        <th>SO Development #</th>
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
		<!--<th></th>-->
        <th></th>
        <th></th>
      </tr>
	  
      </thead>
      <tbody>
	  
        <?php
		//require_once 's_sales_ord_det.php';
        # QUERY TABLE
        $query = mysql_query("select a.id_cost,cancel_h,a.username,a.id,so_no,buyerno,kpno,cost_no,
          supplier,product_group,product_item,styleno,a.qty,a.unit,
          deldate,fullname,a.so_date from so_dev a inner join act_development s on 
          a.id_cost=s.id inner join mastersupplier g on 
          s.id_buyer=g.id_supplier inner join masterproduct h 
          on s.id_product=h.id left join jo_det j on a.id=j.id_so and 'N'=j.cancel 
          inner join userpassword up on a.username=up.username order by a.so_date desc"); 
        if (!$query) {die(mysql_error());}
        $no = 1; 
        while($data = mysql_fetch_array($query))
        { $id=$data['id'];

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
            	{	echo "<a href='?mod=12SO&id=$data[id]'
              	$tt_ubah</a> "; 
              }
            echo "</td>";
            echo "<td>"; 
            	echo "<a href='pdfSODevelopment.php?id=$data[id_cost]'
              	data-toggle='tooltip' title='Preview' target='_blank'><i class='fa fa-print'></i></a>";
            echo "</td>";
            /*echo "
            <td>
            	<a href='pdfSODevelopment.php?id=$data[id_cost]'
      					data-toggle='tooltip' title='Preview Costing' target='_blank'><i class='fa fa-calculator'></i>
    					</a>
  					</td>";*/
            echo "<td>";
           		echo "<a href='?mod=12SO&id=$data[id]&pro=Copy'
              	data-toggle='tooltip' title='Copy SO'><i class='fa fa-copy'></i></a>";
            echo "</td>";
            echo "<td>";
            	if ($data['username']==$user)
            	{	echo "
	              <a href='d_so_development.php?mod=$mod&id=$data[id]'
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