<?php include '../header2.php' ?>
<style>
</style>
    <!-- MAIN -->
    <div class="col p-4">
        <h4 class="text-center">FORM KOREKSI JOURNAL FISCAL</h4>
<div class="box">
    <div class="box header">
<form id="form-data" method="post">
        <div class="form-row">
            <div class="col-md-3 mb-3">            
            <label for="pajak" class="col-form-label" style="width: 150px;"><b>Doc Number</b></label>
                <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $txt_periode = isset($_POST['tgl_doc']) ? $_POST['tgl_doc']: null;
            }else{
              $txt_periode =  date("Y-m-d");
            }

            $sql = mysqli_query($conn2,"select max(no_dok) from sb_journal_fiscal where MONTH(tgl_dok) = MONTH('$txt_periode') and YEAR(tgl_dok) = YEAR('$txt_periode')");
            $row = mysqli_fetch_array($sql);
            $kodepay = $row['max(no_dok)'];
            $urutan = (int) substr($kodepay, 13, 5);
            $urutan++;
            $bln =  date("m",strtotime($txt_periode));
            $thn =  date("y",strtotime($txt_periode));
            $huruf = "KF/NAG/$bln$thn/";
            $kodepay = $huruf . sprintf("%05s", $urutan);

            echo'<input type="text" readonly style="font-size: 14px;" class="form-control-plaintext" id="no_doc" name="no_doc" value="'.$kodepay.'">'
            ?>
        </div>

            <div class="col-md-2 mb-3">            
            <label for="total" class="col-form-label" style="width: 150px;"><b>Date</b></label>
                <input type="text" style="font-size: 15px;" name="tgl_doc" id="tgl_doc" class="form-control tanggal" 
            value="<?php 
            $tgl_doc = isset($_POST['tgl_doc']) ? $_POST['tgl_doc']: null;            
            if(!empty($_POST['tgl_doc'])) {
                echo $_POST['tgl_doc'];
            }
            else{
                echo date("Y-m-d");
            } ?>" autocomplete='off'>

