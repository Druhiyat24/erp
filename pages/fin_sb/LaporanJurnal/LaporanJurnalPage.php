<?php 
# END CEK HAK AKSES KEMBALI
$journal_type = array(
    "1" => "Jurnal Penjualan",
    "2" => "Jurnal Pembelian",
    "3" => "Jurnal Pembayaran",
    "4" => "Jurnal Alokasi AR",
    "5" => "Jurnal Cash Masuk",
    "6" => "Jurnal Umum",
    "7" => "Jurnal Aktiva Tetap",
    "8" => "Jurnal Penyesuaian",
    "9" => "Jurnal Closing",
    "10" => "Jurnal Cash Keluar",
    "11" => "Jurnal Bank Masuk",
    "12" => "Jurnal Bank Keluar",       
    "13" => "Jurnal Penerimaan",
    "14" => "Jurnal Kontra Bon",
    //"15" => "Jurnal Pemakloon",
    //"16" => "Jurnal Makloon",
    "17" => "Jurnal Subcon",
);
$posting_flag = array(
    '0' => 'Parked',
    '2' => 'Posted',
);
//var_dump($journal_type);
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
                                    <label>Tipe Jurnal</label>
                                    <select class='form-control select2' id='txttipe_jurnal' style='width: 100%;' onchange='check_journal(this.value)' name='txttipe_jurnal'>
                                        <option selected="selected" value="">-- Pilih Tipe Jurnal --</option>
                                        <?php 
                                        foreach($journal_type as $jtype => $jtype_value) {
                                            ?>
                                            <option value='<?=$jtype ?>'>
                                                <?= $jtype_value; ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                    </select>

                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class='form-group'>
                                    <label>Status</label>
                                    <select class='form-control select2' id='txtstatus' style='width: 100%;' 
                                    name='txtstatus'>
                                    <option selected="selected" value="">-- Pilih Status --</option>
                                    <?php 
                                    foreach($posting_flag as $post_flag => $post_flag_value) {
                                        ?>
                                        <option value='<?=$post_flag ?>'>
                                            <?=$post_flag_value; ?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            </div>
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

								<th style="font-size: 16px; border-collapse: collapse; border: none;" colspan="14"><strong>LAPORAN JOURNAL</strong></th>

							</tr>


							<tr>

								<th style="font-size: 12px; border-collapse: collapse; border: none;" colspan="14"><strong>Type Journal: <div id="label_type_journal"></div></strong></th>

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
                          <th style="border:1px solid #000000 !important">Periode</th>
						  <th style="border:1px solid #000000 !important">Status</th>
                          <th style="border:1px solid #000000 !important">Nomor Jurnal</th>
                          <th style="border:1px solid #000000 !important">Tanggal Jurnal</th>
                          <th style="border:1px solid #000000 !important">ID COA</th>
                          <th style="border:1px solid #000000 !important">Nama COA</th>
						  <th style="border:1px solid #000000 !important">Mata Uang</th>		
                          <th style="border:1px solid #000000 !important">Debit</th>	
                          <th style="border:1px solid #000000 !important">Kredit</th>
						  
							<th style="border:1px solid #000000 !important">Debit (Eqv IDR)</th>
							<th style="border:1px solid #000000 !important">Kredit (Eqv IDR)</th>
                          <th style="border:1px solid #000000 !important">Keterangan</th>
                          <th style="border:1px solid #000000 !important">ID Cost Center</th>
                          <th style="border:1px solid #000000 !important">Nama Cost Center</th>
                      </tr>
                  </thead>
              </table>
          </div>
  </div>
</div>