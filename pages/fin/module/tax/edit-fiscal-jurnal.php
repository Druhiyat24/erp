<?php include '../header2.php' ?>
<style>
</style>
    <!-- MAIN -->
    <div class="col p-4">
        <h4 class="text-center">KOREKSI FISKAL JOURNAL</h4>
<div class="box">
    <div class="box header">
<form id="form-data" method="post">
        <div class="form-row">
            <div class="col-md-3 mb-3">            
            <label for="pajak" class="col-form-label" style="width: 150px;"><b>Journal Number</b></label>
            <?php
            $no_mj = base64_decode($_GET['no_mj']);
            $sql = mysqli_query($conn2,"select no_mj from tbl_memorial_journal where no_mj = '$no_mj'");
            $row = mysqli_fetch_array($sql);

            echo'<input type="text" readonly style="font-size: 14px;" class="form-control-plaintext input-sm" id="no_doc" name="no_doc" value="'.$row['no_mj'].'">'
            ?>
        </div>

            <div class="col-md-2 mb-3">            
            <label for="total" class="col-form-label" style="width: 150px;"><b>Date</b></label>
                <input type="text"  class="form-control input-sm tanggal" style="font-size: 15px;" name="tgl_doc" id="tgl_doc" 
            value="<?php 
            $no_mj = base64_decode($_GET['no_mj']);
            $sql = mysqli_query($conn2,"select mj_date from tbl_memorial_journal where no_mj = '$no_mj'");
            $row = mysqli_fetch_array($sql);                         
            if(!empty($no_mj)) {
                echo date("d-m-Y",strtotime($row['mj_date']));
            }
            else{
                echo date("d-m-Y");
            }  ?>" autocomplete='off'>

