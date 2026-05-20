<?php
include "../../include/conn.php";
session_start();

date_default_timezone_set('Asia/Jakarta');

header('Content-Type: application/json');

$no_trans  = isset($_POST['no_trans']) ? $_POST['no_trans'] : '';
$cancelIds = isset($_POST['cancel_ids']) ? $_POST['cancel_ids'] : array();
$user      = isset($_SESSION['username']) ? $_SESSION['username'] : 'system';
$now       = date("Y-m-d H:i:s");

$status_cancel = (strpos($no_trans, 'TFTE') !== false) ? 'A-TETF' : 'A-TETM';

if(empty($no_trans)){
    echo json_encode(array(
        'status' => 'error',
        'message' => 'No Trans kosong'
    ));
    exit;
}

// pastikan array
if(!is_array($cancelIds)){
    $cancelIds = array($cancelIds);
}

// sanitize ID
$ids = array();
foreach ($cancelIds as $id) {
    $ids[] = intval($id);
}

$idList = implode(',', $ids);

// mulai transaksi
mysqli_autocommit($conn_li, FALSE);

try {

    if(!empty($ids)){

        // ================= ambil nm_memo =================
        $getMemo = mysqli_query($conn_li, "
            SELECT nm_memo 
            FROM memo_h 
            WHERE id_h IN ($idList)
        ");

        if(!$getMemo){
            throw new Exception(mysqli_error($conn_li));
        }

        $memoList = array();

        while($row = mysqli_fetch_assoc($getMemo)){
            $memoList[] = "'" . mysqli_real_escape_string($conn_li, $row['nm_memo']) . "'";
        }

        if(!empty($memoList)){

            $memoIn = implode(',', $memoList);

            // ================= update memo_h =================
            $updateMemo = "
                UPDATE memo_h 
                SET 
                    status_transfer = '$status_cancel',
                    tetm_by = NULL,
                    tetm_date = NULL
                WHERE id_h IN ($idList)
            ";

            if(!mysqli_query($conn_li, $updateMemo)){
                throw new Exception(mysqli_error($conn_li));
            }

            // ================= update detail =================
            $updateDet = "
                UPDATE transfer_memo_exim_det
                SET 
                    status = 'N',
                    updated_at = '$now'
                WHERE no_trans = '$no_trans'
                AND nm_memo IN ($memoIn)
            ";

            if(!mysqli_query($conn_li, $updateDet)){
                throw new Exception(mysqli_error($conn_li));
            }
        }
    }

    // ================= cek masih ada yg aktif =================
    $cek = mysqli_query($conn_li, "
        SELECT COUNT(*) as total 
        FROM transfer_memo_exim_det
        WHERE no_trans = '$no_trans'
        AND status = 'Y'
    ");

    if(!$cek){
        throw new Exception(mysqli_error($conn_li));
    }

    $rowCek = mysqli_fetch_assoc($cek);

    if($rowCek['total'] == 0){

        // ================= update header =================
        $updateHeader = "
            UPDATE transfer_memo_exim_h
            SET 
                status = 'CANCEL',
                cancel_by = '$user',
                cancel_date = '$now',
                updated_at = '$now'
            WHERE no_trans = '$no_trans'
        ";

        if(!mysqli_query($conn_li, $updateHeader)){
            throw new Exception(mysqli_error($conn_li));
        }
    }

    mysqli_commit($conn_li);

    echo json_encode(array(
        'status' => 'success',
        'message' => 'Semua data berhasil di-cancel'
    ));

} catch (Exception $e) {

    mysqli_rollback($conn_li);

    echo json_encode(array(
        'status' => 'error',
        'message' => $e->getMessage()
    ));
}

mysqli_close($conn_li);
?>
