<?php include '../header2.php' ?>
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


/* Style the tab */
.tab {
  overflow: hidden;
  border: 1px solid #ccc;
  background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
.tab button {
  background-color: inherit;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 14px 16px;
  transition: 0.3s;
  font-size: 17px;
}

/* Change background color of buttons on hover */
.tab button:hover {
  background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
  background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
  display: none;
  padding: 6px 12px;
  border: 1px solid #ccc;
  border-top: none;
}

/* Style the close button */
.topright {
  float: right;
  cursor: pointer;
  font-size: 28px;
}

.topright:hover {color: red;}
.horizontal{

    height:0;
    width:64%;
    margin: auto;
    border:1px solid #000000;

}
.horizontal_fiskal{

    height:0;
    width:94%;
    margin: auto;
    border:1px solid #000000;

}

.horizontal3{

    height:0;
    width:84%;
    margin: auto;
    border:1px solid #000000;

}

.horizontal4{

    height:0;
    width:67%;
    margin: auto;
    border:1px solid #000000;

}
</style>
<!-- MAIN -->
<div class="col p-4">
    <h4 class="text-center">FINANCIAL STATEMENT FISKAL YTD</h4>
    <div class="box">
        <div class="box header">

            <form id="form" action="financial-statement-fiskal.php" method="post">

                <div class="form-row">

                    <div class="col-md-2 mb-3 mt-1"> 
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
                         echo date("M Y");
                     } ?>" 
                     placeholder="Tanggal Awal">
                 </div>

                 <div class="col-md-2 mb-3 mt-1"> 
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
                 placeholder="Tanggal Awal">
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



            </div>                                                            
        </div>
        <br/>
    </div>
</form> 

<div class="tab" style="height: 35px;font-size: 12px;">
  <button style="margin-top: -5px;" class="tablinks btn btn-outline-dark" onclick="openCity(event, 'tril-balance')" id="defaultOpen">Trial Balance</button>
  <button style="margin-top: -5px;" class="tablinks btn btn-outline-dark" onclick="openCity(event, 'neraca')">Neraca</button>
  <button style="margin-top: -5px;" class="tablinks btn btn-outline-dark" onclick="openCity(event, 'laba-rugi')">Laba Rugi</button>
  <button style="margin-top: -5px;" class="tablinks btn btn-outline-dark" onclick="openCity(event, 'arus-kas')">Arus Kas</button>
  <button style="margin-top: -5px;" class="tablinks btn btn-outline-dark" onclick="openCity(event, 'explanation')">Explanation</button>
</div>

