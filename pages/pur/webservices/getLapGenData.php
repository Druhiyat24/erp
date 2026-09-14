<?php 
include __DIR__ .'/../../../include/conn.php';

## Read value
$draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
$row = isset($_POST['start']) ? intval($_POST['start']) : 0;
$rowperpage = isset($_POST['length']) ? intval($_POST['length']) : 10;
$columnIndex = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : 0;
$columnSortOrder = (isset($_POST['order'][0]['dir']) && strtolower($_POST['order'][0]['dir'])=='asc') ? 'ASC' : 'DESC';
$searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';

## Filter params
$from = (isset($_POST['from']) && trim($_POST['from']) != '') ? date('Y-m-d', strtotime($_POST['from'])) : '';
$to = (isset($_POST['to']) && trim($_POST['to']) != '') ? date('Y-m-d', strtotime($_POST['to'])) : '';
$txtsupplier = isset($_POST['txtsupplier']) ? $_POST['txtsupplier'] : '';
$txtbuyer = isset($_POST['txtbuyer']) ? $_POST['txtbuyer'] : '';

$supplier_filter = ($txtsupplier != '') ? "AND a.id_supplier='".$txtsupplier."'" : "";
$buyer_filter = ($txtbuyer != '') ? "AND a.id_buyer='".$txtbuyer."'" : "";
$date_filter = "";
if ($from != '' && $to != '') {
  $date_filter = " AND a.podate BETWEEN '".$from."' AND '".$to."'";
} elseif ($from != '') {
  $date_filter = " AND a.podate >= '".$from."'";
} elseif ($to != '') {
  $date_filter = " AND a.podate <= '".$to."'";
}

$table = "(
SELECT IF(a.app = 'A', 'Approved', 'Waiting') AS status_po,
  a.id, a.pono, a.podate, s.supplier,
  d.nama_pterms, a.n_kurs,
  tmppoit.buyer, tmppoit.t_row, tmppoit.t_rows_cancel,
  tmppoit.itemdesc, tmppoit.qty, tmppoit.unit, tmppoit.price, tmppoit.curr
FROM po_header a
INNER JOIN mastersupplier s ON a.id_supplier=s.id_supplier
INNER JOIN masterpterms d ON a.id_terms=d.id
INNER JOIN (
  SELECT poit.id_jo, poit.id_po, GROUP_CONCAT(DISTINCT reqno) buyer,
    COUNT(*) t_row, SUM(IF(cancel='Y',1,0)) t_rows_cancel,
    j.itemdesc, poit.qty, poit.unit, poit.price, poit.curr
  FROM po_item poit
  INNER JOIN reqnon_header rnh ON poit.id_jo=rnh.id
  INNER JOIN masteritem j ON poit.id_gen = j.id_item
  GROUP BY poit.id_po
) tmppoit ON tmppoit.id_po=a.id
WHERE a.jenis='N' $date_filter $supplier_filter $buyer_filter
)X";

## Search 
$searchQuery = " ";
if($searchValue != ''){
  $searchQuery = " AND (
   X.supplier       LIKE '%".$searchValue."%'
OR X.pono           LIKE '%".$searchValue."%'
OR X.podate         LIKE '%".$searchValue."%'
OR X.buyer          LIKE '%".$searchValue."%'
OR X.itemdesc       LIKE '%".$searchValue."%'
OR X.qty            LIKE '%".$searchValue."%'
OR X.unit           LIKE '%".$searchValue."%'
OR X.price          LIKE '%".$searchValue."%'
OR X.curr           LIKE '%".$searchValue."%'
OR X.status_po      LIKE '%".$searchValue."%'
OR X.nama_pterms    LIKE '%".$searchValue."%'
)";
}

## Total number of records without filtering
$sel = mysqli_query($conn_li,"select count(*) allcount from $table");
$records = mysqli_fetch_assoc($sel); 
$totalRecords = $records['allcount'];

## Total number of record with filtering
$sel = mysqli_query($conn_li,"select count(*) allcount from $table WHERE 1 ".$searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Column mapping for order
$columns = array('no','supplier','pono','podate','buyer','itemdesc','qty','unit','price','curr','status_po');
$columnName = isset($columns[$columnIndex]) ? $columns[$columnIndex] : 'no';
if ($columnName == 'no') { $columnName = 'X.podate'; $columnSortOrder = 'DESC'; }
else { $columnName = 'X.'.$columnName; }

## Fetch records
$colomn = "
  X.status_po, X.id, X.pono, X.podate, X.supplier,
  X.nama_pterms, X.n_kurs, X.buyer, X.t_row, X.t_rows_cancel,
  X.itemdesc, X.qty, X.unit, X.price, X.curr
";

$empQuery = "select $colomn from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
$no = $row + 1;

while ($rowx = mysqli_fetch_assoc($empRecords)) {
  $status_badge = ($rowx['status_po'] == 'Approved') 
    ? '<span style="background:#27ae60;color:#fff;padding:2px 8px;border-radius:10px;font-size:11px;">Approved</span>'
    : '<span style="background:#f39c12;color:#fff;padding:2px 8px;border-radius:10px;font-size:11px;">Waiting</span>';

  $data[] = array(
    "no" => $no,
    "supplier" => $rowx['supplier'],
    "pono" => $rowx['pono'],
    "podate" => date('d M Y', strtotime($rowx['podate'])),
    "buyer" => $rowx['buyer'],
    "itemdesc" => $rowx['itemdesc'],
    "qty" => number_format($rowx['qty'],2),
    "unit" => $rowx['unit'],
    "price" => number_format($rowx['price'],2),
    "curr" => $rowx['curr'],
    "status_po" => $status_badge
  );
  $no++;
}

## Response
$response = array(
  "draw" => intval($draw),
  "recordsTotal" => intval($totalRecords),
  "recordsFiltered" => intval($totalRecordwithFilter),
  "data" => $data
);

echo json_encode($response);
?>
