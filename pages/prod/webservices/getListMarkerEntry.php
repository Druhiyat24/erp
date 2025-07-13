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
			me.id_mark_entry,
			me.id_cost,
			CONCAT(me.username, ' ', me.dateinput) AS created_by,
			ac.ws,
			ac.styleno,
			ac.buyer
		FROM prod_mark_entry AS me
		INNER JOIN (
			SELECT 
				ac.id,
				ac.kpno AS ws,
				ac.styleno,
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
		) AS ac ON ac.id = me.id_cost
		ORDER BY me.id_mark_entry DESC
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
$colomn = "X.id_mark_entry
		  ,X.id_cost
		  ,X.buyer
		  ,X.styleno
		  ,X.ws
		  ,X.created_by";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." ORDER BY X.id_mark_entry desc limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();

// echo $empQuery;
$no = 1;
while ($row = mysqli_fetch_assoc($empRecords)) {
	// echo '123';die();
//echo $row['n_post'];

	$button = '';		
	// $button .="<a href='../prod/?mod=2LA&id=$row[id_mark_entry]' style='color: #3c8dbc;' title='Edit' class='btn btn-sm' onclick='edit(".'"'.$row['id_mark_entry'].'"'.")' ><i class='fa fa-pencil'> </i></a>";
	$button .="<a href='../prod/?mod=WL&id=$row[id_mark_entry]' style='color: #3c8dbc;' title='View' class='btn btn-sm' onclick='preview(".'"'.$row['id_mark_entry'].'"'.")' ><i class='fa fa-eye'> </i></a>";

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