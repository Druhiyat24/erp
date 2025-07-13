<style>
    .form{
        width: 100% !important;
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
        <div class="row">

            <!-- <div class="col-md-6">
                <div class="col-md-4">
                    <label>Cutting Output Date</label>
                </div>
                <div class="col-md-8">
                    <input type="text" id="date" class="form-control form" value="<?php echo date('j M Y'); ?>" readonly>
                </div>

                <div class="col-md-4">
                    <label>WS #</label>
                </div>
                <div class="col-md-8">
                    <select id="ws" class="form-control select2 form" onchange="getDetail(this.value)" tabindex="-1" aria-hidden="true">
                        <option value="">--Choose WS--</option>
                    </select>
                </div>
            </div> -->

            <div class="col-md-12">
                <div class="col-md-4">
                    <div class="col-md-4">
                        <label>Cut Out Date</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" id="date" class="form-control form" value="<?php echo date('j M Y'); ?>" readonly style="height: auto;">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-2">
                        <label>WS#</label>
                    </div>
                    <div class="col-md-10">
                        <select id="ws" class="form-control select2 form" onchange="getColor(this.value)" tabindex="-1" aria-hidden="true">
                            <!-- <option value="">--Choose WS--</option> -->
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-2">
                        <label>Color</label>
                    </div>
                    <div class="col-md-10">
                        <select id="color" class="form-control select2 form" onchange="getDetail(this.value)" tabindex="-1" aria-hidden="true">
                            <option selected disabled>--Choose Color--</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-md-12">
                <table id="tbl_cut_out1" class="table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Fabric Code</th>
                            <th>Fabric Description</th>
                            <th>Grouping</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <br>
        <br>
        <div class="row">
            <div class="col-md-12"> 
                <table id="tbl_cut_out2" class="table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th rowspan="2" style="text-align: center;background-color:#FFFFFF">No</th>
                            <!-- <th rowspan="2" style="text-align: center;background-color:#FFFFFF">Fabric Code</th> -->
                            <th rowspan="2" style="text-align: center;background-color:#FFFFFF">Fabric Description</th>
                            <th rowspan="2" style="text-align: center;background-color:#FFFFFF">Grouping</th>
                            <th rowspan="2" style="text-align: center;background-color:#FFFFFF">Panel</th>
                            <th rowspan="2" style="text-align: center;background-color:#FFFFFF">LOT</th>
                            <th rowspan="2" style="text-align: center;background-color:#FFFFFF">Size</th>
                            <th rowspan="2" style="text-align: center;background-color:#FFFFFF">Qty SO</th>
                            <th rowspan="2" style="text-align: center;background-color:#FFFFFF">Qty Cumulative</th>
                            <th rowspan="2" style="text-align: center;background-color:#FFFFFF">Balance</th>
                            <th colspan="3" style="text-align: center">Cutting Output Update/pcs</th>
                        </tr>
                        <tr> 
                            <th style="text-align: center;background-color:#FFFFFF">Cutting</th>
                            <th style="text-align: center;background-color:#FFFFFF">Reject</th>
                            <th style="text-align: center;background-color:#FFFFFF">&nbsp;Qty Good&nbsp;</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <br>

        <div class="row">
            <div class='col-md-3'>
                <!-- <a type='submit' name='submit' onClick="Save()" class='btn btn-primary simpan'>Simpan</a>
                <a type='submit' name='submit' onClick="Cancel()" class='btn btn-primary batal'>Kembali</a> -->

                <button type="submit" class="btn btn-primary simpan" name='submit' onclick="Save()">Save</button>
                <button type="submit" class="btn btn-warning" name='cancel' onclick="Cancel()">Back</button>
            </div>
        </div>
    </div>
</div>