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
			SELECT s.nama_group,a.*,s.aktif aktif_subgroup FROM 
          mastersubgroup a inner join mastergroup s on a.id_group=s.id 
		  WHERE a.aktif = 'Y'
          ORDER BY a.id DESC
			)X";

			// echo $table;
			// die();


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (
	X.id                          LIKE'%".$searchValue."%'	
	OR X.nama_group               LIKE'%".$searchValue."%'	
	OR X.kode_sub_group		      LIKE'%".$searchValue."%'
	OR X.nama_sub_group		      LIKE'%".$searchValue."%'
	OR X.id_coa_d				  LIKE'%".$searchValue."%'
	OR X.id_coa_k		          LIKE'%".$searchValue."%'
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
			,X.nama_group                      	
			,X.kode_sub_group                  	
			,X.nama_sub_group 
			,X.id_coa_d
			,X.id_coa_k
			,X.aktif_subgroup   				
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
// print_r($empRecords);
// die();
$data = array();
// echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {


$button = '';

	
		$button .="<a $cl_ubah href='../master/?mod=4&mode=$mode&id=$row[id]' $tt_ubah 

                    data-toggle='tooltip' ><i class='fa fa-pencil'></i>

                  </a>";
		$button .="<a $cl_hapus href='d_subgrp.php?mod=4&mode=$mode&id=$row[id]' $tt_hapus 

                   data-toggle='tooltip' ><i class='fa fa-trash'></i>

                   </a>";	

			if($row["aktif_subgroup"] == 'Y'){
				$button .="<a $cl_hapus href='dea_group.php?mod=4&mode=$mode&id=$row[id]&is_active=N&part=subgroup' data-original-title='Inactive'
						data-toggle='tooltip' ><i class='fa fa-ban'></i>
						</a>";				
			}else{
				$button .="<a $cl_hapus href='dea_group.php?mod=4&mode=$mode&id=$row[id]&is_active=Y&part=subgroup' data-original-title='Inactive'
						data-toggle='tooltip' ><i class='fa fa-box-open'></i>
						</a>";					
			}				   
	
           $data[] = array(
			"id"=>htmlspecialchars($row['id']),  
			"nama_group"=>htmlspecialchars($row['nama_group']),  
			"kode_sub_group"=>htmlspecialchars($row['kode_sub_group']),
			"nama_sub_group"=>htmlspecialchars($row['nama_sub_group']),
			"id_coa_d"=>htmlspecialchars($row['id_coa_d']),
			"id_coa_k"=>htmlspecialchars($row['id_coa_k']),
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