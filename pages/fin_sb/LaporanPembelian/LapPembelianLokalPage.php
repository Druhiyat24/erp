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
<div class="box type" >
	<div id="formBs" class='box'>
		<div class='box-body'>
			<div class="panel panel-default">
					<div class="panel-heading">Periode</div>
					<div class="panel-body row">
						<div class="col-md-3">
							<div class='form-group'>
								<label>Dari</label>
								<input type='text' id='period_from' class='form-control monthpicker' autocomplete="off" name='period_from'
								placeholder='MM/YYYY'  required>
							</div>
						</div>
						<div class="col-md-3">
							<div class='form-group'>
								<label>Sampai</label>
								<input type='text' id='period_to' autocomplete="off" class='form-control monthpicker' name='period_to'
								placeholder='MM/YYYY'  required>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="col-md-6">
							<div class="form-group">
								<input type="hidden" name="mod" value="lapemlok" />
								<a href="#" id="submit" class='btn btn-primary' onclick="getListData()" />Tampilkan</a>
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
	<div id="formBs" class='box'>
		<div class='box-body' style="overflow:scroll;height:500px">
			<div class="box-body" >
				<table id="lapemlokal" class="table table-condensed table-bordered" >
            <thead id="freeze_thead">
              <tr>

                <th rowspan="2" style="text-align: center;">KODE BARANG</th>
                <th rowspan="2" style="text-align: center;">NAMA BARANG</th>
                <th rowspan="2" style="text-align: center;">JENIS BARANG</th>
                <th rowspan="2" style="text-align: center;"># WS</th>
                <th rowspan="2" style="text-align: center;">STYLE</th>
                <th rowspan="2" style="text-align: center;">ID SUPPLIER</th>
                <th rowspan="2" style="text-align: center;">NAMA SUPPLIER</th>
                <th rowspan="2" style="text-align: center;">ID KONSUMEN</th>
                <th rowspan="2" style="text-align: center;">NAMA KONSUMEN</th>
                <th colspan="2" style="text-align: center;">BC40</th>
                <th rowspan="2" style="text-align: center;">ID ORDER</th>
                <th colspan="2" style="text-align: center;">BPB</th>
                <th colspan="5" style="text-align: center;">PO</th>
                <th colspan="3" style="text-align: center;">PR</th>
                <th rowspan="2" style="text-align: center;"># SO</th>
                <th colspan="2" style="text-align: center;">SJ SUPPLIER</th>
                <th colspan="2" style="text-align: center;">INVOICE</th>
                <th colspan="2" style="text-align: center;">FAKTUR PAJAK</th>
                <th rowspan="2" style="text-align: center;">SATUAN QTY</th>
                <th rowspan="2" style="text-align: center;">QTY</th>
                <th rowspan="2" style="text-align: center;">UNIT PRICE</th>
                <th colspan="3" style="text-align: center;">DPP</th>
                <th colspan="3" style="text-align: center;">PPN</th>
                <th colspan="3" style="text-align: center;">TOTAL</th>
              </tr>
              <tr>
                <th style="text-align: center;">NOMOR PENDAFTARAN</th>
                <th style="text-align: center;">TANGGAL</th>
                <th style="text-align: center;">NOMOR</th>
                <th style="text-align: center;">TANGGAL</th>
                <th style="text-align: center;">NOMOR</th>
                <th style="text-align: center;">TANGGAL</th>
                <th style="text-align: center;">TOTAL QTY</th>
                <th style="text-align: center;">REALISASI QTY</th>
                <th style="text-align: center;">OUTSTANDING QTY</th>
                <th style="text-align: center;">NOMOR</th>
                <th style="text-align: center;">TANGGAL</th>
                <th style="text-align: center;">USER</th>
                <th style="text-align: center;">NOMOR</th>
                <th style="text-align: center;">TANGGAL</th>
                <th style="text-align: center;">NOMOR</th>
                <th style="text-align: center;">TANGGAL</th>
                <th style="text-align: center;">NOMOR</th>
                <th style="text-align: center;">TANGGAL</th>
                <th style="text-align: center;">US$</th>
                <th style="text-align: center;">KURS</th>
                <th style="text-align: center;">IDR</th>
                <th style="text-align: center;">US$</th>
                <th style="text-align: center;">KURS</th>
                <th style="text-align: center;">IDR</th>
                <th style="text-align: center;">US$</th>
                <th style="text-align: center;">KURS</th>
                <th style="text-align: center;">IDR</th>
              </tr>

            </thead>
			</table>
		</div>
	</div>
</div>
</div>