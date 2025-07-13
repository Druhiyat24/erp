<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';

$date = date('d F Y -- H:m:s'); 

header("Content-type: application/octet-stream"); 
header("Content-Disposition: attachment; filename=laporan_po.xls");//ganti nama sesuai keperluan 
header("Pragma: no-cache"); 
header("Expires: 0");
?>
<h2><b>Not Received PO Report</b></h2>
<br>
<?php echo "<p><i>  Downloaded at $date </i></p>"; ?> 
<table border="1">
  <thead>
        <tr>
          <th>No</th>
          <th># PO</th>
          <th>PO Date</th>
          <th>Supplier</th>
          <th>Qty PO</th>
          <th>Unit PO</th>
          <th>Qty BPB</th>
          <th>Unit BPB</th>
          <th>Currency</th>
          <th>Price</th>
          <th>ETD</th>
          <th>ETA</th>
          <th>Expected Date</th>
          
        </tr>
  </thead>

  <tbody>
    <?php 

    $sql="SELECT c.pono,
     c.podate,
     e.supplier,
     d.qty AS qty_po,
     d.unit AS unit_po,
     a.qty AS qty_bpb,
     a.unit AS unit_bpb,
     d.curr,
     d.price,
     c.etd,
     c.eta,
     c.expected_date
      FROM bpb a
      INNER JOIN jo b
      INNER JOIN po_header c
      INNER JOIN po_item d
      INNER JOIN mastersupplier e
      WHERE a.pono=c.pono
      AND c.pono=d.id_po
      AND a.id_jo=b.id
      AND b.id=d.id_jo
      AND a.id_supplier=e.Id_Supplier
      AND c.pono IS NOT NULL
      AND a.qty=0
      ORDER BY c.podate DESC";
    //tampil_data_tanpa_nourut($sql,10);
    tampil_data($sql,12);
    ?>
  </tbody>
</table>
<br>

