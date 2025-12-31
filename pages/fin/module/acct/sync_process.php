<?php
include '../../conn/conn.php';
date_default_timezone_set('Asia/Jakarta');

header('Content-Type: application/json');

function syncData($conn1, $start_date, $end_date)
{
    $start = date("Y-m-d", strtotime($start_date));
    $end   = date("Y-m-d", strtotime($end_date));

    // ==== BACKUP ====
    $sqlBackup = "
        INSERT INTO sb_list_journal_cancel
        SELECT *
        FROM sb_list_journal 
        WHERE tgl_journal BETWEEN '$start' AND '$end'
          AND no_journal NOT LIKE '%GM/NAG%'
    ";

    if (!mysqli_query($conn1, $sqlBackup)) {
        throw new Exception(mysqli_error($conn1));
    }

    // ==== DELETE ====
    $sqlDel = "
        DELETE FROM sb_list_journal 
        WHERE tgl_journal BETWEEN '$start' AND '$end'
          AND no_journal NOT LIKE '%GM/NAG%'
    ";

    if (!mysqli_query($conn1, $sqlDel)) {
        throw new Exception(mysqli_error($conn1));
    }

    // ==== CALL SP ====
    if (!mysqli_query($conn1, "CALL copy_data_jurnal()")) {
        throw new Exception(mysqli_error($conn1));
    }

    // Jika SP menghasilkan resultset, habiskan hasilnya (menghindari error “commands out of sync”)
    while (mysqli_more_results($conn1) && mysqli_next_result($conn1)) {;}

    // ==== HITUNG DATA ====
    $cek = mysqli_query($conn1,"
        SELECT COUNT(*) jml 
        FROM sb_list_journal 
        WHERE tgl_journal BETWEEN '$start' AND '$end'
    ");

    $row = mysqli_fetch_assoc($cek);

    return $row['jml'];
}


// ========================
// HANDLE AJAX
// ========================
try {

    if(!empty($_POST['start_date']) && !empty($_POST['end_date'])) {

        $total = syncData($conn1, $_POST['start_date'], $_POST['end_date']);

        echo json_encode([
            "status"  => true,
            "message" => "Sync selesai. Total data setelah sync: ".$total
        ]);
    }
    else{
        echo json_encode([
            "status"  => false,
            "message" => "Tanggal belum diisi"
        ]);
    }

} catch (Exception $e) {

    echo json_encode([
        "status"  => false,
        "message" => "Gagal sync: ".$e->getMessage()
    ]);

}
