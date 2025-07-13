<?php include '../header2.php' ?>

    <!-- MAIN -->
    <div class="col p-4">
        <h4 class="text-center">FORM CREATE INVOICE</h4>
<div class="box">
    <div class="box header">
<form id="form-data" method="post">
        <div class="form-row">
            <div class="col-md-3 mb-3">            
            <label for="noftrcbd"><b>No Invoice</b></label>
            <select class="form-control select2bs4" name="no_inv" id="no_inv" data-dropup-auto="false" data-live-search="true" onchange="getdatainv()">
                <option value="" disabled selected="true">Select No Invoice</option>                                                
                <?php
                $no_inv ='';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $no_inv = isset($_POST['no_inv']) ? $_POST['no_inv']: null;
                }                 
                $sql = mysqli_query($conn1,"select no_invoice from sb_book_invoice where status = 'Draft'");
                while ($row = mysqli_fetch_array($sql)) {
                    $data = $row['no_invoice'];
                    if($row['no_invoice'] == $_POST['no_inv']){
                        $isSelected = ' selected="selected"';
                    }else{
                        $isSelected = '';
                    }
                    echo '<option value="'.$data.'"'.$isSelected.'">'. $data .'</option>';    
                }?>
                </select>
            </div>
            <input type="hidden" style="font-size: 15px;" name="unik_code" id="unik_code" class="form-control" 
            value="<?php 
            $karakter = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            $shuffle  = substr(str_shuffle($karakter), 0, 20);
            echo $shuffle; ?>" autocomplete='off' readonly>

            <div class="col-md-4 mb-3">            
            <label for="customer"><b>Customer</b></label>          
            <input type="text" style="font-size: 14px;" name="customer" id="customer" class="form-control" 
            value="" readonly>
            <input type="hidden" style="font-size: 14px;" name="id_customer" id="id_customer" class="form-control" 
            value="" readonly>
            <input type="hidden" class="form-control" id="grade" name="grade" readonly>
            <input type="hidden" class="form-control" id="inv_date" name="inv_date" readonly>
            <input type="hidden" class="form-control" id="inv_curr" name="inv_curr" readonly>
            <input type="hidden" class="form-control" id="no_coa_deb" name="no_coa_deb" readonly>
            <input type="hidden" class="form-control" id="nama_coa_deb" name="nama_coa_deb" readonly>
            <input type="hidden" class="form-control" id="no_coa_cre" name="no_coa_cre" readonly>
            <input type="hidden" class="form-control" id="nama_coa_cre" name="nama_coa_cre" readonly>
            <input type="hidden" class="form-control" id="no_coa_dp" name="no_coa_dp" readonly>
            <input type="hidden" class="form-control" id="nama_coa_dp" name="nama_coa_dp" readonly>
            <input type="hidden" class="form-control" id="no_coa_pot" name="no_coa_pot" readonly>
            <input type="hidden" class="form-control" id="nama_coa_pot" name="nama_coa_pot" readonly>
            <input type="hidden" class="form-control" id="no_coa_ppn" name="no_coa_ppn" readonly>
            <input type="hidden" class="form-control" id="nama_coa_ppn" name="nama_coa_ppn" readonly>
            <input type="hidden" class="form-control" id="inv_rate" name="inv_rate" readonly>
            <input type="hidden" class="form-control" id="id_inv" name="id_inv" readonly required>
            <input type="hidden" class="form-control" id="no_inv_post" name="no_inv_post" readonly>
            <input type="hidden" class="form-control" id="id_inv_post" name="id_inv_post" readonly>
            <input type="hidden" class="form-control" id="pph_post" name="pph_post" readonly>
            <input type="hidden" class="form-control" id="txt_shipp" name="txt_shipp" readonly>
            <input type="hidden" class="form-control" id="txt_type_so" name="txt_type_so" readonly>
            <input type="hidden" class="form-control" id="txt_type" name="txt_type" readonly>
            <input type="hidden" class="form-control" id="txt_cust_ctg" name="txt_cust_ctg" readonly>
            <input type="hidden" class="form-control" id="txt_grade" name="txt_grade" readonly>
            <input type="hidden" class="form-control" id="txt_inv_date" name="txt_inv_date" readonly>
            <input type="hidden" class="form-control" id="txt_vat" name="txt_vat" readonly>
            <input type="hidden" class="form-control" id="txt_pot" name="txt_pot" readonly>
            <input type="hidden" class="form-control" id="txt_curr" name="txt_curr" readonly>
            </div>

            <div class="col-md-3 mb-3">            
            <label for="top_type"><b>TOP Type</b></label>          
            <select class="form-control select2bs4" name="top_type" id="top_type" data-dropup-auto="false" data-live-search="true" onchange="getperiode()">
                    <option value="" disabled selected="true">Select TOP</option>
                </select>
            </div>

            <div class="col-md-2 mb-3">            
            <label for="time_periode"><b>Time Periode</b></label>          
            <input type="text" style="font-size: 14px;" name="time_periode" id="time_periode" class="form-control" 
            value="" readonly>
            </div> 

            <div class="col-md-3 mb-3">            
            <label for="shipp"><b>Shipp</b></label>          
            <input type="text" style="font-size: 14px;" name="shipp" id="shipp" class="form-control" 
            value="" readonly>
            </div>

            <div class="col-md-2 mb-3">            
            <label for="dok_type"><b>Dokumen Type</b></label>          
            <input type="text" style="font-size: 14px;" name="dok_type" id="dok_type" class="form-control" 
            value="" readonly>
            </div>

            <div class="col-md-2 mb-3">            
            <label for="dok_number"><b>Dokumen Number</b></label>          
            <input type="text" style="font-size: 14px;" name="dok_number" id="dok_number" class="form-control" 
            value="" readonly>
            </div> 

            <div class="col-md-3 mb-3">            
            <label for="txt_type"><b>Type</b></label>          
            <input type="text" style="font-size: 14px;" name="txt_type" id="txt_type" class="form-control" 
            value="" readonly>
            </div> 

            <div class="col-md-2 mb-3">            
            <label for="txt_value"><b>Value</b></label>          
            <input type="text" style="font-size: 14px;" name="txt_value" id="txt_value" class="form-control" 
            value="" readonly>
            </div>

            <div class="col-md-3 mb-3">            
            <label for="txt_bank"><b>Bank</b></label>
            <select class="form-control select2bs4" name="txt_bank" id="txt_bank" data-dropup-auto="false" data-live-search="true" >
                <option value="" disabled selected="true">Select Bank</option>                                                
                <?php
                $txt_bank ='';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $txt_bank = isset($_POST['txt_bank']) ? $_POST['txt_bank']: null;
                }                 
                $sql = mysqli_query($conn1,"select id, kode_bank, nama_bank, curr, no_rek,CONCAT(nama_bank,' (',no_rek,')') nama_rek, id_coa, v_company, v_companyaddress, v_branch, v_bankaddress, v_swiftcode FROM masterbank");
                while ($row = mysqli_fetch_array($sql)) {
                    $data = $row['id'];
                    $data2 = $row['nama_rek'];
                    if($row['id'] == $_POST['txt_bank']){
                        $isSelected = ' selected="selected"';
                    }else{
                        $isSelected = '';
                    }
                    echo '<option value="'.$data.'"'.$isSelected.'">'. $data2 .'</option>';    
                }?>
                </select>
            </div>

            <div class="col-md-2 mb-3">            
            <label for="txt_pph"><b>PPH</b></label>
            <select class="form-control select2bs4" name="txt_pph" id="txt_pph" data-dropup-auto="false" data-live-search="true" >
                <option value="NA">NA</option>
                <option value="PPh 21">PPh 21</option>
                <option value="PPh 23">PPh 23</option>
                <option value="4 Ayat 2">4 Ayat 2</option>
                </select>
            </div>

            <div class="col-md-2 mb-3">            
            <label for="type_so"><b>Type SO</b></label>
            <select class="form-control select2bs4" name="type_so" id="type_so" data-dropup-auto="false" data-live-search="true" >
                <option value="" disabled selected="true">Select Type SO</option>                                                
                <option value="FOB">FOB</option>
                <option value="CMT">CMT</option>
            </select>
            </div>

            <div class="col-md-3 mb-3">
            <label for="from_hris" class="col-form-label"><b>SO Number:</b></label>
            <br>            
              <input style="border: 0;line-height: 1;padding: 5px 5px;font-size: 1rem;text-align: center;color: #fff;text-shadow: 1px 1px 1px #000;border-radius: 4px;background-color: rgb(95, 158, 160);" type="button" id="mysupp" name="v" data-toggle="modal" value="Select" class="btn btn-primary btn-lg" style="width: 100%;">    
            </div>        


           
                                        
    </div>
