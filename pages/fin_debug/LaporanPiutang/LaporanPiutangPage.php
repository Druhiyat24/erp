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
									<input type='text'  id="period_from" class='form-control datepicker' name='period'

									placeholder='MM/YYYY' autocomplete="off"  required>

								</div>

							</div>                 

							<div class="col-md-3">

								<div class='form-group'>

									<label>Sampai</label>

									<input type='text'  id="period_to" class='form-control datepicker' name='period_to'

									placeholder='MM/YYYY' autocomplete="off"  required>

								</div>

							</div>
	<div class="col-md-3">
	                    <div class='form-group'>
                        <label>Nama Coa</label>
                        <!-- select  class="form-control select2 select" id="idcoa"> -->
                  <select id='idcoa' name="idcoa" class='form-control' onchange="getidcoa(this.value)" >
					<option>--Select ID COA--</option>
                  </select>

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
    <a href="#" id="submit" class='btn btn-primary' onclick="Back()" />Back</a>

					<table style="width:100%;font-size:11px; border-collapse: collapse; outline: none; border: none;">

						<thead>

							<tr>

								<th style="font-size: 12px; border-collapse: collapse; border: none;"><strong>PT.NIRWANA ALABARE GARMENT</strong></th>

							</tr>

							<tr>

								<th style=" border-collapse: collapse; border: none;"><strong>LAPORAN PIUTANG: </strong>
								<strong><div id="label_idcoa" ></div></strong></th>
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
               <table id="laporan_jurnal_ar" class="table table-bordered table-condensed" style="width:100%;font-size:12px;overflow: scroll;" border="1">

							<thead>

								<tr>

									<th colspan="5">&nbsp;</th>

									<th style="text-align: center;" colspan="3">ORIGINAL CURRENCY</th>

									<th style="text-align: center;" colspan="4">IDR</th>

								</tr>

								<tr>

								<!-- 	<th style="text-align: center;">No.</th> -->

									<th style="text-align: center;">TANGGAL</th>

									<th style="text-align: center;">NOMOR JURNAL</th>

									<th style="text-align: center;">NOMOR REFERENSI DOKUMEN</th>

									<th style="text-align: center;">NOMOR VOUCHER</th>

									<th style="text-align: center;">DESCRIPTIONS</th>

									<th style="text-align: center;">DEBIT</th>

									<th style="text-align: center;">KREDIT</th>

									<th style="text-align: center;">SALDO</th>

									<th style="text-align: center;">KURS</th>

									<th style="text-align: center;">DEBIT</th>

									<th style="text-align: center;">KREDIT</th>

									<th style="text-align: center;">SALDO</th>

								</tr>

							</thead>
              </table>
          </div>
  </div>
</div>