<!--             <input type="hidden" style="font-size: 15px;" name="unik_code" id="unik_code" class="form-control" 
            value="<?php 
            $karakter = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789';
            $shuffle  = substr(str_shuffle($karakter), 0, 8);
            echo $shuffle; ?>" autocomplete='off' readonly> -->
            </div>


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



    <div class="form-row">
    <div class="modal fade" id="mymodal3" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-times"></span></button>
        <h4 class="modal-title" id="Heading">For Payment</h4>
        </div>
          <div class="modal-body">
          <div class="form-group">
            <form id="modal-form3" method="post">
                <div class="form-row">
                    <div class="col-6">

                    <table id="mytable1" class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size: 12px;text-align:center;">
                    <thead>
                        <tr>
                            <th style="width:10px;">Check</th>
                            <th style="width:150px;">Supporting Doc</th>                                                    
                        </tr>
                    </thead>

            <tbody>
                    <?php

            $querys = mysqli_query($conn2,"select ref_doc from master_forpay where ket = '1' ");


            while($row1 = mysqli_fetch_array($querys)){
                
                    echo '<tr>  
                    <td style="width:10px;"><input type="radio" id="select" name="select[]" value="" <?php if(in_array("1",$_POST[select])) echo "checked=checked";?></td>                         
                            <td style="width:150px;">
                            <input style="text-align: left;"  style="font-size: 12px;" class="form-control" id="data-total-ro" name="data-total-ro"  value="'.$row1['ref_doc'].'" disabled>
                            </td>                                                                                                 
                        </tr>';
                   }
                   echo '<tr>  
                    <td style="width:10px;"><input type="radio" id="select" name="select[]" value="" <?php if(in_array("1",$_POST[select])) echo "checked=checked";?></td>                         
                            <td style="width:150px;">
                            <input style="text-align: left;"  style="font-size: 12px;" class="form-control" id="data-total-ro" name="data-total-ro"  value="" >
                            </td>                                                                                                 
                        </tr>';
                    ?> 
                </tbody>
            </table>
        </div>
        <div class="col-6">
            <div class="col-md-12 mb-3">            
            <label for="tanggal"><b>Ke</b></label>          
            <input type="text" style="font-size: 16px;text-align: center;" name="ke_berapa" id="ke_berapa" class="form-control" 
            value="">
            </div>
            <div class="col-md-12 mb-3">            
            <label for="tanggal"><b>Dari</b></label>          
            <input type="text" style="font-size: 16px;text-align: center;" name="dari_berapa" id="dari_berapa" class="form-control" 
            value="">
            </div>

            <div class="col-md-12 mb-3" style="padding-top: 8px;">
            <label for="nama_supp"><b>Dari Account</b></label>            
              <select class="form-control selectpicker" name="dari_akun" id="dari_akun" data-dropup-auto="false" data-live-search="true">
                <option value="-" disabled selected="true">Select Account</option>                                                 
                <?php
                $dari_akun ='';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $dari_akun = isset($_POST['dari_akun']) ? $_POST['dari_akun']: null;
                }                 
                $sql = mysqli_query($conn1,"select name from tbl_akun");
                while ($row = mysqli_fetch_array($sql)) {
                    $data = $row['name'];
                    if($row['name'] == $_POST['dari_akun']){
                        $isSelected = ' selected="selected"';
                    }else{
                        $isSelected = '';

                    }
                    echo '<option value="'.$data.'"'.$isSelected.'">'. $data .'</option>';    
                }?>
                </select>

                </div>

                <div class="col-md-12 mb-3" style="padding-top: 8px;">
            <label for="nama_supp"><b>Ke Account</b></label>            
              <select class="form-control selectpicker" name="ke_akun" id="ke_akun" data-dropup-auto="false" data-live-search="true">
                <option value="-" disabled selected="true">Select Account</option>                                                 
                <?php
                $ke_akun ='';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $ke_akun = isset($_POST['ke_akun']) ? $_POST['ke_akun']: null;
                }                 
                $sql = mysqli_query($conn1,"select name from tbl_akun");
                while ($row = mysqli_fetch_array($sql)) {
                    $data = $row['name'];
                    if($row['name'] == $_POST['ke_akun']){
                        $isSelected = ' selected="selected"';
                    }else{
                        $isSelected = '';

                    }
                    echo '<option value="'.$data.'"'.$isSelected.'">'. $data .'</option>';    
                }?>
                </select>

                </div>

                <div class="col-md-12 mb-3">            
            <label for="pajak" class="col-form-label" style="width: 150px;"><b>Keterangan</b></label>
                <textarea style="font-size: 15px; text-align: left;" cols="30" rows="3" type="text" class="form-control " name="keter" id="keter" value="<?php             
            if(!empty($_POST['keter'])) {
                echo $_POST['keter'];
            }
            else{
                echo '';
            } ?>" placeholder="..." required></textarea>
        </div>

                    
        </div>
                </div>  
            </div>
                <div class="modal-footer">
                    <button type="submit" id="send3" name="send3" class="btn btn-warning btn-lg" style="width: 100%;"><span class="fa fa-check"></span>
                        Save
                    </button>
                </div>           
            </form>
        </div>
      </div>
    </div>
  </div>
 </div>
