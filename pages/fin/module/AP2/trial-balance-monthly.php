<?php include '../header.php' ?>
<style >
    .modal {
  text-align: center;
  padding: 0!important;
}

.modal:before {
  content: '';
  display: inline-block;
  height: 100%;
  vertical-align: middle;
  margin-right: -4px;
}

.modal-dialog {
  display: inline-table;
  width: 700px;
  text-align: left;
  vertical-align: middle;
}

.table-responsive {
    display: table;
}
</style>
    <!-- MAIN -->
    <div class="col p-4">
        <h2 class="text-center">TRIAL BALANCE MONTHLY</h2>
<div class="box">
    <div class="box header">

        <form id="form-data" action="trial-balance-monthly.php" method="post">        
        <div class="form-row">

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
               echo date("M Y",strtotime('2024-01-01'));
            } ?>" 
            placeholder="Tanggal Awal">
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
               echo date("M Y");
            } ?>" 
            placeholder="Tanggal Awal" onchange="settanggalawl(this.value)"> 
            </div>
            <div class="input-group-append col">                                   
            <button  type="submit" id="submit" value=" Search " style="height: 35px; margin-top: 30px; margin-bottom: 5px;margin-right: 15px;border: 0;
    line-height: 1;
    padding: -2px 8px;
    font-size: 1rem;
    text-align: center;
    color: #fff;
    text-shadow: 1px 1px 1px #000;
    border-radius: 6px;
    background-color: rgb(46, 139, 87);"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
<!--             <button type="button" id="reset" value=" Reset " style="height: 35px; margin-top: 30px; margin-bottom: 5px;margin-right: 15px;border: 0;
    line-height: 1;
    padding: -2px 8px;
    font-size: 1rem;
    text-align: center;
    color: #fff;
    text-shadow: 1px 1px 1px #000;
    border-radius: 6px;
    background-color:rgb(250, 69, 1)"><i class="fa fa-repeat" aria-hidden="true"></i> Reset </button> -->

<?php
        // $status = isset($_POST['status']) ? $_POST['status']: null;
        $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
        $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;

        echo '<a style="padding-right: 10px;" target="_blank" href="ekspor_tb_monthly.php?start_date='.$start_date.' && end_date='.$end_date.'"><button type="button" class="btn btn-success " style= "margin-top: 30px;"><i class="fa fa-file-excel-o" aria-hidden="true" style="padding-right: 10px; padding-left: 5px;font-size: 1rem;color: #fff;text-shadow: 1px 1px 1px #000"> Excel</i></button></a>

        <a style="padding-right: 5px;" target="_blank" href="ekspor_sfp_monthly.php?start_date='.$start_date.' && end_date='.$end_date.'"><button type="button" class="btn btn-success " style= "margin-top: 30px;"><i class="fa fa-file-excel-o" aria-hidden="true" style="padding-right: 10px; padding-left: 5px;font-size: 14px;color: #fff;text-shadow: 1px 1px 1px #000"> Excel SFP</i></button></a>

        <a style="padding-right: 5px;" target="_blank" href="ekspor_spl_monthly.php?start_date='.$start_date.' && end_date='.$end_date.'"><button type="button" class="btn btn-success " style= "margin-top: 30px;"><i class="fa fa-file-excel-o" aria-hidden="true" style="padding-right: 10px; padding-left: 5px;font-size: 14px;color: #fff;text-shadow: 1px 1px 1px #000"> Excel SPL</i></button></a>

        <a style="padding-right: 5px;" target="_blank" href="ekspor_cf_direct_monthly.php?start_date='.$start_date.' && end_date='.$end_date.'"><button type="button" class="btn btn-success " style= "margin-top: 30px;"><i class="fa fa-file-excel-o" aria-hidden="true" style="padding-right: 10px; padding-left: 5px;font-size: 14px;color: #fff;text-shadow: 1px 1px 1px #000"> Excel CF Direct</i></button></a>


        <a style="padding-right: 5px;" target="_blank" href="ekspor_cf_indirect_monthly.php?start_date='.$start_date.' && end_date='.$end_date.'"><button type="button" class="btn btn-success " style= "margin-top: 30px;"><i class="fa fa-file-excel-o" aria-hidden="true" style="padding-right: 10px; padding-left: 5px;font-size: 14px;color: #fff;text-shadow: 1px 1px 1px #000"> Excel CF Indirect</i></button></a>

        ';

        // if($status == 'ALL'){
        //     echo '<a target="_blank" href="ekspor_lp_all.php?nama_supp='.$nama_supp.' && status='.$status.' && start_date='.$start_date.' && end_date='.$end_date.'"><button type="button" class="btn btn-success " style= "margin-top: 30px;"><i class="fa fa-file-excel-o" aria-hidden="true" style="padding-right: 10px; padding-left: 5px;font-size: 1rem;color: #fff;text-shadow: 1px 1px 1px #000"> Excel</i></button></a>';
        // }elseif($status == 'draft'){
        //     echo '<a target="_blank" href="ekspor_lp_draft.php?nama_supp='.$nama_supp.' && status='.$status.' && start_date='.$start_date.' && end_date='.$end_date.'"><button type="button" class="btn btn-success " style= "margin-top: 30px;"><i class="fa fa-file-excel-o" aria-hidden="true" style="padding-right: 10px; padding-left: 5px;font-size: 1rem;color: #fff;text-shadow: 1px 1px 1px #000"> Excel</i></button></a>'; 
        // }elseif($status == 'Approved'){
        //     echo '<a target="_blank" href="ekspor_lp_app.php?nama_supp='.$nama_supp.' && status='.$status.' && start_date='.$start_date.' && end_date='.$end_date.'"><button type="button" class="btn btn-success " style= "margin-top: 30px;"><i class="fa fa-file-excel-o" aria-hidden="true" style="padding-right: 10px; padding-left: 5px;font-size: 1rem;color: #fff;text-shadow: 1px 1px 1px #000"> Excel</i></button></a>';
        // }elseif($status == 'Cancel'){
        //     echo '<a target="_blank" href="ekspor_lp_cancel.php?nama_supp='.$nama_supp.' && status='.$status.' && start_date='.$start_date.' && end_date='.$end_date.'"><button type="button" class="btn btn-success " style= "margin-top: 30px;"><i class="fa fa-file-excel-o" aria-hidden="true" style="padding-right: 10px; padding-left: 5px;font-size: 1rem;color: #fff;text-shadow: 1px 1px 1px #000"> Excel</i></button></a>'; 
        // }elseif($status == 'Closed'){
        //     echo '<a target="_blank" href="ekspor_lp_closed.php?nama_supp='.$nama_supp.' && status='.$status.' && start_date='.$start_date.' && end_date='.$end_date.'"><button type="button" class="btn btn-success " style= "margin-top: 30px;"><i class="fa fa-file-excel-o" aria-hidden="true" style="padding-right: 10px; padding-left: 5px;font-size: 1rem;color: #fff;text-shadow: 1px 1px 1px #000"> Excel</i></button></a>'; 
        // }else{
        //     $filterr = ""; 
        // }
        ?>  

            </div>                                                            
    </div>
