<?php include '../header2.php' ?>

    <!-- MAIN -->    
    <div class="col p-4">
        <h4 class="text-center">LIST OUTGOING BANK</h4>
<div class="box">
    <div class="box header">
<form id="form-data" action="bank-out.php" method="post">
        <div class="form-row">
            <div class="col-md-6">
            <label for="nama_supp"><b>Supplier</b></label>            
              <select class="form-control selectpicker" name="nama_supp" id="nama_supp" data-dropup-auto="false" data-live-search="true" onchange="this.form.submit()">
                <option value="ALL" <?php
                $nama_supp = '';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $status = isset($_POST['nama_supp']) ? $_POST['nama_supp']: null;
                }                 
                    if($nama_supp == 'ALL'){
                        $isSelected = ' selected="selected"';
                    }else{
                        $isSelected = '';
                    }
                    echo $isSelected;
                ?>                
                >ALL</option>                                 
                <?php
                $nama_supp ='';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $nama_supp = isset($_POST['nama_supp']) ? $_POST['nama_supp']: null;
                }                 
                $sql = mysqli_query($conn1,"select distinct(Supplier) from mastersupplier where tipe_sup = 'S' order by Supplier ASC");
                while ($row = mysqli_fetch_array($sql)) {
                    $data = $row['Supplier'];
                    if($row['Supplier'] == $_POST['nama_supp']){
                        $isSelected = ' selected="selected"';
                    }else{
                        $isSelected = '';
                    }
                    echo '<option value="'.$data.'"'.$isSelected.'">'. $data .'</option>';    
                }?>
                </select>
                </div>  

                <div class="col-md-3">
            <label for="nama_supp"><b>Bank</b></label>            
              <select class="form-control selectpicker" name="bank" id="bank" data-dropup-auto="false" data-live-search="true" onchange="this.form.submit()">
                <option value="ALL" selected="true">ALL</option>                                                
                <?php
                $bank ='';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $bank = isset($_POST['bank']) ? $_POST['bank']: null;
                }                 
                $sql = mysqli_query($conn1,"select distinct(bank_name) as nama_bank from b_masterbank  order by bank_name ASC");
                while ($row = mysqli_fetch_array($sql)) {
                    $data = $row['nama_bank'];
                    if($row['nama_bank'] == $_POST['bank']){
                        $isSelected = ' selected="selected"';
                    }else{
                        $isSelected = '';
                    }
                    echo '<option value="'.$data.'"'.$isSelected.'">'. $data .'</option>';    
                }?>
                </select>
                </div>

                <div class="col-md-3">
            <label for="nama_supp"><b>Account</b></label>            
              <select class="form-control selectpicker" name="akun" id="akun" data-dropup-auto="false" data-live-search="true" onchange="this.form.submit()">
                <option value="ALL" selected="true">ALL</option>                                                
                <?php
                $akun ='';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $bank = isset($_POST['bank']) ? $_POST['bank']: null;
                $akun = isset($_POST['akun']) ? $_POST['akun']: null;
                }                 
                $sql = mysqli_query($conn1,"select distinct(bank_account) as no_rek from b_masterbank where bank_name = '$bank' and status = 'Active' order by id ASC");
                while ($row = mysqli_fetch_array($sql)) {
                    $data = $row['no_rek'];
                    if($row['no_rek'] == $_POST['akun']){
                        $isSelected = ' selected="selected"';
                    }else{
                        $isSelected = '';
                    }
                    echo '<option value="'.$data.'"'.$isSelected.'">'. $data .'</option>';    
                }?>
                </select>
                </div>


                <div class="col-md-5">
            <label for="status"><b>Status</b></label>            
              <select class="form-control selectpicker" name="status" id="status" data-dropup-auto="false" data-live-search="true" onchange="this.form.submit()">
                <option value="ALL" <?php
                $status = '';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $status = isset($_POST['status']) ? $_POST['status']: null;
                }                 
                    if($status == 'ALL'){
                        $isSelected = ' selected="selected"';
                    }else{
                        $isSelected = '';
                    }
                    echo $isSelected;
                ?>                
                >ALL</option>
                <option value="draft" <?php
                $status = '';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $status = isset($_POST['status']) ? $_POST['status']: null;
                }                 
                    if($status == 'draft'){
                        $isSelected = ' selected="selected"';
                    }else{
                        $isSelected = '';
                    }
                    echo $isSelected;
                ?>
                >Draft</option>
                <option value="Approved" <?php
                $status = '';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $status = isset($_POST['status']) ? $_POST['status']: null;
                }                 
                    if($status == 'Approved'){
                        $isSelected = ' selected="selected"';
                    }else{
                        $isSelected = '';
                    }
                    echo $isSelected;
                ?>
                >Approved</option>
                <option value="Cancel" <?php
                $status = '';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $status = isset($_POST['status']) ? $_POST['status']: null;
                }                 
                    if($status == 'Cancel'){
                        $isSelected = ' selected="selected"';
                    }else{
                        $isSelected = '';
                    }
                    echo $isSelected;
                ?>
                >Cancel</option>                                                                                                            
                </select>
                </div>

            <div class="col-md-2 mb-3"> 
            <label for="start_date"><b>From</b></label>          
            <input type="text" style="font-size: 12px;" class="form-control tanggal" id="start_date" name="start_date" 
            value="<?php
            $start_date ='';
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
               $start_date = date("Y-m-d",strtotime($_POST['start_date']));
            }
            if(!empty($_POST['start_date'])) {
               echo $_POST['start_date'];
            }
            else{
               echo date("d-m-Y");
            } ?>" 
            placeholder="Tanggal Awal" onchange="this.form.submit()">
            </div>

            <div class="col-md-2 mb-3"> 
            <label for="end_date"><b>To</b></label>          
            <input type="text" style="font-size: 12px;" class="form-control tanggal" id="end_date" name="end_date" 
            value="<?php
            $end_date ='';
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
               $end_date = date("Y-m-d",strtotime($_POST['end_date']));
            }
            if(!empty($_POST['end_date'])) {
               echo $_POST['end_date'];
            }
            else{
               echo date("d-m-Y");
            } ?>" 
            placeholder="Tanggal Awal" onchange="this.form.submit()">
            </div>

            <?php
            $nama_supp ='';
            $start_date ='';
            $end_date =''; 
            $bank ='';
            $akun ='';
            $status ='';
            $date_now = date("Y-m-d");           
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_supp = isset($_POST['nama_supp']) ? $_POST['nama_supp']: null;
            $bank = isset($_POST['bank']) ? $_POST['bank']: null;
            $akun = isset($_POST['akun']) ? $_POST['akun']: null;
            $status = isset($_POST['status']) ? $_POST['status']: null;
            $start_date = date("Y-m-d",strtotime($_POST['start_date']));
            $end_date = date("Y-m-d",strtotime($_POST['end_date']));            
            }

            if($nama_supp == 'ALL' and $bank == 'ALL' and $akun == 'ALL' and $status == 'ALL' and $start_date == '1970-01-01' and !empty($end_date)){
            $where = "where bankout_date = '$date_now'";
            }elseif($nama_supp == 'ALL' and $bank == 'ALL' and $akun == 'ALL' and $status == 'ALL' and !empty($start_date) and !empty($end_date)){
            $where = "where bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp != 'ALL' and $bank == 'ALL' and $akun == 'ALL' and $status == 'ALL'){
            $where = "where nama_supp = '$nama_supp' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp == 'ALL' and $bank != 'ALL' and $akun == 'ALL' and $status == 'ALL'){
            $where = "where bank = '$bank' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp == 'ALL' and $bank == 'ALL' and $akun != 'ALL' and $status == 'ALL'){
            $where = "where akun = '$akun' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp == 'ALL' and $bank == 'ALL' and $akun == 'ALL' and $status != 'ALL'){
            $where = "where status = '$status' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp != 'ALL' and $bank != 'ALL' and $akun == 'ALL' and $status == 'ALL'){
            $where = "where nama_supp = '$nama_supp' and bank = '$bank' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp != 'ALL' and $bank == 'ALL' and $akun != 'ALL' and $status == 'ALL'){
            $where = "where nama_supp = '$nama_supp' and akun = '$akun' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp != 'ALL' and $bank == 'ALL' and $akun == 'ALL' and $status != 'ALL'){
            $where = "where nama_supp = '$nama_supp' and status = '$status' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp == 'ALL' and $bank != 'ALL' and $akun != 'ALL' and $status == 'ALL'){
            $where = "where bank = '$bank' and akun = '$akun' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp == 'ALL' and $bank != 'ALL' and $akun == 'ALL' and $status != 'ALL'){
            $where = "where bank = '$bank' and status = '$status' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp == 'ALL' and $bank == 'ALL' and $akun != 'ALL' and $status != 'ALL'){
            $where = "where akun = '$akun' and status = '$status' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp != 'ALL' and $bank != 'ALL' and $akun != 'ALL' and $status == 'ALL'){
            $where = "where nama_supp = '$nama_supp' and bank = '$bank' and akun = '$akun' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp != 'ALL' and $bank != 'ALL' and $akun == 'ALL' and $status != 'ALL'){
            $where = "where nama_supp = '$nama_supp' and bank = '$bank' and status = '$status' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp != 'ALL' and $bank == 'ALL' and $akun != 'ALL' and $status != 'ALL'){
            $where = "where nama_supp = '$nama_supp' and akun = '$akun' and status = '$status' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp == 'ALL' and $bank != 'ALL' and $akun != 'ALL' and $status != 'ALL'){
            $where = "where bank = '$bank' and akun = '$akun' and status = '$status' and bankout_date between '$start_date' and '$end_date'";
            }else{
              $where = "where nama_supp = '$nama_supp' and bank = '$bank' and akun = '$akun' and status = '$status' and bankout_date between '$start_date' and '$end_date'";  
            }

    echo '<div class="col-md-2 mb-3"> 
    <a style="padding-right: 10px;" target="_blank" href="ekspor_bank_out.php?where='.$where.' && start_date='.$start_date.' && end_date='.$end_date.'"><button type="button" class="btn btn-success " style= "margin-top: 30px;"><i class="fa fa-file-excel-o" aria-hidden="true" style="padding-right: 10px; padding-left: 5px;font-size: 1rem;color: #fff;text-shadow: 1px 1px 1px #000"> Excel</i></button></a>
    </div>';
        
