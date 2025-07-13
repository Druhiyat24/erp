<?php
// Session Checking
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$_SESSION['username']=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("F_P_Pembayaran","userpassword","username='$_SESSION[username]'");
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

    public function get_journal($id)
    {
        return $this->query("
   SELECT FD.debit,FH.* FROM
       fin_journal_h  FH
			LEFT JOIN (select id_journal,sum(ifnull(debit,0))debit FROM fin_journal_d WHERE id_coa !='25205' and id_journal ='$id')FD
			ON FD.id_journal = FH.id_journal
            WHERE FH.id_journal = '$id';
        ")->row();
    }

    public function get_journal_by_num($num)
    {
        return $this->query("
            SELECT * FROM
            fin_journal_h jh
            WHERE num_journal = '$num';
        ")->row();
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

    public function check_bpb($id,$no_rekap,$type_nya, $checking = TRUE){
	$where_poi = "";
	if(ISSET($type_nya)){
		if($type_nya == 'WIP'){
			$is_jasa = "Y";
		}else{
			$is_jasa = "N";
			
		}
	}			
		
        $used = $this->query("SELECT reff_doc FROM fin_journal_h WHERE reff_doc = '$id'  AND reff_doc2 = '$no_rekap'")->row();

        if($checking) {
            if (!is_null($used)) {
                return array(
                    'status' => false,
                    'errcode' => '01',
                    'message' => 'List Payment number already used'
                );
            }
        }

        $bpb = $this->query("SELECT * FROM bpb WHERE bpbno_int = '$id'")->result();

        if(!count($bpb)){
            return array(
                'status' => false,
                'errcode' => '02',
                'message' => 'BPB number not exist'
            );
        }

        $bpb_detail = $this->query("SELECT MASTER.* FROM (
		SELECT 
                 bpb.bpbno
				 ,bpb.bpbno_int
				 ,bpb.id id_bpb
				,poh.pono
				,poh.id_supplier supplier_po
				,poi.price price_po
				,poh.ppn
                ,bpb.bpbdate
                ,bpb.id_supplier
                ,bpb.id_item
                ,ifnull(bpb.qty,0) - ifnull(bpb.qty_reject,0)qty
                ,bpb.unit
                ,bpb.curr
                ,bpb.price
                ,ms.Supplier
                ,ms.supplier_code
                ,ms.vendor_cat
                ,mi.itemdesc
                ,mi.mattype
                ,mi.matclass
                ,mg.id id_group
                ,poi.qty qty_po
                ,poi.id id_po_det
				,poi.id_gen
				,((ifnull(poh.ppn,0)/100)*((bpb.qty*bpb.price)) + bpb.qty*bpb.price )nilai
				,'Y' is_jasa
            FROM 
                bpb 
                LEFT JOIN mastersupplier ms ON bpb.id_supplier = ms.Id_Supplier
                LEFT JOIN masteritem mi ON bpb.id_item = mi.id_item
                LEFT JOIN mastergroup mg ON mi.matclass = mg.nama_group
				LEFT JOIN po_item poi ON poi.id_gen = bpb.id_item AND bpb.id_jo = poi.id_jo
				LEFT JOIN po_header poh ON poh.id = poi.id_po AND poh.id_supplier = bpb.id_supplier
				
			WHERE 
                bpb.bpbno_int = '$id' AND poi.cancel = 'N' group by bpb.id
				UNION ALL
            SELECT 
                bpb.bpbno
				,bpb.bpbno_int
				,bpb.id id_bpb
				,poh.pono
				,poh.id_supplier supplier_po
				,poi.price price_po
				,poh.ppn				
                ,bpb.bpbdate
                ,bpb.id_supplier
                ,bpb.id_item
                ,ifnull(bpb.qty,0) - ifnull(bpb.qty_reject,0)qty
                ,bpb.unit
                ,bpb.curr
                ,bpb.price
                ,ms.Supplier
                ,ms.supplier_code
                ,ms.vendor_cat
                ,mi.itemdesc
                ,mi.mattype
                ,mi.matclass
                ,mg.id id_group
                ,poi.qty qty_po
                ,poi.id id_po_det
				,poi.id_gen
				,((ifnull(poh.ppn,0)/100)*((bpb.qty*poi.price)) + bpb.qty*poi.price )nilai
				,'N' is_jasa
            FROM 
                bpb
                LEFT JOIN mastersupplier ms ON bpb.id_supplier = ms.Id_Supplier
                LEFT JOIN masteritem mi ON bpb.id_item = mi.id_item
                LEFT JOIN mastergroup mg ON mi.matclass = mg.nama_group
				LEFT JOIN po_header poh ON poh.pono = bpb.pono
				LEFT JOIN po_item poi ON poi.id_po = poh.id
            WHERE 
                bpb.bpbno_int = '$id' group by bpb.id
				)MASTER WHERE MASTER.is_jasa = '$is_jasa'
        ")->result();
        return array(
            'status' => true,
            'errcode' => null,
            'message' => 'BPB number found',
            'data'  => $bpb_detail
        );
    }
    public function check_po($id, $checking = TRUE){
        $po = $this->query("SELECT pono FROM po_header WHERE pono = '$id'")->result();

        if(!count($po)){
            return array(
                'status' => false,
                'errcode' => '02',
                'message' => 'PO number not exist'
            );
        }

        $po_detail = $this->query("
            SELECT 
                poh.pono
                ,poh.podate
                ,poh.id_supplier
                ,poi.id_gen id_item
                ,poi.qty
                ,poi.unit
                ,poi.curr
                ,poi.price
                ,ms.Supplier
                ,ms.supplier_code
                ,ms.vendor_cat
                ,mi.itemdesc
                ,mi.mattype
                ,mi.matclass
                ,CASE WHEN mg.id IS NULL THEN mi.tipe_item ELSE mg.id END id_group
            FROM po_header poh
                LEFT JOIN po_item poi ON poh.id = poi.id_po
                LEFT JOIN masteritem mi ON poi.id_gen = mi.id_item
                LEFT JOIN mastergroup mg ON mi.matclass = mg.nama_group
                LEFT JOIN mastersupplier ms ON poh.id_supplier = ms.Id_Supplier
            WHERE 1=1
                AND poh.pono = '$id'
        ")->result();

        return array(
            'status' => true,
            'errcode' => null,
            'message' => 'PO number found',
            'data'  => $po_detail
        );
    }

    public function save($d)
    {
        $sql = "
            INSERT INTO fin_journal_h 
              (company, period, id_journal, num_journal, date_journal, type_journal, reff_doc, reff_doc2, src_reference, fg_intercompany, id_intercompany
              ,dateadd, useradd,d_cek_giro,v_cek_giro)
            VALUES 
              ('{$d['company']}', '{$d['period']}', '{$d['id_journal']}', '{$d['num_journal']}', '{$d['date_journal']}'
              , '{$d['type_journal']}', '{$d['reff_doc']}', '{$d['reff_doc2']}', '{$d['src_reference']}', '{$d['fg_intercompany']}', '{$d['id_intercompany']}'
             ,'{$d['dateadd']}', '{$d['useradd']}', '{$d['d_cek_giro']}', '{$d['v_cek_giro']}')
            ;
        ";

        $this->query($sql);

//        return $id_journal; //$this->conn->insert_id;
    }

    public function update($id, $data)
    {
        $sql = "
            UPDATE fin_journal_h SET 
              period = '{$data['period']}',
              num_journal = '{$data['num_journal']}',
              date_journal = '{$data['date_journal']}',
              type_journal = '{$data['type_journal']}',
              reff_doc = '{$data['reff_doc']}',
              reff_doc2 = '{$data['reff_doc2']}',
              fg_intercompany = '{$data['fg_intercompany']}',
              id_intercompany = '{$data['id_intercompany']}',
              dateedit = '{$data['dateedit']}',
              useredit = '{$data['useredit']}',
			  d_cek_giro = '{$d['d_cek_giro']}',
			  v_cek_giro = '{$d['v_cek_giro']}'
            WHERE id_journal = '$id';
        ";

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

    public function post_journal($id, $post)
    {
        $journal = (array) $this->get_journal($id);

        switch($journal['type_journal']){
            case Config::$journal_usage['PURCHASE']:
                //Check whether this is gr journal or not by checking on reff to bpb. code 02 means not existent
                $bpb_request = $this->check_bpb($journal['reff_doc'],$no_rekap,$type_nya, FALSE);

                if($bpb_request['errcode'] != '02'){
                    $this->insert_bpb_ir($journal['reff_doc'],$id);
                }
                break;
            case Config::$journal_usage['PAYMENT']:
                //Check whether this is gr journal or not by checking on reff to bpb. code 02 means not existent
//                $bpb_request = $this->check_bpb($journal['reff_doc'], FALSE);
//
//                if($bpb_request['errcode'] != '02'){
//                    $this->insert_bpb_ir($journal['reff_doc'],$id);
//                }
                break;
            default:
        }

        $sql = "
            UPDATE fin_journal_h SET 
              fg_post = '2',
              date_post = '{$post['date_post']}',
              user_post = '{$post['user_post']}'
            WHERE id_journal = '$id';
        ";

        return $this->query($sql);
    }

    public function get_cost_center_list()
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
        ";

        return $this
            ->query($sql)
            ->result();
    }

    public function get_coa_list($key='')
    {
        $sql = "
            SELECT 
                mc.id_coa
                ,mc.nm_coa
            FROM mastercoa mc
            WHERE 1=1
                AND mc.fg_active = '1'
                AND mc.fg_posting = '1'
                {WHERE}
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

    public function get_accounting_period()
    {
        //TODO: fetch from actual table
        return array(
            date('m/Y', strtotime(date('Y-m-d', time()).' -1 month')),
            date('m/Y', time()),
            date('m/Y', strtotime(date('Y-m-d', time()).' +1 month')),
        );
    }

public function IsGeneral($bpbno_nya){
	 global $con_new;
$Bm = new Basemodel($con_new);	 
    $mapping_coa_gen = $Bm->query("
            SELECT 
	BPB.id_item, BPB.bpbno_int,SUBSTR(MI.goods_code,1,3) code_gen FROM bpb BPB 
	INNER JOIN masteritem MI ON BPB.id_item = MI.id_item
	INNER JOIN mapping_coa_gen MCG ON MCG.n_code = SUBSTR(MI.goods_code,1,3)
	WHERE BPB.bpbno_int = '{$bpbno_nya}'  GROUP BY BPB.bpbno_int 

        ")->row();	

	if((ISSET($mapping_coa_gen->code_gen)) || (!EMPTY($mapping_coa_gen->code_gen))){
		return 1;
	}else{
		return 0;
		
	}
}

public function get_id_coa_gen_payment_from_bpb($bpb){

		// global $con_new;
	   // $Bm = new Basemodel($con_new);
$q = "SELECT BPB.id_item
		, BPB.bpbno
		,SUBSTR(MI.goods_code,1,3) code_gen
		,MCG.n_vendor_cat 
		,MCG.id_coa_debit_gr id_persediaan
		,MCG.id_coa_credit_gr id_gr
		,MCG.id_coa_debit_ir 
		,MCG.id_coa_credit_ir id_hutang
		,COA_GR_DEB.nm_coa nm_persediaan
		,COA_GR_CRE.nm_coa nm_gr
		,COA_IR_DEB.nm_coa nama_coa_ir_deb
		,COA_IR_CRE.nm_coa nm_hutang		
		FROM bpb BPB 
			INNER JOIN masteritem MI ON BPB.id_item = MI.id_item 
			INNER JOIN mapping_coa_gen MCG ON MCG.n_code = SUBSTR(MI.goods_code,1,3) 
			INNER JOIN mastercoa COA_GR_DEB 
				ON COA_GR_DEB.id_coa = MCG.id_coa_debit_gr
			INNER JOIN mastercoa COA_GR_CRE 
				ON COA_GR_CRE.id_coa = MCG.id_coa_credit_gr
			INNER JOIN mastercoa COA_IR_DEB 
				ON COA_IR_DEB.id_coa = MCG.id_coa_debit_ir
			INNER JOIN mastercoa COA_IR_CRE 
				ON COA_IR_CRE.id_coa = MCG.id_coa_credit_ir
			WHERE BPB.bpbno = '{$bpb['bpbno']}' and MCG.n_vendor_cat = '{$bpb['vendor_cat']}'  GROUP BY BPB.bpbno";
			
    $mapping_coa = $this->query($q)->row();
if(!ISSET($mapping_coa->id_item) || EMPTY($mapping_coa->id_item) ){
$q = "SELECT BPB.id_item
		, BPB.bpbno
		,SUBSTR(MI.goods_code,1,3) code_gen
		,MCG.n_vendor_cat 
		,MCG.id_coa_debit_gr id_persediaan
		,MCG.id_coa_credit_gr id_gr
		,MCG.id_coa_debit_ir 
		,MCG.id_coa_credit_ir id_hutang
		,COA_GR_DEB.nm_coa nm_persediaan
		,COA_GR_CRE.nm_coa nm_gr
		,COA_IR_DEB.nm_coa nama_coa_ir_deb
		,COA_IR_CRE.nm_coa nm_hutang		
		FROM bpb BPB 
			INNER JOIN masteritem MI ON BPB.id_item = MI.id_item 
			INNER JOIN mapping_coa_gen MCG ON MCG.n_code = SUBSTR(MI.goods_code,1,3) 
			INNER JOIN mastercoa COA_GR_DEB 
				ON COA_GR_DEB.id_coa = MCG.id_coa_debit_gr
			INNER JOIN mastercoa COA_GR_CRE 
				ON COA_GR_CRE.id_coa = MCG.id_coa_credit_gr
			INNER JOIN mastercoa COA_IR_DEB 
				ON COA_IR_DEB.id_coa = MCG.id_coa_debit_ir
			INNER JOIN mastercoa COA_IR_CRE 
				ON COA_IR_CRE.id_coa = MCG.id_coa_credit_ir
			WHERE MCG.n_code = 'UTL' and MCG.n_vendor_cat = '{$bpb['vendor_cat']}'  GROUP BY BPB.bpbno";
    $mapping_coa = $this->query($q)->row();
}

        $persediaan = array(
            'id' => $mapping_coa->id_persediaan,
            'nm' => $mapping_coa->nm_persediaan
        );
        $gr = array(
            'id' => $mapping_coa->id_gr,
            'nm' => $mapping_coa->nm_gr
        );
        $hutang = array(
            'id' => $mapping_coa->id_hutang,
            'nm' => $mapping_coa->nm_hutang
        );

        return array(
            $persediaan,
            $gr,
            $hutang
        );
		
		
		
}


    public function get_id_coa_payment_from_bpb_subcon($bpb){

$q = "SELECT BPB.id_item
		, BPB.bpbno_int
		,SUBSTR(MI.goods_code,1,3) code_gen
		,MCG.n_vendor_cat 
		,MCG.id_coa_debit_gr id_persediaan
		,MCG.id_coa_credit_gr id_gr
		,MCG.id_coa_debit_ir 
		,MCG.id_coa_credit_ir id_hutang
		,COA_GR_DEB.nm_coa nm_persediaan
		,COA_GR_CRE.nm_coa nm_gr
		,COA_IR_DEB.nm_coa nama_coa_ir_deb
		,COA_IR_CRE.nm_coa nm_hutang		
		FROM bpb BPB 
			INNER JOIN masteritem MI ON BPB.id_item = MI.id_item
            LEFT JOIN mastercf MCF ON MCF.cfdesc = MI.matclass		
			INNER JOIN mapping_coa_gen MCG ON MCG.n_code = MCF.cfcode
			INNER JOIN mastercoa COA_GR_DEB 
				ON COA_GR_DEB.id_coa = MCG.id_coa_debit_gr
			INNER JOIN mastercoa COA_GR_CRE 
				ON COA_GR_CRE.id_coa = MCG.id_coa_credit_gr
			INNER JOIN mastercoa COA_IR_DEB 
				ON COA_IR_DEB.id_coa = MCG.id_coa_debit_ir
			INNER JOIN mastercoa COA_IR_CRE 
				ON COA_IR_CRE.id_coa = MCG.id_coa_credit_ir
			WHERE BPB.bpbno_int = '{$bpb['bpbno_int']}' and MCG.n_vendor_cat = '{$bpb['vendor_cat']}'  GROUP BY BPB.bpbno_int
		";

		
		
        $mapping_coa = $this->query($q)->row();

        $persediaan = array(
            'id' => $mapping_coa->id_persediaan,
            'nm' => $mapping_coa->nm_persediaan
        );
        $gr = array(
            'id' => $mapping_coa->id_gr,
            'nm' => $mapping_coa->nm_gr
        );
        $hutang = array(
            'id' => $mapping_coa->id_hutang,
            'nm' => $mapping_coa->nm_hutang
        );

        return array(
            $persediaan,
            $gr,
            $hutang
        );
    }


    public function get_id_coa_payment_from_bpb($bpb){
        $mapping_coa = $this->query("
            SELECT 
              mp.gr_d id_persediaan, mc1.nm_coa nm_persediaan
              ,mp.gr_k id_gr, mc2.nm_coa nm_gr
              ,mp.ir_k id_hutang, mc3.nm_coa nm_hutang
            FROM mapping_coa mp
            LEFT JOIN mastercoa mc1 ON mc1.id_coa = mp.gr_d
            LEFT JOIN mastercoa mc2 ON mc2.id_coa = mp.gr_k
            LEFT JOIN mastercoa mc3 ON mc3.id_coa = mp.ir_k
            WHERE 
            id_group = '{$bpb['id_group']}' and vendor_cat = '{$bpb['vendor_cat']}' 
        ")->row();

        $persediaan = array(
            'id' => $mapping_coa->id_persediaan,
            'nm' => $mapping_coa->nm_persediaan
        );
        $gr = array(
            'id' => $mapping_coa->id_gr,
            'nm' => $mapping_coa->nm_gr
        );
        $hutang = array(
            'id' => $mapping_coa->id_hutang,
            'nm' => $mapping_coa->nm_hutang
        );

        return array(
            $persediaan,
            $gr,
            $hutang
        );
    }

    public function get_id_coa_payment_from_po($po){
        $mapping_coa = $this->query("
            SELECT 
              mp.gr_d id_persediaan, mc1.nm_coa nm_persediaan
              ,mp.gr_k id_gr, mc2.nm_coa nm_gr
              ,mp.ir_k id_hutang, mc3.nm_coa nm_hutang
            FROM mapping_coa mp
            LEFT JOIN mastercoa mc1 ON mc1.id_coa = mp.gr_d
            LEFT JOIN mastercoa mc2 ON mc2.id_coa = mp.gr_k
            LEFT JOIN mastercoa mc3 ON mc3.id_coa = mp.ir_k
            WHERE 
            id_group = '{$po['id_group']}' and vendor_cat = '{$po['vendor_cat']}' 
        ")->row();

        if(!count($mapping_coa)){
            return array(false, false, false);
        }
        $persediaan = array(
            'id' => $mapping_coa->id_persediaan,
            'nm' => $mapping_coa->nm_persediaan
        );
        $gr = array(
            'id' => $mapping_coa->id_gr,
            'nm' => $mapping_coa->nm_gr
        );
        $hutang = array(
            'id' => $mapping_coa->id_hutang,
            'nm' => $mapping_coa->nm_hutang
        );

        return array(
            $persediaan,
            $gr,
            $hutang
        );
    }

    public function insert_bpb_payment($bpbno, $id_journal, $coa_bank, $amount_bank)
    {
        $bpb_request = $this->check_bpb($bpbno,$no_rekap,$type_nya, false);
		$type_nya = substr($bpbno,0,3);
    $IsGeneral = IsGeneral($bpbno);
	if($type_nya == "WIP"){
		$IsGeneral = "2";
	}
		
		
        if($bpb_request['status']){
            $dataBpb = array();
            $bpb = $bpb_request['data'];
            $row_id = 1;
            $dateadd = date('Y-m-d H:i:s', time());
            $_SESSION['username'] = $_SESSION['username'];

//print_r($IsGeneral);
//die();
            foreach($bpb as $_bpb){
                $_bpb = (array) $_bpb;
             //   list($coa_persediaan, $coa_gr, $coa_hutang) = $this->get_id_coa_payment_from_bpb($_bpb);
/* 			if($IsGeneral == '1'){
				//echo "123";
				list($coa_persediaan, $coa_gr, $coa_hutang) = $this->get_id_coa_gen_payment_from_bpb($_bpb);

			}else{
				list($coa_persediaan, $coa_gr, $coa_hutang) = $this->get_id_coa_payment_from_bpb($_bpb);
			} */
			
			if($IsGeneral == '1'){
				list($coa_persediaan, $coa_gr, $coa_hutang)  = $this->get_id_coa_gen_payment_from_bpb($_bpb);
			}else if($IsGeneral == '2'){
				list($coa_persediaan, $coa_gr, $coa_hutang)  = get_id_coa_payment_from_bpb_subcon($___bpb);
			}else{
				list($coa_persediaan, $coa_gr, $coa_hutang)  = $this->get_id_coa_payment_from_bpb($_bpb);
			}			
			
			
			
                // hutang
                $_debit = array(
                    'row_id' => $row_id++,
                    'id_journal' => $id_journal,
                    'id_coa' => $coa_hutang['id'],
                    'nm_coa' => $coa_hutang['nm'],
                    'curr' => $_bpb['curr'],
                    'debit' => $_bpb['qty'] * $_bpb['price'],
                    'credit' => 0,
                    'description' => $_bpb['itemdesc'],
                    'id_reference' => $_bpb['id_item'],
                    'src_reference' => Config::$reference_src_key_item,
                    'dateadd' => $dateadd,
                    'useradd' => $_SESSION['username'],
                );

                $dataBpb[] = $_debit;
            }


            if(isset($coa_bank['id'])) {
                // bank
                $_credit = array(
                    'row_id' => $row_id++,
                    'id_journal' => $id_journal,
                    'id_coa' => $coa_bank['id'],
                    'nm_coa' => $coa_bank['nm'],
                    'curr' => $_bpb['curr'],
                    'debit' => 0,
                    'credit' => $amount_bank,
                    'description' => $_bpb['bpbno_int'],
                    'id_reference' => '',
                    'src_reference' => '',
                    'dateadd' => $dateadd,
                    'useradd' => $_SESSION['username'],
                );

                $dataBpb[] = $_credit;
            }

            foreach($dataBpb as $_bpb){
                $sql = "
                    INSERT INTO fin_journal_d
                      (id_journal, row_id, id_coa, nm_coa, curr, debit, credit
                      ,description, id_reference, src_reference, dateadd, useradd)
                    VALUES 
                      ('{$_bpb['id_journal']}', '{$_bpb['row_id']}', '{$_bpb['id_coa']}', '{$_bpb['nm_coa']}', '{$_bpb['curr']}', '{$_bpb['debit']}', '{$_bpb['credit']}'
                      ,'{$_bpb['description']}','{$_bpb['id_reference']}','{$_bpb['src_reference']}' ,'{$_bpb['dateadd']}', '{$_bpb['useradd']}'
                      )
                    ;
                ";
                // Insert detail
                $this->query($sql);
            }
        }
    }

    public function insert_po_payment($pono, $id_journal, $coa_bank, $amount_bank)
    {
        $po_request = $this->check_po($pono);

        if($po_request['status']){
            $dataPo = array();
            $po = $po_request['data'];
            $row_id = 1;
            $dateadd = date('Y-m-d H:i:s', time());
            $_SESSION['username'] = $_SESSION['username'];

            foreach($po as $_po){
                $_po = (array) $_po;
                list($coa_persediaan, $coa_gr, $coa_hutang) = $this->get_id_coa_payment_from_po($_po);

                if($coa_hutang) {
                    // hutang
                    $_debit = array(
                        'row_id' => $row_id++,
                        'id_journal' => $id_journal,
                        'id_coa' => $coa_hutang['id'],
                        'nm_coa' => $coa_hutang['nm'],
                        'curr' => $_po['curr'],
                        'debit' => $_po['qty'] * $_po['price'],
                        'credit' => 0,
                        'description' => $_po['itemdesc'],
                        'id_reference' => $_po['id_item'],
                        'src_reference' => Config::$reference_src_key_item,
                        'dateadd' => $dateadd,
                        'useradd' => $_SESSION['username'],
                    );

                    $dataPo[] = $_debit;
                }
            }

            if(isset($coa_bank['id'])) {
                // bank
                $_credit = array(
                    'row_id' => $row_id++,
                    'id_journal' => $id_journal,
                    'id_coa' => $coa_bank['id'],
                    'nm_coa' => $coa_bank['nm'],
                    'curr' => $_po['curr'],
                    'debit' => 0,
                    'credit' => $amount_bank,
                    'description' => $_po['pono'],
                    'id_reference' => '',
                    'src_reference' => '',
                    'dateadd' => $dateadd,
                    'useradd' => $_SESSION['username'],
                );

                $dataPo[] = $_credit;
            }

            foreach($dataPo as $_po){
                $sql = "
                    INSERT INTO fin_journal_d
                      (id_journal, row_id, id_coa, nm_coa, curr, debit, credit
                      ,description, id_reference, src_reference, dateadd, useradd)
                    VALUES 
                      ('{$_po['id_journal']}', '{$_po['row_id']}', '{$_po['id_coa']}', '{$_po['nm_coa']}', '{$_po['curr']}', '{$_po['debit']}', '{$_po['credit']}'
                      ,'{$_po['description']}','{$_po['id_reference']}','{$_po['src_reference']}' ,'{$_po['dateadd']}', '{$_po['useradd']}'
                      )
                    ;
                ";
                // Insert detail
                $this->query($sql);
            }
        }
    }

    public function check_rekap($id, $checking = TRUE)
    {
        $used = $this->query("SELECT reff_doc FROM fin_journal_h WHERE reff_doc = '$id'")->row();

        if($checking) {
            if (!is_null($used)) {
                return array(
                    'status' => false,
                    'errcode' => '01',
                    'message' => 'REKAP number already used'
                );
            }
        }
		$q = "            	SELECT 
                    rap.v_listcode
                    ,rap.v_nojournal
                    ,rap.v_source
                    ,MAX(fjd.reff_doc) no_bpb
                FROM 
                    fin_status_journal_ap rap
                    LEFT JOIN fin_journal_d fjd ON rap.v_source = 'KB' AND rap.v_nojournal = fjd.id_journal
                WHERE v_listcode = '$id'  AND fjd.reff_doc !='' AND fjd.reff_doc IS NOT NULL GROUP BY fjd.reff_doc ";
				
        $rekap = $this->query($q)->result();		
		$rekap[0]->v_source = 'KB';
		$rekap[0]->no_po = '';

	
		$q_ = "SELECT v_source,v_nojournal FROM fin_status_journal_ap WHERE v_listcode = '$id'";
        $_source = $this->query($q_)->result();
		$ref = $_source[0]->v_nojournal;
		$src = $_source[0]->v_source;
		//$source = $_source->v_source;
		//print_r($_source[0]->v_source);
		if($_source[0]->v_source == 'PO'){
			$q_bpb = "SELECT bpbno_int FROM bpb WHERE pono = '$ref'";
			 $_ref = $this->query($q_bpb)->result();
			 $rekap[0]->no_po = $ref;
			  $rekap[0]->no_bpb = $_ref[0]->bpbno_int;
			 $rekap[0]->v_source = $src;
		}
	
		
		
		




        if(!count($rekap)){
            return array(
                'status' => false,
                'errcode' => '02',
                'message' => 'REKAP number not exist'
            );
        }

        return array(
            'status' => true,
            'errcode' => null,
            'message' => 'REKAP number found',
            'data'  => $rekap
        );
    }


	public function check_pph($no_rekap){
		$pph = $this->query("SELECT 
			A.id_journal
			,SUM(IFNULL(MT.percentage,0))pph
			FROM fin_journal_h A
			INNER JOIN(
				SELECT v_listcode,v_nojournal FROM fin_status_journal_ap WHERE v_listcode = '$no_rekap'
			)B ON A.id_journal = B.v_nojournal
			INNER JOIN
				mtax MT ON MT.idtax = A.n_pph
			
			GROUP BY B.v_listcode")->result();
			if($pph[0]->pph == '0' || $pph[0]->pph == ''){
				return array(
					'key' => 0,
					'pph' => 0
				);
			}else{
				if(!ISSET($pph[0]->pph)){
					return array(
						'key' => 1,
						'pph' => $pph[0]->pph
					);							
					
				}
				else{
					return array(
						'key' => 1,
						'pph' => $pph[0]->pph
					);							
				}
		
				
			}
	}


	public function Get_List_payment($id){
		$sql = "SELECT MASTER.*,SUM(MASTER.nilai_kontrabons) nilai_kontrabon FROM(
SELECT AP.v_nojournal
		,AP.v_isapproval
		,AP.v_status
    ,AP.v_listcode
		,AP.v_notes
		,DATE(AP.d_insert) date_list
		,JOURNAL_DETAIL.nilai_pph
		,JOURNAL_DETAIL.nilai nilai_kontrabons
		,JOURNAL_HEADER.date_journal tanggal_kontrabon
		,if(JOURNAL_DETAIL.journal_ref IS NULL,JOURNAL_HEADER.reff_doc,JOURNAL_DETAIL.journal_ref) reff_doc
		,ifnull(BPB.pono,POH_WIP.pono)pono
		,BPB.bpbno
		,BPB.id_supplier 
		,SUPPLIER.Supplier nama_supplier 
		,SUPPLIER.supplier_code supplier_code
		,ifnull(PO.kode_pterms,PT_WIP.kode_pterms) days_pterms
		,DATE_ADD(JOURNAL_HEADER.date_journal, INTERVAL PO.days_pterms DAY) as jatuh_tempo
	FROM fin_status_journal_ap AP LEFT JOIN
	(
    
SELECT 	
			 C.is_normal
			,C.is_utang		
			,C.is_pajak	
			,SUM(C.pajak)pajak
			,SUM(C.debit)debit
			,(SUM(C.debit) - SUM(C.pajak)) debit_before_pajak
			,SUM(C.utang)utang	
			, (((ifnull(C.percentage,0)/100))*((SUM(C.debit) - SUM(C.pajak))  - SUM(C.utang)))nilai_pph
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
		SELECT id_journal,date_journal,reff_doc FROM fin_journal_h
	)JOURNAL_HEADER ON AP.v_nojournal = JOURNAL_HEADER.id_journal
	LEFT JOIN (
		SELECT bpbno_int,pono,bpbno,id_supplier,id_item,id_jo FROM bpb 	 GROUP BY bpbno
	)BPB ON JOURNAL_HEADER.reff_doc =BPB.bpbno_int OR JOURNAL_DETAIL.journal_ref =BPB.bpbno_int
	LEFT JOIN(
		SELECT Id_Supplier,Supplier,supplier_code FROM mastersupplier 
	)SUPPLIER ON SUPPLIER.Id_Supplier = BPB.id_supplier
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
		,'0' nilai_pph
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
 WHERE AP.v_source = 'PO' GROUP BY AP.v_listcode) MASTER WHERE MASTER.v_listcode = '$id' GROUP BY MASTER.v_listcode";
	
	
	
	
$list_detail = $this->query($sql)->result();

        return array(
            'status' => true,
            'errcode' => null,
            'message' => 'List  found',
            'data'  => $list_detail
        );	
	
	
	}

    public function insert_rekap_payment($no_rekap, $id_journal, $coa_bank, $amount_bank__)
    {
		print_r($no_rekap."<br/>");
		print_r($id_journal."<br/>");
		print_r($coa_bank);
		print_r($amount_bank__."<br/>");
		//die();
        $rekap_request = $this->check_rekap($no_rekap, false);
		$type_nya = substr($rekap_request['data'][0]->no_bpb,0,3);
		$IsGeneral = IsGeneral($rekap_request['data'][0]->no_bpb);	
    //$IsGeneral = IsGeneral($bpbno);
	if($type_nya == "WIP"){
		$IsGeneral = "2";
	}
		//echo "<pre>";
		//print_r($rekap_request);
		//echo "</pre>";
		//die();
        if($rekap_request['status']){
            $dataBpb = array();
            $row_id = 1;
            $dateadd = date('Y-m-d H:i:s', time());
            $_SESSION['username'] = $_SESSION['username'];
			$amount  = 0;
			$fg_pkp = 0;
			$ppn_percentage = 0;
			$tot_amt = 0;
			
			$get_list_payment = $this->Get_List_payment($no_rekap);
			$get_list_payment = (object)$get_list_payment;
			$list_n = (object)$get_list_payment->data[0];		
//echo "<pre>";
//print_r($list_n);
//echo "</pre>";			
//die();							
/* 			if($IsGeneral == '1'){
				list($coa_persediaan, $coa_gr, $coa_hutang)  = $this->get_id_coa_gen_payment_from_bpb($_bpb);
			}else if($IsGeneral == '2'){
				list($coa_persediaan, $coa_gr, $coa_hutang)  = $this->get_id_coa_payment_from_bpb_subcon($_bpb);
			}else{
				list($coa_persediaan, $coa_gr, $coa_hutang)  = $this->get_id_coa_payment_from_bpb($_bpb);
			}					
			

                                $_debit = array(
                                    'row_id' => $row_id++,
                                    'id_journal' => $id_journal,
                                    'id_coa' => $coa_hutang['id'],
                                    'nm_coa' => $coa_hutang['nm'],
                                    'curr' => $_bpb['curr'],
                                    'debit' => $list_n->nilai_kontrabons,
                                    'credit' => 0,
                                    'description' => $no_rekap,
                                    'id_reference' => $no_rekap,
                                    'src_reference' => Config::$reference_src_key_item,
                                    'dateadd' => $dateadd,
                                    'useradd' => $_SESSION['username'],
                                );
                                $dataBpb[] = $_debit; */

			
			
            foreach($rekap_request['data'] as $_rekap){
                switch($_rekap->v_source){
                    case 'KB': // Payment from kontrabon
                        $bpb_request = $this->check_bpb($_rekap->no_bpb,$no_rekap,$type_nya, false);
						//echo "<pre>";
/* 							print_r($bpb_request);
							echo "</pre>";
							die(); */
                        if($bpb_request['status']){
                            $bpb = $bpb_request['data'];
                            $dateadd = date('Y-m-d H:i:s', time());
                            $_SESSION['username'] = $_SESSION['username'];

                            foreach($bpb as $_bpb){
                                $_bpb = (array) $_bpb;
                               // list($coa_persediaan, $coa_gr, $coa_hutang) = $this->get_id_coa_payment_from_bpb($_bpb)
						
			if($IsGeneral == '1'){
				list($coa_persediaan, $coa_gr, $coa_hutang)  = $this->get_id_coa_gen_payment_from_bpb($_bpb);
			}else if($IsGeneral == '2'){
				list($coa_persediaan, $coa_gr, $coa_hutang)  = $this->get_id_coa_payment_from_bpb_subcon($_bpb);
			}else{
				list($coa_persediaan, $coa_gr, $coa_hutang)  = $this->get_id_coa_payment_from_bpb($_bpb);
			}		
                $_bpb = (array) $_bpb;
               // list($coa_persediaan, $coa_gr, $coa_hutang) = $this->get_id_coa_payment_from_bpb($_bpb);
			
								if($type_nya == "WIP"){
									
									$amount =( $_bpb['price_po'] * $_bpb['qty']);
									//echo $_bpb['price_po'];
									//$fg_pkp        = $_bpb['fg_pkp'];
									//$ppn_percentage= $_bpb['ppn'];							
									}else{
									$amount = ( $_bpb['price'] * $_bpb['qty']);
									//$fg_pkp        = $_bpb['fg_pkp_non_jasa'];
									//$ppn_percentage= $_bpb['ppn_non_jasa'];				
								}
								
								$ppn_percentage= $_bpb['ppn'];	
								//echo "PPN:$amount";
								
									
									if($ppn_percentage != '0'  ){					
									
										//$amount_bank = $amount +(($ppn_percentage/100)*$amount);
										$amount = $amount +(($ppn_percentage/100)*$amount);
									}else{
										
										//$amount_bank = $amount_bank + $amount;
										
									}
									$tot_amt =$tot_amt + $amount;
									
									//echo "AMOUNT KE".$row_id.":".$amount_bank." ".$amount."</br>";
                                // hutang
                                $_debit = array(
                                    'row_id' => $row_id++,
                                    'id_journal' => $id_journal,
                                    'id_coa' => $coa_hutang['id'],
                                    'nm_coa' => $coa_hutang['nm'],
                                    'curr' => $_bpb['curr'],
                                    'debit' => $amount,
                                    'credit' => 0,
                                    'description' => $_rekap->no_bpb.' - '.$_bpb['itemdesc'],
                                    'id_reference' => $_bpb['id_item'],
                                    'src_reference' => Config::$reference_src_key_item,
                                    'dateadd' => $dateadd,
                                    'useradd' => $_SESSION['username'],
                                );
                                $dataBpb[] = $_debit;
								//echo "<pre>";
								//print_r($dataBpb);
								//echo "</pre>";
								//die(); 
                            }
                        }
                        break;
                    case 'PO': // Payment from PO
                        $po_request = $this->check_po($_rekap->v_nojournal);
						$bpb_request = $this->check_bpb($_rekap->no_bpb,$no_rekap,$type_nya, false);	
                        if($po_request['status']){
							//$bpb = $bpb_request['data'];
						if($IsGeneral == '1'){
							$po = $bpb_request['data'];
						}else{
							$po = $po_request['data'];
						}				

                            $dateadd = date('Y-m-d H:i:s', time());
                            $_SESSION['username'] = $_SESSION['username'];
                            foreach($po as $_po){
								 $_po = (array) $_po;
                               // list($coa_persediaan, $coa_gr, $coa_hutang) = $this->get_id_coa_payment_from_po($_po);
						if($IsGeneral == '1'){
							
							list($coa_persediaan, $coa_gr, $coa_hutang) = $this->get_id_coa_gen_payment_from_bpb($_po);
						}else{
							list($coa_persediaan, $coa_gr, $coa_hutang) = $this->get_id_coa_payment_from_po($_po);
						}
								if($coa_hutang) {
									$amt = ($_po['qty'] * $_po['price']);
									$amount_bank = $amount_bank + $amt;
									
									//echo "AMOUNT KE".$row_id.":".$amount_bank." ".$amt."</br>";
								
                                    // hutang
                                    $_debit = array(
                                        'row_id' => $row_id++,
                                        'id_journal' => $id_journal,
                                        'id_coa' => $coa_hutang['id'],
                                        'nm_coa' => $coa_hutang['nm'],
                                        'curr' => $_po['curr'],
                                        'debit' => $_po['qty'] * $_po['price'],
                                        'credit' => 0,
                                        'description' => $_rekap->v_nojournal.' - '.$_po['itemdesc'],
                                        'id_reference' => $_po['id_item'],
                                        'src_reference' => Config::$reference_src_key_item,
                                        'dateadd' => $dateadd,
                                        'useradd' => $_SESSION['username'],
                                    );

                                    $dataBpb[] = $_debit;
                                }
                            }
                        }
                        break;
                }
            } 

            if(isset($coa_bank['id'])) {
                // bank
				$cur_pph = isset($_bpb['curr'])?$_bpb['curr']:'IDR';
                $_credit = array(
                    'row_id' => $row_id++,
                    'id_journal' => $id_journal,
                    'id_coa' => $coa_bank['id'],
                    'nm_coa' => $coa_bank['nm'],
                    'curr' => $cur_pph,
                    'debit' => 0,
                    'credit' => $list_n->nilai_kontrabons - $list_n->nilai_pph,
                    'description' => $no_rekap,
                    'id_reference' => '',
                    'src_reference' => '',
                    'dateadd' => $dateadd,
                    'useradd' => $_SESSION['username'],
                );

                $dataBpb[] = $_credit;
            }
			
			$pph = $this->check_pph($no_rekap);
			//print_r($pph);
			//die();
			
			if($pph['key'] == '0' ){
				$x = '';
			}else{
				$
	        //PPH
				$val_pph = (($pph['pph']/100)*$amount);
				//$amount = $amount - $val_pph;
				//$dataBpb[1]['credit'] = $amount_bank__ - ($val_pph);
				
				

                $_credit = array(
                    'row_id' => $row_id++,
                    'id_journal' => $id_journal,
                    'id_coa' => '25205',
                    'nm_coa' => 'PAJAK YANG MASIH HARUS DIBAYAR - PPH PASAL 23',
                    'curr' => $cur_pph,
                    'debit' => 0,
                    'credit' => $list_n->nilai_pph,
                    'description' => $no_rekap,
                    'id_reference' => '',
                    'src_reference' => '',
                    'dateadd' => $dateadd,
                    'useradd' => $_SESSION['username'],
                );
				$dataBpb[] = $_credit;
				$dataBpb[0]['debit'] = $amount_bank__;
				//print_r("AMTBANK:".$amount_bank." PPH:".$val_pph);
				//die();
			}	
		//	echo "<pre>";
		//print_r($dataBpb);
		//	echo "</pre>";
		//	die();
				//die();
            foreach($dataBpb as $_bpb){
                $sql = "
                    INSERT INTO fin_journal_d
                      (id_journal, row_id, id_coa, nm_coa, curr, debit, credit
                      ,description, id_reference, src_reference, dateadd, useradd)
                    VALUES 
                      ('{$_bpb['id_journal']}', '{$_bpb['row_id']}', '{$_bpb['id_coa']}', '{$_bpb['nm_coa']}', '{$_bpb['curr']}', '{$_bpb['debit']}', '{$_bpb['credit']}'
                      ,'{$_bpb['description']}','{$_bpb['id_reference']}','{$_bpb['src_reference']}' ,'{$_bpb['dateadd']}', '{$_bpb['useradd']}'
                      )
                    ;
                ";
                // Insert detail
                $this->query($sql);
            }
        }
    }


    public function insert_bank_payment($id_journal, $coa_bank, $amount_bank)
    {
        if(!isset($coa_bank['id'])) {
            return;
        }

        $row_id = 1;
        $dateadd = date('Y-m-d H:i:s', time());
        $_SESSION['username'] = $_SESSION['username'];

        // bank
        $_bank = array(
            'row_id' => $row_id++,
            'id_journal' => $id_journal,
            'id_coa' => $coa_bank['id'],
            'nm_coa' => $coa_bank['nm'],
            'curr' => 'IDR',
            'debit' => 0,
            'credit' => $amount_bank__,
            'description' => '',
            'id_reference' => '',
            'src_reference' => '',
            'dateadd' => $dateadd,
            'useradd' => $_SESSION['username'],
        );

        $sql = "
            INSERT INTO fin_journal_d
              (id_journal, row_id, id_coa, nm_coa, curr, debit, credit
              ,description, id_reference, src_reference, dateadd, useradd)
            VALUES 
              ('{$_bank['id_journal']}', '{$_bank['row_id']}', '{$_bank['id_coa']}', '{$_bank['nm_coa']}', '{$_bank['curr']}', '{$_bank['debit']}', '{$_bank['credit']}'
              ,'{$_bank['description']}','{$_bank['id_reference']}','{$_bank['src_reference']}' ,'{$_bank['dateadd']}', '{$_bank['useradd']}'
              )
            ;
        ";
        // Insert detail
        $this->query($sql);

    }

	function Delete_Detail($data){
		$list_payment = $data['multiple'][0]->no_payment;
		$cnt = count($data['multiple']);
		$ref = "";
	if($cnt > 0){
		echo "Begin...";
		$params_nya = $cnt - 1;	
		for($x=0;$x<$cnt;$x++){
			if($x == $params_nya){
				$ref .= "'".$data['multiple'][$x]->reference."'";
				
			}else{
				$ref .= ",'".$data['multiple'][$x]->reference."'";
			}
			
		}

                $sql = "
					DELETE FROM fin_status_journal_ap WHERE v_nojournal NOT IN($ref) AND v_listcode = '$list_payment';
                ";			
                // Insert detail
                $this->query($sql);				
			
		}else{	
			echo "Waiting...";
		}
	
		
	}


}

// CONTROLLER BEGIN

$M = new Model($con_new);

$ch = new Coa_helper();

$id = isset($_GET['id']) ? $_GET['id'] : '';
if(isset($_POST['submit']) or isset($_POST['posting'])){
	///print_r($_POST);
	$arr_nilai = explode(' ',$_POST['nilai']);
	$_POST['nilai'] = $arr_nilai[1];
	$multiple = json_decode($_POST['array_detail']);
	//die();
    $company = $M->get_master_company();

    $amount = $_POST['nilai'];
//    $lookup_by = $_POST['po'] ? 'po' : ($_POST['bpb'] ? 'bpb' : '');
    $lookup_by = 'rekap_ap';

    if($_POST['bank']) {
        $nm_bank = $M->query("SELECT nm_coa FROM mastercoa WHERE id_coa = '{$_POST['bank']}'")->row();
        $nm_bank = $nm_bank->nm_coa;
        $coa_bank = array(
            'id' => $_POST['bank'],
            'nm' => $nm_bank
        );
    }else{
        $coa_bank = array();
    }



    $data = array(
        'company' => $company->company,
        'period' => $_POST['period'],
        'num_journal' => '',//$_POST['num_journal'],
        'date_journal' => date('Y-m-d', strtotime($_POST['date_journal'])),
        'type_journal' => $_POST['type_journal'],
        'reff_doc' => $_POST['reff_doc'],
        'reff_doc2' => $_POST['reff_doc2'],
        'fg_intercompany' => $_POST['fg_intercompany'],
        'id_intercompany' => $_POST['id_intercompany'],
        'd_cek_giro' => date('Y-m-d', strtotime($_POST['d_cek_giro'])),
        'v_cek_giro' => $_POST['v_cek_giro'],
		'multiple' => $multiple,
    );
    if($status = $ch->general_validation($data)) {

        if ($_POST['mode'] == 'save') {
			//$M->Delete_Detail($data);
            $data['dateadd'] = date('Y-m-d H:i:s', time());
            $data['useradd'] = $_SESSION['username'];
            $journal_code = Config::$journal_code[$data['type_journal']];

            $data['id_journal'] = generate_coa_id("NAG", $journal_code, $data['date_journal']);
            switch ($lookup_by) {
                case 'bpb':
                    $data['src_reference'] = Config::$reference_src_key_bpb;
                    $M->insert_bpb_payment($data['reff_doc'], $data['id_journal'], $coa_bank, $amount);
                    break;
                case 'po':
                    $data['src_reference'] = Config::$reference_src_key_po;
                    $M->insert_po_payment($data['reff_doc'], $data['id_journal'], $coa_bank, $amount);
                    break;
                case 'rekap_ap':
                    $data['src_reference'] = 'REKAP_AP';
                    $M->insert_rekap_payment($data['reff_doc'], $data['id_journal'], $coa_bank, $amount);
                default:
                    $M->insert_bank_payment($data['id_journal'], $coa_bank, $amount);
            }
            $M->save($data);
            echo "<script>window.location.href='?mod=jpay&id={$data['id_journal']}';</script>";
            exit();
        } elseif ($_POST['mode'] == 'update') {
			$M->Delete_Detail($data);
            $journal = $M->get_journal($id);
            if ($journal->num_journal != $data['num_journal'] and $M->get_journal_by_num($data['num_journal'])) {
                echo "<script>alert('Error: Nomor Dokumen sudah terdaftar');</script>";
                $status = false; // Num journal already exists
                $row = json_decode(json_encode($data));
            } else {
                $data['dateedit'] = date('Y-m-d H:i:s', time());
                $data['useredit'] = $_SESSION['username'];
                $status = $M->update($id, $data);

                if (isset($_POST['posting'])) {
                    $data['id_journal'] = $id;
                    if($status = $ch->posting_validation($data)) {
                        $data = array(
                            'date_post' => date('Y-m-d H:i:s', time()),
                            'user_post' => $_SESSION['username']
                        );
                        $M->post_journal($id, $data);
                    }else{
                        $journal = (array) $M->get_journal($id);
                        $row = json_decode(json_encode(array_merge($journal, $data)));
                        $list = $M->get_journal_items($id, $journal['fg_post']);
                        $t_debit = $t_credit = 0;
                        if(count($list)){
                            foreach($list as $l){
                                $t_debit += $l->debit;
                                $t_credit += $l->credit;
                            }
                        }
                    }
                }
                if($status) {
                    echo "<script>window.location.href='?mod=je';</script>";
                    exit();
                }
//            echo "<script>window.location.href='?mod=jp&id=$id';</script>";exit();
            }
        }
    }else{
        if ($_POST['mode'] == 'update') {
            $journal = (array) $M->get_journal($id);
            $row = json_decode(json_encode(array_merge($journal, $data)));
            $list = $M->get_journal_items($id, $journal['fg_post']);
            $t_debit = $t_credit = 0;
            if(count($list)){
                foreach($list as $l){
                    $t_debit += $l->debit;
                    $t_credit += $l->credit;
                }
            }
        }else{
            $row = json_decode(json_encode($data));
        }
    }


    if($status){
        $id = '';
    }
}else{
    if($id){
        $row = $M->get_journal($id);
        $list = $M->get_journal_items($id);
        $t_debit = $t_credit = 0;
        if(count($list)){
            foreach($list as $l){
                $t_debit += $l->debit;
                $t_credit += $l->credit;
            }
        }
    }
}

// TODO: get from mapping
$banks = $M->query("SELECT * FROM mastercoa WHERE (post_to = '10100' OR post_to = '11000') AND fg_posting = '1'")->result();

$ch = new Coa_helper();
$acct_period = $ch->get_available_period();


// CONTROLLER END


?>
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<form method='post' name='form' enctype='multipart/form-data' action="" onsubmit='return validasi_bayar();'>
    <div class='box'>
        <div class='box-body'>
            <div class='row'>
                <div class='col-md-3'>
                    <div class='form-group' style="display:none">
                        <label>Periode Akuntansi</label>
                        <input type='text' id='periodpicker_' readonly class='form-control' name='period' autocomplete="off" 
                               placeholder='MM/YYYY' value='<?=isset($row)?$row->period:''?>'>
                        <?php /*<select id='period' class='form-control select2' name='period'>
                            <option value="" disabled selected>Pilih Periode</option>
                            <?php if(@$row->fg_post):?>
                                <option value="<?=$row->period?>" selected><?=$row->period?></option>
                            <?php else:?>
                                <?php if(@$row->period):?>
                                    <option value="<?=$row->period?>" selected><?=$row->period?></option>
                                <?php endif;?>
                            <?php foreach($acct_period as $_id):?>
                                <option value="<?=$_id?>" <?php //=(@$row->period==$_id) ? 'selected':''?> ><?=$_id?></option>
                            <?php endforeach;?>
                            <?php endif;?>
                        </select>*/?>
                    </div>
                    <div class='form-group'>
                        <label>Tipe Jurnal</label>
                        <select id='type_journal' class='form-control ' name='type_journal'>
                            <option value="<?=Config::$journal_usage['PAYMENT']?>" ><?=Config::$journal_type[Config::$journal_usage['PAYMENT']]?></option>
                        </select>
                    </div>
	

                     <div class='form-group'>
                         <label>Tanggal Cek/Giro</label>
                         <input type='text'  id='datepicker2' autocomplete="off" class='form-control' name='d_cek_giro'
                                placeholder='Masukkan Tanggal Dokumen' value='<?php 
									if(isset($row)){
										$check_date = date('Y-m-d', strtotime($row->d_cek_giro));
										if($check_date < '1990-01-01'){
											echo '';
										}else{
											echo date('d M Y', strtotime($row->d_cek_giro));
										}
									}else{
										echo '';
									}
								
								?>'>
                     </div>
                     <div class='form-group' >
                         <label>Cek/Giro</label>
                     
                         <input type='text'  class='form-control' name='v_cek_giro'
                                placeholder='Masukkan Referensi Dokumen' value='<?=isset($row)?$row->v_cek_giro:''?>' >

                  
                     </div>
	
					
					
					
                </div>
                 <div class='col-md-3'>
                     <div class='form-group'>
                         <label>Nomor Jurnal</label>
                         <input type='text' id='id_journal' class='form-control' name='id_journal' readonly
                                placeholder='(Auto)' value='<?=(isset($row) and isset($row->id_journal))?$row->id_journal:''?>'>
                     </div>
                     <div class='form-group'>
                         <label>Tanggal Jurnal</label>
                         <input type='text' onchange="getPeriodAccounting(this)" id='datepicker1' autocomplete="off" class='form-control' name='date_journal'
                                placeholder='Masukkan Tanggal Dokumen' value='<?=isset($row)?date('d M Y', strtotime($row->date_journal)):''?>'>
                     </div>
                     <div class='form-group input-bpb' >
                         <label>Referensi Dokumen</label>
                         <div class="input-group" id='klik_saya'>
                         <input type='text' id='reff_doc' class='form-control' name='reff_doc'
                                placeholder='Masukkan Referensi Dokumen' value='<?=isset($row)?$row->reff_doc:''?>' readonly>
                         <a onclick="modal_bpb()" style="cursor: pointer" class="input-group-addon" >...</a>
                         </div>
                     </div>

                     <div class='form-group'>
                         <label>L/C</label>
                         <input type='text' id='reff_doc2' readonly class='form-control' name='reff_doc2'
                                placeholder='L/C' value='<?=isset($row)?$row->reff_doc2:''?>'>
                     </div>
                         <input type='hidden' id='array_detail' readonly class='form-control' name='array_detail'
                                placeholder='L/C' >
                 				 

                 </div>
                <div class='col-md-3'>
                    <div class='form-group'>
                        <input type='hidden' id='' name='fg_intercompany' value="0">
                        <label><input type='checkbox' id='fg_intercompany' class='' onclick="toggleInterCompany()" name='fg_intercompany' value="1"
                                <?=(isset($row->fg_intercompany) and $row->fg_intercompany=='1')?'checked':''?>> Inter-company</label>
                        <input type='text' id='id_intercompany' class='form-control' name='id_intercompany'
                               placeholder='IC Partner' value='<?=isset($row)?$row->id_intercompany:''?>'
                            <?=(isset($row->fg_intercompany) and $row->fg_intercompany=='1')?'':'readonly'?>>
                    </div>
                    <div class='form-group'>
                        <label>Kas/Bank *</label>
                        <select id='bank' class='form-control select2' name='bank'>
                            <option value="">-- Pilih Kas/Bank --</option>
                            <?php foreach($banks as $_b):?>
                            <option value="<?=$_b->id_coa?>" ><?=$_b->nm_coa?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class='form-group'>
                        <label>Nilai</label>
                        <input type='text' readonly id='nilai' class='form-control text-right' name='nilai'
                               placeholder='Nilai Pembayaran' value=''>
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class="form-group">
                        <a href="#" class="btn btn-default payment_info form-control" onclick="show_payment(); return  false;">Show Payment History</a>
                    </div>
                    <?php if($id):?>
                    <div class='form-group'>
                        <label>Status Posting: </label><br><?=(isset($row) and $row->fg_post)? 'POSTED':'PARKED'?><br>
                        <label>Create date: </label><br>
                        <?=($row->dateadd) ? $row->dateadd.' ('.$row->useradd.')' : '-'?><br>
                        <label>Edit date: </label><br>
                        <?=($row->dateedit) ? $row->dateedit.' ('.$row->useredit.')' : '-'?><br>
                        <label>Post date: </label><br>
                        <?=($row->date_post) ? $row->date_post.' ('.$row->user_post.')' : '-'?>
                    </div>
                    <?php endif;?>
                </div>
                <div class="col-md-12">
                    <input type='hidden' name='mode' value='<?=$id ? 'update' : 'save';?>'>
                    <?php if(!isset($row) or ($id==false)):?>
                    <button  name='submit'  class='btn btn-primary validasi_proses'>Simpan</button>
                    <?php endif;?>
                    <?php if(isset($row) and !@$row->fg_post and $id):?>
                    <button type='submit' name='submit' class='btn btn-primary validasi_proses'>Simpan</button>
                    <button type='submit' name='posting' class='btn btn-primary validasi_proses'>Posting</button>
                    <?php endif;?>
					 <a href="#" onclick="Call_Detail_Payment_2()" data-toggle='modal' data-target='#myModalLIST_2' class='btn btn-info validasi_proses'>Detail List Payment</a>
                </div>
            </div>
        </div>
    </div>

</form>

<?php if($id):?>
    <?php include("journal_entry_form_item.inc_cashbank.php"); ?>
<?php endif;?>
<div class="modal fade " id="modalPayment"  role="dialog" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Payment History</h4>
            </div>
            <div class="modal-body">
                <div class="paymentresult">
                    <table class="table">
                        <thead>
                        <tr>
                        <th>Date</th>
                        <th>PO No</th>
                        <th>BPB No</th>
                        <th>Group</th>
                        <th>Paid Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalBpb" data-keyboard="false" role="dialog" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Lookup Refference</h4>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#tabBpb" aria-controls="home" role="tab" data-toggle="tab">Payment</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="tabBpb">
                        <div class='form-group'>
                            <div class="row">
                                <div class="col-md-3">
                                    <label>No Payment</label>
                                    <input type='text' class='form-control' id="bpbNo" >
                                </div>
                                <div class="col-md-3">
                                    <label for="">&nbsp;</label><br>
                                    <a onclick="lookup_bpb()" class="btn btn-primary">Lookup</a>
                                </div>
                            </div>
                            <div class="row hidden">
                                <div class="col-md-12">
                                    <em>Gunakan % untuk pencarian wildcard</em>
                                </div>
                            </div>
                        </div>
                        <div class="bpbresult">
                            <table class="table">
                                <thead>
                                <th>Payment No</th>
<!--                                <th>BPB No</th>-->
<!--                                <th>BPB No Internal</th>-->
<!--                                <th>PO</th>-->
                                <th>Supplier</th>
								<th>Jatuh Tempo</th>
                                <th>Amount</th>
                                <th>Action</th>
                                </thead>
                                <hr>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>


<div id="myModalLIST_2" class="modal fade " role="dialog">
  <div class="modal-dialog modal-dialog modal-lg" role="document">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="HeaderModal_2">List Bpb</h4>
      </div>
      <div class="modal-body ">
		<div id="ListModal_2">
		
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
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
<script  src="js/Payment.js"></script>
<script src="../appr/js/ApprovalPage.js"></script>
<script src="js/CashBank.js"></script>
<script>
    function modal_ref(){
        var tipe_jurnal = $('#type_journal').val();

        switch (tipe_jurnal){
            case "<?=Config::$journal_usage['PAYMENT']?>":
                modal_po();
                break;
            default:
                if(tipe_jurnal==""){
                    alert("Pilih tipe jurnal terlebih dahulu");
                }else {
                    alert("Pilihan tidak tersedia untuk tipe jurnal tersebut");
                }
        }
    }
    function modal_bpb(){
		$('#modalBpb').modal({backdrop: 'static', keyboard: false});
		if($split_nya[1]){
			alert("Data Sudah Ada!");
			return false;
		}
        $('#modalBpb').modal('show');
        $("#modalBpb .bpbresult table tbody").html('');
        $("#bpbNo").val('');
        $("#bpbNoInternal").val('');
    }
    function lookup_bpb(){
		
        var request = $.ajax
        ({  type: "POST",
            url: "ajax_fin.php?mdajax=lookup_rekap_ap",
            data: "approved=1&no_payment=" +$("#bpbNo").val(),
            dataType: "json",
            async: false
        }).responseText;

        request = JSON.parse(request);

        var bpb_list = [];
        if(request.status == true){
            bpb_list = request.data;
            var rows = '';
            if(bpb_list.length) {
                for (var i in bpb_list) {
                    rows += '<tr>';  
                    //rows += '<td>' + bpb_list[i].no_payment + '</td>';
					rows +="<td><a href='#' data-toggle='modal' data-target='#myModalLIST' onclick='getListDataRekap("+'"'+bpb_list[i].no_payment+'"'+")' >"+bpb_list[i].no_payment+"</a></td>";	
//                    rows += '<td>' + bpb_list[i].bpbno + '</td>';
//                    rows += '<td>' + bpb_list[i].bpbno_int + '</td>';
//                    rows += '<td>' + bpb_list[i].pono + '</td>';
                    rows += '<td>'+ bpb_list[i].nm_supplier + '</td>';
					 rows += '<td>'+ bpb_list[i].terms_of_pay + '</td>';
                    rows += '<td >'+ bpb_list[i].curr +" <p align='right' style='margin-top:-20px'>"+  formatNumber(bpb_list[i].bpb_amount) + '</p></td>';
//                    rows += '<td class="text-right">' + formatNumber(bpb_list[i].po_amount) + '</td>';
//                    rows += '<td class="text-right">' + formatNumber(bpb_list[i].paid_amount) + '</td>';
//                    rows += '<td class="text-right">' + formatNumber(bpb_list[i].outstanding_amount) + '</td>';
                    rows += '<td><a onclick="select_bpb('+ "'" + bpb_list[i].curr + "'" + ',' + "'" + bpb_list[i].no_payment + "'" + ', '+ "'" + bpb_list[i].bpb_amount + "'" +')" class="btn btn-info">Choose</td>';
                    rows += '</tr>';
                }
            }else{
                rows = '<tr><td colspan="4" class="text-center"><em>--Bpb not found--</em></td></tr>';
            }
            $("#modalBpb .bpbresult table tbody").html(rows);
        }else{
            alert(request.message);
        }
    }
    function formatNumber (num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
    }
    function select_po(po, amount){
        $('#po').val(po);
        $('#bpb').val('');
        $('#nilai').val(amount);
        $('.input-bpb').addClass('hidden');
        $('.input-po').removeClass('hidden');
        $('.input-ref').addClass('hidden');
//        $('#modalPo').modal('hide');
        $('#modalBpb').modal('hide');

    }
    function select_bpb(curr,bpb, amount){
        $('#reff_doc').val(bpb);
//        $('#po').val('');
		$myPayment = bpb;
		$onload = 1;
		Call_Detail_Payment_2();
        $('#nilai').val(curr+" "+amount);
		
//        $('.input-po').addClass('hidden');
//        $('.input-bpb').removeClass('hidden');
//        $('.input-ref').addClass('hidden');
        $('#modalBpb').modal('hide');
    }
    function show_payment(){
//        var bpb = $('#bpb').val();
//        var po = $('#po').val();
        var rekap = $('#reff_doc').val();
        var data = "";

        if(!rekap){
            alert("Mohon pilih reference terlebih dahulu");
            return;
        }

//        if(bpb){
//            data = "bpbno_int="+bpb;
//        }else if(po){
//            data = "pono="+po;
//        }
        data = "rekap="+rekap;

        var request = $.ajax
        ({  type: "POST",
            url: "ajax_fin.php?mdajax=lookup_payment",
            data: data,
            dataType: "json",
            async: false
        }).responseText;

        request = JSON.parse(request);

        var payments = [];
        if(request.status){
            payments = request.data;
        }

        var rows = '';
        if(payments.length) {
            for (var i in payments) {
                rows += '<tr>';
                rows += '<td>' + payments[i].date_journal + '</td>';
                rows += '<td>' + payments[i].pono + '</td>';
                rows += '<td>' + payments[i].bpbno_int + '</td>';
                rows += '<td>' + payments[i].nm_coa + '</td>';
                rows += '<td>' + payments[i].amount + '</td>';
                rows += '</tr>';
            }
        }else{
            rows = '<tr><td colspan="5" class="text-center"><em>--Payment not found--</em></td></tr>';
        }
        $("#modalPayment .paymentresult table tbody").html(rows);

        $('#modalPayment').modal('show');

        return false;
    }
    function toggleInterCompany(){
        if($('#fg_intercompany').is(':checked')){
            $('#id_intercompany').attr('readonly', false);
        }else{
            $('#id_intercompany').attr('readonly', true);
        }
    }
    function validasi_bayar() {
		 $("#datepicker1").attr("disabled",false);
		 //return false;
		var bank = document.form.bank.value;
        var period = document.form.period.value;  
        var type_journal = document.form.type_journal.value;
        var date_journal = document.form.date_journal.value;
        var reff_doc = document.form.reff_doc.value;
       // var po = document.form.po.value;
       // var bpb = document.form.bpb.value;
	  
	  // $("#datepicker1_new").attr("disabled",false);
	 // return false;
        var amount = document.form.nilai.value;
        var bank = document.form.bank.value;
convert_totext().then(function(responnya) {
	$("#array_detail").val(responnya);
	console.log(responnya);

});
		if (!bank || bank == '' || bank < 100 ) {
            alert('Error: Id Coa Kas/ Bank Harus diisi');
            document.form.bank.focus();
			return false;
        }

        if (period == '') {
            alert('Error: Periode Akuntansi tidak boleh kosong');
            document.form.period.focus();
            valid = false;
			return false;
        } else if (type_journal == '') {
            alert('Error: Tipe Jurnal tidak boleh kosong');
            document.form.type_journal.focus();
            valid = false;
			return false;
        } /*else if (num_journal == '') {
            alert('Error: Nomor Dokumen tidak boleh kosong');
            document.form.num_journal.focus();
            valid = false;
        }*/ else if (date_journal == '') {
            alert('Error: Tanggal Dokumen tidak boleh kosong');
            document.form.date_journal.focus();
            valid = false;
			return false;
        }   /*else if (po == '' && bpb == '') {
            alert('Error: PO dan BPB tidak boleh kosong');
            valid = false;
        }   */if (bank && (amount == '' || amount == "0")) {
            alert('Error: Nilai pembayaran tidak boleh kosong');
            valid = false;
			return false;
        }   /*else if (bank == '') {
            alert('Error: Bank tidak boleh kosong');
            valid = false;
        }*/
        <?php /*if(isset($t_debit)):?>
        else if ((<?=@$t_debit?> + <?=@$t_credit?>) == 0) {
            alert('Error: Nilai Debit dan Kredit tidak boleh kosong');
            valid = false;
        } else if (<?=@$t_debit?> != <?=@$t_credit?>) {
            alert('Error: Debit dan Kredit harus balance');
            valid = false;
        }
        <?php endif;*/?>
        else if (<?=(isset($row) and isset($row->fg_post) and $row->fg_post)?'true':'false'?>) {
            alert('Error: Journal sudah di posting');
            valid = false;
        } else valid = true;

        return valid;
    }

    var acct_periods = <?=json_encode($acct_period);?>;
    function check_period(period){
        if(period==''){
            return true;
        }

        var found = false;
        for(var i in acct_periods){
            if(acct_periods[i] == period){
                found = true;
                break;
            }
        }

        return found;
    }
    $(document).ready(function(){
        $("#periodpicker").datepicker( {
            format: "mm/yyyy",
            viewMode: "months",
            minViewMode: "months",
            autoclose: true,
        });/* .on('changeDate', function(e) {
            if(!check_period(e.format())){
                alert("Periode Akuntansi "+e.format()+" belum dibuka");
                $("#periodpicker").val('');
            }
        }); */
    });
</script>