<br/>
</div>
</form> 

<!-- <?php
        $querys = mysqli_query($conn2,"select useraccess.menu as menu,useraccess.username as username, useraccess.fullname as fullname, menurole.id as id from useraccess inner join menurole on menurole.menu = useraccess.menu where username = '$user' and useraccess.menu = 'Create List payment'");
        $rs = mysqli_fetch_array($querys);
        $id = isset($rs['id']) ? $rs['id'] : 0;

        if($id == '9'){
    echo '<button id="btncreate" type="button" class="btn-primary btn-xs" style="border-radius: 6%"><span class="fa fa-pencil-square-o"></span> Create</button>
            <button id="btnupload" type="button" class="btn-success btn-xs" style="border-radius: 6%"><span class="fa fa-upload" aria-hidden="true"></span> Upload</button>';
        }else{
    echo '';
    }
?> -->
    <div class="box body">
        <div class="row">       
            <div class="col-md-12" style="height: 700px;">

<?php

$nama_type ='';
    $Status = '';
    $start_date ='';
    $end_date ='';
    $t_width = 0;
    $date_now = date("Y-m-d");   
    $bulan_awal = date("m",strtotime($date_now));
    $bulan_akhir = date("m",strtotime($date_now));  
    $tahun_awal = date("Y",strtotime($date_now));
    $tahun_akhir = date("Y",strtotime($date_now));             
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_type = isset($_POST['nama_type']) ? $_POST['nama_type']: null; 
    $Status = isset($_POST['Status']) ? $_POST['Status']: null; 
    $start_date = date("Y-m-d",strtotime($_POST['start_date']));
    $end_date = date("Y-m-d",strtotime($_POST['end_date']));
    $start_date_ = isset($start_date) ? $start_date : null;
    $end_date_ = isset($end_date) ? $end_date : null;   
    $bulan_awal = date("m",strtotime($start_date_));
    $bulan_akhir = date("m",strtotime($end_date_));  
    $tahun_awal = date("Y",strtotime($start_date_));
    $tahun_akhir = date("Y",strtotime($end_date_));
}

$sql_periode = mysqli_query($conn2,"select DATE_FORMAT(periode, '%b %Y') periode from (select tgl_awal periode from tbl_tgl_tb where tgl_awal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir')) a");

$sql_t_p = mysqli_query($conn1,"select COUNT(periode) jml_period from (select DATE_FORMAT(periode, '%b %Y') periode from (select tgl_awal periode from tbl_tgl_tb where tgl_awal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir')) a) a");
$row_t_p = mysqli_fetch_array($sql_t_p);
$ttl_periode = isset($row_t_p['jml_period']) ? $row_t_p['jml_period'] : 1;
$t_width = floor(100 / ($ttl_periode + 8)) . '%';
// echo $t_width;
?>

