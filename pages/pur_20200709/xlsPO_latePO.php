<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';

$date = date('d F Y -- H:m:s'); 

header("Content-type: application/octet-stream"); 
header("Content-Disposition: attachment; filename=laporan_po.xls");//ganti nama sesuai keperluan 
header("Pragma: no-cache"); 
header("Expires: 0");
?>
<h2><b>Late PO from PR Report</b></h2>
<br>
<?php echo "<p><i>  Downloaded at $date </i></p>"; ?> 
<table border="1">
  <thead>
        <tr>
          <th>No</th>
          <th>Supplier</th>
          <th># JO</th>
          <th>JO Date</th>
          <th>Username</th>
          <th>Approval PR Date</th>
          <th>Approval PR By</th>

        </tr>
  </thead>

  <tbody>
    <?php 

    $sql="SELECT d.supplier, a.jo_no, a.jo_date, a.username, a.app_date, a.app_by
      FROM jo a inner join po_header b inner join po_item c inner join mastersupplier d WHERE b.id_supplier=d.Id_Supplier AND b.id=c.id_po AND a.id=c.id_jo AND a.app_date IS NOT NULL AND a.jo_date=a.jo_date AND a.app='A' GROUP BY d.Id_Supplier ORDER BY a.jo_date asc ";
    //tampil_data_tanpa_nourut($sql,10);
    tampil_data($sql,6);
    ?>
  </tbody>
</table>
<br>

