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
require_once "../log_activity/log.php";
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
	
	
		$check_pono = check_null_pono_bpb($id);
		
	if($check_pono == '0'){
$sql="	
		SELECT MM.* FROM(
		SELECT MASTER.* FROM (
				SELECT 
				 bpb.id SAVE_id_bpb
				,poh.id SAVE_id_po
				,poh.pono SAVE_pono
				,bpb.id_item SAVE_id_item
				,ifnull(bpb.qty,0) - ifnull(bpb.qty_reject,0)SAVE_qty
				,poi.price SAVE_price
				,((ifnull(bpb.qty,0) - ifnull(bpb.qty_reject,0))*poi.price)SAVE_amount_ori
				,poi.id SAVE_id_po_det
				,bpb.id_supplier SAVE_id_supplier
				,'3' SAVE_type_bpb		
				,poh.ppn SAVE_ppn
                ,bpb.bpbno
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
				,mi.n_code_category
                ,mg.id id_group
                ,poi.qty qty_po
                ,poi.id id_po_det
				,poi.id_gen
				,((ifnull(poh.ppn,0)/100)*((bpb.qty*poi.price)) + bpb.qty*poi.price )nilai
				,'Y' is_jasa
            FROM 
                bpb 
                LEFT JOIN mastersupplier ms ON bpb.id_supplier = ms.Id_Supplier
                LEFT JOIN masteritem mi ON bpb.id_item = mi.id_item
                LEFT JOIN mastergroup mg ON mi.matclass = mg.nama_group
				LEFT JOIN po_item poi ON poi.id_gen = bpb.id_item AND bpb.id_jo = poi.id_jo
				LEFT JOIN po_header poh ON poh.id = poi.id_po AND poh.id_supplier = bpb.id_supplier
				
			WHERE 
               bpb.id = '$id' AND poi.cancel = 'N' AND POH.pono IS NOT NULL group by bpb.id
				UNION ALL
            SELECT 
				 bpb.id SAVE_id_bpb
				,poh.id SAVE_id_po
				,poh.pono SAVE_pono
				,bpb.id_item SAVE_id_item
				,ifnull(bpb.qty,0) - ifnull(bpb.qty_reject,0)SAVE_qty
				,if(SUBSTR(bpb.bpbno_int,1,3)='FG/',poi.price,bpb.price )SAVE_price
				,if(SUBSTR(bpb.bpbno_int,1,3)='FG/',(ifnull(bpb.qty,0) - ifnull(bpb.qty_reject,0))*poi.price,bpb.qty*bpb.price )SAVE_amount_ori
				,poi.id SAVE_id_po_det
				,bpb.id_supplier SAVE_id_supplier
				,if(SUBSTR(bpb.bpbno_int,1,3)='FG/','4',if(SUBSTR(bpb.bpbno_int,1,3)='GEN','2','1'))SAVE_type_bpb
				,poh.ppn SAVE_ppn
                ,bpb.bpbno
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
                ,if(SUBSTR(bpb.bpbno_int,1,3)='FG/',poi.price,bpb.price )price
                ,ms.Supplier
                ,ms.supplier_code
                ,ms.vendor_cat
                ,mi.itemdesc
                ,mi.mattype
                ,mi.matclass
				,mi.n_code_category
                ,mg.id id_group
                ,poi.qty qty_po
                ,poi.id id_po_det
				,poi.id_gen
				,((ifnull(poh.ppn,0)/100)*((bpb.qty*bpb.price)) + bpb.qty*bpb.price )nilai
				,'N' is_jasa
            FROM 
                bpb
                LEFT JOIN mastersupplier ms ON bpb.id_supplier = ms.Id_Supplier
                LEFT JOIN masteritem mi ON bpb.id_item = mi.id_item
                LEFT JOIN mastergroup mg ON mi.matclass = mg.nama_group
				LEFT JOIN po_header poh ON poh.pono = bpb.pono
				LEFT JOIN po_item poi ON poi.id_po = poh.id
            WHERE 
                bpb.id = '$id'  group by bpb.id

				)MASTER WHERE MASTER.is_jasa = '$is_jasa')MM";		
				
	}
	else{
		IF($is_jasa == "Y"){
	$sql="SELECT MASTER.* FROM (
				SELECT 
				 bpb.id SAVE_id_bpb
				,poh.id SAVE_id_po
				,poi.id idpoi
				,poi.id_gen itempoi
				,bpb.id_item
				,poh.pono SAVE_pono
				,bpb.id_item SAVE_id_item
				,ifnull(bpb.qty,0) - ifnull(bpb.qty_reject,0)SAVE_qty
				,poi.price SAVE_price
				,((ifnull(bpb.qty,0) - ifnull(bpb.qty_reject,0))*poi.price)SAVE_amount_ori
				,poi.id SAVE_id_po_det
				,bpb.id_supplier SAVE_id_supplier
				,'3' SAVE_type_bpb		
				,poh.ppn SAVE_ppn
                ,bpb.bpbno
				,bpb.bpbno_int
				,bpb.id id_bpb
				,poh.pono
				,poh.id_supplier supplier_po
				,poi.price price_po
				,poh.ppn
                ,bpb.bpbdate
                ,bpb.id_supplier
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
				,mi.n_code_category
                ,mg.id id_group
                ,poi.qty qty_po
                ,poi.id id_po_det
				,poi.id_gen
				,((ifnull(poh.ppn,0)/100)*((bpb.qty*poi.price)) + bpb.qty*poi.price )nilai
				,'Y' is_jasa
            FROM 
                bpb
                LEFT JOIN mastersupplier ms ON bpb.id_supplier = ms.Id_Supplier
                LEFT JOIN masteritem mi ON bpb.id_item = mi.id_item
                LEFT JOIN mastergroup mg ON mi.matclass = mg.nama_group
				LEFT JOIN po_header poh ON poh.pono = bpb.pono
				LEFT JOIN po_item poi ON poi.id_po = poh.id AND poi.id_gen = bpb.id_item
            WHERE 
                bpb.id = '$id'  group by bpb.id )MASTER";		
			
		}ELSE{
$sql="SELECT MASTER.* FROM (
            SELECT 
				 bpb.id SAVE_id_bpb
				,poh.id SAVE_id_po
				,poh.pono SAVE_pono
				,bpb.id_item SAVE_id_item
				,ifnull(bpb.qty,0) - ifnull(bpb.qty_reject,0)SAVE_qty
				,if(SUBSTR(bpb.bpbno_int,1,3)='FG/',poi.price,bpb.price )SAVE_price
				,if(SUBSTR(bpb.bpbno_int,1,3)='FG/',(ifnull(bpb.qty,0) - ifnull(bpb.qty_reject,0))*poi.price,bpb.qty*bpb.price )SAVE_amount_ori
				,poi.id SAVE_id_po_det
				,bpb.id_supplier SAVE_id_supplier
				,if(SUBSTR(bpb.bpbno_int,1,3)='FG/','4',if(SUBSTR(bpb.bpbno_int,1,3)='GEN','2','1'))SAVE_type_bpb
				,poh.ppn SAVE_ppn
                ,bpb.bpbno
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
                ,if(SUBSTR(bpb.bpbno_int,1,3)='FG/',poi.price,bpb.price )price
                ,ms.Supplier
                ,ms.supplier_code
                ,ms.vendor_cat
                ,mi.itemdesc
                ,mi.mattype
                ,mi.matclass
				,mi.n_code_category
                ,mg.id id_group
                ,poi.qty qty_po
                ,poi.id id_po_det
				,poi.id_gen
				,((ifnull(poh.ppn,0)/100)*((bpb.qty*bpb.price)) + bpb.qty*bpb.price )nilai
				,'N' is_jasa
            FROM 
                bpb
                LEFT JOIN mastersupplier ms ON bpb.id_supplier = ms.Id_Supplier
                LEFT JOIN masteritem mi ON bpb.id_item = mi.id_item
                LEFT JOIN mastergroup mg ON mi.matclass = mg.nama_group
				LEFT JOIN po_header poh ON poh.pono = bpb.pono
				LEFT JOIN po_item poi ON poi.id_po = poh.id
            WHERE 
               bpb.id = '$id'  group by bpb.id )MASTER";

			
		}			
		
	}		
		