<!--             <input type="hidden" style="font-size: 15px;" name="unik_code" id="unik_code" class="form-control" 
            value="<?php 
            $karakter = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789';
            $shuffle  = substr(str_shuffle($karakter), 0, 8);
            echo $shuffle; ?>" autocomplete='off' readonly> -->
            </div>

            <div class="col-md-7 mb-3">
            </div>
            
            <div class="col-md-4 mb-3" style="padding-top:20px;">
            <div class="input-group" >
            <label for="nama_supp" class="col-form-label" style="width: 150px;"><b>COA</b></label>            
              <select class="form-control select2bs4" name="no_coa" id="no_coa" data-dropup-auto="false" data-live-search="true" >
                <option value="" disabled selected="true">Select COA</option>                                                 
                <?php
                $no_coa ='';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $no_coa = isset($_POST['no_coa']) ? $_POST['no_coa']: null;
                }                 
                $sql = mysqli_query($conn1,"select no_coa as id_coa,concat(no_coa,' ', nama_coa) as coa from mastercoa_v2");
                while ($row = mysqli_fetch_array($sql)) {
                    $data = $row['id_coa'];
                    $data2 = $row['coa'];
                    if($row['id_coa'] == $_POST['no_coa']){
                        $isSelected = ' selected="selected"';
                    }else{
                        $isSelected = '';

                    }
                    echo '<option value="'.$data.'"'.$isSelected.'">'. $data2 .'</option>';    
                }?>
                </select>
            </div>
            </div>

            <div class="col-md-8 mb-3">       
            </div>

            <div class="col-md-4 mb-3">
            <div class="input-group" >
            <label for="nama_supp" class="col-form-label" style="width: 150px;"><b>Description</b></label>            
              <textarea style="font-size: 15px; text-align: left;" cols="30" rows="5" type="text" class="form-control " name="pesan" id="pesan" value="" placeholder="descriptions..." required></textarea> 
            </div>
            </div>
            
            <div class="col-md-8 mb-3">       
            </div>

            <div class="col-md-4 mb-3">
            <div class="input-group" >
            <label for="nama_supp" class="col-form-label" style="width: 150px;"><b>Type</b></label>            
              <select class="form-control select2bs4" name="nama_type" id="nama_type" data-dropup-auto="false" data-live-search="true">
                <option value="" disabled selected="true">Select Type</option>                                                 
                <option value="Positif" <?php
                $Status = '';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $Status = isset($_POST['nama_type']) ? $_POST['nama_type']: null;
                }                 
                    if($Status == 'Positif'){
                        $isSelected = ' selected="selected"';
                    }else{
                        $isSelected = '';
                    }
                    echo $isSelected;
                ?>
                >Positif</option>
                <option value="Negatif" <?php
                $Status = '';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $Status = isset($_POST['Status']) ? $_POST['Status']: null;
                }                 
                    if($Status == 'Negatif'){
                        $isSelected = ' selected="selected"';
                    }else{
                        $isSelected = '';
                    }
                    echo $isSelected;
                ?>
                >Negatif</option>
                </select>
            </div>
            </div>

            <div class="col-md-8 mb-3">       
            </div>

            <div class="col-md-4 mb-3">
            <div class="input-group" >
            <label for="nama_supp" class="col-form-label" style="width: 150px;"><b>Amount</b></label>            
              <input type="number" min="0" style="font-size: 14px;text-align: right;" class="form-control" id="nominal_h" name="nominal_h" placeholder="input nominal..." >
              <input type="text" style="font-size: 14px;text-align: right;" class="form-control" id="nominal" name="nominal" placeholder="0.00" readonly>
            </div>
            </div>
            
            <div class="col-md-8 mb-3">       
            </div>
           

            <div class="form-row col">
            <div class="col-md-3 mb-3">                              
            <button type="button" style="border-radius: 6px" class="btn-outline-primary btn-sm" name="simpan" id="simpan"><span class="fa fa-floppy-o"></span> Save</button>                
            <button type="button" style="border-radius: 6px" class="btn-outline-danger btn-sm" name="batal" id="batal" onclick="location.href='koreksi-fiscal.php'"><span class="fa fa-angle-double-left"></span> Back</button>           
            </div>
            </div>   

      <!--       <div class="col-md-2 mb-3" style="padding-top: 8px;">
            <label for="nama_supp"><b>Currency</b></label>            
              <select class="form-control select2bs4" name="currenc" id="currenc" data-live-search="true">
                    <option value="IDR">IDR</option>  
                    <option value="USD">USD</option>                       
                </select> 

            </div>
            <div class="col-md-1 mb-3">            
            <label for="total" class="col-form-label" style="width: 150px;"><b>Rate</b></label>
                <input type="text" style="font-size: 15px;text-align: right;width: 150px;" name="txt_rate" id="txt_rate" class="form-control"  autocomplete='off' readonly value="1" >

            </div> -->

            <input type="hidden" style="font-size: 12px;" class="form-control" id="ambil_ip" name="ambil_ip" 
            value="<?php
                    echo gethostbyaddr($_SERVER['REMOTE_ADDR']); echo ' '; if($_SERVER['REMOTE_ADDR'] == '::1'){ echo 'LOCALHOST';}else{ echo $_SERVER['REMOTE_ADDR'];}
            ?>" >

                <input type="hidden" style="font-size: 14px;text-align: right;" class="form-control" id="rates" name="rates" 
                value="<?php

                    $sqlx = mysqli_query($conn2,"select max(id) as id FROM masterrate where v_codecurr = 'PAJAK'");
                    $rowx = mysqli_fetch_array($sqlx);
                    $maxid = $rowx['id'];

                    $sqly = mysqli_query($conn2,"select IF(rate like ',',ROUND(rate,2),rate) as rate , tanggal  FROM masterrate where id = '$maxid' and v_codecurr = 'PAJAK'");
                    $rowy = mysqli_fetch_array($sqly);
                    $rate = $rowy['rate'];    
            // $top = 30;

                echo $rate;
          
        ?>">

                                        
    </div>
     </div>           
</form>



<div class="modal fade" id="mymodalkbon" data-target="#mymodalkbon" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-times"></span></button>
        <h4 class="modal-title" id="txt_kbon"></h4>
        </div>
        <div class="container">
        <div class="row">
          <div id="txt_tgl_kbon" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>
          <div id="txt_nama_supp" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>
          <div id="txt_tgl_tempo" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>         
          <div id="txt_curr" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>
          <div id="txt_create_user" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>
          <div id="txt_status" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>
          <div id="txt_no_faktur" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>
          <div id="txt_supp_inv" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>
          <div id="txt_tgl_inv" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>                                           
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
  <script language="JavaScript" src="../css/4.1.1/bootstrap-multiselect.min.js"></script>
  <script language="JavaScript" src="../css/4.1.1/dataTables.responsive.min.js"></script>
  <script language="JavaScript" src="../css/4.1.1/responsive.bootstrap4.min.js"></script>
    <script language="JavaScript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.full.js"></script>

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
    $(function() {
      //Initialize Select2 Elements
      $('.select2').select2()
      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      })
    });
  </script>

<script type="text/javascript">
    $(document).ready(function () {
    $('.tanggal').datepicker({
        format: "yyyy-mm-dd",
        autoclose:true
    });
});
</script>

<script>
$(function() {
    $('.selectpicker').selectpicker();
});
</script>


