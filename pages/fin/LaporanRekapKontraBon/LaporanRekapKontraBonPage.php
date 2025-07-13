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

									<label for="period_from">Dari</label>



										<input type='text' id="period_from" class='form-control datepicker' name='period_from'
						
										placeholder='DD/MM/YYYY'  autocomplete="off" >


								</div>

							</div>                 

							<div class="col-md-3">

								<div class='form-group'>

  <label>Sampai</label>

                  <input type='text' id="period_to" class='form-control datepicker' name='period_to'

                  placeholder='DD/MM/YYYY' 
                   
                  autocomplete="off" >

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

								<th style="font-size: 12px; border-collapse: collapse; border: none;"><strong>PT.NIRWANA ALABARE GARMENT</strong></th>

							</tr>

							<tr>

								<th style=" border-collapse: collapse; border: none;"><strong>LAPORAN KAS: </strong>
								<strong><div id="bukukas" ></div></strong></th>
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

            <div class="box-body" style="font-size: 9pt;" >
                <table id="laporan_jurnal" class="table table-condensed table-bordered" style="border:1px solid #000000 !important;width:100%;">
          <thead>
           <tr style="height:0%" class="header">
          <!--   <th height="5" rowspan="2" style="text-align: center;"  >No.</th> -->
            <th colspan="3" style="text-align: center;border:1px solid #000000">SUPPLIER</th>
            <th height="5" rowspan="2" style="text-align: center;border:1px solid #000000">TOP</th>
            <th height="5" rowspan="2" style="text-align: center;border:1px solid #000000">PERIODE TAGIHAN</th>
            <th height="5" colspan="2" style="text-align: center;border:1px solid #000000">KONTRABON</th>
            <th height="5" colspan="2" style="text-align: center;border:1px solid #000000">DOKUMEN</th>
            <th height="5" rowspan="2" style="text-align: center;border:1px solid #000000">TOTAL RUPIAH (RP)</th>
            <th height="5" rowspan="2" style="text-align: center;border:1px solid #000000">KETERANGAN</th>
          </tr>
          <tr>
            <th height="5" style="text-align: center;border:1px solid #000000">KODE/ID</th>
            <th height="5" style="text-align: center;border:1px solid #000000">NAMA</th>
            <th height="5" style="text-align: center;border:1px solid #000000">NAMA ALIAS</th>
            <th height="5" style="text-align: center;border:1px solid #000000">TANGGAL</th>
            <th height="5" style="text-align: center;border:1px solid #000000">NOMOR</th>
            <th height="5" style="text-align: center;border:1px solid #000000">INVOICE</th>
            <th height="5" style="text-align: center;border:1px solid #000000">SURAT JALAN</th>
          </tr>


</thead>
              </table>
          </div>
  </div>
</div>