?>           
</div>                   
    </div>
</form>
<!-- <?php
        $querys = mysqli_query($conn2,"select useraccess.menu as menu,useraccess.username as username, useraccess.fullname as fullname, menurole.id as id from useraccess inner join menurole on menurole.menu = useraccess.menu where username = '$user' and useraccess.menu = 'Create Bank'");
        $rs = mysqli_fetch_array($querys);
        $id = isset($rs['id']) ? $rs['id'] : 0;

        if($id == '37'){
    echo '<button id="btncreate" type="button" class="btn-primary btn-xs" style="border-radius: 6%"><span class="fa fa-pencil-square-o"></span> Create</button>';
        }else{
    echo '';
    }
?> -->

<button id="btncreate" type="button" class="btn-primary btn-xs" style="border-radius: 6%"><span class="fa fa-pencil-square-o"></span> Create</button>

    <div class="box body">
        <div class="row">       
            <div class="col-md-12">

            
<table id="mytable" class="table table-striped table-bordered" role="grid" cellspacing="0" width="100%">
    <thead>
        <tr class="thead-dark">
            <th style="text-align: center;vertical-align: middle;">No Bank In</th>
            <th style="text-align: center;vertical-align: middle;">Source</th>
            <th style="text-align: center;vertical-align: middle;">Date</th>
            <th style="text-align: center;vertical-align: middle;">Curreny</th>
            <th style="text-align: center;vertical-align: middle;">Amount</th>
            <th style="text-align: center;vertical-align: middle;display: none;">Outstanding</th>
            <th style="text-align: center;vertical-align: middle;">Status</th> 
            <th style="text-align: center;vertical-align: middle;">Approve Date</th>  
            <th style="text-align: center;vertical-align: middle;display: none;">Outstanding</th>
            <th style="text-align: center;vertical-align: middle;display: none;">Outstanding</th>
            <th style="text-align: center;vertical-align: middle;display: none;">Outstanding</th>
            <th style="text-align: center;vertical-align: middle;display: none;">Outstanding</th>        
            <th style="text-align: center;vertical-align: middle;">Action</th>                                                                                                                                                             
                        </tr>
                    </thead>

            <tbody>
            <?php
            $nama_supp ='';
            $start_date ='';
            $end_date =''; 
            $bank ='';
            $akun ='';
            $status ='';
            $date_now = date("Y-m-d");           
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_supp = isset($_POST['nama_supp']) ? $_POST['nama_supp']: null;
            $bank = isset($_POST['bank']) ? $_POST['bank']: null;
            $akun = isset($_POST['akun']) ? $_POST['akun']: null;
            $status = isset($_POST['status']) ? $_POST['status']: null;
            $start_date = date("Y-m-d",strtotime($_POST['start_date']));
            $end_date = date("Y-m-d",strtotime($_POST['end_date']));            
            }

            if($nama_supp == 'ALL' and $bank == 'ALL' and $akun == 'ALL' and $status == 'ALL' and $start_date == '1970-01-01' and !empty($end_date)){
            $where = "where bankout_date = '$date_now'";
            }elseif($nama_supp == 'ALL' and $bank == 'ALL' and $akun == 'ALL' and $status == 'ALL' and !empty($start_date) and !empty($end_date)){
            $where = "where bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp != 'ALL' and $bank == 'ALL' and $akun == 'ALL' and $status == 'ALL'){
            $where = "where nama_supp = '$nama_supp' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp == 'ALL' and $bank != 'ALL' and $akun == 'ALL' and $status == 'ALL'){
            $where = "where bank = '$bank' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp == 'ALL' and $bank == 'ALL' and $akun != 'ALL' and $status == 'ALL'){
            $where = "where akun = '$akun' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp == 'ALL' and $bank == 'ALL' and $akun == 'ALL' and $status != 'ALL'){
            $where = "where status = '$status' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp != 'ALL' and $bank != 'ALL' and $akun == 'ALL' and $status == 'ALL'){
            $where = "where nama_supp = '$nama_supp' and bank = '$bank' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp != 'ALL' and $bank == 'ALL' and $akun != 'ALL' and $status == 'ALL'){
            $where = "where nama_supp = '$nama_supp' and akun = '$akun' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp != 'ALL' and $bank == 'ALL' and $akun == 'ALL' and $status != 'ALL'){
            $where = "where nama_supp = '$nama_supp' and status = '$status' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp == 'ALL' and $bank != 'ALL' and $akun != 'ALL' and $status == 'ALL'){
            $where = "where bank = '$bank' and akun = '$akun' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp == 'ALL' and $bank != 'ALL' and $akun == 'ALL' and $status != 'ALL'){
            $where = "where bank = '$bank' and status = '$status' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp == 'ALL' and $bank == 'ALL' and $akun != 'ALL' and $status != 'ALL'){
            $where = "where akun = '$akun' and status = '$status' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp != 'ALL' and $bank != 'ALL' and $akun != 'ALL' and $status == 'ALL'){
            $where = "where nama_supp = '$nama_supp' and bank = '$bank' and akun = '$akun' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp != 'ALL' and $bank != 'ALL' and $akun == 'ALL' and $status != 'ALL'){
            $where = "where nama_supp = '$nama_supp' and bank = '$bank' and status = '$status' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp != 'ALL' and $bank == 'ALL' and $akun != 'ALL' and $status != 'ALL'){
            $where = "where nama_supp = '$nama_supp' and akun = '$akun' and status = '$status' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp == 'ALL' and $bank != 'ALL' and $akun != 'ALL' and $status != 'ALL'){
            $where = "where bank = '$bank' and akun = '$akun' and status = '$status' and bankout_date between '$start_date' and '$end_date'";
            }else{
              $where = "where nama_supp = '$nama_supp' and bank = '$bank' and akun = '$akun' and status = '$status' and bankout_date between '$start_date' and '$end_date'";  
            }

            
            $sql = mysqli_query($conn1," select  a.*,b.beneficiary_name from (select * from (select no_bankout,bankout_date,nama_supp,curr, amount, outstanding,IF(reff_doc = 'Payment','List Payment',reff_doc) as reff_doc, akun, bank,status,IF(deskripsi = '','-',deskripsi) as deskripsi,approve_date from sb_b_bankout_h $where ) a 
                UNION
                select * from (select no_bankout,bankout_date,nama_supp,curr, amount, outstanding,IF(reff_doc = 'Payment','List Payment',reff_doc) as reff_doc, akun, bank,status,IF(deskripsi = '','-',deskripsi) as deskripsi,approve_date from b_bankout_h $where ) a) a left join b_masterbank b on a.akun = b.bank_account where beneficiary_name = 'PT Nirwana Alabare Garment'");
                                                                      
                while($row = mysqli_fetch_array($sql)){
                    $approve_date = isset($row['approve_date']) ? $row['approve_date'] : null;


                     if ($approve_date == null) {
                           $app_date = '-'; 
                        }else{
                            $app_date = date("d-M-Y",strtotime($approve_date));
                        }
                    
           echo'<tr>                       
                            <td style="width:50px; text-align : center" value="'.$row['no_bankout'].'">'.$row['no_bankout'].'</td>
                            <td style="width:200px; text-align : center" value="'.$row['nama_supp'].'">'.$row['nama_supp'].'</td>
                            <td style="width:100px; text-align : center" value="'.$row['bankout_date'].'">'.date("d-M-Y",strtotime($row['bankout_date'])).'</td>                                                                                             
                            <td style="width:50px; text-align : center" value="'.$row['curr'].'">'.$row['curr'].'</td>
                            <td style="width:50px; text-align : center" value="'.$row['amount'].'">'.number_format($row['amount'],2).'</td>
                            <td style="width:50px; text-align : center; display: none;" value="'.$row['outstanding'].'">'.number_format($row['outstanding'],2).'</td>
                            <td style="width:50px; text-align : center" value="'.$row['status'].'">'.$row['status'].'</td>
                            <td style="width:50px; text-align : center" value="'.$app_date.'">'.$app_date.'</td>
                            <td style="display: none; text-align : center" value="'.$row['reff_doc'].'">'.$row['reff_doc'].'</td>
                            <td style="display: none; text-align : center" value="'.$row['akun'].'">'.$row['akun'].'</td> 
                            <td style="display: none; text-align : center" value="'.$row['bank'].'">'.$row['bank'].'</td>  
                            <td style="display: none; text-align : center" value="'.$row['deskripsi'].'">'.$row['deskripsi'].'</td>';
                            if ($row['status'] == 'Cancel') {
                                echo '<td style="text-align: center;">-</td>';
                            }else{
                                echo '<td style="width:50px;text-align : center" ><a href="pdf_bankout.php?no_bankout='.$row['no_bankout'].'" target="_blank"><button style="border-radius: 6px" type="button" class="btn-xs btn-success"><i class="fa fa-file-pdf-o" aria-hidden="true" style="padding-right: 10px; padding-left: 5px;"> Pdf</i></button></a></td>';
                            }                                                                                  
                        echo '</tr>';
                    
                    
                    } ?>
                    </tbody>
                    </table>  
                    </div> 
                    </div>                  

