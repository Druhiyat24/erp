<?php include '../header2.php' ?>
<style>
    /* Samakan ukuran font semua input & select */
#mytable input,
#mytable select {
    font-size: 13px !important;
    height: 28px;
    padding: 3px 6px;
}

/* Jika select masih kosong (value=""), kasih width 150px */
#mytable select:invalid {
    width: 150px !important;
}

/* Biar tidak meledak kalau pakai select2 */
.select2-container--default .select2-selection--single {
    font-size: 13px !important;
    height: 28px !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 28px !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 28px !important;
}

.table-fixed-header {
    width: 100%;
    border-collapse: collapse;
}

.table-fixed-header thead th {
    position: sticky;
    top: 0;
    background: #f8f9fa;
    z-index: 2;
}

.table-fixed-header tbody {
    display: block;
    max-height: 350px;
    overflow-y: auto;
}

.table-fixed-header thead,
.table-fixed-header tbody tr {
    display: table;
    width: 100%;
    table-layout: fixed;
}

.table-fixed-header thead {
    display: table;
    width: calc(100% - 17px); /* default scrollbar width */
    table-layout: fixed;
}

#mytable th:nth-child(1),
#mytable td:nth-child(1) { width: 40px; }

#mytable th:nth-child(13),
#mytable td:nth-child(13) { width: 40px; }

</style>
    <!-- MAIN -->
    <div class="col p-4">
        <h4 class="text-center">FORM UPDATE JOURNAL</h4>
<div class="box">
    <div class="box header">
<form id="form-data" method="post">
        <div class="form-row">
            <div class="col-md-3 mb-3">            
            <label for="pajak" class="col-form-label" style="width: 150px;"><b>No Journal</b></label>
            <?php
            $no_mj = base64_decode($_GET['no_mj']);
            $nama_type = $_GET['nama_type'];
            $start_date = $_GET['start_date'];
            $end_date = $_GET['end_date'];

            echo '<input type="hidden" name="nama_type" id="nama_type" value="'.$nama_type.'">
            <input type="hidden" name="start_date" id="start_date" value="'.$start_date.'">
            <input type="hidden" name="end_date" id="end_date" value="'.$end_date.'">';



            echo'<input type="text" readonly style="font-size: 14px;" class="form-control-plaintext" id="no_doc" name="no_doc" value="'.$no_mj.'">'
            ?>
        </div>

            <div class="col-md-2 mb-3">            
            <label for="total" class="col-form-label" style="width: 150px;"><b>Date</b></label>
                <input type="text" style="font-size: 15px;" name="tgl_doc" id="tgl_doc" class="form-control tanggal" 
            value="<?php 
            $no_mj = base64_decode($_GET['no_mj']);
            $sql = mysqli_query($conn2,"select tgl_journal from sb_list_journal where no_journal = '$no_mj'");
            $row = mysqli_fetch_array($sql);                         
            if(!empty($no_mj)) {
                echo date("d-m-Y",strtotime($row['tgl_journal']));
            }
            else{
                echo date("d-m-Y");
            }  ?>" autocomplete='off'>
            </div>

            
            <div class="col-md-3 mb-3" style="padding-top: 8px;">
            <label for="nama_supp"><b>Type</b></label>            
              <select class="form-control select2bs4" name="nama_type" id="nama_type" data-dropup-auto="false" data-live-search="true">
                <?php 
            $no_mj = base64_decode($_GET['no_mj']);
            $sql = mysqli_query($conn2,"select type_journal from sb_list_journal where no_journal = '$no_mj'");
            $row = mysqli_fetch_array($sql);  
            $id_cmj = $row['type_journal'];
            $nama_cmj = strtoupper($row['type_journal']);  
            $isSelected = ' selected="selected"';                      
            if(!empty($no_mj)) {
                echo '<option value="'.$id_cmj.'"'.$isSelected.'">'. $nama_cmj .'</option>'; 
            }
            else{
                echo '<option value="-">-</option>'; 
            }  ?>
                </select>

            </div>
            <div class="col-md-4 mb-3">     
            <!-- <input type="text" style="font-size: 15px;" name="count_jml" id="count_jml" class="form-control tanggal" 
            value="<?php 
            $no_mj = base64_decode($_GET['no_mj']);
            $sql = mysqli_query($conn2,"select count(id) jml from sb_list_journal where no_journal = '$no_mj' group by no_journal");
            $row = mysqli_fetch_array($sql);                         
            if(!empty($no_mj)) {
                echo $row['jml'];
            }
            else{
                echo '0';
            }  ?>" autocomplete='off'>      -->  
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
           
            <?php
