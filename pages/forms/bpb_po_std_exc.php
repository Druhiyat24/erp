<?php
if (empty($_SESSION['username'])) {
  header("location:../../index.php");
}
if (isset($_SESSION['username'])) {
  $user = $_SESSION['username'];
} else {
  header("location:../../index.php");
}

if ($mode == 'exc') {
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=lap_penerimaan_det.xls"); //ganti nama sesuai keperluan 
  header("Pragma: no-cache");
  header("Expires: 0");
}

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
    <h3 class="box-title">List Penerimaan</h3>
  </div>
  <div>
    Periode : <?php echo $from; ?> - <?php echo $to; ?>
  </div>
  <div class="box-body">
    <table border="1" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>No</th>
          <th>Nomor BPB</th>
          <th>Tgl BPB</th>
          <th>Supplier</th>
          <th>No PO</th>
          <th>No SJ</th>
          <th>Qty BPB</th>
          <th>Qty Rak</th>
          <th>Status Lokasi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $fromcri = date('Y-m-d', strtotime($from));
        $tocri = date('Y-m-d', strtotime($to));

        $sql = "select bpb.bpbno,bpb.bpbno_int, bpb.bpbdate, supplier, ac.kpno, bpb.remark, bpb.invno,bpb.pono,
         jenis_dok, bcno, bcdate, jenis_trans, bpb.username, bpb.dateinput, sum(bpb.qty) qtybpb,sr.roll_qty roll_qty,
         if (sum(bpb.qty) = sr.roll_qty, 'Ok', 'Kurang') stat_lokasi, confirm, confirm_by, confirm_date, bpb.cancel,round(coalesce(bpb.qty,0),2) qty, round(coalesce(sr.roll_qty,0),2) roll_qty
         from bpb
         inner join mastersupplier ms on bpb.id_supplier = ms.id_supplier
         inner join jo_det jd on bpb.id_jo = jd.id_jo
         inner join so on jd.id_so = so.id
         inner join act_costing ac on so.id_cost = ac.id
         left join (select bpbno,id_item,id_jo,sum(roll_qty) roll_qty from bpb_det group by bpbno) sr on bpb.bpbno = sr.bpbno
         where bpb.bpbdate >= '$fromcri' and bpb.bpbdate <= '$tocri' and bpb.bpbno like 'A%' and bpb.bpbno like 'A%' and substring(bpb.bpbno,-2) != '-R'
         and pono is not null and id_po_item is not null
         group by bpbno
         order by bpbdate desc
";

        #echo $sql;
        $query = mysql_query($sql);
        $no = 1;
        while ($data = mysql_fetch_array($query)) {
          echo "<tr>";
          echo "<td>$no</td>";
          echo "<td>$data[bpbno_int]</td>";
          echo "<td>$data[bpbdate]</td>";
          echo "<td>$data[supplier]</td>";
          echo "<td>$data[pono]</td>";
          echo "<td>$data[invno]</td>";
          echo "<td>$data[qtybpb]</td>";
          echo "<td>$data[roll_qty]</td>";
          echo "<td>$data[stat_lokasi]</td>";
          echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
        // echo "<td>$data[tgl_input]</td>";
        ?>
      </tbody>
    </table>
  </div>
</div>