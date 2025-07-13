  <form method='post' name='form' 
  onsubmit='return validasi()'>
  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <div class='col-md-3'>              
          <div class='form-group'>
            <label>Invoice # *</label>
            <input type='text' class='form-control' onkeyup="handleKeyUp(this)" readonly name='txtinvno' id='txtinvno' >
          </div>        
          <div class='form-group'>
            <label>Packing List Date *</label>
            <input type='text' class='form-control' onkeyup="handleKeyUp(this)" id='datepicker1' name='txtinvdate' readonly  >
          </div>          
          <div class='form-group'>
            <label>Customer *</label>
            <select class='form-control select2' onkeyup="handleKeyUp(this)" disabled style='width: 100%;' id="head_costumer" name='txtid_buyer'
               onchange="getListWS(this.value);handleKeyUp(this)">

            </select>
          </div>        
          <div class='form-group'>
            <label>Buyer/Receiver Penerima</label>
            <input type='text' class='form-control' onkeyup="handleKeyUp(this)" name='txtconsignee' id="txtconsignee" placeholder='Buyer/Receiver Penerima'  >
          </div>        
          <div class='form-group'>
            <label>Seller/Sender Pengirim</label>
            <input type='text' class='form-control' onkeyup="handleKeyUp(this)" name='txtshipper' id="txtshipper" placeholder='Seller/Sender Pengirim' readonly >
          </div>        
       
          <div class='form-group'>
            <label>WS # *</label>
				<input type="text" class="form-control" id="ws_nya" disabled>
          </div>
          <div class='form-group'>
            <label>Faktur Pajak</label>
            <input type='text' class='form-control' onkeyup="handleKeyUp(this)" id='faktur_pajak' name='faktur_pajak' placeholder='Masukkan Faktur Pajak'  >
          </div> 		  
        </div>
        <div class='col-md-3'>        
       
          <div class='form-group'>
            <label>Buyer/Receiver Adress</label>
            <input type='text' class='form-control' onkeyup="handleKeyUp(this)" id='txtmanufacture_address' name='txtmanufacture_address' placeholder='Masukkan Mfg Adress'  >
          </div>        
          <div class='form-group'>
            <label>Vessel Name</label>
            <input type='text' class='form-control' onkeyup="handleKeyUp(this)" id='txtvessel_name' name='txtvessel_name' placeholder='Masukkan Vessel Name'  >
          </div>          
          <div class='form-group'>
            <label>Contract No#</label>
            <input type='text' class='form-control' onkeyup="handleKeyUp(this)" id='txtlc_no' name='txtlc_no' placeholder='Contract No#'  >
          </div> 
		
          <div class='form-group'>
            <label>Contract Date</label>
            <input type='text' class='form-control' onkeyup="handleKeyUp(this)" id='txtlc_issue_by' name='txtlc_issue_by' placeholder='Contract Date' >
          </div>  
          <div class='form-group'>
            <label>HS Code *</label>
            <select class='form-control select2' onkeyup="handleKeyUp(this)" onchange="handleKeyUp(this)" id='txths_code' style='width: 100%;' name='txths_code'>

            </select>
          </div>   
          <div class='form-group'>
            <label>Delivary Time</label>
            <input type='text' id='datepicker2' onkeyup="handleKeyUp(this)" id='txtetd' class='form-control' name='txtetd' placeholder='Delivary Tim'  >
          </div>  
		  <div class='form-group'>
            <label>Payment Terms *</label>
            <select class='form-control select2' onkeyup="handleKeyUp(this)" onkeyup="handleKeyUp(this)" style='width: 100%;' id='txtid_pterms' name='txtid_pterms'>

            </select>
          </div>   		  
		  </div>

        <div class='col-md-3'>                  
          <div class='form-group'>
            <label>DelivaryBy *</label>
            <select class='form-control select2' disabled onchange="handleKeyUp(this)" id='txtshipped_by' style='width: 100%;' name='txtshipped_by'>

            </select>
          </div>          
   
      
          <div class='form-group'>
            <label>Net Weight</label>
            <input type='text' class='form-control' onkeyup="handleKeyUp(this)" name='txtnw' id='txtnw' placeholder='Masukkan Net Weight'  >
          </div>        
          <div class='form-group'>
            <label>Gross Weight</label>
            <input type='text' class='form-control' onkeyup="handleKeyUp(this)" name='txtgw' id='txtgw' placeholder='Masukkan Gross Weight'  >
          </div>          
          <div class='form-group'>
            <label>Measurement *</label>
            <select class='form-control select2'  onchange="handleKeyUp(this)" style='width: 100%;' id='txtmeasurement' name='txtmeasurement'>
  
            </select>
          </div>
        </div>
        <div class='box-body'>
          <div id='detail_item'></div>
        </div>
        <div class='col-md-3'>
         <a href=""# name='submit' style="display:none" onclick="Save()" class='btn btn-primary myPost'>Simpan</a>
        </div>  
      </div>
    </div>
  </div>
</form>

