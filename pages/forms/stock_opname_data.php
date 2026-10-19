<?php
// Endpoint server-side DataTables untuk stock opname (dipanggil dari stock_opname.php).
session_start();
header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['username'])) {
  http_response_code(403);
  echo json_encode(array('error' => 'Session tidak valid'));
  exit;
}

include __DIR__ . '/../../include/conn.php';

$mode   = isset($_POST['mode']) ? $_POST['mode'] : '';
$draw   = isset($_POST['draw']) ? (int)$_POST['draw'] : 0;
$start  = isset($_POST['start']) ? max(0, (int)$_POST['start']) : 0;
$length = isset($_POST['length']) ? (int)$_POST['length'] : 10;
if ($length < 1 || $length > 500) { $length = 10; }
$search = isset($_POST['search']['value']) ? trim($_POST['search']['value']) : '';

// Whitelist kolom: key = data DataTables => ekspresi SQL (alias = key)
if ($mode == 'FG') {
  $from   = "from stock a inner join masterstyle s on a.mattype='FG' and a.id_item=s.id_item";
  $where  = "1=1";
  $colMap = array(
    'styleno'    => 'styleno',
    'kpno'       => 'kpno',
    'id_item'    => 'a.id_item',
    'goods_code' => 'goods_code',
    'color'      => 'color',
    'size'       => 'size',
    'stock'      => 'a.stock',
  );
} else {
  if ($mode == 'General') {
    $crit = "s.mattype='N'";
  } else if ($mode == 'Bahan_Baku') {
    $crit = "s.mattype in ('A','F')";
  } else {
    $crit = "s.mattype not in ('N','A','F')";
  }
  $from   = "from stock a inner join masteritem s on a.mattype=s.mattype and a.id_item=s.id_item
             left join masteritem_odo odo on s.id_item_odo=odo.id_item_odo";
  $where  = $crit;
  $codeExpr = "if(s.goods_code='-' or s.goods_code='',concat(s.mattype,' ',s.id_item),s.goods_code)";
  $colMap = array(
    'matclass'   => 's.matclass',
    'id_item'    => 'a.id_item',
    'goods_code' => $codeExpr,
    'itemdesc'   => 's.itemdesc',
    'color'      => 's.color',
    'size'       => 's.size',
    'kode_odo'   => 'odo.goods_code',
    'item_odo'   => 'odo.itemdesc',
    'stock'      => 'a.stock',
  );
}

$selectParts = array();
foreach ($colMap as $key => $expr) { $selectParts[] = "$expr as `$key`"; }
$select = 'select ' . implode(', ', $selectParts);

// Pencarian (parameter bind); kolom stock tidak ikut dicari
$params = array();
$types  = '';
$whereSearch = '';
if ($search !== '') {
  $likes = array();
  foreach ($colMap as $key => $expr) {
    if ($key == 'stock') { continue; }
    $likes[] = "$expr like ?";
    $params[] = '%' . $search . '%';
    $types .= 's';
  }
  $whereSearch = ' and (' . implode(' or ', $likes) . ')';
}

// Urutan (hanya kolom dari whitelist)
$orderSql = ' order by a.id_item asc';
if (isset($_POST['order'][0]['column'])) {
  $colIdx = (int)$_POST['order'][0]['column'];
  $key    = isset($_POST['columns'][$colIdx]['data']) ? $_POST['columns'][$colIdx]['data'] : '';
  $dir    = (isset($_POST['order'][0]['dir']) && strtolower($_POST['order'][0]['dir']) == 'desc') ? 'desc' : 'asc';
  if (isset($colMap[$key])) {
    $orderSql = " order by `$key` $dir";
  }
}

// Total semua baris
$rsTotal = mysqli_query($conn_li, "select count(*) c $from where $where");
$recordsTotal = $rsTotal ? (int)mysqli_fetch_assoc($rsTotal)['c'] : 0;

// Total setelah filter pencarian + jumlah stok (untuk footer)
$stmt = mysqli_prepare($conn_li, "select count(*) c, coalesce(sum(a.stock),0) t $from where $where $whereSearch");
if (!$stmt) {
  http_response_code(500);
  echo json_encode(array('error' => mysqli_error($conn_li)));
  exit;
}
if ($types !== '') { mysqli_stmt_bind_param($stmt, $types, ...$params); }
mysqli_stmt_execute($stmt);
$agg = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);
$recordsFiltered = (int)$agg['c'];
$stockTotal      = (float)$agg['t'];

// Data halaman ini
$stmt = mysqli_prepare($conn_li, "$select $from where $where $whereSearch $orderSql limit ?, ?");
if (!$stmt) {
  http_response_code(500);
  echo json_encode(array('error' => mysqli_error($conn_li)));
  exit;
}
$bindParams = array_merge($params, array($start, $length));
mysqli_stmt_bind_param($stmt, $types . 'ii', ...$bindParams);
mysqli_stmt_execute($stmt);
$rs = mysqli_stmt_get_result($stmt);

$rows = array();
$no = $start + 1;
while ($r = mysqli_fetch_assoc($rs)) {
  $r['no'] = $no++;
  $rows[] = $r;
}
mysqli_stmt_close($stmt);

echo json_encode(array(
  'draw'            => $draw,
  'recordsTotal'    => $recordsTotal,
  'recordsFiltered' => $recordsFiltered,
  'stock_total'     => $stockTotal,
  'data'            => $rows,
));
