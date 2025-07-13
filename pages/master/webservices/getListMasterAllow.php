<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
// echo '123';
// die();
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = 'id'; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(
		 SELECT concat(a.nama_group,' ',s.nama_sub_group) tampil
          ,d.* FROM 
          mastergroup a inner join mastersubgroup s on a.id=s.id_group
          inner join masterallow d on s.id=d.id_sub_group 
          ORDER BY d.id DESC limit 500
			)X";

			// echo $table;
			// die();


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (

	X.id        	        	   LIKE'%".$searchValue."%'	
	OR X.tampil       		  	   LIKE'%".$searchValue."%'
	OR X.qty1       		  	   LIKE'%".$searchValue."%'
	OR X.qty2       		  	   LIKE'%".$searchValue."%'
	OR X.allowance       		   LIKE'% ".$searchValue."%'
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
			X.id
			,X.tampil  
			,X.qty1  
			,X.qty2  
			,X.allowance  
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
// print_r($empRecords);
// die();
$data = array();
// echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {


$button = '';

	
		$button .="<a $cl_ubah href='../master/?mod=22&mode=$mode&id=$row[id]' $tt_ubah 

                    data-toggle='tooltip' ><i class='fa fa-pencil'></i>

                  </a>";
		$button .="<a $cl_hapus href='d_allow.php?mod=22&mode=$mode&id=$row[id]' $tt_hapus 

                   data-toggle='tooltip' ><i class='fa fa-trash'></i>

                   </a>";		
	
           $data[] = array(
           	"id"=>htmlspecialchars($row['id']),
			"tampil"=>htmlspecialchars($row['tampil']),
			"qty1"=>htmlspecialchars(number_format((float)$row['qty1'], 2, '.', ',')),  
			"qty2"=>htmlspecialchars(number_format((float)$row['qty2'], 2, '.', ',')),			
			"allowance"=>htmlspecialchars($row['allowance']),
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