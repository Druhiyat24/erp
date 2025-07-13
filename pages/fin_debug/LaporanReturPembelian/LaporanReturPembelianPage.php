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
                                <label>Periode Dari</label>
                                <input type='text' id='period_from' class='form-control monthpicker' autocomplete="off" name='period_from'
                                placeholder='MM/YYYY'  required>
                            </div>
                        </div>
                    <div class="col-md-3">
                        <div class='form-group'>
                            <label>Periode Sampai</label>
                            <input type='text' id='period_to' autocomplete="off" class='form-control monthpicker' name='period_to'
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

								<th style="font-size: 16px; border-collapse: collapse; border: none;" colspan="14"><strong>Laporan Retur Pembelian</strong></th>

							</tr>



							<tr>

								<th style="font-size: 12px; border-collapse: collapse; border: none;" colspan="14">

									<strong>PERIODE: <div id="label_from"> </div> s/d <div id="label_to"> </div></strong></th>

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

                <th style="background-color:#ffffff" rowspan="2" style="text-align: center;">KODE BARANG</th>
                <th style="background-color:#ffffff" rowspan="2" style="text-align: center;">NAMA BARANG</th>
                <th style="background-color:#ffffff" rowspan="2" style="text-align: center;">JENIS BARANG</th>
                <th style="background-color:#ffffff" rowspan="2" style="text-align: center;"># WS</th>
                <th style="background-color:#ffffff" rowspan="2" style="text-align: center;">STYLE</th>
                <th style="background-color:#ffffff" rowspan="2" style="text-align: center;">ID SUPPLIER</th>
                <th style="background-color:#ffffff" rowspan="2" style="text-align: center;">NAMA SUPPLIER</th>
                <th style="background-color:#ffffff" rowspan="2" style="text-align: center;">ID KONSUMEN</th>
                <th style="background-color:#ffffff" rowspan="2" style="text-align: center;">NAMA KONSUMEN</th>
                <th style="background-color:#ffffff" colspan="2" style="text-align: center;">BC40</th>
                <th style="background-color:#ffffff" rowspan="2" style="text-align: center;">ID ORDER</th>
                <th style="background-color:#ffffff" colspan="2" style="text-align: center;">BPB</th>
                <th style="background-color:#ffffff" colspan="5" style="text-align: center;">PO</th>
                <th style="background-color:#ffffff" colspan="3" style="text-align: center;">PR</th>
                <th style="background-color:#ffffff" rowspan="2" style="text-align: center;"># SO</th>
                <th style="background-color:#ffffff" colspan="2" style="text-align: center;">SJ SUPPLIER</th>
                <th style="background-color:#ffffff" colspan="2" style="text-align: center;">INVOICE</th>
                <th style="background-color:#ffffff" colspan="2" style="text-align: center;">FAKTUR PAJAK</th>
                <th style="background-color:#ffffff" rowspan="2" style="text-align: center;">SATUAN QTY</th>
                <th style="background-color:#ffffff" rowspan="2" style="text-align: center;">QTY</th>
                <th style="background-color:#ffffff" rowspan="2" style="text-align: center;">UNIT PRICE</th>
                <th style="background-color:#ffffff" colspan="3" style="text-align: center;">DPP</th>
                <th style="background-color:#ffffff" colspan="3" style="text-align: center;">PPN</th>
                <th style="background-color:#ffffff" colspan="3" style="text-align: center;">TOTAL</th>
              </tr>
              <tr>
                <th style="background-color:#ffffff" style="text-align: center;">NOMOR PENDAFTARAN</th>
                <th style="background-color:#ffffff" style="text-align: center;">TANGGAL</th>
                <th style="background-color:#ffffff" style="text-align: center;">NOMOR</th>
                <th style="background-color:#ffffff" style="text-align: center;">TANGGAL</th>
                <th style="background-color:#ffffff" style="text-align: center;">NOMOR</th>
                <th style="background-color:#ffffff" style="text-align: center;">TANGGAL</th>
                <th style="background-color:#ffffff" style="text-align: center;">TOTAL QTY</th>
                <th style="background-color:#ffffff" style="text-align: center;">REALISASI QTY</th>
                <th style="background-color:#ffffff" style="text-align: center;">OUTSTANDING QTY</th>
                <th style="background-color:#ffffff" style="text-align: center;">NOMOR</th>
                <th style="background-color:#ffffff" style="text-align: center;">TANGGAL</th>
                <th style="background-color:#ffffff" style="text-align: center;">USER</th>
                <th style="background-color:#ffffff" style="text-align: center;">NOMOR</th>
                <th style="background-color:#ffffff" style="text-align: center;">TANGGAL</th>
                <th style="background-color:#ffffff" style="text-align: center;">NOMOR</th>
                <th style="background-color:#ffffff" style="text-align: center;">TANGGAL</th>
                <th style="background-color:#ffffff" style="text-align: center;">NOMOR</th>
                <th style="background-color:#ffffff" style="text-align: center;">TANGGAL</th>
                <th style="background-color:#ffffff" style="text-align: center;">US$</th>
                <th style="background-color:#ffffff" style="text-align: center;">KURS</th>
                <th style="background-color:#ffffff" style="text-align: center;">IDR</th>
                <th style="background-color:#ffffff" style="text-align: center;">US$</th>
                <th style="background-color:#ffffff" style="text-align: center;">KURS</th>
                <th style="background-color:#ffffff" style="text-align: center;">IDR</th>
                <th style="background-color:#ffffff" style="text-align: center;">US$</th>
                <th style="background-color:#ffffff" style="text-align: center;">KURS</th>
                <th style="background-color:#ffffff" style="text-align: center;">IDR</th>
              </tr>

            </thead>
              </table>
          </div>
  </div>
</div>