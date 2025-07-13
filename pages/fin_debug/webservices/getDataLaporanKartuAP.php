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
$d_to   =date("Y-m-d", strtotime($data['to']));   
$d_saldo = date('Y-m-d', strtotime('-1 days', strtotime($d_from)));

$id_coa = SUBSTR($data['id_coa'],0,5);
$sql3 = "AND fjd.id_coa = '{$id_coa}' AND fjh.fg_post='2' AND fjh.date_journal  >= '".$d_from."' AND fjh.date_journal <= '".$d_to."'";
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
// $columnIndex = $_POST['order'][0]['column']; // Column index
// $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
// $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(
	SELECT if(tmp.debit_idr > 0, tmp.debit_idr,(-1*tmp.credit_idr))saldo_pengaruh,
		if(tmp.debit_ori > 0, tmp.debit_ori,(-1*tmp.credit_ori))saldo_pengaruh_ori,
		tmp.* 
	FROM(
		SELECT 
			fjd.id_journal,
			fjd.dateadd,
			fjh.date_journal,
			IF(bpb.bpbno='','N/A',bpb.bpbno)bpbno,
			fjh.reff_doc,
			''reff_doc2,
			IF(fjhd.v_novoucher='','N/A',fjhd.v_novoucher)v_novoucher,
			IF(fjd.curr='USD' OR fjd.curr='',(fjd.debit),0 )debit_ori,
			IF(fjd.curr='USD' OR fjd.curr='',(fjd.credit),0 )credit_ori,
			IF(fjd.curr='USD' OR fjd.curr='',(fjd.debit*mr.rate),fjd.debit )debit_idr,
    		IF(fjd.curr='USD' OR fjd.curr='',(fjd.credit*mr.rate),fjd.credit )credit_idr,
			fjd.description,
			fjh.type_journal,
			fjd.id_coa,
			IF(fjd.curr='USD' OR fjd.curr='','USD','IDR')curr,
			mr.rate,		
			MS.Supplier,
			MS.supplier_code
		FROM fin_journal_d fjd 
		LEFT JOIN fin_journal_h fjh ON fjd.id_journal = fjh.id_journal 
		LEFT JOIN bpb ON fjh.reff_doc = bpb.bpbno_int 
		LEFT JOIN fin_journalheaderdetail fjhd ON fjh.id_journal = fjhd.v_idjournal 
		LEFT JOIN (
			SELECT v_codecurr,
				rate,
				tanggal 
			FROM masterrate WHERE v_codecurr = 'PAJAK'
		) mr ON mr.tanggal = fjh.date_journal						
		LEFT JOIN mastersupplier MS ON bpb.id_supplier = MS.Id_Supplier
		LEFT JOIN mastercoa mc ON mc.id_coa=fjd.id_coa
		LEFT JOIN fin_history_saldo fhs ON fhs.n_idcoa=fjd.id_coa
		WHERE 1=1 
	
		$sql3 
	
		GROUP BY fjh.id_journal

		UNION ALL
	
		SELECT
	 		fjd.id_journal,
			fjd.dateadd,
			fjh.date_journal,
			'N/A' bpbno,
			fjd.reff_doc2 reff_doc,
			fjd.reff_doc2,
			'N/A' v_novoucher,
			IF(fjd.curr='USD' OR fjd.curr='',(SUM(fjd.debit)),0 )debit_ori,
			IF(fjd.curr='USD' OR fjd.curr='',(SUM(fjd.credit)),0 )credit_ori,
			IF(fjd.curr='USD' OR fjd.curr='',(SUM(fjd.debit)*mr.rate),fjd.debit )debit_idr,
    		IF(fjd.curr='USD' OR fjd.curr='',(SUM(fjd.credit)*mr.rate),fjd.credit )credit_idr,
			fjd.description,
			fjh.type_journal,
			fjd.id_coa,
			IF(fjd.curr='USD' OR fjd.curr='','USD','IDR')curr,
			mr.rate,
			MS.Supplier,
			MS.supplier_code
		FROM fin_journal_d fjd
		LEFT JOIN (
			SELECT * FROM fin_journal_h WHERE 1=1 AND type_journal='14'
		)fjh ON fjd.id_journal = fjh.id_journal 
		LEFT JOIN (
			SELECT v_codecurr,
				rate,
				tanggal 
			FROM masterrate WHERE v_codecurr = 'PAJAK'
		) mr ON mr.tanggal = fjh.date_journal						
		LEFT JOIN mastersupplier MS ON fjd.id_supplier = MS.Id_Supplier	
		LEFT JOIN fin_journal_h f_ref ON fjd.reff_doc2 = f_ref.id_journal
		LEFT JOIN mastercoa mc ON mc.id_coa=fjd.id_coa
		LEFT JOIN fin_history_saldo fhs ON fhs.n_idcoa=fjd.id_coa
		WHERE 1=1 $sql3 
		GROUP BY fjd.reff_doc2
	)tmp ORDER BY tmp.date_journal ASC
)X";
## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (
X.id_journal						LIKE '%".$searchValue."%'
X.date_journal                      LIKE '%".$searchValue."%'
X.v_novoucher                      	LIKE '%".$searchValue."%'
X.description                      	LIKE '%".$searchValue."%'
X.debit_ori                      	LIKE '%".$searchValue."%'
X.credit_ori                      	LIKE '%".$searchValue."%'
X.rate                     			LIKE '%".$searchValue."%'
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
			X.date_journal
			,X.id_journal
			,X.bpbno
			,X.v_novoucher
			,X.description
			,X.debit_ori
			,X.credit_ori
			,X.saldo_pengaruh_ori
			,X.rate
			,X.debit_idr
			,X.credit_idr
			,X.saldo_pengaruh
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
// echo $empQuery;
// die();
	$saldo_berjalan_ori = 0;
	$saldo_berjalan_idr = 0;
