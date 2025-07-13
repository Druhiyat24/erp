<style>
    th{
        text-align: center;
    }

    .lbl{
        -webkit-text-decoration-line: underline;
        text-decoration-line: underline;
    }

    #marker {
        min-width: -webkit-fill-available;
        table-layout: fixed;
        *margin-left: -150px;
    }
    #marker td, #marker th {
        padding: 10px;
        height: 50px;
    }
    .fix {
        position: absolute;
        *position: relative;
        margin-left: -150px;
        min-width: 150px;
    }
    /* .outer {
        position: relative;
    } */
    .inner {
        overflow-x: scroll;
        overflow-y: visible;
        min-width: 50%;
        margin-left: 150px;
    }
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>

<div class="box">
    <div class="box-body">
            
        <form action="" name="form" method="post">

            <div class="row">

                <div class="col-md-12">    
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="col-md-2">
                                <label>WS *</label>
                            </div>
                            <div class="col-md-10">
                                <!-- <select class="form-control select2" id="ws" style="width: 100%;" name="ws" onchange="getDetail(this.value)">
                                    <option selected disabled>--Choose WS--</option>
                                </select> -->
                                <input type="text" class="form-control" name="ws" id="ws" style="width: 100%" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="col-md-2">
                                <label>Color</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="form-control" name="color" id="color" style="width: 100%">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="col-md-2">
                                <label for="">Panel</label>
                            </div>
                            <div class="col-md-10">
                                <select class="form-control select2" id="panel" name="panel" style="width: 100%" onchange="newPanel(this.value)">
                                <!-- <option selected disabled>--Choose Panel--</option> -->
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <br>

            <div class="row">

                <div class="col-md-12">
                    <!-- <table id="item_item" class="table-bordered table-striped" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="text-align: center">No</th>
						        <th style="text-align: center">Fabric Code</th>
						    	<th style="text-align: center">Fabric Name</th>
						        <th style="text-align: center">Action</th>
                            </tr>
                        </thead>
                    </table> -->
                    <div class="col-md-8" id="item-select" style="display: none">
                        <div class="form-group">
                            <div class="col-md-1">
                                <label for="">Item</label>
                            </div>
                            <div class="col-md-11">
                                <select class="form-control select2" id="item" name="item" style="width: 100%">
                                <!-- <option selected disabled>--Choose Panel--</option> -->
                                </select>
                            </div>
                        </div>
                    </div>
                </div>                

            </div>

            <br>

            <div class="row">

                <div class="col-md-12">
                    <div>
                        <!-- <button onclick="tambahTab('Color')" class="btn btn-primary">New Tab&nbsp;<i class="fa fa-plus"></i></button> -->
                        <div class="col-md-12" style="margin-bottom: 10px;">
                            <div class="col-md-1">
                                <input type="button" value="Add Panel" class="btn btn-primary" id="bcol" onclick="saveTab()">
                            </div>
                            <div class="col-md-1">
                                <input type="button" value="Save" class="btn btn-primary"  name="save" onclick="save_all()">
                            </div>
                            <div class="col-md-1" style="margin-right: -30px;">
                                <input type="button" value="Back" class="btn btn-warning" name="cancel" onclick="Cancel()">
                            </div>
                        </div>
                        <br>

                        <div class="col-md-12">
                        
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs mytabs" role="tablist">
                                <!-- <li role="presentation">
                                    <a href="#basic" aria-controls="basic" role="tab" data-toggle="tab">Main Color</a>
                                </li> -->
                            </ul>
                            <br>

                            <!-- Tab panes -->
                            <div class="tab-content mytabsContent">
                                <!-- <div role="tabpanel" class="tab-pane active" id="basic">
                                    <div class="col-md-12">
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-primary" onclick="addTable()"><i class="fa fa-plus"></i>&nbsp;Add</button>
                                        </div>
                                        <div class="col-md-4">
                                            
                                        </div>
                                    </div>
                                    <br><br>
                                    <div id="tbl_dynamic"></div>
                                </div> -->
                                <!-- <br> -->
                                <!-- <br> -->
                                <!-- <div class="row">
                                    <div class="col-md-12">
                                    </div>
                                </div> -->
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <br><br>

            
            <!-- <br><br> -->

            <!-- Modal Ke-1 -->
            <!-- <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="col-md-12">
                                <div class="row">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Add Ratio</h4>
                                </div>
                            </div>
                        </div>

                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="row" style="margin-bottom: 5px;">
                                    <div class="col-md-3">
                                        <label>Spread</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="number" name="spread" id="spread" class="form-control">
                                    </div>
                                </div>
                                
                                <div class="row" id="fld_dynamic" style="margin-bottom: 5px;">
                                    
                                </div>

                                <div class="row" style="margin-bottom: 5px;">
                                    <div class="col-md-3">
                                        <label>Length YDS</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="number" name="yds" id="yds" class="form-control">
                                    </div>
                                    <br><br>
                                    <div class="col-md-3">
                                        <label>Length Inch</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="number" name="inch" id="inch" class="form-control">
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 5px;">
                                    <button type="submit" onclick="saveDetail()" class="btn btn-success">Simpan</button>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- End Modal Ke-1 -->

            <!-- Modal Ke-2 -->
            <!-- <div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="col-md-12">
                                <div class="row">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Input</h4>
                                </div>
                            </div>
                        </div>

                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="row" style="margin-bottom: 5px;">
                                    <div class="col-md-3">
                                        <label>GSM</label>
                                    </div>
                                    <div class="col-md-9" style="margin-bottom: 5px;">
                                        <input type="number" name="gsm" id="gsm" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Width</label>
                                    </div>
                                    <div class="col-md-9" style="margin-bottom: 5px;">
                                        <input type="number" name="width" id="width" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label>B-Cons/Kg</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" name="bcg" id="bcg" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <div class="col-md-12">
                                <div class="row">
                                     <button type="submit" id="sue" onclick="saveCons()" class="btn btn-success">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- End Modal Ke-2 -->

            <!-- Modal Ke-3 -->
            <div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
            
        </form>
        
    </div>
</div>