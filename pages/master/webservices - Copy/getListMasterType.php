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
          inner join mastertype2 d on s.id=d.id_sub_group 
          ORDER BY d.id DESC
			)X";

			// echo $table;
			// die();


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (
	X.id                  	    LIKE'%".$searchValue."%'	
	OR X.tampil				    LIKE'%".$searchValue."%'	
	OR X.kode_type        		LIKE'%".$searchValue."%'
	OR X.nama_type              LIKE'%".$searchValue."%'
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
			,X.kode_type                  	
			,X.nama_type        
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
// print_r($empRecords);
// die();
$data = array();
// echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {


$button = '';

	
		$button .="<a $cl_ubah href='../master/?mod=5&mode=$mode&id=$row[id]' $tt_ubah 

                    data-toggle='tooltip' ><i class='fa fa-pencil'></i>

                  </a>";
		$button .="<a $cl_hapus href='d_type.php?mod=5&mode=$mode&id=$row[id]' $tt_hapus 

                   data-toggle='tooltip' ><i class='fa fa-trash'></i>

                   </a>";		
	
           $data[] = array(
			"id"=>htmlspecialchars($row['id']),  
			"tampil"=>htmlspecialchars($row['tampil']),  
			"kode_type"=>htmlspecialchars($row['kode_type']),
			"nama_type"=>htmlspecialchars($row['nama_type']),
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