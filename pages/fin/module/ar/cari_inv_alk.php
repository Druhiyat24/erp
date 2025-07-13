<?php
include '../../conn/conn.php';
ini_set('date.timezone', 'Asia/Jakarta');

$rate = $_POST['rates'];
$pwith = $_POST['pwith'];
$customer = $_POST['customer'];
$start_date = date("Y-m-d",strtotime($_POST['start_date']));
$end_date = date("Y-m-d",strtotime($_POST['end_date']));

// echo "< -- >";
// echo $no_kbon;

if ($rate == '1' and $pwith == 'IDR') {
   $sql = mysqli_query($conn1,"(SELECT DISTINCT a.no_invoice AS no_invoice, DATE_FORMAT(e.sj_date, '%Y-%m-%d') AS inv_date, f.top, if(g.kontrabon_date is null, DATE_ADD(DATE_FORMAT(e.sj_date, '%Y-%m-%d'), INTERVAL f.top DAY) ,DATE_ADD(DATE_FORMAT(g.kontrabon_date, '%Y-%m-%d'), INTERVAL f.top DAY)) AS duedate, UPPER(b.supplier) AS customer, e.curr,
                                              a.status, a.id, if(h.amount is null,format(d.grand_total, 0), format(((d.grand_total) - (select sum(j.amount) from sb_alokasi_detail j where no_ref = a.no_invoice)), 0)) AS amount, if(h.amount is null,round((d.grand_total), 0),round(((d.grand_total) - (select sum(j.amount) from sb_alokasi_detail j where no_ref = a.no_invoice)), 0)) as amountrate, if(h.amount is null,format((d.grand_total), 0),format(((d.grand_total) - (select sum(j.amount) from sb_alokasi_detail j where no_ref = a.no_invoice)), 0)) as amountrate1, if(h.amount is null,round(d.grand_total, 0), round(((d.grand_total) - (select sum(j.amount) from sb_alokasi_detail j where no_ref = a.no_invoice)), 0)) AS amount1
                                       FROM  sb_book_invoice AS a INNER JOIN 
                                              mastersupplier AS b ON a.id_customer = b.id_supplier INNER JOIN 
                                              tbl_type AS c ON a.id_type = c.id_type INNER JOIN
                                              sb_invoice_pot AS d ON a.id = d.id_book_invoice  INNER JOIN
                                              sb_invoice_detail as e on a.id=e.id_book_invoice left JOIN 
                                              tbl_master_top AS f ON a.id_top = f.id left JOIN 
                                              tbl_duedate AS g ON a.id = g.id_invoice left join
                                              sb_alokasi_detail as h on a.no_invoice = h.no_ref left join
                                              sb_alokasi as i on h.no_alk = i.no_alk left join 
                                                sb_saldoawal_ar as o on o.no_invoice = a.no_invoice
                                              WHERE o.no_invoice is null and e.sj_date BETWEEN '$start_date' AND '$end_date' AND a.id_customer = '$customer' and a.status = 'APPROVED' and if(h.amount is null,round(d.grand_total, 0), round(((d.grand_total) - (select sum(j.amount) from sb_alokasi_detail j where no_ref = a.no_invoice)), 0)) != '0' and e.curr = 'IDR' ORDER BY a.id )
                                              union (select a.no_invoice, a.sj_date, a.top, a.due_date, a.customer, a.curr, a.status, a.id,

if(h.amount is null,format(a.grand_total, 0), format(((a.grand_total) - (select sum(j.amount) from sb_alokasi_detail j where no_ref = a.no_invoice)), 0)) AS amount, 
if(h.amount is null,round((a.grand_total), 0),round(((a.grand_total) - (select sum(j.amount) from sb_alokasi_detail j where no_ref = a.no_invoice)), 0)) as amountrate,
if(h.amount is null,format((a.grand_total), 0),format(((a.grand_total) - (select sum(j.amount) from sb_alokasi_detail j where no_ref = a.no_invoice)), 0)) as amountrate1,
if(h.amount is null,round(a.grand_total, 0), round(((a.grand_total) - (select sum(j.amount) from sb_alokasi_detail j where no_ref = a.no_invoice)), 0)) AS amount1
 from sb_saldoawal_ar a left join sb_alokasi_detail as h on a.no_invoice = h.no_ref left join sb_alokasi as i on h.no_alk = i.no_alk  
 WHERE a.id_customer = '$customer' and a.status = 'APPROVED' and if(h.amount is null,round(a.grand_total, 0), round(((a.grand_total) - (select sum(j.amount) from sb_alokasi_detail j where no_ref = a.no_invoice)), 0)) != '0' and a.curr = 'IDR' ORDER BY a.id)");
}elseif ($rate != '1' and $pwith == 'USD') {
   $sql = mysqli_query($conn1,"(SELECT DISTINCT a.no_invoice AS no_invoice, DATE_FORMAT(e.sj_date, '%Y-%m-%d') AS inv_date, f.top, if(g.kontrabon_date is null, DATE_ADD(DATE_FORMAT(e.sj_date, '%Y-%m-%d'), INTERVAL f.top DAY) ,DATE_ADD(DATE_FORMAT(g.kontrabon_date, '%Y-%m-%d'), INTERVAL f.top DAY)) AS duedate, UPPER(b.supplier) AS customer, e.curr,
                                              a.status, a.id, if(h.amount is null,format(d.grand_total, 2),format((d.grand_total- (select sum(j.amount) from sb_alokasi_detail j left join sb_alokasi as k on j.no_alk = k.no_alk where no_ref = a.no_invoice)), 2)) AS amount, if(h.amount is null,round((d.grand_total  * '$rate'), 2),round(((d.grand_total  * '$rate' - (select sum(j.amount) * '$rate' from sb_alokasi_detail j left join sb_alokasi as k on j.no_alk = k.no_alk where no_ref = a.no_invoice))), 0)) as amountrate, if(h.amount is null,format((d.grand_total  * '$rate'), 2),format(((d.grand_total  * '$rate' - (select sum(j.amount) * '$rate' from sb_alokasi_detail j left join sb_alokasi as k on j.no_alk = k.no_alk where no_ref = a.no_invoice))), 0)) as amountrate1, if(h.amount is null,round(d.grand_total, 2),round((d.grand_total- (select sum(j.amount) from sb_alokasi_detail j left join sb_alokasi as k on j.no_alk = k.no_alk where no_ref = a.no_invoice)), 2)) AS amount1
                                       FROM  sb_book_invoice AS a INNER JOIN 
                                              mastersupplier AS b ON a.id_customer = b.id_supplier INNER JOIN 
                                              tbl_type AS c ON a.id_type = c.id_type INNER JOIN
                                              sb_invoice_pot AS d ON a.id = d.id_book_invoice  INNER JOIN
                                              sb_invoice_detail as e on a.id=e.id_book_invoice left JOIN 
                                              tbl_master_top AS f ON a.id_top = f.id left JOIN 
                                              tbl_duedate AS g ON a.id = g.id_invoice left join
                                              sb_alokasi_detail as h on a.no_invoice = h.no_ref left join
                                              sb_alokasi as i on h.no_alk = i.no_alk left join 
                                                sb_saldoawal_ar as o on o.no_invoice = a.no_invoice
                                              WHERE o.no_invoice is null and e.sj_date BETWEEN '$start_date' AND '$end_date' AND a.id_customer = '$customer' and a.status = 'APPROVED' and if(h.amount is null,round(d.grand_total, 2),round((d.grand_total- (select sum(j.amount) from sb_alokasi_detail j left join sb_alokasi as k on j.no_alk = k.no_alk where no_ref = a.no_invoice)), 2)) != '0' and e.curr = 'USD' ORDER BY a.id) 
                                              union
                                              (select a.no_invoice, a.sj_date, a.top, a.due_date, a.customer, a.curr, a.status, a.id,

if(h.amount is null,format(a.grand_total, 2),format((a.grand_total- (select sum(j.amount) from sb_alokasi_detail j left join sb_alokasi as k on j.no_alk = k.no_alk where no_ref = a.no_invoice)), 2)) AS amount, 
if(h.amount is null,round((a.grand_total  * '$rate'), 2),round(((a.grand_total  * '$rate' - (select sum(j.amount) * '$rate' from sb_alokasi_detail j left join sb_alokasi as k on j.no_alk = k.no_alk where no_ref = a.no_invoice))), 0)) as amountrate,
if(h.amount is null,format((a.grand_total  * '$rate'), 2),format(((a.grand_total  * '$rate' - (select sum(j.amount) * '$rate' from sb_alokasi_detail j left join sb_alokasi as k on j.no_alk = k.no_alk where no_ref = a.no_invoice))), 0)) as amountrate1,
if(h.amount is null,round(a.grand_total, 2),round((a.grand_total- (select sum(j.amount) from sb_alokasi_detail j left join sb_alokasi as k on j.no_alk = k.no_alk where no_ref = a.no_invoice)), 2)) AS amount1
 from sb_saldoawal_ar a left join sb_alokasi_detail as h on a.no_invoice = h.no_ref left join sb_alokasi as i on h.no_alk = i.no_alk  
 WHERE a.id_customer = '$customer' and a.status = 'APPROVED' and if(h.amount is null,round(a.grand_total, 2),round((a.grand_total- (select sum(j.amount) from sb_alokasi_detail j left join sb_alokasi as k on j.no_alk = k.no_alk where no_ref = a.no_invoice)), 2)) != '0' and a.curr = 'USD' ORDER BY a.id)");
}else{
   $sql = mysqli_query($conn1,"(SELECT DISTINCT a.no_invoice AS no_invoice, DATE_FORMAT(e.sj_date, '%Y-%m-%d') AS inv_date, f.top, if(g.kontrabon_date is null, DATE_ADD(DATE_FORMAT(e.sj_date, '%Y-%m-%d'), INTERVAL f.top DAY) ,DATE_ADD(DATE_FORMAT(g.kontrabon_date, '%Y-%m-%d'), INTERVAL f.top DAY)) AS duedate, UPPER(b.supplier) AS customer, e.curr,
                                              a.status, a.id, if(e.curr = 'IDR',if(h.amount is null,format(d.grand_total, 0), format(((d.grand_total) - (select sum(j.amount) from sb_alokasi_detail j where no_ref = a.no_invoice)), 0)),if(h.amount is null,format(d.grand_total, 2),format((d.grand_total- (select sum(j.amount) from sb_alokasi_detail j left join sb_alokasi as k on j.no_alk = k.no_alk where no_ref = a.no_invoice)), 2))) AS amount, IF(e.curr = 'IDR',if(h.amount is null,round((d.grand_total), 0),round(((d.grand_total) - (select sum(j.amount) from sb_alokasi_detail j where no_ref = a.no_invoice)), 0)),if(h.amount is null,round((d.grand_total  * '$rate'), 2),round(((d.grand_total  * '$rate' - (select sum(j.amount) * '$rate' from sb_alokasi_detail j left join sb_alokasi as k on j.no_alk = k.no_alk where no_ref = a.no_invoice))), 0))) as amountrate, IF(e.curr = 'IDR',if(h.amount is null,format((d.grand_total), 0),format(((d.grand_total) - (select sum(j.amount) from sb_alokasi_detail j where no_ref = a.no_invoice)), 0)),if(h.amount is null,format((d.grand_total  * '$rate'), 2),format(((d.grand_total  * '$rate' - (select sum(j.amount) * '$rate' from sb_alokasi_detail j left join sb_alokasi as k on j.no_alk = k.no_alk where no_ref = a.no_invoice))), 0))) as amountrate1, if(e.curr = 'IDR',if(h.amount is null,round(d.grand_total, 0), round(((d.grand_total) - (select sum(j.amount) from sb_alokasi_detail j where no_ref = a.no_invoice)), 0)),if(h.amount is null,round(d.grand_total, 2),round((d.grand_total- (select sum(j.amount) from sb_alokasi_detail j left join sb_alokasi as k on j.no_alk = k.no_alk where no_ref = a.no_invoice)), 2))) AS amount1
                                       FROM  sb_book_invoice AS a INNER JOIN 
                                              mastersupplier AS b ON a.id_customer = b.id_supplier INNER JOIN 
                                              tbl_type AS c ON a.id_type = c.id_type INNER JOIN
                                              sb_invoice_pot AS d ON a.id = d.id_book_invoice  INNER JOIN
                                              sb_invoice_detail as e on a.id=e.id_book_invoice left JOIN 
                                              tbl_master_top AS f ON a.id_top = f.id left JOIN 
                                              tbl_duedate AS g ON a.id = g.id_invoice left join
                                              sb_alokasi_detail as h on a.no_invoice = h.no_ref left join
                                              sb_alokasi as i on h.no_alk = i.no_alk left join 
                                                sb_saldoawal_ar as o on o.no_invoice = a.no_invoice
                                              WHERE o.no_invoice is null and e.sj_date BETWEEN '$start_date' AND '$end_date' AND a.id_customer = '$customer' and e.curr = 'IDR' and a.status = 'APPROVED' and if(e.curr = 'IDR',if(h.amount is null,round(d.grand_total, 0), round(((d.grand_total) - (select sum(j.amount) from sb_alokasi_detail j where no_ref = a.no_invoice)), 0)),if(h.amount is null,round(d.grand_total, 2),round((d.grand_total- (select sum(j.amount) from sb_alokasi_detail j left join sb_alokasi as k on j.no_alk = k.no_alk where no_ref = a.no_invoice)), 2))) != '0' ORDER BY a.id) union
                                              (select a.no_invoice, a.sj_date, a.top, a.due_date, a.customer, a.curr, a.status, a.id,

if(a.curr = 'IDR',if(h.amount is null,format(a.grand_total, 0), format(((a.grand_total) - (select sum(j.amount) from sb_alokasi_detail j where no_ref = a.no_invoice)), 0)),if(h.amount is null,format(a.grand_total, 2),format((a.grand_total- (select sum(j.amount) from sb_alokasi_detail j left join sb_alokasi as k on j.no_alk = k.no_alk where no_ref = a.no_invoice)), 2))) AS amount, 
IF(a.curr = 'IDR',if(h.amount is null,round((a.grand_total), 0),round(((a.grand_total) - (select sum(j.amount) from sb_alokasi_detail j where no_ref = a.no_invoice)), 0)),if(h.amount is null,round((a.grand_total  * '$rate'), 2),round(((a.grand_total  * '$rate' - (select sum(j.amount) * '$rate' from sb_alokasi_detail j left join sb_alokasi as k on j.no_alk = k.no_alk where no_ref = a.no_invoice))), 0))) as amountrate,
IF(a.curr = 'IDR',if(h.amount is null,format((a.grand_total), 0),format(((a.grand_total) - (select sum(j.amount) from sb_alokasi_detail j where no_ref = a.no_invoice)), 0)),if(h.amount is null,format((a.grand_total  * '$rate'), 2),format(((a.grand_total  * '$rate' - (select sum(j.amount) * '$rate' from sb_alokasi_detail j left join sb_alokasi as k on j.no_alk = k.no_alk where no_ref = a.no_invoice))), 0))) as amountrate1,
if(a.curr = 'IDR',if(h.amount is null,round(a.grand_total, 0), round(((a.grand_total) - (select sum(j.amount) from sb_alokasi_detail j where no_ref = a.no_invoice)), 0)),if(h.amount is null,round(a.grand_total, 2),round((a.grand_total- (select sum(j.amount) from sb_alokasi_detail j left join sb_alokasi as k on j.no_alk = k.no_alk where no_ref = a.no_invoice)), 2))) AS amount1
 from sb_saldoawal_ar a left join sb_alokasi_detail as h on a.no_invoice = h.no_ref left join sb_alokasi as i on h.no_alk = i.no_alk  
 WHERE a.id_customer = '$customer' and a.curr = 'IDR' and a.status = 'APPROVED' and if(a.curr = 'IDR',if(h.amount is null,round(a.grand_total, 0), round(((a.grand_total) - (select sum(j.amount) from sb_alokasi_detail j where no_ref = a.no_invoice)), 0)),if(h.amount is null,round(a.grand_total, 2),round((a.grand_total- (select sum(j.amount) from sb_alokasi_detail j left join sb_alokasi as k on j.no_alk = k.no_alk where no_ref = a.no_invoice)), 2))) != '0' ORDER BY a.id)");
}