</form>
<div class="form-row">
    <div class="modal fade" id="mymodal" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div class="modal-dialog modal-xl">
        <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-times"></span></button>
        <h4 class="modal-title" id="Heading">Add Data</h4>
        </div>
          <div class="modal-body">
          <div class="form-group">
            <form id="modal-form2" method="post">
                <div class="form-row">
                    <div class="col-md-6 mb-3">
                        <label style="padding-left: 10px;" for="namasupp"><b>Customer</b></label>
              <input type="text" style="font-size: 14px;" class="form-control" id="mdl_customer" name="mdl_customer" 
                value="" readonly>
                <input type="hidden" style="font-size: 14px;" class="form-control" id="mdl_idcustomer" name="mdl_idcustomer" 
                value="" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="padding-left: 10px;" for="namasupp"><b>Select Customer</b></label>
              <select class="form-control select2bs4" name="namasupp" id="namasupp" data-dropup-auto="false" data-live-search="true">
                <option value="" disabled selected="true">select</option>                
                <?php 
                $sql = mysqli_query($conn1,"select DISTINCT alamat,Id_Supplier, UPPER(Supplier) As Supplier FROM mastersupplier WHERE tipe_sup = 'C' and id_supplier != '1006' order by supplier asc");
                while ($row = mysqli_fetch_array($sql)) {
                    $data2 = $row['Id_Supplier'];
                    $data = $row['Supplier'];
                    if($row['Id_Supplier'] == $_POST['namasupp']){
                        $isSelected = ' selected="selected"';
                    }else{
                        $isSelected = '';
                    }
                    echo '<option value="'.$data2.'"'.$isSelected.'">'. $data .'</option>';    
                }?>
                </select>
                    </div>
                    <div class="col-md-6 mb-3">
                     <label><b>SO Date</b></label>
                <div class="input-group-append">           
                <input type="text" style="font-size: 14px;" class="form-control tanggal" id="startdate_bpb" name="startdate_bpb" 
                value="<?php
                $startdate_bpb ='';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                  $startdate_bpb = date("Y-m-d",strtotime($_POST['startdate_bpb']));
                }
                if(!empty($_POST['startdate_bpb'])) {
                    echo $_POST['startdate_bpb'];
                }
                else{
                    echo date("d-m-Y");
                } ?>" 
                placeholder="Tanggal Awal">

                <label class="col-md-1" for="end_date"><b>-</b></label>
                <input type="text" style="font-size: 14px;" class="form-control tanggal" id="enddate_bpb" name="enddate_bpb" 
                value="<?php
                $enddate_bpb ='';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                  $enddate_bpb = date("Y-m-d",strtotime($_POST['enddate_bpb']));
                }
                if(!empty($_POST['enddate_bpb'])) {
                    echo $_POST['enddate_bpb'];
                }
                else{
                    echo date("d-m-Y");
                } ?>" 
                placeholder="Tanggal Akhir">
                </div>
                </div> 

                <div class="col-md-6 mb-3">
                     <label><b>Search</b></label>
                <div class="input-group-append">           
                <input 
                style="border: 0;
                    line-height: 1;
                    padding: 10px 10px;
                    font-size: 1rem;
                    text-align: center;
                    color: #fff;
                    text-shadow: 1px 1px 1px #000;
                    border-radius: 6px;
                    background-color: rgb(95, 158, 160);"
                type="button" id="send2" name="send2" value="Search"> 
                </div>
                </div> 
              
                </div> 
                
               <!--  <div id="details" class="modal-body col-12" style="font-size: 12px; padding: 0.5rem;"></div> -->
            <div class="tableFix" style="height: 300px;">
                <table id="table-so" class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size: 12px;text-align:center;">
                    <thead>
                        <tr>                       
                            <th style="width:5%;">Cek</th>
                            <th style="width:20%;">SO Number</th>
                            <th style="width:15%;">SO Date</th> 
                            <th style="width:25%;">Customer</th>
                            <th style="width:15%;">Buyer Number</th>
                            <th style="width:10%;">SO Type</th>
                            <th style="width:10%;">ID Sj</th>                                                         
                        </tr>
                    </thead>
                    <tbody id="details">
                    </tbody>
                </table> 
            </div>
                <br>
                <!-- <div id="details_sj" class="modal-body col-12" style="font-size: 12px; padding: 0.5rem;"></div> -->
            <div class="tableFix" style="height: 300px;">
                <table id="table-sj" class="table table-striped table-bordered text-nowrap" cellspacing="0" width="100%" style="font-size: 12px;text-align:center;">
                    <thead>
                        <tr>                       
                            <th>Bppb ID</th>
                            <th>SO Number</th>
                            <th>Bppb Number</th>
                            <th>SJ Date</th>
                            <th>Shipping Number</th>
                            <th>WS#</th>
                            <th>Styleno</th>
                            <th>Produk Group</th>
                            <th>Produk Item</th>
                            <th>Color</th>
                            <th>Size</th>
                            <th>Curr</th>
                            <th>UOM</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Discount (%)</th>
                            <th>Total Price</th>
                            <th>Cek</th>                                                        
                        </tr>
                    </thead>
                    <tbody id="details_sj">
                    </tbody>
                </table> 
            </div>
            <br>
            <div class="form-row col">
            <div class="col-md-6">
                </br>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="mdl_total" class="col-sm-4 col-form-label">Total</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="mdl_total" name="mdl_total" style="text-align:right;" placeholder="0.00" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="mdl_discount" class="col-sm-4 col-form-label">Discount</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="mdl_discount" name="mdl_discount" style="text-align:right;" placeholder="0.00" readonly>
                        <input type="hidden" class="form-control" id="grade_nya" name="grade_nya" readonly>
                        <input type="hidden" class="form-control" id="tanggal_nya" name="tanggal_nya" readonly>
                        <input type="hidden" class="form-control" id="curr_nya" name="curr_nya" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="mdl_dp" class="col-sm-4 col-form-label">Down Payment</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="mdl_dp" name="mdl_dp" style="text-align:right;" placeholder="0.00" onkeypress="javascript:return isNumber(event)" oninput="modal_input_dp(value)" autocomplete="off">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="mdl_dpcbd" class="col-sm-4 col-form-label">DP/CBD from Invoice</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="mdl_dpcbd" name="mdl_dpcbd" style="text-align:right;" placeholder="0.00" onkeypress="javascript:return isNumber(event)" oninput="modal_input_dpcbd(value)" autocomplete="off">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="mdl_return" class="col-sm-4 col-form-label">Return</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="mdl_return" name="mdl_return" style="text-align:right;" placeholder="0.00" onkeypress="javascript:return isNumber(event)" oninput="modal_input_retur(value)" autocomplete="off">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="mdl_twot" class="col-sm-4 col-form-label">Total With Out Tax</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="mdl_twot" name="mdl_twot" style="text-align:right;" placeholder="0.00" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="custom-control custom-checkbox col-sm-2">
                        <input class="custom-control-input" type="checkbox" id="check_vat" name="check_vat" onclick="modal_input_vat()">
                        <label for="check_vat" class="custom-control-label">Vat 10%</label>
                    </div>
                    <div class="custom-control custom-checkbox col-sm-2">
                        <input class="custom-control-input" type="checkbox" id="check_vat_baru" name="check_vat_baru" onclick="modal_input_vat_baru()">
                        <label for="check_vat_baru" class="custom-control-label">Vat 11%</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="mdl_vat" name="mdl_vat" style="text-align:right;" placeholder="0.00" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="mdl_grandtotal" class="col-sm-4 col-form-label">Grand Total</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="mdl_grandtotal" name="mdl_grandtotal" style="text-align:right;" placeholder="0.00" readonly>
                    </div>
                </div>
            </div>
        </div>
  
                <div class="modal-footer">
                    <div class="col-md-2 mb-3">
                    <input type="button" id="savesj" name="savesj" class="btn btn-info btn-sm" style="width: 100%;" value="Save" onclick="duplicate_data_so()">
                </div>
                <div class="col-md-10 mb-3">
                </div>
                </div>           
            </form>
        </div>
      </div>
    </div>
  </div>
 </div>

      <!-- /.modal-dialog --> 
    </div>
    <div class="box body">
        <div class="row">
        
            <div class="col-md-12">

            <div class="tableFix" style="height: 300px;">        
                <table id="datatable" class="table table-striped table-bordered text-nowrap" style="font-size: 12px;" role="grid" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>ID Bppb</th>
                            <th>SO Number</th>
                            <th>Bppb Number</th>
                            <th>Sj_Date</th>
                            <th>Shipping Number</th>
                            <th>WS#</th>
                            <th>Styleno</th>
                            <th>Product Group</th>
                            <th>Product Item</th>
                            <th>Color</th>
                            <th>Size</th>
                            <th>Curr</th>
                            <th>UOM</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Discount (%)</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>

            <tbody id="detailsj">
            
            </tbody>                    
            </table>
            </div>                    
<div class="box footer">   
        <form id="form-simpan">
           <div class="form-row col">
            <div class="col-md-4">
                </br>
            </div>
            <div class="col-md-4">
            </div>
            <div class="col-md-4">
                </br>
                <div class="input-group" >
                <label for="nama_supp" class="col-form-label" style="width: 140px;"><b>Total</b></label>
                <input type="text" class="form-control" id="total" name="total" style="text-align:right;" placeholder="0.00" readonly>
                </div>

                <br>
                <div class="input-group" >
                <label for="nama_supp" class="col-form-label" style="width: 140px;"><b>Discount</b></label>
                <input type="text" class="form-control" id="discount" name="discount" style="text-align:right;" placeholder="0.00" readonly>
                </div>

                <br>
                <div class="input-group" >
                <label for="nama_supp" class="col-form-label" style="width: 140px;"><b>Down Payment</b></label>
                <input type="text" class="form-control" id="dp" name="dp" style="text-align:right;" placeholder="0.00" readonly>
                </div>

                <br>
                <div class="input-group" >
                <label for="nama_supp" class="col-form-label" style="width: 140px;"><b>Return</b></label>
                <input type="text" class="form-control" id="return" name="return" style="text-align:right;" placeholder="0.00" readonly>
                </div>

                <br>
                <div class="input-group" >
                <label for="nama_supp" class="col-form-label" style="width: 140px;"><b>Total With Out Tax</b></label>
                <input type="text" class="form-control" id="twot" name="twot" style="text-align:right;" placeholder="0.00" readonly>
                </div>

                <br>
                <div class="input-group" >
                <label for="nama_supp" class="col-form-label" style="width: 140px;"><b>VAT</b></label>
                <input type="text" class="form-control" id="vat" name="vat" style="text-align:right;" placeholder="0.00" readonly>
                </div>

                <br>
                <div class="input-group" >
                <label for="nama_supp" class="col-form-label" style="width: 140px;"><b>Grand Total</b></label>
                <input type="text" class="form-control" id="grandtotal" name="grandtotal" style="text-align:right;" placeholder="0.00" readonly>
                <input type="hidden" class="form-control" id="keterangan" name="keterangan" readonly>
                </div>

            </br>   
            </div>
            <div class="col-md-3 mb-3">                              
            <br>
        </form>
            <button type="button" style="border-radius: 6px" class="btn-outline-primary btn-sm" name="simpan" id="simpan" onclick="asyncCall();"><span class="fa fa-floppy-o"></span> Save</button>                
            <button type="button" style="border-radius: 6px" class="btn-outline-danger btn-sm" name="batal" id="batal" onclick="location.href='list-invoice.php'"><span class="fa fa-angle-double-left"></span> Back</button>               
            </div>
            </div>                                    
        </div>

