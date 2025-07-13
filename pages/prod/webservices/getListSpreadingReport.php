<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = "X.id_mark_entry DESC"; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(
	SELECT 
		A.id_mark_entry,
		A.id_cost,
		A.username AS created_by,
		A.dateinput,
		A.isDelete,
		E.Supplier buyer,
		D.styleno,
		D.kpno ws,
		S.id AS id_so
	FROM prod_mark_entry AS A
	INNER JOIN prod_mark_entry_group AS B ON A.id_mark_entry = B.id_mark_entry
	INNER JOIN prod_mark_entry_detail AS C ON A.id_mark_entry = C.id_mark
	INNER JOIN act_costing AS D ON A.id_cost = D.id
	INNER JOIN mastersupplier AS E ON D.id_buyer = E.Id_Supplier
	INNER JOIN (
		SELECT 
			S.id,
			S.id_cost
		FROM so AS S
		INNER JOIN so_det AS SD ON SD.id_so = S.id
		WHERE S.cancel_h = 'N' AND SD.cancel = 'N'
		GROUP BY S.id
	) AS S ON S.id_cost = D.id
	WHERE A.isDelete = '0' GROUP BY A.id_mark_entry
) AS X";
### Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (
   		X.buyer				LIKE'%".$searchValue."%'
		OR X.styleno		LIKE'%".$searchValue."%'
		OR X.ws				LIKE'%".$searchValue."%'
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
$colomn = "
	X.id_mark_entry,
	X.id_cost,
	X.buyer,
	X.styleno,
	X.ws,
	X.id_so,
	X.created_by
";

$empQuery = "SELECT $colomn FROM $table WHERE 1 ".$searchQuery." ORDER BY $columnName limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();

// echo $empQuery;
$no = 1;
while ($row = mysqli_fetch_assoc($empRecords)) {
	// echo '123';die();
//echo $row['n_post'];

	$button = '';		
	// $button .="<a href='../prod/?mod=2LA&id=$row[id_mark_entry]' style='color: #3c8dbc;' title='Edit' class='btn btn-sm' onclick='edit(".'"'.$row['id_mark_entry'].'"'.")' ><i class='fa fa-pencil'> </i></a>";
	$button .="<a href='../prod/?mod=SpreadingReportBySoColor&id=$row[id_mark_entry]&id_so=$row[id_so]' style='color: #3c8dbc;' title='View' class='btn btn-sm' onclick='preview(".'"'.$row['id_mark_entry'].'"'.")' ><i class='fa fa-eye'> </i></a>";

	$data[] = array(
		"no"=>$no,
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