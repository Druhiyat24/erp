<?php
session_start();
include "../../include/conn.php";
include "../forms/fungsi.php";
$user=$_SESSION['username'];
$cri_item = isset($_POST['cri_item']) ? $_POST['cri_item'] : 'All';
$status_app = ($cri_item == 'All' || $cri_item == '') ? '' : substr($cri_item, 0, 1);
$status_map = [
  'Approve' => 'A',
  'Reject'  => 'R',
  'Waiting' => 'W',
  'Cancel'  => 'C'
];

if (array_key_exists($cri_item, $status_map)) {
  $status_app = $status_map[$cri_item];
} else {
  $status_app = ''; // All atau tidak ditemukan
}

$mod="1L";
$tt_hapus="data-toggle='tooltip' title='Cancel'";
$tt_hapus2="<i class='fa fa-times'></i>";
// Paging
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 10;
$draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;

// Filtering group
$whereParts = [];
$all_dept = flookup("username", "userpassword", "username='$user' and all_dept='1'");

if ($all_dept == "") {
    $user_group = flookup("kode_mkt", "userpassword", "username='$user'");
    $whereParts[] = "up.kode_mkt = '$user_group'";

    if ($status_app != "") {
        $whereParts[] = "(a.app = '$status_app' OR a.app2 = '$status_app')";
    }
} else {
    if ($status_app == "A" || $status_app == "R" || $status_app == "W") {
        $whereParts[] = "(a.app = '$status_app' OR a.app2 = '$status_app')";
        $whereParts[] = "a.reqdate >= '2022-01-01'";
    } elseif ($status_app == "C") {
        $whereParts[] = "a.cancel_h = 'Y'";
        $whereParts[] = "a.reqdate >= '2022-01-01'";
    } else {
        $whereParts[] = "a.reqdate >= '2022-01-01'";
    }
}

// PENCARIAN GLOBAL
$search = isset($_POST['search']['value']) ? mysql_real_escape_string($_POST['search']['value']) : '';
if (!empty($search)) {
    $whereParts[] = "(
        a.reqno LIKE '%$search%' OR
        ms.supplier LIKE '%$search%' OR
        mi.itemdesc LIKE '%$search%' OR
        a.notes LIKE '%$search%'
    )";
}

$where = '';
if (!empty($whereParts)) {
    $where = 'WHERE ' . implode(' AND ', $whereParts);
}

// Total records
$sql_total = "SELECT COUNT(*) AS total
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

$res_total = mysql_query($sql_total);
$row_total = mysql_fetch_assoc($res_total);
$recordsTotal = $row_total['total'];

// Data query
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

$result = mysql_query($sql);
$data = array();

while ($row = mysql_fetch_assoc($result)) {
    $cancelled = ($row['cancel_h'] == 'Y');
    $status = $cancelled ? "Cancelled" : "";
    $edit = ($row['app'] == 'W' || $row['app'] == 'R') && !$cancelled ?
        "<a href='../others/?mod=1&id={$row['id']}' title='Edit'><i class='fa fa-pencil'></i></a>" : "";
    $cancel = ($row['app'] == 'W' || $row['app'] == 'R') && !$cancelled ?
        "<a href='d_genrh.php?mod=1L&id={$row['id']}' onclick=\"return confirm('Are You Sure Want To Cancel ?')\"><i class='fa fa-times'></i></a>" : "";
    $print = "<a href='../others/pdfReq.php?id={$row['id']}' title='Preview'><i class='fa fa-print'></i></a>";

    $data[] = array(
        "reqno" => $row['reqno'],
        "reqdate" => fd_view($row['reqdate']),
        "supplier" => $row['supplier'],
        "itemdesc" => $row['itemdesc'],
        "notes" => $row['notes'],
        "status" => $status,
        "username" => $row['username'],
        "dateinput" => fd_view_dt($row['dateinput']),
        "app_by" => $row['app_by'] . " (" . $row['app'] . ")",
        "app_by2" => $row['app_by2'] . " (" . $row['app2'] . ")",
        "userpo" => $row['userpo'] . " (" . fd_view($row['podate']) . ")",
        "pono" => $row['pono'],
        "edit" => $edit,
        "cancel" => $cancel,
        "print" => $print
    );
}

// Output JSON
echo json_encode(array(
    "draw" => $draw,
    "recordsTotal" => $recordsTotal,
    "recordsFiltered" => $recordsTotal,
    "data" => $data
));
    ?>