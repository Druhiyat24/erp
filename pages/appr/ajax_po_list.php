<?php
session_start();
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store');
include '../../include/conn.php';
if (empty($_SESSION['username'])) { echo json_encode(array('results' => array(), 'pagination' => array('more' => false))); exit; }

$term = isset($_GET['q']) ? trim($_GET['q']) : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) { $page = 1; }
$limit = 20;
$offset = ($page - 1) * $limit;

$where = "a.app='A'";
if ($term != '') {
    $term_safe = mysql_real_escape_string($term);
    $where .= " AND (a.pono LIKE '%$term_safe%' OR s.supplier LIKE '%$term_safe%')";
}

$count_sql = "SELECT COUNT(DISTINCT a.id) as total FROM po_header a INNER JOIN mastersupplier s ON a.id_supplier=s.id_supplier WHERE $where";
$count_result = mysql_query($count_sql);
$total = 0;
if ($count_result) {
    $row = mysql_fetch_assoc($count_result);
    $total = intval($row['total']);
}

$sql = "SELECT concat(a.id,':',a.pono) as id, concat(a.pono,' - ',s.supplier) as text 
        FROM po_header a 
        INNER JOIN mastersupplier s ON a.id_supplier=s.id_supplier 
        WHERE $where 
        GROUP BY a.id 
        ORDER BY a.pono DESC 
        LIMIT $limit OFFSET $offset";

$query = mysql_query($sql);
if (!$query) {
    echo json_encode(array('results' => array(), 'pagination' => array('more' => false)));
    exit;
}
$results = array();
while ($data = mysql_fetch_array($query)) {
    $results[] = array(
        'id' => $data['id'],
        'text' => $data['text']
    );
}

echo json_encode(array(
    'results' => $results,
    'pagination' => array(
        'more' => ($page * $limit) < $total
    )
));
?>
