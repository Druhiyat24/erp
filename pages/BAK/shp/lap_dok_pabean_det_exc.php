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

$nama_pilihan = $_GET['nama_pilihan'];


header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=LAPORAN DOKUMEN PABEAN DETAIL $nama_pilihan.xls"); //ganti nama sesuai keperluan 
header("Pragma: no-cache");
header("Expires: 0");

?>
<?php
# END COPAS ADD
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">LIST DETAIL DOKUMEN PABEAN <?php echo $nama_pilihan; ?></h3>
  </div>
  <div>
    Periode : <?php echo $from; ?> - <?php echo $to; ?>
  </div>
  <div class="box-body">
    <table border="1" class="table table-bordered table-striped" width="100%">
      <thead>
        <tr>
          <th>No</th>
          <th>No Upload</th>
          <th>Jenis Dokumen</th>
          <th>Supplier</th>
          <th>No Daftar</th>
          <th>Tgl Daftar</th>
          <th>No Aju</th>
          <th>Tgl Aju</th> 
          <th>Kode Barang</th> 
          <th>Nama Barang</th>
          <th>Qty</th>
          <th>Satuan</th>
          <th>Curr</th>
          <th>Price</th>
          <th>Total</th>
          <th>Rate</th>
          <th>Total IDR</th>
          <th>Uploaded By</th>
          <th>Uploaded Date</th>
        </tr>
      </thead>
      <tbody>
        <?php
          # QUERY TABLE
        $fromcri = date('Y-m-d', strtotime($from));
        $tocri = date('Y-m-d', strtotime($to));

        if($nama_pilihan == 'ALL'){
          $where = "where tgl_daftar >= '$fromcri' and tgl_daftar <= '$tocri'";
        }else{
          $where = "where kode_dokumen_format = '$nama_pilihan' tgl_daftar >= '$fromcri' and tgl_daftar <= '$tocri'";  
        }

        $sql = "select * from (SELECT *, CASE 
        WHEN LENGTH(kode_dokumen) = 3 THEN 
        CONCAT('BC ', 
        SUBSTRING(kode_dokumen, 1, 1), '.', 
        SUBSTRING(kode_dokumen, 2, 1), '.', 
        SUBSTRING(kode_dokumen, 3, 1))
        WHEN LENGTH(kode_dokumen) = 2 THEN 
        CONCAT('BC ', 
        SUBSTRING(kode_dokumen, 1, 1), '.', 
        SUBSTRING(kode_dokumen, 2, 1))
        ELSE kode_dokumen 
        END AS kode_dokumen_format FROM ( SELECT a.*,c.nama_entitas,kode_barang, uraian, qty, unit, curr, price, rates, cif, cif_rupiah FROM (SELECT no_dokumen, kode_dokumen ,nomor_aju,SUBSTRING(nomor_aju,-6) no_aju,DATE_FORMAT(STR_TO_DATE(SUBSTRING(nomor_aju,13,8),'%Y%m%d'),'%Y-%m-%d') tgl_aju,LPAD(nomor_daftar,6,0) no_daftar,tanggal_daftar tgl_daftar, created_by, created_date FROM exim_header) a LEFT JOIN ( SELECT nomor_aju,kode_barang,uraian,jumlah_satuan qty,kode_satuan unit, IF(ndpbm = 1,'IDR','USD') curr,(cif/jumlah_satuan) price, ndpbm rates, cif,cif_rupiah FROM exim_barang) b ON b.nomor_aju=a.nomor_aju left join (select nomor_aju, nama_entitas from exim_entitas where  kode_entitas = '5' GROUP BY nomor_aju) c on c.nomor_aju=a.nomor_aju) a) a $where";



      // echo $sql;
        $query = mysql_query($sql);
        $no = 1;
        while ($data = mysql_fetch_array($query)) {
          $tgl_aju = date('d M Y', strtotime($data[tgl_aju]));
          $tgl_daftar = date('d M Y', strtotime($data[tgl_daftar]));

          echo "<tr>";
          echo "<td>$no</td>";
          echo "<td>$data[no_dokumen]</td>";
          echo "<td>$data[kode_dokumen_format]</td>";
          echo "<td>$data[nama_entitas]</td>";
          echo "<td style='mso-number-format:\"@\";'>$data[no_daftar]</td>";
          echo "<td>$tgl_daftar</td>";
          echo "<td style='mso-number-format:\"@\";'>$data[no_aju]</td>";
          echo "<td>$tgl_aju</td>";
          echo "<td>$data[kode_barang]</td>";
          echo "<td>$data[uraian]</td>";
          echo "<td style='text-align: right;'>".number_format($data['qty'],2)."</td>";
          echo "<td>$data[unit]</td>";
          echo "<td>$data[curr]</td>";
          echo "<td style='text-align: right;'>".number_format($data['price'],2)."</td>";
          echo "<td style='text-align: right;'>".number_format($data['cif'],2)."</td>";
          echo "<td style='text-align: right;'>".number_format($data['rates'],2)."</td>";
          echo "<td style='text-align: right;'>".number_format($data['cif_rupiah'],2)."</td>";
          echo "<td>$data[created_by]</td>";
          echo "<td>$data[created_date]</td>";
          echo "</tr>";
            $no++; // menambah nilai nomor urut
          }
          // echo "<td>$data[tgl_input]</td>";
          ?>
        </tbody>
      </table>
    </div>
  </div>