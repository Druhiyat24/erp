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

									<label for="period_from">Per* Tanggal</label>                          
									<input type='text'  id="period_from" class='form-control monthpicker' name='period'

									placeholder='DD/MM/YYYY' autocomplete="off"  required>

								</div>

							</div>                 

							<!-- <div class="col-md-3">

								<div class='form-group'>

									<label>Sampai</label>

									<input type='text'  id="period_to" class='form-control monthpicker' name='period_to'

									placeholder='MM/YYYY' autocomplete="off"  required>

								</div>

							</div> -->
                    <div class="col-md-3">
                        <label></label>
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

								<th style="font-size: 16px; border-collapse: collapse; border: none;" colspan="14"><strong>LAPORAN AGING SPAREPARTS</strong></th>

							</tr>



							<tr>

								<th style="font-size: 12px; border-collapse: collapse; border: none;" colspan="14">

									<strong>PERIODE: <div id="label_from"> </div> </strong>
									
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
                <table id="laporan_jurnal" class="stripe" rules="all" style="border:1px solid #000000 !important;width:100%;">
						<thead>
         <tr>

            <th  rowspan="2" style="text-align: center;vertical-align: middle; background-color:#ffffff;border:1px solid #000000">KODE BARANG</th>
            <th  rowspan="2"style="text-align: center;vertical-align: middle;background-color:#ffffff;border:1px solid #000000">NAMA BARANG</th>
            <th  colspan="2"style="text-align: center;vertical-align: middle;background-color:#ffffff;border:1px solid #000000">BPB</th>
            <th  colspan="4"style="text-align: center;background-color:#ffffff;border:1px solid #000000">QTY (PCS)</th>
            <th  colspan="4"style="text-align: center;background-color:#ffffff;border:1px solid #000000">RUPIAH</th>
            <th  colspan="4"style="text-align: center;background-color:#ffffff;border:1px solid #000000">UNIT COST</th>
            <th  colspan="9"style="text-align: center;background-color:#ffffff;border:1px solid #000000">QTY (PCS)</th>
            <th  colspan="9"style="text-align: center;background-color:#ffffff;border:1px solid #000000">RUPIAH</th>

         </tr>
         <tr>
            <th style="text-align: center;vertical-align: middle;background-color:#ffffff;border:1px solid #000000">NOMOR</th>
            <th  style="text-align: center;vertical-align: middle;background-color:#ffffff;border:1px solid #000000">TGL</th>
            <th style="text-align: center;vertical-align: middle;background-color:#ffffff;border:1px solid #000000">AWAL</th>
            <th  style="text-align: center;vertical-align: middle;background-color:#ffffff;border:1px solid #000000">MASUK</th>
            <th  style="text-align: center;vertical-align: middle;background-color:#ffffff;border:1px solid #000000">KELUAR</th>
            <th  style="text-align: center;vertical-align: middle;background-color:#ffffff;border:1px solid #000000">AKHIR</th>
            <th style="text-align: center;vertical-align: middle;background-color:#ffffff;border:1px solid #000000">AWAL</th>
            <th  style="text-align: center;vertical-align: middle;background-color:#ffffff;border:1px solid #000000">MASUK</th>
            <th style="text-align: center;vertical-align: middle;background-color:#ffffff;border:1px solid #000000">KELUAR</th>
            <th  style="text-align: center;vertical-align: middle;background-color:#ffffff;border:1px solid #000000">AKHIR</th>
            <th  style="text-align: center;vertical-align: middle;background-color:#ffffff;border:1px solid #000000">AWAL</th>
            <th  style="text-align: center;vertical-align: middle;background-color:#ffffff;border:1px solid #000000">MASUK</th>
            <th style="text-align: center;vertical-align: middle;background-color:#ffffff;border:1px solid #000000">KELUAR</th>
            <th  style="text-align: center;vertical-align: middle;background-color:#ffffff;border:1px solid #000000">AKHIR</th>
            <th style="text-align: center;background-color:#ffffff;border:1px solid #000000">0-30 DAYS</th>
            <th  style="text-align: center;background-color:#ffffff;border:1px solid #000000">31-60 DAYS</th>
            <th style="text-align: center;background-color:#ffffff;border:1px solid #000000">61-90 DAYS</th>
            <th  style="text-align: center;background-color:#ffffff;border:1px solid #000000">91-120 DAYS</th>
            <th  style="text-align: center;background-color:#ffffff;border:1px solid #000000">121-150 DAYS</th>
            <th  style="text-align: center;background-color:#ffffff;border:1px solid #000000">151-180 DAYS</th>
            <th style="text-align: center;background-color:#ffffff;border:1px solid #000000">181-360 DAYS</th>
            <th  style="text-align: center;background-color:#ffffff;border:1px solid #000000">>361 DAYS</th>
            <th  style="text-align: center;background-color:#ffffff;border:1px solid #000000">TOTAL</th>
            <th style="text-align: center;vertical-align: middle;background-color:#ffffff;border:1px solid #000000">0-30 DAYS</th>
            <th  style="text-align: center;background-color:#ffffff;border:1px solid #000000">31-60 DAYS</th>
            <th style="text-align: center;background-color:#ffffff;border:1px solid #000000">61-90 DAYS</th>
            <th  style="text-align: center;background-color:#ffffff;border:1px solid #000000">91-120 DAYS</th>
            <th  style="text-align: center;background-color:#ffffff;border:1px solid #000000">121-150 DAYS</th>
            <th  style="text-align: center;background-color:#ffffff;border:1px solid #000000">151-180 DAYS</th>
            <th style="text-align: center;background-color:#ffffff;border:1px solid #000000">181-360 DAYS</th>
            <th  style="text-align: center;background-color:#ffffff;border:1px solid #000000">>361 DAYS</th>
            <th style="text-align: center;vertical-align: middle;background-color:#ffffff;border:1px solid #000000">TOTAL</th>
           </tr>
           <!--  <tr>
            <th style="text-align: center;background-color:#ffffff;border:1px solid #000000">COA</th>
            <th style="text-align: center;background-color:#ffffff;border:1px solid #000000">ID</th>
            <th style="text-align: center;background-color:#ffffff;border:1px solid #000000">NAMA</th>
            <th style="text-align: center;background-color:#ffffff;border:1px solid #000000">NAMA ALIAS</th>
            </tr> -->
						</thead>
              </table>
          </div>
  </div>
</div>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.1.2/css/fixedHeader.dataTables.min.css">