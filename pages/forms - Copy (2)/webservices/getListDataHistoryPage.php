<?php 
	function d_from($from){
		$d_from = explode("/",$from."/01");
		$d_from = $d_from[1]."-".$d_from[0]."-".$d_from[2];
		return $d_from;
	}
	function d_to($to){
		$d_to = explode("/",$to."/01");
		$d_to = $d_to[1]."-".$d_to[0]."-".$d_to[2];
		$d_to = date("Y-m-d", strtotime("1 month",strtotime($d_to)));
		$d_to = date("Y-m-d", strtotime("-1 days",strtotime($d_to)));
		return $d_to;
	}



include __DIR__ .'/../../../include/conn.php';
## Read value
$data = $_GET;
$d_from = $d_from =date("Y-m-d", strtotime($data['from']));
$d_to = $d_to =date("Y-m-d", strtotime($data['to']));
/*
print_r($_POST);
die();  */
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = "X.Trans_Date"; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(
SELECT Trans_Date,Trans_Desc,Trans_Host FROM act_hist WHERE (Trans_Date) >= '$d_from 00:00:00' AND (Trans_Date) <='$d_to 23:59:50'
	
)X";
## Search
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (


	   			 X.Trans_Date			LIKE'%".$searchValue."%'
				OR X.Trans_Desc			LIKE'%".$searchValue."%'
				OR X.Trans_Host			LIKE'%".$searchValue."%'
	

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
			
				 X.Trans_Date
				,X.Trans_Desc
				,X.Trans_Host
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by ".$columnName." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
/*  echo $empQuery;
die(); */ 
while ($row = mysqli_fetch_assoc($empRecords)) {

   $data[] = array(
"Trans_Date"=>htmlspecialchars($row['Trans_Date']),   //ok             
"Trans_Desc"=>htmlspecialchars($row['Trans_Desc']), //ok
"Trans_Host"=>htmlspecialchars($row['Trans_Host']), //ok
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