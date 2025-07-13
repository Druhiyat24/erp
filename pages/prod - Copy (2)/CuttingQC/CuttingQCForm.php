<?php 
if (empty($_SESSION['username'])) { header("location:../../../index.php"); }

?>

<style>
    .form{
        width: 85% !important;
        margin-bottom: 5px;
        height: 27px;
    }
    .select2{
        /* height: 27px !important; */
        margin-bottom: 5px;
    }
    th{
        text-align: center;
    }
    .forms{
        text-align: right;
    }
</style>
<div class="box">
    <div class="box-body">
        <div class="row">

            <div class="col-md-4">
                <div class="col-md-2">
                    <label>WS #</label>
                </div>
                <div class="col-md-10">
                    <select id="ws" class="form-control select2 form" onchange="getDetail(this.value)" tabindex="-1" aria-hidden="true">
                        <!-- <option value="">--Choose WS--</option> -->
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="col-md-3">
                    <label>QC Date</label>
                </div>
                <div class="col-md-9">
                    <input type="text" id="date" class="form-control form" value="<?php echo date('j M Y'); ?>" readonly>
                </div>
            </div>

            <div class="col-md-4">
                <div class="col-md-3">
                    <label>Cutting QC</label>
                </div>
                <div class="col-md-9">
                    <input type="text" id="cutQC" class="form-control form" value="" readonly>
                </div>
            </div>

        </div>
        <br>

        <div class="row">
            <div class='col-md-3'>
                <button type="submit" class="btn btn-primary simpan" name='submit' onclick="Save()">Save</button>
                <button type="submit" class="btn btn-warning" name='cancel' onclick="Cancel()">Back</button>
            </div>
        </div>
    </div>
</div>

<div class="box boxxer">
    <div class="box-body">
        <div class="row">

            <div class="col-md-12">
                <table id="tbl_cut_qc" class="table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th style="text-align: center;background-color:#FFFFFF">No</th>
                            <th style="text-align: center;background-color:#FFFFFF; min-width: 150px !important;">Product Description</th>
                            <th style="text-align: center;background-color:#FFFFFF">Color</th>
                            <th style="text-align: center;background-color:#FFFFFF">Size</th>
                            <th style="text-align: center;background-color:#FFFFFF; min-width: 50px !important;">Shell</th>
                            <th style="text-align: center;background-color:#FFFFFF">Panel</th>
                            <th style="text-align: center;background-color:#FFFFFF">LOT</th>
                            <th style="text-align: center;background-color:#FFFFFF">Qty SO</th>
                            <th style="text-align: center;background-color:#FFFFFF">Qty Cumulative</th>
                            <th style="text-align: center;background-color:#FFFFFF">Balance</th>
                            <th style="text-align: center;background-color:#FFFFFF">Cutting Number</th>
                            <th style="text-align: center;background-color:#FFFFFF">Bundle Number</th>
                            <th style="text-align: center;background-color:#FFFFFF">Sack Number</th>
                            <th style="text-align: center;background-color:#FFFFFF">Qty Input</th>
                            <th style="text-align: center;background-color:#FFFFFF">Qty Reject QC</th>
                            <th style="text-align: center;background-color:#FFFFFF">Qty QC</th>
                            <th style="text-align: center;background-color:#FFFFFF">Remarks</th>
                            <th style="text-align: center;background-color:#FFFFFF">Approve</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>
</div>