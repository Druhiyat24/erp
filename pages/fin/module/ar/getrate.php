<?php
include '../../conn/conn.php';
$sub = 0;
$tax = 0;
$total = 0; 
$amount = 0;
$rate = 0;
$eqv_idr = 0;
$inv_date = isset($_POST['inv_date']) ? $_POST['inv_date']: null;

    $sql = mysqli_query($conn1,"select rate,tanggal from masterrate where v_codecurr = 'PAJAK' and tanggal = '$inv_date'");

    while($r=mysqli_fetch_array($sql)){
        echo $r['rate'];     
        ?>
       <?php        
}

?>