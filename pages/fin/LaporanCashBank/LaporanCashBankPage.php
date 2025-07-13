<?php 
if (empty($_SESSION['username'])) { header("location:../../../index.php"); }


?>


<div class="box list" id="abcd" >
<div class="box-body" >
  <div class="box-body">
  
  <div class="col-md-3">
	                    <div class='form-group'>
                        <label>From</label>
                        <input type='text' id='fromdate' class='form-control' name='period' autocomplete="off"
                               placeholder= 'From MM/YYYY' value='<?=isset($row)?$row->period:''?>'>

                    </div>
					
		
	</div>				
  <div class="col-md-3">
	                    <div class='form-group'>
                        <label>To</label>
                        <input type='text' id='todate' class='form-control' name='period' autocomplete="off"
                               placeholder= 'From MM/YYYY' value='<?=isset($row)?$row->period:''?>'>

                    </div>
	</div>
	<div class="col-md-3">
	                    <div class='form-group'>
                        <label>Nomor Akun</label>
                  <select id='idcoa' name="idcoa" class='form-control' onchange="getidcashbank(this.value)" >
					<option>--Select--</option>
                  </select>

                    </div>
	</div>		
      <div class="col-md-12">

        <button type="button" id="search" onClick="getLaporan()" class="btn btn-primary">Submit</button>
		<img src="./img/25.gif" id="loading" style="display:none" class="img-responsive loading"width="4%">
      </div>			
  </div>

	</div>




</div>


<div class="box list" id="uijurnal" style="display:none">
	<div class="container">
	<br>
	<button type="button" id="backs" onClick="back()" class="btn btn-primary" style="display:none">Back</button>
	<br>
	</div>
	<div class="col-md-2">

		<div class="form-group">
			<label > PT  </label>
		</div>
		<div class="form-group">
			<label > <div id="headertitle"></div>  </label>
		</div>		
		<div class="form-group">
			<label > PERIODE </label>
		</div>		
	</div>
	<div class="col-md-4">
		<div class="form-group">
			: <label >PT Nirwana Alabare Garment</label>
		</div>
		<div class="form-group">
			: <label ><div id="bukukas" style="display:inline-block" ></div> <div id="rekening" style="display:inline-block"></div> </label>
		</div>		
		<div class="form-group">
			: <label ><div id="periode"></div>  </label>
		</div>	
		
	</div>

<div class="box-body" >   
  <div class="box-body">
	<div id="tablelaporan" >
	
	
	</div>

	


	<div id="tablerekap" >
	
	
	</div>	

	
	
  </div>

	</div>




</div>



