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
$d_from = d_from($data['from']);
$d_to   = d_to($data['to']);
$t_journal = $data['type_journal'];
if(!ISSET($t_journal) || EMPTY($t_journal)){
	$type_jour = "AND fjh.type_journal IN ('1','2','3','4','5','6','7','8','9','10','11','12','13','14','17')";
}else{
	$type_jour = "AND fjh.type_journal='$t_journal'";
}
$stts = $data['fg_post'];
if($stts == "" ){
	$where_status = "AND fjh.fg_post IN ('0','2')";
}else{
	$where_status = "AND fjh.fg_post='$stts'";
	
}/* 
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
SELECT    fjh.type_journal
		, fjh.period 
		, fjd.row_id
		, fjh.id_journal
		, fjh.fg_post
		, fjh.date_journal
		, fjd.id_coa
		, fjd.nm_coa
		,IF(fjd.curr='USD' OR fjd.curr='' OR fjd.curr IS NULL,'USD','IDR')curr
		,IF(fjd.curr='USD' OR fjd.curr='' OR fjd.curr IS NULL,debit*ifnull(mr.rate,1),debit)debit_eqv
		,IF(fjd.curr='USD' OR fjd.curr='' OR fjd.curr IS NULL,credit*ifnull(mr.rate,1),credit)credit_eqv	
		
		, fjd.debit AS debit
		, fjd.credit AS credit
		, fjd.description
		, fjh.remark
		, b.id_costcenter
		, b.nm_costcenter
		, mr.rate
		, if(fjh.fg_post ='2','Posted','Parked')status
		FROM fin_journal_h fjh 
		LEFT JOIN fin_journal_d fjd ON fjh.id_journal=fjd.id_journal
		LEFT JOIN fin_journalheaderdetail fj ON fj.v_idjournal=fjh.id_journal
		LEFT JOIN mastercostcategory a ON a.id_cost_category=fjd.id_cost_category
		LEFT JOIN mastercostcenter b ON b.id_cost_category=a.id_cost_category
		LEFT JOIN mastercostdept c ON c.id_cost_dept=b.id_cost_dept
		LEFT JOIN mastercostsubdept d ON d.id_cost_sub_dept=b.id_cost_sub_dept
		LEFT JOIN (SELECT v_codecurr,rate,tanggal FROM masterrate WHERE v_codecurr = 'PAJAK') mr ON mr.tanggal = fjh.date_journal
		WHERE 1=1 $where_status $type_jour AND (fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to') AND fjd.id_coa!='' GROUP BY fjd.id_journal, fjd.row_id ORDER BY fjh.period DESC, fjh.date_journal DESC, fjh.id_journal DESC, fjd.debit DESC, fjd.credit DESC, fjd.id_coa DESC, fjd.nm_coa DESC
)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (

X.period                             LIKE '%".$searchValue."%'
X.status                             LIKE '%".$searchValue."%'
X.id_journal                         LIKE '%".$searchValue."%'
X.date_journal                       LIKE '%".$searchValue."%'
X.id_coa                             LIKE '%".$searchValue."%'
X.nm_coa                             LIKE '%".$searchValue."%'
X.curr                               LIKE '%".$searchValue."%'
X.debit                              LIKE '%".$searchValue."%'
X.curr                               LIKE '%".$searchValue."%'
X.credit                             LIKE '%".$searchValue."%'
X.debit_eqv                          LIKE '%".$searchValue."%'
X.credit_eqv                         LIKE '%".$searchValue."%'
X.description                        LIKE '%".$searchValue."%'
X.id_costcenter						 LIKE '%".$searchValue."%'
X.nm_costcenter                      LIKE '%".$searchValue."%'
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
				X.period
				,X.status
				,X.id_journal
				,X.date_journal
				,X.id_coa
				,X.nm_coa
				,X.curr
				,X.debit
				,X.curr
				,X.credit
				,X.debit_eqv
				,X.credit_eqv
				,X.description
				,X.id_costcenter		
				,X.nm_costcenter
				 ";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
//echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];


   $data[] = array(
"period"=>htmlspecialchars($row['period']),
"status"=>htmlspecialchars($row['status']),
"id_journal"=>htmlspecialchars($row['id_journal']),
"date_journal"=>htmlspecialchars($row['date_journal']),
"id_coa"=>htmlspecialchars($row['id_coa']),
"nm_coa"=>htmlspecialchars($row['nm_coa']),
"curr"=>htmlspecialchars($row['curr']),
"debit"=>htmlspecialchars((number_format($row['debit'], 2, '.', ','))),
"credit"=>htmlspecialchars((number_format($row['credit'], 2, '.', ','))),
"debit_eqv"=>htmlspecialchars((number_format($row['debit_eqv'], 2, '.', ','))),
"credit_eqv"=>htmlspecialchars((number_format($row['credit_eqv'], 2, '.', ','))),
"description"=>htmlspecialchars($row['description']),
"id_costcenter"=>htmlspecialchars($row['id_costcenter']),
"nm_costcenter"=>htmlspecialchars($row['nm_costcenter'])
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