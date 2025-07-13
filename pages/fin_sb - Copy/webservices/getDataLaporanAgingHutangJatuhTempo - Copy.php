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


SELECT 	
		
		P.is_jatem
		,ifnull(if(P.is_jatem = '1','JT TEMPO','AKTUAL'),'AKTUAL')based_on
		,P.supplier
		,P.id_dayterms
		,ifnull(if(P.id_dayterms = '1','BPB','KONTRABON'),'KONTRABON')nama_terms
		,P.jml_pterms
		,P.date_reff
		,P.date_journal
		,P.tgl_sekarang
		,P.umur_hari_tgl_aktual
      	,P.umur_hari_tgl_jatem
		,P.BB
		,P.M1
		,P.M2
		,P.M3
		,P.M4
		,P.M5
		,P.M6
		,P.BB_n
		,P.M1_n
		,P.M2_n
		,P.M3_n
		,P.M4_n
		,P.M5_n
		,P.M6_n
		,P.total_hutang
		,P.tgl_aktual
		,P.tgl_jatem
		,P.id_supplier
		,ifnull(if(P.supplier_code = '','N/A',P.supplier_code),'N/A')supplier_code
		,P.type_journal
		,P.reff_dokumen
		,P.no_journal_pengurangan 
		,P.id_coa
		,P.nm_coa
		,P.curr
		,P.tambah_pembelian
		,P.pengurangan_lain_lain
		,P.pengurangan_retur
		,P.pengurangan_pembayaran
		,P.id_order
		,P.top
		,P.reff_doc
		,P.no_fp
		,P.tgl_fp
		,P.invno
		,P.tgl_inv
		,P.tgl_jatuh_tempo
		,P.rate
		,P.kode_pterms
		,P.area

							FROM (


SELECT 	
		'0' is_jatem
		,O.date_reff
		,O.date_journal
		,O.tgl_sekarang
		,O.umur_hari_tgl_aktual
      ,O.umur_hari_tgl_jatem
      ,O.BB
		,O.M1
		,O.M2
		,O.M3
		,O.M4
		,O.M5
		,O.M6
		,MAX(O.BB_n)BB_n
		,MAX(O.M1_n)M1_n
		,MAX(O.M2_n)M2_n
		,MAX(O.M3_n)M3_n
		,MAX(O.M4_n)M4_n
		,MAX(O.M5_n)M5_n
		,MAX(O.M6_n)M6_n
		,SUM(O.total_hutang)total_hutang
		,O.tgl_aktual
		,O.tgl_jatem
		,O.jml_pterms
		,O.id_dayterms
		,O.id_supplier
		,O.supplier_code
		,O.Supplier supplier
		,O.type_journal
		,O.reff_dokumen
		,O.no_journal_pengurangan 
		,O.id_coa
		,O.nm_coa
		,O.curr
		,O.tambah_pembelian
		,O.pengurangan_lain_lain
		,O.pengurangan_retur
		,O.pengurangan_pembayaran
		,O.id_order
		,O.top
		,O.reff_doc
		,O.no_fp
		,O.tgl_fp
		,O.invno
		,O.tgl_inv
		,O.tgl_jatuh_tempo
		,O.rate
		,O.kode_pterms
		,O.area
FROM (		
		
		SELECT 	
		 N.date_reff
		,N.date_journal
		,N.tgl_sekarang
      ,N.umur_hari_tgl_aktual
      ,N.umur_hari_tgl_jatem
      ,N.BB
		,N.M1
		,N.M2
		,N.M3
		,N.M4
		,N.M5
		,N.M6
		,(IF(N.BB='1',SUM(N.total_hutang),0))BB_n
		,(IF(N.M1='1',SUM(N.total_hutang),0))M1_n
		,(IF(N.M2='1',SUM(N.total_hutang),0))M2_n
		,(IF(N.M3='1',SUM(N.total_hutang),0))M3_n
		,(IF(N.M4='1',SUM(N.total_hutang),0))M4_n
		,(IF(N.M5='1',SUM(N.total_hutang),0))M5_n
		,(IF(N.M6='1',SUM(N.total_hutang),0))M6_n
		,SUM(N.total_hutang)total_hutang
		,N.tgl_aktual
		,N.tgl_jatem
		,N.jml_pterms
		,N.id_dayterms
		,N.id_supplier
		,N.supplier_code
		,N.Supplier supplier
		,N.type_journal
		,N.reff_dokumen
		,N.no_journal_pengurangan 
		,N.id_coa
		,N.nm_coa
		,N.curr
		,N.tambah_pembelian
		,N.pengurangan_lain_lain
		,N.pengurangan_retur
		,N.pengurangan_pembayaran
		,N.id_order
		,N.top
		,N.reff_doc
		,N.no_fp
		,N.tgl_fp
		,N.invno
		,N.tgl_inv
		,N.tgl_jatuh_tempo
		,N.rate
		,N.kode_pterms
		,N.area


FROM (



SELECT 	
		 M.date_reff
		,M.date_journal
		, '{$d_from}' as tgl_sekarang
      , DATEDIFF('{$d_from}', M.tgl_aktual) as umur_hari_tgl_aktual
      , DATEDIFF('{$d_from}', M.tgl_jatem) as umur_hari_tgl_jatem
      ,if(DATEDIFF('{$d_from}', M.tgl_aktual) >=0 AND DATEDIFF('{$d_from}', M.tgl_aktual) <=30,1,0 ) BB
		,if(DATEDIFF('{$d_from}', M.tgl_aktual) >=31 AND DATEDIFF('{$d_from}', M.tgl_aktual) <=60,1,0 ) M1
		,if(DATEDIFF('{$d_from}', M.tgl_aktual) >=61 AND DATEDIFF('{$d_from}', M.tgl_aktual) <=90,1,0 ) M2
		,if(DATEDIFF('{$d_from}', M.tgl_aktual) >=91 AND DATEDIFF('{$d_from}', M.tgl_aktual) <=120,1,0 ) M3
		,if(DATEDIFF('{$d_from}', M.tgl_aktual) >=121 AND DATEDIFF('{$d_from}', M.tgl_aktual) <=150,1,0 ) M4
		,if(DATEDIFF('{$d_from}', M.tgl_aktual) >=151 AND DATEDIFF('{$d_from}', M.tgl_aktual) <=180,1,0 ) M5
		,if(DATEDIFF('{$d_from}', M.tgl_aktual) >=180,1,0 ) M6
		,M.total_hutang
		,M.tgl_aktual
		,M.tgl_jatem
		,M.jml_pterms
		,M.id_dayterms
		,M.id_supplier
		,M.supplier_code
		,M.Supplier supplier
		,M.type_journal
		,M.reff_dokumen
		,M.no_journal_pengurangan 
		,M.id_coa
		,M.nm_coa
		,M.curr
		,M.tambah_pembelian
		,M.pengurangan_lain_lain
		,M.pengurangan_retur
		,M.pengurangan_pembayaran
		,M.id_order
		,M.top
		,M.reff_doc
		,M.no_fp
		,M.tgl_fp
		,M.invno
		,M.tgl_inv
		,M.tgl_jatuh_tempo
		,M.rate
		,M.kode_pterms
		,M.area
FROM(

	SELECT  
			 KB.date_reff
			,KB.date_journal
			,if(KB.id_dayterms='1',KB.date_reff,KB.date_journal)tgl_aktual
			,ADDDATE(if(KB.id_dayterms='1',KB.date_reff,KB.date_journal),IFNULL(KB.jml_pterms,0))tgl_jatem
			,KB.jml_pterms
			,IFNULL(if(KB.id_dayterms='0','2',KB.id_dayterms),'2')id_dayterms
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
			,((IFNULL(KB.tot_cre,0))+(ifnull(PENAMBAHAN_KB.tot_cre,0))+(ifnull(PAYMENT_RETUR.tot_cre,0))+ (ifnull(PAYMENT_TAMBAH_UTANG_MANUAL.tot_cre,0))-(ifnull(KB_PENGURANGAN_LAIN.tot_deb,0)) - (ifnull(PAYMENT_KURANG_UTANG_MANUAL.tot_deb,0)) )total_hutang
			,( (ifnull(PENAMBAHAN_KB.tot_cre,0)) + (ifnull(PAYMENT_RETUR.tot_cre,0)) + (ifnull(PAYMENT_TAMBAH_UTANG_MANUAL.tot_cre,0)) ) penambahan_lain_lain
			,( (ifnull(KB_PENGURANGAN_LAIN.tot_deb,0)) + (ifnull(PAYMENT_KURANG_UTANG_MANUAL.tot_deb,0)) ) pengurangan_lain_lain
			,KB_PENGURANGAN_RETUR.tot_deb pengurangan_retur
			,PAYMENT.tot_deb pengurangan_pembayaran
			,KB.id_order
			,KB.top
			,KB.reff_doc
			,KB.no_fp
			,KB.tgl_fp
			,KB.invno
			,KB.tgl_inv
			,KB.tgl_jatuh_tempo
			,KB.rate
			,KB.kode_pterms
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
				, IFNULL(SUBDATE((ADDDATE(b.date_journal,POH.jml_pterms)),STR_TO_DATE('{$d_from}','%d %b %Y')),'0000-00-00')tgl_jatuh_tempo
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
				,POH.podate
				,POH.pono id_order
				,POH.jml_pterms
				,POH.id_dayterms
				,SUM(ifnull(a.credit,0))tot_cre
				,MAX(a.id_supplier) id_supplier
				,ifnull(MP.kode_pterms,'N/A')top
				,MP.kode_pterms
				,ifnull(MR.rate,0)rate
				FROM fin_journal_d a
					INNER JOIN(SELECT type_journal,id_journal,date_journal,fg_post,inv_supplier invno,d_invoice tgl_inv  FROM fin_journal_h WHERE
					fg_post ='2')b
					ON a.id_journal = b.id_journal
					LEFT JOIN (SELECT v_codecurr,rate,tanggal FROM masterrate)MR ON MR.tanggal = b.date_journal
					LEFT JOIN(SELECT id,bpbdate FROM bpb)bpb ON bpb.id = a.id_bpb
					LEFT JOIN(SELECT id,pono,id_terms,jml_pterms,id_dayterms,podate FROM po_header WHERE 1=1)POH ON POH.id = a.id_po
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
	
	WHERE 1=1 AND KB.date_journal IS NOT null
	
GROUP BY SUP.id_supplier, KB.id_dayterms, KB.jml_pterms

)M

)N 
GROUP BY N.id_supplier, N.id_dayterms, N.jml_pterms,N.umur_hari_tgl_aktual
ORDER BY N.supplier,N.jml_pterms, N.id_dayterms, N.supplier ASC
)O GROUP BY O.id_supplier, O.id_dayterms, O.jml_pterms


UNION ALL


SELECT 	
		
      		'1' is_jatem
		,O.date_reff
		,O.date_journal
		,O.tgl_sekarang
		,O.umur_hari_tgl_aktual
      ,O.umur_hari_tgl_jatem
      ,O.BB_jatem
		,O.M1_j
		,O.M2_j
		,O.M3_j
		,O.M4_j
		,O.M5_j
		,O.M6_j
		,MAX(O.BB_j)BB_jatem
		,MAX(O.M1_j)M1_j
		,MAX(O.M2_j)M2_j
		,MAX(O.M3_j)M3_j
		,MAX(O.M4_j)M4_j
		,MAX(O.M5_j)M5_j
		,MAX(O.M6_j)M6_j
		,SUM(O.total_hutang)total_hutang
		,O.tgl_aktual
		,O.tgl_jatem
		,O.jml_pterms
		,O.id_dayterms
		,O.id_supplier
		,O.supplier_code
		,O.Supplier supplier
		,O.type_journal
		,O.reff_dokumen
		,O.no_journal_pengurangan 
		,O.id_coa
		,O.nm_coa
		,O.curr
		,O.tambah_pembelian
		,O.pengurangan_lain_lain
		,O.pengurangan_retur
		,O.pengurangan_pembayaran
		,O.id_order
		,O.top
		,O.reff_doc
		,O.no_fp
		,O.tgl_fp
		,O.invno
		,O.tgl_inv
		,O.tgl_jatuh_tempo
		,O.rate
		,O.kode_pterms
		,O.area
FROM (		
		
		SELECT 	
		 N.date_reff
		,N.date_journal
		,N.tgl_sekarang
      ,N.umur_hari_tgl_aktual
      ,N.umur_hari_tgl_jatem
      ,N.BB_jatem
		,N.M1_jatem
		,N.M2_jatem
		,N.M3_jatem
		,N.M4_jatem
		,N.M5_jatem
		,N.M6_jatem
		,(IF(N.BB_jatem='1',SUM(N.total_hutang),0))BB_j
		,(IF(N.M1_jatem='1',SUM(N.total_hutang),0))M1_j
		,(IF(N.M2_jatem='1',SUM(N.total_hutang),0))M2_j
		,(IF(N.M3_jatem='1',SUM(N.total_hutang),0))M3_j
		,(IF(N.M4_jatem='1',SUM(N.total_hutang),0))M4_j
		,(IF(N.M5_jatem='1',SUM(N.total_hutang),0))M5_j
		,(IF(N.M6_jatem='1',SUM(N.total_hutang),0))M6_j
		,SUM(N.total_hutang)total_hutang
		,N.tgl_aktual
		,N.tgl_jatem
		,N.jml_pterms
		,N.id_dayterms
		,N.id_supplier
		,N.supplier_code
		,N.Supplier supplier
		,N.type_journal
		,N.reff_dokumen
		,N.no_journal_pengurangan 
		,N.id_coa
		,N.nm_coa
		,N.curr
		,N.tambah_pembelian
		,N.pengurangan_lain_lain
		,N.pengurangan_retur
		,N.pengurangan_pembayaran
		,N.id_order
		,N.top
		,N.reff_doc
		,N.no_fp
		,N.tgl_fp
		,N.invno
		,N.tgl_inv
		,N.tgl_jatuh_tempo
		,N.rate
		,N.kode_pterms
		,N.area


FROM (



SELECT 	
		 M.date_reff
		,M.date_journal
		, '{$d_from}' as tgl_sekarang
      , DATEDIFF('{$d_from}', M.tgl_aktual) as umur_hari_tgl_aktual
      , DATEDIFF('{$d_from}', M.tgl_jatem) as umur_hari_tgl_jatem
		,if(DATEDIFF('{$d_from}', M.tgl_jatem) >=0 AND DATEDIFF('{$d_from}', M.tgl_jatem) <=30,1,0 ) BB_jatem
		,if(DATEDIFF('{$d_from}', M.tgl_jatem) >=31 AND DATEDIFF('{$d_from}', M.tgl_jatem) <=60,1,0 ) M1_jatem
		,if(DATEDIFF('{$d_from}', M.tgl_jatem) >=61 AND DATEDIFF('{$d_from}', M.tgl_jatem) <=90,1,0 ) M2_jatem
		,if(DATEDIFF('{$d_from}', M.tgl_jatem) >=91 AND DATEDIFF('{$d_from}', M.tgl_jatem) <=120,1,0 ) M3_jatem
		,if(DATEDIFF('{$d_from}', M.tgl_jatem) >=121 AND DATEDIFF('{$d_from}', M.tgl_jatem) <=150,1,0 ) M4_jatem
		,if(DATEDIFF('{$d_from}', M.tgl_jatem) >=151 AND DATEDIFF('{$d_from}', M.tgl_jatem) <=180,1,0 ) M5_jatem
		,if(DATEDIFF('{$d_from}', M.tgl_jatem) >=180,1,0 ) M6_jatem
		,M.total_hutang
		,M.tgl_aktual
		,M.tgl_jatem
		,M.jml_pterms
		,M.id_dayterms
		,M.id_supplier
		,M.supplier_code
		,M.Supplier supplier
		,M.type_journal
		,M.reff_dokumen
		,M.no_journal_pengurangan 
		,M.id_coa
		,M.nm_coa
		,M.curr
		,M.tambah_pembelian
		,M.pengurangan_lain_lain
		,M.pengurangan_retur
		,M.pengurangan_pembayaran
		,M.id_order
		,M.top
		,M.reff_doc
		,M.no_fp
		,M.tgl_fp
		,M.invno
		,M.tgl_inv
		,M.tgl_jatuh_tempo
		,M.rate
		,M.kode_pterms
		,M.area
FROM(

	SELECT  
			 KB.date_reff
			,KB.date_journal
			,if(KB.id_dayterms='1',KB.date_reff,KB.date_journal)tgl_aktual
			,ADDDATE(if(KB.id_dayterms='1',KB.date_reff,KB.date_journal),IFNULL(KB.jml_pterms,0))tgl_jatem
			,KB.jml_pterms
			,IFNULL(if(KB.id_dayterms='0','2',KB.id_dayterms),'2')id_dayterms
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
			,((IFNULL(KB.tot_cre,0))+(ifnull(PENAMBAHAN_KB.tot_cre,0))+(ifnull(PAYMENT_RETUR.tot_cre,0))+ (ifnull(PAYMENT_TAMBAH_UTANG_MANUAL.tot_cre,0))-(ifnull(KB_PENGURANGAN_LAIN.tot_deb,0)) - (ifnull(PAYMENT_KURANG_UTANG_MANUAL.tot_deb,0)) )total_hutang
			,( (ifnull(PENAMBAHAN_KB.tot_cre,0)) + (ifnull(PAYMENT_RETUR.tot_cre,0)) + (ifnull(PAYMENT_TAMBAH_UTANG_MANUAL.tot_cre,0)) ) penambahan_lain_lain
			,( (ifnull(KB_PENGURANGAN_LAIN.tot_deb,0)) + (ifnull(PAYMENT_KURANG_UTANG_MANUAL.tot_deb,0)) ) pengurangan_lain_lain
			,KB_PENGURANGAN_RETUR.tot_deb pengurangan_retur
			,PAYMENT.tot_deb pengurangan_pembayaran
			,KB.id_order
			,KB.top
			,KB.reff_doc
			,KB.no_fp
			,KB.tgl_fp
			,KB.invno
			,KB.tgl_inv
			,KB.tgl_jatuh_tempo
			,KB.rate
			,KB.kode_pterms
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
				, IFNULL(SUBDATE((ADDDATE(b.date_journal,POH.jml_pterms)),STR_TO_DATE('{$d_from}','%d %b %Y')),'0000-00-00')tgl_jatuh_tempo
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
				,POH.podate
				,POH.pono id_order
				,POH.jml_pterms
				,POH.id_dayterms
				,SUM(ifnull(a.credit,0))tot_cre
				,MAX(a.id_supplier) id_supplier
				,ifnull(MP.kode_pterms,'N/A')top
				,MP.kode_pterms
				,ifnull(MR.rate,0)rate
				FROM fin_journal_d a
					INNER JOIN(SELECT type_journal,id_journal,date_journal,fg_post,inv_supplier invno,d_invoice tgl_inv  FROM fin_journal_h WHERE
					fg_post ='2')b
					ON a.id_journal = b.id_journal
					LEFT JOIN (SELECT v_codecurr,rate,tanggal FROM masterrate)MR ON MR.tanggal = b.date_journal
					LEFT JOIN(SELECT id,bpbdate FROM bpb)bpb ON bpb.id = a.id_bpb
					LEFT JOIN(SELECT id,pono,id_terms,jml_pterms,id_dayterms,podate FROM po_header WHERE 1=1)POH ON POH.id = a.id_po
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
	
	WHERE 1=1 AND KB.date_journal IS NOT null
	
GROUP BY SUP.id_supplier, KB.id_dayterms, KB.jml_pterms

)M

)N 
GROUP BY N.id_supplier, N.id_dayterms, N.jml_pterms,N.umur_hari_tgl_jatem

)O GROUP BY O.id_supplier, O.id_dayterms, O.jml_pterms

		


) P  


)X";
## Search
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (


	 X.supplier_code             	LIKE'%".$searchValue."%'
	OR X.supplier             		LIKE'%".$searchValue."%'
	OR X.jml_pterms             	LIKE'%".$searchValue."%'
	OR X.nama_terms 					LIKE'%".$searchValue."%'
	OR X.based_on 					LIKE'%".$searchValue."%'
	OR X.total_hutang        		LIKE'%".$searchValue."%'
	OR X.M6_n     					LIKE'%".$searchValue."%'
	OR X.M5_n            			LIKE'%".$searchValue."%'
	OR X.M4_n           			LIKE'%".$searchValue."%'
	OR X.M3_n            			LIKE'%".$searchValue."%'
	OR X.M2_n            			LIKE'%".$searchValue."%'
	OR X.M1_n            			LIKE'%".$searchValue."%'
	OR X.BB_n            			LIKE'%".$searchValue."%'

)
";
}

