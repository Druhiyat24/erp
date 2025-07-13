
  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <div class='col-md-3'>              
          <div class='form-group'>
            <label>Invoice # *</label>
            <input type='text' class='form-control' onkeyup="handleKeyUp(this)" readonly name='txtinvno' id='txtinvno' value=' ' >
          </div>        
          <div class='form-group'>
            <label>Packing List Date *</label>
            <input type='text' class='form-control' autocomplete='off' onchange="handleKeyUp(this)"  onkeyup="handleKeyUp(this)" id='datepicker1' name='txtinvdate'   >
          </div>             
          <div class='form-group'>
            <label>Customer *</label>
            <select class='form-control select2'  style='width: 100%;' id="head_costumer" name='txtid_buyer'
               onchange="getListWS(this.value);handleKeyUp(this)">
            </select>
          </div>         
          <div class='form-group'>
            <label>Consignee</label>
            <input type='text' class='form-control' onkeyup="handleKeyUp(this)" id="txtconsignee" name='txtconsignee' placeholder='Masukkan Consignee' value='' >
          </div>        
          <div class='form-group'>
            <label>Shipper</label>
            <input type='text' class='form-control' onkeyup="handleKeyUp(this)" id='txtshipper' name='txtshipper' placeholder='Masukkan Shipper'>
          </div>        
          <div class='form-group'>
            <label>Notiry Party</label>
            <input type='text' class='form-control' onkeyup="handleKeyUp(this)" id='txtnotify_party' name='txtnotify_party' placeholder='Masukkan Notiry Party'>
          </div>          
          <div class='form-group'>
            <label>WS # *</label><br/>
				<input type="text" class="form-control" id="ws_nya__" style="display:none">
				<a href="#" class="btn btn-info"  onclick="showWS()">Pilih WS</a>
          </div>
          <div class='form-group'>
            <label>Faktur Pajak</label>
            <input type='text' class='form-control' onkeyup="handleKeyUp(this)" name='faktur_pajak' id="faktur_pajak" placeholder='Masukkan Faktur Pajak' value='' >
          </div> 		  
        </div>
        <div class='col-md-3'>        
          <div class='form-group'>
            <label>Country Of Origin</label>
            <input type='text' class='form-control' onkeyup="handleKeyUp(this)" name='txtcountry_of_origin' id='txtcountry_of_origin' placeholder='Masukkan Country Of Origin'>
          </div>        
          <div class='form-group'>
            <label>Mfg Adress</label>
            <input type='text' class='form-control' onkeyup="handleKeyUp(this)" name='txtmanufacture_address' id='txtmanufacture_address' placeholder='Masukkan Mfg Adress'>
          </div>        
          <div class='form-group'>
            <label>Vessel Name</label>
            <input type='text' class='form-control' onkeyup="handleKeyUp(this)" name='txtvessel_name' id='txtvessel_name' placeholder='Masukkan Vessel Name'>
          </div>          
          <div class='form-group'>
            <label>Port Of Loading *</label>
            <select class='form-control select2' onchange="handleKeyUp(this)" id='txtport_of_loading' style='width: 100%;' name='txtport_of_loading'>

            </select>
          </div>          
          <div class='form-group'>
            <label>Port Of Discharges *</label>
            <select class='form-control select2' onchange="handleKeyUp(this)" id='txtport_of_discharges' style='width: 100%;' name='txtport_of_discharge'>

            </select>
          </div>          
          <div class='form-group' style="display:none">
            <label>Port Of Entrances *</label>
            <select class='form-control select2' onchange="handleKeyUp(this)" id='txtport_of_entrances' style='width: 100%;' name='txtport_of_entrance'>

            </select>
          </div>
          <div class='form-group'>
            <label>Container #</label>
            <input type='text' class='form-control' onkeyup="handleKeyUp(this)" name='txtcontainer_no' id='txtcontainer_no' placeholder='Masukkan Container #'>
          </div>        
        </div>
        <div class='col-md-3'>        
          <div class='form-group'>
            <label>LC #</label>
            <input type='text' class='form-control' onkeyup="handleKeyUp(this)" name='txtlc_no' id='txtlc_no' placeholder='Masukkan LC #'>
          </div>        
          <div class='form-group'>
            <label>LC Issue By</label>
            <input type='text' class='form-control'  onchange="handleKeyUp(this)" name='txtlc_issue_by' id='txtlc_issue_by' placeholder='Masukkan LC Issue By'>
          </div>          
          <div class='form-group'>
            <label>HS Code *</label>
            <select class='form-control select2' onchange="handleKeyUp(this)" id='txths_code' style='width: 100%;' name='txths_code'>

            </select>
          </div>        
          <div class='form-group'>
            <label>ETD</label>
            <input type='text' id='datepicker2' onkeyup="handleKeyUp(this)" onchange="handleKeyUp(this)" class='form-control' id='txtetd' name='txtetd' placeholder='Masukkan ETD' >
          </div>        
          <div class='form-group'>
            <label>ETA</label>
            <input type='text' class='form-control' onkeyup="handleKeyUp(this)" onchange="handleKeyUp(this)" id='datepicker3' id='datepicker3' name='txteta' placeholder='Masukkan ETA' >
          </div>        
          <div class='form-group' style="display:none">
            <label>ETA Lax</label>
            <input type='text' class='form-control' onkeyup="handleKeyUp(this)" onchange="handleKeyUp(this)" id='datepicker4' name='txteta_lax' placeholder='Masukkan ETA Lax'  >
          </div>
          <div class='form-group'>
            <label>Seal #</label>
            <input type='text' class='form-control' onkeyup="handleKeyUp(this)" name='txtseal_no' id='txtseal_no' placeholder='Masukkan Seal #' >
          </div>
        </div>
        <div class='col-md-3'>          
          <div class='form-group'>
            <label>Payment Terms *</label>
            <select class='form-control select2' onchange="handleKeyUp(this)" style='width: 100%;' id='txtid_pterms' name='txtid_pterms'>

            </select>
          </div>          
          <div class='form-group'>
            <label>Shipped By *</label>
            <select class='form-control select2' onchange="handleKeyUp(this)" style='width: 100%;' id='txtshipped_by' name='txtshipped_by'>

            </select>
          </div>          
          <div class='form-group' style="display:none">
            <label>Route *</label>
            <select class='form-control select2' onchange="handleKeyUp(this)" style='width: 100%;' id='txtroute' name='txtroute'>

            </select>
          </div>        
          <div class='form-group' style="display:none">
            <label>Ship To</label>
            <input type='text' class='form-control' onkeyup="handleKeyUp(this)" name='txtship_to' id='txtship_to' placeholder='Masukkan Ship To' >
          </div>        
          <div class='form-group'>
            <label>Net Weight</label>
            <input type='text' class='form-control' onkeyup="handleKeyUp(this)" name='txtnw' id='txtnw' placeholder='Masukkan Net Weight' >
          </div>        
          <div class='form-group'>
            <label>Gross Weight</label>
            <input type='text' class='form-control'onkeyup="handleKeyUp(this)" name='txtgw' id='txtgw' placeholder='Masukkan Gross Weight' >
          </div>          
          <div class='form-group'>
            <label>Measurement *</label>