<div class="modal fade" id="modal-simpan-invoice">
    <div style="width: 450px;" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirm Post Invoice</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <!-- No Invoice -->
                <div class="form-group row">
                    <label for="id_inv" class="col-sm-5 col-form-label">Save Invoice Number :</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="no_inv_post" name="no_inv_post" style="border:none;" readonly>
                    </div>
                </div>
                <!-- ID Invoice, Pph -->
                <input type="hidden" class="form-control" id="id_inv_post" name="id_inv_post" readonly>
                <input type="hidden" class="form-control" id="pph_post" name="pph_post" readonly>
                <input type="hidden" class="form-control" id="txt_shipp" name="txt_shipp" readonly>
                <input type="hidden" class="form-control" id="txt_type_so" name="txt_type_so" readonly>
                <input type="hidden" class="form-control" id="txt_type" name="txt_type" readonly>
                <input type="hidden" class="form-control" id="txt_cust_ctg" name="txt_cust_ctg" readonly>
                <input type="hidden" class="form-control" id="txt_grade" name="txt_grade" readonly>
                <input type="hidden" class="form-control" id="txt_inv_date" name="txt_inv_date" readonly>
                <input type="hidden" class="form-control" id="txt_vat" name="txt_vat" readonly>
                <input type="hidden" class="form-control" id="txt_pot" name="txt_pot" readonly>
                <input type="hidden" class="form-control" id="txt_curr" name="txt_curr" readonly>

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary toastrDefaultSuccess" onclick="save_invoice()">Save Change</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mymodalpo" data-target="#mymodalpo" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-times"></span></button>
        <h4 class="modal-title" id="txt_po"></h4>
        </div>
        <div class="container">
        <div class="row">
          <div id="txt_tgl_po" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>
          <div id="txt_supp2" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>        
          <div id="txt_curr" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>                            
          <div id="details" class="modal-body col-12" style="font-size: 12px; padding: 0.5rem;"></div>          
        </div>
        </div>
        </div>
    <!-- /.modal-content --> 
  </div>
      <!-- /.modal-dialog --> 
    </div>         
                                
</div><!-- body-row END -->
</div>
</div>

  <!-- Bootstrap core JavaScript -->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script language="JavaScript" src="../css/4.1.1/datatables.min.js"></script>
  <script language="JavaScript" src="../css/4.1.1/bootstrap-datepicker.js"></script>
  <script language="JavaScript" src="../css/4.1.1/bootstrap-select.min.js"></script>
  <script language="JavaScript" src="../css/4.1.1/select2.full.min.js"></script>


<script>
    $(function() {
      //Initialize Select2 Elements
      $('.select2').select2()
      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      })
    });
  </script>

<script>
  // Hide submenus
$('#body-row .collapse').collapse('hide'); 

// Collapse/Expand icon
$('#collapse-icon').addClass('fa-angle-double-left'); 

// Collapse click
$('[data-toggle=sidebar-colapse]').click(function() {
    SidebarCollapse();
});

function SidebarCollapse () {
    $('.menu-collapsed').toggleClass('d-none');
    $('.sidebar-submenu').toggleClass('d-none');
    $('.submenu-icon').toggleClass('d-none');
    $('#sidebar-container').toggleClass('sidebar-expanded sidebar-collapsed');
    
    // Treating d-flex/d-none on separators with title
    var SeparatorTitle = $('.sidebar-separator-title');
    if ( SeparatorTitle.hasClass('d-flex') ) {
        SeparatorTitle.removeClass('d-flex');
    } else {
        SeparatorTitle.addClass('d-flex');
    }
    
    // Collapse/Expand icon
    $('#collapse-icon').toggleClass('fa-angle-double-left fa-angle-double-right');
}
</script>

<script>
    $(document).ready(function() {
    $('#mytable').dataTable();
    
     $("[data-toggle=tooltip]").tooltip();
    
} );
</script>
<script type="text/javascript">
async function asyncCall() {
  add_data_invoice();
  const result = await resolveAfter5Seconds();
  console.log(result);
}

  function add_data_invoice() { 

    let no_invoice   = $('[name="no_inv"]').val();
    let type_so      = $('[name="type_so"]').val();
    let top          = $('[name="top_type"]').val();
    let shipp        = $('[name="shipp"]').val();
    let type         = $('[name="txt_type"]').val();
    let id_cust      = $('[name="id_customer"]').val();
    let cust         = $('[name="customer"]').val();
    let grade        = $('[name="grade"]').val();
    let inv_date     = $('[name="inv_date"]').val();
    let vat          = $('[name="vat"]').val();
    let diskon       = $('[name="discount"]').val();
    let curr         = $('[name="inv_curr"]').val();
    var kata1        = "PENJUALAN";
    var kata2        = "KE";

        if (id_cust == '524' || id_cust == '804' || id_cust == '366') {
            cust_ctg = 'Related Party';
        }else{
            cust_ctg = 'Third Party';
        }

       if (no_invoice == "") {
          alert("Invoice Number is required");
          $("#no_inv").focus();    
          return false;
        }

        if (top == "") {
          alert("Top is required");
          $("#top").focus();    
          return false;
        }  
    
    $no_invoice = $('[name="no_inv"]').val()   
    $id_inv     = $('[name="id_inv"]').val()    
    $pph     = $('[name="pph"]').val()
    var keter = kata1 +' '+type_so +' '+kata2 +' '+cust;

    getcoa();
    getcoa_credit();
    getcoa_dp();
    getcoa_pot();
    getcoa_ppn();
    getrate();
    //
    $('[name="no_inv_post"]').val($no_invoice);
    $('[name="id_inv_post"]').val($id_inv);
    $('[name="pph_post"]').val($pph);
    $('[name="keterangan"]').val(keter);

    $('[name="txt_shipp"]').val(shipp);
    $('[name="txt_type_so"]').val(type_so);
    $('[name="txt_type"]').val(type);
    $('[name="txt_cust_ctg"]').val(cust_ctg);
    $('[name="txt_grade"]').val(grade);
    $('[name="txt_inv_date"]').val(inv_date);
    $('[name="txt_vat"]').val(vat);
    $('[name="txt_pot"]').val(diskon);
    $('[name="txt_curr"]').val(curr);
    console.log(keter);

    // $('#modal-simpan-invoice').modal('show') 
        
}

function resolveAfter5Seconds() {
  return new Promise(resolve => {
    setTimeout(() => {
    let no_invoice   = $('[name="no_inv"]').val();
    let type_so      = $('[name="type_so"]').val();
    let top          = $('[name="top_type"]').val();
    let shipp        = $('[name="shipp"]').val();
    let type         = $('[name="txt_type"]').val();
    let id_cust      = $('[name="id_customer"]').val();
    let grade        = $('[name="grade"]').val();
    let inv_date     = $('[name="inv_date"]').val();
    let vat          = $('[name="vat"]').val();
    let diskon       = $('[name="discount"]').val();
    let curr         = $('[name="inv_curr"]').val();

        if (id_cust == '524' || id_cust == '804' || id_cust == '366') {
            cust_ctg = 'Related Party';
        }else{
            cust_ctg = 'Third Party';
        }

       if (no_invoice == "") {
          return false;
        }

        if (top == "") {
          return false;
        }

     $('#modal-simpan-invoice').modal('show');
    }, 2000);
  });
}

function getcoa() 
{   

    let no_invoice   = $('[name="inv_number1"]').val();
    let type_so      = $('[name="type_so"]').val();
    let top          = $('[name="top"]').val();
    let shipp        = $('[name="shipp"]').val();
    let type         = $('[name="type"]').val();
    let id_cust      = $('[name="id_cust"]').val();
    let grade        = $('[name="grade"]').val();
    let inv_date     = $('[name="inv_date"]').val();
    let vat          = $('[name="vat"]').val();
    let diskon       = $('[name="discount"]').val();
    let curr         = $('[name="inv_curr"]').val();

        if (id_cust == '524' || id_cust == '804' || id_cust == '366') {
            cust_ctg = 'Related';
        }else{
            cust_ctg = 'Third';
        }

    console.log(type_so + ' ' + shipp + ' ' + cust_ctg + ' ' + grade);

    $.ajax({
            type: 'POST', 
            url: 'getcoadeb.php', 
            data: {'shipp':shipp,'type_so':type_so,'cust_ctg':cust_ctg,'grade':grade},
            success: function(response) { 
                $('#no_coa_deb').val(response); 
            }
        });

    $.ajax({
            type: 'POST', 
            url: 'getcoadeb2.php', 
            data: {'shipp':shipp,'type_so':type_so,'cust_ctg':cust_ctg,'grade':grade},
            success: function(response) { 
                $('#nama_coa_deb').val(response); 
            }
        });

}

