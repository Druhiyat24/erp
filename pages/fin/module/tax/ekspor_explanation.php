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
    header("Content-Disposition: attachment; filename=explanation.xls");
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


    <table style="font-size: 14px; margin:auto" border="0" role="grid" cellspacing="0" width="80%">
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
            <th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 17px;">PENJELASAN ATAS LAPORAN KEUANGAN</th>
            <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr>
        <tr>
            <th style="width: 2%;border-left:1px solid #000000;"></th>
            <th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 17px;border-bottom:3px solid #000000;">UNTUK TAHUN YANG BERAKHIR 31 <?php
            $startdate = date("F Y",strtotime($_GET['start_date'])); 
            $enddate = date("F Y",strtotime($_GET['end_date'])); 
            echo strtoupper($enddate); ?></th>
            <th style="width: 2%;border-right:1px ridge #000000;"></th>
        </tr>
        <tr>
            <th style="width: 2%;border-left:1px solid #000000;"></th>
            <th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
            <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr>                   

        <tr>
            <th style="width: 2%;border-left:1px solid #000000;"></th>
            <th style="text-align: left;vertical-align: middle;width: 29%;">01). Kas dan Setara Kas</th>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td> 
            <th style="width: 2%;border-right:1px solid #000000;"></th>
        </tr>
        <tr>
            <th style="width: 2%;border-left:1px solid #000000;"></th>
            <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Kas dan Setara Kas Perusahaan Per 31 <?= $enddate; ?> :</td>
            <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
            <td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
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

        $sql13 = mysqli_query($conn2,"select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori = 'Modal') a LEFT JOIN
            (select '' ind_categori2,nama_modal ind_categori7, -total total from sb_modal_dasar
            UNION
            select ind_categori2,ind_categori7,(saldo + debit_idr - credit_idr) total from (select ind_categori2,ind_categori7,sum(COALESCE(saldo,0)) saldo,sum(COALESCE(debit_idr,0)) debit_idr,sum(COALESCE(credit_idr,0)) credit_idr from 
            (select no_coa nocoa,nama_coa namacoa,$kata_filter as saldo from sb_saldo_awal_tb order by no_coa asc) saldo
            left join
            (select no_coa,nama_coa,'' beg_balance,ind_categori1,ind_categori2,ind_categori3,ind_categori6,ind_categori7,id_ctg5 from mastercoa_sb order by no_coa asc) coa
            on coa.no_coa = saldo.nocoa
            left join
            (select no_coa coa_no, sum(credit) credit,sum(debit) debit,IF(sum(debit) = sum(credit),'B','NB') balance,sum(ROUND(credit * rate,2)) credit_idr,sum(ROUND(debit * rate,2)) debit_idr,IF(sum(ROUND(debit * rate,2)) = sum(ROUND(credit * rate,2)),'B','NB') balance_idr from sb_list_journal where tgl_journal BETWEEN (select tgl_awal from tbl_tgl_tb where bulan = '$bulan_awal' and tahun = '$tahun_awal') and (select tgl_akhir from tbl_tgl_tb where bulan = '$bulan_akhir' and tahun = '$tahun_akhir') group by no_coa) 
            jnl on jnl.coa_no = coa.no_coa where ind_categori7 != 'LABA / (RUGI) TAHUN BERJALAN' GROUP BY ind_categori7 order by no_coa asc) a
            UNION
            select 'EKUITAS' ind_categori2,'Laba / (Rugi) Tahun Berjalan' ind_categori7,-sum(total) total from (SELECT * FROM(select * from (select id,ref,sub_kategori from sb_kategori_laporan where status = 'Y' and kategori in ('PENDAPATAN USAHA','PENDAPATAN / (BEBAN) LAIN-LAIN')) a INNER JOIN
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
            jnl on jnl.coa_no = coa.no_coa WHERE ind_categori2 = 'BIAYA ADMINISTRASI & UMUM' GROUP BY ind_categori6 order by no_coa asc) a) b on b.ind_categori6 = a.sub_kategori order by id asc) a) a) b on b.ind_categori7 = a.sub_kategori order by id asc");

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


