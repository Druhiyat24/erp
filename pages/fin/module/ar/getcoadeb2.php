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

    $sql = mysqli_query($conn1,"select no_coa, nama_coa from mastercoa_v2 where shipp_tipe = '$shipp' and so_tipe like '%$type_so%' and cus_ctg like '%$cust_ctg%' and grade like '%$grade%' and inv_type like '%INV_debit%'");

    while($r=mysqli_fetch_array($sql)){
        echo $r['nama_coa'];     
        ?>
       <?php        
}


// echo '<div id="txt_sub" class="modal-body col-6" style="padding: 0.5rem; margin-left: 65%;"><h7>Subtotal: '.number_format($sub,2).'</h7></div>';
// echo '<div id="txt_tax" class="modal-body col-6" style="padding: 0.5rem; margin-left: 65%;"><h7>Tax: '.number_format($tax,2).'</h7></div>';
// echo '<div id="txt_total" class="modal-body col-6" style="padding: 0.5rem; margin-left: 65%;"><h6>Total: '.number_format($total,2).'</h6></div>';
?>