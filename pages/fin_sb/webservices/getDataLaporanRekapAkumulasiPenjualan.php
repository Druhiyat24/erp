<?php 
	function d_from($from){
		$d_from = explode("/",$from."/01");
		$d_from = $d_from[1]."-".$d_from[0]."-".$d_from[2];
		return $d_from;
	} 
	// function d_to($to){
	// 	$d_to = explode("/",$to."/01");
	// 	$d_to = $d_to[1]."-".$d_to[0]."-".$d_to[2];
	// 	$d_to = date("Y-m-d", strtotime("1 month",strtotime($d_to)));
	// 	$d_to = date("Y-m-d", strtotime("-1 days",strtotime($d_to)));
	// 	return $d_to;
	// }

	// function dt_awal($from){
	// 	$dt_awal = explode("/",$from);
	// 	$dt_awal = $dt_awal[0];
	// 	return $dt_awal;
	// }

	// function dt_akhir($to){
	// 	$dt_akhir = explode("/",$to);
	// 	$dt_akhir = $dt_akhir[0];
	// 	return $dt_akhir;
	// }

include __DIR__ .'/../../../include/conn.php';
## Read value
$data = $_GET;
$from = $_GET['from'];

// print_r($from);die();
// $to = $_GET['to'];
// $d_from = d_from($from);

// print_r($from);die();
// $d_to = d_to($to);

// $awal = dt_awal($from);
// $akhir = dt_akhir($to);
// $count = $akhir - $awal + 1;
// // print_r($count);die();

// $awal_period = $awal;
// $akhir_period = $akhir;
// for($i=0;$i<$count;$i++){
// 	if($i=='0'){
// 		array_push($awal_period,$d_from);
// 	}
// 	else{
// 		$month = $awal_period;
// 		array_push($data_tgl = array(
// 			"from"=>$d_from,
// 			"to"=>$d_to
// 		));
// 	}
// 	$awal_period = $awal_period + 1;
// }

// // $populasi_period = array($data_tgl);

// // print_r($data_tgl);die();

// $begin = new DateTime($d_fromi);
// $end = new DateTime($d_toi);

// $interval = DateInterval::createFromDateString('1 month');
// $period = new DatePeriod($begin, $interval, $end);

// $interval2 = DateInterval::createFromDateString('30 day');
// $period2 = new DatePeriod($begin, $interval2, $end);

// foreach ($period as $fromi) {
//     $fromi->format("Y-m-d \n");
// }

// // echo "<br><p>PEMISAH</p><br>";

// foreach ($period2 as $toi) {
//     $toi->format("Y-m-d \n");
// }

// print_r($fromi);die();




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
	SELECT 
		SUP.*
	FROM (

		SELECT 
			CUST.coa,
			CUST.id_konsumen,
			CUST.nama_konsumen,
			CUST.id_supplier,		
			'0' AS rata,
			'0' AS total
		FROM (
			SELECT 
				JD.id_coa AS coa,
				SUP.supplier_code AS id_konsumen,
				SUP.Supplier AS nama_konsumen,
				SUP.id_supplier
			FROM (
				SELECT 
					jh.id_journal,
					jh.date_journal,
					jh.type_journal,
					jh.reff_doc
				FROM fin_journal_h AS jh 
			) AS JH 
			INNER JOIN (
				SELECT 
					jd.id_journal,
					jd.id_coa,
					jd.nm_coa,
					jd.debit 
				FROM fin_journal_d AS jd
				WHERE jd.debit > 0 AND nm_coa NOT LIKE '%POTONGAN%'
			) AS JD ON JD.id_journal = JH.id_journal
			INNER JOIN (
				SELECT 
					vh.id,
					vh.invno,
					vh.id_buyer 
				FROM invoice_header AS vh
			) AS VH ON VH.invno = JH.reff_doc
			INNER JOIN (
				SELECT 
					sup.Id_Supplier id_supplier,
					sup.supplier_code,
					sup.Supplier 
				FROM mastersupplier AS sup
			) AS SUP ON SUP.Id_Supplier = VH.id_buyer
			WHERE JH.type_journal = '1'
		) AS CUST GROUP BY CUST.coa,CUST.id_supplier ORDER BY nama_konsumen,CUST.coa ASC
	)SUP
			
	GROUP BY SUP.id_supplier,SUP.coa
)X";

