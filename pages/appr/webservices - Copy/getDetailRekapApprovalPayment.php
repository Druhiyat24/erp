<?php 
session_start();


$data = $_POST;
$getListData = new getListData();
$List = $getListData->get($_POST['no_list']);
print_r($List);

class getListData {
	public function get($id){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT A.*
				,B.debit
				,B.credit
				,C.id_journal nokontrabon
				,C.date_journal tglkontrabon	
				,BPB.pono no_po
				,PO.podate tgl_po				
				,C.date_journal
				,BPB.pono po_no
				,PO.jml_pterms days_pterms
				,DATE_ADD(C.date_journal, INTERVAL PO.jml_pterms DAY) as  jatuh_tempo			
				,SUPPLIER.Supplier
				,SUPPLIER.supplier_code
				,if(B.debit > B.credit,B.debit,B.credit) nilai_kontrabon
				,PO.curr
			FROM fin_status_journal_ap A
				LEFT JOIN (SELECT row_id,id_journal,sum(debit)debit,sum(credit) credit,reff_doc reff_doc_d FROM fin_journal_d GROUP BY id_journal)B ON A.v_nojournal = B.id_journal
				LEFT JOIN(SELECT id_journal,reff_doc,date_journal FROM fin_journal_h)C
				ON A.v_nojournal = C.id_journal
					LEFT JOIN (
						SELECT bpbno_int,id_supplier,pono FROM bpb GROUP BY bpbno_int
					)BPB on BPB.bpbno_int = C.reff_doc OR B.reff_doc_d =BPB.bpbno_int
					LEFT JOIN(
						SELECT 	Id_Supplier,Supplier,supplier_code FROM mastersupplier
					)SUPPLIER ON BPB.id_supplier = SUPPLIER.Id_Supplier				
					LEFT JOIN(
						SELECT po_h.pono,po_h.id_terms,terms.days_pterms,poi.curr,po_h.podate,po_h.jml_pterms FROM po_header po_h LEFT JOIN(
							SELECT id, days_pterms FROM masterpterms 
						)terms ON po_h.id_terms = terms.id
					LEFT JOIN (
						SELECT id_po,curr curr FROM po_item
					)poi ON poi.id_po = po_h.id	
					)PO ON BPB.pono = PO.pono				
			WHERE A.v_listcode = '$id'  AND A.v_source = 'KB' GROUP BY A.v_nojournal
			
			UNION ALL
			
                SELECT A.*
				,'' debit
				,'' credit
				,'' nokontrabon
				,'' tglkontrabon
				,PPPO.pono no_po
				,PPPO.podate tgl_po
				,PPPO.podate date_journal
				,PPPO.pono
				,PPPO.jml_pterms days_pterms
				,DATE_ADD(PPPO.podate, INTERVAL PPPO.jml_pterms DAY) as  jatuh_tempo	
				,PPPO.supplier Supplier
				,PPPO.supplier_code
				,PPPO.po_amount nilai_kontrabon
				,PPPO.curr
			FROM fin_status_journal_ap A

LEFT JOIN (
SELECT po.jml_pterms,po.pono,po.id_terms, po.podate, ms.supplier,ms.supplier_code,o.po_amount,o.curr, o.paid_amount, o.outstanding_amount
            FROM po_header po 
                LEFT JOIN mastersupplier ms ON po.id_supplier = ms.Id_Supplier
                INNER JOIN (
                     SELECT 
                        o.pono
						,o.curr
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
							,po.curr
                        FROM
                        (
                            SELECT 
                                ph.pono
                                ,ph.id_supplier
								,pd.curr
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


)PPPO ON A.v_nojournal = PPPO.pono 
LEFT JOIN
(masterpterms PT) ON  PPPO.id_terms = PT.id

WHERE A.v_source = 'PO' AND A.v_listcode = '$id' GROUP BY A.v_nojournal";
			
		
		
		
		//echo "$q";
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		while($row = mysql_fetch_array($stmt)){ 
				
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"no_kontra_bon":"'.rawurlencode($row['nokontrabon']).'",';
			$outp .= '"list_code":"'. rawurlencode($row["v_listcode"]). '",';
			$outp .= '"tgl_kontra_bon":"'. rawurlencode($row["tglkontrabon"]). '",'; 
			$outp .= '"no_po":"'. rawurlencode($row["no_po"]). '",'; 
			$outp .= '"tgl_po":"'. rawurlencode($row["tgl_po"]). '",'; 
			$outp .= '"kode_supplier":"'. rawurlencode($row["supplier_code"]). '",'; 
			$outp .= '"nama_supplier":"'. rawurlencode($row["Supplier"]). '",'; 
			$outp .= '"nilai":"'. rawurlencode(number_format($row["nilai_kontrabon"])). '",'; 
			$outp .= '"umur_ap":"'. rawurlencode($row["days_pterms"]). '",'; 
			$outp .= '"curr":"'. rawurlencode($row["curr"]). '",'; 
			$outp .= '"v_source":"'. rawurlencode($row["v_source"]). '",'; 
			$outp .= '"jatuh_tempo":"'. rawurlencode($row["jatuh_tempo"]). '"}'; 
		}
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}
?>




