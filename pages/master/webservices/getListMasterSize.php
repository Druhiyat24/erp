<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
// echo '123';
// die();
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = 'size'; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(
		  SELECT * FROM mastersize ORDER BY urut asc limit 500
			)X";

			// echo $table;
			// die();


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (

	X.urut       	        	   LIKE'%".$searchValue."%'	
	OR X.size       		  	   LIKE'%".$searchValue."%'
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
			X.urut
			,X.size  
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
// print_r($empRecords);
// die();
$data = array();
// echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {


$button = '';

	
		$button .="<a $cl_ubah href='../master/?mod=25&mode=$mode&id=$row[size]' $tt_ubah 

                    data-toggle='tooltip' ><i class='fa fa-pencil'></i>

                  </a>";
		$button .="<a $cl_hapus href='d_size.php?mod=25&mode=$mode&id=$row[size]' $tt_hapus 

                   data-toggle='tooltip' ><i class='fa fa-trash'></i>

                   </a>";		
	
           $data[] = array(
			"urut"=>htmlspecialchars($row['urut']),  
			"size"=>htmlspecialchars($row['size']),
			"button"=>rawurlencode($button)

			   );
		
}

// echo $row['n_post'];



// }
## Response
$response = array(
  "draw" => intval($draw),
  "iTotalRecords" => $totalRecordwithFilter,
  "iTotalDisplayRecords" => $totalRecords,
  "aaData" => $data
);
echo json_encode($response);
?>