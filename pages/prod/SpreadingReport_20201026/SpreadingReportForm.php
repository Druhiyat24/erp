<?php 
if (empty($_SESSION['username'])) { header("location:../../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
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
                    <label>Tanggal Input</label>
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
                    <label>Po</label>
                </div>
                <div class="col-md-8">
                    <input type="text" id="po_buyer" class="form-control form" placeholder='(Auto)' readonly>
                </div>


                <div class="col-md-4">
                    <label>Qty Gelar</label>
                </div>
                <div class="col-md-8">
                    <input type="text" id="qty_gelar" class="form-control form" placeholder='(Auto)' readonly>
                </div>

                <div class="col-md-4">
                    <label>Cons</label>
                </div>
                <div class="col-md-8">
                    <input type="text" id="cons" class="form-control form" placeholder='Masukkan Cons' >
                </div>   
				
            </div>
			
	            <div class="col-md-6">
                <div class="col-md-4">
                    <label>Panjang Marker</label>
                </div>
                <div class="col-md-8">
						<input type="text" placeholder="Masukkan Panjang Marker" id="panjang_marker" class="form-control form" >
                </div>
				
                <div class="col-md-4">
                    <label>Lebar Marker</label>
                </div>
                <div class="col-md-8">
						<input type="text" id="lebar_marker" class="form-control form" placeholder="Masukkan Lebar Marker" >
                </div>				
				
                <div class="col-md-4">
                    <label>Bagian</label>
                </div>
                <div class="col-md-8">
						<input type="text" id="lebar_marker" class="form-control form" placeholder="Masukkan Bagian">
                </div>	

                <div class="col-md-4">
                    <label>Nomor Cutting</label>
                </div>
                <div class="col-md-8">
						<input type="text" id="no_cutting" class="form-control form" placeholder="(Auto)" readonly>
                </div>
				
                <div class="col-md-4">
                    <label>Rasio</label>
                </div>
                <div class="col-md-8">
						
						
						<div class="input-group form">
                         <input type="text" id="rasio" class="form-control " name="rasio" placeholder="(Auto)" value="" readonly>	
                         <a onclick="modal_rasio()" id="klik_saya" style="cursor: pointer" class="input-group-addon"  data-toggle="modal" data-target="#myModal3">...</a>
                         </div>						
						
						
						
                </div>		
	
	
				
            </div>	



			
			
        </div>
		
        <br>

        <div class="row">
            <div class='col-md-3'>
                <a type='submit' name='submit' onclick="Save()" class='btn btn-primary'>Simpan</a>
                <a type='submit' name='cancel' onclick="Cancel()" class='btn btn-primary'>Kembali</a>
            </div>
        </div>
		<br/>
</div>
</div>


<div class="box">
    <div class="box-body">
	
        <div class="row">
            <div class='col-md-3'>
                <a href='#' onclick="modal_edit()" class='btn btn-primary' data-toggle="modal" data-target="#myModal2">Add</a>
            </div>
        </div>	
	<br/>
        <div class="row">
            <div class="col-md-12">
                <table id="inv_scrap_detail" class="table-bordered display responsive" style="width:100%">
                    <thead>
                        <tr>
                            <th>Color</th>
							<th>Lot</th>
                            <th>Roll</th>
                            <th>Sticker yards</th>
							<th>Lembar Gelaran</th>
							<th>Sisa Gelar</th>
                            <th>Sambung Duluan Bisa</th>
                            <th>Sisa tidak bisa</th>
							<th>Qty Reject(YDS)</th>
							<th>Total(YDS)</th>
							<th>Short Roll(+/- YDS)</th>
							<th>Percent(%)</th>
							<th>REMARK</th>
							<th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <br>


    </div>
</div>



        <div class="modal fade " id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Add</h4>
                    </div>
                    <div class="modal-body">
						<div class="row">
							<div class='col-md-4'>
								<div class="col_md-4">
									<label>Color</label>
								</div>
							

								<div class="col_md-8">
									<input type="text" id="det_color" class="form-control " placeholder="(Auto)" readonly>
								</div>							


								<div class="col_md-4">
									<label>Lot</label>
								</div>
							

								<div class="col_md-8">
									<input type="text" id="det_lot" class="form-control " placeholder="(Auto)" readonly>
								</div>	

								<div class="col_md-4">
									<label>Roll</label>
								</div>
							

								<div class="col_md-8">
									<input type="text" id="det_roll" class="form-control " placeholder="Masukkan Roll" >
								</div>								
	

								<div class="col_md-4">
									<label>Sticker Yards</label>
								</div>
							

								<div class="col_md-8">
									<input type="text" id="det_sticker" class="form-control " placeholder="Masukkan Sticker" >
								</div>	
								
								<div class="col_md-4">
									<label>Lembar Gelaran</label>
								</div>
							

								<div class="col_md-8">
									<input type="text" id="det_lembar" class="form-control " placeholder="Masukkan Lembar Gelaran" >
								</div>									
	
							</div>
							
							
							<div class='col-md-4'>
								<div class="col_md-4">
									<label>Sisa Gelaran</label>
								</div>
							

								<div class="col_md-8">
									<input type="text" id="det_sisa_gelaran" class="form-control " placeholder="Masukkan Sisa Gelaran" >
								</div>							


								<div class="col_md-4">
									<label>Sambung Duluan Bisa</label>
								</div>
							

								<div class="col_md-8">
									<input type="text" id="det_duluan_bisa" class="form-control " placeholder="Masukkan Sisa Tidak Bisa" >
								</div>	

								<div class="col_md-4">
									<label>Sisa Tidak Bisa</label>
								</div>
							

								<div class="col_md-8">
									<input type="text" id="det_tidak_bisa" class="form-control " placeholder="Masukkan Sisa" >
								</div>	

								<div class="col_md-4">
									<label>Reject YDS</label>
								</div>
							

								<div class="col_md-8">
									<input type="text" id="det_reject_yds" class="form-control " placeholder="Masukkan Reject YDS" >
								</div>							


								<div class="col_md-4">
									<label>Total YDS</label>
								</div>
							

								<div class="col_md-8">
									<input type="text" id="det_total_yds" class="form-control " placeholder="Masukkan Total YDS" >
								</div>	

							
							
							</div>	


							<div class='col-md-4'>


								<div class="col_md-4">
									<label>Short Roll(+/- YDS)</label>
								</div>
							

								<div class="col_md-8">
									<input type="text" id="d_short_roll" class="form-control " placeholder="Masukkan Short Roll" >
								</div>	


								<div class="col_md-4">
									<label>Percent(%)</label>
								</div>
							

								<div class="col_md-8">
									<input type="text" id="det_percent" class="form-control " placeholder="Masukkan Percent" >
								</div>	
								
								<div class="col_md-4">
									<label>Remark</label>
								</div>
							

								<div class="col_md-8">
									<input type="text" id="det_remark" class="form-control " placeholder="Massukkan Remark" >
								</div>	
							
							
							</div>	
							
						
						</div>
						
						
						
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                        <button type="submit" onclick="saveNomor()" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
		
		
<!--<div class="modal fade" id="modal_choose" data-keyboard="false" role="dialog" > -->
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


  								
                            </div>
                        </div>					
					
                        <div id="content_rasio">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
				
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>		
<!--		
		
 <div class="modal fade " id="myModal3"  role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Pilih Marker Rasio</h4>
                    </div>
                    <div class="modal-body">
						<div class="row">
							<div class='col-md-4'>
								<div class="col_md-4">
									<label>Color</label>
								</div>
							

								<div class="col_md-8">
									<select id="marker_color" class="form-control select2" placeholder="(Auto)" onchange="get_rasio(this)">
								</div>							


							</div>
							
							<div class="col-md-12" >
								<div id="content_rasios">
								
								
								</div>
							
							
							</div>
							
				
							
						
						</div>
						
						
						
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> 
                        <button type="submit" onclick="saveNomor()" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
		-->
		