function get_Nilai($from,$bulan,$id_supplier,$id_coa){
	include _DIR_ .'/../../../include/conn.php';


		//IF TERNARY
		$month = ($bulan < 9 ? '0'.$bulan:$bulan);


	$q	= "
	
	SELECT 
		SUP.*
		,SUM(ifnull(BULAN.total_debit_perjurnal,0)) AS bulan
	FROM (

		SELECT 
			CUST.coa,
			CUST.id_konsumen,
			CUST.nama_konsumen,
			CUST.id_supplier,		
			'0' AS rata,
			'0' AS total
		FROM (
			SELECT 
				JD.id_coa AS coa,
				SUP.supplier_code AS id_konsumen,
				SUP.Supplier AS nama_konsumen,
				SUP.id_supplier
			FROM (
				SELECT 
					jh.id_journal,
					jh.date_journal,
					jh.type_journal,
					jh.reff_doc
				FROM fin_journal_h AS jh 
			) AS JH 
			INNER JOIN (
				SELECT 
					jd.id_journal,
					jd.id_coa,
					jd.nm_coa,
					jd.debit 
				FROM fin_journal_d AS jd
				WHERE jd.debit > 0 AND nm_coa NOT LIKE '%POTONGAN%'
			) AS JD ON JD.id_journal = JH.id_journal
			INNER JOIN (
				SELECT 
					vh.id,
					vh.invno,
					vh.id_buyer 
				FROM invoice_header AS vh
			) AS VH ON VH.invno = JH.reff_doc
			INNER JOIN (
				SELECT 
					sup.Id_Supplier id_supplier,
					sup.supplier_code,
					sup.Supplier 
				FROM mastersupplier AS sup
			) AS SUP ON SUP.Id_Supplier = VH.id_buyer
			WHERE JH.type_journal = '1'
		) AS CUST GROUP BY CUST.coa,CUST.id_supplier ORDER BY nama_konsumen,CUST.coa ASC
	)SUP
			
	LEFT JOIN(
		SELECT   
			A.id_journal
			,B.id_coa
			,A.date_journal
			,A.type_journal
			,A.reff_doc
			,B.total_debit_perjurnal
			,D.id_supplier
			,D.konsumen
		FROM fin_journal_h A 
		INNER JOIN(
			SELECT  
				id_journal
				,id_coa
				,nm_coa
				,SUM(debit)total_debit_perjurnal
			FROM fin_journal_d WHERE debit > 0 AND nm_coa NOT LIKE '%POTONGAN%'
			GROUP BY id_journal,id_coa
		)B ON A.id_journal = B.id_journal
		INNER JOIN(
			SELECT 
				invno,
				id,
				id_buyer 
			FROM invoice_header
			WHERE 1=1
		)C ON A.reff_doc = C.invno
		INNER JOIN(
			SELECT 
				Id_Supplier id_supplier,
				Supplier konsumen 
			FROM mastersupplier WHERE 1=1
		)D ON C.id_buyer = D.Id_Supplier
			
		WHERE A.type_journal = '1' AND 
		A.date_journal >='$from-$month-01' AND A.date_journal <='$from-$month-31'
			
	)BULAN ON SUP.id_supplier = BULAN.id_supplier AND SUP.coa = BULAN.id_coa
			WHERE SUP.id_supplier = '$id_supplier' AND SUP.coa = '$id_coa'
	GROUP BY SUP.id_supplier,SUP.coa
	";

	$stmt = mysql_query($q);
	$bulan_total = 0;
		while($row = mysql_fetch_array($stmt)){
			$bulan_total = $row['bulan'];
		}
	// print_r($q);die();
	return $bulan_total;
}


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (

	   X.coa				LIKE'%".$searchValue."%'
	OR X.id_konsumen		LIKE'%".$searchValue."%'
	OR X.nama_konsumen		LIKE'%".$searchValue."%'
	OR X.jan				LIKE'%".$searchValue."%'
	OR X.feb				LIKE'%".$searchValue."%'
	OR X.mar				LIKE'%".$searchValue."%'
	OR X.apr				LIKE'%".$searchValue."%'
	OR X.mei				LIKE'%".$searchValue."%'
	OR X.jun				LIKE'%".$searchValue."%'
	OR X.jul				LIKE'%".$searchValue."%'
	OR X.ags				LIKE'%".$searchValue."%'
	OR X.sep				LIKE'%".$searchValue."%'
	OR X.okt				LIKE'%".$searchValue."%'
	OR X.nov				LIKE'%".$searchValue."%'
	OR X.des				LIKE'%".$searchValue."%'
	OR X.rata				LIKE'%".$searchValue."%'
	OR X.total				LIKE'%".$searchValue."%'
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
	X.coa
	,X.id_supplier
	,X.id_konsumen
	,X.nama_konsumen
	,X.rata
	,X.total
";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();

// echo $empQuery;
$no = 1;

	$total_jan = '0';
	$total_feb = '0';
	$total_mrt = '0';
	$total_apr = '0';
	$total_mei = '0';
	$total_jun = '0';
	$total_jul = '0';
	$total_ags = '0';
	$total_sep = '0';
	$total_okt = '0';
	$total_nov = '0';
	$total_des = '0';

	$fixed_rata = '0';
	$fixed_total = '0';

while ($row = mysqli_fetch_assoc($empRecords)) {
	$jan = '0';
	$feb = '0';
	$mrt = '0';
	$apr = '0';
	$mei = '0';
	$jun = '0';
	$jul = '0';
	$ags = '0';
	$sep = '0';
	$okt = '0';
	$nov = '0';
	$des = '0';

	$rata = '0';
	$total = '0';

//echo $row['n_post'];

	for($i=1;$i<=12;$i++){
		$jan = ($i == '1' ?  get_Nilai($from,$i,$row['id_supplier'],$row['coa']) : $jan );
		$feb = ($i == '2' ?  get_Nilai($from,$i,$row['id_supplier'],$row['coa']) : $feb );
		$mrt = ($i == '3' ?  get_Nilai($from,$i,$row['id_supplier'],$row['coa']) : $mrt );
		$apr = ($i == '4' ?  get_Nilai($from,$i,$row['id_supplier'],$row['coa']) : $apr );
		$mei = ($i == '5' ?  get_Nilai($from,$i,$row['id_supplier'],$row['coa']) : $mei );
		$jun = ($i == '6' ?  get_Nilai($from,$i,$row['id_supplier'],$row['coa']) : $jun );
		$jul = ($i == '7' ?  get_Nilai($from,$i,$row['id_supplier'],$row['coa']) : $jul );
		$ags = ($i == '8' ?  get_Nilai($from,$i,$row['id_supplier'],$row['coa']) : $ags );
		$sep = ($i == '9' ?  get_Nilai($from,$i,$row['id_supplier'],$row['coa']) : $sep );
		$okt = ($i == '10' ?  get_Nilai($from,$i,$row['id_supplier'],$row['coa']) : $okt );
		$nov = ($i == '11' ?  get_Nilai($from,$i,$row['id_supplier'],$row['coa']) : $nov );
		$des = ($i == '12' ?  get_Nilai($from,$i,$row['id_supplier'],$row['coa']) : $nov );
		// print_r($feb);die();
	}

	$rata = ($jan + $feb + $mrt + $apr + $mei + $jun + $jul + $ags + $sep + $okt + $nov + $des) / 12;
	$total = $jan + $feb + $mrt + $apr + $mei + $jun + $jul + $ags + $sep + $okt + $nov + $des;

	$data[] = array(
		"no"=>$no,
		"coa"=>htmlspecialchars($row['coa']),
		"id_konsumen"=>htmlspecialchars($row['id_konsumen']),
		"nama_konsumen"=>htmlspecialchars($row['nama_konsumen']),
		"jan"=>htmlspecialchars((number_format($jan, 2, '.', ','))),
		"feb"=>htmlspecialchars((number_format($feb, 2, '.', ','))),
		"mar"=>htmlspecialchars((number_format($mrt, 2, '.', ','))),
		"apr"=>htmlspecialchars((number_format($apr, 2, '.', ','))),
		"mei"=>htmlspecialchars((number_format($mei, 2, '.', ','))),
		"jun"=>htmlspecialchars((number_format($jun, 2, '.', ','))),
		"jul"=>htmlspecialchars((number_format($jul, 2, '.', ','))),
		"ags"=>htmlspecialchars((number_format($ags, 2, '.', ','))),
		"sep"=>htmlspecialchars((number_format($sep, 2, '.', ','))),
		"okt"=>htmlspecialchars((number_format($okt, 2, '.', ','))),
		"nov"=>htmlspecialchars((number_format($nov, 2, '.', ','))),
		"des"=>htmlspecialchars((number_format($des, 2, '.', ','))),
		"rata-rata"=>htmlspecialchars((number_format($rata, 2, '.', ','))),
		"total"=>htmlspecialchars((number_format($total, 2, '.', ',')))
	);
	$no++;

	$total_jan = $total_jan + $jan;
	$total_feb = $total_feb + $feb;
	$total_mrt = $total_mrt + $mrt;
	$total_apr = $total_apr + $apr;
	$total_mei = $total_mei + $mei;
	$total_jun = $total_jun + $jun;
	$total_jul = $total_jul + $jul;
	$total_ags = $total_ags + $ags;
	$total_sep = $total_sep + $sep;
	$total_okt = $total_okt + $okt;
	$total_nov = $total_nov + $nov;
	$total_des = $total_des + $des;

	$fixed_rata = $fixed_rata + $rata;
	$fixed_total = $fixed_total + $total;
}


$data[] = array(
	"no"=>htmlspecialchars(""),
	"coa"=>htmlspecialchars(""),
	"id_konsumen"=>htmlspecialchars(""),
	"nama_konsumen"=>htmlspecialchars("TOTAL :"),
	"jan"=>htmlspecialchars((number_format($total_jan, 2, '.', ','))),
	"feb"=>htmlspecialchars((number_format($total_feb, 2, '.', ','))),
	"mar"=>htmlspecialchars((number_format($total_mrt, 2, '.', ','))),
	"apr"=>htmlspecialchars((number_format($total_apr, 2, '.', ','))),
	"mei"=>htmlspecialchars((number_format($total_mei, 2, '.', ','))),
	"jun"=>htmlspecialchars((number_format($total_jun, 2, '.', ','))),
	"jul"=>htmlspecialchars((number_format($total_jul, 2, '.', ','))),
	"ags"=>htmlspecialchars((number_format($total_ags, 2, '.', ','))),
	"sep"=>htmlspecialchars((number_format($total_sep, 2, '.', ','))),
	"okt"=>htmlspecialchars((number_format($total_okt, 2, '.', ','))),
	"nov"=>htmlspecialchars((number_format($total_nov, 2, '.', ','))),
	"des"=>htmlspecialchars((number_format($total_des, 2, '.', ','))),
	"rata-rata"=>htmlspecialchars((number_format($fixed_rata, 2, '.', ','))),
	"total"=>htmlspecialchars((number_format($fixed_total, 2, '.', ',')))
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