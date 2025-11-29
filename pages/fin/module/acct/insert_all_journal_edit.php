<?php
include '../../conn/conn.php';
ini_set('date.timezone', 'Asia/Jakarta');

// --- HEADER ---
$no_mj      = $_POST['no_mj'];
$mj_date    = date("Y-m-d", strtotime($_POST['mj_date']));
$id_cmj     = $_POST['id_cmj'];
$keterangan = $_POST['keterangan'];
$create_user = $_POST['create_user'];
$create_date = date("Y-m-d H:i:s");
$status = "Post";

// --- ARRAY (DETAIL) ---
$no_coa        = $_POST['no_coa'];
$no_costcenter = $_POST['no_costcenter'];
$no_reff       = $_POST['no_reff'];
$reff_date     = $_POST['reff_date'];
$buyer         = $_POST['buyer'];
$no_ws         = $_POST['no_ws'];
$curr          = $_POST['curr'];
$rate          = $_POST['rate'];
$debit         = $_POST['debit'];
$credit        = $_POST['credit'];


// ================================
// LOOP SETIAP BARIS
// ================================


    // Lewati baris kosong
    if ($no_coa == '' || $no_coa == '-') {
        continue;
    }

    // Format tanggal
    $reff_date_fix = ($reff_date == "" ? "0000-00-00" : date("Y-m-d", strtotime($reff_date)));

    // Rate / debit / credit
    $r = ($rate == "" ? 1 : $rate);
    $d = ($debit == "" ? 0 : $debit);
    $c = ($credit == "" ? 0 : $credit);

    $debit_idr  = $d * $r;
    $credit_idr = $c * $r;

    // --- ambil nama COA & CC ---
    $qcoa = mysqli_query($conn1, "SELECT nama_coa FROM mastercoa_v2 WHERE no_coa='".$no_coa."' ");
    $rcoa = mysqli_fetch_array($qcoa);
    $nama_coa = $rcoa['nama_coa'];

    $qcc = mysqli_query($conn1, "SELECT cc_name FROM b_master_cc WHERE no_cc='".$no_costcenter."' ");
    $rcc = mysqli_fetch_array($qcc);
    $nama_cc = $rcc['cc_name'];

    $sqlcmj = mysqli_query($conn1,"select id_cmj from master_category_mj where nama_cmj = '$id_cmj'");
	$rowcmj = mysqli_fetch_array($sqlcmj);
	$idcmj = $rowcmj['id_cmj'];


    // =========================================
    // INSERT ke sb_memorial_journal
    // =========================================
    if (strpos($no_mj, 'GM/NAG') === 0) {

        $sqlH = "
        INSERT INTO sb_memorial_journal 
        (no_mj, mj_date, id_cmj, no_coa, no_costcenter, no_reff, reff_date, buyer, no_ws, curr, rate, debit, credit, debit_idr, credit_idr, keterangan, status, create_by, create_date)
        VALUES
        ('$no_mj', '$mj_date', '$idcmj', '".$no_coa."', '".$no_costcenter."', '".$no_reff."', '$reff_date_fix',
         '".$buyer."', '".$no_ws."', '".$curr."', '$r', '$d', '$c', '$debit_idr', '$credit_idr',
         '$keterangan', '$status', '$create_user', '$create_date')";

        mysqli_query($conn2, $sqlH);
    }


    // =========================================
    // INSERT ke sb_list_journal
    // =========================================
    $sqlD = "
    INSERT INTO sb_list_journal
    (no_journal, tgl_journal, type_journal, no_coa, nama_coa, no_costcenter, nama_costcenter, 
     reff_doc, reff_date, buyer, no_ws, curr, rate, debit, credit, debit_idr, credit_idr,
     status, keterangan, create_by, create_date, approve_by, approve_date, cancel_by, cancel_date)
    VALUES
    ('$no_mj', '$mj_date', '$id_cmj', '".$no_coa."', '$nama_coa',
     '".$no_costcenter."', '$nama_cc', '".$no_reff."', '$reff_date_fix',
     '".$buyer."', '".$no_ws."', '".$curr."', '$r', '$d', '$c',
     '$debit_idr', '$credit_idr', '$status', '$keterangan',
     '$create_user', '$create_date', '', '', '', '')";

    mysqli_query($conn2, $sqlD);

mysqli_close($conn2);

echo "SUCCESS|".$no_mj;
?>
