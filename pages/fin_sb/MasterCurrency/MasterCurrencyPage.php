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


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        		<div class="form-group" style="display:none">
			<label >Type : </label>
			<input class="form-control"  readonly type="text" value="USD" id="type">
		</div>	
		<div class="form-group" style="display:none">
			<label >ID : </label>
			<input class="form-control" readonly type="text" value="" id="id">
		</div>		
		
		
		<div class="form-group">
		<label >Currency : </label>
			<input onkeyup="handleKeyUp(this)" class="form-control" type="text" readonly value="USD" id="currency">
		</div>
		<div class="form-group">
		<label >Dari Tanggal: </label>
			<input onchange="handleKeyUp(this)" class="form-control" type="text" value=" " id="tanggal">
		</div>		
		<div class="form-group" id="labelTo">
		<label >Sampai Tanggal: </label>
			<!--<input onchange="handleKeyUp(this)" class="form-control" type="text" value=" " id="tanggalto"> -->
			<input readonly class="form-control" type="text" value=" " id="tanggalto">
		</div>		
		<div class="form-group formHarian" >
		<label >Rate Jual : </label>
			<input onkeyup="handleKeyUp(this);getRateTengah()"  class="form-control" type="text" value="0" id="ratejual">
		</div>		
		<div class="form-group" >
		<label >Rate  : </label>
			<input onkeyup="handleKeyUp(this)"   class="form-control" type="text" value=" " id="rate">
		</div>
		<div class="form-group formHarian" >
		<label >Rate Beli : </label> 
			<input onkeyup="handleKeyUp(this);getRateTengah()"  class="form-control" type="text" value="0" id="ratebeli">
		</div>
		
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="mySave" onClick="save()" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>


            <div class="panel with-nav-tabs panel-default">
                <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab1default" data-toggle="tab" onclick="GetContent('HARIAN')" >Kurs Harian</a></li>
                            <li><a href="#tab2default" data-toggle="tab" onclick="GetContent('COSTING3')" >Kurs Costing</a></li>
                            <li><a href="#tab3default" data-toggle="tab" onclick="GetContent('PAJAK')">Kurs Pajak</a></li>
   
                        </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1default">
							<div class="box-body" >
							<button class="btn btn-primary" onclick="add()" >Add New</button>
							</div>
							<div class="box list" >
							<img src="./img/25.gif" class="loading"  class="img-responsive loading center-block"width="">
							<div class="box-body" >
							
							<div class="box-body">  
								<table id="MasterCurrencyTable" class="display responsive" style="width:100%;font-size:12px;">
								<thead>
									<tr>
										<th style='display:none'>Id</th>
										<th>Tanggal</th>
										<th>Currency</th>   
										<th>Rate Jual</th>
										<th>Rate Tengah</th>  
										<th>Rate Beli</th>  
										<th width='14%'>Action</th>
									</tr>
								</thead>
							
								<tbody id='render'>
							
								</tbody>
								</table>
							</div>
								</div>
							</div>			
						</div>
                        <div class="tab-pane fade" id="tab2default">
							<div class="panel with-nav-tabs panel-info">
								<div class="panel-heading">
									<ul class="nav nav-tabs">
										<li class="active"><a href="#COSTING3DEFAULT" data-toggle="tab" onclick="GetContent('COSTING3')">3 Bulan</a></li>
										<li><a href="#COSTING6DEFAULT" data-toggle="tab" onclick="GetContent('COSTING6')" >6 Bulan</a></li>
										<li><a href="#COSTING8DEFAULT" data-toggle="tab" onclick="GetContent('COSTING8')">8 Bulan</a></li>
										<li><a href="#COSTING12DEFAULT" data-toggle="tab" onclick="GetContent('COSTING12')">12 Bulan</a></li>										
										<li><a href="#COSTINGALLDEFAULT" data-toggle="tab" onclick="GetContent('COSTINGALL')">ALL PERIODE</a></li>				
									</ul>
								</div>						
							</div>
							<!--<div class="box-body" >
							<button class="btn btn-primary" onclick="add('COSTING3')"  >Add New</button>
							</div>
							<div class="box list" >

							<img src="./img/25.gif"   class="img-responsive loading center-block"width="">
							<div class="box-body" >
							
							<div class="box-body">
							<div id="contentcosting3">

							</div>
							</div>
								</div>
							</div>	
-->						    <div class="panel-body">
								<div class="tab-content">
									<div class="tab-pane fade in active" id="COSTING3DEFAULT">	
										<div class="box-body" >
										<button class="btn btn-primary" onclick="add('COSTING3')"  >Add New</button>
										</div>
										<div class="box list" >
			
										<img src="./img/25.gif"   class="img-responsive loading center-block"width="">
										<div class="box-body" >
										
										<div class="box-body">
										<div id="contentcosting3">
			
											</div>
										</div>
											</div>
										</div>		
									</div>
									<div class="tab-pane fade " id="COSTING6DEFAULT">	
										<div class="box-body" >
										<button class="btn btn-primary" onclick="add('COSTING6')"  >Add New</button>
										</div>
										<div class="box list" >
			
										<img src="./img/25.gif"   class="img-responsive loading center-block"width="">
										<div class="box-body" >
										
										<div class="box-body">
										<div id="contentcosting6">
			
											</div>
										</div>
											</div>
										</div>
									</div>			
									<div class="tab-pane fade " id="COSTING8DEFAULT">	
										<div class="box-body" >
										<button class="btn btn-primary" onclick="add('COSTING8')"  >Add New</button>
										</div>
										<div class="box list" >
			
										<img src="./img/25.gif"   class="img-responsive loading center-block"width="">
										<div class="box-body" >
										
										<div class="box-body">
										<div id="contentcosting8">
			
											</div>
										</div>
											</div>
										</div>
									</div>									
									<div class="tab-pane fade " id="COSTING12DEFAULT">	
										<div class="box-body" >
										<button class="btn btn-primary" onclick="add('COSTING12')"  >Add New</button>
										</div>
										<div class="box list" >
			
										<img src="./img/25.gif"   class="img-responsive loading center-block"width="">
										<div class="box-body" >
										
										<div class="box-body">
										<div id="contentcosting12">
			
											</div>
										</div>
											</div>
										</div>
									</div>									
									<div class="tab-pane fade " id="COSTINGALLDEFAULT">	

										<div class="box list" >
			
										<img src="./img/25.gif"   class="img-responsive loading center-block"width="">
										<div class="box-body" >
										
										<div class="box-body">
										<div id="contentcostingall">
			
											</div>
										</div>
											</div>
										</div>
									</div>											
								</div>
							</div>	
						</div>
                        <div class="tab-pane fade" id="tab3default">
						
						
							<div class="box-body" >
							<button class="btn btn-primary" onclick="add('KURSPAJAK')"  >Add New</button>
							</div>
							<div class="box list" >

							<img src="./img/25.gif"   class="img-responsive loading center-block"width="">
							<div class="box-body" >
							
							<div class="box-body">
							<div id="contentpajak">

								</div>
							</div>
								</div>
							</div>		
						</div>
                    </div>
                </div>
            </div>









