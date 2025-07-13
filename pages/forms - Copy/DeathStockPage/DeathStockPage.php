<?php 
if (empty($_SESSION['username'])) { header("location:../../../index.php"); }
$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
# END CEK HAK AKSES KEMBALI
?>
<div class="box list" > 
  <div class="box-header" id="my_new">
	<a href="#" class="btn btn-primary" onclick='news()' data-dismiss="modal">New</a>	
  </div>
<div id="formBs" class='box'>
<div class="box-body" >
<table id="MasterDeathStock" class=" display responsive table table-condensed table-bordered" style="width:100%">
        <thead>
            <tr>
		  <th>Tipe</th>
		  <th>Klasifikasi Bahan Baku</th>
		  <th>Kode Bahan Baku</th>
          <th>Nama Bahan Baku</th>
		  <th>Warna</th>
          <th>Ukuran</th>
		  <th>Qty</th>
          <th>Unit</th>
		   <th>No. Rak</th>
		    <th>Keterangan</th>
			 <th>Action</th>
            </tr>

    </table>
	</div>
 
</div>
</div>



<div id="myModal_ds" data-backdrop="static" class="modal fade " role="dialog">
  <div class="modal-dialog modal-dialog modal-lg" role="document">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="HeaderModal">List WS</h4>
      </div>
      <div class="modal-body ">

	  <div class ="row">
		<div class="col-md-6">
          <div class='form-group'>
            <label>Tipe*</label> <label id='tol_tipe'>  </label>
            <select  class='form-control' autocomplete='off' onchange="handleKeyUp(this)" id='c_type'   >
				<option value='0' disabled>--Pilih Tipe--</option>
				<option value='A' >A</option>
				<option value='F' >F</option>
			</select>
          </div> 
          <div class='form-group'>
            <label>Klasifikasi*</label>
            <input type='text' class='form-control' autocomplete='off'  placeholder='Masukkan Klasifikasi' onkeyup="handleKeyUp(this)" id='v_klasifikasi' >
          </div>		  
	
          <div class='form-group'>
            <label>Kode Bahan Baku*</label>
            <input type='text' class='form-control' autocomplete='off'  placeholder='Masukkan Kode Bahan Baku' onkeyup="handleKeyUp(this)" id='v_c_bahan_baku' >
          </div>	

          <div class='form-group'>
            <label>Nama Bahan Baku*</label>
            <input type='text' class='form-control' autocomplete='off'  placeholder='Masukkan Nama Bahan Baku' onkeyup="handleKeyUp(this)" id='v_n_bahan_baku' >
          </div>	

          <div class='form-group'>
            <label>Warna*</label>
            <input type='text' class='form-control' autocomplete='off'  placeholder='Masukkan Warna' onkeyup="handleKeyUp(this)" id='v_color' >
          </div>

          <div class='form-group'>
            <label>Ukuran*</label>
            <input type='text' class='form-control' autocomplete='off'  placeholder='Masukkan Ukuran' onkeyup="handleKeyUp(this)" id='v_ukuran' >
          </div>
		  
		</div>
		
		
		<div class="col-md-6">
          <div class='form-group'>
            <label>Qty*</label>
            <input type='text' class='form-control' autocomplete='off'  placeholder='Masukkan Qty' onkeyup="handleKeyUp(this)" id='n_qty' >
          </div>		  	

          <div class='form-group'>
            <label>Unit*</label>
            <select  class='select2 form-control' style="width:100%" autocomplete='off' onchange="handleKeyUp(this)" id='v_unit'   >

			</select>			
			
			
			
          </div>	

          <div class='form-group'>
            <label>No Rak*</label>
            <input type='text' class='form-control' autocomplete='off'  placeholder='Masukkan No. Rak' onkeyup="handleKeyUp(this)" id='v_no_rak' >
          </div>

          <div class='form-group'>
            <label>Keterangan</label>
            <input type='text' class='form-control' autocomplete='off'  placeholder='Masukkan Keterangan' onkeyup="handleKeyUp(this)" id='v_keterangan' >
          </div>
		  
		</div>		
		
		<div class="col-md-12">
			<a href="#" class="btn btn-primary" onclick='save()' > <i class="fa fa-refresh fa-spin" id='my_loading' style="display:none"></i> Simpan</a>	
		</div>
		
		
	  </div>  
	  
	  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"> Close</button> 
      </div>
    </div>
  </div>
</div>