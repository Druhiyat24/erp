<?php
include '../../conn/conn.php';
$sub = 0;
$tax = 0;
$total = 0; 
$amount = 0;
$rate = 0;
$eqv_idr = 0;
$sob = isset($_POST['sob']) ? $_POST['sob']: null;
if ($sob == 'L') {
    $option = '<option value="BC 4.1">BC 4.1</option>
                <option value="BC 25">BC 25</option>
                <option value="BC 27">BC 27</option>';
}else {
    $option = '<option value="PEB">PEB</option>';
}

    $jsArray2 = "var prdName = new Array();\n";
  
    echo'<option value="" disabled selected="true">Select Dok type</option>';

        $isSelected = ' selected="selected"';
        echo $option;   
        // $jsArray2 .= "prdName2['" . $row['id_ctg5'] . "'] = {nama_ctg3:'" . addslashes($row['name3']) . "',nama_ctg3_h:'".addslashes($row['id_ctg3'])."',nama_ctg4:'" . addslashes($row['name4']) . "',nama_ctg4_h:'".addslashes($row['id_ctg4'])."',no_coa:'".addslashes($row['no_coa'])."'};\n";




// echo '<div id="txt_sub" class="modal-body col-6" style="padding: 0.5rem; margin-left: 65%;"><h7>Subtotal: '.number_format($sub,2).'</h7></div>';
// echo '<div id="txt_tax" class="modal-body col-6" style="padding: 0.5rem; margin-left: 65%;"><h7>Tax: '.number_format($tax,2).'</h7></div>';
// echo '<div id="txt_total" class="modal-body col-6" style="padding: 0.5rem; margin-left: 65%;"><h6>Total: '.number_format($total,2).'</h6></div>';
?>