<?php
include __DIR__ . '/../../../include/conn.php';

$type = isset($_POST['type']) ? $_POST['type'] : '';

/* ================= SUB GROUP ================= */
if ($type == 'subgroup') {

    $id_group = $_POST['id_group'];

    $query = mysqli_query($conn_li, "
        SELECT id, nama_sub_group 
        FROM mastersubgroup 
        WHERE id_group = '$id_group' 
        AND aktif = 'Y'
        ORDER BY nama_sub_group ASC
    ");

    echo '<option value="">Pilih Sub Group</option>';

    while ($row = mysqli_fetch_assoc($query)) {
        echo "<option value='{$row['id']}'>{$row['nama_sub_group']}</option>";
    }

    exit;
}

/* ================= TYPE ================= */
if ($type == 'type') {

    $id_sub_group = $_POST['id_sub_group'];

    $query = mysqli_query($conn_li, "
        SELECT id, nama_type 
        FROM mastertype2 
        WHERE id_sub_group = '$id_sub_group' 
        AND aktif = 'Y'
        ORDER BY nama_type ASC
    ");

    echo '<option value="">Pilih Type</option>';

    while ($row = mysqli_fetch_assoc($query)) {
        echo "<option value='{$row['id']}'>{$row['nama_type']}</option>";
    }

    exit;
}

/* ================= CONTENTS ================= */
if ($type == 'contents') {

    $id_type = $_POST['id_type'];

    $query = mysqli_query($conn_li, "
        SELECT id, nama_contents 
        FROM mastercontents 
        WHERE id_type = '$id_type' 
        AND aktif = 'Y'
        ORDER BY nama_contents ASC
    ");

    echo '<option value="">Pilih Contents</option>';

    while ($row = mysqli_fetch_assoc($query)) {
        echo "<option value='{$row['id']}'>{$row['nama_contents']}</option>";
    }

    exit;
}

/* ================= WIDTH ================= */
if ($type == 'width') {

    $id_contents = $_POST['id_contents'];

    $query = mysqli_query($conn_li, "
        SELECT id, nama_width 
        FROM masterwidth 
        WHERE id_contents = '$id_contents' 
        AND aktif = 'Y'
        ORDER BY nama_width ASC
    ");

    echo '<option value="">Pilih Width</option>';

    while ($row = mysqli_fetch_assoc($query)) {
        echo "<option value='{$row['id']}'>{$row['nama_width']}</option>";
    }

    exit;
}

/* ================= LENGTH ================= */
if ($type == 'length') {

    $id_width = $_POST['id_width'];

    $query = mysqli_query($conn_li, "
        SELECT id, nama_length 
        FROM masterlength 
        WHERE id_width = '$id_width' 
        AND aktif = 'Y'
        ORDER BY nama_length ASC
    ");

    echo '<option value="">Pilih Length</option>';

    while ($row = mysqli_fetch_assoc($query)) {
        echo "<option value='{$row['id']}'>{$row['nama_length']}</option>";
    }

    exit;
}

/* ================= WEIGHT ================= */
if ($type == 'weight') {

    $id_length = $_POST['id_length'];

    $query = mysqli_query($conn_li, "
        SELECT id, nama_weight 
        FROM masterweight 
        WHERE id_length = '$id_length' 
        AND aktif = 'Y'
        ORDER BY nama_weight ASC
    ");

    echo '<option value="">Pilih Weight</option>';

    while ($row = mysqli_fetch_assoc($query)) {
        echo "<option value='{$row['id']}'>{$row['nama_weight']}</option>";
    }

    exit;
}

/* ================= COLOR ================= */
if ($type == 'color') {

    $id_weight = $_POST['id_weight'];

    $query = mysqli_query($conn_li, "
        SELECT id, nama_color 
        FROM mastercolor 
        WHERE id_weight = '$id_weight' 
        AND aktif = 'Y'
        ORDER BY nama_color ASC
    ");

    echo '<option value="">Pilih Color</option>';

    while ($row = mysqli_fetch_assoc($query)) {
        echo "<option value='{$row['id']}'>{$row['nama_color']}</option>";
    }

    exit;
}

/* ================= DESCRIPTION ================= */
if ($type == 'description') {

    $id_color = $_POST['id_color'];

    $query = mysqli_query($conn_li, "
        SELECT id, nama_desc 
        FROM masterdesc 
        WHERE id_color = '$id_color' 
        AND aktif = 'Y'
        ORDER BY nama_desc ASC
    ");

    echo '<option value="">Pilih Description</option>';

    while ($row = mysqli_fetch_assoc($query)) {
        echo "<option value='{$row['id']}'>{$row['nama_desc']}</option>";
    }

    exit;
}