$d_daily_from = '2019-11-01';
if($d_from == '2019-11-01'){
	$d_saldo = $d_daily_from;
}
if($d_from < '2019-11-01'){
	$saldo_berjalan_ori = 0;
	$saldo_berjalan_idr = 0;
}
else{
	$empQuery_saldo = "SELECT H.n_idcoa,
							H.v_namacoa,
							(H.n_saldo + D.saldo_idr)saldo_awal_idr,
							(H.n_saldo + D.saldo_usd)saldo_awal_usd
						FROM fin_history_saldo H
						INNER JOIN (
							SELECT id_coa,
								SUM(saldo_idr)saldo_idr,
								SUM(saldo_usd)saldo_usd 
							FROM fin_daily_saldo 
							WHERE 1=1 
							AND id_coa = '{$id_coa}'
							AND d_daily >='{$d_daily_from}'
							AND d_daily <= '{$d_saldo}'
							GROUP BY id_coa
						)D ON H.n_idcoa = '{$id_coa}' AND DATE(H.d_dateupdate) = '2019-10-31'"; 
// echo $empQuery_saldo;
// die();

	$empRecords_saldo = mysqli_query($conn_li, $empQuery_saldo);	
	while ($row_saldo = mysqli_fetch_assoc($empRecords_saldo)) {
		$saldo_berjalan_ori = $row_saldo['saldo_awal_usd'];
		$saldo_berjalan_idr = $row_saldo['saldo_awal_idr'];
		// print_r($saldo_berjalan);die();
	}
}
		   
$data[] = array(
	"date_journal"=>htmlspecialchars(""),
	"id_journal"=>htmlspecialchars(""),
	"bpbno"=>htmlspecialchars(""),
	"v_novoucher"=>htmlspecialchars(""),
	"description"=>htmlspecialchars("Saldo Awal :"),
	"debit_ori"=>htmlspecialchars(""),
	"credit_ori"=>htmlspecialchars(""),
	// "saldo_usd"=>htmlspecialchars((number_format($row['saldo_usd'], 2, '.', ','))),
	"saldo_ori"=>htmlspecialchars(number_format((float)$saldo_berjalan_ori, 2, '.', ',')),
	"rate"=>htmlspecialchars(""),
	"debit_idr"=>htmlspecialchars(""),
	"credit_idr"=>htmlspecialchars(""),
	// "saldo_idr"=>htmlspecialchars((number_format($row['saldo_idr'], 2, '.', ','))),
	"saldo_idr"=>htmlspecialchars(number_format((float)$saldo_berjalan_idr, 2, '.', ','))
);

