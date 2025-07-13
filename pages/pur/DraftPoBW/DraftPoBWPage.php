<?php 
if (empty($_SESSION['username'])) { header("location:../../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];

?>

<div class="box">
    <div class="box-header">
        <h3 class="box-title">Draft PO</h3>&nbsp;
        <a href="?mod=draft_po_bW_form" class="btn btn-primary" target="_blank"><i class="fa fa-plus"></i>&nbsp;New</a>
    </div>
    <div class="box-body" style="font-size: 9pt;" >
        <table id="list_po" class="table-bordered display responsive" style="width:100%">
            <thead>
                <tr>
                    <th>No Draft#</th>
					<th>Draft Date</th>
					<th>Supplier</th>
					<th>P Terms</th>
					<th>Buyer</th>
					<th>WS</th>
					<th>Style</th>
                    <th>Notes</th>
					<th>Kurs</th>
					<th>Status</th>
					<th>Approved By</th>
					<th>Approval Date</th>
					<th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>