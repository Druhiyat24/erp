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
											<input type='text' id='period_from' class='form-control monthpicker' autocomplete="off" name='period_from' placeholder='DD/MM/YYYY'  required>
										</div>
									</div>
									<div class="col-md-3">
										<div class='form-group'>
											<label>Periode ke</label>
											<input type='text' id='period_to' class='form-control monthpicker' autocomplete="off" name='period_to' placeholder='DD/MM/YYYY'  required>
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
				<th style="font-size: 16px; border-collapse: collapse; border: none;" colspan="14"><strong>PERINCIAN RETUR & POTONGAN/KLAIM PENJUALAN LOKAL</strong></th>
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
			<table id="laporan_jurnal" class="table table-condensed table-bordered" style="border:1px solid #000000 !important; width: 100%">
				<thead>
					<tr>
						<th rowspan="2" style="text-align: center; vertical-align: middle; background-color: #ffffff;">NO</th>
						<th colspan="4" style="text-align: center; vertical-align: middle; background-color: #ffffff;">KONSUMEN</th>
						<th rowspan="2" style="text-align: center; vertical-align: middle; background-color: #ffffff;">NOMOR SO</th>
						<th rowspan="2" style="text-align: center; vertical-align: middle; background-color: #ffffff;">NOMOR PO</th>
						<th rowspan="2" style="text-align: center; vertical-align: middle; background-color: #ffffff;">NO WS</th>
						<th rowspan="2" style="text-align: center; vertical-align: middle; background-color: #ffffff;">ID ORDER</th>
						<th rowspan="2" style="text-align: center; vertical-align: middle; background-color: #ffffff;">TOP</th>
						<th rowspan="2" style="text-align: center; vertical-align: middle; background-color: #ffffff;">PERIODE TAGIHAN</th>
						<th colspan="2" style="text-align: center; vertical-align: middle; background-color: #ffffff;">SURAT JALAN</th>
						<th colspan="2" style="text-align: center; vertical-align: middle; background-color: #ffffff;">INVOICE</th>
						<th colspan="2" style="text-align: center; vertical-align: middle; background-color: #ffffff;">EX FAKTUR PAJAK</th>
						<th colspan="2" style="text-align: center; vertical-align: middle; background-color: #ffffff;">NOTA RETUR MARKETING</th>
						<th colspan="9" style="text-align: center; vertical-align: middle; background-color: #ffffff;">DESCRIPTIONS BARANG</th>
						<th rowspan="2" style="text-align: center; vertical-align: middle; background-color: #ffffff;">KETERANGAN</th>
					</tr>
					<tr>
						<!-- Konsumen -->
						<th style="text-align: center; vertical-align: middle; background-color: #ffffff;">COA</th>
						<th style="text-align: center; vertical-align: middle; background-color: #ffffff;">ID</th>
						<th style="text-align: center; vertical-align: middle; background-color: #ffffff;">NAMA</th>
						<th style="text-align: center; vertical-align: middle; background-color: #ffffff;">NAMA ALIAS</th>
						
						<!-- Surat Jalan -->
						<th style="text-align: center; vertical-align: middle; background-color: #ffffff;">TANGGAL</th>
						<th style="text-align: center; vertical-align: middle; background-color: #ffffff;">NOMOR</th>

						<!-- Invoice -->
						<th style="text-align: center; vertical-align: middle; background-color: #ffffff;">TANGGAL</th>
						<th style="text-align: center; vertical-align: middle; background-color: #ffffff;">NOMOR</th>

						<!-- EX Faktur Pajak -->
						<th style="text-align: center; vertical-align: middle; background-color: #ffffff;">TANGGAL</th>
						<th style="text-align: center; vertical-align: middle; background-color: #ffffff;">NOMOR</th>

						<!-- Nota Retur Marketing -->
						<th style="text-align: center; vertical-align: middle; background-color: #ffffff;">TANGGAL</th>
						<th style="text-align: center; vertical-align: middle; background-color: #ffffff;">NOMOR</th>

						<!-- Description Barang -->
						<th style="text-align: center; vertical-align: middle; background-color: #ffffff;">KODE</th>
						<th style="text-align: center; vertical-align: middle; background-color: #ffffff;">WARNA/SIZE</th>
						<th style="text-align: center; vertical-align: middle; background-color: #ffffff;">NAMA/STYLE</th>
						<th style="text-align: center; vertical-align: middle; background-color: #ffffff;">SATUAN QTY</th>
						<th style="text-align: center; vertical-align: middle; background-color: #ffffff;">QTY</th>
						<th style="text-align: center; vertical-align: middle; background-color: #ffffff;">US$</th>
						<th style="text-align: center; vertical-align: middle; background-color: #ffffff;">KURS</th>
						<th style="text-align: center; vertical-align: middle; background-color: #ffffff;">IDR</th>
						<th style="text-align: center; vertical-align: middle; background-color: #ffffff;">TOTAL RUPIAH</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>