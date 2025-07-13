<?php 
session_start();

//print_r($_SESSION);
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){

$getListData = new getListData();
$List = $getListData->get();
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function get(){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT MASTER.*,SUM(MASTER.nilai_kontrabons) nilai_kontrabon,count(MASTER.v_listcode) JLH FROM(

SELECT   AP.v_nojournal
		,AP.v_isapproval
		,AP.v_status
        ,AP.v_listcode
		,AP.v_notes
		,DATE(AP.d_insert) date_list
		,if(JOURNAL_DETAIL.percentage !='0' OR JOURNAL_DETAIL.percentage IS NOT NULL OR JOURNAL_DETAIL.percentage !=''
			,( ((JOURNAL_DETAIL.nilai) - ifnull(FD_PPN.ppn,0) ) +  ifnull(FD_PPN.ppn_src,0)  - (((JOURNAL_DETAIL.nilai) - FD_PPN.ppn )*((JOURNAL_DETAIL.percentage/100)))   )
			
			,(JOURNAL_DETAIL.nilai) - ifnull(FD_PPN.ppn,0)  +  ifnull(FD_PPN.ppn_src,0)  )nilai_kontrabons	
		,JOURNAL_HEADER.date_journal tanggal_kontrabon
		,if(JOURNAL_HEADER.reff_doc IS NULL,JOURNAL_DETAIL.bpb_ref,JOURNAL_HEADER.reff_doc )reff_doc
		,ifnull(BPB.pono,POH_WIP.pono)pono
		,BPB.bpbno
		,BPB.id_supplier
		,SUPPLIER.Supplier nama_supplier
		,SUPPLIER.supplier_code supplier_code
		,ifnull(PO.kode_pterms,PT_WIP.kode_pterms) days_pterms
		,DATE_ADD(JOURNAL_HEADER.date_journal, INTERVAL PO.days_pterms DAY) as jatuh_tempo
	FROM fin_status_journal_ap AP LEFT JOIN(

					SELECT A.id_journal
					,A.percentage
					,A.row_id
					,A.is_utang
					,MAX(A.bpb_ref)bpb_ref
					,MAX(A.journal_ref)journal_ref
					,IFNULL(SUM(A.debit),0)Deb
					,IFNULL(SUM(A.credit),0)Cre
					,IFNULL(SUM(A.utang),0)Utang
					,(IFNULL(SUM(A.debit),0) - IFNULL(2*SUM(A.utang),0))nilai
					FROM(
					SELECT a.reff_doc bpb_ref
						,a.reff_doc2 journal_ref
						,MT.percentage
						,a.id_journal
						,a.id_coa
						,a.row_id
						,a.nm_coa
						,a.debit 
						,a.credit
						,if(a.debit !='0',if(c.id_coa IS NOT NULL,a.debit,0),0)utang
						,if(a.debit !='0',if(c.id_coa IS NOT NULL,1,0),0)is_utang
						FROM fin_journal_d a
						INNER JOIN (SELECT id_journal,type_journal,fg_tax,n_pph FROM fin_journal_h
							WHERE type_journal = '14'
						)b ON a.id_journal = b.id_journal 
						LEFT JOIN mapping_utang c ON c.id_coa = a.id_coa
						LEFT JOIN (SELECT idtax,percentage FROM mtax)MT ON MT.idtax = b.n_pph 
						)A GROUP BY A.id_journal,A.bpb_ref




	)JOURNAL_DETAIL ON AP.v_nojournal = JOURNAL_DETAIL.id_journal
	LEFT JOIN(
		SELECT id_journal,date_journal,reff_doc FROM fin_journal_h
	)JOURNAL_HEADER ON AP.v_nojournal = JOURNAL_HEADER.id_journal
	LEFT JOIN (
		SELECT bpbno_int,pono,bpbno,id_supplier,id_item,id_jo FROM bpb 	 GROUP BY bpbno
	)BPB ON JOURNAL_HEADER.reff_doc =BPB.bpbno_int OR JOURNAL_DETAIL.bpb_ref =BPB.bpbno_int
	LEFT JOIN(
		SELECT Id_supplier,Supplier,supplier_code FROM mastersupplier 
	)SUPPLIER ON SUPPLIER.Id_supplier = BPB.id_supplier
	LEFT JOIN(
		SELECT po_h.pono,po_h.id_terms,terms.days_pterms,terms.kode_pterms FROM po_header po_h LEFT JOIN(
			SELECT id, days_pterms,kode_pterms FROM masterpterms 
		)terms ON po_h.id_terms = terms.id
	)PO ON BPB.pono = PO.pono 
	LEFT JOIN po_item POI_WIP ON BPB.id_jo = POI_WIP.id_jo AND BPB.id_item = POI_WIP.id_gen
	LEFT JOIN po_header POH_WIP ON POH_WIP.id = POI_WIP.id_po
	LEFT JOIN(SELECT id, days_pterms,kode_pterms FROM masterpterms )PT_WIP ON POH_WIP.id_terms = PT_WIP.id	
		LEFT JOIN(
			SELECT ifnull(2*credit,0)ppn,ifnull(credit,0)ppn_src,id_coa,nm_coa,id_journal  FROM fin_journal_d WHERE id_journal LIKE '%PK%' AND 
			id_coa IN('15204','15207') AND credit > 0 GROUP BY id_journal
		)FD_PPN ON FD_PPN.id_journal = JOURNAL_HEADER.id_journal		
	WHERE AP.v_source = 'KB' GROUP BY AP.v_listcode
	UNION ALL
	
	
SELECT  AP.v_nojournal
		,AP.v_isapproval
		,AP.v_status
        ,AP.v_listcode
		,AP.v_notes
		,DATE(AP.d_insert) date_list
        ,SUM(PPPO.po_amount) nilai_kontrabons
		,PPPO.podate  tanggal_kontrabon
		,'' reff_doc
		,'' pono
		,'' bpbno
		,'' id_supplier
		,PPPO.supplier nama_supplier
		,PPPO.supplier_code supplier_code
		,PT.days_pterms
		,DATE_ADD(PPPO.podate, INTERVAL PT.days_pterms DAY) as jatuh_tempo
	FROM fin_status_journal_ap AP LEFT JOIN(
	
SELECT po.pono,po.id_terms, po.podate, ms.supplier,ms.supplier_code, o.po_amount, o.paid_amount, o.outstanding_amount
            FROM po_header po 
                LEFT JOIN mastersupplier ms ON po.id_supplier = ms.Id_Supplier
                INNER JOIN (
                     SELECT 
                        o.pono
                        ,SUM(o.po_amount) po_amount
                        ,SUM(IFNULL(p.paid_amount,0)) paid_amount
                        ,(o.po_amount - IFNULL(p.paid_amount,0)) outstanding_amount
                    FROM
                    (
                        SELECT 
                            pono
                            ,id_coa
                            ,nm_coa
                            ,SUM(amount) po_amount
                        FROM
                        (
                            SELECT 
                                ph.pono
                                ,ph.id_supplier
                                ,(pd.qty * pd.price) amount
                                ,mi.matclass kode_group
                                ,ms.vendor_cat
                                ,map.ir_k
                                ,mc.id_coa
                                ,mc.nm_coa
                            FROM
                                po_header ph
                                INNER JOIN po_item pd ON ph.id = pd.id_po
                                INNER JOIN masteritem mi ON pd.id_gen = mi.id_gen
                                LEFT JOIN mastergroup mg ON mi.matclass = mg.nama_group
                                LEFT JOIN mastersupplier ms ON ph.id_supplier = ms.Id_Supplier
                                LEFT JOIN mapping_coa map ON map.id_group = mg.id AND map.vendor_cat = ms.vendor_cat
                                LEFT JOIN mastercoa mc ON map.ir_k = mc.id_coa
                        ) po
                        GROUP BY 
                            pono
                            ,id_coa
                            ,nm_coa
                    ) o
                    LEFT JOIN
                    (
                        SELECT 
                            pono
                            ,id_coa
                            ,nm_coa
                            ,SUM(amount) paid_amount
                        FROM (
                            -- FULFILLMENT
                            SELECT 
                                jh.id_journal
                                ,bpb.pono
                                ,jd.id_coa
                                ,jd.nm_coa
                                ,SUM(jd.debit) amount
                            FROM 
                                fin_journal_h jh
                                LEFT JOIN (
                                    SELECT DISTINCT bpbno_int, pono FROM bpb
                                ) bpb ON jh.reff_doc = bpb.bpbno_int
                                LEFT JOIN fin_journal_d jd ON jh.id_journal = jd.id_journal
                            WHERE jh.type_journal = '3'
                                AND jh.src_reference = 'BPB'
                                AND jd.debit > 0
                                AND jh.fg_post = '2'
                            GROUP BY
                                jh.id_journal
                                ,bpb.pono
                                ,jd.id_coa
                                ,jd.nm_coa
                            UNION ALL
                            -- PREPAYMENT
                            SELECT 
                                jh.id_journal
                                ,jh.reff_doc pono
                                ,jd.id_coa
                                ,jd.nm_coa
                                ,SUM(jd.debit) amount
                            FROM 
                                fin_journal_h jh
                                LEFT JOIN fin_journal_d jd ON jh.id_journal = jd.id_journal
                            WHERE jh.type_journal = '3'
                                AND jh.src_reference = 'PO'
                                AND jd.debit > 0
                                AND jh.fg_post = '2'
                            GROUP BY
                                jh.id_journal
                                ,jh.reff_doc
                                ,jd.id_coa
                                ,jd.nm_coa
                        ) payment
                        GROUP BY 
                            pono
                            ,id_coa
                            ,nm_coa
                    ) p ON o.pono = p.pono AND o.id_coa = p.id_coa
                    GROUP BY o.pono
                 ) o ON po.pono = o.pono
            WHERE 1=1
                AND o.outstanding_amount > 0
            GROUP BY po.pono, po.podate, ms.Supplier
)PPPO ON AP.v_nojournal = PPPO.pono
LEFT JOIN
(masterpterms PT) ON  PPPO.id_terms = PT.id
 WHERE AP.v_source = 'PO' GROUP BY AP.v_listcode) MASTER WHERE MASTER.v_status='S' AND MASTER.v_isapproval='1' GROUP BY MASTER.v_listcode";
		
		
		
		//echo "$q";
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		while($row = mysql_fetch_array($stmt)){ 
				$ekstrak = 0;


			
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"nolist":"'.rawurlencode($row['v_listcode']).'",';
			$outp .= '"checkbox":"'. rawurlencode('0'). '",'; 
			$outp .= '"row":"'. rawurlencode(''). '",'; 
			$outp .= '"nokontrabon":"'. rawurlencode($row["v_nojournal"]). '",'; 	
			$outp .= '"tanggal_kontrabon":"'. rawurlencode($row["tanggal_kontrabon"]). '",'; 
			$outp .= '"supplier_code":"'. rawurlencode($row["supplier_code"]). '",'; 
			$outp .= '"nama_supplier":"'. rawurlencode($row["nama_supplier"]). '",'; 
			$outp .= '"nilai_kontrabon":"'. rawurlencode(number_format($row["nilai_kontrabon"])). '",'; 
			$outp .= '"umur_utang":"'. rawurlencode($row["days_pterms"]). '",'; 
			$outp .= '"jatuh_tempo":"'. rawurlencode($row["jatuh_tempo"]). '",'; 
			$outp .= '"code_status":"'. rawurlencode($row["v_status"]). '",'; 
			$outp .= '"status":"'. rawurlencode($row["v_status"]). '",'; 
			$outp .= '"notes":"'. rawurlencode($row["v_notes"]). '"}'; 
			
		}
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}
?>




