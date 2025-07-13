<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
//print_r($_POST);
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = "id_cut_qc"; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(
	
	SELECT 
		cq.id_cut_qc,
		cq.id_cost,
		cq.color,
		cq.no_cut_qc,
		CONCAT(cq.username_create, ' ', cq.dateinput_create) AS created_by,
		ac.buyer,
		ac.styleno,
		ac.kpno AS ws
	FROM prod_cut_qc AS cq
	INNER JOIN (
		SELECT 
			ac.id,
			ac.kpno,
			ac.styleno,
			ac.id_buyer,
			sup.Supplier AS buyer
		FROM act_costing AS ac
		INNER JOIN mastersupplier AS sup ON sup.Id_Supplier = ac.id_buyer
	) AS ac ON ac.id = cq.id_cost

) AS X";
 
### Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (
   		X.buyer			LIKE'%".$searchValue."%'
		OR X.styleno	LIKE'%".$searchValue."%'
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
$colomn = "
	X.id_cut_qc,
	X.id_cost,
	X.buyer,
	X.styleno,
	X.ws,
	X.color,
	X.no_cut_qc,
	X.created_by
";

$empQuery = "select $colomn  from $table WHERE 1 ORDER BY $columnName DESC ".$searchQuery." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
// echo $empQuery;die();
$no = 1;
while ($row = mysqli_fetch_assoc($empRecords)) {
	// echo '123';die();
//echo $row['n_post'];

	$button = '';		
	$button .="<a href='../prod/?mod=cutQCForm&id=$row[id_cut_qc]' style='color: #3c8dbc;' title='Edit' class='btn btn-sm' onclick='edit(".'"'.$row['id_cut_qc'].'"'.")' ><i class='fa fa-pencil'> </i></a>";
	// $button .="<a href='../prod/?mod=3LA&id=$row[id_cut_out]&v=1' style='color: #3c8dbc;' title='Preview' class='btn btn-sm'><i class='fa fa-eye'> </i></a>";

	$data[] = array(
		"no"=>$no,
		"no_cut_qc"=>htmlspecialchars($row['no_cut_qc']),
		"buyer"=>htmlspecialchars($row['buyer']),
		"ws"=>htmlspecialchars($row['ws']),
		"styleno"=>htmlspecialchars($row['styleno']),
		"color"=>htmlspecialchars($row['color']),
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