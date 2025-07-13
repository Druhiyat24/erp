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

									<label for="period_from">Tanggal</label>                          
									<input type='text'  id="period_from" class='form-control monthpicker' name='period'

									placeholder='MM/YYYY' autocomplete="off"  required>

								</div>

							</div>                 

							<div class="col-md-3">

								<div class='form-group'>

									<label>Sampai</label>

									<input type='text'  id="period_to" class='form-control monthpicker' name='period_to'

									placeholder='MM/YYYY' autocomplete="off"  required>

								</div>

							</div>
                    <div class="col-md-3">
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

								<th style="font-size: 16px; border-collapse: collapse; border: none;" colspan="14"><strong>Laporan Rincian Mutasi Piutang Dagang Ekspor</strong></th>

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
                <table id="laporan_jurnal" class="table table-condensed table-bordered" style="width:100%;border:1px solid height:400px; #000000 !important">
						<thead>
							<tr>

								<th style="border:1px solid #000000;text-align: center;background-color:#ffffff" colspan="3">KONSUMEN</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" colspan="2">SURAT JALAN</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" colspan="2">INVOICE</th>
								<!-- <th style="border:1px solid #000000; text-align: center;background-color:#ffffff" colspan="2">KONTRA BON</th> -->
								<!-- <th style="border:1px solid #000000; text-align: center;background-color:#ffffff" colspan="2">FAKTUR PAJAK</th> -->
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" rowspan="3">NOMOR SALES ORDER</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" rowspan="3">ID ORDER</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" rowspan="3">TOP</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" colspan="3">SALDO AWAL</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" colspan="6">PENAMBAHAN</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" colspan="12">PENGURANGAN</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" colspan="3">SALDO AKHIR</th>
							</tr>
							<tr>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" rowspan="2">COA</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" rowspan="2">ID</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" rowspan="2">NAMA</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" rowspan="2">TANGGAL</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" rowspan="2">NOMOR</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" rowspan="2">TANGGAL</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" rowspan="2">NOMOR</th>
								<!-- <th style="border:1px solid #000000; text-align: center;background-color:#ffffff" rowspan="2">TANGGAL</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" rowspan="2">NOMOR</th> -->
								<!-- <th style="border:1px solid #000000; text-align: center;background-color:#ffffff" rowspan="2">TANGGAL</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" rowspan="2">NOMOR</th> -->
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" rowspan="2">US$</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" rowspan="2">KURS</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" rowspan="2">IDR</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" colspan="3">PENJUALAN</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" colspan="3">LAIN-LAIN</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" rowspan="2">REF DOKUMEN</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" rowspan="2">TANGGAL</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" rowspan="2">KET</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" colspan="3">PELUNASAN</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" colspan="3">RETUR POT</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" colspan="3">LAIN-LAIN</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" rowspan="2">$US</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" rowspan="2">KURS</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff" rowspan="2">IDR</th>
							</tr>
							<tr>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff">US$</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff">KURS</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff">IDR</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff">US$</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff">KURS</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff">IDR</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff">US$</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff">KURS</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff">IDR</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff">US$</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff">KURS</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff">IDR</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff">US$</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff">KURS</th>
								<th style="border:1px solid #000000; text-align: center;background-color:#ffffff">IDR</th>
							</tr>
						</thead>
              </table>
          </div>
  </div>
</div>