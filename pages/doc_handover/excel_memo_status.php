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

$status = $_GET['status'];
$nama_supp = $_GET['nama_supp'];
$nama_buyer = $_GET['nama_buyer'];


header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=LAPORAN STATUS MEMO EXIM.xls"); //ganti nama sesuai keperluan 
header("Pragma: no-cache");
header("Expires: 0");

?>
<?php
# END COPAS ADD
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">LIST STATUS MEMO EXIM</h3>
  </div>
  <div>
    Periode : <?php echo $from; ?> - <?php echo $to; ?>
  </div>
    <div class="box-body">
      <table border="1" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>No</th>
            <th>No Memo</th>
            <th>Tanggal Memo</th>
            <th>Supplier</th>
            <th>Buyer</th>
            <th>Nomor PV</th>
            <th>Tanggal PV</th>
            <th>Nomor Bank Out</th>
            <th>Tanggal Bank Out</th>
            <th>No DN</th>
            <th>Tanggal DN</th>
            <th>Status</th>
            <th>User Input</th>
            <th>Tanggal Input</th>
            <th>User approve</th>
            <th>Tanggal approve</th>
          </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE
          $fromcri = date('Y-m-d', strtotime($from));
          $tocri = date('Y-m-d', strtotime($to));

          if($status == 'ALL' and $nama_supp == 'ALL' and $nama_buyer == 'ALL'){
            $where = "where a.tgl_memo >= '$fromcri' and a.tgl_memo <= '$tocri'";
          }elseif($status != 'ALL' and $nama_supp == 'ALL' and $nama_buyer == 'ALL'){
            $where = "where a.status = '$status' and a.tgl_memo >= '$fromcri' and a.tgl_memo <= '$tocri'";
          }elseif($status == 'ALL' and $nama_supp != 'ALL' and $nama_buyer == 'ALL'){
            $where = "where a.id_supplier = '$nama_supp' and a.tgl_memo >= '$fromcri' and a.tgl_memo <= '$tocri'";
          }elseif($status == 'ALL' and $nama_supp == 'ALL' and $nama_buyer != 'ALL'){
            $where = "where a.id_buyer = '$nama_buyer' and a.tgl_memo >= '$fromcri' and a.tgl_memo <= '$tocri'";
          }elseif($status != 'ALL' and $nama_supp != 'ALL' and $nama_buyer == 'ALL'){
            $where = "where a.status = '$status' and a.id_supplier = '$nama_supp' and a.tgl_memo >= '$fromcri' and a.tgl_memo <= '$tocri'";
          }elseif($status != 'ALL' and $nama_supp == 'ALL' and $nama_buyer != 'ALL'){
            $where = "where a.status = '$status' and a.id_buyer = '$nama_buyer' and a.tgl_memo >= '$fromcri' and a.tgl_memo <= '$tocri'";
          }elseif($status == 'ALL' and $nama_supp != 'ALL' and $nama_buyer != 'ALL'){
            $where = "where a.id_supplier = '$nama_supp' and a.id_buyer = '$nama_buyer' and a.tgl_memo >= '$fromcri' and a.tgl_memo <= '$tocri'";
          }else{
          $where = "where a.status = '$status' and a.id_supplier = '$nama_supp' and a.id_buyer = '$nama_buyer' and a.tgl_memo >= '$fromcri' and a.tgl_memo <= '$tocri'"; 
        }
          $sql = "select a.nm_memo,tgl_memo,supplier,buyer,no_dn,tgl_dn,no_alk,tgl_alk,no_pv,pv_date,no_bankout,bankout_date,status ,id_supplier,id_buyer,date_input,user,approved_date,approved_by from (select a.nm_memo,a.tgl_memo,a.status, ms.supplier supplier, mb.supplier buyer, a.id_supplier,a.id_buyer,a.date_input,a.user,a.approved_date,a.approved_by from memo_h a
          inner join mastersupplier ms on a.id_supplier = ms.id_supplier
          inner join mastersupplier mb on a.id_buyer = mb.id_supplier order by id_h desc) a LEFT JOIN
(select nm_memo, no_dn,tgl_dn,no_alk,tgl_alk from (select * from (select b.nm_memo, a.no_dn,a.tgl_dn from tbl_debitnote_h a INNER JOIN tbl_debitnote_det b on b.no_dn = a.no_dn where b.nm_memo like '%MEMO%' and a.status != 'CANCEL' GROUP BY b.nm_memo) a left JOIN
(select b.no_ref,a.no_alk,a.tgl_alk from tbl_alokasi a INNER JOIN tbl_alokasi_detail b on b.no_alk = a.no_alk where b.no_ref like '%DN%' and a.status != 'CANCEL' GROUP BY b.no_ref) b on b.no_ref = a.no_dn) a) b on b.nm_memo = a.nm_memo LEFT JOIN
(select reff_doc nm_memo, no_pv,pv_date,no_bankout,bankout_date from (select * from (select b.reff_doc,a.no_pv,a.pv_date from tbl_pv_h a INNER JOIN tbl_pv b on b.no_pv =  a.no_pv where b.reff_doc like '%MEMO/%' and a.status != 'CANCEL') a LEFT JOIN
(select a.no_bankout,a.bankout_date,b.no_reff from b_bankout_h a INNER JOIN b_bankout_det b on b.no_bankout = a.no_bankout where b.no_reff like '%PV/%' and a.status != 'CANCEL') b on b.no_reff = a.no_pv) a) c on c.nm_memo = a.nm_memo $where";

          #echo $sql;
          $query = mysql_query($sql);
          $no = 1;
          while ($data = mysql_fetch_array($query)) {
            $tgl_memo = date('d M Y', strtotime($data[tgl_memo]));
            $approveddate = $data[approved_date];
            $pvdate = $data[pv_date];
            $bankoutdate = $data[bankout_date];
            $tgldn = $data[tgl_dn];
            $date_input = date('d M Y', strtotime($data[date_input]));

            if ($approveddate == null || $approveddate == '' ) {
              $approved_date = '-';
            }else{
              $approved_date = date('d M Y', strtotime($data[approved_date]));
            }
            if ($pvdate == null || $pvdate == '' ) {
              $pv_date = '-';
            }else{
              $pv_date = date('d M Y', strtotime($data[pv_date]));
            }
            if ($bankoutdate == null || $bankoutdate == '' ) {
              $bankout_date = '-';
            }else{
              $bankout_date = date('d M Y', strtotime($data[bankout_date]));
            }
            if ($tgldn == null || $tgldn == '' ) {
              $tgl_dn = '-';
            }else{
              $tgl_dn = date('d M Y', strtotime($data[tgl_dn]));
            }

            echo "<tr>";
            echo "<td>$no</td>";
            echo "<td>$data[nm_memo]</td>";
            echo "<td>$tgl_memo</td>";
            echo "<td>$data[supplier]</td>";
            echo "<td>$data[buyer]</td>";
            echo "<td>$data[no_pv]</td>";
            echo "<td>$pv_date</td>";
            echo "<td>$data[no_bankout]</td>";
            echo "<td>$bankout_date</td>";
            echo "<td>$data[no_dn]</td>";
            echo "<td>$tgl_dn</td>";
            echo "<td>$data[status]</td>";
            echo "<td>$data[user]</td>";
            echo "<td>$date_input</td>";
            echo "<td>$data[approved_by]</td>";
            echo "<td>$approved_date</td>";
            echo "</tr>";
            $no++; // menambah nilai nomor urut
          }
          // echo "<td>$data[tgl_input]</td>";
          ?>
        </tbody>
      </table>
    </div>
</div>