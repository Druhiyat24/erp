<?php
if (empty($_SESSION['username'])) {
    header("location:../../index.php");
}
if (isset($_SESSION['username'])) {
    $user = $_SESSION['username'];
} else {
    header("location:../../index.php");
}


// if (isset($_GET['from'])) {
//     $from = date('d M Y', strtotime($_GET['from']));
// } else {
//     $from = "";
// }
// if (isset($_GET['to'])) {
//     $to = date('d M Y', strtotime($_GET['to']));
// } else {
//     $to = "";
// }

// $kd = $_GET['kd'];
// $kode_rak = $_GET['kode_rak'];

// if ($kd == 'ALL' and $kode_rak == 'ALL') {
//     $kata = "Semua Rak";
// } elseif ($kd != 'ALL' and $kode_rak == 'ALL') {
//     $kata = "Rak $kd";
// } elseif ($kd == 'ALL' and $kode_rak != 'ALL') {
//     $kata = "Rak $kode_rak";
// } else {
//     $kata = "Rak $kode_rak";
// }


header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Laporan Mutasi Rak.xls"); //ganti nama sesuai keperluan 
header("Pragma: no-cache");
header("Expires: 0");

?>
<?php
# END COPAS ADD
?>
<div class="box">
    <div class="box-header">
        <h3 class="box-title">List Mutasi Rak </h3>
    </div>
    <!-- <div>
        Periode : <?php echo $from; ?> - <?php echo $to; ?>
    </div> -->
    <div class="box-body">
        <table border="1" class="table table-bordered table-striped" width = "100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Rak</th>
                    <th>Nama Barang</th>
                    <th>Penerimaan</th>
                    <th>Pengeluaran</th>
                    <th>Return</th>
                    <th>Unit</th>
                </tr>
            </thead>
            <tbody>
                <?php
                # QUERY TABLE
                // $fromcri = date('Y-m-d', strtotime($from));
                // $tocri = date('Y-m-d', strtotime($to));

                // if ($kd == 'ALL' and $kode_rak == 'ALL') {
                //     $where = "where c.tgl_dok >= '$fromcri' and c.tgl_dok <= '$tocri'";
                // } elseif ($kd != 'ALL' and $kode_rak == 'ALL') {
                //     $where = "where a.kd = '$kd' and c.tgl_dok >= '$fromcri' and c.tgl_dok <= '$tocri'";
                // } elseif ($kd == 'ALL' and $kode_rak != 'ALL') {
                //     $where = "where a.kode_rak = '$kode_rak' and c.tgl_dok >= '$fromcri' and c.tgl_dok <= '$tocri'";
                // } else {
                //     $where = "where a.kd = '$kd' and a.kode_rak = '$kode_rak' and c.tgl_dok >= '$fromcri' and c.tgl_dok <= '$tocri'";
                // }
                $sql = "select a.kode_rak, nama_barang, round(sum(penerimaan),2) penerimaan, round(sum(pengeluaran),2) pengeluaran, round(sum(retur),2) retur, b.unit  from 
(
select kode_rak,id_in_material, sum(penerimaan) penerimaan, sum(pengeluaran) pengeluaran, sum(retur) retur from (
select a.kode_rak,id_in_material,sum(roll_qty) penerimaan, '0' pengeluaran, '0' retur from in_material_det a
inner join in_material b on a.id_in_material = b.id
where a.cancel = 'N' and b.cancel = 'N'
group by id_in_material, kode_rak
union
select b.kode_rak,id_in_material,'0',sum(pengeluaran) pengeluaran , '0' retur from(
select id_barcode, sum(qty) pengeluaran,cancel from out_material
group by id_barcode) a 
inner join in_material_det b on a.id_barcode = b.id_barcode
where a.cancel = 'N' and b.cancel = 'N'
group by id_in_material, kode_rak
union
select b.kode_rak,id_in_material,'0' penerimaan,'0' pengeluaran,a.retur from(
select id_barcode, sum(qty) retur, cancel from retur_material
group by id_barcode) a 
inner join in_material_det b on a.id_barcode = b.id_barcode
where a.cancel = 'N' and b.cancel = 'N'
group by id_in_material, kode_rak
)tampil_mut 
group by kode_rak, id_in_material
) a 
inner join (select id, kode_barang, nama_barang, job_order, unit from in_material) b on a.id_in_material = b.id 
group by kode_rak,kode_barang, nama_barang, job_order, unit";

                #echo $sql;
                $query = mysql_query($sql);
                $no = 1;
                while ($data = mysql_fetch_array($query)) {
                    // $tgl_dok = date('d M Y', strtotime($data[tgl_dok]));
                    // $date_input = date('d M Y', strtotime($data[date_input]));
                    echo "<tr>";
                    echo "<td>$no</td>";
                    echo "<td>$data[kode_rak]</td>";
                    echo "<td>$data[nama_barang]</td>";
                    echo "<td>$data[penerimaan]</td>";
                    echo "<td>$data[pengeluaran]</td>";
                    echo "<td>$data[retur]</td>";
                    echo "<td>$data[unit]</td>";
                    echo "</tr>";
                    $no++; // menambah nilai nomor urut
                }
                // echo "<td>$data[tgl_input]</td>";
                ?>
            </tbody>
        </table>
    </div>
</div>