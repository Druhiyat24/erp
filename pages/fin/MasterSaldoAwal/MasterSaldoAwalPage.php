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

									<label>Id Coa</label>
									<select class="form-control select2" id="id_coa">
									
									</select>

								</div>

							</div>

							<div class="col-md-3">
							

								<div class='form-group'>

									<label for="period_from">Dari</label>                          
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


<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="row"> 
        <div class="box type" >
            <div id="formBs" class='box'>
                <div class='box-body'>
                    <div class="panel panel-default"> 
                        <div class="panel-heading">Generate Saldo</div>
                        <div class="panel-body row"><!-- START -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <a href="?mod=GenerateSaldoAwalByAccountAllDay" id="submit" class='btn btn-primary' />Generate Saldo By Coa</a>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group">
                            <a href="?mod=GenerateSaldoAwalByAccountAllDay" id="submit" class='btn btn-primary' />Generate Saldo By Coa By Date</a>
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
								<th style="text-align: center;background-color:#ffffff" >Tanggal</th>
								<th style="text-align: center;background-color:#ffffff" >Id Coa</th>
								<th style="text-align: center;background-color:#ffffff" >Nama Coa</th>
								<th style="text-align: center;background-color:#ffffff" >Saldo Harian(Rp)</th>
								<th style="text-align: center;background-color:#ffffff" >Saldo Harian TerUpdate(Rp)</th>
							</tr>
						</thead>
              </table>
          </div>
  </div>
</div>