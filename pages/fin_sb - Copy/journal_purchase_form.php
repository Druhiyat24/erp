<?php
// Session Checking
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("F_P_Pembelian","userpassword","username='$user'");
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
        $sql = "
            INSERT INTO fin_journal_h 
              (company, period, id_journal, num_journal, date_journal, type_journal, reff_doc, reff_doc2, src_reference, fg_intercompany, id_intercompany
              ,dateadd, useradd)
            VALUES 
              ('{$d['company']}', '{$d['period']}', '{$d['id_journal']}', '{$d['num_journal']}', '{$d['date_journal']}'
              , '{$d['type_journal']}', '{$d['reff_doc']}', '{$d['reff_doc2']}', '{$d['src_reference']}', '{$d['fg_intercompany']}', '{$d['id_intercompany']}'
             ,'{$d['dateadd']}', '{$d['useradd']}')
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
              useredit = '{$data['useredit']}'
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

    public function insert_bpb_gr($bpbno, $id_journal)
    {
        $bpb_request = $this->check_bpb($bpbno);
        if($bpb_request['status']){
            $dataBpb = array();
            $bpb = $bpb_request['data'];
            $row_id = 1;
            $dateadd = date('Y-m-d H:i:s', time());
            $user = $_SESSION['username'];
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
                    'useradd' => $user,
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
                    'useradd' => $user,
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

    public function insert_bpb_ir($bpbno, $id_journal)
    {
        $bpb_request = $this->check_bpb($bpbno, FALSE);

        if($bpb_request['status']){
            $dataBpb = array();
            $bpb = $bpb_request['data'];
            $row_id = $this->get_next_item_id($id_journal);
            $dateadd = date('Y-m-d H:i:s', time());
            $user = $_SESSION['username'];
            foreach($bpb as $_bpb){
                $_bpb = (array) $_bpb;
                $amount = $_bpb['price'] * $_bpb['qty'];
                list($coa_debit, $coa_credit) = $this->get_id_coa_ir_from_bpb($_bpb);

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
                    'useradd' => $user,
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
                    'useradd' => $user,
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




function get_id_coa_general($id_journal){
		 global $con_new;
	    $Bm = new Basemodel($con_new);
$q = "SELECT 
	 JOURNAL.id_journal
	,JOURNAL.reff_doc
	,JOURNAL.goods_code
	,JOURNAL.code
	,JOURNAL.area
	,JOURNAL.Supplier
	,JOURNAL.vendor_cat
	,MAP.id_coa_debit_gr
	,MAP.id_coa_credit_gr
	,COA_DEBIT.id_coa
	,COA_DEBIT.nm_coa
	,COA_CREDIT.id_coa
	,COA_CREDIT.nm_coa
FROM(
		SELECT FH.id_journal
			,FH.reff_doc,MI.goods_code
			,MI.code
			,if(MS.area !='I','L',MS.area)area
			,MS.Supplier
			,MS.vendor_cat
		FROM fin_journal_h FH
			LEFT JOIN(
				SELECT id_supplier,id_item,bpbno_int FROM bpb 
			)BPB ON FH.reff_doc = BPB.bpbno_int
			LEFT JOIN (
				SELECT id_item,itemdesc,goods_code,SUBSTR(goods_code,1,3)code FROM masteritem
			)MI ON MI.id_item = BPB.id_item
			LEFT JOIN (
				SELECT Id_Supplier,Supplier,area,vendor_cat FROM mastersupplier
			)MS ON BPB.id_supplier = MS.Id_Supplier
)JOURNAL
	LEFT JOIN(
		SELECT n_code
				,n_vendor_cat
				,id_coa_debit_gr
				,id_coa_credit_gr FROM mapping_coa_gen
	)MAP 
	ON MAP.n_code = JOURNAL.code AND MAP.n_vendor_cat = JOURNAL.vendor_cat
	LEFT JOIN(SELECT id_coa,nm_coa FROM mastercoa)COA_DEBIT ON MAP.id_coa_debit_gr = COA_DEBIT.id_coa
	LEFT JOIN(SELECT id_coa,nm_coa FROM mastercoa)COA_CREDIT ON MAP.id_coa_credit_gr = COA_CREDIT.id_coa
	WHERE id_journal = 'NAG-PJ-1910-00002'";
    $mapping_coa = $Bm->query($q)->row();
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
    $mapping_coa = $Bm->query($q)->row();
}
    $coa_debit = array(
        'id' => $mapping_coa->id_coa_debit_gr,
        'nm' => $mapping_coa->nama_coa_gr_deb
    );
    $coa_credit = array(
        'id' => $mapping_coa->id_coa_credit_gr,
        'nm' => $mapping_coa->nama_coa_gr_cre
    );
	





    return array(
        $coa_debit,
        $coa_credit
    );
	
	
}


}

// CONTROLLER BEGIN

$M = new Model($con_new);

$ch = new Coa_helper();

$id = isset($_GET['id']) ? $_GET['id'] : '';
if(isset($_POST['submit']) or isset($_POST['posting'])){
    $company = $M->get_master_company();
    $data = array(
        'company' => $company->company,
        'period' => $_POST['period'],
        'num_journal' => '',//$_POST['num_journal'],
        'date_journal' => date('Y-m-d', strtotime($_POST['date_journal'])),
        'type_journal' => $_POST['type_journal'],
        'reff_doc' => $_POST['reff_doc'],
        'reff_doc2' => $_POST['reff_doc2'],
        'src_reference' => 'BPB-JOURNAL',
        'fg_intercompany' => $_POST['fg_intercompany'],
        'id_intercompany' => $_POST['id_intercompany'],
        'no_fp' => $_POST['fakturpajak'],
    );

    if($status = $ch->general_validation($data)) {
        if ($_POST['mode'] == 'save') {
            $data['dateadd'] = date('Y-m-d H:i:s', time());
            $data['useradd'] = $user;
            $journal_code = '';

            switch ($data['type_journal']) {
                case Config::$journal_usage['PURCHASE']:
                    $bpb_check = $M->check_bpb($data['reff_doc'], TRUE, $data['reff_doc2']);
                    if ($bpb_check['errcode'] == '01') {
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
                    }
                    break;
                default:
                    $data['id_journal'] = generate_coa_id("NAG", $journal_code, $data['date_journal']);
                    $M->save($data);
                    echo "<script>window.location.href='?mod=jp&id={$data['id_journal']}';</script>";
                    exit();
            }
        } elseif ($_POST['mode'] == 'update') {
            $journal = $M->get_journal($id);
            if ($journal->num_journal != $data['num_journal'] and $M->get_journal_by_num($data['num_journal'])) {
                echo "<script>alert('Error: Nomor Dokumen sudah terdaftar');</script>";
                $status = false; // Num journal already exists
                $row = json_decode(json_encode($data));
            } else {
                $data['dateedit'] = date('Y-m-d H:i:s', time());
                $data['useredit'] = $user;

                $status = $M->update($id, $data);
                $M->update_fp($data['reff_doc'], $data['no_fp']);
                if (isset($_POST['posting'])) {
                    $data['id_journal'] = $id;
                    if($status = $ch->posting_validation($data)) {
                        $data = array(
                            'date_post' => date('Y-m-d H:i:s', time()),
                            'user_post' => $user
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
<form method='post' name='form' enctype='multipart/form-data' action="" onsubmit='return validasi()'>
    <div class='box'>
        <div class='box-body'>
            <div class='row'>
                <div class='col-md-3'>
                    <div class='form-group'>
                        <label>Periode Akuntansi</label>
                        <input type='text' id='periodpicker' class='form-control' name='period' autocomplete="off" onchange="getPeriodAccounting(this)"
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
                    <div class='form-group' style="display:none">
                        <label>Tipe Jurnal</label>
                        <select id='type_journal' class='form-control select2' name='type_journal'>
                            <option value="<?=Config::$journal_usage['PURCHASE']?>" ><?=Config::$journal_type[Config::$journal_usage['PURCHASE']]?></option>
                        </select>
                    </div>

                     <div class='form-group'>
                         <label>Tanggal Jurnal</label>
                         <input type='text' id='datepicker1' autocomplete="off" disabled class='form-control' name='date_journal'
                                placeholder='Masukkan Tanggal Jurnal' value='<?=isset($row)?date('d M Y', strtotime($row->date_journal)):''?>'>
                     </div>
                     <div class='form-group'>
                         <label>No BPB</label>
                         <div class="input-group">
                         <input type='text' id='reff_doc' class='form-control' name='reff_doc'
                                placeholder='Masukkan Referensi Dokumen' value='<?=isset($row)?$row->reff_doc:''?>' readonly>
                         <a onclick="modal_ref()" style="cursor: pointer" class="input-group-addon" >...</a>
                         </div>
                     </div>

                     <div class='form-group'>
                         <label>No Jurnal Reference</label>
                         <input type='text' id='reff_doc2' class='form-control' name='reff_doc2'
                                placeholder='No Jurnal' value='<?=isset($row)?$row->reff_doc2:''?>' readonly>
                     </div>


                     <div class='form-group'>
                         <label>Mata Uang</label>
							<select class="form-control" id="curr" name="matauang" readonly="">
                                <option>IDR</option>
                                <option>USD</option>
								<option>EUR</option>
								<option>JPN</option>
							</select>
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
                         <label>No.Invoice</label>
                         <input type='text' id='noinvoice' class='form-control' name='noinvoice' readonly
                                placeholder='No Invoice' value=''>
                     </div>
                     <div class='form-group'>
                         <label>Tanggal Invoice</label>
                         <input type='text' id='tglinvoice' class='form-control' name='inv_date' autocomplete="off" readonly
                                placeholder='Masukkan Invoice' value=''>
                     </div>
  

                     <div class='form-group'>
                         <label>Faktur Pajak</label>
                         <input type='text' id='fakturpajak' class='form-control' name='fakturpajak' autocomplete="off"
                                placeholder='Masukkan Nomor Faktur' value=''>
                     </div>
                   <div class='form-group'>
                         <label>Supplier</label>
                         <input type='text' id='customer' class='form-control' name='customer' autocomplete="off" readonly
                                placeholder='Masukkan Nama Supplier' value=''>
                     </div>					 
					 
                     <div class='form-group'>
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
                    <input type='hidden' name='mode' value='<?=$id ? 'update' : 'save';?>'>
                    <?php if(!isset($row) or ($id==false)):?>
                    <button type='submit' name='submit' class='btn btn-primary validasi_proses'>Simpan</button>
                    <?php endif;?>
                    <?php if(isset($row) and !@$row->fg_post and $id):?>
                    <button type='submit' name='submit' class='btn btn-primary validasi_proses'>Simpan</button>
                    <button type='submit' name='posting' class='btn btn-primary validasi_proses'>Posting</button>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>
</form>

<?php if($id):?>
    <?php include("journal_entry_form_item.inc.php");?>
<?php endif;?>

<div class="modal fade" id="modalBpb"  role="dialog" >
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
							<th>Tanggal BPB</th>
							<th>Nama Supplier</th>
                            <th>BPB No</th>
                            <th>BPB No Internal</th>
                            <th>PO No</th>
                                <th>Action</th>
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

<script>

    function modal_ref(){
        var tipe_jurnal = $('#type_journal').val();

        switch (tipe_jurnal){
            case "2":
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
    }
    function lookup_bpb(){
        var request = $.ajax
        ({  type: "POST",
            url: "ajax_fin.php?mdajax=lookup_posted_bpb",
            data: "bpbno=" +$("#bpbNo").val()+"&bpbno_internal="+$("#bpbNoInternal").val()+"&posted=1",
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
					rows += '<td>' + bpb_list[i].bpbdate + '</td>';
					rows += '<td>' + bpb_list[i].supplier + '</td>';
                    rows += '<td>' + bpb_list[i].bpbno + '</td>';
                    rows += '<td>' + bpb_list[i].bpbno_int + '</td>';
                    rows += '<td>' + bpb_list[i].pono + '</td>';
                    rows += '<td>' + bpb_list[i].item_count + '</td>';
                    rows += '<td><a onclick="select_bpb(' + "'" + bpb_list[i].bpbno_int + "'" + ')" class="btn btn-info">Choose</td>';
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
    function validasi() {
        var period = document.form.period.value;
        var type_journal = document.form.type_journal.value;
        var num_journal = document.form.num_journal.value;
        var date_journal = document.form.date_journal.value;
        var reff_doc = document.form.reff_doc.value;
        if (period == '') {
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
        }*/ else if (date_journal == '') {
            alert('Error: Tanggal Dokumen tidak boleh kosong');
            document.form.date_journal.focus();
            valid = false;
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
        else if (<?=(isset($row) and isset($row->fg_post) and $row->fg_post)?'true':'false'?>) {
            alert('Error: Journal sudah di posting');
            valid = false;
        } else valid = true;

        return valid;
    }
    function load_reference(){
        var bpbno = $('#reff_doc').val();

        if(!bpbno){
            return false;
        }

        var request = $.ajax
        ({  type: "POST",
            url: "ajax_fin.php?mdajax=get_posted_bpb",
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