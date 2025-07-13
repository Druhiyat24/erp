<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
//print_r($_POST);
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = "id_cut_out"; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(SELECT co.id_cut_out,ms.Supplier,ac.styleno,ac.kpno,co.username,co.dateinput 
FROM cut_out AS co
LEFT JOIN act_costing AS ac ON ac.id=co.id_act_costing
LEFT JOIN mastersupplier AS ms ON ms.Id_Supplier=ac.id_buyer
GROUP BY co.id_cut_out) AS X";
 
### Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (
   		X.Supplier			LIKE'%".$searchValue."%'
		OR X.styleno		LIKE'%".$searchValue."%'
		OR X.kpno			LIKE'%".$searchValue."%'
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
$colomn = "X.id_cut_out
		  ,X.Supplier
		  ,X.styleno
		  ,X.kpno
		  ,concat(X.username,' ',X.dateinput) as createdBy";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
//echo $empQuery;
$no = 1;
while ($row = mysqli_fetch_assoc($empRecords)) {
	// echo '123';die();
//echo $row['n_post'];

$button = '';		
$button .="<a href='../prod/?mod=3LA&id=$row[id_cut_out]' style='color: #3c8dbc;' title='Edit' class='btn btn-sm' onclick='edit(".'"'.$row['id_cut_out'].'"'.")' ><i class='fa fa-pencil'> </i></a>";
$button .="<a href='../prod/?mod=3LA&id=$row[id_cut_out]&v=1' style='color: #3c8dbc;' title='Preview' class='btn btn-sm'><i class='fa fa-eye'> </i></a>";

	$data[] = array(
		"no"=>$no,
		"Supplier"=>htmlspecialchars($row['Supplier']),
		"styleno"=>htmlspecialchars($row['styleno']),
		"kpno"=>htmlspecialchars($row['kpno']),
		"qty"=>htmlspecialchars($row['qty_total']),
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