/* 		echo "<pre>".$sql."</pre>";
		die(); */
			$inject_join = "INNER JOIN(SELECT * FROM fin_journal_d WHERE id_bpb ='$id' AND credit > 0 AND id_bpb IS NOT NULL)FD 
				ON FD.reff_doc = MA.bpbno_int
				INNER JOIN(SELECT n_id,v_nojournal FROM fin_status_journal_ap )AP ON AP.v_nojournal = FD.id_journal";
		
		$tmp_query = "SELECT MA.*,AP.n_id id_payment,FD.row_id row_kb ,FD.id_journal no_kb FROM($sql)MA $inject_join ";

        $bpb_detail = $this->query($tmp_query)->result();
/*  		echo "<pre>";
		print_r($bpb_detail);
		echo "</pre>";
		die(); 	 */			
        return array(
            'status' => true,
            'errcode' => null,
            'message' => 'BPB number found',
            'data'  => $bpb_detail
        );
    }
	
	
	
	
	
	
   public function check_bppb($id,$no_rekap,$type_nya, $checking = TRUE){

        $bpb_detail = $this->query("SELECT 
				 bpb.id SAVE_id_bpb
				 ,AP.n_id id_payment
				,poh.id SAVE_id_po
				,poh.pono SAVE_pono
				,bpb.id_item SAVE_id_item
				,ifnull(bpb.qty,0)SAVE_qty
				,poi.price SAVE_price
				,((ifnull(bpb.qty,0))*poi.price)SAVE_amount_ori
				,poi.id SAVE_id_po_det
				,bpb.id_supplier SAVE_id_supplier
				,'3' SAVE_type_bpb		
				,poh.ppn SAVE_ppn
                ,bpb.bppbno
				,bpb.bppbno_int
				,bpb.id id_bpb
				,poh.pono
				,poh.id_supplier supplier_po
				,poi.price price_po
				,poh.ppn
                ,bpb.bppbdate
                ,bpb.id_supplier
                ,bpb.id_item
                ,ifnull(bpb.qty,0)qty
                ,bpb.unit
                ,bpb.curr
                ,bpb.price
                ,ms.Supplier
                ,ms.supplier_code
                ,ms.vendor_cat
                ,mi.itemdesc
                ,mi.mattype
                ,mi.matclass
				,mi.n_code_category
                ,mg.id id_group
                ,poi.qty qty_po
                ,poi.id id_po_det
				,poi.id_gen

				,((ifnull(poh.ppn,0)/100)*((bpb.qty*poi.price)) + bpb.qty*poi.price )nilai
            FROM 
                bppb bpb
                LEFT JOIN mastersupplier ms ON bpb.id_supplier = ms.Id_Supplier
                LEFT JOIN masteritem mi ON bpb.id_item = mi.id_item
                LEFT JOIN mastergroup mg ON mi.matclass = mg.nama_group
				INNER JOIN po_item poi ON poi.id_gen = mi.id_gen AND bpb.id_jo = poi.id_jo
				LEFT JOIN po_header poh ON poh.id = poi.id_po AND poh.id_supplier = bpb.id_supplier
				INNER JOIN(SELECT * FROM fin_journal_d WHERE id_bppb ='$id' AND credit > 0 AND id_bppb IS NOT NULL)FD 
				ON FD.id_bppb = bpb.id 
				LEFT JOIN(SELECT n_id,v_nojournal FROM fin_status_journal_ap )AP ON AP.v_nojournal = FD.id_journal								
				WHERE bpb.id = '$id' AND POH.app  ='A' AND POI.cancel !='C'								
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

	
    public function delete_detail_journal_d($id)
    {
		//{$d['id_journal']}
		$sql = "DELETE FROM fin_journal_d WHERE id_journal = '{$id}'";
		insert_log_nw($sql,$_SESSION['username']);
		
        $this->query($sql);

//        return $id_journal; //$this->conn->insert_id; $d['period']
    }	
	
    public function save($d)
    {
		$pecah_period 	= explode("/",$d['period']);
		$per_month		= sprintf("%02d", $pecah_period[0]);
		$d['period']	= $per_month."/".$pecah_period[1];
		
		
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
		insert_log_nw($sql,$_SESSION['username']);
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
		insert_log_nw($sql,$_SESSION['username']);
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
		insert_log_nw($sql,$_SESSION['username']);
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
			INNER JOIN mapping_coa_gen MCG ON MCG.n_code_category = MI.n_code_category 
			INNER JOIN mastercoa COA_GR_DEB 
				ON COA_GR_DEB.id_coa = MCG.id_coa_debit_gr
			INNER JOIN mastercoa COA_GR_CRE 
				ON COA_GR_CRE.id_coa = MCG.id_coa_credit_gr
			INNER JOIN mastercoa COA_IR_DEB 
				ON COA_IR_DEB.id_coa = MCG.id_coa_debit_ir
			INNER JOIN mastercoa COA_IR_CRE 
				ON COA_IR_CRE.id_coa = MCG.id_coa_credit_ir
			WHERE BPB.bpbno = '{$bpb['bpbno']}' and MCG.n_vendor_cat = '{$bpb['vendor_cat']}'  GROUP BY BPB.bpbno LIMIT 1";
			
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
			INNER JOIN mapping_coa_gen MCG ON MCG.n_code_category = MI.n_code_category  
			INNER JOIN mastercoa COA_GR_DEB 
				ON COA_GR_DEB.id_coa = MCG.id_coa_debit_gr
			INNER JOIN mastercoa COA_GR_CRE 
				ON COA_GR_CRE.id_coa = MCG.id_coa_credit_gr
			INNER JOIN mastercoa COA_IR_DEB 
				ON COA_IR_DEB.id_coa = MCG.id_coa_debit_ir
			INNER JOIN mastercoa COA_IR_CRE 
				ON COA_IR_CRE.id_coa = MCG.id_coa_credit_ir
			WHERE MCG.n_code = 'UTL' and MCG.n_vendor_cat = '{$bpb['vendor_cat']}'  GROUP BY BPB.bpbno LIMIT 1";
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
	
    public function get_id_coa_payment_from_bpb_fg($bpb){

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
		LEFT JOIN(
			SELECT MY.itemname,POI.id_gen,POH.app,POI.cancel,BPB_NYA.* FROM(
			SELECT * FROM bpb WHERE bpbno_int = '{$bpb['bpbno_int']}')BPB_NYA
			LEFT JOIN (
			
			SELECT * FROM masterstyle )MY ON BPB_NYA.id_so_det = MY.id_so_det
			LEFT JOIN(
			
			SELECT * FROM so_det)SOD ON SOD.id = MY.id_so_det
			LEFT JOIN(
			SELECT * FROM jo_det)JOD ON JOD.id_so = SOD.id_so
			LEFT JOIN(
			SELECT * FROM po_item)POI ON POI.id_jo = JOD.id_jo
			INNER JOIN(
			SELECT * FROM po_header)POH ON POH.id = POI.id_po AND BPB_NYA.pono = POH.pono
			INNER JOIN(
			 SELECT * FROM masteritem
			)MI ON MI.id_item = POI.id_gen)FG ON BPB.id = FG.id		
			INNER JOIN masteritem MI ON FG.id_gen = MI.id_item 
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
    public function get_id_coa_payment_from_bpb_ret($bpb){
		$q= "       
            SELECT 
              mp.gr_d id_persediaan, mc1.nm_coa nm_persediaan
              ,mp.gr_k id_gr, mc2.nm_coa nm_gr
              ,mp.ir_k id_hutang, mc3.nm_coa nm_hutang
            FROM mapping_coa mp
            LEFT JOIN mastercoa mc1 ON mc1.id_coa = mp.gr_d
            LEFT JOIN mastercoa mc2 ON mc2.id_coa = mp.gr_k
            LEFT JOIN mastercoa mc3 ON mc3.id_coa = mp.ir_k
            WHERE 
            id_group = '{$bpb['id_group']}' and vendor_cat = '{$bpb['vendor_cat']}' ";
        $mapping_coa = $this->query($q)->row();


        $persediaan = array(
            'id' => $mapping_coa->id_hutang,
            'nm' => $mapping_coa->nm_hutang
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
				insert_log_nw($sql,$_SESSION['username']);
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
				insert_log_nw($sql,$_SESSION['username']);
                $this->query($sql);
            }
        }
    }

    public function check_rekap($array, $checking = TRUE)
    {
			
				$id_list_payment = $this->pecah_id($array);
		$q = "            	SELECT 
                    rap.v_listcode
                    ,rap.v_nojournal
                    ,rap.v_source
                    ,MAX(fjd.reff_doc) no_bpb
					,MAX(fjd.id_bpb) id_bpb
					,MAX(fjd.is_retur_bh) is_retur_bh
                FROM 
                    fin_status_journal_ap rap
                    LEFT JOIN fin_journal_d fjd ON rap.v_source = 'KB' AND rap.v_nojournal = fjd.id_journal
                WHERE n_id IN ($id_list_payment) AND (fjd.is_retur_bh) = 'N' AND fjd.id_bpb !='' AND fjd.id_bpb IS NOT NULL GROUP BY fjd.id_bpb
          UNION ALL      
SELECT 
                    rap.v_listcode
                    ,rap.v_nojournal
                    ,rap.v_source
                    ,MAX(fjd.reff_doc) no_bpb
					,MAX(fjd.id_bppb) id_bpb
					,MAX(fjd.is_retur_bh) is_retur_bh
                FROM 
                    fin_status_journal_ap rap
                    LEFT JOIN fin_journal_d fjd ON rap.v_source = 'KB' AND rap.v_nojournal = fjd.id_journal
                WHERE n_id IN ($id_list_payment) AND (fjd.is_retur_bh) = 'Y' AND fjd.id_bppb !='' AND fjd.id_bppb IS NOT NULL GROUP BY fjd.id_bppb                 ";

        $rekap = $this->query($q)->result();		
		$rekap[0]->v_source = 'KB';
		$rekap[0]->no_po = '';

	
		$q_ = "SELECT v_source,v_nojournal FROM fin_status_journal_ap WHERE n_id IN ($id_list_payment)";
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
	
	
    public function populasi_kontra_bon($array, $checking = TRUE)
    {

			
				$id_list_payment = $this->pecah_id($array);
		$q = "  SELECT AP.n_id,AP.v_nojournal,AP.v_source,AP.v_listcode,'N' kurang_utang
				,KB.credit nilai,KB.* FROM fin_journal_d KB INNER JOIN
				(SELECT * FROM fin_status_journal_ap WHERE n_id IN ($id_list_payment))AP
				ON KB.id_journal = AP.v_nojournal  AND id_coa IN(SELECT id_coa FROM mapping_utang WHERE 1=1)
				WHERE KB.credit > 0 AND id_coa IN (SELECT id_coa FROM mapping_utang WHERE 1=1) AND id_coa NOT IN ('15204','15207')
					UNION ALL 
				SELECT AP.n_id,AP.v_nojournal,AP.v_source,AP.v_listcode,'Y' kurang_utang,(KB.debit) nilai,KB.* FROM fin_journal_d 	KB INNER JOIN
				(SELECT * FROM fin_status_journal_ap WHERE n_id IN ($id_list_payment))AP
				ON KB.id_journal = AP.v_nojournal
				WHERE KB.debit > 0 AND id_coa IN(SELECT id_coa FROM mapping_utang WHERE 1=1)		
				AND id_coa NOT IN ('15204','15207')
				";
        $rekap = $this->query($q)->result();		
/* 		$rekap[0]->v_source = 'KB';
		$rekap[0]->no_po = ''; */
/*  echo "<pre>";
 print_r($q);
die();   */
	
		$q_ = "SELECT v_source,v_nojournal FROM fin_status_journal_ap WHERE n_id IN ($id_list_payment)";
        $_source = $this->query($q_)->result();
/* 		$ref = $_source[0]->v_nojournal;
		$src = $_source[0]->v_source; */
		//$source = $_source->v_source;
		//print_r($_source[0]->v_source);
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
	
	
    public function amount_kontra_bon($array, $checking = TRUE)
    {
			
				$id_list_payment = $this->pecah_id($array);
		$q = "SELECT   SUM(MASTER.amount)amount
 			,SUM(MASTER.ppn)ppn
 			,SUM(MASTER.pph)pph
  FROM (
    SELECT (SUM(IFNULL(M.amount_original,M.nilai)) + SUM(M.val_ppn) - SUM(M.val_pph) )amount
 			,SUM(M.val_ppn)ppn
			,SUM(M.val_pph)pph
			 FROM(
  SELECT KB.credit nilai
  			 ,(KB.amount_original * (ifnull(KB.n_ppn,0)/100))val_ppn
  			 ,(KB.amount_original * (IFNULL(IFNULL(MT_H.percentage,MT.percentage),0)/100))val_pph
				  ,KB.* FROM fin_journal_d KB 
				  INNER JOIN
				(SELECT * FROM fin_status_journal_ap WHERE n_id IN ($id_list_payment))AP
				ON KB.id_journal = AP.v_nojournal  AND id_coa IN(SELECT id_coa FROM mapping_utang WHERE 1=1)
				  LEFT JOIN mtax MT ON MT.idtax = KB.n_pph
				  LEFT JOIN(SELECT id_journal,n_pph FROM fin_journal_h WHERE type_journal = '14')FH ON KB.id_journal=FH.id_journal
				  LEFT JOIN mtax MT_H ON MT_H.idtax = FH.n_pph
				WHERE 1=1 AND KB.is_retur_bh = 'N' AND  KB.credit > 0 AND id_coa NOT IN ('15204','15207'))M
				UNION ALL

  SELECT (( SUM(IFNULL(M.amount_original,M. nilai)) + SUM(M.val_ppn) - SUM(M.val_pph) ) *-1  )amount
 			,(SUM(ifnull(M.val_ppn,0)) * -1)ppn
			,(SUM(ifnull(M.val_pph,0)) * -1)pph
			 FROM(
  SELECT KB.debit nilai
  			 ,(ifnull(KB.amount_original,0) * (ifnull(KB.n_ppn,0)/100))val_ppn
  			 ,(ifnull(KB.amount_original,0) * (IFNULL(IFNULL(MT_H.percentage,MT.percentage),0)/100))val_pph
				  ,KB.* FROM fin_journal_d KB 
				  INNER JOIN
				(SELECT * FROM fin_status_journal_ap WHERE n_id IN ($id_list_payment))AP
				ON KB.id_journal = AP.v_nojournal  AND id_coa IN(SELECT id_coa FROM mapping_utang WHERE 1=1)
				  LEFT JOIN mtax MT ON MT.idtax = KB.n_pph
				  LEFT JOIN(SELECT id_journal,n_pph FROM fin_journal_h WHERE type_journal = '14')FH ON KB.id_journal=FH.id_journal
				  LEFT JOIN mtax MT_H ON MT_H.idtax = FH.n_pph
				WHERE 1=1 AND KB.is_retur_bh = 'N' AND KB.debit > 0 AND id_coa NOT IN ('15204','15207'))M	)MASTER 		
				WHERE MASTER.amount IS NOT NULL
				";
        $rekap = $this->query($q)->result();		
/* 		$rekap[0]->v_source = 'KB';
		$rekap[0]->no_po = ''; */
/* 		echo "<pre>";
 print_r($q);
die();  */
	
		$q_ = "SELECT v_source,v_nojournal FROM fin_status_journal_ap WHERE n_id IN ($id_list_payment)";
        $_source = $this->query($q_)->result();
/* 		$ref = $_source[0]->v_nojournal;
		$src = $_source[0]->v_source; */
		//$source = $_source->v_source;
		//print_r($_source[0]->v_source);
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
public function pecah_id_journal($array){

		$count_n = count($array);
		$trigger = $count_n - 1;
		$id_list_payment_return = '';
		for($i=0;$i<$count_n;$i++){
			if($i == $trigger){
				$id_list_payment_return .= "'".$array[$i]->v_nojournal."'"; 
			}else{
				$id_list_payment_return .= "'".$array[$i]->v_nojournal."',";
			}
			
			
		}

		return $id_list_payment_return;
}

public function pecah_list_payment($array){

		$count_n = count($array);
		$trigger = $count_n - 1;
		$id_list_payment_return = '';
		for($i=0;$i<$count_n;$i++){
			if($i == $trigger){
				$id_list_payment_return .= "'".$array[$i]->v_nojournal."'"; 
			}else{
				$id_list_payment_return .= "'".$array[$i]->v_nojournal."',";
			}
			
			
		}

		return $id_list_payment_return;
}
public function pecah_id($array){

		$count_n = count($array);
		$trigger = $count_n - 1;
		$id_list_payment_return = '';
		for($i=0;$i<$count_n;$i++){
			if($i == $trigger){
				$id_list_payment_return .= "'".$array[$i]->id."'"; 
			}else{
				$id_list_payment_return .= "'".$array[$i]->id."',";
			}
			
			
		}

		return $id_list_payment_return;

	
	
}


public function populasi_rekap($array){
	$id_list_payment 	= $this->pecah_id($array);
		$sql = " 
			SELECT v_listcode id,n_id FROM fin_status_journal_ap WHERE n_id IN($id_list_payment)  GROUP BY v_listcode";
	$stmt = $this->query($sql)->result(); 
	$populasi_list_payment = "(".$this->pecah_id($stmt).")";
	return $populasi_list_payment;

}


public function get_populasi_id_journal($id){
	$sql = "SELECT A.v_listcode,A.v_nojournal,A.n_id,A.v_source FROM fin_status_journal_ap A WHERE A.n_id IN ($id) AND v_source ='KB'";
	$stmt = $this->query($sql)->result();
	$jl_h = count($stmt);

	if($jl_h > 0){
		
	$populasi_kb = $this->pecah_id_journal($stmt);

		$sql = "
SELECT   a.reff_doc bpb_ref
					,a.id_bpb
					,a.n_pph
					,'0' value_pph
					,'0' value_ppn
					,'0' percentage_h
					,(1*(a.debit)) amount_original
					,a.n_ppn
					, (1*(a.debit))nilai
					,a.qty
					,a.price
					,a.reff_doc2 journal_ref
					,a.row_id
					,a.description
					,b.fg_tax
					,'0' percentage
					,a.id_journal
					,a.id_coa
					,c.id_coa coa_utang
					,c.nm_coa nm_utang
					,a.nm_coa
					,a.curr
					,a.debit
					,a.is_retur_bh
					FROM fin_journal_d a
					INNER JOIN (SELECT id_journal,type_journal,fg_tax FROM fin_journal_h
						WHERE type_journal = '14'
					)b ON a.id_journal = b.id_journal 
					LEFT JOIN mapping_utang c ON c.id_coa = a.id_coa
					LEFT JOIN (SELECT idtax,percentage FROM mtax)MT ON MT.idtax = a.n_pph WHERE 1 =1
					AND a.is_retur_bh = 'N' AND a.id_journal IN ($populasi_kb)  AND 
					a.debit > 0 AND
					a.id_coa NOT IN('15204','15207') AND c.id_coa IS NOT NULL GROUP BY a.id_journal,a.row_id				
		";	
		
		$stmt = $this->query($sql)->result();

		$jl_h_kb = count($stmt);
		if($jl_h_kb > 0){
		

        return array(
            'status' => true,
			'key' => '1',
            'errcode' => null,
            'message' => 'List  found',
            'data'  => $stmt
        );	

		
		}else{
        return array(
            'status' => true,
			'key' => '0',
            'errcode' => null,
            'message' => 'List NOT found',
        );			
		}
		
	}else{
        return array(
            'status' => true,
			'key' => '0',
            'errcode' => null,
            'message' => 'List NOT found',
        );		
	}
	
/* 	
	$sql = "SELECT A,v_listcode,A.v_nojournal,A.n_id FROM fin_status_journal_ap A
				INNER JOIN(
					
				
				
				)B ON A.v_nojournal = B.id_journal
				
			
			WHERE A.n_id IN ($id);
	";
	
	 */
}

public function get_populasi_tambah_utang($id){
	$sql = "SELECT A.v_listcode,A.v_nojournal,A.n_id,A.v_source FROM fin_status_journal_ap A WHERE A.n_id IN ($id) AND v_source ='KB'";
	$stmt = $this->query($sql)->result();
	$jl_h = count($stmt);

	if($jl_h > 0){
		
	$populasi_kb = $this->pecah_id_journal($stmt);

		$sql = "
		SELECT       a.reff_doc bpb_ref
					,a.id_bpb
					,a.n_pph
					,MT_H.percentage percentage_h
					,( (ifnull(MT.percentage,0)/100) * ifnull(a.amount_original,0) )value_pph
					,( (ifnull(a.n_ppn,0)/100) * ifnull(a.amount_original,0) )value_ppn
					,a.credit amount_original
					,a.n_ppn
					,a.credit nilai
					,a.qty
					,a.price
					,a.reff_doc2 journal_ref
					,a.row_id
					,a.description
					,b.fg_tax
					,'0' percentage
					,a.id_journal
					,a.id_coa
					,c.id_coa coa_utang
					,c.nm_coa nm_utang
					,a.nm_coa
					,a.curr
					,a.credit
					,a.is_retur_bh
					FROM fin_journal_d a
					INNER JOIN (SELECT id_journal,type_journal,fg_tax,n_pph FROM fin_journal_h
						WHERE type_journal = '14'
					)b ON a.id_journal = b.id_journal 
					LEFT JOIN mapping_utang c ON c.id_coa = a.id_coa
					LEFT JOIN (SELECT idtax,percentage FROM mtax)MT ON MT.idtax = a.n_pph 
					LEFT JOIN (SELECT idtax,percentage FROM mtax)MT_H ON MT_H.idtax = b.n_pph
					
					WHERE 1=1 AND c.id_coa IS NOT NULL
					AND a.is_retur_bh = 'N' AND a.id_journal IN ($populasi_kb) AND
					a.credit > 0 AND  a.id_coa NOT IN('15204','15207') AND a.reff_doc IS NULL GROUP BY a.id_journal,a.row_id
		";	
		
		$stmt = $this->query($sql)->result();

		$jl_h_kb = count($stmt);
		if($jl_h_kb > 0){
		

        return array(
            'status' => true,
			'key' => '1',
            'errcode' => null,
            'message' => 'List  found',
            'data'  => $stmt
        );	

		
		}else{
        return array(
            'status' => true,
			'key' => '0',
            'errcode' => null,
            'message' => 'List NOT found',
        );			
		}
		
	}else{
        return array(
            'status' => true,
			'key' => '0',
            'errcode' => null,
            'message' => 'List NOT found',
        );		
	}
	
/* 	
	$sql = "SELECT A,v_listcode,A.v_nojournal,A.n_id FROM fin_status_journal_ap A
				INNER JOIN(
					
				
				
				)B ON A.v_nojournal = B.id_journal
				
			
			WHERE A.n_id IN ($id);
	";
	
	 */
}

public function get_total_pphnya($id){
	$sql = "SELECT A.v_listcode,A.v_nojournal,A.n_id,A.v_source FROM fin_status_journal_ap A WHERE A.n_id IN ($id) AND v_source ='KB'";
	$stmt = $this->query($sql)->result();
	$jl_h = count($stmt);

	if($jl_h > 0){
		
	$populasi_kb = $this->pecah_id_journal($stmt);

		$sql = "
SELECT 	 
		 MASTER.id_journal
		,SUM(ifnull(MASTER.value_pph,0))value_pph_d
		,SUM(ifnull(MASTER.value_ppn,0))value_ppn
		,SUM(ifnull(MASTER.amount_original,0))amount_original
		,( (ifnull(MAX(MASTER.percentage_h),0)/100) * SUM(ifnull(MASTER.amount_original,0)))value_pph_header
		,( ( (ifnull(MAX(MASTER.percentage_h),0)/100) * SUM(ifnull(MASTER.amount_original,0))) 
		
			+  SUM(ifnull(MASTER.value_pph,0))) value_pph
		,(SUM(ifnull(MASTER.nilai,0)  ) - ( (ifnull(MAX(MASTER.percentage_h),0)/100) * SUM(ifnull(MASTER.amount_original,0))) ) nilai
FROM(
		SELECT       a.reff_doc bpb_ref
					,a.id_bpb
					,a.n_pph
					,MT_H.percentage percentage_h
					,( (ifnull(MT.percentage,0)/100) * ifnull(a.amount_original,0) )value_pph
					,( (ifnull(a.n_ppn,0)/100) * ifnull(a.amount_original,0) )value_ppn
					,a.amount_original
					,a.n_ppn
					, (ifnull(a.amount_original,0) - ( (ifnull(MT.percentage,0)/100) * ifnull(a.amount_original,0) ) + ( (ifnull(a.n_ppn,0)/100) * ifnull(a.amount_original,0) )  )nilai
					,a.qty
					,a.price
					,a.reff_doc2 journal_ref
					,a.row_id
					,a.description
					,b.fg_tax
					,'0' percentage
					,a.id_journal
					,a.id_coa
					,c.id_coa coa_utang
					,c.nm_coa nm_utang
					,a.nm_coa
					,a.curr
					,a.credit
					,a.is_retur_bh
					FROM fin_journal_d a
					INNER JOIN (SELECT id_journal,type_journal,fg_tax,n_pph FROM fin_journal_h
						WHERE type_journal = '14'
					)b ON a.id_journal = b.id_journal 
					LEFT JOIN mapping_utang c ON c.id_coa = a.id_coa
					LEFT JOIN (SELECT idtax,percentage FROM mtax)MT ON MT.idtax = a.n_pph 
					LEFT JOIN (SELECT idtax,percentage FROM mtax)MT_H ON MT_H.idtax = b.n_pph
					
					WHERE 1=1
					AND a.is_retur_bh = 'N' AND a.id_journal IN ($populasi_kb) AND
					a.credit > 0 AND  a.id_coa NOT IN('15204','15207') AND a.reff_doc IS NOT NULL GROUP BY a.id_journal,a.row_id
					
	)MASTER					
		";	
		//echo "<pre>$sql</pre>";
		$stmt = $this->query($sql)->result();
		$jl_h_kb = count($stmt);
/* 		print_r($stmt[0]->value_pph);
		die(); */
		if($jl_h_kb > 0){
			if($stmt[0]->value_pph > 0){
				return array(
					'status' => true,
					'key' => '1',
					'errcode' => null,
					'message' => 'List  found',
					'data'  => $stmt
				);					
			}else{
				return array(
					'status' => true,
						'key' => '0',
					'errcode' => null,
					'message' => 'List Not found',
				);					
			}

		
		}else{
        return array(
            'status' => true,
			'key' => '0',
            'errcode' => null,
            'message' => 'List NOT found',
        );			
		}
		
	}else{
        return array(
            'status' => true,
			'key' => '0',
            'errcode' => null,
            'message' => 'List NOT found',
        );		
	}
	
/* 	
	$sql = "SELECT A,v_listcode,A.v_nojournal,A.n_id FROM fin_status_journal_ap A
				INNER JOIN(
					
				
				
				)B ON A.v_nojournal = B.id_journal
				
			
			WHERE A.n_id IN ($id);
	";
	
	 */
}


	public function get_pph_src($array){
		$id_list_payment = $this->pecah_id($array);
		$populasi_pph = $this->get_total_pphnya($id_list_payment);
		
		return $populasi_pph;
	}

	
public function get_tambah_utang($array){
		
		
		
		$id_list_payment = $this->pecah_id($array);
		$populasi_tambah_utang_nya = $this->get_populasi_tambah_utang($id_list_payment);

	return $populasi_tambah_utang_nya;
	
	}	
	
	public function Get_List_payment($array){
		$id_list_payment = $this->pecah_id($array);
		$populasi_utang_nya = $this->get_populasi_id_journal($id_list_payment);

	return $populasi_utang_nya;
	
	}
    public function insert_rekap_payment_new($id_journal, $coa_bank, $amount_bank__,$multiple_nya)
    {
		$populasi_kontra_bon  	= $this->populasi_kontra_bon($multiple_nya, false);
		$rekap_str 				= "";	
		$rekap_val				= "";		
		$amount  				= $this->amount_kontra_bon($multiple_nya, false);
		$row_id 				= 1;
			
			
			
        if($populasi_kontra_bon['status']){
            $dataBpb = array();
				foreach($populasi_kontra_bon['data'] as $_rekap){
					if($rekap_val != $_rekap->v_listcode){
						$rekap_val = $_rekap->v_listcode;
						$rekap_str .="$_rekap->v_listcode,";
					}
					$_debit = array(
						'row_id' => $row_id++,
						'id_journal' => $id_journal,
						'id_coa' => $_rekap->id_coa,
						'nm_coa' => $_rekap->nm_coa,
						'curr' => $_rekap->curr,
						'debit' =>  ($_rekap->kurang_utang == 'Y' ? 0:$_rekap->nilai),
						'credit' => ($_rekap->kurang_utang == 'Y' ? $_rekap->nilai:0),
						'description' => $_rekap->reff_doc.'-'.$_rekap->description,
						'id_reference' => $_rekap->id_item,
						'src_reference' => Config::$reference_src_key_item,
						'dateadd' => date('Y-m-d H:m:s'),
						'id_bpb' =>  $_rekap->id_bpb,
						'id_bppb' => $_rekap->id_bppb,
						'id_po' => $_rekap->id_po,
						'id_po_det' => $_rekap->id_po_det,
						'id_item' => $_rekap->id_item,
						'qty' => $_rekap->qty,
						'price' => $_rekap->price,
						'id_supplier' => $_rekap->id_supplier,
						'type_bpb' => $_rekap->type_bpb,
						'id_list_payment' => $_rekap->n_id,
						'reff_doc' => $_rekap->id_journal,
						'reff_doc2' => $_rekap->row_id,
						'is_retur_bh' => $_rekap->is_retur_bh,
						'amount_original' => $_rekap->amount_original,									
									'useradd' => $_SESSION['username'],
								);
								$dataBpb[] = $_debit;			
								
												
			}			
			$rekap_str = substr($rekap_str, 0, -1);
			//PPH 
			if($amount['data'][0]->pph > 0){
                $_credit = array(
                    'row_id' => $row_id++,
                    'id_journal' => $id_journal,
                    'id_coa' => '25205',
                    'nm_coa' => 'PAJAK YANG MASIH HARUS DIBAYAR - PPH PASAL 23',
                    'curr' => $populasi_kontra_bon['data'][0]->curr,
                    'debit' => 0,
                    'credit' => $amount['data'][0]->pph,
                    'description' => $rekap_str,
                    'id_reference' => '',
                    'src_reference' => '',
                    'dateadd' => date('Y-m-d H:m:s'),
                    'useradd' => $_SESSION['username'],
					'id_list_payment' =>'--',
                ); 				
				$dataBpb[] = $_credit;	
			}
			
			//Bank
                $_credit = array(
                    'row_id' => $row_id++,
                    'id_journal' => $id_journal,
                    'id_coa' => $coa_bank['id'],
                    'nm_coa' => $coa_bank['nm'],
                    'curr' => $populasi_kontra_bon['data'][0]->curr,
                    'debit' => 0,
                    'credit' => $amount['data'][0]->amount,
                    'description' => "--",
                    'id_reference' => '',
                    'src_reference' => '',
                    'dateadd' => date('Y-m-d H:m:s'),
                    'useradd' => $_SESSION['username'],
					'id_list_payment' =>'--',
                );	
				$dataBpb[] = $_credit;
			
			
    }
 					/* echo "<pre>"; */
/* 					print_r($dataBpb);
					echo "</pre>";
					die();	 */
				
            foreach($dataBpb as $_bpb){
                $sql_p = "
                    UPDATE fin_status_journal_ap SET is_lunas = 'Y' WHERE n_id = '{$_bpb['id_list_payment']}'
                    ;
                ";
                // Insert detail
				insert_log_nw($sql_p,$_SESSION['username']);
                $this->query($sql_p);				
				
				
				
                $sql = "
                    INSERT INTO fin_journal_d
                      (id_journal, row_id, id_coa, nm_coa, curr, debit, credit
                      ,description, id_reference, src_reference, dateadd, useradd
					  ,reff_doc
					  ,reff_doc2					  
					  ,id_bppb
					  ,id_bpb
					  ,id_po
					  ,id_po_det
					  ,id_item
					  ,qty
					  ,price
					  ,id_supplier
					  ,type_bpb
					  ,amount_original
					  ,is_retur_bh			  
					  ,id_list_payment
					  )
                    VALUES 
                      ('{$_bpb['id_journal']}', '{$_bpb['row_id']}', '{$_bpb['id_coa']}', '{$_bpb['nm_coa']}', '{$_bpb['curr']}', '{$_bpb['debit']}', '{$_bpb['credit']}'
                      ,'{$_bpb['nm_coa']}','{$_bpb['id_reference']}','{$_bpb['src_reference']}' ,'{$_bpb['dateadd']}', '{$_bpb['useradd']}'
						,'{$_bpb['reff_doc']}'
						,'{$_bpb['reff_doc2']}'
						,'{$_bpb['id_bppb']}'
						,'{$_bpb['id_bpb']}'
						,'{$_bpb['id_po']}'
						,'{$_bpb['id_po_det']}'
						,'{$_bpb['id_item']}'
						,'{$_bpb['qty']}'
						,'{$_bpb['price']}'
						,'{$_bpb['id_supplier']}'
						,'{$_bpb['type_bpb']}'
						,'{$_bpb['amount_original']}'
						,'{$_bpb['is_retur_bh']}'
						,'{$_bpb['id_list_payment']}'
					  
					  
                      )
                    ;
                ";
/* 				echo "<pre> $sql</pre>";
				die(); */ 				
                // Insert detail
				insert_log_nw($sql,$_SESSION['username']);
                $this->query($sql);
            }				
				
				
				
	}
	
	
public function insert_rekap_payment($id_journal, $coa_bank, $amount_bank__,$multiple_nya)
    {
		

		print_r($id_journal."<br/>");
		print_r($coa_bank);
		print_r($amount_bank__."<br/>");
		print_r($multiple_nya);
        $rekap_request = $this->check_rekap($multiple_nya, false);
		$type_nya = substr($rekap_request['data'][0]->no_bpb,0,3);
		$IsGeneral = IsGeneral($rekap_request['data'][0]->no_bpb);	
    //$IsGeneral = IsGeneral($bpbno);
	if($type_nya == "WIP"){
		$IsGeneral = "2";
	}
	if($type_nya == "FG/"){
		$IsGeneral = "3";
	}	

        if($rekap_request['status']){
            $dataBpb = array();
            $row_id = 1;
            $dateadd = date('Y-m-d H:i:s', time());
            $_SESSION['username'] = $_SESSION['username'];
			$amount  = 0;
			$fg_pkp = 0;
			$ppn_percentage = 0;
			$tot_amt = 0;
			$line_utang = 0;
			$line_pph = 0;
			$line_tambah_utang = 0;
			$coa_description = $this->populasi_rekap($multiple_nya);
	
			$get_list_payment = $this->Get_List_payment($multiple_nya);
			
			

			$get_list_payment = (object)$get_list_payment;
			if(!ISSET($get_list_payment->data)){
				$list_n = "";
			}else{
				$list_n = $get_list_payment->data;
			}/* 
 echo "<pre>";
print_r($get_list_payment);
echo "</pre>";			
	die();	 */ 	
			if($get_list_payment->key == '1' ){
						$line_utang = '1';
				
			} 
			
	//pph		
			$get_pph = $this->get_pph_src(($multiple_nya));				
			if($get_pph['key']  == 1){
				$pph_ar = 	(object)$get_pph;
				$pph_n = $pph_ar->data;
				if($pph_ar->key == '1' ){
					$line_pph = '1';
					} 	
			}
	//pph

//tambah utang
			$get_tambah_utang = $this->get_tambah_utang(($multiple_nya));			
			if($get_tambah_utang['key']  == 1){
				$tambah_utang = 	(object)$get_tambah_utang;
				$tambah_utang_n = $tambah_utang->data;
					if($tambah_utang->key == '1' ){
						$line_tambah_utang = '1';
					} 				
			}
//tambah utang	
	
	/*			
 			if($IsGeneral == '1'){
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

	/* 		echo "<pre>";
			print_r($rekap_request['data']);
			echo "</pre>";
			die(); */
			$x="1";
            foreach($rekap_request['data'] as $_rekap){
				$x++;
                switch($_rekap->v_source){
                    case 'KB': // Payment from kontrabon
			if($_rekap->is_retur_bh == 'Y'){
				$bpb_request = $this->check_bppb($_rekap->id_bpb,$_rekap->v_listcode,$type_nya, false);
/* 				echo "<pre>";
				print_r($bpb_request);
				echo "</pre>"; */
				
			}else{
				$bpb_request = $this->check_bpb($_rekap->id_bpb,$_rekap->v_listcode,$type_nya, false);
			}
/*  						echo "<pre>";
						print_r($bpb_request);
							echo "</pre>";
							die();  */
                        if($bpb_request['status']){
                            $bpb = $bpb_request['data'];
                            $dateadd = date('Y-m-d H:i:s', time());
                            $_SESSION['username'] = $_SESSION['username'];

                            foreach($bpb as $_bpb){
                                $_bpb = (array) $_bpb;
                               // list($coa_persediaan, $coa_gr, $coa_hutang) = $this->get_id_coa_payment_from_bpb($_bpb)
							
			if($_rekap->is_retur_bh == 'Y'){
				   echo $_rekap->is_retur_bh."<br/>";
				list($coa_persediaan, $coa_gr, $coa_hutang)  = $this->get_id_coa_payment_from_bpb_ret($_bpb);

			}else{	
				if($IsGeneral == '1'){
					list($coa_persediaan, $coa_gr, $coa_hutang)  = $this->get_id_coa_gen_payment_from_bpb($_bpb);
				}else if($IsGeneral == '2'){
					list($coa_persediaan, $coa_gr, $coa_hutang)  = $this->get_id_coa_payment_from_bpb_subcon($_bpb);
				}else if($IsGeneral == '3'){
					list($coa_persediaan, $coa_gr, $coa_hutang)  = $this->get_id_coa_payment_from_bpb_fg($_bpb);
					$_bpb['itemdesc'] = getDescription($_rekap->no_bpb,$_bpb['id_item']);
				}else{
					list($coa_persediaan, $coa_gr, $coa_hutang)  = $this->get_id_coa_payment_from_bpb($_bpb);
				}		
                $_bpb = (array) $_bpb;
               // list($coa_persediaan, $coa_gr, $coa_hutang) = $this->get_id_coa_payment_from_bpb($_bpb);
			}				
			if($type_nya == "WIP"){
									$amount =( $_bpb['price_po'] * $_bpb['qty']);
									//echo $_bpb['price_po'];
									//$fg_pkp        = $_bpb['fg_pkp'];
									//$ppn_percentage= $_bpb['ppn'];							
									}else{
										if($_rekap->is_retur_bh == 'Y'){
											$amount =( $_bpb['price_po'] * $_bpb['qty']);
										}else{
											$amount = ( $_bpb['price'] * $_bpb['qty']);
										}
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
                                    'debit' => ($_rekap->is_retur_bh == 'Y' ? 0:$amount),
                                    'credit' => ($_rekap->is_retur_bh == 'Y' ? $amount:0),
                                    'description' => $_rekap->bpbno_int.' - '.$_bpb['itemdesc'],
                                    'id_reference' => $_bpb['id_item'],
                                    'src_reference' => Config::$reference_src_key_item,
                                    'dateadd' => $dateadd,
									'id_bpb' => ($_rekap->is_retur_bh == 'Y' ? NULL:$_bpb['SAVE_id_bpb']),
									'id_bppb' => ($_rekap->is_retur_bh == 'Y' ? $_bpb['SAVE_id_bpb']:NULL),
									'id_po' => $_bpb['SAVE_id_po'],
									'id_po_det' => $_bpb['SAVE_id_po_det'],
									'id_item' => $_bpb['SAVE_id_item'],
									'qty' => $_bpb['SAVE_qty'],
									'price' => $_bpb['SAVE_price'],
									'id_supplier' => $_bpb['SAVE_id_supplier'],
									'type_bpb' => $_bpb['SAVE_type_bpb'],
									'id_list_payment' => $_bpb['id_payment'],
									'reff_doc' => $_bpb['no_kb'],
									'reff_doc2' => $_bpb['row_kb'],
									'is_retur_bh' => ($_rekap->is_retur_bh == 'Y' ? 'Y':'N'),
									'amount_original' => $_bpb['SAVE_amount_ori'],									
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
										'id_list_payment' =>'--',
										
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
                    'credit' => $amount_bank__,
                    'description' => $coa_description,
                    'id_reference' => '',
                    'src_reference' => '',
                    'dateadd' => $dateadd,
                    'useradd' => $_SESSION['username'],
					'id_list_payment' =>'--',
                );

                $dataBpb[] = $_credit;
            }
			
			//$pph = $this->check_pph($no_rekap);
			//print_r($pph);
			//die();$pph_ar
			if($line_pph == '1'){
	        //PPH
				$val_pph = $pph_n[0]->value_pph;
				//$amount = $amount - $val_pph;
				//$dataBpb[1]['credit'] = $amount_bank__ - ($val_pph);
				
				

                $_credit = array(
                    'row_id' => $row_id++,
                    'id_journal' => $id_journal,
                    'id_coa' => '25205',
                    'nm_coa' => 'PAJAK YANG MASIH HARUS DIBAYAR - PPH PASAL 23',
                    'curr' => $cur_pph,
                    'debit' => 0,
                    'credit' => $pph_n[0]->value_pph,
                    'description' => $no_rekap,
                    'id_reference' => '',
                    'src_reference' => '',
                    'dateadd' => $dateadd,
                    'useradd' => $_SESSION['username'],
					'id_list_payment' =>'--',
                ); 
				$dataBpb[] = $_credit;
				//$dataBpb[0]['debit'] = $amount_bank__;
				//print_r("AMTBANK:".$amount_bank." PPH:".$val_pph);
				//die();				
				
			}
			if($line_utang == '1' ){
				
				foreach($list_n as $_list_n){
                $_credit = array(
                    'row_id' => $row_id++,
                    'id_journal' => $id_journal,
                    'id_coa' => $_list_n->coa_utang,
                    'nm_coa' => $_list_n->nm_utang,
                    'curr' => $cur_pph,
                    'debit' => 0,
                    'credit' => $_list_n->nilai,
                    'description' => $_list_n->description,
                    'id_reference' => '',
                    'src_reference' => '',
                    'dateadd' => $dateadd,
                    'useradd' => $_SESSION['username'],
					'id_list_payment' =>'--',
                ); 		
				$dataBpb[] = $_credit;				
				
				}
				
			}		

			if($line_tambah_utang == '1' ){
				
				foreach($tambah_utang_n as $_tambah_utang_n){
                $_debit = array(
                    'row_id' => $row_id++,
                    'id_journal' => $id_journal,
                    'id_coa' => $_tambah_utang_n->coa_utang,
                    'nm_coa' => $_tambah_utang_n->nm_utang,
                    'curr' => $cur_pph,
                    'debit' => $_tambah_utang_n->nilai,
                    'credit' => 0,
                    'description' => $_tambah_utang_n->description,
                    'id_reference' => '',
                    'src_reference' => '',
                    'dateadd' => $dateadd,
                    'useradd' => $_SESSION['username'],
					'id_list_payment' =>'--',
                ); 	
}				
				$dataBpb[] = $_debit;
			}
/*  echo "<pre>";
print_r($dataBpb);
echo "</pre>"; */
	 
            foreach($dataBpb as $_bpb){
                $sql_p = "
                    UPDATE fin_status_journal_ap SET is_lunas = 'Y' WHERE n_id = '{$_bpb['id_list_payment']}'
                    ;
                ";
                // Insert detail
				insert_log_nw($sql_p,$_SESSION['username']);
                $this->query($sql_p);				
				
				
				
                $sql = "
                    INSERT INTO fin_journal_d
                      (id_journal, row_id, id_coa, nm_coa, curr, debit, credit
                      ,description, id_reference, src_reference, dateadd, useradd
					  ,reff_doc
					  ,reff_doc2					  
					  ,id_bppb
					  ,id_bpb
					  ,id_po
					  ,id_po_det
					  ,id_item
					  ,qty
					  ,price
					  ,id_supplier
					  ,type_bpb
					  ,amount_original
					  ,is_retur_bh			  
					  ,id_list_payment
					  
					  )
                    VALUES 
                      ('{$_bpb['id_journal']}', '{$_bpb['row_id']}', '{$_bpb['id_coa']}', '{$_bpb['nm_coa']}', '{$_bpb['curr']}', '{$_bpb['debit']}', '{$_bpb['credit']}'
                      ,'{$_bpb['nm_coa']}','{$_bpb['id_reference']}','{$_bpb['src_reference']}' ,'{$_bpb['dateadd']}', '{$_bpb['useradd']}'
						,'{$_bpb['reff_doc']}'
						,'{$_bpb['reff_doc2']}'
						,'{$_bpb['id_bppb']}'
						,'{$_bpb['id_bpb']}'
						,'{$_bpb['id_po']}'
						,'{$_bpb['id_po_det']}'
						,'{$_bpb['id_item']}'
						,'{$_bpb['qty']}'
						,'{$_bpb['price']}'
						,'{$_bpb['id_supplier']}'
						,'{$_bpb['type_bpb']}'
						,'{$_bpb['amount_original']}'
						,'{$_bpb['is_retur_bh']}'
						,'{$_bpb['id_list_payment']}'
					  
					  
                      )
                    ;
                ";
/* 				echo "<pre> $sql</pre>";
				die(); */ 				
                // Insert detail
				insert_log_nw($sql,$_SESSION['username']);
                $this->query($sql);
            }
			//die();
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
              ,description, id_reference, src_reference, dateadd, useradd
					  ,id_bppb
					  ,id_bpb
					  ,id_po
					  ,id_po_det
					  ,id_item
					  ,qty
					  ,price
					  ,id_supplier
					  ,type_bpb
					  ,amount_original
					  ,n_pph
					  ,n_ppn
					  ,is_retur_bh			  
					  ,id_list_payment
			  
			  
			  )
            VALUES 
              ('{$_bank['id_journal']}', '{$_bank['row_id']}', '{$_bank['id_coa']}', '{$_bank['nm_coa']}', '{$_bank['curr']}', '{$_bank['debit']}', '{$_bank['credit']}'
              ,'{$_bank['description']}','{$_bank['id_reference']}','{$_bank['src_reference']}' ,'{$_bank['dateadd']}', '{$_bank['useradd']}'
			  {$_bpb['reff_doc']}','{$_bpb['reff_doc2']}'
				,'{$_bank['id_bppb']}'
				,'{$_bank['id_bpb']}'
				,'{$_bank['id_po']}'
				,'{$_bank['id_po_det']}'
				,'{$_bank['id_item']}'
				,'{$_bank['qty']}'
				,'{$_bank['price']}'
				,'{$_bank['id_supplier']}'
				,'{$_bank['type_bpb']}'
				,'{$_bank['amount_original']}'
				,'{$_bank['pph']}'
				,'{$_bank['ppn']}'
				,'{$_bank['id_list_payment']}'
			  
			  
			  
			  
              )
            ;
        ";
        // Insert detail
		insert_log_nw($sql,$_SESSION['username']);
        $this->query($sql);

    }
/* 
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
	
		
	} */


}

// CONTROLLER BEGIN

$M = new Model($con_new);

$ch = new Coa_helper();

$id = isset($_GET['id']) ? $_GET['id'] : '';
if(isset($_POST['submit']) or isset($_POST['posting'])){
	
	$arr_nilai = explode(' ',$_POST['nilai']);
	$_POST['nilai'] = $arr_nilai[1];
	$multiple = json_decode($_POST['array_detail']);

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
        'reff_doc2' => '',//$_POST['reff_doc2'],
        'fg_intercompany' => $_POST['fg_intercompany'],
        'id_intercompany' => $_POST['id_intercompany'],
        'd_cek_giro' => date('Y-m-d', strtotime($_POST['d_cek_giro'])),
        'v_cek_giro' => $_POST['v_cek_giro'],
		'multiple' => $multiple,
    );
/* 	echo"<pre>";
	print_r($data);
    echo"</pre>"; */
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
                    $M->insert_rekap_payment_new($data['id_journal'], $coa_bank, $amount,$data['multiple']);
                default:
                    $M->insert_bank_payment($data['id_journal'], $coa_bank, $amount);
            }
            $M->save($data);
            echo "<script>window.location.href='?mod=jpay&id={$data['id_journal']}';</script>";
            exit();
        } elseif ($_POST['mode'] == 'update') {
/* echo "123";
die(); */
			//$M->Delete_Detail($data);
            $journal = $M->get_journal($id);
            if ($journal->num_journal != $data['num_journal'] and $M->get_journal_by_num($data['num_journal'])) {
                echo "<script>alert('Error: Nomor Dokumen sudah terdaftar');</script>";
                $status = false; // Num journal already exists
                $row = json_decode(json_encode($data));
            } else {
                $data['dateedit'] = date('Y-m-d H:i:s', time());
                $data['useredit'] = $_SESSION['username'];
				$M->delete_detail_journal_d($id);
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
                    $M->insert_rekap_payment_new($id, $coa_bank, $amount,$data['multiple']);
                default:
                    $M->insert_bank_payment($data['id_journal'], $coa_bank, $amount);
            }
                $status = $M->update($id, $data);
                if (isset($_POST['posting'])) {
					if($_POST['posting'] !=""){
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
<link rel="stylesheet" href="./css/overlay.css"> 
<div id="myOverlay">
<div class="col-md-3 col-sm-offset-6" style="padding-top:400px">
<img src="./images/loading.gif" class="img-responsive" width="20%">
</div>
</div>
<form method='post' id="form_payments" name='form' enctype='multipart/form-data' action="">
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
                         <label>Referensi Dokumen *</label>
                         <div class="input-group" id='klik_saya'>
                         <input type='text' id='reff_doc' class='form-control' name='reff_doc'
                                placeholder='Masukkan Referensi Dokumen' value='<?=isset($row)?$row->reff_doc:''?>' readonly>
                         <a onclick="modal_bpb()" style="cursor: pointer" title='pilih list payment' class="input-group-addon" >&nbsp;</a>
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
                    <button style="display:none"  name='submit' id="_submit"  class='btn btn-primary validasi_proses'>Simpan</button>
					 <a href="#" id="_submit" onclick="validasi_bayar(this.id)" id="simpan" class='btn btn-primary validasi_proses'>Simpan</button>
                    <?php endif;?>
                    <?php if(isset($row) and !@$row->fg_post and $id):?>
                    <a href="#" onclick="validasi_bayar(this.id)" id="simpan" class='btn btn-primary validasi_proses'>Simpan</button>
					<a href="#" onclick="validasi_bayar(this.id)" id="posting" class='btn btn-primary validasi_proses'>Posting</button>
					<button style="display:none" id="_submit" type='submit' name='submit' class='btn btn-primary validasi_proses'>Simpan</button>
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
				
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
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
                                    <input type='text' class='form-control' value="2020-" id="bpbNo" >
                                </div>
                                <div class="col-md-3">
                                    <label>Supplier</label>
                                    <input type='text' class='form-control' id="supplier_lookup" >
                                </div>								
                                <div class="col-md-3">
                                    <label for="">&nbsp;</label><br>
                                    <a onclick="lookup_bpb()" class="btn btn-primary">Lookup</a>
                                </div>
                                <div class="col-md-3">
                                    <label>Total Amount Yang Dipilih</label>
                                    <input type='text' class='form-control' id="tot_amnt_new" >
                                </div>									
                            </div>
                            <div class="row hidden">
                                <div class="col-md-12">
                                    <em>Gunakan % untuk pencarian wildcard</em>
                                </div>
                            </div>
                        </div>
                        <div class="bpbresult">
                            <table class="table" height:400px>
                                <thead>
								<th onclick="klik_lookup()">*</th>
                                <th>Payment No</th>
								<th>No Kontra Bon</th>
<!--                                <th>BPB No</th>-->
<!--                                <th>BPB No Internal</th>-->
<!--                                <th>PO</th>-->
                                <th>Supplier</th>
								<th>Jatuh Tempo</th>
                                <th>Amount</th>

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
				
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
        $('#modalBpb').modal('show');
        $("#modalBpb .bpbresult table tbody").html('');
        $("#bpbNo").val('');
        $("#bpbNoInternal").val('');
		if($payment_array.length > 0){
			$("#myOverlay").css("display","block");

					setTimeout(function(){
						lookup_bpb();
						
					},2000);			
						
			
		}		
    }
    function lookup_bpb(){
		if($split_nya[1]){
			$id_payment = $split_nya[1];
		}else{
			$id_payment ="";
		}
        var request = $.ajax
        ({  type: "POST",
            url: "ajax_fin.php?mdajax=lookup_rekap_ap",
            data: "supplier=" +$("#supplier_lookup").val()+" &approved=1&no_payment=" +$("#bpbNo").val()+"&id_payment="+$id_payment,
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
					rows += '<td><input type="checkbox"  id="PAYMENT'+bpb_list[i].n_id+'" data-id_supplier="'+ bpb_list[i].id_supplier+'" data-n_id_ap="'+bpb_list[i].n_id+'" data-nilai="'+bpb_list[i].bpb_amount+'" data-curr="'+bpb_list[i].curr+'"  onclick="klik_lookup(this)"></td>';
					
					rows +="<td><a href='#' data-toggle='modal' data-target='#myModalLIST' onclick='getListDataRekap("+'"'+bpb_list[i].no_payment+'"'+")' >"+bpb_list[i].no_payment+"</a></td>";	
				//	rows += '<td>'+ bpb_list[i].bpb_list[i].bpb_amount + '</td>';
//                    rows += '<td>' + bpb_list[i].bpbno + '</td>';
//                    rows += '<td>' + bpb_list[i].bpbno_int + '</td>';
//                    rows += '<td>' + bpb_list[i].pono + '</td>';
					
					rows += '<td>'+ bpb_list[i].v_nojournal + '</td>';
                    rows += '<td>'+ bpb_list[i].nm_supplier + '</td>';
					rows += '<td>'+ bpb_list[i].terms_of_pay + '</td>';
                    rows += '<td >'+ bpb_list[i].curr +" <p align='right' style='margin-top:-20px'>"+  formatNumber(bpb_list[i].bpb_amount) + '</p></td>';
//                    rows += '<td class="text-right">' + formatNumber(bpb_list[i].po_amount) + '</td>';
//                    rows += '<td class="text-right">' + formatNumber(bpb_list[i].paid_amount) + '</td>';
//                    rows += '<td class="text-right">' + formatNumber(bpb_list[i].outstanding_amount) + '</td>';
;
                    rows += '</tr>';
                }
				if($payment_array.length  > 0){
					setTimeout(function(){
						for(var $y=0;$y<$payment_array.length;$y++){
							document.getElementById("PAYMENT"+$payment_array[$y].id).checked = true;
						}

					setTimeout(function(){
						$("#myOverlay").css("display","none");
						
					},1000);
					
					
					},3000);						
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
       // $('#nilai').val(curr+" "+amount);
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
    function validasi_bayar(my_id) {
		if(my_id == 'posting'){
			$("#posting").val("posting");
		}
		 $("#datepicker1").attr("disabled",false);
		 //return false;
		var bank =$("#bank").val();
        var period = $("#periodpicker_").val();  
        var type_journal = $("#type_journal").val();
        var date_journal = $("#datepicker1").val();
        //var reff_doc = document.form.reff_doc.value;
       // var po = document.form.po.value;
       // var bpb = document.form.bpb.value;
	  
	  // $("#datepicker1_new").attr("disabled",false);
	 // return false;
	  valid = true;
        var amount = $("#nilai").val();
convert_totext().then(function(responnya) {
	$("#array_detail").val(responnya);
	console.log(responnya);


		if (!bank || bank == '' || bank < 100 ) {
            alert('Error: Id Coa Kas/ Bank Harus diisi');
            document.form.bank.focus();
			 valid = false;
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
/*         <?php endif;*/?>
 /*       else if (<?=(isset($row) and isset($row->fg_post) and $row->fg_post)?'true':'false'?>) {
            alert('Error: Journal sudah di posting');
            valid = false;
        } else valid = true; */


if(valid == "false"){
	return false;
}else{
	//alert("123");
/* 	document.getElementById("form_payments").submit(); */
$x++;
 if($x>1){
	 return false;
 }
	$( "#_submit" ).trigger( "click" );
	//$( "form_payments" ).first().submit();
}	
	




});

    }
$x= 0;	

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