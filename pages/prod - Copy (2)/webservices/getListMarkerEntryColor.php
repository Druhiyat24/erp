<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$id_url = $_GET['id'];

// print_r($id_url);die();

$cost = mysqli_query($conn_li,"SELECT me.id_cost AS id, med.id_panel AS id_panel FROM prod_mark_entry AS me LEFT JOIN prod_mark_entry_detail med ON me.id_mark_entry=med.id_mark WHERE me.id_mark_entry = '$id_url'");
$ws = mysqli_fetch_assoc($cost);
$id_cost = $ws['id'];
//$id_panel = $ws['id_panel'];

// print_r($id_cost);die();


$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = "id"; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(
	SELECT 
		ac.id,
		ac.kpno AS ws,
		s.id_so,
		s.id_so_det,
		s.color,
		me.id_mark_entry
	FROM act_costing AS ac
	INNER JOIN (
		SELECT 
			s.id AS id_so,
			s.id_cost,
			sd.id_so_det,
			sd.color
		FROM so AS s
		INNER JOIN (
			SELECT 
				sd.id AS id_so_det,
				sd.id_so,
				sd.color 
			FROM so_det AS sd
			WHERE sd.cancel = 'N'
		) AS sd ON sd.id_so = s.id
		WHERE s.id_cost = '{$id_cost}'
		GROUP BY sd.color
	) AS s ON s.id_cost = ac.id
	INNER JOIN (
		SELECT 
			me.id_mark_entry,
			me.id_cost 
		FROM prod_mark_entry AS me
	) AS me ON me.id_cost = ac.id
) AS X";
### Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (
   		X.ws				LIKE'%".$searchValue."%'
		OR X.color			LIKE'%".$searchValue."%'
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
$colomn = "X.id
		  ,X.id_mark_entry
		  ,X.id_so_det
		  ,X.ws
		  ,X.color";

$empQuery = "select $colomn  from $table WHERE 1 ORDER BY X.id_so_det ASC limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();

// echo $empQuery;die();
$no = 1;
while ($row = mysqli_fetch_assoc($empRecords)) {
	// echo '123';die();
//echo $row['n_post'];

	$button = '';		
	// $button .="<a href='../prod/?mod=2LA&id=$row[id_mark_entry]' style='color: #3c8dbc;' title='Edit' class='btn btn-sm' onclick='edit(".'"'.$row['id_mark_entry'].'"'.")' ><i class='fa fa-pencil'> </i></a>";
	// $button .="<a href='../prod/?mod=AL&url=$row[id_mark_entry]&cost=$row[id]&color=$row[color]' style='color: #3c8dbc;' title='View' class='btn btn-sm' onclick='preview(".'"'.$row['id'].'","'.$row['color'].'","'.$row['id_mark_entry'].'"'.")' ><i class='fa fa-eye'> </i></a>";
	$button .="<a href='../prod/?mod=AL&url=$row[id_mark_entry]&cost=$row[id]&color=$row[color]' style='color: #3c8dbc;' title='View' class='btn btn-sm'><i class='fa fa-eye'> </i></a>";
	$button .="<a href='../prod/xlsMarkerEntry.php?id=$row[id]&color=$row[color]&url=$row[id_mark_entry]' style='color: #3c8dbc;' title='Download Excel' class='btn btn-sm'><i class='fa fa-file-excel-o'> </i></a>";

	$data[] = array(
		"no"=>$no,
		"ws"=>htmlspecialchars($row['ws']),
		"color"=>htmlspecialchars($row['color']),
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