</div> 

 <div class="form-row">
    <div class="modal fade" id="mymodal2" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-times"></span></button>
        <h4 class="modal-title" id="Heading">Choose Supporting Document</h4>
        </div>
          <div class="modal-body">
          <div class="form-group">
            <form id="modal-form2" method="post">
                <div class="form-row">
                    <table id="doc_support" class="table-bordered" cellspacing="0" width="100%" style="font-size: 12px;text-align:center;">
                    <thead>
        <tr><th class="text-center">Cek</th>
            <th class="text-center">Supporting Doc</th>
        </tr>
    </thead>
    <tbody>
        <?php

            $querys = mysqli_query($conn2,"select ref_doc from master_forpay where ket = '2' ");


            while($row1 = mysqli_fetch_array($querys)){
                $nodoc = $row1['ref_doc'];

                $sql22 = mysqli_query($conn2,"select ket from supp_doc_temp where ket = '$nodoc'");
                $row22 = mysqli_fetch_array($sql22);
                $ket = isset($row22['ket']) ? $row22['ket'] : null;

                $sql23 = mysqli_query($conn2,"select ket from supp_doc_temp where ket != 'Sales Order' and ket != 'Purchase Order' and ket != 'PEB' and ket != 'Invoice'");
                $row23 = mysqli_fetch_array($sql23);
                $ket2 = isset($row23['ket']) ? $row23['ket'] : null;
                
                    echo '<tr>'; 
                    if ($ket != '') {
                         echo'<td style="width:10px;"><input type="checkbox" id="select" name="select[]" value="" <?php if(in_array("1",$_POST[select])) echo "checked=checked";? checked></td>'; 
                     } else{
                    echo'<td style="width:10px;"><input type="checkbox" id="select" name="select[]" value="" <?php if(in_array("1",$_POST[select])) echo "checked=checked";?></td>'; 
                    }                        
                            echo '<td style="width:150px;">
                            <input style="text-align: left;"  style="font-size: 12px;" class="form-control" id="data-total-ro" name="data-total-ro"  value="'.$row1['ref_doc'].'" disabled>
                            </td>                                                                                                 
                        </tr>';
                   }
                   echo '<tr>';
                   echo '<tr>'; 
                    if ($ket2 != '') {
                         echo'<td style="width:10px;"><input type="checkbox" id="select" name="select[]" value="" <?php if(in_array("1",$_POST[select])) echo "checked=checked";? checked></td>                         
                            <td style="width:150px;">
                            <input style="text-align: left;"  style="font-size: 12px;" class="form-control" id="data-total-ro" name="data-total-ro"  value="'.$ket2.'" >
                            </td>                                                                                                 
                        </tr>'; 
                     } else{
                    echo'<td style="width:10px;"><input type="checkbox" id="select" name="select[]" value="" <?php if(in_array("1",$_POST[select])) echo "checked=checked";?></td>                         
                            <td style="width:150px;">
                            <input style="text-align: left;"  style="font-size: 12px;" class="form-control" id="data-total-ro" name="data-total-ro"  value="" >
                            </td>                                                                                                 
                        </tr>'; 
                    }  
                    
                    ?> 
    </tbody>                  
            </table> 
              
    </div>
  
                <div class="modal-footer">
                    <button type="submit" id="send2" name="send2" class="btn btn-warning btn-lg" style="width: 100%;"><span class="fa fa-check"></span>
                        Save
                    </button>
                </div>           
            </form>
        </div>
      </div>
    </div>
  </div>
 </div>
</div>                  
</form>
    <div class="box body">
        <div class="row">
        
            <div class="col-md-12">
            <div class="table">
            <table id="mytable" class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size: 11px;text-align:center;table-layout: fixed;">
                    <thead>
        <tr><th class="text-center" style="width: 2%">-</th>
            <th class="text-center" style="width: 11%">COA</th>
            <th class="text-center" style="width: 11%">Cost Center</th>
            <th class="text-center" style="width: 11%">Deskripsi</th>
            <th class="text-center" style="width: 6%">Curr</th>
            <th class="text-center" style="width: 9%">Debit</th>
            <th class="text-center" style="width: 9%">Credit</th>
            <th class="text-center" style="width: 10%">Koreksi</th>
            <th style="display: none;">Koreksi</th>
        </tr>
    </thead>
    
    <tbody id="tbody2">
    
<?php
    $no_mj = base64_decode($_GET['no_mj']);
    $sqlpv = mysqli_query($conn1,"select id,COALESCE(nama_costcenter,'-') nama_cc,concat(no_coa,' ', nama_coa) as coa, curr,round(debit,2) debit,round(credit,2) credit,keterangan from sb_list_journal where no_journal = '$no_mj'");
    $x = 0;

     while($row = mysqli_fetch_array($sqlpv)){
                    $curr = $row['curr'];
                    $debit = $row['debit'];
                    $credit = $row['credit'];

    echo'<tr>
            <td><input type="checkbox" id="select" name="select[]" value="" checked disabled></td>
            <td style="" value = "'.$row['coa'].'">'.$row['coa'].'</td>
            <td style="" value = "'.$row['nama_cc'].'">'.$row['nama_cc'].'</td>
            <td style="" value = "'.$row['keterangan'].'">'.$row['keterangan'].'</td>
            <td style="" value = "'.$row['curr'].'">'.$row['curr'].'</td>';
        
            if ($debit == '0') {
                echo '<td style="text-align: right;">
            <input type="number" class="form-control input-sm" style="text-align: right;font-size: 12px;" min="1" id="txt_debit'.$x.'" name="txt_debit['.$x.']"  oninput="modal_input_amt(value)" value="0" autocomplete = "off" readonly>
            </td>';
            }else{
            echo '<td style="text-align: right;">
            <input type="number" class="form-control input-sm" style="text-align: right;font-size: 12px;" min="1" value="'.$row['debit'].'" id="txt_debit'.$x.'" name="txt_debit['.$x.']"  oninput="modal_input_amt(value)" autocomplete = "off" readonly>
            </td>';
        }

        if ($credit == '0') {
                echo '<td>
        <input type="number" class="form-control input-sm" style="text-align: right;font-size: 12px;" min="1" id="txt_credit'.$x.'" name="txt_credit['.$x.']"  oninput="modal_input_amt2(value)" value="0" autocomplete = "off" readonly>
    </td>';
            }else{
            echo '<td>
        <input type="number" class="form-control input-sm" style="text-align: right;font-size: 12px;" min="1" value="'.$row['credit'].'" id="txt_credit'.$x.'" name="txt_credit['.$x.']"  oninput="modal_input_amt2(value)" autocomplete = "off" readonly>
    </td>';
        }
        echo '<td style="text-align: right;">
            <input type="text" class="form-control input-sm" style="text-align: right;font-size: 12px;" value="" id="txt_koreksi'.$x.'" name="txt_koreksi['.$x.']" autocomplete = "off">
            </td>
            <td style="display:none" style="" value = "'.$row['id'].'">'.$row['id'].'</td>';
            $x++;
}
?>
    </tbody>          
            </table>   
            </div>                 
