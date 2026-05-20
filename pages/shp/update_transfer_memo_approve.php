<?php
include "../../include/conn.php";
session_start();

header('Content-Type: application/json');

$no_trans   = isset($_POST['no_trans']) ? $_POST['no_trans'] : '';
$approveIds = isset($_POST['approve_ids']) ? $_POST['approve_ids'] : array();
$cancelIds  = isset($_POST['cancel_ids']) ? $_POST['cancel_ids'] : array();

$user = isset($_SESSION['username']) ? $_SESSION['username'] : 'SYSTEM';
$now  = date("Y-m-d H:i:s");

$status_approve = (strpos($no_trans, 'TFTE') !== false) ? 'A-TFTE' : 'A-TMTE';

mysqli_autocommit($conn_li, FALSE);

try {

    // ================= APPROVE =================
    if (!empty($approveIds)) {

        $ids = array();
        foreach ($approveIds as $id) {
            $ids[] = intval($id);
        }

        $ids_str = implode(",", $ids);

        $queryApprove = "
            UPDATE memo_h
            SET
                status_transfer = '$status_approve',
                app_tmte_by = '$user',
                app_tmte_date = '$now'
            WHERE id_h IN ($ids_str)
        ";

        if (!mysqli_query($conn_li, $queryApprove)) {
            throw new Exception(mysqli_error($conn_li));
        }
    }

    // ================= CANCEL =================
    if (!empty($cancelIds)) {

        $idsCancel = array();
        foreach ($cancelIds as $id) {
            $idsCancel[] = intval($id);
        }

        $idsCancel_str = implode(",", $idsCancel);

        // ambil nm_memo
        $getMemo = "
            SELECT nm_memo 
            FROM memo_h 
            WHERE id_h IN ($idsCancel_str)
        ";

        $resMemo = mysqli_query($conn_li, $getMemo);

        if (!$resMemo) {
            throw new Exception(mysqli_error($conn_li));
        }

        $cancelMemo = array();

        while ($row = mysqli_fetch_assoc($resMemo)) {
            $cancelMemo[] = "'" . mysqli_real_escape_string($conn_li, $row['nm_memo']) . "'";
        }

        // update memo_h
        $queryCancelMemo = "
            UPDATE memo_h
            SET 
                status_transfer = 'A-TETM',
                tmte_by = NULL,
                tmte_date = NULL
            WHERE id_h IN ($idsCancel_str)
        ";

        if (!mysqli_query($conn_li, $queryCancelMemo)) {
            throw new Exception(mysqli_error($conn_li));
        }

        // update detail
        if (!empty($cancelMemo)) {

            $memoList = implode(",", $cancelMemo);

            $queryDetail = "
                UPDATE transfer_memo_exim_det
                SET 
                    status = 'N',
                    updated_at = '$now'
                WHERE no_trans = '$no_trans'
                AND nm_memo IN ($memoList)
            ";

            if (!mysqli_query($conn_li, $queryDetail)) {
                throw new Exception(mysqli_error($conn_li));
            }
        }
    }

    // ================= HEADER =================
    $queryHeader = "
        UPDATE transfer_memo_exim_h
        SET 
            status = 'APPROVED',
            approved_by = '$user',
            approved_date = '$now',
            updated_at = '$now'
        WHERE no_trans = '$no_trans'
    ";

    if (!mysqli_query($conn_li, $queryHeader)) {
        throw new Exception(mysqli_error($conn_li));
    }

    mysqli_commit($conn_li);

    echo json_encode(array(
        'status' => 'success',
        'message' => 'Data berhasil diproses'
    ));

} catch (Exception $e) {

    mysqli_rollback($conn_li);

    echo json_encode(array(
        'status' => 'error',
        'message' => $e->getMessage()
    ));
}
?>
