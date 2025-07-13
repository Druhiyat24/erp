<?php
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (empty($_SESSION['username'])) {
    header("location:../../index.php");
}
$user = $_SESSION['username'];
$kode_rak = $_POST['kode_rak'];

$sql = mysqli_query($conn_li, "SELECT rak kode_rak, 
nama_rak, 
coalesce(max(a.kapasitas),0) kapasitas, 
count(rak) terisi,
coalesce(max(a.kapasitas),0) - count(rak)  sisa,
round((count(rak)/ max(a.kapasitas)) * 100,2) persen
FROM `upload_rak` a
inner join master_rak mr on a.rak = mr.kode_rak
where kode_rak = '$kode_rak'
group by rak");
// $sql = mysqli_query($conn_li, "select kode_rak,nama_rak,date_input,kapasitas,COALESCE(qty,0) qty,round((COALESCE(qty,0) / kapasitas * 100),2) persen from (select * from (select kode_rak,nama_rak, kapasitas from m_rak) a left join
//             (select kode_rak koderak,count(roll_qty) as qty, max(date_input) date_input from in_material_det where cancel = 'N' group by kode_rak) b on a.kode_rak = b.koderak) a where a.kode_rak = '$kode_rak' order by a.kode_rak asc");
$row = mysqli_fetch_array($sql);
$percent = $row['persen'];
$kode_rak = $row['kode_rak'];
$persen = array('$percent', '$percent');

  
echo $percent;
// echo $kode_rak;  


// echo '<div id="txt_sub" class="modal-body col-6" style="padding: 0.5rem; margin-left: 65%;"><h7>Subtotal: '.number_format($sub,2).'</h7></div>';
// echo '<div id="txt_tax" class="modal-body col-6" style="padding: 0.5rem; margin-left: 65%;"><h7>Tax: '.number_format($tax,2).'</h7></div>';
// echo '<div id="txt_total" class="modal-body col-6" style="padding: 0.5rem; margin-left: 65%;"><h6>Total: '.number_format($total,2).'</h6></div>';
?>