<div class="box footer">   
        <form id="form-simpan">
            <div class="form-row col">
            <div class="col-md-9">
            </div>
            <div class="col-md-3">
            </br>
                <div class="input-group" >
                    <label for="nama_supp" class="col-form-label" style="width: 100px;"><b>Total Debit</b></label>
                    <input type="text" style="font-size: 14px;text-align: right;" class="form-control" id="txt_debit" name="txt_debit" value="<?php             
                    $no_mj = base64_decode($_GET['no_mj']);
                    $sqldes = mysqli_query($conn2,"select format(sum(debit),2) as debit from sb_list_journal where no_journal = '$no_mj'");
                    $row = mysqli_fetch_array($sqldes);      
                    $debit = $row['debit'];                  
                    if(!empty($no_mj)) {
                        echo $debit;
                    }
                    else{
                        echo '';
                    } ?>" placeholder="0.00" readonly>
                </div>
            </br>
                <div class="input-group" >
                    <label for="nama_supp" class="col-form-label" style="width: 100px;"><b>Total Credit</b></label>
                    <input type="text" style="font-size: 14px;text-align: right;" class="form-control" id="txt_credit" name="txt_credit" value="<?php             
                    $no_mj = base64_decode($_GET['no_mj']);
                    $sqldes = mysqli_query($conn2,"select format(sum(credit),2) as credit from sb_list_journal where no_journal = '$no_mj'");
                    $row = mysqli_fetch_array($sqldes);      
                    $credit = $row['credit'];                  
                    if(!empty($no_mj)) {
                        echo $credit;
                    }
                    else{
                        echo '';
                    } ?>" placeholder="0.00" readonly>
                </div>
            </div>
           <div class="form-row col" style="padding-top: 20px;">
            <div class="col-md-3 mb-3">                              
            <button type="button" style="border-radius: 6px" class="btn-outline-primary btn-sm" name="simpan" id="simpan"><span class="fa fa-floppy-o"></span> Save</button>                
            <button type="button" style="border-radius: 6px" class="btn-outline-danger btn-sm" name="batal" id="batal" onclick="location.href='koreksi-fiscal-jurnal.php'"><span class="fa fa-angle-double-left"></span> Back</button>           
            </div>
            </div>                                    
        </form>
        </div>

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
 var element1 = '<tr ><td><input type="checkbox" id="select" name="select[]" value="" checked disabled></td><td style="width: 50px"><select class="form-control selectpicker" name="nomor_coa" id="nomor_coa" data-live-search="true"> <option value="-" > - </option><?php $sql = mysqli_query($conn1,"select no_coa as id_coa,concat(no_coa,' ', nama_coa) as coa from mastercoa_v2"); foreach ($sql as $coa) : ?> <option value="<?= $coa["id_coa"]; ?>"><?= $coa["coa"]; ?> </option><?php endforeach; ?></select></td><td ><select class="form-control selectpicker" name="nomor_cc" id="nomor_cc" data-live-search="true"> <option value="-" > - </option><?php $sql2 = mysqli_query($conn1,"select no_cc as code_combine,cc_name as cost_name from b_master_cc"); foreach ($sql2 as $cc) : ?> <option value="<?= $cc["code_combine"]; ?>"><?= $cc["cost_name"]; ?> </option><?php endforeach; ?></select></td><td><input type="text" class="form-control" name="keterangan[]" placeholder="" autocomplete="off"></td><td><input type="text" class="form-control tanggal" name="keterangan[]" placeholder="" autocomplete="off"></td><td><select class="form-control selectpicker" name="buyer" id="buyer" data-live-search="true"> <option value="-" > - </option><?php $sql4 = mysqli_query($conn1,"select distinct(Supplier) as buyer from mastersupplier where tipe_sup = 'C' order by Supplier ASC"); foreach ($sql4 as $ms) : ?> <option value="<?= $ms["buyer"]; ?>"><?= $ms["buyer"]; ?> </option><?php endforeach; ?></select></td><td><select class="form-control selectpicker" name="no_ws" id="no_ws" data-live-search="true"> <option value="-" > - </option><?php $sql3 = mysqli_query($conn1,"select DISTINCT kpno no_ws from act_costing where cost_date >= '2022-01-01'"); foreach ($sql3 as $ws) : ?> <option value="<?= $ws["no_ws"]; ?>"><?= $ws["no_ws"]; ?> </option><?php endforeach; ?></select></td><td><select class="form-control selectpicker" name="currenc" id="currenc" onchange="ubahrate(this.value)" data-live-search="true"><option value="IDR">IDR</option><option value="USD">USD</option></select></td><td style="text-align: right; display: none"><input type="number" style="text-align: right;" class="form-control" name="keterangan[]" placeholder="" autocomplete="off" readonly value="1"></td><td style="text-align: right;"><input style="text-align: right;" type="number" min="1" style="font-size: 12px;" class="form-control" id="txt_amount" name="txt_amount"  oninput="modal_input_amt(value)" autocomplete = "off"></td><td><input style="text-align: right;" type="number" min="1" style="font-size: 12px;" class="form-control" id="txt_amount" name="txt_amount"  oninput="modal_input_amt2(value)" autocomplete = "off"></td><td><input type="text" class="form-control" name="keterangan[]" placeholder="" autocomplete="off"></td><td><input name="chk_a[]" type="checkbox" class="checkall_a" value=""></td></tr>';
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
                var chkbox = row.cells[12].childNodes[0];
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
        var element2 = '<tr ><td><input type="checkbox" id="select" name="select[]" value="" checked disabled></td><td style="width: 50px"><select class="form-control selectpicker" name="nomor_coa" id="nomor_coa" data-live-search="true"> <option value="-" > - </option><?php $sql = mysqli_query($conn1,"select no_coa as id_coa,concat(no_coa,' ', nama_coa) as coa from mastercoa_v2"); foreach ($sql as $coa) : ?> <option value="<?= $coa["id_coa"]; ?>"><?= $coa["coa"]; ?> </option><?php endforeach; ?></select></td><td ><select class="form-control selectpicker" name="nomor_cc" id="nomor_cc" data-live-search="true"> <option value="-" > - </option><?php $sql2 = mysqli_query($conn1,"select no_cc as code_combine,cc_name as cost_name from b_master_cc"); foreach ($sql2 as $cc) : ?> <option value="<?= $cc["code_combine"]; ?>"><?= $cc["cost_name"]; ?> </option><?php endforeach; ?></select></td><td><input type="text" class="form-control" name="keterangan[]" placeholder="" autocomplete="off"></td><td><input type="text" class="form-control tanggal" name="keterangan[]" placeholder="" autocomplete="off"></td><td><select class="form-control selectpicker" name="buyer" id="buyer" data-live-search="true"> <option value="-" > - </option><?php $sql4 = mysqli_query($conn1,"select distinct(Supplier) as buyer from mastersupplier where tipe_sup = 'C' order by Supplier ASC"); foreach ($sql4 as $ms) : ?> <option value="<?= $ms["buyer"]; ?>"><?= $ms["buyer"]; ?> </option><?php endforeach; ?></select></td><td><select class="form-control selectpicker" name="no_ws" id="no_ws" data-live-search="true"> <option value="-" > - </option><?php $sql3 = mysqli_query($conn1,"select DISTINCT kpno no_ws from act_costing where cost_date >= '2022-01-01'"); foreach ($sql3 as $ws) : ?> <option value="<?= $ws["no_ws"]; ?>"><?= $ws["no_ws"]; ?> </option><?php endforeach; ?></select></td><td><select class="form-control selectpicker" name="currenc" id="currenc" onchange="ubahrate(this.value)" data-live-search="true"><option value="IDR">IDR</option><option value="USD">USD</option></select></td><td style="text-align: right; display: none"><input type="number" style="text-align: right;" class="form-control" name="keterangan[]" placeholder="" autocomplete="off" readonly value="1"></td><td style="text-align: right;"><input style="text-align: right;" type="number" min="1" style="font-size: 12px;" class="form-control" id="txt_amount" name="txt_amount"  oninput="modal_input_amt(value)" autocomplete = "off"></td><td><input style="text-align: right;" type="number" min="1" style="font-size: 12px;" class="form-control" id="txt_amount" name="txt_amount"  oninput="modal_input_amt2(value)" autocomplete = "off"></td><td><input type="text" class="form-control" name="keterangan[]" placeholder="" autocomplete="off"></td><td><input name="chk_a[]" type="checkbox" class="checkall_a" value=""></td></tr>';
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
    alert(curr);
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
        function inputkoreksi(){ 
    // var val = document.getElementById('valuta').value;
    // var tot_pay = parseFloat(document.getElementById('total_cek_h').value,10) || 0; 
    // var tot_pay2 = parseFloat(document.getElementById('total_cek_idr_h').value,10) || 0;     
    var table = document.getElementById("tbody2");
    var total = 0;
    var total2 = 0;
    var val = 0;
    var val2 = 0;
            for (var i = 0; i < (table.rows.length); i++) {

    // var amt = document.getElementById("tbody2").rows[i].cells[4].children[0].value;
    // var amt2 = document.getElementById("tbody2").rows[i].cells[5].children[0].value;
    // var koreksi = document.getElementById("tbody2").rows[i].cells[6].children[0].value;
    var amt = document.getElementById("txt_debit"+i).value || 0;
    var amt2 = document.getElementById("txt_credit"+i).value || 0;
    var koreksi = document.getElementById("txt_koreksi"+i).value || 0;

    if (amt == 0) {
        val = 0;
        val2 = parseFloat(amt2) + parseFloat(koreksi);
    }else{
        val = parseFloat(amt) + parseFloat(koreksi);
        val2 = 0;
    }
    total = parseFloat(val);
    total2 = parseFloat(val2);
    alert(total + '--' + total2);

    if (document.getElementById("txt_koreksi"+i).value > 0) {
    document.getElementById("txt_debit"+i).value = total.toFixed(2);
    document.getElementById("txt_credit"+i).value = total2.toFixed(2);
    }
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
    $("#form-simpan").on("click", "#simpan", function(){
        $("input[type=checkbox]:checked").each(function () {    
        var debit =$(this).closest('tr').find('td:eq(5) input').val() || 0; 
        var credit = $(this).closest('tr').find('td:eq(6) input').val() || 0;                               
        var koreksi = $(this).closest('tr').find('td:eq(7) input').val() || 0;
        var id_jurnal = $(this).closest('tr').find('td:eq(8)').attr('value');
        var create_user = '<?php echo $user; ?>';
        // var t_credit = document.getElementById('txt_credit_h').value;
        // var t_debit = document.getElementById('txt_debit_h').value;

        $.ajax({
            type:'POST',
            url:'insert-koreksi-fiscal-jurnal.php',
            data: {'debit':debit, 'credit':credit, 'koreksi':koreksi, 'id_jurnal':id_jurnal, 'create_user':create_user},
            cache: 'false',
            close: function(e){
                e.preventDefault();
            },
            success: function(response){
                console.log(response);
                // window.location.reload();
                // alert(response);
                window.location = 'koreksi-fiscal-jurnal.php';
                },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr);
                alert(xhr);
            }
        });
                         
    });
        alert("Data Saved Successfully");

    });
</script>


<script type="text/javascript">
$("#select_all").click(function() {
  var c = this.checked;
  $(':checkbox').prop('checked', c);
});  
</script>


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
