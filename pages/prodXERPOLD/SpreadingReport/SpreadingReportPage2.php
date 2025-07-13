<div class="box">
    <div class="box-header">
        <h3 class="box-title">List Spreading Number</h3>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal2" onclick="add_nomor_spreading('1','X','X')"><i class="fa fa-plus"></i>&nbsp;Add Number</button>
    </div>

    <div class="box-body">

        <!-- Modal -->
        <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Add Spreading Number</h4>
                    </div>
                    <div class="modal-body">
						<label>WS#</label>
					
						<input type="text" class="form-control" id="modals_ws" disabled placeholder="WS#"> 
						
						<label>Spreading Number</label>
					
						<input type="text" class="form-control" id="nomor_spreading_internal" placeholder="Spreading Number Input"> 
                    </div>
                    <div class="modal-footer">
                        <button type="submit" onclick="saveNomor()" class="btn btn-success">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <table id="item" class="table-bordered table-striped" style="width: 100%">
            <thead>
                <tr>
                    <th style="text-align: center">WS#</th>
					<th style="text-align: center">SO#</th>
					<th style="text-align: center">Buyer</th>
					<th style="text-align: center">Style</th>
					<th style="text-align: center">Color</th>
                    <th style="text-align: center">Spreading Number#</th>
                    <th style="text-align: center">Action</th>
                </tr>
            </thead>
        </table>

        <div class="row">
            <div class="col-md-3">
                <a href="#" class="btn btn-warning"  onclick="my_back('../prod/?mod=SpreadingReport')">Back</a>
            </div>
        </div>

    </div>
</div>