$table = '';

			while ($row = mysqli_fetch_assoc($sql)) {
			
            $table .= '<tr>   
                        <td style="" value="'.$row['no_invoice'].'">'.$row['no_invoice'].'</td>
                        <td style="" value="'.$row['inv_date'].'">'.date("d-M-Y",strtotime($row['inv_date'])).'</td>
                        <td style="" value="'.$row['duedate'].'">'.date("d-M-Y",strtotime($row['duedate'])).'</td>
                        <td style="" value="'.$row['curr'].'">'.$row['curr'].'</td>
                        <td hidden style="" value="'.$row['amount1'].'">'.$row['amount1'].'</td>
                        <td hidden style="" value="'.$row['amountrate'].'">'.$row['amountrate'].'</td>
                        <td style="" value="'.$row['amount'].'">'.$row['amount'].'</td> 
                        <td style="" value="'.$row['amountrate1'].'">'.$row['amountrate1'].'</td>
                        <td style="" value=""><input type="number" min="0" class="form-control" id="mdl_amo" name="mdl_amo" style="width: 80%; text-align: right" oninput="modal_input_amo(value)" readonly autocomplete="off"></td>
                        <td style="" value=""><input type="checkbox" name="mdl_cek_kwt" id="mdl_cek_kwt" class="flat"  onclick="modal_sum_total_alo(value = '.$row['amount1'].'); modal_get_amount(value = '.$row['amount1'].')"></td>                          
                       </tr>';
        }

        // trHTML += '<tr>';              
        //     trHTML += '<td>' + item.no_invoice + "</td>";
        //     trHTML += '<td>' + item.inv_date + "</td>";
        //     trHTML += '<td>' + item.duedate + "</td>";   
        //     trHTML += '<td>' + item.curr + "</td>";
        //     trHTML += '<td style="display:none;">' + item.amount1 + "</td>";
        //     trHTML += '<td style="display:none;">' + item.amountrate + "</td>";
        //     trHTML += '<td>' + item.amount + "</td>";
        //     trHTML += '<td>' + item.amountrate1 + "</td>";     
        //     trHTML += '<td><input type="number" min="0" class="form-control" id="mdl_amo" name="mdl_amo" style="width: 80%; text-align: right" oninput="modal_input_amo(value)" readonly autocomplete="off"></td>';
        //     trHTML += '<td><input type="checkbox" name="mdl_cek_kwt" id="mdl_cek_kwt" class="flat"  onclick="modal_sum_total_alo(value = ' +  item.amount1 + '); modal_get_amount(value = ' +  item.amount1 + ')"></td>';
            
        //     trHTML += '</tr>';
        // <td style="width:10px;"><input type="checkbox" id="pilih_sj" name="pilih_sj" value="" <?php if(in_array("1",$_POST[select])) echo "checked=checked";? onclick="tambah_sj('.$row['id_so'].')"></td>    

echo $table;

mysqli_close($conn2);



?>