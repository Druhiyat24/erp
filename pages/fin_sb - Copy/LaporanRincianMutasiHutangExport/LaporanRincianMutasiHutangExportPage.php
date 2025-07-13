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
				<th style="font-size: 16px; border-collapse: collapse; border: none;" colspan="14"><strong>Laporan Rincian Mutasi Hutang Dagang Impor</strong></th>
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
	</div>
	<div id="formBs" class='box'>

		<div class="box-body" style="font-size: 9pt;" >
			<table id="laporan_jurnal" class="table table-condensed table-bordered display" style="border:1px solid #000000 !important; width: 100%">
				<thead>
					<tr>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" colspan="2">SUPPLIER</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" colspan="2">SURAT JALAN</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" colspan="2">INVOICE</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" colspan="2">PIB</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" colspan="2">FAKTUR PAJAK</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" colspan="2">BUKTI PENERIMAAN BARANG</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" rowspan="3">ID ORDER</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" rowspan="3">TOP</th>
						<!-- <th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" colspan="3">SALDO AWAL</th> -->
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" colspan="7">PENAMBAHAN</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" colspan="16">PENGURANGAN</th>
						<!-- <th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" colspan="3">SALDO AKHIR</th> -->
					
					</tr>
					<tr>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" rowspan="2">KODE/ID</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" rowspan="2">NAMA</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" rowspan="2">TANGGAL</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" rowspan="2">NOMOR</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" rowspan="2">TANGGAL</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" rowspan="2">NOMOR</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" rowspan="2">TANGGAL</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" rowspan="2">NOMOR</th>								
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" rowspan="2">TANGGAL</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" rowspan="2">NOMOR</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" rowspan="2">TANGGAL</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" rowspan="2">NOMOR</th>
						
						<!-- <th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" rowspan="2">US$</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" rowspan="2">KURS</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" rowspan="2">IDR</th> -->
						
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" colspan="3">PEMBELIAN</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" colspan="3">LAIN-LAIN</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" rowspan="2">REF DOKUMEN</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" rowspan="2">NOMOR JURNAL</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" rowspan="2">NOMOR PAYMENT VOUCHER</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" rowspan="2">TANGGAL</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" rowspan="2">KET</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" colspan="3">PEMBAYARAN</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" colspan="3">RETUR</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" colspan="3">DISCOUNT/KLAIM</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" colspan="3">LAIN-LAIN</th>
						
						<!-- <th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" rowspan="2">US$</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" rowspan="2">KURS</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" rowspan="2">IDR</th> -->
					</tr>
					<tr>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">US$</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">KURS</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">IDR</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">US$</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">KURS</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">IDR</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">US$</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">KURS</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">IDR</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">US$</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">KURS</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">IDR</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">US$</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">KURS</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">IDR</th>
						
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">US$</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">KURS</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">IDR</th> 
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>