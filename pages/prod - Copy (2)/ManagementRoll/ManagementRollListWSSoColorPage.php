<?php 
if (empty($_SESSION['username'])) { header("location:../../../index.php"); }

?>

<div class="box">
    <div class="box-header">
        <h3 class="box-title">List Management Roll</h3>&nbsp;
        <!-- <a href="?mod=2LA" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;New</a> -->
       <!-- <button type="submit" class="btn btn-primary" onclick="addNew_()"><i class="fa fa-plus"></i>&nbsp;New</button>-->
    </div>
    <div class="box-body" style="font-size: 9pt;" >
        <table id="MRoll" class="table-bordered table-striped" style="width:100%">
            <thead>
                <tr>
                    <th style="text-align: center">No</th>
		            <th style="text-align: center">WS #</th>
					<th style="text-align: center">SO #</th>
                    <th style="text-align: center">Style #</th>
					<th style="text-align: center">Color #</th>
					<th style="text-align: center">Item</th>
                    <!-- <th style="text-align: center">No Request #</th>  -->
                    <th style="text-align: center">Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>