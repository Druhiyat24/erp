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
											<label>From</label>
											<input type='text' id='period_from' class='form-control monthpicker' autocomplete="off" name='period_from'
											placeholder='DD/MM/YYYY'  required>
										</div>
									</div>

									<div class="col-md-3">
										<div class='form-group'>
											<label>To</label>
											<input type='text' id='period_to' class='form-control monthpicker' autocomplete="off" name='period_to'
											placeholder='DD/MM/YYYY'  required>
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





		<div id="formBs" class='box'>
			<div class='box-body'>
				<iframe id="txtArea1" style="display:none"></iframe>
				<!-- <a href='#' id="btnExport" style="font-size: 12pt" onclick="fnExcelReport()" /><i class="fa fa-file-excel-o"></i>&nbsp;Export to Excel</a> -->
			</div>
			<div id="formBs" class='box'>

				<div class="box-body" style="font-size: 9pt;" >
					<table id="laporan_jurnal" class="table table-condensed table-bordered" style="border: 1px solid #000000 !important;">
					<thead>
					<tr>
					<th rowspan="2">WS #</th>
					<th rowspan="2">1st Garment Delivery</th>
					<th rowspan="2">ETA Fabric</th>
					<th rowspan="2">RFPD Fabric</th>
					<th colspan="3">Fabric</th>
					<th rowspan="2">ETA Acc. Sewing</th>
					<th rowspan="2">RFPD Acc Sewing</th>
					<th colspan="3">Acc. Sewing</th>
					<th rowspan="2">ETA Acc. Packing</th>
					<th rowspan="2">RFPD Acc Sewing</th>
					<th colspan="3">Acc. Packing</th>
					</tr>
					<tr>
					<th>Items Completed/Total Items</th>
					<th>Qty PO/ Received Qty</th>
					<th>%</th>
					<th>Items Completed/Total Items</th>
					<th>Qty PO/ Received Qty</th>
					<th>%</th>
					<th>Items Completed/Total Items</th>
					<th>Qty PO/ Received Qty</th>
					<th>%</th>
					</tr>
					</thead>
					</table>
				</div>
			</div>
		</div>
		
		
				<!-- Modal Detail Material -- FABRIC, ACC SEWING, ACC PACKING -->

<div class="modal fade detmatstat" role="dialog"> 

	<div class="modal-dialog modal-lg">

		<!-- Modal content-->

		<div class="modal-content">

			<div class="modal-header">

				<h4 class="modal-title">Detail Material Qty</h4>

			</div>

			<div class="modal-body">

			<table id="example1a"  class="display responsive" style="width:100%;font-size:15px;" border="1">

					<thead style="background-color: #e6e6e6">

						<tr >

							<tr>

								<th>No.</th>

								

								<th>Po No.</th>

								<th>Nomor Trans</th>

								<th>Item Name</th>

								<th>Qty PO</th>
								<th>Qty BPB</th>

								<th>Unit PO</th>

							</tr>

						</tr>

					</thead>

					<tbody id="bodyexamle23">

						

							  </tbody>

							</table>
								<center>
				<img src="./images/loading.gif" id="myLoading" class="img-responsive" width="auto">
			</center>
						</div> <!-- end modal body -->

						<div class="modal-footer">

							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

						</div> <!-- end modal footer -->

					</div> <!-- end modal content -->

				</div>

		</div> <!-- end modal -->

		
<div class="modal fade detmatstatcount" role="dialog"> 

	<div class="modal-dialog modal-lg">

		<!-- Modal content-->

		<div class="modal-content">

			<div class="modal-header">

				<h4 class="modal-title">Detail Material </h4>

			</div>

			<div class="modal-body">

			<table id="example1b"  class="display responsive" style="width:100%;font-size:15px;" border="1">

					<thead style="background-color: #e6e6e6">

						<tr >

							<tr>
								<th>No.</th>
								<th>Po No.</th>
								<th>Item Name</th>
								<th>PO</th>
								<th>BPB</th>
							</tr>

						</tr>

					</thead>

					<tbody id="bodyexamle234">

						

							  </tbody>

							</table>
								<center>
				<img src="./images/loading.gif" id="myLoading_2" class="img-responsive" width="auto">
			</center>
						</div> <!-- end modal body -->

						<div class="modal-footer">

							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

						</div> <!-- end modal footer -->

					</div> <!-- end modal content -->

				</div>

		</div> <!-- end modal -->				