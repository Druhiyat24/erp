<html>
<head>
    <title>Export Data List Journal </title>
</head>
<body>
    <style type="text/css">
        body{
            font-family: sans-serif;
        }
        table{
            margin: 20px auto;
            border-collapse: collapse;
        }
        table th,
        table td{
            border: none;

        }
        a{
            background: blue;
            color: #fff;
            padding: 8px 10px;
            text-decoration: none;
            border-radius: 2px;
        }
        .horizontal{

            height:0;
            width:100%;
            margin: auto;
            border:1px solid #000000;

        }
    </style>

    <?php
    include '../../conn/conn.php';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=laba rugi.xls");
    // $nama_supp =$_GET['nama_supp'];
    // $status =$_GET['status'];
    $bulan_awal = date("m",strtotime($_GET['start_date']));
    $bulan_akhir = date("m",strtotime($_GET['end_date']));  
    $tahun_awal = date("Y",strtotime($_GET['start_date']));
    $tahun_akhir = date("Y",strtotime($_GET['end_date'])); 
    $kata_filter = $_GET['kata_filter'];

    $sqlawal = mysqli_query($conn2,"select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal'");
    $rowawal = mysqli_fetch_array($sqlawal);
    $tgl_awal = isset($rowawal['tgl_awal']) ? $rowawal['tgl_awal'] : null;
    $start_date = date("d F Y",strtotime($tgl_awal));

    $sqlakhir = mysqli_query($conn2,"select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir'");
    $rowakhir = mysqli_fetch_array($sqlakhir);
    $tgl_akhir = isset($rowakhir['tgl_akhir']) ? $rowakhir['tgl_akhir'] : null;
    $end_date = date("d F Y",strtotime($tgl_akhir));

    ?>


    <table style="font-size: 14px; margin:auto" border="0" role="grid" cellspacing="0" width="60%">
        <tr>
            <th style="width: 2%;border-left:1px solid #000000;border-top:1px solid #000000;"></th>
            <th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;border-top:1px solid #000000;"></th>
            <th style="width: 2%;border-right:1px solid #000000;border-top:1px solid #000000;"></th>
        </tr>
        <tr>
            <th style="width: 2%;border-left:1px solid #000000;"></th>
            <th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 17px;">PT NIRWANA ALABARE GARMENT</th>
            <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr>
        <tr>
            <th style="width: 2%;border-left:1px solid #000000;"></th>
            <th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 17px;">LAPORAN LABA RUGI</th>
            <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr>
        <tr>
            <th style="width: 2%;border-left:1px solid #000000;"></th>
            <th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 17px;border-bottom:3px solid #000000;">Per 01 <?php
            $startdate = date("F Y",strtotime($_GET['start_date'])); 
            $enddate = date("t F Y",strtotime($_GET['end_date'])); 
            echo $startdate; echo ' S.D '; echo $enddate; ?></th>
            <th style="width: 2%;border-right:1px ridge #000000;"></th>
        </tr>
        <tr>
            <th style="width: 2%;border-left:1px solid #000000;"></th>
            <th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
            <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr>                   

        <tr>
            <th style="width: 2%;border-left:1px solid #000000;"></th>
            <th style="text-align: left;vertical-align: middle;width: 29%;"></th>
            <td style="text-align: center;vertical-align: middle;width: 5%;">Ref</td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 17%;">Komersial</td> 
            <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr>
        <tr>
            <th style="width: 2%;border-left:1px solid #000000;"></th>
            <th style="text-align: left;vertical-align: middle;width: 29%;">PENDAPATAN USAHA</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;">14.</td>
            <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
            <td style="text-align: center;vertical-align: middle;width: 15%;"></td> 
            <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr>
        <?php 
        // koneksi database
        // include '../../conn/conn.php';
        // $nama_supp=$_GET['nama_supp'];
        // $status =$_GET['status'];
        $bulan_awal = date("m",strtotime($_GET['start_date']));
        $bulan_akhir = date("m",strtotime($_GET['end_date']));  
        $tahun_awal = date("Y",strtotime($_GET['start_date']));
        $tahun_akhir = date("Y",strtotime($_GET['end_date']));
        $kata_filter = $_GET['kata_filter'];
        // menampilkan data pegawai


        $sql = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'PENDAPATAN USAHA') a INNER JOIN
            (select ind_categori2,ind_categori6,-(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori6,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa GROUP BY ind_categori6 order by no_coa asc) a) b on b.ind_categori6 = a.sub_kategori order by id asc");

        $sql2 = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'HARGA POKOK PENJUALAN') a INNER JOIN
            (select ind_categori2,ind_categori6,(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori6,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa GROUP BY ind_categori2 order by no_coa asc) a) b on b.ind_categori2 = a.sub_kategori order by id asc");

        $sql3 = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'BIAYA PENJUALAN') a INNER JOIN
            (select ind_categori2,ind_categori6,(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori6,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa WHERE ind_categori2 = 'BIAYA PENJUALAN' GROUP BY ind_categori6 order by no_coa asc) a) b on b.ind_categori6 = a.sub_kategori order by id asc");

        $sql4 = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'BIAYA ADMINISTRASI & UMUM') a INNER JOIN
            (select ind_categori2,ind_categori6,(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori6,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa WHERE ind_categori2 = 'BIAYA ADMINISTRASI & UMUM' GROUP BY ind_categori6 order by no_coa asc) a) b on b.ind_categori6 = a.sub_kategori order by id asc");

        $sql5 = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'PENDAPATAN / (BEBAN) LAIN-LAIN') a INNER JOIN
            (select ind_categori2,ind_categori6,-(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori6,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa WHERE ind_categori2 = 'PENDAPATAN / (BEBAN) LAIN-LAIN' GROUP BY ind_categori6 order by no_coa asc) a) b on b.ind_categori6 = a.sub_kategori order by id asc");

        $no = 1;
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
            <th style="width: 2%;border-left:1px solid #000000;"></th>
            <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">'.$row['sub_kategori'].'</td>
            <td style="text-align: center;vertical-align: middle;width: 5%;">'.$row['ref'].'</td>
            <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
            <td style="text-align: right;vertical-align: middle;width: 15%;">'.$pendapatan_usaha.'</td>
            <th style="width: 2%;border-right:1px solid #000000;"></th>
            </tr>';
        }
        echo '<tr style="line-height: 40px;">
        <th style="width: 2%;border-left:1px solid #000000;"></th>
        <th style="text-align: left;vertical-align: middle;width: 29%;">TOTAL PENDAPATAN</th>
        <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
        <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
        <th style="text-align: right;vertical-align: middle;width: 15%;border-top:1px solid #000000;">'.$total_pendapatan_usaha_.'</th> 
        <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr>
        <tr>
        <th style="width: 2%;border-left:1px solid #000000;"></th>
        <th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
        <th style="width: 2%;border-right:1px solid #000000;"></th>
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
            <th style="width: 2%;border-left:1px solid #000000;"></th>
            <th style="text-align: left;vertical-align: middle;width: 29%;">'.$row2['sub_kategori'].'</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;">'.$row2['ref'].'</td>
            <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
            <td style="text-align: right;vertical-align: middle;width: 15%;">'.$harga_pokok_penjualan.'</td> 
            <th style="width: 2%;border-right:1px solid #000000;"></th>
            </tr>';
        }

        $laba_rugi_kotor = $total_pendapatan_usaha - $total_harga_pokok_penjualan;
        if ($laba_rugi_kotor > 0) {
            $laba_rugi_kotor_ = number_format($laba_rugi_kotor,2);
        }else{
            $laba_rugi_kotor_ = '('.number_format(abs($laba_rugi_kotor),2).')';
        }

        echo '<tr>
        <th style="width: 2%;border-left:1px solid #000000;"></th>
        <th style="text-align: left;vertical-align: middle;width: 29%;">LABA (RUGI) KOTOR</th>
        <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
        <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
        <th style="text-align: right;vertical-align: middle;width: 15%;border-top:1px solid #000000;">'.$laba_rugi_kotor_.'</th> 
        <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr>
        <tr>
        <th style="width: 2%;border-left:1px solid #000000;"></th>
        <th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
        <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr>
        <tr>
        <th style="width: 2%;border-left:1px solid #000000;"></th>
        <th style="text-align: left;vertical-align: middle;width: 29%;">BIAYA PENJUALAN</th>
        <td style="text-align: center;vertical-align: middle;width: 5%;">16.</td>
        <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
        <td style="text-align: center;vertical-align: middle;width: 15%;"></td>
        <th style="width: 2%;border-right:1px solid #000000;"></th> 
        </tr>';


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
            <th style="width: 2%;border-left:1px solid #000000;"></th>
            <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">'.$row3['sub_kategori'].'</td>
            <td style="text-align: center;vertical-align: middle;width: 5%;">'.$row3['ref'].'</td>
            <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
            <td style="text-align: right;vertical-align: middle;width: 15%;">'.$biaya_penjualan.'</td> 
            <th style="width: 2%;border-right:1px solid #000000;"></th> 
            </tr>';
        }

        echo '<tr>
        <th style="width: 2%;border-left:1px solid #000000;"></th>
        <th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
        <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr>
        <tr>
        <th style="width: 2%;border-left:1px solid #000000;"></th>
        <th style="text-align: left;vertical-align: middle;width: 29%;">BIAYA ADMINISTRASI & UMUM</th>
        <td style="text-align: center;vertical-align: middle;width: 5%;">17.</td>
        <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
        <td style="text-align: center;vertical-align: middle;width: 15%;"></td> 
        <th style="width: 2%;border-right:1px solid #000000;"></th> 
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
            <th style="width: 2%;border-left:1px solid #000000;"></th>
            <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">'.$row4['sub_kategori'].'</td>
            <td style="text-align: center;vertical-align: middle;width: 5%;">'.$row4['ref'].'</td>
            <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
            <td style="text-align: right;vertical-align: middle;width: 15%;">'.$biaya_adm_umum.'</td>
            <th style="width: 2%;border-right:1px solid #000000;"></th>  
            </tr>';
        }

        $total_operasional = $total_biaya_penjualan + $total_biaya_adm_umum;
        if ($total_operasional > 0) {
            $total_operasional_ = number_format($total_operasional,2);
        }else{
            $total_operasional_ = '('.number_format(abs($total_operasional),2).')';
        }

        $laba_rugi_operasional = $laba_rugi_kotor - $total_operasional;
        if ($laba_rugi_operasional > 0) {
            $laba_rugi_operasional_ = number_format($laba_rugi_operasional,2);
        }else{
            $laba_rugi_operasional_ = '('.number_format(abs($laba_rugi_operasional),2).')';
        }


        echo '<tr>
        <th style="width: 2%;border-left:1px solid #000000;"></th>
        <th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
        <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr>
        <tr>
        <th style="width: 2%;border-left:1px solid #000000;"></th>
        <th style="text-align: left;vertical-align: middle;width: 29%;">TOTAL BIAYA OPERASIONAL</th>
        <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
        <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
        <th style="text-align: right;vertical-align: middle;width: 15%;border-top:1px solid #000000;">'.$total_operasional_.'</th>  
        <th style="width: 2%;border-right:1px solid #000000;"></th> 
        </tr>
        <tr>
        <th style="width: 2%;border-left:1px solid #000000;"></th>
        <th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
        <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr>
        <tr>
        <th style="width: 2%;border-left:1px solid #000000;"></th>
        <th style="text-align: left;vertical-align: middle;width: 29%;">LABA (RUGI) OPERASIONAL</th>
        <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
        <th style="text-align: center;vertical-align: middle;width: 5%;">Rp.</th>
        <th style="text-align: right;vertical-align: middle;width: 15%;">'.$laba_rugi_operasional_.'</th>
        <th style="width: 2%;border-right:1px solid #000000;"></th> 
        </tr>
        <tr>
        <th style="width: 2%;border-left:1px solid #000000;"></th>
        <th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
        <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr>
        <tr>
        <th style="width: 2%;border-left:1px solid #000000;"></th>
        <th style="text-align: left;vertical-align: middle;width: 29%;">PENDAPATAN / (BEBAN) LAIN-LAIN</th>
        <td style="text-align: center;vertical-align: middle;width: 5%;">18.</td>
        <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
        <td style="text-align: center;vertical-align: middle;width: 15%;"></td>  
        <th style="width: 2%;border-right:1px solid #000000;"></th> 
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
            <th style="width: 2%;border-left:1px solid #000000;"></th>
            <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">'.$row5['sub_kategori'].'</td>
            <td style="text-align: center;vertical-align: middle;width: 5%;">'.$row5['ref'].'</td>
            <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
            <td style="text-align: right;vertical-align: middle;width: 15%;">'.$biaya_pendapatan_lain.'</td> 
            <th style="width: 2%;border-right:1px solid #000000;"></th> 
            </tr>';
        }

        echo '<th style="width: 2%;border-left:1px solid #000000;"></th>
        <th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
        <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr>
        <tr>
        <th style="width: 2%;border-left:1px solid #000000;"></th>
        <th style="text-align: left;vertical-align: middle;width: 29%;">TOTAL PENDAPATAN / (BEBAN) LAIN-LAIN</th>
        <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
        <th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
        <th style="text-align: right;vertical-align: middle;width: 15%;border-top:1px solid #000000;">'.$total_biaya_pendapatan_lain_.'</th>
        <th style="width: 2%;border-right:1px solid #000000;"></th> 
        </tr>';

        $laba_rugi_komersil = $laba_rugi_operasional + $total_biaya_pendapatan_lain;
        if ($laba_rugi_komersil > 0) {
            $laba_rugi_komersil_ = number_format($laba_rugi_komersil,2);
        }else{
            $laba_rugi_komersil_ = '('.number_format(abs($laba_rugi_komersil),2).')';
        }

        echo '<th style="width: 2%;border-left:1px solid #000000;"></th>
        <th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
        <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr>
        <tr>
        <th style="width: 2%;border-left:1px solid #000000;"></th>
        <th style="text-align: left;vertical-align: middle;width: 29%;">LABA (RUGI) KOMERSIL</th>
        <td style="text-align: center;vertical-align: middle;width: 2%;"></td>
        <th style="text-align: center;vertical-align: middle;width: 5%;">Rp.</th>
        <th style="text-align: right;vertical-align: middle;width: 15%;">'.$laba_rugi_komersil_.'</th>
        <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr>';

        echo'<tr>
        <th style="width: 2%;border-left:1px solid #000000;border-bottom:1px solid #000000;"></th>
        <th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;border-bottom:1px solid #000000;"></th>
        <th style="width: 2%;border-right:1px solid #000000;border-bottom:1px solid #000000;"></th>
        </tr>'; 
        ?>
    </table>

</body>
</html>




