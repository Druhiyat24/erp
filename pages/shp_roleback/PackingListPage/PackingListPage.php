<?php 
if (empty($_SESSION['username'])) { header("location:../../../index.php"); }
$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
# END CEK HAK AKSES KEMBALI
?>
<div class="box list" > 
  <div class="box-header" id="my_new">
		
  </div>
<div id="formBs" class='box'>
<div class="box-body" >
<table id="MasterInvoiceActualTable" class="table table-condensed table-bordered" style="width:100%">
        <thead>
            <tr>
		  <th>Type Packing List #</th>
		  <th>Packing List Date #</th>
		  <th>Packing </th>
          <th>Invoice #</th>
          <th>Invoice Date</th> 
		  <th>Ws #</th>
          <th>Customer</th>
		  <th>Created By</th>
		   <th>Status</th>
          <th>Action</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
		  <th>Type Packing List #</th>
		  <th>Packing List Date #</th>
		  <th>Packing </th>
          <th>Invoice #</th>
          <th>Invoice Date</th> 
		  <th>Ws #</th>
          <th>Customer</th>
		  <th>Created By</th>
		   <th>Status</th>
          <th>Action</th>
            </tr>
        </tfoot>
    </table>
	</div>
 
</div>
</div>
