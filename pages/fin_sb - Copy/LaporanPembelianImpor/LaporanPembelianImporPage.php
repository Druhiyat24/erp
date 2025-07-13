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

								<th style="font-size: 16px; border-collapse: collapse; border: none;" colspan="14"><strong>Laporan Pembelian Impor</strong></th>

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
                <!-- <th style="text-align: center; background-color:#ffffff;" rowspan="2">NO</th> -->
                <th style="text-align: center; background-color:#ffffff;" rowspan="2">KODE BARANG</th>
                <th style="text-align: center; background-color:#ffffff;" rowspan="2">NAMA BARANG</th>
                <th style="text-align: center; background-color:#ffffff;" rowspan="2">JENIS BARANG</th>
                <th style="text-align: center; background-color:#ffffff;" rowspan="2"># WS</th>
                <th style="text-align: center; background-color:#ffffff;" rowspan="2">STYLE</th>
                <th style="text-align: center; background-color:#ffffff;" rowspan="2">ID SUPPLIER</th>
                <th style="text-align: center; background-color:#ffffff;" rowspan="2">NAMA SUPPLIER</th>
                <th style="text-align: center; background-color:#ffffff;" rowspan="2">ID KONSUMEN</th>
                <th style="text-align: center; background-color:#ffffff;" rowspan="2">NAMA KONSUMEN</th>
                <th style="text-align: center; background-color:#ffffff;" rowspan="2">ID ORDER</th>
                <th style="text-align: center; background-color:#ffffff;" colspan="2">BPB</th>
                <th style="text-align: center; background-color:#ffffff;" colspan="5">PO</th>
                <th style="text-align: center; background-color:#ffffff;" rowspan="2"># SO</th>
                <th style="text-align: center; background-color:#ffffff;" colspan="2">BL/AWB</th>
                <th style="text-align: center; background-color:#ffffff;" colspan="2">INVOICE</th>
                <th style="text-align: center; background-color:#ffffff;" colspan="2">PIB/DOC BC23</th>
                <th style="text-align: center; background-color:#ffffff;" colspan="2">DOKUMEN LC</th>
                <th style="text-align: center; background-color:#ffffff;" rowspan="2">SATUAN QTY</th>
                <th style="text-align: center; background-color:#ffffff;" rowspan="2">QTY</th>
                <th style="text-align: center; background-color:#ffffff;" rowspan="2">UNIT PRICE</th>
                <th style="text-align: center; background-color:#ffffff;" colspan="3">DPP</th>
                <th style="text-align: center; background-color:#ffffff;" colspan="3">TAXES</th>
                <th style="text-align: center; background-color:#ffffff;" rowspan="2">TOTAL</th>
              </tr>
              <tr>
                <th style="text-align: center; background-color:#ffffff;">NOMOR</th>
                <th style="text-align: center; background-color:#ffffff;">TANGGAL</th>
                <th style="text-align: center; background-color:#ffffff;">NOMOR</th>
                <th style="text-align: center; background-color:#ffffff;">TANGGAL</th>
                <th style="text-align: center; background-color:#ffffff;">TOTAL QTY</th>
                <th style="text-align: center; background-color:#ffffff;">REALISASI QTY</th>
                <th style="text-align: center; background-color:#ffffff;">OUTSTANDING QTY</th>
                <th style="text-align: center; background-color:#ffffff;">NOMOR</th>
                <th style="text-align: center; background-color:#ffffff;">TANGGAL</th>
                <th style="text-align: center; background-color:#ffffff;">NOMOR</th>
                <th style="text-align: center; background-color:#ffffff;">TANGGAL</th>
                <th style="text-align: center; background-color:#ffffff;">NOMOR</th>
                <th style="text-align: center; background-color:#ffffff;">TANGGAL</th>
                <th style="text-align: center; background-color:#ffffff;">NOMOR</th>
                <th style="text-align: center; background-color:#ffffff;">TANGGAL</th>
                <th style="text-align: center; background-color:#ffffff;">US$</th>
                <th style="text-align: center; background-color:#ffffff;">KURS</th>
                <th style="text-align: center; background-color:#ffffff;">IDR</th>
                <th style="text-align: center; background-color:#ffffff;">BEA MASUK</th>
                <th style="text-align: center; background-color:#ffffff;">PPh22</th>
                <th style="text-align: center; background-color:#ffffff;">PPN</th>
              </tr>

            </thead>
              </table>
          </div>
  </div>
</div>