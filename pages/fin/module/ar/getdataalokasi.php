<?php
include '../../conn/conn.php';
$sub = 0;
$tax = 0;
$total = 0; 
$amount = 0;
$rate = 0;
$eqv_idr = 0;
$no_bi = isset($_POST['no_bi']) ? $_POST['no_bi']: null;

    $sql = mysqli_query($conn1,"select * from (select a.doc_num,a.date,c.Id_Supplier,a.customer,b.id as id_bank,a.bank,a.akun,a.curr,
                        if(a.curr = 'USD',if(((a.amount) - (select DISTINCT sum((k.eqp_idr - k.sisa) /k.rate) from sb_alokasi k left join sb_bankin_arcollection d on d.doc_num = k.doc_number where k.doc_number = a.doc_num)) is null,a.amount,Round((a.amount) - (select DISTINCT sum((k.eqp_idr - k.sisa) /k.rate) from sb_alokasi k left join sb_bankin_arcollection d on d.doc_num = k.doc_number where k.doc_number = a.doc_num),2)),if(((a.amount) - (select DISTINCT sum(k.eqp_idr - k.sisa) from sb_alokasi k left join sb_bankin_arcollection d on d.doc_num = k.doc_number where k.doc_number = a.doc_num)) is null,a.amount,((a.amount) - (select DISTINCT sum(k.eqp_idr - k.sisa) from sb_alokasi k left join sb_bankin_arcollection d on d.doc_num = k.doc_number where k.doc_number = a.doc_num)))) as amount,a.rate,
                            if((a.eqv_idr - (select DISTINCT sum(k.eqp_idr - k.sisa) from sb_alokasi k left join sb_bankin_arcollection d on d.doc_num = k.doc_number where k.doc_number = a.doc_num)) is null,a.eqv_idr,(a.eqv_idr - (select DISTINCT sum(k.eqp_idr - k.sisa) from sb_alokasi k left join sb_bankin_arcollection d on d.doc_num = k.doc_number where k.doc_number = a.doc_num)))as eqv_idr 
                            from sb_bankin_arcollection a inner join masterbank b on b.no_rek = a.akun left join mastersupplier c on c.Supplier = a.customer where a.ref_data = 'AR Collection' and a.status = 'Approved' and c.tipe_sup = 'C') a where a.amount > 0 and a.doc_num = '$no_bi'");

  $data=mysqli_fetch_row($sql);
        echo json_encode($data);    

// echo '<div id="txt_sub" class="modal-body col-6" style="padding: 0.5rem; margin-left: 65%;"><h7>Subtotal: '.number_format($sub,2).'</h7></div>';
// echo '<div id="txt_tax" class="modal-body col-6" style="padding: 0.5rem; margin-left: 65%;"><h7>Tax: '.number_format($tax,2).'</h7></div>';
// echo '<div id="txt_total" class="modal-body col-6" style="padding: 0.5rem; margin-left: 65%;"><h6>Total: '.number_format($total,2).'</h6></div>';
?>