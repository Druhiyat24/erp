<?php
// Koneksi
include "../../include/conn.php"; // pastikan file ini menggunakan mysql_connect

// Ambil parameter dari DataTables
$start  = intval($_GET['start']);
$length = intval($_GET['length']);
$search = mysql_real_escape_string($_GET['search']['value']);
$orderCol = intval($_GET['order'][0]['column']);
$orderDir = ($_GET['order'][0]['dir'] === 'desc') ? 'DESC' : 'ASC';

$columns = [
    'a.reqno',
    'a.reqdate',
    'supplier',
    'mi.itemdesc',
    'a.notes',
    'a.cancel_h',
    'a.username',
    'a.dateinput',
    'a.app_by',
    'a.app_by2',
    'tmppo.username',
    'tmppo.pono'
];

// USER FILTERING
session_start();
$user = $_SESSION['username']; // pastikan user login

function flookup($field, $table, $where) {
    $sql = "SELECT $field FROM $table WHERE $where LIMIT 1";
    $q = mysql_query($sql);
    if ($q && mysql_num_rows($q) > 0) {
        $d = mysql_fetch_array($q);
        return $d[$field];
    }
    return "";
}

$all_dept = flookup("username", "userpassword", "username='$user' AND all_dept='1'");

if ($all_dept == "") {
    $user_group = flookup("kode_mkt", "userpassword", "username='$user'");
    $filter_user = "WHERE up.kode_mkt='$user_group'";
    $filter_date = "";
} else {
    $filter_user = "";
    $filter_date = "WHERE a.reqdate >= '2022-01-01'";
}

$where = trim("$filter_user $filter_date");

// PENCARIAN GLOBAL
if (!empty($search)) {
    $where .= ($where ? " AND " : "WHERE ") . "(
        a.reqno LIKE '%$search%' OR
        ms.supplier LIKE '%$search%' OR
        mi.itemdesc LIKE '%$search%' OR
        a.notes LIKE '%$search%'
    )";
}

// HITUNG TOTAL
$total_sql = "SELECT COUNT(*) AS total
FROM (
  SELECT a.reqno, ms.supplier
  FROM reqnon_header a 
  LEFT JOIN reqnon_item s ON a.id = s.id_reqno
  LEFT JOIN userpassword up ON a.username = up.username
  LEFT JOIN masteritem mi ON s.id_item = mi.id_item
  LEFT JOIN mastersupplier ms ON s.id_supplier = ms.id_supplier
  $where
  GROUP BY a.reqno, ms.supplier
) AS subquery;";

$total_q = mysql_query($total_sql);
$total_data = mysql_fetch_array($total_q);
$total = $total_data['total'];

// QUERY UTAMA
$sql = "SELECT a.*, 
    IF(tmppo.supplier_po!='', tmppo.supplier_po, ms.supplier) AS supplier,
    tmppo.username AS userpo,
    tmppo.podate,
    tmppo.app AS app_po,
    mi.itemdesc,
    tmppo.pono
FROM reqnon_header a 
LEFT JOIN reqnon_item s ON a.id = s.id_reqno 
INNER JOIN userpassword up ON a.username = up.username 
LEFT JOIN masteritem mi ON s.id_item = mi.id_item 
LEFT JOIN mastersupplier ms ON s.id_supplier = ms.id_supplier 
LEFT JOIN (
    SELECT s.id_jo, a.username, a.podate, a.pono, d.supplier AS supplier_po, a.app 
    FROM po_header a 
    INNER JOIN po_item s ON a.id = s.id_po 
    INNER JOIN mastersupplier d ON a.id_supplier = d.id_supplier 
    WHERE jenis = 'N'
) tmppo ON tmppo.id_jo = a.id
$where
GROUP BY a.reqno,ms.supplier
ORDER BY a.dateinput DESC
LIMIT $start, $length";

$data = [];
$q = mysql_query($sql);
while ($row = mysql_fetch_array($q)) {
    $cancel_text = ($row['cancel_h'] == 'Y') ? 'Cancelled' : '';
    $created_date = date('d-m-Y H:i', strtotime($row['dateinput']));
    $req_date = date('d-m-Y', strtotime($row['reqdate']));
    $po_date = $row['podate'] ? date('d-m-Y', strtotime($row['podate'])) : '';

    $data[] = [
        $row['reqno'],
        $req_date,
        $row['supplier'],
        $row['itemdesc'],
        $row['notes'],
        $cancel_text,
        $row['username'],
        $created_date,
        $row['app_by'] . " (" . $row['app'] . ")",
        $row['app_by2'] . " (" . $row['app2'] . ")",
        $row['userpo'] . " ($po_date)",
        $row['pono'] . " (" . $row['app_po'] . ")",

        // Aksi
        ($row['app'] == 'W' || $row['app'] == 'R') && $row['cancel_h'] == 'N'
            ? "<a href='../others/?mod=1&id={$row['id']}'><i class='fa fa-pencil'></i></a>"
            : '',
        ($row['app'] == 'W' || $row['app'] == 'R') && $row['cancel_h'] == 'N'
            ? "<a href='d_genrh.php?mod=1&id={$row['id']}' onclick=\"return confirm('Yakin ingin cancel?')\"><i class='fa fa-times'></i></a>"
            : '',
        "<a href='../others/pdfReq.php?id={$row['id']}'><i class='fa fa-print'></i></a>"
    ];
}

// Keluaran JSON
echo json_encode([
    "draw" => intval($_GET['draw']),
    "recordsTotal" => $total,
    "recordsFiltered" => $total,
    "data" => $data
]);
?>