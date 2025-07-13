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
		ci.*,ac.kpno,ac.styleno
	FROM prod_cut_in AS ci 
	LEFT JOIN act_costing AS ac ON ac.id=ci.id_act_costing
	#ORDER BY ci.id_cut_in DESC
) AS X";
### Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (
   		X.id_act_costing	LIKE'%".$searchValue."%'
		OR X.kpno			LIKE'%".$searchValue."%'
		OR X.styleno		LIKE'%".$searchValue."%'
		OR X.request_no		LIKE'%".$searchValue."%'
		OR X.username		LIKE'%".$searchValue."%'
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
$colomn = "X.id_cut_in
		  ,X.kpno
		  ,X.styleno
		  ,X.request_no
		  ,concat(X.username,' ',X.dateinput) as createdBy";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." ORDER BY X.id_cut_in desc limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();

//echo $empQuery;
$no = 1;
while ($row = mysqli_fetch_assoc($empRecords)) {
	// echo '123';die();
//echo $row['n_post'];

	$button = '';		
	$button .="<a href='../prod/?mod=2LA&id=$row[id_cut_in]' style='color: #3c8dbc;' title='Edit' class='btn btn-sm' onclick='edit(".'"'.$row['id_cut_in'].'"'.")' ><i class='fa fa-pencil'> </i></a>";
	$button .="<a href='../prod/?mod=2LA&id=$row[id_cut_in]&v=1' style='color: #3c8dbc;' title='Preview' class='btn btn-sm' onclick='preview(".'"'.$row['id_cut_in'].'"'.")' ><i class='fa fa-eye'> </i></a>";

	$data[] = array(
		"no"=>$no,
		"kpno"=>htmlspecialchars($row['kpno']),
		"styleno"=>htmlspecialchars($row['styleno']),
		"request_no"=>htmlspecialchars($row['request_no']),
		"username"=>htmlspecialchars($row['createdBy']),
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