// PRELOAD DATA
$no_mj = isset($_GET['no_mj']) ? base64_decode($_GET['no_mj']) : '';
if ($no_mj == '') { echo "No journal specified."; exit; }

function h($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

//FETCH JOURNAL ROWS SAJA
$sql = mysqli_query($conn1,
"SELECT a.no_coa, CONCAT(c.no_coa,' ',c.nama_coa) AS coa,
        a.no_costcenter, d.cc_name,
        a.reff_doc AS no_reff, a.reff_date,
        a.buyer, a.no_ws, a.curr, a.rate,
        a.debit, a.credit,
        a.keterangan AS remark
 FROM sb_list_journal a
 LEFT JOIN mastercoa_sb c ON c.no_coa = a.no_coa
 LEFT JOIN b_master_cc d ON d.no_cc = a.no_costcenter
 WHERE a.no_journal = '".mysqli_real_escape_string($conn1,$no_mj)."' order by a.id asc"
);

$rows = [];
while ($r = mysqli_fetch_assoc($sql)) $rows[] = $r;
?>

<table id="mytable" class="table table-bordered table-striped table-fixed-header" style="font-size:11px;">
<thead>
<tr>
    <th>-</th>
    <th>COA</th>
    <th>Cost Center</th>
    <th>Reference</th>
    <th>Ref Date</th>
    <th>Buyer</th>
    <th>Worksheet</th>
    <th>Curr</th>
    <th hidden>Rate</th>
    <th>Debit</th>
    <th>Credit</th>
    <th>Remark</th>
    <th>Action</th>
</tr>
</thead>

<tbody id="tbody2">
<?php foreach ($rows as $r): ?>
<?php
$date = ($r['reff_date']=='' || $r['reff_date']=='1970-01-01') ? '' : date('d-m-Y', strtotime($r['reff_date']));
?>
<tr>
    <td><input type="checkbox" class="checkrow"></td>

    <td>
        <select class="form-control sel-coa" name="nomor_coa[]" data-selected="<?= h($r['no_coa']) ?>" required>
            <?php if ($r['no_coa'] == "" || $r['no_coa'] == null): ?>
                <option value="">-- Pilih --</option>
            <?php endif; ?>
            <option value="<?= h($r['no_coa']) ?>"><?= h($r['coa']) ?></option>
        </select>
    </td>

    <td>
        <select class="form-control sel-cc" name="nomor_cc[]" data-selected="<?= h($r['no_costcenter']) ?>" required>
            <?php if ($r['no_costcenter'] == "" || $r['no_costcenter'] == null): ?>
                <option value="">-- Pilih --</option>
            <?php endif; ?>
            <option value="<?= h($r['no_costcenter']) ?>"><?= h($r['cc_name']) ?></option>
        </select>
    </td>

    <td><input type="text" name="ref_no[]" class="form-control" value="<?= h($r['no_reff']) ?>"></td>

    <td><input type="text" name="tgl_active[]" class="form-control tanggal" value="<?= h($date) ?>"></td>

    <td>
        <select class="form-control sel-buyer" name="buyer[]" data-selected="<?= h($r['buyer']) ?>" required>
            <?php if ($r['buyer'] == "" || $r['buyer'] == null): ?>
                <option value="">-- Pilih --</option>
            <?php endif; ?>
            <option value="<?= h($r['buyer']) ?>"><?= h($r['buyer']) ?></option>
        </select>
    </td>

    <td>
        <select class="form-control sel-ws" name="no_ws[]" data-selected="<?= h($r['no_ws']) ?>" required>
            <?php if ($r['no_ws'] == "" || $r['no_ws'] == null): ?>
                <option value="">-- Pilih --</option>
            <?php endif; ?>
            <option value="<?= h($r['no_ws']) ?>"><?= h($r['no_ws']) ?></option>
        </select>
    </td>

    <td>
        <select class="form-control sel-curr" name="currenc[]" onchange="ubahrate(this)" required>
            <?php if ($r['curr'] == "" || $r['curr'] == null): ?>
                <option value="">-- Pilih --</option>
            <?php endif; ?>
            <option value="<?= h($r['curr']) ?>"><?= h($r['curr']) ?></option>
        </select>
    </td>

    <td hidden><input type="text" name="rate[]" class="form-control" value="<?= h($r['rate']) ?>"></td>

    <td><input type="text" name="debit[]" class="form-control debit" value="<?= $r['debit']=="0"?"":h($r['debit']) ?>" oninput="modal_input_amt(value)"></td>

    <td><input type="text" name="credit[]" class="form-control credit" value="<?= $r['credit']=="0"?"":h($r['credit']) ?>" oninput="modal_input_amt2(value)"></td>

    <td><input type="text" name="remark[]" class="form-control" value="<?= h($r['remark']) ?>"></td>

    <td><input type="checkbox" class="remove"></td>
</tr>
<?php endforeach; ?>
</tbody>

<tfoot>
<tr>
    <td colspan="13" class="text-center">
        <button type="button" class="btn btn-primary" id="btnAdd">Add Row</button>
        <button type="button" class="btn btn-warning" id="btnInsert">Interject Row</button>
        <button type="button" class="btn btn-danger" id="btnDelete">Delete Row</button>
    </td>
</tr>
</tfoot>
</table>




<!-- TEMPLATE ROW (hidden) -->
<table style="display:none;">
<tr id="templateRow">
    <td><input type="checkbox" class="checkrow"></td>

    <td><select class="form-control sel-coa" name="nomor_coa[]"><option value="">-- Pilih --</option></select></td>
    <td><select class="form-control sel-cc" name="nomor_cc[]"><option value="">-- Pilih --</option></select></td>
    <td><input type="text" name="ref_no[]" class="form-control"></td>
    <td><input type="text" name="tgl_active[]" class="form-control tanggal"></td>
    <td><select class="form-control sel-buyer" name="buyer[]"><option value="">-- Pilih --</option></select></td>
    <td><select class="form-control sel-ws" name="no_ws[]"><option value="">-- Pilih --</option></select></td>

    <td>
    <select class="form-control sel-curr" name="currenc[]" onchange="ubahrate(this)">
    <option value="">-- Pilih --</option></select>
    </td>


    <td hidden><input type="text" name="rate[]" class="form-control" value="1"></td>

    <td><input type="number" name="debit[]" class="form-control debit" oninput="modal_input_amt(value)"></td>
    <td><input type="number" name="credit[]" class="form-control credit" oninput="modal_input_amt2(value)"></td>
    <td><input type="text" name="remark[]" class="form-control"></td>

    <td><input type="checkbox" class="remove"></td>
</tr>
</table>

  
            </div>                 
<div class="box footer">   
        <form id="form-simpan">
            <div class="form-row col">
            <div class="col-md-4">
                </br>
      
            
            </br>
        <!--     <div class="input-group" >
                <label for="nama_supp" class="col-form-label" style="width: 80px;"><b>Tax (11%)</b></label>
                <input type="checkbox" id="check_vat_baru" name="check_vat_baru" onclick="modal_input_vat_baru()">
            </div>
            </br> -->
             
            
            </div>
            <div class="col-md-4">

            </div>
            <div class="col-md-4">
                </br>
                <div class="input-group" >
                <label for="nama_supp" class="col-form-label" style="width: 180px;"><b>Total Credit</b></label>
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
                 <input type="hidden" name="txt_credit_h" id="txt_credit_h" value="<?php             
            $no_mj = base64_decode($_GET['no_mj']);
            $sqldes = mysqli_query($conn2,"select sum(credit) as credit from sb_list_journal where no_journal = '$no_mj'");
            $row = mysqli_fetch_array($sqldes);      
            $credit = $row['credit'];                  
            if(!empty($no_mj)) {
                echo $credit;
            }
            else{
                echo '';
            } ?>">
            </div>
            </br>
            <div class="input-group" >
                <label for="nama_supp" class="col-form-label" style="width: 180px;"><b>Total Debit</b></label>
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
                 <input type="hidden" name="txt_debit_h" id="txt_debit_h" value="<?php             
            $no_mj = base64_decode($_GET['no_mj']);
            $sqldes = mysqli_query($conn2,"select sum(debit) as debit from sb_list_journal where no_journal = '$no_mj'");
            $row = mysqli_fetch_array($sqldes);      
            $debit = $row['debit'];                  
            if(!empty($no_mj)) {
                echo $debit;
            }
            else{
                echo '';
            } ?>">
            </div>
            </br>
            <div class="input-group" >
                <label for="nama_supp" class="col-form-label" style="width: 180px;"><b>Total Credit IDR</b></label>
                <input type="text" style="font-size: 14px;text-align: right;" class="form-control" id="txt_credit_idr" name="txt_credit_idr" value="<?php             
            $no_mj = base64_decode($_GET['no_mj']);
            $sqldes = mysqli_query($conn2,"select format(sum(credit_idr),2) as credit_idr from sb_list_journal where no_journal = '$no_mj'");
            $row = mysqli_fetch_array($sqldes);      
            $credit_idr = $row['credit_idr'];                  
            if(!empty($no_mj)) {
                echo $credit_idr;
            }
            else{
                echo '';
            } ?>" placeholder="0.00"  readonly>
                 <input type="hidden" name="txt_credit_idr_h" id="txt_credit_idr_h" value="<?php             
            $no_mj = base64_decode($_GET['no_mj']);
            $sqldes = mysqli_query($conn2,"select sum(credit_idr) as credit_idr from sb_list_journal where no_journal = '$no_mj'");
            $row = mysqli_fetch_array($sqldes);      
            $credit_idr = $row['credit_idr'];                  
            if(!empty($no_mj)) {
                echo $credit_idr;
            }
            else{
                echo '';
            } ?>">
            </div>
            </br>
            <div class="input-group" >
                <label for="nama_supp" class="col-form-label" style="width: 180px;"><b>Total Debit IDR</b></label>
                <input type="text" style="font-size: 14px;text-align: right;" class="form-control" id="txt_debit_idr" name="txt_debit_idr" value="<?php             
            $no_mj = base64_decode($_GET['no_mj']);
            $sqldes = mysqli_query($conn2,"select format(sum(debit_idr),2) as debit_idr from sb_list_journal where no_journal = '$no_mj'");
            $row = mysqli_fetch_array($sqldes);      
            $debit_idr = $row['debit_idr'];                  
            if(!empty($no_mj)) {
                echo $debit_idr;
            }
            else{
                echo '';
            } ?>" placeholder="0.00" readonly>
                 <input type="hidden" name="txt_debit_idr_h" id="txt_debit_idr_h" value="<?php             
            $no_mj = base64_decode($_GET['no_mj']);
            $sqldes = mysqli_query($conn2,"select sum(debit_idr) as debit_idr from sb_list_journal where no_journal = '$no_mj'");
            $row = mysqli_fetch_array($sqldes);      
            $debit_idr = $row['debit_idr'];                  
            if(!empty($no_mj)) {
                echo $debit_idr;
            }
            else{
                echo '';
            } ?>">
            </div>
            </br>
             
        </div>
           <div class="form-row col">
            <div class="col-md-3 mb-3">                              
            <button type="button" style="border-radius: 6px" class="btn-outline-primary btn-sm" name="simpan" id="simpan"><span class="fa fa-floppy-o"></span> Save</button>                
            <button type="button" style="border-radius: 6px" class="btn-outline-danger btn-sm" name="batal" id="batal" onclick="location.href='edit-journal.php'"><span class="fa fa-angle-double-left"></span> Back</button>           
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


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

    function fixHeaderWidth() {
    let tbody = document.querySelector("#mytable tbody");
    let thead = document.querySelector("#mytable thead");

    let scrollbarWidth = tbody.offsetWidth - tbody.clientWidth;
    thead.style.width = `calc(100% - ${scrollbarWidth}px)`;
}

window.onload = fixHeaderWidth;
window.onresize = fixHeaderWidth;

});
</script>

