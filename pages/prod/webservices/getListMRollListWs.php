<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = "id_cut_in"; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(
	SELECT 
		mr.id_m_roll,
		mr.id_cost,
		ac.ws,
		ac.styleno,
		CONCAT(mr.username, ' ', mr.dateinput) AS created_by
	FROM prod_m_roll AS mr
	INNER JOIN (
		SELECT 
			ac.id,
			ac.kpno AS ws,
			ac.styleno
		FROM act_costing AS ac
	) AS ac ON ac.id = mr.id_cost
) AS X";
### Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (
   		X.ws				LIKE'%".$searchValue."%'
		OR X.styleno		LIKE'%".$searchValue."%'
		OR X.created_by		LIKE'%".$searchValue."%'
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
$colomn = "X.id_m_roll
		  ,X.id_cost
		  ,X.ws
		  ,X.styleno
		  ,X.created_by";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." ORDER BY X.id_m_roll desc limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();

// echo $empQuery;die();
$no = 1;
while ($row = mysqli_fetch_assoc($empRecords)) {
	// echo '123';die();
//echo $row['n_post'];

	$button = '';		
	$button .="<a href='../prod/?mod=mRollForm&id=$row[id_m_roll]' style='color: #3c8dbc;' title='Edit' class='btn btn-sm' onclick='Edit(".'"'.$row['id_m_roll'].'"'.")' ><i class='fa fa-pencil'> </i></a>";
	// $button .="<a href='../prod/?mod=2LA&id=$row[id_mark_entry]' style='color: #3c8dbc;' title='Edit' class='btn btn-sm' onclick='edit(".'"'.$row['id_mark_entry'].'"'.")' ><i class='fa fa-pencil'> </i></a>";

	$data[] = array(
		"no"=>$no,
		"ws"=>htmlspecialchars($row['ws']),
		"styleno"=>htmlspecialchars($row['styleno']),
		"created_by"=>htmlspecialchars($row['created_by']),
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