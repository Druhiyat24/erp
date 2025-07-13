<?php
include '../../conn/conn.php';
$sub = 0;
$tax = 0;
$total = 0; 
$amount = 0;
$rate = 0;
$eqv_idr = 0;
$shipp = isset($_POST['shipp']) ? $_POST['shipp']: null;
$type_so = isset($_POST['type_so']) ? $_POST['type_so']: null;
$cust_ctg = isset($_POST['cust_ctg']) ? $_POST['cust_ctg']: null;
$grade = isset($_POST['grade']) ? $_POST['grade']: null;

    $sql = mysqli_query($conn1,"select no_coa, nama_coa from mastercoa_v2 where inv_type like '%PPN INV%'");

    while($r=mysqli_fetch_array($sql)){
        echo $r['no_coa'];     
        ?>
       <?php        
}

?>