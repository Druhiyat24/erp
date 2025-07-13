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
                <a type="submit" name='update' class="btn btn-primary refresh" onclick="Update()">Refresh</a>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-md-12">
                <table id="tbl_cut_in" class="table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Fabric Code</th>
                            <th>Material Color</th>
                            <th>Fabric Description</th>
                            <th>LOT No</th>
                            <!-- <th>LOT QTY</th> -->
                            <th>QTY Received</th>
                            <th>Return</th>
                            <th>Unit</th>
                            <th>Action</th>
                            <th>Remark</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <br>

        <div class="row">
            <div class='col-md-3'>
                <!-- <a type='submit' name='submit' onclick="Save()" class='btn btn-primary simpan'>Simpan</a>
                <a type='submit' name='cancel' onclick="Cancel()" class='btn btn-primary'>Kembali</a> -->

                <button type="submit" class="btn btn-primary simpan" name='submit' onclick="Save()">Simpan</button>
                <button type="submit" class="btn btn-primary" name='cancel' onclick="Cancel()">Kembali</button>
            </div>
        </div>
    </div>
</div>