<?php 
    if (empty($_SESSION['username'])) { header("location:../../../index.php"); }
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
                    <th style="text-align: center">No Sewing In</th>
                    <th style="text-align: center">Buyer</th>
                    <th style="text-align: center">Style</th>
                    <th style="text-align: center">WS#</th>
					<th style="text-align: center">SO#</th>
					<th style="text-align: center">Buyer PO#</th>
					<th style="text-align: center">Output Date#</th>
					<th style="text-align: center">Output Time</th>				
                    <th style="text-align: center">Created By</th>
                    <th style="text-align: center">Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>