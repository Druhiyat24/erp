<?php
if (empty($_SESSION['username'])) {
    header("location:../../index.php");
}
if (isset($_SESSION['username'])) {
    $user = $_SESSION['username'];
} else {
    header("location:../../index.php");
}


$master_name = $_GET['master_name'];

if ($master_name == 'RAK') {
    $kata = "Rak";
} elseif ($master_name == 'UNIT') {
    $kata = "Unit";
} else {
    $kata = "";
}


header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Laporan Master $kata.xls"); //ganti nama sesuai keperluan 
header("Pragma: no-cache");
header("Expires: 0");

?>
<?php
# END COPAS ADD
if ($master_name == 'RAK') {
?>
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">List Master <?php echo $kata; ?></h3>
        </div>

        <div class="box-body">
            <table border="1" width="100%" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Rak</th>
                        <th>Nama Rak</th>
                        <th>Kapasitas</th>
                        <th>Unit</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th>Tanggal Input</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    # QUERY TABLE

                    
                    $sql = "select id,kode_rak,nama_rak,kapasitas,unit, IF(cancel != 'N','Non Aktif','Aktif') status,user_input, tgl_input from m_rak";

                    #echo $sql;
                    $query = mysql_query($sql);
                    $no = 1;
                    while ($data = mysql_fetch_array($query)) {
                        $date_input = date('d M Y', strtotime($data[tgl_input]));
                        echo "<tr>";
                        echo "<td>$no</td>";
                        echo "<td>$data[kode_rak]</td>";
                        echo "<td>$data[nama_rak]</td>";
                        echo "<td>$data[kapasitas]</td>";
                        echo "<td>$data[unit]</td>";
                        echo "<td>$data[status]</td>";
                        echo "<td>$data[user_input]</td>";
                        echo "<td style= 'text-align: left'>$date_input</td>";
                        echo "</tr>";
                        $no++; // menambah nilai nomor urut
                    }
                    // echo "<td>$data[tgl_input]</td>";
                    ?>
                </tbody>
            </table>
        </div>
    </div>
<?php
}else{
?>

    <div class="box">
        <div class="box-header">
            <h3 class="box-title">List Master <?php echo $kata; ?></h3>
        </div>

        <div class="box-body">
            <table border="1" width="100%" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Unit</th>
                        <th>Nama Unit</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th>Tanggal Input</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    # QUERY TABLE

                    
                    $sql = "select kode_unit,nama_unit,IF(cancel != 'N','Non Aktif','Aktif') status,user_input, tgl_input from m_unit";

                    #echo $sql;
                    $query = mysql_query($sql);
                    $no = 1;
                    while ($data = mysql_fetch_array($query)) {
                        $date_input = date('d M Y', strtotime($data[tgl_input]));
                        echo "<tr>";
                        echo "<td>$no</td>";
                        echo "<td>$data[kode_unit]</td>";
                        echo "<td>$data[nama_unit]</td>";
                        echo "<td>$data[status]</td>";
                        echo "<td>$data[user_input]</td>";
                        echo "<td style= 'text-align: left'>$date_input</td>";
                        echo "</tr>";
                        $no++; // menambah nilai nomor urut
                    }
                    // echo "<td>$data[tgl_input]</td>";
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}
?>