<!--<script type="text/javascript"> 
    $("#mytable").on("click", "#delbutton", function() {
    var sub = $(this).closest('tr').find('td:eq(4)').attr('data-subtotal');
    var pajak = $(this).closest('tr').find('td:eq(5)').attr('data-tax');
    var total = $(this).closest('tr').find('td:eq(6)').attr('data-total');        
    var sub_val = document.getElementById("subtotal").value.replace(/[^0-9.]/g, '');
    var sub_tax = document.getElementById("pajak").value.replace(/[^0-9.]/g, '');
    var sub_total = document.getElementById("total").value.replace(/[^0-9.]/g, '');
    var min_sub = 0;
    var min_tax = 0;
    var min_total = 0;
    min_sub = sub_val - sub;
    min_tax = sub_tax - pajak;
    min_total = sub_total - total;
    $('#subtotal').val(formatMoney(min_sub));
    $('#pajak').val(formatMoney(min_tax));
    $('#total').val(formatMoney(min_total));                      
    $(this).closest("tr").remove();

});
</script>-->

<script>
    $(".select2").select2({
        theme: "bootstrap",
        placeholder: "Search"
} );
</script>


<script type="text/javascript">
    
   // JavaScript Document
function addRow(tableID) {
    var tableID = "tbody2";
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
 var element1 = '<tr ><td><input type="checkbox" id="select" name="select[]" value="" checked disabled></td><td style="width: 50px"><select class="form-control selectpicker" name="nomor_coa" id="nomor_coa" data-live-search="true"> <option value="-" > - </option><?php $sql = mysqli_query($conn1,"select no_coa as id_coa,concat(no_coa,' ', nama_coa) as coa from mastercoa_v2"); foreach ($sql as $coa) : ?> <option value="<?= $coa["id_coa"]; ?>"><?= $coa["coa"]; ?> </option><?php endforeach; ?></select></td><td ><select class="form-control selectpicker" name="nomor_cc" id="nomor_cc" data-live-search="true"> <option value="-" > - </option><?php $sql2 = mysqli_query($conn1,"select no_cc as code_combine,cc_name as cost_name from b_master_cc where status = 'Active'"); foreach ($sql2 as $cc) : ?> <option value="<?= $cc["code_combine"]; ?>"><?= $cc["cost_name"]; ?> </option><?php endforeach; ?></select></td><td><input type="text" class="form-control" name="keterangan[]" placeholder="" autocomplete="off"></td><td><input type="text" class="form-control tanggal" name="keterangan[]" placeholder="" autocomplete="off"></td><td><select class="form-control selectpicker" name="buyer" id="buyer" data-live-search="true"> <option value="-" > - </option><?php $sql4 = mysqli_query($conn1,"select distinct(Supplier) as buyer from mastersupplier where tipe_sup = 'C' order by Supplier ASC"); foreach ($sql4 as $ms) : ?> <option value="<?= $ms["buyer"]; ?>"><?= $ms["buyer"]; ?> </option><?php endforeach; ?></select></td><td><select class="form-control selectpicker" name="no_ws" id="no_ws" data-live-search="true"> <option value="-" > - </option><?php $sql3 = mysqli_query($conn1,"select a.kpno no_ws,b.Supplier from act_costing  a inner join mastersupplier b on b.id_supplier = a.id_buyer where cost_date >= '2022-01-01'"); foreach ($sql3 as $ws) : ?> <option value="<?= $ws["no_ws"]; ?>"><?= $ws["no_ws"]; ?> </option><?php endforeach; ?></select></td><td><select class="form-control selectpicker" name="currenc" id="currenc" onchange="ubahrate(this.value)" data-live-search="true"><option value="IDR">IDR</option><option value="USD">USD</option></select></td><td style="text-align: right; display: none"><input type="number" style="text-align: right;" class="form-control" name="keterangan[]" placeholder="" autocomplete="off" readonly value="1"></td><td style="text-align: right;"><input style="text-align: right;" type="number" min="1" style="font-size: 12px;" class="form-control" id="txt_amount" name="txt_amount"  oninput="modal_input_amt(value)" autocomplete = "off"></td><td><input style="text-align: right;" type="number" min="1" style="font-size: 12px;" class="form-control" id="txt_amount" name="txt_amount"  oninput="modal_input_amt2(value)" autocomplete = "off"></td><td><input type="text" class="form-control" name="keterangan[]" placeholder="" autocomplete="off"></td><td><input name="chk_a[]" type="checkbox" class="checkall_a" value=""></td></tr>';
 row.innerHTML = element1;    
    
    }
    
