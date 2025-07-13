<div class="box">
    <div class="box-header">
        <h3 class="box-title">List Spreading Report</h3>
    </div>

    <div class="box-body">

        <!-- Modal -->
        <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Add WS</h4>
                    </div>
                    <div class="modal-body">
                        <select class="form-control select2" id="ws" style="width: 100%;z-index: 9000 !important;" name="ws">
                            <!-- <option selected disabled>--Choose WS--</option> -->
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" onclick="saveWS()" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        <table id="item" class="table-bordered table-striped" style="width: 100%">
            <thead>
                <tr>
                    <th style="text-align: center">No</th>
                    <th style="text-align: center">Buyer</th>
                    <th style="text-align: center">WS#</th>
                    <th style="text-align: center">Style</th>
                    <th style="text-align: center">Created By</th>
                    <th style="text-align: center">Action</th>
                </tr>
            </thead>
        </table>

    </div>
</div>