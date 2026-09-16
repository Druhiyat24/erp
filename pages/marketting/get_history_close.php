<?php
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();

if (empty($_SESSION['username'])) { 
    exit('Akses ditolak.'); 
}

$id_costing = isset($_GET['id']) ? $_GET['id'] : 0;

$sql = "SELECT * FROM close_order_history WHERE id_act_cost = '$id_costing' ORDER BY created_at DESC";
$query = mysql_query($sql);

if (!$query || mysql_num_rows($query) == 0) {
    echo "<div class='alert alert-info text-center mb-0'>Belum ada history close order untuk WS ini.</div>";
    exit();
}
?>

<table id="table_history" class="table table-bordered table-striped table-hover table-sm" style="font-size: 12px; margin-bottom: 0;">
    <thead class="bg-light">
        <tr>
            <th width="5%">No</th>
            <th>Tanggal</th>
            <th>WS</th>
            <th>User</th>
            <th width="15%" class="text-center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1;
        while($row = mysql_fetch_array($query)): 
            $status_close = (strtolower($row['action']) == 'close');
            $badge = $status_close ? '#dc3545' : '#28a745';
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= date('d-m-Y H:i:s', strtotime($row['created_at'])); ?></td>
            <td><?= htmlspecialchars($row['ws']); ?></td>
            <td><?= htmlspecialchars($row['username']); ?></td>
            <td class="text-center">
                <span style="background-color: <?= $badge; ?>; color: #fff; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: bold;">
                    <?= strtoupper($row['action']); ?>
                </span>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>