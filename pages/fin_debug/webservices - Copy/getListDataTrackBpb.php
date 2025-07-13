
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
SELECT GLO.* FROM (
SELECT BELI.reff_doc bpb
		,BELI.id_journal pembelian
		,NULL journal_revers
		,KB.id_journal no_kontrabon
		,NULL kb_reverse
		,AP.v_listcode no_listpayment
		,PAYMENT.id_journal no_pembayaran
		FROM fin_journal_h BELI
	LEFT JOIN(
		SELECT h_kb.id_journal,h_kb.type_journal,kb.bpb_ref FROM fin_journal_h h_kb
			LEFT JOIN (
				SELECT id_journal,reff_doc bpb_ref FROM fin_journal_d WHERE id_journal LIKE '%-PK-%'
					GROUP BY reff_doc
			)kb ON h_kb.id_journal = kb.id_journal
	)KB ON BELI.reff_doc = KB.bpb_ref
	LEFT JOIN(
		SELECT v_listcode,v_nojournal FROM fin_status_journal_ap
		WHERE v_source = 'KB'
	)AP ON KB.id_journal = AP.v_nojournal
	LEFT JOIN(
		SELECT id_journal,reff_doc FROM fin_journal_h WHERE type_journal = '3'
	)PAYMENT ON AP.v_listcode = PAYMENT.reff_doc
	WHERE BELI.type_journal IN('2','17') 
	AND BELI.id_journal NOT IN(SELECT reff_doc2 FROM fin_journal_h WHERE 1=1 AND 
		type_journal = '18'
	)
	UNION ALL
	SELECT 
		 BELI.reff_doc bpb
		,BELI.reff_doc2 pembelian
		,BELI.id_journal journal_revers
		,NULL no_kontrabon
		,NULL kb_reverse
		,NULL no_listpayment
		,NULL no_pembayaran
		FROM fin_journal_h BELI WHERE 1=1 AND type_journal = '18' AND reff_doc2 != 'REV_KB'
		
	UNION ALL

SELECT 

		 BELI.bpb_ref bpb
		,BELI_. pembelian
		,BELI_.journal_revers
		,BELI.reverse_reff no_kontrabon
		,BELI.id_journal kb_reverse
		,NULL no_listpayment
		,NULL no_pembayaran
FROM
(
		SELECT h_kb.id_journal,h_kb.type_journal,kb.bpb_ref, 
			h_kb.reff_doc reverse_reff
			FROM fin_journal_h h_kb
			INNER JOIN (
				SELECT id_journal,reff_doc bpb_ref FROM fin_journal_d WHERE id_journal LIKE '%-RJ-%'
				AND
				reff_doc IS NOT NULL 
			)kb ON h_kb.id_journal = kb.id_journal
			WHERE h_kb.type_journal = '18' AND kb.bpb_ref !='' AND h_kb.reff_doc2='REV_KB'
			GROUP BY kb.bpb_ref)BELI

INNER JOIN (SELECT 
		 reff_doc 
		,reff_doc2 pembelian
		,id_journal journal_revers
		FROM fin_journal_h BELI WHERE 1=1 AND type_journal = '18' AND reff_doc2 != 'REV_KB')BELI_
ON BELI.bpb_ref = BELI_.reff_doc	
)GLO WHERE 1=1
	
		
			)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (
   X.pembelian		LIKE '%".$searchValue."%'
OR X.journal_revers	LIKE '%".$searchValue."%'
OR X.bpb			LIKE '%".$searchValue."%'
OR X.no_kontrabon	LIKE '%".$searchValue."%'
OR X.kb_reverse		LIKE '%".$searchValue."%'
OR X.no_listpayment	LIKE '%".$searchValue."%'
OR X.no_pembayaran  LIKE '%".$searchValue."%'
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
$colomn = "		  X.pembelian
				 ,X.journal_revers
				 ,X.bpb			
				 ,X.no_kontrabon	
				 ,X.kb_reverse
				 ,X.no_listpayment
				 ,X.no_pembayaran
				 ";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." limit ".$row.",".$rowperpage;
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
		
		
		
	$button_pembelian 	= 'N/A';
	$button_revers 	= 'N/A';
	$button_kontrabon 	= 'N/A';
		$button_kb_revers 	= 'N/A';
	$button_listpayment = 'N/A';
	$button_payment 	= 'N/A';

	if(ISSET($row['pembelian']) OR !EMPTY($row['pembelian'])){
		$button_pembelian ="<a href='?mod=jp&id=".$row['pembelian']."'  target='_blank'>".$row['pembelian']."</a>";
	}
	if(ISSET($row['journal_revers']) OR !EMPTY($row['journal_revers'])){
		$button_revers ="<a href='?mod=jp&id=".$row['journal_revers']."'  target='_blank'>".$row['journal_revers']."</a>";
	}	
	if(ISSET($row['no_kontrabon']) OR !EMPTY($row['no_kontrabon'])){
		$button_kontrabon ="<a href='?mod=kb&id=".$row['no_kontrabon']."'   target='_blank'>".$row['no_kontrabon']."</a>";		
	}
	if(ISSET($row['kb_reverse']) OR !EMPTY($row['kb_reverse'])){
		$button_kb_revers ="<a href='?mod=jp&id=".$row['kb_reverse']."'  target='_blank'>".$row['kb_reverse']."</a>";
	}	
	if(ISSET($row['no_listpayment']) OR !EMPTY($row['no_listpayment'])){
		$button_listpayment ="<a href='?mod=rekap&view=1&id=".$row['no_listpayment']."' target='_blank'>".$row['no_listpayment']."</a>";		
	}	
	if(ISSET($row['no_pembayaran']) OR !EMPTY($row['no_pembayaran'])){
		$button_payment ="<a href='?mod=jpay&id=".$row['no_pembayaran']."'  target='_blank'>".$row['no_pembayaran']."</a>";		
	}		
   $data[] = array( 
"pembelian"=>rawurlencode($button_pembelian),
"journal_revers"=>rawurlencode($button_revers),
"bpb"=>$row['bpb'],
"no_kontrabon"=>rawurlencode($button_kontrabon),
"kb_revers"=>rawurlencode($button_kb_revers),
"no_listpayment"=>rawurlencode($button_listpayment),
"no_pembayaran"=>rawurlencode($button_payment),
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