function deleteRow()
{
    try
         {
        var table = document.getElementById("tbody2");
        var rowCount = table.rows.length;
            for(var i=0; i<rowCount; i++)
                {
                var row = table.rows[i];
                var chkbox = row.cells[12].childNodes[0];
                if (null != chkbox && true == chkbox.checked)
                    {
                    if (rowCount <= 0)
                        {
                        alert("Tidak dapat menghapus semua baris.");
                        break;
                        }
                    table.deleteRow(i);
                    rowCount--;
                    i--;
                    }
                }
            } catch(e)
    {
    alert(e);
    }
 }
 
 function InsertRow(tableID)
{
    try{
        var table = document.getElementById(tableID);
        var rowCount = table.rows.length;
            for(var i=0; i<rowCount; i++)
                {
                var row = table.rows[i];
                var chkbox = row.cells[11].childNodes[0];
                if (null != chkbox && true == chkbox.checked)
                    {
$(function() {
    $('.selectpicker').selectpicker();

});

$(document).ready(function () {
    $('.tanggal').datepicker({
        format: "dd-mm-yyyy",
        autoclose:true
    });
});
        var element2 = '<tr ><td><input type="checkbox" id="select" name="select[]" value="" checked disabled></td><td style="width: 50px"><select class="form-control selectpicker" name="nomor_coa" id="nomor_coa" data-live-search="true"> <option value="-" > - </option><?php $sql = mysqli_query($conn1,"select no_coa as id_coa,concat(no_coa,' ', nama_coa) as coa from mastercoa_v2"); foreach ($sql as $coa) : ?> <option value="<?= $coa["id_coa"]; ?>"><?= $coa["coa"]; ?> </option><?php endforeach; ?></select></td><td ><select class="form-control selectpicker" name="nomor_cc" id="nomor_cc" data-live-search="true"> <option value="-" > - </option><?php $sql2 = mysqli_query($conn1,"select no_cc as code_combine,cc_name as cost_name from b_master_cc where status = 'Active'"); foreach ($sql2 as $cc) : ?> <option value="<?= $cc["code_combine"]; ?>"><?= $cc["cost_name"]; ?> </option><?php endforeach; ?></select></td><td><input type="text" class="form-control" name="keterangan[]" placeholder="" autocomplete="off"></td><td><input type="text" class="form-control tanggal" name="keterangan[]" placeholder="" autocomplete="off"></td><td><select class="form-control selectpicker" name="buyer" id="buyer" data-live-search="true"> <option value="-" > - </option><?php $sql4 = mysqli_query($conn1,"select distinct(Supplier) as buyer from mastersupplier where tipe_sup = 'C' order by Supplier ASC"); foreach ($sql4 as $ms) : ?> <option value="<?= $ms["buyer"]; ?>"><?= $ms["buyer"]; ?> </option><?php endforeach; ?></select></td><td><select class="form-control selectpicker" name="no_ws" id="no_ws" data-live-search="true"> <option value="-" > - </option><?php $sql3 = mysqli_query($conn1,"select a.kpno no_ws,b.Supplier from act_costing   a inner join mastersupplier b on b.id_supplier = a.id_buyer where cost_date >= '2022-01-01'"); foreach ($sql3 as $ws) : ?> <option value="<?= $ws["no_ws"]; ?>"><?= $ws["no_ws"]; ?> </option><?php endforeach; ?></select></td><td><select class="form-control selectpicker" name="currenc" id="currenc" onchange="ubahrate(this.value)" data-live-search="true"><option value="IDR">IDR</option><option value="USD">USD</option></select></td><td style="text-align: right; display: none"><input type="number" style="text-align: right;" class="form-control" name="keterangan[]" placeholder="" autocomplete="off" readonly value="1"></td><td style="text-align: right;"><input style="text-align: right;" type="number" min="1" style="font-size: 12px;" class="form-control" id="txt_amount" name="txt_amount"  oninput="modal_input_amt(value)" autocomplete = "off"></td><td><input style="text-align: right;" type="number" min="1" style="font-size: 12px;" class="form-control" id="txt_amount" name="txt_amount"  oninput="modal_input_amt2(value)" autocomplete = "off"></td><td><input type="text" class="form-control" name="keterangan[]" placeholder="" autocomplete="off"></td><td><input name="chk_a[]" type="checkbox" class="checkall_a" value=""></td></tr>';
        var newRow = table.insertRow(i+1);
        newRow.innerHTML = element2;
                    
                    }
                    
                }
            } catch(e)
    {
    alert(e);
    }
 }

 function hitungRow(){
    var rate = parseFloat(document.getElementById('rates').value,10) || 1; 
    var table = document.getElementById("tbody2");
    var val_amt = 0;
    var val_amt2 = 0;
    var total = 0;
    var total2 = 0;
    var total_h = 0;
    var total2_h = 0;
    var tota = 0;
    var harga = 0;
    var totall = 0;

    document.getElementsByName("txt_debit")[0].value = formatMoney(total.toFixed(2));
    document.getElementsByName("txt_debit_h")[0].value = total.toFixed(2);
    document.getElementsByName("txt_debit_idr")[0].value = formatMoney(total_h.toFixed(2));
    document.getElementsByName("txt_debit_idr_h")[0].value = total_h.toFixed(2);
    document.getElementsByName("txt_credit")[0].value = formatMoney(total2.toFixed(2));
    document.getElementsByName("txt_credit_h")[0].value = total2.toFixed(2);
    document.getElementsByName("txt_credit_idr")[0].value = formatMoney(total2_h.toFixed(2));
    document.getElementsByName("txt_credit_idr_h")[0].value = total2_h.toFixed(2);
    
            for (var i = 0; i < (table.rows.length); i++) {

    var rates = document.getElementById("tbody2").rows[i].cells[8].children[0];
    var curren = document.getElementById("tbody2").rows[i].cells[7].children[0].value;
    var ratess = document.getElementById("tbody2").rows[i].cells[8].children[0].value || 1;
    var amt = document.getElementById("tbody2").rows[i].cells[9].children[0].value || 0;
    var amt2 = document.getElementById("tbody2").rows[i].cells[10].children[0].value || 0;
    if (ratess == '1') {
        rates.readOnly = true;
        rates.value = 1;
        val_amt = amt * 1;
        val_amt2 = amt2 * 1;
        total += parseFloat(val_amt);
        total2 += parseFloat(val_amt2);
        total_h += parseFloat(val_amt);
        total2_h += parseFloat(val_amt2);
    }else{
        rates.readOnly = false;
        rates.value = rate;
        val_amt = amt * rate;
        val_amt2 = amt2 * rate;
        total += parseFloat(amt);
        total2 += parseFloat(amt2);
        total_h += parseFloat(val_amt);
        total2_h += parseFloat(val_amt2);
    }
    document.getElementsByName("txt_debit")[0].value = formatMoney(total.toFixed(2));
    document.getElementsByName("txt_debit_h")[0].value = total.toFixed(2);
    document.getElementsByName("txt_debit_idr")[0].value = formatMoney(total_h.toFixed(2));
    document.getElementsByName("txt_debit_idr_h")[0].value = total_h.toFixed(2);
    document.getElementsByName("txt_credit")[0].value = formatMoney(total2.toFixed(2));
    document.getElementsByName("txt_credit_h")[0].value = total2.toFixed(2);
    document.getElementsByName("txt_credit_idr")[0].value = formatMoney(total2_h.toFixed(2));
    document.getElementsByName("txt_credit_idr_h")[0].value = total2_h.toFixed(2);
   
}
     
}