<div id="laba-rugi" class="tabcontent">
    <?php
        // $status = isset($_POST['status']) ? $_POST['status']: null;
    $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
    $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;
    $tanggal_awal = date("Y-m-d",strtotime($start_date));
    $tanggal_akhir = date("Y-m-d",strtotime($end_date)); 
    $tanggal1 = isset($tanggal_awal) ? $tanggal_awal : 0;
    $tanggal2 = isset($tanggal_akhir) ? $tanggal_akhir : 0;
    $kata_awal = date("M",strtotime($start_date));
    $tengah = '_';
    $kata_akhir = date("Y",strtotime($start_date));
    $kata_filter = $kata_awal . $tengah . $kata_akhir;

    if($tanggal2 < $tanggal1){
        echo "";
    }
    else{

        echo '<a style="padding-right: 10px;" target="_blank" href="ekspor_laba_rugi_fiskal.php?start_date='.$start_date.' && end_date='.$end_date.' && kata_filter='.$kata_filter.'"><button type="button" class="btn btn-success " ><i class="fa fa-file-excel-o" aria-hidden="true" style="padding-right: 10px; padding-left: 5px;font-size: 14px;color: #fff;text-shadow: 1px 1px 1px #000"> Excel </i></button></a>

        ';
    }
    ?>
    <div style="border:1px solid black; margin-top: 5px;">        
        <table style="font-size: 16px; margin:auto" border="0" role="grid" cellspacing="0" width="90%">
            <tr>
                <th colspan="4" style="text-align: center;vertical-align: middle;width: 64%;">PT NIRWANA ALABARE GARMENT</th>
            </tr>
            <tr>
                <th colspan="4" style="text-align: center;vertical-align: middle;width: 100%;">LAPORAN LABA RUGI</th>
            </tr>
            <tr>
                <th colspan="4" style="text-align: center;vertical-align: middle;width: 100%;">Per 01 <?php
                $enddate = date("F Y");
                $startdate = date("F Y");
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $enddate = date("F Y",strtotime($_POST['end_date'])); 
                    $startdate = date("F Y",strtotime($_POST['start_date'])); 
                } echo $startdate; echo ' S.D 31'; echo $enddate; ?></th>
            </tr>                   
        </table>
        <div class="horizontal_fiskal"></div>
        <table border="0" style="font-size: 14px;margin:auto" role="grid" cellspacing="0" width="90%">
            <tr style="line-height: 40px;">
                <th style="text-align: left;vertical-align: middle;width: 29%;"></th>
                <td style="text-align: center;vertical-align: middle;width: 5%;">Ref</td>
                <td colspan="2" style="text-align: center;vertical-align: middle;width: 17%;">Komersial</td>
                <td style="text-align: center;vertical-align: middle;width: 1%;"></td>
                <td colspan="2" style="text-align: center;vertical-align: middle;width: 17%;">Koreksi Fiskal</td>
                <td style="text-align: center;vertical-align: middle;width: 1%;"></td>
                <td colspan="2" style="text-align: center;vertical-align: middle;width: 17%;">Fiskal</td>
            </tr>
            <tr>
                <th style="text-align: left;vertical-align: middle;width: 29%;">PENDAPATAN USAHA</th>
                <td style="text-align: center;vertical-align: middle;width: 5%;">14.</td>
                <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
                <td style="text-align: center;vertical-align: middle;width: 15%;"></td>
                <td style="text-align: center;vertical-align: middle;width: 1%;"></td>
                <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
                <td style="text-align: center;vertical-align: middle;width: 15%;"></td> 
                <td style="text-align: center;vertical-align: middle;width: 1%;"></td>
                <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
                <td style="text-align: center;vertical-align: middle;width: 15%;"></td>
            </tr>
            <?php
            $nama_type ='';
            $Status = '';
            $start_date ='';
            $end_date ='';
            $date_now = date("Y-m-d");
            $tanggal_awal = date("Y-m-d",strtotime($date_now ));
            $tanggal_akhir = date("Y-m-d",strtotime($date_now ));
            $bulan_awal = date("m",strtotime($date_now));
            $bulan_akhir = date("m",strtotime($date_now));  
            $tahun_awal = date("Y",strtotime($date_now));
            $tahun_akhir = date("Y",strtotime($date_now));
            $kata_awal = date("M",strtotime($date_now));
            $tengah = '_';
            $kata_akhir = date("Y",strtotime($date_now));
            $kata_filter = $kata_awal . $tengah . $kata_akhir;
            $kata_filter2 = $kata_awal . $tengah . $kata_akhir;                 
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $nama_type = isset($_POST['nama_type']) ? $_POST['nama_type']: null; 
                $Status = isset($_POST['Status']) ? $_POST['Status']: null; 
                $start_date = date("d-m-Y",strtotime($_POST['start_date']));
                $end_date = date("d-m-Y",strtotime($_POST['end_date'])); 

                $tanggal_awal = date("Y-m-d",strtotime($_POST['start_date']));
                $tanggal_akhir = date("Y-m-d",strtotime($_POST['end_date'])); 

                $bulan_awal = date("m",strtotime($_POST['start_date']));
                $bulan_akhir = date("m",strtotime($_POST['end_date']));  
                $tahun_awal = date("Y",strtotime($_POST['start_date']));
                $tahun_akhir = date("Y",strtotime($_POST['end_date']));

                $kata_awal = date("M",strtotime($_POST['start_date']));
                $tengah = '_';
                $kata_akhir = date("Y",strtotime($_POST['start_date']));
                $kata_filter = $kata_awal . $tengah . $kata_akhir;

                $kata_awal2 = date("M",strtotime($_POST['end_date']));
                $tengah2 = '_';
                $kata_akhir2 = date("Y",strtotime($_POST['end_date']));
                $kata_filter2 = $kata_awal2 . $tengah2 . $kata_akhir2;


            }

            $sql = mysqli_query($conn2,"select *,round(total + total_fiskal,2) fiskal from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'PENDAPATAN USAHA') a INNER JOIN
                (select ind_categori2,ind_categori6,-(saldo + debit_idr - credit_idr) total,total_fiskal from (select ind_categori2,ind_categori6,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr, sum(COALESCE(total_fiskal,0)) total_fiskal from 
                (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
                left join
                (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,id_ctg5 from mastercoa_sb order by no_coa asc) coa
                on coa.no_coa = saldo.nocoa
                left join
                (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
                jnl on jnl.coa_no = coa.no_coa LEFT JOIN
                (select no_coa coa_fiskal,(saldo + debit - credit) total_fiskal from (select no_coa,sum(saldo_awal) saldo, sum(debit) debit, sum(credit) credit from (select no_coa,(SUM(COALESCE(val_plus,0)) - SUM(COALESCE(val_min,0))) saldo_awal,0 debit,0 credit from sb_journal_fiscal where status != 'Cancel' and tgl_dok < (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal')  group by no_coa
                UNION
                select no_coa,0 saldo_awal, SUM(COALESCE(val_plus,0)) debit, SUM(COALESCE(val_min,0)) credit from sb_journal_fiscal where status != 'Cancel' and tgl_dok BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) a GROUP BY no_coa) a GROUP BY no_coa) fk on fk.coa_fiskal = coa.no_coa GROUP BY ind_categori6 order by no_coa asc) a) b on b.ind_categori6 = a.sub_kategori order by id asc");

            $sql2 = mysqli_query($conn2,"select *,round(total - total_fiskal,2) fiskal from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'HARGA POKOK PENJUALAN') a INNER JOIN
                (select ind_categori2,ind_categori6,(saldo + debit_idr - credit_idr) total,total_fiskal from (select ind_categori2,ind_categori6,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr, sum(COALESCE(total_fiskal,0)) total_fiskal from 
                (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
                left join
                (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,id_ctg5 from mastercoa_sb order by no_coa asc) coa
                on coa.no_coa = saldo.nocoa
                left join
                (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
                jnl on jnl.coa_no = coa.no_coa LEFT JOIN
                (select no_coa coa_fiskal,(saldo + debit - credit) total_fiskal from (select no_coa,sum(saldo_awal) saldo, sum(debit) debit, sum(credit) credit from (select no_coa,(SUM(COALESCE(val_plus,0)) - SUM(COALESCE(val_min,0))) saldo_awal,0 debit,0 credit from sb_journal_fiscal where status != 'Cancel' and tgl_dok < (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal')  group by no_coa
                UNION
                select no_coa,0 saldo_awal, SUM(COALESCE(val_plus,0)) debit, SUM(COALESCE(val_min,0)) credit from sb_journal_fiscal where status != 'Cancel' and tgl_dok BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) a GROUP BY no_coa) a GROUP BY no_coa) fk on fk.coa_fiskal = coa.no_coa GROUP BY ind_categori2 order by no_coa asc) a) b on b.ind_categori2 = a.sub_kategori order by id asc");

            $sql3 = mysqli_query($conn2,"select *,round(total - total_fiskal,2) fiskal from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'BIAYA PENJUALAN') a INNER JOIN
                (select ind_categori2,ind_categori6,(saldo + debit_idr - credit_idr) total,total_fiskal from (select ind_categori2,ind_categori6,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr, sum(COALESCE(total_fiskal,0)) total_fiskal from 
                (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
                left join
                (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,id_ctg5 from mastercoa_sb order by no_coa asc) coa
                on coa.no_coa = saldo.nocoa
                left join
                (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
                jnl on jnl.coa_no = coa.no_coa LEFT JOIN
                (select no_coa coa_fiskal,(saldo + debit - credit) total_fiskal from (select no_coa,sum(saldo_awal) saldo, sum(debit) debit, sum(credit) credit from (select no_coa,(SUM(COALESCE(val_plus,0)) - SUM(COALESCE(val_min,0))) saldo_awal,0 debit,0 credit from sb_journal_fiscal where status != 'Cancel' and tgl_dok < (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal')  group by no_coa
                UNION
                select no_coa,0 saldo_awal, SUM(COALESCE(val_plus,0)) debit, SUM(COALESCE(val_min,0)) credit from sb_journal_fiscal where status != 'Cancel' and tgl_dok BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) a GROUP BY no_coa) a GROUP BY no_coa) fk on fk.coa_fiskal = coa.no_coa WHERE ind_categori2 = 'BIAYA PENJUALAN' GROUP BY ind_categori6 order by no_coa asc) a) b on b.ind_categori6 = a.sub_kategori order by id asc");

            $sql4 = mysqli_query($conn2,"select *,round(total - total_fiskal,2) fiskal from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'BIAYA ADMINISTRASI & UMUM') a INNER JOIN
                (select ind_categori2,ind_categori6,(saldo + debit_idr - credit_idr) total,total_fiskal from (select ind_categori2,ind_categori6,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr, sum(COALESCE(total_fiskal,0)) total_fiskal from 
                (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
                left join
                (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,id_ctg5 from mastercoa_sb order by no_coa asc) coa
                on coa.no_coa = saldo.nocoa
                left join
                (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
                jnl on jnl.coa_no = coa.no_coa LEFT JOIN
                (select no_coa coa_fiskal,(saldo + debit - credit) total_fiskal from (select no_coa,sum(saldo_awal) saldo, sum(debit) debit, sum(credit) credit from (select no_coa,(SUM(COALESCE(val_plus,0)) - SUM(COALESCE(val_min,0))) saldo_awal,0 debit,0 credit from sb_journal_fiscal where status != 'Cancel' and tgl_dok < (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal')  group by no_coa
                UNION
                select no_coa,0 saldo_awal, SUM(COALESCE(val_plus,0)) debit, SUM(COALESCE(val_min,0)) credit from sb_journal_fiscal where status != 'Cancel' and tgl_dok BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) a GROUP BY no_coa) a GROUP BY no_coa) fk on fk.coa_fiskal = coa.no_coa WHERE ind_categori2 = 'BIAYA ADMINISTRASI & UMUM' GROUP BY ind_categori6 order by no_coa asc) a) b on b.ind_categori6 = a.sub_kategori order by id asc");

            $sql5 = mysqli_query($conn2,"select *,round(total - total_fiskal,2) fiskal from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'PENDAPATAN / (BEBAN) LAIN-LAIN') a INNER JOIN
                (select ind_categori2,ind_categori6,-(saldo + debit_idr - credit_idr) total,total_fiskal from (select ind_categori2,ind_categori6,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr, sum(COALESCE(total_fiskal,0)) total_fiskal from 
                (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
                left join
                (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,id_ctg5 from mastercoa_sb order by no_coa asc) coa
                on coa.no_coa = saldo.nocoa
                left join
                (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
                jnl on jnl.coa_no = coa.no_coa LEFT JOIN
                (select no_coa coa_fiskal,(saldo + debit - credit) total_fiskal from (select no_coa,sum(saldo_awal) saldo, sum(debit) debit, sum(credit) credit from (select no_coa,(SUM(COALESCE(val_plus,0)) - SUM(COALESCE(val_min,0))) saldo_awal,0 debit,0 credit from sb_journal_fiscal where status != 'Cancel' and tgl_dok < (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal')  group by no_coa
                UNION
                select no_coa,0 saldo_awal, SUM(COALESCE(val_plus,0)) debit, SUM(COALESCE(val_min,0)) credit from sb_journal_fiscal where status != 'Cancel' and tgl_dok BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) a GROUP BY no_coa) a GROUP BY no_coa) fk on fk.coa_fiskal = coa.no_coa WHERE ind_categori2 = 'PENDAPATAN / (BEBAN) LAIN-LAIN' GROUP BY ind_categori6 order by no_coa asc) a) b on b.ind_categori6 = a.sub_kategori order by id asc");


            $saldoakhir = 0;

            if($tanggal_akhir < $tanggal_awal){
                $message = "Mohon Masukan Tanggal Filter Yang Benar";
                echo "<script type='text/javascript'>alert('$message');</script>";
            }
            else{
                $no = 01;
                $total_pendapatan_usaha = 0;
                $total_pendapatan_usaha_fiskal = 0;
                $total_pendapatan_usaha_fk = 0;
                while($row = mysqli_fetch_array($sql)){
                    $pendapatan_usaha = isset($row['total']) ? $row['total'] : 0;
                    if ($pendapatan_usaha > 0) {
                        $pendapatan_usaha = number_format($pendapatan_usaha,2);
                    }else{
                        $pendapatan_usaha = '('.number_format(abs($pendapatan_usaha),2).')';
                    }

                    $pendapatan_usaha_fiskal = isset($row['total_fiskal']) ? $row['total_fiskal'] : 0;
                    if ($pendapatan_usaha_fiskal > 0) {
                        $pendapatan_usaha_fiskal = number_format($pendapatan_usaha_fiskal,2);
                    }else{
                        $pendapatan_usaha_fiskal = '('.number_format(abs($pendapatan_usaha_fiskal),2).')';
                    }

                    $pendapatan_usaha_fk = isset($row['fiskal']) ? $row['fiskal'] : 0;
                    if ($pendapatan_usaha_fk > 0) {
                        $pendapatan_usaha_fk = number_format($pendapatan_usaha_fk,2);
                    }else{
                        $pendapatan_usaha_fk = '('.number_format(abs($pendapatan_usaha_fk),2).')';
                    }

                    $total_pendapatan_usaha += isset($row['total']) ? $row['total'] : 0;
                    if ($total_pendapatan_usaha > 0) {
                        $total_pendapatan_usaha_ = number_format($total_pendapatan_usaha,2);
                    }else{
                        $total_pendapatan_usaha_ = '('.number_format(abs($total_pendapatan_usaha),2).')';
                    }

                    $total_pendapatan_usaha_fiskal += isset($row['total_fiskal']) ? $row['total_fiskal'] : 0;
                    if ($total_pendapatan_usaha_fiskal > 0) {
                        $total_pendapatan_usaha_fiskal_ = number_format($total_pendapatan_usaha_fiskal,2);
                    }else{
                        $total_pendapatan_usaha_fiskal_ = '('.number_format(abs($total_pendapatan_usaha_fiskal),2).')';
                    }

                    $total_pendapatan_usaha_fk += isset($row['fiskal']) ? $row['fiskal'] : 0;
                    if ($total_pendapatan_usaha_fk > 0) {
                        $total_pendapatan_usaha_fk_ = number_format($total_pendapatan_usaha_fk,2);
                    }else{
                        $total_pendapatan_usaha_fk_ = '('.number_format(abs($total_pendapatan_usaha_fk),2).')';
                    }

                    echo '<tr>
                    <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">'.$row['sub_kategori'].'</td>
                    <td style="text-align: center;vertical-align: middle;width: 5%;">'.$row['ref'].'</td>
                    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                    <td style="text-align: right;vertical-align: middle;width: 15%;">'.$pendapatan_usaha.'</td> 
                    <td style="text-align: center;vertical-align: middle;width: 1%;"></td>
                    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                    <td style="text-align: right;vertical-align: middle;width: 15%;">'.$pendapatan_usaha_fiskal.'</td> 
                    <td style="text-align: center;vertical-align: middle;width: 1%;"></td>
                    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                    <td style="text-align: right;vertical-align: middle;width: 15%;">'.$pendapatan_usaha_fk.'</td> 
                    </tr>';
                }
                echo '<tr style="line-height: 40px;">
                <th style="text-align: left;vertical-align: middle;width: 29%;">TOTAL PENDAPATAN</th>
                <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
                <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
                <th style="text-align: right;vertical-align: middle;width: 15%;border-top:1px solid #000000;">'.$total_pendapatan_usaha_.'</th> 
                <td style="text-align: center;vertical-align: middle;width: 1%;"></td>
                <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
                <th style="text-align: right;vertical-align: middle;width: 15%;border-top:1px solid #000000;">'.$total_pendapatan_usaha_fiskal_.'</th> 
                <td style="text-align: center;vertical-align: middle;width: 1%;"></td>
                <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
                <th style="text-align: right;vertical-align: middle;width: 15%;border-top:1px solid #000000;">'.$total_pendapatan_usaha_fk_.'</th> 
                </tr>';

                $total_harga_pokok_penjualan = 0;
                $total_harga_pokok_penjualan_fiskal = 0;
                $total_harga_pokok_penjualan_fk = 0;
                while($row2 = mysqli_fetch_array($sql2)){
                    $harga_pokok_penjualan = isset($row2['total']) ? $row2['total'] : 0;
                    if ($harga_pokok_penjualan > 0) {
                        $harga_pokok_penjualan = number_format($harga_pokok_penjualan,2);
                    }else{
                        $harga_pokok_penjualan = '('.number_format(abs($harga_pokok_penjualan),2).')';
                    }

                    $harga_pokok_penjualan_fiskal = isset($row2['total_fiskal']) ? $row2['total_fiskal'] : 0;
                    if ($harga_pokok_penjualan_fiskal > 0) {
                        $harga_pokok_penjualan_fiskal = number_format($harga_pokok_penjualan_fiskal,2);
                    }else{
                        $harga_pokok_penjualan_fiskal = '('.number_format(abs($harga_pokok_penjualan_fiskal),2).')';
                    }

                    $harga_pokok_penjualan_fk = isset($row2['fiskal']) ? $row2['fiskal'] : 0;
                    if ($harga_pokok_penjualan_fk > 0) {
                        $harga_pokok_penjualan_fk = number_format($harga_pokok_penjualan_fk,2);
                    }else{
                        $harga_pokok_penjualan_fk = '('.number_format(abs($harga_pokok_penjualan_fk),2).')';
                    }

                    $total_harga_pokok_penjualan += isset($row2['total']) ? $row2['total'] : 0;
                    if ($total_harga_pokok_penjualan > 0) {
                        $total_harga_pokok_penjualan_ = number_format($total_harga_pokok_penjualan,2);
                    }else{
                        $total_harga_pokok_penjualan_ = '('.number_format(abs($total_harga_pokok_penjualan),2).')';
                    }

                    $total_harga_pokok_penjualan_fiskal += isset($row2['total_fiskal']) ? $row2['total_fiskal'] : 0;
                    if ($total_harga_pokok_penjualan_fiskal > 0) {
                        $total_harga_pokok_penjualan_fiskal_ = number_format($total_harga_pokok_penjualan_fiskal,2);
                    }else{
                        $total_harga_pokok_penjualan_fiskal_ = '('.number_format(abs($total_harga_pokok_penjualan_fiskal),2).')';
                    }

                    $total_harga_pokok_penjualan_fk += isset($row2['fiskal']) ? $row2['fiskal'] : 0;
                    if ($total_harga_pokok_penjualan_fk > 0) {
                        $total_harga_pokok_penjualan_fk_ = number_format($total_harga_pokok_penjualan_fk,2);
                    }else{
                        $total_harga_pokok_penjualan_fk_ = '('.number_format(abs($total_harga_pokok_penjualan_fk),2).')';
                    }

                    echo '<tr>
                    <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">'.$row2['sub_kategori'].'</td>
                    <td style="text-align: center;vertical-align: middle;width: 5%;">'.$row2['ref'].'</td>
                    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                    <td style="text-align: right;vertical-align: middle;width: 15%;">'.$harga_pokok_penjualan.'</td> 
                    <td style="text-align: center;vertical-align: middle;width: 1%;"></td>
                    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                    <td style="text-align: right;vertical-align: middle;width: 15%;">'.$harga_pokok_penjualan_fiskal.'</td> 
                    <td style="text-align: center;vertical-align: middle;width: 1%;"></td>
                    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                    <td style="text-align: right;vertical-align: middle;width: 15%;">'.$harga_pokok_penjualan_fk.'</td> 
                    </tr>';
                }

                $laba_rugi_kotor = $total_pendapatan_usaha - $total_harga_pokok_penjualan;
                if ($laba_rugi_kotor > 0) {
                    $laba_rugi_kotor_ = number_format($laba_rugi_kotor,2);
                }else{
                    $laba_rugi_kotor_ = '('.number_format(abs($laba_rugi_kotor),2).')';
                }

                $laba_rugi_kotor_fiskal = $total_pendapatan_usaha_fiskal - $total_harga_pokok_penjualan_fiskal;
                if ($laba_rugi_kotor_fiskal > 0) {
                    $laba_rugi_kotor_fiskal_ = number_format($laba_rugi_kotor_fiskal,2);
                }else{
                    $laba_rugi_kotor_fiskal_ = '('.number_format(abs($laba_rugi_kotor_fiskal),2).')';
                }

                $laba_rugi_kotor_fk = $total_pendapatan_usaha_fk - $total_harga_pokok_penjualan_fk;
                if ($laba_rugi_kotor_fk > 0) {
                    $laba_rugi_kotor_fk_ = number_format($laba_rugi_kotor_fk,2);
                }else{
                    $laba_rugi_kotor_fk_ = '('.number_format(abs($laba_rugi_kotor_fk),2).')';
                }

                echo '
                <tr style="line-height: 40px;">
                <th style="text-align: left;vertical-align: middle;width: 29%;">LABA (RUGI) KOTOR</th>
                <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
                <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
                <th style="text-align: right;vertical-align: middle;width: 15%;border-top:1px solid #000000;">'.$laba_rugi_kotor_.'</th>
                <td style="text-align: center;vertical-align: middle;width: 1%;"></td>
                <th style="text-align: center;vertical-align: middle;width: 2%;border-top:1px solid #000000;">Rp.</th>
                <th style="text-align: right;vertical-align: middle;width: 15%;border-top:1px solid #000000;">'.$laba_rugi_kotor_fiskal_.'</th> 
                <th style="text-align: center;vertical-align: middle;width: 1%;"></th>
                <th style="text-align: center;vertical-align: middle;width: 2%;border-top:1px solid #000000;">Rp.</th>
                <th style="text-align: right;vertical-align: middle;width: 15%;border-top:1px solid #000000;">'.$laba_rugi_kotor_fk_.'</th>  
                </tr>
                <tr>
                <th style="text-align: left;vertical-align: middle;width: 29%;">BIAYA PENJUALAN</th>
                <td style="text-align: center;vertical-align: middle;width: 5%;">16.</td>
                <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
                <td style="text-align: center;vertical-align: middle;width: 15%;"></td> 
                <td style="text-align: center;vertical-align: middle;width: 1%;"></td>
                <th style="text-align: center;vertical-align: middle;width: 2%;"></th>
                <th style="text-align: right;vertical-align: middle;width: 15%;"></th> 
                <th style="text-align: center;vertical-align: middle;width: 1%;"></th>
                <th style="text-align: center;vertical-align: middle;width: 2%;"></th>
                <th style="text-align: right;vertical-align: middle;width: 15%;"></th>
                </tr>
                ';

                $total_biaya_penjualan = 0;
                $total_biaya_penjualan_fiskal = 0;
                $total_biaya_penjualan_fk = 0;
                while($row3 = mysqli_fetch_array($sql3)){
                    $biaya_penjualan = isset($row3['total']) ? $row3['total'] : 0;
                    if ($biaya_penjualan > 0) {
                        $biaya_penjualan = number_format($biaya_penjualan,2);
                    }else{
                        $biaya_penjualan = '('.number_format(abs($biaya_penjualan),2).')';
                    }

                    $biaya_penjualan_fiskal = isset($row3['total_fiskal']) ? $row3['total_fiskal'] : 0;
                    if ($biaya_penjualan_fiskal > 0) {
                        $biaya_penjualan_fiskal = number_format($biaya_penjualan_fiskal,2);
                    }else{
                        $biaya_penjualan_fiskal = '('.number_format(abs($biaya_penjualan_fiskal),2).')';
                    }

                    $biaya_penjualan_fk = isset($row3['fiskal']) ? $row3['fiskal'] : 0;
                    if ($biaya_penjualan_fk > 0) {
                        $biaya_penjualan_fk = number_format($biaya_penjualan_fk,2);
                    }else{
                        $biaya_penjualan_fk = '('.number_format(abs($biaya_penjualan_fk),2).')';
                    }

                    $total_biaya_penjualan += isset($row3['total']) ? $row3['total'] : 0;
                    if ($total_biaya_penjualan > 0) {
                        $total_biaya_penjualan_ = number_format($total_biaya_penjualan,2);
                    }else{
                        $total_biaya_penjualan_ = '('.number_format(abs($total_biaya_penjualan),2).')';
                    }

                    $total_biaya_penjualan_fiskal += isset($row3['total_fiskal']) ? $row3['total_fiskal'] : 0;
                    if ($total_biaya_penjualan_fiskal > 0) {
                        $total_biaya_penjualan_fiskal_ = number_format($total_biaya_penjualan_fiskal,2);
                    }else{
                        $total_biaya_penjualan_fiskal_ = '('.number_format(abs($total_biaya_penjualan_fiskal),2).')';
                    }

                    $total_biaya_penjualan_fk += isset($row3['fiskal']) ? $row3['fiskal'] : 0;
                    if ($total_biaya_penjualan_fk > 0) {
                        $total_biaya_penjualan_fk_ = number_format($total_biaya_penjualan_fk,2);
                    }else{
                        $total_biaya_penjualan_fk_ = '('.number_format(abs($total_biaya_penjualan_fk),2).')';
                    }

                    echo '<tr>
                    <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">'.$row3['sub_kategori'].'</td>
                    <td style="text-align: center;vertical-align: middle;width: 5%;">'.$row3['ref'].'</td>
                    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                    <td style="text-align: right;vertical-align: middle;width: 15%;">'.$biaya_penjualan.'</td> 
                    <td style="text-align: center;vertical-align: middle;width: 1%;"></td>
                    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                    <td style="text-align: right;vertical-align: middle;width: 15%;">'.$biaya_penjualan_fiskal.'</td> 
                    <td style="text-align: center;vertical-align: middle;width: 1%;"></td>
                    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                    <td style="text-align: right;vertical-align: middle;width: 15%;">'.$biaya_penjualan_fk.'</td> 
                    </tr>';
                }
                echo '<tr style="line-height: 40px;">
                <th style="text-align: left;vertical-align: middle;width: 29%;">BIAYA ADMINISTRASI & UMUM</th>
                <td style="text-align: center;vertical-align: middle;width: 5%;">17.</td>
                <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
                <td style="text-align: center;vertical-align: middle;width: 15%;"></td>  
                <td style="text-align: center;vertical-align: middle;width: 1%;"></td>
                <th style="text-align: center;vertical-align: middle;width: 2%;"></th>
                <th style="text-align: right;vertical-align: middle;width: 15%;"></th> 
                <th style="text-align: center;vertical-align: middle;width: 1%;"></th>
                <th style="text-align: center;vertical-align: middle;width: 2%;"></th>
                <th style="text-align: right;vertical-align: middle;width: 15%;"></th>
                </tr>';

                $total_biaya_adm_umum = 0;
                $total_biaya_adm_umum_fiskal = 0;
                $total_biaya_adm_umum_fk = 0;
                while($row4 = mysqli_fetch_array($sql4)){
                    $biaya_adm_umum = isset($row4['total']) ? $row4['total'] : 0;
                    if ($biaya_adm_umum > 0) {
                        $biaya_adm_umum = number_format($biaya_adm_umum,2);
                    }else{
                        $biaya_adm_umum = '('.number_format(abs($biaya_adm_umum),2).')';
                    }

                    $biaya_adm_umum_fiskal = isset($row4['total_fiskal']) ? $row4['total_fiskal'] : 0;
                    if ($biaya_adm_umum_fiskal > 0) {
                        $biaya_adm_umum_fiskal = number_format($biaya_adm_umum_fiskal,2);
                    }else{
                        $biaya_adm_umum_fiskal = '('.number_format(abs($biaya_adm_umum_fiskal),2).')';
                    }

                    $biaya_adm_umum_fk = isset($row4['fiskal']) ? $row4['fiskal'] : 0;
                    if ($biaya_adm_umum_fk > 0) {
                        $biaya_adm_umum_fk = number_format($biaya_adm_umum_fk,2);
                    }else{
                        $biaya_adm_umum_fk = '('.number_format(abs($biaya_adm_umum_fk),2).')';
                    }

                    $total_biaya_adm_umum += isset($row4['total']) ? $row4['total'] : 0;
                    if ($total_biaya_adm_umum > 0) {
                        $total_biaya_adm_umum_ = number_format($total_biaya_adm_umum,2);
                    }else{
                        $total_biaya_adm_umum_ = '('.number_format(abs($total_biaya_adm_umum),2).')';
                    }

                    $total_biaya_adm_umum_fiskal += isset($row4['total_fiskal']) ? $row4['total_fiskal'] : 0;
                    if ($total_biaya_adm_umum_fiskal > 0) {
                        $total_biaya_adm_umum_fiskal_ = number_format($total_biaya_adm_umum_fiskal,2);
                    }else{
                        $total_biaya_adm_umum_fiskal_ = '('.number_format(abs($total_biaya_adm_umum_fiskal),2).')';
                    }

                    $total_biaya_adm_umum_fk += isset($row4['fiskal']) ? $row4['fiskal'] : 0;
                    if ($total_biaya_adm_umum_fk > 0) {
                        $total_biaya_adm_umum_fk_ = number_format($total_biaya_adm_umum_fk,2);
                    }else{
                        $total_biaya_adm_umum_fk_ = '('.number_format(abs($total_biaya_adm_umum_fk),2).')';
                    }

                    echo '<tr>
                    <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">'.$row4['sub_kategori'].'</td>
                    <td style="text-align: center;vertical-align: middle;width: 5%;">'.$row4['ref'].'</td>
                    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                    <td style="text-align: right;vertical-align: middle;width: 15%;">'.$biaya_adm_umum.'</td> 
                    <td style="text-align: center;vertical-align: middle;width: 1%;"></td>
                    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                    <td style="text-align: right;vertical-align: middle;width: 15%;">'.$biaya_adm_umum_fiskal.'</td> 
                    <td style="text-align: center;vertical-align: middle;width: 1%;"></td>
                    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                    <td style="text-align: right;vertical-align: middle;width: 15%;">'.$biaya_adm_umum_fk.'</td> 
                    </tr>';
                }

                $total_operasional = $total_biaya_penjualan + $total_biaya_adm_umum;
                if ($total_operasional > 0) {
                    $total_operasional_ = number_format($total_operasional,2);
                }else{
                    $total_operasional_ = '('.number_format(abs($total_operasional),2).')';
                }

                $total_operasional_fiskal = $total_biaya_penjualan_fiskal + $total_biaya_adm_umum_fiskal;
                if ($total_operasional_fiskal > 0) {
                    $total_operasional_fiskal_ = number_format($total_operasional_fiskal,2);
                }else{
                    $total_operasional_fiskal_ = '('.number_format(abs($total_operasional_fiskal),2).')';
                }

                $total_operasional_fk = $total_biaya_penjualan_fk + $total_biaya_adm_umum_fk;
                if ($total_operasional_fk > 0) {
                    $total_operasional_fk_ = number_format($total_operasional_fk,2);
                }else{
                    $total_operasional_fk_ = '('.number_format(abs($total_operasional_fk),2).')';
                }
                echo '
                <tr style="line-height: 40px;">
                <th style="text-align: left;vertical-align: middle;width: 29%;">TOTAL BIAYA OPERASIONAL</th>
                <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
                <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
                <th style="text-align: right;vertical-align: middle;width: 15%;border-top:1px solid #000000;">'.$total_operasional_.'</th>
                <td style="text-align: center;vertical-align: middle;width: 1%;"></td>
                <th style="text-align: center;vertical-align: middle;width: 2%;border-top:1px solid #000000;">Rp.</th>
                <th style="text-align: right;vertical-align: middle;width: 15%;border-top:1px solid #000000;">'.$total_operasional_fiskal_.'</th> 
                <th style="text-align: center;vertical-align: middle;width: 1%;"></th>
                <th style="text-align: center;vertical-align: middle;width: 2%;border-top:1px solid #000000;">Rp.</th>
                <th style="text-align: right;vertical-align: middle;width: 15%;border-top:1px solid #000000;">'.$total_operasional_fk_.'</th> 
                </tr>';

                $laba_rugi_operasional = $laba_rugi_kotor - $total_operasional;
                if ($laba_rugi_operasional > 0) {
                    $laba_rugi_operasional_ = number_format($laba_rugi_operasional,2);
                }else{
                    $laba_rugi_operasional_ = '('.number_format(abs($laba_rugi_operasional),2).')';
                }

                $laba_rugi_operasional_fiskal = $laba_rugi_kotor_fiskal - $total_operasional_fiskal;
                if ($laba_rugi_operasional_fiskal > 0) {
                    $laba_rugi_operasional_fiskal_ = number_format($laba_rugi_operasional_fiskal,2);
                }else{
                    $laba_rugi_operasional_fiskal_ = '('.number_format(abs($laba_rugi_operasional_fiskal),2).')';
                }

                $laba_rugi_operasional_fk = $laba_rugi_kotor_fk - $total_operasional_fk;
                if ($laba_rugi_operasional_fk > 0) {
                    $laba_rugi_operasional_fk_ = number_format($laba_rugi_operasional_fk,2);
                }else{
                    $laba_rugi_operasional_fk_ = '('.number_format(abs($laba_rugi_operasional_fk),2).')';
                }

                echo '
                <tr style="line-height: 40px;">
                <th style="text-align: left;vertical-align: middle;width: 29%;">LABA (RUGI) OPERASIONAL</th>
                <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
                <th style="text-align: center;vertical-align: middle;width: 5%;">Rp.</th>
                <th style="text-align: right;vertical-align: middle;width: 15%;">'.$laba_rugi_operasional_.'</th> 
                <th style="text-align: center;vertical-align: middle;width: 1%;"></th>
                <th style="text-align: center;vertical-align: middle;width: 5%;">Rp.</th>
                <th style="text-align: right;vertical-align: middle;width: 15%;">'.$laba_rugi_operasional_fiskal_.'</th> 
                <th style="text-align: center;vertical-align: middle;width: 1%;"></th>
                <th style="text-align: center;vertical-align: middle;width: 5%;">Rp.</th>
                <th style="text-align: right;vertical-align: middle;width: 15%;">'.$laba_rugi_operasional_fk_.'</th>
                </tr>
                <tr>
                <th style="text-align: left;vertical-align: middle;width: 29%;">PENDAPATAN / (BEBAN) LAIN-LAIN</th>
                <td style="text-align: center;vertical-align: middle;width: 5%;">18.</td>
                <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
                <td style="text-align: center;vertical-align: middle;width: 15%;"></td>
                <th style="text-align: center;vertical-align: middle;width: 1%;"></th>
                <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
                <td style="text-align: center;vertical-align: middle;width: 15%;"></td>
                <th style="text-align: center;vertical-align: middle;width: 1%;"></th>
                <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
                <td style="text-align: center;vertical-align: middle;width: 15%;"></td>
                </tr>';

                $total_biaya_pendapatan_lain = 0;
                $total_biaya_pendapatan_lain_fiskal = 0;
                $total_biaya_pendapatan_lain_fk = 0;
                while($row5 = mysqli_fetch_array($sql5)){
                    $biaya_pendapatan_lain = isset($row5['total']) ? $row5['total'] : 0;
                    if ($biaya_pendapatan_lain > 0) {
                        $biaya_pendapatan_lain = number_format($biaya_pendapatan_lain,2);
                    }else{
                        $biaya_pendapatan_lain = '('.number_format(abs($biaya_pendapatan_lain),2).')';
                    }

                    $biaya_pendapatan_lain_fiskal = isset($row5['total_fiskal']) ? $row5['total_fiskal'] : 0;
                    if ($biaya_pendapatan_lain_fiskal > 0) {
                        $biaya_pendapatan_lain_fiskal = number_format($biaya_pendapatan_lain_fiskal,2);
                    }else{
                        $biaya_pendapatan_lain_fiskal = '('.number_format(abs($biaya_pendapatan_lain_fiskal),2).')';
                    }

                    $biaya_pendapatan_lain_fk = isset($row5['fiskal']) ? $row5['fiskal'] : 0;
                    if ($biaya_pendapatan_lain_fk > 0) {
                        $biaya_pendapatan_lain_fk = number_format($biaya_pendapatan_lain_fk,2);
                    }else{
                        $biaya_pendapatan_lain_fk = '('.number_format(abs($biaya_pendapatan_lain_fk),2).')';
                    }

                    $total_biaya_pendapatan_lain += isset($row5['total']) ? $row5['total'] : 0;
                    if ($total_biaya_pendapatan_lain > 0) {
                        $total_biaya_pendapatan_lain_ = number_format($total_biaya_pendapatan_lain,2);
                    }else{
                        $total_biaya_pendapatan_lain_ = '('.number_format(abs($total_biaya_pendapatan_lain),2).')';
                    }

                    $total_biaya_pendapatan_lain_fiskal += isset($row5['total_fiskal']) ? $row5['total_fiskal'] : 0;
                    if ($total_biaya_pendapatan_lain_fiskal > 0) {
                        $total_biaya_pendapatan_lain_fiskal_ = number_format($total_biaya_pendapatan_lain_fiskal,2);
                    }else{
                        $total_biaya_pendapatan_lain_fiskal_ = '('.number_format(abs($total_biaya_pendapatan_lain_fiskal),2).')';
                    }

                    $total_biaya_pendapatan_lain_fk += isset($row5['fiskal']) ? $row5['fiskal'] : 0;
                    if ($total_biaya_pendapatan_lain_fk > 0) {
                        $total_biaya_pendapatan_lain_fk_ = number_format($total_biaya_pendapatan_lain_fk,2);
                    }else{
                        $total_biaya_pendapatan_lain_fk_ = '('.number_format(abs($total_biaya_pendapatan_lain_fk),2).')';
                    }

                    echo '<tr>
                    <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">'.$row5['sub_kategori'].'</td>
                    <td style="text-align: center;vertical-align: middle;width: 5%;">'.$row5['ref'].'</td>
                    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                    <td style="text-align: right;vertical-align: middle;width: 15%;">'.$biaya_pendapatan_lain.'</td> 
                    <td style="text-align: center;vertical-align: middle;width: 1%;"></td>
                    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                    <td style="text-align: right;vertical-align: middle;width: 15%;">'.$biaya_pendapatan_lain_fiskal.'</td> 
                    <td style="text-align: center;vertical-align: middle;width: 1%;"></td>
                    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                    <td style="text-align: right;vertical-align: middle;width: 15%;">'.$biaya_pendapatan_lain_fk.'</td> 
                    </tr>';
                }
                echo '
                <tr style="line-height: 40px;">
                <th style="text-align: left;vertical-align: middle;width: 29%;">TOTAL PENDAPATAN / (BEBAN) LAIN-LAIN</th>
                <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
                <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
                <th style="text-align: right;vertical-align: middle;width: 15%;border-top:1px solid #000000;">'.$total_biaya_pendapatan_lain_.'</th> 
                <td style="text-align: center;vertical-align: middle;width: 1%;"></td>
                <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
                <th style="text-align: right;vertical-align: middle;width: 15%;border-top:1px solid #000000;">'.$total_biaya_pendapatan_lain_fiskal_.'</th> 
                <td style="text-align: center;vertical-align: middle;width: 1%;"></td>
                <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
                <th style="text-align: right;vertical-align: middle;width: 15%;border-top:1px solid #000000;">'.$total_biaya_pendapatan_lain_fk_.'</th> 
                </tr>';

                $laba_rugi_komersil = $laba_rugi_operasional + $total_biaya_pendapatan_lain;
                if ($laba_rugi_komersil > 0) {
                    $laba_rugi_komersil_ = number_format($laba_rugi_komersil,2);
                }else{
                    $laba_rugi_komersil_ = '('.number_format(abs($laba_rugi_komersil),2).')';
                }

                $laba_rugi_komersil_fiskal = $laba_rugi_operasional_fiskal + $total_biaya_pendapatan_lain_fiskal;
                if ($laba_rugi_komersil_fiskal > 0) {
                    $laba_rugi_komersil_fiskal_ = number_format($laba_rugi_komersil_fiskal,2);
                }else{
                    $laba_rugi_komersil_fiskal_ = '('.number_format(abs($laba_rugi_komersil_fiskal),2).')';
                }

                $laba_rugi_komersil_fk = $laba_rugi_operasional_fk + $total_biaya_pendapatan_lain_fk;
                if ($laba_rugi_komersil_fk > 0) {
                    $laba_rugi_komersil_fk_ = number_format($laba_rugi_komersil_fk,2);
                }else{
                    $laba_rugi_komersil_fk_ = '('.number_format(abs($laba_rugi_komersil_fk),2).')';
                }

                echo '
                <tr style="line-height: 40px;">
                <th style="text-align: left;vertical-align: middle;width: 29%;">LABA (RUGI) KOMERSIL</th>
                <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
                <th style="text-align: center;vertical-align: middle;width: 5%;">Rp.</th>
                <th style="text-align: right;vertical-align: middle;width: 15%;">'.$laba_rugi_komersil_.'</th>
                <td style="text-align: center;vertical-align: middle;width: 1%;"></td>
                <th style="text-align: center;vertical-align: middle;width: 5%;">Rp.</th>
                <th style="text-align: right;vertical-align: middle;width: 15%;">'.$laba_rugi_komersil_fiskal_.'</th> 
                <td style="text-align: center;vertical-align: middle;width: 1%;"></td>
                <th style="text-align: center;vertical-align: middle;width: 5%;">Rp.</th>
                <th style="text-align: right;vertical-align: middle;width: 15%;">'.$laba_rugi_komersil_fk_.'</th>
                </tr>';
            }
            ?>
        </table>
        <br>
    </div>
</div>


<div id="tril-balance" class="tabcontent">
    <form id="form-data" method="post">
      <h4 class="text-center">TRIAL BALANCE</h4>
      <?php
        // $status = isset($_POST['status']) ? $_POST['status']: null;
      $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
      $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;
      $tanggal_awal = date("Y-m-d",strtotime($start_date));
      $tanggal_akhir = date("Y-m-d",strtotime($end_date)); 
      $tanggal1 = isset($tanggal_awal) ? $tanggal_awal : 0;
      $tanggal2 = isset($tanggal_akhir) ? $tanggal_akhir : 0;
      $kata_awal = date("M",strtotime($start_date));
      $tengah = '_';
      $kata_akhir = date("Y",strtotime($start_date));
      $kata_filter = $kata_awal . $tengah . $kata_akhir;

      if($tanggal2 < $tanggal1){
        echo "";
    }
    else{

        echo '<a style="padding-right: 10px;" target="_blank" href="../acct/ekspor_tb_ytd.php?start_date='.$start_date.' && end_date='.$end_date.' && kata_filter='.$kata_filter.'"><button type="button" class="btn btn-success " ><i class="fa fa-file-excel-o" aria-hidden="true" style="padding-right: 10px; padding-left: 5px;font-size: 14px;color: #fff;text-shadow: 1px 1px 1px #000"> Excel TB</i></button></a>

        ';
    }
    ?> 

    <input type="text" class="col-md-2 form-control float-right" id="myInput" onkeyup="myFunction()" placeholder="Search no coa..">
</br>
</br>



<div class="tableFix" style="height: 400px;margin-top: -15px;">        
    <table id="datatable" class="table table-striped table-bordered" style="font-size: 12px;" role="grid" cellspacing="0" width="100%">
        <thead>
            <tr class="thead-dark">
                <th style="display: none;width: 2%;">Remark</th>
                <th style="text-align: center;vertical-align: middle;width: 6%;">No coa</th>
                <th style="text-align: center;vertical-align: middle;width: 12%;position: sticky;">COA Name</th>
                <th style="text-align: center;vertical-align: middle;width: 13%;">Category 1</th>
                <th style="text-align: center;vertical-align: middle;width: 13%;">Category 2</th>
                <th style="text-align: center;vertical-align: middle;width: 13%;">Category 3</th>
                <th style="text-align: center;vertical-align: middle;width: 13%;">Category 4</th>
                <th style="text-align: center;vertical-align: middle;width: 7%;">Beginning Balance</th>
                <th style="text-align: center;vertical-align: middle;width: 7%;">Debit</th>
                <th style="text-align: center;vertical-align: middle;width: 7%;">Credit</th>
                <th style="text-align: center;vertical-align: middle;width: 7%;">Ending Balance</th>                                                       
            </tr>
        </thead>

        <tbody>
            <?php
            $nama_type ='';
            $Status = '';
            $start_date ='';
            $end_date ='';
            $date_now = date("Y-m-d");
            $tanggal_awal = date("Y-m-d",strtotime($date_now ));
            $tanggal_akhir = date("Y-m-d",strtotime($date_now ));
            $bulan_awal = date("m",strtotime($date_now));
            $bulan_akhir = date("m",strtotime($date_now));  
            $tahun_awal = date("Y",strtotime($date_now));
            $tahun_akhir = date("Y",strtotime($date_now));
            $kata_awal = date("M",strtotime($date_now));
            $tengah = '_';
            $kata_akhir = date("Y",strtotime($date_now));
            $kata_filter = $kata_awal . $tengah . $kata_akhir;
            $kata_filter2 = $kata_awal . $tengah . $kata_akhir;                 
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $nama_type = isset($_POST['nama_type']) ? $_POST['nama_type']: null; 
                $Status = isset($_POST['Status']) ? $_POST['Status']: null; 
                $start_date = date("d-m-Y",strtotime($_POST['start_date']));
                $end_date = date("d-m-Y",strtotime($_POST['end_date'])); 

                $tanggal_awal = date("Y-m-d",strtotime($_POST['start_date']));
                $tanggal_akhir = date("Y-m-d",strtotime($_POST['end_date'])); 

                $bulan_awal = date("m",strtotime($_POST['start_date']));
                $bulan_akhir = date("m",strtotime($_POST['end_date']));  
                $tahun_awal = date("Y",strtotime($_POST['start_date']));
                $tahun_akhir = date("Y",strtotime($_POST['end_date']));

                $kata_awal = date("M",strtotime($_POST['start_date']));
                $tengah = '_';
                $kata_akhir = date("Y",strtotime($_POST['start_date']));
                $kata_filter = $kata_awal . $tengah . $kata_akhir;

                $kata_awal2 = date("M",strtotime($_POST['end_date']));
                $tengah2 = '_';
                $kata_akhir2 = date("Y",strtotime($_POST['end_date']));
                $kata_filter2 = $kata_awal2 . $tengah2 . $kata_akhir2;

    // echo  $tanggal_awal;
    // echo  $tanggal_akhir;
    // echo  $tahun_akhir;            
            }
            if(empty($start_date) and empty($end_date)){
             $sql = mysqli_query($conn2,"    
                select * from 
                (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
                left join
                (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,id_ctg5 from mastercoa_sb order by no_coa asc) coa
                on coa.no_coa = saldo.nocoa
                left join
                (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
                jnl on jnl.coa_no = coa.no_coa order by no_coa asc");
         }
         else{
            $sql = mysqli_query($conn2,"select * from 
                (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
                left join
                (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,id_ctg5 from mastercoa_sb order by no_coa asc) coa
                on coa.no_coa = saldo.nocoa
                left join
                (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
                jnl on jnl.coa_no = coa.no_coa order by no_coa asc");
        }


        echo '<input type="hidden" style="font-size: 12px;" class="form-control" id="to_saldo" name="to_saldo" 
        value="'.$kata_filter2.'">';
        $saldoakhir = 0;

        if($tanggal_akhir < $tanggal_awal){
            $message = "Mohon Masukan Tanggal Filter Yang Benar";
            echo "<script type='text/javascript'>alert('$message');</script>";
        }
        else{
            while($row = mysqli_fetch_array($sql)){
                $beg_balance = isset($row['saldo']) ? $row['saldo'] : 0;
                $credit_idr = isset($row['credit_idr']) ? $row['credit_idr'] : 0;
                $debit_idr = isset($row['debit_idr']) ? $row['debit_idr'] : 0;
                $saldoakhir = ($beg_balance + $debit_idr) - $credit_idr;
                $balance_idr = isset($row['balance_idr']) ? $row['balance_idr'] : null;

                if ($balance_idr == 'NB') {
                   $warna = '#FF7F50';
               }else{
                 $warna = 'grey';
             }
        // if ($reff_date == '0000-00-00' || $reff_date == '1970-01-01' || $reff_date == '') {
        //     $Reffdate = '-'; 
        // }else{
        //     $Reffdate = date("d-M-Y",strtotime($reff_date));
        // }
        //background-color:'.$warna.';

             echo '<tr style="font-size:11px;text-align:center;">
             <td style="width:5px;display: none;"><input type="checkbox" id="select" name="select[]" value="" <?php if(in_array("1",$_POST[select])) echo "checked=checked";? checked></td>
             <td style="text-align : center;" value = "'.$row['no_coa'].'">'.$row['no_coa'].'</td>
             <td style="text-align : left;" value = "'.$row['nama_coa'].'">'.$row['nama_coa'].'</td>
             <td style="text-align : left;" value = "'.$row['ind_categori1'].'">'.$row['ind_categori1'].'</td>
             <td style="text-align : left;" value = "'.$row['ind_categori2'].'">'.$row['ind_categori2'].'</td>
             <td style="text-align : left;" value = "'.$row['ind_categori3'].'">'.$row['ind_categori3'].'</td>
             <td style="text-align : left;" value = "'.$row['ind_categori6'].'">'.$row['ind_categori6'].'</td>
             <td style=" text-align : right;" value="'.$beg_balance.'">'.number_format($beg_balance,2).'</td>
             <td style=" text-align : right;" value="'.$debit_idr.'">'.number_format($debit_idr,2).'</td>
             <td style=" text-align : right;" value="'.$credit_idr.'">'.number_format($credit_idr,2).'</td>
             <td style=" text-align : right;" value="'.$saldoakhir.'">'.number_format($saldoakhir,2).'</td>

             ';
             echo '</tr>';
         }
     }
     ?>
 </tbody>                    
</table>
</div>
</form>
</div>

<div id="neraca" class="tabcontent">
  <?php
        // $status = isset($_POST['status']) ? $_POST['status']: null;
  $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
  $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;
  $tanggal_awal = date("Y-m-d",strtotime($start_date));
  $tanggal_akhir = date("Y-m-d",strtotime($end_date)); 
  $tanggal1 = isset($tanggal_awal) ? $tanggal_awal : 0;
  $tanggal2 = isset($tanggal_akhir) ? $tanggal_akhir : 0;
  $kata_awal = date("M",strtotime($start_date));
  $tengah = '_';
  $kata_akhir = date("Y",strtotime($start_date));
  $kata_filter = $kata_awal . $tengah . $kata_akhir;

  if($tanggal2 < $tanggal1){
    echo "";
}
else{

    echo '<a style="padding-right: 10px;" target="_blank" href="../acct/ekspor_neraca_ytd.php?start_date='.$start_date.' && end_date='.$end_date.' && kata_filter='.$kata_filter.'"><button type="button" class="btn btn-success " ><i class="fa fa-file-excel-o" aria-hidden="true" style="padding-right: 10px; padding-left: 5px;font-size: 14px;color: #fff;text-shadow: 1px 1px 1px #000"> Excel </i></button></a>

    ';
}
?>
</br>

<div style="border:1px solid black;margin-top: 5px;">        
    <table style="font-size: 16px; margin:auto" border="0" role="grid" cellspacing="0" width="64%">
        <tr>
            <th colspan="4" style="text-align: center;vertical-align: middle;width: 64%;">PT NIRWANA ALABARE GARMENT</th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center;vertical-align: middle;width: 100%;">NERACA</th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center;vertical-align: middle;width: 100%;">Per 31 <?php
            $enddate = date("F Y");
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $enddate = date("F Y",strtotime($_POST['end_date'])); 
            } echo $enddate; ?></th>
        </tr>                   
    </table>
    <div class="horizontal"></div>
    <table border="0" style="font-size: 14px;margin:auto" role="grid" cellspacing="0" width="64%">
        <tr style="line-height: 40px;">
            <th style="text-align: left;vertical-align: middle;width: 29%;">AKTIVA</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;">Ref</td>
            <td style="text-align: center;vertical-align: middle;width: 15%;"></td>
            <td style="text-align: center;vertical-align: middle;width: 15%;"></td> 
        </tr>
        <tr>
            <th style="text-align: left;vertical-align: middle;width: 29%;">AKTIVA LANCAR</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td style="text-align: center;vertical-align: middle;width: 15%;"></td>
            <td style="text-align: center;vertical-align: middle;width: 15%;"></td> 
        </tr>
        <?php
        $nama_type ='';
        $Status = '';
        $start_date ='';
        $end_date ='';
        $date_now = date("Y-m-d");
        $tanggal_awal = date("Y-m-d",strtotime($date_now ));
        $tanggal_akhir = date("Y-m-d",strtotime($date_now ));
        $bulan_awal = date("m",strtotime($date_now));
        $bulan_akhir = date("m",strtotime($date_now));  
        $tahun_awal = date("Y",strtotime($date_now));
        $tahun_akhir = date("Y",strtotime($date_now));
        $kata_awal = date("M",strtotime($date_now));
        $tengah = '_';
        $kata_akhir = date("Y",strtotime($date_now));
        $kata_filter = $kata_awal . $tengah . $kata_akhir;
        $kata_filter2 = $kata_awal . $tengah . $kata_akhir;                 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_type = isset($_POST['nama_type']) ? $_POST['nama_type']: null; 
            $Status = isset($_POST['Status']) ? $_POST['Status']: null; 
            $start_date = date("d-m-Y",strtotime($_POST['start_date']));
            $end_date = date("d-m-Y",strtotime($_POST['end_date'])); 

            $tanggal_awal = date("Y-m-d",strtotime($_POST['start_date']));
            $tanggal_akhir = date("Y-m-d",strtotime($_POST['end_date'])); 

            $bulan_awal = date("m",strtotime($_POST['start_date']));
            $bulan_akhir = date("m",strtotime($_POST['end_date']));  
            $tahun_awal = date("Y",strtotime($_POST['start_date']));
            $tahun_akhir = date("Y",strtotime($_POST['end_date']));

            $kata_awal = date("M",strtotime($_POST['start_date']));
            $tengah = '_';
            $kata_akhir = date("Y",strtotime($_POST['start_date']));
            $kata_filter = $kata_awal . $tengah . $kata_akhir;

            $kata_awal2 = date("M",strtotime($_POST['end_date']));
            $tengah2 = '_';
            $kata_akhir2 = date("Y",strtotime($_POST['end_date']));
            $kata_filter2 = $kata_awal2 . $tengah2 . $kata_akhir2;


        }

        $sql = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'AKTIVA LANCAR') a INNER JOIN
            (select ind_categori2,ind_categori6,(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori6,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa GROUP BY ind_categori6 order by no_coa asc) a) b on b.ind_categori6 = a.sub_kategori order by id asc");

        $sql2 = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'AKTIVA TETAP') a INNER JOIN
            (select ind_categori2,ind_categori6,(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori6,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa GROUP BY ind_categori6 order by no_coa asc) a) b on b.ind_categori6 = a.sub_kategori order by id asc");


        $saldoakhir = 0;

        if($tanggal_akhir < $tanggal_awal){
            $message = "Mohon Masukan Tanggal Filter Yang Benar";
            echo "<script type='text/javascript'>alert('$message');</script>";
        }
        else{
            $no = 01;
            $total_aktiva_lancar = 0;
            while($row = mysqli_fetch_array($sql)){
                $aktiva_lancar = isset($row['total']) ? $row['total'] : 0;
                if ($aktiva_lancar > 0) {
                    $aktiva_lancar = number_format($aktiva_lancar,2);
                }else{
                    $aktiva_lancar = '('.number_format(abs($aktiva_lancar),2).')';
                }

                $total_aktiva_lancar += isset($row['total']) ? $row['total'] : 0;
                if ($total_aktiva_lancar > 0) {
                    $total_aktiva_lancar_ = number_format($total_aktiva_lancar,2);
                }else{
                    $total_aktiva_lancar_ = '('.number_format(abs($total_aktiva_lancar),2).')';
                }

                echo '<tr>
                <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">'.$row['sub_kategori'].'</td>
                <td style="text-align: center;vertical-align: middle;width: 5%;">'.$row['ref'].'</td>
                <td style="text-align: right;vertical-align: middle;width: 15%;">'.$aktiva_lancar.'</td>
                <td style="text-align: center;vertical-align: middle;width: 15%;"></td> 
                </tr>';
            }
            echo '<tr style="line-height: 40px;">
            <th style="text-align: left;vertical-align: middle;width: 29%;">TOTAL AKTIVA LANCAR</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td style="text-align: right;vertical-align: middle;width: 15%;border-top:3px solid #000000;"></td>
            <th style="text-align: center;vertical-align: middle;width: 15%;">'.$total_aktiva_lancar_.'</th> 
            </tr>
            <tr style="line-height: 40px;">
            <th style="text-align: left;vertical-align: middle;width: 29%;">AKTIVA TETAP</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;">07.</td>
            <td style="text-align: right;vertical-align: middle;width: 15%;"></td>
            <th style="text-align: center;vertical-align: middle;width: 15%;"></th> 
            </tr>';

            $total_aktiva_tetap = 0;
            while($row2 = mysqli_fetch_array($sql2)){
                $aktiva_tetap = isset($row2['total']) ? $row2['total'] : 0;
                if ($aktiva_tetap > 0) {
                    $aktiva_tetap = number_format($aktiva_tetap,2);
                }else{
                    $aktiva_tetap = '('.number_format(abs($aktiva_tetap),2).')';
                }

                $total_aktiva_tetap += isset($row2['total']) ? $row2['total'] : 0;
                if ($total_aktiva_tetap > 0) {
                    $total_aktiva_tetap_ = number_format($total_aktiva_tetap,2);
                }else{
                    $total_aktiva_tetap_ = '('.number_format(abs($total_aktiva_tetap),2).')';
                }

                echo '<tr>
                <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">'.$row2['sub_kategori'].'</td>
                <td style="text-align: center;vertical-align: middle;width: 5%;">'.$row2['ref'].'</td>
                <td style="text-align: right;vertical-align: middle;width: 15%;">'.$aktiva_tetap.'</td>
                <td style="text-align: center;vertical-align: middle;width: 15%;"></td> 
                </tr>';
            }
            echo '<tr style="line-height: 40px;">
            <th style="text-align: left;vertical-align: middle;width: 29%;">TOTAL AKTIVA LANCAR</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td style="text-align: right;vertical-align: middle;width: 15%;border-top:3px solid #000000;"></td>
            <th style="text-align: center;vertical-align: middle;width: 15%;">'.$total_aktiva_tetap_.'</th> 
            </tr>';
            $total_aktiva = $total_aktiva_lancar + $total_aktiva_tetap;
            if ($total_aktiva > 0) {
                $total_aktiva_ = number_format($total_aktiva,2);
            }else{
                $total_aktiva_ = '('.number_format(abs($total_aktiva),2).')';
            }
            echo '<tr>
            <th style="text-align: left;vertical-align: middle;width: 29%;">TOTAL AKTIVA</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td style="text-align: right;vertical-align: middle;width: 15%;"></td>
            <th style="text-align: center;vertical-align: middle;width: 15%;border-top:3px solid #000000;border-bottom:3px double #000000;">'.$total_aktiva_.'</th> 
            </tr>'; 
        }
        ?>
    </table>
    <br>
    <div class="horizontal"></div>
    <table border="0" style="font-size: 14px;margin:auto" role="grid" cellspacing="0" width="64%">
        <tr style="line-height: 40px;">
            <th style="text-align: left;vertical-align: middle;width: 29%;">KEWAJIBAN DAN EKUITAS</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td style="text-align: center;vertical-align: middle;width: 15%;"></td>
            <td style="text-align: center;vertical-align: middle;width: 15%;"></td> 
        </tr>
        <tr>
            <th style="text-align: left;vertical-align: middle;width: 29%;">KEWAJIBAN</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td style="text-align: center;vertical-align: middle;width: 15%;"></td>
            <td style="text-align: center;vertical-align: middle;width: 15%;"></td> 
        </tr>
        <?php
        $nama_type ='';
        $Status = '';
        $start_date ='';
        $end_date ='';
        $date_now = date("Y-m-d");
        $tanggal_awal = date("Y-m-d",strtotime($date_now ));
        $tanggal_akhir = date("Y-m-d",strtotime($date_now ));
        $bulan_awal = date("m",strtotime($date_now));
        $bulan_akhir = date("m",strtotime($date_now));  
        $tahun_awal = date("Y",strtotime($date_now));
        $tahun_akhir = date("Y",strtotime($date_now));
        $kata_awal = date("M",strtotime($date_now));
        $tengah = '_';
        $kata_akhir = date("Y",strtotime($date_now));
        $kata_filter = $kata_awal . $tengah . $kata_akhir;
        $kata_filter2 = $kata_awal . $tengah . $kata_akhir;                 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_type = isset($_POST['nama_type']) ? $_POST['nama_type']: null; 
            $Status = isset($_POST['Status']) ? $_POST['Status']: null; 
            $start_date = date("d-m-Y",strtotime($_POST['start_date']));
            $end_date = date("d-m-Y",strtotime($_POST['end_date'])); 

            $tanggal_awal = date("Y-m-d",strtotime($_POST['start_date']));
            $tanggal_akhir = date("Y-m-d",strtotime($_POST['end_date'])); 

            $bulan_awal = date("m",strtotime($_POST['start_date']));
            $bulan_akhir = date("m",strtotime($_POST['end_date']));  
            $tahun_awal = date("Y",strtotime($_POST['start_date']));
            $tahun_akhir = date("Y",strtotime($_POST['end_date']));

            $kata_awal = date("M",strtotime($_POST['start_date']));
            $tengah = '_';
            $kata_akhir = date("Y",strtotime($_POST['start_date']));
            $kata_filter = $kata_awal . $tengah . $kata_akhir;

            $kata_awal2 = date("M",strtotime($_POST['end_date']));
            $tengah2 = '_';
            $kata_akhir2 = date("Y",strtotime($_POST['end_date']));
            $kata_filter2 = $kata_awal2 . $tengah2 . $kata_akhir2;


        }

        $sql3 = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'KEWAJIBAN') a INNER JOIN
            (select ind_categori2,ind_categori6,(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori6,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa GROUP BY ind_categori6 order by no_coa asc) a) b on b.ind_categori6 = a.sub_kategori order by id asc");

        $sql4 = mysqli_query($conn2,"select * from (select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'EKUITAS' and sub_kategori != 'LABA / (RUGI) TAHUN BERJALAN') a INNER JOIN
            (select ind_categori2,ind_categori6,(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori6,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa GROUP BY ind_categori6 order by no_coa asc) a) b on b.ind_categori6 = a.sub_kategori order by id asc) a 
            UNION
            select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and sub_kategori = 'LABA / (RUGI) TAHUN BERJALAN' and kategori = 'EKUITAS') a  JOIN
            (select ind_categori2,ind_categori6,sum(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori6,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa WHERE ind_categori1 = 'LAPORAN LABA RUGI' GROUP BY ind_categori6 order by no_coa asc) a) b  order by id asc");


        $saldoakhir = 0;

        if($tanggal_akhir < $tanggal_awal){
            $message = "Mohon Masukan Tanggal Filter Yang Benar";
            echo "<script type='text/javascript'>alert('$message');</script>";
        }
        else{
            $no = 01;
            $total_kewajiban = 0;
            $total_kewajiban_ = 0;
            while($row3 = mysqli_fetch_array($sql3)){
                $kewajiban = isset($row3['total']) ? $row3['total'] : 0;
                if ($kewajiban > 0) {
                    $kewajiban = number_format($kewajiban,2);
                }else{
                    $kewajiban = '('.number_format(abs($kewajiban),2).')';
                }

                $total_kewajiban += isset($row3['total']) ? $row3['total'] : 0;
                if ($total_kewajiban > 0) {
                    $total_kewajiban_ = number_format($total_kewajiban,2);
                }else{
                    $total_kewajiban_ = '('.number_format(abs($total_kewajiban),2).')';
                }

                echo '<tr>
                <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">'.$row3['sub_kategori'].'</td>
                <td style="text-align: center;vertical-align: middle;width: 5%;">'.$row3['ref'].'</td>
                <td style="text-align: right;vertical-align: middle;width: 15%;">'.$kewajiban.'</td>
                <td style="text-align: center;vertical-align: middle;width: 15%;"></td> 
                </tr>';
            }
            echo '<tr style="line-height: 40px;">
            <th style="text-align: left;vertical-align: middle;width: 29%;">TOTAL KEWAJIBAN</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td style="text-align: right;vertical-align: middle;width: 15%;border-top:3px solid #000000;"></td>
            <th style="text-align: center;vertical-align: middle;width: 15%;">'.$total_kewajiban_.'</th> 
            </tr>
            <tr style="line-height: 40px;">
            <th style="text-align: left;vertical-align: middle;width: 29%;">EKUITAS</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;">13.</td>
            <td style="text-align: right;vertical-align: middle;width: 15%;"></td>
            <th style="text-align: center;vertical-align: middle;width: 15%;"></th> 
            </tr>';

            $total_ekuitas = 0;
            $total_ekuitas_ = 0;
            while($row4 = mysqli_fetch_array($sql4)){
                $ekuitas = isset($row4['total']) ? $row4['total'] : 0;
                if ($ekuitas > 0) {
                    $ekuitas = number_format($ekuitas,2);
                }else{
                    $ekuitas = '('.number_format(abs($ekuitas),2).')';
                }

                $total_ekuitas += isset($row4['total']) ? $row4['total'] : 0;
                if ($total_ekuitas > 0) {
                    $total_ekuitas_ = number_format($total_ekuitas,2);
                }else{
                    $total_ekuitas_ = '('.number_format(abs($total_ekuitas),2).')';
                }

                echo '<tr>
                <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">'.$row4['sub_kategori'].'</td>
                <td style="text-align: center;vertical-align: middle;width: 5%;">'.$row4['ref'].'</td>
                <td style="text-align: right;vertical-align: middle;width: 15%;">'.$ekuitas.'</td>
                <td style="text-align: center;vertical-align: middle;width: 15%;"></td> 
                </tr>';
            }
            echo '<tr style="line-height: 40px;">
            <th style="text-align: left;vertical-align: middle;width: 29%;">TOTAL EKUITAS</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td style="text-align: right;vertical-align: middle;width: 15%;border-top:3px solid #000000;"></td>
            <th style="text-align: center;vertical-align: middle;width: 15%;">'.$total_ekuitas_.'</th> 
            </tr>';
            $total_kewajiban_ekuitas = $total_kewajiban + $total_ekuitas;
            if ($total_kewajiban_ekuitas > 0) {
                $total_kewajiban_ekuitas_ = number_format($total_kewajiban_ekuitas,2);
            }else{
                $total_kewajiban_ekuitas_ = '('.number_format(abs($total_kewajiban_ekuitas),2).')';
            }
            echo '<tr>
            <th style="text-align: left;vertical-align: middle;width: 29%;">TOTAL KEWAJIBAN DAN EKUITAS</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td style="text-align: right;vertical-align: middle;width: 15%;"></td>
            <th style="text-align: center;vertical-align: middle;width: 15%;border-top:3px solid #000000;border-bottom:3px double #000000;">'.$total_kewajiban_ekuitas_.'</th> 
            </tr>'; 
        }
        ?>
    </table>
    <br>
</div> 
</div>

<div id="arus-kas" class="tabcontent">
  <?php
        // $status = isset($_POST['status']) ? $_POST['status']: null;
  $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
  $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;
  $tanggal_awal = date("Y-m-d",strtotime($start_date));
  $tanggal_akhir = date("Y-m-d",strtotime($end_date)); 
  $tanggal1 = isset($tanggal_awal) ? $tanggal_awal : 0;
  $tanggal2 = isset($tanggal_akhir) ? $tanggal_akhir : 0;
  $kata_awal = date("M",strtotime($start_date));
  $tengah = '_';
  $kata_akhir = date("Y",strtotime($start_date));
  $kata_filter = $kata_awal . $tengah . $kata_akhir;

  if($tanggal2 < $tanggal1){
    echo "";
}
else{

    echo '<a style="padding-right: 10px;" target="_blank" href="../acct/ekspor_arus_kas.php?start_date='.$start_date.' && end_date='.$end_date.' && kata_filter='.$kata_filter.'"><button type="button" class="btn btn-success " ><i class="fa fa-file-excel-o" aria-hidden="true" style="padding-right: 10px; padding-left: 5px;font-size: 14px;color: #fff;text-shadow: 1px 1px 1px #000"> Excel </i></button></a>

    ';
}
?>

<div style="border:1px solid black;margin-top: 5px;">        
    <table style="font-size: 16px; margin:auto" border="0" role="grid" cellspacing="0" width="78%">
        <tr>
            <th colspan="4" style="text-align: center;vertical-align: middle;width: 64%;">PT NIRWANA ALABARE GARMENT</th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center;vertical-align: middle;width: 100%;">LAPORAN ARUS KAS</th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center;vertical-align: middle;width: 100%;">Per 01 <?php
            $enddate = date("F Y");
            $startdate = date("F Y");
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $enddate = date("F Y",strtotime($_POST['end_date'])); 
                $startdate = date("F Y",strtotime($_POST['start_date'])); 
            } echo $startdate; echo ' S.D 31'; echo $enddate; ?></th>
        </tr>                   
    </table>
    <div class="horizontal3"></div>
    <table border="0" style="font-size: 14px;margin:auto" role="grid" cellspacing="0" width="78%">
        <tr style="line-height: 40px;">
            <th style="text-align: left;vertical-align: middle;width: 29%;">ARUS KAS DARI AKTIVITAS OPERASI</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
        </tr>
        <?php
        $nama_type ='';
        $Status = '';
        $start_date ='';
        $end_date ='';
        $date_now = date("Y-m-d");
        $tanggal_awal = date("Y-m-d",strtotime($date_now ));
        $tanggal_akhir = date("Y-m-d",strtotime($date_now ));
        $bulan_awal = date("m",strtotime($date_now));
        $bulan_akhir = date("m",strtotime($date_now));  
        $tahun_awal = date("Y",strtotime($date_now));
        $tahun_akhir = date("Y",strtotime($date_now));
        $kata_awal = date("M",strtotime($date_now));
        $tengah = '_';
        $kata_akhir = date("Y",strtotime($date_now));
        $kata_filter = $kata_awal . $tengah . $kata_akhir;
        $kata_filter2 = $kata_awal . $tengah . $kata_akhir;                 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_type = isset($_POST['nama_type']) ? $_POST['nama_type']: null; 
            $Status = isset($_POST['Status']) ? $_POST['Status']: null; 
            $start_date = date("d-m-Y",strtotime($_POST['start_date']));
            $end_date = date("d-m-Y",strtotime($_POST['end_date'])); 

            $tanggal_awal = date("Y-m-d",strtotime($_POST['start_date']));
            $tanggal_akhir = date("Y-m-d",strtotime($_POST['end_date'])); 

            $bulan_awal = date("m",strtotime($_POST['start_date']));
            $bulan_akhir = date("m",strtotime($_POST['end_date']));  
            $tahun_awal = date("Y",strtotime($_POST['start_date']));
            $tahun_akhir = date("Y",strtotime($_POST['end_date']));

            $kata_awal = date("M",strtotime($_POST['start_date']));
            $tengah = '_';
            $kata_akhir = date("Y",strtotime($_POST['start_date']));
            $kata_filter = $kata_awal . $tengah . $kata_akhir;

            $kata_awal2 = date("M",strtotime($_POST['end_date']));
            $tengah2 = '_';
            $kata_akhir2 = date("Y",strtotime($_POST['end_date']));
            $kata_filter2 = $kata_awal2 . $tengah2 . $kata_akhir2;


        }

        $sql1 = mysqli_query($conn2,"select sum(total) total from (SELECT * FROM(select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori in ('PENDAPATAN USAHA','PENDAPATAN / (BEBAN) LAIN-LAIN')) a INNER JOIN
            (select ind_categori2,ind_categori6,-(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori6,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa GROUP BY ind_categori6 order by no_coa asc) a) b on b.ind_categori6 = a.sub_kategori order by id asc) a
            UNION
            SELECT * FROM(select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori in ('HARGA POKOK PENJUALAN')) a INNER JOIN
            (select ind_categori2,ind_categori6,-(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori6,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa GROUP BY ind_categori2 order by no_coa asc) a) b on b.ind_categori2 = a.sub_kategori order by id asc) a
            UNION
            select * from (select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'BIAYA PENJUALAN') a INNER JOIN
            (select ind_categori2,ind_categori6,-(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori6,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa WHERE ind_categori2 = 'BIAYA PENJUALAN' GROUP BY ind_categori6 order by no_coa asc) a) b on b.ind_categori6 = a.sub_kategori order by id asc) a                                          
            UNION 
            select * from (select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'BIAYA ADMINISTRASI & UMUM') a INNER JOIN
            (select ind_categori2,ind_categori6,-(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori6,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa WHERE ind_categori2 = 'BIAYA ADMINISTRASI & UMUM' GROUP BY ind_categori6 order by no_coa asc) a) b on b.ind_categori6 = a.sub_kategori order by id asc) a) a");


$sql2 = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'AKTIVITAS OPERASI') a INNER JOIN
    (select ind_indirect,round(saldo - (saldo + debit_idr - credit_idr),2) total from (select ind_indirect,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
    (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
    left join
    (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,id_ctg5,ind_indirect from mastercoa_sb order by no_coa asc) coa
    on coa.no_coa = saldo.nocoa
    left join
    (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
    jnl on jnl.coa_no = coa.no_coa GROUP BY ind_indirect order by no_coa asc) a) b on b.ind_indirect = a.sub_kategori order by id asc");

$sql3 = mysqli_query($conn2,"select ref,sub_kategori,COALESCE(total,0) total from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'AKTIVITAS INVESTASI') a LEFT JOIN
    (select ind_indirect,round(saldo - (saldo + debit_idr - credit_idr),2) total from (select ind_indirect,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
    (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
    left join
    (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,id_ctg5,ind_indirect from mastercoa_sb order by no_coa asc) coa
    on coa.no_coa = saldo.nocoa
    left join
    (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
    jnl on jnl.coa_no = coa.no_coa GROUP BY ind_indirect order by no_coa asc) a) b on b.ind_indirect = a.sub_kategori order by id asc");

$sql4 = mysqli_query($conn2,"select ref,sub_kategori,COALESCE(total,0) total from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'AKTIVITAS PENDANAAN') a LEFT JOIN
    (select ind_indirect,round(saldo - (saldo + debit_idr - credit_idr),2) total from (select ind_indirect,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
    (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
    left join
    (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,id_ctg5,ind_indirect from mastercoa_sb order by no_coa asc) coa
    on coa.no_coa = saldo.nocoa
    left join
    (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
    jnl on jnl.coa_no = coa.no_coa GROUP BY ind_indirect order by no_coa asc) a) b on b.ind_indirect = a.sub_kategori order by id asc");


$sql5 = mysqli_query($conn2,"select ind_categori6,round(sum(saldo),2) total from (select ind_categori6,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
    (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
    left join
    (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,id_ctg5,ind_indirect from mastercoa_sb order by no_coa asc) coa
    on coa.no_coa = saldo.nocoa
    left join
    (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
    jnl on jnl.coa_no = coa.no_coa where ind_categori6 = 'KAS DAN SETARA KAS' GROUP BY ind_indirect order by no_coa asc) a");


$saldoakhir = 0;

if($tanggal_akhir < $tanggal_awal){
    $message = "Mohon Masukan Tanggal Filter Yang Benar";
    echo "<script type='text/javascript'>alert('$message');</script>";
}
else{
    $no = 01;

    $total_laba_rugi_tb = 0;
    $row1 = mysqli_fetch_array($sql1);
    $laba_rugi_tb = isset($row1['total']) ? $row1['total'] : 0;
    if ($laba_rugi_tb > 0) {
        $laba_rugi_tb = number_format($laba_rugi_tb,2);
    }else{
        $laba_rugi_tb = '('.number_format(abs($laba_rugi_tb),2).')';
    }

    $total_laba_rugi_tb += isset($row1['total']) ? $row1['total'] : 0;
    if ($total_laba_rugi_tb > 0) {
        $total_laba_rugi_tb_ = number_format($total_laba_rugi_tb,2);
    }else{
        $total_laba_rugi_tb_ = '('.number_format(abs($total_laba_rugi_tb),2).')';
    }

    echo '<tr>
    <th style="text-align: left;vertical-align: middle;width: 29%;">LABA RUGI TAHUN BERJALAN</th>
    <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
    <td style="text-align: right;vertical-align: middle;width: 10%;">'.$laba_rugi_tb.'</td> 
    </tr>';
}
echo '<tr>
<th style="text-align: left;vertical-align: middle;width: 29%;">PENYESUAIAN UNTUK :</th>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td style="text-align: center;vertical-align: middle;width: 2%;"></td>
<td style="text-align: right;vertical-align: middle;width: 10%;"></td> 
</tr>';

$total_aktivitas_operasional = 0;
while($row2 = mysqli_fetch_array($sql2)){
    $aktivitas_operasional = isset($row2['total']) ? $row2['total'] : 0;
    if ($aktivitas_operasional > 0) {
        $aktivitas_operasional = number_format($aktivitas_operasional,0);
    }else{
        $aktivitas_operasional = '('.number_format(abs($aktivitas_operasional),0).')';
    }

    $total_aktivitas_operasional += isset($row2['total']) ? $row2['total'] : 0;
    if ($total_aktivitas_operasional > 0) {
        $total_aktivitas_operasional_ = number_format($total_aktivitas_operasional,0);
    }else{
        $total_aktivitas_operasional_ = '('.number_format(abs($total_aktivitas_operasional),0).')';
    }

    echo '<tr>
    <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">'.$row2['sub_kategori'].'</td>
    <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
    <td style="text-align: right;vertical-align: middle;width: 10%;">'.$aktivitas_operasional.'</td> 
    </tr>';
}

$untuk_aktivitas_operasi = $total_laba_rugi_tb + $total_aktivitas_operasional;
if ($untuk_aktivitas_operasi > 0) {
    $untuk_aktivitas_operasi = number_format($untuk_aktivitas_operasi,0);
}else{
    $untuk_aktivitas_operasi = '('.number_format(abs($untuk_aktivitas_operasi),0).')';
}

echo '<tr style="line-height: 40px;">
<th style="text-align: left;vertical-align: middle;width: 29%;">ARUS KAS BERSIH YANG DIPEROLEH ( DIGUNAKAN ) UNTUK AKTIVITAS OPERASI</th>
<td style="text-align: center;vertical-align: middle;width: 2%;"></td>
<td style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</td>
<td style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$untuk_aktivitas_operasi.'</td> 
</tr>
<tr>
<th style="text-align: left;vertical-align: middle;width: 29%;">ARUS KAS DARI AKTIVITAS INVESTASI</th>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td style="text-align: center;vertical-align: middle;width: 2%;"></td>
<td style="text-align: right;vertical-align: middle;width: 10%;"></td> 
</tr>';

$total_aktivitas_investasi = 0;
while($row3 = mysqli_fetch_array($sql3)){
    $aktivitas_investasi = isset($row3['total']) ? $row3['total'] : 0;
    if ($aktivitas_investasi > 0) {
        $aktivitas_investasi = number_format($aktivitas_investasi,0);
    }else{
        $aktivitas_investasi = '('.number_format(abs($aktivitas_investasi),0).')';
    }

    $total_aktivitas_investasi += isset($row3['total']) ? $row3['total'] : 0;
    if ($total_aktivitas_investasi > 0) {
        $total_aktivitas_investasi_ = number_format($total_aktivitas_investasi,0);
    }else{
        $total_aktivitas_investasi_ = '('.number_format(abs($total_aktivitas_investasi),0).')';
    }

    echo '<tr>
    <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">'.$row3['sub_kategori'].'</td>
    <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
    <td style="text-align: right;vertical-align: middle;width: 10%;">'.$aktivitas_investasi.'</td> 
    </tr>';
}

echo '<tr style="line-height: 40px;">
<th style="text-align: left;vertical-align: middle;width: 29%;">ARUS KAS BERSIH YANG DIPEROLEH ( DIGUNAKAN ) UNTUK AKTIVITAS INVESTASI</th>
<td style="text-align: center;vertical-align: middle;width: 2%;"></td>
<td style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</td>
<td style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_aktivitas_investasi_.'</td> 
</tr>
<tr>
<th style="text-align: left;vertical-align: middle;width: 29%;">ARUS KAS DARI AKTIVITAS PENDANAAN</th>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td style="text-align: center;vertical-align: middle;width: 2%;"></td>
<td style="text-align: right;vertical-align: middle;width: 10%;"></td> 
</tr>';

$total_aktivitas_pendanaan = 0;
while($row4 = mysqli_fetch_array($sql4)){
    $aktivitas_pendanaan = isset($row4['total']) ? $row4['total'] : 0;
    if ($aktivitas_pendanaan > 0) {
        $aktivitas_pendanaan = number_format($aktivitas_pendanaan,0);
    }else{
        $aktivitas_pendanaan = '('.number_format(abs($aktivitas_pendanaan),0).')';
    }

    $total_aktivitas_pendanaan += isset($row4['total']) ? $row4['total'] : 0;
    if ($total_aktivitas_pendanaan > 0) {
        $total_aktivitas_pendanaan_ = number_format($total_aktivitas_pendanaan,0);
    }else{
        $total_aktivitas_pendanaan_ = '('.number_format(abs($total_aktivitas_pendanaan),0).')';
    }

    echo '<tr>
    <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">'.$row4['sub_kategori'].'</td>
    <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
    <td style="text-align: right;vertical-align: middle;width: 10%;">'.$aktivitas_pendanaan.'</td> 
    </tr>';
}

$bersih_kas_setarakas = $total_laba_rugi_tb + $total_aktivitas_operasional + $total_aktivitas_investasi + $total_aktivitas_pendanaan;
if ($bersih_kas_setarakas > 0) {
    $bersih_kas_setarakas = number_format($bersih_kas_setarakas,0);
}else{
    $bersih_kas_setarakas = '('.number_format(abs($bersih_kas_setarakas),0).')';
}
echo '<tr style="line-height: 40px;">
<th style="text-align: left;vertical-align: middle;width: 29%;">ARUS KAS BERSIH YANG DIPEROLEH ( DIGUNAKAN ) UNTUK AKTIVITAS PENDANAAN</th>
<td style="text-align: center;vertical-align: middle;width: 2%;"></td>
<td style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</td>
<td style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_aktivitas_pendanaan_.'</td> 
</tr>
<tr style="line-height: 40px;">
<th style="text-align: left;vertical-align: middle;width: 29%;">KENAIKAN / (PENURUNAN) BERSIH KAS DAN SETARA KAS</th>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<th style="text-align: center;vertical-align: middle;width: 2%;">Rp.</th>
<th style="text-align: right;vertical-align: middle;width: 10%;">'.$bersih_kas_setarakas.'</th> 
</tr>';

$total_kas_awal = 0;
$row5 = mysqli_fetch_array($sql5);
$kas_awal = isset($row5['total']) ? $row5['total'] : 0;
if ($kas_awal > 0) {
    $total_kas_awal = number_format($kas_awal,0);
}else{
    $total_kas_awal = '('.number_format(abs($kas_awal),0).')';
}


echo '<tr>
<th style="text-align: left;vertical-align: middle;width: 29%;">KAS DAN SETARA KAS AWAL TAHUN</th>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<tH style="text-align: center;vertical-align: middle;width: 2%;">Rp.</tH>
<tH style="text-align: right;vertical-align: middle;width: 10%;">'.$total_kas_awal.'</tH> 
</tr>';


$kas_akhir = ($total_laba_rugi_tb + $total_aktivitas_operasional + $total_aktivitas_investasi + $total_aktivitas_pendanaan) + $kas_awal;
if ($kas_akhir > 0) {
    $kas_akhir = number_format($kas_akhir,0);
}else{
    $kas_akhir = '('.number_format(abs($kas_akhir),0).')';
}
echo '<tr>
<th style="text-align: left;vertical-align: middle;width: 29%;">KAS DAN SETARA KAS AKHIR TAHUN</th>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<tH style="text-align: center;vertical-align: middle;width: 2%;">Rp.</tH>
<tH style="text-align: right;vertical-align: middle;width: 10%;">'.$kas_akhir.'</tH> 
</tr>';
?>
</table>
<br>
</div>
</div>

<div id="explanation" class="tabcontent">
  <?php
        // $status = isset($_POST['status']) ? $_POST['status']: null;
  $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
  $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;
  $tanggal_awal = date("Y-m-d",strtotime($start_date));
  $tanggal_akhir = date("Y-m-d",strtotime($end_date)); 
  $tanggal1 = isset($tanggal_awal) ? $tanggal_awal : 0;
  $tanggal2 = isset($tanggal_akhir) ? $tanggal_akhir : 0;
  $kata_awal = date("M",strtotime($start_date));
  $tengah = '_';
  $kata_akhir = date("Y",strtotime($start_date));
  $kata_filter = $kata_awal . $tengah . $kata_akhir;

  if($tanggal2 < $tanggal1){
    echo "";
}
else{

    echo '<a style="padding-right: 10px;" target="_blank" href="../acct/ekspor_explanation.php?start_date='.$start_date.' && end_date='.$end_date.' && kata_filter='.$kata_filter.'"><button type="button" class="btn btn-success " ><i class="fa fa-file-excel-o" aria-hidden="true" style="padding-right: 10px; padding-left: 5px;font-size: 14px;color: #fff;text-shadow: 1px 1px 1px #000"> Excel </i></button></a>

    ';
}
?>

<div style="border:1px solid black;margin-top: 5px;">        
    <table style="font-size: 16px; margin:auto" border="0" role="grid" cellspacing="0" width="65%">
        <tr>
            <th colspan="4" style="text-align: center;vertical-align: middle;width: 64%;">PT NIRWANA ALABARE GARMENT</th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center;vertical-align: middle;width: 100%;">PENJELASAN ATAS LAPORAN KEUANGAN</th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center;vertical-align: middle;width: 100%;">UNTUK TAHUN YANG BERAKHIR 31 <?php
            $enddate = date("F Y");
            $startdate = date("F Y");
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $enddate = date("F Y",strtotime($_POST['end_date'])); 
                $startdate = date("F Y",strtotime($_POST['start_date'])); 
            } echo strtoupper($enddate); ?></th>
        </tr>                   
    </table>
    <div class="horizontal4"></div>
    <table border="0" style="font-size: 14px;margin:auto" role="grid" cellspacing="0" width="65%">
        <tr style="margin-top: 10px;">
            <th style="text-align: left;vertical-align: middle;width: 29%;">01). Kas dan Setara Kas</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
        </tr>
        <tr>
            <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Kas dan Setara Kas Perusahaan Per 31 <?= $enddate; ?> :</td>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
        </tr>
        <?php
        $nama_type ='';
        $Status = '';
        $start_date ='';
        $end_date ='';
        $date_now = date("Y-m-d");
        $tanggal_awal = date("Y-m-d",strtotime($date_now ));
        $tanggal_akhir = date("Y-m-d",strtotime($date_now ));
        $bulan_awal = date("m",strtotime($date_now));
        $bulan_akhir = date("m",strtotime($date_now));  
        $tahun_awal = date("Y",strtotime($date_now));
        $tahun_akhir = date("Y",strtotime($date_now));
        $kata_awal = date("M",strtotime($date_now));
        $tengah = '_';
        $kata_akhir = date("Y",strtotime($date_now));
        $kata_filter = $kata_awal . $tengah . $kata_akhir;
        $kata_filter2 = $kata_awal . $tengah . $kata_akhir;                 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_type = isset($_POST['nama_type']) ? $_POST['nama_type']: null; 
            $Status = isset($_POST['Status']) ? $_POST['Status']: null; 
            $start_date = date("d-m-Y",strtotime($_POST['start_date']));
            $end_date = date("d-m-Y",strtotime($_POST['end_date'])); 

            $tanggal_awal = date("Y-m-d",strtotime($_POST['start_date']));
            $tanggal_akhir = date("Y-m-d",strtotime($_POST['end_date'])); 

            $bulan_awal = date("m",strtotime($_POST['start_date']));
            $bulan_akhir = date("m",strtotime($_POST['end_date']));  
            $tahun_awal = date("Y",strtotime($_POST['start_date']));
            $tahun_akhir = date("Y",strtotime($_POST['end_date']));

            $kata_awal = date("M",strtotime($_POST['start_date']));
            $tengah = '_';
            $kata_akhir = date("Y",strtotime($_POST['start_date']));
            $kata_filter = $kata_awal . $tengah . $kata_akhir;

            $kata_awal2 = date("M",strtotime($_POST['end_date']));
            $tengah2 = '_';
            $kata_akhir2 = date("Y",strtotime($_POST['end_date']));
            $kata_filter2 = $kata_awal2 . $tengah2 . $kata_akhir2;


        }

        $sql1 = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'Kas dan Setara Kas') a LEFT JOIN
            (select ind_categori2,ind_categori7,(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori7,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,ind_categori7,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa GROUP BY ind_categori7 order by no_coa asc) a) b on b.ind_categori7 = a.sub_kategori order by id asc");

        $sql2 = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'Piutang Usaha') a LEFT JOIN
            (select ind_categori2,ind_categori7,(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori7,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,ind_categori7,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa GROUP BY ind_categori7 order by no_coa asc) a) b on b.ind_categori7 = a.sub_kategori order by id asc");

        $sql3 = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'Piutang Lain-lain') a LEFT JOIN
            (select ind_categori2,ind_categori7,(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori7,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,ind_categori7,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa GROUP BY ind_categori7 order by no_coa asc) a) b on b.ind_categori7 = a.sub_kategori order by id asc");

        $sql4 = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'Persediaan') a LEFT JOIN
            (select ind_categori2,ind_categori7,(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori7,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,ind_categori7,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa GROUP BY ind_categori7 order by no_coa asc) a) b on b.ind_categori7 = a.sub_kategori order by id asc");

        $sql5 = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'Uang Muka Pajak') a LEFT JOIN
            (select ind_categori2,ind_categori7,(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori7,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,ind_categori7,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa GROUP BY ind_categori7 order by no_coa asc) a) b on b.ind_categori7 = a.sub_kategori order by id asc");

        $sql6 = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'Uang Muka Lain-lain') a LEFT JOIN
            (select ind_categori2,ind_categori7,(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori7,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,ind_categori7,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa GROUP BY ind_categori7 order by no_coa asc) a) b on b.ind_categori7 = a.sub_kategori order by id asc");

        $sql7 = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'Aktiva Tetap Ex') a LEFT JOIN
            (select ind_categori2,ind_categori7,(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori7,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,ind_categori7,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa GROUP BY ind_categori7 order by no_coa asc) a) b on b.ind_categori7 = a.sub_kategori order by id asc");

        $sql8 = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'Hutang Usaha') a LEFT JOIN
            (select ind_categori2,ind_categori7,(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori7,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,ind_categori7,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa GROUP BY ind_categori7 order by no_coa asc) a) b on b.ind_categori7 = a.sub_kategori order by id asc");

        $sql9 = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'Hutang Bank') a LEFT JOIN
            (select ind_categori2,ind_categori7,(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori7,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,ind_categori7,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa GROUP BY ind_categori7 order by no_coa asc) a) b on b.ind_categori7 = a.sub_kategori order by id asc");

        $sql10 = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'Hutang Pajak') a LEFT JOIN
            (select ind_categori2,ind_categori7,(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori7,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,ind_categori7,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa GROUP BY ind_categori7 order by no_coa asc) a) b on b.ind_categori7 = a.sub_kategori order by id asc");

        $sql11 = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'Biaya Yang Masih Harus Dibayar') a LEFT JOIN
            (select ind_categori2,ind_categori7,(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori7,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,ind_categori7,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa GROUP BY ind_categori7 order by no_coa asc) a) b on b.ind_categori7 = a.sub_kategori order by id asc");

        $sql12 = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'Hutang Lain-lain') a LEFT JOIN
            (select ind_categori2,ind_categori7,(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori7,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,ind_categori7,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa GROUP BY ind_categori7 order by no_coa asc) a) b on b.ind_categori7 = a.sub_kategori order by id asc");

        $sql13 = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'Modal' and sub_kategori != 'Laba / (Rugi) Tahun Berjalan') a LEFT JOIN
            (select '' ind_categori2,nama_modal ind_categori7, -total total from sb_modal_dasar
            UNION
            select ind_categori2,ind_categori7,(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori7,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,ind_categori7,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa where ind_categori7 != 'LABA / (RUGI) TAHUN BERJALAN' GROUP BY ind_categori7 order by no_coa asc) a) b on b.ind_categori7 = a.sub_kategori 
        UNION
select '165' id,'','Laba / (Rugi) Tahun Berjalan' sub_kategori, 'ind_categori2', ind_categori6, total from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and sub_kategori = 'LABA / (RUGI) TAHUN BERJALAN' and kategori = 'EKUITAS') a  JOIN
                        (select ind_categori2,ind_categori6,sum(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori6,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
                        (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
                        left join
                        (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,id_ctg5 from mastercoa_sb order by no_coa asc) coa
                        on coa.no_coa = saldo.nocoa
                        left join
                        (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
                        jnl on jnl.coa_no = coa.no_coa WHERE ind_categori1 = 'LAPORAN LABA RUGI' OR no_coa = '3.40.01' GROUP BY ind_categori6 order by no_coa asc) a) b  order by id asc");

        $sql14 = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'Pendapatan Usaha Ex') a LEFT JOIN
            (select ind_categori2,ind_categori7,(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori7,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,ind_categori7,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa GROUP BY ind_categori7 order by no_coa asc) a) b on b.ind_categori7 = a.sub_kategori order by id asc");

        $sql15 = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'Harga Pokok Penjualan Ex') a LEFT JOIN
            (select ind_categori2,ind_categori7,(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori7,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,ind_categori7,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa GROUP BY ind_categori2 order by no_coa asc) a) b on b.ind_categori2 = a.sub_kategori order by id asc");

        $sql16 = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'Biaya Penjualan Ex') a LEFT JOIN
            (select ind_categori2,ind_categori7,(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori7,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,ind_categori7,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa where ind_categori2 = 'BIAYA PENJUALAN' GROUP BY ind_categori7 order by no_coa asc) a) b on b.ind_categori7 = a.sub_kategori order by id asc");

        $sql17 = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'Biaya Administrasi & Umum Ex') a LEFT JOIN
            (select ind_categori2,ind_categori7,(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori7,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,ind_categori7,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa where ind_categori2 = 'BIAYA ADMINISTRASI & UMUM' GROUP BY ind_categori7 order by no_coa asc) a) b on b.ind_categori7 = a.sub_kategori order by id asc");

        $sql18 = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'Pendapatan (Biaya) Lain-lain') a LEFT JOIN
            (select ind_categori2,ind_categori7,(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori7,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,ind_categori7,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa where ind_categori2 = 'PENDAPATAN / (BEBAN) LAIN-LAIN' GROUP BY ind_categori7 order by no_coa asc) a) b on b.ind_categori7 = a.sub_kategori order by id asc");

        
        $saldoakhir = 0;

        if($tanggal_akhir < $tanggal_awal){
            $message = "Mohon Masukan Tanggal Filter Yang Benar";
            echo "<script type='text/javascript'>alert('$message');</script>";
        }
        else{
            $no = 01;


            $total_exp_kas = 0;
            while($row1 = mysqli_fetch_array($sql1)){
                $exp_kas = isset($row1['total']) ? $row1['total'] : 0;
                if ($exp_kas > 0) {
                    $exp_kas = number_format($exp_kas,0);
                }else{
                    $exp_kas = '('.number_format(abs($exp_kas),0).')';
                }

                $total_exp_kas += isset($row1['total']) ? $row1['total'] : 0;
                if ($total_exp_kas > 0) {
                    $total_exp_kas_ = number_format($total_exp_kas,0);
                }else{
                    $total_exp_kas_ = '('.number_format(abs($total_exp_kas),0).')';
                }

                echo '<tr>
                <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row1['sub_kategori'].'</td>
                <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
                <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_kas.'</td> 
                </tr>';
            }

            echo '<tr>
            <th style="text-align: left;vertical-align: middle;width: 29%;"></th>
            <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
            <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
            <th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_kas_.'</th> 
            </tr>
            <tr style="margin-top: 5px;">
            <th style="text-align: left;vertical-align: middle;width: 29%;">02). Piutang Usaha </th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>
            <tr>
            <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Piutang Usaha Perusahaan Per 31 '; echo $enddate; echo ' :</td>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>';

            $total_exp_piutang_usaha = 0;
            while($row2 = mysqli_fetch_array($sql2)){
                $exp_piutang_usaha = isset($row2['total']) ? $row2['total'] : 0;
                if ($exp_piutang_usaha > 0) {
                    $exp_piutang_usaha = number_format($exp_piutang_usaha,0);
                }else{
                    $exp_piutang_usaha = '('.number_format(abs($exp_piutang_usaha),0).')';
                }

                $total_exp_piutang_usaha += isset($row2['total']) ? $row2['total'] : 0;
                if ($total_exp_piutang_usaha > 0) {
                    $total_exp_piutang_usaha_ = number_format($total_exp_piutang_usaha,0);
                }else{
                    $total_exp_piutang_usaha_ = '('.number_format(abs($total_exp_piutang_usaha),0).')';
                }

                echo '<tr>
                <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row2['sub_kategori'].'</td>
                <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
                <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_piutang_usaha.'</td> 
                </tr>';
            }

            echo '<tr>
            <th style="text-align: left;vertical-align: middle;width: 29%;"></th>
            <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
            <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
            <th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_piutang_usaha_.'</th> 
            </tr>
            <tr style="margin-top: 5px;">
            <th style="text-align: left;vertical-align: middle;width: 29%;">03). Piutang Lain-lain</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>
            <tr>
            <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Piutang Lain-lain Perusahaan Per 31 '; echo $enddate; echo ' :</td>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>';

            $total_exp_piutang_lain = 0;
            while($row3 = mysqli_fetch_array($sql3)){
                $exp_piutang_lain = isset($row3['total']) ? $row3['total'] : 0;
                if ($exp_piutang_lain > 0) {
                    $exp_piutang_lain = number_format($exp_piutang_lain,0);
                }else{
                    $exp_piutang_lain = '('.number_format(abs($exp_piutang_lain),0).')';
                }

                $total_exp_piutang_lain += isset($row3['total']) ? $row3['total'] : 0;
                if ($total_exp_piutang_lain > 0) {
                    $total_exp_piutang_lain_ = number_format($total_exp_piutang_lain,0);
                }else{
                    $total_exp_piutang_lain_ = '('.number_format(abs($total_exp_piutang_lain),0).')';
                }

                echo '<tr>
                <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row3['sub_kategori'].'</td>
                <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
                <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_piutang_lain.'</td> 
                </tr>';
            }

            echo '<tr>
            <th style="text-align: left;vertical-align: middle;width: 29%;"></th>
            <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
            <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
            <th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_piutang_lain_.'</th> 
            </tr>
            <tr style="margin-top: 5px;">
            <th style="text-align: left;vertical-align: middle;width: 29%;">04). Persediaan</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>
            <tr>
            <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Persediaan Perusahaan Per 31 '; echo $enddate; echo ' :</td>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>';

            $total_exp_persediaan = 0;
            while($row4 = mysqli_fetch_array($sql4)){
                $exp_persediaan = isset($row4['total']) ? $row4['total'] : 0;
                if ($exp_persediaan > 0) {
                    $exp_persediaan = number_format($exp_persediaan,0);
                }else{
                    $exp_persediaan = '('.number_format(abs($exp_persediaan),0).')';
                }

                $total_exp_persediaan += isset($row4['total']) ? $row4['total'] : 0;
                if ($total_exp_persediaan > 0) {
                    $total_exp_persediaan_ = number_format($total_exp_persediaan,0);
                }else{
                    $total_exp_persediaan_ = '('.number_format(abs($total_exp_persediaan),0).')';
                }

                echo '<tr>
                <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row4['sub_kategori'].'</td>
                <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
                <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_persediaan.'</td> 
                </tr>';
            }

            echo '<tr>
            <th style="text-align: left;vertical-align: middle;width: 29%;"></th>
            <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
            <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
            <th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_persediaan_.'</th> 
            </tr>
            <tr style="margin-top: 5px;">
            <th style="text-align: left;vertical-align: middle;width: 29%;">05). Uang Muka Pajak</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>
            <tr>
            <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Uang Muka Pajak Perusahaan Per 31 '; echo $enddate; echo ' :</td>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>';

            $total_exp_muka_pajak = 0;
            while($row5 = mysqli_fetch_array($sql5)){
                $exp_muka_pajak = isset($row5['total']) ? $row5['total'] : 0;
                if ($exp_muka_pajak > 0) {
                    $exp_muka_pajak = number_format($exp_muka_pajak,0);
                }else{
                    $exp_muka_pajak = '('.number_format(abs($exp_muka_pajak),0).')';
                }

                $total_exp_muka_pajak += isset($row5['total']) ? $row5['total'] : 0;
                if ($total_exp_muka_pajak > 0) {
                    $total_exp_muka_pajak_ = number_format($total_exp_muka_pajak,0);
                }else{
                    $total_exp_muka_pajak_ = '('.number_format(abs($total_exp_muka_pajak),0).')';
                }

                echo '<tr>
                <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row5['sub_kategori'].'</td>
                <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
                <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_muka_pajak.'</td> 
                </tr>';
            }

            echo '<tr>
            <th style="text-align: left;vertical-align: middle;width: 29%;"></th>
            <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
            <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
            <th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_muka_pajak_.'</th> 
            </tr>
            <tr style="margin-top: 5px;">
            <th style="text-align: left;vertical-align: middle;width: 29%;">06). Uang Muka Lain-lain</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>
            <tr>
            <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Uang Muka Lain-lain Perusahaan Per 31 '; echo $enddate; echo ' :</td>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>';

            $total_exp_muka_lain = 0;
            while($row6 = mysqli_fetch_array($sql6)){
                $exp_muka_lain = isset($row6['total']) ? $row6['total'] : 0;
                if ($exp_muka_lain > 0) {
                    $exp_muka_lain = number_format($exp_muka_lain,0);
                }else{
                    $exp_muka_lain = '('.number_format(abs($exp_muka_lain),0).')';
                }

                $total_exp_muka_lain += isset($row6['total']) ? $row6['total'] : 0;
                if ($total_exp_muka_lain > 0) {
                    $total_exp_muka_lain_ = number_format($total_exp_muka_lain,0);
                }else{
                    $total_exp_muka_lain_ = '('.number_format(abs($total_exp_muka_lain),0).')';
                }

                echo '<tr>
                <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row6['sub_kategori'].'</td>
                <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
                <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_muka_lain.'</td> 
                </tr>';
            }

            echo '<tr>
            <th style="text-align: left;vertical-align: middle;width: 29%;"></th>
            <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
            <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
            <th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_muka_lain_.'</th> 
            </tr>
            <tr style="margin-top: 5px;">
            <th style="text-align: left;vertical-align: middle;width: 29%;">07). Aktiva Tetap</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>
            <tr>
            <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Aktiva Tetap Perusahaan Per 31 '; echo $enddate; echo ' :</td>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>';

            $total_exp_aktiva_tetap = 0;
            while($row7 = mysqli_fetch_array($sql7)){
                $exp_aktiva_tetap = isset($row7['total']) ? $row7['total'] : 0;
                if ($exp_aktiva_tetap > 0) {
                    $exp_aktiva_tetap = number_format($exp_aktiva_tetap,0);
                }else{
                    $exp_aktiva_tetap = '('.number_format(abs($exp_aktiva_tetap),0).')';
                }

                $total_exp_aktiva_tetap += isset($row7['total']) ? $row7['total'] : 0;
                if ($total_exp_aktiva_tetap > 0) {
                    $total_exp_aktiva_tetap_ = number_format($total_exp_aktiva_tetap,0);
                }else{
                    $total_exp_aktiva_tetap_ = '('.number_format(abs($total_exp_aktiva_tetap),0).')';
                }

                echo '<tr>
                <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row7['sub_kategori'].'</td>
                <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
                <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_aktiva_tetap.'</td> 
                </tr>';
            }

            echo '<tr>
            <th style="text-align: left;vertical-align: middle;width: 29%;"></th>
            <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
            <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
            <th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_aktiva_tetap_.'</th> 
            </tr>
            <tr style="margin-top: 5px;">
            <th style="text-align: left;vertical-align: middle;width: 29%;">08). Hutang Usaha</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>
            <tr>
            <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Hutang Usaha Perusahaan Per 31 '; echo $enddate; echo ' :</td>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>';

            $total_exp_hutang_usaha = 0;
            while($row8 = mysqli_fetch_array($sql8)){
                $exp_hutang_usaha = isset($row8['total']) ? $row8['total'] : 0;
                if ($exp_hutang_usaha > 0) {
                    $exp_hutang_usaha = number_format($exp_hutang_usaha,0);
                }else{
                    $exp_hutang_usaha = '('.number_format(abs($exp_hutang_usaha),0).')';
                }

                $total_exp_hutang_usaha += isset($row8['total']) ? $row8['total'] : 0;
                if ($total_exp_hutang_usaha > 0) {
                    $total_exp_hutang_usaha_ = number_format($total_exp_hutang_usaha,0);
                }else{
                    $total_exp_hutang_usaha_ = '('.number_format(abs($total_exp_hutang_usaha),0).')';
                }

                echo '<tr>
                <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row8['sub_kategori'].'</td>
                <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
                <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_hutang_usaha.'</td> 
                </tr>'; 
            }

            echo '<tr>
            <th style="text-align: left;vertical-align: middle;width: 29%;"></th>
            <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
            <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
            <th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_hutang_usaha_.'</th> 
            </tr>
            <tr style="margin-top: 5px;">
            <th style="text-align: left;vertical-align: middle;width: 29%;">09). Hutang Bank</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>
            <tr>
            <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Hutang Bank Perusahaan Per 31 '; echo $enddate; echo ' :</td>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>';

            $total_exp_hutang_bank = 0;
            while($row9 = mysqli_fetch_array($sql9)){
                $exp_hutang_bank = isset($row9['total']) ? $row9['total'] : 0;
                if ($exp_hutang_bank > 0) {
                    $exp_hutang_bank = number_format($exp_hutang_bank,0);
                }else{
                    $exp_hutang_bank = '('.number_format(abs($exp_hutang_bank),0).')';
                }

                $total_exp_hutang_bank += isset($row9['total']) ? $row9['total'] : 0;
                if ($total_exp_hutang_bank > 0) {
                    $total_exp_hutang_bank_ = number_format($total_exp_hutang_bank,0);
                }else{
                    $total_exp_hutang_bank_ = '('.number_format(abs($total_exp_hutang_bank),0).')';
                }

                echo '<tr>
                <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row9['sub_kategori'].'</td>
                <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
                <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_hutang_bank.'</td> 
                </tr>';
            }

            echo '<tr>
            <th style="text-align: left;vertical-align: middle;width: 29%;"></th>
            <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
            <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
            <th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_hutang_bank_.'</th> 
            </tr>
            <tr style="margin-top: 5px;">
            <th style="text-align: left;vertical-align: middle;width: 29%;">10). Hutang Pajak</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>
            <tr>
            <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Hutang Pajak Perusahaan Per 31 '; echo $enddate; echo ' :</td>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>';

            $total_exp_hutang_pajak = 0;
            while($row10 = mysqli_fetch_array($sql10)){
                $exp_hutang_pajak = isset($row10['total']) ? $row10['total'] : 0;
                if ($exp_hutang_pajak > 0) {
                    $exp_hutang_pajak = number_format($exp_hutang_pajak,0);
                }else{
                    $exp_hutang_pajak = '('.number_format(abs($exp_hutang_pajak),0).')';
                }

                $total_exp_hutang_pajak += isset($row10['total']) ? $row10['total'] : 0;
                if ($total_exp_hutang_pajak > 0) {
                    $total_exp_hutang_pajak_ = number_format($total_exp_hutang_pajak,0);
                }else{
                    $total_exp_hutang_pajak_ = '('.number_format(abs($total_exp_hutang_pajak),0).')';
                }

                echo '<tr>
                <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row10['sub_kategori'].'</td>
                <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
                <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_hutang_pajak.'</td> 
                </tr>';
            }

            echo '<tr>
            <th style="text-align: left;vertical-align: middle;width: 29%;"></th>
            <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
            <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
            <th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_hutang_pajak_.'</th> 
            </tr>
            <tr style="margin-top: 5px;">
            <th style="text-align: left;vertical-align: middle;width: 29%;">11). Biaya Yang Masih Harus Dibayar</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>
            <tr>
            <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Biaya Yang Masih Harus Dibayar Perusahaan Per 31 '; echo $enddate; echo ' :</td>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>';

            $total_exp_masih_harus_dibayar = 0;
            while($row11 = mysqli_fetch_array($sql11)){
                $exp_masih_harus_dibayar = isset($row11['total']) ? $row11['total'] : 0;
                if ($exp_masih_harus_dibayar > 0) {
                    $exp_masih_harus_dibayar = number_format($exp_masih_harus_dibayar,0);
                }else{
                    $exp_masih_harus_dibayar = '('.number_format(abs($exp_masih_harus_dibayar),0).')';
                }

                $total_exp_masih_harus_dibayar += isset($row11['total']) ? $row11['total'] : 0;
                if ($total_exp_masih_harus_dibayar > 0) {
                    $total_exp_masih_harus_dibayar_ = number_format($total_exp_masih_harus_dibayar,0);
                }else{
                    $total_exp_masih_harus_dibayar_ = '('.number_format(abs($total_exp_masih_harus_dibayar),0).')';
                }

                echo '<tr>
                <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row11['sub_kategori'].'</td>
                <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
                <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_masih_harus_dibayar.'</td> 
                </tr>';
            }

            echo '<tr>
            <th style="text-align: left;vertical-align: middle;width: 29%;"></th>
            <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
            <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
            <th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_masih_harus_dibayar_.'</th> 
            </tr>
            <tr style="margin-top: 5px;">
            <th style="text-align: left;vertical-align: middle;width: 29%;">12). Hutang Lain-lain</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>
            <tr>
            <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Hutang Lain-lain Perusahaan Per 31 '; echo $enddate; echo ' :</td>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>';

            $total_exp_hutang_lain = 0;
            while($row12 = mysqli_fetch_array($sql12)){
                $exp_hutang_lain = isset($row12['total']) ? $row12['total'] : 0;
                if ($exp_hutang_lain > 0) {
                    $exp_hutang_lain = number_format($exp_hutang_lain,0);
                }else{
                    $exp_hutang_lain = '('.number_format(abs($exp_hutang_lain),0).')';
                }

                $total_exp_hutang_lain += isset($row12['total']) ? $row12['total'] : 0;
                if ($total_exp_hutang_lain > 0) {
                    $total_exp_hutang_lain_ = number_format($total_exp_hutang_lain,0);
                }else{
                    $total_exp_hutang_lain_ = '('.number_format(abs($total_exp_hutang_lain),0).')';
                }

                echo '<tr>
                <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row12['sub_kategori'].'</td>
                <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
                <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_hutang_lain.'</td> 
                </tr>';
            }

            echo '<tr>
            <th style="text-align: left;vertical-align: middle;width: 29%;"></th>
            <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
            <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
            <th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_hutang_lain_.'</th> 
            </tr>
            <tr style="margin-top: 5px;">
            <th style="text-align: left;vertical-align: middle;width: 29%;">13). Modal</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>
            <tr>
            <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Modal Perusahaan Per 31 '; echo $enddate; echo ' :</td>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>';

            $total_exp_modal = 0;
            while($row13 = mysqli_fetch_array($sql13)){
                $exp_modal = isset($row13['total']) ? $row13['total'] : 0;
                if ($exp_modal > 0) {
                    $exp_modal = number_format($exp_modal,0);
                }else{
                    $exp_modal = '('.number_format(abs($exp_modal),0).')';
                }

                $total_exp_modal += isset($row13['total']) ? $row13['total'] : 0;
                if ($total_exp_modal > 0) {
                    $total_exp_modal_ = number_format($total_exp_modal,0);
                }else{
                    $total_exp_modal_ = '('.number_format(abs($total_exp_modal),0).')';
                }

                echo '<tr>
                <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row13['sub_kategori'].'</td>
                <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
                <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_modal.'</td> 
                </tr>';
            }

            echo '<tr>
            <th style="text-align: left;vertical-align: middle;width: 29%;"></th>
            <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
            <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
            <th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_modal_.'</th> 
            </tr>
            <tr style="margin-top: 5px;">
            <th style="text-align: left;vertical-align: middle;width: 29%;">14). Pendapatan Usaha</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>
            <tr>
            <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Pendapatan Usaha Perusahaan Per 31 '; echo $enddate; echo ' :</td>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>';

            $total_exp_pendapatan_usaha = 0;
            while($row14 = mysqli_fetch_array($sql14)){
                $exp_pendapatan_usaha = isset($row14['total']) ? $row14['total'] : 0;
                if ($exp_pendapatan_usaha > 0) {
                    $exp_pendapatan_usaha = number_format($exp_pendapatan_usaha,0);
                }else{
                    $exp_pendapatan_usaha = '('.number_format(abs($exp_pendapatan_usaha),0).')';
                }

                $total_exp_pendapatan_usaha += isset($row14['total']) ? $row14['total'] : 0;
                if ($total_exp_pendapatan_usaha > 0) {
                    $total_exp_pendapatan_usaha_ = number_format($total_exp_pendapatan_usaha,0);
                }else{
                    $total_exp_pendapatan_usaha_ = '('.number_format(abs($total_exp_pendapatan_usaha),0).')';
                }

                echo '<tr>
                <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row14['sub_kategori'].'</td>
                <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
                <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_pendapatan_usaha.'</td> 
                </tr>';
            }

            echo '<tr>
            <th style="text-align: left;vertical-align: middle;width: 29%;"></th>
            <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
            <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
            <th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_pendapatan_usaha_.'</th> 
            </tr>
            <tr style="margin-top: 5px;">
            <th style="text-align: left;vertical-align: middle;width: 29%;">15). Harga Pokok Penjualan</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>
            <tr>
            <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Harga Pokok Penjualan Perusahaan Per 31 '; echo $enddate; echo ' :</td>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>';

            $total_exp_hpp = 0;
            while($row15 = mysqli_fetch_array($sql15)){
                $exp_hpp = isset($row15['total']) ? $row15['total'] : 0;
                if ($exp_hpp > 0) {
                    $exp_hpp = number_format($exp_hpp,0);
                }else{
                    $exp_hpp = '('.number_format(abs($exp_hpp),0).')';
                }

                $total_exp_hpp += isset($row15['total']) ? $row15['total'] : 0;
                if ($total_exp_hpp > 0) {
                    $total_exp_hpp_ = number_format($total_exp_hpp,0);
                }else{
                    $total_exp_hpp_ = '('.number_format(abs($total_exp_hpp),0).')';
                }

                echo '<tr>
                <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row15['sub_kategori'].'</td>
                <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
                <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_hpp.'</td> 
                </tr>';
            }

            echo '<tr>
            <th style="text-align: left;vertical-align: middle;width: 29%;"></th>
            <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
            <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
            <th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_hpp_.'</th> 
            </tr>
            <tr style="margin-top: 5px;">
            <th style="text-align: left;vertical-align: middle;width: 29%;">16). Biaya Penjualan</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>
            <tr>
            <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Biaya Penjualan Perusahaan Per 31 '; echo $enddate; echo ' :</td>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>';

            $total_exp_biaya_penjualan = 0;
            while($row16 = mysqli_fetch_array($sql16)){
                $exp_biaya_penjualan = isset($row16['total']) ? $row16['total'] : 0;
                if ($exp_biaya_penjualan > 0) {
                    $exp_biaya_penjualan = number_format($exp_biaya_penjualan,0);
                }else{
                    $exp_biaya_penjualan = '('.number_format(abs($exp_biaya_penjualan),0).')';
                }

                $total_exp_biaya_penjualan += isset($row16['total']) ? $row16['total'] : 0;
                if ($total_exp_biaya_penjualan > 0) {
                    $total_exp_biaya_penjualan_ = number_format($total_exp_biaya_penjualan,0);
                }else{
                    $total_exp_biaya_penjualan_ = '('.number_format(abs($total_exp_biaya_penjualan),0).')';
                }

                echo '<tr>
                <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row16['sub_kategori'].'</td>
                <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
                <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_biaya_penjualan.'</td> 
                </tr>';
            }

            echo '<tr>
            <th style="text-align: left;vertical-align: middle;width: 29%;"></th>
            <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
            <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
            <th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_biaya_penjualan_.'</th> 
            </tr>
            <tr style="margin-top: 5px;">
            <th style="text-align: left;vertical-align: middle;width: 29%;">17). Biaya Administrasi & Umum</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>
            <tr>
            <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Biaya Administrasi & Umum Perusahaan Per 31 '; echo $enddate; echo ' :</td>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>';

            $total_exp_adm_umum = 0;
            while($row17 = mysqli_fetch_array($sql17)){
                $exp_adm_umum = isset($row17['total']) ? $row17['total'] : 0;
                if ($exp_adm_umum > 0) {
                    $exp_adm_umum = number_format($exp_adm_umum,0);
                }else{
                    $exp_adm_umum = '('.number_format(abs($exp_adm_umum),0).')';
                }

                $total_exp_adm_umum += isset($row17['total']) ? $row17['total'] : 0;
                if ($total_exp_adm_umum > 0) {
                    $total_exp_adm_umum_ = number_format($total_exp_adm_umum,0);
                }else{
                    $total_exp_adm_umum_ = '('.number_format(abs($total_exp_adm_umum),0).')';
                }

                echo '<tr>
                <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row17['sub_kategori'].'</td>
                <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
                <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_adm_umum.'</td> 
                </tr>';
            }

            echo '<tr>
            <th style="text-align: left;vertical-align: middle;width: 29%;"></th>
            <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
            <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
            <th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_adm_umum_.'</th> 
            </tr>
            <tr style="margin-top: 5px;">
            <th style="text-align: left;vertical-align: middle;width: 29%;">18). Pendapatan (Biaya) Lain-lain</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>
            <tr>
            <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Pendapatan (Biaya) Lain-lain Perusahaan Per 31 '; echo $enddate; echo ' :</td>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
            </tr>';

            $total_exp_pendapatan_biaya_lain = 0;
            while($row18 = mysqli_fetch_array($sql18)){
                $exp_pendapatan_biaya_lain = isset($row18['total']) ? $row18['total'] : 0;
                if ($exp_pendapatan_biaya_lain > 0) {
                    $exp_pendapatan_biaya_lain = number_format($exp_pendapatan_biaya_lain,0);
                }else{
                    $exp_pendapatan_biaya_lain = '('.number_format(abs($exp_pendapatan_biaya_lain),0).')';
                }

                $total_exp_pendapatan_biaya_lain += isset($row18['total']) ? $row18['total'] : 0;
                if ($total_exp_pendapatan_biaya_lain > 0) {
                    $total_exp_pendapatan_biaya_lain_ = number_format($total_exp_pendapatan_biaya_lain,0);
                }else{
                    $total_exp_pendapatan_biaya_lain_ = '('.number_format(abs($total_exp_pendapatan_biaya_lain),0).')';
                }

                echo '<tr>
                <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row18['sub_kategori'].'</td>
                <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
                <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_pendapatan_biaya_lain.'</td> 
                </tr>';
            }

            echo '<tr>
            <th style="text-align: left;vertical-align: middle;width: 29%;"></th>
            <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
            <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
            <th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_pendapatan_biaya_lain_.'</th> 
            </tr>';


        }
        ?>
    </table>
    <br>
