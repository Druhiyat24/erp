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
// $type_journal = $("#txttipe_jurnal").val();
$type_journal = $_GET['type_journal'];
$status = $_GET['fg_post'];

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
SELECT fjh.type_journal
, fjh.period 
, fjh.id_journal
, fjh.fg_post
, fjh.date_journal
, fjd.id_coa
, fjd.nm_coa
, fjd.debit AS debit
, fjd.credit AS credit
, fjh.remark
, b.id_costcenter
, b.nm_costcenter
FROM fin_journal_h fjh 
LEFT JOIN fin_journal_d fjd ON fjh.id_journal=fjd.id_journal
LEFT JOIN fin_journalheaderdetail fj ON fj.v_idjournal=fjh.id_journal
LEFT JOIN mastercostcategory a ON a.id_cost_category=fjd.id_cost_category
LEFT JOIN mastercostcenter b ON b.id_cost_category=a.id_cost_category
LEFT JOIN mastercostdept c ON c.id_cost_dept=b.id_cost_dept
LEFT JOIN mastercostsubdept d ON d.id_cost_sub_dept=b.id_cost_sub_dept
WHERE fjh.fg_post='$status' AND fjh.type_journal='$type_journal' AND (fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to') AND fjd.id_coa!='' GROUP BY fjd.row_id,fjh.id_journal ORDER BY fjh.period DESC, fjh.date_journal DESC, fjh.id_journal DESC, fjd.debit DESC, fjd.credit DESC, fjd.id_coa DESC, fjd.nm_coa DESC
)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (

	
	X.period           LIKE'%".$searchValue."%'
	OR X.id_journal    LIKE'%".$searchValue."%'
	OR X.date_journal  LIKE'%".$searchValue."%'
	OR X.id_coa        LIKE'%".$searchValue."%'
	OR X.nm_coa        LIKE'%".$searchValue."%'
	OR X.debit         LIKE'%".$searchValue."%'
	OR X.credit        LIKE'%".$searchValue."%'
	OR X.remark        LIKE'%".$searchValue."%'
	OR X.id_costcenter LIKE'%".$searchValue."%'
	OR X.nm_costcenter LIKE'%".$searchValue."%'
	
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

X.period,
X.id_journal,
X.date_journal,
X.id_coa,
X.nm_coa,
X.debit,
X.credit,
X.remark,
X.id_costcenter,
X.nm_costcenter

";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
 //echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];


	$data[] = array(            
		
		"period"=>htmlspecialchars($row['period']),
		"id_journal"=>htmlspecialchars($row['id_journal']),
		"date_journal"=>htmlspecialchars($row['date_journal']),
		"id_coa"=>htmlspecialchars($row['id_coa']),
		"nm_coa"=>htmlspecialchars($row['nm_coa']),
		"debit"=>htmlspecialchars(number_format((float)$row['debit'], 2, '.', ',')),
		"credit"=>htmlspecialchars(number_format((float)$row['credit'], 2, '.', ',')),
		"remark"=>htmlspecialchars($row['remark']),
		"id_costcenter"=>htmlspecialchars($row['id_costcenter']),
		"nm_costcenter"=>htmlspecialchars($row['nm_costcenter']),

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