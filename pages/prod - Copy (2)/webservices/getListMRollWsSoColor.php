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
$id_number = $_GET['id_number'];
$id_so = $_GET['id_so'];
/* echo $_GET['id_so'];
die(); */
$table = "(
	SELECT 
		ac.ws,
		ac.styleno,
		so.so_no,
		so.id id_so,
		so.id,
		srn.id_number,
		srn.color,
		srd.id_item,
		mi.itemdesc
	FROM prod_spread_report_number AS srn
	INNER JOIN (
		SELECT 
			ac.id,
			ac.kpno AS ws,
			ac.styleno
		FROM act_costing AS ac
	) AS ac ON ac.id = srn.id_cost
	INNER JOIN so ON so.id = srn.id_so
	INNER JOIN(SELECT id_number,id_item FROM prod_spread_report_detail GROUP BY id_number)srd ON srn.id_number = srd.id_number
	INNER JOIN masteritem mi ON srd.id_item = mi.id_item
	WHERE  so.id='{$id_so}' GROUP BY id_item
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
$colomn = "
		 
		  X.ws
		  ,X.styleno
		  ,X.id_so
		  ,X.color
		  ,X.itemdesc
		  ,X.id_item
		  ,X.so_no";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery."  limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();

//echo $empQuery;die();
$no = 1;
while ($row = mysqli_fetch_assoc($empRecords)) {
	// echo '123';die();
//echo $row['n_post'];

	$button = '';		
	$button .="<a href='../prod/?mod=mRollForm&id_so=$row[id_so]&color=$row[color]&id_item=$row[id_item]' style='color: #3c8dbc;' title='Edit' class='btn btn-sm' ><i class='fa fa-eye'> </i></a>";
	// $button .="<a href='../prod/?mod=2LA&id=$row[id_mark_entry]' style='color: #3c8dbc;' title='Edit' class='btn btn-sm' onclick='edit(".'"'.$row['id_mark_entry'].'"'.")' ><i class='fa fa-pencil'> </i></a>";

	$data[] = array(
		"no"=>$no,
		"ws"=>htmlspecialchars($row['ws']),
		"styleno"=>htmlspecialchars($row['styleno']),
		"so_no"=>htmlspecialchars($row['so_no']),
		"color"=>htmlspecialchars($row['color']),
		"itemdesc"=>htmlspecialchars($row['itemdesc']),
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