<?php
include_once "../../../include/conn.php";

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
			  


              <div class="col-md-3">

                <div class='form-group'>

                  <label>Group Marketing</label>

                  <input type='text' id="g_m" class='form-control' >

                </div>

              </div>

	

              <div class="col-md-3">

                <div class='form-group'>

                  <label>Status</label>

                  <select id="status" class='form-control select2' >
					
				  

				  </select>

                </div>

              </div>
	
<div class="row">

</div>
           

                <div class="col-md-3">
                        <div class="form-group">
                            <a href="#" id="submit" class='btn btn-primary' onclick="getListData()"/>Show</a>
                        </div>
                    </div>

           

            </div>

          </div>

        </form>
<div class="box list"></div>
       <table rules="all" id="laporan" class="" style="width:100%;font-size:12px">
          <thead>
			<tr border=1>
				<th rowspan="4" style="text-align: center;background-color:#ffffff;border:1px solid #000000">WS#</th>
				<th rowspan="4" style="text-align: center;background-color:#ffffff;border:1px solid #000000">SO#</th>
				<th rowspan="4" style="text-align: center;background-color:#ffffff;border:1px solid #000000">Qty Order</th>	
				<th colspan="16" style="text-align: center;background-color:#ffffff;border:1px solid #000000">Cutting</th>
				<th colspan="18" style="text-align: center;background-color:#ffffff;border:1px solid #000000">Sewing</th>
				<th colspan="10" style="text-align: center;background-color:#ffffff;border:1px solid #000000">Finishing</th>
			
			</tr>
			<tr >
			<!-- Cutting Out -->
				<th colspan="6" style="text-align: center;background-color:#ffffff;border:1px solid #000000">Cutting</th>
				<th colspan="6" style="text-align: center;background-color:#ffffff;border:1px solid #000000">Secoundary</th>	
				<th colspan="4" style="text-align: center;background-color:#ffffff;border:1px solid #000000">Distributin Center</th>
			<!-- Sewing -->	
				<th colspan="4" style="text-align: center;background-color:#ffffff;border:1px solid #000000">Sewing</th>				
				<th colspan="4" style="text-align: center;background-color:#ffffff;border:1px solid #000000">Qc</th>	
				<th colspan="2" rowspan="2" style="text-align: center;background-color:#ffffff;border:1px solid #000000">Steam</th>
				<th colspan="4" style="text-align: center;background-color:#ffffff;border:1px solid #000000">Qc Final</th>

			<!-- Finishing -->	
				<th colspan="2" rowspan="2" style="text-align: center;background-color:#ffffff;border:1px solid #000000">Packing</th>
				<th colspan="2" rowspan="2" style="text-align: center;background-color:#ffffff;border:1px solid #000000">Metal Detector</th>
				<th colspan="2" rowspan="2" style="text-align: center;background-color:#ffffff;border:1px solid #000000">Qc Audit Buyer</th>
				<th colspan="2" rowspan="2" style="text-align: center;background-color:#ffffff;border:1px solid #000000">Finish Good</th>
				<th colspan="2" rowspan="2" style="text-align: center;background-color:#ffffff;border:1px solid #000000">Shipping</th>				
			</tr>
				
			<tr border=1>
				<th colspan="2" style="text-align: center;background-color:#ffffff;border:1px solid #000000">Cutting Output</th>
				<th colspan="2" style="text-align: center;background-color:#ffffff;border:1px solid #000000">  Numbering</th>
				<th colspan="2" style="text-align: center;background-color:#ffffff;border:1px solid #000000"> Qc</th>
				
				<th colspan="2" style="text-align: center;background-color:#ffffff;border:1px solid #000000">In</th>
				<th colspan="2" style="text-align: center;background-color:#ffffff;border:1px solid #000000">Out</th>
				<th colspan="2" style="text-align: center;background-color:#ffffff;border:1px solid #000000">Qc</th>				
				<th colspan="2" style="text-align: center;background-color:#ffffff;border:1px solid #000000">Join</th>
				<th colspan="2" style="text-align: center;background-color:#ffffff;border:1px solid #000000">Set</th>


				<th colspan="2" style="text-align: center;background-color:#ffffff;border:1px solid #000000">In</th>
				<th colspan="2" style="text-align: center;background-color:#ffffff;border:1px solid #000000">Out</th>
	


				<th colspan="2" style="text-align: center;background-color:#ffffff;border:1px solid #000000">In</th>
				<th colspan="2" style="text-align: center;background-color:#ffffff;border:1px solid #000000">Out</th>	
				

				<th colspan="2" style="text-align: center;background-color:#ffffff;border:1px solid #000000">In</th>
				<th colspan="2" style="text-align: center;background-color:#ffffff;border:1px solid #000000">Out</th>				
			</tr>		
			
			<tr border=1>
			<!-- Cutting Out -->
				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Qty</th>
				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Balance</th>
				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Qty</th>
				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Balance</th>
				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Qty</th>
				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Balance</th>
			<!-- Secoundary -->
				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Qty</th>
				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Balance</th>
				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Qty</th>
				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Balance</th>
				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Qty</th>
				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Balance</th>
				
				
				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Qty</th>
				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Balance</th>
				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Qty</th>
				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Balance</th>		
				<!-- Sewing -->
				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Qty</th>
				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Balance</th>	

				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Qty</th>
				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Balance</th>					
				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Qty</th>
				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Balance</th>	

				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Qty</th>
				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Balance</th>


				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Qty</th>
				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Balance</th>		


				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Qty</th>
				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Balance</th>	

				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Qty</th>
				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Balance</th>




				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Qty</th>
				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Balance</th>	

				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Qty</th>
				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Balance</th>


				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Qty</th>
				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Balance</th>		


				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Qty</th>
				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Balance</th>	

				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Qty</th>
				<th style="text-align: center;background-color:#ffffff;border:1px solid #000000">Balance</th>
				
			</tr>

        </thead>
      </table>

    </div>

  </div>

</section>

</div>


