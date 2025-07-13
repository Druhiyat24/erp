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
    </style>
 
    <?php
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=KARTU AR DETAIL.xls");
    $nama_supp =$_GET['nama_supp'];
    $bln1 =$_GET['bln1'];
    $bln2 =$_GET['bln2'];
    $bln3 =$_GET['bln3'];
    $bln4 =$_GET['bln4'];
    $bln5 =$_GET['bln5'];
    $bln6 =$_GET['bln6'];
    $thn1 =$_GET['thn1'];
    $thn2 =$_GET['thn2'];
    $thn3 =$_GET['thn3'];
    $thn4 =$_GET['thn4'];
    $thn5 =$_GET['thn5'];
    $thn6 =$_GET['thn6'];
    $start_date = date("d F Y",strtotime($_GET['start_date']));
    $end_date = date("d F Y",strtotime($_GET['end_date'])); ?>

        <div class="header_title">
        KARTU AR DETAIL
        <br />
        Period : <?= $start_date; ?> To <?= $end_date; ?>
    </div>
        <br />
 
    <table style="width:100%;font-size:10px;" border="1">
        <tr align="center">
            <th rowspan="2" style="text-align: center;">
                Customer
            </th>
            <th rowspan="2" style="text-align: center;">
                No Invoice
            </th>
            <th rowspan="2" style="text-align: center;">
                Invoice Date
            </th>
            <th rowspan="2" style="text-align: center;">
                Due Date
            </th>
            <th rowspan="2" style="text-align: center;">
                TOP
            </th>
            <th rowspan="2" style="text-align: center;">
                Curr
            </th>
            <th rowspan="2" style="text-align: center;">
                Rate
            </th>
            <th rowspan="2" style="text-align: center;">
                Beginning Balance
            </th>
            <th rowspan="2" style="text-align: center;">
                Addition
            </th>
            <th rowspan="2" style="text-align: center;">
                Deduction
            </th>
            <th rowspan="2" style="text-align: center;">
                Ending Balance
            </th>
            <th rowspan="2" style="text-align: center;">
                Equivalent IDR
            </th>
            <th colspan="9" style="text-align: center;">
                Receivable Aging Based on Due Date
            </th>
            <th rowspan="2" style="text-align: center;width: 20px;"></th>
            <th colspan="8" style="text-align: center;">
                Receivable Due Date Projection
            </th>
        </tr>
        <tr>
            <th style="text-align: center;">
                 Not Due
            </th>
            <th class="text" style="text-align: center;">
                 <p class="text">01-30</p>
            </th>
            <th style="text-align: center;">
                 31-60
            </th>
            <th style="text-align: center;">
                 61-90
            </th>
            <th style="text-align: center;">
                 91-120
            </th>
            <th style="text-align: center;">
                 121-180
            </th>
            <th style="text-align: center;">
                 181-360
            </th>
            <th style="text-align: center;">
                 >360
            </th>
            <th style="text-align: center;">
                 Total            
            </th>
            <th style="text-align: center;">
                 Already Due
            </th>
            <th style="text-align: center;">
                 <?= $bln1.'-'.$thn1 ?>
            </th>
            <th style="text-align: center;">
                 <?= $bln2.'-'.$thn2 ?>
            </th>
            <th style="text-align: center;">
                 <?= $bln3.'-'.$thn3 ?>
            </th>
            <th style="text-align: center;">
                 <?= $bln4.'-'.$thn4 ?>
            </th>
            <th style="text-align: center;">
                 <?= $bln5.'-'.$thn5 ?>
            </th>
            <th style="text-align: center;">
                 <?= $bln6.'-'.$thn6 ?>
            </th>
            <th style="text-align: center;">
                 Total            
            </th>
        </tr>
        <?php 
        // koneksi database
        include '../../conn/conn.php';
        $nama_supp=$_GET['nama_supp'];
        $startdate = date("Y-m-d",strtotime($_GET['start_date']));
        $enddate = date("Y-m-d",strtotime($_GET['end_date']));
        // menampilkan data pegawai
  

        $sql = mysqli_query($conn2,"select no_invoice,customer,inv_date,tgl_inv,id_customer,curr,top,amount,amount1,duedate,no_invoice1,coalesce(bayar,0) bayar,no_invoice2,bayar2,rate,shipp, diff_top,ready_due, IF(bln_due = fil_bln1 and thn_due = fil_thn1,amount1,'0') jml_bln1,IF(bln_due = fil_bln2 and thn_due = fil_thn2,amount1,'0') jml_bln2,IF(bln_due = fil_bln3 and thn_due = fil_thn3,amount1,'0') jml_bln3,IF(bln_due = fil_bln4 and thn_due = fil_thn4,amount1,'0') jml_bln4,IF(bln_due = fil_bln5 and thn_due = fil_thn5,amount1,'0') jml_bln5,IF(bln_due = fil_bln6 and thn_due = fil_thn6,amount1,'0') jml_bln6 from (select no_invoice,customer,inv_date,tgl_inv,id_customer,curr,top,amount,amount1,duedate,no_invoice1,bayar,no_invoice2,bayar2,rate,shipp, diff_top, bln_due, thn_due, LPAD(IF(fil_bln1 <= 12,fil_bln1,(fil_bln1 - 12)),2,0) fil_bln1,LPAD(IF(fil_bln2 <= 12,fil_bln2,(fil_bln2 - 12)),2,0) fil_bln2,LPAD(IF(fil_bln3 <= 12,fil_bln3,(fil_bln3 - 12)),2,0) fil_bln3,LPAD(IF(fil_bln4 <= 12,fil_bln4,(fil_bln4 - 12)),2,0) fil_bln4,LPAD(IF(fil_bln5 <= 12,fil_bln5,(fil_bln5 - 12)),2,0) fil_bln5, LPAD(IF(fil_bln6 <= 12,fil_bln6,(fil_bln6 - 12)),2,0) fil_bln6,LPAD(IF(fil_bln1 <= 12,fil_thn,(fil_thn + 1)),4,0) fil_thn1,LPAD(IF(fil_bln2 <= 12,fil_thn,(fil_thn + 1)),4,0) fil_thn2,LPAD(IF(fil_bln3 <= 12,fil_thn,(fil_thn + 1)),4,0) fil_thn3, LPAD(IF(fil_bln4 <= 12,fil_thn,(fil_thn + 1)),4,0) fil_thn4,LPAD(IF(fil_bln5 <= 12,fil_thn,(fil_thn + 1)),4,0) fil_thn5, LPAD(IF(fil_bln6 <= 12,fil_thn,(fil_thn + 1)),4,0) fil_thn6, ready_due from (select no_invoice,customer,inv_date,tgl_inv,id_customer,curr,top,amount,amount1,duedate,no_invoice1,bayar,no_invoice2,bayar2,rate,shipp,DATEDIFF('$enddate',duedate) diff_top, DATE_FORMAT(duedate,'%m') bln_due, DATE_FORMAT(duedate,'%Y') thn_due,DATE_FORMAT('$enddate','%m') fil_bln1,LPAD(DATE_FORMAT('$enddate','%m') + 1,2,0) fil_bln2, LPAD(DATE_FORMAT('$enddate','%m') + 2,2,0) fil_bln3,LPAD(DATE_FORMAT('$enddate','%m') + 3,2,0) fil_bln4,LPAD(DATE_FORMAT('$enddate','%m') + 4,2,0) fil_bln5,LPAD(DATE_FORMAT('$enddate','%m') + 5,2,0) fil_bln6, DATE_FORMAT('$enddate','%Y') fil_thn, IF(duedate <= '$enddate',amount1,0) ready_due from 
(select distinct a.no_invoice AS no_invoice, UPPER(b.supplier) AS customer,a.sj_date inv_date,a.sj_date tgl_inv, b.Id_Supplier AS id_customer, a.curr,f.top,
                                          FORMAT((d.grand_total), 2) AS amount, if(a.curr = 'IDR',round((d.grand_total),0),round((d.grand_total), 2)) AS amount1,if(h.kontrabon_date is null, DATE_ADD(DATE_FORMAT(a.sj_date, '%Y-%m-%d'), INTERVAL f.top DAY) ,DATE_ADD(h.kontrabon_date, INTERVAL f.top DAY)) AS duedate,a.shipp
                                   FROM  tbl_book_invoice AS a INNER JOIN 
                                          mastersupplier AS b ON a.id_customer = b.id_supplier INNER JOIN 
                                          tbl_type AS c ON a.id_type = c.id_type INNER JOIN
                                          tbl_invoice_pot AS d ON a.id = d.id_book_invoice INNER JOIN
                                         tbl_master_top AS f ON a.id_top = f.id left join 
                                          tbl_duedate AS h ON a.id = h.id_invoice left join
                                        saldoawal_ar as g on g.no_invoice = a.no_invoice
                                        where g.no_invoice is null and a.sj_date between '2022-05-01' and '$enddate'
union                                                                     
select no_invoice, customer, inv_date, sj_date as tgl_inv,id_customer, curr, top, FORMAT((grand_total), 2) AS amount, if(curr = 'IDR',round((grand_total),0),round((grand_total), 2)) AS amount1, due_date,shipp from saldoawal_ar where no_invoice not like '%DN/%') inv LEFT JOIN
(select a.no_ref as no_invoice1, sum(a.amount) as bayar from tbl_alokasi_detail a inner join tbl_alokasi b on b.no_alk = a.no_alk where a.status != 'CANCEL' and b.tgl_alk between '$startdate' and '$enddate' and a.total != '0' group by a.no_ref) byr on byr.no_invoice1 = inv.no_invoice LEFT JOIN
(select a.no_ref as no_invoice2, sum(a.amount) as bayar2 from tbl_alokasi_detail a inner join tbl_alokasi b on b.no_alk = a.no_alk where a.status != 'CANCEL' and b.tgl_alk < '$startdate' and a.total != '0' group by a.no_ref) byr2 on byr2.no_invoice2 = inv.no_invoice JOIN
(select IF((select id from tbl_tgl_tb where tgl_akhir = '$enddate') != '',(select rate from masterrate where tanggal = '$enddate' and v_codecurr = 'HARIAN'),(select rate from masterrate where tanggal = '$enddate' and v_codecurr = 'PAJAK')) rate) rt) a) a");

        $no = 1;

        while($dli = mysqli_fetch_array($sql)){
            $bayar2 = isset($dli['bayar2']) ? $dli['bayar2'] : 0;
            $tanggal = $dli['inv_date'];
            $duedate = $dli['duedate'];
            $curr = $dli['curr'];
            $rates = $dli['rate'];
            $diff_top = $dli['diff_top'];
            $jmlbln1 = isset($dli['jml_bln1']) ? $dli['jml_bln1'] : 0;
            $jmlbln2 = isset($dli['jml_bln2']) ? $dli['jml_bln2'] : 0;
            $jmlbln3 = isset($dli['jml_bln3']) ? $dli['jml_bln3'] : 0;
            $jmlbln4 = isset($dli['jml_bln4']) ? $dli['jml_bln4'] : 0;
            $jmlbln5 = isset($dli['jml_bln5']) ? $dli['jml_bln5'] : 0;
            $jmlbln6 = isset($dli['jml_bln6']) ? $dli['jml_bln6'] : 0;
                if($tanggal >= $startdate){
                $sal_awl = 0;
            }   
            else{
                $sal_awl = $dli['amount1'] - $bayar2;
            }

            if($tanggal >= $startdate){
                $tambah = $dli['amount1'] - $bayar2 ;
            }   
            else{
                $tambah = 0;
            }

            $bayar = isset($dli['bayar']) ? $dli['bayar'] : 0;

            $total = ($sal_awl + $tambah) - $bayar;

            if($curr == 'USD'){
                $eqv_idr =(($sal_awl + $tambah) - $bayar) * $rates;
                $rate = $rates;
            }   
            else{
                $eqv_idr = ($sal_awl + $tambah) - $bayar;
                $rate = 1;
            }

            if ($jmlbln1 > 0 && $duedate >= $enddate) {
                $jml_bln1 = $eqv_idr;
            }else{
                $jml_bln1 = 0;
            }
            if ($jmlbln2 <= 0) {
                $jml_bln2 = 0;
            }else{
                $jml_bln2 = $eqv_idr;
            }
            if ($jmlbln3 <= 0) {
                $jml_bln3 = 0;
            }else{
                $jml_bln3 = $eqv_idr;
            }
            if ($jmlbln4 <= 0) {
                $jml_bln4 = 0;
            }else{
                $jml_bln4 = $eqv_idr;
            }
            if ($jmlbln5 <= 0) {
                $jml_bln5 = 0;
            }else{
                $jml_bln5 = $eqv_idr;
            }
            if ($jmlbln6 <= 0) {
                $jml_bln6 = 0;
            }else{
                $jml_bln6 = $eqv_idr;
            }

            if ($total <= '0') {
                $amt_aging_0 = 0;
                $amt_aging_1 = 0;
                $amt_aging_2 = 0;
                $amt_aging_3 = 0;
                $amt_aging_4 = 0;
                $amt_aging_5 = 0;
                $amt_aging_6 = 0;
                $amt_aging_7 = 0;
                $tot_aging = 0;
                $tot_aging2 = 0;
                $readydue = 0;

            }else{
                if($duedate < $enddate){
                    $readydue = $eqv_idr;
                }else{
                    $readydue = 0;
                }  
                $tot_aging2 = $eqv_idr;
                if ($diff_top <= 0) {
                    $amt_aging_0 = $eqv_idr;
                    $amt_aging_1 = 0;
                    $amt_aging_2 = 0;
                    $amt_aging_3 = 0;
                    $amt_aging_4 = 0;
                    $amt_aging_5 = 0;
                    $amt_aging_6 = 0;
                    $amt_aging_7 = 0;
                    $tot_aging = $eqv_idr;
                }

                if ($diff_top > 0 && $diff_top <= 30) {
                    $amt_aging_0 = 0;
                    $amt_aging_1 = $eqv_idr;
                    $amt_aging_2 = 0;
                    $amt_aging_3 = 0;
                    $amt_aging_4 = 0;
                    $amt_aging_5 = 0;
                    $amt_aging_6 = 0;
                    $amt_aging_7 = 0;
                    $tot_aging = $eqv_idr;
                }

                if ($diff_top > 30 && $diff_top <= 60) {
                    $amt_aging_0 = 0;
                    $amt_aging_1 = 0;
                    $amt_aging_2 = $eqv_idr;
                    $amt_aging_3 = 0;
                    $amt_aging_4 = 0;
                    $amt_aging_5 = 0;
                    $amt_aging_6 = 0;
                    $amt_aging_7 = 0;
                    $tot_aging = $eqv_idr;
                }

                if ($diff_top > 60 && $diff_top <= 90) {
                    $amt_aging_0 = 0;
                    $amt_aging_1 = 0;
                    $amt_aging_2 = 0;
                    $amt_aging_3 = $eqv_idr;
                    $amt_aging_4 = 0;
                    $amt_aging_5 = 0;
                    $amt_aging_6 = 0;
                    $amt_aging_7 = 0;
                    $tot_aging = $eqv_idr;
                }

                if ($diff_top > 90 && $diff_top <= 120) {
                    $amt_aging_0 = 0;
                    $amt_aging_1 = 0;
                    $amt_aging_2 = 0;
                    $amt_aging_3 = 0;
                    $amt_aging_4 = $eqv_idr;
                    $amt_aging_5 = 0;
                    $amt_aging_6 = 0;
                    $amt_aging_7 = 0;
                    $tot_aging = $eqv_idr;
                }

                if ($diff_top > 120 && $diff_top <= 180) {
                    $amt_aging_0 = 0;
                    $amt_aging_1 = 0;
                    $amt_aging_2 = 0;
                    $amt_aging_3 = 0;
                    $amt_aging_4 = 0;
                    $amt_aging_5 = $eqv_idr;
                    $amt_aging_6 = 0;
                    $amt_aging_7 = 0;
                    $tot_aging = $eqv_idr;
                }

                if ($diff_top > 180 && $diff_top <= 360) {
                    $amt_aging_0 = 0;
                    $amt_aging_1 = 0;
                    $amt_aging_2 = 0;
                    $amt_aging_3 = 0;
                    $amt_aging_4 = 0;
                    $amt_aging_5 = 0;
                    $amt_aging_6 = $eqv_idr;
                    $amt_aging_7 = 0;
                    $tot_aging = $eqv_idr;
                }

                if ($diff_top > 360) {
                    $amt_aging_0 = 0;
                    $amt_aging_1 = 0;
                    $amt_aging_2 = 0;
                    $amt_aging_3 = 0;
                    $amt_aging_4 = 0;
                    $amt_aging_5 = 0;
                    $amt_aging_6 = 0;
                    $amt_aging_7 = $eqv_idr;
                    $tot_aging = $eqv_idr;
                }

            }

        if ($sal_awl <= 0 && $tambah <= 0 && $bayar <= 0 && $total <= 0) {

        }   else{
        echo'
            <tr>
                <td align="center">
                    '.$dli['customer'].'
                </td>
               <td align="center">
                    '.$dli['no_invoice'].'
                </td>
                <td align="center">
                    '.$dli['inv_date'].'
                </td>
                 <td align="center">
                    '.$dli['duedate'].'
                </td>
                <td align="center">
                    '.$dli['top'].'
                </td>
                <td align="center">
                    '.$dli['curr'].'
                </td>
                <td align="center">
                    '.$rate.'
                </td>
                <td align="center">
                    '.$sal_awl.'
                </td>                
                <td align="center">
                    '.$tambah.'
                </td>
                <td align="center">
                    '.$bayar.'
                </td>
                <td align="right">
                    '.$total.'
                </td>
                <td align="right">
                    '.$eqv_idr.'
                </td>
                <td align="right">
                    '.$amt_aging_0.'
                </td>
                <td align="right">
                    '.$amt_aging_1.'
                </td>
                <td align="right">
                    '.$amt_aging_2.'
                </td>
                <td align="right">
                    '.$amt_aging_3.'
                </td>
                <td align="right">
                    '.$amt_aging_4.'
                </td>
                <td align="right">
                    '.$amt_aging_5.'
                </td>
                <td align="right">
                    '.$amt_aging_6.'
                </td>
                <td align="right">
                    '.$amt_aging_7.'
                </td>
                <td align="right">
                    '.$tot_aging.'
                </td>
                <td align="right">
                   
                </td>
                <td align="right">
                    '.$readydue.'
                </td>
                <td align="right">
                    '.$jml_bln1.'
                </td>
                <td align="right">
                    '.$jml_bln2.'
                </td>
                <td align="right">
                    '.$jml_bln3.'
                </td>
                <td align="right">
                    '.$jml_bln4.'
                </td>
                <td align="right">
                    '.$jml_bln5.'
                </td>
                <td align="right">
                    '.$jml_bln6.'
                </td>
                <td align="right">
                    '.$tot_aging2.'
                </td>
            </tr>';
         
        ?>
        <?php 
        }
    }
        ?>
    </table>

</body>
</html>