<div class="table-responsive">            
<table id="datatable" class="table table-striped table-bordered" role="grid" cellspacing="0" width="100%" >
    <thead>
        <tr class="thead-dark">
            <th style="text-align: center;vertical-align: middle;width: <?= $t_width; ?>;">No coa</th>
            <th style="text-align: center;vertical-align: middle;width: <?= $t_width; ?>;">COA Name</th>
            <th style="text-align: center;vertical-align: middle;width: <?= $t_width; ?>;">Category 1</th>
            <th style="text-align: center;vertical-align: middle;width: <?= $t_width; ?>;">Category 2</th>
            <th style="text-align: center;vertical-align: middle;width: <?= $t_width; ?>;">Category 3</th>
            <th style="text-align: center;vertical-align: middle;width: <?= $t_width; ?>;">Category 4</th>
            <th style="text-align: center;vertical-align: middle;width: <?= $t_width; ?>;">Des <?= $tahun_akhir -1 ?></th>
            <?php
             while($periode = mysqli_fetch_array($sql_periode)){
            echo '<th style="text-align: center;vertical-align: middle;width: '.$t_width.'">'.$periode['periode'].'</th>';
        };
            ?>
            <th style="text-align: center;vertical-align: middle;width: <?= $t_width; ?>;">YTD</th>                                                    
        </tr>
    </thead>
   
    <tbody>
    <?php
    $nama_type ='';
    $Status = '';
    $start_date ='';
    $end_date ='';
    $date_now = date("Y-m-d");   
    $bulan_awal = date("m",strtotime($date_now));
    $bulan_akhir = date("m",strtotime($date_now));  
    $tahun_awal = date("Y",strtotime($date_now));
    $tahun_akhir = date("Y",strtotime($date_now)); 
    $tanggal_awal = date("Y-m-d",strtotime($date_now));
    $tanggal_akhir = date("Y-m-d",strtotime($date_now));            
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_type = isset($_POST['nama_type']) ? $_POST['nama_type']: null; 
    $Status = isset($_POST['Status']) ? $_POST['Status']: null; 
    $start_date = date("Y-m-d",strtotime($_POST['start_date']));
    $end_date = date("Y-m-d",strtotime($_POST['end_date']));
    $start_date_ = isset($start_date) ? $start_date : null;
    $end_date_ = isset($end_date) ? $end_date : null;   
    $bulan_awal = date("m",strtotime($start_date_));
    $bulan_akhir = date("m",strtotime($end_date_));  
    $tahun_awal = date("Y",strtotime($start_date_));
    $tahun_akhir = date("Y",strtotime($end_date_));

    $tanggal_awal = date("Y-m-d",strtotime($_POST['start_date']));
    $tanggal_akhir = date("Y-m-d",strtotime($_POST['end_date']));
    // echo  $start_date;
    // echo  $end_date;
    // echo  $tahun_awal;
    // echo  $tahun_akhir;            
    }
    
    $sql = mysqli_query($conn2,"select no_coa,nama_coa,indname1,indname2,indname3,indname4,saldo_awal,saldo_jan,saldo_feb,saldo_mar,saldo_apr,saldo_may,saldo_jun,saldo_jul,saldo_aug,saldo_sep,saldo_oct,saldo_nov,saldo_dec from 
(select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori4,id_ctg5 from mastercoa_v2 order by no_coa asc) coa
left join
(select a.id_ctg5 as id_ctg5A,a.ind_name as indname5,a.eng_name as engname5, b.ind_name as indname4,b.eng_name as engname4, c.ind_name as indname3,c.eng_name as engname3, d.ind_name as indname2,d.eng_name as engname2, e.ind_name as indname1,e.eng_name as engname1 from master_coa_ctg5 a INNER JOIN master_coa_ctg4 b on b.id_ctg4 = a.id_ctg4 INNER JOIN master_coa_ctg3 c on c.id_ctg3 = a.id_ctg3 INNER JOIN master_coa_ctg2 d on d.id_ctg2 = a.id_ctg2 INNER JOIN master_coa_ctg1 e on e.id_ctg1 = a.id_ctg1 GROUP BY a.id_ctg5) a on a.id_ctg5A =coa.id_ctg5
LEFT JOIN
(select nocoa coa_no, saldo saldo_awal,(saldo + coalesce((debit_idr - credit_idr),0)) saldo_jan from 
(select no_coa nocoa,jan_2023 as saldo from saldo_awal_tb order by no_coa asc) saldo
left join
(select no_coa coa_no,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr from tbl_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '01' and tahun = '2023') and (select tgl_akhir from tbl_tgl_tb where bulan = '01' and tahun = '2023') group by no_coa) 
jnl on jnl.coa_no = saldo.nocoa order by nocoa asc) jan on jan.coa_no = coa.no_coa left join

(select nocoa coa_no, (saldo + coalesce((debit_idr - credit_idr),0)) saldo_feb from 
(select no_coa nocoa,feb_2023 as saldo from saldo_awal_tb order by no_coa asc) saldo
left join
(select no_coa coa_no,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr from tbl_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '02' and tahun = '2023') and (select tgl_akhir from tbl_tgl_tb where bulan = '02' and tahun = '2023') group by no_coa) 
jnl on jnl.coa_no = saldo.nocoa order by nocoa asc) feb on feb.coa_no = coa.no_coa left join

(select nocoa coa_no, (saldo + coalesce((debit_idr - credit_idr),0)) saldo_mar from 
(select no_coa nocoa,mar_2023 as saldo from saldo_awal_tb order by no_coa asc) saldo
left join
(select no_coa coa_no,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr from tbl_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '03' and tahun = '2023') and (select tgl_akhir from tbl_tgl_tb where bulan = '03' and tahun = '2023') group by no_coa) 
jnl on jnl.coa_no = saldo.nocoa order by nocoa asc) mar on mar.coa_no = coa.no_coa left join

(select nocoa coa_no, (saldo + coalesce((debit_idr - credit_idr),0)) saldo_apr from 
(select no_coa nocoa,apr_2023 as saldo from saldo_awal_tb order by no_coa asc) saldo
left join
(select no_coa coa_no,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr from tbl_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '04' and tahun = '2023') and (select tgl_akhir from tbl_tgl_tb where bulan = '04' and tahun = '2023') group by no_coa) 
jnl on jnl.coa_no = saldo.nocoa order by nocoa asc) apr on apr.coa_no = coa.no_coa left join

(select nocoa coa_no, (saldo + coalesce((debit_idr - credit_idr),0)) saldo_may from 
(select no_coa nocoa,may_2023 as saldo from saldo_awal_tb order by no_coa asc) saldo
left join
(select no_coa coa_no,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr from tbl_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '05' and tahun = '2023') and (select tgl_akhir from tbl_tgl_tb where bulan = '05' and tahun = '2023') group by no_coa) 
jnl on jnl.coa_no = saldo.nocoa order by nocoa asc) may on may.coa_no = coa.no_coa left join

(select nocoa coa_no, (saldo + coalesce((debit_idr - credit_idr),0)) saldo_jun from 
(select no_coa nocoa,jun_2023 as saldo from saldo_awal_tb order by no_coa asc) saldo
left join
(select no_coa coa_no,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr from tbl_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '06' and tahun = '2023') and (select tgl_akhir from tbl_tgl_tb where bulan = '06' and tahun = '2023') group by no_coa) 
jnl on jnl.coa_no = saldo.nocoa order by nocoa asc) jun on jun.coa_no = coa.no_coa left join

