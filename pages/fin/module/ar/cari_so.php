<?php
include '../../conn/conn.php';
ini_set('date.timezone', 'Asia/Jakarta');

$nama_supp = $_POST['nama_supp'];
$start_date = date("Y-m-d",strtotime($_POST['start_date']));
$end_date = date("Y-m-d",strtotime($_POST['end_date']));

// echo "< -- >";
// echo $no_kbon;


 $sql = mysqli_query($conn1,"select DISTINCT a.so_no, a.so_date, c.Supplier, a.buyerno, a.so_type, b.id_so
                                 FROM  so_det AS b INNER JOIN so AS a 
                                       ON (b.id_so = a.id) INNER JOIN 
                                       act_costing AS d ON (a.id_cost = d.id) INNER JOIN 
                                       mastersupplier AS c ON (c.Id_Supplier = d.id_buyer) INNER JOIN 
                                       bppb AS e ON (e.id_so_det = b.id) 
                                 WHERE c.Id_Supplier = '$nama_supp' AND a.so_date BETWEEN '$start_date' and '$end_date' AND c.tipe_sup = 'C'");

$table = '';

			while ($row = mysqli_fetch_assoc($sql)) {
			
            $table .= '<tr>   
                        <td style="width:10px;"><input type="checkbox" id="pilih_sj" name="pilih_sj" value="" <?php if(in_array("1",$_POST[select])) echo "checked=checked";? onclick="tambah_sj('.$row['id_so'].')"></td>    
                        <td style="" value="'.$row['so_no'].'">'.$row['so_no'].'</td>
                        <td style="width:100px;" value="'.$row['so_date'].'">'.date("d-M-Y",strtotime($row['so_date'])).'</td>
                        <td style="" value="'.$row['Supplier'].'">'.$row['Supplier'].'</td>
                        <td style="" value="'.$row['buyerno'].'">'.$row['buyerno'].'</td>
                        <td style="" value="'.$row['so_type'].'">'.$row['so_type'].'</td>
                        <td style="" value="'.$row['id_so'].'">'.$row['id_so'].'</td>                    
                             
                       </tr>';
        }

echo $table;

mysqli_close($conn2);
?>