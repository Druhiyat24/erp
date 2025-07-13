<?php
// Session Checking

if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("F_P_Rekap_AP_FORM","userpassword","username='$user'");
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
    public function get_kontrabon_list()
    {
		/*$q= "SELECT  JH.id_journal
					,JH.date_journal
					,JH.type_journal
					,JH.reff_doc
					,JD.debit
					,BPB.id_supplier
					,BPB.pono
					,SUPPLIER.Supplier
					,SUPPLIER.supplier_code
					,PO.days_pterms
					,DATE_ADD(JH.date_journal, INTERVAL PO.days_pterms DAY) as  jatuh_tempo	
					,ORDRCV.qty_order
					,ORDRCV.qty_rcv						
					,ORDRCV.is_complete	
					FROM fin_journal_h JH 
					LEFT JOIN(
						SELECT id_journal,row_id,sum(debit) debit FROM fin_journal_d GROUP BY id_journal
					)JD ON JH.id_journal = JD.id_journal
					LEFT JOIN (
						SELECT bpbno_int,id_supplier,pono FROM bpb GROUP BY bpbno_int
					)BPB on BPB.bpbno_int = JH.reff_doc
					LEFT JOIN(
						SELECT 	Id_Supplier,Supplier,supplier_code FROM mastersupplier
					)SUPPLIER ON BPB.id_supplier = SUPPLIER.Id_Supplier
					LEFT JOIN(
						SELECT po_h.pono,po_h.id_terms,terms.days_pterms, terms.cri_pterms FROM po_header po_h LEFT JOIN(
							SELECT id, days_pterms, cri_pterms FROM masterpterms 
						)terms ON po_h.id_terms = terms.id
					)PO ON BPB.pono = PO.pono
					LEFT JOIN (
						SELECT ORD.pono, qty_order, qty_rcv, case when qty_rcv >= qty_order then 1 ELSE 0 END is_complete FROM
						(
							SELECT pono, SUM(poi.qty) qty_order
							FROM po_header poh
							INNER JOIN po_item poi ON poh.id = poi.id_po
							GROUP BY pono
						) ORD
						INNER JOIN 
						(
							SELECT pono, SUM(qty) qty_rcv FROM bpb
							GROUP BY pono
						) rcv
						ON ORD.pono = rcv.pono
					) ORDRCV ON BPB.pono = ORDRCV.pono
					WHERE JH.type_journal = '14' AND JH.src_reference = 'BPB'
					AND (
						 (PO.id_terms IN (3,8) AND ORDRCV.is_complete = 1)
						OR
						PO.id_terms NOT IN (3,8) 
						(PO.cri_pterms ='BPB' AND ORDRCV.is_complete = 1)
						OR
						PO.id_terms != 'BPB'
					)
					AND JH.id_journal NOT IN (SELECT v_nojournal FROM fin_status_journal_ap WHERE v_status IN('A','W','S') AND JH.fg_post = '2')"
					*/
		$where_po = $this->get_where_po();
		$j_detail = journal_detail();
		$q = "SELECT X.* FROM (
SELECT JH.id_journal
		,JH.date_journal 
		,JH.fg_post
		,JD.nilai before_pph
		,JD.nilai debit

		,ifnull(JD.percentage,0)percentage
		,JD.bpb_ref
		,JD.journal_ref
		,MSS.Id_Supplier id_supplier
		,MSS.Supplier
		,if(POH.pono IS NULL,'WIP',POH.pono)pono
		,ifnull(POH.pono,POH_WIP.pono) no_po
		,ifnull(POH.podate,POH_WIP.podate) tgl_po
		,ifnull(POH.id_terms,POH_WIP.id_terms)id_terms
		,ifnull(POH.jml_pterms,POH_WIP.jml_pterms) days_pterms
		,POH.id_dayterms
		,ifnull(DATE_ADD(JH.date_journal, INTERVAL POH.jml_pterms DAY)
			,DATE_ADD(JH.date_journal, INTERVAL POH_WIP.jml_pterms DAY)
		) as  jatuh_tempo	
		FROM fin_journal_h JH 
		LEFT JOIN (	
		/*journal Detail */
			$j_detail
		/*journal Detail */
		)JD ON JD.id_journal = JH.id_journal 
		LEFT JOIN bpb BPB ON JD.bpb_ref = BPB.bpbno_int	
		LEFT JOIN po_item POI_WIP ON BPB.id_jo = POI_WIP.id_jo AND BPB.id_item = POI_WIP.id_gen
		LEFT JOIN po_header POH_WIP ON POH_WIP.id = POI_WIP.id_po
		LEFT JOIN mastersupplier MSS ON MSS.Id_Supplier = BPB.id_supplier
		LEFT JOIN po_header POH ON BPB.pono = POH.pono
		LEFT JOIN masterpterms MPT ON MPT.id = POH.id_terms

		WHERE 1  AND JH.id_journal NOT IN (SELECT v_nojournal FROM fin_status_journal_ap WHERE v_status IN('A','W','S') )  AND JH.type_journal = '14' AND JH.fg_post='2'
		GROUP BY JH.id_journal
		)X WHERE 1=1 AND X.pono NOT IN($where_po)";		
	//echo "<pre>".$q."</pre>";
		$my_result = $this->query($q)->result();
