<?php 
if (empty($_SESSION['username'])) { header("location:../../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];

?>
<style>
    .form{
        width: 65% !important;
        margin-bottom: 5px;
        height: 27px;
    }
    .select2{
        /* height: 27px !important; */
        margin-bottom: 5px;
    }
    th{
        text-align: center;
    }
</style>
<div class="box list" > 
<div id="formBs" class='box'>
<div class="box-body" >

<table id="MasterInvoiceActualTable" class="dispay  table-bordered" style="width:100%">
        <thead>
            <tr>
				<th style="background-color:white;">BPB #</th>
				<th style="background-color:white;">BPB Retur Pengeluaran#</th>
				<th style="background-color:white;">Pengembalian Bahan Baku</th>
				<th style="background-color:white;">No. Pembelian</th>
				<th style="background-color:white;">No. Kontra Bon</th>
				<th style="background-color:white;">List Payment </th>
				<th style="background-color:white;">No Pembayaran</th>
				<th style="background-color:white;">No. Pembelian Retur </th>
				<th style="background-color:white;">No. Kontra Bon Retur</th>
				<th style="background-color:white;">List Payment Retur </th>
				<th style="background-color:white;">No Pembayaran Retur </th>
				<th style="background-color:white;">No. Pembelian Pengembalian</th>
				<th style="background-color:white;">No. Kontra Bon Pengembalian</th>
				<th style="background-color:white;">List Payment Pengembalian</th>
				<th style="background-color:white;">No Pembayaran Pengembalian</th>				
           </tr>
        </thead>

    </table>

	</div>

 
</div>


</div>





