<?php
include '../../conn/conn.php';
ini_set('date.timezone', 'Asia/Jakarta');

$create_user = $_POST['create_user'];

 $sql = mysqli_query($conn1,"select a.ref_number, DATE_FORMAT(a.ref_date, '%Y-%m-%d') AS ref_date, if(a.due_date = '0000-00-00', '-',DATE_FORMAT(a.due_date, '%Y-%m-%d')) AS due_date, a.total, a.curr, a.eqp_idr, a.amount,REPLACE(b.no_coa,' ','') no_coa,b.nama_coa,concat(REPLACE(b.no_coa,' ',''),' ',b.nama_coa) as coa FROM sb_alokasi_temp a INNER JOIN sb_book_invoice b on b.no_invoice = a.ref_number union
select a.ref_number, DATE_FORMAT(a.ref_date, '%Y-%m-%d') AS ref_date, if(a.due_date = '0000-00-00', '-',DATE_FORMAT(a.due_date, '%Y-%m-%d')) AS due_date, a.total, a.curr, a.eqp_idr, a.amount, b.no_coa,b.nama_coa,concat(b.no_coa,' ',b.nama_coa) as coa FROM sb_alokasi_temp a INNER JOIN sb_saldoawal_ar b on b.no_invoice = a.ref_number");

$table = '';

			while ($row = mysqli_fetch_assoc($sql)) {
			
            $table .= '<tr>       
                        <td style="" value="'.$row['no_coa'].'"><input type="hidden" value="'.$row['no_coa'].'" class="form-control" id="stylein" name="stylein" style="width: 300px;" autocomplete="off" readonly> '.$row['coa'].'</td>
                        <td style=""><input type="text" value="-" class="form-control" style="width: 250px;"  autocomplete="off" readonly></td>
                        <td style=""><input type="text" value="'.$row['ref_number'].'" class="form-control" id="stylein" name="stylein" style="width: 180px; text-align: center;"  autocomplete="off" readonly></td>
                        <td style=""><input type="text" value="'.$row['ref_date'].'" class="form-control" id="stylein" name="stylein" style="width: 180px; text-align: center;"  autocomplete="off" readonly></td>
                        <td style=""><input type="text" value="'.$row['due_date'].'" class="form-control" id="stylein" name="stylein" style="width: 180px; text-align: center;"  autocomplete="off" readonly></td>
                        <td style=""><input type="text" value="'.$row['total'].'" class="form-control" id="stylein" name="stylein" style="width: 180px; text-align: center;"  autocomplete="off" readonly></td>
                        <td style=""><input type="text" value="'.$row['eqp_idr'].'" class="form-control" id="stylein" name="stylein" style="width: 180px; text-align: center;"  autocomplete="off" readonly></td>
                        <td style=""><input type="text" value="'.$row['amount'].'" class="form-control" id="stylein" name="stylein" style="width: 180px; text-align: center;"  autocomplete="off" readonly></td>
                        <td style=""><input type="text" class="form-control" style="width: 300px;text-align: center;" id="desc" name="desc"  autocomplete="off"></td>
                        <td hidden><input type="hidden" value="'.$row['nama_coa'].'" class="form-control" id="stylein" name="stylein" style="width: 180px; text-align: center;"  autocomplete="off" readonly></td>
                        </tr>';
        }

            //   trHTML += '<tr>';   
            // trHTML += '<td width = "300px"><input type="hidden" value="'+ item.no_coa +'" class="form-control" id="stylein" name="stylein" style="width: 300px;" autocomplete="off" readonly>'+item.coa+'</td>';    
            // trHTML += '<td style="width: 300px;"><input type="text" value="-" class="form-control" style="width: 250px;"  autocomplete="off" readonly></td>';
            // trHTML += '<td><input type="text" value="'+ item.ref_number +'" class="form-control" id="stylein" name="stylein" style="width: 180px; text-align: center;"  autocomplete="off" readonly></td>';
            // trHTML += '<td><input type="text" value="'+ item.ref_date +'" class="form-control" id="stylein" name="stylein" style="width: 150px; text-align: center;"  autocomplete="off" readonly></td>';
            // trHTML += '<td><input type="text" value="'+ item.due_date +'" class="form-control" id="stylein" name="stylein" style="width: 150px; text-align: center;"  autocomplete="off" readonly></td>';
            // trHTML += '<td><input type="text" value="'+ item.total +'" class="form-control" id="stylein" name="stylein" style="width: 180px; text-align: center;"  autocomplete="off" readonly></td>';
            // trHTML += '<td><input type="text" value="'+ item.eqp_idr +'" class="form-control" id="stylein" name="stylein" style="width: 180px; text-align: center;"  autocomplete="off" readonly></td>';
            // trHTML += '<td><input type="text" value="'+ item.amount +'" class="form-control" id="stylein" name="stylein" style="width: 180px; text-align: center;"  autocomplete="off" readonly></td>';                                 
            // trHTML += '<td ><input type="text" class="form-control" style="width: 300px;text-align: center;" id="desc" name="desc"  autocomplete="off"></td>';
            // // trHTML += '<td><input type="text" value="'+ item.nama_coa +'" class="form-control" id="stylein" name="stylein" style="width: 180px; text-align: center;"  autocomplete="off" readonly></td>';                      
            // trHTML += '</tr>';

echo $table;

mysqli_close($conn2);
?>