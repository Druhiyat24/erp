<?php
include "../../include/conn.php";

// Matikan semua error ke output agar tidak merusak JSON
error_reporting(0);
ini_set('display_errors', 0);

$requestData = $_REQUEST;

// Kolom urutan sesuai tabel
$columns = array(
  0 => 'buyer',
  1 => 'kpno',
  2 => 'styleno',
  3 => 'jns_so',
  4 => 'main_dest'
);

// Hitung total data tanpa filter
$sql = "SELECT 
          ms.Supplier AS buyer,
          ac.kpno,
          ac.styleno,
          so.jns_so,
          ac.main_dest
        FROM act_costing ac
        INNER JOIN mastersupplier ms ON ac.id_buyer = ms.Id_Supplier
        INNER JOIN so ON ac.id = so.id_cost
        WHERE ac.aktif = 'Y' AND so.cancel_h = 'N'";

$query = mysql_query($sql);
if (!$query) {
  // Jika query error, kirim JSON error untuk debug
  echo json_encode(array("error" => mysql_error()));
  exit;
}

$totalData = mysql_num_rows($query);
$totalFiltered = $totalData;

// Tambahkan filter pencarian (jika ada)
if (!empty($requestData['search']['value'])) {
  $search = mysql_real_escape_string($requestData['search']['value']);
  $sql .= " AND (ms.Supplier LIKE '%" . $search . "%' 
           OR ac.kpno LIKE '%" . $search . "%' 
           OR ac.styleno LIKE '%" . $search . "%' 
           OR so.jns_so LIKE '%" . $search . "%' 
           OR ac.main_dest LIKE '%" . $search . "%')";
}

// Hitung total filtered
$query = mysql_query($sql);
$totalFiltered = mysql_num_rows($query);

// Ambil data dengan limit dan urutan
$order_col = isset($columns[$requestData['order'][0]['column']]) ? $columns[$requestData['order'][0]['column']] : 'buyer';
$order_dir = ($requestData['order'][0]['dir'] == 'desc') ? 'desc' : 'asc';
$start     = intval($requestData['start']);
$length    = intval($requestData['length']);

$sql .= " ORDER BY " . $order_col . " " . $order_dir . " LIMIT " . $start . ", " . $length;
$query = mysql_query($sql);

$data = array();
$no = $start + 1;
while ($row = mysql_fetch_assoc($query)) {
  $nestedData = array();
  $nestedData['no'] = $no;
  $nestedData['buyer'] = $row['buyer'];
  $nestedData['kpno'] = $row['kpno'];
  $nestedData['styleno'] = $row['styleno'];
  $nestedData['jns_so'] = $row['jns_so'];
  $nestedData['main_dest'] = $row['main_dest'];
  $data[] = $nestedData;
  $no++;
}

header('Content-Type: application/json');
echo json_encode(array(
  "draw" => intval($requestData['draw']),
  "recordsTotal" => intval($totalData),
  "recordsFiltered" => intval($totalFiltered),
  "data" => $data
));
?>