async function hapusbaris(){
   await deleteRow()
   console.log("result");
   hitungRow();
}
</script>

<script type="text/javascript">
    $("input[name=nominal_h]").keyup(function(){
    var ttl_jml = 0;
    $("input[type=text]").each(function () {         
    var ttl_h = parseFloat(document.getElementById('nominal_h').value,10) || 0;
    var type =  document.getElementById('nama_type').value;

    ttl_jml = ttl_h;

    // if (type == 'Negatif') {
    //     ttl_jml = (ttl_h * -1);    
    // }else{
    //     ttl_jml = ttl_h;    
    // }


    });

   $("#nominal").val(formatMoney(ttl_jml));

    });
</script>

 <script type="text/javascript">
      function ubahrate(curr){ 
    var rate = parseFloat(document.getElementById('rates').value,10) || 1; 
    var table = document.getElementById("tbody2");
    var val_amt = 0;
    var val_amt2 = 0;
    var total = 0;
    var total2 = 0;
    var total_h = 0;
    var total2_h = 0;
    var tota = 0;
    var harga = 0;
    var totall = 0;
            for (var i = 0; i < (table.rows.length); i++) {

    var rates = document.getElementById("tbody2").rows[i].cells[8].children[0];
    var curren = document.getElementById("tbody2").rows[i].cells[7].children[0].value;
    var ratess = document.getElementById("tbody2").rows[i].cells[8].children[0].value || 1;
    var amt = document.getElementById("tbody2").rows[i].cells[9].children[0].value || 0;
    var amt2 = document.getElementById("tbody2").rows[i].cells[10].children[0].value || 0;
    if (curr == 'IDR') {
        rates.readOnly = true;
        rates.value = 1;
        val_amt = amt * 1;
        val_amt2 = amt2 * 1;
        total += parseFloat(val_amt);
        total2 += parseFloat(val_amt2);
        total_h += parseFloat(val_amt);
        total2_h += parseFloat(val_amt2);
    }else{
        rates.readOnly = false;
        rates.value = rate;
        val_amt = amt * rate;
        val_amt2 = amt2 * rate;
        total += parseFloat(amt);
        total2 += parseFloat(amt2);
        total_h += parseFloat(val_amt);
        total2_h += parseFloat(val_amt2);
    }
    document.getElementsByName("txt_debit")[0].value = formatMoney(total.toFixed(2));
    document.getElementsByName("txt_debit_h")[0].value = total.toFixed(2);
    document.getElementsByName("txt_debit_idr")[0].value = formatMoney(total_h.toFixed(2));
    document.getElementsByName("txt_debit_idr_h")[0].value = total_h.toFixed(2);
    document.getElementsByName("txt_credit")[0].value = formatMoney(total2.toFixed(2));
    document.getElementsByName("txt_credit_h")[0].value = total2.toFixed(2);
    document.getElementsByName("txt_credit_idr")[0].value = formatMoney(total2_h.toFixed(2));
    document.getElementsByName("txt_credit_idr_h")[0].value = total2_h.toFixed(2);
   
}
}
  </script>

