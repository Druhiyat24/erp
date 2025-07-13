<?php 
if (empty($_SESSION['username'])) { header("location:../../../index.php"); }
?>

<style>
    .form{
        width: 65% !important;
        margin-bottom: 5px;
        height: 27px;
    }
    .select2{
        /* height: 27px !important; */
        margin-bottom: 5px;
    }
    th{
        text-align: center;
    }
</style>
<div class="box">
    <div class="box-body">
        <div class="row">

            <div class="col-md-6">
                <div class="col-md-4">
                    <label>No Mutation</label>
                </div>
                <div class="col-md-8">
                    <input type="text" disabled id="no_mut_sew" class="form-control form" placeholder="(Auto)">
                </div>			
			
			
                <div class="col-md-4">
                    <label>Date Output</label>
                </div>
                <div class="col-md-8">
                    <input type="text" disabled id="date_output" class="form-control form" value="<?php echo date('j M Y'); ?>" >
                </div>
				
				
                <div class="col-md-4">
                    <label>Time Output</label>
                </div>
                <div class="col-md-8">
                    <input type="text" id="time" class="form-control form" value="">
                </div>		

                <div class="col-md-4">
                    <label>Notes</label>
                </div>
                <div class="col-md-8">
                    <input type="text" id="notes" class="form-control form" placeholder='Choose Notes' >
                </div>					
				
				
            </div>
			

	        <div class="col-md-6">
			<!--
                <div class="col-md-4">
				    <div class="input-group form">
                        <label>Line</label>
				    </div>	
                </div>
                <div class="col-md-8">
				    <div class="input-group form">	
					    <select class="form-control select2" id="line" >
						    <option>--Choose Line--</option>
					    </select>
				    </div>	
                </div>	
				-->
                <div class="col-md-4">
				    <div class="input-group form">
                        <label>WS#</label>
				    </div>	
                </div>
                <div class="col-md-8">
				    <div class="input-group form">	
					    <select class="form-control select2" id="ws" onchange='getDetail(this)'>
						    <option>--Choose WS--</option>
					    </select>
				    </div>	
                </div>	

						
                </div>		
	




		
            </div>
               <div class='col-md-3'>
                <a type='submit' name='submit' onclick="Save()" class='btn btn-primary'>Save</a>
                <a type='submit' name='cancel' onclick="my_back('../prod/?mod=MUT_SEW_PAGE')" class='btn btn-warning'>Back</a>
            </div>     
        </div>

		
        <br>


		<br/>
    </div>
</div>


<div class="box">
    <div class="box-body">
	    <br/>
        <div class="row">
            <div class="col-md-12">
                <table id="table_detail" class="table-bordered" style="width:100%">
                    <thead>
                        <tr>
							<th style="background-color:#FFFFFF">SO#</th>
							<th style="background-color:#FFFFFF">Buyer PO</th>
                            <th style="background-color:#FFFFFF">Dest</th>
                            <th style="background-color:#FFFFFF">Color</th>
							<th style="background-color:#FFFFFF">Size</th>
                            <th style="background-color:#FFFFFF">Qty SO</th>
                            <th style="background-color:#FFFFFF">Unit SO</th>
							<th style="background-color:#FFFFFF">Qty Dc Set</th>
							<th style="background-color:#FFFFFF">Tot. Qty Transfered To Sewing</th>
							<th style="background-color:#FFFFFF">Qty Ready To Transfer</th>
							<th style="background-color:#FFFFFF">Qty Transfer</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <br>
    </div>
</div>

