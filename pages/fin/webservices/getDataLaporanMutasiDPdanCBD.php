<?php 
	function d_from($from){
		$d_from = explode("/",$from."/01");
		$d_from = $d_from[1]."-".$d_from[0]."-".$d_from[2];
		return $d_from;
	}
	function d_to($to){
		$d_to = explode("/",$to."/01");
		$d_to = $d_to[1]."-".$d_to[0]."-".$d_to[2];
		//$d_to = date("Y-m-d", strtotime("1 month",strtotime($d_to)));
		//$d_to = date("Y-m-d", strtotime("-1 days",strtotime($d_to)));
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
SELECT 
			AA.tgl_bukti
		,	AA.no_bukti
		,	FJ.id_coa
		,	AA.Supplier
		,	AA.kode_pterms
		,	AA.so_no
		,	AA.so_date
		,	AA.tgl_aju_trf
		,	AA.no_aju_trf
		,	AA.podate
		,	AA.pono
		,	AA.qty_po
		,	AA.up_po
		,	AA.total_price_po
		,	AA.tgl_inv
		,	AA.inv_supp
		,	AA.qty_bpb
		,	AA.up_bpb
		,	AA.total_price_bpb
		,	AA.bpbdate		
		,	AA.bpbno_int
		,	FJ.debit 
		,	FJ.credit 
		,	(FJ.debit-FJ.credit)saldo
FROM
(SELECT  
		  A.bpbno_int 
		, A.bpbdate
		, A.bpbdate tgl_inv
		, B.podate
		, B.pono
		, B.Supplier
		, F.kode_pterms
		, B.qty qty_po
		, IF(B.curr='USD',(B.price*G.rate),B.price) up_po
		, B.curr curr_po
		, IF(B.curr='USD',((B.qty*B.price)*G.rate),(B.qty*B.price)) total_price_po
		, A.invno inv_supp
		, A.qty qty_bpb
		, IF(A.curr='USD',(A.price*G.rate),A.price) up_bpb
		, A.curr curr_bpb
		, IF(A.curr='USD',((A.qty*A.price)*G.rate),(A.qty*A.price)) total_price_bpb		
		, E.so_date
		, E.so_no
		, 'N/A' tgl_aju_trf
		, 'N/A' no_aju_trf
		, 'N/A' tgl_bukti
		, 'N/A' no_bukti
FROM (
		
		SELECT bpbno bpbno
		, bpbno_int bpbno_int
		, bpbdate bpbdate
		, invno invno
		, qty qty
		, price price
		, curr curr
		, pono pono
		FROM bpb WHERE confirm='Y' ) A 
LEFT JOIN (
		SELECT B.podate podate
		, B.pono pono
		, B.id_terms id_terms
		, B.id_supplier id_supplier
		, C.qty qty
		, C.price price
		, C.curr curr
		, C.id_jo id_jo
		, Y.Supplier
		FROM po_header B 
		LEFT JOIN po_item C ON C.id_po=B.id
		LEFT JOIN mastersupplier Y ON Y.Id_Supplier=B.id_supplier
		WHERE C.cancel='N'
		)B ON B.pono=A.pono
LEFT JOIN (
		SELECT id_jo id_jo
		, id_so id_so
		FROM jo_det WHERE cancel='N' ) D ON D.id_jo=B.id_jo
LEFT JOIN ( 
		SELECT id id
		, so_no so_no
		, so_date so_date
		FROM so) E ON E.id=D.id_so
LEFT JOIN masterpterms F ON F.id=B.id_terms
LEFT JOIN masterrate G ON G.tanggal=B.podate

WHERE B.id_terms IN ('5','41','42','43','44','45','46','47','48','49','50','51','52','53','54','55','56','57','58','59','60','61','62','63','64','65','66','67','68','69','70','1001','1002','1006','1011')
AND G.v_codecurr='PAJAK' AND B.podate >= '$d_from' and B.podate <= '$d_to'
GROUP BY A.bpbno
ORDER BY B.podate DESC

)AA LEFT JOIN 
(
	SELECT 
		fjd.id_journal
	,	fjh.reff_doc
	,	fjh.fg_post
	,	fjh.type_journal
	,	fjd.id_coa
	,	fjd.nm_coa
	,	fjd.debit
	,	fjd.credit
	,	fjd.id_supplier
	FROM fin_journal_h fjh 
	LEFT JOIN fin_journal_d fjd ON fjd.id_journal=fjh.id_journal
	WHERE fjh.fg_post='2' AND fjh.type_journal='2'
)FJ ON FJ.reff_doc = AA.bpbno_int
WHERE FJ.debit > 0 AND FJ.id_journal IS NOT NULL 
GROUP BY FJ.id_journal


UNION ALL 

SELECT 
			AA.tgl_bukti
		,	AA.no_bukti
		,	FJ.id_coa
		,	AA.Supplier
		,	AA.kode_pterms
		,	AA.so_no
		,	AA.so_date
		,	AA.tgl_aju_trf
		,	AA.no_aju_trf
		,	AA.podate
		,	AA.pono
		,	AA.qty_po
		,	AA.up_po
		,	AA.total_price_po
		,	AA.tgl_inv
		,	AA.inv_supp
		,	AA.qty_bpb
		,	AA.up_bpb
		,	AA.total_price_bpb
		,	AA.bpbdate		
		,	AA.bpbno_int
		,	FJ.debit 
		,	FJ.credit 
		,	(FJ.debit-FJ.credit)saldo
FROM
(SELECT  
		  A.bpbno_int 
		, A.bpbdate
		, A.bpbdate tgl_inv
		, B.podate
		, B.pono
		, B.Supplier
		, F.kode_pterms
		, B.qty qty_po
		, IF(B.curr='USD',(B.price*G.rate),B.price) up_po
		, B.curr curr_po
		, IF(B.curr='USD',((B.qty*B.price)*G.rate),(B.qty*B.price)) total_price_po
		, A.invno inv_supp
		, A.qty qty_bpb
		, IF(A.curr='USD',(A.price*G.rate),A.price) up_bpb
		, A.curr curr_bpb
		, IF(A.curr='USD',((A.qty*A.price)*G.rate),(A.qty*A.price)) total_price_bpb		
		, E.so_date
		, E.so_no
		, 'N/A' tgl_aju_trf
		, 'N/A' no_aju_trf
		, 'N/A' tgl_bukti
		, 'N/A' no_bukti
FROM (
		
		SELECT bpbno bpbno
		, bpbno_int bpbno_int
		, bpbdate bpbdate
		, invno invno
		, qty qty
		, price price
		, curr curr
		, pono pono
		FROM bpb WHERE confirm='Y' ) A 
LEFT JOIN (
		SELECT B.podate podate
		, B.pono pono
		, B.id_terms id_terms
		, B.id_supplier id_supplier
		, C.qty qty
		, C.price price
		, C.curr curr
		, C.id_jo id_jo
		, Y.Supplier
		FROM po_header B 
		LEFT JOIN po_item C ON C.id_po=B.id
		LEFT JOIN mastersupplier Y ON Y.Id_Supplier=B.id_supplier
		WHERE C.cancel='N'
		)B ON B.pono=A.pono
LEFT JOIN (
		SELECT id_jo id_jo
		, id_so id_so
		FROM jo_det WHERE cancel='N' ) D ON D.id_jo=B.id_jo
LEFT JOIN ( 
		SELECT id id
		, so_no so_no
		, so_date so_date
		FROM so) E ON E.id=D.id_so
LEFT JOIN masterpterms F ON F.id=B.id_terms
LEFT JOIN masterrate G ON G.tanggal=B.podate

WHERE B.id_terms IN ('5','41','42','43','44','45','46','47','48','49','50','51','52','53','54','55','56','57','58','59','60','61','62','63','64','65','66','67','68','69','70','1001','1002','1006','1011')
AND G.v_codecurr='PAJAK' AND B.podate >= '$d_from' and B.podate <= '$d_to'
GROUP BY A.bpbno
ORDER BY B.podate DESC

)AA LEFT JOIN 
(
	SELECT 
		fjd.id_journal
	,	fjh.reff_doc
	,	fjh.fg_post
	,	fjh.type_journal
	,	fjd.id_coa
	,	fjd.nm_coa
	,	fjd.debit
	,	fjd.credit
	,	fjd.id_supplier
	FROM fin_journal_h fjh 
	LEFT JOIN fin_journal_d fjd ON fjd.id_journal=fjh.id_journal
	WHERE fjh.fg_post='2' AND fjh.type_journal='2'
)FJ ON FJ.reff_doc = AA.bpbno_int
WHERE FJ.credit > 0 AND FJ.id_journal IS NOT NULL 
GROUP BY FJ.id_journal


)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (

	X.tgl_bukti              LIKE'%".$searchValue."%' OR
	X.no_bukti                     LIKE'%".$searchValue."%' OR
	X.id_coa                   LIKE'%".$searchValue."%' OR
	X.Supplier                LIKE'%".$searchValue."%' OR
	X.kode_pterms          	    LIKE'%".$searchValue."%' OR
	X.so_no	     LIKE'%".$searchValue."%' OR
	X.so_date   LIKE'%".$searchValue."%' OR
	X.tgl_aju_trf      LIKE'%".$searchValue."%' OR
	X.no_aju_trf   LIKE'%".$searchValue."%' OR
	X.podate   LIKE'%".$searchValue."%' OR
	X.pono   LIKE'%".$searchValue."%' OR
	X.qty_po                LIKE'%".$searchValue."%' OR
	X.up_po                   LIKE'%".$searchValue."%' OR
	X.total_price_po                    LIKE'%".$searchValue."%' OR
	X.tgl_inv                    LIKE'%".$searchValue."%' OR
	X.inv_supp                    LIKE'%".$searchValue."%' OR
	X.qty_bpb                    LIKE'%".$searchValue."%' OR
	X.up_bpb                    LIKE'%".$searchValue."%' OR
	X.total_price_bpb                    LIKE'%".$searchValue."%' OR
	X.bpbdate                    LIKE'%".$searchValue."%' OR
	X.bpbno_int                    LIKE'%".$searchValue."%' OR
	X.debit                    LIKE'%".$searchValue."%' OR
	X.credit                    LIKE'%".$searchValue."%' OR
	X.saldo                    LIKE'%".$searchValue."%'
	
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
	X.tgl_bukti   
	,X.no_bukti    
	,X.id_coa      
	,X.Supplier    
	,X.kode_pterms 
	,X.so_no	   
	,X.so_date
	,X.tgl_aju_trf
	,X.no_aju_trf 
	,X.podate 
	,X.pono
	,X.qty_po
	,X.up_po 
	,X.total_price_po
	,X.tgl_inv       
	,X.inv_supp      
	,X.qty_bpb       
	,X.up_bpb        
	,X.total_price_bpb
	,X.bpbdate        
	,X.bpbno_int      
	,X.debit          
	,X.credit         
	,X.saldo          
	
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
//echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];


   $data[] = array(            
"tgl_bukti"=>htmlspecialchars($row['tgl_bukti']),
"no_bukti"=>htmlspecialchars($row['no_bukti']),
"id_coa"=>htmlspecialchars($row['id_coa']),
"Supplier"=>htmlspecialchars($row['Supplier']),
"kode_pterms"=>htmlspecialchars($row['kode_pterms']),
"so_no"=>htmlspecialchars($row['so_no']),
"so_date"=>htmlspecialchars($row['so_date']),
"tgl_aju_trf"=>htmlspecialchars($row['tgl_aju_trf']),
"no_aju_trf"=>htmlspecialchars($row['no_aju_trf']),
"podate"=>htmlspecialchars($row['podate']),
"pono"=>htmlspecialchars($row['pono']),
"qty_po"=>htmlspecialchars(number_format((float)$row['qty_po'], 2, '.', ',')),  
"up_po"=>htmlspecialchars(number_format((float)$row['up_po'], 2, '.', ',')),  
"total_price_po"=>htmlspecialchars(number_format((float)$row['total_price_po'], 2, '.', ',')),  
"tgl_inv"=>htmlspecialchars($row['tgl_inv']),
"inv_supp"=>htmlspecialchars($row['inv_supp']),
"qty_bpb"=>htmlspecialchars(number_format((float)$row['qty_bpb'], 2, '.', ',')),  
"up_bpb"=>htmlspecialchars(number_format((float)$row['up_bpb'], 2, '.', ',')),  
"total_price_bpb"=>htmlspecialchars(number_format((float)$row['total_price_bpb'], 2, '.', ',')),  
"bpbdate"=>htmlspecialchars($row['bpbdate']),
"bpbno_int"=>htmlspecialchars($row['bpbno_int']),
"debit"=>htmlspecialchars(number_format((float)$row['debit'], 2, '.', ',')),  
"credit"=>htmlspecialchars(number_format((float)$row['credit'], 2, '.', ',')),  
"saldo"=>htmlspecialchars(number_format((float)$row['saldo'], 2, '.', ',')),  

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