$no = 1;
$total_debit = 0;
$total_credit = 0;
while ($row = mysqli_fetch_assoc($empRecords)) {
	$total_debit = $total_debit + $row['debit_ori'];
	$total_credit = $total_credit + $row['credit_ori'];
	
	//echo $row['n_post'];
	$saldo_berjalan_idr = $saldo_berjalan_idr + $row['saldo_pengaruh'];
	$saldo_berjalan_ori = $saldo_berjalan_ori + $row['saldo_pengaruh_ori'];

   	$data[] = array(
		// "no"=>$no,
		"date_journal"=>htmlspecialchars($row['date_journal']),
		"id_journal"=>htmlspecialchars($row['id_journal']),
		"bpbno"=>htmlspecialchars($row['bpbno']),
		"v_novoucher"=>htmlspecialchars($row['v_novoucher']),
		"description"=>htmlspecialchars($row['description']),
		"debit_ori"=>htmlspecialchars((number_format($row['debit_ori'], 2, '.', ','))),
		"credit_ori"=>htmlspecialchars((number_format($row['credit_ori'], 2, '.', ','))),
		// "saldo_usd"=>htmlspecialchars((number_format($row['saldo_usd'], 2, '.', ','))),
		"saldo_ori"=>htmlspecialchars(number_format((float)$saldo_berjalan_ori, 2, '.', ',')),
		"rate"=>htmlspecialchars((number_format($row['rate'], 2, '.', ','))),
		"debit_idr"=>htmlspecialchars((number_format($row['debit_idr'], 2, '.', ','))),
		"credit_idr"=>htmlspecialchars((number_format($row['credit_idr'], 2, '.', ','))),
		// "saldo_idr"=>htmlspecialchars((number_format($row['saldo_idr'], 2, '.', ','))),
		"saldo_idr"=>htmlspecialchars(number_format((float)$saldo_berjalan_idr, 2, '.', ','))
   );
//    $no++;

}

$data[] = array(
	"date_journal"=>htmlspecialchars(""),
	"id_journal"=>htmlspecialchars(""),
	"bpbno"=>htmlspecialchars(""),
	"v_novoucher"=>htmlspecialchars(""),
	"description"=>htmlspecialchars("Saldo Akhir :"),
	"debit_ori"=>htmlspecialchars(""),
	"credit_ori"=>htmlspecialchars(""),
	// "saldo_usd"=>htmlspecialchars((number_format($row['saldo_usd'], 2, '.', ','))),
	"saldo_ori"=>htmlspecialchars(number_format((float)$saldo_berjalan_ori, 2, '.', ',')),
	"rate"=>htmlspecialchars(""),
	"debit_idr"=>htmlspecialchars(""),
	"credit_idr"=>htmlspecialchars(""),
	// "saldo_idr"=>htmlspecialchars((number_format($row['saldo_idr'], 2, '.', ','))),
	"saldo_idr"=>htmlspecialchars(number_format((float)$saldo_berjalan_idr, 2, '.', ','))
);

$data[] = array(
	"date_journal"=>htmlspecialchars(""),
	"id_journal"=>htmlspecialchars(""),
	"bpbno"=>htmlspecialchars(""),
	"v_novoucher"=>htmlspecialchars(""),
	"description"=>htmlspecialchars("TOTAL MUTASI :"),
	"debit_ori"=>htmlspecialchars(number_format((float)$total_debit, 2, '.', ',')),
	"credit_ori"=>htmlspecialchars(number_format((float)$total_credit, 2, '.', ',')),
	// "saldo_usd"=>htmlspecialchars((number_format($row['saldo_usd'], 2, '.', ','))),
	"saldo_ori"=>htmlspecialchars(""),
	"rate"=>htmlspecialchars(""),
	"debit_idr"=>htmlspecialchars(""),
	"credit_idr"=>htmlspecialchars(""),
	// "saldo_idr"=>htmlspecialchars((number_format($row['saldo_idr'], 2, '.', ','))),
	"saldo_idr"=>htmlspecialchars("")
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