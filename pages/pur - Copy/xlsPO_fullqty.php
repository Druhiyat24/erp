<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';

$date = date('d F Y -- H:m:s'); 

header("Content-type: application/octet-stream"); 
header("Content-Disposition: attachment; filename=laporan_po.xls");//ganti nama sesuai keperluan 
header("Pragma: no-cache"); 
header("Expires: 0");
?>
<h2><b>Full Qty Report</b></h2>
<br>
<?php echo "<p><i>  Downloaded at $date </i></p>"; ?> 
<table border="1">
  <thead>
        <tr>
          <th>No</th>
          <th># PO</th>
          <th>Qty PO</th>
          <th>Qty BPB</th>
          <th>Unit</th>
          <th>Currency</th>
          <th>Price</th>
          <th>Supplier</th>
          <th>ETD</th>
          <th>ETA</th>

        </tr>
  </thead>

  <tbody>
    <?php 

    $sql="SELECT a.pono,
    b.qty AS qty_po,
    c.qty AS qty_bpb,
    b.unit,
    b.curr,
    b.price,
    d.Supplier,
    a.eta,
    a.etd
     FROM po_header a 
     INNER JOIN po_item b 
     INNER JOIN bpb c 
     INNER JOIN mastersupplier d 
     WHERE a.id=b.id_po
     AND a.id_supplier=c.id_supplier
     AND c.id_supplier=d.Id_Supplier
     AND b.cancel='N'
     AND c.qty>=b.qty
     ORDER BY a.pono, d.Supplier ASC ";
    //tampil_data_tanpa_nourut($sql,10);
    tampil_data($sql,9);
    ?>
  </tbody>
</table>
<br>
