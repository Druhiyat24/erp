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
    header("Content-Disposition: attachment; filename=neraca.xls");
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


    <table style="font-size: 14px; margin:auto" border="0" role="grid" cellspacing="0" width="50%">
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
            <th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 17px;">NERACA</th>
            <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr>
        <tr>
            <th style="width: 2%;border-left:1px solid #000000;"></th>
            <th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 17px;border-bottom:3px solid #000000;">Per <?php
            $enddate = date("t F Y",strtotime($_GET['end_date'])); 
            echo $enddate; ?></th>
            <th style="width: 2%;border-right:1px ridge #000000;"></th>
        </tr>
        <tr>
            <th style="width: 2%;border-left:1px solid #000000;"></th>
            <th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
            <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr>                   

        <tr style="line-height: 40px;">
            <th style="width: 2%;border-left:1px solid #000000;"></th>
            <th style="text-align: left;vertical-align: middle;width: 41%;">AKTIVA</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;">Ref</td>
            <td style="text-align: center;vertical-align: middle;width: 25%;"></td>
            <td style="text-align: center;vertical-align: middle;width: 25%;"></td>
            <th style="width: 2%;border-right:1px solid #000000;"></th> 
        </tr>
        <tr>
            <th style="width: 2%;border-left:1px solid #000000;"></th>
            <th colspan="4" style="text-align: center;vertical-align: middle;width: 94%;font-size: 16px;"></th>
            <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr> 
        <tr>
            <th style="width: 2%;border-left:1px solid #000000;"></th>
            <th style="text-align: left;vertical-align: middle;width: 41%;">AKTIVA LANCAR</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td style="text-align: center;vertical-align: middle;width: 25%;"></td>
            <td style="text-align: center;vertical-align: middle;width: 25%;"></td> 
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

        $no = 1;
        $aktiva_lancar = 0;
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
            <th style="width: 2%;border-left:1px solid #000000;"></th>
            <td style="text-align: left;vertical-align: middle;width: 41%;padding-left:10px;">'.$row['sub_kategori'].'</td>
            <td style="text-align: center;vertical-align: middle;width: 5%;">'.$row['ref'].'</td>
            <td style="text-align: right;vertical-align: middle;width: 25%;">'.$aktiva_lancar.'</td>
            <td style="text-align: center;vertical-align: middle;width: 25%;"></td> 
            <th style="width: 2%;border-right:1px solid #000000;"></th>
            </tr>';
        }
        echo '<tr style="line-height: 40px;">
        <th style="width: 2%;border-left:1px solid #000000;"></th>
        <th style="text-align: left;vertical-align: middle;width: 41%;">TOTAL AKTIVA LANCAR</th>
        <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
        <td style="text-align: right;vertical-align: middle;width: 25%;border-top:1px solid #000000;"></td>
        <th style="text-align: center;vertical-align: middle;width: 25%;">'.$total_aktiva_lancar_.'</th> 
        <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr>
        <tr>
        <th style="width: 2%;border-left:1px solid #000000;"></th>
        <th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
        <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr> 
        <tr style="line-height: 40px;">
        <th style="width: 2%;border-left:1px solid #000000;"></th>
        <th style="text-align: left;vertical-align: middle;width: 41%;">AKTIVA TETAP</th>
        <td style="text-align: center;vertical-align: middle;width: 5%;">07.</td>
        <td style="text-align: right;vertical-align: middle;width: 25%;"></td>
        <th style="text-align: center;vertical-align: middle;width: 25%;"></th> 
        <th style="width: 2%;border-right:1px solid #000000;"></th>
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
            <th style="width: 2%;border-left:1px solid #000000;"></th>
            <td style="text-align: left;vertical-align: middle;width: 41%;padding-left:4%;">'.$row2['sub_kategori'].'</td>
            <td style="text-align: center;vertical-align: middle;width: 5%;">'.$row2['ref'].'</td>
            <td style="text-align: right;vertical-align: middle;width: 25%;">'.$aktiva_tetap.'</td>
            <td style="text-align: center;vertical-align: middle;width: 25%;"></td> 
            <th style="width: 2%;border-right:1px solid #000000;"></th>
            </tr>';
        }
        echo '<tr style="line-height: 40px;">
        <th style="width: 2%;border-left:1px solid #000000;"></th>
        <th style="text-align: left;vertical-align: middle;width: 41%;">TOTAL AKTIVA LANCAR</th>
        <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
        <td style="text-align: right;vertical-align: middle;width: 25%;border-top:1px solid #000000;"></td>
        <th style="text-align: center;vertical-align: middle;width: 25%;">'.$total_aktiva_tetap_.'</th> 
        <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr>
        <tr>
        <th style="width: 2%;border-left:1px solid #000000;"></th>
        <th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
        <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr> ';
        $total_aktiva = $total_aktiva_lancar + $total_aktiva_tetap;
        if ($total_aktiva > 0) {
            $total_aktiva_ = number_format($total_aktiva,2);
        }else{
            $total_aktiva_ = '('.number_format(abs($total_aktiva),2).')';
        }
        echo '<tr>
        <th style="width: 2%;border-left:1px solid #000000;"></th>
        <th style="text-align: left;vertical-align: middle;width: 41%;">TOTAL AKTIVA</th>
        <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
        <td style="text-align: right;vertical-align: middle;width: 25%;"></td>
        <th style="text-align: center;vertical-align: middle;width: 25%;border-top:1px solid #000000;border-bottom:1px double #000000;">'.$total_aktiva_.'</th> 
        <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr>
        <tr>
        <th style="width: 2%;border-left:1px solid #000000;"></th>
        <th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;border-bottom:3px solid #000000;"></th>
        <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr> 
        <tr>
        <th style="width: 2%;border-left:1px solid #000000;"></th>
        <th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
        <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr> '; 
        ?>

        <tr style="line-height: 40px;">
            <th style="width: 2%;border-left:1px solid #000000;"></th>
            <th style="text-align: left;vertical-align: middle;width: 41%;">KEWAJIBAN DAN EKUITAS</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td style="text-align: center;vertical-align: middle;width: 25%;"></td>
            <td style="text-align: center;vertical-align: middle;width: 25%;"></td> 
            <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr>
        <tr>
            <th style="width: 2%;border-left:1px solid #000000;"></th>
            <th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
            <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr>
        <tr>
            <th style="width: 2%;border-left:1px solid #000000;"></th>
            <th style="text-align: left;vertical-align: middle;width: 41%;">KEWAJIBAN</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td style="text-align: center;vertical-align: middle;width: 25%;"></td>
            <td style="text-align: center;vertical-align: middle;width: 25%;"></td> 
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
                        jnl on jnl.coa_no = coa.no_coa WHERE ind_categori1 = 'LAPORAN LABA RUGI' OR no_coa = '3.40.01' GROUP BY ind_categori6 order by no_coa asc) a) b  order by id asc");

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
            <th style="width: 2%;border-left:1px solid #000000;"></th>
            <td style="text-align: left;vertical-align: middle;width: 41%;padding-left:4%;">'.$row3['sub_kategori'].'</td>
            <td style="text-align: center;vertical-align: middle;width: 5%;">'.$row3['ref'].'</td>
            <td style="text-align: right;vertical-align: middle;width: 25%;">'.$kewajiban.'</td>
            <td style="text-align: center;vertical-align: middle;width: 25%;"></td> 
            <th style="width: 2%;border-right:1px solid #000000;"></th>
            </tr>';
        }
        echo '<tr style="line-height: 40px;">
        <th style="width: 2%;border-left:1px solid #000000;"></th>
        <th style="text-align: left;vertical-align: middle;width: 41%;">TOTAL KEWAJIBAN</th>
        <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
        <td style="text-align: right;vertical-align: middle;width: 25%;border-top:1px solid #000000;"></td>
        <th style="text-align: center;vertical-align: middle;width: 25%;">'.$total_kewajiban_.'</th> 
        <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr>
        <tr>
        <th style="width: 2%;border-left:1px solid #000000;"></th>
        <th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
        <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr>
        <tr style="line-height: 40px;">
        <th style="width: 2%;border-left:1px solid #000000;"></th>
        <th style="text-align: left;vertical-align: middle;width: 41%;">EKUITAS</th>
        <td style="text-align: center;vertical-align: middle;width: 5%;">13.</td>
        <td style="text-align: right;vertical-align: middle;width: 25%;"></td>
        <th style="text-align: center;vertical-align: middle;width: 25%;"></th> 
        <th style="width: 2%;border-right:1px solid #000000;"></th>
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
            <th style="width: 2%;border-left:1px solid #000000;"></th>
            <td style="text-align: left;vertical-align: middle;width: 41%;padding-left:4%;">'.$row4['sub_kategori'].'</td>
            <td style="text-align: center;vertical-align: middle;width: 5%;">'.$row4['ref'].'</td>
            <td style="text-align: right;vertical-align: middle;width: 25%;">'.$ekuitas.'</td>
            <td style="text-align: center;vertical-align: middle;width: 25%;"></td> 
            <th style="width: 2%;border-right:1px solid #000000;"></th>
            </tr>';
        }
        echo '<tr style="line-height: 40px;">
        <th style="width: 2%;border-left:1px solid #000000;"></th>
        <th style="text-align: left;vertical-align: middle;width: 41%;">TOTAL EKUITAS</th>
        <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
        <td style="text-align: right;vertical-align: middle;width: 25%;border-top:1px solid #000000;"></td>
        <th style="text-align: center;vertical-align: middle;width: 25%;">'.$total_ekuitas_.'</th> 
        <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr>
        <tr>
        <th style="width: 2%;border-left:1px solid #000000;"></th>
        <th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
        <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr>';
        $total_kewajiban_ekuitas = $total_kewajiban + $total_ekuitas;
        if ($total_kewajiban_ekuitas > 0) {
            $total_kewajiban_ekuitas_ = number_format($total_kewajiban_ekuitas,2);
        }else{
            $total_kewajiban_ekuitas_ = '('.number_format(abs($total_kewajiban_ekuitas),2).')';
        }
        echo '<tr>
        <th style="width: 2%;border-left:1px solid #000000;"></th>
        <th style="text-align: left;vertical-align: middle;width: 41%;">TOTAL KEWAJIBAN DAN EKUITAS</th>
        <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
        <td style="text-align: right;vertical-align: middle;width: 25%;"></td>
        <th style="text-align: center;vertical-align: middle;width: 25%;border-top:1px solid #000000;border-bottom:1px double #000000;">'.$total_kewajiban_ekuitas_.'</th> 
        <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr>

        <tr>
        <th style="width: 2%;border-left:1px solid #000000;border-bottom:1px solid #000000;"></th>
        <th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;border-bottom:1px solid #000000;"></th>
        <th style="width: 2%;border-right:1px solid #000000;border-bottom:1px solid #000000;"></th>
        </tr>'; 
        ?>
    </table>

</body>
</html>