$no = 1;
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
    <th style="width: 2%;border-left:1px solid #000000;"></th>
    <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row1['sub_kategori'].'</td>
    <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
    <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_kas.'</td>
    <th style="width: 2%;border-right:1px solid #000000;"></th> 
    </tr>';
}
echo '<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;"></th>
<td style="text-align: center;vertical-align: middle;width: 2%;"></td>
<th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
<th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_kas_.'</th> 
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;">02). Piutang Usaha </th>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Piutang Usaha Perusahaan Per 31'; echo $enddate; echo ' :</td>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
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
    <th style="width: 2%;border-left:1px solid #000000;"></th>
    <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row2['sub_kategori'].'</td>
    <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
    <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_piutang_usaha.'</td> 
    <th style="width: 2%;border-right:1px solid #000000;"></th>
    </tr>';
}

echo '<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;"></th>
<td style="text-align: center;vertical-align: middle;width: 2%;"></td>
<th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
<th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_piutang_usaha_.'</th>
<th style="width: 2%;border-right:1px solid #000000;"></th> 
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;">03). Piutang Lain-lain</th>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Piutang Lain-lain Perusahaan Per 31'; echo $enddate; echo ' :</td>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
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
    <th style="width: 2%;border-left:1px solid #000000;"></th>
    <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row3['sub_kategori'].'</td>
    <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
    <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_piutang_lain.'</td> 
    <th style="width: 2%;border-right:1px solid #000000;"></th>
    </tr>';
}

echo '<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;"></th>
<td style="text-align: center;vertical-align: middle;width: 2%;"></td>
<th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
<th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_piutang_lain_.'</th> 
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr style="margin-top: 5px;">
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;">04). Persediaan</th>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Persediaan Perusahaan Per 31'; echo $enddate; echo ' :</td>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
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
    <th style="width: 2%;border-left:1px solid #000000;"></th>
    <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row4['sub_kategori'].'</td>
    <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
    <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_persediaan.'</td> 
    <th style="width: 2%;border-right:1px solid #000000;"></th>
    </tr>';
}

echo '<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;"></th>
<td style="text-align: center;vertical-align: middle;width: 2%;"></td>
<th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
<th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_persediaan_.'</th>
<th style="width: 2%;border-right:1px solid #000000;"></th> 
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr style="margin-top: 5px;">
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;">05). Uang Muka Pajak</th>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Uang Muka Pajak Perusahaan Per 31'; echo $enddate; echo ' :</td>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
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
    <th style="width: 2%;border-left:1px solid #000000;"></th>
    <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row5['sub_kategori'].'</td>
    <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
    <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_muka_pajak.'</td> 
    <th style="width: 2%;border-right:1px solid #000000;"></th>
    </tr>';
}

echo '<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;"></th>
<td style="text-align: center;vertical-align: middle;width: 2%;"></td>
<th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
<th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_muka_pajak_.'</th> 
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr style="margin-top: 5px;">
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;">06). Uang Muka Lain-lain</th>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Uang Muka Lain-lain Perusahaan Per 31'; echo $enddate; echo ' :</td>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
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
    <th style="width: 2%;border-left:1px solid #000000;"></th>
    <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row6['sub_kategori'].'</td>
    <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
    <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_muka_lain.'</td> 
    <th style="width: 2%;border-right:1px solid #000000;"></th>
    </tr>';
}

echo '<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;"></th>
<td style="text-align: center;vertical-align: middle;width: 2%;"></td>
<th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
<th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_muka_lain_.'</th>
<th style="width: 2%;border-right:1px solid #000000;"></th> 
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr style="margin-top: 5px;">
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;">07). Aktiva Tetap</th>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Aktiva Tetap Perusahaan Per 31'; echo $enddate; echo ' :</td>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
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
    <th style="width: 2%;border-left:1px solid #000000;"></th>
    <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row7['sub_kategori'].'</td>
    <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
    <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_aktiva_tetap.'</td> 
    <th style="width: 2%;border-right:1px solid #000000;"></th>
    </tr>';
}

