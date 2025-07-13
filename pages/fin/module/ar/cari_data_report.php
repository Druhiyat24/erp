<?php
include '../../conn/conn.php';
ini_set('date.timezone', 'Asia/Jakarta');

$customer = $_POST['customer'];
$start_date = date("Y-m-d",strtotime($_POST['start_date']));
$end_date = date("Y-m-d",strtotime($_POST['end_date']));

// echo "< -- >";
// echo $no_kbon;

if ($customer == 'ALL') {
   $sql = mysqli_query($conn1,"select no_invoice,customer,inv_date,tgl_inv,id_customer,curr,top,amount,amount1,duedate,no_invoice1,coalesce(bayar,0) bayar,no_invoice2,bayar2,rate,shipp, diff_top,ready_due, IF(bln_due = fil_bln1 and thn_due = fil_thn1,amount1,'0') jml_bln1,IF(bln_due = fil_bln2 and thn_due = fil_thn2,amount1,'0') jml_bln2,IF(bln_due = fil_bln3 and thn_due = fil_thn3,amount1,'0') jml_bln3,IF(bln_due = fil_bln4 and thn_due = fil_thn4,amount1,'0') jml_bln4,IF(bln_due = fil_bln5 and thn_due = fil_thn5,amount1,'0') jml_bln5,IF(bln_due = fil_bln6 and thn_due = fil_thn6,amount1,'0') jml_bln6 from (select no_invoice,customer,inv_date,tgl_inv,id_customer,curr,top,amount,amount1,duedate,no_invoice1,bayar,no_invoice2,bayar2,rate,shipp, diff_top, bln_due, thn_due, LPAD(IF(fil_bln1 <= 12,fil_bln1,(fil_bln1 - 12)),2,0) fil_bln1,LPAD(IF(fil_bln2 <= 12,fil_bln2,(fil_bln2 - 12)),2,0) fil_bln2,LPAD(IF(fil_bln3 <= 12,fil_bln3,(fil_bln3 - 12)),2,0) fil_bln3,LPAD(IF(fil_bln4 <= 12,fil_bln4,(fil_bln4 - 12)),2,0) fil_bln4,LPAD(IF(fil_bln5 <= 12,fil_bln5,(fil_bln5 - 12)),2,0) fil_bln5, LPAD(IF(fil_bln6 <= 12,fil_bln6,(fil_bln6 - 12)),2,0) fil_bln6,LPAD(IF(fil_bln1 <= 12,fil_thn,(fil_thn + 1)),4,0) fil_thn1,LPAD(IF(fil_bln2 <= 12,fil_thn,(fil_thn + 1)),4,0) fil_thn2,LPAD(IF(fil_bln3 <= 12,fil_thn,(fil_thn + 1)),4,0) fil_thn3, LPAD(IF(fil_bln4 <= 12,fil_thn,(fil_thn + 1)),4,0) fil_thn4,LPAD(IF(fil_bln5 <= 12,fil_thn,(fil_thn + 1)),4,0) fil_thn5, LPAD(IF(fil_bln6 <= 12,fil_thn,(fil_thn + 1)),4,0) fil_thn6, ready_due from (select no_invoice,customer,inv_date,tgl_inv,id_customer,curr,top,amount,amount1,duedate,no_invoice1,bayar,no_invoice2,bayar2,rate,shipp,DATEDIFF('$end_date',duedate) diff_top, DATE_FORMAT(duedate,'%m') bln_due, DATE_FORMAT(duedate,'%Y') thn_due,DATE_FORMAT('$end_date','%m') fil_bln1,LPAD(DATE_FORMAT('$end_date','%m') + 1,2,0) fil_bln2, LPAD(DATE_FORMAT('$end_date','%m') + 2,2,0) fil_bln3,LPAD(DATE_FORMAT('$end_date','%m') + 3,2,0) fil_bln4,LPAD(DATE_FORMAT('$end_date','%m') + 4,2,0) fil_bln5,LPAD(DATE_FORMAT('$end_date','%m') + 5,2,0) fil_bln6, DATE_FORMAT('$end_date','%Y') fil_thn, IF(duedate <= '$end_date',amount1,0) ready_due from 
(select distinct a.no_invoice AS no_invoice, UPPER(b.supplier) AS customer,a.sj_date inv_date,a.sj_date tgl_inv, b.Id_Supplier AS id_customer, a.curr,f.top,
                                          FORMAT((d.grand_total), 2) AS amount, if(a.curr = 'IDR',round((d.grand_total),0),round((d.grand_total), 2)) AS amount1,if(h.kontrabon_date is null, DATE_ADD(DATE_FORMAT(a.sj_date, '%Y-%m-%d'), INTERVAL f.top DAY) ,DATE_ADD(h.kontrabon_date, INTERVAL f.top DAY)) AS duedate,a.shipp
                                   FROM  tbl_book_invoice AS a INNER JOIN 
                                          mastersupplier AS b ON a.id_customer = b.id_supplier INNER JOIN 
                                          tbl_type AS c ON a.id_type = c.id_type INNER JOIN
                                          tbl_invoice_pot AS d ON a.id = d.id_book_invoice INNER JOIN
                                         tbl_master_top AS f ON a.id_top = f.id left join 
                                          tbl_duedate AS h ON a.id = h.id_invoice left join
                                        saldoawal_ar as g on g.no_invoice = a.no_invoice
                                        where g.no_invoice is null and a.sj_date between '2022-05-01' and '$end_date'
union                                                                     
select no_invoice, customer, inv_date, sj_date as tgl_inv,id_customer, curr, top, FORMAT((grand_total), 2) AS amount, if(curr = 'IDR',round((grand_total),0),round((grand_total), 2)) AS amount1, due_date,shipp from saldoawal_ar where no_invoice not like '%DN/%') inv LEFT JOIN
(select a.no_ref as no_invoice1, sum(a.amount) as bayar from tbl_alokasi_detail a inner join tbl_alokasi b on b.no_alk = a.no_alk where a.status != 'CANCEL' and b.tgl_alk between '$start_date' and '$end_date' and a.total != '0' group by a.no_ref) byr on byr.no_invoice1 = inv.no_invoice LEFT JOIN
(select a.no_ref as no_invoice2, sum(a.amount) as bayar2 from tbl_alokasi_detail a inner join tbl_alokasi b on b.no_alk = a.no_alk where a.status != 'CANCEL' and b.tgl_alk < '$start_date' and a.total != '0' group by a.no_ref) byr2 on byr2.no_invoice2 = inv.no_invoice JOIN
(select IF((select id from tbl_tgl_tb where tgl_akhir = '$end_date') != '',(select rate from masterrate where tanggal = '$end_date' and v_codecurr = 'HARIAN'),(select rate from masterrate where tanggal = '$end_date' and v_codecurr = 'PAJAK')) rate) rt) a) a");
}


            $table = '';
            $bayar = 0;
            $bayar_ = 0;
            $sal_awl = 0;
            $sal_awl_ = 0;
            $tambah = 0;
            $tambah_ = 0;
            $total = 0;
            $total_ = 0;
            $byr_idr = 0;
            $byr_usd = 0;
            $ahr_usd = 0;
            $ahr_idr = 0;
            $eqv_idr = 0;
            $amt_aging_0 = 0;
            $amt_aging_1 = 0;
            $amt_aging_2 = 0;
            $amt_aging_3 = 0;
            $amt_aging_4 = 0;
            $amt_aging_5 = 0;
            $amt_aging_6 = 0;
            $amt_aging_7 = 0;
            $tot_aging = 0;
            $rate = 0;
            $tot_aging2 = 0;
            $readydue = 0;
            $jml_bln1 = 0;
            $jml_bln2 = 0;
            $jml_bln3 = 0;
            $jml_bln4 = 0;
            $jml_bln5 = 0;
            $jml_bln6 = 0;
            $kata = '';
            $eqv_idr_ = 0;
            $amt_aging_0_ = 0;
            $amt_aging_1_ = 0;
            $amt_aging_2_ = 0;
            $amt_aging_3_ = 0;
            $amt_aging_4_ = 0;
            $amt_aging_5_ = 0;
            $amt_aging_6_ = 0;
            $amt_aging_7_ = 0;
            $tot_aging_ = 0;
            $readydue_ = 0;
            $jml_bln1_ = 0;
            $jml_bln2_ = 0;
            $jml_bln3_ = 0;
            $jml_bln4_ = 0;
            $jml_bln5_ = 0;
            $jml_bln6_ = 0;
            $tot_aging__ = 0;

		while ($row = mysqli_fetch_assoc($sql)) {

            if($row['inv_date'] >= $start_date){
                $sal_awl = 0;
            }   
            else{
                $sal_awl = $row['amount1'] - $row['bayar2'];
            }

            if($row['inv_date'] >= $start_date){
                $tambah = $row['amount1'] - $row['bayar2'];
            }   
            else{
                $tambah = 0;
            }

            $total = ($sal_awl + $tambah) - $row['bayar'];
            $total_ += $total;
            $bayar = $row['bayar'];
            $bayar_ += $bayar;
            
            $tambah_ += $tambah;
            $kata = 'Total';

            if($row['curr'] == 'USD'){
                $eqv_idr =(($sal_awl + $tambah) - $row['bayar']) * $row['rate'];
                $rate = $row['rate'];
            }   
            else{
                $eqv_idr = ($sal_awl + $tambah) - $row['bayar'];
                $rate = 1;
            }

            if ($row['jml_bln1'] > 0 && $row['duedate'] > $start_date) {
                $jml_bln1 = $eqv_idr;
            }else{
                $jml_bln1 = 0;
            }
            if ($row['jml_bln2'] <= 0) {
                $jml_bln2 = 0;
            }else{
                $jml_bln2 = $eqv_idr;
            }
            if ($row['jml_bln3'] <= 0) {
                $jml_bln3 = 0;
            }else{
                $jml_bln3 = $eqv_idr;
            }
            if ($row['jml_bln4'] <= 0) {
                $jml_bln4 = 0;
            }else{
                $jml_bln4 = $eqv_idr;
            }
            if ($row['jml_bln5'] <= 0) {
                $jml_bln5 = 0;
            }else{
                $jml_bln5 = $eqv_idr;
            }
            if ($row['jml_bln6'] <= 0) {
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
                if($row['duedate'] <= $start_date){
                $readydue = $eqv_idr;
            }else{
                $readydue = 0;
            }
                $tot_aging2 = $eqv_idr;
                if ($row['diff_top'] <= 0) {
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

                if ($row['diff_top'] > 0 && $row['diff_top'] <= 30) {
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

                if ($row['diff_top'] > 30 && $row['diff_top'] <= 60) {
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

                if ($row['diff_top'] > 60 && $row['diff_top'] <= 90) {
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

                if ($row['diff_top'] > 90 && $row['diff_top'] <= 120) {
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

                if ($row['diff_top'] > 120 && $row['diff_top'] <= 180) {
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

                if ($row['diff_top'] > 180 && $row['diff_top'] <= 360) {
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

                if ($row['diff_top'] > 360) {
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

            $amt_aging_0_ += $amt_aging_0;
            $amt_aging_1_ += $amt_aging_1;
            $amt_aging_2_ += $amt_aging_2;
            $amt_aging_3_ += $amt_aging_3;
            $amt_aging_4_ += $amt_aging_4;
            $amt_aging_5_ += $amt_aging_5;
            $amt_aging_6_ += $amt_aging_6;
            $amt_aging_7_ += $amt_aging_7;
            $tot_aging_ = $amt_aging_0_ + $amt_aging_1_ + $amt_aging_2_ + $amt_aging_3_ + $amt_aging_4_ + $amt_aging_5_ + $amt_aging_6_ + $amt_aging_7_;
            $readydue_ += $readydue;
            $jml_bln1_ += $jml_bln1;
            $jml_bln2_ += $jml_bln2;
            $jml_bln3_ += $jml_bln3;
            $jml_bln4_ += $jml_bln4;
            $jml_bln5_ += $jml_bln5;
            $jml_bln6_ += $jml_bln6;
            $tot_aging__ = $readydue_ + $jml_bln1_ + $jml_bln2_ + $jml_bln3_ + $jml_bln4_ + $jml_bln5_ + $jml_bln6_;

			if ($sal_awl <= 0 && $tambah <= 0 && $row['bayar'] <= 0 && $total <= 0) {

            }else{

                $eqv_idr_ += $eqv_idr;
                $sal_awl_ += $sal_awl;

            $table .= '<tr>   
                        <td style="">'.$row['customer'].'</td>
                        <td style="">'.$row['no_invoice'].'</td>
                        <td style="">'.date("d-M-Y",strtotime($row['inv_date'])).'</td>
                        <td style="">'.$row['shipp'].'</td>
                        <td style="">'.date("d-M-Y",strtotime($row['duedate'])).'</td>
                        <td style="">'.$row['top'].'</td>
                        <td style="">'.$row['curr'].'</td> 
                        <td style="text-align:right;">'.number_format($rate,2).'</td>
                        <td style="text-align:right;">'.number_format($sal_awl,2).'</td>
                        <td style="text-align:right;">'.number_format($tambah,2).'</td>
                        <td style="text-align:right;">'.number_format($row['bayar'],2).'</td>
                        <td style="text-align:right;">'.number_format($total,2).'</td>
                        <td style="text-align:right;">'.number_format($eqv_idr,2).'</td>
                        <td style="text-align:right;">'.number_format($amt_aging_0,2).'</td>
                        <td style="text-align:right;">'.number_format($amt_aging_1,2).'</td>
                        <td style="text-align:right;">'.number_format($amt_aging_2,2).'</td>
                        <td style="text-align:right;">'.number_format($amt_aging_3,2).'</td>
                        <td style="text-align:right;">'.number_format($amt_aging_4,2).'</td>
                        <td style="text-align:right;">'.number_format($amt_aging_5,2).'</td>
                        <td style="text-align:right;">'.number_format($amt_aging_6,2).'</td>
                        <td style="text-align:right;">'.number_format($amt_aging_7,2).'</td>
                        <td style="text-align:right;">'.number_format($tot_aging,2).'</td> 
                        <td style="text-align:right;"></td>    
                        <td style="text-align:right;">'.number_format($readydue,2).'</td>
                        <td style="text-align:right;">'.number_format($jml_bln1,2).'</td>
                        <td style="text-align:right;">'.number_format($jml_bln2,2).'</td>
                        <td style="text-align:right;">'.number_format($jml_bln3,2).'</td>
                        <td style="text-align:right;">'.number_format($jml_bln4,2).'</td>
                        <td style="text-align:right;">'.number_format($jml_bln5,2).'</td>
                        <td style="text-align:right;">'.number_format($jml_bln6,2).'</td>
                        <td style="text-align:right;">'.number_format($tot_aging,2).'</td>                      
                       </tr>';
            }
        }

        $table .= '<tr>   
                        <th colspan="8" style="text-align: center">'.$kata.'</th> 
                        <th style="text-align:right;">'.number_format($sal_awl_,2).'</th>
                        <th style="text-align:right;">'.number_format($tambah_,2).'</th>
                        <th style="text-align:right;">'.number_format($bayar_,2).'</th>
                        <th style="text-align:right;">'.number_format($total_,2).'</th>
                        <th style="text-align:right;">'.number_format($eqv_idr_,2).'</th>
                        <th style="text-align:right;">'.number_format($amt_aging_0_,2).'</th>
                        <th style="text-align:right;">'.number_format($amt_aging_1_,2).'</th>
                        <th style="text-align:right;">'.number_format($amt_aging_2_,2).'</th>
                        <th style="text-align:right;">'.number_format($amt_aging_3_,2).'</th>
                        <th style="text-align:right;">'.number_format($amt_aging_4_,2).'</th>
                        <th style="text-align:right;">'.number_format($amt_aging_5_,2).'</th>
                        <th style="text-align:right;">'.number_format($amt_aging_6_,2).'</th>
                        <th style="text-align:right;">'.number_format($amt_aging_7_,2).'</th>
                        <th style="text-align:right;">'.number_format($tot_aging_,2).'</th> 
                        <th style="text-align:right;"></th>    
                        <th style="text-align:right;">'.number_format($readydue_,2).'</th>
                        <th style="text-align:right;">'.number_format($jml_bln1_,2).'</th>
                        <th style="text-align:right;">'.number_format($jml_bln2_,2).'</th>
                        <th style="text-align:right;">'.number_format($jml_bln3_,2).'</th>
                        <th style="text-align:right;">'.number_format($jml_bln4_,2).'</th>
                        <th style="text-align:right;">'.number_format($jml_bln5_,2).'</th>
                        <th style="text-align:right;">'.number_format($jml_bln6_,2).'</th>
                        <th style="text-align:right;">'.number_format($tot_aging__,2).'</th>                      
                       </tr>';

echo $table;

mysqli_close($conn2);



?>