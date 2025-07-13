<?php 
include __DIR__ .'/../../../include/conn.php';
include __DIR__ .'/../../forms/fungsi.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];/* 
print_r($_POST); */
	$table = "(SELECT A.type_invoice,A.invno,A.id,A.id_buyer,A.user_insert,A.d_insert,A.user_post,A.d_post,A.user_update,A.d_update,MS.Supplier buyer
 FROM shp_invoice_scrap_header A
INNER JOIN mastersupplier MS ON A.id_buyer = MS.Id_Supplier
WHERE A.type_invoice = '101'
)X
";
	$colomn = " 
		 X.id
		 ,X.invno
		,X.buyer
		,X.user_insert
		,X.d_insert
		,X.user_post
		,X.d_post
		,X.user_update
		,X.d_update
	";
$rowperpage = $_POST['length']; // Rows display per page
//$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = "id"; // Column name
//$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
### Search 
$searchQuery = " ";  
if($searchValue != ''){
	$searchQuery = " AND (
		   X.id					LIKE'%".$searchValue."%'
		OR X.buyer              LIKE'%".$searchValue."%'
		OR X.invno				 LIKE'%".$searchValue."%'
		OR X.user_insert        LIKE'%".$searchValue."%'
		OR X.d_insert           LIKE'%".$searchValue."%'
		OR X.user_post          LIKE'%".$searchValue."%'
		OR X.d_post             LIKE'%".$searchValue."%'
		OR X.user_update        LIKE'%".$searchValue."%'
		OR X.d_update           LIKE'%".$searchValue."%'
		
		
		
		
		
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


$empQuery = "select $colomn from $table WHERE 1 ".$searchQuery;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
/*     echo $empQuery;
 die();   */
$no = 0;
while ($row = mysqli_fetch_assoc($empRecords)) {
	// echo '123';die();
//echo $row['n_post'];
	// $yard = $row['qty'];
	// $roll = $yard / 100;

	$qty_yard = "0";
/* 	if($id_url){
		$qty_yard = $row['qty_yard'];
		$qty_roll = $row['qty_roll'];
	} */
//PdfInvoiceScrap
	$input = '';
	$input .="<a href='?mod=InvocieMaterialForm&id=".$row['id']."' title='Edit' target='_blank'><i class='fa fa-pencil'></i></a>";
	$input .="<a href='PdfInvoiceScrap.php?id=".$row['id']."' title='Print' target='_blank'><i class='fa fa-print'></i></a>";	
	$data[] = array(
		"buyer"=>htmlspecialchars($row['buyer']), //,X.id_item invno
		"user_insert"=>htmlspecialchars($row['user_insert']),
		"d_insert"=>htmlspecialchars($row['d_insert']),
		"user_post"=>htmlspecialchars($row['user_post']),
		"d_post"=>htmlspecialchars($row['d_post']),
		"user_update"=>htmlspecialchars(($row['user_update'])),
		"d_update"=>htmlspecialchars(($row['d_update'])),
		"invno"=>htmlspecialchars($row['invno']),
		"button"=>rawurlencode($input),
		
		
		
		
		
		// "qty_header"=>htmlspecialchars($row['qty_header'])
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