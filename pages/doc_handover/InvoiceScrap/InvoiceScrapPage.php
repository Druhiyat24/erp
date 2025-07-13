<?php 
if (empty($_SESSION['username'])) { header("location:../../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];

?>

<div class="box">
    <div class="box-header">
        <h3 class="box-title">List Invoice Scrap</h3>&nbsp;
        <a href="?mod=InvocieScrapForm" class="btn btn-primary" target="_blank"><i class="fa fa-plus"></i>&nbsp;New</a>
    </div>
    <div class="box-body" style="font-size: 9pt;" >
        <table id="Inv_Scrap" class="table-bordered display responsive" style="width:100%">
            <thead>
                <tr>

                    <th>No Invoice#</th>
					<th>Buyer</th>
					<th>Create By</th>
					<th>Create Date</th>
					<th>Update By</th>
					<th>Last Update</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>