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
<style>

.DTFC_LeftWrapper{
	visibility:  : hidden
}
.DTFC_RightWrapper{
	display: none;
}
.dataTables_scrollBody thead {
visibility: hidden;
}
 .fixed_ {
  position: -webkit-sticky; /* for Safari */
  position: sticky;
  top: 0;
  background: #FFFFFF;
  color: #00000;
}


tbody th {
  position: -webkit-sticky; /* for Safari */
  position: sticky;
  left: 0;
  background: #FFF;
  border-right: 1px solid #CCC;
}

thead th:nth-child(1) {
  left: 1px;
  z-index: 1;
}
thead th:nth-child(2) {
  left: 11%;
  z-index: 1;
}
thead th:nth-child(3) {
  left: 19%;
  z-index: 1;
}
#TableDetail tbody td:nth-child(1) {
  position: sticky;
  left: 1px;
  background: #FFF;
  border-right: 1px solid #CCC;
}
#TableDetail tbody td:nth-child(2) {
  position: sticky;
  left: 11%;
  background: #FFF;
  border-right: 1px solid #CCC;
}
#TableDetail tbody td:nth-child(3) {
  position: sticky;
  left: 19%;
  background: #FFF;
  border-right: 1px solid #CCC;
}



</style>
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
<table id="TableDetail" class="stripe row-border order-column text-center table-style1" style="width:100%">
        <thead>
            <tr>
			<th class='fixed_'>No.Sj </th>
			<th class='fixed_'>WS #</th>
			
			<th class='fixed_'>Style #</th>
			<th class='fixed_'>SO #</th>
			<!--<th>Buyer PO</th>-->
			<th class='fixed_'>Dest</th>
			
			<th class='fixed_'>Color</th>
			<th class='fixed_'>Size</th>
			<th class='fixed_'>Qty SO</th>
			<th class='fixed_'>Qty BPB</th>
			<th class='fixed_'>Unit</th>
			
			<th class='fixed_'>Bal</th>
			<th class='fixed_'>Qty Invoice</th>
			<th class='fixed_'>Cartoon No. Dari</th>
			<th class='fixed_'>Cartoon No. Sampai</th>
			<th class='fixed_'>Total Cartoon</th>
			<th class='fixed_'>Total PCS</th>
			<th class='fixed_'>N.W</th>
			<th class='fixed_'>G.W</th>
			<th class='fixed_'>Total N.W</th>
			<th class='fixed_'>Total G.W</th>



            </tr>
        </thead>
		<tbody>
		</tbody>

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