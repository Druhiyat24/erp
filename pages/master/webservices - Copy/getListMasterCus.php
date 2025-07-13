<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];

// echo '123';
// die();
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = 'Id_Supplier'; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(
		 	SELECT *,if(area='I','Import/Export',
		      if(area='L','Lokal',if(area='F','Factory',area))) areanya 
		      FROM mastersupplier where tipe_sup='C' ORDER BY id_supplier desc
			)X";

			// echo $table;
			// die();


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (
	X.Id_Supplier        	           LIKE'%".$searchValue."%'	
	OR X.supplier_code      		   LIKE'%".$searchValue."%'
	OR X.Supplier      		   		   LIKE'%".$searchValue."%'
	OR X.alamat		        	       LIKE'%".$searchValue."%'
	OR X.areanya		      		   LIKE'%".$searchValue."%'
	OR X.country	        	   	   LIKE'%".$searchValue."%'
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
			X.Id_Supplier
			,X.supplier_code  
			,X.Supplier  
			,X.alamat
			,X.areanya
			,X.country
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
// print_r($empRecords);
// die();
$data = array();
// echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {


$button = '';

	
		$button .="<a href='?mod=20&mode=Customer&id=$row[Id_Supplier]' $tt_ubah 

                    data-toggle='tooltip' ><i class='fa fa-pencil'></i>

                  </a>";
		$button .="<a href='../forms/del_data.php?mod=20&mode=Customer&id=$row[Id_Supplier]'$tt_hapus 

                   data-toggle='tooltip' ><i class='fa fa-trash'></i>

                   </a>";	           	
     
	
           $data[] = array(
           	"Id_Supplier"=>htmlspecialchars($row['Id_Supplier']),
			"supplier_code"=>htmlspecialchars($row['supplier_code']),	
			"Supplier"=>htmlspecialchars($row['Supplier']),	
			"alamat"=>htmlspecialchars($row['alamat']),
			"areanya"=>htmlspecialchars($row['areanya']),
			"country"=>htmlspecialchars($row['country']),
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