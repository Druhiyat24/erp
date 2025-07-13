<?php
	
	//include "../../include/conn.php";
	class services{
		
		public function connection(){
			return "../../include/conn.php";
			
			
		}
		public function getApproval(){ 
			include "../../include/conn.php";
			$sql = "
			
			SELECT count(M.v_listcode) JLH FROM (
			SELECT MASTER.*,SUM(MASTER.nilai_kontrabons) nilai_kontrabon FROM(

SELECT AP.v_nojournal
		,AP.v_isapproval
		,AP.v_status
        ,AP.v_listcode
		,AP.v_notes
		,DATE(AP.d_insert) date_list
        ,SUM(JOURNAL_DETAIL.total_debit) nilai_kontrabons
		,JOURNAL_HEADER.date_journal tanggal_kontrabon
		,JOURNAL_HEADER.reff_doc
		,BPB.pono
		,BPB.bpbno
		,BPB.id_supplier
		,SUPPLIER.Supplier nama_supplier
		,SUPPLIER.supplier_code supplier_code
		,PO.days_pterms
		,DATE_ADD(JOURNAL_HEADER.date_journal, INTERVAL PO.days_pterms DAY) as jatuh_tempo
	FROM fin_status_journal_ap AP LEFT JOIN(
		SELECT id_journal,SUM(debit) total_debit,SUM(credit) total_credit FROM fin_journal_d
		GROUP BY id_journal
	)JOURNAL_DETAIL ON AP.v_nojournal = JOURNAL_DETAIL.id_journal
	LEFT JOIN(
		SELECT id_journal,date_journal,reff_doc FROM fin_journal_h
	)JOURNAL_HEADER ON AP.v_nojournal = JOURNAL_HEADER.id_journal
	LEFT JOIN (
		SELECT bpbno_int,pono,bpbno,id_supplier FROM bpb 	 GROUP BY bpbno
	)BPB ON JOURNAL_HEADER.reff_doc =BPB.bpbno_int
	LEFT JOIN(
		SELECT Id_supplier,Supplier,supplier_code FROM mastersupplier 
	)SUPPLIER ON SUPPLIER.Id_supplier = BPB.id_supplier
	LEFT JOIN(
		SELECT po_h.pono,po_h.id_terms,terms.days_pterms FROM po_header po_h LEFT JOIN(
			SELECT id, days_pterms FROM masterpterms 
		)terms ON po_h.id_terms = terms.id
	)PO ON BPB.pono = PO.pono WHERE AP.v_source = 'KB' AND AP.v_status='S' AND AP.v_isapproval='1' GROUP BY AP.v_listcode
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
 WHERE AP.v_source = 'PO' AND AP.v_status='S' AND AP.v_isapproval='1' GROUP BY AP.v_listcode) MASTER WHERE MASTER.v_status='S' AND MASTER.v_isapproval='1' GROUP BY MASTER.v_listcode) M";
			$result = $con_new->query($sql);			
			if ($result->num_rows > 0) {
				// output data of each row
				while($row = $result->fetch_assoc()) {
					$jumlah = $row['JLH'];
				}
				return $jumlah;
			}
			else{
				return '0';
				
			}
		}
	}
?>