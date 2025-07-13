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
        <h3 class="box-title">Sprearing Number List</h3>
        <!-- <button type="submit" class="btn btn-primary" onclick="addNew_()"><i class="fa fa-plus"></i>&nbsp;New</button> -->
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal2" onclick="add_nomor_spreading('1','X','X')"><i class="fa fa-plus"></i>&nbsp;Add Nomor</button>
    </div>

    <div class="box-body">

        <!-- Modal -->
        <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Add Nomor Spreading</h4>
                    </div>
                    <div class="modal-body">
						<label>WS#</label>
					
						<input type="text" class="form-control" id="modals_ws" disabled placeholder="WS#"> 
						
						<label>Nomor Spreading</label>
					
						<input type="text" class="form-control" id="nomor_spreading_internal" placeholder="Masukkan Nomor Spreading"> 
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                        <button type="submit" onclick="saveNomor()" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        <table id="item" class="table-bordered table-striped" style="width: 100%">
            <thead>
                <tr>
                    <th style="text-align: center">WS#</th>
                    <th style="text-align: center">Nomor Spreading#</th>
                    <th style="text-align: center">Action</th>
                </tr>
            </thead>
        </table>

        <div class="row">
            <div class="col-md-3">
                <!-- <a type='submit' name='submit' onclick="save()" class='btn btn-success'>Simpan</a> -->
                <!-- <button type="submit" class="btn btn-primary" name='cancel' onclick="Cancel()">Kembali</button> -->
                <input type="button" value="Kembali" class="btn btn-warning" name="cancel" onclick="back()">
            </div>
        </div>

    </div>
</div>