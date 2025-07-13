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

$id_coa = $data['idcoa'];
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

SELECT if(tmp.debitidr > 0, tmp.debitidr,(-1*tmp.creditidr))saldo_pengaruh
	   ,if(tmp.debitori > 0, tmp.debitori,(-1*tmp.creditori))saldo_pengaruh_ori

		,tmp.* FROM(
		SELECT

									fjd.id_journal,

									fjh.date_journal,
									
									ms.tipe_sup,
									
									ms.Supplier,

									IFNULL(if(fjh.reff_doc='','-',fjh.reff_doc),'-')reff_doc,
									
									IFNULL(if(fjhd.v_novoucher='','-',fjhd.v_novoucher),'-')v_novoucher,

									if(fjd.curr='USD',fjd.debit,0)debitori,
									if(fjd.curr='USD',fjd.credit,0)creditori,
									
									fhs.n_saldo,

									if(fjd.curr='USD',mr.rate*fjd.debit,fjd.debit)debitidr,
									if(fjd.curr='USD',mr.rate*fjd.credit,fjd.credit)creditidr,									
									

									fjh.type_journal,

									fjd.id_coa,

									fjd.curr,

									mr.rate							

									FROM fin_journal_d fjd 
									LEFT JOIN fin_journal_h fjh ON fjd.id_journal = fjh.id_journal 
									INNER JOIN (SELECT * FROM invoice_header WHERE n_post='2')  
									invoice ON invoice.invno = fjh.reff_doc
									LEFT JOIN mastersupplier ms ON invoice.id_buyer = ms.Id_Supplier
									LEFT JOIN fin_journalheaderdetail fjhd ON fjh.id_journal = fjhd.v_idjournal 
									LEFT JOIN masterrate mr ON mr.tanggal = fjh.date_journal 
									LEFT JOIN mastercoa mc ON mc.id_coa = fjd.id_coa
									LEFT JOIN fin_history_saldo fhs ON fhs.n_idcoa = fjd.id_coa		
									
									WHERE ms.tipe_sup='C' AND fjh.type_journal='1' AND fjd.id_coa='{$id_coa}'

									AND fjh.date_journal >= '{$d_from}' AND fjh.date_journal <= '{$d_to}' 
																		
									GROUP BY fjh.id_journal
									
									ORDER BY fjh.date_journal asc
								
											)tmp ORDER BY tmp.date_journal ASC
									)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (

	   X.date_journal             LIKE'%".$searchValue."%'
	OR X.id_journal               LIKE'%".$searchValue."%'
	OR X.reff_doc              LIKE'%".$searchValue."%'
	OR X.v_novoucher                   LIKE'%".$searchValue."%'
	OR X.Supplier                   LIKE'%".$searchValue."%'
	OR X.debitori             LIKE'%".$searchValue."%'
	OR X.creditori             LIKE'%".$searchValue."%'
	OR X.saldo_pengaruh_ori            LIKE'%".$searchValue."%'
	OR X.rate				  LIKE'%".$searchValue."%'
	OR X.debitidr			      LIKE'%".$searchValue."%'
	OR X.creditidr               LIKE'%".$searchValue."%'
	OR X.saldo_pengaruh               LIKE'%".$searchValue."%'
	
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
			,X.reff_doc                 	
			,X.v_novoucher                  	
			,X.Supplier              	
			,X.debitori
			,X.creditori                   	
			,X.saldo_pengaruh_ori
			,X.rate				 
			,X.debitidr			 
			,X.creditidr  
			,X.saldo_pengaruh
			";


$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by X.date_journal limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();

$d_daily_from = '2019-11-01';
if($d_from == '2019-11-01'){
	$d_saldo = $d_daily_from;
}
if($d_from < '2019-11-01'){
	$saldo_berjalan = 0;
	$saldo_berjalan_ori = 0;
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
GROUP BY id_coa)D ON H.n_idcoa = '{$id_coa}' AND DATE(H.d_dateupdate) = '2019-10-31'";/* 
echo $empQuery_saldo;
die(); */

$empRecords_saldo = mysqli_query($conn_li, $empQuery_saldo);	
while ($row_saldo = mysqli_fetch_assoc($empRecords_saldo)) {
	$saldo_berjalan = $row_saldo['saldo_awal'];
	$saldo_berjalan_ori = $row_saldo['saldo_awal'];

   $data[] = array(
"date_journal"=>htmlspecialchars(""),
"id_journal"=>htmlspecialchars(""),
"reff_doc"=>htmlspecialchars(""),
"v_novoucher"=>htmlspecialchars(""),
"Supplier"=>htmlspecialchars("Saldo Awal:"),
"debitori"=>htmlspecialchars(""),
"creditori"=>htmlspecialchars(""),
"saldo_berjalan_ori"=>htmlspecialchars(number_format((float)$saldo_berjalan_ori, 2, '.', ',')),
"rate"=>htmlspecialchars(""),
"debitidr"=>htmlspecialchars(""), //saldo_awal
"creditidr"=>htmlspecialchars(""),
"saldo_berjalan"=>htmlspecialchars(number_format((float)$saldo_berjalan, 2, '.', ',')),

   );

	
}
}


//echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];
$saldo_berjalan = $saldo_berjalan + $row['saldo_pengaruh'];
$saldo_berjalan_ori = $saldo_berjalan_ori + $row['saldo_pengaruh_ori'];

   
   $data[] = array(
"date_journal"=>htmlspecialchars($row['date_journal']),               
"id_journal"=>htmlspecialchars($row['id_journal']),
"reff_doc"=>htmlspecialchars($row['reff_doc']),
"v_novoucher"=>htmlspecialchars($row['v_novoucher']),
"Supplier"=>htmlspecialchars($row['Supplier']),
"debitori"=>htmlspecialchars(number_format((float)$row['debitori'], 2, '.', ',')),
"creditori"=>htmlspecialchars(number_format((float)$row['creditori'], 2, '.', ',')),
"saldo_berjalan_ori"=>htmlspecialchars(number_format((float)$saldo_berjalan_ori, 2, '.', ',')),
"rate"=>htmlspecialchars(number_format((float)$row['rate'], 0, '.', ',')),
"debitidr"=>htmlspecialchars(number_format((float)$row['debitidr'], 2, '.', ',')),
"creditidr"=>htmlspecialchars(number_format((float)$row['creditidr'], 2, '.', ',')),
"saldo_berjalan"=>htmlspecialchars(number_format((float)$saldo_berjalan, 2, '.', ',')),
   );
}

   $data[] = array(
"date_journal"=>htmlspecialchars(""),
"id_journal"=>htmlspecialchars(""),
"reff_doc"=>htmlspecialchars(""),
"v_novoucher"=>htmlspecialchars(""),
"Supplier"=>htmlspecialchars("Saldo Akhir :"),
"debitori"=>htmlspecialchars(""),
"creditori"=>htmlspecialchars(""),
"saldo_berjalan_ori"=>htmlspecialchars(number_format((float)$saldo_berjalan_ori, 2, '.', ',')),
"rate"=>htmlspecialchars(""),
"debitidr"=>htmlspecialchars(""), //saldo_awal
"creditidr"=>htmlspecialchars(""),
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