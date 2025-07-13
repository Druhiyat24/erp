<?php
session_start();
ini_set('max_execution_time', '6000'); //300 seconds = 5 minutes
require_once '../../include/conn.php';
require_once "../forms/journal_interface.php";


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Model extends Basemodel{
    private $conn;
    private $result;

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

    public function supplier($param = array()){
        $sql = "
            SELECT X.invno,X.id_buyer,X.Supplier,COUNT(X.invno) jumlah FROM(
            
            SELECT A.invno,A.id_buyer,B.Supplier FROM invoice_header A
            LEFT JOIN(
                SELECT Supplier, Id_Supplier FROM mastersupplier
                ) B ON id_buyer = Id_supplier
                ) X GROUP BY X.invno {WHERE}
        ";
        $sql_where = '';
        if(isset($param['invno'])){
            $sql_where .= " AND X.invno like '{$param['invno']}' ";
        }
        if(isset($param['supplier'])){
            $sql_where .= " AND X.Supplier like '{$param['supplier']}' ";
        }
        $sql = str_replace('{WHERE}', $sql_where, $sql);
        return $this->query($sql)->result();
    }

    public function lookup_bank_masuk($param = array()){
		//print_r($param);
        $sql = "
            SELECT 
                fjh.id_journal, fjh.date_journal 
                ,SUM(fjd.debit) amount
				,MAX(curr)curr
				,SUM(fjd.debit) nilai_invoice
            FROM fin_journal_h fjh
                INNER JOIN fin_journal_d fjd ON fjh.id_journal = fjd.id_journal
                INNER JOIN fin_prosescashbank fb ON fjh.id_journal = fb.v_idjournal
            WHERE 
              (fjh.type_journal = '13' AND fjh.reff_doc2 = '')
					AND fjh.id_journal NOT IN(SELECT reff_doc FROM fin_journal_h WHERE type_journal = '4')
			
              {WHERE}
            GROUP BY fjh.id_journal, fjh.date_journal 
        ";
        $sql_where = '';
        if(isset($param['id_journal'])){
            $sql_where .= " AND fjh.id_journal like '%{$param['id_journal']}%'  ";
        }

        $sql = str_replace('{WHERE}', $sql_where, $sql);
	//echo $sql;
        return $this->query($sql)->result();
    }

    public function lookup_rekap_ar($param = array())
    {
		$que_nya = GetQuery_Allokasi_AR("LOOKUP");
        $sql  = "SELECT X.* FROM($que_nya)X WHERE 1 " ;
		$sql .= " {WHERE}";
        $sql_where = '';
        if(isset($param['id_rekap'])){
            $sql_where .= " AND X.id_rekap like '%{$param['id_rekap']}%' ";
        }
        if(isset($param['code_supplier'])){
            $sql_where .= " AND X.supplier_code like '%{$param['code_supplier']}%' ";
        }
        $sql = str_replace('{WHERE}', $sql_where, $sql);

		//echo $sql; 
        $list = $this->query($sql)->result();

        if(!count($list)){
            return array();
        }
        $hash = array();
        foreach($list as $l){
            $hash[$l->id_rekap]['id_rekap'] = $l->id_rekap;
            if(!isset($hash[$l->id_rekap]['invoices'])){
                $hash[$l->id_rekap]['invoices'] = $l->no_invoice;
               $hash[$l->id_rekap]['j_price'] = number_format($l->j_price, 2, '.', ',');
                $hash[$l->id_rekap]['jqty'] = number_format($l->jqty);
				$hash[$l->id_rekap]['Supplier'] = $l->Supplier;
				$hash[$l->id_rekap]['curr'] = $l->curr;
				$hash[$l->id_rekap]['supplier_code'] = $l->supplier_code;
            }else{
                $hash[$l->id_rekap]['invoices'] .= '<br>'.$l->no_invoice;
                $hash[$l->id_rekap]['j_price'] .= '<br>'.number_format($l->j_price, 2, '.', ',');
                $hash[$l->id_rekap]['jqty'] .= '<br>'.number_format($l->jqty);
				$$hash[$l->id_rekap]['curr'] = $l->curr;
				$hash[$l->id_rekap]['supplier_code'] = $l->supplier_code;
            }
        }
        return array_values($hash);
    }

    public function lookup_invoice($param = array()){
        /*$sql = "
            SELECT * FROM
            invoice_header ih
            INNER JOIN invoice_detail id ON ih.id = id.id_inv
            LEFT JOIN so_det sd ON sd.id = id.id_so_det
            LEFT JOIN so ON so.id = sd.id_so
            LEFT JOIN act_costing ac ON so.id_cost = ac.id
            LEFT JOIN masterproduct mp ON ac.id_product = mp.id
            LEFT JOIN mastersupplier ms ON ac.id_buyer = ms.Id_Supplier
        ";*/
        /*$sql = "
            SELECT 
                ih.invno
                ,ih.invdate
                ,fh.id_journal
                ,so.curr
                ,SUM(id.qty) invoiced_qty
                ,SUM(id.qty * id.price) invoice_amount
                ,ms.supplier
            FROM 
            invoice_header ih
            INNER JOIN invoice_detail id ON ih.id = id.id_inv
            LEFT JOIN so_det sd ON sd.id = id.id_so_det
            LEFT JOIN so ON so.id = sd.id_so
            LEFT JOIN act_costing ac ON so.id_cost = ac.id
            LEFT JOIN masterproduct mp ON ac.id_product = mp.id
            LEFT JOIN mastersupplier ms ON ac.id_buyer = ms.Id_Supplier
            INNER JOIN fin_journal_h fh ON fh.reff_doc = ih.invno
            WHERE 1=1
              AND fh.type_journal = '1'
              AND fh.fg_post = '2'
              {WHERE}
            GROUP BY
                ih.invno
                ,ih.invdate
                ,fh.id_journal
                ,ms.supplier
                ,so.curr
        ";
        $sql_where = '';
        if(isset($param['invno'])){
            $sql_where .= " AND ih.invno like '%{$param['invno']}%' ";
        }
        if(isset($param['supplier'])){
            $sql_where .= " AND ms.supplier like '%{$param['supplier']}%' ";
        }*/
        /*if(isset($param['posted'])){
            $sql_where .= " AND ih.invno NOT IN (SELECT DISTINCT reff_doc FROM fin_journal_h fh WHERE fh.type_journal = '1') "; // AND fg_post = '2'
        }*/

        $sql = "
SELECT IAC.v_noinvoicecommercial
		,IAC.n_id
		,IAC.n_amount
		,IAC.bpbno
		,IAC.n_idinvoiceheader
		,IAC.v_from
		,IAC.v_to
		,date(IAC.d_insert)
		,IAC.v_pono
		,IH.invno 
		,IH.shipped_by
		,IH.ship_to
		,IH.etd
		,IH.eta
		,IH.measurement
        ,IH.nw j_bersih
        ,IH.gw j_kotor
		,IH.shipper
		,BPB.jqty
		,BPB.j_price
		,BPB.id_supplier
		,BPB.nomor_mobil
		,ACT.styleno
		,ACT.id_buyer
		,MSM.shipdesc
		,MSS.Supplier
		,MSS.terms_of_pay
		,MSS.alamat
		,MSST.itemname
		,MSST.color
		,MSST.goods_code
		,ID.j_carton
		,SO.buyerno
		,SO.fob
		,JH.reff_doc
		,JD.curr
		,CONCAT(
		  DATE_FORMAT(JH.date_journal,'%d')
		,' '
		,	substring(DATE_FORMAT(JH.date_journal,'%M'),1,3)
		,' '
		,	DATE_FORMAT(JH.date_journal,'%Y')
			) date_journal
		,CONCAT(
		  DATE_FORMAT(IH.invdate,'%d')
		,' '
		,	substring(DATE_FORMAT(IH.invdate,'%M'),1,3)
		,' '
		,	DATE_FORMAT(IH.invdate,'%Y')
			) date_invoice
		,CONCAT(
			DATE_FORMAT(JH.date_journal,'%m')
		,	'/'
		,	DATE_FORMAT(JH.date_journal,'%Y')	
		
		) periode			
			
		,JH.id_journal
		,JHD.v_fakturpajak
		,DATE_ADD(JH.date_journal, INTERVAL MPT.days_pterms DAY) as  jatuh_tempo
		FROM invoice_commercial IAC
			LEFT JOIN(
				SELECT SUM(carton) j_carton,id_inv,max(id_so_det)id_so_det FROM invoice_detail GROUP BY id_inv 
			)ID ON IAC.n_idinvoiceheader = ID.id_inv		
			LEFT JOIN(
				SELECT id,id_so FROM so_det
			)SOD ON ID.id_so_det = SOD.id
			LEFT JOIN(
				SELECT id,id_cost,ifnull(qty*fob,0) amount,fob,buyerno FROM so
			)SO ON SOD.id_so = SO.id
			LEFT JOIN(
				SELECT id,kpno styleno,id_buyer FROM act_costing
			)ACT ON SO.id_cost = ACT.id 
			LEFT JOIN(
				SELECT invno,id,shipped_by,ship_to,measurement,etd,eta,nw,gw,shipper,invdate,id_pterms FROM invoice_header
			)IH ON IAC.n_idinvoiceheader =  IH.id
			LEFT JOIN(
				SELECT SUM(price) j_price,SUM(qty) jqty,SUM(berat_bersih) j_bersih,SUM(berat_kotor) j_kotor,invno,max(id_supplier) id_supplier,nomor_mobil,styleno,bpbno,bpbno_int,id_item FROM bpb GROUP BY bpbno_int,bpbno
			)BPB ON IAC.bpbno = BPB.bpbno
			LEFT JOIN(
				SELECT id,shipdesc FROM mastershipmode
			) MSM ON IH.shipped_by = MSM.id
			LEFT JOIN(
				SELECT Supplier,Id_supplier,alamat,terms_of_pay FROM mastersupplier
			)MSS ON MSS.Id_supplier = ACT.id_buyer
			LEFT JOIN(
				SELECT id_item,itemname,Color,goods_code FROM masterstyle 
			)MSST ON MSST.id_item = BPB.id_item
			LEFT JOIN (
				SELECT id_journal,reff_doc,date_journal FROM fin_journal_h
			)JH ON JH.reff_doc = IH.invno
			LEFT JOIN(
				SELECT v_idjournal,v_fakturpajak FROM fin_journalheaderdetail
			)JHD ON JHD.v_idjournal= JH.id_journal
                 LEFT JOIN (
                		SELECT id,days_pterms FROM masterpterms
                )MPT ON IH.id_pterms = MPT.id 
				LEFT JOIN (
					SELECT id_journal,MAX(curr)curr FROM fin_journal_d GROUP BY id_journal
				)JD ON JH.id_journal = JD.id_journal
			
			WHERE JH.reff_doc IS NOT NULL
			  {WHERE}
        ";
        $sql_where = '';  
        if(isset($param['invno'])){
            $sql_where .= " AND IAC.v_noinvoicecommercial like '%{$param['invno']}%' ";
        }
        if(isset($param['supplier'])){  
            $sql_where .= " AND MSS.Supplier '%{$param['supplier']}%' ";
        }

        $sql = str_replace('{WHERE}', $sql_where, $sql);
	//echo "$sql";
//        dd($sql);

        return $this->query($sql)->result();
    }
    public function lookup_invoice_for_rekap($param = array()){
        $sql = "
            SELECT IAC.v_noinvoicecommercial
		,IAC.n_id
		,IAC.n_amount
		,IAC.n_idinvoiceheader
		,IAC.v_from
		,IAC.v_to
		,IAC.d_insert
		,IAC.v_pono
		,IH.invno 
		,IH.shipped_by
		,IH.etd
		,IH.eta
		,IH.measurement
        ,BPB.j_bersih
        ,BPB.j_kotor
		,BPB.jqty
		,BPB.j_price
		,BPB.id_supplier
		,BPB.nomor_mobil
		,BPB.styleno
		,MSM.shipdesc
		,MSS.Supplier
		,MSS.terms_of_pay
		,MSS.alamat
		,MSST.itemname
		,MSST.color
		,MSST.goods_code
		,ID.j_carton
		FROM invoice_commercial IAC
			LEFT JOIN(
				SELECT invno,id,shipped_by,measurement,etd,eta FROM invoice_header
			)IH ON IAC.n_idinvoiceheader =  IH.id
			LEFT JOIN(
				SELECT SUM(price) j_price,SUM(qty) jqty,SUM(berat_bersih) j_bersih,SUM(berat_kotor) j_kotor,invno,max(id_supplier) id_supplier,nomor_mobil,styleno FROM bpb GROUP BY invno
			)BPB ON IH.invno = BPB.invno
			LEFT JOIN(
				SELECT id,shipdesc FROM mastershipmode
			) MSM ON IH.shipped_by = MSM.id
			LEFT JOIN(
				SELECT Supplier,Id_supplier,alamat,terms_of_pay FROM mastersupplier
			)MSS ON MSS.Id_supplier = BPB.id_supplier
			LEFT JOIN(
				SELECT id_item,itemname,Color,goods_code FROM masterstyle 
			)MSST ON MSST.id_item = BPB.styleno
			LEFT JOIN(
				SELECT SUM(carton) j_carton,id_inv FROM invoice_detail GROUP BY id_inv 
			)ID ON IAC.n_idinvoiceheader = ID.id_inv
			WHERE 1=1
			  {WHERE}
        ";
        $sql_where = '';
        if(isset($param['invno'])){
            $sql_where .= " AND IAC.v_noinvoicecommercial like '%{$param['invno']}%' ";
        }
        if(isset($param['supplier'])){
            $sql_where .= " AND MSS.Supplier '%{$param['supplier']}%' ";
        }
        /*if(isset($param['posted'])){
            $sql_where .= " AND ih.invno NOT IN (SELECT DISTINCT reff_doc FROM fin_journal_h fh WHERE fh.type_journal = '1') "; // AND fg_post = '2'
        }*/
        $sql = str_replace('{WHERE}', $sql_where, $sql);
        return $this->query($sql)->result();
    }

    public function lookup_bpb($param = array()){
        $sql = "
            SELECT 
                bpb.bpbno
                ,bpb.bpbno_int
                ,bpb.pono
                ,COUNT(bpb.bpbno) item_count
                ,SUM(bpb.qty * bpb.price) bpb_amount
                ,SUM(o.po_amount) po_amount
                ,SUM(o.paid_amount) paid_amount
                ,SUM(o.outstanding_amount) outstanding_amount
                ,bpb.curr
                ,bpb.invno
                ,bpb.bpbdate
                ,bpb.no_fp
                ,ms.supplier
                ,ms.terms_of_pay
                ,DATE_ADD(bpb.bpbdate, INTERVAL ms.terms_of_pay DAY) due_date
            FROM bpb
                LEFT JOIN mastersupplier ms ON bpb.id_supplier = ms.id_supplier 
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
                ) o ON bpb.pono = o.pono
            WHERE 1=1
                AND o.outstanding_amount > 0
                {WHERE}
            GROUP BY
                bpb.bpbno
                ,bpb.bpbno_int
                ,bpb.pono
                ,bpb.curr
                ,bpb.invno
                ,bpb.bpbdate
                ,bpb.no_fp
                ,ms.supplier
                ,ms.terms_of_pay
                ,DATE_ADD(bpb.bpbdate, INTERVAL ms.terms_of_pay DAY)
        ";
        $sql_where = '';
        if(isset($param['bpbno'])){
            $sql_where .= " AND bpb.bpbno like '%{$param['bpbno']}%' ";
        }
        if(isset($param['bpbno_int'])){
            $sql_where .= " AND bpb.bpbno_int like '%{$param['bpbno_int']}%' ";
        }
        if(isset($param['pono'])){
            $sql_where .= " AND bpb.pono like '%{$param['pono']}%' ";
        }
        if(isset($param['posted'])){
            $sql_where .= " AND bpb.bpbno_int NOT IN (SELECT DISTINCT reff_doc FROM fin_journal_h fh WHERE fh.type_journal = '2') "; // AND fg_post = '2'
        }
        if(isset($param['payable'])){
            $sql_where .= " AND bpb.bpbno_int IN (SELECT DISTINCT reff_doc FROM fin_journal_h fh WHERE fh.type_journal = '2' AND fg_post = '2' AND reff_doc2 != '') ";
        }
        if(isset($param['kontrabon'])){
            $sql_where .= " AND bpb.bpbno_int IN (SELECT DISTINCT reff_doc FROM fin_journal_h fh WHERE fh.type_journal = '2' AND fg_post = '2' AND reff_doc2 = '') ";
        }
        $sql = str_replace('{WHERE}', $sql_where, $sql);

        return $this->query($sql)->result();
    }

 public function lookup_posted_bpb($param = array()){
	 $where_po = $this->get_where_po();
     
if(!ISSET($param['id_journal_exist']) || $param['id_journal_exist'] ==  '0' ){
$sql = "SELECT FH.id_journal
		,FH.date_journal
		,FH.type_journal
		,FH.reff_doc
		,FD.nilai bpb_amount
		,BPB.id_supplier
		,BPPB.bppbno
		,ifnull(BPB.bpbno_int,VBPPB.bppb_journal)bpbno_int
		,ifnull(BPB.bpbno,BPPB.bppbno)bpbno
		,ifnull(BPB.curr,BPPB.curr)curr
		,ifnull(BPB.bpbdate,BPPB.bppbdate)bpbdate
		,ifnull(BPB.invno,BPPB.invno)invno
		,ifnull(DATE_ADD(BPB.bpbdate, INTERVAL MS.terms_of_pay DAY),DATE_ADD(BPPB.bppbdate, INTERVAL MS_RET.terms_of_pay DAY)) due_date
		,ifnull(MS.Supplier ,MS_RET.Supplier)Supplier
		,ifnull(MS.Id_Supplier ,BPPB.id_supplier)Id_Supplier
		,IFNULL(BPB.pono,IFNULL(POH.pono_other,POH_RET.pono))pono
		,BPB.pono ponobpb
		,POH.pono_other		
		,JDD.item item_count
		FROM fin_journal_h FH
	LEFT JOIN(
		SELECT SUM(debit)nilai,SUM(credit),id_journal FROM fin_journal_d
		GROUP BY id_journal
	)FD ON FH.id_journal = FD.id_journal	
	LEFT JOIN (SELECT 
				MAX(id_supplier)id_supplier
				,MAX(pono)pono
				,MAX(id_jo)id_jo 
				,MAX(id_item)id_item
				,bpbno_int
				,bpbno
				,bpbdate
				,invno
				,curr
                ,CANCEL
				 FROM bpb 
                 WHERE CANCEL ='N'
                 GROUP BY bpbno_int
				)BPB ON BPB.bpbno_int = FH.reff_doc
	LEFT JOIN(
		SELECT Id_Supplier,Supplier,terms_of_pay FROM mastersupplier
	)MS ON MS.Id_Supplier = BPB.id_supplier			
	LEFT JOIN(
		SELECT id,id_jo,id_gen,id_po FROM po_item
	)POI ON POI.id_jo = BPB.id_jo AND POI.id_gen = BPB.id_item
	LEFT JOIN (
		SELECT id,pono pono_other FROM po_header 
	)POH ON POH.id = POI.id_po
	LEFT JOIN view_bppbno_ret_pengeluaran VBPPB ON FH.reff_doc = VBPPB.bppbno
	LEFT JOIN (select * from bppb where cancel != 'Y') BPPB ON BPPB.bppbno = VBPPB.bppbno
	LEFT JOIN masteritem MI ON BPPB.id_item = MI.id_item
		LEFT JOIN(
		SELECT Id_Supplier,Supplier,terms_of_pay FROM mastersupplier
	)MS_RET ON MS_RET.Id_Supplier = BPPB.id_supplier	
	LEFT JOIN (SELECT id_po,id,cancel,id_gen,qty,price,curr,id_jo FROM po_item WHERE cancel !='Y')POI_RET ON POI_RET.id_gen = MI.id_gen AND BPPB.id_jo = POI_RET.id_jo
	LEFT JOIN po_header POH_RET ON POH_RET.id = POI_RET.id_po AND POH_RET.id_supplier = BPPB.id_supplier
	LEFT JOIN(
		SELECT COUNT(id_journal)item,id_journal FROM fin_journal_d WHERE debit > 0 AND id_coa NOT IN(
'15200'
,'15201'
,'15202'
,'15203'
,'15204'
,'15205'
,'15206'
,'15207'
,'25200'
,'25201'
,'25202'
,'25203'
,'25204'
,'25205'
,'25206'
,'32000'
,'32001'
,'57802'
,'74000'
,'74001'
,'91000'
,'91001'
,'91002')
		GROUP BY id_journal		
	)JDD ON JDD.id_journal = FH.id_journal
	WHERE 1 AND FH.type_journal IN('2','17','19') AND FD.nilai  > 0 AND trim(FH.reff_doc) NOT IN(SELECT JOINNYA.reff_doc FROM(
SELECT fh.id_journal
		,fh.type_journal 
		,fd.reff_doc
		FROM fin_journal_h fh
LEFT JOIN (SELECT id_journal,reff_doc FROM fin_journal_d )fd
ON fh.id_journal = fd.id_journal
WHERE fh.type_journal = '14' )  JOINNYA WHERE reff_doc is not NULL
GROUP BY reff_doc) 
AND FH.id_journal NOT IN(SELECT reff_doc2 FROM fin_journal_h WHERE 1= 1 AND type_journal= '18')
{WHERE}
 GROUP BY FH.reff_doc
";


        $sql_where = '';
		$sql_where .="AND IFNULL(BPB.pono,IFNULL(POH.pono_other,POH_RET.pono)) NOT IN($where_po)";
        if(isset($param['bpbno'])){
            $sql_where .= " AND (BPB.bpbno like '%{$param['bpbno']}%' OR BPPB.bppbno like '%{$param['bpbno']}%' ) ";
        }
        if(isset($param['bpbno_int'])){
            $sql_where .= " AND (BPB.bpbno_int like '%{$param['bpbno_int']}%' OR BPPB.bppbno_int like '%{$param['bpbno_int']}%') ";
        }
        if(isset($param['pono'])){
            $sql_where .= " AND BPB.pono like '%{$param['pono']}%' OR POH.pono_other like '%{$param['pono']}%'";
        }
       // if(isset($param['not_kontrabon'])){
       //     $sql_where .= " AND kb.id_journal IS NULL ";
       // }
       // if(isset($param['payable'])){
       //     $sql_where .= " AND kb.id_journal != '' ";
       // }
        if(isset($param['supplier'])){
            $sql_where .= " AND ( MS.Supplier like '%{$param['supplier']}%' OR MS_RET.Supplier like '%{$param['supplier']}%') ";
        }	
        if(isset($param['bpbdate'])){
            $sql_where .= " AND BPB.bpbdate like '%{$param['bpbdate']}%' OR BPPB.bppbdate like '%{$param['bpbdate']}%' ";
        }
        $sql = str_replace('{WHERE}', $sql_where, $sql);
	 	//echo "<pre>".$sql."</pre>"; 
        return $this->query($sql)->result();
}else{
	
	$sql = "SELECT FH.id_journal
		,FH.date_journal
		,FH.type_journal
		,FH.reff_doc
		,FD.nilai bpb_amount
		,BPB.id_supplier
		,BPPB.bppbno
		,ifnull(BPB.bpbno_int,VBPPB.bppb_journal)bpbno_int
		,ifnull(BPB.bpbno,BPPB.bppbno)bpbno
		,ifnull(BPB.curr,BPPB.curr)curr
		,ifnull(BPB.bpbdate,BPPB.bppbdate)bpbdate
		,ifnull(BPB.invno,BPPB.invno)invno
		,ifnull(DATE_ADD(BPB.bpbdate, INTERVAL MS.terms_of_pay DAY),DATE_ADD(BPPB.bppbdate, INTERVAL MS_RET.terms_of_pay DAY)) due_date
		,ifnull(MS.Supplier ,MS_RET.Supplier)Supplier
		,ifnull(MS.Id_Supplier ,BPPB.id_supplier)Id_Supplier
		,IFNULL(BPB.pono,IFNULL(POH.pono_other,POH_RET.pono))pono
		,BPB.pono ponobpb
		,POH.pono_other		
		,JDD.item item_count
		FROM fin_journal_h FH
	LEFT JOIN(
		SELECT SUM(debit)nilai,SUM(credit),id_journal FROM fin_journal_d
		GROUP BY id_journal
	)FD ON FH.id_journal = FD.id_journal	
	LEFT JOIN (SELECT 
				MAX(id_supplier)id_supplier
				,MAX(pono)pono
				,MAX(id_jo)id_jo 
				,MAX(id_item)id_item
				,bpbno_int
				,bpbno
				,bpbdate
				,invno
				,curr
				 FROM bpb GROUP BY bpbno_int
				)BPB ON BPB.bpbno_int = FH.reff_doc
	LEFT JOIN(
		SELECT Id_Supplier,Supplier,terms_of_pay FROM mastersupplier
	)MS ON MS.Id_Supplier = BPB.id_supplier			
	LEFT JOIN(
		SELECT id,id_jo,id_gen,id_po FROM po_item
	)POI ON POI.id_jo = BPB.id_jo AND POI.id_gen = BPB.id_item
	LEFT JOIN (
		SELECT id,pono pono_other FROM po_header 
	)POH ON POH.id = POI.id_po
	LEFT JOIN view_bppbno_ret_pengeluaran VBPPB ON FH.reff_doc = VBPPB.bppbno
	LEFT JOIN bppb BPPB ON BPPB.bppbno = VBPPB.bppbno
	LEFT JOIN masteritem MI ON BPPB.id_item = MI.id_item
		LEFT JOIN(
		SELECT Id_Supplier,Supplier,terms_of_pay FROM mastersupplier
	)MS_RET ON MS_RET.Id_Supplier = BPPB.id_supplier	
	LEFT JOIN (SELECT id_po,id,cancel,id_gen,qty,price,curr,id_jo FROM po_item WHERE cancel !='Y')POI_RET ON POI_RET.id_gen = MI.id_gen AND BPPB.id_jo = POI_RET.id_jo
	LEFT JOIN po_header POH_RET ON POH_RET.id = POI_RET.id_po AND POH_RET.id_supplier = BPPB.id_supplier
	LEFT JOIN(
		SELECT COUNT(id_journal)item,id_journal FROM fin_journal_d WHERE debit > 0 AND id_coa NOT IN(
'15200'
,'15201'
,'15202'
,'15203'
,'15204'
,'15205'
,'15206'
,'15207'
,'25200'
,'25201'
,'25202'
,'25203'
,'25204'
,'25205'
,'25206'
,'32000'
,'32001'
,'57802'
,'74000'
,'74001'
,'91000'
,'91001'
,'91002')
		GROUP BY id_journal		
	)JDD ON JDD.id_journal = FH.id_journal
	WHERE 1 AND FH.type_journal IN('2','17','19') AND FD.nilai  > 0 AND FH.reff_doc NOT IN(SELECT trim(JOINNYA.reff_doc)reff_doc FROM(
SELECT fh.id_journal
		,fh.type_journal 
		,fd.reff_doc
		FROM fin_journal_h fh
LEFT JOIN (SELECT id_journal,reff_doc FROM fin_journal_d WHERE 1 GROUP BY reff_doc)fd
ON fh.id_journal = fd.id_journal
WHERE fh.type_journal = '14' AND fh.id_journal != '".$param['id_journal_exist']."' )  JOINNYA WHERE 1=1 AND  JOINNYA.reff_doc is not NULL
) 
AND FH.id_journal NOT IN(SELECT reff_doc2 FROM fin_journal_h WHERE 1= 1 AND type_journal= '18')

{WHERE}
 GROUP BY FH.reff_doc
";


        $sql_where = '';
		$sql_where .="AND IFNULL(BPB.pono,IFNULL(POH.pono_other,POH_RET.pono)) NOT IN($where_po)";
        if(isset($param['bpbno'])){
            $sql_where .= " AND (BPB.bpbno like '%{$param['bpbno']}%' OR BPPB.bppbno like '%{$param['bpbno']}%' ) ";
        }
        if(isset($param['bpbno_int'])){
            $sql_where .= " AND (BPB.bpbno_int like '%{$param['bpbno_int']}%' OR BPPB.bppbno_int like '%{$param['bpbno_int']}%') ";
        }
        if(isset($param['pono'])){
            $sql_where .= " AND BPB.pono like '%{$param['pono']}%' OR POH.pono_other like '%{$param['pono']}%'";
        }
       // if(isset($param['not_kontrabon'])){
       //     $sql_where .= " AND kb.id_journal IS NULL ";
       // }
       // if(isset($param['payable'])){
       //     $sql_where .= " AND kb.id_journal != '' ";
       // }
        if(isset($param['supplier'])){
            $sql_where .= " AND ( MS.Supplier like '%{$param['supplier']}%' OR MS_RET.Supplier like '%{$param['supplier']}%' )";
        }	
        if(isset($param['bpbdate'])){
            $sql_where .= " AND BPB.bpbdate like '%{$param['bpbdate']}%' OR BPPB.bppbdate like '%{$param['bpbdate']}%' ";
        }
        $sql = str_replace('{WHERE}', $sql_where, $sql);
        return $this->query($sql)->result();
}
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
			AND o.outstanding_amount > 0 AND po.pono NOT IN (SELECT v_nojournal FROM fin_status_journal_ap WHERE v_status IN('A','W','S') AND is_deleted = 'N')
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
	


    /**
     * Lookup payment by po or bpb
     * If lookup by bpb, look for pono to bpb table
     * @param array $param
     * @return array
     */
    public function lookup_payment($param = array()){
        $sql = "
        SELECT * FROM (    
            SELECT 
                jh.id_journal
                ,jh.reff_doc pono
                ,NULL bpbno_int
                ,jh.date_journal
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
            UNION ALL
            SELECT 
                jh.id_journal
                ,bpb.pono
                ,bpb.bpbno_int
                ,jh.date_journal
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
        ) p
        WHERE 1=1
          {WHERE}
        ";

        $sql_where = '';
        if(isset($param['pono'])){
            $sql_where .= " AND pono like '%{$param['pono']}%' ";
        }elseif(isset($param['bpbno_int'])){
            $row = $this->query("SELECT DISTINCT pono FROM bpb WHERE bpbno_int = '{$param['bpbno_int']}'")->row();
            if(!isset($row->pono)){
                return array();//bpb not found
            }
            $pono = $row->pono;
            $sql_where .= " AND pono like '%{$pono}%' ";
        }
        $sql = str_replace('{WHERE}', $sql_where, $sql);
//echo $sql;
        return $this->query($sql)->result();
    }

    public function lookup_payment_approval($param = array()){
			if(ISSET($param['id_payment']) || !EMPTY($param['id_payment']) ){
					$ql = "SELECT id_list_payment FROM fin_journal_d WHERE id_journal LIKE '%-PV-%' AND id_journal !='{$param['id_payment']}' AND id_list_payment IS NOT NULL AND id_list_payment >0
UNION ALL
SELECT A.id_list_payment FROM fin_journal_d A
INNER JOIN(SELECT id_journal,fg_post FROM fin_journal_h WHERE fg_post = '2')B
ON A.id_journal = B.id_journal
 WHERE  A.id_journal ='{$param['id_payment']}' AND id_list_payment IS NOT NULL AND id_list_payment >0";
		$populasi_id_payment = $this->query($ql)->result();				
		$count_n = count($populasi_id_payment);
		$trigger = $count_n - 1;
		$id_list_payment_return = '';
		for($i=0;$i<$count_n;$i++){
			if($i == $trigger){
				$id_list_payment_return .= "'".$populasi_id_payment[$i]->id_list_payment."'"; 
			}else{
				$id_list_payment_return .= "'".$populasi_id_payment[$i]->id_list_payment."',";
			}
		}								
				
			}
			
else{
				$ql = "SELECT A.id_list_payment FROM fin_journal_d A
				WHERE id_journal LIKE '%-PV-%' AND id_list_payment IS NOT NULL AND id_list_payment >0";
		$populasi_id_payment = $this->query($ql)->result();				
		$count_n = count($populasi_id_payment);
		$trigger = $count_n - 1;
		$id_list_payment_return = '';
		for($i=0;$i<$count_n;$i++){
			if($i == $trigger){
				$id_list_payment_return .= "'".$populasi_id_payment[$i]->id_list_payment."'"; 
			}else{
				$id_list_payment_return .= "'".$populasi_id_payment[$i]->id_list_payment."',";
			}
		}					
			}	
			$query_tambahan = "";
			if($id_list_payment_return != ""){
				$query_tambahan = "AND X.n_id NOT IN($id_list_payment_return)";	
			}		
		$q = GetQuery_AP_Cicilan('LOOKUP',$param);
        $sql ="SELECT X.* FROM($q)X WHERE X.nm_supplier IS NOT NULL $query_tambahan";
      // echo "$sql";
        $result = $this->query($sql)->result();
//print_r($result);
        $list = array();
        if(count($result)){
            foreach ($result as $r){
				array_push($list,$r);
            }
            $list = array_values($list);
        }
		//print_r($list);
		
        return $list;
    }
    public function lookup_po($param = array()){
        $sql = "
            SELECT po.pono, po.podate, ms.supplier, o.po_amount, o.paid_amount, o.outstanding_amount
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
                {WHERE}
            GROUP BY po.pono, po.podate, ms.Supplier
        ";

        $sql_where = '';
        if(isset($param['pono'])){
            $sql_where .= " AND po.pono like '%{$param['pono']}%' ";
        }
        $sql = str_replace('{WHERE}', $sql_where, $sql);

        return $this->query($sql)->result();
    }

    public function save_journal_item($d)
    {
		

        $sql = "
            INSERT INTO fin_journal_d
              (id_journal, row_id, id_coa, nm_coa, curr, debit, credit
              ,id_costcenter, nm_costcenter, id_cost_category, nm_cost_category
              ,id_cost_dept, nm_cost_dept, id_cost_sub_dept, nm_cost_sub_dept
              ,nm_ws
              ,description, dateadd, useradd,n_rate)
            VALUES 
              ('{$d['id_journal']}', '{$d['row_id']}', '{$d['id_coa']}', '{$d['nm_coa']}' ,'{$d['curr']}', '{$d['debit']}', '{$d['credit']}'
              ,'{$d['id_costcenter']}', '{$d['nm_costcenter']}', '{$d['id_cost_category']}', '{$d['nm_cost_category']}'
              ,'{$d['id_cost_dept']}','{$d['nm_cost_dept']}', '{$d['id_cost_sub_dept']}', '{$d['nm_cost_sub_dept']}'
              ,'{$d['nm_ws']}'
              ,'{$d['description']}','{$d['dateadd']}', '{$d['useradd']}','{$d['rate']}'
              )
            ;
        ";
		print_r($sql);

        $this->query($sql);

        return $this->conn->insert_id;
    }

    public function update_journal_item($id, $row_id, $data)
    {	
	//print_r($id);
        $sql = "
            UPDATE fin_journal_d SET 
              id_coa = '{$data['id_coa']}',
              nm_coa = '{$data['nm_coa']}',
              curr = '{$data['curr']}',
              debit = '{$data['debit']}',
              credit = '{$data['credit']}',
              id_costcenter = '{$data['id_costcenter']}',
              nm_costcenter = '{$data['nm_costcenter']}',
              id_cost_category = '{$data['id_cost_category']}',
              nm_cost_category = '{$data['nm_cost_category']}',
              id_cost_dept = '{$data['id_cost_dept']}',
              nm_cost_dept = '{$data['nm_cost_dept']}',
              id_cost_sub_dept = '{$data['id_cost_sub_dept']}',
              nm_cost_sub_dept = '{$data['nm_cost_sub_dept']}',
              nm_ws = '{$data['nm_ws']}',
              description = '{$data['description']}',
              dateedit = '{$data['dateedit']}',
              useredit = '{$data['useredit']}',
			  n_rate = '{$data['rate']}'
            WHERE id_journal = '$id'
              AND row_id = '$row_id';
        ";
		insert_log_nw($sql_p,$_SESSION['username']);
      $this->query($sql);
		$mNilai = ""; 
		if($row_id == '1' ){
			if($data['debit'] == '0'){
				$mNilai = $data['credit'] ; 
			}else if($data['credit'] == '0'){
				$mNilai = $data['debit'];
			}
		$sql_ = "UPDATE fin_prosescashbank SET n_nilai = '$mNilai' WHERE v_idjournal = '$id' ";
		insert_log_nw($sql_p,$_SESSION['username']);
		//print_r($sql_);
			$this->query($sql_);
		}
    }
    public function get_journal_item($id, $row_id)
    {
        return $this->query("
            SELECT * FROM
            fin_journal_d jd
            WHERE id_journal = '$id'
              AND row_id = '$row_id';
        ")->row();
    }
    public function delete_journal_item($id, $row_id)
    {
        $sql = "
            DELETE FROM
            fin_journal_d
            WHERE id_journal = '$id'
              AND row_id = '$row_id'
        ";
		insert_log_nw($sql_p,$_SESSION['username']);
        return $this->query($sql);
    }
    public function get_next_item_id($id)
    {
        $row = $this->query("
            SELECT IFNULL(MAX(row_id),0)+1 next_id FROM
            fin_journal_d jd
            WHERE id_journal = '$id';
        ")->row();

        return $row->next_id;
    }

    public function get_coa_list($key='')
    {
        $sql = "
            SELECT 
                mc.id_coa
                ,mc.nm_coa
                ,mc.fg_mapping
            FROM mastercoa mc
            WHERE 1=1
                AND mc.fg_active = '1'
                AND mc.fg_posting = '1'
                {WHERE}
            ORDER By mc.id_coa
        ";

        $where = '';
        if($key){
            $where .= "AND mc.id_coa LIKE '$key%' ";
        }
        $sql = str_replace('{WHERE}', $where, $sql);

        return $this
            ->query($sql)
            ->result();
    }

    public function get_cost_center_list($key='')
    {
        $sql = "
            SELECT 
                mc.id_costcenter
                ,mc.nm_costcenter
                ,mcc.id_cost_category
                ,mcc.nm_cost_category
                ,mcd.id_cost_dept
                ,mcd.nm_cost_dept
                ,mcsd.id_cost_sub_dept
                ,mcsd.nm_cost_sub_dept
            FROM
                mastercostcenter mc
                INNER JOIN mastercostcategory mcc ON mc.id_cost_category = mcc.id_cost_category
                INNER JOIN mastercostdept mcd ON mc.id_cost_dept = mcd.id_cost_dept
                INNER JOIN mastercostsubdept mcsd ON mc.id_cost_sub_dept = mcsd.id_cost_sub_dept
            WHERE 1=1
                {WHERE}
        ";

        $where = '';
        if($key){
            $where .= "AND mc.id_costcenter = '$key' ";
        }
        $sql = str_replace('{WHERE}', $where, $sql);

        return $this
            ->query($sql)
            ->result();
    }

    public function get_journal_items($id)
    {
        return $this->query("
            SELECT * FROM (
                SELECT *, CASE WHEN credit = 0 THEN 0 ELSE 1 END ordering 
                FROM
                fin_journal_d jd
                WHERE id_journal = '$id'
            ) x
            ORDER BY ordering;
        ")->result();
    }
}

// CONTROLLER BEGIN

$M = new Model($con_new);
$ch = new Coa_helper();

$ajax_mode = $_GET['mdajax'];

//print_r($_GET);
switch ($ajax_mode){
    case 'lookup_rekap_ar':
        $param = array();
        if(isset($_POST['id_rekap']) and trim($_POST['id_rekap']) != '' ){
            $param['id_rekap'] = $_POST['id_rekap'];
        }
		if(ISSET($_POST['ListAR'])){
			$param['ListAR'] = '1';
		}
		if((ISSET($_POST['code_supplier']) || !EMPTY($_POST['code_supplier']) )){
			$param['code_supplier']= $_POST['code_supplier'];
			
		}
        echo json_encode(array(
            'status' => true,
            'message' => "Lookup berhasil",
            'data' => $M->lookup_rekap_ar($param)
        ));
        exit();
        break;
    case 'lookup_rekap_ap':
        $param = array();
        if(isset($_POST['approved']) and trim($_POST['approved']) != '' ){
            $param['approved'] = $_POST['approved'];
        }
        if(isset($_POST['no_payment']) and trim($_POST['no_payment']) != '' ){
            $param['no_payment'] = $_POST['no_payment'];
        }
        if(isset($_POST['supplier']) and trim($_POST['supplier']) != '' ){
            $param['supplier'] = $_POST['supplier'];
        }		
        if(isset($_POST['id_payment']) and trim($_POST['id_payment']) != '' ){
				if(!empty($_POST['id_payment'])){
					$param['id_payment'] = $_POST['id_payment'];
				}
        }			
        echo json_encode(array(
            'status' => true,
            'message' => "Lookup berhasil",
            'data' => $M->lookup_payment_approval($param)
        ));
        exit();
        break;
    case 'lookup_bank_masuk':
        $param = array();
        if(isset($_POST['id_journal']) and trim($_POST['id_journal']) != '' ){
            $param['id_journal'] = $_POST['id_journal'];
        }
        echo json_encode(array(
            'status' => true,
            'message' => "Lookup berhasil",
            'data' => $M->lookup_bank_masuk($param)
        ));
        exit();
        break;
    case 'lookup_supplier':
        $param = array();
        if(isset($_POST['invno']) and trim($_POST['invno']) != '' ){
            $param['invno'] = $_POST['invno'];
        }
        if(isset($_POST['supplier']) and trim($_POST['supplier']) != ''){
            $param['supplier'] = $_POST['supplier'];
        }
        if(!count($param)){
            echo json_encode(array(
                'status' => false,
                'message' => "Masukkan keyword",
            ));
            exit();
        }

        echo json_encode(array(
            'status' => true,
            'message' => "Lookup berhasil",
            'data' => $M->supplier($param)
        ));
        exit();
        break;
    case 'lookup_invoice':
        $param = array();
        if(isset($_POST['invno']) and trim($_POST['invno']) != '' ){
            $param['invno'] = $_POST['invno'];
        }
        if(isset($_POST['supplier']) and trim($_POST['supplier']) != ''){
            $param['supplier'] = $_POST['supplier'];
        }
        if(!count($param)){
            $param['invno'] = '%';
        }
        if(isset($_POST['posted']) and trim($_POST['posted']) == '1'){
            $param['posted'] = $_POST['posted'];
        }

        echo json_encode(array(
            'status' => true,
            'message' => "Lookup berhasil",
            'data' => $M->lookup_invoice($param)
        ));
        exit();
        break;
    case 'lookup_invoice_for_rekap':
        $param = array();
        if(isset($_POST['invno']) and trim($_POST['invno']) != '' ){
            $param['invno'] = $_POST['invno'];
        }
        if(isset($_POST['supplier']) and trim($_POST['supplier']) != ''){
            $param['supplier'] = $_POST['supplier'];
        }
        if(!count($param)){
            $param['invno'] = '%';
        }
        if(isset($_POST['posted']) and trim($_POST['posted']) == '1'){
            $param['posted'] = $_POST['posted'];
        }

        echo json_encode(array(
            'status' => true,
            'message' => "Lookup berhasil",
            'data' => $M->lookup_invoice_for_rekap($param)
        ));
        exit();
        break;
    case 'get_invoice_for_rekap':
        $param = array();
        $param['invno'] = $_POST['invno'];

        $bpb_list = $M->lookup_invoice($param);
        $bpb = array();
        if(count($bpb_list)){
            $bpb = $bpb_list[0];
        }


        echo json_encode(array(
            'status' => true,
            'message' => "Lookup berhasil",
            'data' => $bpb
        ));
        break;
    case 'get_invoice':

        $param = array();
        $param['invno'] = $_POST['invno'];

        $bpb_list = $M->lookup_invoice($param);
        $bpb = array();
        if(count($bpb_list)){
            $bpb = $bpb_list[0];
        }


        echo json_encode(array(
            'status' => true,
            'message' => "Lookup berhasil",
            'data' => $bpb
        ));
        break;
    case 'lookup_bpb':
        $param = array();
        if(isset($_POST['bpbno']) and trim($_POST['bpbno']) != '' ){
            $param['bpbno'] = $_POST['bpbno'];
        }
        if(isset($_POST['bpbno_internal']) and trim($_POST['bpbno_internal']) != ''){
            $param['bpbno_int'] = $_POST['bpbno_internal'];
        }
        if(isset($_POST['pono']) and trim($_POST['pono']) != ''){
            $param['pono'] = $_POST['pono'];
        }
        if(isset($_POST['posted']) and trim($_POST['posted']) == '1'){
            $param['posted'] = $_POST['posted'];
        }
        if(isset($_POST['payable']) and trim($_POST['payable']) == '1'){
            $param['payable'] = $_POST['payable'];
        }
        if(isset($_POST['kontrabon']) and trim($_POST['kotrabon']) == '1'){
            $param['kontrabon'] = $_POST['kontrabon'];
        }
        if(!count($param)){
//            echo json_encode(array(
//                'status' => false,
//                'message' => "Masukkan keyword",
//            ));
//            exit();
            $param['bpbno'] = '%';
        }


        echo json_encode(array(
            'status' => true,
            'message' => "Lookup berhasil",
            'data' => $M->lookup_bpb($param)
        ));
        exit();
        break;
    case 'lookup_posted_bpb':
        $param = array();
        if(isset($_POST['bpbno']) and trim($_POST['bpbno']) != '' ){
            $param['bpbno'] = $_POST['bpbno'];
        }
        if(isset($_POST['bpbno_internal']) and trim($_POST['bpbno_internal']) != ''){
            $param['bpbno_int'] = $_POST['bpbno_internal'];
        }
        if(isset($_POST['supplier']) and trim($_POST['supplier']) != ''){
            $param['supplier'] = $_POST['supplier'];
        }
        if(isset($_POST['bpbdate']) and trim($_POST['bpbdate']) != ''){
            $param['bpbdate'] = $_POST['bpbdate'];
        }	
        if(isset($_POST['id_journal_exist']) and trim($_POST['id_journal_exist']) != ''){
            $param['id_journal_exist'] = $_POST['id_journal_exist'];
        }else{
			$param['id_journal_exist'] = 0;
		}
        $param['not_kontrabon'] = '1';
        if(!count($param)){
//            echo json_encode(array(
//                'status' => false,
//                'message' => "Masukkan keyword",
//            ));
//            exit();
            $param['bpbno'] = '%';
        }


        echo json_encode(array(
            'status' => true,
            'message' => "Lookup berhasil",
            'data' => $M->lookup_posted_bpb($param)
        ));
        exit();
        break;
    case 'get_bpb':
        $param = array();
        $param['bpbno_int'] = $_POST['bpbno_internal'];

        $bpb_list = $M->lookup_bpb($param);
        $bpb = array();
        if(count($bpb_list)){
            $bpb = $bpb_list[0];
        }


        echo json_encode(array(
            'status' => true,
            'message' => "Lookup berhasil",
            'data' => $bpb
        ));
        break;
    case 'get_posted_bpb':
        $param = array();
		if(ISSET($_POST['bpbno_internal'])){
			$param['bpbno_int'] = $_POST['bpbno_internal'];
			
		}else{
			$data = (object)$_POST['data'];
			$param['bpbno_int'] = $data->bpbno_internal;
			
		}

        $bpb_list = $M->lookup_posted_bpb($param);
        $bpb = array();
        if(count($bpb_list)){
            $bpb = $bpb_list[0];
        }


        echo json_encode(array(
            'status' => true,
            'message' => "Lookup berhasil",
            'data' => $bpb
        ));
        break;
    case 'get_posted_bpb_populate_form':
        $param = array();
        $param['bpbno_int'] = $_POST['bpbno_internal'];

        $bpb_list = $M->lookup_posted_bpb($param);
        $bpb = array();
        if(count($bpb_list)){
            $bpb = $bpb_list[0];
        }

        echo json_encode(array(
            'status' => true,
            'message' => "Lookup berhasil",
            'data' => $bpb
        ));
        break;
    case 'lookup_po':
        $param = array();
        if(isset($_POST['pono']) and trim($_POST['pono']) != '' ){
            $param['pono'] = $_POST['pono'];
        }
        if(!count($param)){
//            echo json_encode(array(
//                'status' => false,
//                'message' => "Masukkan keyword",
//            ));
//            exit();
            $param['pono'] = '%';
        }

        echo json_encode(array(
            'status' => true,
            'message' => "Lookup berhasil",
            'data' => $M->lookup_po($param)
        ));
        exit();
        break;
    case 'lookup_payment':
        $param = array();
        if(isset($_POST['pono']) and trim($_POST['pono']) != '' ){
            $param['pono'] = $_POST['pono'];
        }
        if(isset($_POST['bpbno_int']) and trim($_POST['bpbno_int']) != '' ){
            $param['bpbno_int'] = $_POST['bpbno_int'];
        }

        echo json_encode(array(
            'status' => true,
            'message' => "Lookup berhasil",
            'data' => $M->lookup_payment($param)
        ));
        exit();
        break;
        break;
    case 'get_journal_items':
        $id = $_GET['id'];

        $data = array(
            'rows' => $M->get_journal_items($id),
            'total_debit' => 0,
            'total_credit' => 0,
        );

        foreach($data['rows'] as $k=>$r){
            $data['total_debit'] += $r->debit;
            $data['total_credit'] += $r->credit;

            $data['rows'][$k]->id_coa = $ch->format_coa($data['rows'][$k]->id_coa);
            $data['rows'][$k]->debit = number_format($data['rows'][$k]->debit,0);
            $data['rows'][$k]->credit = number_format($data['rows'][$k]->credit,0);
        }

        echo json_encode(array(
            'status' => true,
            'message' => "Lookup berhasil",
            'data' => $data
        ));
        exit();
        break;
    case 'get_journal_item':
        $id = $_GET['id'];
        $row_id = $_GET['row_id'];

        echo json_encode(array(
            'status' => true,
            'message' => "Lookup berhasil",
            'data' => $M->get_journal_item($id, $row_id)
        ));
        exit();
        break;
    case 'journal_item':
        $id = $_GET['id'];
        $row_id = $_GET['row_id'];

        if($_POST['mode'] != 'del'){
            $data = array(
                'id_journal' => $id,
                'id_coa' => $_POST['id_coa'],
                'curr' => $_POST['curr'],
                'id_costcenter' => isset($_POST['id_costcenter']) ? $_POST['id_costcenter'] : '',
                'nm_ws' => isset($_POST['nm_ws']) ? $_POST['nm_ws'] : '',
                'debit' => $_POST['debit'] ? : 0,
                'credit' => $_POST['credit'] ? : 0,
                'description' => $_POST['description'],
				'rate' => $_POST['rate'] ? : 0,
            );
            if($_POST['id_coa']) {
                $coa = $M->get_coa_list($_POST['id_coa']);
                $data['nm_coa'] = $coa[0]->nm_coa;
            }
            if(isset($_POST['id_costcenter']) and $_POST['id_costcenter']) {
                $costcenter = $M->get_cost_center_list($_POST['id_costcenter']);
                $data['nm_costcenter'] = $costcenter[0]->nm_costcenter;
                $data['id_cost_category'] = $costcenter[0]->id_cost_category;
                $data['nm_cost_category'] = $costcenter[0]->nm_cost_category;
                $data['id_cost_dept'] = $costcenter[0]->id_cost_dept;
                $data['nm_cost_dept'] = $costcenter[0]->nm_cost_dept;
                $data['id_cost_sub_dept'] = $costcenter[0]->id_cost_sub_dept;
                $data['nm_cost_sub_dept'] = $costcenter[0]->nm_cost_sub_dept;
            }else{
                $data['nm_costcenter'] = $data['id_cost_category'] = $data['nm_cost_category'] = $data['id_cost_dept']
                    = $data['nm_cost_dept'] = $data['id_cost_sub_dept'] = $data['nm_cost_sub_dept'] = '';
            }

            if($_POST['mode'] == 'save')
            {
                $data['row_id'] = $M->get_next_item_id($id);
                $data['dateadd'] = date('Y-m-d H:i:s', time());
                $data['useradd'] = $user;
           
				$insert_id = $M->save_journal_item($data);
				


            }elseif($_POST['mode'] == 'update'){
                $data['row_id'] = $row_id;
                $data['dateedit'] = date('Y-m-d H:i:s', time());
                $data['useredit'] = $user;
                $status = $M->update_journal_item($id, $row_id, $data);
            }
        }elseif($_POST['mode'] == 'del'){
            $M->delete_journal_item($id, $row_id);
        }

        echo json_encode(array(
            'status' => true,
            'message' => "Transaksi berhasil",
            'data' => $_POST
        ));
        exit();
        break;
    default:
        exit();
}