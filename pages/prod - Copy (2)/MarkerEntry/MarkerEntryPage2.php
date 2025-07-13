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
        <h3 class="box-title">List Marker Entry WS/Color</h3>
        <!-- <button type="submit" class="btn btn-primary" onclick="addNew_()"><i class="fa fa-plus"></i>&nbsp;New</button> -->
        <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal2" onclick="typeWS()"><i class="fa fa-plus"></i>&nbsp;New</button> -->
    </div>

    <div class="box-body">

        <table id="item" class="table-bordered table-striped" style="width: 100%">
            <thead>
                <tr>
                    <th style="text-align: center">No</th>
                    <th style="text-align: center">WS#</th>
                    <th style="text-align: center">Color</th>
                    <th style="text-align: center">Action</th>
                </tr>
            </thead>
        </table>

        <div class="row">
            <div class="col-md-3">
                <!-- <a type='submit' name='submit' onclick="save()" class='btn btn-success'>Simpan</a> -->
                <!-- <button type="submit" class="btn btn-primary" name='cancel' onclick="Cancel()">Kembali</button> -->
                <input type="button" value="Back" class="btn btn-warning" name="cancel" onclick="back()">
            </div>
        </div>

    </div>
</div>