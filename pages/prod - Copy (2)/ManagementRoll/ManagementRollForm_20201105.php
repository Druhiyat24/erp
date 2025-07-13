<?php 
if (empty($_SESSION['username'])) { header("location:../../../index.php"); }

?>

<style>
    .form{
        width: 65% !important;
        margin-bottom: 5px;
        height: 27px !important;
    }
    .form2{
        width: 50% !important;
        margin-bottom: 5px;
        height: 27px !important;
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
                <div class="row">
                    <div class="col-md-2">
                        <label for="">WS</label>
                    </div> 
                    <div class="col-md-10">
                        <select id="ws" class="form-control select2 form2" onchange="getStyle(this.value);getDetailWS(this.value)" tabindex="-1" aria-hidden="true">
                            <!-- <option value="">--Choose WS--</option> -->
                        </select>
                    </div>
 
                    <div class="col-md-2">
                        <label for="">Style</label>
                    </div>
                    <div class="col-md-10">
                        <input type="text" class="form-control form2" name="style" id="style" readonly placeholder="style">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="col-md-4">
                    <label>Tanggal Cutting Input</label>
                </div>
                <div class="col-md-8">
                    <input type="text" id="dtpicker" class="form-control form" value="<?php echo date('j M Y'); ?>" readonly>
                </div>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-md-12">
                <table id="tbl_mroll" class="table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th style="text-align: center; background-color:#ffffff; vertical-align: middle">No</th>
                            <!-- <th style="text-align: center; background-color:#ffffff; vertical-align: middle">Fabric Code</th>
                            <th style="text-align: center; background-color:#ffffff; vertical-align: middle">Fabric Name</th>
                            <th style="text-align: center; background-color:#ffffff; vertical-align: middle">Fabric Color</th> -->
                            <th style="text-align: center; background-color:#ffffff; vertical-align: middle">LOT</th>
                            <th style="text-align: center; background-color:#ffffff; vertical-align: middle">Roll Number</th>
                            <th style="text-align: center; background-color:#ffffff; vertical-align: middle">QTY Sticker</th>
                            <th>Fabric Used(Kg)</th>
                            <th>Qty Cutting(Pcs)</th>
                            <th>Cons WS(Kg)</th>
                            <th>Cons m(Kg)</th>
                            <th>Cons Act pcs(Kg)</th>
                            <th>Balance Cons</th>
                            <th>Percentage</th>
                            <th>Binding</th>
                            <th>Actual Balance</th>
                            <th>Actual Total</th>
                            <th>Short Roll/Bal</th>
                            <th>Spread Sheet</th>
                            <th>Ratio</th>
                            <th>Qty Pcs</th>
                            <th>P. Markers</th>
                            <th>Efficiency</th>
                            <th>Fabric Total Act</th>
                            <th>Majun(%)</th>
                            <th>Majun(Kg)</th>
                            <th>1 Ampar</th>
                            <th>Total</th>
                            <th>Used Total</th>
                            <th>L. Fabric</th>
                            <!-- <th>User</th> -->
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

                <button type="submit" class="btn btn-success simpan" name='submit' onclick="Save()">Simpan</button>
                <button type="submit" class="btn btn-warning" name='cancel' onclick="Cancel()">Kembali</button>
            </div>
        </div>
    </div>
</div>