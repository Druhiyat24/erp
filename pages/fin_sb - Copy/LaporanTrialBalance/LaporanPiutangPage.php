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


<div class="box type" >
<div id="formBs" class='box'>
    <div class='box-body'>
            <div class="panel panel-default">
                <div class="panel-heading">Periode Akuntansi</div>
                <div class="panel-body row">
                    <div class="col-md-3">
                        <div class='form-group'>
                            <label>Dari</label>

                            <input type='text' id='period_from' class='form-control monthpicker' autocomplete="off" name='period_from'
                                   placeholder='MM/YYYY'  required>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class='form-group'>
                            <label>Sampai</label>

                            <input type='text' id='period_to' autocomplete="off" class='form-control monthpicker' name='period_to'
                                   placeholder='MM/YYYY'  required>

                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="hidden" name="mod" value="reptb" />
                            <a href="#" id="submit" class='btn btn-primary' onclick="getListData()" />Tampilkan</a>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>


</div>



<div class="box list" style="display:none"> 
<a href="#" id="submit" class='btn btn-primary' onclick="back()" />Back</a>
<div id="formBs" class='box'>
    <div class='box-body'>
<div class="box-body" >

<table id="trial_balance" class="table table-condensed table-bordered" style="width:100%">
        <thead>
        <tr>
            <th rowspan="2">&nbsp;</th>
            <th rowspan="2" align="center">COA ID</th>
            <th rowspan="2" align="center">ACCOUNT</th>
            <th colspan="2" align="center">BEGINNING BALANCE</th>
            <th colspan="2" align="center">MUTATION</th>
            <th colspan="2" align="center">ENDING BALANCE</th>
        </tr>
        <tr>

            <th>DEBIT</th>
            <th>CREDIT</th>
            <th>DEBIT</th>
            <th>CREDIT</th>
            <th>DEBIT</th>
            <th>CREDIT</th>
        </tr>
        </thead>
        <tfoot>
            <tr>
			<th >&nbsp;</th>
			<th >&nbsp;</th>	
			<th >&nbsp;</th>			
            <th>DEBIT</th>
            <th>CREDIT</th>
            <th>DEBIT</th>
            <th>CREDIT</th>
            <th>DEBIT</th>
            <th>CREDIT</th>
            </tr>
        </tfoot>
    </table>

	</div>

    </div>
</div>


</div>