<div class="modal fade" id="mymodal" data-target="#mymodal" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-times"></span></button>
        <h4 class="modal-title" id="txt_bpb"></h4>
        </div>
        <div class="container">
        <div class="row">
          <div id="txt_tglbpb" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>
          <div id="txt_no_po" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>
          <div id="txt_supp" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>
          <div id="txt_top" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>         
          <div id="txt_curr" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>
          <div id="txt_confirm" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>
          <div id="txt_tgl_po" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>                     
          <div id="details" class="modal-body col-12" style="font-size: 12px; padding: 0.5rem;"></div>          
        </div>
        </div>
        </div>
    <!-- /.modal-content --> 
  </div>
      <!-- /.modal-dialog --> 
    </div>                            
                    
<div class="box footer">   
        <form id="form-simpan">
           <div class="form-row col">
            <div class="col-md-4 mb-2">
                <h4><i>Total</i></h4>
                <table >
            <?php
            $nama_supp ='';
            $start_date ='';
            $end_date =''; 
            $bank ='';
            $akun ='';
            $status ='';
            $date_now = date("Y-m-d");           
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_supp = isset($_POST['nama_supp']) ? $_POST['nama_supp']: null;
            $bank = isset($_POST['bank']) ? $_POST['bank']: null;
            $akun = isset($_POST['akun']) ? $_POST['akun']: null;
            $status = isset($_POST['status']) ? $_POST['status']: null;
            $start_date = date("Y-m-d",strtotime($_POST['start_date']));
            $end_date = date("Y-m-d",strtotime($_POST['end_date']));            
            }

            if($nama_supp == 'ALL' and $bank == 'ALL' and $akun == 'ALL' and $status == 'ALL' and empty($start_date) and empty($end_date)){
            $where = "where bankout_date = '$date_now'";
            }elseif($nama_supp == 'ALL' and $bank == 'ALL' and $akun == 'ALL' and $status == 'ALL' and !empty($start_date) and !empty($end_date)){
            $where = "where bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp != 'ALL' and $bank == 'ALL' and $akun == 'ALL' and $status == 'ALL'){
            $where = "where nama_supp = '$nama_supp' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp == 'ALL' and $bank != 'ALL' and $akun == 'ALL' and $status == 'ALL'){
            $where = "where bank = '$bank' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp == 'ALL' and $bank == 'ALL' and $akun != 'ALL' and $status == 'ALL'){
            $where = "where akun = '$akun' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp == 'ALL' and $bank == 'ALL' and $akun == 'ALL' and $status != 'ALL'){
            $where = "where status = '$status' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp != 'ALL' and $bank != 'ALL' and $akun == 'ALL' and $status == 'ALL'){
            $where = "where nama_supp = '$nama_supp' and bank = '$bank' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp != 'ALL' and $bank == 'ALL' and $akun != 'ALL' and $status == 'ALL'){
            $where = "where nama_supp = '$nama_supp' and akun = '$akun' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp != 'ALL' and $bank == 'ALL' and $akun == 'ALL' and $status != 'ALL'){
            $where = "where nama_supp = '$nama_supp' and status = '$status' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp == 'ALL' and $bank != 'ALL' and $akun != 'ALL' and $status == 'ALL'){
            $where = "where bank = '$bank' and akun = '$akun' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp == 'ALL' and $bank != 'ALL' and $akun == 'ALL' and $status != 'ALL'){
            $where = "where bank = '$bank' and status = '$status' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp == 'ALL' and $bank == 'ALL' and $akun != 'ALL' and $status != 'ALL'){
            $where = "where akun = '$akun' and status = '$status' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp != 'ALL' and $bank != 'ALL' and $akun != 'ALL' and $status == 'ALL'){
            $where = "where nama_supp = '$nama_supp' and bank = '$bank' and akun = '$akun' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp != 'ALL' and $bank != 'ALL' and $akun == 'ALL' and $status != 'ALL'){
            $where = "where nama_supp = '$nama_supp' and bank = '$bank' and status = '$status' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp != 'ALL' and $bank == 'ALL' and $akun != 'ALL' and $status != 'ALL'){
            $where = "where nama_supp = '$nama_supp' and akun = '$akun' and status = '$status' and bankout_date between '$start_date' and '$end_date'";
            }elseif($nama_supp == 'ALL' and $bank != 'ALL' and $akun != 'ALL' and $status != 'ALL'){
            $where = "where bank = '$bank' and akun = '$akun' and status = '$status' and bankout_date between '$start_date' and '$end_date'";
            }else{
              $where = "where nama_supp = '$nama_supp' and bank = '$bank' and akun = '$akun' and status = '$status' and bankout_date between '$start_date' and '$end_date'";  
            }

            $jml = 0;
            $akun = 0;
            $sql = mysqli_query($conn1,"select sum(amount) as nominal, sum(outstanding) as outstanding from sb_b_bankout_h $where");
                    
                    $row = mysqli_fetch_array($sql);                                                  
                    $jml += $row['nominal'];
                    $akun += $row['outstanding'];

                    
           echo'<tr>   

            <th style="text-align: left;vertical-align: middle;">Amount</th>
            <th style="text-align: left;vertical-align: middle;"> </th>
            <th style="text-align: center;vertical-align: middle;"><input style="border: 3px solid #555; text-align: center; border-radius: 8px;" value = "'.number_format($jml,2).'" readonly></th>                                                                                                                                                                       
                        </tr>';
                    
                    
                     ?>
        </table>
            </div>
            </div>                                   
        </form>
        
        </div>  