<script type="text/javascript">
        function modal_input_amt(){ 
    // var val = document.getElementById('valuta').value;
    // var tot_pay = parseFloat(document.getElementById('total_cek_h').value,10) || 0; 
    // var tot_pay2 = parseFloat(document.getElementById('total_cek_idr_h').value,10) || 0;     
    var table = document.getElementById("tbody2");
    var total = 0;
    var total2 = 0;
    var val = 0;
    var val2 = 0;
    var harga = 0;
    var totall = 0;
            for (var i = 0; i < (table.rows.length); i++) {

    var rate = document.getElementById("tbody2").rows[i].cells[8].children[0].value;
    var amt = document.getElementById("tbody2").rows[i].cells[9].children[0].value;
    var amt2 = document.getElementById("tbody2").rows[i].cells[10].children[0];
    if (amt == '') {
        val = 0;
        val2 = 0;
        amt2.readOnly = false;
    }else{
        val = amt;
        val2 = amt * rate;
        amt2.readOnly = true;
    }
    total += parseFloat(val);
    total2 += parseFloat(val2);

    // totall = tot_pay2 + tota;
    



    document.getElementsByName("txt_debit")[0].value = formatMoney(total.toFixed(2));
    document.getElementsByName("txt_debit_h")[0].value = total.toFixed(2);
    document.getElementsByName("txt_debit_idr")[0].value = formatMoney(total2.toFixed(2));
    document.getElementsByName("txt_debit_idr_h")[0].value = total2.toFixed(2);
}
}
  </script>

  <script type="text/javascript">
        function modal_input_amt2(){ 
    // var val = document.getElementById('valuta').value;
    // var tot_pay = parseFloat(document.getElementById('total_cek_h').value,10) || 0; 
    // var tot_pay2 = parseFloat(document.getElementById('total_cek_idr_h').value,10) || 0;     
    var table = document.getElementById("tbody2");
    var total = 0;
    var total2 = 0;
    var val = 0;
    var val2 = 0;
    var harga = 0;
    var totall = 0;
            for (var i = 0; i < (table.rows.length); i++) {

    var rate = document.getElementById("tbody2").rows[i].cells[8].children[0].value;
    var amt = document.getElementById("tbody2").rows[i].cells[9].children[0];
    var amt2 = document.getElementById("tbody2").rows[i].cells[10].children[0].value;
    if (amt2 == '') {
        val = 0;
        val2 = 0;
        amt.readOnly = false;
    }else{
        val = amt2;
        val2 = amt2 * rate;
        amt.readOnly = true;
    }
    total += parseFloat(val);
    total2 += parseFloat(val2);

    // totall = tot_pay2 + tota;
    



    document.getElementsByName("txt_credit")[0].value = formatMoney(total.toFixed(2));
    document.getElementsByName("txt_credit_h")[0].value = total.toFixed(2);
    document.getElementsByName("txt_credit_idr")[0].value = formatMoney(total2.toFixed(2));
    document.getElementsByName("txt_credit_idr_h")[0].value = total2.toFixed(2);
}
}
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
</script>
    

<!-- <script type="text/javascript">
    $("input[name=txt_amount]").keyup(function(){
    var sum_kb = 0;
    var sum_amount = 0;
    var sum_total = 0;
    var sum_balance = 0;        
    $("input[type=checkbox]:checked").each(function () {        
    var kb = parseFloat($(this).closest('tr').find('td:eq(5)').attr('data-out'),10) || 0;
    var amount = parseFloat($(this).closest('tr').find('td:eq(6) input').val(),10) || 0;
    var balance = parseFloat($(this).closest('tr').find('td:eq(5)').attr('data-out'),10) || 0;
    var select_amount = $(this).closest('tr').find('td:eq(6) input');                
    if(amount > balance){
        sum_kb += kb;
        select_amount.val(balance);
        sum_amount += balance;
        sum_total = sum_kb - sum_amount;
    }else{
    sum_kb += kb;
    sum_amount += amount;
    sum_total = sum_kb - sum_amount;        
    }   
    });
    $("#subtotal").val(formatMoney(sum_kb));
    $("#pajak").val(formatMoney(sum_amount));    
    $("#total").val(formatMoney(sum_total));
    });
</script> -->

<!-- -->

