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
        echo "<option value='{$row['id']}'>{$row['id']} {$row['nama_contents']}</option>";
    }

    exit;
}

/* ================= WIDTH ================= */
if ($type == 'width') {

    $id_contents = $_POST['id_contents'];

    $query = mysqli_query($conn_li, "
        SELECT id, nama_width, id_contents
        FROM masterwidth 
        WHERE id_contents = '$id_contents' 
        AND aktif = 'Y'
        ORDER BY nama_width ASC
    ");

    echo '<option value="">Pilih Width</option>';

    while ($row = mysqli_fetch_assoc($query)) {
        echo "<option value='{$row['id']}'>{$row['id_contents']} {$row['nama_width']}</option>";
    }

    exit;
}

/* ================= LENGTH ================= */
if ($type == 'length') {

    $id_width = $_POST['id_width'];

    $query = mysqli_query($conn_li, "
        SELECT masterlength.id, masterlength.nama_length, masterwidth.id_contents
        FROM masterlength 
        LEFT JOIN masterwidth ON masterwidth.id = masterlength.id_width
        WHERE masterlength.id_width = '$id_width' 
        AND masterlength.aktif = 'Y'
        ORDER BY masterlength.nama_length ASC
    ");

    echo '<option value="">Pilih Length</option>';

    while ($row = mysqli_fetch_assoc($query)) {
        echo "<option value='{$row['id']}'>{$row['id_contents']} {$row['nama_length']}</option>";
    }

    exit;
}

/* ================= WEIGHT ================= */
if ($type == 'weight') {

    $id_length = $_POST['id_length'];

    $query = mysqli_query($conn_li, "
        SELECT masterweight.id, masterweight.nama_weight, masterwidth.id_contents
        FROM masterweight 
        LEFT JOIN masterlength ON masterlength.id = masterweight.id_length
        LEFT JOIN masterwidth ON masterwidth.id = masterlength.id_width
        WHERE masterweight.id_length = '$id_length' 
        AND masterweight.aktif = 'Y'
        ORDER BY masterweight.nama_weight ASC
    ");

    echo '<option value="">Pilih Weight</option>';

    while ($row = mysqli_fetch_assoc($query)) {
        echo "<option value='{$row['id']}'>{$row['id_contents']} {$row['nama_weight']}</option>";
    }

    exit;
}

/* ================= COLOR ================= */
if ($type == 'color') {

    $id_weight = $_POST['id_weight'];

    $query = mysqli_query($conn_li, "
        SELECT mastercolor.id, mastercolor.nama_color, masterwidth.id_contents
        FROM mastercolor 
        LEFT JOIN masterweight ON masterweight.id = mastercolor.id_weight
        LEFT JOIN masterlength ON masterlength.id = masterweight.id_length
        LEFT JOIN masterwidth ON masterwidth.id = masterlength.id_width
        WHERE mastercolor.id_weight = '$id_weight' 
        AND mastercolor.aktif = 'Y'
        ORDER BY mastercolor.nama_color ASC
    ");

    echo '<option value="">Pilih Color</option>';

    while ($row = mysqli_fetch_assoc($query)) {
        echo "<option value='{$row['id']}'>{$row['id_contents']} {$row['nama_color']}</option>";
    }

    exit;
}

/* ================= DESCRIPTION ================= */
if ($type == 'description') {

    $id_color = $_POST['id_color'];

    $query = mysqli_query($conn_li, "
        SELECT masterdesc.id, masterdesc.nama_desc, masterwidth.id_contents 
        FROM masterdesc 
        LEFT JOIN mastercolor ON mastercolor.id = masterdesc.id_color
        LEFT JOIN masterweight ON masterweight.id = mastercolor.id_weight
        LEFT JOIN masterlength ON masterlength.id = masterweight.id_length
        LEFT JOIN masterwidth ON masterwidth.id = masterlength.id_width
        WHERE masterdesc.id_color = '$id_color' 
        AND masterdesc.aktif = 'Y'
        ORDER BY masterdesc.nama_desc ASC
    ");

    echo '<option value="">Pilih Description</option>';

    while ($row = mysqli_fetch_assoc($query)) {
        echo "<option value='{$row['id']}'>{$row['id_contents']} {$row['nama_desc']}</option>";
    }

    exit;
}