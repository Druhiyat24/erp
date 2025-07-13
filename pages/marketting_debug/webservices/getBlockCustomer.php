<?php 
	function d_from($from){
		$d_from = explode("/",$from."/01");
		$d_from = $d_from[1]."-".$d_from[0]."-".$d_from[2];
		return $d_from;
	}
	function d_to($to){
		$d_to = explode("/",$to."/01");
		$d_to = $d_to[1]."-".$d_to[0]."-".$d_to[2];
		//$d_to = date("Y-m-d", strtotime("1 month",strtotime($d_to)));
		//$d_to = date("Y-m-d", strtotime("-1 days",strtotime($d_to)));
		return $d_to;
	}



include __DIR__ .'/../../../include/conn.php';
## Read value
/*
print_r($data);
die();  */
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
//$columnIndex = $_POST['order'][0]['column']; // Column index
//$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
//$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(
SELECT A.id,A.id_cust,A.b_mkt,A.b_inv,A.b_shp 
			,B.Id_Supplier id_sup,B.supplier_code code_customer,B.Supplier nama_customer
FROM tbl_block_cust A
	INNER JOIN mastersupplier B ON A.id_cust = B.Id_Supplier
		
		
)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (


	X.nama_customer            	  LIKE'%".$searchValue."%'
	OR X.code_customer            	  LIKE'%".$searchValue."%'
)
";
}

## Total number of records without filtering
$sel = mysqli_query($conn_li,"select count(*) allcount from $table");
$records = mysqli_fetch_assoc($sel); 

$totalRecords = $records['allcount'];

## Total number of record with filtering
$sel = mysqli_query($conn_li,"select count(*)  allcount from $table WHERE 1 ".$searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];
## Fetch records
$colomn = "		 
	X.code_customer,
	X.nama_customer,
	X.id,
	X.id_sup,
	X.b_mkt,
	X.b_shp,
	X.b_inv
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery."  limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();

//echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];
$c_mkt = "";
$c_inv = "";
$c_shp = "";
$id_sup = $row['id_sup'];
$ck_mkt = $row['b_mkt'] == '1' ? 'checked':'';
$ck_shp = $row['b_shp'] == '1' ? 'checked':'';
$ck_inv = $row['b_inv'] == '1' ? 'checked':'';
$c_mkt ="<input type='checkbox' onclick='handle(this)' id='mkt_".$id_sup."' ".$ck_mkt.">";
$c_shp ="<input type='checkbox' onclick='handle(this)' id='shp_".$id_sup."' ".$ck_shp.">";
$c_inv ="<input type='checkbox' onclick='handle(this)' id='inv_".$id_sup."' ".$ck_inv.">";


   $data[] = array(            
"code_customer"=>htmlspecialchars($row['code_customer']),
"nama_customer"=>htmlspecialchars($row['nama_customer']),
"id_sup"=>htmlspecialchars($row['id_sup']),
"b_mkt"=>htmlspecialchars($row['b_mkt']),
"b_shp"=>htmlspecialchars($row['b_shp']),
"b_inv"=>htmlspecialchars($row['b_inv']),
"c_mkt"=>rawurlencode($c_mkt),
"c_shp"=>rawurlencode($c_shp),
"c_inv"=>rawurlencode($c_inv),
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