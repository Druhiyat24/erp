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
	function d_saldo($from){
		$d_from = explode("/",$from."/01");
		$d_from = $d_from[1]."-".$d_from[0]."-".$d_from[2];
		$d_saldo = date('Y-m-d', strtotime('+1 days', strtotime($d_from))); //operasi penjumlahan tanggal sebanyak 6 hari
		return $d_saldo;
	}


include __DIR__ .'/../../../include/conn.php';
## Read value
$data = $_GET;
$d_from =date("Y-m-d", strtotime($data['from']));
$d_to  =date("Y-m-d", strtotime($data['to']));
$d_saldo = date('Y-m-d', strtotime('-1 days', strtotime($d_from)));

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

SELECT if(tmp.debit_idr > 0, tmp.debit_idr,(-1*tmp.credit_idr))saldo_pengaruh
		,tmp.* FROM(
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
)tmp ORDER BY tmp.date_journal ASC)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (

	   X.date_journal             LIKE'%".$searchValue."%'
	OR X.id_journal               LIKE'%".$searchValue."%'
	OR X.v_novoucher              LIKE'%".$searchValue."%'
	OR X.id_coa                   LIKE'%".$searchValue."%'
	OR X.nm_coa                   LIKE'%".$searchValue."%'
	OR X.id_coa_lawan             LIKE'%".$searchValue."%'
	OR X.nm_coa_lawan             LIKE'%".$searchValue."%'
	OR X.v_fakturpajak            LIKE'%".$searchValue."%'
	OR X.reff_doc				  LIKE'%".$searchValue."%'
	OR X.debit_idr			      LIKE'%".$searchValue."%'
	OR X.credit_idr               LIKE'%".$searchValue."%'
	
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
			 X.date_journal                   
			,X.id_journal            	
			,X.v_novoucher                 	
			,X.id_coa                  	
			,X.nm_coa              	
			,X.id_coa_lawan
			,X.nm_coa_lawan                   	
			,X.v_fakturpajak
			,X.reff_doc				 
			,X.debit_idr			 
			,X.credit_idr  
			,X.saldo_pengaruh
			
			";
$order = "X.date_journal";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." ORDER BY ".$order." limit ".$row.",".$rowperpage;
/* echo $empQuery;
die(); */
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();

/* SALDO AWAL */
/* if($d_from = '2019-11-01'){
	$d_daily_from = 2019-11-01;
	$d_daily_to = 2019-11-01;
	
}else if(d_from = '2019-11-02'){
	$d_daily_from = 2019-11-01;
	$d_daily_to = 2019-11-01;	
	
} */
$d_daily_from = '2019-11-01';
if($d_from == '2019-11-01'){
	$d_saldo = $d_daily_from;
}
if($d_from < '2019-11-01'){
	$saldo_berjalan = 0;
}
else{
	
	if($d_from == '2019-11-01'){
$empQuery_saldo = "SELECT H.n_idcoa
	,H.v_namacoa
	,(H.n_saldo)saldo_awal
	FROM fin_history_saldo H WHERE 1=1
 AND DATE(H.d_dateupdate) = '2019-10-31'
 AND n_idcoa = '{$id_coa}'";
	}
	else{	
	
	
$empQuery_saldo = "SELECT H.n_idcoa
	,H.v_namacoa
	,(H.n_saldo + D.saldo)saldo_awal
	FROM fin_history_saldo H
INNER JOIN (SELECT id_coa
		,SUM(saldo_idr)saldo 
			FROM 
			fin_daily_saldo 
			WHERE 1=1 
			AND id_coa = '{$id_coa}'
			AND d_daily >='{$d_daily_from}'
			AND d_daily <= '{$d_saldo}'
GROUP BY id_coa)D ON H.n_idcoa = '{$id_coa}' AND DATE(H.d_dateupdate) = '2019-10-31'";
	}
/* echo $empQuery_saldo;
die();  */
$empRecords_saldo = mysqli_query($conn_li, $empQuery_saldo);	
while ($row_saldo = mysqli_fetch_assoc($empRecords_saldo)) {
	$saldo_berjalan = $row_saldo['saldo_awal'];

   $data[] = array(
"date_journal"=>htmlspecialchars(""),
"id_journal"=>htmlspecialchars(""),
"v_novoucher"=>htmlspecialchars(""),
"id_coa"=>htmlspecialchars(""),
"nm_coa"=>htmlspecialchars("Saldo Awal :"),
"id_coa_lawan"=>htmlspecialchars(""),
"nm_coa_lawan"=>htmlspecialchars(""),
"v_fakturpajak"=>htmlspecialchars(""),
"reff_doc"=>htmlspecialchars(""),
"debit_idr"=>htmlspecialchars(""), //saldo_awal
"credit_idr"=>htmlspecialchars(""),
"saldo_berjalan"=>htmlspecialchars(number_format((float)$saldo_berjalan, 2, '.', ',')),

   );


	
}
}




//echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];
$saldo_berjalan = $saldo_berjalan + $row['saldo_pengaruh'];

   $data[] = array(
"date_journal"=>htmlspecialchars($row['date_journal']),
"id_journal"=>htmlspecialchars($row['id_journal']),
"v_novoucher"=>htmlspecialchars($row['v_novoucher']),
"id_coa"=>htmlspecialchars($row['id_coa']),
"nm_coa"=>htmlspecialchars($row['nm_coa']),
"id_coa_lawan"=>htmlspecialchars($row['id_coa_lawan']),
"nm_coa_lawan"=>htmlspecialchars($row['nm_coa_lawan']),
"v_fakturpajak"=>htmlspecialchars($row['v_fakturpajak']),
"reff_doc"=>htmlspecialchars($row['reff_doc']),
"debit_idr"=>htmlspecialchars(number_format((float)$row['debit_idr'], 2, '.', ',')),
"credit_idr"=>htmlspecialchars(number_format((float)$row['credit_idr'], 2, '.', ',')),
"saldo_berjalan"=>htmlspecialchars(number_format((float)$saldo_berjalan, 2, '.', ',')),

   );
}

   $data[] = array(
"date_journal"=>htmlspecialchars(""),
"id_journal"=>htmlspecialchars(""),
"v_novoucher"=>htmlspecialchars(""),
"id_coa"=>htmlspecialchars(""),
"nm_coa"=>htmlspecialchars("Saldo Akhir :"),
"id_coa_lawan"=>htmlspecialchars(""),
"nm_coa_lawan"=>htmlspecialchars(""),
"v_fakturpajak"=>htmlspecialchars(""),
"reff_doc"=>htmlspecialchars(""),
"debit_idr"=>htmlspecialchars(""), //saldo_awal
"credit_idr"=>htmlspecialchars(""),
"saldo_berjalan"=>htmlspecialchars(number_format((float)$saldo_berjalan, 2, '.', ',')),

   );


## Response
$response = array(
  "draw" => intval($draw),
  "iTotalRecords" => $totalRecordwithFilter,
  "iTotalDisplayRecords" => $totalRecords,
  "aaData" => $data
);
echo json_encode($response);
?>