<!--             <select class='form-control select2'  onchange="handleKeyUp(this)" style='width: 100%;' id='txtmeasurement' name='txtmeasurement'>
  
            </select> -->
          <div class='form-group'>
		  <div class="col-md-12" style="padding-left:0 !important">
            <label>Type Of Pacikng *</label>
<!--             <select class='form-control select2'  onchange="handleKeyUp(this)" style='width: 100%;' id='txtmeasurement' name='txtmeasurement'>
  
            </select> -->		  
		  </div>

			<div class="col-md-6" style="padding-left:0 !important;">
			<input type='text' class='form-control' onkeyup="handleKeyUp(this)" name='txtmeasurement' id='txtmeasurement' placeholder='Masukkan Measurement'  >			
			</div>
			<div class="col-md-6" style="padding-left:0 !important;padding-right:0 !important">
			<input type='text' class='form-control' onkeyup="handleKeyUp(this)" name='txtmeasurement2' id='txtmeasurement2' placeholder='Masukkan Measurement'  >			
			</div>			

          </div>
          </div>
        </div>
        <div class='box-body'>
          <div id='detail_item'></div>
        </div>
        <div class='col-md-3'>
          <a href="#" onclick="Save()" class='btn btn-primary myPost'>Simpan</a>
        </div>  
      </div>
    </div>
  </div>