<script>
$(function() {
    $('.selectpicker').selectpicker();
});

</script>

<script>
function initSelect2(row) {

    row.find(".sel-coa").select2({
        width: "100%",
        ajax: {
            url: "coa.php",
            dataType: "json",
            delay: 250,
            data: params => ({ q: params.term }),
            processResults: data => ({ results: data })
        }
    });

    row.find(".sel-cc").select2({
        width: "100%",
        ajax: {
            url: "cc.php",
            dataType: "json",
            delay: 250,
            data: params => ({ q: params.term }),
            processResults: data => ({ results: data })
        }
    });

    row.find(".sel-buyer").select2({
        width: "100%",
        ajax: {
            url: "buyer.php",
            dataType: "json",
            delay: 250,
            data: params => ({ q: params.term }),
            processResults: data => ({ results: data })
        }
    });

    row.find(".sel-ws").select2({
        width: "100%",
        ajax: {
            url: "ws.php",
            dataType: "json",
            delay: 250,
            data: params => ({ q: params.term }),
            processResults: data => ({ results: data })
        }
    });

    row.find(".sel-curr").select2({
        width: "100%",
        ajax: {
            url: "curr.php",
            dataType: "json",
            delay: 250,
            data: params => ({ q: params.term }),
            processResults: data => ({ results: data })
        }
    });
}

