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

$id_coa = $data['idcoa'];
$d_saldo = date('Y-m-d', strtotime('-1 days', strtotime($d_from)));
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(
SELECT if(tmp.debit > 0, tmp.debit,(-1*tmp.credit))saldo_pengaruh
		,if(tmp.debit_usd > 0, tmp.debit_usd,(-1*tmp.credit_usd))saldo_pengaruh_usd
		,tmp.* FROM(

SELECT 	 INV.id
		,MS.supplier_code
		,MS.supplier
		,JD.id_coa
		,INV_DET.bppb
		,if(INV.invdate IS NULL OR INV.invdate ='','N/A',INV.invdate)invdate
		,INV.invno
		,INV_COM.bpbno surat_jalan
		,INV.id_buyer
		,INV.id_pterms
		,MP.kode_pterms
		,MR.rate
		,SO.so_no
		,IFNULL(if(SO.buyerno='','N/A',SO.buyerno),'N/A')buyerno
		,if(INV.v_fakturpajak='-','N/A',IF(INV.v_fakturpajak='','N/A',INV.v_fakturpajak))v_fakturpajak
		,INV.n_post
		,MS.id_supplier
		,MS.vendor_cat
		,MS.area
		,JH.id_journal
		,JH.date_journal
		,JH.reff_doc
		,JD.nm_coa
		,SO.curr
		,if(SO.curr='USD',JD.debit,0)debit_usd
		,if(SO.curr='USD',JD.credit,0)credit_usd
		,if(SO.curr='USD',MR.rate*JD.credit,JD.credit)credit
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
		,'N/A' nomor_kb
		,'N/A' tgl_kb
		,'N/A'tgl_fakturpajak
		,'0' saldo_awal
		,'0' saldo_awal_usd
		,'0' saldo_akhir
		,'0' saldo_akhir_usd		
		,IFNULL(if(ALL_AR.reff_doc='','N/A',ALL_AR.reff_doc),'N/A')ref_doc_pengurangan
		,IFNULL(if(ALL_AR.date_journal='','N/A',ALL_AR.date_journal),'N/A')tgl_pengurangan
		,IFNULL(if(ALL_AR_D.description='','N/A',ALL_AR_D.description),'N/A')keterangan_pengurangan

			FROM invoice_header INV
		LEFT JOIN (SELECT area,vendor_cat,Supplier supplier,Id_Supplier,supplier_code FROM mastersupplier)MS
		ON INV.id_buyer = MS.Id_Supplier
		LEFT JOIN (SELECT id_journal,reff_doc,type_journal,date_journal FROM fin_journal_h WHERE type_journal = '1')JH
		ON JH.reff_doc = INV.invno
		LEFT JOIN(SELECT id_coa,debit,credit,nm_coa,id_journal FROM fin_journal_d WHERE debit > 0 
		AND nm_coa NOT LIKE '%POTONGAN%')JD
		ON JH.id_journal = JD.id_journal
		LEFT JOIN(SELECT id_coa,debit,credit,nm_coa,id_journal FROM fin_journal_d WHERE debit > 0 
		AND nm_coa LIKE '%POTONGAN%')JD_ETC
		ON JH.id_journal = JD_ETC.id_journal
	LEFT JOIN(
		SELECT id_journal
						,id_rekap
						FROM fin_status_journal_ar
	)AR ON JH.id_journal = AR.id_journal
	
	LEFT JOIN(
		SELECT id_journal,reff_doc2,reff_doc,date_journal FROM fin_journal_h WHERE type_journal = '13'  AND fg_post = '2'
	)PN ON PN.reff_doc2 = AR.id_rekap	

	LEFT JOIN (
		SELECT reff_doc,id_journal,(credit)credit FROM fin_journal_d WHERE credit > 0 AND id_coa IN (13001,13002,13201,13202)
	)PN_D ON PN.id_journal = PN_D.id_journal AND PN_D.reff_doc = INV.invno
	
	LEFT JOIN(
		SELECT id_journal,reff_doc,reff_doc2,date_journal FROM fin_journal_h WHERE type_journal = '4' AND fg_post = '2'
	)ALL_AR ON ALL_AR.reff_doc2 = AR.id_rekap	
		
	LEFT JOIN (
		SELECT reff_doc,id_journal,(credit)credit,description FROM fin_journal_d WHERE credit > 0 AND id_coa IN (13001,13002,13201,13202)
	)ALL_AR_D ON ALL_AR.id_journal = ALL_AR_D.id_journal AND ALL_AR_D.reff_doc = INV.invno
	LEFT JOIN(
	SELECT id_inv,MAX(id_so_det)id_so_det,GROUP_CONCAT(bppbno)bppb FROM invoice_detail GROUP BY id_inv)INV_DET
	ON INV_DET.id_inv = INV.id
	INNER JOIN(SELECT id_so,id FROM so_det)SOD ON SOD.id = INV_DET.id_so_det
	LEFT JOIN (SELECT so_no, id,curr,buyerno FROM so)SO ON SO.id = SOD.id_so
	LEFT JOIN(
	SELECT n_idinvoiceheader,bpbno FROM invoice_commercial WHERE bpbno IS NOT NULL)INV_COM
	ON INV_COM.n_idinvoiceheader = INV.id
	LEFT JOIN(SELECT id,kode_pterms FROM masterpterms)MP ON MP.id = INV.id_pterms
	LEFT JOIN(SELECT v_codecurr,tanggal,rate FROM masterrate WHERE v_codecurr='PAJAK' GROUP BY tanggal)MR 
	ON MR.tanggal = INV.invdate
		WHERE (JH.date_journal >='{$d_from}' AND JH.date_journal <='{$d_to}') AND MS.area='L' AND INV.n_post = '2' AND JD.id_coa IS NOT NULL
GROUP BY JH.id_journal 

)tmp ORDER BY tmp.date_journal ASC
)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (

	X.id_coa                   =  LIKE'%".$searchValue."%'
	X.supplier_code            =  LIKE'%".$searchValue."%'
	X.supplier                 =  LIKE'%".$searchValue."%'
	X.invdate                  =  LIKE'%".$searchValue."%'
	X.surat_jalan              =  LIKE'%".$searchValue."%'
	X.invno                    =  LIKE'%".$searchValue."%'
	X.tgl_kb                   =  LIKE'%".$searchValue."%'
	X.nomor_kb                 =  LIKE'%".$searchValue."%'
	X.v_fakturpajak			   =  LIKE'%".$searchValue."%'
	X.tgl_fakturpajak		   =  LIKE'%".$searchValue."%'
	X.so_no                    =  LIKE'%".$searchValue."%'
	X.buyerno                 =  LIKE'%".$searchValue."%'
	X.kode_pterms              =  LIKE'%".$searchValue."%'
	X.saldo_pengaruh_usd       =  LIKE'%".$searchValue."%'
	X.rate                     =  LIKE'%".$searchValue."%'
	X.saldo_pengaruh  	       =  LIKE'%".$searchValue."%'
	X.debit_usd                =  LIKE'%".$searchValue."%'
	X.debit                    =  LIKE'%".$searchValue."%'    
	X.lain_lain_usd            =  LIKE'%".$searchValue."%'
	X.lain_lain                =  LIKE'%".$searchValue."%'
	X.ref_doc_pengurangan      =  LIKE'%".$searchValue."%'
	X.tgl_pengurangan          =  LIKE'%".$searchValue."%'
	X.keterangan_pengurangan   =   LIKE'%".$searchValue."%'
	X.pengurangan_usd          =  LIKE'%".$searchValue."%'
	X.pengurangan              =  LIKE'%".$searchValue."%'
	X.retur                    =  LIKE'%".$searchValue."%'
	X.retur_usd                =  LIKE'%".$searchValue."%'
	X.pengurangan_lain_lain    =  LIKE'%".$searchValue."%'
	X.pengurangan_lain_lain_usd=  LIKE'%".$searchValue."%'
	X.saldo_akhir              =  LIKE'%".$searchValue."%'
	X.saldo_akhir_usd          =  LIKE'%".$searchValue."%'

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
			,X.invdate                  	
			,X.surat_jalan              	
			,X.invno                    	
			,X.tgl_kb                   	
			,X.nomor_kb
			,X.v_fakturpajak				 
			,X.tgl_fakturpajak			 
			,X.so_no                    	
			,X.buyerno                 	
			,X.kode_pterms              	
			,X.saldo_pengaruh_usd           	
			,X.rate                     	
			,X.saldo_pengaruh             	
			,X.debit_usd                	
			,X.debit                    	
			,X.lain_lain_usd            	
			,X.lain_lain                	
			,X.ref_doc_pengurangan      	
			,X.tgl_pengurangan          	
			,X.keterangan_pengurangan
			,X.pengurangan_usd          	
			,X.pengurangan              	
			,X.retur                    	
			,X.retur_usd                	
			,X.pengurangan_lain_lain    	
			,X.pengurangan_lain_lain_usd	
			,X.saldo_akhir              	
			,X.saldo_akhir_usd       
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by X.invdate limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
//echo $empQuery;
$data = array();

$d_daily_from = '2019-11-01';
if($d_from == '2019-11-01'){
	$d_saldo = $d_daily_from;
}
if($d_from < '2019-11-01'){
	$saldo_berjalan = 0;
	$saldo_berjalan_idr = 0;
}
else{
$empQuery_saldo = "SELECT H.n_idcoa
	,H.v_namacoa
	,(H.n_saldo + D.saldo)saldo_awal_rimutpilok
	FROM fin_history_saldo H
INNER JOIN (SELECT id_coa
		,SUM(saldo_idr)saldo 
			FROM 
			fin_daily_saldo 
			WHERE 1=1 
			AND id_coa = '13001'
			AND d_daily >='{$d_daily_from}'
			AND d_daily <= '{$d_saldo}'
GROUP BY id_coa)D ON H.n_idcoa = '13001' AND DATE(H.d_dateupdate) = '2019-10-31'"; 

}

$empRecords_saldo = mysqli_query($conn_li, $empQuery_saldo);	
while ($row_saldo = mysqli_fetch_assoc($empRecords_saldo)) {
	$saldo_berjalan_usd =0;
	$saldo_berjalan_idr = $row_saldo['saldo_awal_rimutpilok'];
}
//echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];
$saldo_berjalan_usd = $saldo_berjalan_usd + $row['saldo_pengaruh_usd'];
// $saldo_berjalan_idr = $saldo_berjalan_idr+ $row['saldo_pengaruh'];

$saldo_akhir_idr = $saldo_berjalan_idr + $row['debit'] + $row['lain_lain'] - $row['pengurangan'] - $row['pengurangan_lain_lain'];

   
$data[] = array(
"id_coa"=>htmlspecialchars($row['id_coa']),               
"supplier_code"=>htmlspecialchars($row['supplier_code']),
"supplier"=>htmlspecialchars($row['supplier']),
"invdate"=>htmlspecialchars($row['invdate']),
"surat_jalan"=>htmlspecialchars($row['surat_jalan']),
"invno"=>htmlspecialchars($row['invno']),               
"tgl_kb"=>htmlspecialchars($row['tgl_kb']),
"nomor_kb"=>htmlspecialchars($row['nomor_kb']),
"so_no"=>htmlspecialchars($row['so_no']),
"v_fakturpajak"=>htmlspecialchars($row['v_fakturpajak']),
"tgl_fakturpajak"=>htmlspecialchars($row['tgl_fakturpajak']),               
"buyerno"=>htmlspecialchars($row['buyerno']),
"kode_pterms"=>htmlspecialchars($row['kode_pterms']),
"saldo_berjalan_usd"=>htmlspecialchars(number_format((float)$saldo_berjalan_usd, 2, '.', ',')),
"rate"=>htmlspecialchars(number_format((float)$row['rate'], 2, '.', ',')),
"saldo_berjalan_idr"=>htmlspecialchars(number_format((float)$saldo_berjalan_idr, 2, '.', ',')),               
"debit_usd"=>htmlspecialchars(number_format((float)$row['debit_usd'], 2, '.', ',')),
"debit"=>htmlspecialchars(number_format((float)$row['debit'], 2, '.', ',')),
"lain_lain_usd"=>htmlspecialchars(number_format((float)$row['lain_lain_usd'], 2, '.', ',')),
"lain_lain"=>htmlspecialchars(number_format((float)$row['lain_lain'], 2, '.', ',')),
"ref_doc_pengurangan"=>htmlspecialchars($row['ref_doc_pengurangan']),               
"tgl_pengurangan"=>htmlspecialchars($row['tgl_pengurangan']),
"keterangan_pengurangan"=>htmlspecialchars($row['keterangan_pengurangan']),
"pengurangan_usd"=>htmlspecialchars(number_format((float)$row['pengurangan_usd'], 2, '.', ',')),
"pengurangan"=>htmlspecialchars(number_format((float)$row['pengurangan'], 2, '.', ',')),
"retur"=>htmlspecialchars(number_format((float)$row['retur'], 2, '.', ',')),
"retur_usd"=>htmlspecialchars(number_format((float)$row['retur_usd'], 2, '.', ',')),
"pengurangan_lain_lain"=>htmlspecialchars(number_format((float)$row['pengurangan_lain_lain'], 2, '.', ',')),
"pengurangan_lain_lain_usd"=>htmlspecialchars(number_format((float)$row['pengurangan_lain_lain_usd'], 2, '.', ',')),
"saldo_akhir_idr"=>htmlspecialchars(number_format((float)$saldo_akhir_idr, 2, '.', ',')),
"saldo_akhir_usd"=>htmlspecialchars(number_format((float)$row['saldo_akhir_usd'], 2, '.', ',')),
   );
   $saldo_berjalan_idr=$saldo_akhir_idr;
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