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

// $tipe_inv = $_GET['tipe_inv'];
// $nama_supp = $_GET['nama_supp'];
// $nama_buyer = $_GET['nama_buyer'];


header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=IN MATERIAL.xls"); //ganti nama sesuai keperluan 
header("Pragma: no-cache");
header("Expires: 0");

?>
<?php
# END COPAS ADD
?>
<div class="box">
    <div class="box-header">
        <h3 class="box-title">LIST REQUEST MATERIAL</h3>
    </div>
    <div>
        Periode : <?php echo $from; ?> - <?php echo $to; ?>
    </div>
    <div class="box-body">
        <table border="1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor Request</th>
                    <th>Tanggal Request</th>
                    <th>Nama Supplier</th>
                    <th>Tipe Pengeluaran</th>
                    <th>Keterangan</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Job Order</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Dibuat</th>
                    <th>Tanggal Input</th>
                </tr>
            </thead>
            <tbody>
                <?php
                # QUERY TABLE
                $fromcri = date('Y-m-d', strtotime($from));
                $tocri = date('Y-m-d', strtotime($to));

                //   if($tipe_inv == 'ALL' and $nama_supp == 'ALL' and $nama_buyer == 'ALL'){
                //     $where = "where a.tgl_memo >= '$fromcri' and a.tgl_memo <= '$tocri'";
                //   }elseif($tipe_inv != 'ALL' and $nama_supp == 'ALL' and $nama_buyer == 'ALL'){
                //     $where = "where a.jns_inv = '$tipe_inv' and a.tgl_memo >= '$fromcri' and a.tgl_memo <= '$tocri'";
                //   }elseif($tipe_inv == 'ALL' and $nama_supp != 'ALL' and $nama_buyer == 'ALL'){
                //     $where = "where a.id_supplier = '$nama_supp' and a.tgl_memo >= '$fromcri' and a.tgl_memo <= '$tocri'";
                //   }elseif($tipe_inv == 'ALL' and $nama_supp == 'ALL' and $nama_buyer != 'ALL'){
                //     $where = "where a.id_buyer = '$nama_buyer' and a.tgl_memo >= '$fromcri' and a.tgl_memo <= '$tocri'";
                //   }elseif($tipe_inv != 'ALL' and $nama_supp != 'ALL' and $nama_buyer == 'ALL'){
                //     $where = "where a.jns_inv = '$tipe_inv' and a.id_supplier = '$nama_supp' and a.tgl_memo >= '$fromcri' and a.tgl_memo <= '$tocri'";
                //   }elseif($tipe_inv != 'ALL' and $nama_supp == 'ALL' and $nama_buyer != 'ALL'){
                //     $where = "where a.jns_inv = '$tipe_inv' and a.id_buyer = '$nama_buyer' and a.tgl_memo >= '$fromcri' and a.tgl_memo <= '$tocri'";
                //   }elseif($tipe_inv == 'ALL' and $nama_supp != 'ALL' and $nama_buyer != 'ALL'){
                //     $where = "where a.id_supplier = '$nama_supp' and a.id_buyer = '$nama_buyer' and a.tgl_memo >= '$fromcri' and a.tgl_memo <= '$tocri'";
                //   }else{
                //   $where = "where a.jns_inv = '$tipe_inv' and a.id_supplier = '$nama_supp' and a.id_buyer = '$nama_buyer' and a.tgl_memo >= '$fromcri' and a.tgl_memo <= '$tocri'"; 
                // }
                $sql = "select no_req,tgl_req,supplier,tipe_pengeluaran,keterangan,kode_barang,nama_barang,job_order,qty,unit,dibuat,tgl_input from req_material a where tgl_req >= '$fromcri' and tgl_req <= '$tocri'
";

                #echo $sql;
                $query = mysql_query($sql);
                $no = 1;
                while ($data = mysql_fetch_array($query)) {
                    $tgl_req = date('d M Y', strtotime($data[tgl_req]));
                    // $date_input = date('d M Y', strtotime($data[date_input]));
                    echo "<tr>";
                    echo "<td>$no</td>";
                    echo "<td>$data[no_req]</td>";
                    echo "<td>$tgl_req</td>";
                    echo "<td>$data[supplier]</td>";
                    echo "<td>$data[tipe_pengeluaran]</td>";
                    echo "<td>$data[keterangan]</td>";
                    echo "<td>$data[kode_barang]</td>";
                    echo "<td>$data[nama_barang]</td>";
                    echo "<td>$data[job_order]</td>";
                    echo "<td>$data[qty]</td>";
                    echo "<td>$data[unit]</td>";
                    echo "<td>$data[dibuat]</td>";
                    echo "<td>$data[tgl_input]</td>";
                    echo "</tr>";
                    $no++; // menambah nilai nomor urut
                }
                // echo "<td>$data[tgl_input]</td>";
                ?>
            </tbody>
        </table>
    </div>
</div>