$(document).ready(function() {
    // INIT select2 for existing rows
    $("#tbody2 tr").each(function(){
        initSelect2($(this));
    });

    // ADD ROW
    $("#btnAdd").click(function(){
        let r = $("#templateRow").clone().removeAttr("id").show();
        $("#tbody2").append(r);
        initSelect2(r);
    });

    // INSERT ROW (to top)
    $("#btnInsert").click(function(){
        let r = $("#templateRow").clone().removeAttr("id").show();
        $("#tbody2 tr:first").before(r);
        initSelect2(r);
    });

    // DELETE ROWS
    $("#btnDelete").click(function(){
        $("#tbody2 .remove:checked").each(function(){
            $(this).closest("tr").remove();
        });
    });
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
// function addRow(tableID) {
//     var tableID = "tbody2";
//  var table = document.getElementById(tableID);
//  var rowCount = table.rows.length;
//  var row = table.insertRow(rowCount);

// $(function() {
//     $('.selectpicker').selectpicker();
// });
// $(document).ready(function () {
//     $('.tanggal').datepicker({
//         format: "dd-mm-yyyy",
//         autoclose:true
//     });
// });
// $(function() {
//       //Initialize Select2 Elements
//       var selectcoba = rowCount;
//       $('.rowCount').select2({
//          theme: 'bootstrap4'
//       })
//       //Initialize Select2 Elements
//       $('.select2add').select2({
//         theme: 'bootstrap4'
//       })
//     });
//  $coa = '';
//  var element1 = '<tr ><td><input type="checkbox" id="select" name="select[]" value="" checked disabled></td><td style="width: 50px"><select class="form-control selectpicker" name="nomor_coa" id="nomor_coa" data-live-search="true"> <option value="-" > - </option><?php $sql = mysqli_query($conn1,"select no_coa as id_coa,concat(no_coa,' ', nama_coa) as coa from mastercoa_v2"); foreach ($sql as $coa) : ?> <option value="<?= $coa["id_coa"]; ?>"><?= $coa["coa"]; ?> </option><?php endforeach; ?></select></td><td ><select class="form-control selectpicker" name="nomor_cc" id="nomor_cc" data-live-search="true"> <option value="-" > - </option><?php $sql2 = mysqli_query($conn1,"select no_cc as code_combine,cc_name as cost_name from b_master_cc"); foreach ($sql2 as $cc) : ?> <option value="<?= $cc["code_combine"]; ?>"><?= $cc["cost_name"]; ?> </option><?php endforeach; ?></select></td><td><input type="text" class="form-control" name="keterangan[]" placeholder="" autocomplete="off"></td><td><input type="text" class="form-control tanggal" name="keterangan[]" placeholder="" autocomplete="off"></td><td><select class="form-control selectpicker" name="buyer" id="buyer" data-live-search="true"> <option value="-" > - </option><?php $sql4 = mysqli_query($conn1,"select distinct(Supplier) as buyer from mastersupplier where tipe_sup = 'C' order by Supplier ASC"); foreach ($sql4 as $ms) : ?> <option value="<?= $ms["buyer"]; ?>"><?= $ms["buyer"]; ?> </option><?php endforeach; ?></select></td><td><select class="form-control selectpicker" name="no_ws" id="no_ws" data-live-search="true"> <option value="-" > - </option><?php $sql3 = mysqli_query($conn1,"select DISTINCT kpno no_ws from act_costing where cost_date >= '2022-01-01'"); foreach ($sql3 as $ws) : ?> <option value="<?= $ws["no_ws"]; ?>"><?= $ws["no_ws"]; ?> </option><?php endforeach; ?></select></td><td><select class="form-control selectpicker" name="currenc" id="currenc" onchange="ubahrate(this.value)" data-live-search="true"><option value="IDR">IDR</option><option value="USD">USD</option></select></td><td style="text-align: right; display: none"><input type="number" style="text-align: right;" class="form-control" name="keterangan[]" placeholder="" autocomplete="off" readonly value="1"></td><td style="text-align: right;"><input style="text-align: right;" type="number" min="1" style="font-size: 12px;" class="form-control" id="txt_amount" name="txt_amount"  oninput="modal_input_amt(value)" autocomplete = "off"></td><td><input style="text-align: right;" type="number" min="1" style="font-size: 12px;" class="form-control" id="txt_amount" name="txt_amount"  oninput="modal_input_amt2(value)" autocomplete = "off"></td><td><input type="text" class="form-control" name="keterangan[]" placeholder="" autocomplete="off"></td><td><input name="chk_a[]" type="checkbox" class="checkall_a" value=""></td></tr>';
//  row.innerHTML = element1;    
    
//     }
    
// function deleteRow()
// {
//     try
//          {
//         var table = document.getElementById("tbody2");
//         var rowCount = table.rows.length;
//             for(var i=0; i<rowCount; i++)
//                 {
//                 var row = table.rows[i];
//                 var chkbox = row.cells[12].childNodes[0];
//                 if (null != chkbox && true == chkbox.checked)
//                     {
//                     if (rowCount <= 0)
//                         {
//                         alert("Tidak dapat menghapus semua baris.");
//                         break;
//                         }
//                     table.deleteRow(i);
//                     rowCount--;
//                     i--;
//                     }
//                 }
//             } catch(e)
//     {
//     alert(e);
//     }
//  }
 
//  function InsertRow(tableID)
// {
//     try{
//         var table = document.getElementById(tableID);
//         var rowCount = table.rows.length;
//             for(var i=0; i<rowCount; i++)
//                 {
//                 var row = table.rows[i];
//                 var chkbox = row.cells[12].childNodes[0];
//                 if (null != chkbox && true == chkbox.checked)
//                     {
// $(function() {
//     $('.selectpicker').selectpicker();

// });

// $(document).ready(function () {
//     $('.tanggal').datepicker({
//         format: "dd-mm-yyyy",
//         autoclose:true
//     });
// });
//         var element2 = '<tr ><td><input type="checkbox" id="select" name="select[]" value="" checked disabled></td><td style="width: 50px"><select class="form-control selectpicker" name="nomor_coa" id="nomor_coa" data-live-search="true"> <option value="-" > - </option><?php $sql = mysqli_query($conn1,"select no_coa as id_coa,concat(no_coa,' ', nama_coa) as coa from mastercoa_v2"); foreach ($sql as $coa) : ?> <option value="<?= $coa["id_coa"]; ?>"><?= $coa["coa"]; ?> </option><?php endforeach; ?></select></td><td ><select class="form-control selectpicker" name="nomor_cc" id="nomor_cc" data-live-search="true"> <option value="-" > - </option><?php $sql2 = mysqli_query($conn1,"select no_cc as code_combine,cc_name as cost_name from b_master_cc"); foreach ($sql2 as $cc) : ?> <option value="<?= $cc["code_combine"]; ?>"><?= $cc["cost_name"]; ?> </option><?php endforeach; ?></select></td><td><input type="text" class="form-control" name="keterangan[]" placeholder="" autocomplete="off"></td><td><input type="text" class="form-control tanggal" name="keterangan[]" placeholder="" autocomplete="off"></td><td><select class="form-control selectpicker" name="buyer" id="buyer" data-live-search="true"> <option value="-" > - </option><?php $sql4 = mysqli_query($conn1,"select distinct(Supplier) as buyer from mastersupplier where tipe_sup = 'C' order by Supplier ASC"); foreach ($sql4 as $ms) : ?> <option value="<?= $ms["buyer"]; ?>"><?= $ms["buyer"]; ?> </option><?php endforeach; ?></select></td><td><select class="form-control selectpicker" name="no_ws" id="no_ws" data-live-search="true"> <option value="-" > - </option><?php $sql3 = mysqli_query($conn1,"select DISTINCT kpno no_ws from act_costing where cost_date >= '2022-01-01'"); foreach ($sql3 as $ws) : ?> <option value="<?= $ws["no_ws"]; ?>"><?= $ws["no_ws"]; ?> </option><?php endforeach; ?></select></td><td><select class="form-control selectpicker" name="currenc" id="currenc" onchange="ubahrate(this.value)" data-live-search="true"><option value="IDR">IDR</option><option value="USD">USD</option></select></td><td style="text-align: right; display: none"><input type="number" style="text-align: right;" class="form-control" name="keterangan[]" placeholder="" autocomplete="off" readonly value="1"></td><td style="text-align: right;"><input style="text-align: right;" type="number" min="1" style="font-size: 12px;" class="form-control" id="txt_amount" name="txt_amount"  oninput="modal_input_amt(value)" autocomplete = "off"></td><td><input style="text-align: right;" type="number" min="1" style="font-size: 12px;" class="form-control" id="txt_amount" name="txt_amount"  oninput="modal_input_amt2(value)" autocomplete = "off"></td><td><input type="text" class="form-control" name="keterangan[]" placeholder="" autocomplete="off"></td><td><input name="chk_a[]" type="checkbox" class="checkall_a" value=""></td></tr>';
//         var newRow = table.insertRow(i+1);
//         newRow.innerHTML = element2;
                    
//                     }
                    
//                 }
//             } catch(e)
//     {
//     alert(e);
//     }
//  }

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
    // alert(ratess)
    if (ratess == 1) {
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

    var rates = table.rows[i].cells[8].children[0];
        var curren = table.rows[i].cells[7].children[0].value; // <-- FIXED

        var amt  = parseFloat(table.rows[i].cells[9].children[0].value)  || 0;
        var amt2 = parseFloat(table.rows[i].cells[10].children[0].value) || 0;
    // console.log(curren);
    if (curren == 'IDR') {
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
   $("#simpan").on("click", function () {

    $("#simpan")
        .prop("disabled", true)
        .html('<i class="fa fa-spinner fa-spin"></i> Processing...');

    alert("Data sedang diproses, mohon tunggu...");

    var no_mj      = $("#no_doc").val();
    var mj_date    = $("#tgl_doc").val();
    var create_user = "<?php echo $user; ?>";
    var nama_type   = $("select[name=nama_type]").val();

    var totalDebit  = $("#txt_debit_idr_h").val();
    var totalCredit = $("#txt_credit_idr_h").val();
    let typename  = document.getElementById("nama_type").value;
    let start_date = document.getElementById("start_date").value;
    let end_date   = document.getElementById("end_date").value;

    // VALIDASI
    if (nama_type == "") {
        alert("Please Select Type Journal");
        $("#simpan").prop("disabled", false).html("Save");
        return;
    }

    if (totalDebit != totalCredit) {
        alert("Debit and Credit can't Balance");
        $("#simpan").prop("disabled", false).html("Save");
        return;
    }

    // ===============================
    // STEP 1 : COPY HEADER
    // ===============================
    $.ajax({
        type: "POST",
        url: "copy_data_jurnal.php",
        data: {
            no_mj: no_mj,
            create_user: create_user
        },
        success: function (res1) {

            // ===============================
            // STEP 2 : LOOP PER BARIS
            // ===============================
            $("#tbody2 tr").each(function (index) {

                // Hanya baris yang diceklis
                var tr = $(this);

                var dataRow = {

                    no_mj: no_mj,
                    mj_date: mj_date,
                    id_cmj: nama_type,

                    no_coa: tr.find("select[name='nomor_coa[]']").val(),
                    no_costcenter: tr.find("select[name='nomor_cc[]']").val(),

                    no_reff: tr.find("input[name='ref_no[]']").val(),
                    reff_date: tr.find("input[name='tgl_active[]']").val(),

                    buyer: tr.find("select[name='buyer[]']").val(),
                    no_ws: tr.find("select[name='no_ws[]']").val(),

                    curr: tr.find("select[name='currenc[]']").val(),
                    rate: tr.find("input[name='rate[]']").val(),

                    debit: tr.find("input[name='debit[]']").val() || 0,
                    credit: tr.find("input[name='credit[]']").val() || 0,

                    keterangan: tr.find("input[name='remark[]']").val(),

                    create_user: create_user
                };

                // ===============================
                // INSERT DETAIL PER ROW
                // ===============================
                // console.log(dataRow);
                $.ajax({
                    type: "POST",
                    url: "insert_all_journal_edit.php",
                    data: dataRow,
                    success: function (res2) {
                        console.log("Insert row " + index + ": OK");
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                        alert("Gagal insert row ke-" + index);
                    }
                });

            }); // end each

            alert("No Journal: " + no_mj + " Changed successfully");
            window.location = "edit-journal.php?nama_type=" + typename 
                + "&start_date=" + start_date 
                + "&end_date=" + end_date;
        },

        error: function (xhr) {
            alert("Gagal copy header!");
            console.log(xhr.responseText);
            $("#simpan").prop("disabled", false).html("Save");
        }
    });

});


</script>


<script type="text/javascript">
$("#select_all").click(function() {
  var c = this.checked;
  $(':checkbox').prop('checked', c);
});  
</script>

<script type="text/javascript">
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