<!-- <tr>   

            <th style="text-align: left;vertical-align: middle;">Amount</th>
            <th style="text-align: left;vertical-align: middle;"> </th>
            <th style="text-align: center;vertical-align: middle;"><input style="border: 3px solid #555; text-align: center; border-radius: 8px;" value = "'.number_format($jml,2).'" readonly></th>                                                                                                                                                                       
                        </tr>
                        <tr>
            <th style="text-align: left;vertical-align: middle;">Outstanding</th>
            <th style="text-align: left;vertical-align: middle;"> </th>
            <th style="text-align: center;vertical-align: middle;"><input style="border: 3px solid #555; text-align: center; border-radius: 8px;" value = "'.number_format($akun,2).'" readonly></th>                                                                                                                                                                                                                                
                        </tr>    -->   
                                
</div><!-- body-row END -->
</div>
</div>

  <!-- Bootstrap core JavaScript -->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script language="JavaScript" src="../css/4.1.1/datatables.min.js"></script>
  <script language="JavaScript" src="../css/4.1.1/bootstrap-datepicker.js"></script>
  <script language="JavaScript" src="../css/4.1.1/bootstrap-select.min.js"></script>

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
    $('#mytable').dataTable({
      });
    
     $("[data-toggle=tooltip]").tooltip();
    
} );
</script>