(select nocoa coa_no, (saldo + coalesce((debit_idr - credit_idr),0)) saldo_jul from 
(select no_coa nocoa,jul_2023 as saldo from saldo_awal_tb order by no_coa asc) saldo
left join
(select no_coa coa_no,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr from tbl_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '07' and tahun = '2023') and (select tgl_akhir from tbl_tgl_tb where bulan = '07' and tahun = '2023') group by no_coa) 
jnl on jnl.coa_no = saldo.nocoa order by nocoa asc) jul on jul.coa_no = coa.no_coa left join

(select nocoa coa_no, (saldo + coalesce((debit_idr - credit_idr),0)) saldo_aug from 
(select no_coa nocoa,aug_2023 as saldo from saldo_awal_tb order by no_coa asc) saldo
left join
(select no_coa coa_no,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr from tbl_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '08' and tahun = '2023') and (select tgl_akhir from tbl_tgl_tb where bulan = '08' and tahun = '2023') group by no_coa) 
jnl on jnl.coa_no = saldo.nocoa order by nocoa asc) aug on aug.coa_no = coa.no_coa left join

(select nocoa coa_no, (saldo + coalesce((debit_idr - credit_idr),0)) saldo_sep from 
(select no_coa nocoa,sep_2023 as saldo from saldo_awal_tb order by no_coa asc) saldo
left join
(select no_coa coa_no,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr from tbl_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '09' and tahun = '2023') and (select tgl_akhir from tbl_tgl_tb where bulan = '09' and tahun = '2023') group by no_coa) 
jnl on jnl.coa_no = saldo.nocoa order by nocoa asc) sep on sep.coa_no = coa.no_coa left join

(select nocoa coa_no, (saldo + coalesce((debit_idr - credit_idr),0)) saldo_oct from 
(select no_coa nocoa,oct_2023 as saldo from saldo_awal_tb order by no_coa asc) saldo
left join
(select no_coa coa_no,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr from tbl_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '10' and tahun = '2023') and (select tgl_akhir from tbl_tgl_tb where bulan = '10' and tahun = '2023') group by no_coa) 
jnl on jnl.coa_no = saldo.nocoa order by nocoa asc) oct on oct.coa_no = coa.no_coa left join

(select nocoa coa_no, (saldo + coalesce((debit_idr - credit_idr),0)) saldo_nov from 
(select no_coa nocoa,nov_2023 as saldo from saldo_awal_tb order by no_coa asc) saldo
left join
(select no_coa coa_no,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr from tbl_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '11' and tahun = '2023') and (select tgl_akhir from tbl_tgl_tb where bulan = '11' and tahun = '2023') group by no_coa) 
jnl on jnl.coa_no = saldo.nocoa order by nocoa asc) nov on nov.coa_no = coa.no_coa left join