function getcoa_credit() 
{   

    let no_invoice   = $('[name="inv_number1"]').val();
    let type_so      = $('[name="type_so"]').val();
    let top          = $('[name="top"]').val();
    let shipp        = $('[name="shipp"]').val();
    let type         = $('[name="type"]').val();
    let id_cust      = $('[name="id_cust"]').val();
    let grade        = $('[name="grade"]').val();
    let inv_date     = $('[name="inv_date"]').val();
    let vat          = $('[name="vat"]').val();
    let diskon       = $('[name="discount"]').val();
    let curr         = $('[name="inv_curr"]').val();

        if (id_cust == '524' || id_cust == '804' || id_cust == '366') {
            cust_ctg = 'Related';
        }else{
            cust_ctg = 'Third';
        }

    console.log(type_so + ' ' + shipp + ' ' + cust_ctg + ' ' + grade);

    $.ajax({
            type: 'POST', 
            url: 'getcoacre.php', 
            data: {'shipp':shipp,'type_so':type_so,'cust_ctg':cust_ctg,'grade':grade},
            success: function(response) { 
                $('#no_coa_cre').val(response); 
            }
        });

    $.ajax({
            type: 'POST', 
            url: 'getcoacre2.php', 
            data: {'shipp':shipp,'type_so':type_so,'cust_ctg':cust_ctg,'grade':grade},
            success: function(response) { 
                $('#nama_coa_cre').val(response); 
            }
        });

}

function getcoa_dp() 
{   

    let no_invoice   = $('[name="inv_number1"]').val();
    let type_so      = $('[name="type_so"]').val();
    let top          = $('[name="top"]').val();
    let shipp        = $('[name="shipp"]').val();
    let type         = $('[name="type"]').val();
    let id_cust      = $('[name="id_cust"]').val();
    let grade        = $('[name="grade"]').val();
    let inv_date     = $('[name="inv_date"]').val();
    let vat          = $('[name="vat"]').val();
    let diskon       = $('[name="discount"]').val();
    let curr         = $('[name="inv_curr"]').val();

        if (id_cust == '524' || id_cust == '804' || id_cust == '366') {
            cust_ctg = 'Related';
        }else{
            cust_ctg = 'Third';
        }

    console.log(type_so + ' ' + shipp + ' ' + cust_ctg + ' ' + grade);

    $.ajax({
            type: 'POST', 
            url: 'getcoadp.php', 
            data: {'shipp':shipp,'type_so':type_so,'cust_ctg':cust_ctg,'grade':grade},
            success: function(response) { 
                $('#no_coa_dp').val(response); 
            }
        });

    $.ajax({
            type: 'POST', 
            url: 'getcoadp2.php', 
            data: {'shipp':shipp,'type_so':type_so,'cust_ctg':cust_ctg,'grade':grade},
            success: function(response) { 
                $('#nama_coa_dp').val(response); 
            }
        });

}

function getcoa_pot() 
{   

    let no_invoice   = $('[name="inv_number1"]').val();
    let type_so      = $('[name="type_so"]').val();
    let top          = $('[name="top"]').val();
    let shipp        = $('[name="shipp"]').val();
    let type         = $('[name="type"]').val();
    let id_cust      = $('[name="id_cust"]').val();
    let grade        = $('[name="grade"]').val();
    let inv_date     = $('[name="inv_date"]').val();
    let vat          = $('[name="vat"]').val();
    let diskon       = $('[name="discount"]').val();
    let curr         = $('[name="inv_curr"]').val();

        if (id_cust == '524' || id_cust == '804' || id_cust == '366') {
            cust_ctg = 'Related';
        }else{
            cust_ctg = 'Third';
        }

    console.log(type_so + ' ' + shipp + ' ' + cust_ctg + ' ' + grade);

    $.ajax({
            type: 'POST', 
            url: 'getcoapot.php', 
            data: {'shipp':shipp,'type_so':type_so,'cust_ctg':cust_ctg,'grade':grade},
            success: function(response) { 
                $('#no_coa_pot').val(response); 
            }
        });

    $.ajax({
            type: 'POST', 
            url: 'getcoapot2.php', 
            data: {'shipp':shipp,'type_so':type_so,'cust_ctg':cust_ctg,'grade':grade},
            success: function(response) { 
                $('#nama_coa_pot').val(response); 
            }
        });

}

function getcoa_ppn() 
{   

    let no_invoice   = $('[name="inv_number1"]').val();
    let type_so      = $('[name="type_so"]').val();
    let top          = $('[name="top"]').val();
    let shipp        = $('[name="shipp"]').val();
    let type         = $('[name="type"]').val();
    let id_cust      = $('[name="id_cust"]').val();
    let grade        = $('[name="grade"]').val();
    let inv_date     = $('[name="inv_date"]').val();
    let vat          = $('[name="vat"]').val();
    let diskon       = $('[name="discount"]').val();
    let curr         = $('[name="inv_curr"]').val();

        if (id_cust == '524' || id_cust == '804' || id_cust == '366') {
            cust_ctg = 'Related';
        }else{
            cust_ctg = 'Third';
        }

    console.log(type_so + ' ' + shipp + ' ' + cust_ctg + ' ' + grade);

    $.ajax({
            type: 'POST', 
            url: 'getcoappn.php', 
            data: {'shipp':shipp,'type_so':type_so,'cust_ctg':cust_ctg,'grade':grade},
            success: function(response) { 
                $('#no_coa_ppn').val(response); 
            }
        });

    $.ajax({
            type: 'POST', 
            url: 'getcoappn2.php', 
            data: {'shipp':shipp,'type_so':type_so,'cust_ctg':cust_ctg,'grade':grade},
            success: function(response) { 
                $('#nama_coa_ppn').val(response); 
            }
        });
}

function getrate() 
{   

    let no_invoice   = $('[name="inv_number1"]').val();
    let type_so      = $('[name="type_so"]').val();
    let top          = $('[name="top"]').val();
    let shipp        = $('[name="shipp"]').val();
    let type         = $('[name="type"]').val();
    let id_cust      = $('[name="id_cust"]').val();
    let grade        = $('[name="grade"]').val();
    let inv_date     = $('[name="inv_date"]').val();
    let vat          = $('[name="vat"]').val();
    let diskon       = $('[name="discount"]').val();
    let curr         = $('[name="inv_curr"]').val();

    console.log(inv_date);

    $.ajax({
            type: 'POST', 
            url: 'getrate.php', 
            data: {'inv_date':inv_date},
            success: function(response) { 
                $('#inv_rate').val(response); 
            }
        });
}

function save_invoice() {
    
    let vat          = $('[name="vat"]').val();
    let diskon       = $('[name="discount"]').val();
    let dp           = $('[name="dp"]').val();
    update_invoice_header();
    at_debit_inv();
    if (diskon >= 1) {      
    at_pot_inv();
    }
    if (dp >= 1) {      
    at_dp_inv();
    }
    at_credit_inv();
    if (vat >= 1) {     
    at_ppn_inv();
    }
    simpan_invoice_detail();
    simpan_invoice_pot();

    //  
    $('#modal-simpan-invoice').modal('hide');
    
}

function update_invoice_header() { 
    
    var id_inv  = $('[name="id_inv"]').val()        
    var no_inv  = $('[name="no_inv"]').val()   
    var pph     = $('[name="txt_pph"]').val()   
    var id_top  = $('[name="top_type"]').val()            
    var id_bank = $('[name="txt_bank"]').val()
    var type_so = $('[name="type_so"]').val()
    var no_coa = $('[name="no_coa_deb"]').val()
    var nama_coa = $('[name="nama_coa_deb"]').val()
    var create_user = '<?php echo $user ?>';
    
    $.ajax({
            type:'POST',
            url:'update_invoice_header.php',
            data: {'id_inv':id_inv, 'no_inv':no_inv, 'pph':pph, 'id_top':id_top, 'id_bank':id_bank,  'type_so':type_so,'no_coa':no_coa, 'nama_coa':nama_coa, 'create_user':create_user},
            cache: 'false',
            close: function(e){
                e.preventDefault();
            },
            success: function(response){
                console.log(response);
                // $('#mymodal').modal('hide'); 
                
                // window.location = 'kontrabon.php';
                },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr);
                alert(xhr);
            }
        });

}

