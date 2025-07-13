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
			SELECT * FROM mastergroup WHERE aktif ='Y' ORDER BY id DESC
			)X";

			// echo $table;
			// die();


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (
	X.id                       LIKE'%".$searchValue."%'	
	OR X.kode_group            LIKE'%".$searchValue."%'	
	OR X.nama_group    		   LIKE'%".$searchValue."%'
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
			,X.kode_group                      	
			,X.nama_group    
			,X.aktif   			
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
// print_r($empRecords);
// die();
$tt_ubah = "Ubah";
$tt_hapus = "Hapus";
$data = array();
// echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {


$button = '';

	
		$button .="<a $cl_ubah href='../master/?mod=3&mode=$mode&id=$row[id]' $tt_ubah 

                    data-toggle='tooltip' ><i class='fa fa-pencil'></i>

                  </a>";  
		$button .="<a $cl_hapus href='d_grp.php?mod=3&mode=$mode&id=$row[id]' $tt_hapus 

                   data-toggle='tooltip' ><i class='fa fa-trash'></i>

                   </a>";

			if($row["aktif"] == 'Y'){
				$button .="<a $cl_hapus href='dea_group.php?mod=3&mode=$mode&id=$row[id]&is_active=N&part=group' data-original-title='Inactive'
						data-toggle='tooltip' ><i class='fa fa-ban'></i>
						</a>";				
			}else{
				$button .="<a $cl_hapus href='dea_group.php?mod=3&mode=$mode&id=$row[id]&is_active=Y&part=group'' data-original-title='Inactive'
						data-toggle='tooltip' ><i class='fa fa-box-open'></i>
						</a>";					
			}
           $data[] = array(
			"id"=>htmlspecialchars($row['id']),  
			"kode_group"=>htmlspecialchars($row['kode_group']),  
			"nama_group"=>htmlspecialchars($row['nama_group']),
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