<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = "id_dc_join"; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(
	
	SELECT 
		dc.id_dc_join,
		dc.id_cost,
		dc.no_dc_join,
		ac.buyer,
		ac.styleno,
		ac.ws,
		CONCAT(dc.username_create, ' ', dc.dateinput_create) AS created_by
	FROM prod_dc_join AS dc
	INNER JOIN (
		SELECT 
			ac.id,
			ac.kpno AS ws, 
			ac.styleno, 
			ac.id_buyer,
			buy.buyer
		FROM act_costing AS ac
		INNER JOIN (
			SELECT 
				buy.Id_Supplier AS id_buyer,
				buy.Supplier AS buyer, 
				buy.tipe_sup
			FROM mastersupplier AS buy
			WHERE buy.tipe_sup = 'C'
		) AS buy ON buy.id_buyer = ac.id_buyer
	) AS ac ON ac.id = dc.id_cost
	ORDER BY dc.id_dc_join DESC

) AS X";
### Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (
   		X.no_dc_join		LIKE'%".$searchValue."%'
		OR X.buyer			LIKE'%".$searchValue."%'
		OR X.ws				LIKE'%".$searchValue."%'
		OR X.styleno		LIKE'%".$searchValue."%'
		OR X.created_by		LIKE'%".$searchValue."%'
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
	X.id_dc_join,
	X.no_dc_join,
	X.buyer,
	X.styleno,
	X.ws,
	X.created_by
";

$empQuery = "SELECT $colomn FROM $table WHERE 1 ".$searchQuery." ORDER BY $columnName DESC limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();

// echo $empQuery;die();
$no = 1;
while ($row = mysqli_fetch_assoc($empRecords)) {
	// echo '123';die();
//echo $row['n_post'];

	$button = '';		
	$button .="<a href='../prod/?mod=6LA&id=$row[id_dc_join]' style='color: #3c8dbc;' title='Edit' class='btn btn-sm' onclick='edit(".'"'.$row['id_dc_join'].'"'.")' ><i class='fa fa-pencil'> </i></a>";
	// $button .="<a href='../prod/?mod=SecondaryInputForm&id=$row[id_sec_in]&v=1' style='color: #3c8dbc;' title='Preview' class='btn btn-sm' onclick='preview(".'"'.$row['id_sec_in'].'"'.")' ><i class='fa fa-eye'> </i></a>";

	$data[] = array(
		"no"=>$no,
		"dc_join"=>htmlspecialchars($row['no_dc_join']),
		"buyer"=>htmlspecialchars($row['buyer']),
		"styleno"=>htmlspecialchars($row['styleno']),
		"ws"=>htmlspecialchars($row['ws']),
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