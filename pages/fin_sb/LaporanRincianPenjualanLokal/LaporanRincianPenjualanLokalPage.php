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

											<label>Periode dari</label>

											<input type='text' id='period_from' class='form-control monthpicker' autocomplete="off" name='period_from'

											placeholder='DD/MM/YYYY'  required>

										</div>

									</div>



									<div class="col-md-3">

										<div class='form-group'>

											<label>Periode ke</label>

											<input type='text' id='period_to' class='form-control monthpicker' autocomplete="off" name='period_to'

											placeholder='DD/MM/YYYY'  required>

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



				<th style="font-size: 16px; border-collapse: collapse; border: none;" colspan="14"><strong>Laporan Rincian Penjualan Lokal</strong></th>



			</tr>







			<tr>



				<th style="font-size: 12px; border-collapse: collapse; border: none;" colspan="14">



					<strong>PERIODE: <div id="label_from"> </div> s/d <div id="label_to"> </div></strong>



				</th>



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



		<div class="box-body" style="font-size: 9pt;" >

			<table id="laporan_jurnal" class="table table-condensed table-bordered" style="border:1px solid #000000 !important">


				<thead>
					<tr>
						<!-- <th style="text-align: center; background-color:#ffffff;border:1px solid #000000" rowspan="2">No</th> -->
						<th colspan="4" style="text-align: center; background-color:#ffffff;border:1px solid #000000">KONSUMEN</th>
						<th rowspan="2" style="text-align: center; background-color:#ffffff;border:1px solid #000000">NOMOR SO</th>
						<th rowspan="2" style="text-align: center; background-color:#ffffff;border:1px solid #000000">NOMOR PO</th>
						<th rowspan="2" style="text-align: center; background-color:#ffffff;border:1px solid #000000">NO WS</th>
						<th rowspan="2" style="text-align: center; background-color:#ffffff;border:1px solid #000000">ID ORDER</th>
						<th rowspan="2" style="text-align: center; background-color:#ffffff;border:1px solid #000000">TOP</th>
						<th rowspan="2" style="text-align: center; background-color:#ffffff;border:1px solid #000000">PERIODE TAGIHAN</th>
						<th colspan="2" style="text-align: center; background-color:#ffffff;border:1px solid #000000">SURAT JALAN</th>
						<th colspan="2" style="text-align: center; background-color:#ffffff;border:1px solid #000000">INVOICE</th>
						<th colspan="2" style="text-align: center; background-color:#ffffff;border:1px solid #000000">FAKTUR PAJAK</th>
						<th rowspan="2" style="text-align: center; background-color:#ffffff;border:1px solid #000000">NOMOR DOKUMEN BC</th>
						<th colspan="8" style="text-align: center; background-color:#ffffff;border:1px solid #000000">DESCRIPTIONS BARANG</th>
						<th rowspan="2" style="text-align: center; background-color:#ffffff;border:1px solid #000000">KETERANGAN</th>
					</tr>
					<tr>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000">COA</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000">ID</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000">NAMA</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000">NAMA ALIAS</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000">TANGGAL</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000">NOMOR</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000">TANGGAL</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000">NOMOR</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000">TANGGAL</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000">NOMOR</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000">KODE</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000">WARNA</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000">NAMA/STYLE</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000">SATUAN QTY</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000">QTY</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000">IDR</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000">PPN</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000">TOTAL RUPIAH</th>
					</tr>
				</thead>


			</table>

		</div>

	</div>

</div>