<?php
include '../../conn/conn.php';
$sub = 0;
$tax = 0;
$total = 0; 
$amount = 0;
$rate = 0;
$eqv_idr = 0;
$no_inv = isset($_POST['no_inv']) ? $_POST['no_inv']: null;
    
    $sql = mysqli_query($conn1,"select a.no_invoice,b.id,b.type,b.top, CONCAT(type,' - ',top) nama_type from sb_book_invoice a inner join tbl_master_top b on b.id_customer = a.id_customer where no_invoice = '$no_inv' and b.status = 'Active'");


  
    echo'<option value="" disabled selected="true">Select TOP</option>';
    while($r=mysqli_fetch_array($sql)){
        $isSelected = ' selected="selected"';
        echo '<option name="nama_ctg5" value="'.$r['id'].'">'.$r['nama_type'].'</option>';    
              
}

?>