<?php
include "../../include/conn.php"; // sesuaikan

$request = $_REQUEST;

$columns = array(
    0 => 'ITEM.id_item',
    1 => 'ITEM.goods_code',
    2 => 'MAPP.description',
    3 => 'ITEM.itemdesc',
    4 => 'ITEM.color',
    5 => 'ITEM.size',
    6 => 'ITEM.tipe_item',
    7 => 'ITEM.non_aktif'
);

$persediaan = isset($_POST['persediaan']) ? $_POST['persediaan'] : '';

$where = "WHERE ITEM.mattype IN ('N','M')";

if ($persediaan != "" && $persediaan != "ALL") {
    $where .= " AND ITEM.n_code_category = '".mysql_real_escape_string($persediaan)."'";
}

/* TOTAL DATA */
$sql = "SELECT COUNT(*) as total 
        FROM masteritem ITEM 
        LEFT JOIN mapping_category MAPP ON MAPP.n_id = ITEM.n_code_category 
        $where";
$query = mysql_query($sql);
$data = mysql_fetch_array($query);
$totalData = $data['total'];
$totalFiltered = $totalData;

/* SEARCH */
if (!empty($request['search']['value'])) {

    $search = mysql_real_escape_string($request['search']['value']);

    $where .= " AND (
        ITEM.goods_code LIKE '%$search%' OR
        ITEM.itemdesc LIKE '%$search%' OR
        ITEM.color LIKE '%$search%' OR
        ITEM.size LIKE '%$search%' OR
        ITEM.tipe_item LIKE '%$search%'
    )";

    $sql = "SELECT COUNT(*) as total 
            FROM masteritem ITEM 
            LEFT JOIN mapping_category MAPP ON MAPP.n_id = ITEM.n_code_category 
            $where";
    $query = mysql_query($sql);
    $data = mysql_fetch_array($query);
    $totalFiltered = $data['total'];
}

/* MAIN QUERY */
$sql = "SELECT ITEM.*, MAPP.description 
        FROM masteritem ITEM 
        LEFT JOIN mapping_category MAPP ON MAPP.n_id = ITEM.n_code_category 
        $where 
        ORDER BY ".$columns[$request['order'][0]['column']]." ".$request['order'][0]['dir']."
        LIMIT ".$request['start']." ,".$request['length'];

$query = mysql_query($sql);

$data = array();

while ($row = mysql_fetch_array($query)) {

    $nestedData = array();

    $nestedData[] = $row["id_item"];
    $nestedData[] = $row["goods_code"];
    $nestedData[] = $row["description"];
    $nestedData[] = $row["itemdesc"];
    $nestedData[] = $row["color"];
    $nestedData[] = $row["size"];
    $nestedData[] = $row["tipe_item"];
    $nestedData[] = $row["non_aktif"];

    // tombol action
    if ($row['non_aktif'] == "N") {
        $nestedData[] = "<a href='../others/?mod=2&id=".$row['id_item']."'><i class='fa fa-pencil'></i></a>";
        $nestedData[] = "<a href='d_master.php?id=".$row['id_item']."' onclick=\"return confirm('Hapus?')\"><i class='fa fa-trash'></i></a>";
        $nestedData[] = "<a href='../forms/non_akt.php?mode=Non&id=".$row['id_item']."' onclick=\"return confirm('Non aktif?')\"><i class='fa fa-eye-slash'></i></a>";
    } else {
        $nestedData[] = "";
        $nestedData[] = "";
        $nestedData[] = "";
    }

    $nestedData[] = "<a href='#' class='img-prev' data-id=".$row['id_item']."><i class='fa fa-paperclip'></i></a>";
    $nestedData[] = "<a href='?mod=14&mode=General&id=".$row['id_item']."'><i class='fa fa-history'></i></a>";

    $data[] = $nestedData;
}

/* OUTPUT */
$json_data = array(
    "draw" => intval($request['draw']),
    "recordsTotal" => intval($totalData),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
);

echo json_encode($json_data);
?>