function at_debit_inv() { 
    
 var cust_ctg = '';
 var rate = 0;
 var total_idr = 0;

    var id_cust         = $('[name="id_cust"]').val();
    var buyer           = $('[name="cust"]').val();
    var inv_rate        = $('[name="inv_rate"]').val();
    var inv_curr        = $('[name="inv_curr"]').val();
    var total           = $('[name="grandtotal"]').val();
    var keterangan      = $('[name="keterangan"]').val();
    var no_inv          = $('[name="no_inv"]').val();
    var inv_date        = $('[name="inv_date"]').val();
    var no_coa          = $('[name="no_coa_deb"]').val();
    var nama_coa        = $('[name="nama_coa_deb"]').val();
    var create_user     = '<?php echo $user ?>';

    if (id_cust == '524' || id_cust == '804' || id_cust == '366') {
            cust_ctg = 'Related Party';
        }else{
            cust_ctg = 'Third Party';
        }

    if (inv_curr == 'IDR') {
        rate = 1;
        total_idr = total * rate;
    }else{
        rate = inv_rate;
        total_idr = total * rate;
    }


    $.ajax({
            type:'POST',
            url:'simpan_debit_inv.php',
            data: {'no_inv':no_inv, 'inv_date':inv_date, 'no_coa':no_coa, 'nama_coa':nama_coa, 'buyer':buyer, 'inv_curr':inv_curr,'rate':rate, 'total':total,'total_idr':total_idr, 'keterangan':keterangan, 'create_user':create_user},
            cache: 'false',
            close: function(e){
                e.preventDefault();
            },
            success: function(response){
                console.log(response);
                // $('#mymodal').modal('hide'); 
                
                // window.location = 'kontrabon.php';
                },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr);
                alert(xhr);
            }
        });    
                                                      
}

//ubah desember
function at_pot_inv() { 
    
 var cust_ctg = '';
 var rate = 0;
 var total_idr = 0;

    var id_cust         = $('[name="id_cust"]').val();
    var buyer           = $('[name="cust"]').val();
    var inv_rate        = $('[name="inv_rate"]').val();
    var inv_curr        = $('[name="inv_curr"]').val();
    var total           = $('[name="discount"]').val();
    var keterangan      = $('[name="keterangan"]').val();
    var no_inv          = $('[name="no_inv"]').val();
    var inv_date        = $('[name="inv_date"]').val();
    var no_coa          = $('[name="no_coa_pot"]').val();
    var nama_coa        = $('[name="nama_coa_pot"]').val();
    var create_user     = '<?php echo $user ?>';

    if (id_cust == '524' || id_cust == '804' || id_cust == '366') {
            cust_ctg = 'Related Party';
        }else{
            cust_ctg = 'Third Party';
        }

    if (inv_curr == 'IDR') {
        rate = 1;
        total_idr = total * rate;
    }else{
        rate = inv_rate;
        total_idr = total * rate;
    }


    $.ajax({
            type:'POST',
            url:'simpan_pot_inv.php',
            data: {'no_inv':no_inv, 'inv_date':inv_date, 'no_coa':no_coa, 'nama_coa':nama_coa, 'buyer':buyer, 'inv_curr':inv_curr,'rate':rate, 'total':total,'total_idr':total_idr, 'keterangan':keterangan, 'create_user':create_user},
            cache: 'false',
            close: function(e){
                e.preventDefault();
            },
            success: function(response){
                console.log(response);
                // $('#mymodal').modal('hide'); 
                
                // window.location = 'kontrabon.php';
                },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr);
                alert(xhr);
            }
        }); 

}

function at_dp_inv(){

 var cust_ctg = '';
 var rate = 0;
 var total_idr = 0;

    var id_cust         = $('[name="id_cust"]').val();
    var buyer           = $('[name="cust"]').val();
    var inv_rate        = $('[name="inv_rate"]').val();
    var inv_curr        = $('[name="inv_curr"]').val();
    var total           = $('[name="dp"]').val();
    var keterangan      = $('[name="keterangan"]').val();
    var no_inv          = $('[name="no_inv"]').val();
    var inv_date        = $('[name="inv_date"]').val();
    var no_coa          = $('[name="no_coa_dp"]').val();
    var nama_coa        = $('[name="nama_coa_dp"]').val();
    var create_user     = '<?php echo $user ?>';

    if (id_cust == '524' || id_cust == '804' || id_cust == '366') {
            cust_ctg = 'Related Party';
        }else{
            cust_ctg = 'Third Party';
        }

    if (inv_curr == 'IDR') {
        rate = 1;
        total_idr = total * rate;
    }else{
        rate = inv_rate;
        total_idr = total * rate;
    }


    $.ajax({
            type:'POST',
            url:'simpan_dp_inv.php',
            data: {'no_inv':no_inv, 'inv_date':inv_date, 'no_coa':no_coa, 'nama_coa':nama_coa, 'buyer':buyer, 'inv_curr':inv_curr,'rate':rate, 'total':total,'total_idr':total_idr, 'keterangan':keterangan, 'create_user':create_user},
            cache: 'false',
            close: function(e){
                e.preventDefault();
            },
            success: function(response){
                console.log(response);
                // $('#mymodal').modal('hide'); 
                
                // window.location = 'kontrabon.php';
                },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr);
                alert(xhr);
            }
        }); 

}

function at_credit_inv (){

 var cust_ctg = '';
 var rate = 0;
 var total_idr = 0;

    var id_cust         = $('[name="id_cust"]').val();
    var buyer           = $('[name="cust"]').val();
    var inv_rate        = $('[name="inv_rate"]').val();
    var inv_curr        = $('[name="inv_curr"]').val();
    var total           = $('[name="total"]').val();
    var keterangan      = $('[name="keterangan"]').val();
    var no_inv          = $('[name="no_inv"]').val();
    var inv_date        = $('[name="inv_date"]').val();
    var no_coa          = $('[name="no_coa_cre"]').val();
    var nama_coa        = $('[name="nama_coa_cre"]').val();
    var create_user     = '<?php echo $user ?>';

    if (id_cust == '524' || id_cust == '804' || id_cust == '366') {
            cust_ctg = 'Related Party';
        }else{
            cust_ctg = 'Third Party';
        }

    if (inv_curr == 'IDR') {
        rate = 1;
        total_idr = total * rate;
    }else{
        rate = inv_rate;
        total_idr = total * rate;
    }


    $.ajax({
            type:'POST',
            url:'simpan_credit_inv.php',
            data: {'no_inv':no_inv, 'inv_date':inv_date, 'no_coa':no_coa, 'nama_coa':nama_coa, 'buyer':buyer, 'inv_curr':inv_curr,'rate':rate, 'total':total,'total_idr':total_idr, 'keterangan':keterangan, 'create_user':create_user},
            cache: 'false',
            close: function(e){
                e.preventDefault();
            },
            success: function(response){
                console.log(response);
                // $('#mymodal').modal('hide'); 
                
                // window.location = 'kontrabon.php';
                },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr);
                alert(xhr);
            }
        });

}

function at_ppn_inv(){

 var cust_ctg = '';
 var rate = 0;
 var total_idr = 0;

    var id_cust         = $('[name="id_cust"]').val();
    var buyer           = $('[name="cust"]').val();
    var inv_rate        = $('[name="inv_rate"]').val();
    var inv_curr        = $('[name="inv_curr"]').val();
    var total           = $('[name="vat"]').val();
    var keterangan      = $('[name="keterangan"]').val();
    var no_inv          = $('[name="no_inv"]').val();
    var inv_date        = $('[name="inv_date"]').val();
    var no_coa          = $('[name="no_coa_ppn"]').val();
    var nama_coa        = $('[name="nama_coa_ppn"]').val();
    var create_user     = '<?php echo $user ?>';

    if (id_cust == '524' || id_cust == '804' || id_cust == '366') {
            cust_ctg = 'Related Party';
        }else{
            cust_ctg = 'Third Party';
        }

    if (inv_curr == 'IDR') {
        rate = 1;
        total_idr = total * rate;
    }else{
        rate = inv_rate;
        total_idr = total * rate;
    }


    $.ajax({
            type:'POST',
            url:'simpan_ppn_inv.php',
            data: {'no_inv':no_inv, 'inv_date':inv_date, 'no_coa':no_coa, 'nama_coa':nama_coa, 'buyer':buyer, 'inv_curr':inv_curr,'rate':rate, 'total':total,'total_idr':total_idr, 'keterangan':keterangan, 'create_user':create_user},
            cache: 'false',
            close: function(e){
                e.preventDefault();
            },
            success: function(response){
                console.log(response);
                // $('#mymodal').modal('hide'); 
                
                // window.location = 'kontrabon.php';
                },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr);
                alert(xhr);
            }
        });

}

</script>

<script type="text/javascript">
    function add_value_tgl() { 
    
    var cek_sj_1 = document.getElementsByName("mdl_cek_sj");
    var cek_tgl_1 = document.getElementsByName("cek_tgl");      
    //  
    for (var i = 0; i < cek_sj_1.length; i++) { 
        for (var i = 0; i < cek_tgl_1.length; i++) { 
            //  
            if (cek_sj_1[i].checked) {                              
                cek_tgl_sj(cek_tgl_1[i].value);
            } 
        }   
    }
}

