<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];

$id_cost = $_GET['cost'];

// print_r($id_cost);die();
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = "id_cut_out"; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(

	SELECT 
		co.id_cut_out,
		co.id_cost,
		co.color,
		CONCAT(co.username_create, ' ', co.dateinput_create) created_by,
		ac.ws
	FROM prod_cut_out AS co
	INNER JOIN (
		SELECT 
			ac.id,
			ac.kpno AS ws
		FROM act_costing AS ac
	) AS ac ON ac.id = co.id_cost
	WHERE co.id_cost = '{$id_cost}'

) AS X";
 
### Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (
   		X.color			LIKE'%".$searchValue."%'
		OR X.ws			LIKE'%".$searchValue."%'
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
## Fetch records
$colomn = "X.id_cut_out
		  ,X.id_cost
		  ,X.color
		  ,X.ws
		  ,X.created_by
";

$empQuery = "select $colomn  from $table WHERE 1 ORDER BY $columnName ASC ".$searchQuery." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
// echo $empQuery;die();
$no = 1;
while ($row = mysqli_fetch_assoc($empRecords)) {
	// echo '123';die();
//echo $row['n_post'];

$button = '';		
$button .="<a href='../prod/?mod=numCutForm&id=$row[id_cut_out]&cost=$row[id_cost]&color=$row[color]' style='color: #3c8dbc;' title='Edit' class='btn btn-sm' onclick='edit(".'"'.$row['id_cut_out'].'"'.")' ><i class='fa fa-pencil'> </i></a>";
// $button .="<a href='../prod/?mod=3LA&id=$row[id_cut_out]&v=1' style='color: #3c8dbc;' title='Preview' class='btn btn-sm'><i class='fa fa-eye'> </i></a>";

	$data[] = array(
		"no"=>$no,
		"ws"=>htmlspecialchars($row['ws']),
		"color"=>htmlspecialchars($row['color']),
		"username"=>htmlspecialchars($row['created_by']),
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