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

<div class="box">
    <div class="box-header">
        <h3 class="box-title">List Marker Entry</h3>
        <!-- <button type="submit" class="btn btn-primary" onclick="addNew_()"><i class="fa fa-plus"></i>&nbsp;New</button> -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal2" onclick="typeWS()"><i class="fa fa-plus"></i>&nbsp;New</button>
    </div>

    <div class="box-body">

        <!-- Modal -->
        <div class="modal fade" id="myModal2" role="dialog" aria-labelledby="myModalLabel">
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
                        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                        <button type="submit" onclick="saveWS()" class="btn btn-success">Save</button>
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