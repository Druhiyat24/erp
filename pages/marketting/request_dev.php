<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("sales_order","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI

$mod2 = "";

if (isset($_GET['id'])) {$id_so = $_GET['id']; } else {$id_so = "";}
if (isset($_GET['mod2'])) {$mod2 = $_GET['mod2']; } else {$mod2 = "";}
$user_so=flookup("id","request_sample_dev","id='$id_so'");
$mode="";
$mod=$_GET['mod'];


	$query = mysql_query("SELECT * FROM request_sample_dev where id='$id_so' ");
	$data = mysql_fetch_array($query);
	
	$txtkpno=$data['no_req'];
	$txtdate=$data['req_date'];
	$order=$data['xorder'];
	$buyer=$data['id_buyer'];
	$xstyle=$data['id_item'];
	$txtperpatdate=$data['date_perselpat'];
	$txtpersamdate=$data['date_perselsam'];
	$txtselpatdate=$data['date_selpat'];
	$txtselsamdate=$data['date_selsam'];
	$txtcekpatdate=$data['date_cekpat'];
	$txtselcekpatdate=$data['date_selcekpat'];
	
	$id_lampiran=$data['id_lampiran'];
	$id_lampiran = explode("|" , $id_lampiran);
	$lampiran=$id_lampiran[0];
	
	$id_acc=$data['id_acc'];
	$id_acc = explode("|" , $id_acc);
	$xacc=$id_acc[0];
	
	$id_jenis=$data['id_jenis'];
	$id_jenis = explode("|" , $id_jenis);
	$xjenis=$id_jenis[0];

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
<script src="js/reqDevelopment.Controller.js?v=<?php date('Ymdhms')?>"></script>
<!--COPAS ADD-->
<?php if ($mod=="request1") { ?>

<div class='box'>
	<div class='box-body'>
		<div class='row'>
			<form method='post' name='form' enctype='multipart/form-data' action='s_req_dev.php?mod=<?php echo $mod; ?>&id=<?php echo $id_so; ?>' onsubmit='return validasi()'>
				<div class='col-md-3'>								
					<div class='form-group'>
						<label>Number Sample#</label>
						<input type='text' class='form-control' readonly name='txtkpno' placeholder='Number Request #' value='<?php echo $txtkpno; ?>' >
					</div>	
					<div class='form-group'>
						<label>Date *</label>
						<input type='text' class='form-control' id='datepicker1' name='txtdate' placeholder='Masukkan Delivery Date' value='<?php echo $txtdate; ?>' >
					</div>
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
						<label>Style #</label>
						<input type='text' class='form-control' 
							name='txtid_style' value='<?php echo $xstyle; ?>' placeholder='Masukkan Style #' >
					</div>
							
					<div class='form-group'>
						<label>Order #</label>
						<input type='text' class='form-control' 
							name='order' value='<?php echo $order; ?>' placeholder='Masukkan Order #' >
					</div>		
					<?php
					if ($mod2=="") {
						
					?>
					 <div class='form-group'>
					<label>Jenis Permintaan *</label>
					<select class='form-control select2' multiple='multiple' style='width: 100%;' 
					  name='txtkode_jenis[]' id='cboDept' onchange='getReqList()'>
					  <?php
					  $sql="select kode_jenis_permintaan isi,jenis_permintaan tampil from jenis_permintaan
						where kode_jenis_permintaan!='' group by kode_jenis_permintaan order by kode_jenis_permintaan";
					  IsiCombo($sql,$xjenis,'')
					  ?>
					</select>
					<input type="hidden" name='txtJItem' value='N'>
				  </div>
					<?php
					}else
					{
						echo"";
					}
					?>
					
				</div>
				<div class='col-md-3'>				
					<div class='form-group'>
						<label>Date Permintaan Selesai Patrun*</label>
						<input type='text' class='form-control' id='datepicker2' name='txtperpatdate' placeholder='Masukkan Delivery Date' value='<?php echo $txtperpatdate; ?>' >
					</div>
					<div class='form-group'>
						<label>Date Permintaan Selesai Sample*</label>
						<input type='text' class='form-control' id='datepicker3' name='txtpersamdate' placeholder='Masukkan Delivery Date' value='<?php echo $txtpersamdate; ?>' >
					</div>
					<div class='form-group'>
						<label>Date Selesai Patrun*</label>
						<input type='text' class='form-control' id='datepicker4' name='txtselpatdate' placeholder='Masukkan Delivery Date' value='<?php echo $txtselpatdate; ?>' >
					</div>
					<div class='form-group'>
						<label>Date Selesai Sample*</label>
						<input type='text' class='form-control' id='datepicker5' name='txtselsamdate' placeholder='Masukkan Delivery Date' value='<?php echo $txtselsamdate; ?>' >
					</div>
					<div class='form-group'>
						<label>Date Cek Patrun*</label>
						<input type='text' class='form-control' id='datepicker6' name='txtcekpatdate' placeholder='Masukkan Delivery Date' value='<?php echo $txtcekpatdate; ?>' >
					</div>
					<div class='form-group'>
						<label>Date Selesai Cek Patrun*</label>
						<input type='text' class='form-control' id='datepicker7' name='txtselcekpatdate' placeholder='Masukkan Delivery Date' value='<?php echo $txtselcekpatdate; ?>' >
					</div>
				</div>
				<div class='col-md-3'>	
				<?php
					if ($mod2=="") {
						
					?>
				 <div class='form-group'>
					<label>Lampiran *</label>
					
					<select class='form-control select2' multiple='multiple' style='width: 100%;' 
					  name='txtkode_lampiran[]' id='cboDept' onchange='getReqList()'>
					  <?php
							
				
							  $sql="select kode_lampiran isi,lampiran tampil from lampiran_dev
								where kode_lampiran!='' group by kode_lampiran order by kode_lampiran";
							  IsiCombo($sql,$lampiran,'')
					  ?>
					</select>
					<input type="hidden" name='txtJItem' value='N'>
				  </div>		
				   <div class='form-group'>
					<label>Accessories *</label>
					<select class='form-control select2' multiple='multiple' style='width: 100%;' 
					  name='txtkode_acc[]' id='cboDept' onchange='getReqList()'>
					  <?php
					  $sql="select kode_acc isi,accessories tampil from accessories_dev
						where kode_acc!='' group by kode_acc order by kode_acc";
					  IsiCombo($sql,$xacc,'')
					  ?>
					</select>
				  </div>	
					<?php
					}else
					{
						echo"";
					}
					?>				  
					<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
				
				</div>
				<div class='col-md-3'>
					
					
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
  	<?php } else if ($user_so!=="") { ?>
	
	  &nbsp;&nbsp;	<a href='#' onclick="formss('add')" class='btn btn-primary btn-s'>
	  		<i class='fa fa-plus'></i> Add Item
	  	</a> 
	<?php } ?>
	
  </div>
  
  

  <div class="box-body" style="overflow:scroll;height:400px">
    <table id="example1"  style="width:100%">
      <thead>
      <tr>
        <th>Jenis Kain</th>
       		<th>Color</th>
				<th>Size</th>
				<th>Qty</th>
				<th>Action</th>
	
			</tr>
      </thead>
      <tbody style="overflow:none">
        <?php
        # QUERY TABLE
		    $query = mysql_query("SELECT sod.*,
			so.no_req,
			so.req_date,
			so.id_buyer,
			so.id_item,
			so.xorder,
			so.id_jenis,
			so.id_lampiran,
			so.id_acc,
			so.username,
			so.cancel from request_det_dev sod inner join request_sample_dev so on sod.id_req=so.id 
		    	where sod.id_req='$id_so' and sod.cancel='N'"); 
        $no = 1;
        $tot_qty=0; 
				while($data = mysql_fetch_array($query))
			  { echo "<tr>";
				    
				    echo "
				    
				    <td>$data[kain]</td>
				    <td>$data[color]</td>
				    <td>$data[size]</td>
				    <td>".fn($data['qty'],0)."</td>
					<td>
				   ";
					/*echo "
								<td>
									
									<a href='#' onclick='formss(\"edit\",\"$id_so\",\"$data[id]\")'
		              	data-toggle='tooltip' title='Ubah'><i class='fa fa-pencil'></i></a>
	              ";*/
					?>
					<a href="d_dev_req.php?mod=request1&id=<?php echo $id_so;?>&idd=<?php echo $data['id'];?>"
			            	onclick="return confirm('Apakah anda yakin akan dicancel ?')">
			            	&nbsp;&nbsp;<?php echo $tt_hapus2;?></a>
			          </td><?php
			      
					echo "</td></tr>";
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
				<th style="padding-left:0"> <div id="form_jenis_kain"></div> </th> 
				<th style="padding-left:0"> <!--<div id="form_color"> --></div> </th> 
				<th style="padding-left:0"> <div id="form_size"></div> </th> 
				<th style="padding-left:0"> <div id="form_qty"></div></th>
				<th style="padding-left:0"> <!--<div id="form_qtyadd"></div>--> &nbsp;</th>
				<th style="padding-left:0"> <div id="form_action"></div>  </th>
				<th style="padding-left:0"> &nbsp;</th>
				
			</tr>
		</tfoot>
		 <tfoot id='render'>
		 </tfoot>
		 
	  <?php } ?>
    </table>
	
  
	
 
  


  
  </div>

</div>

<?php } else if ($mod=="request1v") { ?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">List Request Development</h3>
  	<a href='../marketting/?mod=request1' class='btn btn-primary btn-s'>
  		<i class='fa fa-plus'></i> New
  	</a>
  </div>
 
  <div class="box-body">
    <table id="examplefix3" class="display responsive" style="width:100%;font-size:10px;">
      <thead>
      <tr>
        <th>Request Development #</th>
        <th>Request Date</th>
        <th>Buyer</th>
        <th>Order</th>
        <th>Created By</th>
		<th>Status</th>
        <th>Action</th>
        </tr>
	  
      </thead>
      <tbody>
	  
        <?php
		//require_once 's_sales_ord_det.php';
        # QUERY TABLE
        $query = mysql_query("select a.*,supplier from request_sample_dev a inner join mastersupplier g on 
          a.id_buyer=g.id_supplier order by a.req_date desc"); 
        if (!$query) {die(mysql_error());}
        $no = 1; 
        while($data = mysql_fetch_array($query))
        { $id=$data['id'];

            echo "<td>$data[no_req]</td>";
            echo "<td>$data[req_date]</td>";
            echo "<td>$data[supplier]</td>";
            echo "<td>$data[xorder]</td>";
            echo "<td>$data[username]</td>";
			echo "<td>";
				if ($data['cancel']=="Y")
				{
				echo "<font color='RED'>Cancel</font>";
				}else
				{
				echo "";	
				}
				
			echo "</td>";
            echo "<td>"; 
            	echo "<a href='pdfReq_dev.php?id=$data[id]'
              	data-toggle='tooltip' title='Save Pdf' target='_blank'><i class='fa fa-file-pdf-o'></i></a>&nbsp;&nbsp;";
			
			if ($data['cancel']=="Y")
				{
				echo "";
				}else
				{
				echo "
           
            	<a href='../marketting/?mod=request1&id=$data[id]&mod2=2'
      					data-toggle='tooltip' title='Preview Request' target='_blank'><i class='fa fa-print'></i>
    					</a>&nbsp;
  					";
					
				}
				
           
           if ($data['cancel']=="Y")
				{
				echo "";
				}else
				{
				echo "
	              <a href='d_req.php?mod=request1&id=$data[id]'
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

<?php } else if ($id_so!="") { ?>

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
		    $query = mysql_query("SELECT sod.*,so.username,sod.price px_det from so_det_dev sod inner join request_sample_dev so on sod.id_so=so.id 
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

<?php } ?>