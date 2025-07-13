<?php
// Session Checking
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("F_P_List_Pay","userpassword","username='$user'");
if ($akses=="0")
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>"; }
// Session Checking - End

require_once "../forms/journal_interface.php";



class Assets{
    // Logo path
    public static $logo = '../../include/img-01.png';
}
$A = new Assets();
class Model{
    private $conn;
    private $result;
    private $last_id;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Query helper
     * @param $sql
     * @return $this
     */
    public function query($sql)
    {
        $this->result = mysqli_query($this->conn, $sql);
        if(!$this->result){
            $err = "
            <h3>Database Query Error:</h3>
            <p>".mysqli_error($this->conn)."</p>
            <p>$sql</p>
            ";
            #show_error($err);
        }
        return $this;
    }

    public function last_insert_id()
    {
        return $this->conn->insert_id;
    }

    /**
     * Fetch multiple rows helper
     * @return array
     */
    public function result()
    {
        $rows = array();
        if(!$this->result){
            return $rows;
        }
        while($row = mysqli_fetch_object($this->result)){
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * Fetch single row helper
     * @return null|object
     */
    public function row()
    {
        return mysqli_fetch_object($this->result);
    }

    public function get_master_company()
    {
        return $this->query("
            SELECT * FROM mastercompany;
        ")->row();
    }

    public function get_rekap_ap_list()
    {
		/*$sql="SELECT MASTER.*,SUM(MASTER.nilai_kontrabons) nilai_kontrabon FROM(


SELECT AP.v_nojournal
		,AP.v_isapproval
		,AP.v_status
		,AP.v_listcode
		,AP.v_notes
		,DATE(AP.d_insert) date_list
		,SUM(JOURNAL_DETAIL.nilai) nilai_kontrabons
		,JOURNAL_HEADER.date_journal tanggal_kontrabon
		,if(JOURNAL_DETAIL.bpb_ref IS NULL,JOURNAL_HEADER.reff_doc2,JOURNAL_DETAIL.bpb_ref) reff_doc
		,ifnull(BPB.pono,POH_WIP.pono)pono
		,BPB.bpbno
		,BPB.id_supplier 
		,SUPPLIER.Supplier nama_supplier 
		,SUPPLIER.supplier_code supplier_code
		,ifnull(PO.kode_pterms,POH_WIP.kode_pterms) days_pterms
		,ifnull(DATE_ADD(JOURNAL_HEADER.date_journal, INTERVAL PO.days_pterms DAY),DATE_ADD(JOURNAL_HEADER.date_journal, INTERVAL POH_WIP.days_pterms DAY)) as jatuh_tempo
	FROM fin_status_journal_ap AP LEFT JOIN
	(
    
SELECT 	
			 C.is_normal
			,C.is_utang		
			,C.is_pajak	
			,SUM(C.pajak)pajak
			,SUM(C.debit)debit
			,SUM(C.utang)utang	
			,(SUM(C.debit) - ((ifnull(MAX(C.percentage),0)/100) * ((SUM(C.debit) - SUM(C.pajak)) - SUM(C.utang) ))  - SUM(C.utang))nilai
			,C.bpb_ref		
			,C.journal_ref		
			,C.fg_tax		
			,C.n_pph		
			,C.percentage		
			,C.id_journal		
			,C.id_coa		
			,C.nm_coa
FROM (			
	SELECT 			
			 B.is_normal
			,B.is_utang		
			,B.is_pajak	
			,B.pajak
			,if(B.is_normal = '1',B.debit,0)debit 	
			,B.utang			
			,B.bpb_ref		
			,B.journal_ref		
			,B.fg_tax		
			,B.n_pph		
			,B.percentage		
			,B.id_journal		
			,B.id_coa		
			,B.nm_coa		
  FROM (
  SELECT 	
			 A.pajak		
			,if(A.is_pajak = 'N' AND A.is_utang = 'N','1',0)is_normal
			,A.is_utang		
			,A.is_pajak	
			,A.utang			
			,A.bpb_ref		
			,A.journal_ref		
			,A.fg_tax		
			,A.n_pph		
			,A.percentage		
			,A.id_journal		
			,A.id_coa		
			,A.nm_coa		
			,A.debit 			
		FROM(
			SELECT  a.reff_doc bpb_ref
					,a.reff_doc2 journal_ref
					,a.row_id
					,b.fg_tax
					,b.n_pph
					,MT.percentage
					,a.id_journal
					,a.id_coa
					,a.nm_coa
					,a.debit 
					,a.credit
					,if(a.id_coa LIKE '%15204%' OR a.id_coa LIKE '%15207%',
						if(a.id_coa = '15204',a.debit,a.credit)
					
					 ,0 )pajak
					,if(a.id_coa LIKE '%15204%' OR a.id_coa LIKE '%15207%',
						if(a.id_coa = '15204','P','N')
					
					 ,'N')is_pajak					 
					 ,if(a.debit !='0',if(c.id_coa IS NOT NULL,'U','N'),'N')is_utang
					,if(a.debit !='0',if(c.id_coa IS NOT NULL,a.debit,0),0)utang
					FROM fin_journal_d a
					INNER JOIN (SELECT id_journal,type_journal,fg_tax,n_pph FROM fin_journal_h
						WHERE type_journal = '14'
					)b ON a.id_journal = b.id_journal 
					LEFT JOIN mapping_utang c ON c.id_coa = a.id_coa
					LEFT JOIN (SELECT idtax,percentage FROM mtax)MT ON MT.idtax = b.n_pph WHERE
					a.debit > 0 GROUP BY a.id_journal,a.row_id)A
					)B
				)C	GROUP BY C.id_journal
	)JOURNAL_DETAIL ON AP.v_nojournal = JOURNAL_DETAIL.id_journal
	LEFT JOIN(
		SELECT id_journal,date_journal,reff_doc,reff_doc2 FROM fin_journal_h
	)JOURNAL_HEADER ON AP.v_nojournal = JOURNAL_HEADER.id_journal
	LEFT JOIN (
		SELECT bpbno_int,pono,bpbno,id_supplier,id_item,id_jo FROM bpb 	 GROUP BY bpbno
	)BPB ON JOURNAL_HEADER.reff_doc2 =BPB.bpbno_int OR JOURNAL_DETAIL.bpb_ref =BPB.bpbno_int
	LEFT JOIN(
		SELECT Id_Supplier,Supplier,supplier_code FROM mastersupplier 
	)SUPPLIER ON SUPPLIER.Id_Supplier = BPB.id_supplier
	LEFT JOIN(
		SELECT po_h.pono,po_h.id_terms,terms.days_pterms,terms.kode_pterms FROM po_header po_h LEFT JOIN(
			SELECT id, days_pterms,kode_pterms FROM masterpterms 
		)terms ON po_h.id_terms = terms.id
	)PO ON BPB.pono = PO.pono 
	LEFT JOIN po_item POI_WIP ON BPB.id_jo = POI_WIP.id_jo AND BPB.id_item = POI_WIP.id_gen
	LEFT JOIN(
		SELECT po_h.pono,po_h.id_terms,terms.days_pterms,terms.kode_pterms,po_h.id  FROM po_header po_h LEFT JOIN(
			SELECT id, days_pterms,kode_pterms FROM masterpterms 
		)terms ON po_h.id_terms = terms.id
	)POH_WIP ON POH_WIP.id = POI_WIP.id_po	
		LEFT JOIN(
			SELECT ifnull(2*credit,0)ppn,ifnull(credit,0)ppn_src,id_coa,nm_coa,id_journal  FROM fin_journal_d WHERE id_journal LIKE '%PK%' AND 
			id_coa IN('15204','15207') AND credit > 0 GROUP BY id_journal
		)FD_PPN ON FD_PPN.id_journal = JOURNAL_HEADER.id_journal		

	WHERE AP.v_source = 'KB' AND AP.is_header = 'N' 
	AND AP.is_partial = 'N' GROUP BY AP.v_listcode 
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
		,PT.kode_pterms days_pterms
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
 WHERE AP.v_source = 'PO' GROUP BY AP.v_listcode) MASTER GROUP BY MASTER.v_listcode ORDER BY MASTER.v_listcode DESC
	
	;"; */
	$journal_detail = journal_detail();
	$sql="SELECT MASTER.*,SUM(MASTER.nilai_kontrabons) nilai_kontrabon FROM(

/*kb */
SELECT AP.v_nojournal
		,AP.v_isapproval
		,AP.v_status
		,AP.v_listcode
		,AP.v_notes
		,DATE(AP.d_insert) date_list
		,(JOURNAL_DETAIL.nilai) nilai_kontrabons
		,JOURNAL_HEADER.date_journal tanggal_kontrabon
		,if(JOURNAL_DETAIL.bpb_ref IS NULL,JOURNAL_HEADER.reff_doc2,JOURNAL_DETAIL.bpb_ref) reff_doc
		,ifnull(BPB.pono,POH_WIP.pono)pono
		,BPB.bpbno
		,BPB.id_supplier 
		,SUPPLIER.Supplier nama_supplier 
		,SUPPLIER.supplier_code supplier_code
		,ifnull(PO.kode_pterms,POH_WIP.kode_pterms) days_pterms
		,ifnull(DATE_ADD(JOURNAL_HEADER.date_journal, INTERVAL PO.days_pterms DAY),DATE_ADD(JOURNAL_HEADER.date_journal, INTERVAL POH_WIP.days_pterms DAY)) as jatuh_tempo
	FROM fin_status_journal_ap AP LEFT JOIN
	(
  /* jurnal_detail*/  
		$journal_detail
/* new pph*/
	)JOURNAL_DETAIL ON AP.v_nojournal = JOURNAL_DETAIL.id_journal
	LEFT JOIN(
		SELECT id_journal,date_journal,reff_doc,reff_doc2 FROM fin_journal_h
	)JOURNAL_HEADER ON AP.v_nojournal = JOURNAL_HEADER.id_journal
	LEFT JOIN (
		SELECT bpbno_int,pono,bpbno,id_supplier,id_item,id_jo FROM bpb 	 GROUP BY bpbno
	)BPB ON JOURNAL_HEADER.reff_doc2 =BPB.bpbno_int OR JOURNAL_DETAIL.bpb_ref =BPB.bpbno_int
	LEFT JOIN(
		SELECT Id_Supplier,Supplier,supplier_code FROM mastersupplier 
	)SUPPLIER ON SUPPLIER.Id_Supplier = BPB.id_supplier
	LEFT JOIN(
		SELECT po_h.pono,po_h.id_terms,terms.days_pterms,terms.kode_pterms FROM po_header po_h LEFT JOIN(
			SELECT id, days_pterms,kode_pterms FROM masterpterms 
		)terms ON po_h.id_terms = terms.id
	)PO ON BPB.pono = PO.pono 
	LEFT JOIN po_item POI_WIP ON BPB.id_jo = POI_WIP.id_jo AND BPB.id_item = POI_WIP.id_gen
	LEFT JOIN(
		SELECT po_h.pono,po_h.id_terms,terms.days_pterms,terms.kode_pterms,po_h.id  FROM po_header po_h LEFT JOIN(
			SELECT id, days_pterms,kode_pterms FROM masterpterms 
		)terms ON po_h.id_terms = terms.id
	)POH_WIP ON POH_WIP.id = POI_WIP.id_po	
		LEFT JOIN(
			SELECT ifnull(2*credit,0)ppn,ifnull(credit,0)ppn_src,id_coa,nm_coa,id_journal  FROM fin_journal_d WHERE id_journal LIKE '%PK%' AND 
			id_coa IN('15204','15207') AND credit > 0 GROUP BY id_journal
		)FD_PPN ON FD_PPN.id_journal = JOURNAL_HEADER.id_journal		

	WHERE AP.v_source = 'KB'  GROUP BY AP.v_nojournal
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
		,PT.kode_pterms days_pterms
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
 WHERE AP.v_source = 'PO' GROUP BY AP.v_listcode) MASTER GROUP BY MASTER.v_listcode ORDER BY MASTER.v_listcode DESC
	";
	
        $list = $this->query($sql)->result();
		$list =(array)$list;

        if(!count($list)){
            return array();
        }

        $hash = array();
  /*      foreach($list as $l){
            $hash[$l->v_nojournal]['v_nojournal'] = $l->v_nojournal;
            if(!isset($hash[$l->v_listcode]['v_listcode'])){
                $hash[$l->v_listcode]['v_nojournal'] = $l->v_nojournal;
            }else{
                $hash[$l->v_listcode]['v_nojournal'] .= '<br>'.$l->no_invoice;
            }
        }
		*/
		//print_r($hash);
        return $list;
    }
	
    public function get_master_supplier()
    {
        return $this->query("
SELECT distinct(MASTER.nama_supplier) Supplier  FROM(

SELECT AP.v_nojournal
		,AP.v_isapproval
		,AP.v_status
        ,AP.v_listcode
		,AP.v_notes
		,DATE(AP.d_insert) date_list
        ,(JOURNAL_DETAIL.total_debit) nilai_kontrabons
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
	)PO ON BPB.pono = PO.pono WHERE AP.v_source = 'KB' GROUP BY AP.v_listcode
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
 WHERE AP.v_source = 'PO' GROUP BY AP.v_listcode) MASTER GROUP BY MASTER.v_listcode ORDER BY MASTER.date_list DESC
        ")->result();
    }		

}

// CONTROLLER BEGIN

$M = new Model($con_new);
$listSupplier = $M->get_master_supplier();
$list = $M->get_rekap_ap_list();
// CONTROLLER END


?>
<div class="box">
<div class="box-body">
<div class="col-md-3">
<!-- FILTER TABLE -->
<label>Nama Supplier </label>
<select class="form-control select2" onchange="filter_table()" id="filter_supplier">
	<option value="">--Nama Supplier--</option>
	<?php 
		//$listSupplier;
		if(@count($listSupplier))
		foreach($listSupplier as $sup){
			echo "<option value='$sup->Supplier'>$sup->Supplier</option>";
		}
	?>
</select>
<!-- FILTER TABLE -->
</div>
</div>
</div>
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">Rekap Entry</h3>
          <div class="pull-right">
              <a href="?mod=rekap" class="btn btn-primary">Create Payment List</a>
          </div>
      </div>
      <div class="box-body">
        <table id="tbl1" class="display responsive" style="width:100%;font-size:12px;">
          <thead>
            <tr>
                <th>Tanggal List</th>
                <th>No List</th>
               <!-- <th>No Kontrabon</th>
				 <th>Tanggal Kontrabon</th>
				--> 
				 <th>Nama Supplier</th>
				 <th>Terms of Payment</th>
				  <th>Nilai List Payment</th>
				 <!-- <th>Umur Hutang/AP</th>
				  <th>Tanggal Jatuh Tempo</th>
				 --> 
				  <th>Status</th>
				  <th>Notes</th>
                <th width='14%'>Action</th>
            </tr>
          </thead>
          <?php if(@count($list)):?>
          <tbody>
            <?php $no=1;?>
            <?php foreach($list as $l):?> 
            <tr>

                <td><?php if(!ISSET($l->date_list))
					{
							echo " "; 
					}else{
							echo $l->date_list;
							} 
							?></td>
                <td><?php if(!ISSET($l->v_listcode))
					{
							echo " "; 
					}else{
							//echo $l->v_listcode;
							echo "<a href='#' data-toggle='modal' data-target='#myModalLIST' onclick='getListDataRekap(".'"'.$l->v_listcode.'"'.")' /> $l->v_listcode</a>";
							
							
							
							} 
							?></td>
							
					
                <td><?php if(!ISSET($l->nama_supplier))
					{
							echo " "; 
					}else{
							echo $l->nama_supplier;
							} 
							?></td>			
                <td><?php if(!ISSET($l->days_pterms))
					{
							echo " "; 
					}else{
							echo $l->days_pterms;
							} 
							?></td>		
                <td><?php if(!ISSET($l->nilai_kontrabon))
					{
							echo " "; 
					}else{
							echo number_format($l->nilai_kontrabon,2,',','.');
							} 
							?></td>	

                <td><?php if(!ISSET($l->v_status))
					{
							echo " "; 
					}else{
							echo $l->v_status;
							} 
							?></td>		
                <td><?php if(!ISSET($l->v_notes))
					{
							echo " "; 
					}else{
							echo $l->v_notes;
							} 
							?></td>								
                <td>
					<a href="pdf_listpaymen.php?&id=<?=$l->v_listcode?>" class="btn btn-info" title="Print" ><i class='fa fa-print'></i></a>				
				<?php
				if($l->v_status == 'W'){
				?>	
					<?php if($l->v_isapproval == '0'){ ?>
					<a href="?mod=rekap&id=<?=$l->v_listcode?>&view=1" class="btn btn-info" title="detail" ><i class='fa fa-info'></i></a>					
                    <a href="?mod=rekap&id=<?=$l->v_listcode?>" class="btn btn-primary" title="edit"><i class='fa fa-pencil'></i></a> 
						<a href="#" class="btn btn-info" onclick="executed('<?=$l->v_listcode?>','SEND')"  title="kirim"><i class='fa fa-send'></i></a>	
					<a href="#" class="btn btn-danger" onclick="executed('<?=$l->v_listcode?>','DELETE')"  title="delete"><i class='fa fa-trash'></i></a>							
					<?php } ?>
				
<?php
				}else if($l->v_status == 'R'){
?>
										<a href="?mod=rekap&id=<?=$l->v_listcode?>&view=1" class="btn btn-info" title="detail"><i class='fa fa-info'></i></a>
                    <a href="?mod=rekap&id=<?=$l->v_listcode?>" class="btn btn-primary" title="edit"><i class='fa fa-pencil'></i></a> 					
<?php					
				}else if($l->v_status == 'C'){
?>
					<a href="?mod=rekap&id=<?=$l->v_listcode?>&view=1" class="btn btn-info" title="detail"><i class='fa fa-info'></i></a>
					<a href="#" class="btn btn-danger" onclick="executed('<?=$l->v_listcode?>','DELETE')"  title="delete"><i class='fa fa-trash'></i></a> 		
					
<?php					
				}	
				else if($l->v_status == 'A'){
?>
					<a href="?mod=rekap&id=<?=$l->v_listcode?>&view=1" class="btn btn-info" title="detail"><i class='fa fa-info'></i></a>				
<?php					
				}
				else if($l->v_status == 'S'){
?>
					<a href="?mod=rekap&id=<?=$l->v_listcode?>&view=1" class="btn btn-info" title="detail"><i class='fa fa-info'></i></a>				
<?php					
				}
?>				


                </td>
            </tr>
            <?php endforeach;?>
          </tbody>
          <?php endif;?>
        </table>
      </div>
    </div>
	
	

<div id="myModalLIST" class="modal fade " role="dialog">
  <div class="modal-dialog modal-dialog modal-lg" role="document">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="HeaderModal">XX</h4>
      </div>
      <div class="modal-body ">
		<div id="ListModal">
		
		</div>
      </div>
      <div class="modal-footer">
		 <button type="button" id="myBack" onclick='back()' class="btn btn-danger" style="float:right;margin-right:2px" >Back</button> &nbsp;
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="js/ListPayment.js"></script>
<script src="../appr/js/ApprovalPage.js"></script>
<script>
    $(document).ready(function(){
        tbl1= $('#tbl1').DataTable
        ({  scrollY: "300px",
            scrollCollapse: true,
            paging: true,
            pageLength: 20,
			{ "sSortDataType": "title-date", "sType": "string", "aTargets": [ 0 ] }
            /*fixedColumns:
                { leftColumns: 1,
                    rightColumns: 1
                }*/
        });			
	});		
	function filter_table()
    {	
	var filter_supplier = $('#filter_supplier').val();
			tbl1.column(2).search(filter_supplier);
			 tbl1.draw();
    }			
</script>