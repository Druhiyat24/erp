<?php include '../header2.php' ?>

    <!-- MAIN -->
    <div class="col p-4">
        <h4 class="text-center">FORM CREATE ALOKASI</h4>
<div class="box">
    <div class="box header">
<form id="form-data" method="post">
        <div class="form-row">
            <div class="col-md-3 mb-3">            
            <label for="customer"><b>No Alokasi</b></label>          
            <?php
            $sql = mysqli_query($conn2,"select a.no_alk, MAX(LEFT(a.no_alk, 4)) AS kd_max FROM tbl_alokasi a inner join tbl_log b on b.doc_number = a. no_alk WHERE YEAR(b.tanggal_doc) = YEAR(CURRENT_DATE()) AND MONTH(b.tanggal_doc) = MONTH(CURRENT_DATE())");
            $row = mysqli_fetch_array($sql);
            $kodeBarang = $row['kd_max'];
            $urutan = (int) substr($kodeBarang, 1, 4);
            $urutan++;
            $bln = date("m");
            $thn = date("y");
            $huruf = "/ALK/NAG/$bln$thn";
            $kodeBarang = sprintf("%04s", $urutan) . $huruf;

            echo'<input type="text" readonly style="font-size: 14px;" class="form-control" id="alk_number" name="alk_number" value="'.$kodeBarang.'">'
            ?>
            </div>

            <div class="col-md-3 mb-3">            
            <label for="customer"><b>Date</b></label>          
            <div class="input-group">
                <input type="text" readonly style="font-size: 15px;" name="alo_date" id="alo_date" class="form-control" 
            value="<?php 
            if(!empty($_POST['tgl_doc'])) {
                echo $_POST['tgl_doc'];
            }
            else{
                echo date("d-m-Y");
            } ?>" autocomplete='off'>
                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
            </div>
            </div>

            <div class="col-md-3 mb-3">            
            <label for="customer"><b>Customer</b></label>          
            <input type="text" style="font-size: 14px;" name="cust" id="cust" class="form-control" 
            value="" readonly>
            <input type="hidden" class="form-control" id="customer" name="customer" readonly>
            </div>

            <div class="col-md-3 mb-3">            
            <label for="noftrcbd"><b>Doc Number</b></label>
            <select class="form-control select2bs4" name="doc_number" id="doc_number" data-dropup-auto="false" data-live-search="true" onchange="getdataalk()">
                <option value="" disabled selected="true">Select No Bank In</option>                                                
                <?php
                $no_inv ='';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $no_inv = isset($_POST['no_inv']) ? $_POST['no_inv']: null;
                }                 
                $sql = mysqli_query($conn1,"select * from (select a.doc_num,a.date,c.Id_Supplier,a.customer,b.id as id_bank,a.bank,a.akun,a.curr,
                        if(a.curr = 'USD',if(((a.amount) - (select DISTINCT sum((k.eqp_idr - k.sisa) /k.rate) from sb_alokasi k left join sb_bankin_arcollection d on d.doc_num = k.doc_number where k.doc_number = a.doc_num)) is null,a.amount,Round((a.amount) - (select DISTINCT sum((k.eqp_idr - k.sisa) /k.rate) from sb_alokasi k left join sb_bankin_arcollection d on d.doc_num = k.doc_number where k.doc_number = a.doc_num),2)),if(((a.amount) - (select DISTINCT sum(k.eqp_idr - k.sisa) from sb_alokasi k left join sb_bankin_arcollection d on d.doc_num = k.doc_number where k.doc_number = a.doc_num)) is null,a.amount,((a.amount) - (select DISTINCT sum(k.eqp_idr - k.sisa) from sb_alokasi k left join sb_bankin_arcollection d on d.doc_num = k.doc_number where k.doc_number = a.doc_num)))) as amount,a.rate,
                            if((a.eqv_idr - (select DISTINCT sum(k.eqp_idr - k.sisa) from sb_alokasi k left join sb_bankin_arcollection d on d.doc_num = k.doc_number where k.doc_number = a.doc_num)) is null,a.eqv_idr,(a.eqv_idr - (select DISTINCT sum(k.eqp_idr - k.sisa) from sb_alokasi k left join sb_bankin_arcollection d on d.doc_num = k.doc_number where k.doc_number = a.doc_num)))as eqv_idr 
                            from sb_bankin_arcollection a inner join masterbank b on b.no_rek = a.akun left join mastersupplier c on c.Supplier = a.customer where a.ref_data = 'AR Collection' and a.status = 'Approved' and c.tipe_sup = 'C') a where a.amount > 0");

                while ($row = mysqli_fetch_array($sql)) {
                    $data = $row['doc_num'];
                    if($row['doc_num'] == $_POST['doc_number']){
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

            <div class="col-md-3 mb-3">            
            <label for="customer"><b>Bank</b></label>          
            <input type="text" style="font-size: 14px;" name="id_bank" id="id_bank" class="form-control" 
            value="" readonly>
            <input type="hidden" class="form-control" id="bank" name="bank" readonly>
            </div>

            <div class="col-md-3 mb-3">            
            <label for="customer"><b>Account</b></label>          
            <input type="text" style="font-size: 14px;" name="acc" id="acc" class="form-control" 
            value="" readonly>
            </div>

            <div class="col-md-3 mb-3">            
            <label for="customer"><b>Currency</b></label>          
            <input type="text" style="font-size: 14px;" name="currn" id="currn" class="form-control" 
            value="" readonly>
            </div>

            <div class="col-md-3 mb-3">            
            <label for="txt_pph"><b>Payment With</b></label>
            <select class="form-control select2bs4" name="pay_type" id="pay_type" data-dropup-auto="false" data-live-search="true" onchange="changerate()">
                <option value="IDR">IDR</option>
                <option value="USD">USD</option>
                </select>
            </div>

            <div class="col-md-3 mb-3">            
            <label for="customer"><b>Rate</b></label>
            <div class="input-group">          
            <input type="text" style="font-size: 14px;" name="rate" id="rate" class="form-control" 
            value="" onkeypress="javascript:return isNumber(event)" oninput="input_rate(value)" autocomplete="off" placeholder="0.00" readonly>
            <input type="text" style="font-size: 14px;" name="pl_rate" id="pl_rate" class="form-control" 
            value="" placeholder="0.00" readonly>
            <input type="hidden" style="font-size: 14px;" name="rate_h" id="rate_h" class="form-control" 
            value="" readonly>
            </div>
            </div>

            <div class="col-md-3 mb-3">            
            <label for="customer"><b>Amount</b></label>          
            <input type="hidden" style="font-size: 14px;" name="am_awal" id="am_awal" class="form-control" 
            value="" onkeypress="javascript:return isNumber(event)" oninput="input_awal(value)" autocomplete="off" placeholder="0.00" readonly>
            <input type="text" style="font-size: 14px;" name="pl_awal" id="pl_awal" class="form-control" 
            value="" placeholder="0.00" readonly>
            </div>

            <div class="col-md-3 mb-3">            
            <label for="customer"><b>Equivalent IDR</b></label>          
            <input type="text" style="font-size: 14px;" name="am_akhir" id="am_akhir" class="form-control" 
            value=""  autocomplete="off" placeholder="0.00" readonly>
            <input type="hidden" style="font-size: 14px;" name="pl_akhir" id="pl_akhir" class="form-control" 
            value="" placeholder="0.00" readonly>
            </div>

            <div class="col-md-3 mb-3">
            <label for="from_hris" ><b>Add Data:</b></label>
            <br>            
              <input style="border: 0;line-height: 1.3;padding: 5px 5px;font-size: 1rem;text-align: center;color: #fff;text-shadow: 1px 1px 1px #000;border-radius: 4px;background-color: rgb(95, 158, 160);" type="button" data-toggle="modal" value="Add Data" class="btn btn-primary" style="width: 100%;" onclick="caridataalk()">    
            </div>        


           
                                        
    </div>
</form>
<div class="form-row">
    <div class="modal fade" id="modal-add-so" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
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
                    <div class="col-md-5 mb-3">
                        <label style="padding-left: 10px;" for="namasupp"><b>Customer</b></label>
                            <input type="hidden" class="form-control float-right" id="custmr" name="custmr" readonly>
                            <input type="text" class="form-control float-right" id="nama_custmr" name="nama_custmr" readonly>
                            <input type="hidden" class="form-control float-right" id="rates" name="rates" readonly>
                            <input type="hidden" class="form-control float-right" id="pwith" name="pwith" readonly>
                            <input type="hidden" class="form-control float-right" id="id_custm" name="id_custm" readonly>
                    </div>
                    <div class="col-md-5 mb-3">
                     <label><b>Invoice Date</b></label>
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

                <div class="col-md-2 mb-3">
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
            <div class="tableFix" style="height: 250px;">
                <table id="table-inv-alok" class="table table-striped table-bordered text-nowrap" cellspacing="0" width="100%" style="font-size: 12px;text-align:center;">
                    <thead>
                        <tr>                       
                            <th>Reference Number</th>
                            <th>Date</th>
                            <th>Due Date</th>
                            <th>Currency</th>
                            <th>Total</th>
                            <th>Equivalent IDR</th>
                            <th>Amount</th>
                            <th>Cek</th>                                                      
                        </tr>
                    </thead>
                    <tbody id="details">
                    </tbody>
                </table> 
            </div>
                <br>
                <!-- <div id="details_sj" class="modal-body col-12" style="font-size: 12px; padding: 0.5rem;"></div> -->
            <div class="form-row col">
            <div class="col-md-5">
                </br>
            </div>
            <div class="col-md-7">
                <div class="form-group row">
                    <label for="mdl_total" class="col-sm-6 col-form-label">Amount Alokasi</label>
                    <div class="col-sm-6">
                        <input type="hidden" class="form-control" id="val_alo" name="val_alo" style="text-align:right;" placeholder="0.00">
                        <input type="text" class="form-control" id="val_alo1" name="val_alo1" style="text-align:right;" placeholder="0.00" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="mdl_total" class="col-sm-6 col-form-label">Outstanding Amount</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="ost_alo" name="ost_alo" style="text-align:right;" placeholder="0.00" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="mdl_total" class="col-sm-6 col-form-label">Total</label>
                    <div class="col-sm-6">
                        <input type="hidden" class="form-control" id="val_kwt" name="val_kwt" style="text-align:right;" placeholder="0.00">
                        <input type="text" class="form-control" id="val_kwt1" name="val_kwt1" style="text-align:right;" placeholder="0.00" readonly>
                    </div>
                </div>

            </div>
        </div>
  
                <div class="modal-footer">
                    <div class="col-md-2 mb-3">
                    <input type="button" id="savesj" name="savesj" class="btn btn-info btn-sm" style="width: 100%;" value="Save" onclick="duplicate_data_alo()">
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

                <div class="input-group-append col">
                   <button type="button" style="margin-right:15px;" class="btn btn-primary" onclick="addRow('detailalokasi')">Add Row</button>
                    <button type="button" class="btn btn-danger" onclick="hapusbaris()">Delete Row</button>
                </div>

            <div class="tableFix" style="height: 300px;">        
                <table id="datatable" class="table table-striped table-bordered text-nowrap" style="font-size: 12px;" role="grid" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>COA</th>
                            <th>Cost Center</th>
                            <th>Reference Number</th>
                            <th>Date</th>
                            <th>Due Date</th>
                            <th>Total</th>
                            <th>Equivalent IDR</th>
                            <th>Amount</th>
                            <th>descriptions</th>
                        </tr>
                    </thead>

            <tbody id="detailalokasi">
            
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
                <label for="nama_supp" class="col-form-label" style="width: 200px;"><b>Outstanding Amount Alokasi</b></label>
                <input type="text" class="form-control" id="out_alok" name="out_alok" style="text-align:right;" placeholder="0.00" readonly>
                <input type="hidden" class="form-control" id="out_alok_h" name="out_alok_h" style="text-align:right;" placeholder="0.00" readonly>
                <input type="hidden" class="form-control" id="total_alokasi" name="total_alokasi" style="text-align:right;" placeholder="0.00" readonly>
                </div>

            </br>   
            </div>
            <div class="col-md-3 mb-3">                              
            <br>
        </form>
            <button type="button" style="border-radius: 6px" class="btn-outline-primary btn-sm" name="simpan" id="simpan" onclick="simpan_data_alokasi();"><span class="fa fa-floppy-o"></span> Save</button>                
            <button type="button" style="border-radius: 6px" class="btn-outline-danger btn-sm" name="batal" id="batal" onclick="location.href='alokasi.php'"><span class="fa fa-angle-double-left"></span> Back</button>               
            </div>
            </div>                                    
        </div>

<div class="modal fade" id="modal-simpan-alokasi">
    <div style="width: 450px;" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Save Alokasi</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <!-- No Invoice -->
                <div class="form-group row">
                    <label for="id_inv" class="col-sm-5 col-form-label">Save Alokasi Number :</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="no_alokasi" name="no_alokasi" style="border:none;" readonly>
                    </div>
                </div>
                <!-- ID Invoice, Pph -->
                <input type="hidden" class="form-control" id="id_inv_post" name="id_inv_post" readonly>
                <input type="hidden" class="form-control" id="pph_post" name="pph_post" readonly>

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary toastrDefaultSuccess" onclick="save_alokasi()">Save</button>
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

    function addRow(tableID) {
    var tableID = "detailalokasi";
 var table = document.getElementById(tableID);
 var rowCount = table.rows.length;
 var row = table.insertRow(rowCount);

$(function() {
    $('.selectpicker').selectpicker();
});
$(document).ready(function () {
    $('.tanggal').datepicker({
        format: "dd-mm-yyyy",
        autoclose:true
    });
});
$(function() {
      //Initialize Select2 Elements
      var selectcoba = rowCount;
      $('.rowCount').select2({
         theme: 'bootstrap4'
      })
      //Initialize Select2 Elements
      $('.select2add').select2({
        theme: 'bootstrap4'
      })
    });
 $coa = '';
 var element1 = ' <tr> <td><select class="form-control selectpicker" name="coa" id="coa" data-live-search="true" data-size="5"> <option value="-" > - </option><?php $sql = mysqli_query($conn1,"select no_coa as id_coa,concat(no_coa,' ', nama_coa) as coa from mastercoa_v2"); foreach ($sql as $coa) : ?> <option value="<?= $coa["id_coa"]; ?>"><?= $coa["coa"]; ?> </option><?php endforeach; ?></select></td> <td><select class="form-control selectpicker" name="cost" id="cost" data-live-search="true" data-size="5"> <option value="-" > - </option><?php $sql2 = mysqli_query($conn1,"select no_cc as code_combine,cc_name as cost_name from b_master_cc where status = 'Active'"); foreach ($sql2 as $cc) : ?> <option value="<?= $cc["code_combine"]; ?>"><?= $cc["cost_name"]; ?> </option><?php endforeach; ?></select></td> <td><input  type="text" class="form-control" id="ref_number" name="ref_number" style="text-align:center; width: 180px;" autocomplete="off"></td> <td><input  type="text" class="form-control tanggal" id="ref_date" name="ref_date" value="<?php echo date("Y-m-d"); ?>" style="text-align:center; width: 180px;"  autocomplete="off"></td> <td><input  type="text" class="form-control tanggal" id="due_date" name="due_date" value="<?php echo date("Y-m-d"); ?>" style="text-align:center; width: 180px;"  autocomplete="off"></td> <td><input  type="text" class="form-control" id="total" name="total" style="text-align:center; width: 180px;"  autocomplete="off"></td> <td><input  type="text" class="form-control" id="discount" name="discount" style="text-align:center; width: 180px;"  autocomplete="off"></td> <td><input  type="text" class="form-control" id="amt" name="amt" style="text-align:center; width: 180px;" oninput="modal_input_amt(value)" autocomplete="off"></td> <td><input  type="text" class="form-control" id="discount" name="discount" style="text-align:center; width: 300px;"  autocomplete="off"></td> </tr>';


 row.innerHTML = element1;    
    }

    function hapusbaris() {
        var tableID = "detailalokasi";
        var table = document.getElementById(tableID);
        var rowCount = table.rows.length;
        console.log(rowCount);
            if(rowCount != 0) {   
                rowCount = rowCount - 1;
                table.deleteRow(rowCount);
            }   
    }

    function modal_input_amt(){ 
    var table = document.getElementById("detailalokasi");
    var tota = 0;
    var totall = 0;
    var price_h = 0;
            for (var i = 1; i < (table.rows.length); i++) {

    var price = document.getElementById("detailalokasi").rows[i].cells[7].children[0].value;
    var outstan = $('[name="out_alok_h"]').val();
    if (price == '') {
        price_h = 0;
    }else{
        price_h = price;
    }
    tota += parseFloat(price_h);
    totall = parseFloat(outstan - tota);

    document.getElementsByName("out_alok")[0].value = totall.toFixed(2);
    document.getElementsByName("total_alokasi")[0].value = tota.toFixed(2);

}
}

    function modal_get_amount(){
        
        
    var input_kwt = document.getElementsByName("mdl_cek_kwt");
    var totmdl_amo = document.getElementsByName("mdl_amo");
    var val_alo = $('#val_alo').val();  
    var total = 0;
    var totalas = 0;
    var ost = 0;

    //      
            // alert(totmdl_amo);
    for (var i = 0; i < input_kwt.length; i++) {
            if (input_kwt[i].checked) { 
            if(totmdl_amo[i].value == ''){
                total = parseFloat(input_kwt[i].value);
            }else{      
                total = parseFloat(totmdl_amo[i].value);
            }
                totalas += parseFloat(input_kwt[i].value);

    document.getElementsByName("mdl_amo")[i].value = total;         
    console.log(input_kwt[i].value);        
            }
    type: "POST";                   
    }
        ost = val_alo - totalas;


     document.getElementsByName("val_kwt")[0].value = totalas;
    document.getElementsByName("val_kwt1")[0].value = formatMoney(totalas);
    document.getElementsByName("ost_alo")[0].value = formatMoney(ost);
            
}

function modal_sum_total_alo(){
        
    var input_kwt = document.getElementsByName("mdl_cek_kwt");
    var hanya_baca = document.getElementsByName("mdl_amo"); 
    var total = 0;

    //      
    for (var i = 0; i < input_kwt.length; i++) {
        for (var i = 0; i < hanya_baca.length;  i++){
            if (input_kwt[i].checked) {
            hanya_baca[i].readOnly = false;             
                total += parseFloat(input_kwt[i].value);

            }else {             
                hanya_baca[i].readOnly = true;
                hanya_baca[i].value = '';
                // modal_input_amo();
                    
            }
    type: "POST";                   
    }
  }
            
    // document.getElementsByName("val_kwt")[0].value = total;
    // document.getElementsByName("val_kwt1")[0].value = formatMoney(total);
            
}


function modal_input_amo(value){ 

        
    var input = document.getElementsByName("mdl_cek_kwt");
    var pwith = $('#pwith').val();
    var val_alo = $('#val_alo').val();
    var total = 0;
    var ost = 0;    
    //
    var table = document.getElementById("table-inv-alok");
    var arr = document.getElementsByName('mdl_amo');
    var tot = 0;
    var amou = 0;
    for(var i = 0; i < arr.length; i++){
        for (var i = 0; i < input.length; i++) {
    for (var i = 0; i + 1 < table.rows.length; i++) {
        if (pwith == "USD") {
            if (input[i].checked) {
                   total = parseFloat(input[i].value);
                   amou = table.rows[i + 1].cells[4].innerHTML;
                   if(parseFloat(arr[i].value) > table.rows[i + 1].cells[4].innerHTML || parseFloat(arr[i].value) < 1){
                    arr[i].value = table.rows[i + 1].cells[4].innerHTML;
                     tot += parseFloat(table.rows[i + 1].cells[4].innerHTML);
                     ost = val_alo - tot;
                   }else if(arr[i].value == ''){
                    arr[i].value = 1;
                     tot += parseFloat(arr[i].value);
                     ost = val_alo - tot;
                   }else{
                   arr[i].value = arr[i].value;  
                   tot += parseFloat(arr[i].value);
                   ost = val_alo - tot;
                   } 
             } 
        }
            else{
            if (input[i].checked) {
                   total = parseFloat(input[i].value);
                   amou = table.rows[i + 1].cells[5].innerHTML;
                   if(parseFloat(arr[i].value) > table.rows[i + 1].cells[5].innerHTML || parseFloat(arr[i].value) < 1){
                    arr[i].value = table.rows[i + 1].cells[5].innerHTML;
                     tot += parseFloat(table.rows[i + 1].cells[5].innerHTML);
                     ost = val_alo - tot;
                   }else if(arr[i].value == ''){
                    arr[i].value = 1;
                     tot += parseFloat(arr[i].value);
                     ost = val_alo - tot;
                   }else{
                   arr[i].value = arr[i].value;  
                   tot += parseFloat(arr[i].value);
                   ost = val_alo - tot;
                   } 
             } 
            }
        }           
    }   
    }   

     document.getElementsByName("val_kwt")[0].value = tot;
    document.getElementsByName("val_kwt1")[0].value = formatMoney(tot); 
    document.getElementsByName("ost_alo")[0].value = formatMoney(ost);          
}


function duplicate_data_alo(){ 

    var saldo    = $('[name="val_alo"]').val();
  var pay      = $('[name="val_kwt"]').val();
  var total = saldo - pay;
  console.log(saldo);
  console.log(pay);
  console.log(total);
  $('[name="out_alok"]').val(total.toFixed(2));
  $('[name="out_alok_h"]').val(total);
  $('[name="total_alokasi"]').val(pay);
    simpan_load_temp_alo();
        
}

async function simpan_load_temp_alo(){
   var result = await simpan_invoice_detail_alo()
   load_invoice_detail_alo();
}


function simpan_invoice_detail_alo(){
            $("#table-inv-alok input[name='mdl_cek_kwt']:checked").each(function() {
            var ref_number = $(this).closest('tr').find('td:eq(0)').attr('value');
            var ref_date = $(this).closest('tr').find('td:eq(1)').attr('value');
            var due_date = $(this).closest('tr').find('td:eq(2)').attr('value');
            var curr = $(this).closest('tr').find('td:eq(3)').attr('value');
            var total = $(this).closest('tr').find('td:eq(4)').attr('value');
            var eqp_idr = $(this).closest('tr').find('td:eq(5)').attr('value');
            var amount = parseFloat($(this).closest('tr').find('td:eq(8) input').val(),10) || 0;
            var rate = $('[name="rate"]').val();
            var create_user = '<?php echo $user ?>';


            $.ajax({
            type:'POST',
            url:'insert_alo_temp.php',
            data: {'ref_number':ref_number, 'ref_date':ref_date, 'due_date':due_date,'curr':curr, 'total':total, 'eqp_idr':eqp_idr, 'amount':amount, 'rate':rate, 'create_user':create_user},
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

function load_invoice_detail_alo(){
    return new Promise(resolve => {
    setTimeout(() => {
    var create_user = '<?php echo $user ?>';

    $.ajax({
        type:'POST',
        url:'load-alo-temp.php',
        data: {'create_user':create_user},
        cache: 'false',
        close: function(e){
            e.preventDefault();
            return false; 
        },
        success: function(data){
            $('#detailalokasi').html(data);
            // alert(data);  
            },
        error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr);
                alert(xhr);
        }
    }); 
    }, 1000);
    $('#modal-add-so').modal('hide');
  });
};

function simpan_data_alokasi() { 

    let doc_num   = $('[name="doc_number"]').val();
    let bank      = $('[name="bank"]').val();
    let account   = $('[name="acc"]').val();
    let outstand   = $('[name="out_alok"]').val();
       if (doc_num == "") {
          alert("Document Number is required");
          $("#doc_number").focus(); 
          return false;
        }

        if (bank == "") {
          alert("Bank is required");
          $("#bank").focus();   
          return false;
        }

        if (account == "") {
          alert("Account is required");
          $("#acc").focus();    
          return false;
        }  
    
    $no_invoice = $('[name="alk_number"]').val()    

    $('[name="no_alokasi"]').val($no_invoice);
    if (outstand >= 0) {
    $('#modal-simpan-alokasi').modal('show')    
}else{
    alert("Outstanding Can't be minus");
}
        
}


function save_alokasi() {
    simpan_alokasi_detail();
    simpanalokasi();    
    //  
    $('#modal-simpan-alokasi').modal('hide');
    
}

function simpanalokasi() { 

    return new Promise(resolve => {     
        setTimeout(() => {
            var msg
            var data = [];      
            //
            var no_alk      = $('#alk_number').val();
            var tgl_alk     = $('#alo_date').val();
            var customer    = $('#customer').val();
            var buyer       = $('#cust').val();
            var doc_number  = $('#doc_number').val();
            var bank        = $('#bank').val();
            var account     = $('#acc').val();
            var curr        = $('#currn').val();
            var rate        = $('#rate').val();
            var amount      = $('#am_awal').val();
            var eqp_idr     = $('#pl_akhir').val();
            var sisa        = $('#out_alok').val();
            var create_user = '<?php echo $user ?>';        
            
    
        $.ajax({
            type:'POST',
            url:'insert_alokasi.php',
            data: {'no_alk':no_alk, 'tgl_alk':tgl_alk, 'customer':customer, 'buyer':buyer, 'doc_number':doc_number, 'bank':bank,  'account':account,'curr':curr, 'rate':rate, 'amount':amount, 'eqp_idr':eqp_idr, 'sisa':sisa, 'create_user':create_user},
            cache: 'false',
            close: function(e){
                e.preventDefault();
            },
            success: function(response){
                // console.log(response);
                // $('#mymodal').modal('hide'); 
                
                window.location = 'alokasi.php';
                },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr);
                alert(xhr);
            }
        });

        }, 100);
    });

}


function simpan_alokasi_detail(){
    $("#datatable tbody tr").each(function() {
        var no_alk      = $('#alk_number').val();
        var tgl_alk     = $('#alo_date').val();
        var buyer       = $('#cust').val();
        var curr        = $('#currn').val();
        var rate        = $('#rate').val();
        var coa         = $(this).closest('tr').find('td:eq(0)').find('select[name=coa] option').filter(':selected').val() || $(this).closest('tr').find('td:eq(0) input').val();
        var cost_center = $(this).closest('tr').find('td:eq(1)').find('select[name=cost] option').filter(':selected').val() || $(this).closest('tr').find('td:eq(1) input').val();
        var no_ref      = $(this).closest('tr').find('td:eq(2) input').val();
        var ref_date    = $(this).closest('tr').find('td:eq(3) input').val();
        var due_date    = $(this).closest('tr').find('td:eq(4) input').val();
        var total       = parseFloat($(this).closest('tr').find('td:eq(5) input').val(),10) || 0;
        var eqp_idr     = parseFloat($(this).closest('tr').find('td:eq(6) input').val(),10) || 0;
        var amount      = parseFloat($(this).closest('tr').find('td:eq(7) input').val(),10) || 0;
        var keterangan  = $(this).closest('tr').find('td:eq(8) input').val();
        var create_user = '<?php echo $user ?>';
            
            $.ajax({
            type:'POST',
            url:'insert_alokasi_detail.php',
            data: {'no_alk':no_alk, 'tgl_alk':tgl_alk, 'buyer':buyer, 'curr':curr, 'coa':coa, 'rate':rate, 'cost_center':cost_center, 'no_ref':no_ref, 'ref_date':ref_date,'due_date':due_date, 'total':total, 'eqp_idr':eqp_idr, 'amount':amount, 'keterangan':keterangan, 'create_user':create_user},
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
    function getdataalk(){
        var no_bi = document.getElementById('doc_number').value;

        $.ajax({
            type: 'POST',
            url: 'getdataalokasi.php', 
            data: {'no_bi':no_bi},
            success: function(response) { 
                console.log(JSON.parse(response));
                var pay_type  = $('#pay_type').val();
                if(JSON.parse(response)[7] == 'IDR' && pay_type == 'USD'){
                    $('input[name=rate]').prop('readonly', false);
                }else{
                    $('input[name=rate]').prop('readonly', true);
                }

                $('#alo_date').val(JSON.parse(response)[1]);  
                $('#cust').val(JSON.parse(response)[3]);  
                $('#customer').val(JSON.parse(response)[2]);  
                $('#doc_number').val(JSON.parse(response)[0]);  
                $('#id_bank').val(JSON.parse(response)[5]);  
                $('#bank').val(JSON.parse(response)[4]);  
                $('#acc').val(JSON.parse(response)[6]);  
                $('#rate').val(JSON.parse(response)[9]);  
                $('#currn').val(JSON.parse(response)[7]);  
                $('#pl_rate').val(formatMoney(JSON.parse(response)[9]));  
                $('#rate_h').val(JSON.parse(response)[9]);  
                $('#am_awal').val(JSON.parse(response)[8]);  
                $('#pl_awal').val(formatMoney(JSON.parse(response)[8]));  
                $('#pl_akhir').val(JSON.parse(response)[10]);  
                $('#am_akhir').val(formatMoney(JSON.parse(response)[10]));  
            }
        });

    }

    function changerate() {
        var no_bi = document.getElementById('doc_number').value;
        $.ajax({
            type: 'POST',
            url: 'getdataalokasi.php', 
            data: {'no_bi':no_bi},
            success: function(response) { 
                console.log(JSON.parse(response));
                var pay_type  = $('#pay_type').val();
                if(JSON.parse(response)[7] == 'IDR' && pay_type == 'USD'){
                    $('input[name=rate]').prop('readonly', false);
                }else{
                    $('input[name=rate]').prop('readonly', true);
                }
            }
        });
    }

    function caridataalk() {

    $cust = $('[name="customer"]').val();
    $name_cust = $('[name="cust"]').val();

    var cr = $('[name="currn"]').val();
    var paywith = $('[name="pay_type"]').val();
    $pwith = paywith;

    if (cr == 'USD') {
        $rate = $('[name="rate"]').val();
    }else{
        $rate = $('[name="rate_h"]').val();
    }

    if (paywith == 'USD') {
        $alo = $('[name="am_awal"]').val();
    }else{
        $alo = $('[name="pl_akhir"]').val()
    }

    console.log(paywith);

    //
    $('[name="custmr"]').val($cust);
    $('[name="nama_custmr"]').val($name_cust);
    $('[name="rates"]').val($rate);
    $('[name="pwith"]').val($pwith);
    $('[name="val_alo"]').val($alo);
    $('[name="val_alo1"]').val(formatMoney($alo));
    $('[name="ost_alo"]').val(formatMoney($alo));
    //
    $('#datatable tbody tr').remove();   
    //
    $('#so_number1').val("");
    //$('#id_sj').val("");
    //Clear Value : Total, Discount, Down Payment, Return, Total With Out Tax, VAT, Grand Total
    $('#total').val("");
    $('#total1').val("");
    $('#terbilang').val("");
    $('#val_kwt').val("");
    $('#val_kwt1').val("");
    if ($name_cust != '') {
        $('#modal-add-so').modal('show');
        var create_user = '<?php echo $user ?>';

                $.ajax({
                    type:'POST',
                    url:'delete-alk-temp.php',
                    data: {'create_user':create_user},
                    cache: 'false',
                    close: function(e){
                        e.preventDefault();
                        return false; 
                    },
                    success: function(data){
                        // window.location = 'list-invoice.php';  
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        console.log(xhr);
                        alert(xhr);
                    }
                }); 
    }else{
        alert("Please Select Doc Number");
        document.getElementById('doc_number').focus();
    }
    // $('#grandtotal').val("");    
    //
    //
    // delete_invoice_detail_alo();
    // cari_data_inv();

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
        var customer = document.getElementById('custmr').value;
        var rates = document.getElementById('rates').value;
        var pwith = document.getElementById('pwith').value;
        var start_date = document.getElementById('startdate_bpb').value;
        var end_date = document.getElementById('enddate_bpb').value;      
             
        $.ajax({
            type:'POST',
            url:'cari_inv_alk.php',
            data: {'rates':rates, 'pwith':pwith, 'customer':customer, 'start_date':start_date, 'end_date':end_date},
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