(select nocoa coa_no, (saldo + coalesce((debit_idr - credit_idr),0)) saldo_dec from 
(select no_coa nocoa,dec_2023 as saldo from saldo_awal_tb order by no_coa asc) saldo
left join
(select no_coa coa_no,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr from tbl_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '12' and tahun = '2023') and (select tgl_akhir from tbl_tgl_tb where bulan = '12' and tahun = '2023') group by no_coa) 
jnl on jnl.coa_no = saldo.nocoa order by nocoa asc) des on des.coa_no = coa.no_coa 
order by no_coa asc");


    $sql_jmlper = mysqli_query($conn2,"select COUNT(periode) jml_periode from (select tgl_awal periode from tbl_tgl_tb where tgl_awal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir')) a");

    $rowjmlper = mysqli_fetch_array($sql_jmlper);
    $jmlper = isset($rowjmlper['jml_periode']) ? $rowjmlper['jml_periode'] : 0;

        $saldoakhir = 0;
        if($tanggal_akhir < $tanggal_awal){
        $message = "Mohon Masukan Tanggal Filter Yang Benar";
    echo "<script type='text/javascript'>alert('$message');</script>";
    }
        else{
    while($row = mysqli_fetch_array($sql)){
        $coa_number = $row['no_coa'];
        $saldo_awal = isset($row['saldo_awal']) ? $row['saldo_awal'] : 0;
        $saldo_jan = isset($row['saldo_jan']) ? $row['saldo_jan'] : 0;
        $saldo_feb = isset($row['saldo_feb']) ? $row['saldo_feb'] : 0;
        $saldo_mar = isset($row['saldo_mar']) ? $row['saldo_mar'] : 0;
        $saldo_apr = isset($row['saldo_apr']) ? $row['saldo_apr'] : 0;
        $saldo_may = isset($row['saldo_may']) ? $row['saldo_may'] : 0;
        $saldo_jun = isset($row['saldo_jun']) ? $row['saldo_jun'] : 0;
        $saldo_jul = isset($row['saldo_jul']) ? $row['saldo_jul'] : 0;
        $saldo_aug = isset($row['saldo_aug']) ? $row['saldo_aug'] : 0;
        $saldo_sep = isset($row['saldo_sep']) ? $row['saldo_sep'] : 0;
        $saldo_oct = isset($row['saldo_oct']) ? $row['saldo_oct'] : 0;
        $saldo_nov = isset($row['saldo_nov']) ? $row['saldo_nov'] : 0;
        $saldo_dec = isset($row['saldo_dec']) ? $row['saldo_dec'] : 0;

        $saldo_jan_ = isset($row['saldo_jan']) ? $row['saldo_jan'] : 0;
        $saldo_feb_ = isset($row['saldo_feb']) ? $row['saldo_feb'] : $saldo_jan;
        $saldo_mar_ = isset($row['saldo_mar']) ? $row['saldo_mar'] : $saldo_feb;
        $saldo_apr_ = isset($row['saldo_apr']) ? $row['saldo_apr'] : $saldo_mar;
        $saldo_may_ = isset($row['saldo_may']) ? $row['saldo_may'] : $saldo_apr;
        $saldo_jun_ = isset($row['saldo_jun']) ? $row['saldo_jun'] : $saldo_may;
        $saldo_jul_ = isset($row['saldo_jul']) ? $row['saldo_jul'] : $saldo_jun;
        $saldo_aug_ = isset($row['saldo_aug']) ? $row['saldo_aug'] : $saldo_jul;
        $saldo_sep_ = isset($row['saldo_sep']) ? $row['saldo_sep'] : $saldo_aug;
        $saldo_oct_ = isset($row['saldo_oct']) ? $row['saldo_oct'] : $saldo_sep;
        $saldo_nov_ = isset($row['saldo_nov']) ? $row['saldo_nov'] : $saldo_oct;
        $saldo_dec_ = isset($row['saldo_dec']) ? $row['saldo_dec'] : $saldo_nov;

        // $saldoakhir = ($beg_balance + $debit_idr) - $credit_idr;
        // $balance_idr = isset($row['saldo_nov']) ? $row['saldo_dec'] : null;

        // if ($balance_idr == 'NB') {
        //    $warna = '#FF7F50';
        // }else{
        //      $warna = 'grey';
        // }
        // if ($reff_date == '0000-00-00' || $reff_date == '1970-01-01' || $reff_date == '') {
        //     $Reffdate = '-'; 
        // }else{
        //     $Reffdate = date("d-M-Y",strtotime($reff_date));
        // }
        //background-color:'.$warna.';
                   
        echo '<tr style="font-size:12px;text-align:center;">
            <td style="text-align : center;" value = "'.$row['no_coa'].'">'.$row['no_coa'].'</td>
            <td style="text-align : left;" value = "'.$row['nama_coa'].'">'.$row['nama_coa'].'</td>
            <td style="text-align : left;" value = "'.$row['indname1'].'">'.$row['indname1'].'</td>
            <td style="text-align : left;" value = "'.$row['indname2'].'">'.$row['indname2'].'</td>
            <td style="text-align : left;" value = "'.$row['indname3'].'">'.$row['indname3'].'</td>
            <td style="text-align : left;" value = "'.$row['indname4'].'">'.$row['indname4'].'</td>
            <td style=" text-align : right;" value="'.$saldo_awal.'">'.number_format($saldo_awal,2).'</td>
            ';
            if($coa_number >= 4){

                if ($jmlper == '1') {
                echo '
                <td style=" text-align : right;" value="'.$saldo_jan.'">'.number_format($saldo_jan,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_jan.'">'.number_format($saldo_jan,2).'</td>
                ';
            }elseif ($jmlper == '2') {
                $jumlah_ytd = $saldo_jan + $saldo_feb;
                echo '
                <td style=" text-align : right;" value="'.$saldo_jan.'">'.number_format($saldo_jan,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_feb.'">'.number_format($saldo_feb,2).'</td>
                <td style=" text-align : right;" value="'.$jumlah_ytd.'">'.number_format($jumlah_ytd,2).'</td>
                ';
            }elseif ($jmlper == '3') {
                $jumlah_ytd = $saldo_jan + $saldo_feb + $saldo_mar; 
                echo '
                <td style=" text-align : right;" value="'.$saldo_jan.'">'.number_format($saldo_jan,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_feb.'">'.number_format($saldo_feb,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_mar.'">'.number_format($saldo_mar,2).'</td>
                <td style=" text-align : right;" value="'.$jumlah_ytd.'">'.number_format($jumlah_ytd,2).'</td>
                ';
            }elseif ($jmlper == '4') {
                $jumlah_ytd = $saldo_jan + $saldo_feb + $saldo_mar + $saldo_apr; 
                echo '
                <td style=" text-align : right;" value="'.$saldo_jan.'">'.number_format($saldo_jan,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_feb.'">'.number_format($saldo_feb,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_mar.'">'.number_format($saldo_mar,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_apr.'">'.number_format($saldo_apr,2).'</td>
                <td style=" text-align : right;" value="'.$jumlah_ytd.'">'.number_format($jumlah_ytd,2).'</td>
                ';
            }elseif ($jmlper == '5') {
                $jumlah_ytd = $saldo_jan + $saldo_feb + $saldo_mar + $saldo_apr + $saldo_may; 
                echo '
                <td style=" text-align : right;" value="'.$saldo_jan.'">'.number_format($saldo_jan,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_feb.'">'.number_format($saldo_feb,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_mar.'">'.number_format($saldo_mar,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_apr.'">'.number_format($saldo_apr,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_may.'">'.number_format($saldo_may,2).'</td>
                <td style=" text-align : right;" value="'.$jumlah_ytd.'">'.number_format($jumlah_ytd,2).'</td>
                ';
            }elseif ($jmlper == '6') {
                $jumlah_ytd = $saldo_jan + $saldo_feb + $saldo_mar + $saldo_apr + $saldo_may + $saldo_jun;
                echo '
                <td style=" text-align : right;" value="'.$saldo_jan.'">'.number_format($saldo_jan,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_feb.'">'.number_format($saldo_feb,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_mar.'">'.number_format($saldo_mar,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_apr.'">'.number_format($saldo_apr,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_may.'">'.number_format($saldo_may,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_jun.'">'.number_format($saldo_jun,2).'</td>
                <td style=" text-align : right;" value="'.$jumlah_ytd.'">'.number_format($jumlah_ytd,2).'</td>
                ';
            }elseif ($jmlper == '7') {
                $jumlah_ytd = $saldo_jan + $saldo_feb + $saldo_mar + $saldo_apr + $saldo_may + $saldo_jun + $saldo_jul;
                echo '
                <td style=" text-align : right;" value="'.$saldo_jan.'">'.number_format($saldo_jan,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_feb.'">'.number_format($saldo_feb,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_mar.'">'.number_format($saldo_mar,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_apr.'">'.number_format($saldo_apr,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_may.'">'.number_format($saldo_may,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_jun.'">'.number_format($saldo_jun,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_jul.'">'.number_format($saldo_jul,2).'</td>
                <td style=" text-align : right;" value="'.$jumlah_ytd.'">'.number_format($jumlah_ytd,2).'</td>
                ';
            }elseif ($jmlper == '8') {
                $jumlah_ytd = $saldo_jan + $saldo_feb + $saldo_mar + $saldo_apr + $saldo_may + $saldo_jun + $saldo_jul + $saldo_aug;
                echo '
                <td style=" text-align : right;" value="'.$saldo_jan.'">'.number_format($saldo_jan,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_feb.'">'.number_format($saldo_feb,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_mar.'">'.number_format($saldo_mar,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_apr.'">'.number_format($saldo_apr,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_may.'">'.number_format($saldo_may,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_jun.'">'.number_format($saldo_jun,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_jul.'">'.number_format($saldo_jul,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_aug.'">'.number_format($saldo_aug,2).'</td>
                <td style=" text-align : right;" value="'.$jumlah_ytd.'">'.number_format($jumlah_ytd,2).'</td>
                ';
            }elseif ($jmlper == '9') {
                $jumlah_ytd = $saldo_jan + $saldo_feb + $saldo_mar + $saldo_apr + $saldo_may + $saldo_jun + $saldo_jul + $saldo_aug + $saldo_sep;
                echo '
                <td style=" text-align : right;" value="'.$saldo_jan.'">'.number_format($saldo_jan,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_feb.'">'.number_format($saldo_feb,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_mar.'">'.number_format($saldo_mar,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_apr.'">'.number_format($saldo_apr,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_may.'">'.number_format($saldo_may,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_jun.'">'.number_format($saldo_jun,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_jul.'">'.number_format($saldo_jul,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_aug.'">'.number_format($saldo_aug,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_sep.'">'.number_format($saldo_sep,2).'</td>
                <td style=" text-align : right;" value="'.$jumlah_ytd.'">'.number_format($jumlah_ytd,2).'</td>
                ';
            }elseif ($jmlper == '10') {
                $jumlah_ytd = $saldo_jan + $saldo_feb + $saldo_mar + $saldo_apr + $saldo_may + $saldo_jun + $saldo_jul + $saldo_aug + $saldo_sep + $saldo_oct;
                echo '
                <td style=" text-align : right;" value="'.$saldo_jan.'">'.number_format($saldo_jan,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_feb.'">'.number_format($saldo_feb,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_mar.'">'.number_format($saldo_mar,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_apr.'">'.number_format($saldo_apr,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_may.'">'.number_format($saldo_may,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_jun.'">'.number_format($saldo_jun,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_jul.'">'.number_format($saldo_jul,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_aug.'">'.number_format($saldo_aug,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_sep.'">'.number_format($saldo_sep,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_oct.'">'.number_format($saldo_oct,2).'</td>
                <td style=" text-align : right;" value="'.$jumlah_ytd.'">'.number_format($jumlah_ytd,2).'</td>
                ';
            }elseif ($jmlper == '11') {
                $jumlah_ytd = $saldo_jan + $saldo_feb + $saldo_mar + $saldo_apr + $saldo_may + $saldo_jun + $saldo_jul + $saldo_aug + $saldo_sep + $saldo_oct + $saldo_nov;
                echo '
                <td style=" text-align : right;" value="'.$saldo_jan.'">'.number_format($saldo_jan,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_feb.'">'.number_format($saldo_feb,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_mar.'">'.number_format($saldo_mar,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_apr.'">'.number_format($saldo_apr,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_may.'">'.number_format($saldo_may,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_jun.'">'.number_format($saldo_jun,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_jul.'">'.number_format($saldo_jul,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_aug.'">'.number_format($saldo_aug,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_sep.'">'.number_format($saldo_sep,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_oct.'">'.number_format($saldo_oct,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_nov.'">'.number_format($saldo_nov,2).'</td>
                <td style=" text-align : right;" value="'.$jumlah_ytd.'">'.number_format($jumlah_ytd,2).'</td>
                ';
            }elseif ($jmlper == '12') {
                $jumlah_ytd = $saldo_jan + $saldo_feb + $saldo_mar + $saldo_apr + $saldo_may + $saldo_jun + $saldo_jul + $saldo_aug + $saldo_sep + $saldo_oct + $saldo_nov + $saldo_dec;
                echo '
                <td style=" text-align : right;" value="'.$saldo_jan.'">'.number_format($saldo_jan,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_feb.'">'.number_format($saldo_feb,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_mar.'">'.number_format($saldo_mar,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_apr.'">'.number_format($saldo_apr,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_may.'">'.number_format($saldo_may,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_jun.'">'.number_format($saldo_jun,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_jul.'">'.number_format($saldo_jul,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_aug.'">'.number_format($saldo_aug,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_sep.'">'.number_format($saldo_sep,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_oct.'">'.number_format($saldo_oct,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_nov.'">'.number_format($saldo_nov,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_dec.'">'.number_format($saldo_dec,2).'</td>
                <td style=" text-align : right;" value="'.$jumlah_ytd.'">'.number_format($jumlah_ytd,2).'</td>
                ';
            }else{

            }

            }else{
            if ($jmlper == '1') {
                echo '
                <td style=" text-align : right;" value="'.$saldo_jan.'">'.number_format($saldo_jan,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_jan.'">'.number_format($saldo_jan,2).'</td>
                ';
            }elseif ($jmlper == '2') {
                echo '
                <td style=" text-align : right;" value="'.$saldo_jan.'">'.number_format($saldo_jan,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_feb.'">'.number_format($saldo_feb,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_feb_.'">'.number_format($saldo_feb_,2).'</td>
                ';
            }elseif ($jmlper == '3') {
                echo '
                <td style=" text-align : right;" value="'.$saldo_jan.'">'.number_format($saldo_jan,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_feb.'">'.number_format($saldo_feb,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_mar.'">'.number_format($saldo_mar,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_mar_.'">'.number_format($saldo_mar_,2).'</td>
                ';
            }elseif ($jmlper == '4') {
                echo '
                <td style=" text-align : right;" value="'.$saldo_jan.'">'.number_format($saldo_jan,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_feb.'">'.number_format($saldo_feb,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_mar.'">'.number_format($saldo_mar,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_apr.'">'.number_format($saldo_apr,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_apr_.'">'.number_format($saldo_apr_,2).'</td>
                ';
            }elseif ($jmlper == '5') {
                echo '
                <td style=" text-align : right;" value="'.$saldo_jan.'">'.number_format($saldo_jan,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_feb.'">'.number_format($saldo_feb,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_mar.'">'.number_format($saldo_mar,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_apr.'">'.number_format($saldo_apr,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_may.'">'.number_format($saldo_may,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_may_.'">'.number_format($saldo_may_,2).'</td>
                ';
            }elseif ($jmlper == '6') {
                echo '
                <td style=" text-align : right;" value="'.$saldo_jan.'">'.number_format($saldo_jan,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_feb.'">'.number_format($saldo_feb,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_mar.'">'.number_format($saldo_mar,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_apr.'">'.number_format($saldo_apr,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_may.'">'.number_format($saldo_may,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_jun.'">'.number_format($saldo_jun,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_jun_.'">'.number_format($saldo_jun_,2).'</td>
                ';
            }elseif ($jmlper == '7') {
                echo '
                <td style=" text-align : right;" value="'.$saldo_jan.'">'.number_format($saldo_jan,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_feb.'">'.number_format($saldo_feb,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_mar.'">'.number_format($saldo_mar,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_apr.'">'.number_format($saldo_apr,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_may.'">'.number_format($saldo_may,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_jun.'">'.number_format($saldo_jun,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_jul.'">'.number_format($saldo_jul,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_jul_.'">'.number_format($saldo_jul_,2).'</td>
                ';
            }elseif ($jmlper == '8') {
                echo '
                <td style=" text-align : right;" value="'.$saldo_jan.'">'.number_format($saldo_jan,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_feb.'">'.number_format($saldo_feb,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_mar.'">'.number_format($saldo_mar,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_apr.'">'.number_format($saldo_apr,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_may.'">'.number_format($saldo_may,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_jun.'">'.number_format($saldo_jun,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_jul.'">'.number_format($saldo_jul,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_aug.'">'.number_format($saldo_aug,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_aug_.'">'.number_format($saldo_aug_,2).'</td>
                ';
            }elseif ($jmlper == '9') {
                echo '
                <td style=" text-align : right;" value="'.$saldo_jan.'">'.number_format($saldo_jan,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_feb.'">'.number_format($saldo_feb,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_mar.'">'.number_format($saldo_mar,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_apr.'">'.number_format($saldo_apr,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_may.'">'.number_format($saldo_may,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_jun.'">'.number_format($saldo_jun,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_jul.'">'.number_format($saldo_jul,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_aug.'">'.number_format($saldo_aug,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_sep.'">'.number_format($saldo_sep,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_sep_.'">'.number_format($saldo_sep_,2).'</td>
                ';
            }elseif ($jmlper == '10') {
                echo '
                <td style=" text-align : right;" value="'.$saldo_jan.'">'.number_format($saldo_jan,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_feb.'">'.number_format($saldo_feb,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_mar.'">'.number_format($saldo_mar,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_apr.'">'.number_format($saldo_apr,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_may.'">'.number_format($saldo_may,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_jun.'">'.number_format($saldo_jun,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_jul.'">'.number_format($saldo_jul,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_aug.'">'.number_format($saldo_aug,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_sep.'">'.number_format($saldo_sep,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_oct.'">'.number_format($saldo_oct,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_oct_.'">'.number_format($saldo_oct_,2).'</td>
                ';
            }elseif ($jmlper == '11') {
                echo '
                <td style=" text-align : right;" value="'.$saldo_jan.'">'.number_format($saldo_jan,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_feb.'">'.number_format($saldo_feb,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_mar.'">'.number_format($saldo_mar,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_apr.'">'.number_format($saldo_apr,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_may.'">'.number_format($saldo_may,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_jun.'">'.number_format($saldo_jun,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_jul.'">'.number_format($saldo_jul,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_aug.'">'.number_format($saldo_aug,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_sep.'">'.number_format($saldo_sep,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_oct.'">'.number_format($saldo_oct,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_nov.'">'.number_format($saldo_nov,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_nov_.'">'.number_format($saldo_nov_,2).'</td>
                ';
            }elseif ($jmlper == '12') {
                echo '
                <td style=" text-align : right;" value="'.$saldo_jan.'">'.number_format($saldo_jan,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_feb.'">'.number_format($saldo_feb,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_mar.'">'.number_format($saldo_mar,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_apr.'">'.number_format($saldo_apr,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_may.'">'.number_format($saldo_may,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_jun.'">'.number_format($saldo_jun,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_jul.'">'.number_format($saldo_jul,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_aug.'">'.number_format($saldo_aug,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_sep.'">'.number_format($saldo_sep,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_oct.'">'.number_format($saldo_oct,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_nov.'">'.number_format($saldo_nov,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_dec.'">'.number_format($saldo_dec,2).'</td>
                <td style=" text-align : right;" value="'.$saldo_dec_.'">'.number_format($saldo_dec_,2).'</td>
                ';
            }else{

            }
        }
            echo '</tr>';
}
}?>
</tbody>                    
</table>
</div>
</div>
   
    </div>
    </div>
