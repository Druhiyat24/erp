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
  .border-full {
    border-top: none;
    border-left: none;
    border-right: none;
    border-bottom: none;
    width: 18%;
}
.horizontal{

    height:0;
    width:55%;
    margin: auto;
    border:1px solid #000000;

}
</style>
<!-- MAIN -->
<div class="col p-4">
    <h4 class="text-center">LAPORAN LABA RUGI</h4>
    <div class="box">
        <div class="box header">

            <form id="form-data" action="laba_rugi.php" method="post">
                <div style="padding-left: 10px;padding-top: 5px;">
                    <button style="-ms-transform: skew(8deg);-webkit-transform: skew(8deg);transform: skew(10deg);" id="btn_tb" type="button" class="btn-secondary btn-xs"><span>Trial Balance</span></button>
                    <button style="-ms-transform: skew(8deg);-webkit-transform: skew(8deg);transform: skew(10deg);" id="btn_neraca" type="button" class="btn-secondary btn-xs"><span></span> Neraca</button>
                    <button style="-ms-transform: skew(8deg);-webkit-transform: skew(8deg);transform: skew(10deg);" id="btn_labarugi" type="button" class="btn-primary btn-xs"><span></span> Laba Rugi</button>
                </div>        
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

                    echo '<a style="padding-right: 10px;" target="_blank" href="ekspor_laba_rugi.php?start_date='.$start_date.' && end_date='.$end_date.' && kata_filter='.$kata_filter.'"><button type="button" class="btn btn-success " style= "margin-top: 30px;"><i class="fa fa-file-excel-o" aria-hidden="true" style="padding-right: 10px; padding-left: 5px;font-size: 14px;color: #fff;text-shadow: 1px 1px 1px #000"> Excel</i></button></a>

                    ';
                }
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
        <div class="col-md-12">    
            <div style="border:1px solid black;">        
                <table style="font-size: 16px; margin:auto" border="0" role="grid" cellspacing="0" width="55%">
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
                <div class="horizontal"></div>
                <table border="0" style="font-size: 14px;margin:auto" role="grid" cellspacing="0" width="50%">
                    <tr style="line-height: 40px;">
                        <th style="text-align: left;vertical-align: middle;width: 29%;"></th>
                        <td style="text-align: center;vertical-align: middle;width: 5%;">Ref</td>
                        <td colspan="2" style="text-align: center;vertical-align: middle;width: 17%;">Komersial</td>
                    </tr>
                    <tr>
                        <th style="text-align: left;vertical-align: middle;width: 29%;">PENDAPATAN USAHA</th>
                        <td style="text-align: center;vertical-align: middle;width: 5%;">14.</td>
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

                    $sql = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'PENDAPATAN USAHA') a INNER JOIN
                        (select ind_categori2,ind_categori4,-(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori4,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
                        (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
                        left join
                        (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori4,id_ctg5 from mastercoa_sb order by no_coa asc) coa
                        on coa.no_coa = saldo.nocoa
                        left join
                        (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
                        jnl on jnl.coa_no = coa.no_coa GROUP BY ind_categori4 order by no_coa asc) a) b on b.ind_categori4 = a.sub_kategori order by id asc");

                    $sql2 = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'HARGA POKOK PENJUALAN') a INNER JOIN
                        (select ind_categori2,ind_categori4,(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori4,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
                        (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
                        left join
                        (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori4,id_ctg5 from mastercoa_sb order by no_coa asc) coa
                        on coa.no_coa = saldo.nocoa
                        left join
                        (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
                        jnl on jnl.coa_no = coa.no_coa GROUP BY ind_categori4 order by no_coa asc) a) b on b.ind_categori4 = a.sub_kategori order by id asc");

                    $sql3 = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'BIAYA PENJUALAN') a INNER JOIN
                        (select ind_categori2,ind_categori4,(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori4,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
                        (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
                        left join
                        (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori4,id_ctg5 from mastercoa_sb order by no_coa asc) coa
                        on coa.no_coa = saldo.nocoa
                        left join
                        (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
                        jnl on jnl.coa_no = coa.no_coa WHERE ind_categori2 = 'BIAYA PENJUALAN' GROUP BY ind_categori4 order by no_coa asc) a) b on b.ind_categori4 = a.sub_kategori order by id asc");

                    $sql4 = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'BIAYA ADMINISTRASI & UMUM') a INNER JOIN
                        (select ind_categori2,ind_categori4,(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori4,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
                        (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
                        left join
                        (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori4,id_ctg5 from mastercoa_sb order by no_coa asc) coa
                        on coa.no_coa = saldo.nocoa
                        left join
                        (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
                        jnl on jnl.coa_no = coa.no_coa WHERE ind_categori2 = 'BIAYA ADMINISTRASI & UMUM' GROUP BY ind_categori4 order by no_coa asc) a) b on b.ind_categori4 = a.sub_kategori order by id asc");

                    $sql5 = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'PENDAPATAN / (BEBAN) LAIN-LAIN') a INNER JOIN
                        (select ind_categori2,ind_categori4,-(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori4,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
                        (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
                        left join
                        (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori4,id_ctg5 from mastercoa_sb order by no_coa asc) coa
                        on coa.no_coa = saldo.nocoa
                        left join
                        (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
                        jnl on jnl.coa_no = coa.no_coa WHERE ind_categori2 = 'PENDAPATAN / (BEBAN) LAIN-LAIN' GROUP BY ind_categori4 order by no_coa asc) a) b on b.ind_categori4 = a.sub_kategori order by id asc");


                    $saldoakhir = 0;

                    if($tanggal_akhir < $tanggal_awal){
                        $message = "Mohon Masukan Tanggal Filter Yang Benar";
                        echo "<script type='text/javascript'>alert('$message');</script>";
                    }
                    else{
                        $no = 01;
                        $total_pendapatan_usaha = 0;
                        while($row = mysqli_fetch_array($sql)){
                            $pendapatan_usaha = isset($row['total']) ? $row['total'] : 0;
                            if ($pendapatan_usaha > 0) {
                                $pendapatan_usaha = number_format($pendapatan_usaha,2);
                            }else{
                                $pendapatan_usaha = '('.number_format(abs($pendapatan_usaha),2).')';
                            }

                            $total_pendapatan_usaha += isset($row['total']) ? $row['total'] : 0;
                            if ($total_pendapatan_usaha > 0) {
                                $total_pendapatan_usaha_ = number_format($total_pendapatan_usaha,2);
                            }else{
                                $total_pendapatan_usaha_ = '('.number_format(abs($total_pendapatan_usaha),2).')';
                            }

                            echo '<tr>
                            <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">'.$row['sub_kategori'].'</td>
                            <td style="text-align: center;vertical-align: middle;width: 5%;">'.$row['ref'].'</td>
                            <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                            <td style="text-align: right;vertical-align: middle;width: 15%;">'.$pendapatan_usaha.'</td> 
                            </tr>';
                        }
                        echo '<tr style="line-height: 40px;">
                        <th style="text-align: left;vertical-align: middle;width: 29%;">TOTAL PENDAPATAN</th>
                        <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
                        <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
                        <th style="text-align: right;vertical-align: middle;width: 15%;border-top:1px solid #000000;">'.$total_pendapatan_usaha_.'</th> 
                        </tr>';

                        $total_harga_pokok_penjualan = 0;
                        while($row2 = mysqli_fetch_array($sql2)){
                            $harga_pokok_penjualan = isset($row2['total']) ? $row2['total'] : 0;
                            if ($harga_pokok_penjualan > 0) {
                                $harga_pokok_penjualan = number_format($harga_pokok_penjualan,2);
                            }else{
                                $harga_pokok_penjualan = '('.number_format(abs($harga_pokok_penjualan),2).')';
                            }

                            $total_harga_pokok_penjualan += isset($row2['total']) ? $row2['total'] : 0;
                            if ($total_harga_pokok_penjualan > 0) {
                                $total_harga_pokok_penjualan_ = number_format($total_harga_pokok_penjualan,2);
                            }else{
                                $total_harga_pokok_penjualan_ = '('.number_format(abs($total_harga_pokok_penjualan),2).')';
                            }

                            echo '<tr>
                            <th style="text-align: left;vertical-align: middle;width: 29%;">'.$row2['sub_kategori'].'</th>
                            <td style="text-align: center;vertical-align: middle;width: 5%;">'.$row2['ref'].'</td>
                            <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                            <td style="text-align: right;vertical-align: middle;width: 15%;">'.$harga_pokok_penjualan.'</td> 
                            </tr>';
                        }

                        $laba_rugi_kotor = $total_pendapatan_usaha - $total_harga_pokok_penjualan;
                        if ($laba_rugi_kotor > 0) {
                            $laba_rugi_kotor_ = number_format($laba_rugi_kotor,2);
                        }else{
                            $laba_rugi_kotor_ = '('.number_format(abs($laba_rugi_kotor),2).')';
                        }

                        echo '
                        <tr style="line-height: 40px;">
                        <th style="text-align: left;vertical-align: middle;width: 29%;">LABA (RUGI) KOTOR</th>
                        <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
                        <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
                        <th style="text-align: right;vertical-align: middle;width: 15%;border-top:1px solid #000000;">'.$laba_rugi_kotor_.'</th> 
                        </tr>
                        <tr>
                        <th style="text-align: left;vertical-align: middle;width: 29%;">BIAYA PENJUALAN</th>
                        <td style="text-align: center;vertical-align: middle;width: 5%;">16.</td>
                        <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
                        <td style="text-align: center;vertical-align: middle;width: 15%;"></td> 
                        </tr>
                        ';

                        $total_biaya_penjualan = 0;
                        while($row3 = mysqli_fetch_array($sql3)){
                            $biaya_penjualan = isset($row3['total']) ? $row3['total'] : 0;
                            if ($biaya_penjualan > 0) {
                                $biaya_penjualan = number_format($biaya_penjualan,2);
                            }else{
                                $biaya_penjualan = '('.number_format(abs($biaya_penjualan),2).')';
                            }

                            $total_biaya_penjualan += isset($row3['total']) ? $row3['total'] : 0;
                            if ($total_biaya_penjualan > 0) {
                                $total_biaya_penjualan_ = number_format($total_biaya_penjualan,2);
                            }else{
                                $total_biaya_penjualan_ = '('.number_format(abs($total_biaya_penjualan),2).')';
                            }

                            echo '<tr>
                            <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">'.$row3['sub_kategori'].'</td>
                            <td style="text-align: center;vertical-align: middle;width: 5%;">'.$row3['ref'].'</td>
                            <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                            <td style="text-align: right;vertical-align: middle;width: 15%;">'.$biaya_penjualan.'</td> 
                            </tr>';
                        }
                        echo '<tr style="line-height: 40px;">
                        <th style="text-align: left;vertical-align: middle;width: 29%;">BIAYA ADMINISTRASI & UMUM</th>
                        <td style="text-align: center;vertical-align: middle;width: 5%;">17.</td>
                        <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
                        <td style="text-align: center;vertical-align: middle;width: 15%;"></td> 
                        </tr>';

                        $total_biaya_adm_umum = 0;
                        while($row4 = mysqli_fetch_array($sql4)){
                            $biaya_adm_umum = isset($row4['total']) ? $row4['total'] : 0;
                            if ($biaya_adm_umum > 0) {
                                $biaya_adm_umum = number_format($biaya_adm_umum,2);
                            }else{
                                $biaya_adm_umum = '('.number_format(abs($biaya_adm_umum),2).')';
                            }

                            $total_biaya_adm_umum += isset($row4['total']) ? $row4['total'] : 0;
                            if ($total_biaya_adm_umum > 0) {
                                $total_biaya_adm_umum_ = number_format($total_biaya_adm_umum,2);
                            }else{
                                $total_biaya_adm_umum_ = '('.number_format(abs($total_biaya_adm_umum),2).')';
                            }

                            echo '<tr>
                            <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">'.$row4['sub_kategori'].'</td>
                            <td style="text-align: center;vertical-align: middle;width: 5%;">'.$row4['ref'].'</td>
                            <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                            <td style="text-align: right;vertical-align: middle;width: 15%;">'.$biaya_adm_umum.'</td> 
                            </tr>';
                        }

                        $total_operasional = $total_biaya_penjualan + $total_biaya_adm_umum;
                            if ($total_operasional > 0) {
                                $total_operasional_ = number_format($total_operasional,2);
                            }else{
                                $total_operasional_ = '('.number_format(abs($total_operasional),2).')';
                            }
                        echo '
                        <tr style="line-height: 40px;">
                        <th style="text-align: left;vertical-align: middle;width: 29%;">TOTAL BIAYA OPERASIONAL</th>
                        <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
                        <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
                        <th style="text-align: right;vertical-align: middle;width: 15%;border-top:1px solid #000000;">'.$total_operasional_.'</th> 
                        </tr>';

                        $laba_rugi_operasional = $laba_rugi_kotor - $total_operasional;
                        if ($laba_rugi_operasional > 0) {
                            $laba_rugi_operasional_ = number_format($laba_rugi_operasional,2);
                        }else{
                            $laba_rugi_operasional_ = '('.number_format(abs($laba_rugi_operasional),2).')';
                        }

                        echo '
                        <tr style="line-height: 40px;">
                        <th style="text-align: left;vertical-align: middle;width: 29%;">LABA (RUGI) OPERASIONAL</th>
                        <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
                        <th style="text-align: center;vertical-align: middle;width: 5%;">Rp.</th>
                        <th style="text-align: right;vertical-align: middle;width: 15%;">'.$laba_rugi_operasional_.'</th> 
                        </tr>
                        <tr>
                        <th style="text-align: left;vertical-align: middle;width: 29%;">PENDAPATAN / (BEBAN) LAIN-LAIN</th>
                        <td style="text-align: center;vertical-align: middle;width: 5%;">18.</td>
                        <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
                        <td style="text-align: center;vertical-align: middle;width: 15%;"></td> 
                        </tr>';

                        $total_biaya_pendapatan_lain = 0;
                        while($row5 = mysqli_fetch_array($sql5)){
                            $biaya_pendapatan_lain = isset($row5['total']) ? $row5['total'] : 0;
                            if ($biaya_pendapatan_lain > 0) {
                                $biaya_pendapatan_lain = number_format($biaya_pendapatan_lain,2);
                            }else{
                                $biaya_pendapatan_lain = '('.number_format(abs($biaya_pendapatan_lain),2).')';
                            }

                            $total_biaya_pendapatan_lain += isset($row5['total']) ? $row5['total'] : 0;
                            if ($total_biaya_pendapatan_lain > 0) {
                                $total_biaya_pendapatan_lain_ = number_format($total_biaya_pendapatan_lain,2);
                            }else{
                                $total_biaya_pendapatan_lain_ = '('.number_format(abs($total_biaya_pendapatan_lain),2).')';
                            }

                            echo '<tr>
                            <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">'.$row5['sub_kategori'].'</td>
                            <td style="text-align: center;vertical-align: middle;width: 5%;">'.$row5['ref'].'</td>
                            <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
                            <td style="text-align: right;vertical-align: middle;width: 15%;">'.$biaya_pendapatan_lain.'</td> 
                            </tr>';
                        }
                        echo '
                        <tr style="line-height: 40px;">
                        <th style="text-align: left;vertical-align: middle;width: 29%;">TOTAL PENDAPATAN / (BEBAN) LAIN-LAIN</th>
                        <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
                        <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
                        <th style="text-align: right;vertical-align: middle;width: 15%;border-top:1px solid #000000;">'.$total_biaya_pendapatan_lain_.'</th> 
                        </tr>';

                        $laba_rugi_komersil = $laba_rugi_operasional + $total_biaya_pendapatan_lain;
                        if ($laba_rugi_komersil > 0) {
                            $laba_rugi_komersil_ = number_format($laba_rugi_komersil,2);
                        }else{
                            $laba_rugi_komersil_ = '('.number_format(abs($laba_rugi_komersil),2).')';
                        }

                        echo '
                        <tr style="line-height: 40px;">
                        <th style="text-align: left;vertical-align: middle;width: 29%;">LABA (RUGI) KOMERSIL</th>
                        <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
                        <th style="text-align: center;vertical-align: middle;width: 5%;">Rp.</th>
                        <th style="text-align: right;vertical-align: middle;width: 15%;">'.$laba_rugi_komersil_.'</th> 
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

<script type="text/javascript">
    document.getElementById('btn_tb').onclick = function () {
        location.href = "trial-balance-ytd.php";
    };
</script>

<script type="text/javascript">
    document.getElementById('btn_neraca').onclick = function () {
        location.href = "neraca_ytd.php";
    };
</script>

<script type="text/javascript">
    document.getElementById('btn_labarugi').onclick = function () {
        location.href = "laba_rugi.php";
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