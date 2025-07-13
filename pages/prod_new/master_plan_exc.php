<?php
if (empty($_SESSION['username'])) {
  header("location:../../index.php");
}
if (isset($_SESSION['username'])) {
  $user = $_SESSION['username'];
} else {
  header("location:../../index.php");
}


header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=lap_plan.xls"); //ganti nama sesuai keperluan 
header("Pragma: no-cache");
header("Expires: 0");


$mod = $_GET['mod'];

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

?>
<?php
# END COPAS ADD
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Master Plan</h3>
  </div>
  <div>
    Periode : <?php echo $from; ?> - <?php echo $to; ?>
  </div>
  <div class="box-body">
    <table border="1" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>No</th>
          <th>Tgl Plan</th>
          <th>Line</th>
          <th>WS</th>
          <th>Buyer</th>
          <th>Style</th>
          <th>Color</th>
          <th>Qty Order</th>
          <th>SMV</th>
          <th>Man Power</th>
          <th>Jam Kerja</th>
          <th>Plan Day</th>
          <th>Target Effy</th>
          <th>Created By</th>
        </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $fromcri = date('Y-m-d', strtotime($from));
        $tocri = date('Y-m-d', strtotime($to));

        $sql = "select a.*, ac.kpno,ac.styleno, supplier buyer,sd.qty_order from master_plan a
        inner join act_costing ac on a.id_ws = ac.id
        inner join mastersupplier mb on ac.id_buyer = mb.id_supplier
        inner join so on ac.id = so.id_cost
        inner join (select id_so, color,sum(qty) qty_order from so_det sd where sd.cancel = 'N' group by id_so,color) sd 
        on so.id = sd.id_so and sd.color = a.color
        where a.tgl_plan >= '$fromcri' and a.tgl_plan <= '$tocri'
";

        #echo $sql;
        $query = mysql_query($sql);
        $no = 1;
        while ($data = mysql_fetch_array($query)) {
          $createby = $data['create_by'] . " (" . fd_view_dt($data['tgl_input']) . ")";
          echo "<tr>";
          echo "
          <td>$no</td>";
          echo "
          <td>" . fd_view($data[tgl_plan]) . "</td>";
          echo "
          <td>$data[sewing_line]</td>
          <td>$data[kpno]</td>
          <td>$data[buyer]</td>
          <td>$data[styleno]</td>
          <td>$data[color]</td>
          <td>$data[qty_order]</td>
          <td>$data[smv]</td>
          <td>$data[man_power]</td>
          <td>$data[jam_kerja]</td>
          <td>$data[plan_target]</td>
          <td>$data[target_effy]</td>
          <td>$createby</td>";
          echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
        // echo "<td>$data[tgl_input]</td>";
        ?>
      </tbody>
    </table>
  </div>
</div>