</div>
</div><!-- body-row END -->
</div>
</div>

<div class="form-row">
    <div class="modal fade" id="mymodal2" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div style="width:450px;" class="modal-dialog modal-md">
        <div style="height: 225px" class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-times"></span></button>
        <h4 class="modal-title" id="Heading" style="text-align: center;"><b>UPLOAD</b></h4>
        </div>
          <div class="modal-body">
          <div class="form-group">
            <form method="post" enctype="multipart/form-data" action="proses_upload.php">
                                    Pilih File:
                                    <input class="form-control" name="fileexcel" type="file" required="required">
                                    <br>
                                    <button class="btn btn-sm btn-info" type="submit">Submit</button>
                                    <a target="_blank" href="format_upload_mj.xls"><button type="button" class="btn btn-warning "><i class="fa fa-file-excel-o" aria-hidden="true"> Format Upload</i></button></a>
                                </form>
        </div>
      </div>
    </div>
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
<!--           <div id="txt_top" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>         
          <div id="txt_curr" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div> -->
          <div id="txt_confirm" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>
  <!--         <div id="txt_tgl_po" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>  -->                    
          <div id="details" class="modal-body col-12" style="font-size: 12px; padding: 0.5rem;"></div>          
        </div>
        </div>
        </div>
    <!-- /.modal-content 
  </div>
      /.modal-dialog 
    </div> -->         
                                
