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
          s.nama_sub_group,' ',d.nama_type,' ',e.nama_contents) tampil
          ,f.* FROM 
          mastergroup a inner join mastersubgroup s on a.id=s.id_group
          inner join mastertype2 d on s.id=d.id_sub_group
          inner join mastercontents e on d.id=e.id_type
          inner join masterwidth f on e.id=f.id_contents 
		  WHERE f.aktif = 'Y'
          ORDER BY e.id ASC
			)X";

			// echo $table;
			// die();


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (
	X.id                       LIKE'%".$searchValue."%'	
	OR X.tampil                LIKE'%".$searchValue."%'	
	OR X.kode_width       	   LIKE'%".$searchValue."%'
	OR X.nama_width            LIKE'%".$searchValue."%'
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
			,X.kode_width                  	
			,X.nama_width 
			,X.aktif
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
/* print_r($empQuery);
die(); */
$data = array();
$tt_ubah = "Ubah";
$tt_hapus = "Hapus";
// echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {


$button = '';

	
		$button .="<a $cl_ubah href='../master/?mod=7&mode=$mode&id=$row[id]' $tt_ubah 

                    data-toggle='tooltip' ><i class='fa fa-pencil'></i>

                  </a>";
		$button .="<a $cl_hapus href='d_width.php?mod=7&mode=$mode&id=$row[id]' $tt_hapus 

                   data-toggle='tooltip' ><i class='fa fa-trash'></i>

                   </a>";	

			if($row["aktif"] == 'Y'){
				$button .="<a  href='dea_group.php?mod=7&mode=$mode&id=$row[id]&is_active=N&part=width' data-original-title='Inactive'
						data-toggle='tooltip' ><i class='fa fa-ban'></i>
						</a>";				
			}else{
				$button .="<a  href='dea_group.php?mod=7&mode=$mode&id=$row[id]&is_active=Y&part=width' data-original-title='Inactive'
						data-toggle='tooltip' ><i class='fa fa-box-open'></i>
						</a>";					
			}				   
	
           $data[] = array(
			"id"=>htmlspecialchars($row['id']),  
			"tampil"=>htmlspecialchars($row['tampil']),  
			"kode_width"=>htmlspecialchars($row['kode_width']),
			"nama_width"=>htmlspecialchars($row['nama_width']),
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