function cek_tgl_sj($tgl){ 
        
    var cek_sj_2 = document.getElementsByName("mdl_cek_sj");
    var cek_tgl_2 = document.getElementsByName("cek_tgl");  
    //
    for (var i = 0; i < cek_sj_2.length; i++) {
       for (var i = 0; i < cek_tgl_2.length; i++) { 
          //
          if (cek_tgl_2[i].value != $tgl ) {            
              cek_sj_2[i].style.display = "none";       
          }
       }    
    }
}

    function modal_sum_total_sj(){

    add_value_tgl();
    
    var hanya_baca = document.getElementsByName("mdl_disc");
    var mdl_grade = document.getElementsByName("mdl_grade");
    var mdl_tgl_inv = document.getElementsByName("mdl_tgl_inv");
    var mdl_curr = document.getElementsByName("mdl_curr");      
    var input = document.getElementsByName("mdl_cek_sj");
    var total = 0;  
    var grade = '';
    var tgl_inv = '';
    var curr = '';  
    //      
    for (var i = 0; i < input.length; i++) {
      for (var i = 0; i < mdl_grade.length; i++) {  
        for (var i = 0; i < hanya_baca.length;  i++){
            if (input[i].checked) {     
                hanya_baca[i].readOnly = false;         
                total += parseFloat(input[i].value);
                grade = mdl_grade[i].value;
                tgl_inv = mdl_tgl_inv[i].value;
                curr = mdl_curr[i].value;
            } else {                
                hanya_baca[i].readOnly = true;
                hanya_baca[i].value = '';
                modal_input_discount();
                    
            }           
            
        }
     }

    }
    // alert(grade);
    var discount = $('[name="mdl_discount"]').val();
    var dp = $('[name="mdl_dp"]').val(); 
    var retur = $('[name="mdl_return"]').val(); 
    document.getElementsByName("grade_nya")[0].value = grade;   
    document.getElementsByName("tanggal_nya")[0].value = tgl_inv;
    document.getElementsByName("curr_nya")[0].value = curr;         
    document.getElementsByName("mdl_total")[0].value = total.toFixed(2);    
    document.getElementsByName("mdl_grandtotal")[0].value = (total-discount-dp-retur).toFixed(2);
    document.getElementsByName("mdl_twot")[0].value = (total-discount-dp-retur).toFixed(2);
            
}

function modal_input_discount(value){ 
        
    var input = document.getElementsByName("mdl_cek_sj");
    var total = 0;  
    //
    var arr = document.getElementsByName('mdl_disc');
    var tot = 0;
    for(var i = 0; i < arr.length; i++){
        for (var i = 0; i < input.length; i++) {
            if (input[i].checked) {
                   total = parseFloat(input[i].value);
               if(parseFloat(arr[i].value))
                   tot += parseFloat(arr[i].value) / 100 * total;      

             } 
        }           
    }       
    document.getElementById('mdl_discount').value = tot.toFixed(2);
    //Grand Total
    var total_sj = $('[name="mdl_total"]').val();
    var discount = $('[name="mdl_discount"]').val();
    var dp = $('[name="mdl_dp"]').val(); 
    var retur = $('[name="mdl_return"]').val();             
    document.getElementsByName("mdl_grandtotal")[0].value = (total_sj- discount-dp-retur).toFixed(2);
    document.getElementsByName("mdl_twot")[0].value = (total_sj- discount-dp-retur).toFixed(2);
                
}

function modal_input_dp(){ 
    var total_sj = $('[name="mdl_total"]').val();
    var discount = $('[name="mdl_discount"]').val();
    var dp = $('[name="mdl_dp"]').val(); 
    document.getElementsByName("mdl_grandtotal")[0].value = (total_sj- discount-dp).toFixed(2);
    document.getElementsByName("mdl_twot")[0].value = (total_sj- discount-dp).toFixed(2);
}

function modal_input_retur() { 
    var total_sj = $('[name="mdl_total"]').val();
    var discount = $('[name="mdl_discount"]').val();
    var dp = $('[name="mdl_dp"]').val(); 
    var retur = $('[name="mdl_return"]').val(); 
    document.getElementsByName("mdl_grandtotal")[0].value = (total_sj- discount-dp-retur).toFixed(2);
    document.getElementsByName("mdl_twot")[0].value = (total_sj- discount-dp-retur).toFixed(2);
}

//ubah september
function modal_input_dpcbd() { 
    var total_sj = $('[name="mdl_total"]').val();
    var discount = $('[name="mdl_discount"]').val();
    var dp = $('[name="mdl_dp"]').val() || 0; 
    var retur = $('[name="mdl_return"]').val() || 0; 
    var dp_cbd = $('[name="mdl_dpcbd"]').val(); 
    document.getElementsByName("mdl_grandtotal")[0].value = (total_sj- discount-dp-retur-dp_cbd).toFixed(2);
    document.getElementsByName("mdl_twot")[0].value = (total_sj- discount-dp-retur-dp_cbd).toFixed(2);
    // alert(dp_cbd);
}

function modal_input_vat(){ 

    var vat = 0.1; 
    //
    if ($('[name="check_vat"]').is(':checked')) {           
            var total_sj = $('[name="mdl_total"]').val();
            var discount = $('[name="mdl_discount"]').val();
            var dp = $('[name="mdl_dp"]').val(); 
            var retur = $('[name="mdl_return"]').val();     
            var twot = (total_sj- discount-dp-retur).toFixed(2) * vat;
            document.getElementsByName("mdl_vat")[0].value = (twot).toFixed(2);
            //document.getElementsByName("mdl_grandtotal")[0].value = (total_sj- discount-dp-retur-twot).toFixed(2);    
            document.getElementsByName("mdl_grandtotal")[0].value = (total_sj- discount-dp-retur+twot).toFixed(2);  
    } else {        
            var total_sj = $('[name="mdl_total"]').val();
            var discount = $('[name="mdl_discount"]').val();
            var dp = $('[name="mdl_dp"]').val(); 
            var retur = $('[name="mdl_return"]').val(); 
            document.getElementsByName("mdl_vat")[0].value = "0.00";
            document.getElementsByName("mdl_grandtotal")[0].value = (total_sj- discount-dp-retur).toFixed(2);
    }
}

function modal_input_vat_baru(){ 

    var vat = 0.11; 
    //
    if ($('[name="check_vat_baru"]').is(':checked')) {          
            var total_sj = $('[name="mdl_total"]').val();
            var discount = $('[name="mdl_discount"]').val();
            var dp = $('[name="mdl_dp"]').val(); 
            var retur = $('[name="mdl_return"]').val();     
            var twot = (total_sj- discount-dp-retur).toFixed(2) * vat;
            document.getElementsByName("mdl_vat")[0].value = (twot).toFixed(2);
            //document.getElementsByName("mdl_grandtotal")[0].value = (total_sj- discount-dp-retur-twot).toFixed(2);    
            document.getElementsByName("mdl_grandtotal")[0].value = (total_sj- discount-dp-retur+twot).toFixed(2);  
    } else {        
            var total_sj = $('[name="mdl_total"]').val();
            var discount = $('[name="mdl_discount"]').val();
            var dp = $('[name="mdl_dp"]').val(); 
            var retur = $('[name="mdl_return"]').val(); 
            document.getElementsByName("mdl_vat")[0].value = "0.00";
            document.getElementsByName("mdl_grandtotal")[0].value = (total_sj- discount-dp-retur).toFixed(2);
    }
}

function duplicate_data_so(){ 
    
    //Tambah Data Potongan Invoice
    var total       = $('[name="mdl_total"]').val();
    var discount    = $('[name="mdl_discount"]').val();
    var dp          = $('[name="mdl_dp"]').val(); 
    var retur       = $('[name="mdl_return"]').val(); 
    var twot        = $('[name="mdl_twot"]').val(); 
    var vat         = $('[name="mdl_vat"]').val(); 
    var grand_total = $('[name="mdl_grandtotal"]').val(); 
    var grade_nya   = $('[name="grade_nya"]').val();
    var tanggal_nya = $('[name="tanggal_nya"]').val();
    var curr_nya    = $('[name="curr_nya"]').val();     
    $('[name="total"]').val(total);
    $('[name="discount"]').val(discount);
    $('[name="dp"]').val(dp); 
    $('[name="return"]').val(retur); 
    $('[name="twot"]').val(twot); 
    $('[name="vat"]').val(vat); 
    $('[name="grandtotal"]').val(grand_total);
    $('[name="grade"]').val(grade_nya); 
    $('[name="inv_date"]').val(tanggal_nya); 
    $('[name="inv_curr"]').val(curr_nya);  
    // $('#mymodal').modal('hide'); 
    //Hapus Temporary Table Detail SJ
    //Simpan Table SJ Temp
    // delete_invoice_detail_temporary()  
    simpan_load_temp();  
    // simpan_invoice_detail_temporary(); 
    // load_invoice_detail_temporary(); 

}

async function simpan_load_temp(){
   var result = await simpan_invoice_detail_temporary()
   load_invoice_detail_temporary();
}
</script>

<script type="text/javascript">
    function getdatainv(){
        var no_inv = document.getElementById('no_inv').value;

        $.ajax({
            type: 'POST', 
            url: 'getdatainv.php', 
            data: {'no_inv':no_inv},
            success: function(response) { 
                $('#customer').val(response); 
            }
        });

        $.ajax({
            type: 'POST', 
            url: 'getdatainv7.php', 
            data: {'no_inv':no_inv},
            success: function(response) { 
                $('#id_customer').val(response); 
            }
        });

        $.ajax({
            type: 'POST', 
            url: 'getdatainv8.php', 
            data: {'no_inv':no_inv},
            success: function(response) { 
                $('#id_inv').val(response); 
            }
        });

        $.ajax({
            type: 'POST', 
            url: 'getdatainv2.php', 
            data: {'no_inv':no_inv},
            success: function(response) { 
                $('#shipp').val(response); 
            }
        });

        $.ajax({
            type: 'POST', 
            url: 'getdatainv3.php', 
            data: {'no_inv':no_inv},
            success: function(response) { 
                $('#dok_type').val(response); 
            }
        });

        $.ajax({
            type: 'POST', 
            url: 'getdatainv4.php', 
            data: {'no_inv':no_inv},
            success: function(response) { 
                $('#dok_number').val(response); 
            }
        });

        $.ajax({
            type: 'POST', 
            url: 'getdatainv5.php', 
            data: {'no_inv':no_inv},
            success: function(response) { 
                $('#txt_type').val(response); 
            }
        });

        $.ajax({
            type: 'POST', 
            url: 'getdatainv6.php', 
            data: {'no_inv':no_inv},
            success: function(response) { 
                $('#txt_value').val(response); 
            }
        });

        $.ajax({
            type: 'POST', 
            url: 'getdatatop.php', 
            data: {'no_inv':no_inv},
            success: function(response) { 
                $('#top_type').html(response); 
            }
        });

    }
