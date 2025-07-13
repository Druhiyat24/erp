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

        <!-- Modal Ke-3 -->
        <div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="col-md-12">
                                <div class="row">
                                    <h4 class="modal-title" id="myModalLabel" style="text-align: center;">Are you sure want to delete ???</h4>
                                </div>
                            </div>
                        </div>

                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="row" style="text-align: center;">
                                    <a href="#" id="delete" onclick="drop()" data-dismiss="modal" class="btn btn-danger">Yes, Delete it</a>
                                    <button type="button" class="btn btn-warning" data-dismiss="modal">No, Don't Delete</button>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Modal Ke-3 -->

        <table id="item" class="table-bordered table-striped" style="width: 100%">
            <thead>
                <tr>
                    <th style="text-align: center">WS#</th>
					<th style="text-align: center">SO#</th>
					<th style="text-align: center">Buyer</th>
					<th style="text-align: center">Style</th>
					<th style="text-align: center">Color</th>
                    <th style="text-align: center">Spreading Number</th>
                    <th style="text-align: center; min-width: 150px;">Action</th>
                </tr>
            </thead>
        </table>
        <br>
        <div class="row">
            <div class="col-md-3">
                <a href="#" class="btn btn-warning"  onclick="my_back('../prod/?mod=SpreadingReport')">Back</a>
            </div>
        </div>

    </div>
</div>