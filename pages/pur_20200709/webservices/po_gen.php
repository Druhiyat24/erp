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
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(
select a.app,a.app_by,a.app_date,a.id,pono,podate,supplier,

          nama_pterms,n_kurs,tmppoit.buyer,tmppoit.t_row,tmppoit.t_rows_cancel 
			,if(t_row > 0
				,if(t_row = t_rows_cancel,'Cancelled','')
				,'')status_po
          from po_header a inner join 

          mastersupplier s on a.id_supplier=s.id_supplier inner join 

          masterpterms d on a.id_terms=d.id

          inner join 

          (select poit.id_jo,poit.id_po,group_concat(distinct reqno) buyer,

            count(*) t_row,sum(if(cancel='Y',1,0)) t_rows_cancel from po_item poit 

            inner join reqnon_header rnh on poit.id_jo=rnh.id  group by poit.id_po) 

          tmppoit on tmppoit.id_po=a.id 

          where a.jenis='N' order by podate desc
)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (

X.id  			LIKE'%".$searchValue."%'
OR X.pono           LIKE'%".$searchValue."%'
OR X.podate        LIKE'%".$searchValue."%'
OR X.supplier       LIKE'%".$searchValue."%'
OR X.nama_pterms    LIKE'%".$searchValue."%'
OR X.buyer          LIKE'%".$searchValue."%'
OR X.n_kurs	     LIKE'%".$searchValue."%'
OR X.status_po	     LIKE'%".$searchValue."%'
OR X.app			 LIKE'%".$searchValue."%'
OR X.app_by			 LIKE'%".$searchValue."%'
OR X.app_date			 LIKE'%".$searchValue."%'

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
			 ,X.pono                	
			,X.podate                 	
			,X.supplier                  	
			,X.nama_pterms              	
			,X.buyer                   
			,X.n_kurs	
			,X.app			 
			,X.app_by		
			,X.status_po								
			,X.app_date		 
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
//echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];
$button = '';
/* echo $row['status_po'];
die(); */
if($row['status_po'] == 'Cancelled' ){
	$button = $row['status_po'];
}else{
	if($row['app'] == 'A' ){
		$button .="<a href='?mod=9z&id=$row[id]'

                    data-toggle='tooltip' title='View'><i class='fa fa-eye'></i>

                  </a>";
		$button .="<a href='pdfPOG.php?id=$row[id]'
                  data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i>
                </a>";				  
                   	
	}else{
		$button .="<a href='?mod=9e&id=$row[id]'

                    data-toggle='tooltip' title='edit'><i class='fa fa-pencil'></i>

                  </a>";

	}
}



   $data[] = array(
"pono"=>htmlspecialchars($row['pono']),        
"podate"=>htmlspecialchars($row['podate']),
"supplier"=>htmlspecialchars($row['supplier']),
"nama_pterms"=>htmlspecialchars($row['nama_pterms']),
"buyer"=>htmlspecialchars($row['buyer']),
"n_kurs"=>htmlspecialchars($row['n_kurs']),
"app"=>htmlspecialchars($row['app']),
"app_by"=>htmlspecialchars($row['app_by']),
"app_date"=>htmlspecialchars($row['app_date']),
"button"=>rawurlencode($button)
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