## Total number of records without filtering
$sel = mysqli_query($conn_li,"select count(*) allcount from $table");
$records = mysqli_fetch_assoc($sel); 

$totalRecords = $records['allcount'];
$ord_by = "	X.supplier ASC, 
			X.id_dayterms ASC,
			X.jml_pterms ASC,
			X.nama_terms ASC,    
				X.is_jatem ASC";
## Total number of record with filtering
$sel = mysqli_query($conn_li,"select count(*)  allcount from $table WHERE 1 ".$searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];
## Fetch records
$colomn = "		 
			
			 X.supplier_code
			 ,X.is_jatem
			 ,X.id_supplier
			 ,X.id_dayterms
			,X.supplier
			,X.jml_pterms
			,X.nama_terms
		    ,X.based_on
			,X.total_hutang
			,X.M6_n
			,X.M5_n
			,X.M4_n
			,X.M3_n
			,X.M2_n
			,X.M1_n
			,X.BB_n
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." ORDER BY ".$ord_by; 
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();  
//echo $empQuery;
$id_supplier = "";
$id_dayterms = "";
$jml_pterms = "";
$is_jatem  = 0;
$is_aktual = 0;
$is_show = 1;
$x = 0;
$trigger_show_aktual =1;
while ($row = mysqli_fetch_assoc($empRecords)) {
if($row['is_jatem'] =='0'){
	$tot_hut = $row['M6_n']+$row['M5_n']+$row['M4_n']+$row['M3_n']+$row['M2_n']+$row['M1_n']+$row['BB_n'];
	if($tot_hut < 1){
		$is_show = 0;
		$trigger_show_aktual == '0';
	}else{
		$is_show = 1;
	}	
}	
if( ($row['is_jatem'] =='0') && ($trigger_show_aktual == '0')){
	$is_show = 0;
}

if($is_show == '1'){	
$trigger_show_aktual =1;
//echo $row['n_post'];
	if($x >0){
		if($is_jatem == '1'){
			//SELISIH
			$total_hutang = $aktual_total_hutang - $jatem_total_hutang;
			$M6_n   =   ( ($jatem_M6_n	- $aktual_M6_n) < 0 ? (-1*($jatem_M6_n	- $aktual_M6_n) ):($jatem_M6_n	- $aktual_M6_n) );
			$M5_n   =   ( ($jatem_M5_n	- $aktual_M5_n) < 0 ? (-1*($jatem_M5_n	- $aktual_M5_n) ):($jatem_M5_n	- $aktual_M5_n) );
			$M4_n   = 	( ($jatem_M4_n	- $aktual_M4_n) < 0 ? (-1*($jatem_M4_n	- $aktual_M4_n) ):($jatem_M4_n	- $aktual_M4_n) );
			$M3_n   =	( ($jatem_M3_n	- $aktual_M3_n) < 0 ? (-1*($jatem_M3_n	- $aktual_M3_n) ):($jatem_M3_n	- $aktual_M3_n) );
			$M2_n   =	( ($jatem_M2_n	- $aktual_M2_n) < 0 ? (-1*($jatem_M2_n	- $aktual_M2_n) ):($jatem_M2_n	- $aktual_M2_n) );
			$M1_n   =	( ($jatem_M1_n	- $aktual_M1_n) < 0 ? (-1*($jatem_M1_n	- $aktual_M1_n) ):($jatem_M1_n	- $aktual_M1_n) );
			$BB_n   =	( ($jatem_BB_n	- $aktual_BB_n) < 0 ? (-1*($jatem_BB_n	- $aktual_BB_n) ):($jatem_BB_n	- $aktual_BB_n) );
			
			
		$data[] = array(
				
		"supplier_code"=>htmlspecialchars(""), //ok
		"supplier"=>htmlspecialchars(""), //ok
		"jml_pterms"=>htmlspecialchars(""), //ok
		"nama_terms"=>htmlspecialchars(""), //ok
		"based_on"=>htmlspecialchars("SELISIH"), //ok days_pterms
		"total_hutang"=>htmlspecialchars(number_format((float)$total_hutang, 2, '.', ',')), //ok
		"M6_n"=>htmlspecialchars(number_format((float)$M6_n, 2, '.', ',')), //ok
		"M5_n"=>htmlspecialchars(number_format((float)$M5_n, 2, '.', ',')), //ok
		"M4_n"=>htmlspecialchars(number_format((float)$M4_n, 2, '.', ',')), //ok
		"M3_n"=>htmlspecialchars(number_format((float)$M3_n, 2, '.', ',')), //ok
		"M2_n"=>htmlspecialchars(number_format((float)$M2_n, 2, '.', ',')), //ok
		"M1_n"=>htmlspecialchars(number_format((float)$M1_n, 2, '.', ',')), //ok
		"BB_n"=>htmlspecialchars(number_format((float)$BB_n, 2, '.', ',')), //ok 
		);	
		}
		
		}
		
		if( $row['is_jatem'] == '1' ){
			$row['supplier_code'] = "";
			$row['supplier'] = "";
			$row['jml_pterms'] = "";
			$row['nama_terms'] = "";
			
		}else{
			$id_supplier = $row['id_supplier'];
			$id_dayterms = $row['id_dayterms'];
			$jml_pterms = $row['jml_pterms']; 
			$nama_terms = $row['nama_terms'];
			
		}
		
		
		if($row['is_jatem'] == '1'){
			$jatem_supplier_code = $row['supplier_code'];
			$jatem_supplier	   = $row['supplier'];
			$jatem_jml_pterms    = $row['jml_pterms'];
			$jatem_nama_terms	   = $row['nama_terms'];
			$jatem_total_hutang  = $row['M6_n']+$row['M5_n']+$row['M4_n']+$row['M3_n']+$row['M2_n']+$row['M1_n']+$row['BB_n'];
			$jatem_M6_n	   		= $row['M6_n'];
			$jatem_M5_n 	   		= $row['M5_n'];
			$jatem_M4_n	   		= $row['M4_n'];
			$jatem_M3_n 	   		= $row['M3_n'];
			$jatem_M2_n	   		= $row['M2_n'];
			$jatem_M1_n 	   		= $row['M1_n'];
			$jatem_BB_n      		= $row['BB_n'];
			$jatem_supplier	   = $row['supplier'];
			$jatem_supplier_code = $row['supplier_code'];	
		}
		if($row['is_jatem'] == '0'){
			$aktual_supplier_code = $row['supplier_code'];
			$aktual_supplier	   = $row['supplier'];
			$aktual_jml_pterms    = $row['jml_pterms'];
			$aktual_nama_terms	   = $row['nama_terms'];
			$aktual_total_hutang  = $row['M6_n']+$row['M5_n']+$row['M4_n']+$row['M3_n']+$row['M2_n']+$row['M1_n']+$row['BB_n'];
			$aktual_M6_n	   		= $row['M6_n'];
			$aktual_M5_n 	   		= $row['M5_n'];
			$aktual_M4_n	   		= $row['M4_n'];
			$aktual_M3_n 	   		= $row['M3_n'];
			$aktual_M2_n	   		= $row['M2_n'];
			$aktual_M1_n 	   		= $row['M1_n'];
			$aktual_BB_n      		= $row['BB_n'];
			$aktual_supplier	   = $row['supplier'];
			$aktual_supplier_code = $row['supplier_code'];		
			
		}
		
		
		
		
		$selisih=$row['total_hutang']-$row['total_hutang'];
		$total_hutang=$row['M6_n']+$row['M5_n']+$row['M4_n']+$row['M3_n']+$row['M2_n']+$row['M1_n']+$row['BB_n'];
		
		
		//TAMBAH ROW SELISIH
		
		
		
		
		$data[] = array(
				
		"supplier_code"=>htmlspecialchars($row['supplier_code']), //ok
		"supplier"=>htmlspecialchars($row['supplier']), //ok
		"jml_pterms"=>htmlspecialchars($row['jml_pterms']), //ok
		"nama_terms"=>htmlspecialchars($row['nama_terms']), //ok
		"based_on"=>htmlspecialchars($row['based_on']), //ok days_pterms
		"total_hutang"=>htmlspecialchars(number_format((float)$total_hutang, 2, '.', ',')), //ok
		"M6_n"=>htmlspecialchars(number_format((float)$row['M6_n'], 2, '.', ',')), //ok
		"M5_n"=>htmlspecialchars(number_format((float)$row['M5_n'], 2, '.', ',')), //ok
		"M4_n"=>htmlspecialchars(number_format((float)$row['M4_n'], 2, '.', ',')), //ok
		"M3_n"=>htmlspecialchars(number_format((float)$row['M3_n'], 2, '.', ',')), //ok
		"M2_n"=>htmlspecialchars(number_format((float)$row['M2_n'], 2, '.', ',')), //ok
		"M1_n"=>htmlspecialchars(number_format((float)$row['M1_n'], 2, '.', ',')), //ok
		"BB_n"=>htmlspecialchars(number_format((float)$row['BB_n'], 2, '.', ',')), //ok 
		);
		$x = $x+1; 
		$is_jatem = $row['is_jatem'];
		}
	}


