<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
//print_r($_POST);
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = "id_collect"; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(SELECT * FROM cut_out_detail_temporary) AS X";

// echo('hello world');die();

### Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (
   		X.Supplier			LIKE'%".$searchValue."%'
		OR X.styleno		LIKE'%".$searchValue."%'
		OR X.kpno			LIKE'%".$searchValue."%'
		OR X.so_no			LIKE'%".$searchValue."%'
		OR X.buyerno		LIKE'%".$searchValue."%'
		OR X.username		LIKE'%".$searchValue."%'
		OR X.dateinput		LIKE'%".$searchValue."%'
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
$colomn = "X.id_collect
		  ,X.id_cut_out
		  ,X.fabric_code
		  ,X.fabric_desc
		  ,X.id_grouping
		  ,X.id_panel
		  ,X.number_from
		  ,X.number_to
		  ,X.embro
		  ,X.printing
		  ,X.heat_transfer
		  ,X.size
		  ,X.date_detail
		  ,X.cutting_output
		  ,X.reject
		  ,X.ok_cutt";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
// $no = 1;
// echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
	// echo '123';die();
//echo $row['n_post'];

$button = '';		
$button .="<a href='../prod/?mod=3LA&id=$row[id_collect]' style='color: #3c8dbc;' title='delete' class='btn btn-sm' onclick='edit(".'"'.$row['id_cut_out'].'"'.")' ><i class='fa fa-trash'> </i></a>";

	$data[] = array(
		// "no"=>$no,
		"id_cut_out"=>htmlspecialchars($row['id_cut_out']),
		"fabric_code"=>htmlspecialchars($row['fabric_code']),
		"fabric_desc"=>htmlspecialchars($row['fabric_desc']),
		"id_grouping"=>htmlspecialchars($row['id_grouping']),
		"id_panel"=>htmlspecialchars($row['id_panel']),
		"number_from"=>htmlspecialchars($row['number_from']),
		"number_to"=>htmlspecialchars($row['number_to']),
		"embro"=>htmlspecialchars($row['embro']),
		"printing"=>htmlspecialchars($row['printing']),
		"heat_transfer"=>htmlspecialchars($row['heat_transfer']),
		"size"=>htmlspecialchars($row['size']),
		"date_detail"=>htmlspecialchars($row['date_detail']),
		"cutting_output"=>htmlspecialchars($row['cutting_output']),
		"reject"=>htmlspecialchars($row['reject']),
		"ok_cutt"=>htmlspecialchars($row['ok_cutt']),
		"button"=>rawurlencode($button)
	);
	// $no++;
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