<?php
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();

if (empty($_SESSION['username'])) {
    echo json_encode(["status" => "error", "message" => "Session expired"]);
    exit;
}

$conn = $con_new;
$user = $_SESSION['username'];

mysqli_begin_transaction($conn);

try {

    // =========================
    // CHECKBOX
    // =========================
    $chk_group     = isset($_POST['chk_group']);
    $chk_sub       = isset($_POST['chk_sub_group']);
    $chk_type      = isset($_POST['chk_type']);
    $chk_contents  = isset($_POST['chk_contents']);
    $chk_width     = isset($_POST['chk_width']);
    $chk_length    = isset($_POST['chk_length']);
    $chk_weight    = isset($_POST['chk_weight']);
    $chk_color     = isset($_POST['chk_color']);
    $chk_desc      = isset($_POST['chk_description']);

    // =========================
    // CASCADE
    // =========================
    if ($chk_group) {
        $chk_sub = $chk_type = $chk_contents = $chk_width =
        $chk_length = $chk_weight = $chk_color = $chk_desc = true;
    } elseif ($chk_sub) {
        $chk_type = $chk_contents = $chk_width =
        $chk_length = $chk_weight = $chk_color = $chk_desc = true;
    } elseif ($chk_type) {
        $chk_contents = $chk_width =
        $chk_length = $chk_weight = $chk_color = $chk_desc = true;
    } elseif ($chk_contents) {
        $chk_width = $chk_length =
        $chk_weight = $chk_color = $chk_desc = true;
    } elseif ($chk_width) {
        $chk_length = $chk_weight = $chk_color = $chk_desc = true;
    } elseif ($chk_length) {
        $chk_weight = $chk_color = $chk_desc = true;
    } elseif ($chk_weight) {
        $chk_color = $chk_desc = true;
    } elseif ($chk_color) {
        $chk_desc = true;
    }

    // =========================
    // VALIDASI (STOP DI SINI)
    // =========================
    $valid = true;
    $msg = "";

    if ($chk_group) {
        if (trim($_POST['txt_group_kode']) == '') {
            $valid = false;
            $msg = "Kode Group wajib diisi";
        }
    } else {
        if (trim($_POST['cbo_group']) == '') {
            $valid = false;
            $msg = "Group wajib dipilih";
        }
    }

    if ($valid) {
        if ($chk_sub) {
            if (trim($_POST['txt_subgroup_kode']) == '') {
                $valid = false;
                $msg = "Kode Sub Group wajib diisi";
            }
        } else {
            if (trim($_POST['cbo_sub_group']) == '') {
                $valid = false;
                $msg = "Sub Group wajib dipilih";
            }
        }
    }

    if ($valid) {
        if ($chk_type) {
            if (trim($_POST['txt_type_kode']) == '') {
                $valid = false;
                $msg = "Kode Type wajib diisi";
            }
        } else {
            if (trim($_POST['cbo_type']) == '') {
                $valid = false;
                $msg = "Type wajib dipilih";
            }
        }
    }

    if ($valid) {
        if ($chk_contents) {
            if (trim($_POST['txt_contents_kode']) == '') {
                $valid = false;
                $msg = "Kode Contents wajib diisi";
            }
        } else {
            if (trim($_POST['cbo_contents']) == '') {
                $valid = false;
                $msg = "Contents wajib dipilih";
            }
        }
    }

    if ($valid) {
        if ($chk_width) {
            if (trim($_POST['txt_width_kode']) == '') {
                $valid = false;
                $msg = "Kode Width wajib diisi";
            }
        } else {
            if (trim($_POST['cbo_width']) == '') {
                $valid = false;
                $msg = "Width wajib dipilih";
            }
        }
    }

    if ($valid) {
        if ($chk_length) {
            if (trim($_POST['txt_length_kode']) == '') {
                $valid = false;
                $msg = "Kode Length wajib diisi";
            }
        } else {
            if (trim($_POST['cbo_length']) == '') {
                $valid = false;
                $msg = "Length wajib dipilih";
            }
        }
    }

    if ($valid) {
        if ($chk_weight) {
            if (trim($_POST['txt_weight_kode']) == '') {
                $valid = false;
                $msg = "Kode Weight wajib diisi";
            }
        } else {
            if (trim($_POST['cbo_weight']) == '') {
                $valid = false;
                $msg = "Weight wajib dipilih";
            }
        }
    }

    if ($valid) {
        if ($chk_color) {
            if (trim($_POST['txt_color_kode']) == '') {
                $valid = false;
                $msg = "Kode Color wajib diisi";
            }
        } else {
            if (trim($_POST['cbo_color']) == '') {
                $valid = false;
                $msg = "Color wajib dipilih";
            }
        }
    }

    if ($valid) {
        if ($chk_desc) {
            if (trim($_POST['txt_description_kode']) == '') {
                $valid = false;
                $msg = "Kode Description wajib diisi";
            }
        } else {
            if (trim($_POST['cbo_description']) == '') {
                $valid = false;
                $msg = "Description wajib dipilih";
            }
        }
    }

    if ($valid) {

        function cleanKode($val) {
            $val = trim($val);
            return ($val == '-' || $val == '') ? '' : $val;
        }

        # ================= GROUP =================
        $group_id = $chk_group ? null : $_POST['cbo_group'];

        if (!$chk_group) {
            $res = mysqli_query($conn, "SELECT kode_group FROM mastergroup WHERE id='$group_id'");
            $row = mysqli_fetch_assoc($res);
            $group_code = cleanKode($row['kode_group']);
        } else {
            $group_code = cleanKode($_POST['txt_group_kode']);
        }

        # ================= SUB GROUP =================
        $sub_id = $chk_sub ? null : $_POST['cbo_sub_group'];

        if (!$chk_sub) {
            $res = mysqli_query($conn, "SELECT kode_sub_group FROM mastersubgroup WHERE id='$sub_id'");
            $row = mysqli_fetch_assoc($res);
            $sub_code = cleanKode($row['kode_sub_group']);
        } else {
            $sub_code = cleanKode($_POST['txt_subgroup_kode']);
        }

        # ================= TYPE =================
        $type_id = $chk_type ? null : $_POST['cbo_type'];

        if (!$chk_type) {
            $res = mysqli_query($conn, "SELECT kode_type FROM mastertype2 WHERE id='$type_id'");
            $row = mysqli_fetch_assoc($res);
            $type_code = cleanKode($row['kode_type']);
        } else {
            $type_code = cleanKode($_POST['txt_type_kode']);
        }

        # ================= CONTENTS =================
        $contents_id = $chk_contents ? null : $_POST['cbo_contents'];

        if (!$chk_contents) {
            $res = mysqli_query($conn, "SELECT kode_contents FROM mastercontents WHERE id='$contents_id'");
            $row = mysqli_fetch_assoc($res);
            $contents_code = cleanKode($row['kode_contents']);
        } else {
            $contents_code = cleanKode($_POST['txt_contents_kode']);
        }

        # ================= WIDTH =================
        $width_id = $chk_width ? null : $_POST['cbo_width'];

        if (!$chk_width) {
            $res = mysqli_query($conn, "SELECT kode_width FROM masterwidth WHERE id='$width_id'");
            $row = mysqli_fetch_assoc($res);
            $width_code = cleanKode($row['kode_width']);
        } else {
            $width_code = cleanKode($_POST['txt_width_kode']);
        }

        # ================= LENGTH =================
        $length_id = $chk_length ? null : $_POST['cbo_length'];

        if (!$chk_length) {
            $res = mysqli_query($conn, "SELECT kode_length FROM masterlength WHERE id='$length_id'");
            $row = mysqli_fetch_assoc($res);
            $length_code = cleanKode($row['kode_length']);
        } else {
            $length_code = cleanKode($_POST['txt_length_kode']);
        }

        # ================= WEIGHT =================
        $weight_id = $chk_weight ? null : $_POST['cbo_weight'];

        if (!$chk_weight) {
            $res = mysqli_query($conn, "SELECT kode_weight FROM masterweight WHERE id='$weight_id'");
            $row = mysqli_fetch_assoc($res);
            $weight_code = cleanKode($row['kode_weight']);
        } else {
            $weight_code = cleanKode($_POST['txt_weight_kode']);
        }

        # ================= COLOR =================
        $color_id = $chk_color ? null : $_POST['cbo_color'];

        if (!$chk_color) {
            $res = mysqli_query($conn, "SELECT kode_color FROM mastercolor WHERE id='$color_id'");
            $row = mysqli_fetch_assoc($res);
            $color_code = cleanKode($row['kode_color']);
        } else {
            $color_code = cleanKode($_POST['txt_color_kode']);
        }

        # ================= DESCRIPTION =================
        $desc_id = $chk_desc ? null : $_POST['cbo_description'];

        if (!$chk_desc) {
            $res = mysqli_query($conn, "SELECT kode_desc FROM masterdesc WHERE id='$desc_id'");
            $row = mysqli_fetch_assoc($res);
            $desc_code = cleanKode($row['kode_desc']);
        } else {
            $desc_code = cleanKode($_POST['txt_description_kode']);
        }

        # ================= GOODS CODE FINAL =================
        $goods_code =
            $group_code .
            $sub_code .
            $type_code .
            $contents_code .
            $width_code .
            $length_code .
            $weight_code .
            $color_code .
            $desc_code;

        $goods_code = trim($goods_code);
        $goods_code_esc = mysqli_real_escape_string($conn, $goods_code);

        $cek = mysqli_query($conn, "
            SELECT 1
            FROM masteritem
            WHERE goods_code = '$goods_code_esc'
            LIMIT 1
        ");

        if (!$cek) {
            throw new Exception("Gagal cek goods_code");
        }

        if (mysqli_num_rows($cek) > 0) {
            $valid = false;
            $msg = "Item sudah pernah dibuat dengan spesifikasi yang sama";
        }
    }

    if (!$valid) {
        echo json_encode([
            "status" => "error",
            "message" => $msg
        ]);
        exit;
    }

    // ==========================================================
    //  INSERT
    // ==========================================================

    // ================= GROUP =================
    if ($chk_group) {

        $kode = $_POST['txt_group_kode'];
        $desc = $_POST['txt_group_desc'];
        
        $sql = "INSERT INTO mastergroup (kode_group, nama_group)
                VALUES ('$kode','$desc')";
        mysqli_query($conn, $sql);
        
        $id_group = mysqli_insert_id($conn);
        
    } else {
        $id_group = $_POST['cbo_group'];
    }

    // ================= SUB GROUP =================
    if ($chk_sub) {

        $kode = $_POST['txt_subgroup_kode'];
        $desc = $_POST['txt_subgroup_desc'];
        $idcoad = $_POST['txt_subgroup_id_coa_debet'];
        $idcoac = $_POST['txt_subgroup_id_coa_credit'];

        $sql = "INSERT INTO mastersubgroup (id_group, kode_sub_group, nama_sub_group, id_coa_d, id_coa_k)
                VALUES ('$id_group','$kode','$desc','$idcoad','$idcoac')";
        mysqli_query($conn, $sql);

        $id_sub_group = mysqli_insert_id($conn);

    } else {
        $id_sub_group = $_POST['cbo_sub_group'];
    }

    // ================= TYPE =================
    if ($chk_type) {

        $kode = $_POST['txt_type_kode'];
        $desc = $_POST['txt_type_desc'];

        $sql = "INSERT INTO mastertype2 (id_sub_group, kode_type, nama_type)
                VALUES ('$id_sub_group','$kode','$desc')";
        mysqli_query($conn, $sql);

        $id_type = mysqli_insert_id($conn);

    } else {
        $id_type = $_POST['cbo_type'];
    }
    
    // ================= CONTENTS =================
    if ($chk_contents) {

        $kode = $_POST['txt_contents_kode'];
        $desc = $_POST['txt_contents_desc'];

        $sql = "INSERT INTO mastercontents (id_type, kode_contents, nama_contents)
                VALUES ('$id_type','$kode','$desc')";
        mysqli_query($conn, $sql);

        $id_contents = mysqli_insert_id($conn);

    } else {
        $id_contents = $_POST['cbo_contents'];
    }

    // ================= WIDTH =================
    if ($chk_width) {

        $kode = $_POST['txt_width_kode'];
        $desc = $_POST['txt_width_desc'];

        $sql = "INSERT INTO masterwidth (id_contents, kode_width, nama_width)
                VALUES ('$id_contents','$kode','$desc')";
        mysqli_query($conn, $sql);

        $id_width = mysqli_insert_id($conn);

    } else {
        $id_width = $_POST['cbo_width'];
    }

    // ================= LENGTH =================
    if ($chk_length) {

        $kode = $_POST['txt_length_kode'];
        $desc = $_POST['txt_length_desc'];

        $sql = "INSERT INTO masterlength (id_width, kode_length, nama_length)
                VALUES ('$id_width','$kode','$desc')";
        mysqli_query($conn, $sql);

        $id_length = mysqli_insert_id($conn);

    } else {
        $id_length = $_POST['cbo_length'];
    }

    // ================= WEIGHT =================
    if ($chk_weight) {

        $kode = $_POST['txt_weight_kode'];
        $desc = $_POST['txt_weight_desc'];

        $sql = "INSERT INTO masterweight (id_length, kode_weight, nama_weight)
                VALUES ('$id_length','$kode','$desc')";
        mysqli_query($conn, $sql);

        $id_weight = mysqli_insert_id($conn);

    } else {
        $id_weight = $_POST['cbo_weight'];
    }

    // ================= COLOR =================
    if ($chk_color) {

        $kode = $_POST['txt_color_kode'];
        $desc = $_POST['txt_color_desc'];
        $pantone = $_POST['txt_color_pantone'];
        $image = $_FILES['txt_color_image']['name'];
        $tmp   = $_FILES['txt_color_image']['tmp_name'];

        $path = "upload_files/color/" . $image;
        move_uploaded_file($tmp, $path);

        $sql = "INSERT INTO mastercolor (id_weight, kode_color, nama_color, phantom, nm_file)
                VALUES ('$id_weight','$kode','$desc','$pantone','$image')";
        mysqli_query($conn, $sql);

        $id_color = mysqli_insert_id($conn);

    } else {
        $id_color = $_POST['cbo_color'];
    }

    // ================= DESCRIPTION =================
    if ($chk_desc) {

        $kode = $_POST['txt_description_kode'];
        $desc = $_POST['txt_description_desc'];

        $sql = "INSERT INTO masterdesc (id_color, kode_desc, nama_desc)
                VALUES ('$id_color','$kode','$desc')";
        mysqli_query($conn, $sql);

        $id_desc = mysqli_insert_id($conn);

        $sql_item = "
        INSERT INTO masteritem 
        (mattype,id_gen,matclass,size,color,goods_code,itemdesc)
        SELECT 
            CASE 
                WHEN a.nama_group = 'Fabric' THEN 'F'
                WHEN a.nama_group = 'Sample' THEN 'SMP'
                ELSE 'A'
            END AS mattype,
            j.id,
            a.nama_group,
            f.nama_width,
            i.nama_color,
            CONCAT(
                IF(a.kode_group='-','',a.kode_group),
                IF(s.kode_sub_group='-','',s.kode_sub_group),
                IF(d.kode_type='-','',d.kode_type),
                IF(e.kode_contents='-','',e.kode_contents),
                IF(f.kode_width='-','',f.kode_width),
                IF(g.kode_length='-','',g.kode_length),
                IF(h.kode_weight='-','',h.kode_weight),
                IF(i.kode_color='-','',i.kode_color),
                IF(j.kode_desc='-','',j.kode_desc)
            ) AS gen_kode,
            CONCAT(
                a.nama_group,
                IF(s.nama_sub_group='-','',CONCAT(' ',s.nama_sub_group)),
                IF(d.nama_type='-','',CONCAT(' ',d.nama_type)),
                IF(e.nama_contents='-','',CONCAT(' ',e.nama_contents)),
                IF(f.nama_width='-','',CONCAT(' ',f.nama_width)),
                IF(g.nama_length='-','',CONCAT(' ',g.nama_length)),
                IF(h.nama_weight='-','',CONCAT(' ',h.nama_weight)),
                IF(i.nama_color='-','',CONCAT(' ',i.nama_color)),
                IF(j.nama_desc='-','',CONCAT(' ',j.nama_desc))
            ) AS itemdesc
        FROM mastergroup a
        INNER JOIN mastersubgroup s ON a.id=s.id_group
        INNER JOIN mastertype2 d ON s.id=d.id_sub_group
        INNER JOIN mastercontents e ON d.id=e.id_type
        INNER JOIN masterwidth f ON e.id=f.id_contents 
        INNER JOIN masterlength g ON f.id=g.id_width
        INNER JOIN masterweight h ON g.id=h.id_length
        INNER JOIN mastercolor i ON h.id=i.id_weight
        INNER JOIN masterdesc j ON i.id=j.id_color
        LEFT JOIN masteritem mi ON j.id=mi.id_gen 
        WHERE mi.id_item IS NULL
        AND j.id = '$id_desc'
        ";

        if (!mysqli_query($conn, $sql_item)) {
            throw new Exception("Gagal insert masteritem");
        }

    } else {
        $id_desc = $_POST['cbo_description'];
    }

    // ================= COMMIT =================
    mysqli_commit($conn);

    echo json_encode([
        "status" => "ok",
        "message" => "Data berhasil disimpan"
    ]);

} catch (Exception $e) {

    mysqli_rollback($conn);

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}

exit;
?>