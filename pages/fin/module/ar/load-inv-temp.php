<?php
include '../../conn/conn.php';
ini_set('date.timezone', 'Asia/Jakarta');

$create_user = $_POST['create_user'];

 $sql = mysqli_query($conn1,"select id_bppb, so_number, bppb_number, sj_date, shipp_number, ws, styleno, product_group, product_item, color, size, curr, uom, qty, unit_price, disc, total_price FROM sb_invoice_detail_temp");

$table = '';

			while ($row = mysqli_fetch_assoc($sql)) {
			
            $table .= '<tr>       
                        <td style="" value="'.$row['id_bppb'].'">'.$row['id_bppb'].'</td>
                        <td style="" value="'.$row['so_number'].'">'.$row['so_number'].'</td>
                        <td style="" value="'.$row['bppb_number'].'">'.$row['bppb_number'].'</td>
                        <td style="width:100px;" value="'.$row['sj_date'].'">'.date("d-M-Y",strtotime($row['sj_date'])).'</td>
                        <td style="" value="'.$row['shipp_number'].'">'.$row['shipp_number'].'</td>
                        <td style="" value="'.$row['ws'].'">'.$row['ws'].'</td>
                        <td style="" value="'.$row['styleno'].'">'.$row['styleno'].'</td>
                        <td style="" value="'.$row['product_group'].'">'.$row['product_group'].'</td>
                        <td style="" value="'.$row['product_item'].'">'.$row['product_item'].'</td>
                        <td style="" value="'.$row['color'].'">'.$row['color'].'</td>
                        <td style="" value="'.$row['size'].'">'.$row['size'].'</td>
                        <td style="" value="'.$row['curr'].'">'.$row['curr'].'</td>
                        <td style="" value="'.$row['uom'].'">'.$row['uom'].'</td>
                        <td style="" value="'.$row['qty'].'">'.$row['qty'].'</td>
                        <td style="" value="'.$row['unit_price'].'">'.$row['unit_price'].'</td>
                        <td style="" value="'.$row['disc'].'">'.$row['disc'].'</td>
                        <td style="" value="'.$row['total_price'].'">'.$row['total_price'].'</td>
                        </tr>';
        }

                  // trHTML += '<tr>';
                  // trHTML += '<td>' + item.id_bppb + "</td>";            
                  // trHTML += '<td>' + item.so_number + "</td>";
                  // trHTML += '<td>' + item.bppb_number + "</td>";                    
                  // trHTML += '<td>' + item.sj_date + "</td>";                 
                  // trHTML += '<td>' + item.shipp_number + "</td>";    
                  // trHTML += '<td>' + item.ws + "</td>";  
                  // trHTML += '<td>' + item.styleno + "</td>";   
                  // trHTML += '<td>' + item.product_group + "</td>";   
                  // trHTML += '<td>' + item.product_item + "</td>";
                  // trHTML += '<td>' + item.color + "</td>";
                  // trHTML += '<td>' + item.size + "</td>";
                  // trHTML += '<td>' + item.curr + "</td>";   
                  // trHTML += '<td>' + item.uom + "</td>";
                  // trHTML += '<td>' + item.qty + "</td>";
                  // trHTML += '<td>' + item.unit_price + "</td>";
                  // trHTML += '<td>' + item.disc + "</td>";   
                  // trHTML += '<td align="right">' + item.total_price + "</td>";   
                  // // trHTML += '<td><input type="checkbox" name="cek_pilih_sj" id="cek_pilih_sj" class="flat" checked></td>';                                  
                  // trHTML += '</tr>';

echo $table;

mysqli_close($conn2);
?>