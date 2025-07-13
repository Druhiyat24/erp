<?php 
if (empty($_SESSION['username'])) { header("location:../../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
# END CEK HAK AKSES KEMBALI


?>

<div class="box type" >
<div id="formBs" class='box'>
    <div class='box-body'>
            <div class="panel panel-default">
                <div class="panel-heading">Jenis Invoice</div>
                <div class="panel-body row">
                    <div class="col-md-3">
                        <div class='form-group'>
                            <label>Invoice</label>

							<select class="form-control" id='txt_inv' onchange="GenerateTable(this.value)">
								<option value='1'>Lokal</option>
								<option value='2'>Export</option>
							</select>

                        </div>
                    </div>

                    <div class="clearfix"></div>

                </div>
            </div>
    </div>
</div>


</div>
<div class="box list" > 
<div id="formBs" class='box'>
<div class="box-body" >

<table id="MasterInvoiceActualTable" class="dispay responsive  table-bordered" style="width:100%">
        <thead>
            <tr>

			<th>Type Invoice</th>
			<th>No Invoice</th>
			<th>No Packing List</th>
			<th>Buyer</th>
			<th>Ws</th>
			<th>So</th>
			<th>Style</th>
           <th>PO</th>   
		   <th>Created By</th>
		   <th>Created Date</th>
		   <th>User Post</th>
		   <th>Date Post</th>
			<th >Action</th>
           </tr>
        </thead>
        <tfoot>
            <tr>
			<th>Type Invoice</th>
			<th>No Invoice</th>
			<th>No Packing List</th>
			<th>Buyer</th>
			<th>Ws</th>
			<th>So</th>
			<th>Style</th>
           <th>PO</th>   
		   <th>Created By</th>
		   <th>Created Date</th>
		   <th>User Post</th>
		   <th>Date Post</th>
			<th >Action</th>
            </tr>
        </tfoot>
    </table>

	</div>

 
</div>


</div>





