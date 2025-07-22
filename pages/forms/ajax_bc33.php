<?php
include "../../include/conn.php";
include "../forms/fungsi.php";

$tglf = $_POST['tglfrom'];
$tglt = $_POST['tglto'];

$columns = array(
    0 => 'jenis_dokumen',
    1 => 'matclass',
    2 => 'bcno',
    3 => 'bcdate',
    4 => 'trans_no',
    5 => 'trans_date',
    6 => 'supplier',
    7 => 'kode_brg',
    8 => 'itemdesc',
    9 => 'unit',
    10 => 'qty',
    11 => 'curr',
    12 => 'nilai_barang'
);

$start = intval($_POST['start']);
$length = intval($_POST['length']);

if ($length == -1) {
    $limit = ""; // tampilkan semua data
} else {
    $limit = "LIMIT $start, $length";
}
$orderCol = $columns[intval($_POST['order'][0]['column'])];
$orderDir = $_POST['order'][0]['dir'];
$order = "ORDER BY $orderCol $orderDir";

$where = "WHERE a.bppbdate BETWEEN '$tglf' AND '$tglt' AND a.cancel='N' AND jenis_dok='BC 3.3'";

// QUERY UTAMA
$sql_union = "
SELECT * FROM (
    SELECT 'BC 3.3' jenis_dokumen,
        LPAD(a.bcno,6,'0') bcno,
        a.bcdate,
        IF(a.bppbno_int != '', a.bppbno_int, a.bppbno) trans_no,
        a.bppbdate trans_date,
        d.supplier,
        IF(s.goods_code != '' AND s.goods_code != '-' AND s.goods_code != '0', s.goods_code, CONCAT('FG ',s.id_item)) kode_brg,
        s.itemname itemdesc,
        a.unit,
        a.qty,
        ROUND(a.qty * IFNULL(a.price_bc, a.price), 2) nilai_barang,
        a.curr,
        IF(a.price_bc IS NULL OR a.price_bc = 0 OR a.price_bc = '', a.price, a.price_bc) price,
        s.id_item,
        'BARANG JADI' matclass
    FROM bppb a
    INNER JOIN masterstyle s ON a.id_item = s.id_item
    LEFT JOIN mastersupplier d ON a.id_supplier = d.id_supplier
    $where AND MID(a.bppbno,4,2) = 'FG'

    UNION ALL

    SELECT 'BC 3.3', LPAD(a.bcno,6,'0'), a.bcdate,
        IF(a.bppbno_int != '', a.bppbno_int, a.bppbno),
        a.bppbdate, d.supplier,
        IF(s.goods_code != '' AND s.goods_code != '-' AND s.goods_code != '0', s.goods_code, CONCAT('F ',s.id_item)),
        s.itemdesc, a.unit, a.qty,
        ROUND(a.qty * IFNULL(a.price_bc, a.price), 2),
        a.curr,
        IF(a.price_bc IS NULL OR a.price_bc = 0 OR a.price_bc = '', a.price, a.price_bc),
        s.id_item, 'FABRIC'
    FROM bppb a
    INNER JOIN masteritem s ON a.id_item = s.id_item
    LEFT JOIN mastersupplier d ON a.id_supplier = d.id_supplier
    $where AND LEFT(a.bppbno_int,2) = 'GK'

    UNION ALL

    SELECT 'BC 3.3', LPAD(a.bcno,6,'0'), a.bcdate,
        IF(a.bppbno_int != '', a.bppbno_int, a.bppbno),
        a.bppbdate, d.supplier,
        IF(s.goods_code != '' AND s.goods_code != '-' AND s.goods_code != '0', s.goods_code, CONCAT('F ',s.id_item)),
        s.itemdesc, a.unit, a.qty,
        ROUND(a.qty * IFNULL(a.price_bc, a.price), 2),
        a.curr,
        IF(a.price_bc IS NULL OR a.price_bc = 0 OR a.price_bc = '', a.price, a.price_bc),
        s.id_item, s.matclass
    FROM bppb a
    INNER JOIN masteritem s ON a.id_item = s.id_item
    LEFT JOIN mastersupplier d ON a.id_supplier = d.id_supplier
    $where AND LEFT(a.bppbno_int,3) = 'GEN'
) x
";

// Hitung total
$totalData = mysql_num_rows(mysql_query($sql_union));
$sql_query = "$sql_union $order $limit";
$query = mysql_query($sql_query);

$data = array();
$no = $_POST['start'] + 1;
while ($row = mysql_fetch_assoc($query)) {
    $data[] = array(
        "no" => $no++,
        "jenis_dokumen" => $row['jenis_dokumen'],
        "matclass" => $row['matclass'],
        "bcno" => $row['bcno'],
        "bcdate" => ($row['bcdate'] == '0000-00-00' ? '' : date('d M Y', strtotime($row['bcdate']))),
        "trans_no" => $row['trans_no'],
        "trans_date" => ($row['trans_date'] == '0000-00-00' ? '' : date('d M Y', strtotime($row['trans_date']))),
        "supplier" => $row['supplier'],
        "kode_brg" => $row['kode_brg'],
        "itemdesc" => $row['itemdesc'],
        "unit" => $row['unit'],
        "qty" => number_format($row['qty'], 2),
        "curr" => $row['curr'],
        "nilai_barang" => number_format($row['nilai_barang'], 2)
    );
}

$json_data = array(
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $totalData,
    "recordsFiltered" => $totalData, // update if ada filter pencarian nanti
    "data" => $data
);

echo json_encode($json_data);
?>
