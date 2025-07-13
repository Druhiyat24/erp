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
    .forms{
        text-align: right;
    }
</style>
<div class="box">
    <div class="box-body">
        <div class="row">

            <div class="col-md-6">
                <div class="col-md-4">
                    <label>Buyer</label>
                </div>
                <div class="col-md-8">
                    <input type="text" id="buyer" class="form-control form" placeholder='(Auto)' readonly>
                </div>		

                <div class="col-md-4">
                    <label>WS#</label>
                </div>
                <div class="col-md-8">
                    <input type="text" id="ws" class="form-control form" placeholder='(Auto)' readonly>
                </div>					
				
                <div class="col-md-4">
                    <label>SO#</label>
                </div>
                <div class="col-md-8">
                    <input type="text" id="so" class="form-control form" placeholder='(Auto)' readonly>
                </div>					
								

                <div class="col-md-4">
                    <label>Color</label>
                </div>
                <div class="col-md-8">
                    <input type="text" id="colors" class="form-control form" placeholder='(Auto)' readonly>
                </div>
								

                <div class="col-md-4">
                    <label>Po</label>
                </div>
                <div class="col-md-8">
                    <input type="text" id="po_buyer" class="form-control form" placeholder='(Auto)' readonly>
                </div>
            </div>
			
	        <div class="col-md-6">
            </div>
        </div>
		
        <br>

        <div class="row">
            <div class='col-md-3'>
                <a type='submit' name='submit' onclick="Save()" class='btn btn-primary'>Save</a>
                <a type='submit' name='cancel' onclick="my_back('../prod/?mod=mRollListWs')" class='btn btn-warning'>Back</a>
            </div>
        </div>
		<br/>
    </div>
</div>


<div class="box">
    <div class="box-body">
	
    	<br/>
        <div class="row">
            <div class="col-md-12">
                <table id="tbl_mroll" class="table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th style="text-align: center; background-color:#ffffff; vertical-align: middle">No</th>
                            <!-- <th style="text-align: center; background-color:#ffffff; vertical-align: middle">Fabric Code</th>
                            <th style="text-align: center; background-color:#ffffff; vertical-align: middle">Fabric Name</th>
                            <th style="text-align: center; background-color:#ffffff; vertical-align: middle">Color</th> -->
                            <th style="text-align: center; background-color:#ffffff; vertical-align: middle">Panel</th>
                            <th style="text-align: center; background-color:#ffffff; vertical-align: middle">LOT</th>
                            <th style="text-align: center; background-color:#ffffff; vertical-align: middle">Roll Number</th>
                            <th style="text-align: center; background-color:#ffffff; vertical-align: middle">QTY Sticker</th>
                            <th style="min-width: 150px">Fabric Used(Kg)</th>
                            <th style="min-width: 150px">Qty Cutting(Pcs)</th>
                            <th style="min-width: 150px">Cons WS(Kg)</th>
                            <th style="min-width: 150px">Cons m(Kg)</th>
                            <th style="min-width: 150px">Cons Act pcs(Kg)</th>
                            <th style="min-width: 150px">Balance Cons</th>
                            <th style="min-width: 150px">Percentage</th>
                            <th style="min-width: 150px">Binding</th>
                            <th style="min-width: 150px">Actual Balance</th>
                            <th style="min-width: 150px">Actual Total</th>
                            <th style="min-width: 150px">Short Roll/Bal</th>
                            <th style="min-width: 150px">Spread Sheet</th>
                            <th style="min-width: 150px">Ratio</th>
                            <th style="min-width: 150px">Qty Pcs</th>
                            <th>Length Markers</th>
                            <th style="min-width: 150px">Efficiency</th>
                            <th style="min-width: 150px">Fabric Total Act</th>
                            <th style="min-width: 150px">Majun(%)</th>
                            <th style="min-width: 150px">Majun(Kg)</th>
                            <th style="min-width: 150px">1 Spread</th>
                            <th style="min-width: 150px">Total</th>
                            <th style="min-width: 150px">Used Total</th>
                            <th style="min-width: 150px">Width Fabric</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>