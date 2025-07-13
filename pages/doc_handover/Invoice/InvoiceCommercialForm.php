<?php 
if (empty($_SESSION['username'])) { header("location:../../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];W
# END CEK HAK AKSES KEMBALI


?>
		<div class='box'>
			<div class='box-body'>
				<div class="row">
					<div class='col-md-3'>  
						<select id="type_invoice" disabled  class="form-control" name="type_invoice">
							<option value="99" disabled selected>--Pilih Invoice--</option>
							<option  value="1">LOKAL</option>
							<option   value="2">EXPORT</option>
						</select>
					</div>
					<div class='col-md-3'>  
						<a href="#"  class='btn btn-primary myPost' style="display:none" onclick="post()">Post</a> 
					</div>					
				</div>
			</div>
		</div>

<form method='post' name='form' action='s_inv.php?mod=<?php echo $mod; ?>' 
  onsubmit='return validasi()'>
  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <div class='col-md-3'>
          <div class='form-group' >
            <label>Bank</label>
            <select class='form-control select2' id="idcoa" style='width: 100%;'
              onchange="handlekeyup(this)">
            </select>
          </div>  		
          <div class='form-group' style="display:none">
            <label>No BPB</label>
            <select class='form-control select2' id="bpb_no" style='width: 100%;'
              onchange="getDetailBpb(this.value)">
            </select>
          </div>      
          <div class='form-group'>
            <label>No.Invoice</label>
            <select class='form-control select2' disabled id="id_invoiceheader" style='width: 100%;'
              onchange="handlekeyup(this);">
            </select>
          </div> 
          <div class='form-group'>
            <label><input type="checkbox" id="fg_discount" onclick="toggleDiscount()"  >  Discount(percent(%))</label>
            <input type='text' onkeyup="handlekeyup(this)" id='discount' class='form-control'  placeholder='Masukkan Discount'  value=' ' >
          </div>   		  
          <div class='form-group' style="display:none">
            <label>From</label>
            <input type='text' onkeyup="handlekeyup(this)" class='form-control' id='from'  placeholder='Masukkan Alamat Awal'  value=' ' >
          </div>          
          <div class='form-group'>
            <label>To</label>
            <input type='text' onkeyup="handlekeyup(this)" class='form-control' id='to'  placeholder='Masukkan Alamat Tujuan' value=' ' >
          </div>       
          <div class='form-group' style="display:none">
            <label>Amount</label>
            <input type='text' readonly class='form-control' onkeyup="handlekeyup(this)" id="amount" placeholder='Masukkan Amount' value='' >
          </div>   
          <div class='form-group' style="display:none">
            <label>PO</label>
            <input type='text' readonly class='form-control' onkeyup="handlekeyup(this)" id="po" placeholder='Masukkan PO' value='' >
          </div>		  
        <div class='col-md-3'>
          <a href="#" onclick="Save()" style="display:none" class='btn btn-primary myPost'>Simpan</a>
        </div> 
	   </div>
	   

	 </div>
    </div>
  </div>
</form>


