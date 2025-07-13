<?php
include '../../conn/conn.php';
ini_set('date.timezone', 'Asia/Jakarta');

$id_so = $_POST['id_so'];

 $sql = mysqli_query($conn1,"select a.so_no AS no_so, c.bppbno AS sj, c.bppbdate, c.bppbno_int AS shipping_number, d.kpno AS ws,  
                                        d.styleno, e.product_group, e.product_item, b.color, b.size,  
                                        c.curr, c.unit AS uom, c.qty, Round(c.price,4) AS unit_price,  
                                        ROUND(c.qty * Round(c.price,4), 4) AS total_price,  b.id_so, c.id AS id_bppb,c.grade,if(c.grade = 'GRADE A','A','B') as grade
                                 FROM so AS a INNER JOIN 
                                       so_det AS b ON a.id = b.id_so INNER JOIN 
                                       bppb AS c ON b.id = c.id_so_det INNER JOIN 
                                       act_costing AS d ON a.id_cost = d.id INNER JOIN 
                                       masterproduct AS e ON d.id_product = e.id               
                                 WHERE b.id_so = '$id_so' and c.id_supplier != '1038' AND (ISNULL(c.stat_inv) OR c.stat_inv = '' or c.stat_inv='0')
                                 ORDER BY c.bppbno");

$table = '';

			while ($row = mysqli_fetch_assoc($sql)) {
			
            $table .= '<tr>       
                        <td style="" value="'.$row['id_bppb'].'">'.$row['id_bppb'].'</td>
                        <td style="" value="'.$row['no_so'].'">'.$row['no_so'].'</td>
                        <td style="" value="'.$row['sj'].'">'.$row['sj'].'</td>
                        <td style="width:100px;" value="'.$row['bppbdate'].'">'.date("d-M-Y",strtotime($row['bppbdate'])).'</td>
                        <td style="" value="'.$row['shipping_number'].'">'.$row['shipping_number'].'</td>
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
                        <td style="" ><input type="text" class="form-control" id="mdl_disc" name="mdl_disc" style="width: 80%; text-align: center" onkeypress="javascript:return isNumber(event)" oninput="modal_input_discount(value)" readonly autocomplete="off"></td></td>
                        <td style="" value="'.$row['total_price'].'">'.$row['total_price'].'</td> 
                        <td style="width:10px;"><input type="checkbox" id="mdl_cek_sj" name="mdl_cek_sj" value="'.$row['total_price'].'" <?php if(in_array("1",$_POST[select])) echo "checked=checked";? onclick="modal_sum_total_sj('.$row['total_price'].')"></td>
                        <td hidden><input type="hidden" id="cek_tgl" name="cek_tgl" style="border:none; width:120px;" value = "'.$row['bppbdate'].'" readonly></td> 
                        <td hidden> <input type="hidden" class="form-control" id="mdl_grade" name="mdl_grade" value = "'.$row['grade'].'" style="width: 80%; text-align: center"  readonly autocomplete="off"></td>    
                        <td hidden> <input type="text" class="form-control" id="mdl_tgl_inv" name="mdl_tgl_inv" value = "'.$row['bppbdate'].'" style="width: 80%; text-align: center"  readonly autocomplete="off"></td>  
                        <td hidden> <input type="text" class="form-control" id="mdl_curr" name="mdl_curr" value = "'.$row['curr'].'" style="width: 80%; text-align: center"  readonly autocomplete="off"></td>            
                             
                       </tr>';
        }

        // trHTML += '<tr>';   
        //                     trHTML += '<td>' + item.id_bppb + "</td>";          
        //                     trHTML += '<td>' + item.no_so + "</td>";
        //                     trHTML += '<td>' + item.sj + "</td>";                           
        //                     //trHTML += '<td>' + item.bppbdate + "</td>";
        //                     trHTML += '<td><input type="text" id="cek_tgl" name="cek_tgl" style="border:none; width:120px;" value = ' + item.bppbdate + ' readonly></td>';                                  
        //                     trHTML += '<td>' + item.shipping_number + "</td>";      
        //                     trHTML += '<td>' + item.ws + "</td>";   
        //                     trHTML += '<td>' + item.styleno + "</td>";  
        //                     trHTML += '<td>' + item.product_group + "</td>";
        //                     trHTML += '<td>' + item.product_item + "</td>"; 
        //                     trHTML += '<td>' + item.color + "</td>";
        //                     trHTML += '<td>' + item.size + "</td>";
        //                     trHTML += '<td>' + item.curr + "</td>"; 
        //                     trHTML += '<td>' + item.uom + "</td>";
        //                     trHTML += '<td>' + item.qty + "</td>";
        //                     trHTML += '<td>' + item.unit_price + "</td>";               
        //                     trHTML += '<td><input type="text" class="form-control" id="mdl_disc" name="mdl_disc" style="width: 80%; text-align: center" onkeypress="javascript:return isNumber(event)" oninput="modal_input_discount(value)" readonly autocomplete="off"></td>';        
        //                     trHTML += '<td class="totalsj" align="right">' + item.total_price + "</td>";    
        //                     trHTML += '<td><input type="checkbox" name="mdl_cek_sj" id="mdl_cek_sj" class="flat" value = ' + item.total_price + ' onclick="modal_sum_total_sj(value = ' +  item.total_price + ')"></td>';
        //                     trHTML += '<td hidden> <input type="text" class="form-control" id="mdl_grade" name="mdl_grade" value = ' + item.grade + ' style="width: 80%; text-align: center"  readonly autocomplete="off"></td>';
        //                     trHTML += '<td hidden> <input type="text" class="form-control" id="mdl_tgl_inv" name="mdl_tgl_inv" value = ' + item.bppbdate + ' style="width: 80%; text-align: center"  readonly autocomplete="off"></td>';
        //                     trHTML += '<td hidden> <input type="text" class="form-control" id="mdl_curr" name="mdl_curr" value = ' + item.curr + ' style="width: 80%; text-align: center"  readonly autocomplete="off"></td>';                                  
        //                     trHTML += '</tr>';

echo $table;

mysqli_close($conn2);
?>