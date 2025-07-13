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
    .is___lembar_gelar,
    .is___sisa_gelar,
    .is___sambung_duluan_bisa,
    .is___sisa_tidak_bisa,
    .is___qty_reject_yds,
    .is___total_yds,
    .is___short_roll,
    .is___percent{
        text-align: right;
    }
</style>
<div class="box">
    <div class="box-body">
        <div class="row">

            <div class="col-md-6">
                <div class="col-md-4">
                    <label>Date Input</label>
                </div>
                <div class="col-md-8">
                    <input type="text" id="dtpicker" class="form-control form" value="<?php echo date('j M Y'); ?>" readonly>
                </div>
				
				
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
                    <label>PO</label>
                </div>
                <div class="col-md-8">
                    <input type="text" id="po_buyer" class="form-control form" placeholder='(Auto)' readonly>
                </div>


                <div class="col-md-4">
                    <label>Spread Qty</label>
                </div>
                <div class="col-md-8">
                    <input type="text" id="qty_gelar" class="form-control form" placeholder='(Auto)' readonly>
                </div>

                <div class="col-md-4">
                    <label>Cons</label>
                </div>
                <div class="col-md-8">
                    <input type="text" id="cons" class="form-control form" placeholder='Cons Input' >
                </div>

                <div class="col-md-4">
                    <label>Efficiency</label>
                </div>
                <div class="col-md-8">
                    <input type="text" id="efficiency" class="form-control form" placeholder='Efficiency Input' >
                </div>

                <div class="col-md-4">
                    <label>Yield</label>
                </div>
                <div class="col-md-8">
                    <input type="text" id="yield" class="form-control form" placeholder='Yield Input' >
                </div>
				
            </div>
			
			
	        <div class="col-md-6">


                <div class="col-md-4" style="display:none">
				    <div class="input-group form">
                        <label>No Request</label>
				    </div>	
                </div>
                <div class="col-md-8" style="display:none">
				    <div class="input-group form">	
					    <select class="form-control select2" id="no_req" onchange='getDataReq(this)'>
						    <option>--Choose Request--</option>
					    </select>
				    </div>	
                </div>	

                <div class="col-md-4">
				    <div class="input-group form">
                        <label>Item</label>
				    </div>	
                </div>
                <div class="col-md-8">
				    <div class="input-group form">	
					    <select class="form-control select2" id="item" onchange='getDataReqItem(this)'>
						    <option>--Choose Item--</option>					
					    </select>
				    </div>	
                </div>					
				
                <div class="col-md-4">
                    <label>Marker Length</label>
                </div>
                <div class="col-md-8">
					<input type="text" placeholder='(Auto)' readonly id="panjang_marker" class="form-control form" >
                </div>
				
                <div class="col-md-4">
                    <label>Marker Width</label>
                </div>
                <div class="col-md-8">
					<input type="text" id="lebar_marker" class="form-control form" placeholder='(Auto)' readonly >
                </div>				
				
                <div class="col-md-4">
                    <label>Panel</label>
                </div>
                <div class="col-md-8">
					<input type="text" id="bagian" class="form-control form" placeholder='(Auto)' readonly>
                    <!-- <select id="panel" class="form-control select2 form">
					</select> -->
                </div>	

                <div class="col-md-4">
                    <label>Cutting Number</label>
                </div>
                <div class="col-md-8">
					<input type="text" id="no_cutting" class="form-control form" placeholder="(Auto)" readonly>
                </div>
				
                <div class="col-md-4">
                    <label>Ratio</label>
                </div>
                <div class="col-md-8">			

					<div class="input-group form">
                        <input type="text" id="size" class="form-control " name="size" placeholder="..." value="" onmouseover="titleSize(this.value)" readonly style="background-color:#ffffff !important">	
                    </div>	

					<div class="input-group form">
                        <input type="text" id="rasio" class="form-control " name="rasio" placeholder="..." value="" readonly style="background-color:#ffffff !important">	
                        <a onclick="modal_rasio()" id="klik_saya" style="cursor: pointer" class="input-group-addon"  data-toggle="modal" data-target="#myModal3" >...</a>
                    </div>							
						
                </div>		
			
            </div>
        </div>
        <div class="modal fade " id="myModal3"  role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Detail List</h4>
                    </div>
                    <div class="modal-body">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#tabBpb" aria-controls="home" role="tab" data-toggle="tab">Rasio</a></li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="tabBpb">
                                <div class='form-group'>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Color</label>
 									        <select id="marker_color" class="form-control select2"  onchange="get_rasio(this)">
									        </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label>Panel</label>
 									        <select id="marker_panel" class="form-control select2"  onchange="get_panel_rasio(this)">
									        </select>
                                        </div>
                                    </div>
                                </div>
                        
                                <div id="content_rasio"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
		
        <br>

        <div class="row">
            <div class='col-md-3'>
                <a type='submit' name='submit' onclick="Save()" class='btn btn-primary'>Save</a>
                <a type='submit' name='cancel' onclick="my_back('../prod/?mod=SpreadingReport')" class='btn btn-warning'>Back</a>
            </div>
        </div>
		<br/>
    </div>
</div>
<div class="box">
    <div class="box-body">
	        <button type="button" class="btn btn-primary" id="add_more_item" onclick="add_more_item()" style="display:none"><i class="fa fa-plus"></i>&nbsp;Add More Item</button>
	        <button type="button" class="btn btn-warning" id="cancel_more_item" onclick="cancel_more_item()" style="display:none">&nbsp;Cancel</button>			
	    <br/>
        <div class="row">
            <div class="col-md-12">
                <table id="inv_scrap_detail" class="table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
							<th style="background-color:#FFFFFF"><input type="checkbox" onclick="all_check(this)" id="master_checked" ></th>
							<th style="background-color:#FFFFFF">Fabric Name</th>
							<th style="background-color:#FFFFFF">Fabric Code</th>
                            <th style="background-color:#FFFFFF">Bppb</th>
                            <th style="background-color:#FFFFFF">Color</th>
							<th style="background-color:#FFFFFF">Lot</th>
                            <th style="background-color:#FFFFFF">Roll</th>
                            <th style="background-color:#FFFFFF">Sticker QTY</th>
							<th style="background-color:#FFFFFF; min-width: 69px !important">Lembar Gelaran</th>
							<th style="background-color:#FFFFFF; min-width: 69px !important">Sisa Gelar</th>
                            <th style="background-color:#FFFFFF; min-width: 69px !important">Sambung Duluan Bisa</th>
                            <th style="background-color:#FFFFFF; min-width: 69px !important">Sisa tidak bisa</th>
							<th style="background-color:#FFFFFF; min-width: 69px !important">Qty Reject(YDS)</th>
							<th style="background-color:#FFFFFF; min-width: 69px !important">Total(YDS)</th>
							<th style="background-color:#FFFFFF; min-width: 69px !important">Short Roll(+/- YDS)</th>
							<th style="background-color:#FFFFFF; min-width: 69px !important">Percent(%)</th>
							<th style="background-color:#FFFFFF; min-width: 69px !important">REMARK</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <br>
    </div>
</div>