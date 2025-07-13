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
SELECT   INV.id
		,INV.invno
		,ifnull(INV_COM.bpbno,INV_DET.bppb)surat_jalan
		,INV.id_buyer
		,INV.id_pterms
		,MP.kode_pterms
		,MR.rate
		,MP.days_pterms
		,SO.so_no
		,'' id_order
		,INV.v_fakturpajak
		,'' tgl_fakturpajak
		,INV.n_post
		,INV.invdate
		,MS.supplier
		,MS.id_supplier
		,MS.vendor_cat
		,MS.supplier_code
		,MS.area
		,JH.id_journal
		,JH.date_journal
		,JH.reff_doc
		,JH.period
		,JD.id_coa
		,JD.nm_coa
		,SO.curr
		,if(SO.curr='USD',ifnull(JD.debit,0),0)debit_usd
		,if(SO.curr='USD',MR.rate*JD.debit,JD.debit)debit
		,if(SO.curr='USD',ifnull(JD_ETC.debit,0),0)lain_lain_usd
		,if(SO.curr='USD',MR.rate*ifnull(JD_ETC.debit,0),ifnull(JD_ETC.debit,0))lain_lain
		,ifnull(ALL_AR.id_journal,PN.id_journal) no_penerimaan
		,if(SO.curr='USD',MR.rate*( ifnull(ALL_AR_D.credit,ifnull(PN_D.credit,0)) ),ifnull(ALL_AR_D.credit,ifnull(PN_D.credit,0)))pengurangan
		,if(SO.curr='USD',ifnull(ALL_AR_D.credit,ifnull(PN_D.credit,0)),0)pengurangan_usd
		,'0' pengurangan_lain_lain
		,'0' pengurangan_lain_lain_usd
		,'0' retur_usd
		,'0' retur
		,'' nomor_kb
		,'' tgl_kb
		,'0' saldo_awal
		,'0' saldo_awal_usd
		,'0' saldo_akhir
		,'0' saldo_akhir_usd		
		,ALL_AR.fg_post post_ar
		,PN.fg_post post_pn
		,ifnull(ALL_AR.reff_doc,PN.reff_doc) ref_doc_pengurangan
		,ifnull(ALL_AR.date_journal,PN.date_journal) tgl_pengurangan
		,ifnull(ALL_AR_D.description,ALL_AR_D.description) keterangan_pengurangan
		,ifnull((365/(  ( (if(SO.curr='USD',MR.rate*JD.debit,JD.debit)) + (if(SO.curr='USD',MR.rate*ifnull(JD_ETC.debit,0),ifnull(JD_ETC.debit,0))) ) /2 ) ),0)ar_days	
         ,DATE_ADD(JH.date_journal, INTERVAL MP.days_pterms DAY)jatuh_tempo		
			FROM invoice_header INV
		LEFT JOIN (SELECT area,vendor_cat,Supplier supplier,Id_Supplier,supplier_code FROM mastersupplier)MS
		ON INV.id_buyer = MS.Id_Supplier
		LEFT JOIN (SELECT id_journal,reff_doc,type_journal,date_journal,period FROM fin_journal_h WHERE type_journal = '1')JH
		ON JH.reff_doc = INV.invno
		LEFT JOIN(SELECT id_coa,debit,nm_coa,id_journal FROM fin_journal_d WHERE debit > 0 
		AND nm_coa NOT LIKE '%POTONGAN%')JD
		ON JH.id_journal = JD.id_journal
		LEFT JOIN(SELECT id_coa,debit,nm_coa,id_journal FROM fin_journal_d WHERE debit > 0 
		AND nm_coa LIKE '%POTONGAN%')JD_ETC
		ON JH.id_journal = JD_ETC.id_journal
	LEFT JOIN(
		SELECT id_journal
						,id_rekap
						FROM fin_status_journal_ar
	)AR ON JH.id_journal = AR.id_journal
	
	LEFT JOIN(
		SELECT id_journal,reff_doc2,reff_doc,date_journal,fg_post FROM fin_journal_h WHERE type_journal = '13'  AND fg_post = '2'
	)PN ON PN.reff_doc2 = AR.id_rekap	

	LEFT JOIN (
		SELECT reff_doc,id_journal,(credit)credit FROM fin_journal_d WHERE credit > 0 AND id_coa IN (13001,13002,13201,13202)
	)PN_D ON PN.id_journal = PN_D.id_journal AND PN_D.reff_doc = INV.invno
	
	LEFT JOIN(
		SELECT id_journal,reff_doc,reff_doc2,date_journal,fg_post FROM fin_journal_h WHERE type_journal = '4' AND fg_post = '2'
	)ALL_AR ON ALL_AR.reff_doc2 = AR.id_rekap	
		
	LEFT JOIN (
		SELECT reff_doc,id_journal,(credit)credit,description FROM fin_journal_d WHERE credit > 0 AND id_coa IN (13001,13002,13201,13202)
	)ALL_AR_D ON ALL_AR.id_journal = ALL_AR_D.id_journal AND ALL_AR_D.reff_doc = INV.invno
	LEFT JOIN(
	SELECT id_inv,MAX(id_so_det)id_so_det,GROUP_CONCAT(bppbno)bppb FROM invoice_detail GROUP BY id_inv)INV_DET
	ON INV_DET.id_inv = INV.id
	INNER JOIN(SELECT id_so,id FROM so_det)SOD ON SOD.id = INV_DET.id_so_det
	LEFT JOIN (SELECT so_no, id,curr FROM so)SO ON SO.id = SOD.id_so
	LEFT JOIN(
	SELECT n_idinvoiceheader,bpbno FROM invoice_commercial WHERE bpbno IS NOT NULL)INV_COM
	ON INV_COM.n_idinvoiceheader = INV.id
	LEFT JOIN(SELECT id,kode_pterms,days_pterms FROM masterpterms)MP ON MP.id = INV.id_pterms
	LEFT JOIN(SELECT v_codecurr,tanggal,rate FROM masterrate WHERE v_codecurr='PAJAK' GROUP BY tanggal)MR 
	ON MR.tanggal = INV.invdate
		WHERE  ((ifnull(DATE_ADD(JH.date_journal, INTERVAL MP.days_pterms DAY),DATE_ADD(JH.date_journal, INTERVAL MP.days_pterms DAY)) >= '{$d_from}') AND (ifnull(DATE_ADD(JH.date_journal, INTERVAL MP.days_pterms DAY),DATE_ADD(JH.date_journal, INTERVAL MP.days_pterms DAY)) <= '{$d_to}') ) AND INV.n_post = '2' AND (ALL_AR.fg_post !='2' OR  ALL_AR.fg_post IS NULL) AND (PN.fg_post !='2' OR  PN.fg_post IS NULL)   AND JD.id_coa IS NOT NULL
)X";
## Search
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (

	   X.id_coa           	    	IKE'%".$searchValue."%'
	OR X.supplier_code             	LIKE'%".$searchValue."%'
	OR X.supplier             		LIKE'%".$searchValue."%'
	OR X.kode_pterms             	LIKE'%".$searchValue."%'
	OR X.period             		LIKE'%".$searchValue."%'
	OR X.debit             			LIKE'%".$searchValue."%'
	OR X.reff_doc             		LIKE'%".$searchValue."%'
	OR X.ar_days             	LIKE'%".$searchValue."%'
	

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
			,X.supplier_code
			,X.supplier
			,X.kode_pterms
			,X.period
			,X.debit
			,X.reff_doc
			,X.ar_days
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
//echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];


   $data[] = array(
"id_coa"=>htmlspecialchars($row['id_coa']),   //ok             
"supplier_code"=>htmlspecialchars($row['supplier_code']), //ok
"supplier"=>htmlspecialchars($row['supplier']), //ok
"kode_pterms"=>htmlspecialchars($row['kode_pterms']), //ok
"period"=>htmlspecialchars($row['period']), //ok days_pterms
"debit"=>htmlspecialchars(number_format((float)$row['debit'], 2, '.', ',')), //ok
 "reff_doc"=>htmlspecialchars($row['reff_doc']), //ok days_pterms
  "ar_days"=>((float)$row['ar_days'] == '0'? htmlspecialchars(number_format((float)$row['ar_days'], 2, '.', ',')) : htmlspecialchars(number_format((float)$row['ar_days'], 9, '.', ',')) ),
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