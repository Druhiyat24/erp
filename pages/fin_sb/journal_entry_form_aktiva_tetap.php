<?php
// Session Checking
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$_SESSION['username']=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("F_P_Fixed_Asset","userpassword","username='$_SESSION[username]'");
if ($akses=="0")
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>";}
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
            SELECT jh.*,ma.* FROM
            fin_journal_h jh
			LEFT JOIN (masteractiva ma) ON jh.id_journal = ma.id_journal
			
			
            WHERE jh.id_journal = '$id';
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

    public function check_bpb($id, $checking = TRUE){
        $used = $this->query("SELECT reff_doc FROM fin_journal_h WHERE reff_doc = '$id'")->row();

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


    public function getNamaAkunAktiva($idAkunAktiva){
        $q = $this->query("SELECT id_coa,nm_coa FROM mastercoa WHERE id_coa = '$idAkunAktiva'")->row();
		$Nama = $q->nm_coa;
		return $Nama;
    }
    public function getNamaDepartment($idDepartment){
        $q = $this->query("SELECT id_cost_dept,nm_cost_dept FROM mastercostdept WHERE id_cost_dept = '$idDepartment'")->row();
		$Nama = $q->nm_cost_dept;

		return $Nama;
    }
    public function getNamaSubDepartment($idSubDepartment){
        $q = $this->query("SELECT id_cost_sub_dept,nm_cost_sub_dept FROM mastercostsubdept WHERE id_cost_sub_dept = '$idSubDepartment'")->row();
		$Nama = $q->nm_cost_sub_dept;
		return $Nama;
    }
    public function save($d)
    {
		//print_r($d);
		$namaakunaktiva = $this->getNamaAkunAktiva($d['akunactiva']);
		$namadepartment = $this->getNamaDepartment($d['department']);
		$namasubdepartment = $this->getNamaSubDepartment($d['subdepartment']);


		$explodedate = explode("/",$d['tanggalbeli']);
		$d['tanggalbeli'] = $explodedate[2].'-'.$explodedate[1].'-'.$explodedate[0];
		$explodedate = explode("/",$d['tanggalpakai']);
		$d['tanggalpakai'] = $explodedate[2].'-'.$explodedate[1].'-'.$explodedate[0];
		$d['nilai'] = str_replace(".","",$d['nilai']);

		$sql2 = "
            INSERT INTO masteractiva 
              (id_journal,kd_aktiva, nm_aktiva
					,v_tipeactiva
					,d_tanggalbeli
					,d_tanggalpakai
					,d_umurtahunactiva
					,d_umurbulanactiva
					,v_akunactiva
					,v_akunakumulasipenyusutan
					,v_akunbiayapenyusutan			
					
					,n_qty
					,n_nilai
					,n_iddept
					,n_idsupdept							
			  )
            VALUES (
			   '".$d['id_journal']."'
              ,'".$d['kodeaktiva']."'
			  ,'".$d['namaaktiva']."'
			  ,'".$d['tipeaktiva']."'
			  ,'".$d['tanggalbeli']."'
			  ,'".$d['tanggalpakai']."'
			  ,'".$d['umurtahunactiva']."'
			  ,'".$d['umurbulanactiva']."'
			  ,'".$d['akunactiva']."'
			  ,'".$d['akunakumulasipenyusutan']."'
			  ,'".$d['akunbiayapenyusutan']."'

			  ,'".$d['qty']."'
			  ,'".$d['nilai']."'
			  ,'".$d['department']."'
			  ,'".$d['subdepartment']."'			  
            );
        ";
		$this->query($sql2);
		$myTotal = $d['qty'] * $d['nilai'];
		$date = date("Y-m-d");
/* insert to jurnal_fin_d */
        $sql3 = "
            INSERT INTO fin_journal_d
              (id_journal, row_id, id_coa, nm_coa, curr, debit
              ,id_cost_dept, nm_cost_dept, id_cost_sub_dept, nm_cost_sub_dept
              ,description, dateadd, useradd)
            VALUES 
              ('{$d['id_journal']}', '1', '{$d['akunactiva']}', '{$namaakunaktiva}' ,'IDR', '{$myTotal}'
              ,'{$d['department']}','{$namadepartment}', '{$d['subdepartment']}', '{$namasubdepartment}'
              ,'{$d['reff_doc']}','{$date}', '{$_SESSION['username']}'
              )
            ;
        ";


        $this->query($sql3);



  

        $sql = "
            INSERT INTO fin_journal_h 
              (company, period, id_journal, num_journal, date_journal, type_journal, reff_doc, fg_intercompany, id_intercompany
              ,dateadd, useradd)
            VALUES 
              ('{$d['company']}', '{$d['period']}', '{$d['id_journal']}', '{$d['num_journal']}', '{$d['date_journal']}'
              , '{$d['type_journal']}', '{$d['reff_doc']}', '{$d['fg_intercompany']}', '{$d['id_intercompany']}'
             ,'{$d['dateadd']}', '{$d['useradd']}')
            ;
        ";

        $this->query($sql);

//        return $id_journal; //$this->conn->insert_id;
    }

    public function update($id, $data)
    {

		
			$data['nilai'] = str_replace(".","",$data['nilai']);
		$explodedate = explode("/",$data['tanggalbeli']);
		$data['tanggalbeli'] = $explodedate[2].'-'.$explodedate[1].'-'.$explodedate[0];
		$explodedate = explode("/",$data['tanggalpakai']);
		$data['tanggalpakai'] = $explodedate[2].'-'.$explodedate[1].'-'.$explodedate[0];		
        $sql2 = "
			UPDATE masteractiva SET nm_aktiva='".$data['namaaktiva']."'
			,d_tanggalbeli                   ='".$data['tanggalbeli']."'
			,d_tanggalpakai                  ='".$data['tanggalpakai']."'
			,d_umurbulanactiva               ='".$data['umurbulanactiva']."'
			,d_umurtahunactiva               ='".$data['umurtahunactiva']."'
			,v_akunactiva                    ='".$data['akunactiva']."'
			,v_akunakumulasipenyusutan       ='".$data['akunakumulasipenyusutan']."'
			,v_akunbiayapenyusutan           ='".$data['akunbiayapenyusutan']."'
			
			,n_nilai                         ='".$data['nilai']."'
			,n_qty                           ='".$data['qty']."'
			,n_iddept                        ='".$data['department']."'
			,n_idsupdept                     ='".$data['subdepartment']."'   
			 WHERE id_journal = '$id';
        ";
		
		
		$this->query($sql2);

        $sql = "
            UPDATE fin_journal_h SET 
              period = '{$data['period']}',
              num_journal = '{$data['num_journal']}',
              date_journal = '{$data['date_journal']}',
              type_journal = '{$data['type_journal']}',
              reff_doc = '{$data['reff_doc']}',
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
        $journal = (array) $this->get_journal($id);

        switch($journal['type_journal']){
            case Config::$journal_usage['purchase']:
                //Check whether this is gr journal or not by checking on reff to bpb. code 02 means not existent
                $bpb_request = $this->check_bpb($journal['reff_doc'], FALSE);

                if($bpb_request['errcode'] != '02'){
                    $this->insert_bpb_ir($journal['reff_doc'],$id);
                    /*
                    $data = array(
                        'company' => $journal['company'],
                        'period' => $journal['period'],
                        'num_journal' => '',//$journal['num_journal'],
                        'date_journal' => date('Y-m-d', time()),
                        'type_journal' => $journal['type_journal'],
//                    'reff_doc' => $journal['reff_doc'],
                        'reff_doc' => $id,
                        'fg_intercompany' => $journal['fg_intercompany'],
                        'id_intercompany' => $journal['id_intercompany'],
                    );

                    $journal_code = Config::$journal_code[$data['type_journal']];
                    $data['id_journal'] = generate_coa_id("NAG", $journal_code);

                    $this->insert_bpb_ir($journal['reff_doc'],$data['id_journal']);

                    $data['dateadd'] = date('Y-m-d H:i:s', time());
                    $data['useradd'] = $_SESSION['username'];
                    $this->save($data);*/
                }
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

    public function insert_bpb_ir($bpbno, $id_journal)
    {
        $bpb_request = $this->check_bpb($bpbno, FALSE);

        if($bpb_request['status']){
            $dataBpb = array();
            $bpb = $bpb_request['data'];
            $row_id = $this->get_next_item_id($id_journal);
            $dateadd = date('Y-m-d H:i:s', time());
            $_SESSION['username'] = $_SESSION['username'];
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
        'fg_intercompany' => $_POST['fg_intercompany'],
        'id_intercompany' => $_POST['id_intercompany'],
		'kodeaktiva' => $_POST['kodeaktiva'],
        'namaaktiva' => $_POST['namaaktiva'],
	'tipeaktiva' => $_POST['tipeaktiva'],
	'tanggalbeli' => $_POST['tanggalbeli'],
	'tanggalpakai' => $_POST['tanggalpakai'],
	'umuratahunctiva' => $_POST['umurtahunactiva'],
	'umurbulanactiva' => $_POST['umurbulanactiva'],
	'akunactiva' => $_POST['akunactiva'],
	'akunakumulasipenyusutan' => $_POST['akunakumulasipenyusutan'],
	'akunbiayapenyusutan' => $_POST['akunbiayapenyusutan'],		
	
	'qty' => $_POST['qty'],
	'nilai' => $_POST['nilai'],
	'department' => $_POST['department'],
	'subdepartment' => $_POST['subdepartment'],			
	
    );

    if($_POST['mode'] == 'save'){
        $data['dateadd'] = date('Y-m-d H:i:s', time());
        $data['useradd'] = $_SESSION['username'];
        $journal_code = Config::$journal_code[$data['type_journal']];

        switch($data['type_journal']){
            case Config::$journal_usage['purchase']:
                $bpb_check = $M->check_bpb($data['reff_doc']);
                if($bpb_check['errcode'] == '01') {
                    echo "<script>alert('Error: Nomor Refference (BPB) sudah digunakan');</script>";
                    $status = false; // Num journal already exists
                    $row = json_decode(json_encode($data));
                }else{
                    $data['id_journal'] = generate_coa_id("NAG", $journal_code,$data['date_journal']);
                    $M->insert_bpb_gr($data['reff_doc'], $data['id_journal']);
                    $M->save($data);
                    echo "<script>window.location.href='?mod=jefh&id={$data['id_journal']}';</script>";exit();
                }
                break;
            default:
                $data['id_journal'] = generate_coa_id("NAG", $journal_code, $data['date_journal']);
                $M->save($data);
                echo "<script>window.location.href='?mod=jact&id={$data['id_journal']}';</script>";exit();
        }
    }elseif($_POST['mode'] == 'update'){
        $journal = $M->get_journal($id);
        if($journal->num_journal != $data['num_journal'] and $M->get_journal_by_num($data['num_journal'])){
            echo "<script>alert('Error: Nomor Dokumen sudah terdaftar');</script>";
            $status = false; // Num journal already exists
            $row = json_decode(json_encode($data));
        }else{
            $data['dateedit'] = date('Y-m-d H:i:s', time());
            $data['useredit'] = $_SESSION['username'];
            $status = $M->update($id, $data);

            if(isset($_POST['posting'])){
                //TODO: Posting validation
                $data = array(
                    'date_post' => date('Y-m-d H:i:s', time()),
                    'user_post' => $_SESSION['username']
                );
                $M->post_journal($id, $data);

            }
            echo "<script>window.location.href='?mod=je';</script>";exit();
//            echo "<script>window.location.href='?mod=jefh&id=$id';</script>";exit();
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

$acct_period = $M->get_accounting_period();


// CONTROLLER END


?>
<form method='post' name='form' enctype='multipart/form-data' action="" onsubmit='return validasi()'>
    <div class='box'>
        <div class='box-body'>
            <div class='row'>
                <div class='col-md-3'>
                    <div class='form-group'>
                        <label>Periode Akuntansi</label>
                        <input type='text' id='periodpicker_' readonly class='form-control' name='period' autocomplete="off" 
                               placeholder='MM/YYYY' value='<?=isset($row)?$row->period:''?>'>

                    </div>
                    <div class='form-group'>
                        <label>Tipe Jurnal</label>

                  <select id='type_journal' name="type_journal" class='form-control' onchange="filter_table()" readonly>
                      <option value="<?=Config::$journal_usage['ACTIVA']?>" ><?=Config::$journal_type[Config::$journal_usage['ACTIVA']]?></option>
                  </select>


                    </div>
					
	<!--16 AGUSTUS 2018 NEW -->

	      
					<div class='form-group'>
						<label>Tipe Aktiva</label>
                        <select id='tipeaktiva' class='form-control' <?=isset($row)? 'disabled':''?> onchange="getTipeAktivaDetail(this)" name='tipeaktiva'>
                          
						 </select> 							      
					</div>	
					

					<div class='form-group'>
						<label>Kode Type Aktiva</label>
						                        <input type='text' autocomplete="off" id='kodeaktiva' class='form-control' name='kodeaktiva'
                               placeholder='Masukkan Tipe Activa'  readonly  value='<?=isset($row)?$row->kd_aktiva:''?>'>

					</div>								
<!--
					<div class='form-group'>
						<label>Nama Aktiva</label>
                        <input type='text' autocomplete="off" id='namaaktiva' id='date' class='form-control' name='namaaktiva'
                               placeholder='Masukkan Nama Activa' value='<? // =isset($row)?$row->nm_aktiva:''?>'>
					</div>
-->
					<div class='form-group'>
						<label>Tanggal Beli</label>
                        <input type='text' id='tanggalbeli' id='date' class='form-control' name='tanggalbeli' autocomplete="off"
                               placeholder='Masukkan Tanggal Beli' value='<?=isset($row)?date('d/m/Y', strtotime($row->d_tanggalbeli)):''?>'>
					</div>	
				
					<div class='form-group'>
						<label>Tanggal Pakai</label>
                        <input type='text' id='tanggalpakai' id='date' class='form-control' name='tanggalpakai' autocomplete="off"
                               placeholder='Masukkan Tanggal pakai'  value='<?=isset($row)?date('d/m/Y', strtotime($row->d_tanggalpakai)):''?>'>
					</div>	

					<div class='form-group'>
						<label>Umur Tahun Activa</label>
                        <input type='text' id='umurtahunactiva' id='date' class='form-control' name='umurtahunactiva'
                               placeholder='Masukkan Umur Tahun Activa' autocomplete="off" readonly value='<?=isset($row)?$row->d_umurtahunactiva:''?>'>
					</div>	
							
			

 


   <!-- 16 AGUSTUS 2018 NEW -->	
					
					
                </div>
                 <div class='col-md-3'>


					<div class='form-group'>
						<label>Umur Bulan Activa</label>
                        <input type='text' id='umuractiabulan' id='date' class='form-control' name='umurbulanactiva'
                               placeholder='Masukkan Bulan Tanggal Activa' autocomplete="off" readonly value='<?=isset($row)?$row->d_umurbulanactiva:''?>'>
					</div>					 
					<div class='form-group'>
						<label>Akun Activa</label>
                        <select id='akunactiva'  class='form-control select2' name='akunactiva'>
                          
						 </select> 
					</div>								

					<div class='form-group'>
						<label>Akun Akumulasi Penyusutan</label>
                        <select id='akunakumulasipenyusutan' class='form-control select2' name='akunakumulasipenyusutan'>
                          
						 </select> 
					</div>								
		
       
					<div class='form-group'>
						<label>Akun Biaya Penyusutan </label>
                        <select id='akunbiayapenyusutan' class='form-control select2' name='akunbiayapenyusutan'>
                          
						 </select> 
					</div>					 
                     <div class='form-group'>
                         <label>Nomor Jurnal</label>
                         <input type='text' id='id_journal' class='form-control' name='id_journal' readonly
                                placeholder='(Auto)' value='<?=(isset($row) and isset($row->id_journal))?$row->id_journal:''?>'>
                     </div>
                     <div class='form-group'>
                         <label>Tanggal Jurnal</label>
                         <input type='text' id='datepicker1' onchange="getPeriodAccounting(this)" class='form-control' name='date_journal' autocomplete="off"
                                placeholder='Masukkan Tanggal Jurnal' value='<?=isset($row)?date('d M Y', strtotime($row->date_journal)):''?>'>
                     </div>
                     <div class='form-group'>
                         <label>Referensi Dokumen</label>
                         <input type='text' id='reff_doc' class='form-control' name='reff_doc'
                                placeholder='Masukkan Referensi Dokumen' value='<?=isset($row)?$row->reff_doc:''?>'>
                       
                     </div>
					 




					
                </div>
                <div class='col-md-3'>
						<div class='form-group'>
						<label>Department </label>
                        <select id='department' class='form-control' onchange="getMasterSubCostCenter(this)" name='department'>
                          
						 </select> 
					</div>						 

					<div class='form-group'>
						<label>Sub Department</label>
                        <select id='subdepartment' class='form-control' name='subdepartment'>
								
						 </select> 
					</div>	

			
                     <div class='form-group'>
                         <label>Qty</label>
                         <input type='text' id='qty' class='form-control' name='qty' autocomplete="off"
                                placeholder='Masukkan Qty' onkeyup="totalnilais()" value='<?=isset($row)?$row->n_qty:''?>'>
                     </div>
                     <div class='form-group'>
                         <label>Nilai</label>
                         <input type='text' id='nilai' class='form-control' name='nilai'
                                placeholder='Masukkan Nilai' onkeyup="totalnilais()" value='<?=isset($row)?$row->n_nilai:''?>'>
                       
                     </div>			
                     <div class='form-group'>
                         <label>Total Nilai</label>
                         <input type='text' id='totalnilai' onkeyup="changenilai(this)" onclick="changenilai(this)" class='form-control' 
                              readonly  placeholder='Masukkan Nilai' >
                       
                     </div>							 
				
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
                            <label>Status Posting: </label><br><?=(isset($row) and $row->fg_post)? 'POSTED':'PARKED'?><br>
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
    <?php include("journal_entry_form_item.inc.php"); ?>
<?php endif;?>
<div class="modal fade" id="modalBpb"  role="dialog" >
    <div class="modal-dialog">
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
                    <div class="row">
                        <div class="col-md-12">
                            <em>Gunakan % untuk pencarian wildcard</em>
                        </div>
                    </div>
                </div>
                <div class="bpbresult">
                    <table class="table">
                        <thead>
                            <th>BPB No</th>
                            <th>BPB No Internal</th>
                            <th>Num of Items</th>
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
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="js/MasterAktivaTetap.js"></script>
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
            url: "ajax_fin.php?mdajax=lookup_bpb",
            data: "bpbno=" +$("#bpbNo").val()+"&bpbno_internal="+$("#bpbNoInternal").val(),
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
                    rows += '<td>' + bpb_list[i].bpbno + '</td>';
                    rows += '<td>' + bpb_list[i].bpbno_int + '</td>';
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
    function select_bpb(bpb){
        $('#reff_doc').val(bpb);
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
        <?php if(isset($t_debit)):?>
        else if ((<?=@$t_debit?> + <?=@$t_credit?>) == 0) {
            alert('Error: Nilai Debit dan Kredit tidak boleh kosong');
            valid = false;
        } else if (<?=@$t_debit?> != <?=@$t_credit?>) {
            alert('Error: Debit dan Kredit harus balance');
            valid = false;
        }
        <?php endif;?>
        else if (<?=(isset($row) and isset($row->fg_post) and $row->fg_post)?'true':'false'?>) {
            alert('Error: Journal sudah di posting');
            valid = false;
        } else valid = true;

        return valid;
    }
</script>