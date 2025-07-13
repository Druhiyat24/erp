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
        <h3 class="box-title">List Sewing Input</h3>
        <button type="submit" class="btn btn-primary" onclick="addNew_()"><i class="fa fa-plus"></i>&nbsp;New</button>
    </div>

    <div class="box-body">
        <table id="sewing" class="table-bordered table-striped" style="width: 100%">
            <thead>
                <tr>
                    <th style="text-align: center">No Sewing Output#</th>
                    <th style="text-align: center">No Qc Input#</th>
					<th style="text-align: center">WS#</th>
                    <th style="text-align: center">Style</th>
                    <th style="text-align: center">Created By</th>
                    <th style="text-align: center">Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>