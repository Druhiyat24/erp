<?php 
if (empty($_SESSION['username'])) { header("location:../../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("invoice","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI


?>
		<div class='box'>
			<div class='box-body'>
				<div class="row">
					<div class='col-md-3'>  
						<select disabled id="type_invoice" onchange="get_type_invoice(this)" class="form-control" name="type_invoice">
							<option value="99" disabled selected>--Pilih Invoice--</option>
							<option  value="1">LOKAL</option>
							<option   value="2">EXPORT</option>
						</select>
					</div>
					<div class='col-md-3'>  
						<a href="#" style="display:none" class='btn btn-primary myPost' onclick="post()">Post</a> 
					</div>					
				</div>
			</div>
		</div>


<div id="form_">

</div>

  <div class='box'>
  <div class="box-body">
         <div class='col-md-3'>
         <!-- <a href=""# name='submit' onclick="AddDetail('Add','999999')" class='btn btn-primary'>Add New</a> -->
        </div>  
		<br/><br/><br/>
  	<table id="MasterCurrencyTable" class="display" style="width:100%;font-size:12px;">
      <thead>
        <tr>

			<th>No So </th>
			<th>Style</th>
			<th>Dest</th>
			<th>Color</th>
			<th>Size</th>
			<th>Qty SO</th>
			<th>Qty BPB</th> 
			<th>Qty Invoice</th>
            <th>Unit</th>   
            <th>Price</th>
			 <th>Carton</th> 
			 <th>Lot</th>

          <!--  <th width='14%'>Action</th> -->
		</tr>
		<tbody id="renders">
		
		</tbody>
	
      </thead>

      <tbody id='render'>

      </tbody>
    </table>
	<a href=""# name='submit' style="display:none"  onclick="saveDetail()" class='btn btn-primary myPost'>Save</a>
  </div>
 </div>


