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
$d_from =date("Y-m-d", strtotime($data['from']));  
$d_to   =date("Y-m-d", strtotime($data['to']));   
$id_coa = SUBSTR($data['id_coa'],0,5);
$sql3 = "AND fjd.id_coa = '{$id_coa}' AND fjh.fg_post='2' AND fjh.date_journal  >= '".$d_from."' AND fjh.date_journal <= '".$d_to."'";
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(
SELECT 
	fjd.id_journal,
	fjd.dateadd,
	fjh.date_journal,
	bpb.bpbno,
	fjh.reff_doc,
	''reff_doc2,
	fjhd.v_novoucher
	,IF(fjd.curr='USD' OR fjd.curr='',(fjd.debit),0 )debit_ori
	,IF(fjd.curr='USD' OR fjd.curr='',(fjd.credit),0 )credit_ori
	,IF(fjd.curr='USD' OR fjd.curr='',(fjd.debit*mr.rate),fjd.debit )debit_idr
    ,IF(fjd.curr='USD' OR fjd.curr='',(fjd.credit*mr.rate),fjd.credit )credit_idr
	,fjd.description, 
	fjh.type_journal,
	fjd.id_coa,
	IF(fjd.curr='USD' OR fjd.curr='','USD','IDR')curr
	,mr.rate		
	,MS.Supplier
	,MS.supplier_code
	FROM fin_journal_d fjd 
	LEFT JOIN fin_journal_h fjh ON fjd.id_journal = fjh.id_journal 
	LEFT JOIN bpb ON fjh.reff_doc = bpb.bpbno_int 
	LEFT JOIN fin_journalheaderdetail fjhd
	ON fjh.id_journal = fjhd.v_idjournal 
	LEFT JOIN (SELECT v_codecurr,rate,tanggal FROM masterrate WHERE v_codecurr = 'PAJAK') mr ON mr.tanggal = fjh.date_journal						
	LEFT JOIN mastersupplier MS ON bpb.id_supplier = MS.Id_Supplier
	WHERE 1=1 $sql3
	GROUP BY fjh.id_journal

	UNION ALL
	
SELECT
	 fjd.id_journal
	,fjd.dateadd
	,fjh.date_journal
	,'' bpbno
	,fjd.reff_doc2 reff_doc
	,fjd.reff_doc2
	,'' v_novoucher
	,IF(fjd.curr='USD' OR fjd.curr='',(SUM(fjd.debit)),0 )debit_ori
	,IF(fjd.curr='USD' OR fjd.curr='',(SUM(fjd.credit)),0 )credit_ori
	,IF(fjd.curr='USD' OR fjd.curr='',(SUM(fjd.debit)*mr.rate),fjd.debit )debit_idr
    ,IF(fjd.curr='USD' OR fjd.curr='',(SUM(fjd.credit)*mr.rate),fjd.credit )credit_idr
	,fjd.description
	,fjh.type_journal
	,fjd.id_coa
	,IF(fjd.curr='USD' OR fjd.curr='','USD','IDR')curr
	,mr.rate
	,MS.Supplier
	,MS.supplier_code
	FROM fin_journal_d fjd
	LEFT JOIN (SELECT * FROM fin_journal_h WHERE 1=1 AND type_journal='14')fjh ON fjd.id_journal = fjh.id_journal 
	LEFT JOIN (SELECT v_codecurr,rate,tanggal FROM masterrate WHERE v_codecurr = 'PAJAK') mr ON mr.tanggal = fjh.date_journal						
	LEFT JOIN mastersupplier MS ON fjd.id_supplier = MS.Id_Supplier	
	LEFT JOIN fin_journal_h f_ref ON fjd.reff_doc2 = f_ref.id_journal
	WHERE 1=1 $sql3
	GROUP BY fjd.reff_doc2
)X";
## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (
X.id_journal						LIKE '%".$searchValue."%'
X.date_journal                      LIKE '%".$searchValue."%'
X.reff_doc                      	LIKE '%".$searchValue."%'
X.supplier_code                     LIKE '%".$searchValue."%'
X.Supplier                      	LIKE '%".$searchValue."%'
X.v_novoucher                      	LIKE '%".$searchValue."%'
X.description                      	LIKE '%".$searchValue."%'
X.rate                     			LIKE '%".$searchValue."%'
X.debit_ori                      	LIKE '%".$searchValue."%'
X.credit_ori                      	LIKE '%".$searchValue."%'
X.debit_idr                      	LIKE '%".$searchValue."%'
X.credit_idr                      	LIKE '%".$searchValue."%'
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
			X.id_journal
			,X.date_journal
			,X.reff_doc
			,X.supplier_code
			,X.Supplier
			,X.v_novoucher
			,X.description
			,X.rate
			,X.debit_ori
			,X.credit_ori
			,X.debit_idr
			,X.credit_idr
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
//echo $empQuery;
$no = 0;
while ($row = mysqli_fetch_assoc($empRecords)) {
$no++;
//echo $row['n_post'];


   $data[] = array(

"date_journal"=>htmlspecialchars($row['date_journal']),
"id_journal"=>htmlspecialchars($row['id_journal']),
"reff_doc"=>htmlspecialchars($row['reff_doc']),
"supplier_code"=>htmlspecialchars($row['supplier_code']),
"Supplier"=>htmlspecialchars($row['Supplier']),
"v_novoucher"=>htmlspecialchars($row['v_novoucher']),
"description"=>htmlspecialchars($row['description']),
"rate"=>htmlspecialchars((number_format($row['rate'], 2, '.', ','))),
"debit_ori"=>htmlspecialchars((number_format($row['debit_ori'], 2, '.', ','))),
"credit_ori"=>htmlspecialchars((number_format($row['credit_ori'], 2, '.', ','))),
"debit_idr"=>htmlspecialchars((number_format($row['debit_idr'], 2, '.', ','))),
"credit_idr"=>htmlspecialchars((number_format($row['credit_idr'], 2, '.', ',')))
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