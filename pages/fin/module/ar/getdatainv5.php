<?php
include '../../conn/conn.php';
$sub = 0;
$tax = 0;
$total = 0; 
$amount = 0;
$rate = 0;
$eqv_idr = 0;
$no_inv = isset($_POST['no_inv']) ? $_POST['no_inv']: null;

    $sql = mysqli_query($conn1,"select a.no_invoice,c.supplier,a.shipp,a.value,a.doc_type,b.type,a.doc_number from sb_book_invoice a inner join tbl_type b on b.id_type = a.id_type inner join mastersupplier c on c.id_supplier = a.id_customer where no_invoice = '$no_inv'");

    while($r=mysqli_fetch_array($sql)){
        echo $r['type'];     
        ?>
       <?php        
}


// echo '<div id="txt_sub" class="modal-body col-6" style="padding: 0.5rem; margin-left: 65%;"><h7>Subtotal: '.number_format($sub,2).'</h7></div>';
// echo '<div id="txt_tax" class="modal-body col-6" style="padding: 0.5rem; margin-left: 65%;"><h7>Tax: '.number_format($tax,2).'</h7></div>';
// echo '<div id="txt_total" class="modal-body col-6" style="padding: 0.5rem; margin-left: 65%;"><h6>Total: '.number_format($total,2).'</h6></div>';
?>