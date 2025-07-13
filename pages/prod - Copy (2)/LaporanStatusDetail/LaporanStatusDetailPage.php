<?php
include_once "../../include/conn.php"; //tambah ../ di erpdev

if (empty($_SESSION['username'])) { header("location:../../../index.php"); }
$user=$_SESSION['username'];

$sesi=$_SESSION['sesi'];

# CEK HAK AKSES KEMBALI

//$akses = flookup("act_cost","userpassword","username='$user'");

?>
<style>
#laporan tbody > tr > td {
white-space: nowrap;
height :10px !important;
}
.no-footer{
	 border-bottom: none !important;
}
.dt-buttons{
	display:"block" !important;
}


th {
    padding-top: 0 !important;
	padding-bottom: 0 !important;
	
}
</style>
<div class="container-fluid">

  <section class="content">

    <div class="box">

      <div class="box-body">

        <form   enctype='multipart/form-data' >

          <div class="panel panel-default">
            <div class="panel-heading">Filter</div>
            <div class="panel-body row">
              <div class="col-md-3">
                <div class='form-group'>

                  <label>Filter By</label>

                  <select id="src" class='form-control select2' >
						<option>Delivery Date</option>
				  </select>

                </div>

              </div>			
			

              <div class="col-md-3">

                <div class='form-group'>

                  <label for="period_from">From </label>



                  <input type='text' id="from" class='form-control datepicker' name='period_from'

                  placeholder='DD/MM/YYYY'  autocomplete="off" >

                </div>

              </div>

              <div class="col-md-3">

                <div class='form-group'>

                  <label>To </label>

                  <input type='text' id="to" class='form-control datepicker' name='period_to'

                  placeholder='DD/MM/YYYY' 
                   
                  autocomplete="off" >

                </div>

              </div>


              <div class="col-md-3">

                <div class='form-group'>

                  <label>Buyer Name</label>

                  <select id="buyer" class='form-control select2' >
					
				  

				  </select>

                </div>

              </div>
			  
<div class="row">

</div>
           

                <div class="col-md-3">
                        <div class="form-group">
                            <a href="#" id="submit" class='btn btn-primary' onclick="getListData()"/>Show</a>
                            <a href="#" id="submit" class='btn btn-info' onclick="Back()"/>Back</a>
                        </div>
                    </div>

           

            </div>

          </div>

        </form>
<div class="box list"></div>
<table id="laporan" style="width: 100%; font-size: 12px;" rules="all">
<thead>
<tr>
<th style="text-align: center; background-color: #ffffff; border: 1px solid #000000;">WS#</th>
  <th style="text-align: center; background-color: #ffffff; border: 1px solid #000000;">Output Date</th>
  <th style="text-align: center; background-color: #ffffff; border: 1px solid #000000;">Process No.</th>
  <th style="text-align: center; background-color: #ffffff; border: 1px solid #000000;">Process</th>
  <th style="text-align: center; background-color: #ffffff; border: 1px solid #000000;">PO Buyer</th>
  <th style="text-align: center; background-color: #ffffff; border: 1px solid #000000;">Destination</th>
  <th style="text-align: center; background-color: #ffffff; border: 1px solid #000000;">Color</th>
  <th style="text-align: center; background-color: #ffffff; border: 1px solid #000000;">Size</th>
  <th style="text-align: center; background-color: #ffffff; border: 1px solid #000000;">Qty SO</th>
  <th style="text-align: center; background-color: #ffffff; border: 1px solid #000000;">Unit</th>
  <th style="text-align: center; background-color: #ffffff; border: 1px solid #000000;">Qty</th>
  <th style="text-align: center; background-color: #ffffff; border: 1px solid #000000;">Balance</th>

</tr>
</thead>
</table>


    </div>

  </div>

</section>

</div>


