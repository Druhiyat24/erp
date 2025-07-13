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

$kd = $_GET['kd'];
$kode_rak = $_GET['kode_rak'];

if ($kd == 'ALL' and $kode_rak == 'ALL') {
    $kata = "Semua Rak";
} elseif ($kd != 'ALL' and $kode_rak == 'ALL') {
    $kata = "Rak $kd";
} elseif ($kd == 'ALL' and $kode_rak != 'ALL') {
    $kata = "Rak $kode_rak";
} else {
    $kata = "Rak $kode_rak";
}


header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Laporan Penerimaan Material $kata.xls"); //ganti nama sesuai keperluan 
header("Pragma: no-cache");
header("Expires: 0");

?>
<?php
# END COPAS ADD
?>
<div class="box">
    <div class="box-header">
        <h3 class="box-title">List Penerimaan Material <?php echo $kata; ?></h3>
    </div>
    <div>
        Periode : <?php echo $from; ?> - <?php echo $to; ?>
    </div>
    <div class="box-body">
        <table border="1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Rak</th>
                    <th>Kode Rak</th>
                    <th>Nomor Lot</th>
                    <th>No Dokumen</th>
                    <th>Tanggal Dokumen</th>
                    <th>nama Supplier</th>
                    <th>Nomor Po</th>
                    <th>Nomor SJ</th>
                    <th>Job Order</th>
                    <th>Tipe Pembelian</th>
                    <th>Nama Barang</th>
                    <th>Nomor Roll</th>
                    <th>Qty Roll</th>
                    <th>Keterangan</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th>Tanggal Input</th>
                </tr>
            </thead>
            <tbody>
                <?php
                # QUERY TABLE
                $fromcri = date('Y-m-d', strtotime($from));
                $tocri = date('Y-m-d', strtotime($to));

                if ($kd == 'ALL' and $kode_rak == 'ALL') {
                    $where = "where c.tgl_dok >= '$fromcri' and c.tgl_dok <= '$tocri'";
                } elseif ($kd != 'ALL' and $kode_rak == 'ALL') {
                    $where = "where a.kd = '$kd' and c.tgl_dok >= '$fromcri' and c.tgl_dok <= '$tocri'";
                } elseif ($kd == 'ALL' and $kode_rak != 'ALL') {
                    $where = "where a.kode_rak = '$kode_rak' and c.tgl_dok >= '$fromcri' and c.tgl_dok <= '$tocri'";
                } else {
                    $where = "where a.kd = '$kd' and a.kode_rak = '$kode_rak' and c.tgl_dok >= '$fromcri' and c.tgl_dok <= '$tocri'";
                }
                $sql = "select a.nama_rak,a.kode_rak,b.id_barcode,b.lot_no,b.roll_no,concat(b.roll_qty,' ',c.unit) roll_qty, count(b.roll_qty) qty, c.no_dok,c.tgl_dok,c.supplier,c.no_po,c.tipe_pembelian,c.no_sj,c.keterangan, c.kode_barang, c.nama_barang, c.job_order,b.user,b.date_input from m_rak a inner join in_material_det b on b.kode_rak = a.kode_rak inner join in_material c on c.id = b.id_in_material   $where group by b.id
";

                #echo $sql;
                $query = mysql_query($sql);
                $no = 1;
                while ($data = mysql_fetch_array($query)) {
                    $tgl_dok = date('d M Y', strtotime($data[tgl_dok]));
                    $date_input = date('d M Y', strtotime($data[date_input]));
                    echo "<tr>";
                    echo "<td>$no</td>";
                    echo "<td>$data[nama_rak]</td>";
                    echo "<td>$data[kode_rak]</td>";
                    echo "<td>$data[lot_no]</td>";
                    echo "<td>$data[no_dok]</td>";
                    echo "<td>$tgl_dok</td>";
                    echo "<td>$data[supplier]</td>";
                    echo "<td>$data[no_po]</td>";
                    echo "<td>$data[no_sj]</td>";
                    echo "<td>$data[job_order]</td>";
                    echo "<td>$data[tipe_pembelian]</td>";
                    echo "<td>$data[nama_barang]</td>";
                    echo "<td>$data[roll_no]</td>";
                    echo "<td>$data[roll_qty]</td>";
                    echo "<td>$data[keterangan]</td>";
                    echo "<td></td>";
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