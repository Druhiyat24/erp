
<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(
SELECT IJ.id_journal penjualan
		,IJ.reff_doc invoice
		,AR.id_rekap rekapar
		,if(ALL_AR.id_journal IS NOT NULL,ALL_AR.reff_doc,PN.id_journal)penerimaan
		,ALL_AR.id_journal allokasiar
			FROM fin_journal_h IJ
	LEFT JOIN(
		SELECT id_journal
						,id_rekap
						FROM fin_status_journal_ar
	)AR ON IJ.id_journal = AR.id_journal
	LEFT JOIN(
		SELECT id_journal,reff_doc2 FROM fin_journal_h WHERE type_journal = '13'
	)PN ON PN.reff_doc2 = AR.id_rekap
	LEFT JOIN(
		SELECT id_journal,reff_doc,reff_doc2 FROM fin_journal_h WHERE type_journal = '4'
	)ALL_AR ON ALL_AR.reff_doc2 = AR.id_rekap
			WHERE IJ.type_journal = '1'

/* PEMBAYARAN */
			)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (
   X.penjualan		LIKE '%".$searchValue."%'
OR X.invoice		LIKE '%".$searchValue."%'
OR X.rekapar		LIKE '%".$searchValue."%'
OR X.penerimaan		LIKE '%".$searchValue."%'
OR X.allokasiar 	LIKE '%".$searchValue."%'
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
$colomn = "		  X.penjualan	
				 ,X.invoice	
				 ,X.rekapar	
				 ,X.penerimaan	
				 ,X.allokasiar 
				 ";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery."  limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
//echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];

/* 		$button = '';
		if($row['n_post'] != 2){
			$button .= "<a href='#' class='btn btn-primary' onclick='edit(".'"'.$row['n_id'].'"'.")' ><i class='fa fa-pencil'> </i></a>";
			$button .= "<a href='#' class='btn btn-info' onclick='send(".'"'.$row['n_idinvoiceheader'].'",'.'"INV"'.")' ><i class='fa fa-send'> </i></a>";			
			$button .=" <a href='#' class='btn btn-warning' onclick='print(".'"'.$row['n_id'].'",'.'"'.$row['n_typeinvoice'] .'"'.")' ><i class='fa fa-file-pdf-o'> </i></a>";	
		}else{
			$button .=" <a href='#' class='btn btn-warning' onclick='print(".'"'. $row['n_id'] .'",'.'"'. $row['n_typeinvoice'] .'"'.")' ><i class='fa fa-file-pdf-o'> </i></a>";	jp			
		}	 */
		
	$button_penjualan	 ='N/A';
	$button_rekapar	     ='N/A';
	$button_penerimaan   ='N/A';
	$button_allokasiar   ='N/A';
	if(ISSET($row['penjualan']) OR !EMPTY($row['penjualan'])){
		$button_penjualan ="<a href='?mod=js&id=".$row['penjualan']."'  target='_blank'>".$row['penjualan']."</a>";
	}
	if(ISSET($row['rekapar']) OR !EMPTY($row['rekapar'])){
		$button_rekapar ="<a href='?mod=rekar&view=1&id=".$row['rekapar']."'   target='_blank'>".$row['rekapar']."</a>";		
	}
	if(ISSET($row['penerimaan']) OR !EMPTY($row['penerimaan'])){
		$button_penerimaan ="<a href='?mod=jrcp&id=".$row['penerimaan']."' target='_blank'>".$row['penerimaan']."</a>";		
	}	
	if(ISSET($row['allokasiar']) OR !EMPTY($row['allokasiar'])){
		$button_allokasiar ="<a href='?mod=jallocar&id=".$row['allokasiar']."'  target='_blank'>".$row['allokasiar']."</a>";		
	}		
   $data[] = array( 
"penjualan"=>rawurlencode($button_penjualan),
"invoice"=>$row['invoice'],
"rekapar"=>rawurlencode($button_rekapar),
"penerimaan"=>rawurlencode($button_penerimaan),
"allokasiar"=>rawurlencode($button_allokasiar),
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