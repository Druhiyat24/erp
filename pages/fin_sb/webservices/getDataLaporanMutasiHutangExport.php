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
/*
print_r($data);
die();  */
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(
SELECT a.id_journal id_journal_pembelian
		,		i.id_journal id_journal_pembayaran
		,		a.id_coa
		,		d.supplier_code
		,		d.Supplier
		,		IF(a.curr='USD', ROUND(a.credit,2), '0')pembelian_usd
		,		IF(a.curr='USD', ROUND(a.credit*a.rate,2), a.credit)pembelian_idr
		,		a.curr curr_j_pembelian
		,		a.rate rate_j_pembelian
		,		a.rate saldo_akhir_kurs
		,		a.rate saldo_awal_kurs
		,		a.rate pembelian_kurs
		,		a.rate lain_lain_kurs
		,		i.rate lain2_kurs
		,		i.rate pembayaran_kurs
		,		i.rate diskon_kurs
		,		i.rate retur_kurs
		,		a.date_journal
		,		IF(a.curr='IDR',ROUND(a.credit,2), ROUND((a.credit*a.rate),2))jumlah_idr_beli
		,		IF(a.curr='USD',ROUND(i.debit,2), '-')debit_j_pembayaran
		,		i.curr curr_j_pembayaran
		,		i.rate rate_j_pembayaran
		,		IF(i.curr='IDR',ROUND(i.debit,2), ROUND((i.debit*i.rate),2))jumlah_idr_bayar
		,		f.pono
		,		g.kode_pterms
		,		c.invno inv_supplier
		,		a.d_invoice
		,		e.v_fakturpajak
		,		e.d_fakturpajak
		,		c.bpbdate
		,		c.bpbno_int
		,		'0' saldo_awal_usd
		,		'0' saldo_awal_idr		
		,		'0' lain_lain_usd
		,		'0' lain_lain_idr
		,		h.v_listcode nomor_payment_voucher
		,		i.date_journal  tgl_payment_voucher
		,		i.reff_doc keterangan
		,		'0' pembayaran_usd
		,		'0' pembayaran_idr
		,		'0' retur_usd
		,		'0' retur_idr
		,		'0' diskon_usd
		,		'0' diskon_idr
		,		'0' lain2_usd
		,		'0' lain2_idr
		,		'0' saldo_akhir_usd
		,		'0' saldo_akhir_idr
		,		if(a.curr='USD', ( 365 / ( (a.credit*a.rate) / ( ( (a.credit*a.rate) + 0 )/2) ) ),( 365 / ( (a.credit) / ( ( (a.credit) + 0 )/2) ) )  )ap_days
		FROM (
		SELECT 	aa.id_journal
		, 		aa.type_journal
		,		aa.date_journal
		,		aa.period
		, 		aa.reff_doc
		, 		aa.fg_post
		, 		aa.n_ppn
		, 		aa.n_pph
		, 		aa.inv_supplier
		, 		aa.d_invoice
		,		MAX(bb.curr)curr
		,		SUM(bb.credit)credit
		,		bb.debit
		,		bb.id_bpb
		,		bb.id_po
		,		cc.rate
		,		bb.id_coa
			
		FROM fin_journal_h aa
		LEFT JOIN fin_journal_d bb ON bb.id_journal=aa.id_journal
		LEFT JOIN masterrate cc ON cc.tanggal=aa.date_journal
		WHERE aa.type_journal='2' AND aa.fg_post='2' AND bb.credit > 0 AND cc.v_codecurr='PAJAK' GROUP BY bb.id_journal)a
		LEFT JOIN ( 
		SELECT id, bpbno_int, bpbdate, MAX(id_supplier)id_supplier, price, qty,MAX(invno)invno, pono from bpb GROUP BY bpbno_int) c ON c.bpbno_int=a.reff_doc
		LEFT JOIN mastersupplier d ON d.Id_Supplier=c.id_supplier
		LEFT JOIN fin_journalheaderdetail e ON e.v_idjournal=a.id_journal
		LEFT JOIN po_header f ON f.pono=c.pono
		LEFT JOIN masterpterms g ON g.id=f.id_terms
		LEFT JOIN (
		SELECT id_bpb,id_journal,reff_doc,reff_doc2 FROM fin_journal_d WHERE id_journal LIKE '%-PK-%' AND reff_doc IS NOT NULL GROUP BY id_journal,reff_doc2)KB ON KB.reff_doc2 = a.id_journal
		LEFT JOIN (SELECT n_id, v_nojournal, v_status, is_lunas,v_listcode FROM fin_status_journal_ap WHERE v_status='A' AND is_lunas='Y')h ON h.v_nojournal=KB.id_journal
		LEFT JOIN (
		SELECT m.id_journal
		, 		m.type_journal
		, 		m.reff_doc
		,		m.date_journal
		, 		m.fg_post
		, 		m.n_ppn
		, 		m.n_pph
		, 		m.inv_supplier
		, 		m.d_invoice
		,		n.id_list_payment
		,		n.curr
		,		n.debit
		,		o.rate

		FROM fin_journal_h m
		LEFT JOIN fin_journal_d n ON n.id_journal=m.id_journal
		LEFT JOIN masterrate o ON o.tanggal=m.date_journal
		WHERE m.type_journal='3' AND m.fg_post='2' AND n.debit > 0 AND o.v_codecurr='PAJAK' )i ON i.id_list_payment=h.n_id
		WHERE (a.date_journal >= '$d_from' AND a.date_journal <= '$d_to') AND d.area='I'

		GROUP BY a.id_journal
		ORDER BY a.period DESC
		, a.date_journal DESC
		, a.id_journal DESC
		, a.debit DESC
		, a.credit DESC
		
		
		
)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (


	X.supplier_code            	  LIKE'%".$searchValue."%'
	X.id_coa                   	  LIKE'%".$searchValue."%'
	X.Supplier                 	  LIKE'%".$searchValue."%'
	X.kode_pterms              	  LIKE'%".$searchValue."%'
	X.saldo_awal_idr          	   	  LIKE'%".$searchValue."%'
	X.pembelian_idr	   			  LIKE'%".$searchValue."%'
	X.lain_lain_idr 			  LIKE'%".$searchValue."%'
	X.jumlah_idr_beli    		  LIKE'%".$searchValue."%'
	X.retur_idr 				  LIKE'%".$searchValue."%'
	X.lain2_idr 				  LIKE'%".$searchValue."%'
	X.saldo_akhir_idr             LIKE'%".$searchValue."%'
	X.ap_days                  	  LIKE'%".$searchValue."%'
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
	X.supplier_code,
	X.id_coa,
	X.Supplier,
	X.kode_pterms,
	X.saldo_awal_idr,
	X.pembelian_idr,
	X.lain_lain_idr,
	X.jumlah_idr_beli,
	X.retur_idr,
	X.lain2_idr,
	X.saldo_akhir_idr,
	X.ap_days
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();

//echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];


   $data[] = array(            
"supplier_code"=>htmlspecialchars($row['supplier_code']),
"id_coa"=>htmlspecialchars($row['id_coa']),
"supplier"=>htmlspecialchars($row['Supplier']),
"kode_pterms"=>htmlspecialchars($row['kode_pterms']),
"saldo_awal_idr"=>htmlspecialchars(number_format((float)$row['saldo_awal_idr'], 2, '.', ',')),
"pembelian_idr"=>htmlspecialchars(number_format((float)$row['pembelian_idr'], 2, '.', ',') ),
"lain_lain_idr"=>htmlspecialchars(number_format((float)$row['lain_lain_idr'], 2, '.', ',')),
"jumlah_idr_beli"=>htmlspecialchars(number_format((float)$row['jumlah_idr_beli'], 2, '.', ',')),
"retur_idr"=>htmlspecialchars(number_format((float)$row['retur_idr'], 2, '.', ',')),
"lain2_idr"=>htmlspecialchars(number_format((float)$row['lain2_idr'], 2, '.', ',')),
"saldo_akhir_idr"=>htmlspecialchars(number_format((float)$row['saldo_akhir_idr'], 2, '.', ',')),
 "ap_days"=>((float)$row['ap_days'] == '0'? htmlspecialchars(number_format((float)$row['ap_days'], 2, '.', ',')) : htmlspecialchars(number_format((float)$row['ap_days'], 9, '.', ',')) ),



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