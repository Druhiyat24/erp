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

$tipe_inv = $_GET['tipe_inv'];
$nama_supp = $_GET['nama_supp'];
$nama_buyer = $_GET['nama_buyer'];


header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=LAPORAN MEMO EXIM $tipe_inv.xls"); //ganti nama sesuai keperluan 
header("Pragma: no-cache");
header("Expires: 0");

?>
<?php
# END COPAS ADD
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">LIST MEMO EXIM <?php echo $tipe_inv; ?></h3>
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
            <th>Jenis Invoice</th>
            <th>No Invoice</th>
            <th>No Invoice Buyer</th>
            <th>kepada</th>
            <th>Jenis Transaksi</th>
            <th>Jenis Pengiriman</th>
            <th>Dokumen Pendukung</th>
            <th>Supplier</th>
            <th>Buyer</th>
            <th>Ditagihkan</th>
            <th>jatuh Tempo</th>
            <th>Curr</th>
            <th>Biaya</th>
            <th>Catatan</th>
            <th>Status</th>
            <th>User Input</th>
            <th>Tanggal Input</th>
          </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE
          $fromcri = date('Y-m-d', strtotime($from));
          $tocri = date('Y-m-d', strtotime($to));

          if($tipe_inv == 'ALL' and $nama_supp == 'ALL' and $nama_buyer == 'ALL'){
            $where = "where a.tgl_memo >= '$fromcri' and a.tgl_memo <= '$tocri'";
          }elseif($tipe_inv != 'ALL' and $nama_supp == 'ALL' and $nama_buyer == 'ALL'){
            $where = "where a.jns_inv = '$tipe_inv' and a.tgl_memo >= '$fromcri' and a.tgl_memo <= '$tocri'";
          }elseif($tipe_inv == 'ALL' and $nama_supp != 'ALL' and $nama_buyer == 'ALL'){
            $where = "where a.id_supplier = '$nama_supp' and a.tgl_memo >= '$fromcri' and a.tgl_memo <= '$tocri'";
          }elseif($tipe_inv == 'ALL' and $nama_supp == 'ALL' and $nama_buyer != 'ALL'){
            $where = "where a.id_buyer = '$nama_buyer' and a.tgl_memo >= '$fromcri' and a.tgl_memo <= '$tocri'";
          }elseif($tipe_inv != 'ALL' and $nama_supp != 'ALL' and $nama_buyer == 'ALL'){
            $where = "where a.jns_inv = '$tipe_inv' and a.id_supplier = '$nama_supp' and a.tgl_memo >= '$fromcri' and a.tgl_memo <= '$tocri'";
          }elseif($tipe_inv != 'ALL' and $nama_supp == 'ALL' and $nama_buyer != 'ALL'){
            $where = "where a.jns_inv = '$tipe_inv' and a.id_buyer = '$nama_buyer' and a.tgl_memo >= '$fromcri' and a.tgl_memo <= '$tocri'";
          }elseif($tipe_inv == 'ALL' and $nama_supp != 'ALL' and $nama_buyer != 'ALL'){
            $where = "where a.id_supplier = '$nama_supp' and a.id_buyer = '$nama_buyer' and a.tgl_memo >= '$fromcri' and a.tgl_memo <= '$tocri'";
          }else{
          $where = "where a.jns_inv = '$tipe_inv' and a.id_supplier = '$nama_supp' and a.id_buyer = '$nama_buyer' and a.tgl_memo >= '$fromcri' and a.tgl_memo <= '$tocri'"; 
        }
          $sql = "select id_h,id_supplier,id_buyer,nm_memo,tgl_memo,jns_inv, no_invoice, inv_buyer, kepada, jns_trans,jns_pengiriman,ditagihkan,curr,jatuh_tempo,dok_pendukung,supplier,buyer,nm_ctg,nm_sub_ctg,biaya, cancel,notes, status,user, date_input from (select * from (select a.id_h,a.nm_memo,a.tgl_memo,a.jns_inv,IF(mdet.inv_vendor is null,'-',mdet.inv_vendor) inv_buyer,a.kepada,a.jns_trans,a.jns_pengiriman,IF(a.ditagihkan != 'Y','TIDAK','YA') ditagihkan,a.curr,a.jatuh_tempo, a.dok_pendukung, ms.supplier supplier, mb.supplier buyer,mdet.nm_ctg,mdet.nm_sub_ctg,format(round(sum(mdet.biaya),2),2) biaya,mdet.cancel, IF(a.notes is null,'-',a.notes) notes,a.status,a.user,a.date_input,a.id_supplier,a.id_buyer from memo_h a
          inner join mastersupplier ms on a.id_supplier = ms.id_supplier
          inner join mastersupplier mb on a.id_buyer = mb.id_supplier
          inner join memo_det mdet on mdet.id_h = a.id_h where mdet.cancel != 'Y'
          GROUP BY a.nm_memo order by mdet.id_h asc) a left join      
(select a.id_h idh, GROUP_CONCAT(b.no_invoice) no_invoice from memo_h a inner join memo_inv b on b.id_h = a.id_h GROUP BY a.id_h) b on b.idh = a.id_h) a $where";

          // $sql = "select a.nm_memo,a.tgl_memo,a.jns_inv,IF(a.inv_buyer is null,'-',a.inv_buyer) inv_buyer,a.kepada,a.jns_trans,a.jns_pengiriman,IF(a.ditagihkan != 'Y','TIDAK','YA') ditagihkan,a.curr,a.jatuh_tempo, a.dok_pendukung, ms.supplier supplier, mb.supplier buyer,mdet.nm_ctg,mdet.nm_sub_ctg,round(sum(mdet.biaya),2) biaya,mdet.cancel, IF(a.notes is null,'-',a.notes) notes,a.status,a.user,a.date_input from memo_h a
          // inner join mastersupplier ms on a.id_supplier = ms.id_supplier
          // inner join mastersupplier mb on a.id_buyer = mb.id_supplier
          // inner join memo_det mdet on mdet.id_h = a.id_h
          // $where GROUP BY a.nm_memo order by mdet.id_h asc";

          #echo $sql;
          $query = mysql_query($sql);
          $no = 1;
          while ($data = mysql_fetch_array($query)) {
            $tgl_memo = date('d M Y', strtotime($data[tgl_memo]));
            $date_input = date('d M Y', strtotime($data[date_input]));
            echo "<tr>";
            echo "<td>$no</td>";
            echo "<td>$data[nm_memo]</td>";
            echo "<td>$tgl_memo</td>";
            echo "<td>$data[jns_inv]</td>";
            echo "<td>$data[no_invoice]</td>";
            echo "<td>$data[inv_buyer]</td>";
            echo "<td>$data[kepada]</td>";
            echo "<td>$data[jns_trans]</td>";
            echo "<td>$data[jns_pengiriman]</td>";
            echo "<td>$data[dok_pendukung]</td>";
            echo "<td>$data[supplier]</td>";
            echo "<td>$data[buyer]</td>";
            echo "<td>$data[ditagihkan]</td>";
            echo "<td>$data[jatuh_tempo]</td>";
            echo "<td>$data[curr]</td>";
            echo "<td>$data[biaya]</td>";
            echo "<td>$data[notes]</td>";
            echo "<td>$data[status]</td>";
            echo "<td>$data[user]</td>";
            echo "<td>$date_input</td>";
            echo "</tr>";
            $no++; // menambah nilai nomor urut
          }
          // echo "<td>$data[tgl_input]</td>";
          ?>
        </tbody>
      </table>
    </div>
</div>