</div><!-- body-row END -->
</div>
</div>

  <!-- Bootstrap core JavaScript -->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script language="JavaScript" src="../css/4.1.1/datatables.min.js"></script>
    <script language="JavaScript" src="../css/4.1.1/bootstrap-datepicker.js"></script>
  <script language="JavaScript" src="../css/4.1.1/bootstrap-select.min.js"></script>
  <script language="JavaScript" src="../css/4.1.1/dataTables.fixedColumns.min.js"></script>
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
    $('#datatable').dataTable();
    
     $("[data-toggle=tooltip]").tooltip();
    
} );
</script>

<script>
    function settanggalawl(val){
        // alert(val);
    }
</script>

<script type="text/javascript">
    $(document).ready(function () {
    $('.tanggal').datepicker({
        format: "M yyyy",
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
    $("table tbody tr").on("click", "#active", function(){                 
        var doc_number = $(this).closest('tr').find('td:eq(0)').attr('value');
        var active_user = '<?php echo $user ?>';

        $.ajax({
            type:'POST',
            url:'activebank.php',
            data: {'doc_number':doc_number, 'active_user':active_user},
            close: function(e){
                e.preventDefault();
            },
            success: function(data){                
                // console.log(data);
                window.location.reload();
                // alert("Active");                                              
            },
            error:  function (xhr, ajaxOptions, thrownError) {
               alert(xhr);
            }
        });
        });
</script>

<script type="text/javascript">
    $("table tbody tr").on("click", "#deactive", function(){                 
        var doc_number = $(this).closest('tr').find('td:eq(0)').attr('value');
        var active_user = '<?php echo $user ?>';

        $.ajax({
            type:'POST',
            url:'deactivebank.php',
            data: {'doc_number':doc_number, 'active_user':active_user},
            close: function(e){
                e.preventDefault();
            },
            success: function(data){                
                // console.log(data);
                window.location.reload();
                // alert("Deactive");                                              
            },
            error:  function (xhr, ajaxOptions, thrownError) {
               alert(xhr);
            }
        });
        });
</script>


<script type="text/javascript">     
    $('table tbody tr').on('click', 'td:eq(0)', function(){                
    $('#mymodal').modal('show');
    var no_ib = $(this).closest('tr').find('td:eq(0)').attr('value');
    var date = $(this).closest('tr').find('td:eq(1)').text();
    var reff = $(this).closest('tr').find('td:eq(2)').attr('value');
    var reff_doc = $(this).closest('tr').find('td:eq(3)').attr('value');
    var oth_doc = $(this).closest('tr').find('td:eq(4)').attr('value');
    var curr = "IDR";

    $.ajax({
    type : 'post',
    url : 'ajax_cashin.php',
    data : {'no_ib': no_ib},
    success : function(data){
    $('#details').html(data); //menampilkan data ke dalam modal
        }
    });         
        //make your ajax call populate items or what even you need
    $('#txt_bpb').html(no_ib);
    $('#txt_tglbpb').html('Date : ' + date + '');
    $('#txt_no_po').html('Refference : ' + reff + '');
    $('#txt_supp').html('Refference Document : ' + reff_doc + '');
    // $('#txt_top').html('Other Document : ' + oth_doc + '');
    // $('#txt_curr').html('Kas Account : ' + akun + '');        
    $('#txt_confirm').html('Currency : ' + curr + '');
    // $('#txt_tgl_po').html('Description : ' + desk + '');                    
});

</script>

<script type="text/javascript">
    document.getElementById('btncreate').onclick = function () {
    location.href = "create-list-journal.php";
};
</script>

<script type="text/javascript">
    document.getElementById('btnupload').onclick = function () {
    location.href = "upload-list-journal.php";
};
</script>

<script type="text/javascript">
    document.getElementById('reset').onclick = function () {
    location.href = "list-journal.php";
};
</script>

<!-- <script type="text/javascript">     
    document.getElementById('btnupload').onclick = function (){ 
    // var txt_type = $(this).closest('tr').find('td:eq(4)').attr('value'); 
    // var txt_id = $(this).closest('tr').find('td:eq(0)').attr('value');           
    $('#mymodal2').modal('show');
    // $('#txt_type').val(txt_type);
    // $('#txt_id').val(txt_id);

};

</script> -->

<script>
function alert_cancel() {
  alert("Master Bank Deactive");
  location.reload();
}
function alert_approve() {
  alert("Master Bank Active");
  location.reload();
}
</script>

<!--<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>-->
  
</body>

</html>