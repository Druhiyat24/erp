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
$id_coa = $data['akun'];
/* 
print_r($data);
die();  */ 
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
/* $columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name */
//$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(

SELECT tmp.* FROM(
SELECT   FH.id_journal
		,FH.fg_post
		,FH.date_journal
		,FH.type_journal
		,FH.reff_doc
		,FD_DEBIT.id_coa
		,FD_DEBIT.nm_coa
		,FD_DEBIT.debit
		,'0' credit
		,IF(FD_DEBIT.curr='IDR',FD_DEBIT.debit,(FD_DEBIT.debit*MR.rate))debit_idr
		,'0' credit_idr
		,FD_LAWAN_DEBIT.id_coa id_coa_lawan
		,FD_LAWAN_DEBIT.nm_coa nm_coa_lawan
		,MR.rate
		,J_HELP.v_novoucher
		,J_HELP.v_fakturpajak
		FROM fin_journal_h FH
			INNER JOIN(
					SELECT  C.id_journal
				,MAX(C.id_coa)id_coa
				,MAX(C.nm_coa)nm_coa
				,SUM(C.debit)debit 
				,MAX(C.curr)curr
				FROM(	
			SELECT       A.id_journal 
						,if(A.id_coa='{$id_coa}',A.id_coa,'')id_coa 
						,if(A.id_coa='{$id_coa}',A.nm_coa,'')nm_coa 
						,if(B.v_normal='D',A.debit,(-1*A.debit))debit 
						,A.curr 
						FROM fin_journal_d A
				LEFT JOIN(SELECT id_coa,v_normal FROM mastercoa WHERE 1 )B 
				ON A.id_coa = B.id_coa 
				WHERE A.debit > 0 GROUP BY A.id_journal,A.row_id
				
				)C WHERE 1=1 GROUP BY C.id_journal
				
			)FD_DEBIT ON FH.id_journal = FD_DEBIT.id_journal
			INNER JOIN(
					SELECT  C.id_journal
				,GROUP_CONCAT(C.id_coa)id_coa
				,GROUP_CONCAT(C.nm_coa)nm_coa
				 FROM(		
				SELECT   A.id_journal 
						,A.id_coa
						,A.nm_coa
						,A.debit 
						,A.curr 
						FROM fin_journal_d A
				LEFT JOIN(SELECT id_coa,v_normal FROM mastercoa WHERE 1 )B 
				ON A.id_coa = B.id_coa 
				WHERE A.credit > 0 GROUP BY A.id_journal,A.row_id
				)C WHERE 1=1 GROUP BY C.id_journal
			)FD_LAWAN_DEBIT ON FD_DEBIT.id_journal = FD_LAWAN_DEBIT.id_journal	
			LEFT JOIN(
				SELECT v_codecurr,rate,tanggal FROM masterrate WHERE v_codecurr = 'PAJAK'
			)MR ON FH.date_journal = MR.tanggal
			INNER JOIN(
				SELECT n_id,v_idjournal,v_novoucher,v_fakturpajak FROM fin_journalheaderdetail WHERE v_novoucher !=''
			)J_HELP ON J_HELP.v_idjournal = FH.id_journal
		WHERE 1=1 AND FD_DEBIT.id_coa='{$id_coa}' AND FH.fg_post='2' AND FH.date_journal >= '{$d_from}' AND FH.date_journal <= '{$d_to}' 
		GROUP BY FH.id_journal
		
		
	UNION ALL


SELECT   FH.id_journal
		,FH.fg_post
		,FH.date_journal
		,FH.type_journal
		,FH.reff_doc
		,FD_CREDIT.id_coa
		,FD_CREDIT.nm_coa
		,'0' debit
		,FD_CREDIT.credit
		,'0' debit_idr
		,IF(FD_CREDIT.curr='IDR',FD_CREDIT.credit,(FD_CREDIT.credit*MR.rate))credit_idr
		,FD_LAWAN_CREDIT.id_coa id_coa_lawan
		,FD_LAWAN_CREDIT.nm_coa nm_coa_lawan
		,MR.rate
		,J_HELP.v_novoucher
		,J_HELP.v_fakturpajak
		FROM fin_journal_h FH
			INNER JOIN(
					SELECT  C.id_journal
				,MAX(C.id_coa)id_coa
				,MAX(C.nm_coa)nm_coa
				,SUM(C.credit)credit 
				,MAX(C.curr)curr
				FROM(	
			SELECT       A.id_journal 
						,if(A.id_coa='{$id_coa}',A.id_coa,'')id_coa 
						,if(A.id_coa='{$id_coa}',A.nm_coa,'')nm_coa 
						,if(B.v_normal='D',A.credit,(-1*A.credit))credit 
						,A.curr 
						FROM fin_journal_d A
				LEFT JOIN(SELECT id_coa,v_normal FROM mastercoa WHERE 1 )B 
				ON A.id_coa = B.id_coa 
				WHERE A.credit > 0 GROUP BY A.id_journal,A.row_id
				
				)C WHERE 1=1 GROUP BY C.id_journal
				
			)FD_CREDIT ON FH.id_journal = FD_CREDIT.id_journal
			INNER JOIN(
					SELECT  C.id_journal
				,GROUP_CONCAT(C.id_coa)id_coa
				,GROUP_CONCAT(C.nm_coa)nm_coa
				 FROM(		
				SELECT   A.id_journal 
						,A.id_coa
						,A.nm_coa
						,A.credit 
						,A.curr 
						FROM fin_journal_d A
				LEFT JOIN(SELECT id_coa,v_normal FROM mastercoa WHERE 1 )B 
				ON A.id_coa = B.id_coa 
				WHERE A.debit > 0 GROUP BY A.id_journal,A.row_id
				)C WHERE 1=1 GROUP BY C.id_journal
			)FD_LAWAN_CREDIT ON FD_CREDIT.id_journal = FD_LAWAN_CREDIT.id_journal	
			LEFT JOIN(
				SELECT v_codecurr,rate,tanggal FROM masterrate WHERE v_codecurr = 'PAJAK'
			)MR ON FH.date_journal = MR.tanggal
			INNER JOIN(
				SELECT n_id,v_idjournal,v_novoucher,v_fakturpajak FROM fin_journalheaderdetail WHERE v_novoucher !=''
			)J_HELP ON J_HELP.v_idjournal = FH.id_journal
		WHERE 1=1 AND FD_CREDIT.id_coa='{$id_coa}' AND FH.fg_post='2' AND FH.date_journal >= '{$d_from}' AND FH.date_journal <= '{$d_to}'
		GROUP BY FH.id_journal
)tmp ORDER BY tmp.date_journal)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (


	 X.id_coa                   LIKE'%".$searchValue."%'
	OR X.nm_coa                   LIKE'%".$searchValue."%'
	OR X.debit_idr			      LIKE'%".$searchValue."%'
	OR X.debit_usd                LIKE'%".$searchValue."%'
	OR X.credit_idr			      LIKE'%".$searchValue."%'
	OR X.credit_usd                LIKE'%".$searchValue."%'	
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
              	
			X.id_coa                  	
			,X.nm_coa    
			,X.debit_idr	
			,X.debit_usd
			,X.credit_idr                    	
			,X.credit_usd	
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
//echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];


   $data[] = array(
"id_coa"=>htmlspecialchars($row['id_coa']),
"nm_coa"=>htmlspecialchars($row['nm_coa']),
"debit_idr"=>htmlspecialchars(number_format((float)$row['debit_idr'], 2, '.', ',')),
"debit_usd"=>htmlspecialchars(number_format((float)$row['debit_usd'], 2, '.', ',')),
"credit_idr"=>htmlspecialchars(number_format((float)$row['credit_idr'], 2, '.', ',')),
"credit_usd"=>htmlspecialchars(number_format((float)$row['credit_usd'], 2, '.', ',')),

/* "price"=>htmlspecialchars(number_format((float)$row['price'], 2, ',', '.')),
"dpp"=>htmlspecialchars(number_format((float)$row['dpp'], 2, ',', '.')),
"ppn"=>htmlspecialchars(number_format((float)$row['ppn'], 2, ',', '.')),
"after_ppn"=>htmlspecialchars(number_format((float)$row['after_ppn'] 2, ',', '.'))
 */
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