</script>

<script type="text/javascript">
    function getperiode(){
        var id_top = document.getElementById('top_type').value;

        $.ajax({
            type: 'POST', 
            url: 'getperiode.php', 
            data: {'id_top':id_top},
            success: function(response) { 
                $('#time_periode').val(response); 
            }
        });
    }
</script>

<script type="text/javascript">
    $(document).ready(function () {
    $('.tanggal').datepicker({
        format: "dd-mm-yyyy",
        autoclose:true
    });
});
</script>

<script>
$(function() {
    $('.selectpicker').selectpicker();
});
</script>


<script type="text/javascript">
function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
  try {
    decimalCount = Math.abs(decimalCount);
    decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

    const negativeSign = amount < 0 ? "-" : "";

    let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
    let j = (i.length > 3) ? i.length % 3 : 0;

    return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
  } catch (e) {
    console.log(e)
  }
};
    $("input[type=radio]").click(function(){
    var sum_sub = 0;
    var sum_tax = 0;
    var ceklist = 0;
    var sum_total = 0;
    $("input[type=radio]").prop('disabled', true);             
    $("input[type=radio]:checked").each(function () {        
    var price = parseFloat($(this).closest('tr').find('td:eq(4)').attr('data-subtotal'),10) || 0;
    var tax = parseFloat($(this).closest('tr').find('td:eq(5)').attr('data-tax'),10) ||0;
    var pph = parseFloat($(this).closest('tr').find('td:eq(6) input').val(),10) ||0;
    var select_pi = $(this).closest('tr').find('td:eq(2) input');
    select_pi.prop('disabled', false);                    
    sum_sub += price;
    sum_tax += tax;
    sum_total = sum_sub + sum_tax;     
    });
    $("#subtotal").val(formatMoney(sum_sub));
    $("#pajak").val(formatMoney(sum_tax));    
    $("#total").val(formatMoney(sum_total));
    $("#select").val("1");                    
});        
</script>

<!--<script type="text/javascript">
    $("#form-data").on("click", "#send", function(){
        var datas= $('select[name=nama_supp] option').filter(':selected').val();
        var start_date= $('#start_date').attr('value');
        var end_date= $('#start_date').attr('value');
        $.ajax({
            type:'POST',
            url:'cek.php',
            data: {'nama_supp':datas, 'start_date': start_date, 'end_date': end_date},
            close: function(e){
                e.preventDefault();
            },
            success: function(response){
                console.log(response);
                alert(response);
            },
            error:  function (xhr, ajaxOptions, thrownError) {
                alert(xhr);
            }
        });
    });
</script>-->

<!-- <script type="text/javascript">
    $("input[type=radio]:checked").click(function(){        
    $(this).closest('tr').find('td:eq(2) input').prop('disabled', true);
    $(this).closest('tr').find('td:eq(2) input').val("");        
    })
</script> -->


<script type="text/javascript">
    $("#modal-form2").on("click", "#send2", function(){
        var namasupp = $('select[name=namasupp] option').filter(':selected').val(); 
        var customer = document.getElementById('mdl_idcustomer').value;
        var start_date = document.getElementById('startdate_bpb').value;
        var end_date = document.getElementById('enddate_bpb').value; 

        if (namasupp == '') {
            var nama_supp = customer;
        }else{
            var nama_supp = namasupp; 
        }     
             
        $.ajax({
            type:'POST',
            url:'cari_so.php',
            data: {'nama_supp':nama_supp, 'start_date':start_date, 'end_date':end_date},
            cache: 'false',
            close: function(e){
                e.preventDefault();
                return false; 
            },
            success: function(data){
                $('#details').html(data);
                // alert(data);  
                },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr);
                alert(xhr);
            }
        });             
 
    return false; 
    });


</script>

<script>
    function tambah_sj(val){
        $('#table-sj tbody tr').remove();
        $("input[type=checkbox]:checked").each(function () {
        var id_so = $(this).closest('tr').find('td:eq(6)').attr('value');

            $.ajax({
            type:'POST',
            url:'cari_sj.php',
            data: {'id_so':id_so},
            cache: 'false',
            close: function(e){
                e.preventDefault();
                return false; 
            },
            success: function(data){
                $('#details_sj').append(data);
                // alert(data);  
                },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr);
                alert(xhr);
            }
        }); 
                         
        });
        return false; 
    };

function simpan_invoice_detail_temporary(){
    $("#table-sj input[name='mdl_cek_sj']:checked").each(function() {
            var id_bppb = $(this).closest('tr').find('td:eq(0)').attr('value');
            var so_number = $(this).closest('tr').find('td:eq(1)').attr('value');
            var bppb_number = $(this).closest('tr').find('td:eq(2)').attr('value');
            var sj_date = $(this).closest('tr').find('td:eq(3)').attr('value');
            var shipp_number = $(this).closest('tr').find('td:eq(4)').attr('value');
            var ws = $(this).closest('tr').find('td:eq(5)').attr('value');
            var styleno = $(this).closest('tr').find('td:eq(6)').attr('value');
            var product_group = $(this).closest('tr').find('td:eq(7)').attr('value');
            var product_item = $(this).closest('tr').find('td:eq(8)').attr('value');
            var color = $(this).closest('tr').find('td:eq(9)').attr('value');
            var size = $(this).closest('tr').find('td:eq(10)').attr('value');
            var curr = $(this).closest('tr').find('td:eq(11)').attr('value');
            var uom = $(this).closest('tr').find('td:eq(12)').attr('value');
            var qty = $(this).closest('tr').find('td:eq(13)').attr('value');
            var unit_price = $(this).closest('tr').find('td:eq(14)').attr('value');
            var disc = parseFloat($(this).closest('tr').find('td:eq(15) input').val(),10) || 0;
            var total_price = $(this).closest('tr').find('td:eq(16)').attr('value');
            var create_user = '<?php echo $user ?>';
            

            $.ajax({
            type:'POST',
            url:'insert_invoice.php',
            data: {'id_bppb':id_bppb, 'so_number':so_number, 'bppb_number':bppb_number, 'sj_date':sj_date, 'shipp_number':shipp_number,  'ws':ws,
            'styleno':styleno, 'product_group':product_group, 'product_item':product_item, 'color':color, 'size':size, 'curr':curr,
            'uom':uom, 'qty':qty, 'unit_price':unit_price, 'disc':disc, 'total_price':total_price, 'create_user':create_user},
            cache: 'false',
            close: function(e){
                e.preventDefault();
            },
            success: function(response){
                console.log(response);
                // $('#mymodal').modal('hide'); 
                
                // window.location = 'kontrabon.php';
                },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr);
                alert(xhr);
            }
        });

        }); 
}


function load_invoice_detail_temporary(){
    return new Promise(resolve => {
    setTimeout(() => {
    var create_user = '<?php echo $user ?>';

    $.ajax({
        type:'POST',
        url:'load-inv-temp.php',
        data: {'create_user':create_user},
        cache: 'false',
        close: function(e){
            e.preventDefault();
            return false; 
        },
        success: function(data){
            $('#detailsj').html(data);
            // alert(data);  
            },
        error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr);
                alert(xhr);
        }
    }); 
    }, 1500);
    $('#mymodal').modal('hide');
  });
};

function simpan_invoice_detail(){

    $("#datatable tbody tr").each(function() {
            var id_book_invoice = document.getElementById('id_inv').value;
            var id_bppb = $(this).closest('tr').find('td:eq(0)').attr('value');
            var so_number = $(this).closest('tr').find('td:eq(1)').attr('value');
            var bppb_number = $(this).closest('tr').find('td:eq(2)').attr('value');
            var sj_date = $(this).closest('tr').find('td:eq(3)').attr('value');
            var shipp_number = $(this).closest('tr').find('td:eq(4)').attr('value');
            var ws = $(this).closest('tr').find('td:eq(5)').attr('value');
            var styleno = $(this).closest('tr').find('td:eq(6)').attr('value');
            var product_group = $(this).closest('tr').find('td:eq(7)').attr('value');
            var product_item = $(this).closest('tr').find('td:eq(8)').attr('value');
            var color = $(this).closest('tr').find('td:eq(9)').attr('value');
            var size = $(this).closest('tr').find('td:eq(10)').attr('value');
            var curr = $(this).closest('tr').find('td:eq(11)').attr('value');
            var uom = $(this).closest('tr').find('td:eq(12)').attr('value');
            var qty = $(this).closest('tr').find('td:eq(13)').attr('value');
            var unit_price = $(this).closest('tr').find('td:eq(14)').attr('value');
            var disc = $(this).closest('tr').find('td:eq(15)').attr('value');
            var total_price = $(this).closest('tr').find('td:eq(16)').attr('value');
            var create_user = '<?php echo $user ?>';

            $.ajax({
            type:'POST',
            url:'insert_invoice_det.php',
            data: {'id_book_invoice':id_book_invoice, 'id_bppb':id_bppb, 'so_number':so_number, 'bppb_number':bppb_number, 'sj_date':sj_date, 'shipp_number':shipp_number,  'ws':ws,'styleno':styleno, 'product_group':product_group, 'product_item':product_item, 'color':color, 'size':size, 'curr':curr,'uom':uom, 'qty':qty, 'unit_price':unit_price, 'disc':disc, 'total_price':total_price, 'create_user':create_user},
            cache: 'false',
            close: function(e){
                e.preventDefault();
            },
            success: function(response){
                console.log(response);
                // $('#mymodal').modal('hide'); 
                
                // window.location = 'kontrabon.php';
                },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr);
                alert(xhr);
            }
        });

        }); 

}

