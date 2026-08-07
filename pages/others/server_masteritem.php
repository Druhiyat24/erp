<?php
include "../../include/conn.php"; // sesuaikan

$request = $_REQUEST;
$user = isset($request['user']) ? $request['user'] : '';
$sql_user="SELECT m_general_req, edit_m_general_req FROM userpassword WHERE username ='$user'";
$q_user=mysql_query($sql_user);
if (!$q_user) {
    // query error - fallback biar tidak error
    $akses = '';
    // bisa di-uncomment untuk debug:
    $error_msg = mysql_error();
    file_put_contents('debug_log.txt', date('Y-m-d H:i:s').' - '.$error_msg.PHP_EOL, FILE_APPEND);
} else {
    $hsl=mysql_fetch_array($q_user);
    $akses = ($hsl) ? $hsl['m_general_req'] : '';
    $akses2 = ($hsl) ? $hsl['edit_m_general_req'] : '';
}

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
$sql = "SELECT ITEM.*, MAPP.description,
        CONCAT(IFNULL(C1.no_coa,''),IF(C1.nama_coa IS NOT NULL,CONCAT(' - ',C1.nama_coa),'')) as coa_prod_name,
        CONCAT(IFNULL(C2.no_coa,''),IF(C2.nama_coa IS NOT NULL,CONCAT(' - ',C2.nama_coa),'')) as coa_sup_prod_name,
        CONCAT(IFNULL(C3.no_coa,''),IF(C3.nama_coa IS NOT NULL,CONCAT(' - ',C3.nama_coa),'')) as coa_sup_gen_name,
        CONCAT(IFNULL(C4.no_coa,''),IF(C4.nama_coa IS NOT NULL,CONCAT(' - ',C4.nama_coa),'')) as coa_sup_sell_name
        FROM masteritem ITEM
        LEFT JOIN mapping_category MAPP ON MAPP.n_id = ITEM.n_code_category
        LEFT JOIN mastercoa_v2 C1 ON C1.no_coa = ITEM.coa_production
        LEFT JOIN mastercoa_v2 C2 ON C2.no_coa = ITEM.coa_sup_production
        LEFT JOIN mastercoa_v2 C3 ON C3.no_coa = ITEM.coa_sup_gen_adm
        LEFT JOIN mastercoa_v2 C4 ON C4.no_coa = ITEM.coa_sup_selling
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
    if ($akses == "1" && $akses2 == "0") {
        $nestedData[] = "<a href='../others/?mod=2&id=".$row['id_item']."' style='color:orange'><i class='fa fa-pencil'></i></a>";
        $nestedData[] = !empty($row['file_gambar']) ? "<a href='javascript:void(0)' data-toggle='modal' data-target='#imgModal' onclick=\"document.getElementById('imgPreview').src='upload_files/".$row['file_gambar']."'\"><i class='fa fa-picture-o'></i></a>" : "";
        $nestedData[] = "";
        $nestedData[] = "";
        $nestedData[] = "";
        $nestedData[] = "";
        $nestedData[] = "";
    } elseif ($row['non_aktif'] == "N") {
        $nestedData[] = "<a href='../others/?mod=2&id=".$row['id_item']."'><i class='fa fa-pencil'></i></a>";
        $nestedData[] = "<a href='d_master.php?id=".$row['id_item']."' onclick=\"return confirm('Hapus?')\"><i class='fa fa-trash'></i></a>";
        $nestedData[] = "<a href='../forms/non_akt.php?mode=Non&id=".$row['id_item']."' onclick=\"return confirm('Non aktif?')\"><i class='fa fa-eye-slash'></i></a>";
        $nestedData[] = "<a href='#' class='img-prev' data-id=".$row['id_item']."><i class='fa fa-paperclip'></i></a>";
        $nestedData[] = "<a href='?mod=14&mode=General&id=".$row['id_item']."'><i class='fa fa-history'></i></a>";
        $p1 = htmlspecialchars($row['coa_prod_name'],     ENT_QUOTES);
        $p2 = htmlspecialchars($row['coa_sup_prod_name'], ENT_QUOTES);
        $p3 = htmlspecialchars($row['coa_sup_gen_name'],  ENT_QUOTES);
        $p4 = htmlspecialchars($row['coa_sup_sell_name'], ENT_QUOTES);
        $nestedData[] = "<a href='#' class='coa-popup' data-prod='".$p1."' data-spr='".$p2."' data-sga='".$p3."' data-ssl='".$p4."' title='Lihat COA'><i class='fa fa-book'></i></a>";
        $nestedData[] = !empty($row['file_gambar']) ? "<a href='javascript:void(0)' data-toggle='modal' data-target='#imgModal' onclick=\"document.getElementById('imgPreview').src='upload_files/".$row['file_gambar']."'\"><i class='fa fa-picture-o'></i></a>" : "";                
    } else {
        $nestedData[] = "";
        $nestedData[] = "";
        $nestedData[] = "";
        $nestedData[] = "";
        $nestedData[] = "";
        $nestedData[] = "";
        $nestedData[] = "";
    }




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