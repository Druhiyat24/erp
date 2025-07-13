

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
      <div class="col-md-12">

        <button type="button" id="search" onClick="getLaporan()" class="btn btn-primary">Submit</button>
		<img src="./img/25.gif" id="loading" style="display:none" class="img-responsive loading"width="4%">
      </div>			
  </div>

	</div>




</div>



<div class="box list" id="uijurnal" >
<div class="box-body" >   <button type="button" id="backs" onClick="back()" class="btn btn-primary" style="display:none">Back</button>
  <div class="box-body">

  	<table id="examplefix1010" class="display responsive" style="width:100%;font-size:12px;">
    </table>
  </div>

	</div>




</div>


