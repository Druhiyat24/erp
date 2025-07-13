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

$nama_supp = $_GET['nama_supp'];


header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=LAPORAN TRANSFER BPB.xls"); //ganti nama sesuai keperluan 
header("Pragma: no-cache");
header("Expires: 0");

?>
<?php
# END COPAS ADD
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">LIST TRANSFER BPB</h3>
  </div>
  <div>
    Periode : <?php echo $from; ?> - <?php echo $to; ?>
  </div>
  <div class="box-body">
    <table border="1" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>No</th>
          <th>No Transfer</th>
          <th>Transfer Date</th>
          <th>Supplier</th>
          <th>No BPB</th>
          <th>BPB Date</th>
          <th>No PO</th>
          <th>Curr</th>
          <th>Total Amount</th>
          <th>Status</th>
          <th>User Created</th>
          <th>Created date</th>
        </tr>
      </thead>
      <tbody>
        <?php
          # QUERY TABLE
        $fromcri = date('Y-m-d', strtotime($from));
        $tocri = date('Y-m-d', strtotime($to));

        if($nama_supp == 'ALL' ){
          $where = "where tgl_transfer BETWEEN '$fromcri' and '$tocri'";
        }else{
          $where = "where tgl_transfer BETWEEN '$fromcri' and '$tocri' and supplier = '$nama_supp' ";  
        }


        $sql = "select * from (select no_transfer,tgl_transfer,nama_supp,no_bpb,tgl_bpb,no_po,curr,total,status,CASE
        WHEN status = 'Approved' THEN 'Accept From Warehouse To Accounting'
        WHEN status = 'Transfer' THEN 'Transfer From Warehouse To Accounting'
        WHEN status = 'Cancel' THEN 'Cancel From Warehouse To Accounting'
        END as keterangan,created_by,created_at from ir_trans_bpb GROUP BY id) a $where";

          #echo $sql;
        $query = mysql_query($sql);
        $no = 1;
        while ($data = mysql_fetch_array($query)) {
          $tgl_transfer = date('d M Y', strtotime($data[tgl_transfer]));
          $tgl_bpb = date('d M Y', strtotime($data[tgl_bpb]));

          echo "<tr>";
          echo "<td>$no</td>";
          echo "<td>$data[no_transfer]</td>";
          echo "<td>$tgl_transfer</td>";
          echo "<td>$data[nama_supp]</td>";
          echo "<td>$data[no_bpb]</td>";
          echo "<td>$tgl_bpb</td>";
          echo "<td>$data[no_po]</td>";
          echo "<td>$data[curr]</td>";
          echo "<td>".number_format($data[total],2)."</td>";
          echo "<td>$data[keterangan]</td>";
          echo "<td>$data[created_by]</td>";
          echo "<td>$data[created_at]</td>";
          echo "</tr>";
            $no++; // menambah nilai nomor urut
          }
          // echo "<td>$data[tgl_input]</td>";
          ?>
        </tbody>
      </table>
    </div>
  </div>