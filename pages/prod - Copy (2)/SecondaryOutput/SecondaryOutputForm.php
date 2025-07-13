<?php 
    if (empty($_SESSION['username'])) { header("location:../../../index.php"); }
?>

<style>
    th{
        text-align: center;
    }
</style>

<div class="box">
    <div class="box-body">
            
        <form action="" name="form" method="post">
                
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>No Sec. In</label>
                        <input type='text'class='form-control' id='no_sec_out' 
                        name='txtdateout' placeholder='(Auto)'  disabled>
                    </div>
                    <div class="form-group">
                        <label>Input Date</label>
                        <input type='text'class='form-control' id='tanggalinput' 
                        name='txtdateout' placeholder='Masukkan Tanggal Output' value='<?php echo date('d M Y') ?>' disabled>
                    </div>
                </div>			
                <div class="col-md-3">

                    <div class="form-group">
                        <label>WS *</label>
                        <select class='form-control select2' style='width: 100%;' id='ws' onchange="get_panel_proses(this.value);">
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Inhouse / Subkon *</label>
                        <select class='form-control select2' onchange="getListDeptSubcon(this.value)" id="inhousesubcon" style='width: 100%;'>
                        </select>
                    </div>					
                </div>

                <div class="col-md-3">

                    <div class="form-group">
                        <label>Dept / Subkon *</label>
                        <select class='form-control select2'  id="deptsubcon" style='width: 100%;'>
                            <option selected disabled>--Choose Dept/Subkon--</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Process *</label>
                        <select class='form-control select2' style='width: 100%;' id="id_proses"  multiple="multiple">
                        </select>
                    </div>					
					
                </div>

                <div class="col-md-3">

                    <div class="form-group">
                        <label>Panel</label>
                        <select class="form-control select2" id="id_panel" style="width: 100%;" multiple="multiple">
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Notes</label>
                        <input type='text' class='form-control' onkeyup ="handlekeyup(this)" name='txtnotes' id="notes"
                        placeholder='Masukkan Notes' value=''>
                    </div>


                </div>
				<br/>
                <div class="col-md-12">
                    <div class="form-group">
                        <br>
                        <a href="#" value="Search" class="btn btn-info" id="cari" name="cari" onclick="getDetail()">Search</a>
                    </div>

                </div>				
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <table id="table_detail" class="table-bordered table-striped" style="width:100%">
                        <thead>
                            <tr>

                	    	    <th>Product Description</th>
                	            <th>Color</th>
                		        <th>Size</th>
                		        <th>Shell</th>
                                <th>Panel</th>
            	    	        <th>LOT</th>
								<th>Balance</th>
                                <th>Tot. Qty Sec In</th>
            			        <th>Cutting Number</th>
                                <th>Bundle Number</th>
                                <th>Sack Number</th>
                                <th>Qty Reject Input</th>
                                <th>Qty Input</th>
                            </tr>
                        </thead>
                    </table>

                    <!-- <div id="detail_item">
                    </div> -->
                </div>
            </div>
            <br><br>

            <div class="row">
                <div class="col-md-3">
                    <a type='submit' name='submit' onclick="Save()" class='btn btn-primary'>Save</a>
                    <input type="button" value="Back" class="btn btn-warning" name="cancel" onclick="Cancel()">
                </div>
            </div>

            
        </form>
        
    </div>
</div>