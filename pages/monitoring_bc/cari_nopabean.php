<?php
include "../../include/conn.php";
include "fungsi.php";

$jenis_dok = isset($_POST['jenis_dok']) ? $_POST['jenis_dok']: null;
if ($jenis_dok != '') {
    $sql = mysql_query("select DISTINCT no_daftar from whs_inmaterial_fabric where type_bc = '$jenis_dok'");
}
  
    echo'<option value="ALL" selected="true">ALL</option>';
    while($r=mysql_fetch_array($sql)){
        ?>
        <option value="<?php echo $r['no_daftar'] ?>"><?php echo $r['no_daftar'] ?></option>
        <?php        
}

?>