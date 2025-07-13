<html>
<head>
    <title>Export Data Ke Excel </title>
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
        border: 1px solid #3c3c3c;
        padding: 3px 8px;
 
    }
    a{
        background: blue;
        color: #fff;
        padding: 8px 10px;
        text-decoration: none;
        border-radius: 2px;
    }
    .text {
          mso-number-format: "\@";
          /*force text*/
        }
    </style>
 
    <?php
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Summary Item Type 1.xls");
    $nama_supp=$_GET['nama_supp'];
    $start_date = date("d F Y",strtotime($_GET['start_date']));
    $end_date = date("d F Y",strtotime($_GET['end_date'])); ?>

        <h4>PAYABLE CARD STATEMENT <?php echo $nama_supp; ?><br/> PERIODE <?php echo $start_date; ?> - <?php echo $end_date; ?></h4>
 
    <table style="width:100%;font-size:10px;" border="1" >
        <tr>
            <th rowspan="2" style="text-align: center;vertical-align: middle;background-color: #FFE4E1;">No</th>
            <th rowspan="2" style="text-align: center;vertical-align: middle;background-color: #FFE4E1;">Nama Supplier</th>
            <th rowspan="2" style="text-align: center;vertical-align: middle;background-color: #FFE4E1;">Item Type</th>
            <th rowspan="2" style="text-align: center;vertical-align: middle;background-color: #FFE4E1;">Relationship</th>
            <th rowspan="2" style="text-align: center;vertical-align: middle;background-color: #FFE4E1;">Amount (Equivalent IDR)</th>
            <th rowspan="2" style="text-align: center;vertical-align: middle;background-color: #FFE4E1;">Percentage from Total</th>    
            <th rowspan="2" style="border: none;width: 50px;background-color: white;"></th>    
            <th colspan="9" style="text-align: center;vertical-align: middle;background-color: #98FB98;">Account Payable Aging Based on Due Date</th>      
            <th rowspan="2" style="border: none;width: 50px;background-color: white;"></th> 
            <th colspan="8" style="text-align: center;vertical-align: middle;background-color: #87CEFA;">Account Payable Based on Due Date Projection</th>                                                                        
        </tr>
        <tr>
            <th style="text-align: center;vertical-align: middle;background-color: #98FB98;">Current</th>
            <th style="text-align: center;vertical-align: middle;background-color: #98FB98;"><p class="text">1-30</p></th>
            <th style="text-align: center;vertical-align: middle;background-color: #98FB98;">31-60</th>
            <th style="text-align: center;vertical-align: middle;background-color: #98FB98;">61-90</th>
            <th style="text-align: center;vertical-align: middle;background-color: #98FB98;">91-120</th>
            <th style="text-align: center;vertical-align: middle;background-color: #98FB98;">121-180</th>
            <th style="text-align: center;vertical-align: middle;background-color: #98FB98;">181-360</th>
            <th style="text-align: center;vertical-align: middle;background-color: #98FB98;">>360</th>
            <th style="text-align: center;vertical-align: middle;background-color: #98FB98;">Total</th>
            <th style="text-align: center;vertical-align: middle;background-color: #87CEFA;">Due</th>
            <?php 
                include '../../conn/conn.php';
                $end_date = date("Y-m-d",strtotime($_GET['end_date']));

                $sqlbulan = mysqli_query($conn1,"select kode_tanggal,bulan,bulan_text,nama_bulan,nama_bulan_singkat,tahun, CONCAT(UPPER(SUBSTR(nama_bulan_singkat,1,1)),LOWER(SUBSTR(nama_bulan_singkat,2)), ' ',tahun) bulan_tahun from dim_date where kode_tanggal BETWEEN CONCAT(YEAR('$end_date'),LPAD(MONTH('$end_date'),2,0),'01') and CONCAT(IF(MONTH('$end_date')+5 > 12,YEAR('$end_date')+1,YEAR('$end_date')),LPAD(IF(MONTH('$end_date')+5 > 12,MOD((MONTH('$end_date')+5),12),(MONTH('$end_date')+5)),2,0),'01') GROUP BY bulan,tahun order by kode_tanggal asc");
                while($rowbulan = mysqli_fetch_array($sqlbulan)){
                    echo'<th style="text-align: center;vertical-align: middle;background-color: #87CEFA;">'.$rowbulan['bulan_tahun'].'</th>';
                }
            ?>
            <th style="text-align: center;vertical-align: middle;background-color: #87CEFA;">Total</th>
        </tr>
        <?php 
        // koneksi database
        $nama_supp=$_GET['nama_supp'];
        $start_date = date("Y-m-d",strtotime($_GET['start_date']));
        $end_date = date("Y-m-d",strtotime($_GET['end_date']));
        $bulan = date("m",strtotime($_GET['end_date']));  
        $tahun = date("Y",strtotime($_GET['end_date']));
        // menampilkan data
  
        $data = mysqli_query($conn1,"select  nama_supp,item_type1,relasi,sum(total_type) total_type, sum(due_0) due_0, sum(due_1) due_1, sum(due_2) due_2, sum(due_3) due_3, sum(due_4) due_4, sum(due_5) due_5, sum(due_6) due_6, sum(due_7) due_7, sum(due_total) due_total, sum(produe_0) produe_0, sum(produe_1) produe_1, sum(produe_2) produe_2, sum(produe_3) produe_3, sum(produe_4) produe_4, sum(produe_5) produe_5, sum(produe_6) produe_6, sum(produe_total) produe_total from (select 'bpb' id, nama_supp,item_type1,relasi,sum(end_balance_idr) total_type, sum(due_0) due_0, sum(due_1) due_1, sum(due_2) due_2, sum(due_3) due_3, sum(due_4) due_4, sum(due_5) due_5, sum(due_6) due_6, sum(due_7) due_7, sum(due_total) due_total, sum(produe_0) produe_0, sum(produe_1) produe_1, sum(produe_2) produe_2, sum(produe_3) produe_3, sum(produe_4) produe_4, sum(produe_5) produe_5, sum(produe_6) produe_6, sum(produe_total) produe_total from rpt_ap_bpb where end_balance_idr != 0 GROUP BY nama_supp,item_type1,relasi
UNION
select 'kbn' id, nama_supp,item_type1,relasi, sum(end_balance_idr) total_type, sum(due_0) due_0, sum(due_1) due_1, sum(due_2) due_2, sum(due_3) due_3, sum(due_4) due_4, sum(due_5) due_5, sum(due_6) due_6, sum(due_7) due_7, sum(due_total) due_total, sum(produe_0) produe_0, sum(produe_1) produe_1, sum(produe_2) produe_2, sum(produe_3) produe_3, sum(produe_4) produe_4, sum(produe_5) produe_5, sum(produe_6) produe_6, sum(produe_total) produe_total from rpt_ap_kbon where end_balance_idr != 0 GROUP BY nama_supp,item_type1,relasi
UNION
select 'lp' id, nama_supp,item_type1,relasi, sum(end_balance_idr) total_type, sum(due_0) due_0, sum(due_1) due_1, sum(due_2) due_2, sum(due_3) due_3, sum(due_4) due_4, sum(due_5) due_5, sum(due_6) due_6, sum(due_7) due_7, sum(due_total) due_total, sum(produe_0) produe_0, sum(produe_1) produe_1, sum(produe_2) produe_2, sum(produe_3) produe_3, sum(produe_4) produe_4, sum(produe_5) produe_5, sum(produe_6) produe_6, sum(produe_total) produe_total from rpt_ap_lp where end_balance_idr != 0 GROUP BY nama_supp,item_type1,relasi) a GROUP BY nama_supp,item_type1,relasi order by nama_supp,item_type1 asc");

    $no = 1;
    $ttl_type = 0;
    $ttl_due_0 = 0;
    $ttl_due_1 = 0;
    $ttl_due_2 = 0;
    $ttl_due_3 = 0;
    $ttl_due_4 = 0;
    $ttl_due_5 = 0;
    $ttl_due_6 = 0;
    $ttl_due_7 = 0;
    $ttl_due_total = 0;
    $ttl_produe_0 = 0;
    $ttl_produe_1 = 0;
    $ttl_produe_2 = 0;
    $ttl_produe_3 = 0;
    $ttl_produe_4 = 0;
    $ttl_produe_5 = 0;
    $ttl_produe_6 = 0;
    $ttl_produe_total = 0;
    $percen_type = 0;
    $ttl_percen = 0;

    $sqlaging = mysqli_query($conn1,"select  sum(total_type) jml_type from (select 'bpb' id, nama_supp,item_type1,relasi,sum(end_balance_idr) total_type, sum(due_0) due_0, sum(due_1) due_1, sum(due_2) due_2, sum(due_3) due_3, sum(due_4) due_4, sum(due_5) due_5, sum(due_6) due_6, sum(due_7) due_7, sum(due_total) due_total, sum(produe_0) produe_0, sum(produe_1) produe_1, sum(produe_2) produe_2, sum(produe_3) produe_3, sum(produe_4) produe_4, sum(produe_5) produe_5, sum(produe_6) produe_6, sum(produe_total) produe_total from rpt_ap_bpb where end_balance_idr != 0 GROUP BY nama_supp,item_type1,relasi
UNION
select 'kbn' id, nama_supp,item_type1,relasi, sum(end_balance_idr) total_type, sum(due_0) due_0, sum(due_1) due_1, sum(due_2) due_2, sum(due_3) due_3, sum(due_4) due_4, sum(due_5) due_5, sum(due_6) due_6, sum(due_7) due_7, sum(due_total) due_total, sum(produe_0) produe_0, sum(produe_1) produe_1, sum(produe_2) produe_2, sum(produe_3) produe_3, sum(produe_4) produe_4, sum(produe_5) produe_5, sum(produe_6) produe_6, sum(produe_total) produe_total from rpt_ap_kbon where end_balance_idr != 0 GROUP BY nama_supp,item_type1,relasi
UNION
select 'lp' id, nama_supp,item_type1,relasi, sum(end_balance_idr) total_type, sum(due_0) due_0, sum(due_1) due_1, sum(due_2) due_2, sum(due_3) due_3, sum(due_4) due_4, sum(due_5) due_5, sum(due_6) due_6, sum(due_7) due_7, sum(due_total) due_total, sum(produe_0) produe_0, sum(produe_1) produe_1, sum(produe_2) produe_2, sum(produe_3) produe_3, sum(produe_4) produe_4, sum(produe_5) produe_5, sum(produe_6) produe_6, sum(produe_total) produe_total from rpt_ap_lp where end_balance_idr != 0 GROUP BY nama_supp,item_type1,relasi) a");
        $rowaging = mysqli_fetch_array($sqlaging);
        $jml_type = isset($rowaging['jml_type']) ? $rowaging['jml_type'] : 0;

        while($row = mysqli_fetch_array($data)){
            
        $total_type = isset($row['total_type']) ? $row['total_type'] : 0;
        $jml_due_0 = isset($row['due_0']) ? $row['due_0'] : 0;
        $jml_due_1 = isset($row['due_1']) ? $row['due_1'] : 0;
        $jml_due_2 = isset($row['due_2']) ? $row['due_2'] : 0;
        $jml_due_3 = isset($row['due_3']) ? $row['due_3'] : 0;
        $jml_due_4 = isset($row['due_4']) ? $row['due_4'] : 0;
        $jml_due_5 = isset($row['due_5']) ? $row['due_5'] : 0;
        $jml_due_6 = isset($row['due_6']) ? $row['due_6'] : 0;
        $jml_due_7 = isset($row['due_7']) ? $row['due_7'] : 0;
        $jml_due_total = isset($row['due_total']) ? $row['due_total'] : 0;
        $jml_produe_0 = isset($row['produe_0']) ? $row['produe_0'] : 0;
        $jml_produe_1 = isset($row['produe_1']) ? $row['produe_1'] : 0;
        $jml_produe_2 = isset($row['produe_2']) ? $row['produe_2'] : 0;
        $jml_produe_3 = isset($row['produe_3']) ? $row['produe_3'] : 0;
        $jml_produe_4 = isset($row['produe_4']) ? $row['produe_4'] : 0;
        $jml_produe_5 = isset($row['produe_5']) ? $row['produe_5'] : 0;
        $jml_produe_6 = isset($row['produe_6']) ? $row['produe_6'] : 0;
        $jml_produe_total = isset($row['produe_total']) ? $row['produe_total'] : 0;

        $percen_type = ($total_type/$jml_type) * 100;
        $ttl_percen += $percen_type;

        $ttl_type += $total_type;
        $ttl_due_0 += $jml_due_0;
        $ttl_due_1 += $jml_due_1;
        $ttl_due_2 += $jml_due_2;
        $ttl_due_3 += $jml_due_3;
        $ttl_due_4 += $jml_due_4;
        $ttl_due_5 += $jml_due_5;
        $ttl_due_6 += $jml_due_6;
        $ttl_due_7 += $jml_due_7;
        $ttl_due_total += $jml_due_total;
        $ttl_produe_0 += $jml_produe_0;
        $ttl_produe_1 += $jml_produe_1;
        $ttl_produe_2 += $jml_produe_2;
        $ttl_produe_3 += $jml_produe_3;
        $ttl_produe_4 += $jml_produe_4;
        $ttl_produe_5 += $jml_produe_5;
        $ttl_produe_6 += $jml_produe_6;
        $ttl_produe_total += $jml_produe_total;

        echo '<tr style="font-size:12px;text-align:center;">
            <td >'.$no++.'</td>
            <td value = "'.$row['nama_supp'].'">'.$row['nama_supp'].'</td>
            <td value="'.$row['item_type1'].'">'.$row['item_type1'].'</td>     
            <td value="'.$row['relasi'].'">'.$row['relasi'].'</td>                         
            <td style="text-align:right;" value="'.$total_type.'">'.number_format($total_type,2).'</td>
            <td style="text-align:right;" value="'.$percen_type.'">'.number_format($percen_type,2).'%</td>   
            <td style="width:50px;background-color: white;border:none" value="">&nbsp;&nbsp;</td>
            <td style="text-align:right;" value="'.$jml_due_0.'">'.number_format($jml_due_0,2).'</td>
            <td style="text-align:right;" value="'.$jml_due_1.'">'.number_format($jml_due_1,2).'</td>
            <td style="text-align:right;" value="'.$jml_due_2.'">'.number_format($jml_due_2,2).'</td>
            <td style="text-align:right;" value="'.$jml_due_3.'">'.number_format($jml_due_3,2).'</td>
            <td style="text-align:right;" value="'.$jml_due_4.'">'.number_format($jml_due_4,2).'</td>
            <td style="text-align:right;" value="'.$jml_due_5.'">'.number_format($jml_due_5,2).'</td>
            <td style="text-align:right;" value="'.$jml_due_6.'">'.number_format($jml_due_6,2).'</td>
            <td style="text-align:right;" value="'.$jml_due_7.'">'.number_format($jml_due_7,2).'</td>
            <td style="text-align:right;" value="'.$jml_due_total.'">'.number_format($jml_due_total,2).'</td>
            <td style="width:50px;background-color: white;border:none" value="">&nbsp;&nbsp;</td>
            <td style="text-align:right;" value="'.$jml_produe_0.'">'.number_format($jml_produe_0,2).'</td>
            <td style="text-align:right;" value="'.$jml_produe_1.'">'.number_format($jml_produe_1,2).'</td>
            <td style="text-align:right;" value="'.$jml_produe_2.'">'.number_format($jml_produe_2,2).'</td>
            <td style="text-align:right;" value="'.$jml_produe_3.'">'.number_format($jml_produe_3,2).'</td>
            <td style="text-align:right;" value="'.$jml_produe_4.'">'.number_format($jml_produe_4,2).'</td>
            <td style="text-align:right;" value="'.$jml_produe_5.'">'.number_format($jml_produe_5,2).'</td>
            <td style="text-align:right;" value="'.$jml_produe_6.'">'.number_format($jml_produe_6,2).'</td>
            <td style="text-align:right;" value="'.$jml_produe_total.'">'.number_format($jml_produe_total,2).'</td>
             ';
        }
        ?>
    </table>

</body>
</html>




