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
    12 => 'nilai_barang',
    13 => 'rate',
    14 => 'nilai_barang_idr'
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

// QUERY UTAMA
$sql_union = "SELECT jenis_dokumen, bcno, bcdate, trans_no, trans_date, supplier, kode_brg, itemdesc, unit, qty, nilai_barang, a.curr, price, id_item, matclass, COALESCE(mr.rate,1) rate, (nilai_barang * COALESCE(mr.rate,1)) nilai_barang_idr from (SELECT 'BC 4.1 LOKAL MESIN (SEWA)' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,if(a.bppbno_int!='',a.bppbno_int,a.bppbno) trans_no, a.bppbdate trans_date,d.supplier,if(goods_code<>'' AND goods_code<>'-' AND goods_code<>'0',goods_code,concat(s.mattype,s.id_item)) kode_brg, s.itemdesc,a.unit,a.qty, round(a.qty*ifnull(a.price_bc,a.price),2) nilai_barang,a.id_item ,a.curr,a.price ,s.mattype,s.matclass from bppb a inner join masteritem s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier where bppbdate between '$tglf' AND '$tglt' and mid(a.bppbno,4,2)<>'FG' and jenis_dok='BC 4.1' and ucase(remark) like '%SEWA%' and mid(bppbno,4,1)<>'S' order by bcdate,bcno) a LEFT JOIN (SELECT tanggal, curr, rate FROM ap_masterrate where v_codecurr = 'PAJAK' GROUP BY tanggal, curr) mr ON mr.tanggal = a.bcdate AND mr.curr = a.curr";

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
        "nilai_barang" => number_format($row['nilai_barang'], 2),
        "rate" => number_format($row['rate'], 2),
        "nilai_barang_idr" => number_format($row['nilai_barang_idr'], 2)
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