/* 		if(count($my_result > 0)){
			for($i=0;$i < count($my_result);$i++){
				$value_pph = get_pph_kontra_bon($my_result[$i]->id_journal,"KB");
				
				$my_result[$i]->debit = $my_result[$i]->debit - $value_pph;
			}
			//print_r($my_result[$i]->id_journal);
		} */
        return $my_result;
    }
	public function get_where_po(){
		$sql = "SELECT po.pono

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
								,ph.id_terms
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
				LEFT JOIN
				(masterpterms PT) ON  po.id_terms = PT.id
				LEFT JOIN masterdayterms MDT ON MDT.id = po.id_dayterms
			 
								
            WHERE 1=1
			AND o.outstanding_amount > 0 AND po.pono NOT IN (SELECT v_nojournal FROM fin_status_journal_ap WHERE v_status IN('A','W','S'))
				AND po.id_terms IN ('1001','1002','1011','1012')
				AND po.id_dayterms = '1'
                AND PT.aktif = 'Y'
            GROUP BY po.pono, po.podate, ms.Supplier";
			$x= $this->query($sql)->result();
			$po__ = "";
			foreach($x as $XX){
			//	print_r($XX);
				$po__ .= "'".$XX->pono."',";
			}
			$po__ .= "'"."XXX"."'";
			return $po__;
		
	}
	
	
    public function get_po_list()
    {
		$sql = "SELECT 
				if(po.id_terms = '1001','DP - Complete quantity',
					if(po.id_terms = '1002','DP - Partial shipment allowed',
						if(po.id_terms = '1005','COD',if(po.id_terms = '1006','CBD',if(po.id_terms = '1004','Credit - Partial shipment allowed','-')))
					)	
				)type_data
				,po.jml_pterms days_pterms,po.id_terms,po.id_dayterms,po.pono,po.id, po.podate,po.id_supplier, ms.supplier,ms.supplier_code, o.po_amount, o.paid_amount,
				DATE_ADD(po.podate, INTERVAL po.jml_pterms DAY) as jatuh_tempo,
			o.outstanding_amount
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
								,ph.id_terms
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
				LEFT JOIN
				(masterpterms PT) ON  po.id_terms = PT.id
				LEFT JOIN masterdayterms MDT ON MDT.id = po.id_dayterms
			 
								
            WHERE 1=1
			AND o.outstanding_amount > 0 AND po.pono NOT IN (SELECT v_nojournal FROM fin_status_journal_ap WHERE v_status IN('A','W','S'))
				AND po.id_terms IN ('1001','1002','1011','1012')
                AND PT.aktif = 'Y'
            GROUP BY po.pono, po.podate, ms.Supplier";
			//echo $sql;
        return $this->query($sql)->result();// TODO: filter terms by type COD, CBD, DP. not by terms id
    }	
	
    public function get_po_list_installment_dp()
    {
		$sql = "
SELECT 
 PO.type_data
,PO.days_pterms
,PO.id_terms
,PO.id_dayterms
,PO.pono
,PO.id
,PO.podate
,PO.id_supplier
,PO.supplier
,PO.supplier_code
,PO.po_amount
,PO.paid_amount
,PO.jatuh_tempo
,PO.outstanding_amount
,INS.pono pono_ins
,INS.d_payment
,INS.id_journal no_bayar 
FROM fin_installment INS
INNER JOIN(
SELECT 
				 PT.kode_pterms type_data
				,po.jml_pterms days_pterms
				,po.id_terms
				,po.id_dayterms
				,po.pono
				,po.id
				,po.podate
				,po.id_supplier
				,ms.supplier
				,ms.supplier_code
				,o.po_amount
				,o.paid_amount
				,DATE_ADD(po.podate, INTERVAL po.jml_pterms DAY) as jatuh_tempo
				,o.outstanding_amount
				FROM po_header po 
                LEFT JOIN mastersupplier ms ON po.id_supplier = ms.Id_Supplier
                INNER JOIN (
                     SELECT 
                        o.pono
                        ,SUM(o.po_amount) outstanding_amount
						,SUM(o.po_amount) po_amount
                        ,SUM(IFNULL(p.paid_amount,0)) paid_amount
                        ,(o.po_amount - IFNULL(p.paid_amount,0) ) outstanding_amount_cbd
							,o.qty
							,o.price
,o.id_po			
                    FROM
                    (
                        SELECT 
                            pono
                            ,id_coa
                            ,nm_coa
                            ,(SUM(amount) / po.n_installment ) po_amount
							,po.qty
							,po.price
							,po.id_po
							,po.n_installment
                        FROM
                        (
                            SELECT 
                                ph.pono
								,ph.id_terms
                                ,ph.id_supplier
								,ph.n_installment
								,pd.qty
								,pd.price
								,pd.id_po
                                ,(pd.qty * pd.price) amount
                                ,mi.matclass kode_group
                                ,ms.vendor_cat
                                ,map.ir_k
                                ,mc.id_coa
                                ,mc.nm_coa
                            FROM
                                po_header ph
                                INNER JOIN po_item pd ON ph.id = pd.id_po
                                INNER JOIN masteritem mi ON pd.id_gen = mi.id_item
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
				LEFT JOIN
				(masterpterms PT) ON  po.id_terms = PT.id
				LEFT JOIN masterdayterms MDT ON MDT.id = po.id_dayterms	
            WHERE 1=1
			AND o.outstanding_amount > 0 AND po.pono NOT IN (SELECT v_nojournal FROM fin_status_journal_ap WHERE v_status IN('A','W','S'))
				AND po.id_terms IN ('1011','1012')
                AND PT.aktif = 'Y'
            GROUP BY po.pono, po.podate, ms.Supplier) PO ON PO.pono = INS.pono WHERE INS.id_journal IS NULL";
			//echo $sql;
        return $this->query($sql)->result();// TODO: filter terms by type COD, CBD, DP. not by terms id
    }		
	
	
	
    public function get_rekap($id)
    {
	$j_detail = journal_detail();
    $sql = "
SELECT   A.*
		,JOURNAL_DETAIL.nilai debit
		,JOURNAL_DETAIL.nilai credit
		,JOURNAL_HEADER.id_journal nokontrabon
		,JOURNAL_HEADER.date_journal tglkontrabon	
		,ifnull(BPB.pono,POH_WIP.pono) no_po
		,ifnull(PO.podate,POH_WIP.podate) tgl_po				
		,JOURNAL_HEADER.date_journal
		,ifnull(BPB.pono,POH_WIP.podate) po_no
		,ifnull(PO.jml_pterms,POH_WIP.jml_pterms) days_pterms
		,ifnull(DATE_ADD(JOURNAL_HEADER.date_journal, INTERVAL PO.jml_pterms DAY),DATE_ADD(JOURNAL_HEADER.date_journal, INTERVAL POH_WIP.jml_pterms DAY)) as  jatuh_tempo			
		,SUPPLIER.Supplier
		,SUPPLIER.supplier_code
		,JOURNAL_DETAIL.nilai nilai_kontrabon
		,ifnull(PO.curr,POI_WIP.curr)curr
			FROM fin_status_journal_ap A
				LEFT JOIN 
				(		
					/*journal Detail */
						$j_detail
					/*journal Detail */
				)JOURNAL_DETAIL ON A.v_nojournal = JOURNAL_DETAIL.id_journal
				LEFT JOIN(SELECT id_journal,reff_doc,date_journal FROM fin_journal_h)JOURNAL_HEADER
				ON A.v_nojournal = JOURNAL_HEADER.id_journal
					LEFT JOIN (
						SELECT bpbno_int,id_supplier,pono,id_jo,id_item FROM bpb GROUP BY bpbno_int
					)BPB on BPB.bpbno_int = JOURNAL_HEADER.reff_doc OR JOURNAL_DETAIL.bpb_ref =BPB.bpbno_int
					LEFT JOIN(
						SELECT 	Id_Supplier,Supplier,supplier_code FROM mastersupplier
					)SUPPLIER ON JOURNAL_DETAIL.id_supplier = SUPPLIER.Id_Supplier				
					LEFT JOIN(
						SELECT po_h.pono,po_h.id_terms,terms.days_pterms,poi.curr,po_h.podate,po_h.jml_pterms FROM po_header po_h LEFT JOIN(
							SELECT id, days_pterms FROM masterpterms 
						)terms ON po_h.id_terms = terms.id
					LEFT JOIN (
						SELECT id_po,curr curr FROM po_item
					)poi ON poi.id_po = po_h.id	
					
					)PO ON BPB.pono = PO.pono	
					LEFT JOIN po_item POI_WIP ON BPB.id_jo = POI_WIP.id_jo AND BPB.id_item = POI_WIP.id_gen
					LEFT JOIN po_header POH_WIP ON POH_WIP.id = POI_WIP.id_po
					LEFT JOIN(SELECT id, days_pterms FROM masterpterms )PT_WIP ON POH_WIP.id_terms = PT_WIP.id	
		LEFT JOIN(
			SELECT ifnull(2*credit,0)ppn,ifnull(credit,0)ppn_src,id_coa,nm_coa,id_journal  FROM fin_journal_d WHERE id_journal LIKE '%PK%' AND 
			id_coa IN('15204','15207') AND credit > 0 GROUP BY id_journal
		)FD_PPN ON FD_PPN.id_journal = JOURNAL_HEADER.id_journal						
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
        //return $this->query($sql)->result();
		$my_result = $this->query($sql)->result();
/* 		if(count($my_result > 0)){
			$value_pph = 0;
			for($i=0;$i < count($my_result);$i++){
				$value_pph = 0;
				if($my_result[$i]->v_source == "KB"){
				$value_pph = get_pph_kontra_bon($my_result[$i]->nokontrabon,"KB");
				//$my_result[$i]->nilai_kontrabon = $my_result[$i]->nilai_kontrabon - $value_pph;					
				}
			}
			//print_r($my_result[$i]->id_journal);
		} */
		//echo "<pre>$</pre>"
        return $my_result;		
    }
    public function save($data)
    {

        $id_rekap = generate_rekap_id('AP');
        $sql = "
            INSERT INTO fin_status_journal_ap
              (v_listcode, v_nojournal, v_status,v_source)
            VALUES 
        ";
        $detail = array();
        foreach($data as $d){
            $detail[] = "('{$id_rekap}', '{$d['id_journal']}', 'W', '{$d['source']}')";
        }
        $sql .= implode(', ', $detail);

        return $this->query($sql);
//        return $id_journal; //$this->conn->insert_id;
    }
    public function update($id_rekap, $data)
    {
		$delete = "";
		$count = count($data);

		$tr = 0;
		foreach($data as $myRow){
			$tr++;
			$delete .= "'".$myRow['id_journal']."'";
			if(intval($tr) < intval($count)){
					$delete .= ",";
			
			}else{
			$delete .= '';
			}
			
		}

		//echo "ID:$delete ";
		//print_r($data);
		//die();
        $this->query("
            DELETE FROM fin_status_journal_ap WHERE v_listcode = '$id_rekap' 
        ");
	//echo " DELETE FROM fin_status_journal_ap WHERE v_listcode = '$id_rekap' AND v_nojournal NOT IN($delete)";
	//die();
	   $sql = "
            INSERT INTO fin_status_journal_ap 
              (v_listcode, v_nojournal,v_status,v_source)
            VALUES 
        ";
        $detail = array();
        foreach($data as $d){
            $detail[] = "('{$id_rekap}', '{$d['id_journal']}', 'W', '{$d['source']}')";
        }




        $sql .= implode(', ', $detail);


        return $this->query($sql);
    }
	
    public function get_master_supplier()
    {
        return $this->query("
SELECT distinct(QQQ.Supplier)Supplier FROM(
SELECT  distinct(QQ.Supplier)Supplier FROM(
SELECT  JH.id_journal
		,JH.date_journal 
		,JH.fg_post
		,JD.nilai debit
		,JD.bpb_ref
		,JD.journal_ref
		,MSS.Id_Supplier 
		,MSS.Supplier
		,POH.pono
		,POH.id_terms
		,POH.jml_pterms days_pterms
		,POH.id_dayterms
		,DATE_ADD(JH.date_journal, INTERVAL POH.jml_pterms DAY) as  jatuh_tempo	
		FROM fin_journal_h JH 
		LEFT JOIN (
			SELECT reff_doc bpb_ref
					,reff_doc2 journal_ref
					,id_journal
					,sum(debit)nilai FROM fin_journal_d GROUP BY id_journal
		)JD ON JD.id_journal = JH.id_journal 
		LEFT JOIN bpb BPB ON JD.bpb_ref = BPB.bpbno_int	
		LEFT JOIN mastersupplier MSS ON MSS.Id_Supplier = BPB.id_supplier
		LEFT JOIN po_header POH ON BPB.pono = POH.pono
		LEFT JOIN masterpterms MPT ON MPT.id = POH.id_terms
		WHERE JH.type_journal = '14' GROUP BY JH.id_journal
		AND JH.id_journal NOT IN (SELECT v_nojournal FROM fin_status_journal_ap WHERE v_status IN('A','W','S') AND JH.fg_post = '2')	
)QQ
UNION ALL
				
				SELECT distinct(QQ.Supplier)Supplier FROM(
				SELECT 
				if(po.id_terms = '1001','DP - Complete quantity',
					if(po.id_terms = '1002','DP - Partial shipment allowed',
						if(po.id_terms = '1005','COD',if(po.id_terms = '1006','CBD','-'))
					)	
				)type_data
				,po.jml_pterms days_pterms,po.id_terms,po.id_dayterms,po.pono,po.id, po.podate,po.id_supplier, ms.supplier,ms.supplier_code, o.po_amount, o.paid_amount,
				DATE_ADD(po.podate, INTERVAL po.jml_pterms DAY) as jatuh_tempo,
			o.outstanding_amount
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
								,ph.id_terms
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
				LEFT JOIN
				(masterpterms PT) ON  po.id_terms = PT.id
				LEFT JOIN masterdayterms MDT ON MDT.id = po.id_dayterms
			 
								
            WHERE 1=1
			AND o.outstanding_amount > 0 AND po.pono NOT IN (SELECT v_nojournal FROM fin_status_journal_ap WHERE v_status IN('A','W','S'))
			--	AND po.id_terms IN (1001,1002,1006,1005)
				AND po.id_dayterms = '1'
                AND PT.aktif = 'Y'
            GROUP BY po.pono, po.podate, ms.Supplier)QQ )QQQ
        ")->result();
    }		
	
}
// CONTROLLER BEGIN

$M = new Model($con_new);

$ch = new Coa_helper();
echo "<script>";
echo "tmp_s = [];";
echo "</script>";
$id = isset($_GET['id']) ? $_GET['id'] : '';
$view = isset($_GET['view']) ? $_GET['view'] : '';
if(isset($_POST['submit'])){

	
    $data = array();
    if($_POST['id_journal'] and @count($_POST['id_journal'])){
        foreach($_POST['id_journal'] as $k=>$v){
            $data[] = array(
                'id_journal' => $_POST['id_journal'][$k],
                'id_rekap' => $_POST['id_rekap'],
				'source' => $_POST['source'][$k]
            );
        }


		
        if($_POST['mode'] == 'save'){
            $M->save($data);
        }elseif($_POST['mode'] == 'update'){
            $M->update($id, $data);
        }
        echo "<script>window.location.href='?mod=listpayment';</script>";exit();
    }else{
        echo '<script>alert("Rekap tidak boleh kosong!");</script>';
    }
}else{
    if($id){
        $rows = $M->get_rekap($id);
    }
}
$listSupplier = $M->get_master_supplier();
$list = $M->get_kontrabon_list();
//print_r($list);
$listpo = $M->get_po_list();
$listpo_idp = $M->get_po_list_installment_dp();
// CONTROLLER END 
?>
<link rel="stylesheet" href="./css/tab.css"> 
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="js/global.js"></script>

<form method='post' name='form' enctype='multipart/form-data' action="" onsubmit='return validasi();return false'>
    <div class='box'>
        <div class='box-body'>
            <div class='row'>
                <div class='col-md-3'>
                    <div class='form-group'>
                        <div class='form-group'>
                            <label>Nomor Rekap</label>
                            <input type='text' id='id_rekap' class='form-control' name='id_rekap' readonly
                                   placeholder='(Auto)' value='<?=(isset($id))?$id:''?>'>
                        </div>
                    </div>
                </div>



                <div class="col-md-12">
                    <input type='hidden' name='mode' value='<?=$id ? 'update' : 'save';?>'>
                    <button type='submit' name='submit' class='btn btn-primary'
					<?=$view =='1' ? 'style="display:none"' : '';?>
					
					>Simpan</button>
                </div>
            </div>
        </div>
    </div>


    <div class="box">
      <div class="box-header">
        <h3 class="box-title">Rekap Entry</h3>
      </div>
      <div class="box-body">
        <table id="tbl2" class="display responsive" style="width:100%;font-size:12px;">
          <thead>
                <tr>
                    <th>No Kontra Bon</th>
					<th>Tanggal Kontra Bon</th>
					 <th>No PO</th>
					 	<th>Tanggal PO</th>
						<th>Terms Of Payment</th>
						<th>Nama Supplier</th>
<!--                    <th>Mata Uang</th>-->
						<th>Nilai</th>
						<th>Umur AP</th>
						<th>Tanggal Jatuh Tempo</th>
                    <th width='14%'>Action</th>
                </tr>
          </thead>
          <?php if(@count($rows)):?>
          <tbody>
            <?php $no=1;
			?>
			<?php foreach($rows as $l):?>

<?php 
					echo "<script>";
					echo "tmp_s.push(parseFloat('".$l->id_supplier."'))";
					echo "</script>";
?>			
                        <tr>

                            <td><?=$l->nokontrabon?><input type="hidden" name="id_journal[]" value="<?=$l->v_nojournal ?>" />
							<input type="hidden" name="source[]" value="<?=$l->v_source ?>" />
							
							
							</td>
							<td class="text-left"><?=$l->tglkontrabon?></td>
							<td class="text-left"><?=$l->no_po?></td>
							<td class="text-left"><?=$l->tgl_po?></td>
							<td class="text-left"><?=$l->days_pterms?></td>							
							<td class="text-left"><?=$l->Supplier?></td>
							                            <td class="text-left"><?=number_format($l->nilai_kontrabon,2,'.',',')?></td> 
							<td class="text-left"><?=$l->days_pterms?></td>							
							<td class="text-left"><?=$l->jatuh_tempo?></td>							

							 
                            <td>


                    <a
					<?php
					if($l->v_source == "KB"){
						echo "id=$l->v_nojournal";
						
					}else if($l->v_source == "PO"){
						echo "id=".str_replace('/','_',$l->no_po);
						
					}
					?>
					href="#" class="btn btn-danger btn-del"
					<?=$view =='1' ? 'style="display:none"' : '';?>
					
					>Remove</a>
                            </td>
                        </tr>
            <?php endforeach;?>
          </tbody>
          <?php endif;?>
        </table>
      </div>
    </div>

</form>
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



            <div class="panel with-nav-tabs panel-default">
                <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab1default" data-toggle="tab" onclick="klik('KB')"  >Kontra Bon</a></li>
                            <li><a href="#tab2default" data-toggle="tab" onclick="klik('PO')"  >PO</a></li>
							<li><a href="#tab3default" data-toggle="tab" onclick="klik('PO')"  >PO(Installment-DP)</a></li>
                        </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1default">

							<div class="box list" >
<form id="form_detail" method='post' name='form' enctype='multipart/form-data' action=""  >
    <div class='box'>
        <div class="box-header">
            <h3 class="box-title">Tambah/Edit Entry</h3>
        </div>
        <div class='box-body'>
            <table id="tbl1" class="display responsive" style="width:100%;font-size:12px;">
                <thead>
                <tr>
                    <th>No</th>
                    <th>No. Kontra Bon</th>
					<th>Tanggal Kontra Bon</th>
					 <th>Terms Of Payment</th>
					 <th>Nama Supplier</th>
<!--                    <th>Mata Uang</th>-->
                    <th>Nilai</th>
					 <th>Umur AP</th>
					 <th>Tanggal Jatuh Tempo</th>
                    <th width='14%'>Action</th>
                </tr>
                </thead>
                <?php if(@count($list)):?>
                    <tbody>
                    <?php $no=1;?>
                    <?php foreach($list as $l):?>
                        <tr>
                            <td><?=$no++?></td>
                            <td><?=$l->id_journal?></td>
 <td class="text-left"><?=$l->date_journal?></td>
 <td class="text-left"><?=$l->days_pterms?></td>
  <td class="text-left"><?=$l->Supplier?></td>
                            <td class="text-left"><?=number_format($l->debit,2,'.',',')?></td> 
							 <td class="text-left"><?=$l->days_pterms?></td>
							 <td class="text-left"><?=$l->jatuh_tempo?></td>
                            <td>
                                <a id="<?=str_replace('-','_',$l->id_journal)?>" class="btn btn-primary btn-s btn-add" href="#" 
								data-type="KB"
								data-idsupplier="<?php echo $l->id_supplier?>"
								data-kodesupplier="<?php echo $l->supplier_code?>"
								data-umurap="<?php echo $l->days_pterms?>"
								data-jatuhtempo="<?php echo $l->jatuh_tempo?>"							
								
								data-id_journal="<?=$l->id_journal?>" data-nilai="<?=$l->debit?>" data-date_journal="<?=$l->date_journal?>"
								data-suppliers="<?=$l->Supplier?>"  data-source="KB" 

								title="Add to rekap" >Add</a>
                            </td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                <?php endif;?>
            </table>
        </div>
    </div>
</form>
							</div>			
						</div>
						
						
                        <div class="tab-pane fade" id="tab2default">
						
						


<form id="form_detail" method='post' name='form' enctype='multipart/form-data' action=""  >
    <div class='box'>
        <div class="box-header">
            <h3 class="box-title">Tambah/Edit Entry</h3>
        </div>
        <div class='box-body'>
            <table id="tbl3" class="display responsive" style="width:100%;font-size:12px;">
                <thead>
                <tr>
                    <th>No</th>
					<th>PO Terms</th>
                    <th>No. PO</th>
					<th>Tanggal PO</th>
					 <th>Terms Of Payment</th>
					 <th>Nama Supplier</th>
<!--                    <th>Mata Uang</th>-->
                    <th>Nilai</th>
					 <th>Umur AP</th>
					 <th>Tanggal Jatuh Tempo</th>
                    <th width='14%'>Action</th>
                </tr>
                </thead>
                <?php if(@count($listpo)):?>
                    <tbody>
                    <?php $no=1;?>
                    <?php foreach($listpo as $lpo):?>
                        <tr>
                            <td><?=$no++?></td>
							<td><?=$lpo->type_data?></td>
                            <td><?=$lpo->pono?></td>
 <td class="text-left"><?=$lpo->podate?></td>
   <td class="text-left"><?=$lpo->days_pterms?></td>
  <td class="text-left"><?=$lpo->supplier?></td>
                            <td class="text-left"><?=number_format($lpo->po_amount)?></td>
							 <td class="text-left"><?=$lpo->days_pterms?></td>
							 <td class="text-left"><?=$lpo->jatuh_tempo?></td>
                            <td>
                                <a class="btn btn-primary btn-s btn-add" id="<?=str_replace('/','_',$lpo->pono)?>"  href="#"
								data-type="PO"
								data-idsupplier="<?php echo $lpo->id_supplier?>"
								data-kodesupplier="<?php echo $lpo->supplier_code?>"
								data-umurap="<?php echo $lpo->days_pterms?>"
								data-jatuhtempo="<?php echo $lpo->jatuh_tempo?>"
								data-id_journal="<?php echo $lpo->pono?>" data-nilai="<?=$lpo->po_amount?>" data-date_journal="<?php echo $lpo->podate?>"
								data-suppliers="<?php echo $lpo->supplier?>" data-source="PO"

								title="Add to rekap" 
								<?=$view =='1' ? 'style="display:none"' : '';?>
								
								
								>Add</a>
                            </td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                <?php endif;?>
            </table>
        </div>
    </div>
</form>	
						</div>						


                        <div class="tab-pane fade" id="tab3default">
						
						


<form id="form_detail" method='post' name='form' enctype='multipart/form-data' action=""  >
    <div class='box'>
        <div class="box-header">
            <h3 class="box-title">Tambah/Edit Entry</h3>
        </div>
        <div class='box-body'>
            <table id="tbl4" class="display responsive" style="width:100%;font-size:12px;">
                <thead>
                <tr>
                    <th>No</th>
					<th>PO Terms</th>
                    <th>No. PO</th>
					<th>Tanggal PO</th>
					 <th>Terms Of Payment</th>
					 <th>Nama Supplier</th>
<!--                    <th>Mata Uang</th>-->
                    <th>Nilai</th>
					 <th>Umur AP</th>
					 <th>Tanggal Jatuh Tempo</th>
                    <th width='14%'>Action</th>
                </tr>
                </thead>
                <?php if(@count($listpo_idp)):?>
                    <tbody>
                    <?php $no=1;?>
                    <?php foreach($listpo_idp as $lpo_idp):?>
                        <tr>
                            <td><?=$no++?></td>
							<td><?=$lpo_idp->type_data?></td>
                            <td><?=$lpo_idp->pono?></td>
 <td class="text-left"><?=$lpo_idp->podate?></td>
   <td class="text-left"><?=$lpo_idp->days_pterms?></td>
  <td class="text-left"><?=$lpo_idp->supplier?></td> 
                            <td class="text-left"><?=number_format($lpo_idp->po_amount)?></td>
							 <td class="text-left"><?=$lpo_idp->days_pterms?></td>
							 <td class="text-left"><?=$lpo_idp->jatuh_tempo?></td>
                            <td>
                                <a class="btn btn-primary btn-s btn-add" id="<?=str_replace('/','_',$lpo_idp->pono)?>"  href="#"
								data-type="PO"
								data-idsupplier="<?php echo $lpo_idp->id_supplier?>"
								data-kodesupplier="<?php echo $lpo_idp->supplier_code?>"
								data-umurap="<?php echo $lpo_idp->days_pterms?>"
								data-jatuhtempo="<?php echo $lpo_idp->jatuh_tempo?>"
								data-id_journal="<?php echo $lpo_idp->pono?>" data-nilai="<?=$lpo_idp->po_amount?>" data-date_journal="<?php echo $lpo_idp->podate?>"
								data-suppliers="<?php echo $lpo_idp->supplier?>" data-source="PO"
 
								title="Add to rekap" 
								<?=$view =='1' ? 'style="display:none"' : '';?>
								
								
								>Add</a>
                            </td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                <?php endif;?>
            </table>
        </div>
    </div>
</form>	
						</div>		
						
						
					</div>
				</div>
				

<script>
    var tbl2;
    function validasi() {
        valid = true;

        return valid;
    }
	id_s = [];
	function checkSupplier(id){
		console.log(id);
		var key = 0;
		var count = id_s.length;

			for(var i=0;i<count;i++){
				if(id == id_s[i]){
					key = 0;
				}else{
					if(id_s[i] == 'XX'){
						key = 0;
					}else{
						key = 1;
					} 
				}
			}
			
		
		console.log(id_s);
		if(key > 0){
			alert("Supplier harus sama!");
			return false;
		}
		else{
			id_s.push(id);
			return true;
		}
	}
		
	
	
    $(document).ready(function(){
		content = "";
		filter_table();	
		id_s = tmp_s;
		console.log(id_s);
        $('.btn-add').click(function(){
			if($(this).is('[disabled=disabled]')){
				return false;
				alert("123");
			}
			var idsupplier=$(this).data('idsupplier');
			if(!checkSupplier(idsupplier)){
				return false;
			};			
			$(this).attr('disabled',true);
			var type        =$(this).data('type');
			var kodesupplier=$(this).data('kodesupplier');
			var umurap      =$(this).data('umurap');
			var jatuhtempo  =$(this).data('jatuhtempo');
			
			if(type=="KB"){
				var nokontrabon   = $(this).data('id_journal');
				var tgl_kontrabon = $(this).data('date_journal');
				var no_po 		  = '';
				var tgl_po        = '';
				
			}else if(type == "PO"){
				var nokontrabon   = ''
				var tgl_kontrabon = ''
				var no_po  		  = $(this).data('id_journal');;
				var tgl_po 		  = $(this).data('date_journal');;				
				
				
			}
            var id_journal = $(this).data('id_journal');
            var nilai = number_format($(this).data('nilai'));
            var date_journal = $(this).data('date_journal');
            var suppliers = $(this).data('suppliers');
			var source= $(this).data('source');
			//rep_1 = id_journal.replace('/',"_")+'__R';
			//rep_2 = rep_1.replace('/',"_");
			var rep_2 = id_journal.replace(/[^\w\s]/gi, '_')+'__R';
            var btn = '<input type="hidden" name="id_journal[]" value="'+id_journal+'" />'+
                '<input type="hidden" name="nilai[]" value="'+nilai+'" />'+
				'<input type="hidden" name="date_journal[]" value="'+date_journal+'" />'+
				'<input type="hidden" name="idsupplier[]" value="'+idsupplier+'" />'+
				'<input type="hidden" name="suppliers[]" value="'+suppliers+'" />'+
				'<input type="hidden" name="source[]" value="'+source+'" />'+
                '<a href="#" data-myids="'+idsupplier+'" id ="'+rep_2+'" class="btn btn-danger btn-del">Remove</a>';
            tbl2.row.add( [
				nokontrabon  
				,tgl_kontrabon
				,no_po 		 
				,tgl_po   
				,umurap
				,suppliers
				 ,nilai
				 ,umurap
				,jatuhtempo
                ,btn
            ] ).draw( false );
            return false;
        });
        tbl2 = $('#tbl2').DataTable
        ({  scrollY: "300px",
            scrollCollapse: true,
            paging: true,
            pageLength: 20,
            /*fixedColumns:
                { leftColumns: 1,
                    rightColumns: 1
                }*/
        });
		
		
        tbl3= $('#tbl3').DataTable
        ({  scrollY: "300px",
            scrollCollapse: true,
            paging: true,
            pageLength: 20,
            /*fixedColumns:
                { leftColumns: 1,
                    rightColumns: 1
                }*/
        });	

		
        tbl4= $('#tbl4').DataTable
        ({  scrollY: "300px",
            scrollCollapse: true,
            paging: true,
            pageLength: 20,
            /*fixedColumns:
                { leftColumns: 1,
                    rightColumns: 1
                }*/
        });	



        tbl1= $('#tbl1').DataTable
        ({  scrollY: "300px",
            scrollCollapse: true,
            paging: true,
            pageLength: 20,
            /*fixedColumns:
                { leftColumns: 1,
                    rightColumns: 1
                }*/
        });			
		
    });

	function klik(Item){

	 setTimeout(function(){ 	
	console.log("Begin");
		$(".sorting").trigger("click");
	}, 1000);			
			
			
	content = Item;
	filter_table();	
		
	}
	
    $(document).on( 'click', '.btn-del', function () {
		 var myids = $(this).data('myids');
		var idTmp = this.id;
			idTmp = idTmp.split("__R");
			console.log(idTmp);
			idTmp = idTmp[0];
			console.log(idTmp);
		$("#"+idTmp).removeAttr('disabled');
	
	   tbl2
         .row( $(this).parents('tr') )
         .remove()
         .draw();
		var key= id_s.indexOf(myids);
		id_s.splice(key, 1, 'XX');
		console.log(id_s);
        return false;
		
		
		
		
    } );
	
	
					
	
	    function filter_table()
    {	

			var filter_supplier = $('#filter_supplier').val();
		if(content == "KB"){
			tbl1.column(4).search(filter_supplier);
			 tbl1.draw();
		}
		else if(content == "PO"){
			tbl3.column(4).search(filter_supplier);
			 tbl3.draw();
			
		}else{
			content = "KB";
			
			
		}



    }	
	
</script>				
				
				
			</div>