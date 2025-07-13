<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';

$id_cost=$_GET['id'];

header("Content-type: application/octet-stream"); 
header("Content-Disposition: attachment; filename=master.xls");//ganti nama sesuai keperluan 
header("Pragma: no-cache"); 
header("Expires: 0");
?>
<table>
  <thead>
    <th>Kode Customer</th>
    <th>Customer</th>
    <th>Alamat</th>
    <th>Area</th>
    <th>Negara</th>
  </thead>
  <tbody>
    <?php 
    $sql="SELECT supplier_code, supplier, alamat, if(area='I','Import/Export',
      if(area='L','Lokal',if(area='F','Factory',area))) areanya , country
      FROM mastersupplier where tipe_sup='C' ORDER BY id_supplier desc";
    tampil_data_tanpa_nourut($sql,5);
    ?>
  </tbody>
</table>  