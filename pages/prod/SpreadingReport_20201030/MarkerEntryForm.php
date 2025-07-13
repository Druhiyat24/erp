<?php 
    if (empty($_SESSION['username'])) { header("location:../../../index.php"); }
?>

<style>
    th{
        text-align: center;
    }
</style>

<div class="box">
    <div class="box-body">
            
        <form action="" name="form" method="post">

            <div class="row">

                <div class="col-md-4">
                    <div class="form-group">
                        <div class="col-md-2">
                            <label>WS *</label>
                        </div>
                        <div class="col-md-10">
                            <!-- <select class="form-control select2" id="ws" style="width: 100%;" name="ws" onchange="getDetail(this.value)">
                                <option selected disabled>--Choose WS--</option>
                            </select> -->
                            <input type="text" class="form-control" name="ws" id="ws" style="width: 100%">
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <div class="col-md-2">
                            <label>Color</label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="color" id="color" style="width: 70%">
                        </div>
                    </div>
                </div>

            </div>
            <br>

            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn btn-primary" onclick="addTable()"><i class="fa fa-plus"></i>&nbsp;Add</button>
                </div>
            </div>
            <br><br>

            <div class="row">
                <div class="col-md-12">
                    <div id="tbl_dynamic"></div>
                </div>
            </div>
            <br><br>

            <!-- Modal -->
            <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                                    <!-- <div class="col-md-3">
                                        <label>S</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="number" name="s" id="s" class="form-control">
                                    </div>
                                    <br><br>
                                    <div class="col-md-3">
                                        <label>M</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="number" name="m" id="m" class="form-control">
                                    </div>
                                    <br><br>
                                    <div class="col-md-3">
                                        <label>L</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="number" name="l" id="l" class="form-control">
                                    </div>
                                    <br><br>
                                    <div class="col-md-3">
                                        <label>XL</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="number" name="xl" id="xl" class="form-control">
                                    </div> -->
                                </div>

                                <div class="row" style="margin-bottom: 5px;">
                                    <div class="col-md-3">
                                        <label>L. YDS</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="number" name="yds" id="yds" class="form-control">
                                    </div>
                                    <br><br>
                                    <div class="col-md-3">
                                        <label>L. Inch</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="number" name="inch" id="inch" class="form-control">
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 5px;">
                                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                    <button type="submit" onclick="saveDetail()" class="btn btn-success">Simpan</button>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <!-- <div class="col-md-12">
                                <div class="row">
                                     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" onclick="saveWS()" class="btn btn-success">Simpan</button>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <!-- <a type='submit' name='submit' onclick="save()" class='btn btn-success'>Simpan</a> -->
                    <!-- <button type="submit" class="btn btn-primary" name='cancel' onclick="Cancel()">Kembali</button> -->
                    <input type="button" value="Kembali" class="btn btn-warning" name="cancel" onclick="Cancel()">
                </div>
            </div>


            <!-- Button trigger modal
            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
            Launch demo modal
            </button> -->

            

            
        </form>
        
    </div>
</div>