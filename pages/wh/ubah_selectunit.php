<?php
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (empty($_SESSION['username'])) {
    header("location:../../index.php");
}
$user = $_SESSION['username'];

$kd_rak = isset($_POST['kd_rak']) ? $_POST['kd_rak']: null;

    $sql = mysqli_query($conn_li, "select DISTINCT a.kode_unit, a.nama_unit from m_unit a inner join in_material b on b.unit = a.kode_unit inner join in_material_det c on c.id_in_material = b.id inner join m_rak d on d.kode_rak = c.kode_rak where d.kd = '$kd_rak'");
  
    while($r=mysqli_fetch_array($sql)){
        $isSelected = ' selected="selected"';
        echo '<option value="'.$r['kode_unit'].'"">'.$r['nama_unit'].'</option>'; 
        ?>
<!--         <option  value="<?php echo $r['id_ctg5'] ?>"><?php echo $r['name'] ?></option>
 -->        <?php        
}


// echo '<div id="txt_sub" class="modal-body col-6" style="padding: 0.5rem; margin-left: 65%;"><h7>Subtotal: '.number_format($sub,2).'</h7></div>';
// echo '<div id="txt_tax" class="modal-body col-6" style="padding: 0.5rem; margin-left: 65%;"><h7>Tax: '.number_format($tax,2).'</h7></div>';
// echo '<div id="txt_total" class="modal-body col-6" style="padding: 0.5rem; margin-left: 65%;"><h6>Total: '.number_format($total,2).'</h6></div>';
?>