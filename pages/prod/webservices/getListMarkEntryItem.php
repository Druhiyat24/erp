<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];

$id_ws = $_GET['id_cost'];
$color = $_GET['color'];
$panel = $_GET['panel'];

// print_r($id_cost);die();

$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = "id_cost"; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(
	
	SELECT 
		ac.id AS id_cost,
		ac.kpno AS ws,
		s.id AS id_so,
		j.id AS id_jo,
		vpb.id_item_ms_item AS id_item,
		vpb.fabric_code,
		vpb.fabric_desc,
		IF(med.item_id IS NULL, '0', med.item_id) AS item_id_detail,
		med.id_panel
	FROM act_costing AS ac
	INNER JOIN (
		SELECT 
			s.id,
			s.id_cost
		FROM so AS s
	) AS s ON s.id_cost = ac.id
	INNER JOIN (
		SELECT 
			jd.id,
			jd.id_so,
			jd.id_jo
		FROM jo_det AS jd
	) AS jd ON jd.id_so = s.id
	INNER JOIN (
		SELECT 
			j.id 
		FROM jo AS j
	) AS j ON j.id = jd.id_jo
	INNER JOIN (
		SELECT 
			vpb.id_item_ms_item,
			vpb.goods_code AS fabric_code,
			vpb.fabric_desc,
			vpb.color,
			vpb.id_jo
		FROM view_portal_bom AS vpb
	) AS vpb ON vpb.id_jo = j.id
	LEFT JOIN (
		SELECT 
			med.id_cost,
			med.color,
			med.id_item AS item_id,
			med.id_panel
		FROM prod_mark_entry_detail AS med 
		WHERE med.id_cost = '{$id_ws}' AND med.color = '{$color}' AND med.id_panel = '$panel' 
		GROUP BY med.id_item
	) AS med ON med.item_id = vpb.id_item_ms_item
	WHERE ac.id = '{$id_ws}'
	#AND vpb.color = '{$color}'
	GROUP BY vpb.id_item_ms_item

) AS X";
### Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (
   		X.ws				LIKE'%".$searchValue."%'
	)";
}

## Total number of records without filtering
$sel = mysqli_query($conn_li,"select count(*) allcount from $table");
$records = mysqli_fetch_assoc($sel); 

$totalRecords = $records['allcount'];

## Total number of record with filtering
$sel = mysqli_query($conn_li,"select count(*) allcount from $table WHERE 1 ");
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];
## Fetch records
$colomn = "X.id_cost
		  ,X.ws
		  ,X.id_item
		  ,X.id_so
		  ,X.id_jo
		  ,X.fabric_code
		  ,X.fabric_desc
		  ,X.item_id_detail
		  ,X.id_panel";

$empQuery = "select $colomn from $table WHERE 1 ORDER BY X.id_item ASC";
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();

// echo $empQuery;die();
$no = 1;
// $sqlItem=mysqli_query($conn_li,"SELECT med.id_cost,med.color,med.id_item AS item FROM prod_mark_entry_detail AS med WHERE med.id_cost = '{$id_ws}' AND med.color = '{$color}' GROUP BY med.id_item");
// $rowItem = mysqli_fetch_assoc($sqlItem);

while ($row = mysqli_fetch_assoc($empRecords)) {
	// echo '123';die();
	//echo $row['n_post'];


	if($row['item_id_detail'] == '0'){
		// $value = 'Add';
		// $disabled = '';
		$buttonItem = "<input type='button' value='Add' class='btn btn-primary' onclick='editItem(".'"'.$row['id_item'].'","'.$row['id_jo'].'","'.$row['id_panel'].'"'.")'>";
	}
	else{
		// $value = 'Oke';
		// $disabled = 'disabled';
		$buttonItem = "<input type='button' value='Oke' class='btn btn-success' disabled onclick='editItem(".'"'.$row['id_item'].'","'.$row['id_jo'].'","'.$row['id_panel'].'"'.")'>";
	}
	$button = '';
	// $button .="<input type='button' value=$value class='btn btn-primary' $disabled onclick='editItem(".'"'.$row['id_item'].'","'.$row['id_jo'].'","'.$row['id_panel'].'"'.")'>";
	$button .="$buttonItem";


	$data[] = array(
		"no"=>$no,
		"fabric_code"=>htmlspecialchars($row['fabric_code']),
		"fabric_desc"=>htmlspecialchars($row['fabric_desc']),
		"button"=>rawurlencode($button)
	);
	$no++;
}
## Response
$response = array(
  "draw" => intval($draw),
  "iTotalRecords" => $totalRecordwithFilter,
  "iTotalDisplayRecords" => $totalRecords,
  "aaData" => $data
);
echo json_encode($response);
?>