//LAST Rows
if($is_show == '1'){
			if($is_jatem == '1'){
			//SELISIH
			$total_hutang = $aktual_total_hutang - $jatem_total_hutang;
			$M6_n   =   ( ($jatem_M6_n	- $aktual_M6_n) < 0 ? (-1*($jatem_M6_n	- $aktual_M6_n) ):($jatem_M6_n	- $aktual_M6_n) );
			$M5_n   =   ( ($jatem_M5_n	- $aktual_M5_n) < 0 ? (-1*($jatem_M5_n	- $aktual_M5_n) ):($jatem_M5_n	- $aktual_M5_n) );
			$M4_n   = 	( ($jatem_M4_n	- $aktual_M4_n) < 0 ? (-1*($jatem_M4_n	- $aktual_M4_n) ):($jatem_M4_n	- $aktual_M4_n) );
			$M3_n   =	( ($jatem_M3_n	- $aktual_M3_n) < 0 ? (-1*($jatem_M3_n	- $aktual_M3_n) ):($jatem_M3_n	- $aktual_M3_n) );
			$M2_n   =	( ($jatem_M2_n	- $aktual_M2_n) < 0 ? (-1*($jatem_M2_n	- $aktual_M2_n) ):($jatem_M2_n	- $aktual_M2_n) );
			$M1_n   =	( ($jatem_M1_n	- $aktual_M1_n) < 0 ? (-1*($jatem_M1_n	- $aktual_M1_n) ):($jatem_M1_n	- $aktual_M1_n) );
			$BB_n   =	( ($jatem_BB_n	- $aktual_BB_n) < 0 ? (-1*($jatem_BB_n	- $aktual_BB_n) ):($jatem_BB_n	- $aktual_BB_n) );
			
			
		$data[] = array(
				
		"supplier_code"=>htmlspecialchars(""), //ok
		"supplier"=>htmlspecialchars(""), //ok
		"jml_pterms"=>htmlspecialchars(""), //ok
		"nama_terms"=>htmlspecialchars(""), //ok
		"based_on"=>htmlspecialchars("SELISIH"), //ok days_pterms
		"total_hutang"=>htmlspecialchars(number_format((float)$total_hutang, 2, '.', ',')), //ok
		"M6_n"=>htmlspecialchars(number_format((float)$M6_n, 2, '.', ',')), //ok
		"M5_n"=>htmlspecialchars(number_format((float)$M5_n, 2, '.', ',')), //ok
		"M4_n"=>htmlspecialchars(number_format((float)$M4_n, 2, '.', ',')), //ok
		"M3_n"=>htmlspecialchars(number_format((float)$M3_n, 2, '.', ',')), //ok
		"M2_n"=>htmlspecialchars(number_format((float)$M2_n, 2, '.', ',')), //ok
		"M1_n"=>htmlspecialchars(number_format((float)$M1_n, 2, '.', ',')), //ok
		"BB_n"=>htmlspecialchars(number_format((float)$BB_n, 2, '.', ',')), //ok 
		);	
		}
	
}

## Response
$totalRecords = $totalRecords - $x;
$totalRecordwithFilter = $totalRecordwithFilter - $x;
$response = array(
  "draw" => intval($draw),
  "iTotalRecords" => $totalRecordwithFilter,
  "iTotalDisplayRecords" => $totalRecords,
  "aaData" => $data
);
echo json_encode($response);
?>