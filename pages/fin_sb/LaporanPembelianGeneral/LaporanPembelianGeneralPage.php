<style>
	table.DTCR_clonedTable.dataTable {
		position: absolute !important;
		background-color: rgba(255, 255, 255, 0.7);
		z-index: 202;
	}

	div.DTCR_pointer {
		width: 1px;
		background-color: #0259C4;
		z-index: 201;
	}

</style>
<?php 

if (empty($_SESSION['username'])) { header("location:../../../index.php"); }
$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("act_costing","userpassword","username='$user'");
if ($akses=="0") 
	{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI

?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.1.6/css/fixedHeader.dataTables.min.css">
<link rel="stylesheet" type="text/javascript" href="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js">
<link rel="stylesheet" type="text/javascript" href="https://cdn.datatables.net/fixedheader/3.1.6/js/dataTables.fixedHeader.min.js">

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="row"> 
				<div class="box type" >
					<div id="formBs" class='box'>
						<div class='box-body'>
							<div class="panel panel-default"> 
								<div class="panel-heading">Filter</div>
								<div class="panel-body row"><!-- START -->

									<div class="col-md-3">
										<div class='form-group'>
											<label>Periode Dari</label>
											<input type='text' id='period_from' class='form-control monthpicker' autocomplete="off" name='period_from'
											placeholder='MM/YYYY'  required>
										</div>
									</div>
									<div class="col-md-3">
										<div class='form-group'>
											<label>Periode Ke</label>
											<input type='text' id='period_to' class='form-control monthpicker' autocomplete="off" name='period_from'
											placeholder='MM/YYYY'  required>
										</div>
									</div>
									<div class="col-md-3">
										<label>&nbsp;</label>
										<div class="form-group">
											<input type="hidden" name="mod" value="lapjur"/>
											<a href="#" id="submit" class='btn btn-primary' onclick="getListData()"/>Tampilkan</a>
										</div>
									</div>
								</div> 
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>




<div class="box list" style="display:none"> 
	<a href="#" id="submit" class='btn btn-primary' onclick="back()" />Back</a>

	<table style="width:100%;font-size:11px; border-collapse: collapse; outline: none; border: none;">

		<thead>

			<tr>

				<th style="font-size: 12px; border-collapse: collapse; border: none;" colspan="14"><strong>PT.NIRWANA ALABARE GARMENT</strong></th>

			</tr>

			<tr>

				<th style="font-size: 16px; border-collapse: collapse; border: none;" colspan="14"><strong>Laporan Pembelian General</strong></th>

			</tr>



			<tr>

				<th style="font-size: 12px; border-collapse: collapse; border: none;" colspan="14">

					<strong>PER: <div id="label_from"> </div></th>

					</tr>

				</thead>

			</table>			
			
		</div> 

		<div id="formBs" class='box'>
			<div class='box-body'>
				<iframe id="txtArea1" style="display:none"></iframe>
				<!-- <a href='#' id="btnExport" style="font-size: 12pt" onclick="fnExcelReport()" /><i class="fa fa-file-excel-o"></i>&nbsp;Export to Excel</a> -->
			</div>
			<div id="formBs" class='box'>

				<div class="box-body" style="font-size: 12pt;" >
					<table id="laporan_jurnal" class="table table-condensed table-bordered" style="border:1px solid #000000 !important">
						<thead>
									<tr>
										<th style="background-color: #ffffff; text-align: center;">Nomor BPB</th>
										<th style="background-color: #ffffff; text-align: center;">Tanggal BPB</th>
										<th style="background-color: #ffffff; text-align: center;">Nama Supplier</th>
										<th style="background-color: #ffffff; text-align: center;">Kode Barang</th>
										<th style="background-color: #ffffff; text-align: center;">Nama Barang</th>
										<th style="background-color: #ffffff; text-align: center;">Qty</th>
										<th style="background-color: #ffffff; text-align: center;">Unit</th>
										<th style="background-color: #ffffff; text-align: center;">Currency</th>
										<th style="background-color: #ffffff; text-align: center;">Price</th>
										<th style="background-color: #ffffff; text-align: center;">Total</th>
										<th style="background-color: #ffffff; text-align: center;">Remark</th>
										<th style="background-color: #ffffff; text-align: center;">Nomor Invoice / SJ Supplier</th>
										<th style="background-color: #ffffff; text-align: center;">Nomor PO</th>
										<th style="background-color: #ffffff; text-align: center;">Username</th>
										<th style="background-color: #ffffff; text-align: center;">Nomor BC</th>
										<th style="background-color: #ffffff; text-align: center;">Tanggal BC</th>
										<th style="background-color: #ffffff; text-align: center;">Jenis Dokumen</th>
										<th style="background-color: #ffffff; text-align: center;">Confirmed By</th>
										<th style="background-color: #ffffff; text-align: center;">Confirmed Date</th>

									</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>