echo '<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;"></th>
<td style="text-align: center;vertical-align: middle;width: 2%;"></td>
<th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
<th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_aktiva_tetap_.'</th> 
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr style="margin-top: 5px;">
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;">08). Hutang Usaha</th>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Hutang Usaha Perusahaan Per 31'; echo $enddate; echo ' :</td>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
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
    <th style="width: 2%;border-left:1px solid #000000;"></th>
    <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row8['sub_kategori'].'</td>
    <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
    <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_hutang_usaha.'</td>
    <th style="width: 2%;border-right:1px solid #000000;"></th> 
    </tr>'; 
}

echo '<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;"></th>
<td style="text-align: center;vertical-align: middle;width: 2%;"></td>
<th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
<th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_hutang_usaha_.'</th>
<th style="width: 2%;border-right:1px solid #000000;"></th> 
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr style="margin-top: 5px;">
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;">09). Hutang Bank</th>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Hutang Bank Perusahaan Per 31'; echo $enddate; echo ' :</td>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
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
    <th style="width: 2%;border-left:1px solid #000000;"></th>
    <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row9['sub_kategori'].'</td>
    <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
    <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_hutang_bank.'</td> 
    <th style="width: 2%;border-right:1px solid #000000;"></th>
    </tr>';
}

echo '<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;"></th>
<td style="text-align: center;vertical-align: middle;width: 2%;"></td>
<th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
<th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_hutang_bank_.'</th> 
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr style="margin-top: 5px;">
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;">10). Hutang Pajak</th>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Hutang Pajak Perusahaan Per 31'; echo $enddate; echo ' :</td>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
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
    <th style="width: 2%;border-left:1px solid #000000;"></th>
    <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row10['sub_kategori'].'</td>
    <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
    <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_hutang_pajak.'</td> 
    <th style="width: 2%;border-right:1px solid #000000;"></th>
    </tr>';
}

echo '<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;"></th>
<td style="text-align: center;vertical-align: middle;width: 2%;"></td>
<th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
<th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_hutang_pajak_.'</th> 
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr style="margin-top: 5px;">
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;">11). Biaya Yang Masih Harus Dibayar</th>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Biaya Yang Masih Harus Dibayar Perusahaan Per 31'; echo $enddate; echo ' :</td>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
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
    <th style="width: 2%;border-left:1px solid #000000;"></th>
    <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row11['sub_kategori'].'</td>
    <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
    <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_masih_harus_dibayar.'</td> 
    <th style="width: 2%;border-right:1px solid #000000;"></th>
    </tr>';
}

echo '<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;"></th>
<td style="text-align: center;vertical-align: middle;width: 2%;"></td>
<th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
<th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_masih_harus_dibayar_.'</th> 
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr style="margin-top: 5px;">
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;">12). Hutang Lain-lain</th>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Hutang Lain-lain Perusahaan Per 31'; echo $enddate; echo ' :</td>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
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
    <th style="width: 2%;border-left:1px solid #000000;"></th>
    <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row12['sub_kategori'].'</td>
    <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
    <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_hutang_lain.'</td>
    <th style="width: 2%;border-right:1px solid #000000;"></th> 
    </tr>';
}

echo '<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;"></th>
<td style="text-align: center;vertical-align: middle;width: 2%;"></td>
<th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
<th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_hutang_lain_.'</th> 
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr style="margin-top: 5px;">
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;">13). Modal</th>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Modal Perusahaan Per 31'; echo $enddate; echo ' :</td>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
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
    <th style="width: 2%;border-left:1px solid #000000;"></th>
    <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row13['sub_kategori'].'</td>
    <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
    <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_modal.'</td> 
    <th style="width: 2%;border-right:1px solid #000000;"></th>
    </tr>';
}

