
<?php 
include __DIR__ .'/../../../include/conn.php';
include __DIR__ .'/../../forms/journal_interface.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(
SELECT BPB.bpbno_int
		,ifnull(BPB.pono,POH_WIP.pono)pono
		,ifnull(POH.podate,POH_WIP.podate)podate
		,BPB.id_jo
		,BPB.bpbdate
		,BPB.id_item
		,BPB.is_cancel 
		,BPB.confirm
		,MAX(ACT.kpno) kpno
		,KB.id_journal
		,if(KB.id_journal IS NULL AND BPB.is_cancel='N' AND BPB.confirm='Y',1,0)key_cancel
		FROM bpb BPB
		LEFT JOIN(
			SELECT pono,podate FROM po_header
		)POH ON BPB.pono = POH.pono
		LEFT JOIN 
			(SELECT id_jo,id_so FROM jo_det)JOD ON BPB.id_jo = JOD.id_jo
		LEFT JOIN(
			SELECT id,id_cost FROM so)SO ON JOD.id_so = SO.id
			LEFT JOIN(
				SELECT id_po,id_gen,id_jo FROM po_item WHERE cancel !='Y'
			)POI_WIP ON BPB.id_jo = POI_WIP.id_jo AND BPB.id_item = POI_WIP.id_gen
			LEFT JOIN(
				SELECT id,pono,podate FROM po_header
			)POH_WIP ON POH_WIP.id=POI_WIP.id_po
		LEFT JOIN(
			SELECT kpno,id FROM act_costing			
		)ACT ON SO.id_cost = ACT.id

		LEFT JOIN(
			SELECT id_journal,reff_doc FROM fin_journal_d WHERE id_journal LIKE '%-PK-%'
			AND id_journal  IN(SELECT v_nojournal FROM fin_status_journal_ap WHERE v_source = 'KB')
			
		)KB ON BPB.bpbno_int = KB.reff_doc
		WHERE 1 = 1
			GROUP BY BPB.bpbno_int
		)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (
 X.bpbno_int      		LIKE '%".$searchValue."%'
OR X.bpbdate      			LIKE '%".$searchValue."%'
OR X.pono      				LIKE '%".$searchValue."%'
OR X.podate      			LIKE '%".$searchValue."%'
OR X.kpno      				LIKE '%".$searchValue."%'












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
$colomn = "		 X.bpbno_int
				 ,X.bpbdate
				 ,X.pono
				 ,X.podate
				 ,X.kpno
				 ,X.key_cancel
				 ";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
//echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];

		$button = '';
		if($row['key_cancel'] == '1'){
		
			$button .=" <a href='#' class='btn btn-warning' onclick='cancel(".'"'.$row['bpbno_int'].'"'.")' ><i class='fa fa-close'> </i></a>";	
		}
   $data[] = array( 
"bpbno_int"=>$row['bpbno_int'],
"bpbdate"=>$row['bpbdate'],
"pono"=>$row['pono'],
"podate"=>$row['podate'],
"kpno"=>$row['kpno'],
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