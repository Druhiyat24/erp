<?php
if (empty($_SESSION['username'])) {
  header("location:../../index.php");
}
if (isset($_SESSION['username'])) {
  $user = $_SESSION['username'];
} else {
  header("location:../../index.php");
}


if (isset($_GET['from'])) {
  $from = date('d M Y', strtotime($_GET['from']));
} else {
  $from = "";
}
if (isset($_GET['to'])) {
  $to = date('d M Y', strtotime($_GET['to']));
} else {
  $to = "";
}
$tipe_data = $_GET['tipe_data'];

if ($tipe_data == 'CUTTING NUMBERING') {
  $tipe_data_xls = 'cut_numbering';
} else if ($tipe_data == '') {
  $tipe_data_xls = '';
}


header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Lap_$tipe_data_xls.xls"); //ganti nama sesuai keperluan 
header("Pragma: no-cache");
header("Expires: 0");

?>
<?php
# END COPAS ADD
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">LIST PENERIMAAN <?php echo $tipe_data; ?></h3>
  </div>
  <div>
    Periode : <?php echo $from; ?> - <?php echo $to; ?>
  </div>
  <?php if ($tipe_data == 'CUTTING NUMBERING') { ?>
    <div class="box-body">
      <table border="1" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>No</th>
            <th>Tgl</th>
            <th>Buyer</th>
            <th>Product Group</th>
            <th>Product Type</th>
            <th>No WS</th>
            <th>Style</th>
            <th>Style Prod</th>
            <th>Brand</th>
            <th>Color</th>
            <th>Size</th>
            <th>Main Dest</th>
            <th>Dest</th>
            <th>Qty</th>
            <th>Unit</th>
          </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE
          $fromcri = date('Y-m-d 00:00:00', strtotime($from));
          $tocri = date('Y-m-d 23:59:59', strtotime($to));

          $sql = "SELECT CAST(a.tgl_input AS date)tgl,supplier buyer,ac.main_dest,product_group,product_item,ac.kpno,ac.styleno,ac.brand,so.so_no,sd.styleno_prod, sd.color, sd.size, sd.dest, count(a.id_qr) total, 'PCS' unit 
          FROM numbering_input a
          inner join so_det sd on a.id_qr = sd.id
          inner join so on sd.id_so = so.id
          inner join act_costing ac on so.id_cost = ac.id
          inner join masterproduct mp on ac.id_product = mp.id
          inner join mastersupplier mb on ac.id_buyer = mb.id_supplier
          where a.tgl_input >= '$fromcri' and a.tgl_input <= '$tocri'
          group by a.id_qr, tgl
          order by tgl asc
";

          #echo $sql;
          $query = mysql_query($sql);
          $no = 1;
          while ($data = mysql_fetch_array($query)) {
            $tgl = date('d M Y', strtotime($data[tgl]));
            echo "<tr>";
            echo "<td>$no</td>";
            echo "<td>$tgl</td>";
            echo "<td>$data[buyer]</td>";
            echo "<td>$data[product_group]</td>";
            echo "<td>$data[product_item]</td>";
            echo "<td>$data[kpno]</td>";
            echo "<td>$data[styleno]</td>";
            echo "<td>$data[styleno_prod]</td>";
            echo "<td>$data[brand]</td>";
            echo "<td>$data[color]</td>";
            echo "<td>$data[size]</td>";
            echo "<td>$data[main_dest]</td>";
            echo "<td>$data[total]</td>";
            echo "<td>$data[unit]</td>";
            echo "</tr>";
            $no++; // menambah nilai nomor urut
          }
          // echo "<td>$data[tgl_input]</td>";
          ?>
        </tbody>
      </table>
    </div>
  <?php } ?>
</div>