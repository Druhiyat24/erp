<div class="container-fluid">
	<div class="row">
    	<div class="col-sm-12">
      	<div class="row"> 
        	<div class="box type" >
            	<div id="formBs" class='box'>
			
                	<div class='box-body'>
						<div class="panel panel-default">
							<div class="panel-heading">Periode</div>
							<div class="panel-body row">
								
								<div class="col-md-3">
									<div class='form-group'>
										<label for="period_from">Tanggal</label>                          
										<input type='text'  id="period_from" class='form-control monthpicker' name='period' placeholder='MM/YYYY' autocomplete="off"  required>
									</div>
								</div>                 

								<div class="col-md-3">
									<div class='form-group'>
										<label>Sampai</label>
										<input type='text'  id="period_to" class='form-control monthpicker' name='period_to' placeholder='MM/YYYY' autocomplete="off"  required>
									</div>
								</div>

								<div class="col-md-3">
									<div class='form-group'>
										<label>Nama COA</label>
										<br />

										<!-- Dropdown --> 
										<select id='id_coa' class="select2" name="id_coa" style='width: 500px;'>
											<?php 
												$sql = "SELECT id_coa, nm_coa FROM mastercoa WHERE v_normal='C' AND id_coa <= '21999' and id_coa >= '21000' AND fg_posting ='1' ORDER BY id_coa ASC";
												$query = mysql_query($sql);							
												while($data = mysql_fetch_array($query)){

													if ($data['id_coa'] == $idcoa1x[0]){ 
														$selected = 'selected' ;
													}
													else{ 
														$selected = "";
													}

													echo "<option value='".$data['id_coa']."-".$data['nm_coa']."' ".$selected." required>".$data['id_coa']." - ".$data['nm_coa']."</option>";
												}
											?>
										</select>
										
										<br/>
									</div>
								</div>                    

								<div class="clearfix"></div>

								<div class="col-md-6">
									<div class="form-group">
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




<div class="box list" style="display:none"> 
    <a href="#" id="submit" class='btn btn-primary' onclick="back()" />Back</a>

	<table style="width:100%;font-size:11px; border-collapse: collapse; outline: none; border: none;">
		<thead>
			<tr>
				<th style="font-size: 12px; border-collapse: collapse; border: none;" colspan="14"><strong>PT.NIRWANA ALABARE GARMENT</strong></th>
			</tr>
			<tr>
				<th style="font-size: 16px; border-collapse: collapse; border: none;" colspan="14"><strong>LAPORAN KARTU AP</strong></th>
			</tr>
			<tr>
				<th style="font-size: 12px; border-collapse: collapse; border: none;" colspan="14"><strong>NAMA COA : <div id="label_type_journal"></div></strong></th>
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
            <table id="laporan_jurnal_hutang" class="table table-condensed table-bordered " style="border:1px solid #000000 !important;width:100%">
				<thead>

					<tr>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" colspan="5">&nbsp;</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" colspan="3">ORIGINAL CURRENCY</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" colspan="4">IDR</th>
					</tr>
					<tr>
						<!-- <th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">NO</th> -->
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">TANGGAL</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle"">NOMOR JURNAL</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle"">NOMOR BPB</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">NOMOR VOUCHER</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">DESCRIPTIONS</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">DEBET</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">KREDIT</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">SALDO</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">KURS</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">DEBET</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">KREDIT</th>
						<th style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">SALDO</th>
					</tr>
					
				</thead>

				<!-- <tfoot>
					<tr>
						<th style="text-align: right; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" colspan="5">TOTAL MUTASI</td>
						<td style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">-</td>
						<td style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle">-</td>
						<td style="text-align: center; background-color:#ffffff;border:1px solid #000000; vertical-align: middle" colspan="5">&nbsp;</td>
					</tr>
				</tfoot> -->
            </table>
        </div>
	</div>
</div>