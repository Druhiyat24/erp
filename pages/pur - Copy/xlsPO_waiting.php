<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';

$date = date('d F Y -- H:m:s'); 

header("Content-type: application/octet-stream"); 
header("Content-Disposition: attachment; filename=laporan_po.xls");//ganti nama sesuai keperluan 
header("Pragma: no-cache"); 
header("Expires: 0");
?>
<h2><b>Waiting PO Report</b></h2>
<br>
<?php echo "<p><i>  Downloaded at $date </i></p>"; ?> 
<table border="1">
  <thead>
        <tr>
          <th>No</th>
          <th># PO</th>
          <th>PO Date</th>
          <th>Supplier</th>
          <th>Payment Terms</th>
          <th>ETD</th>
          <th>ETA</th>
          <th>Expected Date</th>
          <th>Status</th>
        </tr>
  </thead>

  <tbody>
    <?php 

    $sql="SELECT a.pono, DATE_FORMAT(a.podate, '%e %M %Y'), b.supplier, c.nama_pterms, DATE_FORMAT(a.etd, '%e %M %Y'), DATE_FORMAT(a.eta, '%e %M %Y'), DATE_FORMAT(a.expected_date, '%e %M %Y'), a.app from po_header a inner join mastersupplier b inner join masterpterms c WHERE a.id_supplier=b.Id_Supplier AND a.id_terms=c.id AND a.app='W' AND pono is not null  order by podate DESC";
    //tampil_data_tanpa_nourut($sql,10);
    tampil_data($sql,8);
    ?>
  </tbody>
</table>
<br>