echo '<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;"></th>
<td style="text-align: center;vertical-align: middle;width: 2%;"></td>
<th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
<th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_modal_.'</th> 
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr style="margin-top: 5px;">
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;">14). Pendapatan Usaha</th>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Pendapatan Usaha Perusahaan Per 31'; echo $enddate; echo ' :</td>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
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
    <th style="width: 2%;border-left:1px solid #000000;"></th>
    <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row14['sub_kategori'].'</td>
    <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
    <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_pendapatan_usaha.'</td> 
    <th style="width: 2%;border-right:1px solid #000000;"></th>
    </tr>';
}

echo '<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;"></th>
<td style="text-align: center;vertical-align: middle;width: 2%;"></td>
<th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
<th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_pendapatan_usaha_.'</th> 
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr style="margin-top: 5px;">
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;">15). Harga Pokok Penjualan</th>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Harga Pokok Penjualan Perusahaan Per 31'; echo $enddate; echo ' :</td>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
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
    <th style="width: 2%;border-left:1px solid #000000;"></th>
    <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row15['sub_kategori'].'</td>
    <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
    <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_hpp.'</td> 
    <th style="width: 2%;border-right:1px solid #000000;"></th>
    </tr>';
}

echo '<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;"></th>
<td style="text-align: center;vertical-align: middle;width: 2%;"></td>
<th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
<th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_hpp_.'</th> 
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr style="margin-top: 5px;">
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;">16). Biaya Penjualan</th>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Biaya Penjualan Perusahaan Per 31'; echo $enddate; echo ' :</td>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
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
    <th style="width: 2%;border-left:1px solid #000000;"></th>
    <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row16['sub_kategori'].'</td>
    <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
    <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_biaya_penjualan.'</td>
    <th style="width: 2%;border-right:1px solid #000000;"></th> 
    </tr>';
}

echo '<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;"></th>
<td style="text-align: center;vertical-align: middle;width: 2%;"></td>
<th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
<th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_biaya_penjualan_.'</th> 
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr style="margin-top: 5px;">
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;">17). Biaya Administrasi & Umum</th>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Biaya Administrasi & Umum Perusahaan Per 31'; echo $enddate; echo ' :</td>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
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
    <th style="width: 2%;border-left:1px solid #000000;"></th>
    <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row17['sub_kategori'].'</td>
    <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
    <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_adm_umum.'</td> 
    <th style="width: 2%;border-right:1px solid #000000;"></th>
    </tr>';
}

echo '<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;"></th>
<td style="text-align: center;vertical-align: middle;width: 2%;"></td>
<th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
<th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_adm_umum_.'</th> 
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr style="margin-top: 5px;">
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;">18). Pendapatan (Biaya) Lain-lain</th>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">Saldo Pendapatan (Biaya) Lain-lain Perusahaan Per 31'; echo $enddate; echo ' :</td>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td colspan="2" style="text-align: center;vertical-align: middle;width: 12%;"></td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
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
    <th style="width: 2%;border-left:1px solid #000000;"></th>
    <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">- '.$row18['sub_kategori'].'</td>
    <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
    <td style="text-align: right;vertical-align: middle;width: 10%;">'.$exp_pendapatan_biaya_lain.'</td> 
    <th style="width: 2%;border-right:1px solid #000000;"></th>
    </tr>';
}

echo '<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;"></th>
<td style="text-align: center;vertical-align: middle;width: 2%;"></td>
<th style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</th>
<th style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_exp_pendapatan_biaya_lain_.'</th> 
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>';


// <tr>
// <th style="width: 2%;border-left:1px solid #000000;"></th>
// <th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
// <th style="width: 2%;border-right:1px solid #000000;"></th>
// </tr>


echo'<tr>
<th style="width: 2%;border-left:1px solid #000000;border-bottom:1px solid #000000;"></th>
<th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;border-bottom:1px solid #000000;"></th>
<th style="width: 2%;border-right:1px solid #000000;border-bottom:1px solid #000000;"></th>
</tr>'; 
?>
</table>

</body>
</html>




