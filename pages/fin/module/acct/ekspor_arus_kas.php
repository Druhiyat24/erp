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
    header("Content-Disposition: attachment; filename=arus kas.xls");
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


    <table style="font-size: 14px; margin:auto" border="0" role="grid" cellspacing="0" width="90%">
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
            <th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 17px;">LAPORAN ARUS KAS</th>
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
            <th style="text-align: left;vertical-align: middle;width: 29%;">ARUS KAS DARI AKTIVITAS OPERASI</th>
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

$no = 1;
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
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;">LABA RUGI TAHUN BERJALAN</th>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
<td style="text-align: right;vertical-align: middle;width: 10%;">'.$laba_rugi_tb.'</td>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>';

echo '<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;">PENYESUAIAN UNTUK :</th>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td style="text-align: center;vertical-align: middle;width: 2%;"></td>
<td style="text-align: right;vertical-align: middle;width: 10%;"></td> 
<th style="width: 2%;border-right:1px solid #000000;"></th>
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
    <th style="width: 2%;border-left:1px solid #000000;"></th>
    <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">'.$row2['sub_kategori'].'</td>
    <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
    <td style="text-align: right;vertical-align: middle;width: 10%;">'.$aktivitas_operasional.'</td> 
    <th style="width: 2%;border-right:1px solid #000000;"></th>
    </tr>';
}

$untuk_aktivitas_operasi = $total_laba_rugi_tb + $total_aktivitas_operasional;
if ($untuk_aktivitas_operasi > 0) {
    $untuk_aktivitas_operasi = number_format($untuk_aktivitas_operasi,0);
}else{
    $untuk_aktivitas_operasi = '('.number_format(abs($untuk_aktivitas_operasi),0).')';
}

echo '<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;">ARUS KAS BERSIH YANG DIPEROLEH ( DIGUNAKAN ) UNTUK AKTIVITAS OPERASI</th>
<td style="text-align: center;vertical-align: middle;width: 2%;"></td>
<td style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</td>
<td style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$untuk_aktivitas_operasi.'</td>
<th style="width: 2%;border-right:1px solid #000000;"></th> 
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;">ARUS KAS DARI AKTIVITAS INVESTASI</th>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td style="text-align: center;vertical-align: middle;width: 2%;"></td>
<td style="text-align: right;vertical-align: middle;width: 10%;"></td> 
<th style="width: 2%;border-right:1px solid #000000;"></th>
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
    <th style="width: 2%;border-left:1px solid #000000;"></th>
    <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">'.$row3['sub_kategori'].'</td>
    <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
    <td style="text-align: right;vertical-align: middle;width: 10%;">'.$aktivitas_investasi.'</td> 
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
<th style="text-align: left;vertical-align: middle;width: 29%;">ARUS KAS BERSIH YANG DIPEROLEH ( DIGUNAKAN ) UNTUK AKTIVITAS INVESTASI</th>
<td style="text-align: center;vertical-align: middle;width: 2%;"></td>
<td style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</td>
<td style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_aktivitas_investasi_.'</td>
<th style="width: 2%;border-right:1px solid #000000;"></th> 
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;">ARUS KAS DARI AKTIVITAS PENDANAAN</th>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<td style="text-align: center;vertical-align: middle;width: 2%;"></td>
<td style="text-align: right;vertical-align: middle;width: 10%;"></td> 
<th style="width: 2%;border-right:1px solid #000000;"></th>
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
    <th style="width: 2%;border-left:1px solid #000000;"></th>
    <td style="text-align: left;vertical-align: middle;width: 29%;padding-left:4%;">'.$row4['sub_kategori'].'</td>
    <td style="text-align: center;vertical-align: middle;width: 5%;"></td>
    <td style="text-align: center;vertical-align: middle;width: 2%;">Rp.</td>
    <td style="text-align: right;vertical-align: middle;width: 10%;">'.$aktivitas_pendanaan.'</td> 
    <th style="width: 2%;border-right:1px solid #000000;"></th>
    </tr>';
}

    $bersih_kas_setarakas = $total_laba_rugi_tb + $total_aktivitas_operasional + $total_aktivitas_investasi + $total_aktivitas_pendanaan;
if ($bersih_kas_setarakas > 0) {
    $bersih_kas_setarakas = number_format($bersih_kas_setarakas,0);
}else{
    $bersih_kas_setarakas = '('.number_format(abs($bersih_kas_setarakas),0).')';
}
echo '<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;">ARUS KAS BERSIH YANG DIPEROLEH ( DIGUNAKAN ) UNTUK AKTIVITAS PENDANAAN</th>
<td style="text-align: center;vertical-align: middle;width: 2%;"></td>
<td style="text-align: center;vertical-align: middle;width: 5%;border-top:1px solid #000000;">Rp.</td>
<td style="text-align: right;vertical-align: middle;width: 10%;border-top:1px solid #000000;">'.$total_aktivitas_pendanaan_.'</td> 
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;">KENAIKAN / (PENURUNAN) BERSIH KAS DAN SETARA KAS</th>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<th style="text-align: center;vertical-align: middle;width: 2%;">Rp.</th>
<th style="text-align: right;vertical-align: middle;width: 10%;">'.$bersih_kas_setarakas.'</th>
<th style="width: 2%;border-right:1px solid #000000;"></th> 
</tr>
<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th colspan="4" style="text-align: center;vertical-align: middle;width: 96%;font-size: 16px;"></th>
<th style="width: 2%;border-right:1px solid #000000;"></th>
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
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;">KAS DAN SETARA KAS AWAL TAHUN</th>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<th style="text-align: center;vertical-align: middle;width: 2%;">Rp.</th>
<th style="text-align: right;vertical-align: middle;width: 10%;">'.$total_kas_awal.'</th> 
<th style="width: 2%;border-right:1px solid #000000;"></th>
</tr>';


$kas_akhir = ($total_laba_rugi_tb + $total_aktivitas_operasional + $total_aktivitas_investasi + $total_aktivitas_pendanaan) + $kas_awal;
if ($kas_akhir > 0) {
    $kas_akhir = number_format($kas_akhir,0);
}else{
    $kas_akhir = '('.number_format(abs($kas_akhir),0).')';
}
echo '<tr>
<th style="width: 2%;border-left:1px solid #000000;"></th>
<th style="text-align: left;vertical-align: middle;width: 29%;">KAS DAN SETARA KAS AKHIR TAHUN</th>
<td style="text-align: center;vertical-align: middle;width: 5%;"></td>
<th style="text-align: center;vertical-align: middle;width: 2%;">Rp.</th>
<th style="text-align: right;vertical-align: middle;width: 10%;">'.$kas_akhir.'</th> 
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




