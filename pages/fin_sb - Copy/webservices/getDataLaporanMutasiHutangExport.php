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
	SELECT OK.*
		,if(OK.curr ='USD',OK.tambah_pembelian,0)tambah_pembelian_usd
		,if(OK.curr ='USD',(OK.tambah_pembelian * OK.rate),OK.tambah_pembelian)tambah_pembelian_idr
		
		,if(OK.curr ='USD',OK.penambahan_lain_lain,0)penambahan_lain_usd
		,if(OK.curr ='USD',(OK.penambahan_lain_lain * OK.rate),OK.penambahan_lain_lain)penambahan_lain_idr

		,if(OK.curr ='USD',OK.pengurangan_lain_lain,0)pengurangan_lain_usd
		,if(OK.curr ='USD',(OK.pengurangan_lain_lain * OK.rate),OK.pengurangan_lain_lain)pengurangan_lain_idr	

		,if(OK.curr ='USD',OK.pengurangan_retur,0)pengurangan_retur_usd
		,if(OK.curr ='USD',(OK.pengurangan_retur * OK.rate),OK.pengurangan_retur)pengurangan_retur_idr	

		,if(OK.curr ='USD',OK.pengurangan_pembayaran,0)pengurangan_pembayaran_usd
		,if(OK.curr ='USD',(OK.pengurangan_pembayaran * OK.rate),OK.pengurangan_pembayaran)pengurangan_pembayaran_idr	
		
		FROM (

SELECT			 
				
				 MA.date_journal 
 				,MA.id_supplier
				,MA.supplier_code
				,MA.supplier
				,MA.type_journal
				,MA.id_journal reff_dokumen
				,CONCAT(ifnull(PENGURANGAN_RETUR.id_journal,'')
					,if(PENGURANGAN_RETUR.id_journal IS NULL,'',',')
					,PENGURANGAN_LAIN_LAIN.id_journal) no_journal_pengurangan
				,MA.id_coa
				,MA.nm_coa
				,MA.curr
				,MA.penambahan tambah_pembelian
				,'0' penambahan_lain_lain
				,ifnull(PENGURANGAN_LAIN_LAIN.tot_deb,0)pengurangan_lain_lain 
				,ifnull(PENGURANGAN_RETUR.pengurangan_retur,0)pengurangan_retur
				,'0' pengurangan_pembayaran
				,MA.id_order
				,MA.top
				,MA.reff_doc
				,MA.date_reff
				,if(MA.no_fp = '','N/A',MA.no_fp)no_fp
				,if(MA.tgl_fp='0000-00-00' OR MA.tgl_fp='1970-01-01' OR MA.tgl_fp IS NULL OR MA.tgl_fp IS NULL,'N/A',MA.tgl_fp)tgl_fp
				,MA.invno
				,MA.tgl_inv		
				,MA.rate
				,MA.area
				FROM (
	
	SELECT       FH.id_journal
				,BPB.id_order
				,FH.fg_post
				,FH.date_journal
				,FH.type_journal
				,FH.reff_doc 
				,BPB.id_supplier
				,BPB.supplier
				,BPB.supplier_code
				,BPB.top
				,BPB.bpbdate date_reff
				,BPB.no_fp
				,BPB.tgl_fp
				,BPB.area
				,ifnull(if(BPB.invno ='','N/A',BPB.invno),'N/A')invno
				,BPB.tgl_inv			
				,FD_PEM.id_coa
				,FD_PEM.nm_coa
				,FD_PEM.curr
				,SUM(FD_PEM.tot_cre)penambahan
				,ifnull(MR.rate,'0')rate
				FROM fin_journal_h FH
				LEFT JOIN (SELECT v_codecurr,rate,tanggal FROM masterrate)MR ON MR.tanggal = FH.date_journal
				INNER JOIN(SELECT b.area,b.supplier_code,invno,'N/A' tgl_inv,no_fp,tgl_fp,bpbdate,ifnull(d.kode_pterms,'N/A')top,b.Supplier supplier,MAX(a.bpbno_int)bpbno_int,MAX(a.pono)id_order,MAX(a.id_supplier)id_supplier
				FROM bpb a
					INNER JOIN mastersupplier b ON b.Id_Supplier = a.id_supplier
					LEFT JOIN (select id,pono,id_terms FROM po_header WHERE 1=1)c ON a.pono = c.pono
					INNER JOIN (SELECT id,kode_pterms FROM masterpterms WHERE 1=1)d ON c.id_terms = d.id
					GROUP BY a.bpbno_int
				)BPB ON FH.reff_doc =  BPB.bpbno_int
				INNER JOIN (SELECT MAX(curr)curr,id_coa,nm_coa,id_journal,SUM(ifnull(credit,0))tot_cre FROM 
				fin_journal_d WHERE credit > 0 
				GROUP BY id_journal,id_coa)FD_PEM ON
				FD_PEM.id_journal = FH.id_journal
				WHERE FH.type_journal ='2' GROUP BY BPB.id_supplier,FD_PEM.id_coa,FD_PEM.id_journal ORDER BY BPB.supplier ASC
				)MA
			 
	LEFT JOIN(
SELECT   MS.Supplier supplier
        ,A.id_journal
		,A.id_coa
		,A.nm_coa
		,A.reff_doc2
		,SUM(A.debit)tot_deb
		,MAX(A.id_supplier)id_supplier
		,A.reff_doc
		FROM fin_journal_d A
		INNER JOIN mastersupplier MS ON MS.id_supplier = A.id_supplier
		INNER JOIN (SELECT id_journal,date_journal,fg_post,type_journal FROM fin_journal_h
			WHERE type_journal = '14' AND fg_post='2') B ON A.id_journal = B.id_journal
		WHERE A.id_journal LIKE '%PK%' AND A.debit > 0 AND A.id_supplier IS NOT NULL
		GROUP BY A.id_supplier,A.id_coa,A.reff_doc2
	)PENGURANGAN_LAIN_LAIN	ON MA.id_journal=PENGURANGAN_LAIN_LAIN.reff_doc2 AND MA.id_supplier = PENGURANGAN_LAIN_LAIN.id_supplier AND MA.id_coa = PENGURANGAN_LAIN_LAIN.id_coa
	
	
	LEFT JOIN(
SELECT 	 A.id_journal
		,A.date_journal
		,MS.id_supplier
		,MS.Supplier supplier
		,A.fg_post
		,A.type_journal
		,A.reff_doc
		,B.id_coa
		,E.id_journal j_p
		,B.nm_coa
		,SUM(ifnull(B.debit,0))pengurangan_retur
		FROM fin_journal_h A
		INNER JOIN (SELECT id_journal,id_coa,nm_coa,debit FROM fin_journal_d WHERE debit > 0
		AND id_journal LIKE '%RP%'
		)B
		ON A.id_journal = B.id_journal
			
		INNER JOIN(SELECT (a.bppbno_int),(a.bpbno_ro),a.bppbno FROM bppb a GROUP BY a.bppbno)C
		ON A.reff_doc = C.bppbno
		INNER JOIN(SELECT a.bpbno,MAX(a.bpbno_int)bpbno_int,MAX(id_supplier)id_supplier FROM bpb a GROUP BY a.bpbno_int )D
		ON C.bpbno_ro = D.bpbno
		INNER JOIN (SELECT id_journal,reff_doc FROm fin_journal_h WHERE type_journal='2' and fg_post ='2')E
		ON E.reff_doc = D.bpbno_int
		INNER JOIN mastersupplier MS ON MS.Id_Supplier = D.id_supplier
		
		GROUP BY MS.Id_Supplier,B.id_coa,B.id_journal	
	
	
	)PENGURANGAN_RETUR
	ON MA.id_journal =PENGURANGAN_RETUR.j_p  AND MA.id_supplier = PENGURANGAN_RETUR.id_supplier AND MA.id_coa = PENGURANGAN_RETUR.id_coa	
	
	
	
	
UNION ALL
SELECT  
		 KB.date_journal
		 ,SUP.id_supplier
		  ,MS.supplier_code
		 ,MS.Supplier supplier
		 ,KB.type_journal
		,KB.id_journal reff_dokumen
		,CONCAT(ifnull(KB_PENGURANGAN_LAIN.id_journal,'')
						,if(KB_PENGURANGAN_LAIN.id_journal IS NULL,'',',')
					,ifnull(KB_PENGURANGAN_RETUR.id_journal,'')
						,if(KB_PENGURANGAN_RETUR.id_journal IS NULL,'',',')
					,PAYMENT.id_journal) no_journal_pengurangan 
		,SUP.id_coa
		,SUP.nm_coa
		,KB.curr
		,KB.tot_cre tambah_pembelian
		,( (ifnull(PENAMBAHAN_KB.tot_cre,0)) + (ifnull(PAYMENT_RETUR.tot_cre,0)) + (ifnull(PAYMENT_TAMBAH_UTANG_MANUAL.tot_cre,0)) ) penambahan_lain_lain
		,( (ifnull(KB_PENGURANGAN_LAIN.tot_deb,0)) + (ifnull(PAYMENT_KURANG_UTANG_MANUAL.tot_deb,0)) ) pengurangan_lain_lain
		,KB_PENGURANGAN_RETUR.tot_deb pengurangan_retur
		,PAYMENT.tot_deb pengurangan_pembayaran
		,KB.id_order
		,KB.top
		,KB.reff_doc
		,KB.date_reff
		,KB.no_fp
		,KB.tgl_fp
		,KB.invno
		,KB.tgl_inv
		,KB.rate
		,MS.area
			FROM (
			SELECT   S.id_supplier
				,S.id_journal
				,S.id_coa
				,S.nm_coa
		FROM(
			SELECT   MAX(POPULASI.id_supplier)id_supplier
				,POPULASI.id_journal
				,POPULASI.id_coa
				,POPULASI.nm_coa
				,POPULASI.credit
				FROM fin_journal_d POPULASI 		
				WHERE POPULASI.id_journal LIKE '%PK%' AND POPULASI.credit >0 AND POPULASI.id_bpb IS NOT NULL AND POPULASI.id_supplier GROUP BY POPULASI.id_supplier,POPULASI.id_coa
				
				UNION ALL
				
			SELECT   MAX(POPULASI.id_supplier)id_supplier
				,POPULASI.id_journal
				,POPULASI.id_coa
				,POPULASI.nm_coa
				,POPULASI.debit
				FROM fin_journal_d POPULASI 		
				WHERE POPULASI.id_journal LIKE '%PK%' AND POPULASI.debit >0 AND POPULASI.id_bppb IS NOT NULL AND POPULASI.id_supplier GROUP BY POPULASI.id_supplier,POPULASI.id_coa	)S 
				WHERE S.id_coa !=''
				
				GROUP BY S.id_supplier,S.id_coa
				)SUP
	LEFT JOIN(
		SELECT   a.id_coa
				,a.nm_coa
				,a.id_journal
				,b.date_journal
				,b.type_journal
				,if(b.invno IS NULL OR b.invno ='','N/A',b.invno)invno
				,if(b.tgl_inv ='' OR b.tgl_inv ='1970-01-01' OR b.tgl_inv IS NULL,'N/A',b.tgl_inv)tgl_inv
				,a.reff_doc
				,a.reff_doc2
				,a.id_po
				,a.id_bpb
				,a.curr
				,help.no_fp
				,help.tgl_fp
				,bpb.bpbdate date_reff
				,POH.pono id_order
				,SUM(ifnull(a.credit,0))tot_cre
				,MAX(a.id_supplier) id_supplier
				,ifnull(MP.kode_pterms,'N/A')top
				,ifnull(MR.rate,0)rate
				FROM fin_journal_d a
					INNER JOIN(SELECT type_journal,id_journal,date_journal,fg_post,inv_supplier invno,d_invoice tgl_inv  FROM fin_journal_h WHERE
					fg_post ='2')b
					ON a.id_journal = b.id_journal
					LEFT JOIN (SELECT v_codecurr,rate,tanggal FROM masterrate)MR ON MR.tanggal = b.date_journal
			
					LEFT JOIN(SELECT id,bpbdate FROM bpb)bpb ON bpb.id = a.id_bpb
					LEFT JOIN(SELECT id,pono,id_terms FROM po_header WHERE 1=1)POH ON POH.id = a.id_po
					LEFT JOIN(SELECT id,kode_pterms FROM masterpterms)MP ON MP.id = POH.id_terms
					LEFT JOIN(SELECT v_idjournal,if(d_fakturpajak ='1970-01-01' OR d_fakturpajak IS NULL OR d_fakturpajak 	='0000-00-00','N/A',d_fakturpajak)tgl_fp
					,if(v_fakturpajak ='' OR v_fakturpajak IS NULL, 'N/A',v_fakturpajak)no_fp

					from fin_journalheaderdetail WHERE v_idjournal LIKE '%PK%')help ON help.v_idjournal = a.id_journal
				WHERE a.id_journal LIKE '%PK%' AND a.credit > 0 AND a.nm_coa LIKE '%UTANG%' AND a.id_bpb IS NOT NULL
				GROUP BY a.id_supplier,a.id_coa,a.id_journal
	)KB	ON SUP.id_supplier = KB.id_supplier AND SUP.id_coa = KB.id_coa
	LEFT JOIN mastersupplier MS ON MS.Id_Supplier = SUP.id_supplier
	LEFT JOIN(SELECT id_journal,date_journal FROM fin_journal_h WHERE type_journal = '2')ord
	ON ord.id_journal = KB.reff_doc2
	
	LEFT JOIN(
		SELECT   a.id_coa
				,a.nm_coa
				,SUM(ifnull(a.credit,0))tot_cre
				,TRIGGERS.id_supplier id_supplier
				,a.id_journal
				FROM fin_journal_d a
					INNER JOIN(SELECT id_journal,date_journal,fg_post FROM fin_journal_h WHERE fg_post ='2')b
						ON a.id_journal = b.id_journal
					INNER JOIN(SELECT id_journal,MAX(id_supplier)id_supplier FROM fin_journal_d WHERE id_journal LIKE '%PK%'
						AND id_supplier IS NOT NULL GROUP BY id_journal
					)TRIGGERS ON a.id_journal = TRIGGERS.id_journal
				WHERE a.id_journal LIKE '%PK%' AND a.credit > 0 AND a.nm_coa LIKE '%UTANG%' AND a.id_bpb IS NULL AND a.id_bppb 
				IS NULL GROUP BY TRIGGERS.id_supplier,a.id_coa,a.id_journal
	)PENAMBAHAN_KB ON KB.id_journal=PENAMBAHAN_KB.id_journal	

	LEFT JOIN(
		SELECT   a.id_coa
				,a.nm_coa
				,a.id_journal
				,SUM(ifnull(a.debit,0))tot_deb
				,TRIGGERS.id_supplier id_supplier
				FROM fin_journal_d a
					INNER JOIN(SELECT id_journal,date_journal,fg_post FROM fin_journal_h WHERE fg_post ='2')b
						ON a.id_journal = b.id_journal
					INNER JOIN(SELECT id_journal,MAX(id_supplier)id_supplier FROM fin_journal_d WHERE id_journal LIKE '%PK%'
						AND id_supplier IS NOT NULL GROUP BY id_journal
					)TRIGGERS ON a.id_journal = TRIGGERS.id_journal
				WHERE a.id_journal LIKE '%PK%' AND a.debit > 0 AND a.nm_coa LIKE '%UTANG%' AND a.id_bpb IS NULL AND a.id_bppb 
				IS NULL GROUP BY TRIGGERS.id_supplier,a.id_coa,a.id_journal
	)KB_PENGURANGAN_LAIN ON KB.id_journal=KB_PENGURANGAN_LAIN.id_journal
	
	LEFT JOIN(
		SELECT   a.id_coa
				,a.nm_coa
				,a.id_journal
				,SUM(ifnull(a.debit,0))tot_deb
				,MAX(a.id_supplier) id_supplier
				FROM fin_journal_d a
					INNER JOIN(SELECT id_journal,date_journal,fg_post FROM fin_journal_h WHERE fg_post ='2')b
					ON a.id_journal = b.id_journal
				WHERE a.id_journal LIKE '%PK%' AND a.debit > 0 AND a.nm_coa LIKE '%UTANG%' AND a.id_bppb IS NOT NULL
				GROUP BY a.id_supplier,a.id_coa,a.id_journal
	)KB_PENGURANGAN_RETUR	ON KB.id_journal=KB_PENGURANGAN_RETUR.id_journal	
	
	
	
	LEFT JOIN(
	SELECT
		 b.id_supplier 
		,a.id_journal
		,a.type_journal
		,b.reff_doc kb_reff
		,a.date_journal
		,fg_post
		,b.id_coa
		,b.nm_coa
		,SUM(b.tot_deb)tot_deb
		FROM fin_journal_h a
		LEFT JOIN
			(SELECT reff_doc,id_journal,id_coa,nm_coa,SUM(ifnull(debit,0))tot_deb,MAX(id_supplier)id_supplier FROM fin_journal_d WHERE nm_coa LIKE '%UTANG%' AND debit >0 AND id_bpb IS NOT NULL GROUP BY reff_doc,id_coa
			)b ON a.id_journal = b.id_journal
		WHERE a.type_journal ='3' AND a.fg_post ='2' AND b.id_supplier IS NOT NULL AND b.id_supplier >0
		GROUP BY b.id_supplier,b.id_coa,b.reff_doc
	)PAYMENT ON KB.id_journal=PAYMENT.kb_reff
	
	LEFT JOIN(
/* ADA RETUR DI PAYMENT */
SELECT 	
		 b.id_supplier 
		,a.id_journal
		,a.type_journal
		,a.date_journal
		,b.reff_doc
		,fg_post
		,b.id_coa
		,b.nm_coa
		,SUM(b.tot_cre)tot_cre
		FROM fin_journal_h a
		LEFT JOIN
			(SELECT reff_doc,id_journal,id_coa,nm_coa,SUM(credit)tot_cre,MAX(id_supplier)id_supplier FROM fin_journal_d WHERE nm_coa LIKE '%UTANG%' AND credit >0 AND id_bppb IS NOT NULL GROUP BY reff_doc,id_coa
			)b ON a.id_journal = b.id_journal
		WHERE a.type_journal ='3' AND a.fg_post ='2'  AND b.id_supplier IS NOT NULL AND b.id_supplier >0
		GROUP BY b.id_supplier,b.id_coa,b.reff_doc
/* ADA RETUR DI PAYMENT */
	
	)PAYMENT_RETUR ON KB.id_journal=PAYMENT_RETUR.reff_doc
	
	
	LEFT JOIN(
	
SELECT 	
		 TRIGGERS.id_supplier 
		,a.id_journal
		,a.type_journal
		,a.date_journal
		,b.reff_doc
		,fg_post
		,b.id_coa
		,b.nm_coa
		,SUM(b.tot_deb)tot_deb
		FROM fin_journal_h a
		LEFT JOIN
			(SELECT reff_doc,id_journal,id_coa,nm_coa,SUM(debit)tot_deb,MAX(id_supplier)id_supplier FROM fin_journal_d WHERE nm_coa LIKE '%UTANG%' AND debit  > 0 AND id_bppb IS NULL AND id_bpb IS NULL GROUP BY reff_doc,id_coa
			)b ON a.id_journal = b.id_journal
		INNER JOIN(SELECT id_journal,MAX(id_supplier)id_supplier,reff_doc FROM fin_journal_d WHERE id_journal LIKE '%PV%' 
			AND id_supplier IS NOT NULL GROUP BY id_journal
		)TRIGGERS ON b.reff_doc = TRIGGERS.reff_doc			
		WHERE a.type_journal ='3' AND a.fg_post ='2' 
		GROUP BY b.id_supplier,b.id_coa,b.reff_doc
	
	
	)PAYMENT_KURANG_UTANG_MANUAL ON KB.id_journal=PAYMENT_KURANG_UTANG_MANUAL.reff_doc
	LEFT JOIN(
SELECT 	
		 TRIGGERS.id_supplier 
		,a.id_journal
		,a.type_journal
		,a.date_journal
		,fg_post
		,b.id_coa
		,b.reff_doc
		,b.nm_coa
		,SUM(b.tot_cre)tot_cre
		FROM fin_journal_h a
		LEFT JOIN
			(SELECT reff_doc,id_journal,id_coa,nm_coa,SUM(credit)tot_cre,MAX(id_supplier)id_supplier FROM fin_journal_d WHERE nm_coa LIKE '%UTANG%' AND debit  > 0 AND id_bppb IS NULL AND id_bpb IS NULL GROUP BY reff_doc,id_coa
			)b ON a.id_journal = b.id_journal
		INNER JOIN(SELECT id_journal,MAX(id_supplier)id_supplier FROM fin_journal_d WHERE id_journal LIKE '%PV%'
			AND id_supplier IS NOT NULL GROUP BY id_journal
		)TRIGGERS ON b.id_journal = TRIGGERS.id_journal			
		WHERE a.type_journal ='3' AND a.fg_post ='2' 
		GROUP BY b.id_supplier,b.id_coa	,b.reff_doc
	
	
	)PAYMENT_TAMBAH_UTANG_MANUAL ON KB.id_journal=PAYMENT_TAMBAH_UTANG_MANUAL.reff_doc
	)OK WHERE OK.date_journal IS NOT NULL AND OK.area ='I' AND OK.date_journal >='{$d_from}' AND OK.date_journal <='{$d_to}'
	
	ORDER BY OK.supplier,OK.date_journal ASC
		
)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (


	X.supplier_code            	  LIKE'%".$searchValue."%'
	X.id_coa                   	  LIKE'%".$searchValue."%'
	X.Supplier                 	  LIKE'%".$searchValue."%'
	X.kode_pterms              	  LIKE'%".$searchValue."%'
	X.saldo_awal_idr          	  LIKE'%".$searchValue."%'
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
			X.supplier,
			X.top,
			#X.saldo_awal_idr,
			X.tambah_pembelian_idr,
			X.penambahan_lain_idr,
			X.pengurangan_pembayaran_idr,
			X.pengurangan_retur_idr,
			X.pengurangan_lain_idr
			#X.saldo_akhir_idr,
			#X.ap_days
";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();

// echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];


   	$data[] = array(            
	   	"id_coa"=>htmlspecialchars($row['id_coa']),
		"supplier_code"=>htmlspecialchars($row['supplier_code']),
		"supplier"=>htmlspecialchars($row['supplier']),
		"kode_pterms"=>htmlspecialchars($row['top']),
		#"saldo_awal_idr"=>htmlspecialchars(number_format((float)$row['saldo_awal_idr'], 2, '.', ',')),
		"pembelian_idr"=>htmlspecialchars(number_format((float)$row['tambah_pembelian_idr'], 2, '.', ',') ),
		"lain_lain_idr"=>htmlspecialchars(number_format((float)$row['penambahan_lain_idr'], 2, '.', ',')),
		"jumlah_idr_beli"=>htmlspecialchars(number_format((float)$row['pengurangan_pembayaran_idr'], 2, '.', ',')),
		"retur_idr"=>htmlspecialchars(number_format((float)$row['pengurangan_retur_idr'], 2, '.', ',')),
		"lain2_idr"=>htmlspecialchars(number_format((float)$row['pengurangan_lain_idr'], 2, '.', ','))
		#"saldo_akhir_idr"=>htmlspecialchars(number_format((float)$row['saldo_akhir_idr'], 2, '.', ',')),
		#"ap_days"=>((float)$row['ap_days'] == '0'? htmlspecialchars(number_format((float)$row['ap_days'], 2, '.', ',')) : htmlspecialchars(number_format((float)$row['ap_days'], 9, '.', ',')) ),

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