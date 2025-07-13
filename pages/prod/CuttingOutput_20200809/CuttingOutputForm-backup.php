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
    #tbl_cut_in{
        /* display: none; */
    }
</style>
<div class="box">
    <div class="box-body">
        <div class="row">

            <div class="col-md-6">
                <div class="col-md-4">
                    <label>Tanggal Cutting Output</label>
                </div>
                <div class="col-md-8">
                    <input type="text" id="dtpicker" class="form-control form" value="<?php echo date('j M Y'); ?>" readonly>
                </div>

                <div class="col-md-4">
                    <label>WS #</label>
                </div>
                <div class="col-md-8">
                    <select id="ws" class="form-control select2 form" onchange="getDetail(this.value)" tabindex="-1" aria-hidden="true">
                        <!-- <option value="">--Choose WS--</option> -->
                    </select>
                </div>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-md-12">
                <table id="tbl_cut_out1" class="table-bordered display responsive" style="width:100%">
                    <thead>
                        <tr>
                            <th>Fabric Code</th>
                            <th>Fabric Description</th>
                            <th>Grouping</th>
                            <th>Panel</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-md-12"> 
                <table id="tbl_cut_out2" class="table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <!-- <th rowspan="2" style="text-align: center; visibility: hidden">No</th> -->
                            <th rowspan="2" style="text-align: center">Fabric Code</th>
                            <th rowspan="2" style="text-align: center">Fabric Description</th>
                            <th rowspan="2" style="text-align: center">Grouping</th>
                            <th rowspan="2" style="text-align: center">Panel</th>
                            <th colspan="2" style="text-align: center">Numbering</th>
                            <th colspan="3" style="text-align: center">Secondary/pcs</th>
                            <th rowspan="2" style="text-align: center">Size</th>
                            <th colspan="4" style="text-align: center">Cutting Output Update/pcs</th>
                            <!-- <th rowspan="2" style="text-align: center">Action</th> -->
                        </tr>
                        <tr>
                            <th style="text-align: center">From</th>
                            <th style="text-align: center">&nbsp;To&nbsp;</th>
                            <th style="text-align: center">Embro</th>
                            <th style="text-align: center">Printing</th>
                            <th style="text-align: center">Heat Transfer</th>
                            <th style="text-align: center">Date</th>
                            <th style="text-align: center">Cutting</th>
                            <th style="text-align: center">Reject</th>
                            <th style="text-align: center">&nbsp;OK Cutt&nbsp;</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <br>

        <div class="row">
            <div class='col-md-3'>
                <a type='submit' name='submit' onClick="Save()" class='btn btn-primary simpan'>Simpan</a>
                <a type='submit' name='submit' onClick="Cancel()" class='btn btn-primary batal'>Kembali</a>
            </div>
        </div>
    </div>
</div>