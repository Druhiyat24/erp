<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];

// echo '123';
// die();
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = 'id_defect'; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(
		 	select * from master_defect order by id_defect desc
			)X";

			// echo $table;
			// die();


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (
	X.id_defect	        	           LIKE'%".$searchValue."%'	
	OR X.jenis_defect      		  	   LIKE'%".$searchValue."%'
	OR X.mattype      		   		   LIKE'%".$searchValue."%'
	OR X.kode_defect		           LIKE'%".$searchValue."%'
	OR X.nama_defect		      	   LIKE'%".$searchValue."%'
	OR X.kode_posisi	        	   LIKE'%".$searchValue."%'
	OR X.nama_posisi	        	   LIKE'%".$searchValue."%'
	OR X.remark	        	   	 	   LIKE'%".$searchValue."%'
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
			X.id_defect
			,X.jenis_defect  
			,X.mattype  
			,X.kode_defect
			,X.nama_defect
			,X.kode_posisi
			,X.nama_posisi
			,X.remark
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
// print_r($empRecords);
// die();
$data = array();
// echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {


$button = '';

	
		$button .="<a $cl_ubah href='../master/?mod=24&mode=$mode&id=$row[id_defect]' $tt_ubah

                    data-toggle='tooltip' ><i class='fa fa-pencil'></i>

                  </a>";
		$button .=" <a $cl_hapus href='d_defect.php?mod=24&mode=$mode&id=$row[id_defect]' 

                   data-toggle='tooltip' ><i class='fa fa-trash'></i>

                   </a>";	           	
     
	
           $data[] = array(
           	"id_defect"=>htmlspecialchars($row['id_defect']),
			"jenis_defect"=>htmlspecialchars($row['jenis_defect']),	
			"mattype"=>htmlspecialchars($row['mattype']),	
			"kode_defect"=>htmlspecialchars($row['kode_defect']),
			"nama_defect"=>htmlspecialchars($row['nama_defect']),
			"kode_posisi"=>htmlspecialchars($row['kode_posisi']),
			"nama_posisi"=>htmlspecialchars($row['nama_posisi']),
			"remark"=>htmlspecialchars($row['remark']),
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