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
		<div class='box' style="display:none">
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
        </div>  
		<br/><br/><br/>
<table id="TableDetail" class="display responsive" style="width:100%">
        <thead>
            <tr>
			<th>No.Sj </th>
			<th>WS #</th>
			
			<th>Style #</th>
			<th>SO #</th>
			<!--<th>Buyer PO</th>-->
			<th>Dest</th>
			
			<th>Color</th>
			<th>Size</th>
			<th>Qty SO</th>
			<th>Qty BPB</th>
			<th>Unit</th>
			
			<th>Bal</th>
			<th>Qty Invoice</th>
			<th>Price</th>			


			<th>Carton</th> 
			<th>Lot</th>
            </tr>
        </thead>

</table>
	<!-- <a href=""# name='submit' style="display:none"  onclick="saveDetail()" class='btn btn-primary myPost'>Save</a> -->
  </div>
 </div>
<div id="myModalLIST" class="modal fade " role="dialog">
  <div class="modal-dialog modal-dialog modal-lg" role="document">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="HeaderModal">List WS</h4>
      </div>
      <div class="modal-body ">
<table id="TableWs" class="table table-condensed table-bordered" style="width:100%">
        <thead>
            <tr>
		  <th>*</th>
		  <th>WS #*</th>
		  <th>Style #*</th>
		  <th>Destination </th>
		   <th>Qty </th>
          <th>Customer</th>
		   <th>No.Surat Jalan</th>
          <th>Bppbno</th> 
            </tr>
        </thead>
        <tfoot>
          <tr>
		  <th>*</th>
		  <th>WS #*</th>
		  <th>Style #*</th>
		  <th>Destination </th>
		   <th>Qty </th>
          <th>Customer</th>
		  <th>No.Surat Jalan</th>
          <th>Bppbno</th> 
            </tr>
        </tfoot>
</table>	
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>