<script type="text/javascript">
    $("input[name=amount]").keyup(function(){
    var sum_kb = 0;
    var sum_amount = 0;
    var sum_total = 0;
    var sum_balance = 0;        
    $("input[type=checkbox]:checked").each(function () {        
    var amount = parseFloat($(this).closest('tr').find('td:eq(5) input').val(),10) || 0;

    sum_amount += amount;
 
     
    });

    $("#nomrate1").val(formatMoney(sum_amount));    
    $("#nomrate2").val(formatMoney(sum_amount));    

    });
</script>


<!-- <script type="text/javascript"> 
<?php echo $jsArray; ?>
function changeValueACC(id){
    var select_rate = document.getElementById('rate');   
    document.getElementById('nama_bank').value = prdName[id].nama_bank;
    document.getElementById('valuta').value = prdName[id].valuta;
    document.getElementById('kode').value = prdName[id].kode;
    if (prdName[id].valuta == 'IDR') {
            select_rate.disabled = true;
        }else{
            select_rate.disabled = false;
        }
};
</script>
 -->
<!-- <script type="text/javascript">
    $("input[name=rate]").keyup(function(){
    var ttl_jml = 0;
    var rat = 0;
    var valu = '';
    $("input[type=text]").each(function () {         
    var rate = parseFloat(document.getElementById('rate').value,10) || 1;
    var ttl_h = parseFloat(document.getElementById('nominal_h').value,10) || 0;
    var val = document.getElementById('valuta').value;
    valu = val;
    rat = rate;
    if (valu == 'IDR') {
    ttl_jml = ttl_h / rate;  
    }else{
    ttl_jml = ttl_h * rate;    
    }
    });
   $("#nomrate").val(formatMoney(ttl_jml));
   $("#nomrate_h").val(ttl_jml);
   $("#rate_h").val(formatMoney(rat));

    });
</script> -->

<script type="text/javascript">
    $("input[name=nominal_h]").keyup(function(){
    var ttl_jml = 0;
    var rat = 0;
    var valu = '';
    $("input[type=text]").each(function () {         
    var rate = parseFloat(document.getElementById('rate').value,10) || 1;
    var ttl_h = parseFloat(document.getElementById('nominal_h').value,10) || 0;
    var val = document.getElementById('valuta').value;
    valu = val;
    rat = ttl_h;
    if (valu == 'IDR') {
    ttl_jml = ttl_h / rate;  
    }else{
    ttl_jml = ttl_h * rate;    
    }
    });
   $("#nomrate").val(formatMoney(ttl_jml));
   $("#nomrate_h").val(ttl_jml);
   $("#nominal").val(formatMoney(rat));

    });
</script>

<script type="text/javascript">
    $("#modal-form3").on("click", "#send3", function(){
        var valu = '';
        $("input[type=radio]:checked").each(function () {
        var data = $(this).closest('tr').find('td:eq(1) input').val();
        valu = data;
        console.log(data);
         
             
                  
        });
        $("#txt_forpay").val(valu);
 
    });


</script>


<script type="text/javascript">
// get all number fields
var numInputs = document.querySelectorAll('input[type="number"]');

// Loop through the collection and call addListener on each element
Array.prototype.forEach.call(numInputs, addListener); 


function addListener(elm,index){
  elm.setAttribute('min', 1);  // set the min attribute on each field
  
  elm.addEventListener('keypress', function(e){  // add listener to each field 
     var key = !isNaN(e.charCode) ? e.charCode : e.keyCode;
     str = String.fromCharCode(key); 
    if (str.localeCompare('-') === 0){
       event.preventDefault();
    }
    
  });
  
}
</script>



