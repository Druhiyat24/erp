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
			SELECT concat(a.nama_group,' ',
          s.nama_sub_group,' ',d.nama_type) tampil
          ,e.* FROM 
          mastergroup a inner join mastersubgroup s on a.id=s.id_group
          inner join mastertype2 d on s.id=d.id_sub_group
          inner join mastercontents e on d.id=e.id_type 
          ORDER BY e.id DESC
			)X";

			// echo $table;
			// die();


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (
	X.id                     	   LIKE'%".$searchValue."%'	
	OR X.tampil               	   LIKE'%".$searchValue."%'	
	OR X.kode_contents     	   	   LIKE'%".$searchValue."%'
	OR X.nama_contents             LIKE'%".$searchValue."%'
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
			,X.kode_contents                  	
			,X.nama_contents        
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
// print_r($empRecords);
// die();
$data = array();
// echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {


$button = '';

	
		$button .="<a $cl_ubah href='../master/?mod=6&mode=$mode&id=$row[id]' $tt_ubah 

                    data-toggle='tooltip' ><i class='fa fa-pencil'></i>

                  </a>";
		$button .="<a $cl_hapus href='d_desc.php?mod=6&mode=$mode&id=$row[id]' $tt_hapus 

                   data-toggle='tooltip' ><i class='fa fa-trash'></i>

                   </a>";
        $button .=" <a href='dea_cont.php?mod=6&mode=$mode&id=$row[id]' 
        			data-toggle='tooltip' title='Inactive'
               		<i class='fa fa-ban'></i>
             	 </a>";	           		
	
           $data[] = array(
			"id"=>htmlspecialchars($row['id']),  
			"tampil"=>htmlspecialchars($row['tampil']),  
			"kode_contents"=>htmlspecialchars($row['kode_contents']),
			"nama_contents"=>htmlspecialchars($row['nama_contents']),
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