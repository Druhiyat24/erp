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
$data = $_GET;
/* $d_from = $d_from =date("Y-m-d", strtotime($data['from']));
$d_to = $d_to =date("Y-m-d", strtotime($data['to']));
$id_coa = $_GET['id_coa']; */
$d_from = "";
$d_to = "";
$id_coa = "";
if(ISSET($_GET['from'])){
	$d_from  =date("Y-m-d", strtotime($data['from']));
}

if(ISSET($_GET['to'])){
	$d_to  =date("Y-m-d", strtotime($data['to']));
}

if(ISSET($_GET['id_coa'])){
	$id_coa = $_GET['id_coa'];
}
IF(($d_from !="") &&  ($d_to !="") && ($id_coa !="") ){
	$my_where = "AND A.id_coa='{$id_coa}' AND A.d_daily >= '{$d_from}' AND A.d_daily <= '{$d_to}'";
}else{
	$my_where = "";
}


$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(
	SELECT CURRENT.daily_saldo_IDR current_saldo,A.d_daily,A.id_coa,MC.nm_coa,A.saldo_idr saldo FROM fin_daily_saldo A LEFT JOIN mastercoa MC 
	ON MC.id_coa = A.id_coa
	
	
	LEFT JOIN(
SELECT  MASTER.id_journal
       ,MASTER.id_journal j_fh
       ,MASTER.row_id
       ,MASTER.date_journal
       ,MASTER.id_coa
       ,MASTER.nm_coa
       ,MASTER.v_normal
		,ifnull(( SUM(MASTER.n_debit_IDR) + SUM(MASTER.n_credit_IDR) + SUM(MASTER.a_credit_IDR) + SUM(MASTER.a_debit_IDR) ),0)daily_saldo_IDR
		,ifnull(( SUM(MASTER.n_debit_USD) + SUM(MASTER.n_credit_USD) + SUM(MASTER.a_credit_USD) + SUM(MASTER.a_debit_USD) ),0)daily_saldo_USD	
		FROM(

	SELECT  M.id_journal
	       ,M.j_fh
	       ,M.row_id
	       ,M.date_journal
	       ,M.id_coa
	       ,M.nm_coa
	       ,M.credit
	       ,M.debit
	       ,M.v_normal
		   ,IF(M.curr = 'IDR',M.n_debit,M.n_debit*MR.rate)n_debit_IDR
		   ,IF(M.curr = 'USD',M.n_debit,0)n_debit_USD
	 
		   ,IF(M.curr = 'IDR',M.n_credit,M.n_credit*MR.rate)n_credit_IDR
		   ,IF(M.curr = 'USD',M.n_credit,0)n_credit_USD	
	
		   ,IF(M.curr = 'IDR',M.a_credit,M.a_credit*MR.rate)a_credit_IDR
		   ,IF(M.curr = 'USD',M.a_credit,0)a_credit_USD	
	
		   ,IF(M.curr = 'IDR',M.a_debit,M.a_debit*MR.rate)a_debit_IDR
		   ,IF(M.curr = 'USD',M.a_debit,0)a_debit_USD	FROM(
	
	
		SELECT
				 FD.id_journal
				,FH.id_journal j_fh
				,FD.row_id
				,FH.date_journal
				,FD.id_coa
				,FD.nm_coa
				,FD.credit
				,FD.debit
				,MC.v_normal
				,FD.curr
				,IF(MC.v_normal = 'D' AND FD.debit > 0,FD.debit,0 )n_debit
				,IF(MC.v_normal = 'C' AND FD.debit > 0,FD.credit,0 )n_credit
				,IF(MC.v_normal = 'D' AND FD.credit > 0,(-1*FD.credit),0 )a_credit
				,IF(MC.v_normal = 'C' AND FD.debit > 0,(-1*FD.debit),0 )a_debit
				FROM fin_journal_d FD
				LEFT JOIN (SELECT * FROM fin_journal_h WHERE 1=1)FH ON FH.id_journal = FD.id_journal
				LEFT JOIN(SELECT id_coa,v_normal FROM mastercoa WHERE 1=1)MC ON MC.id_coa =FD.id_coa
			WHERE 1=1 AND FH.fg_post  = '2' AND FD.id_coa='$id_coa'
			)M
LEFT JOIN (SELECT tanggal,ifnull(rate,0)rate FROM masterrate 
WHERE 1=1 AND v_codecurr = 'PAJAK' GROUP BY tanggal)MR ON MR.tanggal = M.date_journal
			)MASTER  WHERE MASTER.date_journal >= '2019-11-01' GROUP BY MASTER.id_coa,MASTER.date_journal	
	
	
	
	)CURRENT ON A.d_daily = CURRENT.date_journal AND A.id_coa = CURRENT.id_coa
	
	
	 WHERE 1=1 $my_where
)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (

	   X.d_daily             LIKE'%".$searchValue."%'
	OR X.id_coa              LIKE'%".$searchValue."%'
	OR X.nm_coa                   LIKE'%".$searchValue."%'
	OR X.saldo                   LIKE'%".$searchValue."%'
	OR X.current_saldo  LIKE'%".$searchValue."%'

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
			 X.d_daily   
			,X.id_coa    
			,X.nm_coa    
			,X.saldo       
			,X.current_saldo
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
//echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {


   $data[] = array(
"d_daily"=>htmlspecialchars($row['d_daily']),
"id_coa"=>htmlspecialchars($row['id_coa']),
"nm_coa"=>htmlspecialchars($row['nm_coa']),
"saldo"=>htmlspecialchars(number_format((float)$row['saldo'], 2, '.', ',')),
"current_saldo"=>htmlspecialchars(number_format((float)$row['current_saldo'], 2, '.', ','))
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