<script type="text/javascript">
    $("#modal-form2").on("click", "#send2", function(){
        $("input[type=checkbox]:checked").each(function () {
        var doc_number = document.getElementById('no_doc').value;
        var unik_code = document.getElementById('unik_code').value;        
        var data = $(this).closest('tr').find('td:eq(1) input').val();
         
             
        $.ajax({
            type:'POST',
            url:'insertdoc.php',
            data: {'doc_number':doc_number, 'unik_code':unik_code, 'data':data},
            cache: 'false',
            close: function(e){
                e.preventDefault();
            },
            success: function(response){
                console.log(response);
                // $('#modal-form2').modal('toggle');
                // $('#modal-form2').modal('hide');
                 // alert("Data saved successfully");
                window.location.reload(false);
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

<!-- <script type="text/javascript">
    $("#form-data").on("click", "#btn2", function(){
        $("input[type=checkbox]:checked").each(function () {
        var doc_number = document.getElementById('no_doc').value;        
         
             
        $.ajax({
            type:'POST',
            url:'hapusdoc.php',
            data: {'doc_number':doc_number},
            cache: 'false',
            close: function(e){
                e.preventDefault();
            },
            success: function(response){
                console.log(response);
                // $('#modal-form2').modal('toggle');

                // return false; 
                },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr);
                alert(xhr);
            }
        });             
        });
 
    });


</script> -->


<!-- 
<script type="text/javascript">
    $('#currenc').change(function() {
        var angka = 0; 
        var curr = $(this).val(); 
        var rates = document.getElementById('rates').value;
        if (curr == 'IDR') {
            angka = 1;
        }else{
            angka = rates;
        }
        
        $('#txt_rate').val(angka);
        $('#txt_rate').prop('readonly', false);
    });
 
</script> -->

<script type="text/javascript">
    $("#form-data").on("click", "#simpan", function(){
        
        var no_doc  = document.getElementById('no_doc').value;  
        var tgl_doc = document.getElementById('tgl_doc').value; 
        var no_coa  = document.getElementById('no_coa').value;
        var pesan   = document.getElementById('pesan').value;
        var nama_type = document.getElementById('nama_type').value;
        var nominal_h = document.getElementById('nominal_h').value;
        var create_user = '<?php echo $user; ?>';
        

        if (no_coa != '' && nama_type != '' && nominal_h >= 1) {
        $.ajax({
            type:'POST',
            url:'insert-koreksi-fiscal.php',
            data: {'no_doc':no_doc, 'tgl_doc':tgl_doc, 'no_coa':no_coa, 'pesan':pesan, 'nama_type':nama_type, 'nominal_h':nominal_h, 'create_user':create_user},
            cache: 'false',
            close: function(e){
                e.preventDefault();
            },
            success: function(response){
                console.log(response);
                // alert(response);
                window.location = 'koreksi-fiscal.php';
                },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr);
                alert(xhr);
            }
        });
        } 
                         
       if(document.getElementById('no_coa').value == '' ){
        alert("Please Enter COA");
        }else if(document.getElementById('nama_type').value == '' ){
        alert("Please Enter Type");
        }else if(document.getElementById('nominal_h').value <= 1 ){
        alert("Please Enter Amount");
        }else{               
       
            alert("data saved successfully");
        }
    });
</script>

<script type="text/javascript">
$("#select_all").click(function() {
  var c = this.checked;
  $(':checkbox').prop('checked', c);
});  
</script>

<!-- <script type="text/javascript">
    $("#form-simpan").on("click", "#batal", function(){
        $("input[type=checkbox]:checked").each(function () {
        var doc_number = document.getElementById('no_doc').value;        
         
             
        $.ajax({
            type:'POST',
            url:'hapusdoc.php',
            data: {'doc_number':doc_number},
            cache: 'false',
            close: function(e){
                e.preventDefault();
            },
            success: function(response){
                console.log(response);
                // $('#modal-form2').modal('toggle');

                // return false; 
                },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr);
                alert(xhr);
            }
        });             
        });
 
    });


</script> -->

<!-- <script type="text/javascript">     
    $('table tbody tr').on('click', 'td:eq(1)', function(){                
    $('#mymodalkbon').modal('show');
    var no_kbon = $(this).closest('tr').find('td:eq(1)').attr('value');
    var tgl_kbon = $(this).closest('tr').find('td:eq(2)').text();
    var supp = $(this).closest('tr').find('td:eq(9)').attr('value');
    var tgl_tempo = $(this).closest('tr').find('td:eq(7)').text();
    var curr = $(this).closest('tr').find('td:eq(8)').attr('value');
    var create_user = $(this).closest('tr').find('td:eq(16)').attr('value');
    var status = $(this).closest('tr').find('td:eq(17)').attr('value');
    var no_faktur = $(this).closest('tr').find('td:eq(18)').attr('value');
    var supp_inv = $(this).closest('tr').find('td:eq(15)').attr('value');
    var tgl_inv = $(this).closest('tr').find('td:eq(19)').text();                

    $.ajax({
    type : 'post',
    url : 'ajaxkbon.php',
    data : {'no_kbon': no_kbon},
    success : function(data){
    $('#details').html(data); //menampilkan data ke dalam modal
        }
    });         
        //make your ajax call populate items or what even you need
    $('#txt_kbon').html(no_kbon);
    $('#txt_tgl_kbon').html('Tgl Kontrabon : ' + tgl_kbon + '');
    $('#txt_nama_supp').html('Supplier : ' + supp + '');
    $('#txt_tgl_tempo').html('Tgl Jatuh Tempo : ' + tgl_tempo + '');
    $('#txt_curr').html('Currency : ' + curr + '');        
    $('#txt_create_user').html('Create By : ' + create_user + '');
    $('#txt_status').html('Status : ' + status + '');
    $('#txt_no_faktur').html('No Faktur : ' + no_faktur + '');
    $('#txt_supp_inv').html('No Supplier Invoice : ' + supp_inv + '');
    $('#txt_tgl_inv').html('Tgl Supplier Invoice : ' + tgl_inv + '');                               
});

</script> -->

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
