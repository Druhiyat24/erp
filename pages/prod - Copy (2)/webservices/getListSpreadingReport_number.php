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
$id = $_GET["id"];
$id_so = $_GET["id_so"];
$color = $_GET["color"];
$table = "(
SELECT 
	 A.id_number
	,A.id_mark_entry
	,A.id_cost
	,A.id_number_group
	,A.username
	,A.d_insert
	,A.isDelete
	,A.id_internal
	,B.kpno ws
	,C.so_no 
	,A.color
	,B.styleno style
	,D.Supplier buyer
FROM prod_spread_report_number 


A

	INNER JOIN act_costing B ON A.id_cost = B.id
	INNER JOIN so C ON C.id = A.id_so 
	INNER JOIN mastersupplier D ON B.id_buyer = D.Id_Supplier
WHERE A.id_mark_entry ='{$id}' AND A.id_so = '{$id_so}' AND A.color='{$color}'
) AS X";
### Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (
		OR X.id_number_group        LIKE'%".$searchValue."%'
		OR X.ws               LIKE'%".$searchValue."%'
		OR X.buyer               LIKE'%".$searchValue."%'
		OR X.so_no               LIKE'%".$searchValue."%'
		OR X.color               LIKE'%".$searchValue."%'
		OR X.style               LIKE'%".$searchValue."%'
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
		X.id_number_group
        ,X.ws   
		,X.id_internal
		,X.id_number
		,X.buyer
		,X.so_no
		,X.color
		,X.style
";

$empQuery = "select $colomn  from $table WHERE 1 ORDER BY X.id_mark_entry desc limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();

 //echo $empQuery;
$no = 1;
while ($row = mysqli_fetch_assoc($empRecords)) {
	// echo '123';die();
//echo $row['n_post'];

	$button 					 = '';	
/* echo $_on_click;
die(); */
	
	$button .="<a href='../prod/?mod=SpreadingReportForm&id_number=$row[id_number]&color=$row[color]' style='color: #3c8dbc;' title='View' class='btn btn-sm'><i class='fa fa-eye'> </i></a>";
	$button .="<a href='#' data-format='2' data-spr_number='".$row['id_internal']."' data-id_number='".$row['id_number']."' style='color: #3c8dbc;' data-toggle='modal' data-target='#myModal2' onclick='edit_nomor_spreading(this)' title='Edit' class='btn btn-sm'><i class='fa fa-pencil'> </i></a>";
// echo $_on_click;	

	$data[] = array(
		"ws"=>htmlspecialchars($row['ws']),
		"spreding_number"=>htmlspecialchars($row['id_internal']),
		"buyer"=>htmlspecialchars($row['buyer']),
		"so_no"=>htmlspecialchars($row['so_no']),
		"color"=>htmlspecialchars($row['color']),
		"style"=>htmlspecialchars($row['style']),
		"button"=>rawurlencode($button)
	);
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