function simpan_invoice_pot() { 

    return new Promise(resolve => {     
        setTimeout(() => {
            var msg
            var data = [];      
            //
            var id_book_invoice = $('#id_inv').val();
            var total           = $('#total').val();
            var discount        = $('#discount').val();
            var dp              = $('#dp').val();
            var retur           = $('#return').val();
            var twot            = $('#twot').val();
            var vat             = $('#vat').val();
            var grand_total     = $('#grandtotal').val();
            

           $.ajax({
            type:'POST',
            url:'insert_invoice_pot.php',
            data: {'id_book_invoice':id_book_invoice, 'total':total, 'discount':discount, 'dp':dp, 'retur':retur, 'twot':twot,  'vat':vat,'grand_total':grand_total},
            cache: 'false',
            close: function(e){
                e.preventDefault();
            },
            success: function(response){
                console.log(response);
                var create_user = '<?php echo $user ?>';

                $.ajax({
                    type:'POST',
                    url:'delete-inv-temp.php',
                    data: {'create_user':create_user},
                    cache: 'false',
                    close: function(e){
                        e.preventDefault();
                        return false; 
                    },
                    success: function(data){
                        window.location = 'list-invoice.php';  
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        console.log(xhr);
                        alert(xhr);
                    }
                }); 
                

                },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr);
                alert(xhr);
            }
        });
            // $.ajax({                
            //     url: "simpan_invoice_pot/",
            //     type: "POST",
            //     data: fdata,
            //     dataType: "JSON",
            //     success: function (data) {
                    
            //         if (data.status) //if success close modal and reload ajax table
            //         {
            //             msg = 'Success Input Detail'
            //         } else {
            //             msg = 'Error Input Detail'
            //         }
            //         // Delete Table Invoice Detail Temporary
            //         delete_invoice_detail_temporary();
            //         // Print Preview Invoice
            //         let id_invoice = $('[name="id_inv"]').val();
            //         print_invoice(id_invoice);
            //         // Reload Page
            //         window.location.href = window.location.href;

            //     },
            //     error: function (jqXHR, textStatus, errorThrown) {
            //         msg = 'Error Input Detail' + jqXHR.text
            //     }
            // });

        }, 100);
    });

}
</script>


<script>
    $("#modal-form2").on("click", "#savebpb", function(){
        $("input[type=checkbox]:checked").each(function () {
        var no_bpb = $(this).closest('tr').find('td:eq(1)').attr('value');
        var tgl_bpb = $(this).closest('tr').find('td:eq(2)').attr('value');
        var supplier = $(this).closest('tr').find('td:eq(3)').attr('value');
        var curr = $(this).closest('tr').find('td:eq(4)').attr('value');
        var total = $(this).closest('tr').find('td:eq(5)').attr('value');
        var user = '<?php echo $user ?>';
             
        $.ajax({
            type:'POST',
            url:'insert_bpb_temp.php',
            data: {'no_bpb':no_bpb, 'tgl_bpb':tgl_bpb,'supplier':supplier, 'curr':curr, 'total':total, 'user':user},
            cache: 'false',
            close: function(e){
                e.preventDefault();
                // return false; 
            },
            success: function(response){
                // console.log(response);
                $('#mymodal').modal('toggle');
                $('#mymodal').modal('hide');
                 // alert(response);
            var user = '<?php echo $user ?>';
                $.ajax({
            type:'POST',
            url:'load_bpb_temp.php',
            data: { 'user':user},
            cache: 'false',
            close: function(e){
                e.preventDefault();
                return false; 
            },
            success: function(data){
                $('#tbl_bpb').html(data);
                $('#table-bpb tbody tr').remove(); 
                },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr);
                alert(xhr);
            }
            });
                },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr);
                alert(xhr);
            }
        });             
        });
 
                // return false; 
    });
</script>


<script>
    $("#form-data").on("click", "#mysupp", function(){
        var no_inv = $('select[name=no_inv] option').filter(':selected').val();
        var customer = document.getElementById('customer').value;
        var id_customer = document.getElementById('id_customer').value;
        var create_user = '<?php echo $user ?>';

        $.ajax({
        type:'POST',
        url:'delete-inv-temp.php',
        data: {'create_user':create_user},
        cache: 'false',
        close: function(e){
            e.preventDefault();
            return false; 
        },
        success: function(data){
            // $('#detailsj').html(data);
            // alert(data);  
            },
        error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr);
                alert(xhr);
        }
    }); 

        if (no_inv != '') {
            $('[name="mdl_customer"]').val(customer); 
            $('[name="mdl_idcustomer"]').val(id_customer);
            $('#mymodal').modal('show');
        }else{
            alert("Please Select Invoice");
            document.getElementById('no_inv').focus();
        }
                
        });
 
</script>

<script type="text/javascript">
    $("#form-simpan").on("click", "#simpan", function(){ 
    var unik_code = document.getElementById('unik_code').value;  
    var create_user = '<?php echo $user; ?>';
    var no_faktur = document.getElementById('no_faktur').value;
    var tgl_faktur = document.getElementById('tanggal_faktur').value;

        if (no_faktur != '' && tgl_faktur != '') {
        $.ajax({
            type:'POST',
            url:'log_bpb_faktur_inv.php',
            data: {'unik_code':unik_code, 'create_user':create_user},
            cache: 'false',
            close: function(e){
                e.preventDefault();
            },
            success: function(response){
        $("input[type=checkbox]:checked").each(function () {
        var no_dok = document.getElementById('no_dok').value;        
        var tgl_dok = document.getElementById('tanggal').value;
        var no_inv = '';
        var tgl_inv = '';
        var no_faktur = document.getElementById('no_faktur').value;
        var tgl_faktur = document.getElementById('tanggal_faktur').value;        
        var no_bpb = $(this).closest('tr').find('td:eq(1)').attr('value');                               
        var tgl_bpb = $(this).closest('tr').find('td:eq(2)').attr('value');
        var supplier = $(this).closest('tr').find('td:eq(3)').attr('value');
        var create_user = '<?php echo $user; ?>'; 
        var unik_code = document.getElementById('unik_code').value;        
        if(no_faktur != '' && tgl_faktur != '' && no_bpb != ''){
        $.ajax({
            type:'POST',
            url:'insert_bpb_fakturinv2.php',
            data: {'no_dok':no_dok, 'tgl_dok':tgl_dok, 'no_inv':no_inv, 'tgl_inv':tgl_inv, 'no_faktur':no_faktur, 'tgl_faktur':tgl_faktur, 'no_bpb':no_bpb, 'tgl_bpb':tgl_bpb, 'supplier':supplier, 'create_user':create_user, 'unik_code':unik_code},
            cache: 'false',
            close: function(e){
                e.preventDefault();
            },
            success: function(response){
                console.log(response);
                // alert("Data saved successfully");
                window.location = 'list-invoice.php';
                },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr);
                alert(xhr);
            }
        });
    } 
    });
                },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr);
                alert(xhr);
            }
        });
        } 

        if(document.getElementById('no_faktur').value == ''){
            alert("Please Input No Faktur");
            document.getElementById('no_faktur').focus();
        }else if(document.querySelectorAll("input[name='select[]']:checked").length == 0){
            alert("Please select data");
        }else{
            alert("Data saved successfully");
        }
    });
</script>

<!--<script type="text/javascript">
$("#select_all").click(function() {
  var c = this.checked;
  $(':checkbox').prop('checked', c);
});  
</script>-->

<script type="text/javascript">     
    $('table tbody tr').on('click', 'td:eq(1)', function(){                
    $('#mymodalpo').modal('show');
    var no_po = $(this).closest('tr').find('td:eq(1)').attr('value');
    var tgl_po = $(this).closest('tr').find('td:eq(3)').text();
    var supp = $(this).closest('tr').find('td:eq(8)').attr('value');
    var curr = $(this).closest('tr').find('td:eq(7)').attr('value');   

    $.ajax({
    type : 'post',
    url : 'ajaxpocbd.php',
    data : {'no_po': no_po},
    success : function(data){
    $('#details').html(data); //menampilkan data ke dalam modal
        }
    });         
        //make your ajax call populate items or what even you need
    $('#txt_po').html(no_po);
    $('#txt_tgl_po').html('Tgl PO : ' + tgl_po + '');
    $('#txt_supp2').html('Supplier : ' + supp + '');
    $('#txt_curr').html('Currency : ' + curr + '');                               
});

</script>

<!--<script>
    $(document).ready(){
        $('#mybpb').click(function){
            $('#mymodal').modal('show');
        }
    }
</script>-->
<!--<script>
$(document).ready(function() {   
    $("#send").click(function(e) {
        e.preventDefault();
        var datas= $(this).children("option:selected").val();
        $.ajax({
            type:"post",
            url:"cek.php",
            dataType: "json",
            data: {datas:datas},
            success: function(data){
                alert("Success: " + data);
            }
        });               
    });
</script>-->
<!--<script>
$(document).ready(function (){
    $("select.selectpicker").change(function(){
        var selectedbpb = $(this).children("option:selected").val();
        document.getElementById("bpbvalue").value = selectedbpb;             
    });
});
</script>-->
<!--<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>-->
  
</body>

</html>
