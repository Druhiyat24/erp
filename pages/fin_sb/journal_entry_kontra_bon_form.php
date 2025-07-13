<?php
ini_set('memory_limit','-1');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Session Checking
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
if(ISSET($_SESSION['username'])){
	$_SESSION['username']=$_SESSION['username'];
}
else{
	$_SESSION['username'] = "SignalBit";
}
//$_SESSION['username']=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("F_P_Kontrabon","userpassword","username='$_SESSION[username]'");
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
            SELECT * FROM
            fin_journal_h jh
            WHERE id_journal = '$id';
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
/* 
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
 */
public function getIdCoaIrGeneral($bpb){

		// global $con_new;
	   // $Bm = new Basemodel($con_new);
$q = "SELECT BPB.id_item
		, BPB.bpbno
		,SUBSTR(MI.goods_code,1,3) code_gen
		,MCG.n_vendor_cat 
		,MCG.id_coa_debit_gr 
		,MCG.id_coa_credit_gr 
		,MCG.id_coa_debit_ir 
		,MCG.id_coa_credit_ir 
		,COA_GR_DEB.nm_coa nama_coa_gr_deb
		,COA_GR_CRE.nm_coa nama_coa_gr_cre
		,COA_IR_DEB.nm_coa nama_coa_ir_deb
		,COA_IR_CRE.nm_coa nama_coa_ir_cre		
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
		,MCG.id_coa_debit_gr 
		,MCG.id_coa_credit_gr 
		,MCG.id_coa_debit_ir 
		,MCG.id_coa_credit_ir 
		,COA_GR_DEB.nm_coa nama_coa_gr_deb
		,COA_GR_CRE.nm_coa nama_coa_gr_cre
		,COA_IR_DEB.nm_coa nama_coa_ir_deb
		,COA_IR_CRE.nm_coa nama_coa_ir_cre		
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
    $coa_debit = array(
        'id' => $mapping_coa->id_coa_debit_ir,
        'nm' => $mapping_coa->nama_coa_ir_deb
    );
    $coa_credit = array(
        'id' => $mapping_coa->id_coa_credit_ir,
        'nm' => $mapping_coa->nama_coa_ir_cre
    );
    return array(
        $coa_debit,
        $coa_credit
    );
}


    public function get_journal_items($id, $post_flag)
    {
        // If posted, hide GR/IR
//        if($post_flag == 2){
//            return $this->query("
//                SELECT * FROM (
//                    SELECT *, CASE WHEN credit = 0 THEN 0 ELSE 1 END ordering
//                    FROM
//                    fin_journal_d jd
//                    WHERE id_journal = '$id'
//                    and ( id_coa < '21001' or id_coa > '21049')
//                ) x
//                ORDER BY ordering;
//            ")->result();
//        }else {
            return $this->query("
                SELECT * FROM (
                    SELECT *, CASE WHEN credit = 0 THEN 0 ELSE 1 END ordering 
                    FROM
                    fin_journal_d jd
                    WHERE id_journal = '$id'
                ) x
                ORDER BY ordering;
            ")->result();
//        }
    }

     public function check_bpb($id, $checking = TRUE, $id2=''){
        if($id2){
            $used = $this->query("SELECT reff_doc FROM fin_journal_h WHERE reff_doc = '$id' AND reff_doc = '$id2' ")->row();
        }else{
            $used = $this->query("SELECT reff_doc FROM fin_journal_h WHERE reff_doc = '$id'")->row();
        }

        if($checking) {
            if (!is_null($used)) {
                return array(
                    'status' => false,
                    'errcode' => '01',
                    'message' => 'BPB number already used'
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

        $bpb_detail = $this->query("
            SELECT 
                bpb.bpbno
                ,bpb.bpbdate
                ,bpb.id_supplier
                ,bpb.id_item
                ,bpb.qty
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
            FROM 
                bpb
                LEFT JOIN mastersupplier ms ON bpb.id_supplier = ms.Id_Supplier
                LEFT JOIN masteritem mi ON bpb.id_item = mi.id_item
                LEFT JOIN mastergroup mg ON mi.matclass = mg.nama_group
            WHERE 
                bpb.bpbno_int = '$id'
        ")->result();

        return array(
            'status' => true,
            'errcode' => null,
            'message' => 'BPB number found',
            'data'  => $bpb_detail
        );
    }
 
    public function save($d)
    {
		
		//print_r($d);
		//die();
        $sql = "
            INSERT INTO fin_journal_h 
              (company, period, id_journal, num_journal, date_journal, type_journal, reff_doc, reff_doc2, src_reference, fg_intercompany, id_intercompany
              ,dateadd, useradd,fg_tax,n_ppn,n_pph,inv_supplier,d_invoice 
			  )
            VALUES 
              ('{$d['company']}', '{$d['period']}', '{$d['id_journal']}', '{$d['num_journal']}', '{$d['date_journal']}'
              , '{$d['type_journal']}', 'KB', 'KB', '{$d['src_reference']}', '{$d['fg_intercompany']}', '{$d['id_intercompany']}'
             ,'{$d['dateadd']}', '{$d['useradd']}','{$d['fg_tax']}','{$d['ppn']}','{$d['pph']}','{$d['noinvoiceSupplier']}',
			 '{$d['tgl_invoice']}'
			 )
            ;
        ";
        $this->query($sql);
		
        $sql_ = "
            INSERT INTO fin_journalheaderdetail
              (v_idjournal,v_fakturpajak, d_fakturpajak) 
			  
            VALUES 
              ('{$d['id_journal']}', '{$d['no_fp']}', '{$d['tgl_fp']}')
            ;
        ";
        $this->query($sql_);		
		
		
		

//        return $id_journal; //$this->conn->insert_id;
    }

    public function update($id, $data)
    {
		print_r($data);
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
			  fg_tax = '{$data['fg_tax']}',
			  n_ppn = '{$data['ppn']}',
			  n_pph = '{$data['pph']}',
			  inv_supplier = '{$data['noinvoiceSupplier']}',
              useredit = '{$data['useredit']}',
			  d_invoice = '{$data['tgl_invoice']}'
            WHERE id_journal = '$id';
        ";

        $this->query($sql);
        $sql_ = "
            UPDATE fin_journalheaderdetail SET 
              v_fakturpajak = '{$data['no_fp']}',
              d_fakturpajak = '{$data['tgl_fp']}'
            WHERE v_idjournal = '$id';
        ";
        return $this->query($sql_);	
	}		
    public function update_pph_detail($id,$pph_nya)
    {
/*  		print_r($pph_nya);
		die();   */
/*         $sql = "
            UPDATE fin_journal_d SET 
              n_pph = NULL
			WHERE id_journal = '{$id}';
        "; */
/* 
        $this->query($sql); */
		$pph_nya =((array)$pph_nya);
/* 		echo count($pph_nya);
		die(); */
		for($i=0;$i<count($pph_nya);$i++){
        $sql = "
            UPDATE fin_journal_d SET 
              n_pph = '{$pph_nya[$i]->value}'
			WHERE id_journal = '{$id}' AND credit > 0 AND id_bpb = '{$pph_nya[$i]->id}';
        ";
/* echo $sql;
die(); */
        $this->query($sql);
			
			
		}
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
        $sql = "
            UPDATE fin_journal_h SET 
              fg_post = '2',
              date_post = '{$post['date_post']}',
              user_post = '{$post['user_post']}'
            WHERE id_journal = '$id';
        ";

        return $this->query($sql);

//        $journal = (array) $this->get_journal($id);
//
//        switch($journal['type_journal']){
//            case Config::$journal_usage['PURCHASE']:
//                //Check whether this is gr journal or not by checking on reff to bpb. code 02 means not existent
//                $bpb_request = $this->check_bpb($journal['reff_doc'], FALSE);
//
//                if($bpb_request['errcode'] != '02'){
//                    $this->insert_bpb_ir($journal['reff_doc'],$id);
////
////                    $data = array(
////                        'company' => $journal['company'],
////                        'period' => $journal['period'],
////                        'num_journal' => '',//$journal['num_journal'],
////                        'date_journal' => date('Y-m-d', time()),
////                        'type_journal' => $journal['type_journal'],
//////                    'reff_doc' => $journal['reff_doc'],
////                        'reff_doc' => $id,
////                        'fg_intercompany' => $journal['fg_intercompany'],
////                        'id_intercompany' => $journal['id_intercompany'],
////                    );
////
////                    $journal_code = Config::$journal_code[$data['type_journal']];
////                    $data['id_journal'] = generate_coa_id("NAG", $journal_code);
////
////                    $this->insert_bpb_ir($journal['reff_doc'],$data['id_journal']);
////
////                    $data['dateadd'] = date('Y-m-d H:i:s', time());
////                    $data['useradd'] = $_SESSION['username'];
////                    $this->save($data);
//                }
//                break;
//            default:
//        }
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
        $Ch = new Coa_helper();
        return $Ch->get_available_period();
    }

    public function get_id_coa_gr_from_bpb($bpb){
        $mapping_coa = $this->query("
            SELECT mp.gr_d, mcd.nm_coa nm_coa_d, mp.gr_k, mck.nm_coa nm_coa_k
            FROM mapping_coa mp
            LEFT JOIN mastercoa mcd ON mcd.id_coa = mp.gr_d
            LEFT JOIN mastercoa mck ON mck.id_coa = mp.gr_k
            WHERE 
            id_group = '{$bpb['id_group']}' and vendor_cat = '{$bpb['vendor_cat']}' 
        ")->row();

        $coa_debit = array(
            'id' => $mapping_coa->gr_d,
            'nm' => $mapping_coa->nm_coa_d
        );
        $coa_credit = array(
            'id' => $mapping_coa->gr_k,
            'nm' => $mapping_coa->nm_coa_k
        );
        return array(
            $coa_debit,
            $coa_credit
        );
    }

    public function get_id_coa_ir_from_bpb($bpb){
        $mapping_coa = $this->query("
            SELECT mp.ir_d, mcd.nm_coa nm_coa_d, mp.ir_k, mck.nm_coa nm_coa_k
            FROM mapping_coa mp
            LEFT JOIN mastercoa mcd ON mcd.id_coa = mp.ir_d
            LEFT JOIN mastercoa mck ON mck.id_coa = mp.ir_k
            WHERE 
            id_group = '{$bpb['id_group']}' and vendor_cat = '{$bpb['vendor_cat']}' 
        ")->row();

        $coa_debit = array(
            'id' => $mapping_coa->ir_d,
            'nm' => $mapping_coa->nm_coa_d
        );
        $coa_credit = array(
            'id' => $mapping_coa->ir_k,
            'nm' => $mapping_coa->nm_coa_k
        );
        return array(
            $coa_debit,
            $coa_credit
        );
    }

/*     public function insert_bpb_gr($bpbno, $id_journal)
    {
        $bpb_request = $this->check_bpb($bpbno);
        if($bpb_request['status']){
            $dataBpb = array();
            $bpb = $bpb_request['data'];
            $row_id = 1;
            $dateadd = date('Y-m-d H:i:s', time());
            $_SESSION['username'] = $_SESSION['username'];
            foreach($bpb as $_bpb){
                $_bpb = (array) $_bpb;
                $amount = $_bpb['price'] * $_bpb['qty'];
                list($coa_debit, $coa_credit) = $this->get_id_coa_gr_from_bpb($_bpb);

                // GR
                $_debit = array(
                    'row_id' => $row_id++,
                    'id_journal' => $id_journal,
                    'id_coa' => $coa_debit['id'],
                    'nm_coa' => $coa_debit['nm'],
                    'curr' => $_bpb['curr'],
                    'debit' => $amount,
                    'credit' => 0,
                    'description' => $_bpb['itemdesc'],
                    'dateadd' => $dateadd,
                    'useradd' => $_SESSION['username'],
                );
                // Piutang
                $_credit = array(
                    'row_id' => $row_id++,
                    'id_journal' => $id_journal,
                    'id_coa' => $coa_credit['id'],
                    'nm_coa' => $coa_credit['nm'],
                    'curr' => $_bpb['curr'],
                    'debit' => 0,
                    'credit' => $amount,
                    'description' => $_bpb['itemdesc'],
                    'dateadd' => $dateadd,
                    'useradd' => $_SESSION['username'],
                );

                $dataBpb[] = $_debit;
                $dataBpb[] = $_credit;
            }
            foreach($dataBpb as $_bpb){
                $sql = "
                    INSERT INTO fin_journal_d
                      (id_journal, row_id, id_coa, nm_coa, curr, debit, credit
                      ,description, dateadd, useradd)
                    VALUES 
                      ('{$_bpb['id_journal']}', '{$_bpb['row_id']}', '{$_bpb['id_coa']}', '{$_bpb['nm_coa']}', '{$_bpb['curr']}', '{$_bpb['debit']}', '{$_bpb['credit']}'
                      ,'{$_bpb['description']}','{$_bpb['dateadd']}', '{$_bpb['useradd']}'
                      )
                    ;
                ";
                // Insert detail
                $this->query($sql);
            }
        }
    }
 */
 
 
	public function check_pajak($bpbno_arr){
		$count = count($bpbno_arr);
		$params = $count - 1;
		$in_where = "";
		for($i=0;$i< $count;$i++){
			if($i == $params){
				$in_where .= "'".trim($bpbno_arr[0])."'";
			}else{
				$in_where .= "'".trim($bpbno_arr[0])."',";
			}
			
		}
		
        $det_pajak = $this->query("
SELECT 
						sum(ifnull(if(SUBSTR(bpb.bpbno_int, 1, 3)='WIP',poh.ppn,poh_nonjasa.ppn),0))ppn_total
            ,bpb.bpbno
			,bpb.id_po_item 
			,bpb.bpbno_int
			,poh_nonjasa.pono pono_non_jasa
			,poh_nonjasa.ppn ppn_non_jasa
			,poh_nonjasa.fg_pkp fg_pkp_non_jasa
            ,bpb.bpbdate
            ,bpb.id_supplier 
            ,bpb.id_item
            ,bpb.id id_bpb 
            ,bpb.qty
            ,bpb.unit
            ,bpb.curr
            ,bpb.price
			,bpb.id_jo
            ,ms.Supplier
            ,ms.supplier_code
            ,ms.vendor_cat
            ,mi.itemdesc
            ,mi.mattype
            ,mi.matclass
            ,mg.id id_group
                ,poi.qty qty_po
                ,poi.price price_po		
                ,poi.id id_po_det
                ,poi.id_gen
                ,bpb.id_item
				,poh.id idpo
				,poh.fg_pkp
				,poh.pono
				,poh.ppn
				,if(poh.fg_pkp > 0 OR poh.ppn > 0,(bpb.qty*poi.price)+((poh.ppn/100)*poi.price),bpb.qty*poi.price)nilai
            FROM 
                bpb
                LEFT JOIN mastersupplier ms ON bpb.id_supplier = ms.Id_Supplier
                LEFT JOIN masteritem mi ON bpb.id_item = mi.id_item
                LEFT JOIN mastergroup mg ON mi.matclass = mg.nama_group
				LEFT JOIN po_item poi ON poi.id_jo = bpb.id_jo AND poi.id_gen = bpb.id_item
				LEFT JOIN po_header poh ON poh.id = poi.id_po
				LEFT JOIN po_item poi_nonjasa ON poi_nonjasa.id = bpb.id_po_item
				LEFT JOIN po_header poh_nonjasa ON poh_nonjasa.id = poi_nonjasa.id_po				
				
            WHERE 
                trim(bpb.bpbno_int) IN ($in_where)		
        ")->row();

		if($det_pajak->ppn_total != 0){
			$key = '1';
		}else{
			$key = '0';
		}

        $detail = array(
            'ppn' => $det_pajak->ppn_total,
			'key' => $key
        );
        return array(
            $detail
        );		
		
	}
 
    public function insert_bpb_ir_pajak($bpbno)
		{

			$check_pajak = $this->check_pajak($bpbno);
			print_r($check_pajak);
			die();
		
		}
 
 
    public function delete_detail_journal_d($id)
    {
		//{$d['id_journal']}
		$sql = "DELETE FROM fin_journal_d WHERE id_journal = '{$id}'";
/* 		echo $sql;
		die(); */
        $this->query($sql);

//        return $id_journal; //$this->conn->insert_id;
    }	 
 
     public function insert_bpb_ir_ret($bpbno, $id_journal,$reff_doc2)
    {
        $bpb_request = check_bppb_update($bpbno);	


        if($bpb_request['status']){
            $dataBpb = array();
            $bpb = $bpb_request['data'];
            $row_id = $this->get_next_item_id($id_journal);
            $dateadd = date('Y-m-d H:i:s', time());
            $_SESSION['username'] = $_SESSION['username'];
			$fg_pkp = "0";
			$ppn_percentage = "0";
			$amount = 0;
			$total_amount = 0;		
			$curr = '';
			$row_nya = 0;
            foreach($bpb as $_bpb){
                $_bpb = (array) $_bpb;

             //   $amount = $_bpb['price'] * $_bpb['qty'];
				

				$amount = ( $_bpb['price_po'] * $_bpb['qty']);	

				$ppn_percentage= $_bpb['ppn'];
				//$ppn_percentage= $_bpb['ppn_non_jasa'];	
			$curr = $_bpb['curr'];

			$total_amount = $total_amount + $amount;
				list($coa_debit, $coa_credit) = get_id_coa_ir_from_bpb_ret($_bpb);
				
			
			
			// GR
                $_debit = array(
                    'row_id' => $row_id++,
                    'id_journal' => $id_journal,
                    'id_coa' => $coa_debit['id'],
                    'nm_coa' => $coa_debit['nm'],
                    'curr' => $_bpb['curr'],
                    'debit' => $amount,
                    'credit' => 0,
                    'description' => $_bpb['itemdesc'],
                    'dateadd' => $dateadd,
                    'useradd' => $_SESSION['username'],
					'reff_doc' => $bpbno,
					'reff_doc2' => $reff_doc2,
					'id_bpb' => $_bpb['SAVE_id_bpb'],
					'id_po' => $_bpb['SAVE_id_po'],
					'id_po_det' => $_bpb['SAVE_id_po_det'],
					'id_item' => $_bpb['SAVE_id_item'],
					'qty' => $_bpb['SAVE_qty'],
					'price' => $_bpb['SAVE_price'],
					'id_supplier' => $_bpb['SAVE_id_supplier'],
					'type_bpb' => $_bpb['SAVE_type_bpb'],
					'amount_original' => $_bpb['SAVE_amount_ori'],
					'pph' => '0',
					'ppn' => $_bpb['SAVE_ppn'],
                );
                // Piutang
                $_credit = array(
                    'row_id' => $row_id++,
                    'id_journal' => $id_journal,
                    'id_coa' => $coa_credit['id'],
                    'nm_coa' => $coa_credit['nm'],
                    'curr' => $_bpb['curr'],
                    'debit' => 0,
                    'credit' => $amount,
                    'description' => $_bpb['itemdesc'],
                    'dateadd' => $dateadd,
                    'useradd' => $_SESSION['username'],
					'reff_doc' => $bpbno,
					'reff_doc2' => $reff_doc2,
					'id_bpb' => $_bpb['SAVE_id_bpb'],
					'id_po' => $_bpb['SAVE_id_po'],
					'id_po_det' => $_bpb['SAVE_id_po_det'],
					'id_item' => $_bpb['SAVE_id_item'],
					'qty' => $_bpb['SAVE_qty'],
					'price' => $_bpb['SAVE_price'],
					'id_supplier' => $_bpb['SAVE_id_supplier'],
					'type_bpb' => $_bpb['SAVE_type_bpb'],
					'amount_original' => $_bpb['SAVE_amount_ori'],	
					'pph' => $pph,		
					'ppn' => $_bpb['SAVE_ppn'],					
                );
                $dataBpb[] = $_debit;
                $dataBpb[] = $_credit;

				
            }

/*  echo "<pre>";
print_r($dataBpb);
echo "</pre>";
die();  */
            foreach($dataBpb as $_bpb){
		
                $sql = "
                    INSERT INTO fin_journal_d
                      (id_journal, row_id, id_coa, nm_coa, curr, debit, credit
                      ,description, dateadd, useradd,reff_doc,reff_doc2,
					  id_bppb
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
					  
					  )
                    VALUES 
                      ('{$_bpb['id_journal']}', '{$_bpb['row_id']}', '{$_bpb['id_coa']}', '{$_bpb['nm_coa']}', '{$_bpb['curr']}', '{$_bpb['debit']}', '{$_bpb['credit']}'
                      ,'{$_bpb['description']}','{$_bpb['dateadd']}', '{$_bpb['useradd']}','{$_bpb['reff_doc']}','{$_bpb['reff_doc2']}'
					  ,'{$_bpb['id_bpb']}'
					  ,'{$_bpb['id_po']}'
					  ,'{$_bpb['id_po_det']}'
					  ,'{$_bpb['id_item']}'
					  ,'{$_bpb['qty']}'
					  ,'{$_bpb['price']}'
					  ,'{$_bpb['id_supplier']}'
					  ,'{$_bpb['type_bpb']}'
					  ,'{$_bpb['amount_original']}'
					  ,'{$_bpb['pph']}'
					  ,'{$_bpb['ppn']}'
					  ,'Y'
                      )
                    
                ";

                // Insert detail
                 $this->query($sql);
				//print_r($this->query($sql));
				//die();
            }
        }
    }
 
 
    public function insert_bpb_ir($bpbno, $id_journal,$reff_doc2)
    {
/* 		echo $bpbno;
		die(); */
		$type_nya = substr($bpbno,0,3);	
        $bpb_request = check_bpb_update($bpbno,$type_nya,'14');	
		$IsGeneral = IsGeneral($bpbno);
/* 		echo $id_journal;
		die(); */
		
	if($type_nya == "WIP"){
		$IsGeneral = "2";
	}
	if($type_nya == "FG/"){
		$IsGeneral = "3";
	}	

        if($bpb_request['status']){
            $dataBpb = array();
            $bpb = $bpb_request['data'];
			//CHECK ID BPB EXIST
			$key_exist_bpb = exist_bpb_kontra_bon($id_journal,$bpbno,$bpb[0]->id_bpb);
				if($key_exist_bpb == '1'){
			//CHECK ID BPB EXIST
/* 			echo "<pre>";
			print_r($bpb);
			die(); */
	/* 		echo "</pre>"; */
            $row_id = $this->get_next_item_id($id_journal);
            $dateadd = date('Y-m-d H:i:s', time());
            $_SESSION['username'] = $_SESSION['username'];
			$fg_pkp = "0";
			$ppn_percentage = "0";
			$amount = 0;
			$total_amount = 0;		
			$curr = '';
			$row_nya = 0;
            foreach($bpb as $_bpb){
                $_bpb = (array) $_bpb;

             //   $amount = $_bpb['price'] * $_bpb['qty'];
				
			if($type_nya == "WIP"){
				$amount = ( $_bpb['price_po'] * $_bpb['qty']);	
				//$fg_pkp        = $_bpb['fg_pkp_wip'];
				//$ppn_percentage= $_bpb['ppn_wip'];						
				}else{
				$amount = ( $_bpb['price'] * $_bpb['qty']);
				//$fg_pkp        = $_bpb['fg_pkp_non_jasa'];
				//$ppn_percentage= $_bpb['ppn_non_jasa'];		
		
			}
				$ppn_percentage= $_bpb['ppn'];
				//$ppn_percentage= $_bpb['ppn_non_jasa'];	
			$curr = $_bpb['curr'];

			$total_amount = $total_amount + $amount;
			
            //list($coa_debit, $coa_credit) = $this->get_id_coa_ir_from_bpb($_bpb);
/* 			if($IsGeneral == '1'){
				//echo "123";
				list($coa_debit, $coa_credit) = $this->getIdCoaIrGeneral($_bpb);
			}else{
				list($coa_debit, $coa_credit) = $this->get_id_coa_ir_from_bpb($_bpb);
			} */
			if($IsGeneral == '1'){
				list($coa_debit, $coa_credit) = get_id_coa_general_ir($_bpb);
			}
				else if($IsGeneral == '2'){
				list($coa_debit, $coa_credit) = get_id_coa_wip_ir($_bpb);
				//echo "123";
			}			
			else if($IsGeneral == '3'){
				list($coa_debit, $coa_credit) = get_id_coa_fg_ir($_bpb);
				$_bpb['itemdesc'] = getDescription($bpbno,$_bpb[id_item]);
			}else{
				list($coa_debit, $coa_credit) = get_id_coa_ir_from_bpb($_bpb);
			}
			// GR
                $_debit = array(
                    'row_id' => $row_id++,
                    'id_journal' => $id_journal,
                    'id_coa' => $coa_debit['id'],
                    'nm_coa' => $coa_debit['nm'],
                    'curr' => $_bpb['curr'],
                    'debit' => $amount,
                    'credit' => 0,
                    'description' => $_bpb['itemdesc'],
                    'dateadd' => $dateadd,
                    'useradd' => $_SESSION['username'],
					'reff_doc' => $bpbno,
					'reff_doc2' => $reff_doc2,
					'id_bpb' => $_bpb['id_bpb'],
					'id_bpb' => $_bpb['SAVE_id_bpb'],
					'id_po' => $_bpb['SAVE_id_po'],
					'id_po_det' => $_bpb['SAVE_id_po_det'],
					'id_item' => $_bpb['SAVE_id_item'],
					'qty' => $_bpb['SAVE_qty'],
					'price' => $_bpb['SAVE_price'],
					'id_supplier' => $_bpb['SAVE_id_supplier'],
					'type_bpb' => $_bpb['SAVE_type_bpb'],
					'amount_original' => $_bpb['SAVE_amount_ori'],
					'ppn' => $_bpb['SAVE_ppn'],
                );
                // Piutang
                $_credit = array(
                    'row_id' => $row_id++,
                    'id_journal' => $id_journal,
                    'id_coa' => $coa_credit['id'],
                    'nm_coa' => $coa_credit['nm'],
                    'curr' => $_bpb['curr'],
                    'debit' => 0,
                    'credit' => $amount,
                    'description' => $_bpb['itemdesc'],
                    'dateadd' => $dateadd,
                    'useradd' => $_SESSION['username'],
					'reff_doc' => $bpbno,
					'reff_doc2' => $reff_doc2,
					'id_bpb' => $_bpb['id_bpb'],					'id_bpb' => $_bpb['SAVE_id_bpb'],
					'id_po' => $_bpb['SAVE_id_po'],
					'id_po_det' => $_bpb['SAVE_id_po_det'],
					'id_item' => $_bpb['SAVE_id_item'],
					'qty' => $_bpb['SAVE_qty'],
					'price' => $_bpb['SAVE_price'],
					'id_supplier' => $_bpb['SAVE_id_supplier'],
					'type_bpb' => $_bpb['SAVE_type_bpb'],
					'amount_original' => $_bpb['SAVE_amount_ori'],
					'ppn' => $_bpb['SAVE_ppn'],
					
                );

                $dataBpb[] = $_debit;
                $dataBpb[] = $_credit;

				
            }

/*   echo "<pre>";
print_r($dataBpb);
echo "</pre>";
die();    */
            foreach($dataBpb as $_bpb){

                $sql = "
                    INSERT INTO fin_journal_d
                      (id_journal, row_id, id_coa, nm_coa, curr, debit, credit
                      ,description, dateadd, useradd,reff_doc,reff_doc2,
					  id_bpb
					  ,id_po
					  ,id_po_det
					  ,id_item
					  ,qty
					  ,price
					  ,id_supplier
					  ,type_bpb
					  ,amount_original
					  ,n_ppn
					  
					  )
                    VALUES 
                      ('{$_bpb['id_journal']}', '{$_bpb['row_id']}', '{$_bpb['id_coa']}', '{$_bpb['nm_coa']}', '{$_bpb['curr']}', '{$_bpb['debit']}', '{$_bpb['credit']}'
                      ,'{$_bpb['description']}','{$_bpb['dateadd']}', '{$_bpb['useradd']}','{$_bpb['reff_doc']}','{$_bpb['reff_doc2']}'
					  ,'{$_bpb['id_bpb']}'
					  ,'{$_bpb['id_po']}'
					  ,'{$_bpb['id_po_det']}'
					  ,'{$_bpb['id_item']}'
					  ,'{$_bpb['qty']}'
					  ,'{$_bpb['price']}'
					  ,'{$_bpb['id_supplier']}'
					  ,'{$_bpb['type_bpb']}'
					  ,'{$_bpb['amount_original']}'
					  ,'{$_bpb['ppn']}'
                      )
                    
                ";

                // Insert detail
                // $Bm->query($sql);
				  $this->query($sql);
/* 				print_r($sql);
				die(); */
            }
			
			}
        }
    }

    /**
     * Update faktur pajak di tabel bpb
     * @param $bpbno
     * @param $fpno
     */
    public function update_fp($bpbno, $fpno){
       if($bpbno == ''){
           return;
       }

       $sql = "
            UPDATE bpb SET no_fp = '$fpno' WHERE bpbno_int = '$bpbno';
       ";

       $this->query($sql);
    }


}

// CONTROLLER BEGIN

$M = new Model($con_new);

$ch = new Coa_helper();

$id = isset($_GET['id']) ? $_GET['id'] : '';



if(isset($_POST['submit']) or isset($_POST['posting'])){

	if(ISSET($_POST['ppn']) || ISSET($_POST['pph']) ){
		$_POST['fg_tax'] == '1';
		if(!ISSET($_POST['ppn'])){
			$_POST['ppn'] = '0';
			
		}
		if(!ISSET($_POST['pph'])){
			$_POST['pph'] = '0';
			
		}		
	}else{
		if(!ISSET($_POST['fg_tax'])){
			$_POST['fg_tax'] == '1';
		}
		if(!ISSET($_POST['ppn'])){
			$_POST['ppn'] == '1';
		}
		if(!ISSET($_POST['pph'])){
			$_POST['pph'] == '1';
		}		
/* 		$_POST['ppn']    == '0';
		$_POST['pph']    == '0'; */
	}

	$multiple = json_decode($_POST['reff_doc']);
	//print_r($multiple);
    $company = $M->get_master_company();
    $data = array(
        'company' => $company->company,
		'type_journal' => $_POST['type_journal'],
		'fg_tax' => $_POST['fg_tax'],
		'ppn' => $_POST['ppn'],
		'pph' => $_POST['pph'],
        'period' => $_POST['period'],
        'num_journal' => '',//$_POST['num_journal'],
        'src_reference' => 'BPB-JOURNAL',
		'u_name' => $_SESSION['username'],
        'fg_intercompany' => $_POST['fg_intercompany'],
        'id_intercompany' => $_POST['id_intercompany'],
        'noinvoiceSupplier' => $_POST['noinvoiceSupplier'],
		'no_fp' => $_POST['fakturpajak'],
		'tgl_fp' => date('Y-m-d', strtotime($_POST['tglpajak'])),
		'tgl_invoice' => date('Y-m-d', strtotime($_POST['tglinvoice'])),
		'date_journal' => date('Y-m-d', strtotime($_POST['date_journal'])),
		'multiple' => $multiple,
    );
		$arr_ = explode('/',$data['period']);
		$data['period'] = sprintf('%02d', $arr_[0])."/".$arr_[1];
    if($status = $ch->general_validation($data)) {
        if ($_POST['mode'] == 'save') {
            $data['dateadd'] = date('Y-m-d H:i:s', time());
            $data['useradd'] = $_SESSION['username'];
            $journal_code = '';
            switch ($data['type_journal']) {
                case Config::$journal_usage['KONTRABON']:
                    $arr_count = count($data['multiple']->arraynya);				
 					if($arr_count > 0){
						for($i=0;$i<$arr_count;$i++){
						$type_nya = substr($data['multiple']->arraynya[$i]->bpbnoint,0,3);
							$bpb_check = check_bpb($data['multiple']->arraynya[$i]->bpbnoint,$type_nya,'14');
							$bpb_check['errcode'] = '00';
							if ($bpb_check['errcode'] == '01') {
								echo "BPB SUDAH ADA";
								die();
								echo "<script>alert('Error: Nomor Refference (BPB) sudah digunakan');</script>";
								$status = false; // Num journal already exists
								$row = json_decode(json_encode($data));
							}else{
								echo "Checking BPB...";
							}
						}	
					}else{
						 echo "alert('TIDAK ADA DATA!')</script>";
                        echo "<script>window.location.href='?mod=kb';</script>";
                        exit();
						
					} 
						$data['id_journal'] = generate_coa_id("NAG", "PK", $data['date_journal']);


						for($i=0;$i<$arr_count;$i++){
							$is_retur = check_retur($data['multiple']->arraynya[$i]->bpbnoint);
/* 							echo $is_retur;
							die(); */
								if($is_retur == '1'){
									$M->insert_bpb_ir_ret($data['multiple']->arraynya[$i]->bpbnoint, $data['id_journal'],$data['multiple']->arraynya[$i]->journal_reff);									
								}else{
									$M->insert_bpb_ir($data['multiple']->arraynya[$i]->bpbnoint, $data['id_journal'],$data['multiple']->arraynya[$i]->journal_reff);							
								}
							
							
							

					}
							insert_bpb_ir_pajak($data['id_journal']);
						
                        $M->save($data);

                       // $M->update_fp($data['reff_doc'], $data['no_fp']);

                        echo "<script>window.location.href='?mod=kb&id={$data['id_journal']}';</script>";
                        exit();					
					
					
					//$bpb_check = $M->check_bpb($data['reff_doc'], TRUE, $data['reff_doc2']);
                  /*   if ($bpb_check['errcode'] == '01') {
                        echo "<script>alert('Error: Nomor Refference (BPB) sudah digunakan');</script>";
                        $status = false; // Num journal already exists
                        $row = json_decode(json_encode($data));
                    } else {
                        $data['id_journal'] = generate_coa_id("NAG", "PK", $data['date_journal']);
//                        $M->insert_bpb_gr($data['reff_doc'], $data['id_journal']);
                        $M->insert_bpb_ir($data['reff_doc'], $data['id_journal']);
                        $M->save($data);

                        $M->update_fp($data['reff_doc'], $data['no_fp']);

                        echo "<script>window.location.href='?mod=jp&id={$data['id_journal']}';</script>";
                        exit();
                    } */
                    break;
                default:
                    $data['id_journal'] = generate_coa_id("NAG", $journal_code, $data['date_journal']);
                    $M->save($data);
                    echo "<script>window.location.href='?mod=kb&id={$data['id_journal']}';</script>";
                    exit();
            }
        } elseif ($_POST['mode'] == 'update') {
			if($_POST['mode2'] == 'ed_detail' ){
				$data['id_journal'] = $id;
				// echo "<pre>";
				// print_r(json_decode($_POST['json_pph']));
				// echo "</pre>";
				// die();
				//$M->delete_detail_journal_d($data['id_journal']);
			$M->update_fp($data['reff_doc'], $data['no_fp']);
            $journal = $M->get_journal($id);
			$pph_nya = json_decode($_POST['json_pph']);
				$M->delete_detail_journal_d($data['id_journal']);
				//$update_pph = $M->update_pph_detail($id,$pph_nya);
                    $arr_count = count($data['multiple']->arraynya);
 					if($arr_count > 0){
						for($i=0;$i<$arr_count;$i++){
						$type_nya = substr($data['multiple']->arraynya[$i]->bpbnoint,0,3);
							$bpb_check = check_bpb($data['multiple']->arraynya[$i]->bpbnoint,$type_nya,'14');
/* 							echo "<pre>";
							print_r($bpb_check);
							die(); */
							$bpb_check['errcode'] = '00';
							if ($bpb_check['errcode'] == '01') {

								echo "<script>alert('Error: Nomor Refference (BPB) sudah digunakan');</script>";
								$status = false; // Num journal already exists
								$row = json_decode(json_encode($data));
							}else{
								echo "Checking BPB...";
							}
						}	
					}else{
						 echo "alert('TIDAK ADA DATA!')</script>";
                        echo "<script>window.location.href='?mod=kb';</script>";
                        exit();
						
					} 



/* 						for($i=0;$i<$arr_count;$i++){
							print_r($data['id_journal']);
							//die();
							$M->insert_bpb_ir($data['multiple']->arraynya[$i]->bpbnoint, $data['id_journal'],$data['multiple']->arraynya[$i]->journal_reff);
					}	 */		
/* echo "<pre>";
							print_r($arr_count);
							die();	 */				
						for($i=0;$i<$arr_count;$i++){
							$is_retur = check_retur($data['multiple']->arraynya[$i]->bpbnoint);
								if($is_retur == '1'){
									$M->insert_bpb_ir_ret($data['multiple']->arraynya[$i]->bpbnoint, $data['id_journal'],$data['multiple']->arraynya[$i]->journal_reff);									
								}else{
/*  									echo "<pre>";
									print_r($data['multiple']);
									echo "</pre>"; 
									 die();*/
									$M->insert_bpb_ir($data['multiple']->arraynya[$i]->bpbnoint, $data['id_journal'],$data['multiple']->arraynya[$i]->journal_reff);							
								}
							
							
							

					}
							insert_bpb_ir_pajak($data['id_journal']);
							$update_pph = $M->update_pph_detail($id,$pph_nya);
							$M->update($id, $data);
				$status = "1";

                if($status) {
                    echo "<script>window.location.href='?mod=je';</script>";
                    exit();
                }
//            echo "<script>window.location.href='?mod=jp&id=$id';</script>";exit();
            


				
			}else{

					//$M->update_fp($data['reff_doc'], $data['no_fp']);
				$journal = $M->get_journal($id);
				if ($journal->num_journal != $data['num_journal'] and $M->get_journal_by_num($data['num_journal'])) {
					echo "<script>alert('Error: Nomor Dokumen sudah terdaftar');</script>";
					$status = false; // Num journal already exists
					$row = json_decode(json_encode($data));
				} else {
					$data['dateedit'] = date('Y-m-d H:i:s', time());
					$data['useredit'] = $_SESSION['username'];
						//print_r($_SESSION['username']);
					$pph_nya = json_decode($_POST['json_pph']);
						
						$update_pph = $M->update_pph_detail($id,$pph_nya);
					$status = $M->update($id, $data);
					//$M->update_fp($data['reff_doc'], $data['no_fp']);
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
//         		  echo "<script>window.location.href='?mod=jp&id=$id';</script>";exit();
				}				
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
        $list = $M->get_journal_items($id, $row->fg_post);
        $t_debit = $t_credit = 0;
        if(count($list)){
            foreach($list as $l){
                $t_debit += $l->debit;
                $t_credit += $l->credit;
            }
        }
    }
}

$acct_period = $ch->get_available_period();


// CONTROLLER END


?>
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<form method='post' name='form' id='_form' enctype='multipart/form-data' action="">
    <div class='box'>
        <div class='box-body'>
            <div class='row'>
                <div class='col-md-3'>
                    <div class='form-group'>
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
                        </select>*/  ?>
                    </div>
                    <div class='form-group'>
                        <label>Tipe Jurnal</label>
                        <select id='type_journal' class='form-control select2' name='type_journal'>
                            <option value="<?=Config::$journal_usage['KONTRABON']?>" ><?=Config::$journal_type[Config::$journal_usage['KONTRABON']]?></option>
                        </select>
                    </div>
                     <div class='form-group'>
                         <label>Tanggal Jurnal</label>
                         <input type='text' onchange="getPeriodAccounting(this)" id='datepicker1' autocomplete="off"  class='form-control' name='date_journal'
                                placeholder='Masukkan Tanggal Jurnal' value='<?=isset($row)?date('d M Y', strtotime($row->date_journal)):''?>'>
                     </div>
                     <div class='form-group'>
                         <label>No BPB</label>
                         <div class="input-group">
                         <input type='text' id='reff_doc' class='form-control' name='reff_doc'
                                placeholder='Masukkan Referensi Dokumen' value='<?=isset($row)?$row->reff_doc:''?>' readonly>
						<input type="hidden" name="json_pph" id="json_pph">		
                         <a onclick="modal_ref()" id="klik_saya" style="cursor: pointer" class="input-group-addon" >...</a>
                         </div>
                     </div>

                     <div class='form-group' style="display:none">
                         <label>No Jurnal Reference</label>
                         <input type='text' id='reff_doc2' class='form-control' name='reff_doc2'
                                placeholder='No Jurnal' value='<?=isset($row)?$row->reff_doc2:''?>' readonly>
                     </div>


                     <div class='form-group'>
                         <label>Mata Uang</label>
						 <input type='text' name='matauang' id='curr' class='form-control' readonly placeholder='auto'>
			<!--			<select class="form-control" id="curr" name="matauang" readonly="">
                                <option>IDR</option>
                                <option>USD</option>
								<option>EUR</option>
								<option>JPN</option>
							</select>
-->            
					</div>
                     <div class='form-group'>
                         <label>Amount</label>
                         <input type='text' id='amount' class='form-control' name='amount' autocomplete="off"
                                placeholder='Masukkan Nilai' value='' readonly>
                     </div>



                </div>
                 <div class='col-md-3'>
                     <div class='form-group'>
                         <label>Nomor Jurnal</label>
                         <input type='text' id='id_journal' class='form-control' name='id_journal' readonly
                                placeholder='(Auto)' value='<?=(isset($row) and isset($row->id_journal))?$row->id_journal:''?>'>
                     </div>


                     <div class='form-group'>
                         <label>Faktur Pajak</label>
                         <input type='text' id='fakturpajak' class='form-control' name='fakturpajak' autocomplete="off"
                                placeholder='Masukkan Nomor Faktur' value=''>
                     </div>
                   <div class='form-group' style="display:none">
                         <label>Supplier</label>
                         <input type='text' id='customer' class='form-control' name='customer' autocomplete="off" readonly
                                placeholder='Masukkan Nama Supplier' value=''>
                     </div>					 

                     <div class='form-group' >
                         <label>Tanggal Faktur Pajak</label>
                         <input type='text' id='tglpajak' class='form-control' name='tglpajak' autocomplete="off" 
                                placeholder='Masukkan Tanggal Faktur Pajak' value=''>
                     </div>
					 <div class='form-group' >
                         <label>No.Invoice</label>
                         <input type='text' id='noinvoiceSupplier' class='form-control' name='noinvoiceSupplier' 
                                placeholder='No Invoice' value=''>
                     </div>
                     <div class='form-group' >
                         <label>Tanggal Invoice</label>
                         <input type='text' id='tglinvoice' class='form-control' name='tglinvoice' autocomplete="off" 
                                placeholder='Masukkan Invoice' value=''>
                     </div>
					 
                     <div class='form-group' style="display:none"> 
                         <label>Tgl Jatuh Tempo</label>
                         <input type='text' id='tgljatuhtempoo' class='form-control' name='tgljatuhtempo' autocomplete="off" readonly
                                placeholder='tgl jatuh tempo' value=''>
                     </div>


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

                    <div class='form-group' >
                        <label><input type='checkbox' id='fg_tax' class='' onclick="toggleTax()" name='fg_tax' value="1">Tax</label>
						<div style="display:none">
						<select disabled id='ppn' name='ppn' class="form-control select2" > 
						</select>
						</div>
						</div>
					<!-- <div class='form-group' >
						<select disabled id='pph' name='pph' class="form-control select2">

						</select>					 
					 </div>						
						-->
                    </div>					
					
					
              
                <?php if($id):?>
                    <div class='col-md-3'>
                        <div class='form-group'>
                            <label>Status Posting: </label><br><?=(isset($row) and @$row->fg_post)? 'POSTED':'PARKED'?><br>
                            <label>Create date: </label><br>
                            <?=($row->dateadd) ? $row->dateadd.' ('.$row->useradd.')' : '-'?><br>
                            <label>Edit date: </label><br>
                            <?=($row->dateedit) ? $row->dateedit.' ('.$row->useredit.')' : '-'?><br>
                            <label>Post date: </label><br>
                            <?=($row->date_post) ? $row->date_post.' ('.$row->user_post.')' : '-'?>
                        </div>
                    </div>
                <?php endif;?>
                <div class="col-md-12">
                    <input type='hidden' name='mode' value='<?=$id  ? 'update' : 'save';?>'>
					<input type='hidden' id ='ed_detail' name='mode2' value=''>
                    <?php if(!isset($row) or ($id==false)):?>
                    <button style='display:none'  id='_submit' type='submit' name='submit' >Simpan</button>
					<a href='#' onclick='validasi()'   class='btn btn-primary validasi_proses'>Simpan</a>
                    <?php endif;?>
                    <?php if(isset($row) and !@$row->fg_post and $id):?>
                    <button style='display:none'  id='_submit' type='submit' name='submit' >Simpan</button>
					<a href='#' onclick='ed_detail()'  class='btn btn-primary validasi_proses'>Generate Detail</a>
					<a href='#' onclick='validasi()'  class='btn btn-primary validasi_proses'>Simpan</a>					
                    <button type='submit' onclick='validasi()' name='posting' class='btn btn-primary validasi_proses'>Posting</button>
					
                    <?php endif;?>
					<a href="#" onclick="getListBpbRekap()" data-toggle='modal' data-target='#myModalLIST' class='btn btn-info validasi_proses'>Detail</a>
					<a href="pdf_kontrabon.php?&id=<?=$row->id_journal?>" class="btn btn-warning validasi_proses" title="Print" ><i class='fa fa-print'></i> Cetak</a>
                </div>
			  </div>	
            </div>
        </div>
    </div>
</form>

<?php if($id):?>
    <?php include("journal_entry_form_item.inc_cashbank.php"); ?>
<?php endif;?>



<div id="myModalLIST" class="modal fade " role="dialog">
  <div class="modal-dialog modal-dialog modal-lg" role="document">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="HeaderModal">List Bpb</h4>
      </div>
      <div class="modal-body ">
		<div id="ListModal">
		
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modalBpb" data-backdrop="static"  data-keyboard="false"  role="dialog" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Lookup Bpb</h4>
            </div>
            <div class="modal-body">
                <div class='form-group'>
                    <div class="row">
                        <div class="col-md-4">
                            <label>No Bpb</label>
                            <input type='text' class='form-control' id="bpbNo" >
                        </div>
                        <div class="col-md-4">
                            <label>Supplier</label>
                            <input type='text' class='form-control' id="suppliercari" >
                        </div>
                        <div class="col-md-4">
                            <label>Bpb Date</label>
                            <input type='text' class='form-control' id="bpbdatecari" >
                        </div>						
                        <div class="col-md-4">
                            <label>No Bpb Internal</label>
                            <input type='text' class='form-control' id="bpbNoInternal" >
                        </div>
                        <div class="col-md-4">
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
							<th>*</th>
							<th>Tanggal BPB</th>
							<th>Nama Supplier</th>
                            <th>BPB No</th>
                            <th>BPB No Internal</th>
                            <th>PO No</th>
							<th>&nbsp;</th>
							<th>Nilai</th>
                            <th>Num of Items</th>
                          <!--  <th>Action</th> -->
                        </thead>
                        <hr>
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
<script src="js/KontraBon.js"></script>
<script src="js/CashBank.js"></script>
<script>

    function modal_ref(){
		console.log($supplier);
/* 		if($split_nya[1]){
			alert("BPB sudah dipilih!");
			getListBpbRekap();
			$("#myModalLIST").modal();
			
			return false;
		} */
        var tipe_jurnal = $('#type_journal').val();

        switch (tipe_jurnal){
            case "14":
                modal_bpb();
                break;
            default:
                if(tipe_jurnal==""){
                    alert("Pilih tipe jurnal terlebih dahulu");;
                }else {
                    alert("Pilihan tidak tersedia untuk tipe jurnal tersebut");
                }
        }
    }
    function modal_bpb(){
        $('#modalBpb').modal('show');
        $("#modalBpb .bpbresult table tbody").html('');
        $("#bpbNo").val('');
        $("#bpbNoInternal").val('');
		if($supplier.arraynya.length > 0){
			lookup_bpb();
		
		}		
    }


	async function CheckList(){
		 if($supplier.arraynya.length > 0){
		 for(var t=0;t<$supplier.arraynya.length;t++){
			$('#'+$supplier.arraynya[t].key).prop('checked', true);
		}
		}

	}

    function lookup_bpb(){
			$('#modalBpb').modal({backdrop: 'static', keyboard: false});
			if($split_nya[1]){
				$id_journal_exist = $split_nya[1];
			}else{
				$id_journal_exist = 0;
			}
			

        var request = $.ajax
        ({  type: "POST",
            url: "ajax_fin.php?mdajax=lookup_posted_bpb",
            data: "bpbno=" +$("#bpbNo").val()+"&bpbno_internal="+$("#bpbNoInternal").val()+"&posted=1&supplier="+$("#suppliercari").val()+"&bpbdate="+$("#bpbdatecari").val()+"&id_journal_exist="+$id_journal_exist,
            dataType: "json",
            async: false
        }).responseText;
        request = JSON.parse(request);
        var bpb_list = [];
        if(request.status == true){
            bpb_list = request.data; 
			console.log(bpb_list);
            var rows = '';
            if(bpb_list.length) {
                for (var i in bpb_list) {
                    rows += '<tr>';
					 rows += '<td><input onclick="supplierCheck(this)"  data-bpbintnya="'+bpb_list[i].bpbno_int+'" data-bpbnya="'+ bpb_list[i].bpbno +'" data-nilainya="'+bpb_list[i].bpb_amount+'" value="'+bpb_list[i].Id_Supplier+'" data-suppliernya="'+bpb_list[i].Supplier+'" type="checkbox" id="'+bpb_list[i].bpbno+'"></td>';
					rows += '<td>' + bpb_list[i].bpbdate + '</td>';
					rows += '<td>' + bpb_list[i].Supplier + '</td>';
                    rows += '<td>' + bpb_list[i].bpbno + '</td>';
                    rows += '<td>' + bpb_list[i].bpbno_int + '</td>';
                    rows += '<td>' + bpb_list[i].pono + '</td>';
					rows += '<td>' + bpb_list[i].curr + '</td>';
					rows += '<td style="text-align:right">'+  number_format(bpb_list[i].bpb_amount) + '</td>';
                    rows += '<td>' + bpb_list[i].item_count + '</td>';
                    //rows += '<td><a onclick="select_bpb(' + "'" + bpb_list[i].bpbno_int + "'" + ')" class="btn btn-info">Choose</td>';
                    rows += '</tr>';
                }
            }else{
                rows = '<tr><td colspan="4" class="text-center"><em>--Bpb not found--</em></td></tr>';
            }
            $("#modalBpb .bpbresult table tbody").html(rows);
        }else{
            alert(request.message);
        }
		CheckList();
    }
    function select_bpb(bpbno){
        $('#reff_doc').val(bpbno);
        load_reference();
        $('#modalBpb').modal('hide');
    }
    function toggleInterCompany(){
        if($('#fg_intercompany').is(':checked')){
            $('#id_intercompany').attr('readonly', false);
        }else{
            $('#id_intercompany').attr('readonly', true);
        }
    }
	
	
    async function ed_detail() {
		$("#ed_detail").val("ed_detail");
		$("#datepicker1").attr("disabled",false);
		convert_totext().then(function(responnya) {
		$("#json_pph").val(JSON.stringify($array_pph));
		var date_journal = $("#datepicker1").val();
		var period = $("#periodpicker_").val();  
        var type_journal = $("#type_journal").val();
		$send_key = 1;		
        if (period == '') {
            alert('Error: Periode Akuntansi tidak boleh kosong');
            document.form.period.focus();
			$send_key = 0;
            return false; 
        }if (type_journal == '') {
            alert('Error: Tipe Jurnal tidak boleh kosong');
			if($send_key == 0){
				$send_key = 0;
			}
            document.form.type_journal.focus();

        }if(date_journal == ''){
			if($send_key == 0){
				$send_key = 0;
			}			
	            alert('Error: Tanggal Journal Harus Diisi');
				return false;
		}
        <?php /*if(isset($t_debit)):?>
        else if ((<?=@$t_debit?> + <?=@$t_credit?>) == 0) {
            alert('Error: Nilai Debit dan Kredit tidak boleh kosong');
            valid = false;
        } else if (<?=@$t_debit?> != <?=@$t_credit?>) {
            alert('Error: Debit dan Kredit harus balance');
            valid = false;
        }
        <?php endif;*/?>
        if (<?=(isset($row) and isset($row->fg_post) and $row->fg_post)?'true':'false'?>) {
            alert('Error: Journal sudah di posting');
            return false;
        } else { };
			console.log(responnya);
	

if($send_key  == '1'){
			$("#reff_doc").val(responnya);
		$( "#_submit" ).trigger( "click" ); 
	
	
}else{
	alert("Periksa Kembali Data");
	
}	
				
		});
		

	return false;


    }	

    async function validasi() {
/*      var period = document.form.period.value;
        var type_journal = document.form.type_journal.value;
        var num_journal = document.form.num_journal.value; */
        //var date_journal = document.form.date_journal.value;
       // var reff_doc = document.form.reff_doc.value; 
		$("#datepicker1").attr("disabled",false);
		convert_totext().then(function(responnya) {
		$("#json_pph").val(JSON.stringify($array_pph));
		var date_journal = $("#datepicker1").val();
		var period = $("#periodpicker_").val();  
        var type_journal = $("#type_journal").val();
		$send_key = 1;		
        if (period == '') {
            alert('Error: Periode Akuntansi tidak boleh kosong');
            document.form.period.focus();
			$send_key = 0;
            return false; 
        }if (type_journal == '') {
            alert('Error: Tipe Jurnal tidak boleh kosong');
			if($send_key == 0){
				$send_key = 0;
			}
            document.form.type_journal.focus();

        }if(date_journal == ''){
			if($send_key == 0){
				$send_key = 0;
			}			
	            alert('Error: Tanggal Journal Harus Diisi');
				return false;
		}
        <?php /*if(isset($t_debit)):?>
        else if ((<?=@$t_debit?> + <?=@$t_credit?>) == 0) {
            alert('Error: Nilai Debit dan Kredit tidak boleh kosong');
            valid = false;
        } else if (<?=@$t_debit?> != <?=@$t_credit?>) {
            alert('Error: Debit dan Kredit harus balance');
            valid = false;
        }
        <?php endif;*/?>
        if (<?=(isset($row) and isset($row->fg_post) and $row->fg_post)?'true':'false'?>) {
            alert('Error: Journal sudah di posting');
            return false;
        } else { };
			console.log(responnya);
	

if($send_key  == '1'){
			$("#reff_doc").val(responnya);
		$( "#_submit" ).trigger( "click" ); 
	
	
}else{
	alert("Periksa Kembali Data");
	
}	
				
		});
		

	return false;


		



      /*   if (period == '') {
            alert('Error: Periode Akuntansi tidak boleh kosong');
            document.form.period.focus();
            valid = false;
        } else if (type_journal == '') {
            alert('Error: Tipe Jurnal tidak boleh kosong');
            document.form.type_journal.focus();
            valid = false;
        } /*else if (num_journal == '') {
            alert('Error: Nomor Dokumen tidak boleh kosong');
            document.form.num_journal.focus();
            valid = false;
        }*//*  else if (date_journal == '') {
            alert('Error: Tanggal Dokumen tidak boleh kosong');
            document.form.date_journal.focus();
            valid = false;
        } */
        <?php /*if(isset($t_debit)):?>
        else if ((<?=@$t_debit?> + <?=@$t_credit?>) == 0) {
            alert('Error: Nilai Debit dan Kredit tidak boleh kosong');
            valid = false;
        } else if (<?=@$t_debit?> != <?=@$t_credit?>) {
            alert('Error: Debit dan Kredit harus balance');
            valid = false;
        }
        <?php endif;*/?>
      /*   else if (<?=(isset($row) and isset($row->fg_post) and $row->fg_post)?'true':'false'?>) {
            alert('Error: Journal sudah di posting');
            valid = false;
        } else valid = false; */
 
       // return valid;
    }
    function load_reference(){
        var bpbno = $('#reff_doc').val();

        if(!bpbno){
            return false;
        }

        var request = $.ajax
        ({  type: "POST",
            url: "ajax_fin.php?mdajax=get_posted_bpb",
            data: "bpbno_internal="+bpbno_internal,
            dataType: "json",
            async: false
        }).responseText;

        request = JSON.parse(request);

        if(request.status == true) {
            var bpb = request.data;

            //$('#amount').val(bpb.bpb_amount);
           // $('#noinvoice').val(bpb.invno);
           // $('#tglinvoice').val(bpb.bpbdate);
           // $('#fakturpajak').val(bpb.no_fp);
           // $('#curr').val(bpb.curr);
          //  $('#customer').val(bpb.supplier);
            $('#tgljatuhtempoo').val(bpb.due_date);
          //  $('#reff_doc2').val(bpb.id_journal);
        }else{
//            alert("Error: BPB not available");
        }
    }

    function load_reference_populate(){
        var bpbno = $('#reff_doc').val();

        if(!bpbno){
            return false;
        }

        var request = $.ajax
        ({  type: "POST",
            url: "ajax_fin.php?mdajax=get_posted_bpb_populate_form",
            data: "bpbno_internal="+bpbno,
            dataType: "json",
            async: false
        }).responseText;

        request = JSON.parse(request);

        if(request.status == true) {
            var bpb = request.data;

            $('#amount').val(bpb.bpb_amount);
            $('#noinvoice').val(bpb.invno);
            $('#tglinvoice').val(bpb.bpbdate);
            $('#fakturpajak').val(bpb.no_fp);
            $('#curr').val(bpb.curr);
            $('#customer').val(bpb.supplier);
            $('#tgljatuhtempoo').val(bpb.due_date);
            $('#reff_doc2').val(bpb.id_journal);
        }else{
//            alert("Error: BPB not available");
        }
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
        load_reference_populate();
       $("#periodpicker").datepicker( {
            format: "mm/yyyy",
            viewMode: "months",
            minViewMode: "months",
            autoclose: true,
        });  /*
		.on('changeDate', function(e) {
            if(!check_period(e.format())){
                alert("Periode Akuntansi "+e.format()+" belum dibuka");
                $("#periodpicker").val('');
            }
        }); */
    });
</script>