<?php 
if (empty($_SESSION['username'])) { header("location:../../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
# END CEK HAK AKSES KEMBALI


?>
<!--
<div class="box type" >
<div id="formBs" class='box'>
    <div class='box-body'>
            <div class="panel panel-default">
                <div class="panel-heading">Filter Bpb</div>
                <div class="panel-body row">
                    <div class="col-md-3">
                        <div class='form-group'>
                            <label>Bpb</label>
							<input type="text" class="form-control" id="bpb" placeholder="Masukkan Bpb">

                        </div>
                    </div>

                    <div class="clearfix"></div>

                </div>
            </div>
    </div>
</div>


</div>
-->
<div class="box list" > 
<div id="formBs" class='box'>
<div class="box-body" >

<table id="MasterInvoiceActualTable" class="dispay responsive  table-bordered" style="width:100%">
        <thead>
            <tr>
			<th>Invoice #</th>
			<th>Jurnal Penjualan</th>
			<th>Rekap AR</th>
			<th>No. Penerimaan</th>
			<th>No. Allokasi AR</th>
           </tr>
        </thead>
        <tfoot>
            <tr>
			<th>Invoice #</th>
			<th>Jurnal Penjualan</th>
			<th>Rekap AR</th>
			<th>No. Penerimaan</th>
			<th>No. Allokasi AR</th>
            </tr>
        </tfoot>
    </table>

	</div>

 
</div>


</div>