</div>
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
    function openCity(evt, cityName) {
      var i, tabcontent, tablinks;
      tabcontent = document.getElementsByClassName("tabcontent");
      for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active text-light bg-dark", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active text-light bg-dark";
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script>
<!-- <script>
    $(document).ready(function() {
    $('#datatable').dataTable();
    
     $("[data-toggle=tooltip]").tooltip();
    
} );
</script> -->

<script>
    function myFunction() {
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("datatable");
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
    $("#form-data").on("click", "#co_sal", function(){ 
        var no_coa = $(this).closest('tr').find('td:eq(1)').attr('value');
        var beg_balance = $(this).closest('tr').find('td:eq(7)').attr('value');
        var debit = $(this).closest('tr').find('td:eq(8)').attr('value');
        var credit = $(this).closest('tr').find('td:eq(9)').attr('value');
        var end_balance = $(this).closest('tr').find('td:eq(10)').attr('value');
        var copy_user = '<?php echo $user ?>';
        var to_saldo = document.getElementById('to_saldo').value;

        $.ajax({
            type:'POST',
            url:'copy_saldo_tb.php',
            data: {'no_coa':no_coa, 'beg_balance':beg_balance,'debit':debit, 'credit':credit,'end_balance':end_balance, 'copy_user':copy_user,'to_saldo':to_saldo},
            close: function(e){
                e.preventDefault();
            },
            success: function(response){                
                // alert(response);            
            },
            error:  function (xhr, ajaxOptions, thrownError) {
             alert(xhr);
         }
     });
        alert("Copy Saldo successfully");     
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


<!-- <script type="text/javascript">     
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

</script> -->


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