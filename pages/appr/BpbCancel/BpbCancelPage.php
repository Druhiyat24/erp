<?php 
if (empty($_SESSION['username'])) { header("location:../../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
# END CEK HAK AKSES KEMBALI


?>

<div class="box list" > 
<div id="formBs" class='box'>
<div class="box-body" >

<table id="MasterInvoiceActualTable" class="dispay responsive  table-bordered" style="width:100%">
        <thead>
            <tr>

			<th>No Bpb</th>
			<th>Tanggal Bpb</th>
			<th>No Po</th>
			<th>Tanggal Po</th>
			<th>No Ws</th>
			<th >Action</th>
           </tr>
        </thead>
        <tfoot>
            <tr>
			<th>No Bpb</th>
			<th>Tanggal Bpb</th>
			<th>No Po</th>
			<th>Tanggal Po</th>
			<th>No Ws</th>
			<th >Action</th>
            </tr>
        </tfoot>
    </table>

	</div>

 
</div>


</div>