<script>
function myFunction() {
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("mytable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
</script>

<script type="text/javascript">
    $(document).ready(function () {
    $('.tanggal').datepicker({
        format: "dd-mm-yyyy",
        startDate : "01-01-2021",
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
    $('table tbody tr').on('click', 'td:eq(0)', function(){                
    $('#mymodal').modal('show');
    var no_ob = $(this).closest('tr').find('td:eq(0)').attr('value');
    var supp = $(this).closest('tr').find('td:eq(1)').text();
    var refdoc = $(this).closest('tr').find('td:eq(8)').attr('value');
    var akun = $(this).closest('tr').find('td:eq(9)').attr('value');
    var bank = $(this).closest('tr').find('td:eq(10)').attr('value');
    var curr = $(this).closest('tr').find('td:eq(3)').attr('value');
    var status = $(this).closest('tr').find('td:eq(6)').attr('value');
    var desk = $(this).closest('tr').find('td:eq(11)').text();

    $.ajax({
    type : 'post',
    url : 'ajaxbankout.php',
    data : {'no_ob': no_ob, 'refdoc': refdoc},
    success : function(data){
    $('#details').html(data); //menampilkan data ke dalam modal
        }
    });         
        //make your ajax call populate items or what even you need
    $('#txt_bpb').html(no_ob);
    $('#txt_tglbpb').html('Supplier : ' + supp + '');
    $('#txt_no_po').html('Reff Doc : ' + refdoc + '');
    $('#txt_supp').html('Account : ' + akun + '');
    $('#txt_top').html('Bank : ' + bank + '');
    $('#txt_curr').html('Currency : ' + curr + '');        
    $('#txt_confirm').html('Status : ' + status + '');
    $('#txt_tgl_po').html('Description : ' + desk + '');                    
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
    $("input[type=checkbox]").change(function(){
    var sub = 0;
    var tax = 0;
    var total = 0;
    var ceklist = 0;         
    $("input[type=checkbox]:checked").each(function () {        
    var price = parseFloat($(this).closest('tr').find('td:eq(5)').attr('value'),10) || 0;
    var qty = parseFloat($(this).closest('tr').find('td:eq(7)').attr('value'),10) ||0;
    var tax = parseFloat($(this).closest('tr').find('td:eq(8)').attr('value'),10) ||0;               
    sub += price * qty;
    tax += tax;
    total = sub + tax;     
    });
    $("#subtotal").val(formatMoney(sub));
    $("#pajak").val(formatMoney(tax));
    $("#total").val(formatMoney(total));
    $("#select").val("1");                    
});        
</script>

<!--<script type="text/javascript">
$(document).ready(function(){
    $("#supp").on("change", function(){
        var supp= $('select[name=supp] option').filter(':selected').val();
        $.ajax({
            type:'POST',
            url:'cek.php',
            data: {'supp':supp},
            close: function(e){
                e.preventDefault();
            },
            success: function(html){
                console.log(html);
                $("#no_po").html(html);
            },
            error:  function (xhr, ajaxOptions, thrownError) {
                alert(xhr);
            }
        });            
        });
    });    
</script>-->

<script type="text/javascript">
    $("#form-simpan").on("click", "#simpan", function(){
        $("input[type=checkbox]:checked").each(function () {        
        var ceklist = document.getElementById('select').value;         
        var no_bpb = $(this).closest('tr').find('td:eq(1)').attr('value');
        var no_bpb_asal = $(this).closest('tr').find('td:eq(4)').attr('value');
        var create_user = '<?php echo $user ?>';
        var start_date = document.getElementById('start_date').value;
        var end_date = document.getElementById('end_date').value;
        var tgl_po = $(this).closest('tr').find('td:eq(9)').attr('value');
        var pterms = $(this).closest('tr').find('td:eq(10)').attr('value');        

        $.ajax({
            type:'POST',
            url:'insertfmvbpb.php',
            data: {'no_bpb':no_bpb, 'no_bpb_asal':no_bpb_asal, 'ceklist':ceklist, 'create_user':create_user, 'start_date':start_date, 'end_date':end_date, 'tgl_po':tgl_po, 'pterms':pterms},
            close: function(e){
                e.preventDefault();
            },
            success: function(response){                
                console.log(response);
                window.location = 'verifikasibpb.php';
                                               
            },
            error:  function (xhr, ajaxOptions, thrownError) {
               alert(xhr);
            }
        });
        });
        if(document.querySelectorAll("input[name='select[]']:checked").length >= 1){
            alert("Data saved successfully");
        }else{
            alert("Please check the BPB number");
        }        
    });
</script>

<script type="text/javascript">
    document.getElementById('btncreate').onclick = function () {
    location.href = "create-bankout.php";
};
</script>

<script type="text/javascript">
$("#select_all").click(function() {
  var c = this.checked;
  $(':checkbox').prop('checked', c);
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
