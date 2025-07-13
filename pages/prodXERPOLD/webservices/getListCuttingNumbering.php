<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
//print_r($_POST);
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = "X.id_cut_number"; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(
	
	SELECT 
		cn.id_cut_number,
		cn.id_cost,
		cn.color,
		cn.no_cut_number,
		CONCAT(cn.username_create, ' ', cn.dateinput_create) AS created_by,
		ac.ws,
		ac.styleno,
		ac.buyer
	FROM prod_cut_number AS cn
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
	) AS ac ON ac.id = cn.id_cost

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
	X.id_cut_number,	
	X.id_cost,
	X.no_cut_number,
	X.buyer,
	X.ws,
	X.styleno,
	X.color,
	X.created_by
";

$empQuery = "SELECT $colomn FROM$table WHERE 1 ORDER BY $columnName DESC ".$searchQuery." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
// echo $empQuery;die();
$no = 1;
while ($row = mysqli_fetch_assoc($empRecords)) {
	// echo '123';die();
//echo $row['n_post'];

	$button = '';		
	$button .="<a href='../prod/?mod=numCutForm&id=$row[id_cut_number]' style='color: #3c8dbc;' title='Edit' class='btn btn-sm' onclick='edit(".'"'.$row['id_cut_number'].'"'.")' ><i class='fa fa-pencil'> </i></a>";
	// $button .="<a href='../prod/?mod=3LA&id=$row[id_cut_out]&v=1' style='color: #3c8dbc;' title='Preview' class='btn btn-sm'><i class='fa fa-eye'> </i></a>";

	$data[] = array(
		"no"=>$no,
		"no_cut_number"=>htmlspecialchars($row['no_cut_number']),
		"buyer"=>htmlspecialchars($row['buyer']),
		"ws"=>htmlspecialchars($row['ws']),
		"style"=>htmlspecialchars($row['styleno']),
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