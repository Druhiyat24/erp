<?php 
if (empty($_SESSION['username'])) { header("location:../../../index.php"); }

?>

<style>
    .form{
        width: 65% !important;
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
</style>
<div class="box">
    <div class="box-body">
        <div class="row">

            <div class="col-md-6">
                <div class="col-md-3">
                    <label>Tanggal Input</label>
                </div>
                <div class="col-md-9">
                    <input type="text" id="dtpicker" class="form-control form" value="<?php echo date('j M Y'); ?>" readonly>
                </div>

                <div class="col-md-3">
                    <label>WS #</label>
                </div>
                <div class="col-md-9">
                    <select id="ws" class="form-control select2 form" onchange="getDetail(this.value)" tabindex="-1" aria-hidden="true">
                        <!-- <option value="">--Choose WS--</option> -->
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <a type="submit" name='update' class="btn btn-primary" onclick="Update()">Refresh</a>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-md-12">
                <table id="tbl_cut_in" class="table-bordered display responsive" style="width:100%">
                    <thead>
                        <tr>
                            <th rowspan="2">Fabric Code</th>
                            <th rowspan="2">Material Color</th>
                            <th rowspan="2">Fabric Description</th>
                            <th colspan="4">Yard</th>
                            <th colspan="4">Roll</th>
                        </tr>
                        <tr>
                            <th>Qty Needs</th>
                            <th>Qty Received</th>
                            <th>Balance Fabric</th>
                            <th>Unit</th>
                            <th>Qty Needs</th>
                            <th>Qty Received</th>
                            <th>Balance Fabric</th>
                            <th>Unit</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <br>

        <div class="row">
            <div class='col-md-3'>
                <a type='submit' name='submit' onclick="Save()" class='btn btn-primary'>Simpan</a>
                <a type='submit' name='cancel' onclick="Cancel()" class='btn btn-primary'>Kembali</a>
            </div>
        </div>
    </div>
</div>